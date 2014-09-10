$(function (){
	var headTitle = document.title;
	var tmpl = '<div class="header-wrap">'
	        		+'<a href="javascript:history.back();" class="header-back"><span>返回</span></a>'
						+'<h2>'+headTitle+'</h2>'
						+'<a href="javascript:void(0)" id="btn-opera" class="i-main-opera">'
					 	+'<span></span>'
				 	+'</a>'
    			+'</div>'
		    	
    //渲染页面
	var html = template.compile(tmpl);
	$("#header").html(html);
	$("#btn-opera").click(function (){
		location.href=WapSiteUrl+"/tmpl/map.html"
	});
	
});