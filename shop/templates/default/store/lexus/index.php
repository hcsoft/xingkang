<?php defined('InShopNC') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/css/bootstrap.css" rel="stylesheet" />
<link href="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/css/stylesheet.css" rel="stylesheet" />
<link href="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/js/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<link href="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/css/animation.css" rel="stylesheet" />
<link href="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/css/font-awesome.min.css" rel="stylesheet" />
<link href="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/css/font.css" rel="stylesheet" />
<link href="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/js/jquery/colorbox/colorbox.css" rel="stylesheet" />
<link href="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/css/pavproductcarousel.css" rel="stylesheet" />
<link href="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/css/pavblog.css" rel="stylesheet" />
<link href="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/css/pavnewsletter.css" rel="stylesheet" />
<link href="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/css/sliderlayer/css/typo.css" rel="stylesheet" />
<link href="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/css/pavmegamenu/style.css" rel="stylesheet" />
<link href="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/css/pavcarousel.css" rel="stylesheet" />

<link href="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/css/google_font.css" rel='stylesheet' rel='stylesheet' type='text/css' />

<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/js/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/js/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/js/jquery/ui/external/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/js/common1.js"></script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/js/common.js"></script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/js/jquery/bootstrap/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/js/jquery/colorbox/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/js/jquery/colorbox/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/js/layerslider/jquery.themepunch.plugins.min.js"></script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/js/layerslider/jquery.themepunch.revolution.min.js"></script>
<!--[if lt IE 9]>
<script src="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/js/html5.js"></script>
<script src="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/js/respond.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/css/ie8.css" />
<![endif]-->


<section id="page1" class="offcanvas-pusher" role="main">

<?php foreach ($store_theme as $i => $theme){?>
<?php if($theme['module_name']=='slideshow' && $theme['isshow']==='1'){?>
	<!-- 幻灯片广告 -->
	<?php include 'slideshow.php';?>
<?php }?>	
<?php if($theme['module_name']=='showcase' && $theme['isshow']==='1'){?>
	<!-- 静态图片广告/商家logo -->
	<?php include 'showcase.php';?>
<?php }?>	
<?php if($theme['module_name']=='promotion' && $theme['isshow']==='1'){?>	
	<!-- 三个静态图片广告 折扣商品 -->
	<?php include 'promotion.php';?>
<?php }?>	
<?php if($theme['module_name']=='mostview' && $theme['isshow']==='1'){?>
	<!-- 查看最多的商品(4个) -->
	<?php include 'mostview.php';?>
<?php }?>	
<?php if($theme['module_name']=='goods_ad' && $theme['isshow']==='1'){?>	
	<!-- 二个静态图片广告 折扣商品 -->
	<?php include 'goods_ad.php';?>
<?php }?>	
<?php if($theme['module_name']=='bestsell' && $theme['isshow']==='1'){?>
	<!-- 热卖商品二排有分页 -->
	<?php include 'bestsell.php';?>
<?php }?>	
<?php if($theme['module_name']=='store_blog' && $theme['isshow']==='1'){?>
	<!-- 店铺动态 -->
	<?php include 'store_blog.php';?>
<?php }?>	
<?php }?>					
</section>
