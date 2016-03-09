<?php defined('InShopNC') or exit('Access Invalid!'); ?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>历史会员</h3>
            
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form method="get" name="formSearch" id="formSearch">
        <input type="hidden" value="member" name="act">
        <input type="hidden" value="memberhistory" name="op">
        <input type="hidden" id ='export' name="export" value="false">
        <table class="tb-type1 noborder search">
            <tbody>
            <tr>
                <td>会员卡号：</td>
                <td><input type="text" value="<?php echo $_GET['member_id']; ?>" name="member_id"
                           class="txt"></td>
                
                <td>是否激活：</td>
                <td><select name="activeflag">
                        <option value="0" <?php if ('0' == $_GET['activeflag']){ ?>selected<?php } ?>>未激活</option>
                        <option value="1" <?php if ('1' == $_GET['activeflag']){ ?>selected<?php } ?>>已激活</option>
                        
                    </select>
                </td>
                <td >
                                身份证号码:
                                <input type="text" value="<?php echo $_GET['idnumber']; ?>" name="idnumber"
                                       class="txt">
                            </td>
                 <td>
                                会员姓名:
                                <input type="text" value="<?php echo $_GET['name']; ?>" name="name"
                                       class="txt">
                            </td>
                            <td>
                                电话:
                                <input type="text" value="<?php echo $_GET['tel']; ?>" name="tel"
                                       class="txt">
                            </td>
                            <td>
                                生日:
                                <input type="text" value="<?php echo $_GET['birthday']; ?>" name="birthday"
                                       class="txt date">
                            </td>
                            <td>
                                建卡日期:
                                <input type="text" value="<?php echo $_GET['createcard_begin']; ?>" name="createcard_begin"
                                       class="txt date ">至
                                <input type="text" value="<?php echo $_GET['createcard_end']; ?>" name="createcard_end"
                                       class="txt date">
                            </td>
                            <td>
			            	<a href="javascript:void(0);" id="ncsubmit" class="btn-search "
                                   title="<?php echo $lang['nc_query']; ?>">&nbsp;</a>
                                <a href="javascript:void(0);" id="ncexport" class="btn-export "
                                   title="导出"></a>
                                <?php if ($output['search_field_value'] != '' or $output['search_sort'] != '') { ?>
                                    <a href="index.php?act=member&op=member"
                                       class="btns "><span><?php echo $lang['nc_cancel_search'] ?></span></a>
                                <?php } ?>
			            	</td>
            </tr>
           
            </tbody>
        </table>
    </form>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/jquery.ui.js"></script>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/i18n/zh-CN.js"
            charset="utf-8"></script>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.validation.min.js"></script>
    <link rel="stylesheet" type="text/css"
          href="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/themes/smoothness/jquery.ui.css"/>
    <link href="<?php echo RESOURCE_SITE_URL; ?>/js/ztree/css/zTreeStyle/zTreeStyle.css" rel="stylesheet"
          type="text/css"/>
    <link href="<?php echo RESOURCE_SITE_URL; ?>/js/multiselect/jquery.multiselect.css" rel="stylesheet"
          type="text/css"/>
    <script type="text/javascript"
            src="<?php echo RESOURCE_SITE_URL; ?>/js/ztree/js/jquery.ztree.all-3.5.min.js"></script>
    <script type="text/javascript"
            src="<?php echo RESOURCE_SITE_URL; ?>/js/multiselect/jquery.multiselect.min.js"></script>
    <script>
        $(function () {
            $('input.date').datepicker({dateFormat: 'yy-mm-dd'}).removeAttr('readonly');
            
        });
    </script>
    <table class="table tb-type2" id="prompt">
        <tbody>
        <tr class="space odd">
            <th colspan="12">
                <div class="title">
                    <h5><?php echo $lang['nc_prompts']; ?></h5>
                    <span class="arrow"></span></div>
            </th>
        </tr>
        </tbody>
    </table>
    <form method="post" id="form_member">
        <input type="hidden" name="form_submit" value="ok"/>
        <table class="table tb-type2 nobdb">
            <thead>
            <tr class="thead">
                <th>&nbsp;</th>
                <th class="align-center">会员卡号</th>
                <th class="align-center">姓名</th>
                <th class="align-center">电话</th>
                <th class="align-center">性别</th>
                <th class="align-center">身份证</th>
                <th class="align-center">生日</th>
                <th class="align-center">地址</th>
                <th class="align-center">建卡日期</th>
                <th class="align-center">激活状态</th>
                <th class="align-center">操作</th>
            </tr>
            <tbody>
            <?php if (!empty($output['member_list']) && is_array($output['member_list'])) { ?>
                <?php foreach ($output['member_list'] as $k => $v) { ?>
                    <tr class="hover member">
                        <td class="w24"></td>
                        <td class="align-center">
                        	<?php echo $v->member_id ?>
                        </td>
                        <td class=" align-center">
                        	<?php echo $v->Name ?>
                        </td>
                        <td class=" align-center">
                        	<?php echo $v->TEL ?>
                        </td>
                        <td class="align-center">
                        	<?php echo $v->Sex ?>
                        </td>
                        <td class="align-center">
                        	<?php echo $v->IDNumber ?>
                        </td>
                        <td class="align-center">
                        	<?php echo substr($v->Birthday,0,10); ?>
                        </td>
                        <td class="align-left">
                        	<?php echo $v->Address?>
                        </td>
                        <td class="align-center">
                        	<?php echo substr($v->dCreateDate,0,10); ?>
                        </td>
                        <td class="align-center">
                        	 <?php if ($v->activeflag == 0) {
                                    echo '未激活';
                                } elseif ($v->activeflag == 1) {
                                    echo '已激活';
                                } ?>
                        </td>
                        <td class="align-center">
                        	<?php if ($v->activeflag == 0) { ?>
                            <a href="javascript:void(0)"
                               onclick="showactivedialog('<?php echo htmlentities(json_encode($v)) ?>',this)">激活</a><br>
                               <?php }?>
                               </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr class="no_data">
                    <td colspan="11"><?php echo $lang['nc_no_record'] ?></td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot class="tfoot">
            <?php if (!empty($output['member_list']) && is_array($output['member_list'])) { ?>
                <tr>
                    <td colspan="16">
                        <div class="pagination"> <?php echo $output['page']; ?> </div>
                    </td>
                </tr>
            <?php } ?>
            </tfoot>
        </table>
    </form>
</div>
<div id="activedialog" title="激活">
    <span class="errormsg" style="color:red;width:100%;display:block;text-align: center;font-weight: bold;"></span>
    <span>
        <form>
            <input type="hidden" id="id" name="id">
        </form>
      是否确认激活？
    </span>
</div>



<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/i18n/zh-CN.js"
        charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.formautofill.js"></script>

<link rel="stylesheet" type="text/css"
      href="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/themes/smoothness/jquery.ui.css"/>
<script>
    $(function () {
        $('#ncsubmit').click(function () {
            //alert(1111);
            $('input[name="op"]').val('memberhistory');
            $('#formSearch').submit();
        });

        $('#ncexport').click(function () {
            $("#export").val('true');
            $('#formSearch').submit();
        });


        $("#formSearch input").keypress(function(event){
            if(event.keyCode==13){
                $('#ncsubmit').click();
            }
        });

        $("#activedialog").dialog({
            resizable: false,
//            width:350,
//            height:250,
//            modal: true,
            autoOpen: false,
            close: function () {
                var elem = $(this).dialog("option", "elem");
                $(elem).parent().parent().removeClass('yellow');
            },
            buttons: {
                "关闭": function () {
                    $(this).dialog("close");
                },
                "确定激活": function () {
                    $.ajax({
                        url: "index.php?act=member&op=ajax_active",
                        data: $("#activedialog form").serialize(), dataType: 'json', success: function (data) {
                            console.log(data);
                            if (data.success) {
                                success('#activedialog', data.msg);
                            } else {
                                error('#activedialog', data.msg);
                            }
                            $('#formSearch').submit();
                        }
                    }).fail(function( jqxhr, textStatus, errortext ) {
                        console.log(jqxhr, textStatus, errortext );
                        if(jqxhr.responseText.indexOf("您不具备进行该操作的权限")>=0){
                            error('#unregisterdialog', "您不具备进行该操作的权限!");
                        }else{
                            error('#unregisterdialog', "系统错误,请与管理员联系!");
                        }

                    });
                }
            }
        });
    });
    function numtostr(numstr) {
        var num = parseFloat(numstr);
        if (num) {
            return "" + num.toFixed(2);
        } else {
            return "";
        }
    }
    function textstr(tstr) {
        if (tstr) {
            return tstr;
        } else {
            return '';
        }
    }
    function error(selector, msg) {
        $(selector + " .errormsg").css("color", "red");
        $(selector + " .errormsg").html(msg);
    }
    function success(selector, msg) {
        $(selector + " .errormsg").css("color", "green");
        $(selector + " .errormsg").html(msg);
    }

    function showactivedialog(objstr, elem) {
        var obj = eval('(' + unescape(objstr) + ')');
        $("#activedialog .errormsg").html('');
        $("#activedialog #id").val(obj.ID);
        $(elem).parent().parent().addClass('yellow');
        $("#activedialog").dialog("option", "title", '会员激活' + obj.Name);
        $("#activedialog").dialog("option", "elem", elem);
        $("#activedialog").dialog("open");
    }
</script>
