<?php
/**
 * 店铺统计
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
class statisticsModel{
	/**
	 * 更新统计表
	 *
	 * @param	array $param	条件数组
	 */
	public function updatestat($param){
		if (empty($param)){
			return false;
		}
		$result = Db::update($param['table'],array($param['field']=>array('sign'=>'increase','value'=>$param['value'])),$param['where']);
		return $result;
	}
}
