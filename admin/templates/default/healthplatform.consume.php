<?php defined('InShopNC') or exit('Access Invalid!'); ?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>消费频次提醒</h3>

        </div>
    </div>
    <div class="fixed-empty"></div>
    <form method="get" name="formSearch" id="formSearch">
        <input type="hidden" value="healthplatform" name="act">
        <input type="hidden" value="call" name="op">
        <input type="hidden" value="<?php echo $_GET['status'] ?>" name="status">
        <table class="tb-type1 noborder search">
            <tbody>
            <tr>
                <th><label>选择机构</label></th>
                <td colspan="1"><select name="orgids[]" id="orgids" class="orgSelect" multiple>
                        <?php
                        $orgids = $_GET['orgids'];
                        if (!isset($orgids)) {
                            $orgids = array();
                        }
                        foreach ($output['treelist'] as $k => $v) {
                            ?>
                            <option value="<?php echo $v->id; ?>"
                                    <?php if (in_array($v->id, $orgids)){ ?>selected<?php } ?>><?php echo $v->name; ?></option>
                        <?php } ?>
                    </select></td>
                </td>

                <td>卡类型</td>
                <td><select name="cardtype">
                        <option value="">全部</option>
                        <option value="0" <?php if ('0' == $_GET['cardtype']){ ?>selected<?php } ?>>普通卡</option>
                        <option value="1" <?php if ('1' == $_GET['cardtype']){ ?>selected<?php } ?>>储值卡</option>
                    </select>
                </td>
                <td>卡级别</td>
                <td><select name="cardgrade">
                        <option value="">全部</option>
                        <option value="0" <?php if ('0' == $_GET['cardgrade']){ ?>selected<?php } ?>>健康卡</option>
                        <option value="1" <?php if ('1' == $_GET['cardgrade']){ ?>selected<?php } ?>>健康金卡</option>
                        <option value="2" <?php if ('2' == $_GET['cardgrade']){ ?>selected<?php } ?>>健康钻卡</option>
                    </select>
                </td>
                <td>消费次数</td>
                <td><input id = 'consumnum' name="consumnum" value="<?php echo $_GET['consumnum']; ?>" type="text" style="width:50px;">
                </td>
                <td>
                                消费日期:
                                <input type="text" value="<?php if($_GET['dcodate_begin'] !=''){echo $_GET['dcodate_begin'];}else{echo date('Y-m-d',time());}; ?>" name="dcodate_begin" 
                                       class="txt date ">至
                                <input type="text" value="<?php if($_GET['dcodate_end'] !=''){echo $_GET['dcodate_end'];}else{echo date('Y-m-d',time());}; ?>" name="dcodate_end"
                                       class="txt date">
                            </td>

            </tr>
            <tr>
                <td colspan="12">
                    <table>
                        <tr>
                            <td>会员卡号</td>
                            <td><input type="text" value="<?php echo $output['member_id']; ?>" name="member_id"
                                       class="txt"></td>
                            <td>
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
                            
                            <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search "
                                   title="<?php echo $lang['nc_query']; ?>">&nbsp;</a></td>
                        </tr>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/jquery.ui.js"></script>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/i18n/zh-CN.js"
            charset="utf-8"></script>
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
            $('input.date').datepicker({dateFormat: 'yy-mm-dd',constrainInput:false}).removeAttr('readonly');
            //生成机构下拉
            function orgtext(n1, n2, list) {
                var texts = [];
                for (var idx in list) {
                    texts.push($(list[idx]).attr("title"));
                }
                return texts.join('<br>');
            }

            $("#orgids").multiselect(
                {
                    checkAllText: '选择全部',
                    uncheckAllText: '清除选择',
                    noneSelectedText: '未选择',
                    selectedText: orgtext
                }
            );
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
                <th>卡号</th>
                <th>姓名</th>
                <th>性别</th>
                <th>出生日期</th>
                <th>手机</th>
                <th>联系电话</th>
                <th>地址</th>
                <th>身份证</th>
                <th>消费次数</th>
            </tr>
            <tbody>
            <?php if (!empty($output['data_list']) && is_array($output['data_list'])) { ?>
                <?php foreach ($output['data_list'] as $k => $v) { ?>
                    <tr class="hover member">
                        <td class="w24"></td>
                        <td class="nowrap"><?php echo $v->member_id; ?></td>
                        <td class="nowrap"><?php echo $v->member_truename; ?></td>
                        <td class=""><?php if ($v->member_sex == 1) {
                                echo '男';
                            } elseif ($v->member_sex == 2) {
                                echo '女';
                            } ?></td>
                        <td class="nowrap"><?php echo substr($v->member_birthday, 0, 10); ?></td>
                        <td class="nowrap"><span
                                style="display: inline-block;color:blue;"><?php echo $v->Mobile; ?></span></td>
                        <td class="nowrap"><span
                                style="display: inline-block;color:blue;"><?php echo $v->sLinkPhone; ?></span></td>
                        <td class="nowrap"><?php echo $v->sAddress; ?></td>
                        <td class="nowrap"><?php echo $v->sIDCard; ?></td>
                        <td class="nowrap"><?php echo $v->consumnum; ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr class="no_data">
                    <td colspan="15"><?php echo $lang['nc_no_record'] ?></td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot class="tfoot">
            <?php if (!empty($output['data_list']) && is_array($output['data_list'])) { ?>
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

<style>
    #detaildialog table {
        width: 100%;
    }

    #detaildialog table tbody tr td {
        text-align: right;
    }

    /*前3列居中*/
    #detaildialog table tbody tr td:first-child, #detaildialog table tbody tr td:first-child + td, #detaildialog table tbody tr td:first-child + td + td {
        text-align: center;
    }

    #detaildialog table td {
        border: solid 1px #808080;
        padding: 5px;
    }

    #detaildialog table th {
        white-space: pre;
        background-color: lightblue;
        border: solid 1px #808080;
        font-weight: bold;
        padding: 5px;
        text-align: center;
    }

    .yellow {
        background-color: yellow !important;
    }

    p.change {
        display: table-row;

    }

    p.change > input:first-child {

    }

    p.change > input {
        width: 100px;
    }

    p.change > span, p.change > input {
        display: table-cell;
        padding-left: 10px;

    }
</style>



<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/i18n/zh-CN.js"
        charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.formautofill.js"></script>

<link rel="stylesheet" type="text/css"
      href="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/themes/smoothness/jquery.ui.css"/>
<script>
    $(function () {
        $('#ncsubmit').click(function () {
            $('input[name="op"]').val('consume');
            var consumnum = $('#consumnum').val();
            var   r   =   /^[0-9]*[1-9][0-9]*$/ ;
            if(!r.test(consumnum) || parseInt(consumnum)<3){
                alert('消费次数必须为正整数，且必须大于等于3');
                return;
            }
            $('#formSearch').submit();
        });
        $("#formSearch input").keypress(function(event){
            if(event.keyCode==13){
                $('#ncsubmit').click();
            }
        });

        $("#detaildialog").dialog({
            resizable: false,
            maxHeight: 200,
            width: 500,
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
                    console.log($("#detaildialog form").serialize());
                    $.ajax({
                        url: "index.php?act=healthplatform&op=consume",
                        data: $("#detaildialog form").serialize(), dataType: 'json', success: function (data) {
                            console.log(data);
                            if (data.success) {
                                success('#detaildialog', data.msg);
                            } else {
                                error('#detaildialog', data.msg);
                            }
                        }
                    });
                }
            }
        });

    });
    function showpsreset(id, elem) {
        $("#psresetdialog .errormsg").html('');
        $("#psresetdialog #cardid").val(id);
//        $("#psresetdialog").dialog("option", "position", {my: "right top", at: "left bottom", of: $(elem)});
        $("#psresetdialog").dialog("open");
    }

    function showdetail(objstr, elem) {
        var obj = eval('(' + unescape(objstr) + ')');
        $("#detaildialog .errormsg").html('');
        console.log(obj);
        var formdata = {
            'oldname': obj.member_truename,
            'oldtel': obj.sLinkPhone,
            'oldidcard': obj.sIDCard,
            'oldid': obj.member_id,
            'oldbirthday': obj.member_birthday.substring(0, 10)
        }
        $("#detaildialog form").autofill(formdata);
        $("#callid").val(obj.member_id);
        $("#detaildialog .datamsg").html('正在查询....');
        $(elem).parent().parent().addClass('yellow');
        $.ajax({
            url: "index.php?act=healthplatform&op=calldetailajax",
            data: $("#detaildialog form").serialize(), dataType: 'json', success: function (ret) {
                console.log(ret);
                if (ret && ret.data && ret.data.length > 0) {
                    $("#detaildialog .datamsg").html('');
                    $("#detaildialog table tbody").html('');
                    for (var i = 0; i < ret.data.length; i++) {
                        var row = ret.data[i];
                        var rowstr = '<tr>';
                        rowstr += '<td>' + textstr(row.datatypename) + '</td>';
                        rowstr += '<td>' + textstr(row.id) + '</td>';
                        rowstr += '<td>' + textstr(row.dPayDate) + '</td>';
                        rowstr += '<td>' + textstr(row.MakePerson) + '</td>';
                        rowstr += '<td>' + textstr(row.orgname) + '</td>';
                        rowstr += '<td>' + numtostr(row.fRecharge) + '</td>';
                        rowstr += '<td>' + numtostr(row.InitRecharge) + '</td>';
//                        rowstr+='<td>'+numtostr(row.InitScale)+'</td>';
                        rowstr += '<td>' + numtostr(row.fConsume) + '</td>';
                        rowstr += '<td>' + numtostr(row.InitConsume) + '</td>';
                        rowstr += '<td>' + numtostr(row.fScaleToMoney) + '</td>';
                        rowstr += '<td>' + numtostr(row.fScale) + '</td>';
                        rowstr += '<td>' + numtostr(row.fAddScale) + '</td>';
                        rowstr += '<td>' + numtostr(row.InitScale) + '</td>';
                        rowstr += '</tr>';
                        $("#detaildialog table tbody").append(rowstr)
                    }
                } else {
                    $("#detaildialog .datamsg").html('无数据!');
                }
                $("#detaildialog").dialog("option", "title", '回访  ' + obj.member_truename);
                $("#detaildialog").dialog("option", "elem", elem);
                $("#detaildialog").dialog("open");
            }
        });

    }
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
</script>
