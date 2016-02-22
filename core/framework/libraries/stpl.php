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
		self::$smarty->left_delimiter = '{{';
		self::$smarty->right_delimiter = '}}';
		self::$smarty->caching = Smarty::CACHING_OFF;

		global $config;

//默认平台店铺id
		self::$smarty->assign('DEFAULT_PLATFORM_STORE_ID', $config['default_store_id']);

		self::$smarty->assign('URL_MODEL',URL_MODEL);
		self::$smarty->assign('SHOP_SITE_URL', SHOP_SITE_URL);
		self::$smarty->assign('CMS_SITE_URL', CMS_SITE_URL);
		self::$smarty->assign('CIRCLE_SITE_URL', CIRCLE_SITE_URL);
		self::$smarty->assign('MICROSHOP_SITE_URL', MICROSHOP_SITE_URL);
		self::$smarty->assign('ADMIN_SITE_URL', ADMIN_SITE_URL);
		self::$smarty->assign('MOBILE_SITE_URL', MOBILE_SITE_URL);
		self::$smarty->assign('WAP_SITE_URL', WAP_SITE_URL);
		self::$smarty->assign('UPLOAD_SITE_URL',UPLOAD_SITE_URL);
		self::$smarty->assign('RESOURCE_SITE_URL',RESOURCE_SITE_URL);
		self::$smarty->assign('ADMIN_TEMPLATES_URL',ADMIN_TEMPLATES_URL);
		self::$smarty->assign('BASE_DATA_PATH',BASE_DATA_PATH);
		self::$smarty->assign('BASE_UPLOAD_PATH',BASE_UPLOAD_PATH);
		self::$smarty->assign('BASE_RESOURCE_PATH',BASE_RESOURCE_PATH);
		self::$smarty->assign('lang',Language::getLangContent());
		self::$smarty->assign('html_title','');
		self::$smarty->assign('CHARSET','utf8');
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
		self::$smarty->assign('tpl_name',$page_name);

		self::$smarty->display('layout/layout.tpl');
	}

	public static function showTrace()
	{
		$trace = array();
		//当前页面
//		$trace[Language::get('nc_debug_current_page')] =  $_SERVER['REQUEST_URI'].'<br>';
//    	//请求时间
//        $trace[Language::get('nc_debug_request_time')] =  date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']).'<br>';
//        //系统运行时间
//        $query_time = number_format((microtime(true)-StartTime),3).'s';
//        $trace[Language::get('nc_debug_execution_time')] = $query_time.'<br>';
//		//内存
//		$trace[Language::get('nc_debug_memory_consumption')] = number_format(memory_get_usage()/1024/1024,2).'MB'.'<br>';
//		//请求方法
//        $trace[Language::get('nc_debug_request_method')] = $_SERVER['REQUEST_METHOD'].'<br>';
//        //通信协议
//        $trace[Language::get('nc_debug_communication_protocol')] = $_SERVER['SERVER_PROTOCOL'].'<br>';
//        //用户代理
//        $trace[Language::get('nc_debug_user_agent')] = $_SERVER['HTTP_USER_AGENT'].'<br>';
//        //会话ID
//        $trace[Language::get('nc_debug_session_id')] = session_id().'<br>';
		//执行日志
		$log = Log::read();
		$trace[Language::get('nc_debug_logging')] = count($log) ? count($log) . Language::get('nc_debug_logging_1') . '<br/>' . implode('<br/>', $log) : Language::get('nc_debug_logging_2');
		$trace[Language::get('nc_debug_logging')] = $trace[Language::get('nc_debug_logging')] . '<br>';
		//文件加载
//		$files =  get_included_files();
//		$trace[Language::get('nc_debug_load_files')] = count($files).str_replace("\n",'<br/>',substr(substr(print_r($files,true),7),0,-2)).'<br>';
		return $trace;
	}
}
