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
	<a href="JavaScript:void(0);" class="button2" onclick="query()"><span>查询</span></a>&nbsp;&nbsp;&nbsp;
	<a href="JavaScript:void(0);" class="button2" onclick="band()"><span>关联</span></a>&nbsp;&nbsp;&nbsp;
    <a href="JavaScript:void(0);" class="button2" onclick="add()"><span>快速建档</span></a>
    <span class="errormsg" style="color:red;width:100%;display:block;text-align: center;font-weight: bold;"></span>
	<div class="row clearfix" style="overflow: auto;">
		<div class="col-md-5 column" style="overflow: auto;">
			<form method="post" id="form_memberlist">
				<input type="hidden" value="member" name="act">
        		<input type="hidden" value="member2" name="op">
		        <input type="hidden" name="form_submit" value="ok"/>
		        <input type="hidden" name=queryfileno />
		        <input type="hidden" id="member_id" name="member_id" value="<?php echo $output['member_id']; ?>" />
		        <input type="hidden" id="member_truename" name="member_truename" value="<?php echo $output['member_truename']; ?>" />
		        <input type="hidden" id="member_idnumber" name="member_idnumber" value="<?php echo $output['member_idnumber']; ?>" />
	        	<fieldset style="position: relative;padding:10px;line-height: 26px;">
	        		<p class="change">
	       <span> 姓名：</span><input type="text" name="queryname" id="queryname" value="<?php echo $_REQUEST['queryname']; ?>"/>
	        		</p>
	        		<p class="change">
	       <span>身份证号：</span><input type="text" name="queryidnumber" id="queryidnumber" value="<?php echo $_REQUEST['queryidnumber']; ?>"/>
	        		</p>
	        		<p class="change">
	       <span>电话号码：</span><input type="text" name="querytel" id="querytel" value="<?php echo $_REQUEST['querytel']; ?>"/>
	        		</p>
	        	</fieldset>
		        <table class="table">
		            <thead>
		            <tr class="thead">
		                <th>&nbsp;</th>
		                <th class="align-center">姓名</th>
		                <th class="align-center">性别</th>
		                <th class="align-center">出生年月</th>
<!-- 		                <th class="align-center">身份证号</th> -->
		                <th class="align-center">电话</th>
<!-- 		                <th class="align-center">地址</th> -->
		            </tr>
		            <tbody>
		            <?php if (!empty($output['member_list']) && is_array($output['member_list'])) { ?>
		                <?php foreach ($output['member_list'] as $k => $v) { ?>
		                    <tr class="hover member">
		                        <td class="w24"><input type="radio" name="check" value=<?php echo $v->FileNo; ?>|<?php echo $v->Name; ?>|<?php echo $v->IDNumber; ?> /></td>
		                        	
		                        
		                        <td class="align-left">
		                        	<?php echo $v->Name; ?>
		                        </td>
		                        <td class="align-left">
		                        	<?php echo $v->Sex; ?>
		                        </td>
		                        <td class="align-left">
		                        	<?php echo substr($v->Birthday,0,10); ?>
		                        </td>
		                        <td class="align-left">
		                        	<?php echo $v->TEL; ?>
		                        </td>
		                    </tr>
		                <?php } ?>
		            <?php } else { ?>
		                <tr class="no_data">
		                    <td colspan="7"><?php echo $lang['nc_no_record'] ?></td>
		                </tr>
		            <?php } ?>
		            </tbody>
		            <tfoot class="tfoot">
		            	
		            </tfoot>
		        </table>
		    </form>
		</div>
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
		            <a href="JavaScript:void(0);" class="button2" id="save-button" onclick="save()"><span>保存</span></a>
		        </form>
		    </span>
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
	        },
	        "保存": function () {
	            console.log($("#changedialog form").serialize());
	            $.ajax({
	                url: "index.php?act=member&op=changebaseinfo",
	                data: $("#changedialog form").serialize(), dataType: 'json', success: function (data) {
	                    console.log(data);
	                    if (data.success) {
	                        success('#changedialog', data.msg);
	                    } else {
	                        error('#changedialog', data.msg);
	                    }
	                }
	            }).fail(function( jqxhr, textStatus, errortext ) {
	                console.log(jqxhr, textStatus, errortext );
	                if(jqxhr.responseText.indexOf("您不具备进行该操作的权限")>=0){
	                    error('#changedialog', "您不具备进行该操作的权限!");
	                }else{
	                    error('#changedialog', "系统错误,请与管理员联系!");
	                }
	
	            });
	        }
	    }
	});
	$("input[name='check']").click(function(){
		//alert($("input[name='check']:checked").val());
		var checkrow = $("input[name='check']:checked").val().split("|");
		var param = {};
		param.fileno = checkrow[0];
		$("#save-button").hide();
		$.ajax({
	         url: "index.php?act=member&op=gethealthfiledetail",
	         data: param, 
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
	});
	
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
    $.validator.addMethod("checkeqname", function(value, element) {
        if(value != $('#member_truename').val()){
            return false;
        }
        return true; 
     }, "姓名与会员名称不一致！");
    $.validator.addMethod("checkeqidnumber", function(value, element) {
        if(value != ''){
	        if(value != $('#member_idnumber').val()){
	            return false;
	        }
	        return true; 
        }
        return true;
     }, "身份证号码与会员身份证号码不一致！");
    $.validator.addMethod("isIdCardNo", function(value, element) {
        return this.optional(element) || idCardNoUtil.checkIdCardNo(value); 
     }, "请正确输入您的身份证号码");
    $.validator.addMethod("checkIdnumber",function(value,element){
        var user = value;
        $.ajax({
            type:"POST",
            async:false, 
            dataType: 'json', 
            url:"index.php?act=member&op=ajax_checkidnumber",
            data:"idnumber="+user,
            success:function(response){
                console.log(response);
                if(response.result){
                    res = false;
                }else{
                    res = true;
                }
            }
        });
        return res;
    },"该身份证号在系统中已存在！");
//     $.validator.methods.date = function (value, element) {
//         //var matches = /(\d{4})[-\/](\d{2})[-\/](\d{2})/.exec(value);
//         var matches = /^((?:19|20)\d\d)-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/.exec(value);
//         if (matches == null) return this.optional(element)|| false;
//         return this.optional(element) || true;
//     };
    $.validator.addMethod("date", function(value, element) {
    	var matches = /^((?:19|20)\d\d)-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/.exec(value);
        if (matches == null) return this.optional(element)|| false;
        return this.optional(element) || true;
     }, "日期格式不正确，格式为yyyy-MM-dd(2016-12-01)");
//     jQuery.validator.addMethod("isIdCardNo", function(value, element) {
//         return this.optional(element) || idCardNoUtil.checkIdCardNo(value); 
//      }, "请正确输入您的身份证号码");
    $('#detailform').validate({
    	rules: {
    		Name:{ 
		    	required:true,
		    	checkeqname:true
			},
			Sex:{ 
		    	required:true
			},
			Birthday:{
				date:true,
			},
	    	IDNumber:{ 
		    	required:false,
		    	checkeqidnumber:true,
				isIdCardNo:true,
				checkIdnumber:true
			},
    	},
    	message: {
    		Name:{ 
		    	required:"姓名不能为空"
			},
			Sex:{ 
		    	required:"性别不能为空"
			},
			Birthday:{
				date:"日期格式不正确，格式为yyyy-MM-dd(2016-12-01)"
			},
	    	IDNumber:{
				isIdCardNo:"请输入正确的身份证号",
				checkIdnumber:"该身份证号在系统中已存在！"
			}
    	}
    });
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

function band(){
	var checkrow = $("input[name='check']:checked").val().split("|");
	var bandfileno = checkrow[0];
	var bandname = checkrow[1];
	var bandidnumber = checkrow[2];
	if($("input[name='check']:checked").val()=='' || $("input[name='check']:checked").val()== undefined){
		alert("请选择要关联的档案！");
		return;
	}
	if(bandname != $('#member_truename').val()){
		alert("关联的档案名称与会员名称不一致");
		return;
	}
	if(bandidnumber !='' && bandidnumber != undefined && $('#member_idnumber').val() != '' && $('#member_idnumber').val() != undefined){
		if(bandidnumber != $('#member_idnumber').val()){
			alert("关联的档案身份证号码与会员身份证号码不一致");
			return;
		}
	}
	$('input[name="op"]').val('ajax_bandhealthfile');
	//alert($("input[name='check']:checked").val());
	var params = $("#form_memberlist").serialize();
	params.fileno = bandfileno;
	$.ajax({
        url: "index.php?act=member&op=ajax_bandhealthfile",
        data: params, dataType: 'json', success: function (data) {
            //console.log(data);
            if (data.success) {
                //query();
//                 alert(data.msg);
                success('#memberinfo', data.msg);
            } else {
               error('#memberinfo', data.msg);
            }
        }
    }).fail(function( jqxhr, textStatus, errortext ) {
        console.log(jqxhr, textStatus, errortext );
        if(jqxhr.responseText.indexOf("您不具备进行该操作的权限")>=0){
            error('#memberinfo', "您不具备进行该操作的权限!");
        }else{
            error('#memberinfo', "系统错误,请与管理员联系!");
        }

    });
// 	$.ajax({
//         url: "index.php?act=member&op=member_moneydetail",
//         data: $("#detaildialog form").serialize(), dataType: 'json', success: function (data) {
//             console.log(data);
//             if (data.data && data.data.length > 0) {
//                 $("#detaildialog .datamsg").html('');
//                 $("#detaildialog table tbody").html('');
//                 for (var i = 0; i < data.data.length; i++) {
//                     var row = data.data[i];
//                     var rowstr = '<tr>';
//                     rowstr += '<td>' + textstr(row.datatypename) + '</td>';
//                     rowstr += '<td>' + textstr(row.id) + '</td>';
//                     rowstr += '<td>' + textstr(row.dPayDate) + '</td>';
//                     rowstr += '<td>' + textstr(row.MakePerson) + '</td>';
//                     rowstr += '<td>' + textstr(row.orgname) + '</td>';
//                     rowstr += '<td>' + numtostr(row.fRecharge) + '</td>';
//                     rowstr += '<td>' + numtostr(row.InitRecharge) + '</td>';
// //                    rowstr+='<td>'+numtostr(row.InitScale)+'</td>';
//                     rowstr += '<td>' + numtostr(row.fConsume) + '</td>';
//                     rowstr += '<td>' + numtostr(row.InitConsume) + '</td>';
//                     rowstr += '<td>' + numtostr(row.fScaleToMoney) + '</td>';
//                     rowstr += '<td>' + numtostr(row.fScale) + '</td>';
//                     rowstr += '<td>' + numtostr(row.fAddScale) + '</td>';
//                     rowstr += '<td>' + numtostr(row.InitScale) + '</td>';
//                     rowstr += '</tr>';
//                     $("#detaildialog table tbody").append(rowstr)
//                 }
//             } else {
//                 $("#detaildialog .datamsg").html('无数据!');
//             }
//             $("#detaildialog").dialog("option", "title", '充值消费明细  ' + obj.member_truename);
//             $("#detaildialog").dialog("open");
//         }
//     });
}

function add(){
	clearform();
	var param = {};
	param.member_id=$('input[name="member_id"]').val();
	$.ajax({
         url: "index.php?act=member&op=ajax_loadmember",
         data: param, 
         dataType: 'json', 
         success: function (data) {
             console.log(data);
             data.data.Birthday = data.data.Birthday.substring(0, 10);
             $("#detailform").autofill(data.data);
         },
         error:function(XMLResponse){
	     }
	});
	$("#save-button").show();
}

function query(){
	if(($('#queryname').val()=='' || $('#queryname').val()==undefined) && ($('#queryidnumber').val()=='' || $('#queryidnumber').val()==undefined)){
		alert("查询条件中姓名和身份证号不能同时为空！");
		return;
	}
	//$('input[name="op"]').val('member3');
    $('#form_memberlist').submit();
}

function clearform(){
	$('#PaperFileNo').val('');
	$('#Name').val('');
	$('#PaperFileNo').val('');
	$('#Birthday').val('');
	$('#Nation').val('');
	$('#IDNumber').val('');
	$('#WorkUnit').val('');
	$('#ResideType').val('');
	$('#Folk').val('');
	$('#farmStatus').val('');
	$('#townStatus').val('');
	$('#TEL').val('');
	$('#Village').val('');
	$('#BuildUnit').val('');
	$('#BuildPerson').val('');
	$('#Doctor').val('');
	$('#BuildDate').val('');
	$("input[name='check']:checked").attr('checked',false);
}

function save(){
	//var param = {};
	//param.fileno = $("input[name='check']:checked").val();
	//$("#save-button").hide();
	if(!$("#detailform").valid()){
		return;
	}
	$.ajax({
        url: "index.php?act=member&op=ajax_savehealthfile",
        data: $("#detailform").serialize(), dataType: 'json', success: function (data) {
            console.log(data);
            if (data.success) {
                query();
                success('#memberinfo', data.msg);
            } else {
               error('#memberinfo', data.msg);
            }
        }
    }).fail(function( jqxhr, textStatus, errortext ) {
        console.log(jqxhr, textStatus, errortext );
        if(jqxhr.responseText.indexOf("您不具备进行该操作的权限")>=0){
            error('#memberinfo', "您不具备进行该操作的权限!");
        }else{
            error('#memberinfo', "系统错误,请与管理员联系!");
        }

    });
}


</script>
</body>
</html>
