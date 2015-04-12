<?php defined('InShopNC') or exit('Access Invalid!'); ?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>商品调价审核</h3>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form method="get" name="formSearch" id="formSearch">
        <input type="hidden" value="goods" name="act">
        <input type="hidden" value="changeprice" name="op">
        <input type="hidden" value="false" id="export" name="export">
        <input type="hidden" value="<?php echo $_GET['showmore'] ?>" id="showmore" name="showmore">
        <table class="tb-type1 noborder search">
            <tbody>
            <tr>
                <td>
                    <table>
                        <tr>
                            <!--机构-->
                            <th><label>选择机构</label></th>
                            <td colspan="1">
                                <select name="orgids[]" id="orgids" class="orgSelect" multiple>
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
                                </select>
                            </td>
                            <th><label for="iDrug_ID">商品编码</label></th>
                            <td>
                                <input class="txt" type="text" value="<?php echo $_GET['iDrug_ID']; ?>"
                                       id="iDrug_ID" name="iDrug_ID" digits="true">
                            </td>
                            <th><label for="ItemName">商品名称</label></th>
                            <td>
                                <input class="txt" type="text" value="<?php echo $_GET['ItemName']; ?>"
                                       id="ItemName" name="ItemName">
                            </td>

                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table>
                        <tr>
                            <th><label for="query_start_time">调价日期</label></th>
                            <td colspan="3">
                                <input class="txt date" type="text" value="<?php echo $_GET['dPrice_Date_begin']; ?>"
                                       id="dPrice_Date_begin" name="dPrice_Date_begin">
                                <input class="txt date" type="text" value="<?php echo $_GET['dPrice_Date_end']; ?>"
                                       id="dPrice_Date_end" name="dPrice_Date_end"/>
                            </td>
                            <th>
                                排序字段：
                            </th>
                            <td>
                                <select name="orderby">
                                    <?php foreach ($output['orderbys'] as $k => $v) { ?>
                                        <option value="<?php echo $v['txt'] ?>"
                                                <?php if ($v['txt'] === $_GET['orderby']){ ?>selected<?php } ?> ><?php echo $v['txt'] ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <th> 顺序：</th>
                            <td>
                                <select name="order">
                                    <option value="desc" <?php if ('desc' == $_GET['order']){ ?>selected<?php } ?> >倒序
                                    </option>
                                    <option value="asc" <?php if ('asc' == $_GET['order']){ ?>selected<?php } ?> >正序
                                    </option>
                                </select>
                            </td>

                            </td>
                            <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search "
                                   title="<?php echo $lang['nc_query']; ?>">&nbsp;</a>
                            </td>
                            <td><a href="javascript:void(0);" id="ncexport" class="btn-export "
                                   title="导出"></a>
                            </td>
                            <td><a href="javascript:void(0);" onclick="search_more()">更多查询条件</a></td>
                            <td>
                                <button type="button" onclick="allcheck()" title="审核满足查询条件的全部记录">审核全部</button>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="search_more">
                <td>
                    <table>
                        <tr>

                            <th><label for="query_start_time">执行开始日期</label></th>
                            <td colspan="3">
                                <input class="txt date" type="text"
                                       value="<?php echo $_GET['dPrice_BeginDate_begin']; ?>"
                                       id="dPrice_BeginDate_begin" name="dPrice_BeginDate_begin">
                                <input class="txt date" type="text" value="<?php echo $_GET['dPrice_BeginDate_end']; ?>"
                                       id="dPrice_BeginDate_end" name="dPrice_BeginDate_end"/>
                            </td>

                            <th><label for="sPrice_Person">调价人</label></th>
                            <td>
                                <input class="txt" type="text" value="<?php echo $_GET['sPrice_Person']; ?>"
                                       id="sPrice_Person" name="sPrice_Person">
                            </td>
                            <th><label for="iPrice_State">调价状态</label></th>
                            <td>
                                <select name="iPrice_State" id="iPrice_State">
                                    <?php
                                    foreach ($output['map_iPrice_State'] as $k => $v) {
                                        ?>
                                        <option value="<?php echo $k; ?>"
                                                <?php if ($k === $_GET['iPrice_State']){ ?>selected<?php } ?>><?php echo $v; ?></option>
                                    <?php } ?>
                                </select>
                            </td>


                            <th><label for="sPrice_CheckPerson">审核人</label></th>
                            <td>
                                <input class="txt" type="text" value="<?php echo $_GET['sPrice_CheckPerson']; ?>"
                                       id="sPrice_CheckPerson" name="sPrice_CheckPerson">
                            </td>

                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="search_more">
                <td>
                    <table>
                        <tr>
                            <th><label for="dPrice_EndDate_begin">执行结束日期</label></th>
                            <td colspan="3">
                                <input class="txt date" type="text" value="<?php echo $_GET['dPrice_EndDate_begin']; ?>"
                                       id="dPrice_EndDate_begin" name="dPrice_EndDate_begin">
                                <input class="txt date" type="text" value="<?php echo $_GET['dPrice_EndDate_end']; ?>"
                                       id="dPrice_EndDate_end" name="dPrice_EndDate_end"/>
                            </td>
                            <th><label for="Unit">单位</label></th>
                            <td>
                                <input class="txt" type="text" value="<?php echo $_GET['Unit']; ?>"
                                       id="Unit" name="Unit">
                            </td>
                            <th><label for="ItemType">商品类型</label></th>
                            <td>
                                <input class="txt" type="text" value="<?php echo $_GET['ItemType']; ?>"
                                       id="ItemType" name="ItemType">
                            </td>
                            <th><label for="iPrice_Type">单价类型</label></th>
                            <td>
                                <select name="iPrice_Type" id="iPrice_Type">
                                    <?php
                                    foreach ($output['map_iPrice_Type'] as $k => $v) {
                                        ?>
                                        <option value="<?php echo $k; ?>"
                                                <?php if ($k === $_GET['iPrice_Type']){ ?>selected<?php } ?>><?php echo $v; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="search_more">
                <td>
                    <table>
                        <tr>
                            <th><label for="dPrice_CheckDate_begin">审核日期</label></th>
                            <td colspan="3">
                                <input class="txt date" type="text"
                                       value="<?php echo $_GET['dPrice_CheckDate_begin']; ?>"
                                       id="dPrice_CheckDate_begin" name="dPrice_CheckDate_begin">
                                <input class="txt date" type="text" value="<?php echo $_GET['dPrice_CheckDate_end']; ?>"
                                       id="dPrice_CheckDate_end" name="dPrice_CheckDate_end"/>
                            </td>
                            <th><label for="fPrice_Before_begin">调前价</label></th>
                            <td>
                                <input number="true" class="txt" type="text"
                                       value="<?php echo $_GET['fPrice_Before_begin']; ?>"
                                       id="fPrice_Before_begin" name="fPrice_Before_begin">至
                                <input number="true" class="txt" type="text"
                                       value="<?php echo $_GET['fPrice_Before_end']; ?>"
                                       id="fPrice_Before_end" name="fPrice_Before_end">
                            </td>
                            <th><label for="ItemType">调后价</label></th>
                            <td>
                                <input number="true" class="txt" type="text"
                                       value="<?php echo $_GET['fPrice_After_begin']; ?>"
                                       id="fPrice_After_begin" name="fPrice_After_begin">至
                                <input number="true" class="txt" type="text"
                                       value="<?php echo $_GET['fPrice_After_end']; ?>"
                                       id="fPrice_After_end" name="fPrice_After_end">
                            </td>


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
    <form id="tableform">
        <table class="table tb-type2 nobdb">
            <thead>
            <tr class="thead">
                <th>
                    <button type="button"
                            onclick="checkSelect()">
                        审核选中
                    </button>
                </th>
                <th>商品编码</th>
                <th>商品名称</th>
                <th>调前价</th>
                <th>调后价</th>
                <th>调价日期</th>
                <th>执行开始日期</th>
                <th>执行结束日期</th>
                <th>调价人</th>
                <th>调价状态</th>
                <th>审核日期</th>
                <th>审核人</th>
                <th>机构</th>
                <th>单位</th>
                <th>项目类型</th>
                <th>价格类型</th>
                <th>操作</th>
            </tr>
            <tbody>
            <?php if (!empty($output['ret_list']) && is_array($output['ret_list'])) { ?>
                <?php foreach ($output['ret_list'] as $k => $v) { ?>
                    <tr class="hover member">
                        <td class=" align-left" style="vertical-align: middle;">
                            <label>
                                <?php if (empty($v->sPrice_CheckPerson)) { ?>
                                    <input type="checkbox" name="Iids[]" value="<?php echo $v->iID ?>" >
                                <?php } else { ?>
                                    <input type="checkbox" name="Iids[]" value="<?php echo $v->iID ?>"  disabled>
                                <?php  } ?>

                                <?php echo $k + 1 ?>
                            </label>

                        </td>
                        <td class=" align-center">
                            <?php echo $v->iDrug_ID ?>
                        </td>
                        <td class=" align-left" style="white-space: inherit;">
                            <?php echo $v->ItemName ?>
                        </td>
                        <td class=" align-right">
                            <?php echo number_format($v->fPrice_Before, 2) ?>
                        </td>
                        <td class=" align-right">
                            <?php echo number_format($v->fPrice_After, 2) ?>
                        </td>
                        <td class=" align-center">
                            <?php echo substr($v->dPrice_Date, 0, 10) ?>
                        </td>
                        <td class=" align-center">
                            <?php echo substr($v->dPrice_BeginDate, 0, 10) ?>
                        </td>
                        <td class=" align-center">
                            <?php echo substr($v->dPrice_EndDate, 0, 10) ?>
                        </td>
                        <td class=" align-center">
                            <?php echo $v->sPrice_Person ?>
                        </td>
                        <td class=" align-center">
                            <?php echo $output['map_iPrice_State'][$v->iPrice_State] ?>
                        </td>
                        <td class=" align-center">
                            <?php if (empty($v->dPrice_CheckDate)) {
                                echo substr($v->dPrice_CheckDate, 0, 10);
                            } else {
                                echo '';
                            } ?>
                        </td>
                        <td class=" align-center">
                            <?php echo $v->sPrice_CheckPerson ?>
                        </td>
                        <td class=" align-center">
                            <?php echo $output['org_map'][$v->OrgID] ?>
                        </td>
                        <td class=" align-center">
                            <?php echo $v->Unit ?>
                        </td>
                        <td class=" align-center">
                            <?php echo $v->ItemType ?>
                        </td>
                        <td class=" align-center">
                            <?php echo $output['map_iPrice_Type'][$v->iPrice_Type] ?>
                        </td>
                        <td class=" align-center">
                            <?php if (empty($v->sPrice_CheckPerson)) { ?>
                                <button type="button"
                                        onclick="showCheckDialog(this, <?php echo htmlspecialchars(json_encode($v)) ?> )">
                                    审核
                                </button>
                                <button type="button"
                                        onclick="showDeleteDialog(this, <?php echo htmlspecialchars(json_encode($v)) ?> )">
                                    删除
                                </button>
                            <?php } else { ?>
                                已审核
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr class="no_data">
                    <td colspan="17"><?php echo $lang['nc_no_record'] ?></td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot class="tfoot">
            <?php if (!empty($output['ret_list']) && is_array($output['ret_list'])) { ?>
                <tr>
                    <td colspan="17">
                        <div class="pagination"> <?php echo $output['page']; ?> </div>
                    </td>
                </tr>
            <?php } ?>
            </tfoot>
        </table>
    </form>
</div>
<div id="checkdialog" title="审核调价">
    <span class="errormsg" style="color:red;width:100%;display:block;text-align: center;"></span>

    <form>
        <input type="hidden" id='iID' name="iID">
        <table class="tb-type1">
            <tr>
                <td>机构：</td>
                <td><input style="color:blue;" id="OrgID" name="OrgID" class="readonly" readonly></td>
            </tr>
            <tr>
                <td>商品编码：</td>
                <td><input style="color:blue;" id="iDrug_ID" name="iDrug_ID" class="readonly" readonly></td>
            </tr>
            <tr>
                <td>商品名称：</td>
                <td><input style="color:blue;" id="ItemName" name="ItemName" class="readonly" readonly></td>
            </tr>
            <tr>
                <td>调前价：</td>
                <td><input style="color:blue;" id="fPrice_Before" name="fPrice_Before" class="readonly" readonly></td>
            </tr>
            <tr>
                <td>调后价：</td>
                <td><input style="color:blue;" id="fPrice_After" name="fPrice_After" class="readonly" readonly></td>
            </tr>

            <tr>
                <td>调价日期：</td>
                <td><input style="color:blue;" id="dPrice_Date" name="dPrice_Date" class="readonly" readonly></td>
            </tr>
            <tr>
                <td>执行开始日期：</td>
                <td><input style="color:blue;" id="dPrice_BeginDate" name="dPrice_BeginDate" class="readonly" readonly>
                </td>
            </tr>
            <tr>
                <td>执行结束日期：</td>
                <td><input style="color:blue;" id="dPrice_EndDate" name="dPrice_EndDate" class="readonly" readonly></td>
            </tr>
            <tr>
                <td>调价人：</td>
                <td><input style="color:blue;" id="sPrice_Person" name="sPrice_Person" class="readonly" readonly></td>
            </tr>
            <tr>
                <td>调价状态：</td>
                <td><input style="color:blue;" id="iPrice_State" name="iPrice_State" class="readonly" readonly></td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr>
                </td>
            </tr>
            <tr>
                <td>审核人：</td>
                <td><input style="color:blue;" id="sPrice_CheckPerson" required name="sPrice_CheckPerson"></td>
            </tr>
            <tr>
                <td>审核日期：</td>
                <td><input style="color:blue;" value="" type="text" class="txt date" required id="dPrice_CheckDate"
                           name="dPrice_CheckDate"></td>
            </tr>

        </table>
    </form>
</div>
<div id="deletedialog" title="删除调价">
    <span class="errormsg" style="color:red;width:100%;display:block;text-align: center;"></span>

    <form>
        <input type="hidden" id='iID' name="iID">
        <table class="tb-type1">
            <tr>
                <td>机构：</td>
                <td><input style="color:blue;" id="OrgID" name="OrgID" class="readonly" readonly></td>
            </tr>
            <tr>
                <td>商品编码：</td>
                <td><input style="color:blue;" id="iDrug_ID" name="iDrug_ID" class="readonly" readonly></td>
            </tr>
            <tr>
                <td>商品名称：</td>
                <td><input style="color:blue;" id="ItemName" name="ItemName" class="readonly" readonly></td>
            </tr>
            <tr>
                <td>调前价：</td>
                <td><input style="color:blue;" id="fPrice_Before" name="fPrice_Before" class="readonly" readonly></td>
            </tr>
            <tr>
                <td>调后价：</td>
                <td><input style="color:blue;" id="fPrice_After" name="fPrice_After" class="readonly" readonly></td>
            </tr>

            <tr>
                <td>调价日期：</td>
                <td><input style="color:blue;" id="dPrice_Date" name="dPrice_Date" class="readonly" readonly></td>
            </tr>
            <tr>
                <td>执行开始日期：</td>
                <td><input style="color:blue;" id="dPrice_BeginDate" name="dPrice_BeginDate" class="readonly" readonly>
                </td>
            </tr>
            <tr>
                <td>执行结束日期：</td>
                <td><input style="color:blue;" id="dPrice_EndDate" name="dPrice_EndDate" class="readonly" readonly></td>
            </tr>
            <tr>
                <td>调价人：</td>
                <td><input style="color:blue;" id="sPrice_Person" name="sPrice_Person" class="readonly" readonly></td>
            </tr>
            <tr>
                <td>调价状态：</td>
                <td><input style="color:blue;" id="iPrice_State" name="iPrice_State" class="readonly" readonly></td>
            </tr>

        </table>
    </form>
</div>

<div id="multicheckdialog" title="批量审核">
    <span class="errormsg" style="color:red;width:100%;display:block;text-align: center;"></span>

    <form>
        <input type="hidden" id='iID' name="iID">
        <table class="tb-type1">
            <tr>
                <td>审核人：</td>
                <td><input style="color:blue;" id="check_sPrice_CheckPerson" required name="check_sPrice_CheckPerson"></td>
            </tr>
            <tr>
                <td>审核日期：</td>
                <td><input style="color:blue;" value="" type="text" class="txt date" required id="check_dPrice_CheckDate"
                           name="check_dPrice_CheckDate"></td>
            </tr>
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
</style>


<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/i18n/zh-CN.js"
        charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.formautofill.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.validation.min.js"></script>

<link rel="stylesheet" type="text/css"
      href="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/themes/smoothness/jquery.ui.css"/>
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
        //日期
        $('input.date').datepicker({dateFormat: 'yy-mm-dd'});
        //初始化showmore
        var showmore = "<?php echo $_GET['showmore'] ?>";
        if (showmore == '1') {
            $(".search_more").addClass("show");
        }
        //查询按钮
        $('#ncsubmit').click(function () {
            $('input[name="op"]').val('changeprice');
            if ($('#formSearch').valid()) {
                $("#export").val('false');
                $('#formSearch').submit();
            }
        });
        //导出
        $('#ncexport').click(function () {
            $('input[name="op"]').val('changeprice');
            if ($('#formSearch').valid()) {
                $("#export").val('true');
                $('#formSearch').submit();
            }
        });
        //弹出窗口
        $("#checkdialog").dialog({
            resizable: false,
            autoOpen: false,
            modal: true,
            close: function () {
                $('input[name="op"]').val('changeprice');
                $("#formSearch").submit();
            },
            buttons: {
                "关闭": function () {

                    $(this).dialog("close");

                },
                "审核": function () {
//                    console.log($("#checkdialog form").valid());
                    if ($("#checkdialog form").valid()) {
                        $.ajax({
                            url: "index.php?act=goods&op=checkChangePrice",
                            data: $("#checkdialog form").serialize(), dataType: 'json', success: function (data) {
                                if (data.success) {
                                    success("#checkdialog", data.msg);
                                } else {
                                    error("#checkdialog", data.msg);
                                }
                            }
                        });
                    }
                }
            }
        });
        //删除
        $("#deletedialog").dialog({
            resizable: false,
            autoOpen: false,
            modal: true,
            close: function () {
                $('input[name="op"]').val('changeprice');
                $("#formSearch").submit();
            },
            buttons: {
                "关闭": function () {
                    $(this).dialog("close");
                },
                "确认删除": function () {
                    $.ajax({
                        url: "index.php?act=goods&op=deleteChangePrice",
                        data: $("#deletedialog form").serialize(), dataType: 'json', success: function (data) {
                            if (data.success) {
                                success("#deletedialog", data.msg);
                            } else {
                                error("#deletedialog", data.msg);
                            }
                        }
                    });
                }
            }
        });
        $("#multicheckdialog").dialog({
            resizable: false,
            autoOpen: false,
            modal: true,
            close: function () {
                $('input[name="op"]').val('changeprice');
                $("#formSearch").submit();
            },
            buttons: {
                "关闭": function () {
                    $(this).dialog("close");
                },
                "批量审核": function () {
                    if ($("#multicheckdialog form").valid()) {
                        $.ajax({
                            url: "index.php?act=goods&op=multicheckChangePrice",
                            data: $("#multicheckdialog").data("data"), dataType: 'json', success: function (data) {
                                if (data.success) {
                                    success("#multicheckdialog", data.msg);
                                } else {
                                    error("#multicheckdialog", data.msg);
                                }
                            }
                        });
                    }
                }
            }
        });
    });
    var map_iPrice_Type = <?php echo json_encode($output['map_iPrice_Type']);?>;
    var org_map = <?php echo json_encode($output['org_map']);?>;
    var map_iPrice_State = <?php echo json_encode($output['map_iPrice_State']);?>;

    function checkSelect() {
        var data = {};
        data.check_dPrice_CheckDate = '<?php echo date('Y-m-d', time()) ?>';
        data.check_sPrice_CheckPerson = '<?php echo $output['adminname'];?>';
        var checkdata = $("#tableform").serializeArray();
        var array = $.map(data, function(value, index) {
            return {"name":index,"value":value};
        });
        checkdata = checkdata.concat(array);
        $("#multicheckdialog").data("data",checkdata);
        $("#multicheckdialog form").autofill(data);
        $("#multicheckdialog").dialog("open");
    }

    function allcheck() {
        $('input[name="op"]').val('multicheckChangePrice');
        var data = {};
        data.check_dPrice_CheckDate = '<?php echo date('Y-m-d', time()) ?>';
        data.check_sPrice_CheckPerson = '<?php echo $output['adminname'];?>';
        var checkdata = $("#formSearch").serializeArray();
        var array = $.map(data, function(value, index) {
            return {"name":index,"value":value};
        });
        checkdata = checkdata.concat(array);
        $("#multicheckdialog").data("data",checkdata);
        $("#multicheckdialog form").autofill(data);
        $("#multicheckdialog").dialog("open");
    }

    function showCheckDialog(elem, data, abc) {
        $("#checkdialog .errormsg").html('');
        //处理data
        data.fPrice_Before = number_format(data.fPrice_Before, 2);
        data.fPrice_After = number_format(data.fPrice_After, 2);
        data.dPrice_Date = data.dPrice_Date.substr(0, 10);
        data.dPrice_BeginDate = data.dPrice_BeginDate.substr(0, 10);
        data.dPrice_EndDate = data.dPrice_EndDate.substr(0, 10);
        data.dPrice_CheckDate = data.dPrice_CheckDate.substr(0, 10);
        if (data.dPrice_CheckDate === '1900-01-01') {
            data.dPrice_CheckDate = null;
        }
        data.iPrice_State = map_iPrice_State[data.iPrice_State];
        data.dPrice_CheckDate = '<?php echo date('Y-m-d', time()) ?>';
        data.sPrice_CheckPerson = '<?php echo $output['adminname'];?>';
        data.OrgID = org_map[data.OrgID];
        $("#checkdialog form").autofill(data);
        $("#checkdialog").dialog("open");
        $('#checkdialog input.date').datepicker({dateFormat: 'yy-mm-dd'});
    }
    function showDeleteDialog(elem, data, abc) {
        $("#deletedialog .errormsg").html('');
        //处理data
        data.fPrice_Before = number_format(data.fPrice_Before, 2);
        data.fPrice_After = number_format(data.fPrice_After, 2);
        data.dPrice_Date = data.dPrice_Date.substr(0, 10);
        data.dPrice_BeginDate = data.dPrice_BeginDate.substr(0, 10);
        data.dPrice_EndDate = data.dPrice_EndDate.substr(0, 10);
        data.dPrice_CheckDate = data.dPrice_CheckDate.substr(0, 10);
        if (data.dPrice_CheckDate === '1900-01-01') {
            data.dPrice_CheckDate = null;
        }
        data.iPrice_State = map_iPrice_State[data.iPrice_State];
        data.dPrice_CheckDate = '<?php echo date('Y-m-d', time()) ?>';
        data.sPrice_CheckPerson = '<?php echo $output['adminname'];?>';
        data.OrgID = org_map[data.OrgID];

        $("#deletedialog form").autofill(data);
        $("#deletedialog").dialog("open");
        $('#deletedialog input.date').datepicker({dateFormat: 'yy-mm-dd'});
    }

    function numtostr(numstr) {
        var num = parseFloat(numstr);
        if (num > 0) {
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
    function search_more() {
        $(".search_more").toggleClass("show");
        var showmore = $("#showmore").val();
        if (showmore == '1') {
            showmore = '0'
        } else {
            showmore = '1';
        }
        $("#showmore").val(showmore);
    }
</script>
<style>
    .thead > th {
        text-align: center;
    }

    table.table > tbody > tr > td {
        white-space: nowrap;
    }

    table.search td table th {
        width: 100px;
        text-align: right;
    }

    table.search td table th label:after {
        content: '：'
    }

    .search_more {
        display: none;
    }

    .search_more.show {
        display: block;
    }

    .ui-dialog input.readonly:read-only {
        background-color: #eee;
        border: none;
        padding: 5px;
    }

    .ui-dialog input[type="text"].date {
        width: 80px;
        /*padding: 0;*/
        margin: 0;
        border-radius: inherit;
    }
    .ui-dialog input[type="text"].date:hover {
        width: 80px;
        /*padding: 0;*/
        margin: 0;
        border-radius: inherit;
    }
    .ui-dialog input{
        padding:2px 4px;
    }

</style>