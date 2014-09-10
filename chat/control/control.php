<?php
/**
 * 前台control父类
 *
 *
 * @copyright  Copyright (c) 2014-2020 SZGR Inc. (http://www.szgr.com.cn)
 * @license    http://www.szgr.com.cn
 * @link       http://www.szgr.com.cn
 * @since      File available since Release v1.1
 */
defined('InShopNC') or exit('Access Invalid!');

/********************************** 前台control父类 **********************************************/

class BaseControl {
	public function __construct(){
		Language::read('common');
	}
}
