<?php
/**
 * 购买流程
 *
 * @copyright  Copyright (c) 2014-2020 SZGR Inc. (http://www.szgr.com.cn)
 * @license    http://www.szgr.com.cn
 * @link       http://www.szgr.com.cn
 * @since      File available since Release v1.1
 */
defined('InShopNC') or exit('Access Invalid!');
class buyControl extends BaseBuyControl {

    public function __construct() {
        parent::__construct();
        Language::read('home_cart_index');
        if (!$_SESSION['member_id']){
            redirect('index.php?act=login&ref_url='.urlencode(request_uri()));
        }
        //验证该会员是否禁止购买
        if(!$_SESSION['is_buy']){
            showMessage(Language::get('cart_buy_noallow'),'','html','error');
        }
    }

    /**
     * 购物车、直接购买第一步:选择收获地址和配置方式
     */
    public function buy_step1Op() {
        $model_buy = Model('buy');

        $result = $model_buy->buyStep1($_POST['cart_id'], $_POST['ifcart'], $_POST['invalid_cart'], $_SESSION['member_id'], $_SESSION['store_id']);
        if(!empty($result['error'])) {
            showMessage($result['error'], '', 'html', 'error');
        }

        Tpl::output('ifcart', $result['ifcart']);
        //商品金额计算(分别对每个商品/优惠套装小计、每个店铺小计)
        Tpl::output('store_cart_list', $result['store_cart_list']);
        Tpl::output('store_goods_total', $result['store_goods_total']);
        //取得店铺优惠 - 满即送(赠品列表，店铺满送规则列表)
        Tpl::output('store_premiums_list', $result['store_premiums_list']);
        Tpl::output('store_mansong_rule_list', $result['store_mansong_rule_list']);
        //返回店铺可用的代金券
        Tpl::output('store_voucher_list', $result['store_voucher_list']);
        //返回需要计算运费的店铺ID数组 和 不需要计算运费(满免运费活动的)店铺ID及描述
        Tpl::output('need_calc_sid_list', $result['need_calc_sid_list']);
        Tpl::output('cancel_calc_sid_list', $result['cancel_calc_sid_list']);
        //将商品ID、数量、运费模板、运费序列化，加密，输出到模板，选择地区AJAX计算运费时作为参数使用
        Tpl::output('freight_hash', $result['freight_list']);
        //输出用户默认收货地址
        Tpl::output('address_info', $result['address_info']);
        //输出有货到付款时，在线支付和货到付款及每种支付下商品数量和详细列表
        Tpl::output('pay_goods_list', $result['pay_goods_list']);
        Tpl::output('ifshow_offpay', $result['ifshow_offpay']);
        Tpl::output('deny_edit_payment', $result['deny_edit_payment']);
        //不提供增值税发票时抛出true(模板使用)
        Tpl::output('vat_deny', $result['vat_deny']);
        //增值税发票哈希值(php验证使用)
        Tpl::output('vat_hash', $result['vat_hash']);
        //输出默认使用的发票信息
        Tpl::output('inv_info', $result['inv_info']);
        //显示使用预存款支付及会员预存款
        Tpl::output('available_pd_amount', $result['available_predeposit']);

        //标识 购买流程执行第几步
        Tpl::output('buy_step','step2');
        Tpl::showpage('buy_step1');
    }

    /**
     * 购物车、直接购买第二步:保存订单入库，产生订单号，开始选择支付方式
     *
     */
    public function buy_step2Op() {
        $model_buy = Model('buy');

        $result = $model_buy->buyStep2($_POST, $_SESSION['member_id'], $_SESSION['member_name'], $_SESSION['member_email']);
        if(!empty($result['error'])) {
            showMessage($result['error'], '', 'html', 'error');
        }

        //转向到商城支付页面
        $pay_url = 'index.php?act=buy&op=pay&pay_sn='.$result['pay_sn'];
        redirect($pay_url);
    }

    /**
     * 下单时支付页面
     */
    public function payOp() {
        $pay_sn	= $_GET['pay_sn'];
        if (!preg_match('/^\d{18}$/',$pay_sn)){
            showMessage(Language::get('cart_order_pay_not_exists'),'index.php?act=member_order','html','error');
        }

        //查询支付单信息
        $model_order= Model('order');
        $pay_info = $model_order->getOrderPayInfo(array('pay_sn'=>$pay_sn,'buyer_id'=>$_SESSION['member_id']));
        if(empty($pay_info)){
            showMessage(Language::get('cart_order_pay_not_exists'),'','html','error');
        }
        Tpl::output('pay_info',$pay_info);

        //取子订单列表
        $condition = array();
        $condition['pay_sn'] = $pay_sn;
        $condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY));
        $order_list = $model_order->getOrderList($condition,'','order_id,order_state,payment_code,order_amount,pd_amount,order_sn');
        if (empty($order_list)) {
            showMessage('未找到需要支付的订单','index.php?act=member_order','html','error');
        }

        //重新计算在线支付金额
        $pay_amount_online = 0;
        $pay_amount_offline = 0;
        //订单总支付金额(不包含货到付款)
        $pay_amount = 0;

        foreach ($order_list as $key => $order_info) {

            //计算相关支付金额
            if ($order_info['payment_code'] != 'offline') {
                if ($order_info['order_state'] == ORDER_STATE_NEW) {
                    $pay_amount_online += ncPriceFormat(floatval($order_info['order_amount'])-floatval($order_info['pd_amount']));
                }
                $pay_amount += floatval($order_info['order_amount']);
            } else {
                $pay_amount_offline += floatval($order_info['order_amount']);
            }

            //显示支付方式与支付结果
            if ($order_info['payment_code'] == 'offline') {
                $order_list[$key]['payment_state'] = '货到付款';
            } else {
                $order_list[$key]['payment_state'] = '在线支付';
                if (floatval($order_info['pd_amount']) > 0) {
                    if ($order_info['order_state'] == ORDER_STATE_PAY) {
                    $order_list[$key]['payment_state'] .= " ( 已使用预存款完全支付，支付金额 ￥ {$order_info['pd_amount']} )";
                    } else {
                    $order_list[$key]['payment_state'] .= " ( 已使用预存款部分支付，支付金额 ￥ {$order_info['pd_amount']} )";
                    }
                }
            }
        }
        Tpl::output('order_list',$order_list);

        //如果线上线下支付金额都为0，转到支付成功页
        if (empty($pay_amount_online) && empty($pay_amount_offline)) {
            redirect('index.php?act=buy&op=pay_ok&pay_sn='.$pay_sn.'&pay_amount='.ncPriceFormat($pay_amount));
        }

        //输入订单描述
        if (empty($pay_amount_online)) {
            $order_remind = '下单成功，我们会尽快为您发货，请保持电话畅通！';
        } elseif (empty($pay_amount_offline)) {
            $order_remind = '请您及时付款，以便订单尽快处理！';
        } else {
            $order_remind = '部分商品需要在线支付，请尽快付款！';
        }
        Tpl::output('order_remind',$order_remind);
        Tpl::output('pay_amount_online',ncPriceFormat($pay_amount_online));
        Tpl::output('pd_amount',ncPriceFormat($pd_amount));

        //显示支付接口列表
        if ($pay_amount_online > 0) {
            $model_payment = Model('payment');
            $condition = array();
            $payment_list = $model_payment->getPaymentOpenList($condition);
            if (!empty($payment_list)) {
                unset($payment_list['predeposit']);
                unset($payment_list['offline']);
            }
            if (empty($payment_list)) {
                showMessage('暂未找到合适的支付方式','index.php?act=member_order','html','error');
            }
            Tpl::output('payment_list',$payment_list);
        }

        //标识 购买流程执行第几步
        Tpl::output('buy_step','step3');
        Tpl::showpage('buy_step2');
    }

    /**
     * 预存款充值下单时支付页面
     */
    public function pd_payOp() {
        $pay_sn	= $_GET['pay_sn'];
        if (!preg_match('/^\d{18}$/',$pay_sn)){
            showMessage(Language::get('para_error'),'index.php?act=predeposit','html','error');
        }

        //查询支付单信息
        $model_order= Model('predeposit');
        $pd_info = $model_order->getPdRechargeInfo(array('pdr_sn'=>$pay_sn,'pdr_member_id'=>$_SESSION['member_id']));
        if(empty($pd_info)){
            showMessage(Language::get('para_error'),'','html','error');
        }
        if (intval($pd_info['pdr_payment_state'])) {
            showMessage('您的订单已经支付，请勿重复支付','index.php?act=predeposit','html','error');
        }
        Tpl::output('pdr_info',$pd_info);

        //显示支付接口列表
		$model_payment = Model('payment');
        $condition = array();
        $condition['payment_code'] = array('not in',array('offline','predeposit'));
        $condition['payment_state'] = 1;
        $payment_list = $model_payment->getPaymentList($condition);
        Tpl::output('payment_list',$payment_list);
    
        //标识 购买流程执行第几步
        Tpl::output('buy_step','step3');
        Tpl::showpage('predeposit_pay');
    }

	/**
	 * 支付成功页面
	 */
	public function pay_okOp() {
	    $pay_sn	= $_GET['pay_sn'];
	    if (!preg_match('/^\d{18}$/',$pay_sn)){
	        showMessage(Language::get('cart_order_pay_not_exists'),'index.php?act=member_order','html','error');
	    }

	    //查询支付单信息
	    $model_order= Model('order');
	    $pay_info = $model_order->getOrderPayInfo(array('pay_sn'=>$pay_sn,'buyer_id'=>$_SESSION['member_id']));
	    if(empty($pay_info)){
	        showMessage(Language::get('cart_order_pay_not_exists'),'index.php?act=member_order','html','error');
	    }
	    Tpl::output('pay_info',$pay_info);

	    Tpl::output('buy_step','step4');
	    Tpl::showpage('buy_step3');
	}

	/**
	 * 加载买家收货地址
	 *
	 */
	public function load_addrOp() {
	    $model_addr = Model('address');
	    //如果传入ID，先删除再查询
	    if (!empty($_GET['id']) && intval($_GET['id']) > 0) {
            $model_addr->delAddress(array('address_id'=>intval($_GET['id']),'member_id'=>$_SESSION['member_id']));
	    }
	    $list = $model_addr->getAddressList(array('member_id'=>$_SESSION['member_id']));
	    Tpl::output('address_list',$list);
	    Tpl::showpage('buy_address.load','null_layout');
	}

    /**
     * 选择不同地区时，异步处理并返回每个店铺总运费以及本地区是否能使用货到付款
     * 如果店铺统一设置了满免运费规则，则运费模板无效
     * 如果店铺未设置满免规则，且使用运费模板，按运费模板计算，如果其中有商品使用相同的运费模板，则两种商品数量相加后再应用该运费模板计算（即作为一种商品算运费）
     * 如果未找到运费模板，按免运费处理
     * 如果没有使用运费模板，商品运费按快递价格计算，运费不随购买数量增加
     */
    public function change_addrOp() {
        $model_buy = Model('buy');

        $data = $model_buy->changeAddr($_POST['freight_hash'], $_POST['city_id'], $_POST['area_id'], $_SESSION['member_id']);
        if(!empty($data)) {
            exit(json_encode($data));
        } else {
            exit();
        }
    }

     /**
      * 添加新的收货地址
      *
      */
     public function add_addrOp(){
        $model_addr = Model('address');
     	if (chksubmit()){
     		//验证表单信息
     		$obj_validate = new Validate();
     		$obj_validate->validateparam = array(
     			array("input"=>$_POST["true_name"],"require"=>"true","message"=>Language::get('cart_step1_input_receiver')),
     			array("input"=>$_POST["area_id"],"require"=>"true","validator"=>"Number","message"=>Language::get('cart_step1_choose_area')),
     			array("input"=>$_POST["address"],"require"=>"true","message"=>Language::get('cart_step1_input_address'))
     		);
     		$error = $obj_validate->validate();
			if ($error != ''){
				$error = strtoupper(CHARSET) == 'GBK' ? Language::getUTF8($error) : $error;
				exit(json_encode(array('state'=>false,'msg'=>$error)));
			}
			$data = array();
			$data['member_id'] = $_SESSION['member_id'];
			$data['true_name'] = $_POST['true_name'];
			$data['area_id'] = intval($_POST['area_id']);
			$data['city_id'] = intval($_POST['city_id']);
			$data['area_info'] = $_POST['area_info'];
			$data['address'] = $_POST['address'];
			$data['tel_phone'] = $_POST['tel_phone'];
			$data['mob_phone'] = $_POST['mob_phone'];
	     	//转码
            $data = strtoupper(CHARSET) == 'GBK' ? Language::getGBK($data) : $data;
			$insert_id = $model_addr->addAddress($data);
			if ($insert_id){
				exit(json_encode(array('state'=>true,'addr_id'=>$insert_id)));
			}else {
				exit(json_encode(array('state'=>false,'msg'=>Language::get('cart_step1_addaddress_fail','UTF-8'))));
			}
     	} else {
     		Tpl::showpage('buy_address.add','null_layout');
     	}
     }

	/**
	 * 加载买家发票列表，最多显示10条
	 *
	 */
	public function load_invOp() {
        $model_buy = Model('buy');

	    $condition = array();
	    if ($model_buy->buyDecrypt($_GET['vat_hash'], $_SESSION['member_id']) == 'allow_vat') {
	    } else {
	        Tpl::output('vat_deny',true);
	        $condition['inv_state'] = 1;
	    }
	    $condition['member_id'] = $_SESSION['member_id'];

	    $model_inv = Model('invoice');
	    //如果传入ID，先删除再查询
	    if (intval($_GET['del_id']) > 0) {
            $model_inv->delInv(array('inv_id'=>intval($_GET['del_id']),'member_id'=>$_SESSION['member_id']));
	    }
	    $list = $model_inv->getInvList($condition,10);
	    if (!empty($list)) {
	        foreach ($list as $key => $value) {
	           if ($value['inv_state'] == 1) {
	               $list[$key]['content'] = '普通发票'.' '.$value['inv_title'].' '.$value['inv_content'];
	           } else {
	               $list[$key]['content'] = '增值税发票'.' '.$value['inv_company'].' '.$value['inv_code'].' '.$value['inv_reg_addr'];
	           }
	        }
	    }
	    Tpl::output('inv_list',$list);
	    Tpl::showpage('buy_invoice.load','null_layout');
	}

     /**
      * 新增发票信息
      *
      */
     public function add_invOp(){
        $model_inv = Model('invoice');
     	if (chksubmit()){
     		//如果是增值税发票验证表单信息
     		if ($_POST['invoice_type'] == 2) {
     		    if (empty($_POST['inv_company']) || empty($_POST['inv_code']) || empty($_POST['inv_reg_addr'])) {
     		        exit(json_encode(array('state'=>false,'msg'=>Language::get('nc_common_save_fail','UTF-8'))));
     		    }
     		}
			$data = array();
            if ($_POST['invoice_type'] == 1) {
                $data['inv_state'] = 1;
                $data['inv_title'] = $_POST['inv_title_select'] == 'person' ? '个人' : $_POST['inv_title'];
                $data['inv_content'] = $_POST['inv_content'];
            } else {
                $data['inv_state'] = 2;
    			$data['inv_company'] = $_POST['inv_company'];
    			$data['inv_code'] = $_POST['inv_code'];
    			$data['inv_reg_addr'] = $_POST['inv_reg_addr'];
    			$data['inv_reg_phone'] = $_POST['inv_reg_phone'];
    			$data['inv_reg_bname'] = $_POST['inv_reg_bname'];
    			$data['inv_reg_baccount'] = $_POST['inv_reg_baccount'];
    			$data['inv_rec_name'] = $_POST['inv_rec_name'];
    			$data['inv_rec_mobphone'] = $_POST['inv_rec_mobphone'];
    			$data['inv_rec_province'] = $_POST['area_info'];
    			$data['inv_goto_addr'] = $_POST['inv_goto_addr'];
            }
            $data['member_id'] = $_SESSION['member_id'];
	     	//转码
            $data = strtoupper(CHARSET) == 'GBK' ? Language::getGBK($data) : $data;
			$insert_id = $model_inv->addInv($data);
			if ($insert_id) {
				exit(json_encode(array('state'=>'success','id'=>$insert_id)));
			} else {
				exit(json_encode(array('state'=>'fail','msg'=>Language::get('nc_common_save_fail','UTF-8'))));
			}
     	} else {
     		Tpl::showpage('buy_address.add','null_layout');
     	}
     }


    /**
     * AJAX验证登录密码
     */
    public function check_pd_pwdOp(){
        if (empty($_GET['password'])) exit('0');
        $buyer_info	= Model('member')->infoMember(array('member_id' => $_SESSION['member_id']));
        echo $buyer_info['member_passwd'] === md5($_GET['password']) ? '1' : '0';
    }

}
