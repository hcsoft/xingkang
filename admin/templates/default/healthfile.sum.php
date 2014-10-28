<?php defined('InShopNC') or exit('Access Invalid!'); ?>
<style>
    .datatable {

    }

    .datatable th, .datatable td {
        border: solid 1px #DEEFFB;
    }
    #lefttree{
        height:100%;
        width:90% !important;
    }
</style>
<div class="page">
<div class="fixed-bar">
    <div class="item-title">
        <h3>健康档案汇总</h3>
    </div>
</div>
<div class="fixed-empty"></div>

<form method="get" name="formSearch" id="formSearch">
    <input type="hidden" value="healthfile" name="act">
    <input type="hidden" value="sum" name="op">
    <input type="hidden" id ='export' name="export" value="false">
    <input type="hidden" name="checkednode" id="checkednode" value=""/>
    <table class="tb-type1 noborder search">
        <tbody>
        <tr>
            <th><label for="query_start_time">录入日期</label></th>
            <td><input class="txt date" type="text" value="<?php echo $_GET['query_start_time']; ?>"
                       id="query_start_time" name="query_start_time">
                <input class="txt date" type="text" value="<?php echo $_GET['query_end_time']; ?>" id="query_end_time"
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
    <div style="width:100%;height:510px;position:  relative;">
<form method="post" id="form_member" style='position: relative;height:100%;'>

    <input type="hidden" name="form_submit" value="ok"/>

    <div style="position: absolute;left:0;width:20%;top:0px;bottom:0;border-right:1px solid #fff;display: inline-block;z-index: 0;clear: both;">
        <ul id="lefttree" class="ztree" style="width:260px; overflow:auto;clear: both;"></ul>
    </div>
    <div style = 'position: absolute;    width: 80%;    right: 0;    display: inline-block;    z-index: 1000   ;clear: both;'>
    <table class="table tb-type2 nobdb datatable">
        <thead>
<!--        <tr class="thead">-->
<!--            <th class="align-center" colspan="2">单据编号</th>-->
<!--            <th class="align-center" colspan="10">入库</th>-->
<!--        </tr>-->
        <tr class="thead">
            <th class="align-center">行号</th>
            <th class="align-center">机构名称</th>
            <th class="align-center">档案数</th>
            <th class="align-center">农业户口档案数</th>
            <th class="align-center">城镇户口档案数</th>
            <th class="align-center">儿童档案数</th>
            <th class="align-center">孕产妇档案数</th>
            <th class="align-center">高血压档案数</th>
            <th class="align-center">糖尿病档案数</th>
            <th class="align-center">重性精神病档案数</th>
        </tr>
        <tbody>
        <?php if (!empty($output['data_list']) && is_array($output['data_list'])) { ?>
            <?php foreach ($output['data_list'] as $k => $v) { ?>
                <tr class="hover member">
                    <td class=" align-center">
                        <?php echo $v->RowID ?>
                    </td>
                    <td class=" align-center">
                        <?php echo $v->OrgName ?>
                    </td>
                    <td class=" align-center">
                        <?php echo $v->VHealthCount + $v->CHealthCount ?>
                    </td>
                    <td class=" align-center">
                        <?php echo $v->VHealthCount ?>
                    </td>
                    <td class=" align-center">
                        <?php echo $v->CHealthCount ?>
                    </td>
                    <td class=" align-center">
                        <?php echo $v->BabyHealthCount ?>
                    </td>
                    <td class=" align-center">
                        <?php echo $v->MaternalCount ?>
                    </td>
                    <td class=" align-center">
                        <?php echo $v->HypertensionHealthCount ?>
                    </td>
                    <td class=" align-center">
                        <?php echo $v->DiabetesHealthCount ?>
                    </td>
                    <td class=" align-center">
                        <?php echo $v->FuriousHealthCount ?>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr class="no_data">
                <td colspan="11"><?php echo $lang['nc_no_record'] ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    </div>

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
        var isclicking = false;
        $('#ncsubmit').click(function () {

            var nodes = lefttreeObj.getCheckedNodes(true);
            var checkednode = [];
            for(var idx in nodes){
                var node = nodes[idx];
                console.log(node);
                if(!node.isParent){
                    checkednode.push (node.id);
                }
            }
            $("#checkednode").val(checkednode.join(","));
            if(!isclicking){
                isclicking = true;
                $('#formSearch').submit();
            }

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

