$(function(){
	var key = getcookie('key');
	if(key==''){
		window.location.href = WapSiteUrl+'/tmpl/member/login.html';
	}
	var page = pagesize;
	var curpage = 1;
	var hasMore = true;
	function initPage(page,curpage){
		$.ajax({
			type:'post',
			url:ApiUrl+"/index.php?act=member_order&op=order_list&page="+page+"&curpage="+curpage,	
			data:{key:key},
			dataType:'json',
			success:function(result){
				checklogin(result.login);//检测是否登录了
				var data = result.datas;
				data.hasmore = result.hasmore;//是不是可以用下一页的功能，传到页面里去判断下一页是否可以用
				data.WapSiteUrl = WapSiteUrl;//页面地址
				data.curpage = curpage;//当前页，判断是否上一页的disabled是否显示
				data.ApiUrl = ApiUrl;
				data.key = getcookie('key');
				template.helper('$getLocalTime', function (nS) {
					return new Date(parseInt(nS) * 1000).toLocaleString().replace(/:\d{1,2}$/,' ');  
				});
				var html = template.render('order-list-tmpl', data);
				$("#order-list").html(html);
				//取消订单
				$(".cancel-order").click(cancelOrder);
				//下一页
				$(".next-page").click(nextPage);
				//上一页
				$(".pre-page").click(prePage);
				//确认订单
				$(".sure-order").click(sureOrder);
			}
		});
	}
	//初始化页面
	initPage(page,curpage);
	//取消订单
	function cancelOrder(){
		var self = $(this);
		var order_id = self.attr("order_id");
		$.ajax({
			type:"post",
			url:ApiUrl+"/index.php?act=member_order&op=order_cancel",
			data:{order_id:order_id,key:key},
			dataType:"json",
			success:function(result){
				if(result.datas && result.datas == 1){
					initPage(page,curpage);
				}
			}
		});
	}
	//下一页
	function nextPage (){
		var self = $(this);
		var hasMore = self.attr("has_more");
		if(hasMore == "true"){
			curpage = curpage+1;
			initPage(page,curpage);
		}
	}
	//上一页
	function prePage (){
		var self = $(this);
		if(curpage >1){
			self.removeClass("disabled");
			curpage = curpage-1;
			initPage(page,curpage);
		}
	}
	//确认订单
	function sureOrder(){
		var self = $(this);
		var order_id = self.attr("order_id");
		$.ajax({
			type:"post",
			url:ApiUrl+"/index.php?act=member_order&op=order_receive",
			data:{order_id:order_id,key:key},
			dataType:"json",
			success:function(result){
				if(result.datas && result.datas == 1){
					initPage(page,curpage);
				}
			}
		});
	}
});