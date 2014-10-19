<?php defined('InShopNC') or exit('Access Invalid!'); ?>
<style>
    .datatable {
        position: absolute;
        width: 80%;
        right: 0;
    }

    .datatable th, .datatable td {
        border: solid 1px #DEEFFB;
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
        <input type="hidden" value="sum" name="op">
        <input type="hidden" name="checkednode" id="checkednode" value=""/>
        <table class="tb-type1 noborder search">
            <tbody>
            <tr>
                <th><label>单据类型</label></th>
                <td colspan="1"><select name="search_type" class="querySelect">
                        <option value="-1" selected >全部</option>
                        <option value="0" <?php if ($_GET['search_type'] == '0'){ ?>selected<?php } ?>>期初入库</option>
                        <option value="1" <?php if ($_GET['search_type'] == '1'){ ?>selected<?php } ?>>采购入库</option>
                        <option value="2" <?php if ($_GET['search_type'] == '2'){ ?>selected<?php } ?>>购进退回</option>
                        <option value="3" <?php if ($_GET['search_type'] == '3'){ ?>selected<?php } ?>>盘盈</option>
                        <option value="5" <?php if ($_GET['search_type'] == '5'){ ?>selected<?php } ?>>领用</option>
                        <option value="12" <?php if ($_GET['search_type'] == '12'){ ?>selected<?php } ?>>盘亏</option>
                        <option value="14" <?php if ($_GET['search_type'] == '14'){ ?>selected<?php } ?>>领用退回</option>
                        <option value="50" <?php if ($_GET['search_type'] == '50'){ ?>selected<?php } ?>>采购计划</option>
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
                    <input type='checkbox' name="sumtype[]" value="person" id="sumtype_person" <?php if (in_array('person',$sumtype)){ ?>checked<?php } ?>><label for="sumtype_person">经手人</label>
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
        <tr>
            <td>
                <ul>
                    <li><?php echo $lang['member_index_help1']; ?></li>
                    <li><?php echo $lang['member_index_help2']; ?></li>
                </ul>
            </td>
        </tr>
        </tbody>
    </table>
    <form method="post" id="form_member" style='position: relative;'>
        <input type="hidden" name="form_submit" value="ok"/>

        <div style="position: absolute;left:0;width:20%;top:0;bottom:0;border-right:1px solid #fff">
            <ul id="lefttree" class="ztree" style="width:260px; overflow:auto;"></ul>
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
                        <?php foreach ($output['col'] as $key => $item) {     ?>
                                    <th class="align-left"><?php echo $v->$key?></th>
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
<script type="text/javascript">
    $(function () {
        //生成树
        var setting = {
            check: {
                enable: true
            },
            data: {
                simpleData: {
                    enable: true
                }
            },
            callback:{
                onCheck:function(event,treeid,treenode){
                    var nodes = lefttreeObj.getCheckedNodes(true);
                    var checkednode = [];
                    for(var idx in nodes){
                        var node = nodes[idx];
                        console.log(node);
                        if(!node.isParent){
                            checkednode.push (node.id);
                        }
                    }
                    console.log(checkednode);
                    console.log(checkednode.join(","));
                    $("#checkednode").val(checkednode.join(","));
                    $('#ncsubmit').click();

                }
            }
        };
        var zNodes =<?php echo json_encode($output['treedata']); ?>;
        var lefttreeObj = $.fn.zTree.init($("#lefttree"), setting, zNodes);
        //生成日期
        $('#query_start_time , #query_end_time').datepicker({dateFormat: 'yy-mm-dd'});
        $('#ncsubmit').click(function () {
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

