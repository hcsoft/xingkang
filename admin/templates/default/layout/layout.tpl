<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1">
    <meta http-equiv="Content-Type" content="text/html; charset={{$CHARSET}}">
    <title>{{$html_title}}</title>
    <script type="text/javascript" src="../data/resource/js/jquery.js"></script>
    <script type="text/javascript" src="../data/resource/js/jquery.validation.min.js"></script>
    <script type="text/javascript" src="../data/resource/js/admincp.js"></script>
    <script type="text/javascript" src="../data/resource/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="../data/resource/js/common.js" charset="utf-8"></script>

    <link href="{{ADMIN_TEMPLATES_URL}}/css/skin_0.css" rel="stylesheet" type="text/css" id="cssfile2"/>

    <script type="text/javascript">
        var SITEURL = '{{$SHOP_SITE_URL}}';
        var RESOURCE_SITE_URL = '{{$RESOURCE_SITE_URL}}';
        var MICROSHOP_SITE_URL = '{{$MICROSHOP_SITE_URL}}';
        var CIRCLE_SITE_URL = '{{$CIRCLE_SITE_URL}}';
        var ADMIN_TEMPLATES_URL = '{{$ADMIN_TEMPLATES_URL}}';
        var LOADING_IMAGE = "{{$ADMIN_TEMPLATES_URL}}/images/loading.gif";
        //换肤
        var cookie_skin = $.cookie("MyCssSkin");
        if (cookie_skin) {
            console.log(ADMIN_TEMPLATES_URL + "/css/" + cookie_skin + ".css");
            $('#cssfile2').attr("href", ADMIN_TEMPLATES_URL + "/css/" + cookie_skin + ".css");
        }
    </script>
</head>
<body>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
{{include file="$tpl_name"}}

</body>
</html>
