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
        <h3>健康档案查询</h3>
    </div>
</div>
<div class="fixed-empty"></div>

<form method="get" name="formSearch" id="formSearch">
    <input type="hidden" value="healthfile" name="act">
    <input type="hidden" value="query" name="op">
    <input type="hidden" name="checkednode" id="checkednode" value=""/>
    <table class="tb-type1 noborder search">
        <tbody>
        <tr>
            <th><label>姓名</label></th>
            <td><input class="txt" type="text" value="<?php echo $_GET['name']; ?>"
                       id="name" name="name"></td>
            <th><label>身份证号</label></th>
            <td><input class="txt" type="text" value="<?php echo $_GET['idnumber']; ?>"
                       id="idnumber" name="idnumber"></td>
            <td>
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
<!--        <tr class="thead">-->
<!--            <th class="align-center" colspan="2">单据编号</th>-->
<!--            <th class="align-center" colspan="10">入库</th>-->
<!--        </tr>-->
        <tr class="thead">
            <th class="align-center">档案编号</th>
            <th class="align-center">姓名</th>
            <th class="align-center">地址</th>
            <th class="align-center">电话</th>
            <th class="align-center">性别</th>
            <th class="align-center">生日</th>
            <th class="align-center">身份证号</th>
            <th class="align-center">录入人员</th>
            <th class="align-center">录入日期</th>
        </tr>
        <tbody>
        <?php if (!empty($output['data_list']) && is_array($output['data_list'])) { ?>
            <?php foreach ($output['data_list'] as $k => $v) { ?>
                <tr class="hover member">
                    <td class=" align-center">
                        <?php echo $v->fileno ?>
                    </td>
                    <td class=" align-center">
                        <?php echo $v->name ?>
                    </td>
                    <td class=" align-center">
                        <?php echo $v->address ?>
                    </td>
                    <td class=" align-center">
                        <?php echo $v->tel ?>
                    </td>
                    <td class=" align-center">
                        <?php echo $v->sex ?>
                    </td>
                    <td class=" align-center">
                        <?php  if($v->birthday == null )echo ''; else  echo date('Y-m-d', strtotime($v->birthday)); ?>
                    </td>
                    <td class=" align-center">
                        <?php echo $v->idnumber ?>
                    </td>
                    <td class=" align-center">
                        <?php echo $v->username ?>
                    </td>
                    <td class=" align-center">
                        <?php  if($v->inputdate == null )echo ''; else  echo date('Y-m-d', strtotime($v->inputdate)); ?>
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
        <?php if (!empty($output['data_list']) && is_array($output['data_list'])) { ?>
            <tr>
                <td colspan="22">
                    <div class="pagination"> <?php echo $output['page']; ?> </div>
                </td>
            </tr>
        <?php } ?>
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

