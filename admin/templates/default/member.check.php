<?php defined('InShopNC') or exit('Access Invalid!'); ?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>会员储值积分对账</h3>
            <ul class="tab-base">
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form method="get" name="formSearch" id="formSearch">
        <input type="hidden" value="member" name="act">
        <input type="hidden" value="member" name="op">
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
                <td><input type="text" value="<?php echo $_GET['member_id']; ?>" name="member_id"
                           class="txt"></td>
                <td>卡类型</td>
                <td><select name="cardtype">
                        <option value="">全部</option>
                        <option value="0" <?php if ('0'== $_GET['cardtype']){ ?>selected<?php } ?>>普通卡</option>
                        <option value="1" <?php if ('1'== $_GET['cardtype']){ ?>selected<?php } ?>>储值卡</option>
                    </select>
                </td>
                <td>卡级别</td>
                <td><select name="cardgrade">
                        <option value="">全部</option>
                        <option value="0"  <?php if ('0'== $_GET['cardgrade']){ ?>selected<?php } ?>>健康卡</option>
                        <option value="1"  <?php if ('1'== $_GET['cardgrade']){ ?>selected<?php } ?>>健康金卡</option>
                        <option value="2" <?php if ('2'== $_GET['cardgrade']){ ?>selected<?php } ?>>健康钻卡</option>
                    </select>
                </td>
                <td>
                    <label><input type="checkbox" id="flag1" name="flag1" value="1" <?php if ('1'== $_GET['flag1']){ ?>checked<?php } ?> >储值余额</label>
                    <label><input type="checkbox" id="flag2" name="flag2" value="1"<?php if ('1'== $_GET['flag2']){ ?>checked<?php } ?>>赠送余额</label>
                    <label><input type="checkbox" id="flag3" name="flag3" value="1"<?php if ('1'== $_GET['flag3']){ ?>checked<?php } ?>>积分余额</label>
                </td>
                <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search "
                       title="<?php echo $lang['nc_query']; ?>">&nbsp;</a>
                    <?php if ($output['search_field_value'] != '' or $output['search_sort'] != '') { ?>
                        <a href="index.php?act=member&op=member"
                           class="btns "><span><?php echo $lang['nc_cancel_search'] ?></span></a>
                    <?php } ?></td>
                <td>
                    排序字段：
                    <select name="orderby">
                        <?php foreach ($output['orderbys'] as $k => $v) { ?>
                            <option value="<?php echo $v['txt'] ?>"
                                    <?php if ($v['txt'] == $_GET['orderby']){ ?>selected<?php } ?> ><?php echo $v['txt'] ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td>
                    顺序：
                    <select name="order">
                        <option value="desc" <?php if ('desc' == $_GET['order']){ ?>selected<?php } ?> >倒序</option>
                        <option value="asc" <?php if ('asc' == $_GET['order']){ ?>selected<?php } ?> >正序</option>
                    </select>
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
                <th class="align-center">卡号</th>
                <th class="align-center">姓名</th>
                <th class="align-center">储值余额</th>
                <th class="align-center">计算储值余额</th>
                <th class="align-center">赠送余额</th>
                <th class="align-center">计算赠送余额</th>
                <th class="align-center">积分余额</th>
                <th class="align-center">计算积分余额</th>
                <th class="align-center">操作</th>
            </tr>
            <tbody>
            <?php if (!empty($output['data_list']) && is_array($output['data_list'])) { ?>
                <?php foreach ($output['data_list'] as $k => $v) { ?>
                    <tr class="hover member">
                        <td class="w24"></td>
                        <td class="w108"> <?php echo $v->member_id; ?></td>
                        <td class="w108 "><?php echo $v->member_truename; ?></td>
                        <td class="w108 align-right"><?php echo number_format($v->available_predeposit, 2); ?></td>
                        <td class="w108 align-right"><?php echo number_format($v->calc_predeposit, 2); ?></td>
                        <td class="w108 align-right"><?php echo number_format($v->fConsumeBalance, 2); ?></td>
                        <td class="w108 align-right"><?php echo number_format($v->calc_consume, 2); ?></td>
                        <td class="w108 align-right"><?php echo number_format($v->member_points, 2); ?></td>
                        <td class="w108 align-right"><?php echo number_format($v->calc_points, 2); ?></td>
                        <td class="w108 align-center"><a href="javascript:void(0)"
                                           onclick="showdetail('<?php echo htmlentities(json_encode($v)) ?>',this)">充值消费明细</a></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr class="no_data">
                    <td colspan="11"><?php echo $lang['nc_no_record'] ?></td>
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
<div id="psresetdialog" title="密码重置">
    <span class="errormsg" style="color:red;width:100%;display:block;text-align: center;font-weight: bold;"></span>
    <span>
        <form>
            <input type="hidden" id="cardid" name="cardid">
        </form>
        密码将被重置为000000，是否确认重置？
    </span>
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
</style>
<div id="detaildialog" title="充值消费明细">
    <span class="errormsg" style="color:red;width:100%;display:block;text-align: center;font-weight: bold;"></span>
    <span>
        <form>
            <input type="hidden" id="cardid1" name="cardid1">
        </form>
        <table>
            <thead>
            <tr>

                <th>数据类型</th>
                <th>id</th>
                <th>业务日期</th>
                <th>经办人</th>
                <th>服务社区</th>
                <th>储值增减</th>
                <th>储值余额</th>
                <th>赠送金额增减</th>
                <th>赠送余额</th>
                <th>积分消费金额</th>
                <th>兑换积分</th>
                <th>积分增减</th>
                <th>积分余额</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <span class="datamsg">无数据!</span>
    </span>
</div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/i18n/zh-CN.js"
        charset="utf-8"></script>
<link rel="stylesheet" type="text/css"
      href="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/themes/smoothness/jquery.ui.css"/>
<script>
    $(function () {
        $('#ncsubmit').click(function () {
            $('input[name="op"]').val('check');
            $('#formSearch').submit();
        });
        $("#psresetdialog").dialog({
            resizable: false,
//            width:350,
//            height:250,
//            modal: true,
            autoOpen: false,
            buttons: {
                "取消": function () {
                    $(this).dialog("close");
                },
                "确定重置": function () {
                    console.log($("#psresetdialog form").serialize());
                    $.ajax({
                        url: "index.php?act=member&op=psreset",
                        data: $("#psresetdialog form").serialize(), dataType: 'json', success: function (data) {
                            console.log(data);
                            if (data.success) {
                                success('#psresetdialog', data.msg);
                            } else {
                                error('#psresetdialog', data.msg);
                            }
                        }
                    });
                }
            }
        });

        $("#detaildialog").dialog({
            resizable: false,
            maxHeight: 200,
            width: 1100,
//            height:250,
//            modal: true,
            autoOpen: false,
            buttons: {
                "关闭": function () {
                    $(this).dialog("close");
                }
            }
        });
    });
    function showpsreset(id, elem) {
        $("#psresetdialog .errormsg").html('');
        $("#psresetdialog #cardid").val(id);
        $("#psresetdialog").dialog("option", "position", {my: "right top", at: "left bottom", of: $(elem)});
        $("#psresetdialog").dialog("open");
    }

    function showdetail(objstr, elem) {
        var obj = eval('(' + unescape(objstr) + ')');
        $("#detaildialog .errormsg").html('');
        $("#cardid1").val(obj.member_id);
        $("#detaildialog .datamsg").html('正在查询....');
        $.ajax({
            url: "index.php?act=member&op=membermoneydetail",
            data: $("#detaildialog form").serialize(), dataType: 'json', success: function (data) {
                console.log(data);
                if (data.data && data.data.length > 0) {
                    $("#detaildialog .datamsg").html('');
                    $("#detaildialog table tbody").html('');
                    for (var i = 0; i < data.data.length; i++) {
                        var row = data.data[i];
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
                $("#detaildialog").dialog("option", "title", '充值消费明细  ' + obj.member_truename);
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
