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

class financeControl extends SystemControl
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
        $classsql = ' select iClass_ID,sClass_ID,sClass_Name from Center_Class  where iClass_Type =3  ';
        $classstmt = $conn->query($classsql);
        $classtypes = array();
        while ($row = $classstmt->fetch(PDO::FETCH_OBJ)) {
            array_push($classtypes, $row);
        }
//        $this->types = array(0 => '期初入库', 1 => '采购入库', 2 => '购进退回', 3 => '盘盈', 5 => '领用', 12 => '盘亏', 14 => '领用退回', 50 => '采购计划',);
        Tpl::output('classtypes', $classtypes);

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


    public function saledetailOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        //处理数据
        $page = new Page();
        $page->setEachNum(10);
        $page->setNowPage($_REQUEST["curpage"]);
        $startnum = $page->getEachNum() * ($page->getNowPage() - 1);
        $endnum = $page->getEachNum() * ($page->getNowPage());
        $sql = ' from Center_ClinicSale a
                left join  shopnc_goods_common good  on a.iDrug_ID = good.goods_commonid
                left join Center_Class class on   good.iDrug_StatClass = class.iClass_ID  and class.iClass_Type = 3
                , Organization org
                where   a.orgid = org.id  ';
        if ($_GET['itemtype']) {
            $sql = $sql . ' and a.itemtype =\'' . $_GET['itemtype'] . '\'';
        }

        if ($_GET['query_start_time']) {
            $sql = $sql . ' and a.dSale_MakeDate >=\'' . $_GET['query_start_time'] . '\'';
        }

        if ($_GET['query_end_time']) {
            $sql = $sql . ' and a.dSale_MakeDate < dateadd(day,1,\'' . $_GET['query_end_time'] . '\')';
        }

        if ($_GET['orgids']) {
            $sql = $sql . ' and a.OrgID in ( ' . implode(',', $_GET['orgids']) . ')';

        }
        if ($_GET['search_goods_name'] != '') {
            $sql = $sql . ' and a.itemname like \'%' . trim($_GET['search_goods_name']) . '%\'';
        }

        if ($_GET['classtype'] != '') {
            if ($_GET['classtype'] == 'null') {
                $sql = $sql . ' and class.iClass_ID  is null ';
            } else {
                $sql = $sql . ' and class.iClass_ID =  ' . trim($_GET['classtype']);
            }
        }

        if (intval($_GET['search_commonid']) > 0) {
            $sql = $sql . ' and a.iDrug_ID = ' . intval($_GET['search_commonid']);
        }

        $customsql = 'from  Center_Buy buy left join Center_Customer cus on buy.iCustomer_ID = cus.iCustomer_ID  where  a.iDrug_ID = buy.iDrug_ID ' ;

        if (intval($_GET['sCustomer_ID']) > 0) {
            $sql = $sql . ' and EXISTS (  select 1  from  Center_Buy buy left join Center_Customer cus on buy.iCustomer_ID = cus.iCustomer_ID  where  a.iDrug_ID = buy.iDrug_ID and cus.sCustomer_ID =   ' . $_GET['sCustomer_ID'] . ' )';
            $customsql = $customsql . ' and  cus.sCustomer_ID =   ' . $_GET['sCustomer_ID'];
        }
        if ($_GET['sCustomer_Name'] !='' ) {
            $sql = $sql . ' and EXISTS (  select 1  from  Center_Buy buy left join Center_Customer cus on buy.iCustomer_ID = cus.iCustomer_ID  where  a.iDrug_ID = buy.iDrug_ID and cus.sCustomer_Name  like \'%' . $_GET['sCustomer_Name'] . '%\' )';
            $customsql = $customsql . ' and  cus.sCustomer_Name like   \'%' . $_GET['sCustomer_Name'].'%\'';
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

        $tsql = "SELECT * FROM  ( SELECT  * FROM (SELECT TOP $endnum row_number() over( order by  a.dSale_MakeDate desc) rownum,
                        a.iDrug_ID,
                        a.sSale_id ,
                        a.dSale_MakeDate,
                        isnull(good.sDrug_TradeName,a.itemname) as sDrug_TradeName ,
                        a.ItemType ,
                        good.sDrug_Spec ,
                        good.sDrug_Unit ,
                        good.sDrug_Brand ,
                        (select top 1 cus.sCustomer_ID   $customsql ) sCustomer_ID,
                        (select top 1 cus.sCustomer_Name  $customsql) sCustomer_Name,
                        a.fSale_Num ,
                        a.fSale_TaxPrice ,
                        a.fSale_TaxFactMoney ,
                        org.name,
                        a.StatSection,
                        a.DoctorName,
                        a.sClinicKey ,
                        a.ida_id
                        $sql order by  a.dSale_MakeDate desc)zzzz where rownum>$startnum )zzzzz order by rownum";

        $exportsql = "SELECT  row_number() over( order by  a.dSale_MakeDate desc) rownum,
                        a.sSale_id ,
                        a.dSale_MakeDate,
                        good.sDrug_TradeName ,
                        a.ItemType ,
                        good.sDrug_Spec ,
                        good.sDrug_Unit ,
                        good.sDrug_Brand ,
                        (select top 1 cus.sCustomer_ID   $customsql ) sCustomer_ID,
                        (select top 1 cus.sCustomer_Name  $customsql) sCustomer_Name,
                        a.fSale_Num ,
                        a.fSale_TaxPrice ,
                        a.fSale_TaxFactMoney ,
                        org.name,
                        a.StatSection,
                        a.DoctorName,
                        a.sClinicKey ,
                        a.ida_id
                        $sql order by  a.dSale_MakeDate desc ";
        if (isset($_GET['export']) && $_GET['export'] == 'true') {
            $this->exportxlsx($exportsql, array('序号', '单据编号', '制单日期', '项目名称', '项目类型', '规格', '单位', '产地/厂商','供应商编码','供应商名称', '数量', '单价', '金额', '机构', '科室', '医生', '就诊流水', '处方流水'), '销售明细');
        }
        $stmt = $conn->query($tsql);
        $data_list = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($data_list, $row);
        }
        Tpl::output('data_list', $data_list);
        //--0:期初入库 1:采购入库 2:购进退回 3:盘盈 5:领用 12:盘亏 14:领用退回 50:采购计划
        Tpl::output('page', $page->show());
        Tpl::showpage('finance.saledetail');
    }

    public function ajaxGetDetailOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        $saleid = $_GET['saleid'];
        $orgid = $_GET['orgid'];
        $sql = " select * from Center_ClinicSale  where ssale_id = $saleid and  orgid = $orgid ";
        $stmt = $conn->query($sql);
        $sale = $stmt->fetchAll(PDO::FETCH_OBJ)[0];
        $drugid = $sale->iDrug_ID;
        $sql = " select * from shopnc_goods_common  where goods_commonid = $drugid";
        $stmt = $conn->query($sql);
        $goods = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($goods && count($goods) > 0) {
            $good = $goods[0];
        }
        $ret = array('sale' => $sale, 'good' => $good);
        echo json_encode($ret);
        exit;
    }

    public function ajaxSaveStateClassOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        $iDrug_ID = $_GET['iDrug_ID'];
        $goodid = $_GET['goodid'];
        if ($iDrug_ID != $goodid) {
            $ret = array('success' => false, 'msg' => '新建的"药品编码"应该与"项目编码"一致!');
            echo json_encode($ret);
            exit;
        }

        $classtype = $_GET['classtype'];
        if(!$classtype){
            $ret = array('success' => false, 'msg' => '"财务分类"不能为未分类!');
            echo json_encode($ret);
            exit;
        }
        $goodname = $_GET['goodname'];
        $sDrug_Spec = $_GET['sDrug_Spec'];
        $sDrug_Unit = $_GET['sDrug_Unit'];
        $sDrug_Brand = $_GET['sDrug_Brand'];
        $flag = false;
        $oldgood = null;
        if ($goodid) {
            $sql = " select * from shopnc_goods_common  where goods_commonid = $goodid";
            $stmt = $conn->query($sql);
            $goods = $stmt->fetchAll(PDO::FETCH_OBJ);
            if (count($goods) > 0) {
                $flag = true;
                $oldgood = $goods[0];
            }
        }
        try {
            if ($flag) {

                $sql = " update shopnc_goods_common
                    set iDrug_StatClass = $classtype

                  where goods_commonid = $goodid";
//            throw new Exception($sql);
                $conn->exec($sql);
                $oldstr = json_encode($oldgood);
                $oldgood -> iDrug_StatClass = $classtype;
                $newstr = json_encode($oldgood);
                $admininfo = $this->getAdminInfo();
                $adminid = $admininfo['id'];
                $sql = " insert into log_main
                    values(newid(),'shopnc_goods_common','update','shopnc_goods_common',$adminid,getdate(),?,?)
                     ";
//                throw new Exception($sql);
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(1, $oldstr);
                $stmt->bindValue(2, $newstr);
                $stmt->execute();

            } else {
                $sql = " insert into  shopnc_goods_common
                      (goods_commonid,
                      gc_id,
                      store_id,
                      brand_id,
                      type_id,
                      goods_state,
                      goods_verify,
                      goods_lock,
                      goods_addtime,
                      goods_selltime,
                      goods_price,
                      goods_marketprice,
                      goods_costprice,
                      goods_discount,
                      transport_id,
                      goods_commend,
                      goods_freight,
                      goods_vat,
                      areaid_1,
                      areaid_2,
                      sDrug_ID,
                      iDrug_RecType,
                      sDrug_TradeName,
                      iDrug_Quotiety,
                      iCustomer_ID,
                      fPrice_SRetail,
                      fPrice_PRetail,
                      fPrice_Retail,
                      fPrice_LeastRetail,
                      fPrice_LLimit,
                      fPrice_OBuy,
                      fDrug_ULimit,
                      fDrug_LLimit,
                      fDrug_OStock,
                      fDrug_SStock,
                      fDrug_LeastOStock,
                      fDrug_LeastSStock,
                      iDrug_Pack,
                      iDrug_SmallPack,
                      fDrug_Weight,
                      iDrug_AlarmDays,
                      iDrug_Type,
                      iDrug_Purpose,
                      iDrug_Tax,
                      iDrug_MediCare,
                      iDrug_SubMedicare,
                      iDrug_IsImport,
                      iDrug_IsRecipe,
                      iDrug_State,
                      iDrug_UseFulLife,
                      fDrug_NoTaxAvgPrice,
                      fDrug_NoTaxStockMoney,
                      iDrug_UseFulLifeFormat,
                      iDrug_OutpatientClass,
                      iDrug_InpatientClass,
                      iDrug_UpdateStock,
                      iDrug_ExcuteType,
                      fDrug_AdjustNum,
                      fDrug_LeastAdjustNum,
                      iDrug_BuildPerson,
                      iDrug_IsChineseMedicine,
                      iDrug_ReceiptType,
                      iML_ID,
                      iDrug_IsMeterial,
                      iDrug_IsNCCM,
                      fDrug_Dosage,
                      iDrug_StatClass,
                      iDrug_IsGrain,
                      iDrug_XNHClass,
                      NccmPrice,
                      CZZGPrice,
                      CZJMPrice,
                      iDrug_XnhSpecial,
                      Downloaded,
                      LeastNccmPrice,
                      LeastCZZGPrice,
                      LeastCZJMPrice,
                      iML_NccmId,
                      iDrug_NCCMLevel,
                      iDrug_CanNotSale,
                      iDrug_IsMediCare,
                      iDrug_IsBed,
                      iDrug_IsAnesthesia,
                      iDrug_CaseClass,
                      iDrug_IsHealth
                      )
                    values(
                      $goodid,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      $goodid,
                      0,
                      '$goodname',
                      0,
                      1018,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      1,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      $classtype,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0,
                      0
                    )";


//            echo $sql;
//            throw new Exception($sql);
                $conn->exec($sql);

                $sql = " select * from shopnc_goods_common  where goods_commonid = $goodid";
                $stmt = $conn->query($sql);
                $goods = $stmt->fetchAll(PDO::FETCH_OBJ);
                if (count($goods) > 0) {
                    $oldgood = $goods[0];
                    $oldstr = json_encode($oldgood);
                    $admininfo = $this->getAdminInfo();
                    $adminid = $admininfo['id'];
                    $sql = " insert into log_main
                    values(newid(),'shopnc_goods_common','insert','shopnc_goods_common',$adminid,getdate(),null,?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindValue(1, $oldstr);
                    $stmt->execute();
                }

            }
            $ret = array('success' => true, 'msg' => '保存成功!');
            echo json_encode($ret);
        } catch (Exception $e) {
            throw $e;
            $ret = array('success' => false, 'msg' => $e->getMessage());
            echo json_encode($ret);
        }


        exit;
    }

    public function saledetailmanagerOp()
    {

        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        //处理数据
        $page = new Page();
        $page->setEachNum(10);
        $page->setNowPage($_REQUEST["curpage"]);
        $startnum = $page->getEachNum() * ($page->getNowPage() - 1);
        $endnum = $page->getEachNum() * ($page->getNowPage());
        $sql = ' from Center_ClinicSale a
                left join  shopnc_goods_common good  on a.iDrug_ID = good.goods_commonid
                left join Center_Class class on   good.iDrug_StatClass = class.iClass_ID  and class.iClass_Type = 3
                , Organization org
                where   a.orgid = org.id  ';
        if ($_GET['itemtype']) {
            $sql = $sql . ' and a.itemtype =\'' . $_GET['itemtype'] . '\'';
        }

        if ($_GET['query_start_time']) {
            $sql = $sql . ' and a.dSale_MakeDate >=\'' . $_GET['query_start_time'] . '\'';
        }

        if ($_GET['query_end_time']) {
            $sql = $sql . ' and a.dSale_MakeDate < dateadd(day,1,\'' . $_GET['query_end_time'] . '\')';
        }

        if ($_GET['orgids']) {
            $sql = $sql . ' and a.OrgID in ( ' . implode(',', $_GET['orgids']) . ')';

        }
        if ($_GET['search_goods_name'] != '') {
            $sql = $sql . ' and a.itemname like \'%' . trim($_GET['search_goods_name']) . '%\'';
        }

        if ($_GET['classtype'] != '') {
            if ($_GET['classtype'] == 'null') {
                $sql = $sql . ' and good.iDrug_StatClass  is null ';
            } else {
                $sql = $sql . ' and good.iDrug_StatClass =  ' . trim($_GET['classtype']);
            }
        }

        if (intval($_GET['search_commonid']) > 0) {
            $sql = $sql . ' and a.iDrug_ID = ' . intval($_GET['search_commonid']);
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
        $tsql = "SELECT * FROM  ( SELECT  * FROM (SELECT TOP $endnum row_number() over( order by  a.dSale_MakeDate desc) rownum,
                        a.iDrug_ID,
                        a.sSale_id ,
                        a.dSale_MakeDate,
                        isnull(good.sDrug_TradeName,a.itemname) as sDrug_TradeName ,
                        a.ItemType ,
                        good.sDrug_Spec ,
                        good.sDrug_Unit ,
                        good.sDrug_Brand ,
                        a.fSale_Num ,
                        a.fSale_TaxPrice ,
                        a.fSale_TaxFactMoney ,
                        org.name,
                        a.StatSection,
                        a.DoctorName,
                        a.sClinicKey ,
                        a.ida_id,
                        a.orgid
                        $sql order by  a.dSale_MakeDate desc)zzzz where rownum>$startnum )zzzzz order by rownum";
//        echo $tsql;
        $exportsql = "SELECT  row_number() over( order by  a.dSale_MakeDate desc) rownum,
                        a.sSale_id ,
                        a.dSale_MakeDate,
                        good.sDrug_TradeName ,
                        a.ItemType ,
                        good.sDrug_Spec ,
                        good.sDrug_Unit ,
                        good.sDrug_Brand ,
                        a.fSale_Num ,
                        a.fSale_TaxPrice ,
                        a.fSale_TaxFactMoney ,
                        org.name,
                        a.StatSection,
                        a.DoctorName,
                        a.sClinicKey ,
                        a.ida_id
                        $sql order by  a.dSale_MakeDate desc ";
        if (isset($_GET['export']) && $_GET['export'] == 'true') {
            $this->exportxlsx($exportsql, array('序号', '单据编号', '制单日期', '项目名称', '项目类型', '规格', '单位', '产地/厂商', '数量', '单价', '金额', '机构', '科室', '医生', '就诊流水', '处方流水'), '销售明细');
        }
        $stmt = $conn->query($tsql);
        $data_list = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($data_list, $row);
        }
        Tpl::output('data_list', $data_list);
        //--0:期初入库 1:采购入库 2:购进退回 3:盘盈 5:领用 12:盘亏 14:领用退回 50:采购计划
        Tpl::output('page', $page->show());
        Tpl::showpage('finance.saledetailmanager');
    }

    public function financesumOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        if (!isset($_GET['search_type'])) {
            $_GET['search_type'] = '0';
        }
        $sqlarray = array(
            'classname' => ' case when class.sClass_ID is not null then class.sClass_ID+\'.\'+class.sClass_Name  else \'\' end as "classname" ',
            'Section' => 'a.StatSection as "Section"',
            'execSection' => ' ',
            'Doctor' => ' a.DoctorName as "Doctor" ',
            'year' => ' year(a.dSale_GatherDate) as "year" ',
            'month' => ' left(convert(varchar,dSale_GatherDate,112),6) as  "month" ',
            'day' => ' convert(varchar,dSale_GatherDate,112) as "day" ',
            'OrgID' => ' org.name as "OrgID" ',
            'dSale_MakeDate' => ' replace( CONVERT( CHAR(10), a.dSale_MakeDate, 102), \'.\', \'-\') as "dSale_MakeDate" ',
            'dSale_GatherDate' => ' replace( CONVERT( CHAR(10), a.dSale_GatherDate , 102), \'.\', \'-\') as "dSale_GatherDate" ',
        );
        $config = array('sumcol' => array(
            'OrgID' => array(name => 'OrgID', 'text' => '机构'),

            'Section' => array(name => 'Section', 'text' => '统计科室'),
            'classname' => array(name => 'classname', 'text' => '财务分类'),
//            'execSection' => array(name => 'execSection', 'text' => '执行科室'),
            'Doctor' => array(name => 'Doctor', 'text' => '医生'),
            'year' => array('text' => '年', name => 'year', uncheck => 'month,day'),
            'month' => array('text' => '月', name => 'month', uncheck => 'year,day'),
            'day' => array('text' => '日', name => 'day', uncheck => 'year,month'),
        ));
        Tpl::output('config', $config);

        //处理汇总字段
        $sumtype = $_GET['sumtype'];
        if ($sumtype == null) {
            $sumtype = array(0 => "OrgID", 1 => "classname");
            $_GET['sumtype'] = $sumtype;
        }
        $checked = $_GET['checked'];
        $page = new Page();
        $page->setEachNum(10);
        $page->setNowPage($_REQUEST["curpage"]);
        $sql = 'from Center_ClinicSale a
                left join  shopnc_goods_common good  on a.iDrug_ID = good.goods_commonid
                left join Center_Class class on   good.iDrug_StatClass = class.iClass_ID and class.iClass_Type = 3
                , Organization org
                where   a.orgid = org.id  ';

        if ($_GET['query_start_time']) {
            $sql = $sql . ' and a.dSale_MakeDate >=\'' . $_GET['query_start_time'] . '\'';
        }

        if ($_GET['query_end_time']) {
            $sql = $sql . ' and a.dSale_MakeDate < dateadd(day,1,\'' . $_GET['query_end_time'] . '\')';
        }

        if ($_GET['gather_start_time']) {
            $sql = $sql . ' and a.dSale_GatherDate >=\'' . $_GET['gather_start_time'] . '\'';
        }

        if ($_GET['gather_end_time']) {
            $sql = $sql . ' and a.dSale_GatherDate < dateadd(day,1,\'' . $_GET['gather_end_time'] . '\')';
        }
        if ($_GET['search_goods_name'] != '') {
            $sql = $sql . ' and good.goods_name like \'%' . trim($_GET['search_goods_name']) . '%\'';
        }
        if (intval($_GET['search_commonid']) > 0) {
            $sql = $sql . ' and good.goods_commonid = ' . intval($_GET['search_commonid']);
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
            if (isset($colconfig['sqlwher'])) {
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
        array_push($displaytext, '收入');
        array_push($displaytext, '成本金额');
        array_push($displaytext, '毛利');
        array_push($displaytext, '毛利率');
//        var_dump($totalcol);
        $totalcol[0] = '\'总计：\' as ' . explode(' as ', $totalcol[0])[1];
//        var_dump($totalcol);
        $totalcolstr = join(',', $totalcol);
        $sumcolstr = join(',', $sumcol);
        $groupbycolstr = join(',', $groupbycol);
//        echo $sumcolstr;
        $tsql = " select $sumcolstr ,
                    sum(fSale_TaxFactMoney) taxmoney ,
                    sum(fSale_NoTaxMoney) notaxmoney ,
                    sum(fSale_TaxFactMoney) -sum(fSale_NoTaxMoney)  grossprofit,
                    case when sum(fSale_TaxFactMoney) =0 then 0 else (sum(fSale_TaxFactMoney) -sum(fSale_NoTaxMoney))/sum(fSale_TaxFactMoney) end  grossprofitrate
                        $sql group by $groupbycolstr order by $groupbycolstr ";
//        echo $tsql;
        $totalsql = " select $totalcolstr , sum(fSale_TaxFactMoney) taxmoney ,
                    sum(fSale_NoTaxMoney) notaxmoney ,
                    sum(fSale_TaxFactMoney) -sum(fSale_NoTaxMoney)  grossprofit,
                    case when sum(fSale_TaxFactMoney) =0 then 0 else (sum(fSale_TaxFactMoney) -sum(fSale_NoTaxMoney))/sum(fSale_TaxFactMoney) end  grossprofitrate
                        $sql ";
        if (isset($_GET['export']) && $_GET['export'] == 'true') {
            $this->exportxlsx(array(0 => $tsql, 1 => $totalsql), $displaytext, '门诊收入统计');
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
        Tpl::showpage('finance.sum');
    }


    public function financeinsumOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        if (!isset($_GET['search_type'])) {
            $_GET['search_type'] = '0';
        }
        $sqlarray = array(
            'classname' => ' case when class.sClass_ID is not null then class.sClass_ID+\'.\'+class.sClass_Name  else \'\' end as "classname" ',
            'Section' => 'a.StatSection as "Section"',
            'execSection' => ' ',
            'Doctor' => ' a.DoctorName as "Doctor" ',
            'year' => ' year(a.dSale_MakeDate) as "year" ',
            'month' => ' left(convert(varchar,dSale_MakeDate,112),6) as  "month" ',
            'day' => ' convert(varchar,dSale_MakeDate,112) as "day" ',
            'OrgID' => ' org.name as "OrgID" ',
            'dSale_MakeDate' => ' replace( CONVERT( CHAR(10), a.dSale_MakeDate, 102), \'.\', \'-\') as "dSale_MakeDate" ',
            'dSale_GatherDate' => ' replace( CONVERT( CHAR(10), a.dSale_GatherDate , 102), \'.\', \'-\') as "dSale_GatherDate" ',
        );
        $config = array('sumcol' => array(
            'OrgID' => array(name => 'OrgID', 'text' => '机构'),

            'Section' => array(name => 'Section', 'text' => '统计科室'),
            'classname' => array(name => 'classname', 'text' => '财务分类'),
//            'execSection' => array(name => 'execSection', 'text' => '执行科室'),
            'Doctor' => array(name => 'Doctor', 'text' => '医生'),
            'year' => array('text' => '年', name => 'year', uncheck => 'month,day'),
            'month' => array('text' => '月', name => 'month', uncheck => 'year,day'),
            'day' => array('text' => '日', name => 'day', uncheck => 'year,month'),
        ));
        Tpl::output('config', $config);

        //处理汇总字段
        $sumtype = $_GET['sumtype'];
        if ($sumtype == null) {
            $sumtype = array(0 => "OrgID", 1 => "classname");
            $_GET['sumtype'] = $sumtype;
        }
        $checked = $_GET['checked'];
        $page = new Page();
        $page->setEachNum(10);
        $page->setNowPage($_REQUEST["curpage"]);
        $sql = 'from Center_InpatientSale a
                left join  shopnc_goods_common good  on a.iDrug_ID = good.goods_commonid
                left join Center_Class class on   good.iDrug_StatClass = class.iClass_ID and class.iClass_Type = 3
                , Organization org
                where   a.orgid = org.id  ';

        if ($_GET['query_start_time']) {
            $sql = $sql . ' and a.dSale_MakeDate >=\'' . $_GET['query_start_time'] . '\'';
        }

        if ($_GET['query_end_time']) {
            $sql = $sql . ' and a.dSale_MakeDate < dateadd(day,1,\'' . $_GET['query_end_time'] . '\')';
        }

        if ($_GET['gather_start_time']) {
            $sql = $sql . ' and a.dSale_MakeDate >=\'' . $_GET['gather_start_time'] . '\'';
        }

        if ($_GET['gather_end_time']) {
            $sql = $sql . ' and a.dSale_MakeDate < dateadd(day,1,\'' . $_GET['gather_end_time'] . '\')';
        }
        if ($_GET['search_goods_name'] != '') {
            $sql = $sql . ' and good.goods_name like \'%' . trim($_GET['search_goods_name']) . '%\'';
        }
        if (intval($_GET['search_commonid']) > 0) {
            $sql = $sql . ' and good.goods_commonid = ' . intval($_GET['search_commonid']);
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
            if (isset($colconfig['sqlwher'])) {
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
        array_push($displaytext, '收入');
        array_push($displaytext, '成本金额');
        array_push($displaytext, '毛利');
        array_push($displaytext, '毛利率');
//        var_dump($totalcol);
        $totalcol[0] = '\'总计：\' as ' . explode(' as ', $totalcol[0])[1];
//        var_dump($totalcol);
        $totalcolstr = join(',', $totalcol);
        $sumcolstr = join(',', $sumcol);
        $groupbycolstr = join(',', $groupbycol);
//        echo $sumcolstr;
        $tsql = " select $sumcolstr ,
                    sum(fSale_TaxFactMoney) taxmoney ,
                    sum(fSale_NoTaxMoney) notaxmoney ,
                    sum(fSale_TaxFactMoney) -sum(fSale_NoTaxMoney)  grossprofit,
                    case when sum(fSale_TaxFactMoney) =0 then 0 else (sum(fSale_TaxFactMoney) -sum(fSale_NoTaxMoney))/sum(fSale_TaxFactMoney) end  grossprofitrate
                        $sql group by $groupbycolstr order by $groupbycolstr ";
//        echo $tsql;
        $totalsql = " select $totalcolstr , sum(fSale_TaxFactMoney) taxmoney ,
                    sum(fSale_NoTaxMoney) notaxmoney ,
                    sum(fSale_TaxFactMoney) -sum(fSale_NoTaxMoney)  grossprofit,
                    case when sum(fSale_TaxFactMoney) =0 then 0 else (sum(fSale_TaxFactMoney) -sum(fSale_NoTaxMoney))/sum(fSale_TaxFactMoney) end  grossprofitrate
                        $sql ";
        if (isset($_GET['export']) && $_GET['export'] == 'true') {
            $this->exportxlsx(array(0 => $tsql, 1 => $totalsql), $displaytext, '住院收入统计');
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
        Tpl::showpage('finance.insum');
    }

    public function financegoodsumOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        if (!isset($_GET['search_type'])) {
            $_GET['search_type'] = '0';
        }
        $sqlarray = array(
            'classname' => ' case when class.sClass_ID is not null then class.sClass_ID+\'.\'+class.sClass_Name  else \'\' end as "classname" ',
            'Section' => 'a.StatSection as "Section"',
            'execSection' => ' ',
            'Doctor' => ' a.DoctorName as "Doctor" ',
            'year' => ' year(a.dSale_GatherDate) as "year" ',
            'month' => ' left(convert(varchar,dSale_GatherDate,112),6) as  "month" ',
            'day' => ' convert(varchar,dSale_GatherDate,112) as "day" ',
            'OrgID' => ' org.name as "OrgID" ',
            'dSale_MakeDate' => ' replace( CONVERT( CHAR(10), a.dSale_MakeDate, 102), \'.\', \'-\') as "dSale_MakeDate" ',
            'dSale_GatherDate' => ' replace( CONVERT( CHAR(10), a.dSale_GatherDate , 102), \'.\', \'-\') as "dSale_GatherDate" ',
            'sDrug_Spec' => ' goods.sDrug_Spec as "sDrug_Spec" ',
            'sDrug_Unit' => ' goods.sDrug_Unit as "sDrug_Unit" ',
            'sDrug_Brand' => ' goods.sDrug_Brand as "sDrug_Brand" ',

            'sDrug_TradeName' => ' goods.sDrug_TradeName as "sDrug_TradeName"  ',
        );
        $config = array('sumcol' => array(
            'OrgID' => array(name => 'OrgID', 'text' => '机构'),
            'year' => array('text' => '年', name => 'year', uncheck => 'month,day'),
            'month' => array('text' => '月', name => 'month', uncheck => 'year,day'),
            'day' => array('text' => '日', name => 'day', uncheck => 'year,month'),
        ));
        Tpl::output('config', $config);

        //处理汇总字段
        $sumtype = $_GET['sumtype'];
        if ($sumtype == null) {
            $sumtype = array();
            $_GET['sumtype'] = $sumtype;
        }
        $checked = $_GET['checked'];
        $page = new Page();
        $page->setEachNum(10);
        $page->setNowPage($_REQUEST["curpage"]);
        $sql = 'from Center_ClinicSale a
                left join  shopnc_goods_common goods  on a.iDrug_ID = goods.goods_commonid
                left join Center_Class class on   goods.iDrug_StatClass = class.iClass_ID and class.iClass_Type = 3
                , Organization org
                where   a.orgid = org.id and  goods.idrug_rectype in (0,1,3)  ';

        if ($_GET['query_start_time']) {
            $sql = $sql . ' and a.dSale_MakeDate >=\'' . $_GET['query_start_time'] . '\'';
        }

        if ($_GET['query_end_time']) {
            $sql = $sql . ' and a.dSale_MakeDate < dateadd(day,1,\'' . $_GET['query_end_time'] . '\')';
        }

        if ($_GET['gather_start_time']) {
            $sql = $sql . ' and a.dSale_GatherDate >=\'' . $_GET['gather_start_time'] . '\'';
        }

        if ($_GET['gather_end_time']) {
            $sql = $sql . ' and a.dSale_GatherDate < dateadd(day,1,\'' . $_GET['gather_end_time'] . '\')';
        }

        //处理树的参数
        if ($_GET['orgids']) {
            $sql = $sql . ' and a.OrgID in ( ' . implode(',', $_GET['orgids']) . ')';
        }

        if ($_GET['search_goods_name'] != '') {
            $sql = $sql . ' and goods.goods_name like \'%' . trim($_GET['search_goods_name']) . '%\'';
        }
        if (intval($_GET['search_commonid']) > 0) {
            $sql = $sql . ' and goods.sDrug_ID = \'' . ($_GET['search_commonid']).'\'';
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
            if (isset($colconfig['sqlwher'])) {
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
        array_push($displaytext, '商品编码');
        array_push($displaytext, '商品名称');
        array_push($displaytext, '规格');
        array_push($displaytext, '单位');
        array_push($displaytext, '产地厂牌');
        array_push($displaytext, '数量');
        array_push($displaytext, '平均单价');
        array_push($displaytext, '金额');
        array_push($displaytext, '成本');
        array_push($displaytext, '毛利');
        array_push($displaytext, '毛利率');
//        var_dump($totalcol);
        if (count($totalcol) > 0)
            $totalcol[0] = '\'总计：\' as ' . explode(' as ', $totalcol[0])[1];
//        var_dump($totalcol);
        if (count($totalcol) > 0)
            $totalcolstr = join(',', $totalcol) . ',';
        if (count($sumcol) > 0)
            $sumcolstr = join(',', $sumcol) . ',';
        if (count($groupbycol) > 0)
            $groupbycolstr = join(',', $groupbycol) . ',';
//        echo $sumcolstr;
        $tsql = " select
                    $sumcolstr
                    goods.sDrug_ID as iDrug_ID,
                    goods.sDrug_TradeName as sDrug_TradeName,
                    goods.sDrug_Spec as sDrug_Spec,
                    goods.sDrug_Unit as sDrug_Unit,
                    goods.sDrug_Brand as sDrug_Brand,
                    sum(fSale_Num) drugcount ,
                    case when sum(fSale_Num) =0 then 0 else  sum(fSale_TaxFactMoney)/ sum(fSale_Num) end as fSale_TaxPrice,
                    sum(fSale_TaxFactMoney) taxmoney ,
                    sum(fSale_NoTaxMoney) notaxmoney ,
                    sum(fSale_TaxFactMoney) -sum(fSale_NoTaxMoney)  grossprofit,
                    case when sum(fSale_TaxFactMoney) =0 then 0 else (sum(fSale_TaxFactMoney) -sum(fSale_NoTaxMoney))/sum(fSale_TaxFactMoney) end  grossprofitrate
                        $sql group by  $groupbycolstr goods.sDrug_ID, goods.sDrug_TradeName ,
                    goods.sDrug_Spec ,
                    goods.sDrug_Unit ,
                    goods.sDrug_Brand  having sum(fSale_Num) >0  order by $groupbycolstr goods.sDrug_TradeName ,
                    goods.sDrug_Spec ,
                    goods.sDrug_Unit ,
                    goods.sDrug_Brand  ";
//        echo $tsql;
        if (count($totalcol) > 0) {
            $totalsql = " select $totalcolstr
                    '' as iDrug_ID,
                    '' as sDrug_TradeName,
                    '' as sDrug_Spec,
                    '' as sDrug_Unit,
                    '' as sDrug_Brand,
                    sum(fSale_Num) drugcount ,
                    0 as fSale_TaxPrice,
                    sum(fSale_TaxFactMoney) taxmoney ,
                    sum(fSale_NoTaxMoney) notaxmoney ,
                    sum(fSale_TaxFactMoney) -sum(fSale_NoTaxMoney)  grossprofit,
                    case when sum(fSale_TaxFactMoney) =0 then 0 else (sum(fSale_TaxFactMoney) -sum(fSale_NoTaxMoney))/sum(fSale_TaxFactMoney) end  grossprofitrate
                        $sql ";
        } else {
            $totalsql = " select '总计：' as   iDrug_ID,
                     ''   as sDrug_TradeName,
                    '' as sDrug_Spec,
                    '' as sDrug_Unit,
                    '' as sDrug_Brand,
                    sum(fSale_Num) drugcount ,
                    0 as fSale_TaxPrice,
                    sum(fSale_TaxFactMoney) taxmoney ,
                    sum(fSale_NoTaxMoney) notaxmoney ,
                    sum(fSale_TaxFactMoney) -sum(fSale_NoTaxMoney)  grossprofit,
                    case when sum(fSale_TaxFactMoney) =0 then 0 else (sum(fSale_TaxFactMoney) -sum(fSale_NoTaxMoney))/sum(fSale_TaxFactMoney) end  grossprofitrate
                        $sql ";
        }
        if (isset($_GET['export']) && $_GET['export'] == 'true') {
            $this->exportxlsx(array(0 => $tsql, 1 => $totalsql), $displaytext, '收入统计');
        }

//        echo $totalsql;
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
        Tpl::showpage('finance.good.sum');
    }
}
