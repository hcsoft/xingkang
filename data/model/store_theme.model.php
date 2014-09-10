<?php
/**
 * 店铺模板Model  模板对应module (位置,是否显示)
 *
 */
defined('InShopNC') or exit('Access Invalid!');

class store_themeModel extends Model {
    public function __construct() {
        parent::__construct('store_theme');
    }
    
	/**
	 * 取单个模板对应的module
	 *
	 * @param String $theme_name 模板名称
	 * @return array 数组类型的返回结果
	 */
	public function getOneTheme($theme_name){
		if (!empty($theme_name)){
			$param = array();
			$param['table'] = 'store_theme';
			$param['where'] = "theme_name='".$theme_name."'";
			$param['order'] = 'sort';
			$result = Db::select($param);
			$result_new=array();
			foreach ($result as $v){
				$result_new[$v['module_name']]=$v;
// 				array_push($result_new,array($v['module_name']=>&$v));
			}
			return $result_new;
		}else {
			return false;
		}
	}
	
	/**
	 * 更新
	 *
	 * @param array $param 更新数据
	 * @return bool 布尔类型的返回结果
	 */
	public function edit($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$tmp = array();
			foreach ($param as $k => $v){
				$tmp[$k] = $v;
			}
			$where = " theme_name = '". $param['theme_name'] ."' and module_name='". $param['module_name'] ."' ";
			$result = Db::update('store_theme',$tmp,$where);
			return $result;
		}else {
			return false;
		}
	}
	
}
