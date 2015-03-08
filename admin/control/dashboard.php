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
            $datesql = ' and checkout.dCO_Date>= \''.date('Y-m-1',strtotime('-1 month')). '\' and checkout.dCO_Date< \''.date('Y-m-1',time()).'\'';
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
            $datesql = ' and checkout.RechargeDate>= \''.date('Y-m-1',strtotime('-1 month')). '\' and checkout.RechargeDate< \''.date('Y-m-1',time()).'\'';
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
            $datesql = ' and checkout.RechargeDate>= \''.date('Y-m-1',strtotime('-1 month')). '\' and checkout.RechargeDate< \''.date('Y-m-1',time()).'\'';
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
            $outdatesql = ' and out.dco_date>= \''.date('Y-m-1',strtotime('-1 month')). '\' and out.dco_date< \''.date('Y-m-1',time()).'\'';
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
                having sum(num)>0
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
            $datesql = ' and checkout.checkdate>= \''.date('Y-m-1',strtotime('-1 month')). '\' and checkout.checkdate< \''.date('Y-m-1',time()).'\'';
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
            $datesql = ' and hf.InputDate>= \''.date('Y-m-1',strtotime('-1 month')). '\' and hf.InputDate< \''.date('Y-m-1',time()).'\'';
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
            $datesql = ' and checkout.checkdate>= \''.date('Y-m-1',strtotime('-1 month')). '\' and checkout.checkdate< \''.date('Y-m-1',time()).'\'';
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
            $datesql = ' and a.InputDate>= \''.date('Y-m-1',strtotime('-1 month')). '\' and a.InputDate< \''.date('Y-m-1',time()).'\'';
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
        echo json_encode($statistics);
        exit;
    }

    public function chartdetailOp(){
        $func = $_GET['opt'].'chartOp';
        echo json_encode($this->$func($_GET['type']));
    }
}
