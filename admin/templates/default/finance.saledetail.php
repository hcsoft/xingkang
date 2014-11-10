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
        <input type="hidden" value="saledetail" name="op">
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
                    <th class="align-center">单据编号</th>
                    <th class="align-center">制单日期</th>
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
                                <?php echo $v->sSale_id ?>
                            </td>
                            <td class=" align-center" nowrap>
                                <?php if ($v->dSale_MakeDate == null) echo ''; else  echo date('Y-m-d', strtotime($v->dSale_MakeDate)); ?>
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
                        <td colspan="16">
                            <div class="pagination"> <?php echo $output['page']; ?> </div>
                        </td>
                    </tr>
                <?php } ?>
                </tfoot>
            </table>
        </form>
    </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/i18n/zh-CN.js"
        charset="utf-8"></script>
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
        $('#ncexport').click(function () {
            $("#export").val('true');
            $('#formSearch').submit();
        });
    });

    function showmsg(msg) {
        $("#spotdialog-message").html(msg);
        $("#spotdialog").dialog("open");
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

