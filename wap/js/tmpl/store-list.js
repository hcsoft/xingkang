$(function (){
	var key = getcookie('key');
//	if(key==''){
//		window.location.href = WapSiteUrl+'/tmpl/member/login.html';
//	}
	key ='tiger'
	
    //初始化页面数据
    function initCartList(){
         $.ajax({
            url:ApiUrl+"/index.php?act=store&op=store_list",
            type:"post",
            dataType:"json",
            success:function (result){
                    if(!result.datas.error){
                        var rData = result.datas;
                        var html = template.render('home_body', result.datas);
            			$("#store_list").append(html);
                    }else{
                       alert(result.datas.error);
                    }
               
            }
        });
    }
   initCartList();
   
   $('.keyorder').click(function(){
		var key = parseInt($("input[name=key]").val());
		var order = parseInt($("input[name=order]").val());
		var page = parseInt($("input[name=page]").val());			
		var curpage = eval(parseInt($("input[name=curpage]").val())-1);
		var gc_id = parseInt($("input[name=gc_id]").val());
		var keyword = $("input[name=keyword]").val();
		var hasmore = $("input[name=hasmore]").val();

		var curkey = $(this).attr('key');//1.   2.人气 3.信誉 4.距离
		
		
		$(this).addClass("current").siblings().removeClass("current");
		
		
		//var url = ApiUrl+"/index.php?act=goods&op=goods_list&key="+curkey+"&order="+curorder+"&page="+page+"&curpage=1&keyword="+keyword;
		
		var url = ApiUrl+"/index.php?act=store&op=store_list&key="+curkey;
		$.ajax({
			url:url,
			type:'get',
			dataType:'json',
			success:function(result){
				$("input[name=hasmore]").val(result.hasmore);
				var html = template.render('home_body', result.datas);
				$("#store_list").empty();
				$("#store_list").append(html);	
				$("input[name=key]").val(curkey);
				$("input[name=order]").val(curorder);			
			}
		});
	});
	
    
});