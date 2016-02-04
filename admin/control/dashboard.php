<?php
/**
 * 控制台
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

class dashboardControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('dashboard');
	}
	/**
	 * 欢迎页面
	 */
	public function welcomeOp(){
		/**
		 * 管理员信息
		 */
		$model_admin = Model('admin');
		$tmp = $this->getAdminInfo();
		$condition['admin_id'] = $tmp['id'];
		$admin_info = $model_admin->infoAdmin($condition);
		$admin_info['admin_login_time'] = date('Y-m-d H:i:s',($admin_info['admin_login_time'] == '' ? time() : $admin_info['admin_login_time']));
		/**
		 * 系统信息
		 */
		$version = C('version');
		$setup_date = C('setup_date');
		$statistics['os'] = PHP_OS;
		$statistics['web_server'] = $_SERVER['SERVER_SOFTWARE'];
		$statistics['php_version'] = PHP_VERSION;
		$statistics['sql_version'] = Db::getServerInfo();
		$statistics['shop_version'] = $version;
		$statistics['setup_date'] = substr($setup_date,0,10);
		Tpl::output('statistics',$statistics);
		Tpl::output('admin_info',$admin_info);
		Tpl::showpage('welcome');
	}

    public function chartpageOp(){
         Tpl::showpage('chartPage');
    }
	
	/**
	 * 关于我们
	 */
	public function aboutusOp(){
		
		Tpl::showpage('aboutus');
	}
	
	/**
	 * 统计
	 */
	public function statisticsOp(){
        $statistics = array();
        // 本周开始时间点
        $tmp_time = mktime(0,0,0,date('m'),date('d'),date('Y'))-(date('w')==0?7:date('w')-1)*24*60*60;
        /**
         * 会员
         */
        $model_member = Model('member');
        // 会员总数
        $statistics['member'] = $model_member->getMemberCount(array());
        // 新增会员数
        $statistics['week_add_member'] = $model_member->getMemberCount(array('member_time' => array('egt', $tmp_time)));
        // 预存款提现
        $statistics['cashlist'] = Model('predeposit')->getPdCashCount(array('pdc_payment_state'=>0));

        /**
         * 店铺
         */
        $model_store = Model('store');
        // 店铺总数
        $statistics['store'] = Model('store')->getStoreCount(array());
        // 店铺申请数
        $statistics['store_joinin'] = Model('store_joinin')->getStoreJoininCount(array('joinin_state' => array('in', array(10, 11))));
        // 即将到期
        $statistics['store_expire'] = $model_store->getStoreCount(array('store_state'=>1, 'store_end_time'=>array('between', array(TIMESTAMP, TIMESTAMP + 864000))));
        // 已经到期
        $statistics['store_expired'] = $model_store->getStoreCount(array('store_state'=>1, 'store_end_time'=>array('between', array(1, TIMESTAMP))));
        
        /**
         * 商品
         */
        $model_goods = Model('goods');
        // 商品总数
        $statistics['goods'] = $model_goods->getGoodsCommonCount(array());
        // 新增商品数
        $statistics['week_add_product'] = $model_goods->getGoodsCommonCount(array('goods_addtime' => array('egt', $tmp_time)));
        // 等待审核
        $statistics['product_verify'] = $model_goods->getGoodsCommonWaitVerifyCount(array());
        // 举报
        $statistics['inform_list'] = Model('inform')->getInformCount(array('inform_state'=>1));
        // 品牌申请
        $statistics['brand_apply'] = Model('brand')->getBrandCount(array('brand_apply' => '0'));
		
        /**
         * 交易
         */
        $model_order = Model('order');
        $model_refund_return = Model('refund_return');
        $model_complain = Model('complain');
        // 订单总数
        $statistics['order'] = $model_order->getOrderCount(array());
        // 退款
        $statistics['refund'] = $model_refund_return->getRefundReturn(array('refund_type' => 1, 'refund_state' => 2));
        // 退货
        $statistics['return'] = $model_refund_return->getRefundReturn(array('refund_type' => 2, 'refund_state' => 2));
        // 投诉
        $statistics['complain_new_list'] = $model_complain->getComplainCount(array('complain_state'=>10));
        // 带仲裁
		$statistics['complain_handle_list'] = $model_complain->getComplainCount(array('complain_state'=>40));

		/**
         * 运营
		 */
		// 团购数量
		$statistics['groupbuy_verify_list'] = Model('groupbuy')->getGroupbuyCount(array('state'=>10));
		// 积分订单
		$statistics['points_order'] = Model()->cls()->table('points_order')->where(array('point_orderstate'=>array('in',array(11,20))))->count();
		//待审核账单
		$model_bill = Model('bill');
		$condition = array();
		$condition['ob_state'] = BILL_STATE_STORE_COFIRM;
		$statistics['check_billno'] = $model_bill->getOrderBillCount($condition);
		//待支付账单
		$condition = array();
		$condition['ob_state'] = BILL_STATE_SYSTEM_CHECK;
		$statistics['pay_billno'] = $model_bill->getOrderBillCount($condition);		
        /**
         * CMS
         */
        if (C('cms_isuse')) {
            // 文章审核
            $statistics['cms_article_verify'] = Model('cms_article')->getCmsArticleCount(array('article_state' => 2));
            // 画报审核
            $statistics['cms_picture_verify'] = Model('cms_picture')->getCmsPictureCount(array('picture_state' => 2));
        }
        /**
         * 圈子
         */
        if (C('circle_isuse')) {
            $statistics['circle_verify'] = Model('circle')->getCircleUnverifiedCount();
        }

        echo json_encode($statistics);
		exit;
	}

    private function orgchartOp($type){
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');


        //查询社区数量
        $orgsql = 'select c.id ,  c.name , count(*) as num
                    from map_org_wechat a, Organization b , District c
                    where a.orgid = b.id and left(b.DistrictNumber,6) = c.id group by c.id, c.name order by count(*) desc  ';
        $orgstmt = $conn->query($orgsql);
        $orgdata_list = array();
        while ($row = $orgstmt->fetch(PDO::FETCH_OBJ)) {
            $detailsql = ' select id,name from  Organization  where DistrictNumber like \''.$row->id.'%\' and id in (select orgid from map_org_wechat) ';
            $detailstmt = $conn->query($detailsql);
            $detail_list = array();
            while ($detailrow = $detailstmt->fetch(PDO::FETCH_NUM)) {
                array_push($detail_list, $detailrow);
            }
            $row->details =$detail_list;
            array_push($orgdata_list, $row);
        }
        return $orgdata_list;
    }


    private function salechartOp($type){
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        //查询销售情况
        $datesql = '';
        if($type=='2'){
            $datesql = ' and checkout.dCO_Date>= \''.date('Y-m-d',time()). '\' and checkout.dCO_Date< \''.date('Y-m-d',strtotime('+1 day')).'\'';
        }else if($type=='3'){
            $datesql = ' and checkout.dCO_Date>= \''.date('Y-m-1',time()). '\' and checkout.dCO_Date< \''.date('Y-m-d',strtotime('+1 day')).'\'';
        }else if($type=='4'){
            $datesql = ' and checkout.dCO_Date>= \''.date('Y-1-1',time()). '\' and checkout.dCO_Date< \''.date('Y-m-d',strtotime('+1 day')).'\'';
        }else if($type=='5'){
            $datesql = ' and checkout.dCO_Date>= \''.date('Y-m-d',strtotime('-1 day')). '\' and checkout.dCO_Date< \''.date('Y-m-d',time()).'\'';
        }else if($type=='6'){
            $datesql = ' and checkout.dCO_Date>= \''.date('Y-m-1' ,strtotime(date('Y-m-01')) - 86400 ). '\' and checkout.dCO_Date< \''.date('Y-m-1',time()).'\'';
        }else if($type=='7'){
            $datesql = ' and checkout.dCO_Date>= \''.date('Y-1-1',strtotime('-1 year')). '\' and checkout.dCO_Date< \''.date('Y-1-1',time()).'\'';
        }
        $sql = "select  b.id , b.name , sum(fCO_IncomeMoney) as num
                    from  Center_CheckOut checkout left join  Organization b   on checkout.OrgID = b.id
                    where checkout.OrgID in (select orgid from map_org_wechat) $datesql
              group by b.id , b.name order by sum(fCO_IncomeMoney) desc   ";

        $stmt = $conn->query($sql);
        $salelist = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $row->details= array();
            array_push($salelist, $row);
        }
//        array_push($salelist,$sql);
        return $salelist;
    }

    private function memberchartOp($type){
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        //查询充值情况
        $datesql = '';
        if($type=='2'){
            $datesql = ' and checkout.RechargeDate>= \''.date('Y-m-d',time()). '\' and checkout.RechargeDate< \''.date('Y-m-d',strtotime('+1 day')).'\'';
        }else if($type=='3'){
            $datesql = ' and checkout.RechargeDate>= \''.date('Y-m-1',time()). '\' and checkout.RechargeDate< \''.date('Y-m-d',strtotime('+1 day')).'\'';
        }else if($type=='4'){
            $datesql = ' and checkout.RechargeDate>= \''.date('Y-1-1',time()). '\' and checkout.RechargeDate< \''.date('Y-m-d',strtotime('+1 day')).'\'';
        }else if($type=='5'){
            $datesql = ' and checkout.RechargeDate>= \''.date('Y-m-d',strtotime('-1 day')). '\' and checkout.RechargeDate< \''.date('Y-m-d',time()).'\'';
        }else if($type=='6'){
            $datesql = ' and checkout.RechargeDate>= \''.date('Y-m-1' ,strtotime(date('Y-m-01')) - 86400 ). '\' and checkout.RechargeDate< \''.date('Y-m-1',time()).'\'';
        }else if($type=='7'){
            $datesql = ' and checkout.RechargeDate>= \''.date('Y-1-1',strtotime('-1 year')). '\' and checkout.RechargeDate< \''.date('Y-1-1',time()).'\'';
        }
        $sql = "select  b.id , b.name , sum(RechargeMoney) as num
                    from  center_MemberRecharge checkout left join  Organization b  on checkout.OrgID = b.id
                    where checkout.OrgID in (select orgid from map_org_wechat) and checkout.[type]=1 $datesql
              group by b.id , b.name having sum(RechargeMoney) >0 order by sum(RechargeMoney) desc   ";
        $stmt = $conn->query($sql);
        $memberlist = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $row->details= array();
            array_push($memberlist, $row);
        }

        return $memberlist;
    }

    private function consumechartOp($type){
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        //查询充值情况
        $datesql = '';
        if($type=='2'){
            $datesql = ' and checkout.RechargeDate>= \''.date('Y-m-d',time()). '\' and checkout.RechargeDate< \''.date('Y-m-d',strtotime('+1 day')).'\'';
        }else if($type=='3'){
            $datesql = ' and checkout.RechargeDate>= \''.date('Y-m-1',time()). '\' and checkout.RechargeDate< \''.date('Y-m-d',strtotime('+1 day')).'\'';
        }else if($type=='4'){
            $datesql = ' and checkout.RechargeDate>= \''.date('Y-1-1',time()). '\' and checkout.RechargeDate< \''.date('Y-m-d',strtotime('+1 day')).'\'';
        }else if($type=='5'){
            $datesql = ' and checkout.RechargeDate>= \''.date('Y-m-d',strtotime('-1 day')). '\' and checkout.RechargeDate< \''.date('Y-m-d',time()).'\'';
        }else if($type=='6'){
            $datesql = ' and checkout.RechargeDate>= \''.date('Y-m-1' ,strtotime(date('Y-m-01')) - 86400 ). '\' and checkout.RechargeDate< \''.date('Y-m-1',time()).'\'';
        }else if($type=='7'){
            $datesql = ' and checkout.RechargeDate>= \''.date('Y-1-1',strtotime('-1 year')). '\' and checkout.RechargeDate< \''.date('Y-1-1',time()).'\'';
        }

        $outdatesql = '';
        if($type=='2'){
            $outdatesql = ' and out.dco_date>= \''.date('Y-m-d',time()). '\' and out.dco_date< \''.date('Y-m-d',strtotime('+1 day')).'\'';
        }else if($type=='3'){
            $outdatesql = ' and out.dco_date>= \''.date('Y-m-1',time()). '\' and out.dco_date< \''.date('Y-m-d',strtotime('+1 day')).'\'';
        }else if($type=='4'){
            $outdatesql = ' and out.dco_date>= \''.date('Y-1-1',time()). '\' and out.dco_date< \''.date('Y-m-d',strtotime('+1 day')).'\'';
        }else if($type=='5'){
            $outdatesql = ' and out.dco_date>= \''.date('Y-m-d',strtotime('-1 day')). '\' and out.dco_date< \''.date('Y-m-d',time()).'\'';
        }else if($type=='6'){
            $outdatesql = ' and out.dco_date>= \''.date('Y-m-1' ,strtotime(date('Y-m-01')) - 86400 ). '\' and out.dco_date< \''.date('Y-m-1',time()).'\'';
        }else if($type=='7'){
            $outdatesql = ' and out.dco_date>= \''.date('Y-1-1',strtotime('-1 year')). '\' and out.dco_date< \''.date('Y-1-1',time()).'\'';
        }
        $sql = "  select id , name ,sum(num) as num
                  from (
                    select  b.id ,
                    b.name , -RechargeMoney as num
                    from  center_MemberRecharge checkout left join  Organization b  on checkout.OrgID = b.id
                    where checkout.OrgID in (select orgid from map_org_wechat) and checkout.[type]=2 $datesql
                  union all
                    select b.id , b.name , fCO_FactMoney as num
                    from center_checkout out left join  Organization b  on out.OrgID = b.id
                    where out.orgid in(select orgid from map_org_wechat) and isnull(smemberid , '') <> '' $outdatesql
                    ) datatable
                group by id ,name
                order by sum(num) desc
               ";
        $stmt = $conn->query($sql);

        $consumelist = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $row->details= array();
            array_push($consumelist, $row);
        }
//        array_push($consumelist, $sql);

        return $consumelist;
    }

    //查询会员分布情况
    private function membernumchartOp($type){
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        $datesql = '';
//        if($type=='2'){
//            $datesql = ' and checkout.RechargeDate>= \''.date('Y-m-d',time()). '\' and checkout.RechargeDate< \''.date('Y-m-d',strtotime('+1 day')).'\'';
//        }else if($type=='3'){
//            $datesql = ' and checkout.RechargeDate>= \''.date('Y-m-1',time()). '\' and checkout.RechargeDate< \''.date('Y-m-d',strtotime('+1 day')).'\'';
//        }else if($type=='4'){
//            $datesql = ' and checkout.RechargeDate>= \''.date('Y-1-1',time()). '\' and checkout.RechargeDate< \''.date('Y-m-d',strtotime('+1 day')).'\'';
//        }else if($type=='5'){
//            $datesql = ' and checkout.RechargeDate>= \''.date('Y-m-d',strtotime('-1 day')). '\' and checkout.RechargeDate< \''.date('Y-m-d',time()).'\'';
//        }else if($type=='6'){
//            $datesql = ' and checkout.RechargeDate>= \''.date('Y-m-d',strtotime('-1 month')). '\' and checkout.RechargeDate< \''.date('Y-m-1',time()).'\'';
//        }else if($type=='7'){
//            $datesql = ' and checkout.RechargeDate>= \''.date('Y-m-d',strtotime('-1 year')). '\' and checkout.RechargeDate< \''.date('Y-1-1',time()).'\'';
//        }
        $sql = "select  b.id , b.name , count(1) as num
                    from  shopnc_member member left join  Organization b  on member.createOrgID = b.id
                    where member.createOrgID in (select orgid from map_org_wechat)   $datesql
              group by b.id , b.name   order by count(1) desc   ";
        $stmt = $conn->query($sql);
        $membernumlist = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $row->details= array();
            array_push($membernumlist, $row);
        }
        return $membernumlist;
    }

    private function spotchartOp($type){
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        //查询回访情况
        $datesql = '';
        if($type=='2'){
            $datesql = ' and checkout.checkdate>= \''.date('Y-m-d',time()). '\' and checkout.checkdate< \''.date('Y-m-d',strtotime('+1 day')).'\'';
        }else if($type=='3'){
            $datesql = ' and checkout.checkdate>= \''.date('Y-m-1',time()). '\' and checkout.checkdate< \''.date('Y-m-d',strtotime('+1 day')).'\'';
        }else if($type=='4'){
            $datesql = ' and checkout.checkdate>= \''.date('Y-1-1',time()). '\' and checkout.checkdate< \''.date('Y-m-d',strtotime('+1 day')).'\'';
        }else if($type=='5'){
            $datesql = ' and checkout.checkdate>= \''.date('Y-m-d',strtotime('-1 day')). '\' and checkout.checkdate< \''.date('Y-m-d',time()).'\'';
        }else if($type=='6'){
            $datesql = ' and checkout.checkdate>= \''.date('Y-m-1' ,strtotime(date('Y-m-01')) - 86400 ). '\' and checkout.checkdate< \''.date('Y-m-1',time()).'\'';
        }else if($type=='7'){
            $datesql = ' and checkout.checkdate>= \''.date('Y-1-1',strtotime('-1 year')). '\' and checkout.checkdate< \''.date('Y-1-1',time()).'\'';
        }
        $sql = "select  b.id , b.name , count(1) as num
                    from  spotcheck_main checkout left join  Organization b  on checkout.OrgID = b.id
                    where checkout.OrgID in (select orgid from map_org_wechat) and checktype like '5%' $datesql
              group by b.id , b.name having count(1)  >0 order by count(1)  desc   ";
        $stmt = $conn->query($sql);
        $spotlist = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $row->details= array();
            array_push($spotlist, $row);
            $row->sql = $sql;
        }
        return $spotlist;
    }

    private function healthfilechartOp($type){
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        //查询健康档案情况
        $datesql = '';
        if($type=='2'){
            $datesql = ' and hf.InputDate>= \''.date('Y-m-d',time()). '\' and hf.InputDate< \''.date('Y-m-d',strtotime('+1 day')).'\'';
        }else if($type=='3'){
            $datesql = ' and hf.InputDate>= \''.date('Y-m-1',time()). '\' and hf.InputDate< \''.date('Y-m-d',strtotime('+1 day')).'\'';
        }else if($type=='4'){
            $datesql = ' and hf.InputDate>= \''.date('Y-1-1',time()). '\' and hf.InputDate< \''.date('Y-m-d',strtotime('+1 day')).'\'';
        }else if($type=='5'){
            $datesql = ' and hf.InputDate>= \''.date('Y-m-d',strtotime('-1 day')). '\' and hf.InputDate< \''.date('Y-m-d',time()).'\'';
        }else if($type=='6'){
            $datesql = ' and hf.InputDate>= \''.date('Y-m-1' ,strtotime(date('Y-m-01')) - 86400 ). '\' and hf.InputDate< \''.date('Y-m-1',time()).'\'';
        }else if($type=='7'){
            $datesql = ' and hf.InputDate>= \''.date('Y-1-1',strtotime('-1 year')). '\' and hf.InputDate< \''.date('Y-1-1',time()).'\'';
        }
        $sql = "select  b.id , b.name , count(1) as num
                    from  healthfile hf , sam_taxempcode checkout left join  Organization b  on checkout.org_id = b.id
                    where hf.inputpersonid = checkout.loginname and checkout.org_id in (select orgid from map_org_wechat) $datesql
              group by b.id , b.name having count(1)  >0 order by count(1)  desc  ";
        $stmt = $conn->query($sql);
        $healthfilelist = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $row->details= array();
            array_push($healthfilelist, $row);
        }

        return $healthfilelist;
    }

    private function healthfilespotchartOp($type){
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        //查询健康档案回访情况
        $datesql = '';
        if($type=='2'){
            $datesql = ' and checkout.checkdate>= \''.date('Y-m-d',time()). '\' and checkout.checkdate< \''.date('Y-m-d',strtotime('+1 day')).'\'';
        }else if($type=='3'){
            $datesql = ' and checkout.checkdate>= \''.date('Y-m-1',time()). '\' and checkout.checkdate< \''.date('Y-m-d',strtotime('+1 day')).'\'';
        }else if($type=='4'){
            $datesql = ' and checkout.checkdate>= \''.date('Y-1-1',time()). '\' and checkout.checkdate< \''.date('Y-m-d',strtotime('+1 day')).'\'';
        }else if($type=='5'){
            $datesql = ' and checkout.checkdate>= \''.date('Y-m-d',strtotime('-1 day')). '\' and checkout.checkdate< \''.date('Y-m-d',time()).'\'';
        }else if($type=='6'){
            $datesql = ' and checkout.checkdate>= \''.date('Y-m-1' ,strtotime(date('Y-m-01')) - 86400 ). '\' and checkout.checkdate< \''.date('Y-m-1',time()).'\'';
        }else if($type=='7'){
            $datesql = ' and checkout.checkdate>= \''.date('Y-1-1',strtotime('-1 year')). '\' and checkout.checkdate< \''.date('Y-1-1',time()).'\'';
        }
        $sql = "select  b.id , b.name , count(1) as num
                    from  spotcheck_main checkout left join  Organization b  on checkout.OrgID = b.id
                    where checkout.OrgID in (select orgid from map_org_wechat) and checktype like '0%' $datesql
              group by b.id , b.name having count(1)  >0 order by count(1)  desc   ";
        $stmt = $conn->query($sql);
        $healthfilespotlist = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $row->details= array();
            array_push($healthfilespotlist, $row);
        }

        return $healthfilespotlist;
    }

    private  function healthbusinesschartOp($type){
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        //查询业务开展数量情况
        $datesql = '';
        if($type=='2'){
            $datesql = ' and a.InputDate>= \''.date('Y-m-d',time()). '\' and a.InputDate< \''.date('Y-m-d',strtotime('+1 day')).'\'';
        }else if($type=='3'){
            $datesql = ' and a.InputDate>= \''.date('Y-m-1',time()). '\' and a.InputDate< \''.date('Y-m-d',strtotime('+1 day')).'\'';
        }else if($type=='4'){
            $datesql = ' and a.InputDate>= \''.date('Y-1-1',time()). '\' and a.InputDate< \''.date('Y-m-d',strtotime('+1 day')).'\'';
        }else if($type=='5'){
            $datesql = ' and a.InputDate>= \''.date('Y-m-d',strtotime('-1 day')). '\' and a.InputDate< \''.date('Y-m-d',time()).'\'';
        }else if($type=='6'){
            $datesql = ' and a.InputDate>= \''.date('Y-m-1' ,strtotime(date('Y-m-01')) - 86400 ). '\' and a.InputDate< \''.date('Y-m-1',time()).'\'';
        }else if($type=='7'){
            $datesql = ' and a.InputDate>= \''.date('Y-1-1',strtotime('-1 year')). '\' and a.InputDate< \''.date('Y-1-1',time()).'\'';
        }
        $sql = "select id , name ,sum(num) num
                from(
                select c.id , c.name ,count(1) num from MedicalExam  a  , sam_taxempcode b ,   Organization c
                where a.InputPersonID = b.loginname and   b.org_id = c.id  and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by c.id , c.name
                union all
                select c.id , c.name ,count(1) num from HealthFileMaternal  a  , sam_taxempcode b ,   Organization c
                where a.InputPersonID = b.loginname and   b.org_id = c.id  and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by c.id , c.name
                union all
                select c.id , c.name ,count(1) num from FirstVistBeforeBorn  a  , sam_taxempcode b ,   Organization c
                where a.InputPersonID = b.loginname and   b.org_id = c.id  and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by c.id , c.name
                union all
                select c.id , c.name ,count(1) num from VisitBeforeBorn  a  , sam_taxempcode b ,   Organization c
                where a.InputPersonID = b.loginname and   b.org_id = c.id  and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by c.id , c.name
                union all
                select c.id , c.name ,count(1) num from VisitAfterBorn  a  , sam_taxempcode b ,   Organization c
                where a.InputPersonID = b.loginname and   b.org_id = c.id  and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by c.id , c.name
                union all
                select c.id , c.name ,count(1) num from HealthFileChildren  a  , sam_taxempcode b ,   Organization c
                where a.InputPersonID = b.loginname and   b.org_id = c.id  and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by c.id , c.name
                union all
                select c.id , c.name ,count(1) num from ChildrenMediExam  a  , sam_taxempcode b ,   Organization c
                where a.InputPersonID = b.loginname and   b.org_id = c.id  and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by c.id , c.name
                union all
                select c.id , c.name ,count(1) num from ChildrenMediExam3_6  a  , sam_taxempcode b ,   Organization c
                where a.InputPersonID = b.loginname and   b.org_id = c.id  and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by c.id , c.name
                union all
                select c.id , c.name ,count(1) num from HypertensionVisit  a  , sam_taxempcode b ,   Organization c
                where a.InputPersonID = b.loginname and   b.org_id = c.id  and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by c.id , c.name
                union all
                select c.id , c.name ,count(1) num from DiabetesVisit  a  , sam_taxempcode b ,   Organization c
                where a.InputPersonID = b.loginname and   b.org_id = c.id  and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by c.id , c.name
                union all
                select c.id , c.name ,count(1) num from FuriousVisit  a  , sam_taxempcode b ,   Organization c
                where a.InputPersonID = b.loginname and   b.org_id = c.id  and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by c.id , c.name
                ) uniontable
                group by id ,name
                order by sum(num) desc   ";
        $stmt = $conn->query($sql);
        $healthbusinesslist = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $row->details= array();
            array_push($healthbusinesslist, $row);
        }

        return $healthbusinesslist;
    }

    private function prescriptionchartOp($type){
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        //查询健康档案情况
//        $datesql = '';
//        if($type=='2'){
//            $datesql = ' and a.ClinicDate>= \''.date('Y-m-d',time()). '\' and a.ClinicDate< \''.date('Y-m-d',strtotime('+1 day')).'\'';
//        }else if($type=='3'){
//            $datesql = ' and a.ClinicDate>= \''.date('Y-m-1',time()). '\' and a.ClinicDate< \''.date('Y-m-d',strtotime('+1 day')).'\'';
//        }else if($type=='4'){
//            $datesql = ' and a.ClinicDate>= \''.date('Y-1-1',time()). '\' and a.ClinicDate< \''.date('Y-m-d',strtotime('+1 day')).'\'';
//        }else if($type=='5'){
//            $datesql = ' and a.ClinicDate>= \''.date('Y-m-d',strtotime('-1 day')). '\' and a.ClinicDate< \''.date('Y-m-d',time()).'\'';
//        }else if($type=='6'){
//            $datesql = ' and a.ClinicDate>= \''.date('Y-m-1' ,strtotime(date('Y-m-01')) - 86400 ). '\' and a.ClinicDate< \''.date('Y-m-1',time()).'\'';
//        }else if($type=='7'){
//            $datesql = ' and a.ClinicDate>= \''.date('Y-1-1',strtotime('-1 year')). '\' and a.ClinicDate< \''.date('Y-1-1',time()).'\'';
//        }
//        $sql = "select  b.id , b.name , count(1) as num
//                    from  Center_ClinicLog a   left join  Organization b  on a.orgid = b.id
//                    where  a.orgid in (select orgid from map_org_wechat) $datesql
//              group by b.id , b.name having count(1)  >0 order by count(1)  desc  ";
//        $stmt = $conn->query($sql);
		if($type == null || $type == ''){
			$type = '-1';
		}
        $sql = "exec pXPrescriptionChart $type;";
		$stmt = $conn->prepare($sql);
		$stmt->execute();
        $healthfilelist = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $row->details= array();
            array_push($healthfilelist, $row);
		}
        return $healthfilelist;
    }



    public function chartOp(){
        $statistics = array();
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        //查询社区数量
         $statistics['orgdata'] = $this->orgchartOp('1');
        //查询销售情况
        $statistics['saledata'] = $this->salechartOp('1');
        //查询充值情况
        $statistics['memberdata'] = $this->memberchartOp('1');
        //查询消费情况
        $statistics['consumedata'] = $this->consumechartOp('1');
        //查询回访情况
        $statistics['spotdata'] = $this->spotchartOp('1');
        //查询健康档案情况
        $statistics['healthdata'] = $this->healthfilechartOp('1');
        //查询健康档案回访情况
        $statistics['healthspotdata'] = $this->healthfilespotchartOp('1');
        //查询业务开展数量情况
        $statistics['healthbusinessdata'] = $this->healthbusinesschartOp('1');
        //查询会员分布情况
        $statistics['membernumber'] = $this->membernumchartOp('1');
        //查询门诊人次
        $statistics['prescriptiondata'] = $this->prescriptionchartOp('1');
        echo json_encode($statistics);
        exit;
    }

    public function chartdetailOp(){
        $func = $_GET['opt'].'chartOp';
        echo json_encode($this->$func($_GET['type']));
    }

    public function timelineOp(){
    	if (isset($_GET['loaddetailinfo']) and $_GET['loaddetailinfo'] == '1') {
    		$id = '';
    		$type = '';
    		if (isset($_GET['businessId']) and $_GET['businessId'] != '') {
    			$id = $_GET['businessId'];
    		}
    		if (isset($_GET['businessType']) and $_GET['businessType'] != '') {
    			$type = $_GET['businessType'];
    		}
    		$conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
	    	$sql = "exec pXMemberHealthFileServiceIndexDetailInfo '". $id ."','" . $type . "';";
			$stmt = $conn->prepare($sql);
			$stmt->execute();
			$serviceIndexCount_list = array();
			while ( $row = $stmt->fetchObject () ) {
				array_push ($serviceIndexCount_list, $row );
			}
			$standarddisplayhtml = '';
			$keyvaluehtml = '';
			if($type == '1'){
				$standarddisplayhtml = '<div class="display_medialexam standard-display"><table class="form_tbl">	<tr>		<td>姓名</td>		<td colspan="2">${Name}</td>		<td>编号：</td>		<td colspan="4">${FileNo}</td>	</tr>	<tr>		<td>体检日期</td>		<td colspan="2">${ExamDate}</td>		<td>责任医生</td>		<td colspan="4">${Doctor}</td>	</tr>	<tr>		<td>内容</td>		<td colspan="7">检 查 项 目</td>	</tr>	<tr>		<td>症状</td>		<td colspan="7">${ExamSymptom}</td>	</tr>	<tr>		<td rowspan="9">一般状况</td>		<td>体温</td>		<td>${General01}℃</td>		<td colspan="2">脉率</td>		<td colspan="3">${General02}次/分钟</td>	</tr>	<tr>		<td rowspan="2">呼吸频率</td>		<td rowspan="2">${General03}次/分钟</td>		<td rowspan="2">血压</td>		<td>左侧</td>		<td colspan="3">${General04}/${General05}mmHg</td>	</tr>	<tr>		<td>右侧</td>		<td colspan="3">${General06}/${General07}mmHg</td>	</tr>	<tr>		<td>身高</td>		<td>${General08}cm</td>		<td>体重</td>		<td colspan="4">${General09}kg</td>	</tr>	<tr>		<td>腰围</td>		<td>${General10}cm</td>		<td>体质指数</td>		<td colspan="4">${General11}</td>	</tr>	<tr>		<td colspan="2">老年人健康状态自我评估*</td>		<td colspan="5">${OldManHealthEstimate}</td>	</tr>	<tr>		<td colspan="2">老年人生活自理能力自我评估*</td>		<td colspan="5">${OldManLifeEstimate}</td>	</tr>	<tr>		<td colspan="1">老年人认知功能</td>		<td colspan="2">${General14}</td>		<td>简易智力状态检查</td>		<td colspan="3">${General15}</td>	</tr>	<tr>		<td>老年人情感状态</td>		<td colspan="2">${General16}</td>		<td>老年人抑郁评分检查</td>		<td colspan="3">${General17}</td>	</tr>	<tr>		<td rowspan="18">生活方式</td>		<td rowspan="3">体育锻炼</td>		<td>锻炼频率</td>		<td colspan="5">${Life01}</td>	</tr>	<tr>		<td>每次锻炼时间</td>		<td>${Life02}分钟</td>		<td>坚持锻炼时间</td>		<td colspan="3">${Life03}年</td>	</tr>	<tr>		<td>锻炼方式</td>		<td colspan="5">${Life04}</td>	</tr>	<tr>		<td>饮食习惯</td>		<td colspan="6">${EatHabit}</td>	</tr>	<tr>		<td rowspan="3">吸烟情况</td>		<td>吸烟状况</td>		<td colspan="5">${Life06}</td>	</tr>	<tr>		<td>日吸烟量</td>		<td colspan="5"><span>平均</span>${Life07}支</td>	</tr>	<tr>		<td>开始吸烟年龄</td>		<td>${Life08}岁</td>		<td>戒烟年龄</td>		<td colspan="3">${Life09}岁</td>	</tr>	<tr>		<td rowspan="5">饮酒情况</td>		<td>饮酒频率</td>		<td colspan="5">${Life10}</td>	</tr>	<tr>		<td>日饮酒量</td>		<td colspan="5">平均${Life11}两</td>	</tr>	<tr>		<td>是否戒酒</td>		<td>${Life12}</td>		<td>戒酒年龄</td>		<td colspan="3">${Life13}岁</td>	</tr>	<tr>		<td>开始饮酒年龄</td>		<td>${Life14}岁</td>		<td colspan="2">近一年内是否曾醉酒</td>		<td colspan=2>${Life15}</td>	</tr>	<tr>		<td>饮酒种类</td>		<td colspan="5">${DrinkHabit}</td>	</tr>	<tr>		<td rowspan="6">职业病危害因素<br>接触史		</td>		<td colspan="6">${Life17}${Life18}{Life19}</td>	</tr>	<tr>		<td rowspan="5">毒物种类</td>		<td>粉尘:${Life20}</td>		<!-- <td id="life20"></td> -->		<td>防护措施</td>		<td colspan=2>${Life21}</td>	</tr>	<tr>		<td>放射物质:${Life22}</td>		<!-- <td id="life22"></td> -->		<td>防护措施</td>		<td colspan=2>${Life23}</td>	</tr>	<tr>		<td>物理因素:${Life24}</td>		<!-- <td id="life24"></td> -->		<td>防护措施</td>		<td colspan=2>${Life25}</td>	</tr>	<tr>		<td>化学物质:${Life26}</td>		<!-- <td id="life26"></td> -->		<td>防护措施</td>		<td colspan=2>${Life27}</td>	</tr>	<tr>		<td>其他:${Life28}</td>		<!-- <td id="life28"></td> -->		<td>防护措施</td>		<td colspan=2>${Life29}</td>	</tr>	<tr>		<td rowspan="6" style="border-bottom: 1px solid #000;">脏器功能</td>		<td rowspan="3">口腔</td>		<td>口唇</td>		<td colspan="5">${Viscera01}</td>	</tr>	<tr>		<td>齿列</td>		<td>${Viscera02}</td>		<td colspan="2">齿列描述</td>		<td colspan=2>			<table cellpadding="0" cellspacing="0"				style="margin: 0px; padding: 0px; border: 0px; border-collapse: collapse; height: 100%;">				<tr>					<td>${Viscera02Description}</td>					<td>${Viscera02Description1}</td>				</tr>				<tr>					<td>${Viscera02Description2}</td>					<td>${Viscera02Description3}</td>				</tr>			</table>		</td>	</tr>	<tr>		<td>咽部</td>		<td colspan="5">${Viscera03}</td>	</tr>	<tr>		<td>视力</td>		<td colspan="6">左眼${Viscera04}右眼${Viscera05} <span>(矫正视力：左眼</span>${Viscera06}右眼${Viscera07})		</td>	</tr>	<tr>		<td>听力</td>		<td colspan="6">${Viscera08}</td>	</tr>	<tr>		<td style="border-bottom: 1px solid #000;">运动能力</td>		<td style="border-bottom: 1px solid #000;" colspan="6">${Viscera09}</td>	</tr>	<tr>		<td rowspan="24" style="border-bottom: 1px solid #000;">查体</td>		<td>眼底*<br /> <font size="2px" color="red">（空代表未测）</font></td>		<td colspan="5">${Exam29}</td>	</tr>	<tr>		<td>皮肤</td>		<td colspan="5">${Body01}</td>	</tr>	<tr>		<td>巩膜</td>		<td colspan="5">${Body02}</td>	</tr>	<tr>		<td>淋巴结</td>		<td colspan="5">${Body03}</td>	</tr>	<tr>		<td rowspan="3">肺</td>		<td>桶状胸</td>		<td colspan="4">${Body04}</td>	</tr>	<tr>		<td>呼吸音</td>		<td colspan="4">${Body05}</td>	</tr>	<tr>		<td>罗音</td>		<td colspan="4">${Body06}</td>	</tr>	<tr>		<td rowspan="2">心脏</td>		<td>心率</td>		<td>${Body07}次/分钟</td>		<td>心律</td>		<td colspan=2>${Body08}</td>	</tr>	<tr>		<td>杂音</td>		<td colspan="4">${Body09}</td>	</tr>	<tr>		<td rowspan="5">腹部</td>		<td>压痛</td>		<td colspan="4">${Body10}</td>	</tr>	<tr>		<td>包块</td>		<td colspan="4">${Body12}</td>	</tr>	<tr>		<td>肝大</td>		<td colspan="4">${Body13}</td>	</tr>	<tr>		<td>脾大</td>		<td colspan="4">${Body14}</td>	</tr>	<tr>		<td>移动性浊音</td>		<td colspan="4">${Body15}</td>	</tr>	<tr>		<td>下肢水肿</td>		<td colspan="5">${Body16}</td>	</tr>	<tr>		<td>足背动脉博动</td>		<td colspan="5">${Body17}</td>	</tr>	<tr>		<td>肛门指诊*<br /> <font size="2px" color="red">（空代表未测）</font></td>		<td colspan="5">${Body18}</td>	</tr>	<tr>		<td>乳腺*</td>		<td colspan="5">${Galactophore}</td>	</tr>	<tr>		<td rowspan="5">妇科</td>		<td>外阴*</td>		<td colspan="4">${Body20}</td>	</tr>	<tr>		<td>阴道*</td>		<td colspan="4">${Body21}</td>	</tr>	<tr>		<td>宫颈*</td>		<td colspan="4">${Body22}</td>	</tr>	<tr>		<td>宫体*</td>		<td colspan="4">${Body23}</td>	</tr>	<tr>		<td>附件*</td>		<td colspan="4">${Body24}</td>	</tr>	<tr>		<td style="border-bottom: 1px solid #000;">其它*</td>		<td style="border-bottom: 1px solid #000;" colspan="5">${Body25}</td>	</tr>	<tr>		<td rowspan="30" style="border-bottom: 1px solid #000;">辅助检查</td>		<td rowspan="2">血常规*</td>		<td>血红蛋白<span>${Exam03}</span>g/L		</td>		<td>白细胞<span>${Exam04}</span>×10<sup>9</sup>/L		</td>		<td colspan="3">血小板<span>${Exam05}</span>×10<sup>9</sup>/L		</td>	</tr>	<tr>		<td colspan="5">其它<span>${Exam06}</span></td>	</tr>	<tr>		<td rowspan="2">尿常规*</td>		<td>尿蛋白<span>${Exam07}</span></td>		<td>尿糖<span>${Exam08}</span></td>		<td colspan="3">尿酮体<span>${Exam09}</span></td>	</tr>	<tr>		<td>尿潜血<span>${Exam10}</span></td>		<td colspan="4">其它<span>${Exam11}</span></td>	</tr>	<tr>		<td>空腹血糖*</td>		<td colspan="5"><span>${Exam01}</span>mmol/L 或 (餐后)<span>${Exam02}</span>mmol/L</td>	</tr>	<tr>		<td>心电图*<br /> <font size="2px" color="red">（空代表未测）</font></td>		<td colspan="5">${Exam30}</td>	</tr>	<tr>		<td>尿微量白蛋白*</td>		<td colspan="5"><span>${Exam12}</span></td>	</tr>	<tr>		<td>大便潜血*<br /> <font size="2px" color="red">（空代表未测）</font></td>		<td colspan="5"><span>${Exam13}</span></td>	</tr>	<tr>		<td>糖化血红蛋白*</td>		<td colspan="5"><span>${Exam27}</span></td>	</tr>	<tr>		<td>乙型肝炎表面抗原*<br /> <font size="2px" color="red">（空代表未测）</font></td>		<td colspan="5"><span>${Exam28}</span></td>	</tr>	<tr>		<td rowspan="2">肝功能*</td>		<td>血清谷丙转氨酶<span>${Exam14}</span>U/L		</td>		<td>血清谷草转氨酶<span>${Exam15}</span>U/L		</td>		<td colspan="4">白蛋白<span>${Exam16}</span>g/L		</td>	</tr>	<tr>		<td>总胆红素<span>${Exam17}</span>µmol		</td>		<td colspan="4">结合胆红素<span>${Exam18}</span>µmol		</td>	</tr>	<tr>		<td rowspan="2">肾功能*</td>		<td>血清肌酐<span>${Exam19}</span>µmol		</td>		<td colspan="4">血尿素氮<span>${Exam20}</span>mmol		</td>	</tr>	<tr>		<td>血钾浓度<span>${Exam21}</span>mmol		</td>		<td colspan="4">血纳浓度<span>${Exam22}</span>mmol		</td>	</tr>	<tr>		<td rowspan="3">血脂*</td>		<td>总胆固醇<span>${Exam23}</span>mmol/L		</td>		<td colspan="4">甘油三酯<span>${Exam24}</span>mmol/L		</td>	</tr>	<tr>		<td colspan="5">血清低密度脂蛋白胆固醇<span>${Exam25}</span>mmol/L		</td>	</tr>	<tr>		<td colspan="6">血清高密度脂蛋白胆固醇<span>${Exam26}</span>mmol/L		</td>	</tr>	<tr>		<td>胸片X线片*<br /> <font size="2px" color="red">（空代表未测）</font></td>		<td colspan="5">${Exam31}</td>	</tr>	<tr>		<td>B超*<br /> <font size="2px" color="red">（空代表未测）</font></td>		<td colspan="5">${Exam32}</td>	</tr>	<tr>		<td>宫颈涂片*<br /> <font size="2px" color="red">（空代表未测）</font></td>		<td colspan="5">${Exam33}</td>	</tr>	<tr>		<td>其他*</td>		<td colspan="5">${Exam34}</td>	</tr>	<tr>		<td rowspan="9" style="border-bottom: 1px solid #000;">中医体质辨识*<br />			<font size="2px" color="red">（空代表未测）</font></td>		<td>平和质</td>		<td colspan="5"><span>${CHN01}</span></td>	</tr>	<tr>		<td>气虚质</td>		<td colspan="5"><span>${CHN02}</span></td>	</tr>	<tr>		<td>阳虚质</td>		<td colspan="5"><span>${CHN03}</span></td>	</tr>	<tr>		<td>阴虚质</td>		<td colspan="5"><span>${CHN04}</span></td>	</tr>	<tr>		<td>痰湿质</td>		<td colspan="5"><span>${CHN05}</span></td>	</tr>	<tr>		<td>湿热质</td>		<td colspan="5"><span>${CHN06}</span></td>	</tr>	<tr>		<td>血瘀质</td>		<td colspan="5"><span>${CHN07}</span></td>	</tr>	<tr>		<td>气郁质</td>		<td colspan="5"><span>${CHN08}</span></td>	</tr>	<tr>		<td style="border-bottom: 1px solid #000;">特秉质</td>		<td style="border-bottom: 1px solid #000;" colspan="5"><span>${CHN09}</span></td>	</tr>	<tr>		<td rowspan="7">现在主要健康问题</td>		<td>脑血管疾病</td>		<td colspan="5">${HarnsSick}</td>	</tr>	<tr>		<td>肾脏疾病</td>		<td colspan="5">${KidneySick}</td>	</tr>	<tr>		<td>心脏疾病</td>		<td colspan="5">${HeartSick}</td>	</tr>	<tr>		<td>血管疾病</td>		<td colspan="5">${VasSick}</td>	</tr>	<tr>		<td>眼部疾病</td>		<td colspan="5">${EyeSick}</td>	</tr>	<tr>		<td>神经系统疾病</td>		<td colspan="5">${Problem06}</td>	</tr>	<tr>		<td>其它系统疾病</td>		<td colspan="5">${Problem07}</td>	</tr>	<tr>		<td>住院治疗情况</td>		<td colspan="6"><span>${Hospitalization}</span></td>	</tr>	<tr>		<td>主要用药情况</td>		<td colspan="6"><span>${ExamMedication}</span></td>	</tr>	<tr>		<td>非免疫规划预防接种史</td>		<td colspan="6"><span>${VaccinateHistory}</span></td>	</tr>	<tr>		<td class="hearder" rowspan="5">健康评价</td>		<td colspan="6">${Judge01}</td>	</tr>	<tr>		<td colspan="6">异常1<span>${Judge02}</span></td>	</tr>	<tr>		<td colspan="6">异常2<span>${Judge03}</span></td>	</tr>	<tr>		<td colspan="6">异常3<span>${Judge04}</span></td>	</tr>	<tr>		<td colspan="6">异常4<span>${Judge05}</span></td>	</tr>	<tr>		<td>健康指导</td>		<td colspan="6"><span>${HealthDirect}</span></td>	</tr>	<tr>		<td style="border-bottom: 1px solid #000;">危险因素控制</td>		<td style="border-bottom: 1px solid #000;" colspan="6"><span>${DangerControl}</span><span>${DangerControlOther1}</span><span>${DangerControlOther2}</span><span>${DangerControlOther3}</span></td>	</tr></table></div>';
				$keyvaluehtml = '<div class="display_keyvalue_medialexam key-value-display">	<table class="form_tbl">		<tr>			<td id="姓名">姓名</td>			<td>${Name}</td>		</tr>		<tr>			<td id="编号">编号</td>			<td>${FileNo}</td>		</tr>		<tr>			<td id="体检日期">体检日期</td>			<td>${ExamDate}</td>		</tr>		<tr>			<td id="责任医生">责任医生</td>			<td>${Doctor}</td>		</tr>		<tr>			<td id="症状">症状</td>			<td>${ExamSymptom}</td>		</tr>		<tr>			<td id="体温">体温</td>			<td>${General01}</td>		</tr>		<tr>			<td id="脉率">脉率</td>			<td>${General02}次/分钟</td>		</tr>		<tr>			<td id="呼吸频率">呼吸频率</td>			<td>${General03}次/分钟</td>		</tr>		<tr>			<td id="血压">血压</td>			<td>左侧：${General04}/${General05}mmHg，右侧：${General06}/${General07}mmHg</td>		</tr>		<tr>			<td id="身高">身高</td>			<td>${General08}cm</td>		</tr>		<tr>			<td id="体重">体重</td>			<td>${General09}kg</td>		</tr>		<tr>			<td id="腰围">腰围</td>			<td>${General10}cm</td>		</tr>		<tr>			<td id="体质指数">体质指数</td>			<td>${General11}</td>		</tr>		<tr>			<td id="老年人健康状态自我评估">老年人健康状态自我评估*</td>			<td>${OldManHealthEstimate}</td>		</tr>		<tr>			<td id="老年人生活自理能力自我评估">老年人生活自理能力自我评估*</td>			<td>${OldManLifeEstimate}</td>		</tr>		<tr>			<td id="老年人认知功能">老年人认知功能</td>			<td>${General14}</td>		</tr>		<tr>			<td id="简易智力状态检查">简易智力状态检查</td>			<td>${General15}</td>		</tr>		<tr>			<td id="老年人情感状态">老年人情感状态</td>			<td>${General16}</td>		</tr>		<tr>			<td id="老年人抑郁评分检查">老年人抑郁评分检查</td>			<td>${General17}</td>		</tr>		<tr>			<td id="锻炼频率">锻炼频率</td>			<td>${Life01}</td>		</tr>		<tr>			<td id="每次锻炼时间">每次锻炼时间</td>			<td>${Life02}分钟</td>		</tr>		<tr>			<td id="坚持锻炼时间">坚持锻炼时间</td>			<td>${Life03}年</td>		</tr>		<tr>			<td id="锻炼方式">锻炼方式</td>			<td>${Life04}</td>		</tr>		<tr>			<td id="饮食习惯">饮食习惯</td>			<td>${EatHabit}</td>		</tr>		<tr>			<td id="吸烟状况">吸烟状况</td>			<td>${Life06}</td>		</tr>		<tr>			<td id="日吸烟量">日吸烟量</td>			<td><span>平均</span>${Life07}支</td>		</tr>		<tr>			<td id="开始吸烟年龄">开始吸烟年龄</td>			<td>${Life08}岁</td>		</tr>		<tr>			<td id="戒烟年龄">戒烟年龄</td>			<td>${Life09}岁</td>		</tr>		<tr>			<td id="饮酒频率">饮酒频率</td>			<td>${Life10}</td>		</tr>		<tr>			<td id="日饮酒量">日饮酒量</td>			<td>平均${Life11}两</td>		</tr>		<tr>			<td id="是否戒酒">是否戒酒</td>			<td>${Life12}</td>		</tr>		<tr>			<td id="戒酒年龄">戒酒年龄</td>			<td>${Life13}岁</td>		</tr>		<tr>			<td id="开始饮酒年龄">开始饮酒年龄</td>			<td>${Life14}岁</td>		</tr>		<tr>			<td id="近一年内是否曾醉酒">近一年内是否曾醉酒</td>			<td>${Life15}</td>		</tr>		<tr>			<td id="饮酒种类">饮酒种类</td>			<td>${DrinkHabit}</td>		</tr>		<tr>			<td id="职业病危害因素接触史">职业病危害因素接触史</td>			<td>${Life17}${Life18}{Life19}</td>		</tr>		<tr>			<td id="粉尘">粉尘</td>			<td>${Life20};防护措施:${Life21}</td>		</tr>		<tr>			<td id="放射物质">放射物质</td>			<td>${Life22};防护措施:${Life23}</td>		</tr>		<tr>			<td id="物理因素">物理因素</td>			<td>${Life24};防护措施:${Life25}</td>		</tr>		<tr>			<td id="化学物质">化学物质</td>			<td>${Life26};防护措施:${Life27}</td>		</tr>		<tr>			<td id="毒物种类其他">毒物种类其他</td>			<td>${Life28};防护措施:${Life29}</td>		</tr>		<tr>			<td id="口唇">口唇</td>			<td>${Viscera01}</td>		</tr>		<tr>			<td id="齿列">齿列</td>			<td>${Viscera02}</td>		</tr>		<tr>			<td id="齿列描述">齿列描述</td>			<td>上:${Viscera02Description},右:${Viscera02Description1},下:${Viscera02Description2},左:${Viscera02Description3}			</td>		</tr>		<tr>			<td id="咽部">咽部</td>			<td>${Viscera03}</td>		</tr>		<tr>			<td id="视力">视力</td>			<td>左眼${Viscera04}右眼${Viscera05} <span>(矫正视力：左眼</span>${Viscera06}右眼${Viscera07})			</td>		</tr>		<tr>			<td id="听力">听力</td>			<td>${Viscera08}</td>		</tr>		<tr>			<td id="运动能力">运动能力</td>			<td>${Viscera09}</td>		</tr>		<tr>			<td id="眼底">眼底*<br /> <font size="2px" color="red">（空代表未测）</font></td>			<td>${Exam29}</td>		</tr>		<tr>			<td id="皮肤">皮肤</td>			<td>${Body01}</td>		</tr>		<tr>			<td id="巩膜">巩膜</td>			<td>${Body02}</td>		</tr>		<tr>			<td id="淋巴结">淋巴结</td>			<td>${Body03}</td>		</tr>		<tr>			<td id="桶状胸">桶状胸</td>			<td>${Body04}</td>		</tr>		<tr>			<td id="呼吸音">呼吸音</td>			<td>${Body05}</td>		</tr>		<tr>			<td id="罗音">罗音</td>			<td>${Body06}</td>		</tr>		<tr>			<td id="心率">心率</td>			<td>${Body07}次/分钟</td>		</tr>		<tr>			<td id="心律">心律</td>			<td>${Body08}</td>		</tr>		<tr>			<td id="杂音">杂音</td>			<td>${Body09}</td>		</tr>		<tr>			<td id="压痛">压痛</td>			<td>${Body10}</td>		</tr>		<tr>			<td id="包块">包块</td>			<td>${Body12}</td>		</tr>		<tr>			<td id="肝大">肝大</td>			<td>${Body13}</td>		</tr>		<tr>			<td id="脾大">脾大</td>			<td>${Body14}</td>		</tr>		<tr>			<td id="移动性浊音">移动性浊音</td>			<td>${Body15}</td>		</tr>		<tr>			<td id="下肢水肿">下肢水肿</td>			<td>${Body16}</td>		</tr>		<tr>			<td id="足背动脉博动">足背动脉博动</td>			<td>${Body17}</td>		</tr>		<tr>			<td id="肛门指诊">肛门指诊*<br /> <font size="2px" color="red">（空代表未测）</font></td>			<td>${Body18}</td>		</tr>		<tr>			<td id="乳腺">乳腺*</td>			<td>${Galactophore}</td>		</tr>		<tr>			<td id="外阴">外阴*</td>			<td>${Body20}</td>		</tr>		<tr>			<td id="阴道">阴道*</td>			<td>${Body21}</td>		</tr>		<tr>			<td id="宫颈">宫颈*</td>			<td>${Body22}</td>		</tr>		<tr>			<td id="宫体">宫体*</td>			<td>${Body23}</td>		</tr>		<tr>			<td id="附件">附件*</td>			<td>${Body24}</td>		</tr>		<tr>			<td id="妇科其它">妇科其它*</td>			<td>${Body25}</td>		</tr>		<tr>			<td id="血红蛋白">血红蛋白</td>			<td>${Exam03}g/L</td>		</tr>		<tr>			<td id="白细胞">白细胞</td>			<td><span>${Exam04}</span>×10<sup>9</sup>/L</td>		</tr>		<tr>			<td id="血小板">血小板</td>			<td><span>${Exam05}</span>×10<sup>9</sup>/L</td>		</tr>		<tr>			<td id="血常规其它">血常规其它</td>			<td>${Exam06}</td>		</tr>		<tr>			<td id="尿蛋白">尿蛋白</td>			<td>${Exam07}</td>		</tr>		<tr>			<td id="尿糖">尿糖</td>			<td>${Exam08}</td>		</tr>		<tr>			<td id="尿酮体">尿酮体</td>			<td>${Exam09}</td>		</tr>		<tr>			<td id="尿潜血">尿潜血</td>			<td>${Exam10}</td>		</tr>		<tr>			<td id="尿常规其它">尿常规其它</td>			<td>${Exam11}</td>		</tr>		<tr>			<td id="空腹血糖">空腹血糖*</td>			<td><span>${Exam01}</span>mmol/L 或 (餐后)<span>${Exam02}</span>mmol/L</td>		</tr>		<tr>			<td id="心电图">心电图*<br /> <font size="2px" color="red">（空代表未测）</font></td>			<td>${Exam30}</td>		</tr>		<tr>			<td id="尿微量白蛋白">尿微量白蛋白*</td>			<td><span>${Exam12}</span></td>		</tr>		<tr>			<td id="大便潜血">大便潜血*<br /> <font size="2px" color="red">（空代表未测）</font></td>			<td><span>${Exam13}</span></td>		</tr>		<tr>			<td id="糖化血红蛋白">糖化血红蛋白*</td>			<td><span>${Exam27}</span></td>		</tr>		<tr>			<td id="乙型肝炎表面抗原">乙型肝炎表面抗原*<br /> <font size="2px" color="red">（空代表未测）</font></td>			<td><span>${Exam28}</span></td>		</tr>		<tr>			<td id="血清谷丙转氨酶">血清谷丙转氨酶</td>			<td><span>${Exam14}</span>U/L</td>		</tr>		<tr>			<td id="血清谷草转氨酶">血清谷草转氨酶</td>			<td><span>${Exam15}</span>U/L</td>		</tr>		<tr>			<td id="白蛋白">白蛋白</td>			<td><span>${Exam16}</span>U/L</td>		</tr>		<tr>			<td id="总胆红素">总胆红素</td>			<td><span>${Exam17}</span>µmol		</tr>		<tr>			<td id="血清肌酐">血清肌酐</td>			<td><span>${Exam19}</span>µmol</td>		</tr>		<tr>			<td id="结合胆红素">结合胆红素</td>			<td><span>${Exam18}</span>µmol</td>		</tr>		<tr>			<td id="血尿素氮">血尿素氮</td>			<td><span>${Exam20}</span>mmol</td>		</tr>		<tr>			<td id="血钾浓度">血钾浓度</td>			<td><span>${Exam21}</span>mmol</td>		</tr>		<tr>			<td id="血纳浓度">血纳浓度</td>			<td><span>${Exam22}</span>mmol</td>		</tr>		<tr>			<td id="总胆固醇">总胆固醇</td>			<td><span>${Exam23}</span>mmol/L</td>		</tr>		<tr>			<td id="甘油三酯">甘油三酯</td>			<td><span>${Exam24}</span>mmol/L</td>		</tr>		<tr>			<td id="血清低密度脂蛋白胆固醇">血清低密度脂蛋白胆固醇</td>			<td><span>${Exam25}</span>mmol/L</td>		</tr>		<tr>			<td id="血清高密度脂蛋白胆固醇">血清高密度脂蛋白胆固醇</td>			<td><span>${Exam26}</span>mmol/L</td>		</tr>		<tr>			<td id="胸片X线片">胸片X线片*<br /> <font size="2px" color="red">（空代表未测）</font></td>			<td>${Exam31}</td>		</tr>		<tr>			<td id="B超">B超*<br /> <font size="2px" color="red">（空代表未测）</font></td>			<td>${Exam32}</td>		</tr>		<tr>			<td id="宫颈涂片">宫颈涂片*<br /> <font size="2px" color="red">（空代表未测）</font></td>			<td>${Exam33}</td>		</tr>		<tr>			<td id="辅助检查其他">辅助检查其他*</td>			<td>${Exam34}</td>		</tr>		<tr>			<td id="平和质">平和质</td>			<td><span>${CHN01}</span></td>		</tr>		<tr>			<td id="气虚质">气虚质</td>			<td><span>${CHN02}</span></td>		</tr>		<tr>			<td id="阳虚质">阳虚质</td>			<td><span>${CHN03}</span></td>		</tr>		<tr>			<td id="阴虚质">阴虚质</td>			<td><span>${CHN04}</span></td>		</tr>		<tr>			<td id="痰湿质">痰湿质</td>			<td><span>${CHN05}</span></td>		</tr>		<tr>			<td id="湿热质">湿热质</td>			<td><span>${CHN06}</span></td>		</tr>		<tr>			<td id="血瘀质">血瘀质</td>			<td><span>${CHN07}</span></td>		</tr>		<tr>			<td id="气郁质">气郁质</td>			<td><span>${CHN08}</span></td>		</tr>		<tr>			<td id="特秉质">特秉质</td>			<td><span>${CHN09}</span></td>		</tr>		<tr>			<td id="脑血管疾病">脑血管疾病</td>			<td>${HarnsSick}</td>		</tr>		<tr>			<td id="肾脏疾病">肾脏疾病</td>			<td>${KidneySick}</td>		</tr>		<tr>			<td id="心脏疾病">心脏疾病</td>			<td>${HeartSick}</td>		</tr>		<tr>			<td id="血管疾病">血管疾病</td>			<td>${VasSick}</td>		</tr>		<tr>			<td id="眼部疾病">眼部疾病</td>			<td>${EyeSick}</td>		</tr>		<tr>			<td id="神经系统疾病">神经系统疾病</td>			<td>${Problem06}</td>		</tr>		<tr>			<td id="其它系统疾病">其它系统疾病</td>			<td>${Problem07}</td>		</tr>		<tr>			<td id="住院治疗情况">住院治疗情况</td>			<td><span>${Hospitalization}</span></td>		</tr>		<tr>			<td id="主要用药情况">主要用药情况</td>			<td><span>${ExamMedication}</span></td>		</tr>		<tr>			<td id="非免疫规划预防接种史">非免疫规划预防接种史</td>			<td><span>${VaccinateHistory}</span></td>		</tr>		<tr>			<td id="健康评价">健康评价</td>			<td>${Judge01}</td>		</tr>		<tr>			<td id="健康评价异常1">健康评价异常1</td>			<td><span>${Judge02}</span></td>		</tr>		<tr>			<td id="健康评价异常2">健康评价异常2</td>			<td>异常2<span>${Judge03}</span></td>		</tr>		<tr>			<td id="健康评价异常3">健康评价异常3</td>			<td>异常3<span>${Judge04}</span></td>		</tr>		<tr>			<td id="健康评价异常4">健康评价异常4</td>			<td>异常4<span>${Judge05}</span></td>		</tr>		<tr>			<td id="健康指导">健康指导</td>			<td><span>${HealthDirect}</span></td>		</tr>		<tr>			<td style="border-bottom: 1px solid #000;" id="危险因素控制">危险因素控制</td>			<td style="border-bottom: 1px solid #000;" colspan="6"><span>${DangerControl}</span><span>${DangerControlOther1}</span><span>${DangerControlOther2}</span><span>${DangerControlOther3}</span></td>		</tr>	</table></div>';
			}
	    	
			echo json_encode(array('success' => true, 'msg' => '保存成功!','data' => $serviceIndexCount_list,
				'standarddisplayhtml' => $standarddisplayhtml,'keyvaluehtml' => $keyvaluehtml));
		}else{
			$member_id = trim($_GET ['member_id']);
	    	
	    	$conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
	    	$sql = "exec pXMemberHealthFileServiceIndexCount '". $member_id ."';";
			$stmt = $conn->prepare($sql);
			$stmt->execute();
			$serviceIndexCount_list = array();
			while ( $row = $stmt->fetchObject () ) {
				array_push ($serviceIndexCount_list, $row );
			}
	    	
	    	$sql = "exec pXMemberHealthFileServiceIndexInfo '". $member_id ."';";
			$stmt = $conn->prepare($sql);
			$stmt->execute();
			$serviceIndexInfo_list = array();
			while ( $row = $stmt->fetchObject () ) {
				array_push ($serviceIndexInfo_list, $row );
			}
	    	Tpl::output('member_id',$member_id);
	    	Tpl::output('member_truename', trim($_GET ['member_truename']));
	    	Tpl::output('sLinkPhone', trim($_GET ['sLinkPhone']));
	    	Tpl::output('sAddress', trim($_GET ['sAddress']));
	    	Tpl::output('sIDCard', trim($_GET ['sIDCard']));
	    	Tpl::output('MediCardID', trim($_GET ['MediCardID']));
	    	Tpl::output('FileNo', trim($_GET ['FileNo']));
	    	Tpl::output('CardType', trim($_GET ['CardType']));
	    	Tpl::output('CardGrade', trim($_GET ['CardGrade']));
	    	Tpl::output('GetWay', trim($_GET ['GetWay']));
	    	Tpl::output('Referrer', trim($_GET ['Referrer']));
	    	Tpl::output('available_predeposit', trim($_GET ['available_predeposit']));
	    	Tpl::output('fConsumeBalance', trim($_GET ['fConsumeBalance']));
	    	Tpl::output('member_points', trim($_GET ['member_points']));
	    	Tpl::output('LastPayDate', trim($_GET ['LastPayDate']));
	    	Tpl::output('LastPayOrgName', trim($_GET ['LastPayOrgName']));
	    	Tpl::output('ServiceIndexCount', $serviceIndexCount_list);
	    	Tpl::output('ServiceIndexInfo',$serviceIndexInfo_list);
	        Tpl::showpage('timeline-new');
		}
    }
}
