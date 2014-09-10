<?php
/**
 * APP会员
 *
 *
 *
 * @copyright  Copyright (c) 2014-2020 SZGR Inc. (http://www.szgr.com.cn)
 * @license    http://www.szgr.com.cn
 * @link       http://www.szgr.com.cn
 * @since      File available since Release v1.1
 */
defined('InShopNC') or exit('Access Invalid!');

class memberControl{

	public function __construct(){
		require_once(BASE_PATH.'/framework/function/client.php');
	}

	public function infoOp(){
		if (!empty($_GET['uid'])){
			$member_info = nc_member_info($_GET['uid'],'uid');
		}elseif(!empty($_GET['user_name'])){
			$member_info = nc_member_info($_GET['user_name'],'user_name');
		}
		return $member_info;
	}
}
