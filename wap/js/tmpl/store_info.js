$(function (){
	var store_id = GetQueryString("store_id");
	
	$('input[name=store_id]').val(store_id);
	$.ajax({
		url:ApiUrl+"/index.php?act=store&op=store_info&store_id="+GetQueryString("store_id"),
		type:'get',
		dataType:'json',
		success:function(result){
			
			var rData =  result.datas;
			
			var html = template.render('home_body',rData);
			$("#home-cnt-wp").html(html);
			
			

		}
	});
	
	
	
	
	
});