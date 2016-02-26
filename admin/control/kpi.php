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

class kpiControl extends SystemControl
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
        STpl::output('treelist', $this->treedata_list);
        $stmt = $conn->query(' select * from Center_codes  where type=\'iCO_Type\' order by code ');
        $this->types = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($this->types, $row);
        }
        STpl::output('types', $this->types);
        $this->goodtype = array(0 => '药品', 1 => '卫生用品', 2 => '诊疗项目', 3 => '特殊材料');
        STpl::output('goodtype', $this->goodtype);
        $classsql = ' select iClass_ID,sClass_ID,sClass_Name from Center_Class   ';
        $classstmt = $conn->query($classsql);
        $classtypes = array();
        while ($row = $classstmt->fetch(PDO::FETCH_OBJ)) {
            array_push($classtypes, $row);
        }
        STpl::output('classtypes', $classtypes);
        //计算月份
        $begin = new DateTime('2016-01-01');
        $end = date_create();
        $months = array();
        $month = $end;
        while ($month > $begin) {
            $months[$end->format('Ym')] = $end->format('Ym');
//            array_push($months, $end->format('Ym'));
            date_add($month, date_interval_create_from_date_string('-1 months'));;
        }
        $this->months = $months;
        STpl::output("months", $months);
    }

    public function kpisetOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        $stmt = $conn->query(' select * from kpi_cfg  order by ord ');
        $cfg = array();
        while ($row = $stmt->fetch()) {
            array_push($cfg, $row);
        }

        STpl::output('cfg', $cfg);
        STpl::output('query', $_REQUEST);

        STpl::showpage('kpi.set.html');
    }

    public function kpiOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        $stmt = $conn->query(' select * from kpi_cfg  order by ord ');
        $cfg = array();
        $month = $_REQUEST['month'];
        if(!$month) {
            $month =  date_create()->format('Ym');
        }
        echo $this->months[1];
        $firstrow = -1;
        $idx =0 ;
        while ($row = $stmt->fetch()) {
            if($row['type']=='0'){
                if(!$row['helptext'])
                    $row['helptext'] ='手工录入';
                $row['value'] = $this->getInputValue($month,$row);
                //取值
            }else{
                if(!$row['helptext'])
                    $row['helptext'] ='系统取值';
                //查值
                $row['value'] = $this->getSqlValue($month,$row);

            }
            $row['month']=$month;
            array_push($cfg, $row);
            //动态计算部门列合并

            if($firstrow <0){
                $firstrow = $idx;
                $cfg[$firstrow]['showorg'] = 'true';
                $cfg[$firstrow]['rowspan'] = 1;
            }else{
                if($row['orgname'] ==  $cfg[$firstrow]['orgname']){
                    $cfg[$firstrow]['rowspan'] =  $cfg[$firstrow]['rowspan']+1;
                    $row['showorg'] = false;
                }else{
                    $firstrow = $idx;
                    $cfg[$firstrow]['showorg'] = 'true';
                    $cfg[$firstrow]['rowspan'] = 1;
                }
            }

            $idx++;

        }
        $exportflag =false;
        if (isset ( $_GET ['export'] ) && $_GET ['export'] == 'true') {
            $exportflag = true;
        }
        if ($exportflag) {
            $displaytext = array();
            array_push($displaytext,'序号');
            array_push($displaytext,'时间');
            array_push($displaytext,'部门');
            array_push($displaytext,'考核对象');
            array_push($displaytext,'考核指标');
            array_push($displaytext,'值');
            array_push($displaytext,'说明');

            $propertys = array();
            array_push($propertys,'month');
            array_push($propertys,'orgname');
            array_push($propertys,'target');
            array_push($propertys,'name');
            array_push($propertys,'value');
            array_push($propertys,'helptext');

            $propertysmap =  array();
//            $propertysmap['iDrug_StatClass'] =$this->classmap ;
            $this->exportxlsxbyObject($displaytext,$propertys,$propertysmap,$month.'指标',$cfg);
            exit ;
        }
        STpl::output('cfg', $cfg);
        STpl::output('query', $_REQUEST);
        //计算月份
        STpl::showpage('kpi.index.html');
    }

    private function getInputValue($month,$row){
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        $stmt = $conn->prepare(' select value from kpi_input where name=? and month= convert(date,?+\'01\')');
        $bool = $stmt->execute(array($row['method'],$month));
        if($bool){
            $value = $stmt->fetch(PDO::FETCH_NUM);
        }else{
            $value = array('999');
        }
        $display = $row['display'];
        if(!$display || $display==''){
            $display = '%s';
        }
//        return $value;
        return vsprintf($display,$value);
    }

    private function getSqlValue($month, $cfg){
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        $method = $this::decode($cfg['method']);

        $monthcolstr = $cfg['datecol'];
        $monthcols = explode(',',$monthcolstr);
        $monthsqls = array();
        $idx = 0 ;
        for($i = 0 ; $i< count($monthcols);$i++){
            $col = $monthcols[$i];
            if($col){
                $monthparam1 = ':monthparam'.$idx;
                $idx++;
                $monthparam2 = ':monthparam'.$idx;
                $idx++;
                $sqlstr = "  $col>=convert(date,$monthparam1) and $col<  dateadd(month,1,convert(date,$monthparam2)) ";
                $method = str_replace('##monthsql_'.$i.'##',$sqlstr,$method);
            }
        }
        $this->execsql = $method;
        $stmt = $conn->prepare($method);

        if(count($monthcols)>0){
            $monthstr = $month.'01';
            //named params 不能绑定多次 (sqlserver的bug)
            for($i =0 ; $i<$idx;$i++){
                $stmt->bindValue(':monthparam'.$i, $monthstr);
            }
        }
        $stmt->execute();
        $data = array();
        if ($row = $stmt->fetch()) {
            $data = $row;
        }
        $display = $cfg['display'];

        if(!$display || $display==''){
            $display = '%s';
        }

        return vsprintf($display,$data);
    }
    public function kpiSaveCfg_ajaxOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        $type = $_REQUEST['type'];
        $method = $this->getParam('method');
        $ord = $this->getParam('ord');
        $cfgid = $this->getParam('cfgid');
        $display = $this->getParam('display');
        $orgname = $this->getParam('orgname');
        $datecol = $this->getParam('datecol');
        $helptext = $this->getParam('helptext');
        $sql = ' update kpi_cfg set type = ? , method = ? , ord = ? , display=?,orgname=?,datecol=?,helptext=? where id = ? ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($type, $method, $ord, $display, $orgname,$datecol,$helptext, $cfgid));
        $ret = array('success' => true, 'msg' => '保存成功!');
        echo json_encode($ret);
        exit;
    }

    public function saveInput_ajaxOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        $colname = $this->getParam('colname');
        $value = $this->getParam('value');
        $month = $this->getParam('month');
        $stmt = $conn->prepare(' select id from kpi_input where name=? and month= convert(date,?+\'01\')');
        $stmt->execute(array($colname,$month));
        if ($id = $stmt->fetch(PDO::FETCH_NUM)) {
//            echo '===='.$id[0];
            $sql = ' update kpi_input set value = ?  where id = ? ';
            $stmt = $conn->prepare($sql);
            $stmt->execute(array($value,$id[0]));
        }else{
            $sql = ' insert into kpi_input (id,name,month,value) values(newid(),?,convert(date,?+\'01\'),?) ';
            $stmt = $conn->prepare($sql);
            $stmt->execute(array($colname, $month, $value));
        }
        $ret = array('success' => true, 'msg' => '保存成功!');
        $ret['params'] = $_REQUEST;
        echo json_encode($ret);
        exit;
    }




    public function getParam($name)
    {
        return $this::decode($_REQUEST[$name]);
    }

    public static function decode($value)
    {
        return stripslashes(html_entity_decode($value, ENT_COMPAT | ENT_HTML401 | ENT_QUOTES));
    }

    public function getvalue_ajaxOp()
    {
        try {
            $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
            $type = $_REQUEST['type'];
            $month = $_REQUEST['month'];
            $ret = array('success' => true, 'msg' => '保存成功!');
            if($type=='0'){
                $ret['value'] = $this->getInputValue($month,$_REQUEST);
            }else{
                $ret['value'] = $this->getSqlValue($month,$_REQUEST);
            }

            $ret['params'] = $_REQUEST;
            $ret['execsql'] = $this->execsql;
            echo json_encode($ret);
        } catch (Exception $e) {
            echo json_encode(array('success' => false, 'msg' => '错误!', 'exception' => $e->xdebug_message, 'e' => $e));
        }
    }
}
