<?php
/**
 * 店铺动态自动发布
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

class store_sns_settingModel extends Model {
    public function __construct(){
        parent::__construct('store_sns_setting');
    }

    /**
     * 获取单条动态设置设置信息
     * 
     * @param unknown $condition
     * @param string $field
     * @return array
     */
    public function getStoreSnsSettingInfo($condition, $field = '*') {
        return $this->field($field)->where($condition)->find();
    }
    
    /**
     * 保存店铺动态设置
     * 
     * @param unknown $insert
     * @return boolean
     */
    public function saveStoreSnsSetting($insert) {
        return $this->insert($insert);
    }
}
