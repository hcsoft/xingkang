<?php
/**
 * 模板驱动
 *
 * 模板驱动，商城模板引擎
 *
 *
 * @package    tpl
 * @copyright  Copyright (c) 2014-2020 SZGR Inc. (http://www.szgr.com.cn)
 * @license    http://www.szgr.com.cn
 * @link       http://www.szgr.com.cn
 * @author	   ShopNC Team
 * @since      File available since Release v1.1
 */
defined('InShopNC') or exit('Access Invalid!');
define('SMARTY_DIR',BASE_PATH.'/include/Smarty/');
require(SMARTY_DIR.'Smarty.class.php');

//require(BASE_PATH.'/include/Smarty/Smarty.class.php');
class STpl{
	/**
	 * 单件对象
	 */
	private static $instance = null;
	/**
	 * 模板路径设置
	 */
	private static $tpl_dir='';


	/**
	 * STpl constructor.
	 */
	private static $smarty = null;

	private function __construct(){

		self::$smarty = new Smarty();
		if (!empty(self::$tpl_dir)){
			$tpl_folder = self::$tpl_dir.DS;
		}
		if (!defined('TPL_NAME')) define('TPL_NAME','default');

		$tpl_dir = './templates/'.TPL_NAME.'/'.$tpl_folder;
		self::$smarty->template_dir = $tpl_dir;
		self::$smarty->compile_dir =  './templates_c/'.TPL_NAME.DS.$tpl_folder;
		self::$smarty->config_dir = './config/tpl/';
		self::$smarty->cache_dir = './tmp/';
		self::$smarty->caching = Smarty::CACHING_LIFETIME_CURRENT;
	}
	
	/**
	 * 实例化
	 *
	 * @return obj
	 */
	public static function getInstance(){
		if (self::$instance === null || !(self::$instance instanceof STpl)){
			self::$instance = new STpl();
		}
		return self::$instance;
	}
	
	/**
	 * 设置模板目录
	 *
	 * @param string $dir
	 * @return bool
	 */
	public static function setDir($dir){
		self::$tpl_dir = $dir;
		return true;
	}

	/**
	 * 抛出变量
	 *
	 * @param mixed $output
	 * @param  $input
	 */
	public static function output($output,$input=''){
		self::getInstance();

		self::$smarty->assign($output,$input);
	}
	
	/**
	 * 调用显示模板
	 *
	 * @param string $page_name
	 */
	public static function showpage($page_name=''){
		self::$smarty->display($page_name);
	}
}
