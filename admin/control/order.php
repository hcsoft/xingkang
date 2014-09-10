<?php
/**
 * 交易管理
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
class orderControl extends SystemControl{
    /**
     * 每次导出订单数量
     * @var int
     */
	const EXPORT_SIZE = 1000;

	public function __construct(){
		parent::__construct();
		Language::read('trade');		
	}

	public function indexOp(){
	    $model_order = Model('order');
        $condition	= array();
        if($_GET['order_sn']) {
        	$condition['order_sn'] = $_GET['order_sn'];
        }
        if($_GET['store_name']) {
            $condition['store_name'] = $_GET['store_name'];
        }
        if(in_array($_GET['order_state'],array('0','10','20','30','40','50'))){
        	$condition['order_state'] = $_GET['order_state'];
        }
        if($_GET['payment_code']) {
            $condition['payment_code'] = $_GET['payment_code'];
        }
        if($_GET['buyer_name']) {
            $condition['buyer_name'] = $_GET['buyer_name'];
        }
        $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_time']);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_time']);
        $start_unixtime = $if_start_time ? strtotime($_GET['query_start_time']) : null;
        $end_unixtime = $if_end_time ? strtotime($_GET['query_end_time']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
        $order_list	= $model_order->getOrderList($condition,30);

        foreach ($order_list as $order_id => $order_info) {
            //显示取消订单
            $order_list[$order_id]['if_cancel'] = $model_order->getOrderOperateState('system_cancel',$order_info);
            //显示收到货款
            $order_list[$order_id]['if_system_receive_pay'] = $model_order->getOrderOperateState('system_receive_pay',$order_info);            
        }
        //显示支付接口列表(搜索)
        $payment_list = Model('payment')->getPaymentOpenList();
        Tpl::output('payment_list',$payment_list);

        Tpl::output('order_list',$order_list);
        Tpl::output('show_page',$model_order->showpage());
        Tpl::showpage('order.index');
	}

	/**
	 * 平台订单状态操作
	 *
	 */
	public function change_stateOp() {
        $order_id = intval($_GET['order_id']);
        if($order_id <= 0){
            showMessage(L('miss_order_number'),$_POST['ref_url'],'html','error');
        }
        $model_order = Model('order');

        //获取订单详细
        $condition = array();
        $condition['order_id'] = $order_id;
        $order_info	= $model_order->getOrderInfo($condition);
        try {

            $model_order->beginTransaction();
            $state_type	= $_GET['state_type'];
            if ($state_type == 'cancel') {
                $this->_change_state_order_cancel($order_info);
            } elseif ($state_type == 'receive_pay') {
                $this->_change_state_order_receive_pay($order_info);
            }

            $model_order->commit();
            showMessage(L('nc_common_op_succ'),$_POST['ref_url']);

        } catch (Exception $e) {
            $model_order->rollback();
            showMessage($e->getMessage(),$_POST['ref_url'],'html','error');
        }
	}

	/**
	 * 系统取消订单
	 * @throws Exception
	 */
	private function _change_state_order_cancel($order_info) {
	    $order_id = $order_info['order_id'];
	    $model_order = Model('order');
	    $if_allow = $model_order->getOrderOperateState('system_cancel',$order_info);
	    if (!$if_allow) {
	        throw new Exception(L('invalid_request'));
	    }
	    $goods_list = $model_order->getOrderGoodsList(array('order_id'=>$order_id));
	    $model_goods= Model('goods');
	    if(is_array($goods_list) and !empty($goods_list)) {
	        $data = array();
	        foreach ($goods_list as $goods) {
	            $data['goods_storage'] = array('exp','goods_storage+'.$goods['goods_num']);
	            $data['goods_salenum'] = array('exp','goods_salenum-'.$goods['goods_num']);
	            $update = $model_goods->editGoods($data,array('goods_id'=>$goods['goods_id']));
	            if (!$update) {
	                throw new Exception(L('nc_common_save_fail'));
	            }
	        }
	    }

	    //解冻预存款
	    $pd_amount = floatval($order_info['pd_amount']);
	    if ($pd_amount > 0) {
	        $model_pd = Model('predeposit');
	        $data_pd = array();
            $data_pd['member_id'] = $order_info['buyer_id'];
            $data_pd['member_name'] = $order_info['buyer_name'];
	        $data_pd['amount'] = $pd_amount;
	        $data_pd['order_sn'] = $order_info['order_sn'];
	        $model_pd->changePd('order_cancel',$data_pd);
	    }

	    //更新订单状态
	    $update_order = array('order_state' => ORDER_STATE_CANCEL);
	    $update = $model_order->editOrder($update_order,array('order_id'=>$order_id));
	    if (!$update) {
	        throw new Exception(L('nc_common_save_fail'));
	    }

	    //添加订单日志
	    $data = array();
	    $data['order_id'] = $order_id;
	    $data['log_role'] = 'system';
	    $data['log_user'] = $this->admin_info['name'];
	    $data['log_msg'] = L('order_log_cancel');
	    $data['log_orderstate'] = ORDER_STATE_CANCEL;
	    $model_order->addOrderLog($data);
	    
	    $this->log(L('order_log_cancel').','.L('order_number').':'.$order_info['order_sn'],1);
	}
	
	/**
	 * 系统收到货款
	 * @throws Exception
	 */
	private function _change_state_order_receive_pay($order_info) {
	    $order_id = $order_info['order_id'];
	    $model_order = Model('order');
	    $if_allow = $model_order->getOrderOperateState('system_receive_pay',$order_info);
	    if (!$if_allow) {
	        throw new Exception(L('invalid_request'));
	    }

	    if (!chksubmit()) {
	        Tpl::output('order_info',$order_info);

	        //显示支付接口列表
	        $payment_list = Model('payment')->getPaymentOpenList();
	        //去掉预存款和货到付款
	        foreach ($payment_list as $key => $value){
	            if ($value['payment_code'] == 'predeposit' || $value['payment_code'] == 'offline') {
	               unset($payment_list[$key]); 
	            }
	        }
	        Tpl::output('payment_list',$payment_list);

	        Tpl::showpage('order.receive_pay');exit();
	    }

	    //下单，支付被冻结的预存款
	    $pd_amount = floatval($order_info['pd_amount']);
	    if ($pd_amount > 0) {
	        $model_pd = Model('predeposit');
	        $data_pd = array();
	        $data_pd['member_id'] = $order_info['buyer_id'];
	        $data_pd['member_name'] = $order_info['buyer_name'];
	        $data_pd['amount'] = $pd_amount;
	        $data_pd['order_sn'] = $order_info['order_sn'];
	        $model_pd->changePd('order_comb_pay',$data_pd);
	    }

	    //更新订单状态
	    $update_order = array();
	    $update_order['order_state'] = ORDER_STATE_PAY;
	    $update_order['payment_time'] = strtotime($_POST['payment_time']);
	    $update_order['payment_code'] = $_POST['payment_code'];
	    $update = $model_order->editOrder($update_order,array('order_id'=>$order_id));
	    if (!$update) {
	        throw new Exception(L('nc_common_save_fail'));
	    }
	
	    //添加订单日志
	    $data = array();
	    $data['order_id'] = $order_id;
	    $data['log_role'] = 'system';
	    $data['log_user'] = $this->admin_info['name'];
	    $data['log_msg'] = L('order_log_receive_paye').' ( 支付平台交易号 : '.$_POST['trade_no'].' )';
	    $data['log_orderstate'] = ORDER_STATE_PAY;
	    $model_order->addOrderLog($data);

	    $this->log(L('order_change_received').','.L('order_number').':'.$order_info['order_sn'],1);
	}

	/**
	 * 查看订单
	 *
	 */
	public function show_orderOp(){
	    $order_id = intval($_GET['order_id']);
	    if($order_id <= 0 ){
	        showMessage(L('miss_order_number'));
	    }
        $model_order	= Model('order');
        $order_info	= $model_order->getOrderInfo(array('order_id'=>$order_id),array('order_goods','order_common','store'));

        //订单变更日志
		$log_list	= $model_order->getOrderLogList(array('order_id'=>$order_info['order_id']));
		Tpl::output('order_log',$log_list);

		//退款退货信息
        $model_refund = Model('refund_return');
        $condition = array();
        $condition['order_id'] = $order_info['order_id'];
        $condition['seller_state'] = 2;
        $condition['admin_time'] = array('gt',0);
        $return_list = $model_refund->getReturnList($condition);
        Tpl::output('return_list',$return_list);

        //退款信息
        $refund_list = $model_refund->getRefundList($condition);
        Tpl::output('refund_list',$refund_list);

		//卖家发货信息
		if (!empty($order_info['extend_order_common']['daddress_id'])) {
		    $daddress_info = Model('daddress')->getAddressInfo(array('address_id'=>$order_info['extend_order_common']['daddress_id']));
		    Tpl::output('daddress_info',$daddress_info);
		}

		Tpl::output('order_info',$order_info);
        Tpl::showpage('order.view');
	}

	/**
	 * 导出
	 *
	 */
	public function export_step1Op(){
		$lang	= Language::getLangContent();

	    $model_order = Model('order');
        $condition	= array();
        if($_GET['order_sn']) {
        	$condition['order_sn'] = $_GET['order_sn'];
        }
        if($_GET['store_name']) {
            $condition['store_name'] = $_GET['store_name'];
        }
        if(in_array($_GET['order_state'],array('0','10','20','30','40','50'))){
        	$condition['order_state'] = $_GET['order_state'];
        }
        if($_GET['payment_code']) {
            $condition['payment_code'] = $_GET['payment_code'];
        }
        if($_GET['buyer_name']) {
            $condition['buyer_name'] = $_GET['buyer_name'];
        }
        $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_time']);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_time']);
        $start_unixtime = $if_start_time ? strtotime($_GET['query_start_time']) : null;
        $end_unixtime = $if_end_time ? strtotime($_GET['query_end_time']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }

		if (!is_numeric($_GET['curpage'])){		
			$count = $model_order->getOrderCount($condition);
			$array = array();
			if ($count > self::EXPORT_SIZE ){	//显示下载链接
				$page = ceil($count/self::EXPORT_SIZE);
				for ($i=1;$i<=$page;$i++){
					$limit1 = ($i-1)*self::EXPORT_SIZE + 1;
					$limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
					$array[$i] = $limit1.' ~ '.$limit2 ;
				}
				Tpl::output('list',$array);
				Tpl::output('murl','index.php?act=order&op=index');
				Tpl::showpage('export.excel');
			}else{	//如果数量小，直接下载
				$data = $model_order->getOrderList($condition,'','*','order_id desc',self::EXPORT_SIZE);
				$this->createExcel($data);
			}
		}else{	//下载
			$limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
			$limit2 = self::EXPORT_SIZE;
			$data = $model_order->getOrderList($condition,'','*','order_id desc',"{$limit1},{$limit2}");
			$this->createExcel($data);
		}
	}

	/**
	 * 生成excel
	 *
	 * @param array $data
	 */
	private function createExcel($data = array()){
		Language::read('export');
		import('libraries.excel');
		$excel_obj = new Excel();
		$excel_data = array();
		//设置样式
		$excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
		//header
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_no'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_store'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_buyer'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_xtimd'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_count'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_yfei'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_paytype'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_state'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_storeid'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_buyerid'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_bemail'));
		//data
		foreach ((array)$data as $k=>$v){
			$tmp = array();
			$tmp[] = array('data'=>'NC'.$v['order_sn']);
			$tmp[] = array('data'=>$v['store_name']);
			$tmp[] = array('data'=>$v['buyer_name']);
			$tmp[] = array('data'=>date('Y-m-d H:i:s',$v['add_time']));
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['order_amount']));
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['shipping_fee']));
			$tmp[] = array('data'=>orderPaymentName($v['payment_code']));
			$tmp[] = array('data'=>orderState($v));
			$tmp[] = array('data'=>$v['store_id']);
			$tmp[] = array('data'=>$v['buyer_id']);
			$tmp[] = array('data'=>$v['buyer_email']);
			$excel_data[] = $tmp;
		}
		$excel_data = $excel_obj->charset($excel_data,CHARSET);
		$excel_obj->addArray($excel_data);
		$excel_obj->addWorksheet($excel_obj->charset(L('exp_od_order'),CHARSET));
		$excel_obj->generateXML($excel_obj->charset(L('exp_od_order'),CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
	}
}
