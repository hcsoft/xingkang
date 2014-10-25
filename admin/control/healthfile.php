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
class healthfileControl extends SystemControl
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
    public function queryOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        $this->getTreeData();

        //处理数据
        $page = new Page();
        $page->setEachNum(10);
        $page->setNowPage($_REQUEST["curpage"]);
        $startnum = $page->getEachNum() * ($page->getNowPage() - 1);
        $endnum = $page->getEachNum() * ($page->getNowPage());
        $sql = 'from healthfile a  , personalinfo b , sam_taxempcode emp, district dist '.
                ' where a.fileno = b.fileno  and a.inputpersonid = emp.loginname and a.districtnumber = dist.id
                 and emp.org_id in(
                 32,45,48,64,98,132,238,1419,1489,1542,1994)
                 ';


//        if (gettype($_GET['orgid'])=='string' && intval($_GET['orgid'])>=0 ) {
//            $sql = $sql . ' and  emp.org_id = \''.$_GET['orgid'].'\'';
//        }

        if ($_GET['query_start_time']) {
            $sql = $sql . ' and a.inputdate >=\'' . $_GET['query_start_time'] . '\'';
        }

        if ($_GET['query_end_time']) {
            $sql = $sql . ' and a.inputdate < dateadd(day,1,\'' . $_GET['query_end_time'] . '\')';
        }

        if ($_GET['idnumber']) {
            $sql = $sql . ' and b.idnumber  = \'' . $_GET['idnumber'] . '\'';
        }

        if ($_GET['name']) {
            $sql = $sql . ' and a.name  like \'' . $_GET['name'] . '%\'';
        }
        //处理树的参数
        $checkednode = $_GET['checkednode'];
        if($checkednode && isset($checkednode) && count($checkednode)>0){
            $sql = $sql . " and emp.org_id  in ($checkednode) ";
        }

        $countsql = " select count(*)  $sql ";
//        echo $countsql;
        $stmt = $conn->query($countsql);
//        echo $countsql;
        $total = $stmt->fetch(PDO::FETCH_NUM);
        $page->setTotalNum($total[0]);
        $tsql = "SELECT * FROM  ( SELECT  * FROM (SELECT TOP $endnum row_number() over( order by  a.inputdate desc) rownum,
                        a.fileno,
                        a.name,
                        a.address,
                        a.tel,
                        b.sex,
                        b.birthday,
                        b.idnumber,
                        emp.username,
                        a.inputdate
                        $sql order by  a.inputdate desc )zzzz where rownum>$startnum )zzzzz order by rownum";
//        echo $sql;
        $stmt = $conn->query($tsql);
        $data_list = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($data_list, $row);
        }

        $totalsql = " select '总数：' as fileno,
                        count(a.fileno) name,
                        null as address,
                        null as sex,
                        null as birthday,
                        null as idnumber,
                        null as username,
                        null as inputdate
                        $sql  ";
//        echo $sql;
        $totalstmt = $conn->query($totalsql);
        while ($row = $totalstmt->fetch(PDO::FETCH_OBJ)) {
            array_push($data_list, $row);
        }
        Tpl::output('data_list', $data_list);
        //--0:期初入库 1:采购入库 2:购进退回 3:盘盈 5:领用 12:盘亏 14:领用退回 50:采购计划
        Tpl::output('page', $page->show());
        Tpl::showpage('healthfile.query');
    }

    public function sumOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        $this->getTreeData();
        $where = '   ';
        $orgs = '1,32,45,48,64,98,132,238,1419,1489,1542,1994';
        if(!isset($_GET['query_start_time']))
        $_GET['query_start_time'] = date('Y-m-d', time());
        if(!isset($_GET['query_end_time']))
            $_GET['query_end_time'] = date('Y-m-d', time());
        if ($_GET['query_start_time']) {
            $where = $where . ' and a.inputdate >=\'\'' . $_GET['query_start_time'] . '\'\'';
        }

        if ($_GET['query_end_time']) {
            $where = $where . ' and a.inputdate < dateadd(day,1,\'\'' . $_GET['query_end_time'] . '\'\')';
        }
//
//        if ($_GET['idnumber']) {
//            $where  = $where . ' and b.idnumber  = \'' . $_GET['idnumber'] . '\'';
//        }
//
//        if ($_GET['name']) {
//            $where = $where . ' and a.name  like \'\'' . $_GET['name'] . '%\'\'';
//        }
        //处理树的参数
        $checkednode = $_GET['checkednode'];
        if($checkednode && isset($checkednode) && count($checkednode)>0){
            $orgs = $checkednode;
        }
//        echo $orgs;
//        die;
//        $countsql = " select count(*)  $sql ";
//        echo $countsql;
//        $stmt = $conn->query($countsql);
//        echo $countsql;
//        $total = $stmt->fetch(PDO::FETCH_NUM);
//        $page->setTotalNum($total[0]);
        $tsql = "SET NOCOUNT ON; Exec InputPersonProc_SP_new 'admin','100',' $where ','111100','0' , '$orgs';SET NOCOUNT off; ";
//        echo $tsql;
        $stmt = $conn->prepare($tsql);
        $stmt->execute();
        $data_list = array();
        while ($row = $stmt->fetchObject()) {
            array_push($data_list, $row);
        }

        Tpl::output('data_list', $data_list);
        //--0:期初入库 1:采购入库 2:购进退回 3:盘盈 5:领用 12:盘亏 14:领用退回 50:采购计划
//        Tpl::output('page', $page->show());
        Tpl::showpage('healthfile.sum');
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

}
