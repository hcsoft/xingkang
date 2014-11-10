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
class goodsControl extends SystemControl{
    const EXPORT_SIZE = 5000;
    public function __construct() {
        parent::__construct ();
        Language::read('goods');
    }
    
    /**
     * 商品设置
     */
    public function goods_setOp() {
		$model_setting = Model('setting');
		if (chksubmit()){
			$update_array = array();
			$update_array['goods_verify'] = $_POST['goods_verify'];
			$result = $model_setting->updateSetting($update_array);
			if ($result === true){
				$this->log(L('nc_edit,nc_goods_set'),1);
				showMessage(L('nc_common_save_succ'));
			}else {
				$this->log(L('nc_edit,nc_goods_set'),0);
				showMessage(L('nc_common_save_fail'));
			}
		}
		$list_setting = $model_setting->getListSetting();
		Tpl::output('list_setting',$list_setting);
        Tpl::showpage('goods.setting');
    }
    /**
     * 商品管理
     */
    public function goodsOp() {
        $model_goods = Model ( 'goods' );
        
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
        if (in_array($_GET['search_state'], array('0','1','10'))) {
            $where['goods_state'] = $_GET['search_state'];
        }
        if (in_array($_GET['search_verify'], array('0','1','10'))) {
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

        $goods_class = Model('goods_class')->getTreeClassList ( 1 );
        // 品牌
        $condition = array();
        $condition['brand_apply'] = '1';
        $brand_list = Model('brand')->getBrandList ( $condition );
        
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
    public function goods_lockupOp() {
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
    public function goods_delOp() {
        if (chksubmit()) {
            $commonid_array = $_POST['id'];
            foreach ($commonid_array as $value) {
                if ( !is_numeric($value)) {
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
    public function goods_verifyOp(){
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

    public function stockOp() {

        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        $treesql = 'select  b.id , b.name,b.districtnumber,b.parentid pId from map_org_wechat a, Organization b where a.orgid = b.id ';
        $treestmt = $conn->query($treesql);
        $treedata_list = array();
        while ($row = $treestmt->fetch(PDO::FETCH_OBJ)) {
            array_push($treedata_list, $row);
        }
        Tpl::output('treelist', $treedata_list);
        if(! isset($_GET['orgid'])){
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
            $sql = $sql . ' and good.goods_name like \'%' .  trim($_GET['search_goods_name']) . '%\'';
        }
        if (intval($_GET['search_commonid']) > 0) {
            $sql = $sql . ' and good.goods_commonid = ' . intval($_GET['search_commonid']) ;
        }
        if ($_GET['search_store_name'] != '') {
            $sql = $sql . ' and good.store_name like \'%' . trim($_GET['search_store_name']) . '%\'';
        }
        if (intval($_GET['search_brand_id']) > 0) {
            $sql = $sql . ' and good.brand_id = ' . intval($_GET['search_brand_id']) . '';
        }
        if (intval($_GET['cate_id']) > 0) {
            $sql = $sql . ' and good.gc_id = ' . intval($_GET['cate_id']) ;
        }
        if (in_array($_GET['search_state'], array('0','1','10'))) {
            $sql = $sql . ' and good.goods_state  =' . $_GET['search_state'] ;
        }
        if (in_array($_GET['search_verify'], array('0','1','10'))) {
            $sql = $sql . ' and good.goods_verify = ' . $_GET['search_verify'] ;
        }
        if ($_GET['allowzero'] && $_GET['allowzero']=='true') {
            $sql = $sql . '   '  ;
        }else{
            $sql = $sql . ' and (stock.fDS_OStock <> 0 or  stock.fDS_LeastOStock  <> 0)  '  ;
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

        $goods_class = Model('goods_class')->getTreeClassList ( 1 );
        // 品牌
        $condition = array();
        $condition['brand_apply'] = '1';
        $brand_list = Model('brand')->getBrandList ( $condition );

        Tpl::output('search', $_GET);
        Tpl::output('goods_class', $goods_class);
        Tpl::output('brand_list', $brand_list);

        Tpl::output('state', array('1' => '出售中', '0' => '仓库中', '10' => '违规下架'));

        Tpl::output('verify', array('1' => '通过', '0' => '未通过', '10' => '等待审核'));

        Tpl::showpage('goods.stock');
    }


    /**
     * ajax获取商品列表
     */
    public function get_goods_stock_ajaxOp() {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        $commonid = $_GET['commonid'];
        $orgid = $_GET['orgid'];
        if ($commonid <= 0) {
            echo 'false';exit();
        }
        $sql = " select org.name 'OrgName', * from Center_DrugStocksub sub left join Organization org on sub.orgid=org.id where idrug_id = '$commonid' and orgid='$orgid' ";
        if($_GET['zeroallow'] && $_GET['zeroallow']=='true'){
        }else{
            $sql = $sql.' and (sub.fBS_OStock <> 0 or  sub.fBS_LeastOStock  <> 0)';
        }
        $newstmt = $conn->query($sql);

//        $stmt = $conn->query($tsql);
        $goods_list = array();
        while ($row = $newstmt->fetch(PDO::FETCH_ASSOC)) {
//            array_push($row," select org.name 'OrgName', * from Center_DrugStocksub sub left join Organization org on sub.orgid=org.id where idrug_id = '$commonid'");
            array_push($goods_list, $row);
            array_push($row,$sql);
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
    public function get_goods_list_ajaxOp() {
        $commonid = $_GET['commonid'];
        if ($commonid <= 0) {
            echo 'false';exit();
        }
        $model_goods = Model('goods');
        $goodscommon_list = $model_goods->getGoodeCommonInfo(array('goods_commonid' => $commonid), 'spec_name');
        if (empty($goodscommon_list)) {
            echo 'false';exit();
        }
        $goods_list = $model_goods->getGoodsList(array('goods_commonid' => $commonid), 'goods_id,goods_spec,store_id,goods_price,goods_serial,goods_storage,goods_image');
        if (empty($goods_list)) {
            echo 'false';exit();
        }
        
        $spec_name = array_values((array)unserialize($goodscommon_list['spec_name']));
        foreach ($goods_list as $key => $val) {
            $goods_spec = array_values((array)unserialize($val['goods_spec']));
            $spec_array = array();
            foreach ($goods_spec as $k => $v) {
                $spec_array[] = '<div class="goods_spec">' . $spec_name[$k] . L('nc_colon') . '<em title="' . $v . '">' . $v .'</em>' . '</div>';
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
