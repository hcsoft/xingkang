<?php
defined('InShopNC') or exit('Access Invalid!');



/**
 * 取得商店图片
 *
 * @param string $image_name
 * @return string
 */
function storeImage($image_name = '') {
	if ($image_name != '') {
		return UPLOAD_SITE_URL.'/'.ATTACH_STORE.'/'.$image_name;
	}
	return UPLOAD_SITE_URL.'/'.ATTACH_STORE.'/default_store_logo.gif';
}

/**
 * 取得商品图片
 *
 * @param string $image_name
 * @return string
 */
function goodImage($image_name = '',$store_id = '') {
	
	if ($image_name != '') {
		return  cthumb($image_name, 240, $store_id);
	}
	return UPLOAD_SITE_URL.'/'.ATTACH_GOODS.'/default_goods_image.gif';
}


/**
 * 删除地址参数
 * 
 * @param array $param
 */
function dropParam($param) {
    $purl = getParam();
    if (!empty($param)) {
        foreach ($param as $val) {
            $purl['param'][$val]= 0;
        }
    }
    return urlShop($purl['act'], $purl['op'], $purl['param']);
}

/**
 * 替换地址参数
 * 
 * @param array $param
 */
function replaceParam($param) {
    $purl = getParam();
    if (!empty($param)) {
        foreach ($param as $key => $val) {
            $purl['param'][$key] = $val;
        }
    }
    
    return urlShop($purl['act'], $purl['op'], $purl['param']);
}

/**
 * 删除部分地址参数
 * 
 * @param array $param
 */
function removeParam($param) {
    $purl = getParam();
    if (!empty($param)) {
        foreach ($param as $key => $val) {
            if (!isset($purl['param'][$key])) {
                continue;
            }
            $tpl_params = explode('_', $purl['param'][$key]);
            foreach ($tpl_params as $k=>$v) {
                if ($val == $v) {
                    unset($tpl_params[$k]);
                }
            }
            if (empty($tpl_params)) {
                $purl['param'][$key] = 0;
            } else {
                $purl['param'][$key] = implode('_', $tpl_params);
            }
        }
    }
    return urlShop($purl['act'], $purl['op'], $purl['param']);
}

function getParam() {
    $param = $_GET;
    $purl = array();
    $purl['act'] = $param['act'];
    unset($param['act']);
    $purl['op'] = $param['op'];
    unset($param['op']); unset($param['curpage']);
    $purl['param'] = $param;
    return $purl;
}
