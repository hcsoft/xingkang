<?php
/**
 * 店铺卖家注销
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

class seller_logoutControl extends BaseSellerControl {

	public function __construct() {
		parent::__construct();
	}

    public function indexOp() {
        $this->logoutOp();
    }

    public function logoutOp() {
        $this->recordSellerLog('注销成功');
        session_destroy();
        showMessage('注销成功', 'index.php?act=seller_login');
    }

}
