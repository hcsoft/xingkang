<?php
/**
 * 默认展示页面
 *
 *
 *
 * @copyright  Copyright (c) 2014-2020 SZGR Inc. (http://www.szgr.com.cn)
 * @license    http://www.szgr.com.cn
 * @link       http://www.szgr.com.cn
 * @since      File available since Release v1.1
 */
defined('InShopNC') or exit('Access Invalid!');
class albumControl extends MircroShopControl{

	public function __construct() {
		parent::__construct();
        Tpl::output('index_sign','album');
    }

	//首页
	public function indexOp(){
		Tpl::showpage('album');
	}
}
