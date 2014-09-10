<?php
/**
 * 前台店铺列表
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
class store_listControl extends BaseHomeControl {
	public function indexOp(){
		//读取语言包
		Language::read('home_store_list');
		//分类导航
		$nav_link = array(
			0=>array(
				'title'=>Language::get('homepage'),
				'link'=>'index.php'
			),
			1=>array(
				'title'=>Language::get('brand_index_all_brand')
			)
		);
		Tpl::output('nav_link_list',$nav_link);
		
        $model_store = Model('store');
        //店铺列表
//         $store_list = $model_store->getStoreList($condition, 10);

        $store_list=$model_store->getShowList(true,true);    

        Tpl::output('store',$store_list);
//         Tpl::output('brand_class',$brand_class);
//         Tpl::output('brand_r',$brand_r_list);
//         Tpl::output('html_title',Language::get('brand_index_brand_list'));
		
        //分页头
        Tpl::output('show_page1', $model_store->showpage(4));
        //分页尾
        Tpl::output('show_page', $model_store->showpage(5));
        //加载 shop/framework/function/search.php
        loadfunc('search');
		//页面输出
		Tpl::output('index_sign','store');
		Model('seo')->type('store')->show();
		Tpl::showpage('store_list');
	}
	
}
