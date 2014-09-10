<?php
/**
 * 店铺模板module 图片
 *
 */
defined('InShopNC') or exit('Access Invalid!');

class store_theme_imgModel extends Model {
    public function __construct() {
        parent::__construct('store_theme_img');
    }
    
	/**
	 * 查询列表
	 *
	 * @param array $condition 检索条件
	 * @return array 数组结构的返回结果
	 */
	public function getImgList($store_id){
		
		$result =$this->query("select i.*,t.module_name from "
				.$GLOBALS['config']['tablepre']."store_theme_img i left join "
				.$GLOBALS['config']['tablepre']."store_theme t on i.module_id=t.module_id where  i.store_id=".$store_id." order by i.position");
		
		$result_new=array();
		if(!empty($result)){
			foreach ($result as $v){
				$result_new[$v['module_name']][$v['position']-1]=$v;
			}
		}
		return $result_new;
// 		return $result;
	}
	
	public function getOne($condition){
		$condition_str = $this->_condition($condition);
		$param = array();
		$param['table'] = 'store_theme_img';
		$param['order'] = $condition['order'] ? $condition['order'] : 'position';
		$param['where'] = $condition_str;
		$result = Db::select($param,$page);
		return $result;
	}
	
	/**
	 * 构造检索条件
	 *
	 * @param int $id 记录ID
	 * @return array $rs_row 返回数组形式的查询结果
	 */
	private function _condition($condition){
		$condition_str = '';
		
		if($condition['id'] != '') {
			$condition_str	.= " and id = ".$condition['id'];
		}
		if($condition['module_id'] != '') {
			$condition_str	.= " and module_id = ".$condition['module_id'];
		}
		if($condition['store_id'] != ''){
			$condition_str	.= " and store_id =".$condition['store_id'];
		}
		if($condition['position'] != ''){
			$condition_str	.= " and position =".$condition['position'];
		}
		return $condition_str;
	}
	
	/**
	 * 新增或更新
	 *
	 * @param array $param 参数内容
	 * @return bool 布尔类型的返回结果
	 */
	public function insertorupdate($param){
		if (empty($param)){
			return false;
		}
		
		if(count($this-> getOne($param))===1){
			$this->edit($param);
			return 'edit';
		}else{
			$this->add($param);
			return 'add';
		}
	}
	
	/**
	 * 新增
	 *
	 * @param array $param 参数内容
	 * @return bool 布尔类型的返回结果
	 */
	public function add($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$tmp = array();
			foreach ($param as $k => $v){
				$tmp[$k] = $v;
			}
			$result = Db::insert('store_theme_img',$tmp);
			return $result;
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
			$where = " store_id = ". $param['store_id'] ." and module_id=". $param['module_id'] ." and position=".$param['position'];
			$result = Db::update('store_theme_img',$tmp,$where);
			return $result;
		}else {
			return false;
		}
	}
	
	/**
	 * 删除
	 *
	 * @param int $id 记录ID
	 * @return bool 布尔类型的返回结果
	 */
	public function del($id){
		if (intval($id) > 0){
			$where = " id = '". intval($id) ."'";
			$result = Db::delete('store_theme_img',$where);
			return $result;
		}else {
			return false;
		}
	}
}
