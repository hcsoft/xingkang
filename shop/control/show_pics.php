<?php
/**
 * 显示图片 
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

class show_picsControl extends BaseMemberControl {

	public function indexOp(){

        $type = trim($_GET['type']);
        if(empty($_GET['pics'])) {
            $this->goto_index();
        }
        $pics = explode('|',trim($_GET['pics']));
        $pic_path = '';
        switch ($type) {
            case 'inform':
                $pic_path = UPLOAD_SITE_URL.DS.'shop/inform/'; 
                break;
            case 'complain':
                $pic_path = UPLOAD_SITE_URL.DS.'shop/complain/';
                break;
            default:
                $this->goto_index();
                break;
        }

        Tpl::output('pic_path',$pic_path);
		Tpl::output('pics',$pics);
		//输出页面
		Tpl::showpage('show_pics','null_layout');
	}

    private function goto_index() {
	    @header("Location: index.php?act=member_snsindex");
		exit;
    }
}
