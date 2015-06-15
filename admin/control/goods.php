<?php
/**
 * 商品栏目管理
 *
 *
 *
 *
 * @copyright  Copyright (c) 2014-2020 SZGR Inc. (http://www.szgr.com.cn)
 * @license    http://www.szgr.com.cn
 * @link       http://www.szgr.com.cn
 * @since      File available since Release v1.1
 */
defined('InShopNC') or exit('Access Invalid!');

class goodsControl extends SystemControl
{
    const EXPORT_SIZE = 5000;

    public function __construct()
    {
        parent::__construct();
        Language::read('goods');
    }

    /**
     * 商品设置
     */
    public function goods_setOp()
    {
        $model_setting = Model('setting');
        if (chksubmit()) {
            $update_array = array();
            $update_array['goods_verify'] = $_POST['goods_verify'];
            $result = $model_setting->updateSetting($update_array);
            if ($result === true) {
                $$this->log(L('nc_edit,nc_goods_set'), 1);
                showMessage(L('nc_common_save_succ'));
            } else {
                $this->log(L('nc_edit,nc_goods_set'), 0);
                showMessage(L('nc_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting', $list_setting);
        Tpl::showpage('goods.setting');
    }

    /**
     * 商品管理
     */
    public function goodsOp()
    {
        $model_goods = Model('goods');

        /**
         * 查询条件
         */
        $where = array();
        if ($_GET['search_goods_name'] != '') {
            $where['goods_name'] = array('like', '%' . trim($_GET['search_goods_name']) . '%');
        }
        if (intval($_GET['search_commonid']) > 0) {
            $where['goods_commonid'] = intval($_GET['search_commonid']);
        }
        if ($_GET['search_store_name'] != '') {
            $where['store_name'] = array('like', '%' . trim($_GET['search_store_name']) . '%');
        }
        if (intval($_GET['search_brand_id']) > 0) {
            $where['brand_id'] = intval($_GET['search_brand_id']);
        }
        if (intval($_GET['cate_id']) > 0) {
            $where['gc_id'] = intval($_GET['cate_id']);
        }
        if (in_array($_GET['search_state'], array('0', '1', '10'))) {
            $where['goods_state'] = $_GET['search_state'];
        }
        if (in_array($_GET['search_verify'], array('0', '1', '10'))) {
            $where['goods_verify'] = $_GET['search_verify'];
        }


        switch ($_GET['type']) {
            // 禁售
            case 'lockup':
                $goods_list = $model_goods->getGoodsCommonLockUpList($where);
                break;
            // 等待审核
            case 'waitverify':
                $goods_list = $model_goods->getGoodsCommonWaitVerifyList($where, '*', 10, 'goods_verify desc, goods_commonid desc');
                break;
            // 全部商品
            default:
                $goods_list = $model_goods->getGoodsCommonList($where);
                break;
        }

        Tpl::output('goods_list', $goods_list);
        Tpl::output('page', $model_goods->showpage(2));

        $storage_array = $model_goods->calculateStorage($goods_list);
        Tpl::output('storage_array', $storage_array);

        $goods_class = Model('goods_class')->getTreeClassList(1);
        // 品牌
        $condition = array();
        $condition['brand_apply'] = '1';
        $brand_list = Model('brand')->getBrandList($condition);

        Tpl::output('search', $_GET);
        Tpl::output('goods_class', $goods_class);
        Tpl::output('brand_list', $brand_list);

        Tpl::output('state', array('1' => '出售中', '0' => '仓库中', '10' => '违规下架'));

        Tpl::output('verify', array('1' => '通过', '0' => '未通过', '10' => '等待审核'));

        switch ($_GET['type']) {
            // 禁售
            case 'lockup':
                Tpl::showpage('goods.close');
                break;
            // 等待审核
            case 'waitverify':
                Tpl::showpage('goods.verify');
                break;
            // 全部商品
            default:
                Tpl::showpage('goods.index');
                break;
        }
    }

    /**
     * 违规下架
     */
    public function goods_lockupOp()
    {
        if (chksubmit()) {
            $commonids = $_POST['commonids'];
            $commonid_array = explode(',', $commonids);
            foreach ($commonid_array as $value) {
                if (!is_numeric($value)) {
                    showDialog(L('nc_common_op_fail'), 'reload');
                }
            }
            $update = array();
            $update['goods_stateremark'] = trim($_POST['close_reason']);

            $where = array();
            $where['goods_commonid'] = array('in', $commonid_array);

            Model('goods')->editProducesLockUp($update, $where);
            showDialog(L('nc_common_op_succ'), 'reload', 'succ');
        }
        Tpl::output('commonids', $_GET['id']);
        Tpl::showpage('goods.close_remark', 'null_layout');
    }

    /**
     * 删除商品
     */
    public function goods_delOp()
    {
        if (chksubmit()) {
            $commonid_array = $_POST['id'];
            foreach ($commonid_array as $value) {
                if (!is_numeric($value)) {
                    showDialog(L('nc_common_op_fail'), 'reload');
                }
            }
            Model('goods')->delGoodsAll(array('goods_commonid' => array('in', $commonid_array)));
            showDialog(L('nc_common_op_succ'), 'reload', 'succ');
        }
    }

    /**
     * 审核商品
     */
    public function goods_verifyOp()
    {
        if (chksubmit()) {
            $commonids = $_POST['commonids'];
            $commonid_array = explode(',', $commonids);
            foreach ($commonid_array as $value) {
                if (!is_numeric($value)) {
                    showDialog(L('nc_common_op_fail'), 'reload');
                }
            }
            $update2 = array();
            $update2['goods_verify'] = intval($_POST['verify_state']);

            $update1 = array();
            $update1['goods_verifyremark'] = trim($_POST['verify_reason']);
            $update1 = array_merge($update1, $update2);
            $where = array();
            $where['goods_commonid'] = array('in', $commonid_array);

            Model('goods')->editProduces($where, $update1, $update2);
            showDialog(L('nc_common_op_succ'), 'reload', 'succ');
        }
        Tpl::output('commonids', $_GET['id']);
        Tpl::showpage('goods.verify_remark', 'null_layout');
    }

    /**
     * 库存管理
     */

    public function stockOp()
    {

        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        $treesql = 'select  b.id , b.name,b.districtnumber,b.parentid pId from map_org_wechat a, Organization b where a.orgid = b.id ';
        $treestmt = $conn->query($treesql);
        $treedata_list = array();
        while ($row = $treestmt->fetch(PDO::FETCH_OBJ)) {
            array_push($treedata_list, $row);
        }
        Tpl::output('treelist', $treedata_list);
        if (!isset($_GET['orgid'])) {
            $_GET['orgid'] = $treedata_list[0]->id;
        }

        $page = new Page();
        $page->setEachNum(10);
        $page->setNowPage($_REQUEST["curpage"]);
        $startnum = $page->getEachNum() * ($page->getNowPage() - 1);
        $endnum = $page->getEachNum() * ($page->getNowPage());
        $sql = 'from Center_DrugStock stock  left join shopnc_goods_common good   on good.goods_commonid = stock.iDrug_ID
            left join Organization org on stock.orgid = org.id
         where good.idrug_rectype in (0,1,3) ';

        if ($_GET['search_goods_name'] != '') {
            $sql = $sql . ' and good.goods_name like \'%' . trim($_GET['search_goods_name']) . '%\'';
        }
        if (intval($_GET['search_commonid']) > 0) {
            $sql = $sql . ' and good.goods_commonid = ' . intval($_GET['search_commonid']);
        }
        if ($_GET['search_store_name'] != '') {
            $sql = $sql . ' and good.store_name like \'%' . trim($_GET['search_store_name']) . '%\'';
        }
        if (intval($_GET['search_brand_id']) > 0) {
            $sql = $sql . ' and good.brand_id = ' . intval($_GET['search_brand_id']) . '';
        }
        if (intval($_GET['cate_id']) > 0) {
            $sql = $sql . ' and good.gc_id = ' . intval($_GET['cate_id']);
        }
        if (in_array($_GET['search_state'], array('0', '1', '10'))) {
            $sql = $sql . ' and good.goods_state  =' . $_GET['search_state'];
        }
        if (in_array($_GET['search_verify'], array('0', '1', '10'))) {
            $sql = $sql . ' and good.goods_verify = ' . $_GET['search_verify'];
        }
        if ($_GET['allowzero'] && $_GET['allowzero'] == 'true') {
            $sql = $sql . '   ';
        } else {
            $sql = $sql . ' and (stock.fDS_OStock <> 0 or  stock.fDS_LeastOStock  <> 0)  ';
        }

        if ($_GET['orgid'] != '') {
            $sql = $sql . ' and stock.orgid =\'' . $_GET['orgid'] . '\'';
        }


        $countsql = " select count(*)  $sql ";
//        echo $countsql;
        $stmt = $conn->query($countsql);
//        echo $countsql;
        $total = $stmt->fetch(PDO::FETCH_NUM);
        $page->setTotalNum($total[0]);
        $tsql = "SELECT * FROM  ( SELECT  * FROM (SELECT TOP $endnum row_number() over( order by  good.goods_commonid) rownum,
                        org.name as OrgName,  *
                            $sql order by  good.goods_commonid)zzzz where rownum>$startnum )zzzzz order by rownum";
        $stmt = $conn->query($tsql);
        $goods_list = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $goods_list[] = $row;
//            $newstmt = $conn->query(" select * from Center_DrugStocksub where idrug_id = '$row->goods_commonid'");
//            $row->detail = $newstmt->fetch(PDO::FETCH_OBJ);
        }

//        var_dump($goods_list);
        Tpl::output('goods_list', $goods_list);
        Tpl::output('page', $page->show());

        $goods_class = Model('goods_class')->getTreeClassList(1);
        // 品牌
        $condition = array();
        $condition['brand_apply'] = '1';
        $brand_list = Model('brand')->getBrandList($condition);

        Tpl::output('search', $_GET);
        Tpl::output('goods_class', $goods_class);
        Tpl::output('brand_list', $brand_list);

        Tpl::output('state', array('1' => '出售中', '0' => '仓库中', '10' => '违规下架'));

        Tpl::output('verify', array('1' => '通过', '0' => '未通过', '10' => '等待审核'));

        Tpl::showpage('goods.stock');
    }
    //审核调价
    public function checkChangePriceOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        $ret = array('success' => true, 'msg' => '审核成功!');
        $iID = $_GET['iID'];
        if ($this->notEmpty($iID)) {
            $retrivesql = 'select count(*) from  Center_OrgPrice where iID = ? ';
            $stmt = $conn->prepare($retrivesql);
            $stmt->bindValue(1, intval($iID));
            $stmt->execute();
            $total = $stmt->fetch(PDO::FETCH_NUM)[0];

            if ($total <= 0) {
                $ret['success'] = false;
                $ret['msg'] = "审核失败,数据 $iID 已经不存在!";
            } else {
                $sPrice_CheckPerson = $_GET['sPrice_CheckPerson'];
                $dPrice_CheckDate = $_GET['dPrice_CheckDate'];
                $updatesql = 'update Center_OrgPrice set iPrice_State = 2,dPrice_CheckDate = ? , sPrice_CheckPerson = ?  where  iID = ? ';
                $stmt = $conn->prepare($updatesql);
                $stmt->bindValue(1, $dPrice_CheckDate);
                $stmt->bindValue(2, $sPrice_CheckPerson);
                $stmt->bindValue(3, intval($iID));
                $rows = $stmt->execute();
                if ($rows > 0) {
                    $ret['success'] = true;
                    $ret['msg'] = "审核成功!";
                } else {
                    $ret['success'] = false;
                    $ret['msg'] = "审核失败,$iID 未更新!";
                }
            }
        } else {
            $ret['success'] = false;
            $ret['msg'] = "审核失败,iID 未传入!";
        }


        echo json_encode($ret);
        exit;
    }

    public function multicheckChangePriceOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        $ret = array('success' => true, 'msg' => '审核成功!');
        $iIDs = $_GET['Iids'];
        $sPrice_CheckPerson = $_GET['check_sPrice_CheckPerson'];
        $dPrice_CheckDate = $_GET['check_dPrice_CheckDate'];
        if ($this->notEmpty($iIDs)) {
            $updatesql = 'update Center_OrgPrice set dPrice_CheckDate = ? , sPrice_CheckPerson = ?  where  iID  in( ' .join($iIDs,',').') ';
//            Log::record($updatesql,"SQL");
            $stmt = $conn->prepare($updatesql);
            $stmt->bindValue(1, $dPrice_CheckDate);
            $stmt->bindValue(2, $sPrice_CheckPerson);
            $rows = $stmt->execute();
            if ($rows > 0) {
                $ret['success'] = true;
                $ret['msg'] = "审核成功!";
            } else {
                $ret['success'] = false;
                $ret['msg'] = "审核失败,$iIDs 未更新!";
            }
        } else {
            $sql = ' from Center_OrgPrice a where (a.sPrice_CheckPerson is null or a.sPrice_CheckPerson = \'\')  ';
            //处理查询字段
            //机构选择
            if ($this->notEmpty($_GET['orgids'])) {
                $sql = $sql . ' and a.OrgID in ( ' . implode(',', $_GET['orgids']) . ')';
            }
            //调价日期 起
            if ($this->notEmpty($_GET['dPrice_Date_begin'])) {
                $sql = $sql . ' and a.dPrice_Date >=  \'' . $_GET['dPrice_Date_begin'] . '\' ';
            }
            //调价日期 止
            if ($this->notEmpty($_GET['dPrice_Date_end'])) {
                $sql = $sql . ' and a.dPrice_Date < dateadd(day,1,\'' . $_GET['dPrice_Date_end'] . '\') ';
            }
            //执行开始日期 起
            if ($this->notEmpty($_GET['dPrice_BeginDate_begin'])) {
                $sql = $sql . ' and a.dPrice_BeginDate >=  \'' . $_GET['dPrice_BeginDate_begin'] . '\' ';
            }
            //执行开始日期 止
            if ($this->notEmpty($_GET['dPrice_BeginDate_end'])) {
                $sql = $sql . ' and a.dPrice_BeginDate < dateadd(day,1,\'' . $_GET['dPrice_BeginDate_end'] . '\') ';
            }
            //执行结束日期 起
            if ($this->notEmpty($_GET['dPrice_EndDate_begin'])) {
                $sql = $sql . ' and a.dPrice_EndDate >=  \'' . $_GET['dPrice_EndDate_begin'] . '\' ';
            }
            //执行结束日期 止
            if ($this->notEmpty($_GET['dPrice_EndDate_end'])) {
                $sql = $sql . ' and a.dPrice_EndDate < dateadd(day,1,\'' . $_GET['dPrice_EndDate_end'] . '\') ';
            }
            //调价人
            if ($this->notEmpty($_GET['sPrice_Person'])) {
                $sql = $sql . ' and a.sPrice_Person =  \'' . $_GET['sPrice_Person'] . '\' ';
            }
            //状态
            if ($this->notEmpty($_GET['iPrice_State'])) {
                $sql = $sql . ' and a.iPrice_State =  ' . intval($_GET['iPrice_State']) . ' ';
            }
            //状态码值表
            $map_iPrice_State = array('' => '全部', '0' => '新增', '1' => '提交', '2' => '审核');
            Tpl::output('map_iPrice_State', $map_iPrice_State);
            //审核日期 起
            if ($this->notEmpty($_GET['dPrice_CheckDate_begin'])) {
                $sql = $sql . ' and a.dPrice_CheckDate >=  \'' . $_GET['dPrice_CheckDate_begin'] . '\' ';
            }
            //审核日期 止
            if ($this->notEmpty($_GET['dPrice_CheckDate_end'])) {
                $sql = $sql . ' and a.dPrice_CheckDate < dateadd(day,1,\'' . $_GET['dPrice_CheckDate_end'] . '\') ';
            }
            //审核人
            if ($this->notEmpty($_GET['sPrice_CheckPerson'])) {
                $sql = $sql . ' and a.sPrice_CheckPerson =  \'' . $_GET['sPrice_CheckPerson'] . '\' ';
            }
            //项目主键
            if ($this->notEmpty($_GET['iDrug_ID'])) {
                $sql = $sql . ' and a.iDrug_ID =  ' . $_GET['iDrug_ID'] . ' ';
            }
            //项目名称
            if ($this->notEmpty($_GET['ItemName'])) {
                $sql = $sql . ' and a.ItemName like \'%' . $_GET['ItemName'] . '%\' ';
            }
            //单位
            if ($this->notEmpty($_GET['Unit'])) {
                $sql = $sql . ' and a.Unit = \'' . $_GET['Unit'] . '\' ';
            }
            //项目类型
            if ($this->notEmpty($_GET['ItemType'])) {
                $sql = $sql . ' and a.ItemType = \'' . ($_GET['ItemType']) . '\' ';
            }
            //单价类型
            if ($this->notEmpty($_GET['iPrice_Type'])) {
                $sql = $sql . ' and a.iPrice_Type = ' . intval($_GET['iPrice_Type']) . ' ';
            }
            //单价类型码值表
            $map_iPrice_Type = array('' => '全部', '0' => '零售价', '1' => '特价', '2' => '二件价');
            Tpl::output('map_iPrice_Type', $map_iPrice_Type);
            //调前价 起
            if ($this->notEmpty($_GET['fPrice_Before_begin'])) {
                $sql = $sql . ' and a.fPrice_Before >=  ' . floatval($_GET['fPrice_Before_begin']) . ' ';
            }
            //调前价 止
            if ($this->notEmpty($_GET['fPrice_Before_end'])) {
                $sql = $sql . ' and a.fPrice_Before <=  ' . floatval($_GET['fPrice_Before_end']) . ' ';
            }
            //调后价 起
            if ($this->notEmpty($_GET['fPrice_After_begin'])) {
                $sql = $sql . ' and a.fPrice_After =  ' . intval($_GET['fPrice_After_begin']) . ' ';
            }
            //调后价 止
            if ($this->notEmpty($_GET['fPrice_After_end'])) {
                $sql = $sql . ' and a.fPrice_After <=  ' . intval($_GET['fPrice_After_end']) . ' ';
            }
            //特殊说明
            if ($this->notEmpty($_GET['sPrice_Remark'])) {
                $sql = $sql . ' and a.sPrice_Remark =  ' . intval($_GET['sPrice_Remark']) . ' ';
            }
            //是否下载说明
            if ($this->notEmpty($_GET['Downloaded'])) {
                $sql = $sql . ' and a.Downloaded =  ' . intval($_GET['Downloaded']) . ' ';
            }

            $updatesql = 'update Center_OrgPrice set iPrice_State = 2, dPrice_CheckDate = ? , sPrice_CheckPerson = ?  '. $sql.' ';
            $stmt = $conn->prepare($updatesql);
            $stmt->bindValue(1, $dPrice_CheckDate);
            $stmt->bindValue(2, $sPrice_CheckPerson);
            $rows = $stmt->execute();
            if ($rows > 0) {
                $ret['success'] = true;
                $ret['msg'] = "审核成功!";
            } else {
                $ret['success'] = false;
                $ret['msg'] = "审核失败,无数据更新!";
            }
        }


        echo json_encode($ret);
        exit;
    }

    public function deleteChangePriceOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        $ret = array('success' => true, 'msg' => '删除成功!');
        $iID = $_GET['iID'];
        if ($this->notEmpty($iID)) {
            $retrivesql = 'select count(*) from  Center_OrgPrice where iID = ? ';
            $stmt = $conn->prepare($retrivesql);
            $stmt->bindValue(1, intval($iID));
            $stmt->execute();
            $total = $stmt->fetch(PDO::FETCH_NUM)[0];
            if ($total <= 0) {
                $ret['success'] = false;
                $ret['msg'] = "删除失败,数据 $iID 已经不存在!";
            } else {
                $sPrice_CheckPerson = $_GET['sPrice_CheckPerson'];
                $dPrice_CheckDate = $_GET['dPrice_CheckDate'];
                $updatesql = 'delete Center_OrgPrice where  iID = ? ';
                $stmt = $conn->prepare($updatesql);
                $stmt->bindValue(1, intval($iID));
                $rows = $stmt->execute();
                if ($rows > 0) {
                    $ret['success'] = true;
                    $ret['msg'] = "删除成功!";
                } else {
                    $ret['success'] = false;
                    $ret['msg'] = "删除失败,$iID 未删除!";
                }
            }
        } else {
            $ret['success'] = false;
            $ret['msg'] = "删除失败,iID 未传入!";
        }


        echo json_encode($ret);
        exit;
    }

    public function changepriceOp()
    {
        //引入查询
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        //存入登录人员名称
        $admininfo = $this->getAdminInfo();
        Tpl::output('adminname', $admininfo['name']);
        //查询机构列表
        $treesql = 'select  b.id , b.name,b.districtnumber,b.parentid pId from map_org_wechat a, Organization b where a.orgid = b.id ';
        $treestmt = $conn->query($treesql);
        $treedata_list = array();
        $org_map = array();
        $treedata_list[] = (object)array('id' => 0, 'name' => '全部机构', 'districtnumber' => '', 'parentid' => '');
        while ($row = $treestmt->fetch(PDO::FETCH_OBJ)) {
            $treedata_list[] = $row;
            $org_map[$row->id] = $row->name;
        }

        Tpl::output('treelist', $treedata_list);
        //加入全部机构
        $org_map[0] = '全部机构';
        Tpl::output('org_map', $org_map);
        //初始化机构
        if (!isset($_GET['orgid'])) {
            $_GET['orgid'] = $treedata_list[0]->id;
        }

        //生成排序字段
        $orderbys = array(
            array('txt' => '调价日期', 'col' => ' dPrice_Date '),
            array('txt' => '执行开始日期', 'col' => ' dPrice_BeginDate '),
            array('txt' => '执行结束日期', 'col' => ' dPrice_EndDate '),
            array('txt' => '项目主键', 'col' => ' iDrug_ID '),
            array('txt' => '项目名称', 'col' => ' ItemName '),
            array('txt' => '审核日期', 'col' => ' dPrice_CheckDate '),
            array('txt' => '调价状态', 'col' => ' iPrice_State '),
            array('txt' => '项目类型', 'col' => ' ItemType '),
            array('txt' => '调前价', 'col' => ' fPrice_Before '),
            array('txt' => '调后价', 'col' => ' fPrice_After '),
        );
        Tpl::output('orderbys', $orderbys);
        //处理分页
        $page = new Page();
        $page->setEachNum(10);
        $page->setNowPage($_REQUEST["curpage"]);
        $startnum = $page->getEachNum() * ($page->getNowPage() - 1);
        $endnum = $page->getEachNum() * ($page->getNowPage());
        //生成SQL
        $sql = 'from Center_OrgPrice a where 1=1 ';
        //处理查询字段
        //机构选择
        if ($this->notEmpty($_GET['orgids'])) {
            $sql = $sql . ' and a.OrgID in ( ' . implode(',', $_GET['orgids']) . ')';
        }
        //调价日期 起
        if ($this->notEmpty($_GET['dPrice_Date_begin'])) {
            $sql = $sql . ' and a.dPrice_Date >=  \'' . $_GET['dPrice_Date_begin'] . '\' ';
        }
        //调价日期 止
        if ($this->notEmpty($_GET['dPrice_Date_end'])) {
            $sql = $sql . ' and a.dPrice_Date < dateadd(day,1,\'' . $_GET['dPrice_Date_end'] . '\') ';
        }
        //执行开始日期 起
        if ($this->notEmpty($_GET['dPrice_BeginDate_begin'])) {
            $sql = $sql . ' and a.dPrice_BeginDate >=  \'' . $_GET['dPrice_BeginDate_begin'] . '\' ';
        }
        //执行开始日期 止
        if ($this->notEmpty($_GET['dPrice_BeginDate_end'])) {
            $sql = $sql . ' and a.dPrice_BeginDate < dateadd(day,1,\'' . $_GET['dPrice_BeginDate_end'] . '\') ';
        }
        //执行结束日期 起
        if ($this->notEmpty($_GET['dPrice_EndDate_begin'])) {
            $sql = $sql . ' and a.dPrice_EndDate >=  \'' . $_GET['dPrice_EndDate_begin'] . '\' ';
        }
        //执行结束日期 止
        if ($this->notEmpty($_GET['dPrice_EndDate_end'])) {
            $sql = $sql . ' and a.dPrice_EndDate < dateadd(day,1,\'' . $_GET['dPrice_EndDate_end'] . '\') ';
        }
        //调价人
        if ($this->notEmpty($_GET['sPrice_Person'])) {
            $sql = $sql . ' and a.sPrice_Person =  \'' . $_GET['sPrice_Person'] . '\' ';
        }
        //状态
        if ($this->notEmpty($_GET['iPrice_State'])) {
            $sql = $sql . ' and a.iPrice_State =  ' . intval($_GET['iPrice_State']) . ' ';
        }
        //状态码值表
        $map_iPrice_State = array('' => '全部', '0' => '新增', '1' => '提交', '2' => '审核');
        Tpl::output('map_iPrice_State', $map_iPrice_State);
        //审核日期 起
        if ($this->notEmpty($_GET['dPrice_CheckDate_begin'])) {
            $sql = $sql . ' and a.dPrice_CheckDate >=  \'' . $_GET['dPrice_CheckDate_begin'] . '\' ';
        }
        //审核日期 止
        if ($this->notEmpty($_GET['dPrice_CheckDate_end'])) {
            $sql = $sql . ' and a.dPrice_CheckDate < dateadd(day,1,\'' . $_GET['dPrice_CheckDate_end'] . '\') ';
        }
        //审核人
        if ($this->notEmpty($_GET['sPrice_CheckPerson'])) {
            $sql = $sql . ' and a.sPrice_CheckPerson =  \'' . $_GET['sPrice_CheckPerson'] . '\' ';
        }
        //项目主键
        if ($this->notEmpty($_GET['iDrug_ID'])) {
            $sql = $sql . ' and a.iDrug_ID =  ' . $_GET['iDrug_ID'] . ' ';
        }
        //项目名称
        if ($this->notEmpty($_GET['ItemName'])) {
            $sql = $sql . ' and a.ItemName like \'%' . $_GET['ItemName'] . '%\' ';
        }
        //单位
        if ($this->notEmpty($_GET['Unit'])) {
            $sql = $sql . ' and a.Unit = \'' . $_GET['Unit'] . '\' ';
        }
        //项目类型
        if ($this->notEmpty($_GET['ItemType'])) {
            $sql = $sql . ' and a.ItemType = \'' . ($_GET['ItemType']) . '\' ';
        }
        //单价类型
        if ($this->notEmpty($_GET['iPrice_Type'])) {
            $sql = $sql . ' and a.iPrice_Type = ' . intval($_GET['iPrice_Type']) . ' ';
        }
        //单价类型码值表
        $map_iPrice_Type = array('' => '全部', '0' => '零售价', '1' => '特价', '2' => '二件价');
        Tpl::output('map_iPrice_Type', $map_iPrice_Type);
        //调前价 起
        if ($this->notEmpty($_GET['fPrice_Before_begin'])) {
            $sql = $sql . ' and a.fPrice_Before >=  ' . floatval($_GET['fPrice_Before_begin']) . ' ';
        }
        //调前价 止
        if ($this->notEmpty($_GET['fPrice_Before_end'])) {
            $sql = $sql . ' and a.fPrice_Before <=  ' . floatval($_GET['fPrice_Before_end']) . ' ';
        }
        //调后价 起
        if ($this->notEmpty($_GET['fPrice_After_begin'])) {
            $sql = $sql . ' and a.fPrice_After =  ' . intval($_GET['fPrice_After_begin']) . ' ';
        }
        //调后价 止
        if ($this->notEmpty($_GET['fPrice_After_end'])) {
            $sql = $sql . ' and a.fPrice_After <=  ' . intval($_GET['fPrice_After_end']) . ' ';
        }
        //特殊说明
        if ($this->notEmpty($_GET['sPrice_Remark'])) {
            $sql = $sql . ' and a.sPrice_Remark =  ' . intval($_GET['sPrice_Remark']) . ' ';
        }
        //是否下载说明
        if ($this->notEmpty($_GET['Downloaded'])) {
            $sql = $sql . ' and a.Downloaded =  ' . intval($_GET['Downloaded']) . ' ';
        }
        //是否下载 码值表
        $map_Downloaded = array('0' => '未下载', '1' => '已下载');
        Tpl::output('map_Downloaded', $map_Downloaded);
        //设置orderby 顺序
        if (!$this->notEmpty($_GET['order'])) {
            $ordersql = 'desc';
        } else {
            $ordersql = $_GET['order'];
        }
        //处理orderby字段
        if ($this->notEmpty($_GET['orderby'])) {
            foreach ($orderbys as $orderby) {
                if ($orderby['txt'] == $_GET['orderby']) {
                    $order = $orderby['col'] . ' ' . $ordersql;
                    break;
                }
            }
        }
        if (empty ($order)) {
            $order = ' ' . $orderbys[0]['col'] . ' desc';
        }

        //查看是否导出
        if (isset($_GET['export']) && $_GET['export'] == 'true') {
            $exportsql = "SELECT  row_number() over( order by   $order) 'rownumber',
                        	[iDrug_ID] ,
                            [ItemName] ,
                            convert(date,dPrice_Date)  'dPrice_Date' ,
                            convert(date,dPrice_BeginDate) 'dPrice_BeginDate',
                            convert(date,dPrice_EndDate) 'dPrice_EndDate',
                            sPrice_Person ,
                            [iPrice_State] ,
                            convert(date,dPrice_CheckDate) 'dPrice_CheckDate' ,
                            sPrice_CheckPerson ,
                            [OrgID] ,
                            [Unit] ,
                            [ItemType] ,
                            [iPrice_Type] ,
                            [fPrice_Before] ,
                            [fPrice_After]
                        $sql order by  $order ";
            $titlearray = array('序号', '商品编码', '商品名称', '调价日期', '执行开始日期', '执行结束日期'
            , '调价人', '调价状态', '审核日期', '审核人', '机构', '商品单位', '项目类型', '单价类型', '调前价', '调后价');
            $maparray = array('iPrice_Type' => $map_iPrice_Type, 'OrgID' => $org_map, 'iPrice_State' => $map_iPrice_State);
            $this->exportxlsx($exportsql, $titlearray, '商品调价审核', $maparray);
        }
        //合计字段
        $countsql = " select count(*)  $sql ";
        Log::record($countsql, 'SQL');
        $stmt = $conn->query($countsql);
        $total = $stmt->fetch(PDO::FETCH_NUM);
        $page->setTotalNum($total[0]);
        //查询数据
        $tsql = "SELECT * FROM  ( SELECT  * FROM (SELECT TOP $endnum row_number() over( order by  $order) rownum,  *
                            $sql order by $order)zzzz where rownum>$startnum )zzzzz order by $order";
        Log::record($tsql, 'SQL');
        $stmt = $conn->query($tsql);
        $ret_list = array();
        //处理结果
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $ret_list[] = $row;
        }

        //展示处理结果
        Tpl::output('ret_list', $ret_list);
        Tpl::output('page', $page->show());
        //显示模板
        Tpl::showpage('goods.changeprice');
    }


    /**
     * ajax获取商品列表
     */
    public function get_goods_stock_ajaxOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        $commonid = $_GET['commonid'];
        $orgid = $_GET['orgid'];
        if ($commonid <= 0) {
            echo 'false';
            exit();
        }
        $sql = " select org.name 'OrgName', * from Center_DrugStocksub sub left join Organization org on sub.orgid=org.id where idrug_id = '$commonid' and orgid='$orgid' ";
        if ($_GET['zeroallow'] && $_GET['zeroallow'] == 'true') {
        } else {
            $sql = $sql . ' and (sub.fBS_OStock <> 0 or  sub.fBS_LeastOStock  <> 0)';
        }
        $newstmt = $conn->query($sql);

//        $stmt = $conn->query($tsql);
        $goods_list = array();
        while ($row = $newstmt->fetch(PDO::FETCH_ASSOC)) {
//            array_push($row," select org.name 'OrgName', * from Center_DrugStocksub sub left join Organization org on sub.orgid=org.id where idrug_id = '$commonid'");
            array_push($goods_list, $row);
            array_push($row, $sql);
        }

//        $goods_list = $newstmt->fetchAll(PDO::FETCH_ASSOC);
//        echo "{sql:\" select org.name 'OrgName', * from Center_DrugStocksub sub left join Organization org on sub.orgid=org.id where idrug_id = '$commonid' \"}";
//        die;
        /**
         * 转码
         */
        if (strtoupper(CHARSET) == 'GBK') {
            Language::getUTF8($goods_list);
        }
        echo json_encode($goods_list);
    }

    /**
     * ajax获取商品列表
     */
    public function get_goods_list_ajaxOp()
    {
        $commonid = $_GET['commonid'];
        if ($commonid <= 0) {
            echo 'false';
            exit();
        }
        $model_goods = Model('goods');
        $goodscommon_list = $model_goods->getGoodeCommonInfo(array('goods_commonid' => $commonid), 'spec_name');
        if (empty($goodscommon_list)) {
            echo 'false';
            exit();
        }
        $goods_list = $model_goods->getGoodsList(array('goods_commonid' => $commonid), 'goods_id,goods_spec,store_id,goods_price,goods_serial,goods_storage,goods_image');
        if (empty($goods_list)) {
            echo 'false';
            exit();
        }

        $spec_name = array_values((array)unserialize($goodscommon_list['spec_name']));
        foreach ($goods_list as $key => $val) {
            $goods_spec = array_values((array)unserialize($val['goods_spec']));
            $spec_array = array();
            foreach ($goods_spec as $k => $v) {
                $spec_array[] = '<div class="goods_spec">' . $spec_name[$k] . L('nc_colon') . '<em title="' . $v . '">' . $v . '</em>' . '</div>';
            }
            $goods_list[$key]['goods_image'] = thumb($val, '60');
            $goods_list[$key]['goods_spec'] = implode('', $spec_array);
            $goods_list[$key]['url'] = urlShop('goods', 'index', array('goods_id' => $val['goods_id']));
        }

        /**
         * 转码
         */
        if (strtoupper(CHARSET) == 'GBK') {
            Language::getUTF8($goods_list);
        }
        echo json_encode($goods_list);
    }

}
