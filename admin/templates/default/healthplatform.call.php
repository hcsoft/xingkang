<?php defined('InShopNC') or exit('Access Invalid!'); ?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>呼叫中心</h3>
            <ul class="tab-base">
                <li><a href="index.php?act=healthplatform&op=call&status=99"
                       class="<?php if (!$_GET['status'] || $_GET['status'] == '99') echo 'current'; ?>"><span>全部</span></a>
                </li>
                <li><a href="index.php?act=healthplatform&op=call&status=1"
                       class="<?php if ($_GET['status'] == '1') echo 'current'; ?>"><span>待核实</span></a></li>
                <li><a href="index.php?act=healthplatform&op=call&status=2"
                       class="<?php if ($_GET['status'] == '2') echo 'current'; ?>"><span>真档</span></a></li>
                <li><a href="index.php?act=healthplatform&op=call&status=3"
                       class="<?php if ($_GET['status'] == '3') echo 'current'; ?>"><span>假档</span></a></li>
                <li><a href="index.php?act=healthplatform&op=call&status=4"
                       class="<?php if ($_GET['status'] == '4') echo 'current'; ?>"><span>未接电话</span></a></li>
                <li><a href="index.php?act=healthplatform&op=calllog"><span>回访日志</span></a></li>
            </ul>
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
                <td>会员卡号</td>
                <td><input type="text" value="<?php echo $output['member_id']; ?>" name="member_id"
                           class="txt"></td>
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
                <td>是否建档</td>
                <td><select name="hasfile">
                        <option value="">全部</option>
                        <option value="1" <?php if ('1' == $_GET['hasfile']){ ?>selected<?php } ?>>有健康档案</option>
                        <option value="-1" <?php if ('-1' == $_GET['hasfile']){ ?>selected<?php } ?>>无健康档案</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="12">
                    <table>
                        <tr>
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
        jQuery.extend(jQuery.validator.messages, {
            required: "不可空",
//        remote: "Veuillez remplir ce champ pour continuer.",
            email: "邮件格式不正确",
//        url: "Veuillez entrer une URL valide.",
            date: "日期格式不正确",
//        dateISO: "Veuillez entrer une date valide (ISO).",
            number: "数字格式不正确",
            digits: "数字格式不正确",
            creditcard: "信用卡格式不正确",
            equalTo: "不相等",
//        accept: "Veuillez entrer une valeur avec une extension valide.",
            maxlength: jQuery.validator.format("超过最大长度{0}"),
            minlength: jQuery.validator.format("未达到最小长度{0}"),
            rangelength: jQuery.validator.format("不在{0} 至 {1} 的长度范围"),
            range: jQuery.validator.format("不在{0} 至 {1} 的范围"),
            max: jQuery.validator.format("超过了最大值{0}"),
            min: jQuery.validator.format("未达到最小值{0}")
        });

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
                <th>卡类型</th>
                <th>出生日期</th>
                <th>档案编号</th>
                <th>手机</th>
                <th>联系电话</th>
                <th>地址</th>
                <th>身份证</th>
                <th>建卡日期</th>
                <th>末次消费日期</th>
                <th>末次消费地点</th>
                <th>储值余额</th>
                <th>赠送余额</th>
                <th>消费积分</th>
                <th>操作</th>
            </tr>
            <tbody>
            <?php if (!empty($output['member_list']) && is_array($output['member_list'])) { ?>
                <?php foreach ($output['member_list'] as $k => $v) { ?>
                    <tr class="hover member">
                        <td class="w24"></td>
                        <td class="nowrap"><?php echo $v['member_id']; ?></td>
                        <td class="nowrap"><?php echo $v['member_truename']; ?></td>
                        <td class=""><?php if ($v['member_sex'] == 1) {
                                echo '男';
                            } elseif ($v['member_sex'] == 2) {
                                echo '女';
                            } ?></td>
                        <td class="">

                            <?php
                            if ($v['CardType'] == 0) {
                                echo '普通卡';
                            } elseif ($v['CardType'] == 1 ) {
                                echo '储值卡';
                            } ?></td>
                        <td class="nowrap"><?php echo substr($v['member_birthday'], 0, 10); ?></td>
                        <td class="nowrap"><?php echo $v['FileNo']; ?></td>
                        <td class="nowrap"><span
                                style="display: inline-block;color:blue;"><?php echo $v['Mobile']; ?></span></td>
                        <td class="nowrap"><span
                                style="display: inline-block;color:blue;"><?php echo $v['sLinkPhone']; ?></span></td>
                        <td class="nowrap"><?php echo $v['sAddress']; ?></td>
                        <td class="nowrap"><?php echo $v['sIDCard']; ?></td>
                        <td class="nowrap"><?php echo substr($v['dCreateDate'], 0, 10); ?></td>
                        <td class="nowrap"><?php echo substr($v['LastPayDate'], 0, 10); ?></td>
                        <td class=""><?php echo $v['LastPayOrgName']; ?></td>
                        <td class="nowrap"><?php echo $v['available_predeposit']; ?></td>
                        <td class="nowrap"><?php echo number_format($v['fConsumeBalance'], 2); ?></td>
                        <td class="nowrap"><?php echo $v['member_points']; ?></td>
                        <td class="align-center">
                            <?php if (!empty($_REQUEST['status']) && $_REQUEST['status'] == '5') { ?>
<!--                                --><?php //echo json_encode($v); ?>
                                <?php echo $v['changestr']; ?>
                            <?php } else { ?>
                                    <a href="javascript:void(0)"
                                       onclick="showdetail('<?php echo htmlentities(json_encode($v)) ?>',this)">回访</a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr class="no_data">
                    <td colspan="18"><?php echo $lang['nc_no_record'] ?></td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot class="tfoot">
            <?php if (!empty($output['member_list']) && is_array($output['member_list'])) { ?>
                <tr>
                    <td colspan="18">
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
        width: 150px;
    }

    p.change > span, p.change > input {
        display: table-cell;
        padding-left: 10px;

    }
</style>

<div id="detaildialog" title="回访">
    <span class="errormsg" style="color:red;width:100%;display:block;text-align: center;font-weight: bold;"></span>
    <span>
        <form>
            <input type="hidden" id="callid" name="callid">

            <p>回访时间：<input style="color:blue;" id="spotdate" name="spotdate"
                           value="<?php echo date('Y-m-d', time()) ?>"></p>

            <p>回访结果：<input id="spotresult_pass" name="spotresult" type="radio" value="真档" checked>
                <label for="spotresult_pass" style="cursor:pointer">真档</label>
                <input id="spotresult_false" name="spotresult" type="radio" value="假档">
                <label for="spotresult_false" style="cursor:pointer">假档</label>
                <input id="spotresult_unknown" name="spotresult" type="radio" value="待核实">
                <label for="spotresult_unknown" style="cursor:pointer">待核实</label>
                <input id="spotresult_noanswer" name="spotresult" type="radio" value="未接电话">
                <label for="spotresult_noanswer" style="cursor:pointer">未接电话</label></p>
            <p>健康档案：<input id="spotresult_nofile" name="hasfile" type="radio" value="-1" checked>
                <label for="spotresult_nofile" style="cursor:pointer">无健康档案</label>
                <input id="spotresult_hasfile" name="hasfile" type="radio" value="1">
                <label for="spotresult_hasfile" style="cursor:pointer">有健康档案</label></p>
            <p style="vertical-align: top;padding-top:5px;">回访原因：<textarea style="color:blue;" id="reason" name="reason" value=""

                                                           rows="5"></textarea></p>

            <p style="vertical-align: top;">备&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注：<textarea style="color:blue;" id="remark" name="remark" value=""
                                                         rows="5"></textarea></p>

            <fieldset style="position: relative;padding:10px;margin-top:10px;">
                <legend style="position:absolute;left:20px;background-color: #fff;top:-10px;padding:0 10px;font-weight: bold;">基本资料修改</legend>

            <p class="change">
                <span>原姓名：</span>
                <input id="oldname" name="oldname" type="text" readonly>
                <span title="留空表示不修改">新姓名:</span>
                <input placeholder="留空表示不修改" title="留空表示不修改" id="newname" name="newname" type="text">
            </p>

            <p class="change">
                <span>原电话：</span>
                <input id="oldtel" name="oldtel" readonly type="text">
                <span title="留空表示不修改">新电话:</span>
                <input placeholder="留空表示不修改" title="留空表示不修改" id="newtel" name="newtel" type="text">
            </p>

            <p class="change">
                <span>原生日：</span>
                <input id="oldbirthday" name="oldbirthday" readonly
                       type="text">
                <span title="留空表示不修改">新生日:</span>
                <input placeholder="留空表示不修改" title="留空表示不修改" id="newbirthday" name="newbirthday" type="text">
            </p>

            <p class="change">
                <span>原身份证号：</span>
                <input id="oldidcard" name="oldidcard"  style="width:150px;" readonly type="text">
                <span title="留空表示不修改">新身份证号:</span>
                <input placeholder="留空表示不修改" style="width:150px;" title="留空表示不修改" id="newidcard" name="newidcard" type="text">
            </p>

            <p class="change">
                <span>原会员卡号：</span>
                <input id="oldid" name="oldid" readonly type="text">
                <span title="留空表示不修改">新会员卡号:</span>
                <input placeholder="留空表示不修改" title="留空表示不修改" id="newid" name="newid" type="text">
            </p>

            </fieldset>

        </form>
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
            $('input[name="op"]').val('call');
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
                    console.log($("#detaildialog form").serialize());
                    $.ajax({
                        url: "index.php?act=healthplatform&op=savecallajax",
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
