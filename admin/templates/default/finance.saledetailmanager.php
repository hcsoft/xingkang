<?php defined('InShopNC') or exit('Access Invalid!'); ?>
<style>
    .datatable {
        position: absolute;
        width: 100%;
        right: 0;
    }

    .datatable th, .datatable td {
        border: solid 1px #DEEFFB;
    }

    .typeselect {
        display: none;
    }

    .typeselect + label {
        width: 90%;
        height: 30px;
        line-height: 30px;
        margin: 1px auto;
        border: 1px solid #DEEFFB;
        border-radius: 5px;
        display: block;
        text-align: center;
    }

    .typeselect:checked + label {
        background-color: #DEEFFB;
    }

    .leftdiv {
        position: absolute;
        left: 0;
        width: 10%;
        top: 0;
        /*bottom: 0;*/
        border-right: 1px solid #fff;
        padding-top: 7px;
    }

    #editdialog {
        display: none;
    }
</style>
<div class="page">
<div class="fixed-bar">
    <div class="item-title">
        <h3>销售明细查询</h3>
    </div>
</div>
<div class="fixed-empty"></div>

<form method="get" name="formSearch" id="formSearch">
    <input type="hidden" value="finance" name="act">
    <input type="hidden" value="saledetailmanager" name="op">
    <input type="hidden" id='export' name="export" value="false">
    <table class="tb-type1 noborder search">
        <tbody>
        <tr>
            <th><label for="search_goods_name"> 商品名称</label></th>
            <td><input type="text" value="<?php echo $_GET['search_goods_name']; ?>"
                       name="search_goods_name" id="search_goods_name" class="txt"></td>

            <th><label>项目类型</label></th>
            <td colspan="1">
                <select name="itemtype" id='itemtype'>
                    <option value="">全部</option>
                    <?php foreach ($output['goodtype'] as $k => $v) { ?>
                        <option value="<?php echo $v; ?>"
                                <?php if ($v == $_GET['itemtype']){ ?>selected<?php } ?>><?php echo $v; ?></option>
                    <?php } ?>
                </select>
            </td>
            <th><label for="query_start_time">制单日期</label></th>
            <td><input class="txt date" type="text" value="<?php echo $_GET['query_start_time']; ?>"
                       id="query_start_time" name="query_start_time">
                <input class="txt date" type="text" value="<?php echo $_GET['query_end_time']; ?>"
                       id="query_end_time"
                       name="query_end_time"/></td>
            <th colspan="2"><label>财务分类</label></th>
            <td><select name="classtype" id='classtype'>
                    <option value="">全部</option>
                    <option value="null" <?php if ('null' == $_GET['classtype']){ ?>selected<?php } ?>>未分类</option>
                    <?php foreach ($output['classtypes'] as $k => $v) { ?>
                        <option value="<?php echo $v->iClass_ID; ?>"
                                <?php if ($v->iClass_ID == $_GET['classtype']){ ?>selected<?php } ?>><?php echo $v->sClass_ID . $v->sClass_Name; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>

            <th><label for="search_commonid">商品编码</label></th>
            <td><input type="text" value="<?php echo $_GET['search_commonid'] ?>" name="search_commonid"
                       id="search_commonid" class="txt"/></td>
            <th><label>选择机构</label></th>
            <td colspan="3"><select name="orgids[]" id="orgids" class="orgSelect" multiple>
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
            <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search "
                   title="<?php echo $lang['nc_query']; ?>">&nbsp;</a>
            </td>
            <td><a href="javascript:void(0);" id="ncexport" class="btn-export "
                   title="导出"></a>
            </td>
        </tr>
        </tbody>
    </table>
</form>
<table class="table tb-type2 " id="prompt">
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
<div style='position: relative;display: block;'>
    <form method="post" id="form_member">
        <input type="hidden" name="form_submit" value="ok"/>
        <table class="table tb-type2 nobdb datatable">
            <thead>

            <tr class="thead">
                <th class="align-center">序号</th>
                <th class="align-center">操作</th>
                <th class="align-center">单据编号</th>
                <th class="align-center">制单日期</th>
                <th class="align-center">项目编码</th>
                <th class="align-center">项目名称</th>
                <th class="align-center">项目类型</th>
                <th class="align-center">规格</th>
                <th class="align-center">单位</th>
                <th class="align-center">产地/厂商</th>
                <th class="align-center">数量</th>
                <th class="align-center">单价</th>
                <th class="align-center">金额</th>
                <th class="align-center">机构</th>
                <th class="align-center">科室</th>
                <th class="align-center">医生</th>
                <th class="align-center">就诊流水</th>
                <th class="align-center">处方流水</th>
            </tr>
            <tbody>
            <?php if (!empty($output['data_list']) && is_array($output['data_list'])) { ?>
                <?php foreach ($output['data_list'] as $k => $v) { ?>
                    <tr class="hover member">
                        <td class=" align-center">
                            <?php echo $k + 1 ?>
                        </td>
                        <td class=" align-center" nowrap>
                            <button type="button" onclick="edit(<?php echo $v->sSale_id ?>,<?php echo $v->orgid ?>)">
                                修改财务分类
                            </button>
                        </td>
                        <td class=" align-center" nowrap>
                            <?php echo $v->sSale_id ?>
                        </td>
                        <td class=" align-center" nowrap>
                            <?php if ($v->dSale_MakeDate == null) echo ''; else  echo date('Y-m-d', strtotime($v->dSale_MakeDate)); ?>
                        </td>
                        <td class=" align-left">
                            <?php echo $v->iDrug_ID ?>
                        </td>
                        <td class=" align-left">
                            <?php echo $v->sDrug_TradeName ?>
                        </td>
                        <td class=" align-left" nowrap>
                            <?php echo $v->ItemType ?>
                        </td>
                        <td class=" align-left" nowrap>
                            <?php echo $v->sDrug_Spec ?>
                        </td>
                        <td class=" align-left" nowrap>
                            <?php echo $v->sDrug_Unit ?>
                        </td>
                        <td class=" align-left">
                            <?php echo $v->sDrug_Brand ?>
                        </td>
                        <td class=" align-right" nowrap>
                            <?php echo number_format($v->fSale_Num, 0) ?>
                        </td>
                        <td class=" align-right" nowrap>
                            <?php echo number_format($v->fSale_TaxPrice, 3) ?>
                        </td>
                        <td class=" align-right" nowrap>
                            <?php echo number_format($v->fSale_TaxFactMoney, 3) ?>
                        </td>
                        <td class=" align-center" nowrap>
                            <?php echo $v->name ?>
                        </td>
                        <td class=" align-center" nowrap>
                            <?php echo $v->StatSection ?>
                        </td>
                        <td class=" align-center" nowrap>
                            <?php echo $v->DoctorName ?>
                        </td>
                        <td class=" align-center" nowrap>
                            <?php echo $v->sClinicKey ?>
                        </td>
                        <td class=" align-center" nowrap>
                            <?php echo $v->ida_id ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr class="no_data">
                    <td colspan="16"><?php echo $lang['nc_no_record'] ?></td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot class="tfoot">
            <?php if (!empty($output['data_list']) && is_array($output['data_list'])) { ?>
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
<div id="editdialog" title="修改财务分类">
    <span id="errormsg" style="color:red;width:100%;display:block;text-align: center;"></span>

    <form>
        <input type="hidden" id='spotid' name="spotid">
        <input type="hidden" id='spotid' name="spotid">
        <table>
            <tr>
                <td>单据编号：</td>
                <td><input style="color:blue;" id="sSale_id" name="sSale_id" readonly></td>
            </tr>
            <tr>
                <td>制单日期：</td>
                <td><input style="color:blue;" id="dSale_MakeDate" name="dSale_MakeDate" readonly></td>
            </tr>
            <tr>
                <td>项目编码：</td>
                <td><input style="color:blue;" id="iDrug_ID" name="iDrug_ID" readonly></td>
            </tr>
            <tr>
                <td>项目名称：</td>
                <td><input style="color:blue;" id="itemname" name="itemname" readonly></td>
            </tr>

            <tr>
                <td>财务分类：</td>
                <td><select name="classtype" id="classtype">
                        <option value="">未分类</option>
                        <option value="11">011中成药</option>
                        <option value="12">012保健食品</option>
                        <option value="13">013化妆品</option>
                        <option value="14">014食品</option>
                        <option value="15">015外用</option>
                        <option value="16">016卫生用品</option>
                        <option value="17">017西药</option>
                        <option value="18">018医疗器械</option>
                        <option value="19">019疫苗</option>
                        <option value="21">021中药饮片</option>
                        <option value="22">022注射剂</option>
                        <option value="23">000其他</option>
                        <option value="24">024按摩</option>
                        <option value="25">025针灸</option>
                        <option value="26">026理疗</option>
                        <option value="27">027西医诊费</option>
                        <option value="28">028中医诊费</option>
                        <option value="29">029体检</option>
                        <option value="30">030化验</option>
                        <option value="31">031检查</option>
                    </select></td>
            </tr>
            <tr>
                <td>药品编码：</td>
                <td><input style="color:blue;" id="goodid" name="goodid"></td>
            </tr>
            <tr>
                <td>药品名称：</td>
                <td><input style="color:blue;" id="goodname" name="goodname"></td>
            </tr>
            <tr>
                <td>规格：</td>
                <td><input style="color:blue;" id="sDrug_Spec" name="sDrug_Spec"></td>
            </tr>
            <tr>
                <td>单位：</td>
                <td><input style="color:blue;" id="sDrug_Unit" name="sDrug_Unit"></td>
            </tr>
            <tr>
                <td>产地/厂商：</td>
                <td><input style="color:blue;" id="sDrug_Brand" name="sDrug_Brand"></td>
            </tr>

        </table>
    </form>
</div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/i18n/zh-CN.js"
        charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.formautofill.js"></script>

<link rel="stylesheet" type="text/css"
      href="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/themes/smoothness/jquery.ui.css"/>
<link href="<?php echo RESOURCE_SITE_URL; ?>/js/ztree/css/zTreeStyle/zTreeStyle.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo RESOURCE_SITE_URL; ?>/js/multiselect/jquery.multiselect.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/ztree/js/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/multiselect/jquery.multiselect.min.js"></script>
<script type="text/javascript">
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
        //生成日期
        $('input.date').datepicker({dateFormat: 'yy-mm-dd'});
        $('#ncsubmit').click(function () {
            $("#export").val('false');
            $('#formSearch').submit();
        });
        $("#formSearch input").keypress(function(event){
            if(event.keyCode==13){
                $('#ncsubmit').click();
            }
        });
        $('#ncexport').click(function () {
            $("#export").val('true');
            $('#formSearch').submit();
        });
        $("#editdialog").dialog({
            resizable: false,
            autoOpen: false,
            close: function () {
                $("#formSearch").submit();
            },
            buttons: {
                "关闭": function () {
                    $(this).dialog("close");

                },
                "保存": function () {
                    console.log($("#editdialog form").serialize());
                    $.ajax({
                        url: "index.php?act=finance&op=ajaxSaveStateClass",
                        data: $("#editdialog form").serialize(), dataType: 'json', success: function (data) {
                            if (data.success) {
                                success(data.msg);
                            } else {
                                error(data.msg);
                            }
                        }
                    });
                }
            }
        });
    });
    function edit(saleid, orgid) {
        $.getJSON("index.php?act=finance&op=ajaxGetDetail", {'saleid': saleid, 'orgid': orgid}, function (data) {

            var formdata = {
                sSale_id: data.sale.sSale_ID,
                dSale_MakeDate: data.sale.dSale_MakeDate,
                iDrug_ID: data.sale.iDrug_ID,
                itemname: data.sale.ItemName

            };
            if (data.good) {
                formdata.classtype = data.good.iDrug_StatClass;
                formdata.goodid = data.good.goods_commonid;
                formdata.goodname = data.good.goods_name;
                formdata.sDrug_Spec = data.good.sDrug_Spec;
                formdata.sDrug_Unit = data.good.sDrug_Unit;
                formdata.sDrug_Brand = data.good.sDrug_Brand;
            }
            $("#editdialog form").autofill(formdata);
            $("#editdialog").dialog("open");
        });
    }

    function showmsg(msg) {
        $("#editdialog-message").html(msg);
        $("#editdialog").dialog("open");
    }
    function error(msg) {
        $("#errormsg").css("color", "red");
        $("#errormsg").html(msg);
    }
    function success(msg) {
        $("#errormsg").css("color", "green");
        $("#errormsg").html(msg);
    }
</script>
<style>
    #spotresult_pass:checked + label {
        color: #008000;
    }

    #spotresult_false:checked + label {
        color: red;
    }

    #spotresult_unknown:checked + label {
        color: sienna;
    }

    #ui-datepicker-div {
        display: none;
    }
</style>

