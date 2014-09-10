<?php
/**
 * 模板 lexus Control
 */
defined('InShopNC') or exit('Access Invalid!');

class store_theme_setting_lexusControl extends BaseSellerControl {
	public function __construct() {
		parent::__construct();
		Language::read('member_store_index');
	}
	
	/**
	 * 卖家店铺主题设置
	 *
	 * @param string
	 * @param string
	 * @return
	 */
	public function theme_settingOp(){
		/**
		 * 模型实例化
		 */
		$model_store_theme = Model('store_theme');
		$model_store_img = Model('store_theme_img');
		$model_upload = Model('upload');
		
		$store_theme=$model_store_theme->getOneTheme('lexus');
		/**
		 * 保存模板设置信息
		*/
		if ($_POST['form_submit'] == 'ok'){
			//是否显示
			$model_store_theme->edit(array( 'theme_name'=>'lexus',
					'module_name'=>'slideshow',
					'isshow'=>$_POST['slideshow']));
			$model_store_theme->edit(array( 'theme_name'=>'lexus',
					'module_name'=>'showcase',
					'isshow'=>$_POST['showcase']));
			$model_store_theme->edit(array( 'theme_name'=>'lexus',
					'module_name'=>'promotion',
					'isshow'=>$_POST['promotion']));
			$model_store_theme->edit(array( 'theme_name'=>'lexus',
					'module_name'=>'mostview',
					'isshow'=>$_POST['mostview']));
			$model_store_theme->edit(array( 'theme_name'=>'lexus',
					'module_name'=>'goods_ad',
					'isshow'=>$_POST['goods_ad']));
			$model_store_theme->edit(array( 'theme_name'=>'lexus',
					'module_name'=>'bestsell',
					'isshow'=>$_POST['bestsell']));
			$model_store_theme->edit(array( 'theme_name'=>'lexus',
					'module_name'=>'store_blog',
					'isshow'=>$_POST['store_blog']));
			
			//处理设置图片信息
			
			/**/
			//slideshow  张幻灯片
			foreach ($_POST['image_path'] as $i => $image){
				$model_store_img->insertorupdate(
						array(  'store_id'=>$_SESSION['store_id'],
								'module_id'=>$store_theme['slideshow']['module_id'],
								'img'=>$image===''?$_POST['image_path_old'][$i]:$image,
								'text1'=>$_POST['text1'][$i],
								'text2'=>$_POST['text2'][$i],
								'text3'=>$_POST['text3'][$i],
								'text4'=>$_POST['text4'][$i],
								'url'=>'http://'===$_POST['url'][$i]?'':$_POST['url'][$i],
								'position'=>($i+1)));
				// 删除upload表中数据
				$model_upload->delByWhere(array('upload_type'=>7,'store_id'=>$_SESSION['store_id'],'file_name'=>$image));
				//删除原来的图片
				if($image!='' &&  $_POST['image_path_old'][$i]!='' && $_POST['image_path_old'][$i]!=$image){
					@unlink(BASE_UPLOAD_PATH.DS.ATTACH_SLIDE.DS.$_POST['image_path_old'][$i]);
				}
			}
			//showcase 静态广告一 /商家logo
			foreach ($_POST['showcase_pic'] as $i => $image){
				if($image==='' && $_POST['showcase_url'][$i]==='http://'){
					continue;
				}
				$model_store_img->insertorupdate(
						array(  'store_id'=>$_SESSION['store_id'],
								'module_id'=>$store_theme['showcase']['module_id'],
								'img'=>$image===''?$_POST['showcase_pic_old'][$i]:$image,
								'url'=>'http://'===$_POST['showcase_url'][$i]?'':$_POST['showcase_url'][$i],
								'position'=>($i+1)));
				// 删除upload表中数据
				$model_upload->delByWhere(array('upload_type'=>7,'store_id'=>$_SESSION['store_id'],'file_name'=>$image));
				//删除原来的图片
				if($image!='' && $_POST['showcase_pic_old'][$i]!='' && $_POST['showcase_pic_old'][$i]!=$image){
					@unlink(BASE_UPLOAD_PATH.DS.ATTACH_SLIDE.DS.$_POST['showcase_pic_old'][$i]);
				}
			}
			//promotion 静态广告二(三张图片) 折扣商品
			foreach ($_POST['promotion_pic'] as $i => $image){
				if($image==='' && $_POST['promotion_url'][$i]==='http://'){
					continue;
				}
				$model_store_img->insertorupdate(
						array(  'store_id'=>$_SESSION['store_id'],
								'module_id'=>$store_theme['promotion']['module_id'],
								'img'=>$image===''?$_POST['promotion_pic_old'][$i]:$image,
								'url'=>'http://'===$_POST['promotion_url'][$i]?'':$_POST['promotion_url'][$i],
								'position'=>($i+1)));
				// 删除upload表中数据
				$model_upload->delByWhere(array('upload_type'=>7,'store_id'=>$_SESSION['store_id'],'file_name'=>$image));
				//删除原来的图片
				if($image!='' && $_POST['promotion_pic_old'][$i]!='' && $_POST['promotion_pic_old'][$i]!=$image){
					@unlink(BASE_UPLOAD_PATH.DS.ATTACH_SLIDE.DS.$_POST['promotion_pic_old'][$i]);
				}
			}
			//goods_ad 静态广告三(二张图片)
			foreach ($_POST['goods_ad_pic'] as $i => $image){
				if($image==='' && $_POST['goods_ad_url'][$i]==='http://'){
					continue;
				}
				$model_store_img->insertorupdate(
						array(  'store_id'=>$_SESSION['store_id'],
								'module_id'=>$store_theme['goods_ad']['module_id'],
								'img'=>$image===''?$_POST['goods_ad_pic_old'][$i]:$image,
								'url'=>'http://'===$_POST['goods_ad_url'][$i]?'':$_POST['goods_ad_url'][$i],
								'position'=>($i+1)));
				// 删除upload表中数据
				$model_upload->delByWhere(array('upload_type'=>7,'store_id'=>$_SESSION['store_id'],'file_name'=>$image));
				//删除原来的图片
				if($image!='' && $_POST['goods_ad_pic_old'][$i]!='' && $_POST['goods_ad_pic_old'][$i]!=$image){
					@unlink(BASE_UPLOAD_PATH.DS.ATTACH_SLIDE.DS.$_POST['goods_ad_pic_old'][$i]);
				}
			}
			showDialog(Language::get('nc_common_save_succ'),'index.php?act=store_theme_setting_lexus&op=theme_setting','succ');
		}
		
		// 删除upload中的无用数据
		$upload_info = $model_upload->getUploadList(array('upload_type'=>7,'store_id'=>$_SESSION['store_id']),'file_name');
		if(is_array($upload_info) && !empty($upload_info)){
			foreach ($upload_info as $val){
				@unlink(BASE_UPLOAD_PATH.DS.ATTACH_SLIDE.DS.$val['file_name']);
			}
		}
		$model_upload->delByWhere(array('upload_type'=>7,'store_id'=>$_SESSION['store_id']));
		
		
		//查询
		$imglist=$model_store_img->getImgList($_SESSION['store_id']);
		Tpl::output('imglist',$imglist);
		Tpl::output('store_theme',$store_theme);
		
		
		self::profile_menu('store_theme_setting');
		Tpl::output('menu_sign','store_theme_setting_lexus');
		Tpl::output('menu_sign_url','index.php?act=store_theme_setting_lexus&op=theme_setting');
		Tpl::output('menu_sign1','store_theme_setting_lexus');
		
		/**
		 * 页面输出
		*/
		Tpl::showpage('store_theme_setting_lexus');
	}
	

	
	/**
	 * 店铺幻灯片ajax上传
	 */
	public function silde_image_uploadOp(){
		$upload = new UploadFile();
		$upload->set('default_dir',ATTACH_SLIDE);
		$upload->set('max_size',C('image_max_filesize'));
		
		$result = $upload->upfile($_POST['id']);
		
		
		$output	= array();
		if(!$result){
			/**
			 * 转码
			 */
			if (strtoupper(CHARSET) == 'GBK'){
				$upload->error = Language::getUTF8($upload->error);
			}
			$output['error']	= $upload->error;
			echo json_encode($output);die;
		}
		
		$img_path = $upload->file_name;
		
		/**
		 * 模型实例化
		 */
		$model_upload = Model('upload');
		
		if(intval($_POST['file_id']) > 0){
			$file_info = $model_upload->getOneUpload($_POST['file_id']);
			@unlink(BASE_UPLOAD_PATH.DS.ATTACH_SLIDE.DS.$file_info['file_name']);
			
			$update_array	= array();
			$update_array['upload_id']	= intval($_POST['file_id']);
			$update_array['file_name']	= $img_path;
			$update_array['file_size']	= $_FILES[$_POST['id']]['size'];
			$model_upload->update($update_array);

			$output['file_id']	= intval($_POST['file_id']);
			$output['id']		= $_POST['id'];
			$output['file_name']	= $img_path;
			echo json_encode($output);die;
		}else{
			/**
			 * 图片数据入库
			 */
			$insert_array = array();
			$insert_array['file_name']		= $img_path;
			$insert_array['upload_type']	= '7';
			$insert_array['file_size']		= $_FILES[$_POST['id']]['size'];
			$insert_array['store_id']		= $_SESSION['store_id'];
			$insert_array['upload_time']	= time();
			
			$result = $model_upload->add($insert_array);
			
			if(!$result){
				@unlink(BASE_UPLOAD_PATH.DS.ATTACH_SLIDE.DS.$img_path);
				$output['error']	= Language::get('store_slide_upload_fail','UTF-8');
				echo json_encode($output);die;
			}
			
			$output['file_id']	= $result;
			$output['id']		= $_POST['id'];
			$output['file_name']	= $img_path;
			echo json_encode($output);die;
		}
	}

	/**
	 * ajax删除幻灯片图片
	 */
	public function dorp_imgOp(){
		
		$model_store_theme = Model('store_theme');
		$store_theme=$model_store_theme->getOneTheme('lexus');
		
		$model_store_img = Model('store_theme_img');
		/**/
		$model_store_img->insertorupdate(
				array(  'store_id'=>$_SESSION['store_id'],
						'module_id'=>$store_theme['slideshow']['module_id'],
						'img'=>'',
						'position'=>($_GET['index']+1)));
		@unlink(BASE_UPLOAD_PATH.DS.ATTACH_SLIDE.DS.$_GET['img_src']);
		echo json_encode(array('succeed'=>Language::get('nc_common_save_succ','UTF-8')));die;
	}

	
	
	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return 
	 */
	private function profile_menu($menu_key='') {
		Language::read('member_layout');
        $menu_array = array(
            1=>array('menu_key'=>'store_setting','menu_name'=>Language::get('nc_member_path_store_config'),'menu_url'=>'index.php?act=store_setting&op=store_setting'),
            4=>array('menu_key'=>'store_slide','menu_name'=>Language::get('nc_member_path_store_slide'),'menu_url'=>'index.php?act=store_setting&op=store_slide'),
            5=>array('menu_key'=>'store_theme','menu_name'=>'店铺主题','menu_url'=>'index.php?act=store_setting&op=theme')
        );
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
    
}
