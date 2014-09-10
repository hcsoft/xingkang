<?php
/**
 * cms专题
 *
 *
 *
 * @copyright  Copyright (c) 2014-2020 SZGR Inc. (http://www.szgr.com.cn)
 * @license    http://www.szgr.com.cn
 * @link       http://www.szgr.com.cn
 * @since      File available since Release v1.1
 */
defined('InShopNC') or exit('Access Invalid!');
class specialControl extends CMSHomeControl{

    public function __construct() {
        parent::__construct();
        Tpl::output('index_sign','special');
    }

    public function indexOp() {
        $this->special_listOp();
    }

    /**
     * 专题列表
     */
    public function special_listOp() {
        $conition = array();
        $conition['special_state'] = 2; 
        $model_special = Model('cms_special');
        $special_list = $model_special->getList($conition, 10, 'special_id desc');
        Tpl::output('show_page', $model_special->showpage(2));	
        Tpl::output('special_list', $special_list);
        Tpl::showpage('special_list');
    }

    /**
     * 专题详细页
     */
    public function special_detailOp() {
        Tpl::output('index_sign', 'special');
        Tpl::showpage('special_detail');
    }
}
