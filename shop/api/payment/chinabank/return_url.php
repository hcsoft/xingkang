<?php
/**
 * 网银在线返回地址
 *
 * 
 * @copyright  Copyright (c) 2014-2020 SZGR Inc. (http://www.szgr.com.cn)
 * @license    http://www.szgr.com.cn
 * @link       http://www.szgr.com.cn
 * @since      File available since Release v1.1
 */
$_GET['act']	= 'payment';
$_GET['op']		= 'return';
$_GET['payment_code'] = 'chinabank';

//赋值，方便后面合并使用支付宝验证方法
$_GET['out_trade_no'] = $_POST['v_oid'];
$_GET['extra_common_param'] = $_POST['remark1'];
$_GET['trade_no'] = $_POST['v_idx'];
require_once(dirname(__FILE__).'/../../../index.php');
?>
