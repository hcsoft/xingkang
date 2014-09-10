<?php
	$model_store_theme = Model('store_theme');
	$store_theme=$model_store_theme->getOneTheme('lexus');
	
    $model_store_img = Model('store_theme_img');
	$imglist=$model_store_img->getImgList($_SESSION['store_id']);
	
	$model_goods = Model('goods');
	$condition = array();
	$condition['store_id'] = $_SESSION['store_id'];
	$fieldstr = "goods_id,goods_commonid,goods_name,goods_jingle,store_id,store_name,goods_price,goods_marketprice,goods_storage,goods_image,goods_freight,goods_salenum,color_id,evaluation_good_star,evaluation_count";
	
	//人气商品
	$mostview = $model_goods->getLexusGoodsList($condition, $fieldstr, 'goods_click desc', 8);
	//热卖商品
	$bestsell = $model_goods->getLexusGoodsList($condition, $fieldstr, 'goods_salenum desc', 16);
?>
