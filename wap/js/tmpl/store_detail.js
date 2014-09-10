$(function (){
	var store_id = GetQueryString("store_id");
	
	$('input[name=store_id]').val(store_id);
	$.ajax({
		url:ApiUrl+"/index.php?act=store&op=store_detail&store_id="+GetQueryString("store_id"),
		type:'get',
		dataType:'json',
		success:function(result){
			var rData =  result.datas;
			rData.WapSiteUrl = WapSiteUrl;
			var goods = rData.goods;
			var homeMap = [];
			for(var i=0;2*i<goods.length;i++){
				if (i<3){
				homeMap.push([goods[2*i],goods[2*i+1]]);
				}
				
			}
			rData.homeMap = homeMap;
			//JSON.stringify(rData.homeMap);
			//alert(JSON.stringify(rData.homeMap));
			var html = template.render('home_body',rData);
			$("#home-cnt-wp").html(html);
			
			
//			var home41 = rData.home41;
//			var homeMap4 = [];
//			for(var i=0;2*i<home41.length;i++){
//				homeMap4.push([home41[2*i],home41[2*i+1]]);
//			}
//			rData.homeMap4 = homeMap4;
//			var html = template.render('home_body',rData);
//			$("#home-cnt-wp").html(html);
			
			
			
//			addPaginat();
//			mySwipe();
			
//			$('.home1').click(function(){
//				var keyword = encodeURIComponent($(this).attr('keyword'));
//				location.href = WapSiteUrl+'/tmpl/product_list.html?keyword='+keyword;
//			});
//			
//			$('.home2').click(function(){
//				var keyword = encodeURIComponent($(this).attr('keyword'));
//				location.href = WapSiteUrl+'/tmpl/product_list.html?keyword='+keyword;
//			});
		}
	});
	
	$('.search-btn').click(function(){
		var keyword = encodeURIComponent($('#keyword').val());
		var store_id = $('input[name=store_id]').val();
		alert(WapSiteUrl+'/tmpl/product_list.html?keyword='+keyword+'&store_id='+store_id);
		location.href = WapSiteUrl+'/tmpl/store_goods_list.html?keyword='+keyword+'&store_id='+store_id;
	});
	$('.more').click(function(){
		var keyword = encodeURIComponent($('#keyword').val());
		location.href = WapSiteUrl+'/tmpl/store_goods_list.html?store_id='+store_id;
		
	});
	
	$('.it-is-me').click(function(){
		
		location.href = WapSiteUrl+'/tmpl/store_info.html?store_id='+store_id;
		
	});
	
	
	
	
});