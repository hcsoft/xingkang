<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<style>
.cb-enable, .cb-disable, .cb-enable span, .cb-disable span { background: url(<?php echo ADMIN_SITE_URL;?>/templates/default/images/form_onoff.png) repeat-x; display: block; float: left;}
.cb-enable span, .cb-disable span { font-weight: bold; line-height: 24px; background-repeat: no-repeat; display: block;}
.cb-enable span { background-position: left -72px; padding: 0 10px;}
.cb-disable span { background-position: right -144px; padding: 0 10px;}
.cb-disable.selected { background-position: 0 -24px;}
.cb-disable.selected span { background-position: right -168px; color: #fff;}
.cb-enable.selected { background-position: 0 -48px;}
.cb-enable.selected span { background-position: left -120px; color: #fff;}
.noborder, .noborder td{ border-bottom:0; border-top:0;vertical-align: middle; }
.noborder td.tips{ color: #999; vertical-align: middle; }
.noborder td.tips:hover, .normalfont { color: #000;}
.vatop { vertical-align:top; }
.type-file-box { position:relative; width:256px; height:25px; margin:0; padding:0; float:left;}
.type-file-text{ width:187px  !important;  line-height:19px  !important; height:19px  !important; margin:0 2px 0 0  !important; float:left  !important; display:inline  !important;padding: 2px  !important; *}
.type-file-button , input.type-file-button:focus { background: url(<?php echo ADMIN_SITE_URL;?>/templates/default/images/sky/bg_position.gif) no-repeat -50px -450px; display: inline; width: 57px; height: 25px  !important; float: left; border: 0;}
.type-file-file { position:absolute  !important; top:0px  !important; right:0px  !important; height:25px  !important; width:256px  !important; filter:alpha(opacity:0)  !important; opacity: 0  !important; cursor: pointer  !important;}
.type-file-show { float: right; margin-right:10px; cursor: help; margin-left: 10px;}
.type-file-preview { background: #FFF; display: none; padding:5px; border: solid 5px #71CBEF; position: absolute; z-index:999;}
.image_display .type-file-show { width: 16px; height: 16px; padding: 2px; border: solid 1px #D8D8D8; cursor:auto;}

</style>
 <form action="index.php?act=store_theme_setting_lexus&op=theme_setting" id="store_slide_form" method="post" onsubmit="ajaxpost('store_theme_setting_lexus', '', '', 'onerror');return false;">
    <input type="hidden" name="form_submit" value="ok" />
	<div class="ncsc-form-default"> 
	<h3>幻灯片广告</h3>
	<table style="margin-top: 5px;margin-bottom: 5px;">
	 	<tr class="noborder" style="background: rgb(255, 255, 255);">
          <td class="vatop rowform onoff">
            <label style="" class="cb-enable <?php if($output['store_theme']['slideshow']['isshow']==1)echo 'selected';?>" title="启用"><span>启用</span></label>
            <label style="" class="cb-disable <?php if($output['store_theme']['slideshow']['isshow']!=1)echo 'selected';?>" title="关闭"><span>关闭</span></label>
            <input type="hidden" name="slideshow" value="<?php echo $output['store_theme']['slideshow']['isshow']?>" />
          </td><td class="vatop tips"></td>
        </tr>
    </table>   
	<div class="alert">
      <ul>
        <li>1.最多可上传5张幻灯片图片。</li>
        <li>2.支持jpg、jpeg、gif、png格式上传，建议图片宽度1170px、高度450px、大小1.00M以内的图片。提交2~5张图片可以进行幻灯片播放，一张图片没有幻灯片播放效果。</li>
        <li>3.跳转链接必须为全路径地址(带有“http://”)</li>
      </ul>
    </div>
    <!-- 图片上传部分 -->
    <ul class="ncsc-store-slider" id="goods_images">
      <?php for($i=0;$i<5;$i++){?>
      <li nc_type="handle_pic" id="thumbnail_<?php echo $i;?>">
        <div class="picture" nctype="file_<?php echo $i;?>">
          <?php if (empty($output['imglist']['slideshow'][$i]) || $output['imglist']['slideshow'][$i]['img']=='') {?>
          <i class="icon-picture"></i>
          <?php } else {?>
          <img nctype="file_<?php echo $i;?>" src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_SLIDE.DS.$output['imglist']['slideshow'][$i]['img'];?>" />
          <?php }?>
          <input type="hidden" name="image_path[]" nctype="file_<?php echo $i;?>" value="" />
          <input type="hidden" name="image_path_old[]"  value="<?php echo $output['imglist']['slideshow'][$i]['img'];?>" />
          <a href="javascript:void(0)" nctype="del" class="del" title="移除">X</a></div>
			
		<div class="url">
          <label>文本1</label>
          <input type="text" class="text w150" name="text1[]" value="<?php if($output['imglist']['slideshow'][$i]['text1'] == ''){  echo '';}else{echo $output['imglist']['slideshow'][$i]['text1'];}?>" />
        </div>
        <div class="url">
          <label>文本2</label>
          <input type="text" class="text w150" name="text2[]" value="<?php if($output['imglist']['slideshow'][$i]['text2'] == ''){  echo '';}else{echo $output['imglist']['slideshow'][$i]['text2'] ;}?>" />
        </div>
        <div class="url">
          <label>文本3</label>
          <input type="text" class="text w150" name="text3[]" value="<?php if($output['imglist']['slideshow'][$i]['text3'] == ''){  echo '';}else{echo $output['imglist']['slideshow'][$i]['text3'] ;}?>" />
        </div>
        <div class="url">
          <label>文本4</label>
          <input type="text" class="text w150" name="text4[]" value="<?php if($output['imglist']['slideshow'][$i]['text4'] == ''){  echo '';}else{echo $output['imglist']['slideshow'][$i]['text4'] ;}?>" />
        </div>
        <div class="url">
          <label><?php echo $lang['store_slide_image_url'];?></label>
          <input type="text" class="text w150" name="url[]" value="<?php if($output['imglist']['slideshow'][$i]['url'] == ''){  echo 'http://';}else{echo $output['imglist']['slideshow'][$i]['url'];}?>" />
        </div>
         <div class="ncsc-upload-btn"> <a href="javascript:void(0);"><span>
          <input type="file" hidefocus="true" size="1" class="input-file" name="file_<?php echo $i;?>" id="file_<?php echo $i;?>"/>
          </span>
          <p><i class="icon-upload-alt"></i><?php echo $lang['store_slide_image_upload'];?></p>
          </a></div>
       </li>
      <?php } ?>
    </ul>
   <h3>静态广告一(一张图片)</h3>
   <table style="margin-top: 5px;margin-bottom: 5px;">
	 	<tr class="noborder" style="background: rgb(255, 255, 255);">
          <td class="vatop rowform onoff">
          	<label style="" class="cb-enable <?php if($output['store_theme']['showcase']['isshow']==1)echo 'selected';?>" title="启用"><span>启用</span></label>
            <label style="" class="cb-disable <?php if($output['store_theme']['showcase']['isshow']!=1)echo 'selected';?>" title="关闭"><span>关闭</span></label>
            <input type="hidden" name="showcase" value="<?php echo $output['store_theme']['showcase']['isshow']?>" />
          </td><td class="vatop tips"></td>
        </tr>
   </table>  
   <table>
   		<tr class="noborder" style="background: rgb(255, 255, 255);">
          <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="<?php echo ADMIN_SITE_URL.'/templates/'.TPL_NAME;?>/images/preview.png">
            <div class="type-file-preview" style="display: none;"><img nctype="file_showcase_pic" src="<?php echo ($output['imglist']['showcase'][0]['img']=='')? ADMIN_SITE_URL.'/templates/'.TPL_NAME.'/images/preview.png': UPLOAD_SITE_URL.'/'.ATTACH_SLIDE.DS.$output['imglist']['showcase'][0]['img'];?>"></div>
            </span><span class="type-file-box ncsc-upload-btn">
            <input type="text" name="showcase_pic[]"  class="type-file-text" nctype="file_showcase_pic">
            <input type="button" name="button"  value="" class="type-file-button">
            <input name="file_showcase_pic" type="file" class="type-file-file"  size="30" id="file_showcase_pic">
            <input type="hidden" name="showcase_pic_old[]"  value="<?php echo $output['imglist']['showcase'][0]['img'];?>" />
            </span></td>
          <td class="vatop tips"><span class="vatop rowform">1170px * 110px</span></td>
          <td class="vatop tips">
          <span class="vatop rowform" style="margin-left: 50px;vertical-align: -webkit-baseline-middle;">跳转URL:</span>
          <input type="text"  class="text w150" name="showcase_url[]" value="<?php if($output['imglist']['showcase'][0]['url'] == ''){  echo 'http://';}else{echo $output['imglist']['showcase'][0]['url'];}?>" />
          </td>
        </tr>
   </table>     
   <h3>静态广告二(三张图片)</h3>
   <table style="margin-top: 5px;margin-bottom: 5px;">
	 	<tr class="noborder" style="background: rgb(255, 255, 255);">
          <td class="vatop rowform onoff">
          	<label style="" class="cb-enable <?php if($output['store_theme']['promotion']['isshow']==1)echo 'selected';?>" title="启用"><span>启用</span></label>
            <label style="" class="cb-disable <?php if($output['store_theme']['promotion']['isshow']!=1)echo 'selected';?>" title="关闭"><span>关闭</span></label>
            <input type="hidden" name="promotion" value="<?php echo $output['store_theme']['promotion']['isshow']?>" />
          </td><td class="vatop tips"></td>
        </tr>
   </table>
   <table>
   		<tr class="noborder" style="background: rgb(255, 255, 255);">
           <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="<?php echo ADMIN_SITE_URL.'/templates/'.TPL_NAME;?>/images/preview.png">
            <div class="type-file-preview" style="display: none;"><img nctype="file_promotion_pic1" src="<?php echo ($output['imglist']['promotion'][0]['img']=='')? ADMIN_SITE_URL.'/templates/'.TPL_NAME.'/images/preview.png': UPLOAD_SITE_URL.'/'.ATTACH_SLIDE.DS.$output['imglist']['promotion'][0]['img'];?>"></div>
            </span><span class="type-file-box ncsc-upload-btn">
            <input type="text" name="promotion_pic[]"  class="type-file-text" nctype="file_promotion_pic1">
            <input type="button" name="button"  value="" class="type-file-button">
            <input name="file_promotion_pic1" type="file" class="type-file-file"  size="30" id="file_promotion_pic1">
            <input type="hidden" name="promotion_pic_old[]"  value="<?php echo $output['imglist']['promotion'][0]['img'];?>" />
            </span></td>
          <td class="vatop tips"><span class="vatop rowform">376px * 220px</span></td>
          <td class="vatop tips">
          <span class="vatop rowform" style="margin-left: 50px;vertical-align: -webkit-baseline-middle;">跳转URL:</span>
          <input type="text"  class="text w150" name="promotion_url[]" value="<?php if($output['imglist']['promotion'][0]['url'] == ''){  echo 'http://';}else{echo $output['imglist']['promotion'][0]['url'];}?>" />
          </td>
        </tr>
        <tr class="noborder" style="background: rgb(255, 255, 255);">
          <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="<?php echo ADMIN_SITE_URL.'/templates/'.TPL_NAME;?>/images/preview.png">
            <div class="type-file-preview" style="display: none;"><img nctype="file_promotion_pic2" src="<?php echo ($output['imglist']['promotion'][1]['img']=='')? ADMIN_SITE_URL.'/templates/'.TPL_NAME.'/images/preview.png': UPLOAD_SITE_URL.'/'.ATTACH_SLIDE.DS.$output['imglist']['promotion'][1]['img'];?>"></div>
            </span><span class="type-file-box ncsc-upload-btn">
            <input type="text" name="promotion_pic[]"  class="type-file-text" nctype="file_promotion_pic2">
            <input type="button" name="button"  value="" class="type-file-button">
            <input name="file_promotion_pic2" type="file" class="type-file-file"  size="30" id="file_promotion_pic2">
            <input type="hidden" name="promotion_pic_old[]"  value="<?php echo $output['imglist']['promotion'][1]['img'];?>" />
            </span></td>
          <td class="vatop tips"><span class="vatop rowform">376px * 220px</span></td>
          <td class="vatop tips">
          <span class="vatop rowform" style="margin-left: 50px;vertical-align: -webkit-baseline-middle;">跳转URL:</span>
          <input type="text"  class="text w150" name="promotion_url[]" value="<?php if($output['imglist']['promotion'][1]['url'] == ''){  echo 'http://';}else{echo $output['imglist']['promotion'][1]['url'];}?>" />
          </td>
        </tr>
        <tr class="noborder" style="background: rgb(255, 255, 255);">
          <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="<?php echo ADMIN_SITE_URL.'/templates/'.TPL_NAME;?>/images/preview.png">
            <div class="type-file-preview" style="display: none;"><img nctype="file_promotion_pic3" src="<?php echo ($output['imglist']['promotion'][2]['img']=='')? ADMIN_SITE_URL.'/templates/'.TPL_NAME.'/images/preview.png': UPLOAD_SITE_URL.'/'.ATTACH_SLIDE.DS.$output['imglist']['promotion'][2]['img'];?>"></div>
            </span><span class="type-file-box ncsc-upload-btn">
            <input type="text" name="promotion_pic[]"  class="type-file-text" nctype="file_promotion_pic3">
            <input type="button" name="button"  value="" class="type-file-button">
            <input name="file_promotion_pic3" type="file" class="type-file-file"  size="30" id="file_promotion_pic3">
            <input type="hidden" name="promotion_pic_old[]"  value="<?php echo $output['imglist']['promotion'][2]['img'];?>" />
            </span></td>
          <td class="vatop tips"><span class="vatop rowform">376px * 220px</span></td>
          <td class="vatop tips">
          <span class="vatop rowform" style="margin-left: 50px;vertical-align: -webkit-baseline-middle;">跳转URL:</span>
          <input type="text"  class="text w150" name="promotion_url[]" value="<?php if($output['imglist']['promotion'][2]['url'] == ''){  echo 'http://';}else{echo $output['imglist']['promotion'][2]['url'];}?>" />
          </td>
        </tr>
   </table> 
   <h3>人气商品(4个商品)</h3>
   <table style="margin-top: 5px;margin-bottom: 5px;">
	 	<tr class="noborder" style="background: rgb(255, 255, 255);">
          <td class="vatop rowform onoff">
          	<label style="" class="cb-enable <?php if($output['store_theme']['mostview']['isshow']==1)echo 'selected';?>" title="启用"><span>启用</span></label>
            <label style="" class="cb-disable <?php if($output['store_theme']['mostview']['isshow']!=1)echo 'selected';?>" title="关闭"><span>关闭</span></label>
            <input type="hidden" name="mostview" value="<?php echo $output['store_theme']['mostview']['isshow']?>" />
          </td><td class="vatop tips"></td>
        </tr>
   </table> 
   <h3>静态广告三(二张图片)</h3>
   <table style="margin-top: 5px;margin-bottom: 5px;">
	 	<tr class="noborder" style="background: rgb(255, 255, 255);">
          <td class="vatop rowform onoff">
            <label style="" class="cb-enable <?php if($output['store_theme']['goods_ad']['isshow']==1)echo 'selected';?>" title="启用"><span>启用</span></label>
            <label style="" class="cb-disable <?php if($output['store_theme']['goods_ad']['isshow']!=1)echo 'selected';?>" title="关闭"><span>关闭</span></label>
            <input type="hidden" name="goods_ad" value="<?php echo $output['store_theme']['goods_ad']['isshow']?>" />
          </td><td class="vatop tips"></td>
        </tr>
   </table> 
   <table>
   		<tr class="noborder" style="background: rgb(255, 255, 255);">
          <tr class="noborder" style="background: rgb(255, 255, 255);">
           <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="<?php echo ADMIN_SITE_URL.'/templates/'.TPL_NAME;?>/images/preview.png">
            <div class="type-file-preview" style="display: none;"><img nctype="file_goods_ad_pic1" src="<?php echo ($output['imglist']['goods_ad'][0]['img']=='')? ADMIN_SITE_URL.'/templates/'.TPL_NAME.'/images/preview.png': UPLOAD_SITE_URL.'/'.ATTACH_SLIDE.DS.$output['imglist']['goods_ad'][0]['img'];?>"></div>
            </span><span class="type-file-box ncsc-upload-btn">
            <input type="text" name="goods_ad_pic[]"  class="type-file-text" nctype="file_goods_ad_pic1">
            <input type="button" name="button"  value="" class="type-file-button">
            <input name="file_goods_ad_pic1" type="file" class="type-file-file"  size="30" id="file_goods_ad_pic1">
            <input type="hidden" name="goods_ad_pic_old[]"  value="<?php echo $output['imglist']['goods_ad'][0]['img'];?>" />
            </span></td>  
          <td class="vatop tips"><span class="vatop rowform">575px * 110px</span></td>
          <td class="vatop tips">
          <span class="vatop rowform" style="margin-left: 50px;vertical-align: -webkit-baseline-middle;">跳转URL:</span>
          <input type="text"  class="text w150" name="goods_ad_url[]" value="<?php if($output['imglist']['goods_ad'][0]['url'] == ''){  echo 'http://';}else{echo $output['imglist']['goods_ad'][0]['url'];}?>" />
          </td>
        </tr>
        <tr class="noborder" style="background: rgb(255, 255, 255);">
         <tr class="noborder" style="background: rgb(255, 255, 255);">
           <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="<?php echo ADMIN_SITE_URL.'/templates/'.TPL_NAME;?>/images/preview.png">
            <div class="type-file-preview" style="display: none;"><img nctype="file_goods_ad_pic2" src="<?php echo ($output['imglist']['goods_ad'][1]['img']=='')? ADMIN_SITE_URL.'/templates/'.TPL_NAME.'/images/preview.png': UPLOAD_SITE_URL.'/'.ATTACH_SLIDE.DS.$output['imglist']['goods_ad'][1]['img'];?>"></div>
            </span><span class="type-file-box ncsc-upload-btn">
            <input type="text" name="goods_ad_pic[]"  class="type-file-text" nctype="file_goods_ad_pic2">
            <input type="button" name="button"  value="" class="type-file-button">
            <input name="file_goods_ad_pic2" type="file" class="type-file-file"  size="30" id="file_goods_ad_pic2">
            <input type="hidden" name="goods_ad_pic_old[]"  value="<?php echo $output['imglist']['goods_ad'][1]['img'];?>" />
            </span></td> 
          <td class="vatop tips"><span class="vatop rowform">575px * 110px</span></td>
           <td class="vatop tips">
          <span class="vatop rowform" style="margin-left: 50px;vertical-align: -webkit-baseline-middle;">跳转URL:</span>
          <input type="text"  class="text w150" name="goods_ad_url[]" value="<?php if($output['imglist']['goods_ad'][1]['url'] == ''){  echo 'http://';}else{echo $output['imglist']['goods_ad'][1]['url'];}?>" />
          </td>
        </tr>
   </table> 
   <h3>热卖商品(多个)</h3>
   <table style="margin-top: 5px;margin-bottom: 5px;">
	 	<tr class="noborder" style="background: rgb(255, 255, 255);">
          <td class="vatop rowform onoff">
          	<label style="" class="cb-enable <?php if($output['store_theme']['bestsell']['isshow']==1)echo 'selected';?>" title="启用"><span>启用</span></label>
            <label style="" class="cb-disable <?php if($output['store_theme']['bestsell']['isshow']!=1)echo 'selected';?>" title="关闭"><span>关闭</span></label>
            <input type="hidden" name="bestsell" value="<?php echo $output['store_theme']['bestsell']['isshow']?>" />
          </td><td class="vatop tips"></td>
        </tr>
   </table> 
   <h3>店铺动态</h3>
   <table style="margin-top: 5px;margin-bottom: 5px;">
	 	<tr class="noborder" style="background: rgb(255, 255, 255);">
          <td class="vatop rowform onoff">
          	<label style="" class="cb-enable <?php if($output['store_theme']['store_blog']['isshow']==1)echo 'selected';?>" title="启用"><span>启用</span></label>
            <label style="" class="cb-disable <?php if($output['store_theme']['store_blog']['isshow']!=1)echo 'selected';?>" title="关闭"><span>关闭</span></label>
            <input type="hidden" name="store_blog" value="<?php echo $output['store_theme']['store_blog']['isshow']?>" />
          </td><td class="vatop tips"></td>
        </tr>
   </table> 
</div>
<div class="bottom" style=" border: solid #E6E6E6; border-width: 0px 0px 1px 0px; margin-bottom: 10px; TEXT-ALIGN: center;">
   <label class="submit-border" style="border: none;margin-top: 0px;">
   <input type="button" class="submit" value="返回" style="float: left;margin-right: 20px;" onclick="window.location.href='<?php echo urlShop('store_setting', 'theme');?>'">
   <input type="submit" class="submit" value="<?php echo $lang['store_slide_submit'];?>"></label>
</div>
</form>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js" charset="utf-8"></script> 

<script type="text/javascript">
var SITEURL = "<?php echo SHOP_SITE_URL;?>";
var SHOP_TEMPLATES_URL = '<?php echo SHOP_TEMPLATES_URL;?>';
var UPLOAD_SITE_URL = '<?php echo UPLOAD_SITE_URL;?>';
var ATTACH_COMMON = '<?php echo ATTACH_COMMON;?>';
var ATTACH_STORE = '<?php echo ATTACH_STORE;?>';
var SHOP_RESOURCE_SITE_URL = '<?php echo SHOP_RESOURCE_SITE_URL;?>';

//自定义radio样式
$(document).ready( function(){ 
	$(".cb-enable").click(function(){
		var parent = $(this).parents('.onoff');
		$('.cb-disable',parent).removeClass('selected');
		$(this).addClass('selected');
		$('input:hidden',parent).val('1');
//		$('.checkbox',parent).attr('checked', true);
	});
	$(".cb-disable").click(function(){
		var parent = $(this).parents('.onoff');
		$('.cb-enable',parent).removeClass('selected');
		$(this).addClass('selected');
		$('input:hidden',parent).val('0');
//		$('.checkbox',parent).attr('checked', false);
	});
	// 显示隐藏预览图 start
	$('.show_image').hover(
		function(){
			$(this).next().css('display','block');
		},
		function(){
			$(this).next().css('display','none');
		}
	);
/*
	$('input[class="type-file-file"]').change(uploadChange);
	function uploadChange(){
		var filepatd=$(this).val();
		var extStart=filepatd.lastIndexOf(".");
		var ext=filepatd.substring(extStart,filepatd.lengtd).toUpperCase();		
		if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
			alert("file type error");
			$(this).attr('value','');
			return false;
		}
		if ($(this).val() == '') return false;
		ajaxFileUpload();
	}
	function ajaxFileUpload()
	{
		$.ajaxFileUpload
		(
			{
				url:'index.php?act=common&op=pic_upload&form_submit=ok&uploadpath=<?php echo ATTACH_BRAND;?>',
				secureuri:false,
				fileElementId:'_pic',
				dataType: 'json',
				success: function (data, status)
				{
					if (data.status == 1){
						ajax_form('cutpic','<?php echo $lang['nc_cut'];?>','index.php?act=common&op=pic_cut&type=brand&x=150&y=50&resize=1&ratio=3&url='+data.url,690);
					}else{
						alert(data.msg);
					}
					$('input[class="type-file-file"]').bind('change',uploadChange);
				},
				error: function (data, status, e)
				{
					alert('upload failed');$('input[class="type-file-file"]').bind('change',uploadChange);
				}
			}
		)
	};	
	*/
	
	 /* 商品图片ajax上传 */
    var url = SITEURL + '/index.php?act=store_theme_setting_lexus&op=silde_image_upload';
    $('.ncsc-upload-btn').find('input[type="file"]').unbind().change(
        function() {
            var id = $(this).attr('id');
            var file_id = $(this).attr('file_id');
            ajaxFileUpload1(url, id, file_id);
        });

    /* 删除图片 */
    $('a[nctype="del"]').unbind().click(
        function() {
            var obj = $(this).parents('li');
            var file_id = obj.find('input[type="file"]').attr('file_id');
            var img_src = obj.find('input[type="hidden"]:first').val();
            var index=obj.find('input[type="hidden"]:first').attr('nctype');
            index=index.substr(-1);
            
            obj.find('img:first').attr('src', SHOP_TEMPLATES_URL + "/images/loading.gif");
            $.getJSON('index.php?act=store_theme_setting_lexus&op=dorp_img', {index:index,file_id : file_id, img_src : img_src}, function(data) {
                obj.find('img:first').replaceWith('<i class="icon-picture"></i>');
                obj.find('input[type="file"]').attr('file_id', '').end()
                    .find('input[type="hidden"]:first').val('');
            });
            //$.getScript(SHOP_RESOURCE_SITE_URL + "/js/store_slide.js");
        });
});

/* 图片上传ajax */
function ajaxFileUpload1(url, id, file_id)
{
    $('div[nctype="'+id+'"]').find('i').remove().end().find('img').remove()
            .end().prepend('<img nctype="'+id+'" scr="'+SHOP_TEMPLATES_URL+'/images/loading.gif">');
	$('img[nctype="'+id+'"]').attr('src',SHOP_TEMPLATES_URL+"/images/loading.gif");

	$.ajaxFileUpload
	(
		{
			url:url,
			secureuri:false,
			fileElementId:id,
			dataType: 'json',
			data:{name:'logan', id:id, file_id:file_id},
			success: function (data, status)
			{
				if(typeof(data.error) != 'undefined')
				{
					alert(data.error);
					$('img[nctype="'+id+'"]').attr('src',UPLOAD_SITE_URL+'/'+ATTACH_COMMON+'/default_goods_image.gif');
				}else
				{
					$('input[nctype="'+id+'"]').val(data.file_name).attr('file_id',data.file_id);
					$('img[nctype="'+id+'"]').attr('src',UPLOAD_SITE_URL+'/'+ATTACH_STORE+'/slide/'+data.file_name);
					$('#'+id).attr('file_id',data.file_id);
				}
			},
			error: function (data, status, e)
			{
				alert(e);
			}
		}
	)
	return false;

}

$("#btn_addimg").on("click", function() {
});
</script> 
