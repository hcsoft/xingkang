<?php defined('InShopNC') or exit('Access Invalid!'); ?>
<style>
    .datatable {
        position: absolute;
        width: 90%;
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
            <h3>仓库单据明细</h3>
        </div>
    </div>
    <div class="fixed-empty"></div>

    <form method="get" name="formSearch" id="formSearch">
        <input type="hidden" value="storehouse" name="act">
        <input type="hidden" value="detail" name="op">
        <input type="hidden" id ='export' name="export" value="false">
        <input type="hidden" name="search_type" id="search_type" value="<?php echo $_GET['search_type']?>"/>
        <table class="tb-type1 noborder search">
            <tbody>
            <tr>
                <th><label>配送机构</label></th>
                <td colspan="1"><select name="sendorgids[]" id="sendorgids" class="orgSelect" multiple>
                        <option value="1">配送中心</option>
                        <?php
                        $sendorgids = $_GET['sendorgids'];
                        if (!isset($sendorgids)) {
                            $sendorgids = array();
                        }
                        foreach ($output['treelist'] as $k => $v) {
                            ?>
                            <option value="<?php echo $v->id; ?>"
                                    <?php if (in_array($v->id, $sendorgids)){ ?>selected<?php } ?>><?php echo $v->name; ?></option>
                        <?php } ?>
                    </select></td>
                </td>
                <th><label>下级机构</label></th>
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

            </tr>
            <tr>
                <th><label for="query_start_time">发生日期</label></th>
                <td><input class="txt date" type="text" value="<?php echo $_GET['query_start_time']; ?>"
                           id="query_start_time" name="query_start_time">
                    <input class="txt date" type="text" value="<?php echo $_GET['query_end_time']; ?>"
                           id="query_end_time"
                           name="query_end_time"/></td>
                <th><label for="search_goods_name"> 商品名称</label></th>
                <td><input type="text" value="<?php echo $_GET['search_goods_name']; ?>"
                           name="search_goods_name" id="search_goods_name" class="txt"></td>
                <th><label for="search_commonid">商品编码</label></th>
                <td><input type="text" value="<?php echo $_GET['search_commonid'] ?>" name="search_commonid"
                           id="search_commonid" class="txt"/></td>
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
    <div  style='position: relative;display: block;'>
    <form method="post" id="form_member">
        <input type="hidden" name="form_submit" value="ok"/>
        <div class="leftdiv">
            <?php
            foreach ($output['types'] as $k => $v) {
                ?>
                <input type="radio" class="typeselect" id="types_<?php echo $v->code ?>" value="<?php echo $v->code?>"
                       name="search_type_select" <?php if ($_GET['search_type'] == $v->code) echo 'checked' ?> >
                <label for="types_<?php echo $v->code ?>"><?php echo $v->name ?></label>
            <?php } ?>
        </div>
        <table class="table tb-type2 nobdb datatable">
            <thead>
            <tr class="thead">
                <th class="align-center" colspan="2">单据编号</th>
                <th class="align-center" colspan="13">信息</th>
            </tr>
            <tr class="thead">
                <th class="align-center">总票据</th>
                <!--            <th class="align-center">单据编号</th>-->
                <th class="align-center">明细号</th>
                <th class="align-center">供应商</th>
                <th class="align-center">发生日期</th>
                <th class="align-center">商品类型</th>
                <th class="align-center">单据类型</th>
                            <th class="align-center">配送机构</th>
                <th class="align-center">下级机构</th>
                <!--            <th class="align-center">供应商</th>-->
                <th class="align-center">商品编码</th>
                <th class="align-center">商品名称</th>
                <th class="align-center">规格</th>
                <th class="align-center">单位</th>

                <th class="align-center">数量</th>
                <th class="align-center">进价金额</th>
                <th class="align-center">零价金额</th>
                <th class="align-center">进销差</th>
            </tr>
            <tbody>
            <?php if (!empty($output['data_list']) && is_array($output['data_list'])) { ?>
                <?php foreach ($output['data_list'] as $k => $v) { ?>
                    <tr class="hover member">
                        <td class=" align-center" nowrap>
                            <?php echo $v->iBuy_TicketID ?>
                        </td>
                        <td class=" align-center" nowrap>
                            <?php echo $v->iBuy_ID ?>
                        </td>
                        <td class=" align-left">
                            <?php echo $v->sCustomer_Name ?>
                        </td>
                        <td class=" align-center" nowrap>
                            <?php if ($v->dBuy_Date == null) echo ''; else  echo date('Y-m-d', strtotime($v->dBuy_Date)); ?>
                        </td>
                        <td class=" align-center" nowrap>
                            <?php echo $output['goodtype'][$v->iDrug_RecType] ?>
                        </td>
                        <td class=" align-center">
                            <?php echo $v->iBuy_Type ?>
                        </td>
                        <td class=" align-center">
                            <?php echo $v->OrgId ?>
                        </td>
                        <td class=" align-center">
                            <?php echo $v->SaleOrgID; ?>
                        </td>
                        <td class=" align-center" nowrap>
                            <?php echo $v->iDrug_ID ?>
                        </td>
                        <td class=" align-left" nowrap>
                            <?php echo $v->sDrug_TradeName ?>
                        </td>
                        <td class=" align-left" nowrap>
                            <?php echo $v->spec_name ?>
                        </td>
                        <td class=" align-left" nowrap>
                            <?php echo $v->sdrug_unit ?>
                        </td>

                        <td class=" align-center" nowrap>
                            <?php if ($v->fBuy_FactNum == null) echo ''; else echo number_format($v->fBuy_FactNum, 0) . $v->sBuy_DrugUnit; ?>
                        </td>
                        <td class=" align-right" nowrap>
                            <?php echo number_format($v->fBuy_TaxMoney, 2) ?>
                        </td>
                        <td class=" align-right" nowrap>
                            <?php echo number_format($v->fBuy_RetailMoney, 2) ?>
                        </td>
                        <td class=" align-right" nowrap>
                            <?php echo number_format($v->diffmoney, 2) ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr class="no_data">
                    <td colspan="14"><?php echo $lang['nc_no_record'] ?></td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot class="tfoot">
            <?php if (!empty($output['data_list']) && is_array($output['data_list'])) { ?>
                <tr>
                    <td colspan="15">
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

        $(".orgSelect").multiselect(
            {
                checkAllText: '选择全部',
                uncheckAllText: '清除选择',
                noneSelectedText: '未选择',
                selectedText: orgtext
            }
        );
        //点击事件
        $(".typeselect").change(function(){
            $("#search_type").val($('input[name="search_type_select"]:checked').val());
            $('#ncsubmit').click();
        });
        //生成日期
        $('input.date').datepicker({dateFormat: 'yy-mm-dd'});
        $('#ncsubmit').click(function () {
            $("#export").val('false');
            $("#search_type").val($('input[name="search_type_select"]:checked').val());
            $('#formSearch').submit();
        });
        $("#formSearch input").keypress(function(event){
            if(event.keyCode==13){
                $('#ncsubmit').click();
            }
        });
        $('#ncexport').click(function () {
            $("#export").val('true');
            $("#search_type").val($('input[name="search_type_select"]:checked').val());
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

