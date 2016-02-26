<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" media="all" rel="stylesheet">
    <link href="<?php echo ADMIN_TEMPLATES_URL;?>/css/skin_0.css" rel="stylesheet" type="text/css" id="cssfile"/>
	<link href="<?php echo RESOURCE_SITE_URL; ?>/js/ztree/css/zTreeStyle/zTreeStyle.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" SRC="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/jquery.ui.js"></script>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/i18n/zh-CN.js"
            charset="utf-8"></script>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.validation.min.js"></script>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.formautofill.js"></script>
    
	<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/ztree/js/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/checkvalid.js"></script>
</head>
<body style="padding-top: 0px;">
<style>
    table {
        width: 100%;
    }

    table tbody tr td {
        text-align: right;
    }

    /*前3列居中*/
/*     table tbody tr td:first-child, #detaildialog table tbody tr td:first-child + td, #detaildialog table tbody tr td:first-child + td + td { */
/*         text-align: center; */
/*     } */

    table td {
        border: solid 1px #808080;
        padding: 5px;
    }
    

    th {
        white-space: pre;
        background-color: lightblue;
        border: solid 1px #808080 !important;
        font-weight: bold;
        padding: 5px;
        text-align: center;
    }
    .div1{width:100%;border:1px red solid;overflow:hidden;zoom:1;}
    .div2{width:30%;height:200px;border:1px blue solid;float:left;}
    .div3{width:70%;height:200px;border:1px blue solid;float:left;}
    .button2{ font-size: 14px; color: #555; font-weight: 700; line-height:18px; background: transparent url(templates/default/images/sky/bg_position.gif) no-repeat scroll 0 -280px; display: inline-block; height: 38px; padding-left: 15px; margin-right:6px; cursor: pointer;}
    .button2 span { background: #FFF url(templates/default/images/sky/bg_position.gif) no-repeat scroll 100% -280px; display: inline-block;  padding: 10px 15px 10px 0;}
</style>
<div class="container" id ="memberinfo">
    <span class="errormsg" style="color:red;width:100%;display:block;text-align: center;font-weight: bold;"></span>
	<div class="row clearfix" style="overflow: auto;">
		
		<div class="col-md-7 column" style="overflow: auto;" >
		    <span>
		        <form id="detailform">
		            <input type="hidden" id="change_id" name="change_id">
		
		            <fieldset style="position: relative;padding:10px;margin-top:10px;line-height: 26px;">
		                <p class="change">
		                	<span>纸质档案编号:</span>
		                    <input id="PaperFileNo" name="PaperFileNo" type="text">
		                    <span>姓名：</span>
		                    <input id="Name" name="Name" type="text" >
		                </p>
		                <p class="change">
		                    <span>性别：</span>
		                    <label><input type="radio" name="Sex" value="男">男</label>
                    		<label><input type="radio" name="Sex" value="女">女</label>
		                    <span>出生日期:</span>
		                    <input  id="Birthday" name="Birthday" type="text">
		                </p>
		                <p class="change">
		                    <span>现住址：</span>
		                    <input id="Address" name="Address"  type="text">
		                    <span>户籍地址:</span>
		                    <input  id="ResidenceAddress" name="ResidenceAddress" type="text">
		                </p>
		                <p class="change">
		                    <span>国籍：</span>
		                    <input id="Nation" name="Nation"  type="text">
		                    <span>身份证号码:</span>
		                    <input  id="IDNumber" name="IDNumber" type="text">
		                </p>
		                <p class="change">
		                    <span>工作单位：</span>
		                    <input id="WorkUnit" name="WorkUnit"  type="text">
		                    <span>常住类型:</span>
		                    <input id="ResideType" name="ResideType" type="text">
		                </p>
		                <p class="change">
		                    <span>民族：</span>
		                    <input id="Folk" name="Folk"  type="text">
		                    <span>农业人口:</span>
		                    <input  id="farmStatus" name="farmStatus" type="text">
		                </p>
		                <p class="change">
		                    <span>城镇居民：</span>
		                    <input id="townStatus" name="townStatus"  type="text">
		                </p>
		                <p class="change">
		                    <span>联系电话：</span>
		                    <input  id="TEL" name="TEL" type="text">
		                    <span>村(居)委会：</span>
		                    <input id="Village" name="Village"  type="text" readonly value="" style="width:120px;"/>
		                    <input id="districtid" name="districtid"  type="hidden">
		                    <input id="pid" name="pid"  type="hidden">
		                    <a id="menuBtn" href="#" onclick="showMenu(); return false;">选择</a>
<!-- 		                    <input  id="Village" name="Village" type="text"> -->
		                </p>
		                <p class="change">
		                    <span>建档单位：</span>
		                    <select name="BuildUnit" id="BuildUnit" class="orgSelect">
		                        <?php
		                        foreach ($output['treelist'] as $k => $v) {
		                            ?>
		                            <option value="<?php echo $v->name; ?>"><?php echo $v->name; ?></option>
		                        <?php } ?>
		                    </select>
		                    
		                    <span>建档人：</span>
		                    <input id="BuildPerson" name="BuildPerson" type="text" readOnly="true" >
		                </p>
		                <p class="change">
		                    <span>责任医生：</span>
		                    <input  id="Doctor" name="Doctor" type="text">
		                    <span>建档日期：</span>
		                    <input  id="BuildDate" name="BuildDate" type="text" readOnly="true" >
		                </p>
		            </fieldset>
		            
		        </form>
		    </span>
		</div>
		<div class="col-md-5 column" style="overflow: auto;">
			<form method="post" id="form_memberlist">
				<input type="hidden" value="member" name="act">
        		<input type="hidden" value="member2" name="op">
		        <input type="hidden" name="form_submit" value="ok"/>
		        <input type="hidden" id="member_id" name="member_id" value="<?php echo $output['member_id']; ?>" />
		        <input type="hidden" id="member_truename" name="member_truename" value="<?php echo $output['member_truename']; ?>" />
		        <input type="hidden" id="member_idnumber" name="member_idnumber" value="<?php echo $output['member_idnumber']; ?>" />
	        	<input type="hidden" name="fileno" id="fileno" value="<?php echo $output['fileno']; ?>"/>
		        
		    </form>
		</div>
	</div>
</div>
<div id="menuContent" class="menuContent" style="display:none; position: absolute;">
	<ul id="treeDemo" class="ztree" style="margin-top:0; width:200px;"></ul>
</div>

<style>
    #healthfiledialog table {
        width: 100%;
    }

    #healthfiledialog table tbody tr td {
        text-align: right;
    }

    /*前3列居中*/
    #healthfiledialog table tbody tr td:first-child, #healthfiledialog table tbody tr td:first-child + td, #healthfiledialog table tbody tr td:first-child + td + td {
        text-align: center;
    }

    #healthfiledialog table td {
        border: solid 1px #808080;
        padding: 5px;
    }

    #healthfiledialog table th {
        white-space: pre;
        background-color: lightblue;
        border: solid 1px #808080;
        font-weight: bold;
        padding: 5px;
        text-align: center;
    }

    .yellow {
        background-color: #f2c6ff !important;
    }

    p.change {
        display: table-row;

    }

    p.change > input:first-child {

    }

    p.change > input {
        width: 150px;
    }

    p.change > span, p.change > input {
        display: table-cell;
        padding-left: 10px;

    }

</style>

<script>
$(function () {
	$("#healthfiledialog").dialog({
	    resizable: false,
	    maxHeight: 200,
	    width: 560,
	    modal: true,
	    autoOpen: false,
	    close: function () {
	        var elem = $(this).dialog("option", "elem");
	        $(elem).parent().parent().removeClass('yellow');
	    },
	    buttons: {
	        "关闭": function () {
	
	            $(this).dialog("close");
	        }
	    }
	});
	var a = {};
	a.fileno = $('#fileno').val();
	//$("#save-button").hide();
	$.ajax({
         url: "index.php?act=member&op=gethealthfiledetail",
         data: a, 
         dataType: 'json', 
         success: function (data) {
             console.log(data);
             data.data.Birthday = data.data.Birthday.substring(0, 10);
             data.data.BuildDate = data.data.BuildDate.substring(0, 10);
             $("#detailform").autofill(data.data);
         },
         error:function(XMLResponse){
	         //alert(XMLResponse.responseText);
	     }
	});
// 	$("input[name='check']").click(function(){
// 		//alert($("input[name='check']:checked").val());
// 		var checkrow = $("input[name='check']:checked").val().split("|");
// 		var param = {};
// 		param.fileno = checkrow[0];
// 		$("#save-button").hide();
// 		$.ajax({
// 	         url: "index.php?act=member&op=gethealthfiledetail",
// 	         data: param, 
// 	         dataType: 'json', 
// 	         success: function (data) {
// 	             console.log(data);
// 	             data.data.Birthday = data.data.Birthday.substring(0, 10);
// 	             data.data.BuildDate = data.data.BuildDate.substring(0, 10);
// 	             $("#detailform").autofill(data.data);
// 	         },
// 	         error:function(XMLResponse){
// 		         //alert(XMLResponse.responseText);
// 		     }
// 		});
// 	});
	

	//$("input[name='check']:eq(0)").attr("checked",'checked');
	var setting = {
		view: {
			dblClickExpand: false
		},
		data: {
			simpleData: {
				enable: true
			}
		},
		callback: {
			beforeClick: beforeClick,
			onClick: onClick
		}
	};
	console.log(<?php echo json_encode($output['treedata']); ?>);
    var zNodes =<?php echo json_encode($output['treedata']); ?>;
    //var lefttreeObj = $.fn.zTree.init($("#lefttree"), setting, zNodes);
    $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    
    
});

function beforeClick(treeId, treeNode) {
	var check = (treeNode && !treeNode.isParent);
	if (!check) alert("只能选择村（居）委会");
	return check;
}

function onClick(e, treeId, treeNode) {
	var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
	nodes = zTree.getSelectedNodes(),
	v = "";
	var ids = "";
	var pids = "";
	nodes.sort(function compare(a,b){return a.id-b.id;});
	for (var i=0, l=nodes.length; i<l; i++) {
		v += nodes[i].name + ",";
		ids += nodes[i].id + ",";
		pids += nodes[i].pId + ",";
	}
	if (v.length > 0 ) v = v.substring(0, v.length-1);
	if (ids.length > 0 ) ids = ids.substring(0, ids.length-1);
	if (pids.length > 0 ) pids = pids.substring(0, pids.length-1);
	var cityObj = $("#Village");
	cityObj.attr("value", v);
	$("#districtid").attr("value", ids);
	$("#pid").attr("value", pids);
}

function showMenu() {
	var cityObj = $("#Village");
	var cityOffset = $("#Village").offset();
	$("#menuContent").css({left:cityOffset.left + "px", top:cityOffset.top + cityObj.outerHeight() + "px"}).slideDown("fast");

	$("body").bind("mousedown", onBodyDown);
}
function hideMenu() {
	$("#menuContent").fadeOut("fast");
	$("body").unbind("mousedown", onBodyDown);
}
function onBodyDown(event) {
	if (!(event.target.id == "menuBtn" || event.target.id == "menuContent" || $(event.target).parents("#menuContent").length>0)) {
		hideMenu();
	}
}




</script>
</body>
</html>
