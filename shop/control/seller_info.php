<?php
/**
 * 店铺基本信息维护
 *
 * @copyright  Copyright (c) 2007-2014 ShopNC Inc. (http://www.szgr.com.cn)
 * @license    http://www.szgr.com.cn
 * @link       http://www.szgr.com.cn
 * @since      File available since Release v1.1
 */
defined('InShopNC') or exit('Access Invalid!');

class seller_infoControl extends BaseSellerControl {

    /**
     * 构造方法
     *
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * 店铺基本信息设置
     *
     */
    public function indexOp() {
        Tpl::showpage('index');
    }

}
