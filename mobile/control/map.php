<?php 
defined('InShopNC') or exit('Access Invalid!');
class mapControl extends mobileHomeControl {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function mapOp(){
		$ch = curl_init("http://api.map.baidu.com/location/ip?ak=AC567a85b2886efaaafa2aa09f683fbd&coor=bd09ll") ;
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
		$baiduresult=curl_exec($ch) ;
		$baiduresult=json_decode($baiduresult,true);
		curl_close($ch);
		
//		$model_store = Model('store');
//        $shop_list = $model_store->getShowList();
		
		output_data( $baiduresult);
	}
	
}

