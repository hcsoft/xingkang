<?php defined('InShopNC') or exit('Access Invalid!'); ?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><?php echo $lang['member_index_manage'] ?></h3>
            <ul class="tab-base">
                <li><a  href="index.php?act=member&op=member"><span><?php echo $lang['nc_manage'] ?></span></a></li>
<!--                <li><a href="index.php?act=member&op=member_add"><span>--><?php //echo $lang['nc_new'] ?><!--</span></a></li>-->
                <li><a href="index.php?act=member&op=changelog"><span>修改日志</span></a></li>
                <li><a href="JavaScript:void(0);" class="current"><span>注销日志</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form method="get" name="formSearch" id="formSearch">
        <input type="hidden" value="member" name="act">
        <input type="hidden" value="unregister" name="op">
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
            <tr>
                <td colspan="12">
                    <table>
                        <tr>
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
                            <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search "
                                   title="<?php echo $lang['nc_query']; ?>">&nbsp;</a>
<!--                                <a href="javascript:void(0);" id="ncexport" class="btn-export "-->
<!--                                   title="导出"></a>-->
                                <?php if ($output['search_field_value'] != '' or $output['search_sort'] != '') { ?>
                                    <a href="index.php?act=member&op=member"
                                       class="btns "><span><?php echo $lang['nc_cancel_search'] ?></span></a>
                                <?php } ?></td>
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
                <th class="align-center" colspan="2"><?php echo $lang['member_index_name'] ?></th>
                <th class="align-center" >注销情况</th>
                <th class="align-center" >注销前余额</th>
                <th class="align-center">基本信息</th>
                <th class="align-center">卡情况</th>
                <th class="align-center">办卡渠道</th>
                <th class="align-center">消费情况</th>
                <th class="align-center">账户余额</th>
<!--                <th class="align-center">--><?php //echo $lang['nc_handle']; ?><!--</th>-->
            </tr>
            <tbody>
            <?php if (!empty($output['member_list']) && is_array($output['member_list'])) { ?>
                <?php foreach ($output['member_list'] as $k => $v) { ?>
                    <tr class="hover member">
                        <td class="w24"></td>
                        <td class="w48 picture">
                            <div class="size-44x44"><span class="thumb size-44x44"><i></i><img
                                        src="<?php if ($v['member_avatar'] != '') {
                                            echo UPLOAD_SITE_URL . DS . ATTACH_AVATAR . DS . $v['member_avatar'];
                                        } else {
                                            echo UPLOAD_SITE_URL . '/' . ATTACH_COMMON . DS . C('default_user_portrait');
                                        } ?>?<?php echo microtime(); ?>"
                                        onload="javascript:DrawImage(this,44,44);"/></span></div>
                        </td>

                        <td>
                            <p class="name"><!--会员名:<strong><?php echo $v['member_name']; ?></strong>-->
                                卡号: <?php echo $v['member_id']; ?></p>

                            <p class="name"><!--会员名:<strong><?php echo $v['member_name']; ?></strong>-->
                                姓名: <?php echo $v['member_truename']; ?></p>

                            <p class="smallfont">电话:&nbsp;<?php echo $v['sLinkPhone']; ?></p>

                            <p class="smallfont">地址:&nbsp;<?php echo $v['sAddress']; ?></p>

                            <div class="im">
                            </div>
                        </td>
                        <td>
                            <p class="">注销时间:<strong  class="red"><?php echo substr($v['dChangeDate'],0,19)?></strong></p>
                            <p class="">注销原因:<strong  class="red"><?php echo $v['sMemo']?></strong></p>
                            <p class="">操作人员:<strong  class="red"><?php echo $v['UpdatePerson']?></strong></p>

                        </td>
                        <td><p class="name">
                            <p>储值余额:&nbsp;<strong  class="red"><?php echo number_format($v['fRecharge'],2); ?></strong>&nbsp;元</p>
                            <p>赠送余额: <strong class="red"><?php echo number_format($v['fConsume'], 2); ?></strong>&nbsp;元</p>
                            <p>赠送余额: <strong class="red"><?php echo number_format($v['fScale'], 2); ?></strong>&nbsp;元</p>
                        </td>
                        <td>
                            <p class="name">身份证: <?php echo $v['sIDCard']; ?></p>
                            <p class="name">生日: <?php echo substr($v['member_birthday'],0,10); ?></p>

                            <p class="smallfont">医保卡:&nbsp;<?php echo $v['MediCardID']; ?></p>

                            <p class="smallfont">健康档案:&nbsp;<?php echo $v['FileNo']; ?></p>
                        </td>

                        <td><p class="name">卡类型: <?php if ($v['CardType'] == 0) {
                                    echo '普通卡';
                                } elseif ($v['CardType'] == 1) {
                                    echo '储值卡';
                                } ?></p>

                            <p class="smallfont">卡级别: <?php if ($v['CardGrade'] == 0) {
                                    echo '健康卡';
                                } elseif ($v['CardGrade'] == 1) {
                                    echo '健康金卡';
                                } elseif ($v['CardGrade'] == 2) {
                                    echo '健康钻卡';
                                } ?></p>
                            <p class="smallfont">卡状态: <?php if ($v['iMemberState'] == 99) {
                                    echo '已注销';
                                } else {
                                    echo '正常';
                                } ?></p>
                        </td>
                        <td>
                        <p class="name">建卡时间: <?php echo substr($v['dCreateDate'], 0, 10); ?></p>
                        <p class="name">建卡机构: <?php echo $output['orgmap'][$v['CreateOrgID']]; ?></p>

                        <p class="name">办卡渠道: <?php echo $v['GetWay']; ?></p>

                            <p class="smallfont">推荐人:&nbsp;<?php echo $v['Referrer']; ?></p>

                        </td>
                        <td><p class="name">末次消费日期: <?php echo substr($v['LastPayDate'], 0, 10); ?></p>

                            <p class="smallfont">末次消费地点: <?php echo $v['LastPayOrgName']; ?></p>

                        </td>
                        <td class=""><p>储值余额:&nbsp;<strong
                                    class="red"><?php echo $v['available_predeposit']; ?></strong>&nbsp;元</p>
                            <!--<p><?php echo $lang['member_index_frozen']; ?>:&nbsp;<strong class="red"><?php echo $v['freeze_predeposit']; ?></strong>&nbsp;元</p>-->
                            <p>赠送余额: <strong class="red"><?php echo number_format($v['fConsumeBalance'], 2); ?></strong>&nbsp;元
                            </p>

                            <p>消费积分: <strong class="red"><?php echo $v['member_points']; ?></strong></p>

                        </td>
                        <td class="align-center">
                            <!--
                            <a href="javascript:void(0)"
                               onclick="showdetail('<?php echo htmlentities(json_encode($v)) ?>',this)">充值消费明细</a><br>
                            <a href="javascript:void(0)" onclick="showpsreset('<?php echo $v['member_id'] ?>',this)">密码重置</a><br>
                            <a href="javascript:void(0)" onclick="showchange('<?php echo htmlentities(json_encode($v)) ?>',this)">会员信息修改</a><br>
                            <a href="javascript:void(0)" onclick="showunregister('<?php echo htmlentities(json_encode($v)) ?>',this)">会员注销</a><br>

                            <a href="javascript:showindex()" target="_blank">健康服务索引</a>
    -->
                            <!--<a
                                href="index.php?act=member&op=member_edit&member_id=<?php echo $v['member_id']; ?>"><?php echo $lang['nc_edit'] ?></a>
                            | <a
                                href="index.php?act=notice&op=notice&member_name=<?php echo ltrim(base64_encode($v['member_name']), '='); ?>"><?php echo $lang['member_index_to_message']; ?></a>
                                -->
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
<div id="psresetdialog" title="密码重置">
    <span class="errormsg" style="color:red;width:100%;display:block;text-align: center;font-weight: bold;"></span>
    <span>
        <form>
            <input type="hidden" id="cardid" name="cardid">
        </form>
        密码将被重置为000000，是否确认重置？
    </span>
</div>

<div id="unregisterdialog" title="会员注销">
    <span class="errormsg" style="color:red;width:100%;display:block;text-align: center;font-weight: bold;"></span>
    <span>
        <form>
            <input type="hidden" id="unregister_id" name="unregister_id">
        </form>
        会员将被注销，是否确认注销？
    </span>
</div>


<style>
    #changedialog table {
        width: 100%;
    }

    #changedialog table tbody tr td {
        text-align: right;
    }

    /*前3列居中*/
    #changedialog table tbody tr td:first-child, #changedialog table tbody tr td:first-child + td, #changedialog table tbody tr td:first-child + td + td {
        text-align: center;
    }

    #changedialog table td {
        border: solid 1px #808080;
        padding: 5px;
    }

    #changedialog table th {
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
<div id="changedialog" title="基本信息修改">
    <span class="errormsg" style="color:red;width:100%;display:block;text-align: center;font-weight: bold;"></span>
    <span>
        <form>
            <input type="hidden" id="change_id" name="change_id">

            <fieldset style="position: relative;padding:10px;margin-top:10px;line-height: 26px;">
                <legend style="position:absolute;left:20px;background-color: #fff;top:-10px;padding:0 10px;font-weight: bold;line-height: 19px;">基本资料修改</legend>

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
                    <span>原性别：</span>
                    <label><input type="radio" name="oldsex" value="1">男</label>
                    <label><input type="radio" name="oldsex" value="2">女</label>
                    <span title="留空表示不修改">新性别:</span>
                    <label><input type="radio" name="newsex" value="1">男</label>
                    <label><input type="radio" name="newsex" value="2">女</label>
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
<div id="indexdialog" title="健康服务索引">
    <iframe class="iframe" style="border:none;width:100%;height;100%;" ></iframe>
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
            $('input[name="op"]').val('unregisterlog');
            $('#formSearch').submit();
        });

        $('#ncexport').click(function () {
            $('input[name="op"]').val('unregisterlog');
            $('#formSearch').submit();
        });


        $("#formSearch input").keypress(function(event){
            if(event.keyCode==13){
                $('#ncsubmit').click();
            }
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
                    }).fail(function( jqxhr, textStatus, errortext ) {
                        console.log(jqxhr, textStatus, errortext );
                        if(jqxhr.responseText.indexOf("您不具备进行该操作的权限")>=0){
                            error('#psresetdialog', "您不具备进行该操作的权限!");
                        }else{
                            error('#psresetdialog', "系统错误,请与管理员联系!");
                        }

                    });
                }
            }
        });
        $("#unregisterdialog").dialog({
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
                "取消": function () {
                    $(this).dialog("close");
                },
                "确定注销": function () {
                    $.ajax({
                        url: "index.php?act=member&op=unregister",
                        data: $("#unregisterdialog form").serialize(), dataType: 'json', success: function (data) {
                            console.log(data);
                            if (data.success) {
                                success('#unregisterdialog', data.msg);
                            } else {
                                error('#unregisterdialog', data.msg);
                            }
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
        $("#indexdialog").dialog({
            resizable: false,
            height: 800,
            width: 1300,
//            height:250,
            modal: true,
            autoOpen: false,
            buttons: {
                "关闭": function () {
                    $(this).dialog("close");
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
        $("#changedialog").dialog({
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
    });
    function showpsreset(id, elem) {
        $("#psresetdialog .errormsg").html('');
        $("#psresetdialog #cardid").val(id);
        $("#psresetdialog").dialog("option", "position", {my: "right top", at: "left bottom", of: $(elem)});
        $("#psresetdialog").dialog("open");
    }
    function showunregister(objstr, elem) {
        var obj = eval('(' + unescape(objstr) + ')');
        $("#unregisterdialog .errormsg").html('');
        $("#unregisterdialog #unregister_id").val(obj.member_id);
        $(elem).parent().parent().addClass('yellow');
        $("#unregisterdialog").dialog("option", "title", '会员注销  ' + obj.member_truename);
        $("#unregisterdialog").dialog("option", "elem", elem);
        $("#unregisterdialog").dialog("open");
    }
    function showindex(src){
        $("#indexdialog").dialog("option", {width:$(window).width()-50,height:$(window).height()-50});

        $("#indexdialog").dialog("open");
        $("#indexdialog .iframe").attr("src",'<?php echo ADMIN_SITE_URL ;?>/index.php?act=dashboard&op=timeline')
        $("#indexdialog .iframe").css("height",'100%')

    }

    function showdetail(objstr, elem) {
        var obj = eval('(' + unescape(objstr) + ')');
        $("#detaildialog .errormsg").html('');
        $("#cardid1").val(obj.member_id);
        $("#detaildialog .datamsg").html('正在查询....');
        $.ajax({
            url: "index.php?act=member&op=member_moneydetail",
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

    function showchange(objstr, elem) {
        var obj = eval('(' + unescape(objstr) + ')');
        $("#changedialog .errormsg").html('');
        console.log(obj);
        var formdata = {
            'oldname': obj.member_truename,
            'oldtel': obj.sLinkPhone,
            'oldidcard': obj.sIDCard,
            'oldid': obj.member_id,
            'oldbirthday': obj.member_birthday.substring(0, 10),
            'oldsex':obj.member_sex
        }
        $("#changedialog form").autofill(formdata);
        $("#change_id").val(obj.member_id);
        $("#changedialog .datamsg").html('正在查询....');
        $(elem).parent().parent().addClass('yellow');
        $("#changedialog").dialog("option", "title", '会员信息修改  ' + obj.member_truename);
        $("#changedialog").dialog("option", "elem", elem);
        $("#changedialog").dialog("open");

    }
</script>
