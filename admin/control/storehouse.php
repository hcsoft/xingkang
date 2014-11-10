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
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        $treesql = 'select  b.id , b.name,b.districtnumber,b.parentid pId from map_org_wechat a, Organization b where a.orgid = b.id ';
        $treestmt = $conn->query($treesql);
        $this->treedata_list = array();
        while ($row = $treestmt->fetch(PDO::FETCH_OBJ)) {
            array_push($this->treedata_list, $row);
        }
        Tpl::output('treelist', $this->treedata_list);
        $this->getTreeData();
        $stmt = $conn->query(' select * from Center_codes where type=\'iBuy_Type\' order by code ');
        $this->types = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($this->types, $row);
        }
//        $this->types = array(0 => '期初入库', 1 => '采购入库', 2 => '购进退回', 3 => '盘盈', 5 => '领用', 12 => '盘亏', 14 => '领用退回', 50 => '采购计划',);
        Tpl::output('types', $this->types);
        $this->goodtype = array(0 => '药品', 1 => '卫生用品', 2 => '诊疗项目', 3 => '特殊材料');
        Tpl::output('goodtype', $this->goodtype);

        $stmt = $conn->query(' select distinct orgid from map_org_wechat order by orgid ');
        $this->orgidarray = array();
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            array_push($this->orgidarray, $row[0]);
        }

    }

    /**
     * 新增会员
     */
    public function detailOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        //处理数据
        $page = new Page();
        $page->setEachNum(10);
        $page->setNowPage($_REQUEST["curpage"]);
        $startnum = $page->getEachNum() * ($page->getNowPage() - 1);
        $endnum = $page->getEachNum() * ($page->getNowPage());
        $sql = 'from Center_Buy a  , Center_Drug b , Organization c, Organization d,shopnc_goods_common good ,
            Center_codes storetype
            where a.iDrug_ID = b.iDrug_ID ' .
            ' and a.SaleOrgID = -( c.id +1000) and a.orgid = d.id and a.iDrug_ID = good.goods_commonid
             and a.iBuy_Type = storetype.code and storetype.type=\'iBuy_Type\' ';
        if (!isset($_GET['search_type'])) {
            $_GET['search_type'] = '1';
        }
        if (gettype($_GET['search_type']) == 'string' && intval($_GET['search_type']) >= 0) {
            $sql = $sql . ' and  a.iBuy_Type = \'' . $_GET['search_type'] . '\'';
        }

        if ($_GET['query_start_time']) {
            $sql = $sql . ' and a.dBuy_Date >=\'' . $_GET['query_start_time'] . '\'';
        }

        if ($_GET['query_end_time']) {
            $sql = $sql . ' and a.dBuy_Date < dateadd(day,1,\'' . $_GET['query_end_time'] . '\')';
        }
        if ($_GET['search_goods_name'] != '') {
            $sql = $sql . ' and good.goods_name like \'%' .  trim($_GET['search_goods_name']) . '%\'';
        }
        if (intval($_GET['search_commonid']) > 0) {
            $sql = $sql . ' and good.goods_commonid = ' . intval($_GET['search_commonid']) ;
        }

        if ($_GET['orgids']) {
            if ($_GET['search_type'] == 0 || $_GET['search_type'] == 1) {
                $orgarray = array();
                foreach ($_GET['orgids'] as $v) {
                    $orgarray[] = -($v + 1000);
                }
                $sql = $sql . ' and a.SaleOrgID in (' . implode(',', $orgarray) . ' )';
            } else {
                $sql = $sql . ' and a.OrgID in ( ' . implode(',', $_GET['orgids']) . ')';
            }

        }
        //处理树的参数
        $checkednode = $_GET['checkednode'];
        if ($checkednode && isset($checkednode) && count($checkednode) > 0) {
            $sql = $sql . " and a.SaleOrgID  in ($checkednode) ";
        }

        $countsql = " select count(*)  $sql ";
        $stmt = $conn->query($countsql);
        $total = $stmt->fetch(PDO::FETCH_NUM);
        $page->setTotalNum($total[0]);
        $tsql = "SELECT * FROM  ( SELECT  * FROM (SELECT TOP $endnum row_number() over( order by  a.dBuy_Date) rownum,
                        a.iBuy_TicketID,
                        a.sBuy_A6,
                        a.iBuy_ID,
                        a.dBuy_Date,
                        b.iDrug_RecType,
                        storetype.name as 'iBuy_Type',
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
        $exportsql = "SELECT
                        a.iBuy_TicketID,
                        a.iBuy_ID,
                        a.dBuy_Date,
                        b.iDrug_RecType,
                        storetype.name as 'iBuy_Type',
                        d.name OrgId,
                        a.iDrug_ID,
                        good.sdrug_chemname,
                        good.spec_name,
                        good.sdrug_unit,
                        a.fBuy_FactNum,
                        a.fBuy_TaxMoney,
                        a.fBuy_RetailMoney,
                        a.fBuy_RetailMoney - a.fBuy_TaxMoney as diffmoney
                        $sql order by  a.dBuy_Date ";
        $exporttotalsql = "SELECT
                        '总计：' as iBuy_TicketID,
                        null as iBuy_ID,
                        null as dBuy_Date,
                        null as iDrug_RecType,
                        null as iBuy_Type,
                        null as OrgId,
                        null as iDrug_ID,
                        null as sdrug_chemname,
                        null as spec_name,
                        null as sdrug_unit,
                        null as fBuy_FactNum,
                        sum(fBuy_TaxMoney) as fBuy_TaxMoney,
                        sum(fBuy_RetailMoney) as fBuy_RetailMoney,
                        sum(fBuy_RetailMoney)-sum(fBuy_TaxMoney) as diffmoney
                        $sql   ";
        if(isset($_GET['export']) && $_GET['export']=='true'){
            $this->exportxlsx(array(0=>$exportsql,1=>$exporttotalsql),array('总票据','明细号','发生日期','商品类型','单据类型','机构','商品编码','商品名称','规格','单位','数量','进价金额','零价金额','进销差'),'仓库单据明细');
        }
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
                        sum(fBuy_TaxMoney) as fBuy_TaxMoney,
                        sum(fBuy_RetailMoney) as fBuy_RetailMoney,
                        sum(fBuy_RetailMoney)-sum(fBuy_TaxMoney) as diffmoney
                        $sql  ";
//        echo $sql;
        $totalstmt = $conn->query($totalsql);
        while ($row = $totalstmt->fetch(PDO::FETCH_OBJ)) {
            array_push($data_list, $row);
        }
        Tpl::output('data_list', $data_list);
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
            $result = $_REQUEST['spotresult'] == null ? "" : $_REQUEST['spotresult'];
            $reason = $_REQUEST['reason'] == null ? "" : $_REQUEST['reason'];
            $sql = " insert into spotcheck_spot (spotid,spotdate,result,reason,inputdate) values('$spotid','$spotdate','$result','$reason',getdate())";
            $conn->exec($sql);
            echo json_encode(array('success' => true, 'msg' => '保存成功!'));
        } catch (Exception $e) {
            echo json_encode(array('success' => false, 'msg' => '异常!' . $e->getMessage()));
        }
        exit;
    }

    private function getTreeData()
    {
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
        if (isset($checkednode)) {
            $checkednodearray = explode(',', $checkednode);
        }
        //处理父节点
        $root = array(id => -1, name => "全部", open => true, halfCheck => false);
        if ($checkednode && isset($checkednode) && count($checkednode) > 0) {
            $root['checked'] = true;
        }
        array_push($treedata_list, (object)$root);
        for ($i = 0; $i < count($treedata_list); $i++) {
            $item = $treedata_list[$i];
            $idmap[$item->id] = $item->id;
        }
        for ($i = 0; $i < count($treedata_list); $i++) {
            $item = $treedata_list[$i];
            if ($item->id >= 0) {
                $item->id = -(1000 + $item->id);
            }
            if (!isset($idmap[$item->pid])) {
                $item->pId = -1;
            }
        }

        foreach ($treedata_list as &$v) {
            if (in_array($v->id, $checkednodearray)) {
                $v->checked = true;
            }
        }

        Tpl::output('treedata', $treedata_list);
    }

    public function sumOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        if (!isset($_GET['search_type'])) {
            $_GET['search_type'] = '0';
        }
        $sqlarray = array('iBuy_Type' => 'storetype.name as "iBuy_Type"',
            'customname' => ' custom.sCustomer_Name as "customname" ',
            'sDrug_Spec' => ' goods.sDrug_Spec as "sDrug_Spec" ',
            'sDrug_Unit' => ' goods.sDrug_Unit as "sDrug_Unit" ',
            'sDrug_Brand' => ' goods.sDrug_Brand as "sDrug_Brand" ',
            'drugcount' => ' sum(fBuy_FactNum) as "drugcount" ',
            'year' => ' year(a.dBuy_Date) as "year" ',
            'month' => ' left(convert(varchar,dBuy_Date,112),6) as  "month" ',
            'day' => ' convert(varchar,dBuy_Date,112) as "day" ',
            'sDrug_TradeName' => ' goods.sDrug_TradeName as "sDrug_TradeName"  ',
            'OrgID' => ' c.name as "OrgID" '
        );
        $config = array(0 => array('text' => '采购金额汇总',
            'sqlwher' => ' and iBuy_Type in (1,2) ',
            'sumcol' => array('iBuy_Type' => array(name => 'iBuy_Type', 'text' => '单据类型', map => $this->types),
                'customname' => array(name => 'customname', 'text' => '供货企业'),
                'good' => array('text' => '商品',
                    'cols' => array(0 => array(name => 'sDrug_TradeName', 'text' => '商品名称')
                    , 1 => array(name => 'sDrug_Spec', 'text' => '规格')
                    , 2 => array(name => 'sDrug_Unit', 'text' => '单位')
                    , 3 => array(name => 'sDrug_Brand', 'text' => '产地厂牌')
                    , 4 => array(name => 'drugcount', 'text' => '数量'))),
                'year' => array('text' => '年', name=>'year',uncheck=>'month,day' ),
                'month' => array('text' => '月', name=>'month',uncheck=>'year,day'),
                'day' => array('text' => '日', name=>'day',uncheck=>'year,month'),
            ))
        , 1 => array('text' => '领用金额汇总',
                'sqlwher' => ' and  iBuy_Type in (5,14) ',
                'sumcol' => array('type' => array('name' => 'OrgID', 'text' => '领用部门'),
                    'good' => array('text' => '商品',
                        'cols' => array(0 => array(name => 'sDrug_TradeName', 'text' => '商品名称')
                        , 1 => array(name => 'sDrug_Spec', 'text' => '规格')
                        , 2 => array(name => 'sDrug_Unit', 'text' => '单位')
                        , 3 => array(name => 'sDrug_Brand', 'text' => '产地厂牌')
                        , 4 => array(name => 'drugcount', 'text' => '数量'))),
                    'year' => array('text' => '年', name=>'year',uncheck=>'month,day' ),
                    'month' => array('text' => '月', name=>'month',uncheck=>'year,day'),
                    'day' => array('text' => '日', name=>'day',uncheck=>'year,month'),
                ))
        , 2 => array('text' => '盈亏金额汇总',
                'sqlwher' => ' and  iBuy_Type in (3,4,12) ',
                'sumcol' => array('iBuy_Type' => array(name=>'iBuy_Type', 'text' => '单据类型'),
                    'good' => array('text' => '商品',
                        'cols' => array(0 => array(name => 'sDrug_TradeName', 'text' => '商品名称')
                        , 1 => array(name => 'sDrug_Spec', 'text' => '规格')
                        , 2 => array(name => 'sDrug_Unit', 'text' => '单位')
                        , 3 => array(name => 'sDrug_Brand', 'text' => '产地厂牌')
                        , 4 => array(name => 'drugcount', 'text' => '数量'))),
                    'year' => array('text' => '年', name=>'year',uncheck=>'month,day' ),
                    'month' => array('text' => '月', name=>'month',uncheck=>'year,day'),
                    'day' => array('text' => '日', name=>'day',uncheck=>'year,month'),
                ))
        , 3 => array('text' => '采购计划数量汇总',
                'sqlwher' => ' and iBuy_Type in (50) ',
                'sumcol' => array('OrgID' => array('name' => 'OrgID', 'text' => '采购计划机构'),
                    'good' => array('text' => '商品',
                        'cols' => array(0 => array(name => 'sDrug_TradeName', 'text' => '商品名称')
                        , 1 => array(name => 'sDrug_Spec', 'text' => '规格')
                        , 2 => array(name => 'sDrug_Unit', 'text' => '单位')
                        , 3 => array(name => 'sDrug_Brand', 'text' => '产地厂牌')
                        , 4 => array(name => 'drugcount', 'text' => '数量'))),
                    'year' => array('text' => '年', name=>'year',uncheck=>'month,day' ),
                    'month' => array('text' => '月', name=>'month',uncheck=>'year,day'),
                    'day' => array('text' => '日', name=>'day',uncheck=>'year,month'),
                )));
        Tpl::output('config', $config);

        //处理汇总字段
        $sumtype = $_GET['sumtype'];
        if ($sumtype == null) {
            $sumtype = array(0 => "good");
            $_GET['sumtype'] = $sumtype;
        }
        $checked = $_GET['checked'];
        $page = new Page();
        $page->setEachNum(10);
        $page->setNowPage($_REQUEST["curpage"]);
        $sql = 'from Center_Buy a  , Center_Drug b ,
                    Organization c, Organization d ,
                      shopnc_goods_common goods,Center_Customer custom ,
                      Center_codes storetype  where a.iDrug_ID = b.iDrug_ID ' .
            ' and a.SaleOrgID = -( c.id +1000) and a.orgid = d.id  and a.iDrug_ID = goods.goods_commonid
                and a.iCustomer_ID = custom.iCustomer_ID and a.iBuy_Type = storetype.code and storetype.type=\'iBuy_Type\'';

        if ($_GET['query_start_time']) {
            $sql = $sql . ' and a.dBuy_Date >=\'' . $_GET['query_start_time'] . '\'';
        }

        if ($_GET['query_end_time']) {
            $sql = $sql . ' and a.dBuy_Date < dateadd(day,1,\'' . $_GET['query_end_time'] . '\')';
        }
        if ($_GET['search_goods_name'] != '') {
            $sql = $sql . ' and goods.goods_name like \'%' .  trim($_GET['search_goods_name']) . '%\'';
        }
        if (intval($_GET['search_commonid']) > 0) {
            $sql = $sql . ' and goods.goods_commonid = ' . intval($_GET['search_commonid']) ;
        }

        //处理树的参数
        if ($_GET['orgids']) {
            if ($_GET['search_type'] == 0 || $_GET['search_type'] == 1) {
                $orgarray = array();
                foreach ($_GET['orgids'] as $v) {
                    $orgarray[] = -($v + 1000);
                }
                $sql = $sql . ' and a.SaleOrgID in (' . implode(',', $orgarray) . ' )';
            } else {
                $sql = $sql . ' and a.OrgID in ( ' . implode(',', $_GET['orgids']) . ')';
            }
        }

        $search_type = $_GET['search_type'];
//        echo $search_type;
        $colconfig = $config[intval($search_type)];
//        var_dump($config[intval($search_type)]);
        $displaycol = array();
        $displaytext = array();
        $sumcol = array();
        $totalcol = array();
        $groupbycol = array();
        foreach ($sumtype as $i => $v) {
//            var_dump($colconfig['sumcol'][$v]);
            if(isset($colconfig['sqlwher'])){
                $sql = $sql . $colconfig['sqlwher'];
            }
            if (isset($colconfig['sumcol'][$v])) {
                if (isset($colconfig['sumcol'][$v]['cols'])) {
                    foreach ($colconfig['sumcol'][$v]['cols'] as $item) {
//                        echo $item['name'] . '<br>';
                        array_push($sumcol, $sqlarray[$item['name']]);
                        array_push($displaycol, $item['name']);
                        array_push($displaytext, $item['text']);
                        $itemsplit = explode(' as ', $sqlarray[$item['name']]);
                        array_push($totalcol, ' null as ' . $itemsplit[1]);
                        $str = strtolower(str_replace(' ', '', trim($itemsplit[0])));
                        if (substr($str, 0, 4) != 'sum(' && substr($str, 0, 6) != 'count(')
                            array_push($groupbycol, $itemsplit[0]);
                    }
                } else {
                    $item = $colconfig['sumcol'][$v];
                    array_push($sumcol, $sqlarray[$item['name']]);
                    array_push($displaycol, $item['name']);
                    array_push($displaytext, $item['text']);
                    $itemsplit = explode(' as ', $sqlarray[$item['name']]);
                    array_push($totalcol, ' null as ' . $itemsplit[1]);
                    $str = strtolower(str_replace(' ', '', trim($itemsplit[0])));
                    if (substr($str, 0, 4) != 'sum(' && substr($str, 0, 6) != 'count(')
                        array_push($groupbycol, $itemsplit[0]);
                }
            }
        }
        array_push($displaytext, '进价金额');
        array_push($displaytext, '零价金额');
        array_push($displaytext, '进销差');
//        var_dump($totalcol);
        $totalcol[0] = '\'总计：\' as ' . explode(' as ', $totalcol[0])[1];
//        var_dump($totalcol);
        $totalcolstr = join(',', $totalcol);
        $sumcolstr = join(',', $sumcol);
        $groupbycolstr = join(',', $groupbycol);
//        echo $sumcolstr;
        $tsql = " select $sumcolstr , sum(fBuy_TaxMoney) taxmoney, sum(fBuy_RetailMoney) retailmoney , sum(fBuy_RetailMoney) - sum(fBuy_TaxMoney) diffmoney
                        $sql group by $groupbycolstr order by $groupbycolstr ";
//        echo $tsql;
//处理合计
        $totalsql = " select $totalcolstr , sum(fBuy_TaxMoney) taxmoney, sum(fBuy_RetailMoney) retailmoney , sum(fBuy_RetailMoney) - sum(fBuy_TaxMoney) diffmoney
                        $sql ";
        if(isset($_GET['export']) && $_GET['export']=='true'){
            $this->exportxlsx(array(0=>$tsql,1=>$totalsql),$displaytext,'仓库单据汇总');
        }
        $stmt = $conn->query($tsql);
        $data_list = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($data_list, $row);
        }

//        echo $totalsql;
        $totalstmt = $conn->query($totalsql);
        while ($row = $totalstmt->fetch(PDO::FETCH_OBJ)) {
            array_push($data_list, $row);
        }
        Tpl::output('data_list', $data_list);
        //--0:期初入库 1:采购入库 2:购进退回 3:盘盈 5:领用 12:盘亏 14:领用退回 50:采购计划
        Tpl::output('page', $page->show());
        //处理需要显示的列
        $col = array();
        foreach ($sumtype as $i => $v) {
            if (isset($sumtypestr[$v])) {
                foreach ($sumtypestr[$v] as $key => $item) {
                    $col[$key] = $item;
                }
            }
        }
//        var_dump($col);
        Tpl::output('displaycol', $displaycol);
        Tpl::output('displaytext', $displaytext);
        Tpl::showpage('storehouse.sum');
    }


}
