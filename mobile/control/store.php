<?php

defined('InShopNC') or exit('Access Invalid!');

class storeControl extends mobileHomeControl {
	
	public function __construct() {
		parent::__construct();
	}
	
    /**
     * 商家列表
     */
    function store_listOp() {
        $model_store = Model('store');
        $shop_list = $model_store->getShowList();
    	     
        
        //*******你当前坐标确定
        $ch = curl_init("http://api.map.baidu.com/location/ip?ak=AC567a85b2886efaaafa2aa09f683fbd&coor=bd09ll") ;
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
		$baiduresult=curl_exec($ch) ;
		$baiduresult=json_decode($baiduresult,true);
		curl_close($ch);
		$baidu_location = array();
		$baidu_location[] = $baiduresult['content']['point']['x'];
		$baidu_location[] = $baiduresult['content']['point']['y'];
		$location_you = implode('', $baidu_location);
		//////\\\\\\\\*******你当前坐标
		
		
		
		foreach ($shop_list as $key => $value) {
		  	$file = $value['store_label'];
            if (empty($file)){
            	$shop_list[$key]['store_image_url'] = UPLOAD_SITE_URL.'/'.'shop/common/'.'default_store_logo.gif';
            }else{
            	$shop_list[$key]['store_image_url'] = UPLOAD_SITE_URL.'/'.'shop/common/'.$file;
            }
            $shop_list[$key]['good_star']= rand(3, 5);
            $shop_list[$key]['rq'] = rand(100,200);
			$location = $value['location'];
			//$distance = $this->GetDistance($this->BaiduConvertToGPS($location_you), $this->BaiduConvertToGPS($location));
			//  explode
			$distance = $this->GetDistance($location_you,$location );
			
			$shop_list[$key]['distance'] = rand(500, 600);
		}
		
		
		//按curkey 2= 人气 3 = 信誉 4 = 距离排序
		
		//$store_list  = $this->array_sort($shop_list,'distance');
		
		$curkey = $_GET['curkey'];
		if (!empty($curkey)){
			if ($curkey==2){
				$store_list  = $this->array_sort($shop_list,'rq');
			}
			if ($curkey==3){
					$store_list  = $this->array_sort($shop_list,'good_star');
			}
			if ($curkey==4){
					$store_list  = $this->array_sort($shop_list,'distance');
			}
		}
		$store_list = $shop_list;
        output_data(array('store_list' => $store_list));
    }
    //排序算法
    
	function array_sort($arr,$keys,$type='asc'){ 
		$keysvalue = $new_array = array();
		foreach ($arr as $k=>$v){
			$keysvalue[$k] = $v[$keys];
		}
		if($type == 'asc'){
			asort($keysvalue);
		}else{
			arsort($keysvalue);
		}
		reset($keysvalue);
		foreach ($keysvalue as $k=>$v){
			$new_array[$k] = $arr[$k];
		}
		return $new_array; 
	}
	
    /**
	 * 
	 * Enter description here ...
	 * @param $offset
	 */
	public function BaiduConvertToGPS($offset){
		//取出坐标值
		$offset_arr = explode(',', $offset);
		$x1 = $offset_arr[0];
		$y1 = $offset_arr[1];
		//将百度坐标当成GPS坐标并调用百度的转换接口
		$baidu_str = @file_get_contents('http://api.map.baidu.com/ag/coord/convert?from=0&to=4&x='.$x1.'&y='.$y1);
		//解析json数据
		$baidu_str_arr = json_decode($baidu_str, true);
		//获取转换后的百度坐标值
		$x2 = base64_decode($baidu_str_arr['x']);
		$y2 = base64_decode($baidu_str_arr['y']);
		//计算GPS坐标值
		$x = 2*$x1 - $x2;
		$y = 2*$y1 - $y2;
		return $x.','.$y;
	}
	 
	
	 
	/**
	 * @method:计算GPS两点间的距离
	 */
	public function GetDistance($s, $e){
		$s = explode(',', $s);
		$e = explode(',', $e);
		//地球半径
		$r = 6378137;
		//经度
		$lat1 = $s[1]*pi()/180;
		$lat2 = $e[1]*pi()/180;
		$a = $lat1 - $lat2;
		$b = ($s[0] - $e[0])*pi()/180;
		$sa2 = sin($a/2);
		$sb2 = sin($b/2);
		$distance = 2*$r*asin(sqrt($sa2*$sa2 + cos($lat1)*cos($lat2)*$sb2*$sb2));
		//$distance = $r*acos($sa2*$sa2 + cos($lat1)*cos($lat2)*$sb2*$sb2);
		return $distance;
	}
    
    
    
    
    
    
    
    /**
     * 商家Wap首页查询   store_detail 
     * 本页面包括该商家广告     单列      两列     四列 
     */
    public function store_detailOp() {
		$model_store = Model('store');
		$model_goods = Model('goods');
		$model_mb_store_ad = Model('mb_store_ad');
		$model_mb_store_home = Model('mb_store_home');
		
		$datas = array();
		
		//店铺广告
//		$adv_list = array();
//        $mb_ad_list = $model_mb_ad->getMbAdList(array(), null, 'link_sort asc');
//        foreach ($mb_ad_list as $value) {
//            $adv = array();
//            $adv['image'] = $value['link_pic_url'];
//            $adv['keyword'] = $value['link_keyword'];
//            $adv_list[] = $adv;
//        }
//      $datas['adv_list'] = $adv_list;

		$condition = array();
		if(!empty($_GET['store_id']) && intval($_GET['store_id']) > 0) {
            $condition['store_id'] = $_GET['store_id'];
        } 
        
		$store_ad_list = array();
		$store_ad_ = $model_mb_store_ad->getMbStoreAdList($condition, null, 'link_sort asc');
		
     	foreach ($store_ad_ as $value) {
            $ads = array();
            $ads['image'] = $value['link_pic_url'];
            $ads['keyword'] = $value['link_keyword'];
            $store_ad_list[] = $ads;
        }
		$datas['store_ad_list'] = $store_ad_list;
		
		
		
		//店铺首页 中的 一列        两列      四列  
        $store_home_type1_list = array();
        $store_home_type2_list = array();
        $store_home_type41_list = array();
        $store_home_type42_list = array();
        $mb_home_list = $model_mb_store_home->getMbStoreHomeList($condition, null, 'h_sort asc');
        foreach ($mb_home_list as $value) {
            $home = array();
            $home['image'] = $value['h_img_url'];
            $home['title'] = $value['h_title'];
            $home['desc'] = $value['h_desc'];
            //$home['keyword'] = $value['h_keyword'];
            if($value['h_type'] == 'type1') {
                //$home['keyword1'] = $value['h_multi_keyword'];
                $store_home_type1_list[] = $home;
            } elseif($value['h_type'] == 'type2') {
                $store_home_type2_list[] = $home;
            } elseif($value['h_type'] == 'type41') {
                $store_home_type41_list[] = $home;
            } else {
                $store_home_type42_list[] = $home;
            }
        }
        $datas['home1'] = $store_home_type1_list;
        $datas['home2'] = $store_home_type2_list;
        $datas['home41'] = $store_home_type41_list;
        $datas['home42'] = $store_home_type42_list;
		
		//店铺商品  
        // $field = '*', $group = '',$order = ''
        
        $order = 'goods_salenum';
        $goods_list_0 = $model_goods->getGoodsList($condition,$field = '*', $group = '',$order = '');
        //商品图片
        $goods_list = array(); 
        foreach ( $goods_list_0  as $key => $value) {
        	$goods_list[$key]['goods_id']  = $value['goods_id'];
        	$goods_list[$key]['goods_sub_name']  = mb_substr($value['goods_name'],0, 8,'utf-8')."...";
        	$goods_list[$key]['goods_name']  = $value['goods_name'];
        	//$goods_list[$key]['goods_name']  = $value['goods_name'];
        	$goods_list[$key]['goods_price']  = $value['goods_price'];
        	$goods_list[$key]['goods_marketprice']  = $value['goods_marketprice'];
        	$goods_list[$key]['goods_salenum']  = $value['goods_salenum'];
        	//商品图片url
            $goods_list[$key]['goods_image_url'] = cthumb($value['goods_image'], 360, $value['store_id']); 
            
        }
        $datas['goods'] = $goods_list;
        output_data($datas);;
        
		
    }
    /*
     * 商家信息  getStoreInfo
     */
    public function store_infoOp(){
    	$model_store = Model('store');
    	 if(!empty($_GET['store_id']) && intval($_GET['store_id']) > 0) {
            $condition['store_id'] = $_GET['store_id'];
    	 }
    	 $store_info = $model_store->getStoreInfo($condition);
    	 //echo $store_info;
    	 output_data($store_info);
    }
    
    
    /*
     * 商家多种查询 包括距离、人气、信誉
     */
    public function goods_listOp() {
        $model_goods = Model('store');

        //查询条件
        $condition = array();
        if(!empty($_GET['gc_id']) && intval($_GET['gc_id']) > 0) {
            $condition['gc_id'] = $_GET['gc_id'];
        } elseif (!empty($_GET['keyword'])) { 
            $condition['goods_name|goods_jingle'] = array('like', '%' . $_GET['keyword'] . '%');
        }

        //所需字段
        $fieldstr = "goods_id,goods_commonid,store_id,goods_name,goods_price,goods_marketprice,goods_image,goods_salenum,evaluation_good_star,evaluation_count";

        //排序方式
        $order = $this->_goods_list_order($_GET['key'], $_GET['order']);

        $goods_list = $model_goods->getGoodsListByColorDistinct($condition, $fieldstr, $order, $this->page);
        $page_count = $model_goods->gettotalpage();

        //处理商品列表(团购、限时折扣、商品图片)
        $goods_list = $this->_goods_list_extend($goods_list);

        output_data(array('goods_list' => $goods_list), mobile_page($page_count));
    }
    
    
 
    
    
    
    
    
    

	function image_path($file, $type = '') {
	    $type_array = explode(',_', ltrim(GOODS_IMAGES_EXT, '_'));
	    if (!in_array($type, $type_array)) {
	        $type = '240';
	    }
	    if (empty($file)) {
	        return UPLOAD_SITE_URL . '/' . defaultGoodsImage ( $type );
	    }
	    $search_array = explode(',', GOODS_IMAGES_EXT);
	    $file = str_ireplace($search_array,'',$file);
	    $fname = basename($file);
	   
	    // 本地存储时，增加判断文件是否存在，用默认图代替
	    if ( !file_exists(BASE_UPLOAD_PATH . '/' . ATTACH_GOODS . '/' . $store_id . '/' . ($type == '' ? $file : str_ireplace('.', '_' . $type . '.', $file)) )) {
	        return UPLOAD_SITE_URL.'/'.defaultGoodsImage($type);
	    }
	    $thumb_host = UPLOAD_SITE_URL . '/' . ATTACH_GOODS;
	    return $thumb_host . '/' . $store_id . '/' . ($type == '' ? $file : str_ireplace('.', '_' . $type . '.', $file));
		if ( !file_exists(UPLOAD_SITE_URL.'/'.'shop/common/'.$file)) {
	        return UPLOAD_SITE_URL.'/'.defaultGoodsImage($type);
	    }
	    return UPLOAD_SITE_URL.'/'.'shop/common/'.$file;
	    
	    
	}
    
    
	 /**
     * 购物车列表
     */
//    public function seller_listOp() {
//        $model_cart = Model('cart');
//
//        $condition = array('buyer_id' => $this->member_info['member_id']);
//        $cart_list	= $model_cart->listCart('db', $condition);
//        $sum = 0;
//        foreach ($cart_list as $key => $value) {
//            $cart_list[$key]['goods_image_url'] = cthumb($value['goods_image'], $value['store_id']);
//            $cart_list[$key]['goods_sum'] = ncPriceFormat($value['goods_price'] * $value['goods_num']);
//            $sum += $cart_list[$key]['goods_sum'];
//        }
//
//        output_data(array('cart_list' => $cart_list, 'sum' => ncPriceFormat($sum)));
//    }
//    
    
    
    /**
     * 购物车添加
     */
    public function cart_addOp() {
        $goods_id = intval($_POST['goods_id']);
        $quantity = intval($_POST['quantity']);
        if($goods_id <= 0 || $quantity <= 0) {
            output_error('参数错误');
        }

        $model_goods = Model('goods');
        $model_cart	= Model('cart');

        $goods_info = $model_goods->getGoodsOnlineInfo(array('goods_id' => $goods_id));
        //判断是不是在限时折扣中，如果是返回折扣信息
        $xianshi_info = $model_cart->getXianshiInfo($goods_info, $quantity);
        if (!empty($xianshi_info)) {
            $goods_info = $xianshi_info;
        }

        //验证是否可以购买
		if(empty($goods_info)) {
            output_error('商品不存在');
		}
        if ($goods_info['store_id'] == $this->member_info['store_id']) {
            output_error('不能购买自己发布的商品');
		}
		if(intval($goods_info['goods_storage']) < 1 || intval($goods_info['goods_storage']) < $quantity) {
            output_error('库存不足');
		}

        $param = array();
        $param['buyer_id']	= $this->member_info['member_id'];
        $param['store_id']	= $goods_info['store_id'];
        $param['goods_id']	= $goods_info['goods_id'];
        $param['goods_name'] = $goods_info['goods_name'];
        $param['goods_price'] = $goods_info['goods_price'];
        $param['goods_image'] = $goods_info['goods_image'];
        $param['store_name'] = $goods_info['store_name'];

        $result = $model_cart->addCart($param, 'db', $quantity);
        if($result) {
            output_data('1');
        } else {
            output_error('收藏失败');
        }
    }

    /**
     * 购物车删除
     */
    public function cart_delOp() {
        $cart_id = intval($_POST['cart_id']);
        
        $model_cart = Model('cart');

        if($cart_id > 0) {
            $condition = array();
            $condition['buyer_id'] = $this->member_info['member_id'];
            $condition['cart_id'] = $cart_id;

            $model_cart->delCart('db', $condition);
        }

        output_data('1');
    }

    /**
     * 更新购物车购买数量
     */
    public function cart_edit_quantityOp() {
		$cart_id = intval(abs($_POST['cart_id']));
		$quantity = intval(abs($_POST['quantity']));
		if(empty($cart_id) || empty($quantity)) {
            output_error('参数错误');
		}

		$model_cart = Model('cart');

        $cart_info = $model_cart->getCartInfo(array('cart_id'=>$cart_id, 'buyer_id' => $this->member_info['member_id']));

        //检查是否为本人购物车
        if($cart_info['buyer_id'] != $this->member_info['member_id']) {
            output_error('参数错误');
        }

        //检查库存是否充足
        if(!$this->_check_goods_storage($cart_info, $quantity, $this->member_info['member_id'])) {
            output_error('库存不足');
        }

		$data = array();
        $data['goods_num'] = $quantity;
        $update = $model_cart->editCart($data, array('cart_id'=>$cart_id));
		if ($update) {
		    $return = array();
            $return['quantity'] = $quantity;
			$return['goods_price'] = ncPriceFormat($cart_info['goods_price']);
			$return['total_price'] = ncPriceFormat($cart_info['goods_price'] * $quantity);
            output_data($return);
		} else {
            output_error('修改失败');
		}
    }

    /**
     * 检查库存是否充足 
     */
    private function _check_goods_storage($cart_info, $quantity, $member_id) {
		$model_goods= Model('goods');
        $model_bl = Model('p_bundling');

		if ($cart_info['bl_id'] == '0') {
            //普通商品
		    $goods_info	= $model_goods->getGoodsOnlineInfo(array('goods_id' => $cart_info['goods_id']));

		    if(intval($goods_info['goods_storage']) < $quantity) {
                return false;
		    }
		} else {
		    //优惠套装商品
		    $bl_goods_list = $model_bl->getBundlingGoodsList(array('bl_id' => $cart_info['bl_id']));
		    $goods_id_array = array();
		    foreach ($bl_goods_list as $goods) {
		        $goods_id_array[] = $goods['goods_id'];
		    }
		    $bl_goods_list = $model_goods->getGoodsOnlineList(array('goods_id' => array('in', $goods_id_array)));

		    //如果有商品库存不足，更新购买数量到目前最大库存
		    foreach ($bl_goods_list as $goods_info) {
		        if (intval($goods_info['goods_storage']) < $quantity) {
                    return false;
		        }
		    }
		}
        return true;
    }

}
