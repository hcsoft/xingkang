<?php
/**
 * 满即送模型 
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
class p_mansongModel extends Model{

    const MANSONG_STATE_NORMAL = 1;
    const MANSONG_STATE_CLOSE = 2;
    const MANSONG_STATE_CANCEL = 3;

    private $mansong_state_array = array(
        0 => '全部',
        self::MANSONG_STATE_NORMAL => '正常',
        self::MANSONG_STATE_CLOSE => '已结束',
        self::MANSONG_STATE_CANCEL => '管理员关闭'
    );

    public function __construct(){
        parent::__construct('p_mansong');
    }

	/**
     * 读取满即送列表
	 * @param array $condition 查询条件
	 * @param int $page 分页数
	 * @param string $order 排序
	 * @param string $field 所需字段
     * @return array 限时折扣列表
	 *
	 */
	public function getMansongList($condition, $page=null, $order='', $field='*') {
        $mansong_list = $this->field($field)->where($condition)->page($page)->order($order)->select();
        if(!empty($mansong_list)) {
            for($i =0, $j = count($mansong_list); $i < $j; $i++) {
                $mansong_list[$i] = $this->getMansongExtendInfo($mansong_list[$i]);
            }
        }
        return $mansong_list;
	}

    /**
     * 获取店铺新满即送活动开始时间限制
     *
     */
    public function getMansongNewStartTime($store_id) {
        if(empty($store_id)) {
            return null;
        }
        $condition = array();
        $condition['store_id'] = $store_id;
        $condition['state'] = self::MANSONG_STATE_NORMAL;
        $mansong_list = $this->getMansongList($condition, null, 'end_time desc');
        return $mansong_list[0]['end_time'];
    }

    /**
	 * 根据条件读满即送信息
	 * @param array $condition 查询条件
     * @return array 限时折扣信息
	 *
	 */
    public function getMansongInfo($condition) {
        $mansong_info = $this->where($condition)->find();
        $mansong_info = $this->getMansongExtendInfo($mansong_info);
        return $mansong_info;
    }

    /**
	 * 根据满即送编号读取信息
	 * @param array $mansong_id 限制折扣活动编号
	 * @param int $store_id 如果提供店铺编号，判断是否为该店铺活动，如果不是返回null
     * @return array 限时折扣信息
	 *
	 */
    public function getMansongInfoByID($mansong_id, $store_id = 0) {
        if(intval($mansong_id) <= 0) {
            return null;
        }

        $condition = array();
        $condition['mansong_id'] = $mansong_id;
        $mansong_info = $this->getMansongInfo($condition);
        if($store_id > 0 && $mansong_info['store_id'] != $store_id) {
            return null;
        } else {
            return $mansong_info;
        }
    }

    /**
	 * 获取店铺当前可用满即送活动
	 * @param array $store_id 店铺编号 
     * @return array 满即送活动
	 *
	 */
    public function getMansongInfoByStoreID($store_id) {
        if(intval($store_id) <= 0) {
            return null;
        }

        $condition = array();
        $condition['state'] = self::MANSONG_STATE_NORMAL;
        $condition['store_id'] = $store_id;
        $condition['start_time'] = array('lt', TIMESTAMP);
        $condition['end_time'] = array('gt', TIMESTAMP);
        $mansong_list = $this->getMansongList($condition, null, 'start_time asc');

        $mansong_info = $mansong_list[0];

        if(empty($mansong_info)) {
            return null;
        }

        $model_mansong_rule = Model('p_mansong_rule');
        $mansong_info['rules'] = $model_mansong_rule->getMansongRuleListByID($mansong_info['mansong_id']);

        return $mansong_info;
    }

    /**
	 * 获取订单可用满即送规则
	 * @param array $store_id 店铺编号 
	 * @param array $order_price 订单金额
     * @return array 满即送规则
	 *
	 */
    public function getMansongRuleByStoreID($store_id, $order_price) {
        $mansong_info = $this->getMansongInfoByStoreID($store_id);

        if(empty($mansong_info)) {
            return null;
        }

        $rule_info = null;

        foreach ($mansong_info['rules'] as $value) {
            if($order_price >= $value['price']) {
                $rule_info = $value;
                $rule_info['mansong_name'] = $mansong_info['mansong_name'];
                $rule_info['start_time'] = $mansong_info['start_time'];
                $rule_info['end_time'] = $mansong_info['end_time'];
                break;
            }
        }

        return $rule_info;
    }

    /**
     * 获取满即送状态列表
     *
     */
    public function getMansongStateArray() {
        return $this->mansong_state_array;
    }

    /**
     * 获取满即送扩展信息，包括状态文字和是否可编辑状态
     * @param array $mansong_info
     * @return string
     *
     */
    public function getMansongExtendInfo($mansong_info) {
        if($mansong_info['end_time'] > TIMESTAMP) {
            $mansong_info['mansong_state_text'] = $this->mansong_state_array[$mansong_info['state']];
        } else {
            $mansong_info['mansong_state_text'] = '已结束';
        }

        if($mansong_info['state'] == self::MANSONG_STATE_NORMAL && $mansong_info['end_time'] > TIMESTAMP) {
            $mansong_info['editable'] = true;
        } else {
            $mansong_info['editable'] = false;
        }

        return $mansong_info;
    }

	/*
	 * 增加 
	 * @param array $param
	 * @return bool
     *
	 */
    public function addMansong($param){
        $param['state'] = self::MANSONG_STATE_NORMAL;
        return $this->insert($param);	
    }

    /*
	 * 更新
	 * @param array $update
	 * @param array $condition
	 * @return bool
     *
	 */
    public function editMansong($update, $condition){
        return $this->where($condition)->update($update);
    }

	/*
	 * 删除限时折扣活动，同时删除限时折扣商品
	 * @param array $condition
	 * @return bool
     *
	 */
    public function delMansong($condition){
        $mansong_list = $this->getMansongList($condition);
        $mansong_id_string = '';
        if(!empty($mansong_list)) {
            foreach ($mansong_list as $value) {
                $mansong_id_string .= $value['mansong_id'] . ',';
            }
        }

        //删除满送规则
        $model_mansong_rule = Model('p_mansong_rule');
        $model_mansong_rule->delMansongRule($condition);

        return $this->where($condition)->delete();
    }

	/*
	 * 取消满即送活动
	 * @param array $condition
	 * @return bool
     *
	 */
    public function cancelMansong($condition){
        $update = array();
        $update['state'] = self::MANSONG_STATE_CANCEL;
        return $this->editMansong($update, $condition);
    }


    /**
     * 过期满送修改状态
     */
    public function editExpireMansong() {
        $updata = array();
        $update['state'] = self::MANSONG_STATE_CLOSE;

        $condition = array();
        $condition['end_time'] = array('lt', TIMESTAMP);
        $condition['state'] = self::MANSONG_STATE_NORMAL;
        $this->editMansong($update, $condition);
    }
}
