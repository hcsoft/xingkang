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
        bottom: 0;
        border-right: 1px solid #fff;
        padding-top: 7px;
    }
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>仓库单据汇总</h3>
        </div>
    </div>
    <div class="fixed-empty"></div>

    <form method="get" name="formSearch" id="formSearch">
        <input type="hidden" value="storehouse" name="act">
        <input type="hidden" value="sum" name="op">
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
                <th><label for="query_start_time">发生日期</label></th>
                <td><input class="txt date" type="text" value="<?php echo $_GET['query_start_time']; ?>"
                           id="query_start_time" name="query_start_time">
                    <input class="txt date" type="text" value="<?php echo $_GET['query_end_time']; ?>" id="query_end_time"
                           name="query_end_time"/></td>
                <th><label>汇总类型</label></th>
                <td colspan="1">
                    <?php $sumtype = $_GET['sumtype']; if($sumtype==null){$sumtype= Array();} ?>
                    <input type='checkbox' name="sumtype[]" value="org" id="sumtype_org"  <?php if (in_array('org',$sumtype)){ ?>checked<?php } ?>><label for="sumtype_org">领用部门</label>
                    <input type='checkbox' name="sumtype[]" value="store" id="sumtype_store" <?php if (in_array('store',$sumtype)){ ?>checked<?php } ?>><label for="sumtype_store">库房</label>
                    <input type='checkbox' name="sumtype[]" value="goods" id="sumtype_goods" <?php if (in_array('goods',$sumtype)){ ?>checked<?php } ?>><label for="sumtype_goods">商品</label>
                    <input type='checkbox' name="sumtype[]" value="year" id="sumtype_year" <?php if (in_array('year',$sumtype)){ ?>checked<?php } ?>><label for="sumtype_year">年</label>
                    <input type='checkbox' name="sumtype[]" value="month" id="sumtype_month" <?php if (in_array('month',$sumtype)){ ?>checked<?php } ?>><label for="sumtype_month">月</label>
                    <input type='checkbox' name="sumtype[]" value="day" id="sumtype_day" <?php if (in_array('day',$sumtype)){ ?>checked<?php } ?>><label for="sumtype_day">日</label>

                </td>
                </td>
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
    <form method="post" id="form_member" style='position: relative;'>
        <input type="hidden" name="form_submit" value="ok"/>

        <div class="leftdiv">
            <?php
            foreach ($output['types'] as $k => $v) {
                ?>
                <input type="radio" class="typeselect" id="types_<?php echo $k ?>" value="<?php echo $k?>"
                       name="search_type_select" <?php if ($_GET['search_type'] == $k) echo 'checked' ?> >
                <label for="types_<?php echo $k ?>"><?php echo $v ?></label>
            <?php } ?>
        </div>
        <table class="table tb-type2 nobdb datatable">
            <thead>
            <tr class="thead">
                <?php foreach ($output['col'] as $k => $v) {
                    ?>
                    <th class="align-center"><?php echo $v?></th>
                <?php  }?>
                <th class="align-center">进价金额</th>
                <th class="align-center">零价金额</th>
                <th class="align-center">进销差</th>
            </tr>
            <tbody>
            <?php if (!empty($output['data_list']) && is_array($output['data_list'])) { ?>
                <?php foreach ($output['data_list'] as $k => $v) { ?>
                    <tr class="hover member">
                        <?php foreach ($output['col'] as $key => $item) {
                            ?>
                            <th class="align-left"><?php if(substr($key,-5) == 'count')  echo number_format($v->$key,0); else  echo $v->$key;?></th>
                        <?php  }?>
                        <td class=" align-right">
                            <?php echo number_format($v->taxmoney, 3)?>
                        </td>
                        <td class=" align-right">
                            <?php echo number_format($v->retailmoney, 3)?>
                        </td>
                        <td class=" align-right">
                            <?php echo number_format($v->diffmoney, 3)?>
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
            </tfoot>
        </table>
    </form>
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
            if($(":checkbox[name='sumtype[]'][checked]").length<=0){
                $("#sumtype_org").attr("checked", true);
            }
            $('#formSearch').submit();
        });
    });
    function showspot(id, elem) {
        console.log(elem);

        $("#spotid").val(id);
        $("#reason").val();
        $("#errormsg").html("");
        $("#spotdialog").dialog("option", "position", { my: "right top", at: "left bottom", of: $(elem) });
        $("#spotresult_pass").prop("checked", true);

        $("#spotdialog").dialog("open");
    }
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

