<?php
/**
 * 买家相册模型
 *
 * @copyright  Copyright (c) 2014-2020 SZGR Inc. (http://www.szgr.com.cn)
 * @license    http://www.szgr.com.cn
 * @link       http://www.szgr.com.cn
 * @since      File available since Release v1.1
 */
defined('InShopNC') or exit('Access Invalid!');
class sns_albumModel extends Model {

    public function __construct(){
        parent::__construct('sns_albumpic');
    }

    public function getSnsAlbumClassDefault($member_id) {
        if(empty($member_id)) {
            return null;
        }

        $condition = array();
        $condition['member_id'] = $member_id;
        $condition['is_default'] = 1;
        $info = $this->table('sns_albumclass')->where($condition)->find();

        if(!empty($info)) {
            return $info['ac_id'];
        } else {
            return null;
        }
    }
}
