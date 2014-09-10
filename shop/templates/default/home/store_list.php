<?php defined('InShopNC') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/layout.css" rel="stylesheet" type="text/css">
<div class="nch-container wrapper">
  
  <div class="shop_con_list" id="main-nav-holder">
      <nav class="sort-bar" id="main-nav">
      <div class="pagination"><?php echo $output['show_page1']; ?> </div>
      <div class="nch-all-category">
      </div>
        <div class="nch-sortbar-array"> 排序方式：
          <ul>
            <li <?php if(!$_GET['key']){?>class="selected"<?php }?>><a href="<?php echo dropParam(array('order', 'key'));?>"  title="<?php echo $lang['store_list_default_sort'];?>"><?php echo $lang['store_list_default'];?></a></li>
            <li <?php if($_GET['key'] == '1'){?>class="selected"<?php }?>><a href="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '1') ? replaceParam(array('key' => '1', 'order' => '1')):replaceParam(array('key' => '1', 'order' => '2')); ?>" <?php if($_GET['key'] == '1'){?>class="<?php echo $_GET['order'] == 1 ? 'asc' : 'desc';?>"<?php }?> title="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '1')?$lang['store_list_sold_asc']:$lang['store_list_sold_desc']; ?>"><?php echo $lang['store_list_sold'];?><i></i></a></li>
            <li <?php if($_GET['key'] == '2'){?>class="selected"<?php }?>><a href="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '2') ? replaceParam(array('key' => '2', 'order' => '1')):replaceParam(array('key' => '2', 'order' => '2')); ?>" <?php if($_GET['key'] == '2'){?>class="<?php echo $_GET['order'] == 1 ? 'asc' : 'desc';?>"<?php }?> title="<?php  echo ($_GET['order'] == '2' && $_GET['key'] == '2')?$lang['store_list_credit_asc']:$lang['store_list_credit_desc']; ?>"><?php echo $lang['store_list_credit']?><i></i></a></li>
            <li <?php if($_GET['key'] == '3'){?>class="selected"<?php }?>><a href="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '3') ? replaceParam(array('key' => '3', 'order' => '1')):replaceParam(array('key' => '3', 'order' => '2')); ?>" <?php if($_GET['key'] == '3'){?>class="<?php echo $_GET['order'] == 1 ? 'asc' : 'desc';?>"<?php }?> title="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '3')?$lang['store_list_click_asc']:$lang['store_list_click_desc']; ?>"><?php echo $lang['store_list_click'];?><i></i></a></li>
          </ul>
        </div>
        <div class="nch-sortbar-owner">商品类型： <span><a href="<?php echo dropParam(array('type'));?>" <?php if (!isset($_GET['type']) || !in_array($_GET['type'], array(1,2))) {?>class="selected"<?php }?>>
        	<i></i>全部</a></span> 
        	<span><a href="<?php echo replaceParam(array('type' => '1'));?>" <?php if ($_GET['type'] == 1) {?>class="selected"<?php }?>>
        	<i></i>商城自营</a></span> <span><a href="<?php echo replaceParam(array('type' => '2'));?>" <?php if ($_GET['type'] == 2) {?>class="selected"<?php }?>>
        	<i></i>商家加盟</a></span> 
        </div>
		<div class="nch-sortbar-array">展示模式：
			  <ul class="tabs-nav"> 
			  	<li class='selected'><a href="#" title="默认排序">列表模式</a></li>
			  	<li><a href="#" title="默认排序">地图模式</a></li>
			  </ul>
        </div>
      </nav>
      <!-- 商品列表循环  -->

      <div id="show_store_list">
         <?php if(!empty($output['store']) && is_array($output['store'])){?>
		  <div class="nch-recommend-borand">
		    <div class="" title="<?php echo $lang['brand_index_recommend_brand'];?>"></div>
		    <div class="list-content">
		      <ul>
		        <?php foreach($output['store'] as $key=>$store_r){?>
		        <li class="list-item">
		          <ul class="store_clearfix">
                        <li class="list-img">
                            <a href="<?php echo urlShop('show_store', 'index', array('store_id'=>$store_r['store_id']));?>"><img src="<?php echo storeImage($store_r['store_label']);?>" alt="<?php echo $store_r['store_name'];?>" /></a>
                        </li>
                        <li class="list-info">
                            <h4>
                             <a  style="color: #0063DC;" href="<?php echo urlShop('show_store', 'index', array('store_id'=>$store_r['store_id']));?>"><?php echo $store_r['store_name'];?></a>
                            </h4>
                            <p class="shop-info">
                            	<span>店铺等级：</span>
                            	<span style="margin-left: 1px;"><?php echo $store_r['sg_name'];?></span>
                            </p>
                            <p class="shop-info">
                                <span>卖家:</span>
                            <span style="margin-left: 6px;">
                                 <?php echo $store_r['member_name'];?>
                            </p>
                            <p class="main-cat">
                                <span>主营:</span>
                                <?php echo $store_r['store_zy'];?>
                            </p>
                            <span class="pro-store-adress">
                            	<span>地址:</span>
                            	<span class="info-sum"><?php echo $store_r['address'];?></span>
                            </span>
                            <span class="promotion">
                                   <span>联系电话:</span>
                                   <span class="info-sum"><em><?php echo $store_r['phone'];?></em></span>
                            </span>
			                <span class="pro-sale-num">
			                    <span>销量<em><?php echo $store_r['goods_salenum'];?></em></span>
			                    <span class="info-sum">共<em><?php echo $store_r['goodnum'];?></em>件宝贝</span>
			                    <span class="shop-promotion"> 好评率: <?php echo round($store_r['good_star']*100/(5*$store_r['goodnum']),2);?>%</span>
			                </span>
		                </li>
               			<li class="shop-products" >
                               <div class="shop-products-container" style="width: 624px; overflow: hidden; height: 160px;">
                               	<div style="position: absolute; width: 2496px; overflow: hidden; left: 0px;">
                               	<?php if(!empty($store_r['list']) && is_array($store_r['list'])){?>
                               	 <?php foreach($store_r['list'] as $key=>$good){?>
                               	 <div class="one-product" >
	                                <div class="product-img">
	                                    <a  href="<?php echo urlShop('goods', 'index', array('goods_id' => $good['goods_id']));?>" target="_blank">
	                                        <img src="<?php echo goodImage($good['goods_image'],$store_r['store_id']);?>" >
	                                    </a>
	                                </div>
	                                <div class="price-wrap">
	                                    <div class="price-wrap-shade"></div>
	                                    <span>￥</span><span class="price-num"><?php echo $good['goods_price'];?></span>
	                                </div>
	                            </div>
	                            <?php }?>
	                            <?php }?>
	                          </div>
                            </div>
                       </li>
                  </ul>	
                  <!-- 
		          <dl style="width: 300px;">
		            <dt style="position: relative;margin-right: 14px;padding-bottom: 20px;margin-top: 5px;">
		            	<a href="<?php echo urlShop('show_store', 'index', array('store_id'=>$store_r['store_id']));?>"><img src="<?php echo storeImage($store_r['store_label']);?>" alt="<?php echo $store_r['store_name'];?>" /></a>
		            </dt>
		            <dd><a href="<?php echo urlShop('show_store', 'index', array('store_id'=>$store_r['store_id']));?>"><?php echo $store_r['store_name'];?></a></dd>
		            <dd>店铺等级：<?php echo $store_r['sg_name'];?></dd>
		            <dd>主营商品：<?php echo $store_r['store_zy'];?></dd>
		            <dd>商家地址：<?php echo $store_r['address'];?></dd>
		          </dl>
		           -->
		        </li>
		        <?php }?>
		      </ul>
		    </div>
		  </div>
		  <?php }?>
      </div>
      <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=AC567a85b2886efaaafa2aa09f683fbd"></script>
      <div id="show_store_map" style="display: none;width: 100%;height: 450px; overflow: hidden; position: relative; z-index: 0; background-color: rgb(243, 241, 236); color: rgb(0, 0, 0); text-align: left;">
      </div>
		<script type="text/javascript">
		// 百度地图API功能  
		var map = new BMap.Map("show_store_map");          // 创建地图实例  
		map.addControl(new BMap.ScaleControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT}));//比例尺
		//添加 地图平移缩放控件
		map.addControl(new BMap.NavigationControl());

		<?php 
			$ch = curl_init("http://api.map.baidu.com/location/ip?ak=AC567a85b2886efaaafa2aa09f683fbd&coor=bd09ll") ;
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
			$baiduresult=curl_exec($ch) ;
			$baiduresult=json_decode($baiduresult,true);
			curl_close($ch);
			if($output['status']==0){
		?>
				var mypoint = new BMap.Point(<?php echo $baiduresult['content']['point']['x']; ?>,<?php echo $baiduresult['content']['point']['y']; ?>);
				//自定义点的图标
				var myIcon = new BMap.Icon("<?php echo UPLOAD_SITE_URL.'/'.ATTACH_STORE.'/mylocation.png';?>", new BMap.Size(20, 20));
				var marker = new BMap.Marker(mypoint, { icon: myIcon });
				marker.addEventListener("click", function(){   
					marker.openInfoWindow(new BMap.InfoWindow('我的位置',  {
						width: 60,     // 信息窗口宽度
						height: 40,     // 信息窗口高度
						title: ""  // 信息窗口标题
					}));
					map.centerAndZoom(mypoint,15);
				});
				map.addOverlay(marker);
				map.centerAndZoom(mypoint, 15); // 初始化地图，设置中心点坐标和地图级别
		<?php }?>
		<?php foreach($output['store'] as $key=>$store_r){?>
			var point<?php echo $key;?> =new BMap.Point(<?php echo $store_r['location'] ?>);
			var marker<?php echo $key;?> = new BMap.Marker(point<?php echo $key;?>);
			marker<?php echo $key;?>.addEventListener("click", function(){    
				var opts = {
						width: 150,     // 信息窗口宽度
						height: 60,     // 信息窗口高度
						title: ""  // 信息窗口标题
					}
				var jd_title = '<a href="<?php echo urlShop('show_store', 'index', array('store_id'=>$store_r['store_id']));?>"><b style="color: #CC5522;font-size: 14px;font-weight: bold;white-space: nowrap;"><?php echo $store_r['store_name'] ?></b></a>';
				var jd_info = '地址：<?php echo $store_r['address'] ?>';
				var jd_phone = '电话：<?php echo $store_r['phone'] ?>';
				var infoWindow = new BMap.InfoWindow(jd_title + '<br>' + jd_phone + '<br>' + jd_info, opts);
				marker<?php echo $key;?>.openInfoWindow(infoWindow);
				map.centerAndZoom(point<?php echo $key;?>,15);
			});
			map.addOverlay(marker<?php echo $key;?>);
		<?php }?>

		 
		</script>
		<div class="tc mt20 mb20">
        <div class="pagination"> <?php echo $output['show_page']; ?> </div>
      </div>
    </div>
  
  
 
  
</div>
<script>
//首页Tab标签卡滑门切换
$(".tabs-nav > li > a").live('mousedown', (function(e) {
	if (e.target == this) {
		var tabs = $(this).parents('ul:first').children("li");
		//var panels = $(this).parents('.nch-brand-class:first').children(".tabs-panel");
		var index = $.inArray(this, $(this).parents('ul:first').find("a"));
		//if (panels.eq(index)[0]) {
			tabs.removeClass("tabs-selected").eq(index).addClass("tabs-selected");
			tabs.removeClass("selected").eq(index).addClass("selected");
			//panels.addClass("tabs-hide").eq(index).removeClass("tabs-hide");
		//}
		if(index==0){
			$("#show_store_map").hide();
			$("#show_store_list").show();
		}else{
			$("#show_store_map").show();
			map.centerAndZoom(mypoint, 15);
			$("#show_store_list").hide();
		}
	}
}));
</script>
