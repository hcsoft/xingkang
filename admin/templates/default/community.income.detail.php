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
            <h3>收入明细查询</h3>
        </div>
    </div>
    <div class="fixed-empty"></div>

    <form method="get" name="formSearch" id="formSearch">
        <input type="hidden" value="community" name="act">
        <input type="hidden" value="incomedetail" name="op">
        <input type="hidden" name="search_type" id="search_type" value="<?php echo $_GET['search_type']?>"/>
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
                <th><label>类型</label></th>
                <td colspan="1">
                    <select name="types" id ='types' >
                        <option value="">全部</option>
                        <?php  foreach ($output['types'] as $k => $v) {                      ?>
                            <option value="<?php echo $v->code; ?>" <?php if ($v->code== $_GET['types']){ ?>selected<?php } ?>><?php echo $v->name; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <th><label for="query_start_time">结算日期</label></th>
                <td><input class="txt date" type="text" value="<?php echo $_GET['query_start_time']; ?>"
                           id="query_start_time" name="query_start_time">
                    <input class="txt date" type="text" value="<?php echo $_GET['query_end_time']; ?>"
                           id="query_end_time"
                           name="query_end_time"/></td>
                <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search "
                       title="<?php echo $lang['nc_query']; ?>">&nbsp;</a>
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
        <table class="table tb-type2 nobdb datatable">
            <thead>

            <tr class="thead">
                <th class="align-center">序号</th>
                <th class="align-center">类型</th>
                <th class="align-center">结算日期</th>
                <th class="align-center">制单日期</th>
                <th class="align-center">收费员</th>
                <th class="align-center">押金</th>
                <th class="align-center">结算余额</th>
                <th class="align-center">实际金额</th>
                <th class="align-center">结算金额</th>
                <th class="align-center">收取金额</th>
                <th class="align-center">支付金额</th>
                <th class="align-center">金额大写</th>
            </tr>
            <tbody>
            <?php if (!empty($output['data_list']) && is_array($output['data_list'])) { ?>
                <?php foreach ($output['data_list'] as $k => $v) { ?>
                    <tr class="hover member">
                        <td class=" align-center">
                            <?php echo $k+1 ?>
                        </td>
                        <td class=" align-center">
                            <?php echo $v->iCO_Type ?>
                        </td>
                        <td class=" align-center">
                            <?php if ($v->dCO_Date == null) echo ''; else  echo date('Y-m-d', strtotime($v->dCO_Date)); ?>
                        </td>
                        <td class=" align-center">
                            <?php if ($v->dCO_MakeDate == null) echo ''; else  echo date('Y-m-d', strtotime($v->dCO_MakeDate)); ?>
                        </td>
                        <td class=" align-center">
                            <?php echo $v->iCO_MakePerson ?>
                        </td>
                        <td class=" align-center">
                            <?php echo number_format($v->fCO_Foregift,2); ?>
                        </td>
                        <td class=" align-center">
                            <?php echo number_format($v->fCO_Balance,2);?>
                        </td>
                        <td class=" align-center">
                            <?php  echo number_format($v->fCO_FactMoney,2);?>
                        </td>
                        <td class=" align-left">
                            <?php  echo number_format($v->fCO_IncomeMoney,2);?>
                        </td>
                        <td class=" align-left">
                            <?php  echo number_format($v->fCO_GetMoney,2);?>
                        </td>
                        <td class=" align-left">
                            <?php  echo number_format($v->fCO_PayMoney,2);?>
                        </td>
                        <td class=" align-left">
                            <?php echo $v->sCO_CapitalMoney ?>
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
        //点击事件
        $(".typeselect").change(function(){
            $("#search_type").val($('input[name="search_type_select"]:checked').val());
            $('#ncsubmit').click();
        });
        //生成日期
        $('input.date').datepicker({dateFormat: 'yy-mm-dd'});
        $('#ncsubmit').click(function () {
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

