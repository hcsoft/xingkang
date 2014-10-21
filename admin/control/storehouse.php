<?php
/**
 * 统计管理
 *
 * @copyright  Copyright (c) 2014-2020 SZGR Inc. (http://www.szgr.com.cn)
 * @license    http://www.szgr.com.cn
 * @link       http://www.szgr.com.cn
 * @since      File available since Release v1.1
 */
defined('InShopNC') or exit('Access Invalid!');
require(BASE_DATA_PATH . '/../core/framework/db/mssql.php');
class storehouseControl extends SystemControl
{

    public function __construct()
    {
        parent::__construct();
        Language::read('stat');
        import('function.statistics');
        import('function.datehelper');
        $model = Model('stat');
        //存储参数
        $this->search_arr = $_REQUEST;
        //处理搜索时间
        if (in_array($_REQUEST['op'], array('list'))) {
            $this->search_arr = $model->dealwithSearchTime($this->search_arr);
            //获得系统年份
            $year_arr = getSystemYearArr();
            //获得系统月份
            $month_arr = getSystemMonthArr();
            //获得本月的周时间段
            $week_arr = getMonthWeekArr($this->search_arr['week']['current_year'], $this->search_arr['week']['current_month']);
            Tpl::output('year_arr', $year_arr);
            Tpl::output('month_arr', $month_arr);
            Tpl::output('week_arr', $week_arr);
        }
        Tpl::output('search_arr', $this->search_arr);
    }

    /**
     * 新增会员
     */
    public function detailOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        $this->getTreeData();

        //处理数据
        $page = new Page();
        $page->setEachNum(10);
        $page->setNowPage($_REQUEST["curpage"]);
        $startnum = $page->getEachNum() * ($page->getNowPage() - 1);
        $endnum = $page->getEachNum() * ($page->getNowPage());
        $sql = 'from Center_Buy a  , Center_Drug b , Organization c, Organization d,shopnc_goods_common good  where a.iDrug_ID = b.iDrug_ID '.
                ' and a.SaleOrgID = -( c.id +1000) and a.orgid = d.id and a.iDrug_ID = good.goods_commonid  ';
        if(!isset($_GET['search_type'])){
            $_GET['search_type'] = '1';
        }
        if (gettype($_GET['search_type'])=='string' && intval($_GET['search_type'])>=0 ) {
            $sql = $sql . ' and  a.iBuy_Type = \''.$_GET['search_type'].'\'';
        }

        if ($_GET['query_start_time']) {
            $sql = $sql . ' and a.dBuy_Date >=\'' . $_GET['query_start_time'] . '\'';
        }

        if ($_GET['query_end_time']) {
            $sql = $sql . ' and a.dBuy_Date < dateadd(day,1,\'' . $_GET['query_end_time'] . '\')';
        }
        //处理树的参数
        $checkednode = $_GET['checkednode'];
        if($checkednode && isset($checkednode) && count($checkednode)>0){
            $sql = $sql . " and a.SaleOrgID  in ($checkednode) ";
        }

        $countsql = " select count(*)  $sql ";
//        echo $countsql;
        $stmt = $conn->query($countsql);
//        echo $countsql;
        $total = $stmt->fetch(PDO::FETCH_NUM);
        $page->setTotalNum($total[0]);
        $tsql = "SELECT * FROM  ( SELECT  * FROM (SELECT TOP $endnum row_number() over( order by  a.dBuy_Date) rownum,
                        a.iBuy_TicketID,
                        a.sBuy_A6,
                        a.iBuy_ID,
                        a.dBuy_Date,
                        b.iDrug_RecType,
                        a.iBuy_Type,
                        d.name OrgId,
                        c.name SaleOrgID,
                        good.sdrug_chemname,
                        good.spec_name,
                        good.sdrug_unit,
                        a.iDrug_ID,
                        a.fBuy_FactNum,
                        a.sBuy_DrugUnit,
                        a.fBuy_TaxMoney,
                        a.fBuy_RetailMoney,
                        a.fBuy_RetailMoney - a.fBuy_TaxMoney as diffmoney

                        $sql order by  a.dBuy_Date)zzzz where rownum>$startnum )zzzzz order by rownum";
//        echo $sql;
        $stmt = $conn->query($tsql);
        $data_list = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($data_list, $row);
        }

        $totalsql = " select '总计：' as iBuy_TicketID,
                        null as sBuy_A6,
                        null as iBuy_ID,
                        null as dBuy_Date,
                        null as iDrug_RecType,
                        null as iBuy_Type,
                        null as OrgId,
                        null as SaleOrgID,
                        null as iDrug_ID,
                        null as fBuy_FactNum,
                        null as sBuy_DrugUnit,
                        sum(fBuy_TaxPrice) as fBuy_TaxMoney,
                        sum(fBuy_RetailMoney) as fBuy_RetailMoney,
                        sum(fBuy_RetailMoney)-sum(fBuy_TaxPrice) as diffmoney
                        $sql  ";
//        echo $sql;
        $totalstmt = $conn->query($totalsql);
        while ($row = $totalstmt->fetch(PDO::FETCH_OBJ)) {
            array_push($data_list, $row);
        }
        Tpl::output('data_list', $data_list);
        $types = array(0=>'期初入库',1=>'采购入库',2=>'购进退回',3=>'盘盈',5=>'领用',12=>'盘亏',14=>'领用退回',50=>'采购计划',);
        $goodtype = array(0=>'药品',1=>'卫生用品',2=>'诊疗项目',3=>'特殊材料');
        Tpl::output('types',$types);
        Tpl::output('goodtype',$goodtype);
        //--0:期初入库 1:采购入库 2:购进退回 3:盘盈 5:领用 12:盘亏 14:领用退回 50:采购计划
        Tpl::output('page', $page->show());
        Tpl::showpage('storehouse.detail');
    }


    public function ajaxOp()
    {
        //spotcheck_spot
        try {
            $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
            $id = $_REQUEST['id'];
            $spotid = $_REQUEST['spotid'];
            $spotdate = $_REQUEST['spotdate'];
            $result = $_REQUEST['spotresult'] == null ?"":$_REQUEST['spotresult'];
            $reason = $_REQUEST['reason'] == null ?"":$_REQUEST['reason'];
            $sql = " insert into spotcheck_spot (spotid,spotdate,result,reason,inputdate) values('$spotid','$spotdate','$result','$reason',getdate())";
            $conn->exec($sql);
            echo json_encode(array('success' => true, 'msg' => '保存成功!'));
        } catch (Exception $e) {
            echo json_encode(array('success' => false, 'msg' => '异常!'.$e->getMessage()));
        }
        exit;
    }

    private function getTreeData(){
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');

        //查询机构树的类型
        $treesql = 'select  b.id , b.name,b.districtnumber,b.parentid pId from map_org_wechat a, Organization b where a.orgid = b.id ';
        $treestmt = $conn->query($treesql);
        $treedata_list = array();

        while ($row = $treestmt->fetch(PDO::FETCH_OBJ)) {
            array_push($treedata_list, $row);
        }
        $idmap = Array();
        //处理树选择节点
        $checkednode = $_GET['checkednode'];
        $checkednodearray = array();
        if(isset($checkednode)){
            $checkednodearray = explode(',',$checkednode);
        }
        //处理父节点
        $root = array(id=> -1 , name=> "全部", open=> true,halfCheck =>false);
        if($checkednode && isset($checkednode) && count($checkednode)>0){
            $root['checked'] = true;
        }
        array_push($treedata_list,(object)$root);
        for($i =0 ; $i < count($treedata_list);$i++){
            $item = $treedata_list[$i];
            $idmap[$item->id] = $item->id;
        }
        for($i =0 ; $i < count($treedata_list);$i++){
            $item = $treedata_list[$i];
            if($item->id>=0){
                $item->id  = -(1000+$item->id);
            }
            if(!isset($idmap[$item->pid])){
                $item->pId = -1;
            }
        }

        foreach($treedata_list as &$v){
            if(in_array($v->id,$checkednodearray)){
                $v->checked = true;
            }
        }

        Tpl::output('treedata', $treedata_list);
    }

    public function sumOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        $this->getTreeData();

        //处理数据
        $sumtypewhere = Array(
            'org'=>'d.name as "OrgId"',
            'store'=>'c.name as "SaleOrgID"',
            'goods'=> array(0=>'goods.sDrug_TradeName as "sDrug_TradeName" ',1=>' goods.sDrug_Spec as "sDrug_TradeName"  ',2=>'goods.sDrug_Brand as "sDrug_Brand"'),
            'person'=>'a.iBuy_CAPerson as "iBuy_CAPerson" ',
            'year'=>'year(a.dBuy_Date) as  "year"',
            'month'=>'month(a.dBuy_Date) as  "month"',
            'day'=>' day(a.dBuy_Date) as  "day" '
        );
        $sumtypestr = Array(
            'org'=>array('OrgId'=>'领用部门'),
            'store'=>array('SaleOrgID'=>'库房'),
            'goods'=>array('sDrug_TradeName'=>'商品名称','sDrug_Spec'=>'规格','sDrug_Brand'=>'产地'),
            'person'=>array('iBuy_CAPerson'=>'经手人'),
            'year'=>array('year'=>'年'),
            'month'=>array('month'=>'月'),
            'day'=>array('day'=>'日')
        );
        $page = new Page();
        $page->setEachNum(10);
        $page->setNowPage($_REQUEST["curpage"]);
        $startnum = $page->getEachNum() * ($page->getNowPage() - 1);
        $endnum = $page->getEachNum() * ($page->getNowPage());
        $sql = 'from Center_Buy a  , Center_Drug b , Organization c, Organization d ,shopnc_goods_common goods where a.iDrug_ID = b.iDrug_ID '.
            ' and a.SaleOrgID = -( c.id +1000) and a.orgid = d.id  and a.iDrug_ID = goods.goods_commonid ';

        if (gettype($_GET['search_type'])=='string' && intval($_GET['search_type'])>=0 ) {
            $sql = $sql . ' and  a.iBuy_Type = \''.$_GET['search_type'].'\'';
        }

        if ($_GET['query_start_time']) {
            $sql = $sql . ' and a.dBuy_Date >=\'' . $_GET['query_start_time'] . '\'';
        }

        if ($_GET['query_end_time']) {
            $sql = $sql . ' and a.dBuy_Date < dateadd(day,1,\'' . $_GET['query_end_time'] . '\')';
        }

        //处理树的参数
        $checkednode = $_GET['checkednode'];
        if($checkednode && isset($checkednode) && count($checkednode)>0){
            $sql = $sql . " and a.SaleOrgID  in ($checkednode) ";
        }
        //处理汇总条件
        $sumtype = $_GET['sumtype'];
        if($sumtype==null){
            $sumtype = array(0=>"org");
            $_GET['sumtype'] = $sumtype;
        }
        $sumcol = array();
        $totalcol = array();
        $groupbycol = array();
        foreach($sumtype as $i=>$v){
            if(isset($sumtypewhere[$v])){
                if(is_array($sumtypewhere[$v])){
                    foreach($sumtypewhere[$v] as $item){
                        array_push($sumcol,$item);
                        array_push($totalcol, ' null as '.explode(' as ',$item)[1]);
                        array_push($groupbycol,explode(' as ',$item)[0]);
                    }
                }else{
                    array_push($sumcol,$sumtypewhere[$v]);
                    array_push($totalcol, ' null as '.explode(' as ',$sumtypewhere[$v])[1]);
                    array_push($groupbycol,explode(' as ',$sumtypewhere[$v])[0]);
                }
            }
        }
        $totalcol[0] = '\'总计：\' as '. explode(' as ',$totalcol[0])[1];
        $totalcolstr = join(',',$totalcol);
        $sumcolstr = join(',',$sumcol);
        $groupbycolstr = join(',',$groupbycol);
        $tsql = " select $sumcolstr , sum(fBuy_TaxMoney) taxmoney, sum(fBuy_RetailMoney) retailmoney , sum(fBuy_RetailMoney) - sum(fBuy_TaxMoney) diffmoney
                        $sql group by $groupbycolstr order by $groupbycolstr ";
//        echo $tsql;
        $stmt = $conn->query($tsql);
        $data_list = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($data_list, $row);
        }
        //处理合计
        $totalsql = " select $totalcolstr , sum(fBuy_TaxMoney) taxmoney, sum(fBuy_RetailMoney) retailmoney , sum(fBuy_RetailMoney) - sum(fBuy_TaxMoney) diffmoney
                        $sql ";
        $totalstmt = $conn->query($totalsql);
        while ($row = $totalstmt->fetch(PDO::FETCH_OBJ)) {
            array_push($data_list, $row);
        }
        Tpl::output('data_list', $data_list);
        //--0:期初入库 1:采购入库 2:购进退回 3:盘盈 5:领用 12:盘亏 14:领用退回 50:采购计划
        Tpl::output('page', $page->show());
        //处理需要显示的列
        $col = array();
        foreach($sumtype as $i=>$v){
            if(isset($sumtypestr[$v])){
                foreach($sumtypestr[$v] as $key=>$item){
                    $col[$key] =$item;
                }
            }
        }
        $types = array(0=>'期初入库',1=>'采购入库',2=>'购进退回',3=>'盘盈',5=>'领用',12=>'盘亏',14=>'领用退回',50=>'采购计划',);
        Tpl::output('types',$types);
        Tpl::output('col',$col);
        Tpl::showpage('storehouse.sum');
    }


}
