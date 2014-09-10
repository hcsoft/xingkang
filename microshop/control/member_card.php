<?php
/**
 * The AJAX call member information
 *
 *
 *
 *
 * @copyright  Copyright (c) 2014-2020 SZGR Inc. (http://www.szgr.com.cn)
 * @license    http://www.szgr.com.cn
 * @link       http://www.szgr.com.cn
 * @since      File available since Release v1.1
 */

class member_cardControl extends MircroShopControl{
	public function mcard_infoOp(){
		$uid	= intval($_GET['uid']);
        if($uid <= 0) {
			echo 'false';exit;
        }
        $model_micro_member_info = Model('micro_member_info');
        $micro_member_info = $model_micro_member_info->getOneById($uid);
		if(empty($micro_member_info)){
			echo 'false';exit;
		}
		echo json_encode($micro_member_info);exit;
	}
}
