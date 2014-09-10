<?php
/**
 * 广告展示
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
class advControl {
    /**
	 * 
	 * 广告展示
	 */
	public function advshowOp(){
		import('function.adv');
		$ap_id = intval($_GET['ap_id']);
		echo advshow($ap_id,'js');
	}
}
