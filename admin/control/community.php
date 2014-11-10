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

class communityControl extends SystemControl
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
        $stmt = $conn->query(' select * from Center_codes  where type=\'iCO_Type\' order by code ');
        $this->types = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($this->types, $row);
        }
//        $this->types = array(0 => '期初入库', 1 => '采购入库', 2 => '购进退回', 3 => '盘盈', 5 => '领用', 12 => '盘亏', 14 => '领用退回', 50 => '采购计划',);
        Tpl::output('types', $this->types);
        $this->goodtype = array(0 => '药品', 1 => '卫生用品', 2 => '诊疗项目', 3 => '特殊材料');
        Tpl::output('goodtype', $this->goodtype);


    }

    /**
     * 新增会员
     */
    public function prescriptiondetailOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        //处理数据
        $page = new Page();
        $page->setEachNum(10);
        $page->setNowPage($_REQUEST["curpage"]);
        $startnum = $page->getEachNum() * ($page->getNowPage() - 1);
        $endnum = $page->getEachNum() * ($page->getNowPage());
        $sql = 'from Center_ClinicLog a
            where 1=1 ';
//        if (!isset($_GET['search_type'])) {
//            $_GET['search_type'] = '1';
//        }
//        if (gettype($_GET['search_type']) == 'string' && intval($_GET['search_type']) >= 0) {
//            $sql = $sql . ' and  a.iBuy_Type = \'' . $_GET['search_type'] . '\'';
//        }

        if ($_GET['query_start_time']) {
            $sql = $sql . ' and a.ClinicDate >=\'' . $_GET['query_start_time'] . '\'';
        }

        if ($_GET['query_end_time']) {
            $sql = $sql . ' and a.ClinicDate < dateadd(day,1,\'' . $_GET['query_end_time'] . '\')';
        }

        if ($_GET['orgids']) {
            $sql = $sql . ' and a.OrgID in ( ' . implode(',', $_GET['orgids']) . ')';
        }

        //处理树的参数
        $checkednode = $_GET['checkednode'];
        if ($checkednode && isset($checkednode) && count($checkednode) > 0) {
            $sql = $sql . " and a.orgid  in ($checkednode) ";
        }

        $countsql = " select count(*)  $sql ";
        $stmt = $conn->query($countsql);
        $total = $stmt->fetch(PDO::FETCH_NUM);
        $page->setTotalNum($total[0]);
        $tsql = "SELECT * FROM  ( SELECT  * FROM (SELECT TOP $endnum row_number() over( order by  a.ClinicDate desc) rownum,
                        a.sSickName,
                        a.sSex,
                        a.sShowAge,
                        a.ClinicDate,
                        a.Diagnosis,
                        a.AllergyHistory,
                        a.Signs ,
                        a.Opinion,
                        a.Section,
                        a.Doctor,
                        a.sPhone,
                        a.sAddress,
                        a.sLinkman,
                        a.sIDCard,
                        a.sFileNo,
                        a.sHealthCardID
                        $sql order by  a.ClinicDate desc)zzzz where rownum>$startnum )zzzzz order by rownum";
//        echo $sql;
        $exportsql = " SELECT row_number() over( order by  a.ClinicDate desc) rownum,
                        a.sSickName,
                        a.sSex,
                        a.sShowAge,
                        a.ClinicDate,
                        a.Diagnosis,
                        a.AllergyHistory,
                        a.Signs ,
                        a.Opinion,
                        a.Section,
                        a.Doctor,
                        a.sPhone,
                        a.sAddress,
                        a.sLinkman,
                        a.sIDCard,
                        a.sFileNo,
                        a.sHealthCardID
                        $sql order by  a.ClinicDate desc ";
//        echo $_GET['export']=='true';
//        echo $_GET['export'];
        if(isset($_GET['export']) && $_GET['export']=='true'){
            $this->exportxlsx($exportsql,array('序号','姓名','性别','就诊年龄','就诊日期','诊断','过敏史','主要症状及体征',
                    '处理意见','科室','医生','电话','现住址','联系人','身份证号','档案编号'),'处方明细');
        }
        $stmt = $conn->query($tsql);
        $data_list = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($data_list, $row);
        }

//        $totalsql = " select '总计：' as iBuy_TicketID,
//                        null as sBuy_A6,
//                        null as iBuy_ID,
//                        null as dBuy_Date,
//                        null as iDrug_RecType,
//                        null as iBuy_Type,
//                        null as OrgId,
//                        null as SaleOrgID,
//                        null as iDrug_ID,
//                        null as fBuy_FactNum,
//                        null as sBuy_DrugUnit,
//                        sum(fBuy_TaxMoney) as fBuy_TaxMoney,
//                        sum(fBuy_RetailMoney) as fBuy_RetailMoney,
//                        sum(fBuy_RetailMoney)-sum(fBuy_TaxMoney) as diffmoney
//                        $sql  ";
////        echo $sql;
//        $totalstmt = $conn->query($totalsql);
//        while ($row = $totalstmt->fetch(PDO::FETCH_OBJ)) {
//            array_push($data_list, $row);
//        }
        Tpl::output('data_list', $data_list);
        //--0:期初入库 1:采购入库 2:购进退回 3:盘盈 5:领用 12:盘亏 14:领用退回 50:采购计划
        Tpl::output('page', $page->show());
        Tpl::showpage('community.prescription.detail');
    }

    public function incomedetailOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        //处理数据
        $page = new Page();
        $page->setEachNum(10);
        $page->setNowPage($_REQUEST["curpage"]);
        $startnum = $page->getEachNum() * ($page->getNowPage() - 1);
        $endnum = $page->getEachNum() * ($page->getNowPage());
        $sql = 'from Center_CheckOut a  , Center_codes ico, Center_codes gather,Center_codes state,Center_codes tag,
             Organization org
          where a.iCO_Type = ico.code and ico.type=\'iCO_Type\'
           and  a.iCO_GatherType = gather.code and gather.type=\'iCO_GatherType\'
           and  a.iCO_State = state.code and state.type=\'iCO_State\'
           and  a.iCO_Tag = tag.code and tag.type=\'iCO_Tag\'
           and a.orgid = org.id  ';
//        if (!isset($_GET['search_type'])) {
//            $_GET['search_type'] = '1';
//        }
//        if (gettype($_GET['search_type']) == 'string' && intval($_GET['search_type']) >= 0) {
//            $sql = $sql . ' and  a.iBuy_Type = \'' . $_GET['search_type'] . '\'';
//        }

        if ($_GET['query_start_time']) {
            $sql = $sql . ' and a.dCO_Date >=\'' . $_GET['query_start_time'] . '\'';
        }

        if ($_GET['query_end_time']) {
            $sql = $sql . ' and a.dCO_Date < dateadd(day,1,\'' . $_GET['query_end_time'] . '\')';
        }

        if ($_GET['orgids']) {
            $sql = $sql . ' and a.OrgID in ( ' . implode(',', $_GET['orgids']) . ')';
        }
        if (isset($_GET['types']) and $_GET['types'] != '') {
            $sql = $sql . ' and a.iCO_Type  =  ' . $_GET['types'] . '';
        }
        //处理树的参数
        $checkednode = $_GET['checkednode'];
        if ($checkednode && isset($checkednode) && count($checkednode) > 0) {
            $sql = $sql . " and a.orgid  in ($checkednode) ";
        }


        $moneycol = array('fCO_Foregift','fCO_Balance','fCO_FactMoney','fCO_IncomeMoney','fCO_GetMoney','fCO_PayMoney',
            'fCO_Card','fCO_Cash','fCO_StartMoney','fCO_Medicare','fCO_SelfCost','fCO_SelfPay','fCO_HospitalSubsidy',
            'fCO_BeforeSubsidy','fOweMoney','fCO_PosPay','fRecharge','fConsume','fRechargeBalance','fConsumeBalance',
            'fGive','fCanConsume');
        Tpl::output('moneycol', $moneycol);
        $countsql = " select count(*)  $sql ";

        $stmt = $conn->query($countsql);
        $total = $stmt->fetch(PDO::FETCH_NUM);
        $page->setTotalNum($total[0]);
        $tsql = "SELECT * FROM  ( SELECT  * FROM (SELECT TOP $endnum row_number() over( order by  a.dCO_Date desc) rownum,
                        ico.name as 'iCO_Type',
                        sMakePerson 'iCO_MakePerson',
                        a.dCO_Date,
                        a.dCO_MakeDate,
                        a.fCO_Foregift,
                        a.fCO_Balance,
                        a.fCO_FactMoney,
                        a.fCO_IncomeMoney ,
                        a.fCO_GetMoney,
                        a.fCO_PayMoney,
                        gather.name as 'iCO_GatherType',
                        state.name as 'iCO_State',
                        a.sCO_CapitalMoney,
                        a.sCO_Remark,
                        tag.name as 'iCO_Tag',
                        a.fCO_Card,
                        a.fCO_Cash,
                        a.fCO_StartMoney,
                        a.fCO_Medicare,
                        a.fCO_SelfCost,
                        a.fCO_SelfPay,
                        a.fCO_HospitalSubsidy,
                        a.sCO_SubsidyReason,
                        a.fCO_BeforeSubsidy,
                        a.fOweMoney,
                        a.fCO_PosPay,
                        a.sMemberID,
                        a.sMemberAssistantID,
                        a.fRecharge,
                        a.fConsume,
                        a.fRechargeBalance,
                        a.fConsumeBalance,
                        a.fScale,
                        a.fScaleBalance,
                        a.fScaleToMoney,
                        a.fGive,
                        a.fCanConsume,
                        a.fCanScale,
                        a.fCanGive,
                        a.fAddScale,
                        org.name as 'OrgID'
                        $sql order by  a.dCO_Date desc)zzzz where rownum>$startnum )zzzzz order by rownum";
//        $exportsql = "SELECT  row_number() over( order by  a.dCO_Date desc) rownum,
//                        ico.name as 'iCO_Type',
//                        a.dCO_Date,
//                        a.dCO_MakeDate,
//                        person.sPerson_Name 'iCO_MakePerson',
//                        a.fCO_Foregift,
//                        a.fCO_Balance,
//                        a.fCO_FactMoney,
//                        a.fCO_IncomeMoney ,
//                        a.fCO_GetMoney,
//                        a.fCO_PayMoney,
//                        a.sCO_CapitalMoney
//                        $sql order by  a.dCO_Date desc ";

        $exportsql = "SELECT  row_number() over( order by  a.dCO_Date desc) as '序号',
                        ico.name as '类型',
                        a.dCO_Date '结算日期',
                        a.dCO_MakeDate '制单日期',
                        sMakePerson  '收费员',
                        a.fCO_Foregift '押金',
                        a.fCO_Balance '结算余额',
                        a.fCO_FactMoney '实际金额',
                        a.fCO_IncomeMoney '结算金额' ,
                        a.fCO_GetMoney '收取金额',
                        a.fCO_PayMoney '支付金额',
                        a.sCO_CapitalMoney '金额大写'
                        $sql order by  a.dCO_Date desc ";
//        echo $_GET['export']=='true';
//        echo $_GET['export'];
        if(isset($_GET['export']) && $_GET['export']=='true'){
            $this->exportxlsx($exportsql,array('序号','类型','结算日期','制单日期','收费员','押金','结算余额','实际金额','结算金额','收取金额','支付金额','金额大写'),'收入明细');
        }
        $stmt = $conn->query($tsql);
        $data_list = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($data_list, $row);
        }
        Tpl::output('data_list', $data_list);
        Tpl::output('page', $page->show());
        Tpl::showpage('community.income.detail');
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

    public function prescriptionsumOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        if (!isset($_GET['search_type'])) {
            $_GET['search_type'] = '0';
        }
        $sqlarray = array('Section' => 'a.Section as "Section"',
            'Doctor' => ' a.Doctor as "Doctor" ',
            'year' => ' year(a.ClinicDate) as "year" ',
            'month' => ' left(convert(varchar,ClinicDate,112),6) as  "month" ',
            'day' => ' convert(varchar,ClinicDate,112) as "day" ',
            'OrgID' => ' org.name as "OrgID" '
        );
        $config = array('sumcol' => array('OrgID' => array(name => 'OrgID', 'text' => '机构', map => $this->types),
            'Section' => array(name => 'Section', 'text' => '科室'),
            'Doctor' => array(name => 'Doctor', 'text' => '医生'),
            'year' => array('text' => '年', name=>'year',uncheck=>'month,day' ),
            'month' => array('text' => '月', name=>'month',uncheck=>'year,day'),
            'day' => array('text' => '日', name=>'day',uncheck=>'year,month'),
        ));
        Tpl::output('config', $config);

        //处理汇总字段
        $sumtype = $_GET['sumtype'];
        if ($sumtype == null) {
            $sumtype = array(0 => "OrgID");
            $_GET['sumtype'] = $sumtype;
        }
        $checked = $_GET['checked'];
        $page = new Page();
        $page->setEachNum(10);
        $page->setNowPage($_REQUEST["curpage"]);
        $sql = 'from Center_ClinicLog a  , Organization org  where  a.orgid = org.id ';

        if ($_GET['query_start_time']) {
            $sql = $sql . ' and a.ClinicDate >=\'' . $_GET['query_start_time'] . '\'';
        }

        if ($_GET['query_end_time']) {
            $sql = $sql . ' and a.ClinicDate < dateadd(day,1,\'' . $_GET['query_end_time'] . '\')';
        }

        //处理树的参数
        if ($_GET['orgids']) {
            $sql = $sql . ' and a.OrgID in ( ' . implode(',', $_GET['orgids']) . ')';
        }

        $search_type = $_GET['search_type'];
//        echo $search_type;
        $colconfig = $config;
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
        array_push($displaytext, '人次');
//        var_dump($totalcol);
        $totalcol[0] = '\'总计：\' as ' . explode(' as ', $totalcol[0])[1];
//        var_dump($totalcol);
        $totalcolstr = join(',', $totalcol);
        $sumcolstr = join(',', $sumcol);
        $groupbycolstr = join(',', $groupbycol);
//        echo $sumcolstr;
        $tsql = " select $sumcolstr , count(1) cliniccount
                        $sql group by $groupbycolstr order by $groupbycolstr ";
        $totalsql = " select $totalcolstr , count(1) cliniccount
                        $sql ";
        if(isset($_GET['export']) && $_GET['export']=='true'){
            $this->exportxlsx(array(0=>$tsql,1=>$totalsql),$displaytext,'处方汇总');
        }

//        echo $tsql;
        $stmt = $conn->query($tsql);
        $data_list = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($data_list, $row);
        }
        //处理合计

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
        Tpl::showpage('community.prescription.sum');
    }

    public function incomesumOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        if (!isset($_GET['search_type'])) {
            $_GET['search_type'] = '0';
        }
        $sqlarray = array('iCO_Type' => 'ico.name as "iCO_Type"',
            'MakePerson' => ' sMakePerson as "MakePerson" ',
            'section' => ' sStatSection as "section" ',
            'doctor' => ' sDoctor as "doctor" ',
            'iCO_GatherType' => ' gather.name as "iCO_GatherType" ',
            'year' => ' year(a.dCO_Date) as "year" ',
            'month' => ' left(convert(varchar,dCO_Date,112),6) as  "month" ',
            'day' => ' convert(varchar,dCO_Date,112) as "day" ',
            'OrgID' => ' org.name as "OrgID" '

        );
        $config = array('sumcol' => array(
            'OrgID' => array(name => 'OrgID', 'text' => '机构'),
            'iCO_Type' => array(name => 'iCO_Type', 'text' => '类型', map => $this->types),
            'section' => array(name => 'section', 'text' => '科室'),
            'doctor' => array(name => 'doctor', 'text' => '医生'),
            'MakePerson' => array(name => 'MakePerson', 'text' => '收费员'),
            'iCO_GatherType' => array(name => 'iCO_GatherType', 'text' => '医保类型'),
            'iCO_GatherType' => array(name => 'iCO_GatherType', 'text' => '医保类型'),
            'iCO_GatherType' => array(name => 'iCO_GatherType', 'text' => '医保类型'),
            'year' => array('text' => '年', name=>'year',uncheck=>'month,day' ),
            'month' => array('text' => '月', name=>'month',uncheck=>'year,day'),
            'day' => array('text' => '日', name=>'day',uncheck=>'year,month'),
        ));
        Tpl::output('config', $config);

        //处理汇总字段
        $sumtype = $_GET['sumtype'];
        if ($sumtype == null) {
            $sumtype = array(0 => "OrgID");
            $_GET['sumtype'] = $sumtype;
        }
        $checked = $_GET['checked'];
        $page = new Page();
        $page->setEachNum(10);
        $page->setNowPage($_REQUEST["curpage"]);
        $sql = 'from Center_CheckOut a  , Center_codes ico, Center_codes gather,Center_codes state,Center_codes tag,
            Organization org
          where a.iCO_Type = ico.code and ico.type=\'iCO_Type\'
           and  a.iCO_GatherType = gather.code and gather.type=\'iCO_GatherType\'
           and  a.iCO_State = state.code and state.type=\'iCO_State\'
           and  a.iCO_Tag = tag.code and tag.type=\'iCO_Tag\'
           and a.orgid = org.id ';

        if ($_GET['query_start_time']) {
            $sql = $sql . ' and a.dCO_Date >=\'' . $_GET['query_start_time'] . '\'';
        }

        if ($_GET['query_end_time']) {
            $sql = $sql . ' and a.dCO_Date < dateadd(day,1,\'' . $_GET['query_end_time'] . '\')';
        }

        //处理树的参数
        if ($_GET['orgids']) {
            $sql = $sql . ' and a.OrgID in ( ' . implode(',', $_GET['orgids']) . ')';
        }

        $search_type = $_GET['search_type'];
//        echo $search_type;
        $colconfig = $config;
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
        array_push($displaytext, '应缴现金');
        array_push($displaytext, '处方金额');
        array_push($displaytext, '统筹支付');
        array_push($displaytext, '医保卡支付');
        array_push($displaytext, '现金支付');
        array_push($displaytext, '银行卡付');
        array_push($displaytext, '处方总数');
        array_push($displaytext, '预存下账');
        array_push($displaytext, '赠送下账');
        array_push($displaytext, '积分下账金额');
//        var_dump($totalcol);
        $totalcol[0] = '\'总计：\' as ' . explode(' as ', $totalcol[0])[1];
//        var_dump($totalcol);
        $totalcolstr = join(',', $totalcol);
        $sumcolstr = join(',', $sumcol);
        $groupbycolstr = join(',', $groupbycol);
//        echo $sumcolstr;
        $tsql = " select $sumcolstr ,
                    Sum(fCO_InComeMoney - fCO_Medicare  - fCO_Card - fCO_PosPay - fRecharge  - fConsume - fScaleToMoney) factmoney,
                    Sum(fCO_InComeMoney) incomemoney,
                    Sum(fCO_Medicare) medicare,
                    Sum(fCO_Card) cardmoney,
                    Sum(fCO_Cash) cashmoney,
                    Sum(fCO_PosPay) postpaymoney,
                    Sum( case when RecipeID > 0 and fCO_IncomeMoney>0  then 1  when  RecipeID > 0 and fCO_IncomeMoney<0 then  -1 end ) cliniccount,
                    Sum(fRecharge) sumfRecharge,
                    Sum(fConsume) sumfConsume,
                    Sum(fScaleToMoney) scaletomoney

                        $sql group by $groupbycolstr order by $groupbycolstr ";
//        echo $tsql;
        //处理合计
        $totalsql = " select $totalcolstr ,
                    Sum(fCO_InComeMoney - fCO_Medicare  - fCO_Card - fCO_PosPay - fRecharge  - fConsume - fScaleToMoney) factmoney,
                    Sum(fCO_InComeMoney) incomemoney,
                    Sum(fCO_Medicare) medicare,
                    Sum(fCO_Card) cardmoney,
                    Sum(fCO_Cash) cashmoney,
                    Sum(fCO_PosPay) postpaymoney,
                    Sum( case when RecipeID > 0 and fCO_IncomeMoney>0  then 1  when  RecipeID > 0 and fCO_IncomeMoney<0 then  -1 end ) cliniccount,
                    Sum(fRecharge) sumfRecharge,
                    Sum(fConsume) sumfConsume,
                    Sum(fScaleToMoney) scaletomoney
                        $sql ";

        if(isset($_GET['export']) && $_GET['export']=='true'){
            $this->exportxlsx(array(0=>$tsql,1=>$totalsql),$displaytext,'收入汇总');
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
        Tpl::showpage('community.income.sum');
    }

}
