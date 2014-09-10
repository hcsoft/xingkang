<?php defined('InShopNC') or exit('Access Invalid!'); ?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>统计</h3>
        </div>
    </div>
    <div class="fixed-empty"></div>

    <form method="get" name="formSearch" id="formSearch">
        <input type="hidden" value="healthplatform" name="act">
        <input type="hidden" value="statistical" name="op">
        <table class="tb-type1 noborder search">
            <tbody>
            <tr>
                <th><label>回访类型</label></th>
                <td colspan="1"><select name="search_spottype" class="querySelect">
                        <option value="">全部</option>
                        <option value="13" <?php if ($_GET['search_spottype'] == '13'){ ?>selected<?php } ?>>居民健康档案</option>
                        <option value="14" <?php if ($_GET['search_spottype'] == '14'){ ?>selected<?php } ?>>老年人体检</option>
                        <option value="15" <?php if ($_GET['search_spottype'] == '15'){ ?>selected<?php } ?>>高血压随访</option>
                        <option value="16" <?php if ($_GET['search_spottype'] == '16'){ ?>selected<?php } ?>>糖尿病随访</option>
                    </select></td>
                <th>回访人</th>
                <td><input class="txt-short" type="text" name="buyer_name" value="<?php echo $_GET['buyer_name']; ?>"/>
                </td>
                <th><label for="spot_start_time">回访时间</label></th>
                <td><input class="txt date" type="text" value="<?php echo $_GET['spot_start_time']; ?>"
                           id="spot_start_time" name="spot_start_time">
                    <label for="spot_end_time">~</label>
                    <input class="txt date" type="text" value="<?php echo $_GET['spot_end_time']; ?>" id="spot_end_time"
                           name="spot_end_time"/></td>
                <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search "
                       title="<?php echo $lang['nc_query']; ?>">&nbsp;</a>

                </td>
            </tr>
            </tbody>
        </table>
    </form>
    <table class="table tb-type2" id="prompt">
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
    <form method="post" id="form_member">
        <input type="hidden" name="form_submit" value="ok"/>
        <table class="table tb-type2 nobdb">
            <thead>
            <tr class="thead">
                <th>&nbsp;</th>
                <th class="align-center">机构</th>
                <th class="align-center">时间</th>
                <th class="align-center">总数</th>
                <th class="align-center">居民健康档案</th>
                <th class="align-center">老年人健康体检</th>
                <th class="align-center">高血压随访</th>
                <th class="align-center">糖尿病随访</th>
            </tr>
            <tbody>
            <?php if (!empty($output['data_list']) && is_array($output['data_list'])) { ?>
                <?php foreach ($output['data_list'] as $k => $v) { ?>
                    <tr class="hover member">
                        <td class="w24 align-center"><?php echo $k + 1 ?></td>
                        <td class=" align-center">
                            <?php echo $v->name ?>
                        </td>
                        <td class=" align-center">
                                <?php if (!empty($_REQUEST['spot_start_time'])) { ?>
                            <p>统计开始时间：<span style="color:blue"><?php echo date('Y-m-d', strtotime($_REQUEST['spot_start_time'])) ?></span></p>
                            <?php } if (!empty($_REQUEST['spot_end_time'])) { ?>
                            <p>统计结束时间：<span style="color:blue"><?php echo date('Y-m-d', strtotime($_REQUEST['spot_end_time'])) ?></span></p>
                            <?php }?>
                        </td>
                        <td class="">
                            <p>回访数：<span style="color:blue"><?php echo $v->all_check_count ?></span></p>
                            <p>抽查数：<span style="color:blue"><?php echo $v->all_spot_count ?></span></p>
                            <p>抽查比例：<span style="color:blue"><?php echo $v->all_check_count>0?intval($v->all_spot_count *100/$v->all_check_count):0  ?>%</span></p>
                            <p>抽查通过数：<span style="color:blue"><?php echo $v->all_spot_access_count ?></span></p>
                            <p>抽查通过比例：<span style="color:blue"><?php echo $v->all_spot_count>0?intval($v->all_spot_access_count *100/$v->all_spot_count):0  ?>%</span></p>
                        </td>
                        <td class="">
                            <p>回访数：<span style="color:blue"><?php echo $v->health_check_count ?></span></p>
                            <p>抽查数：<span style="color:blue"><?php echo $v->health_spot_count ?></span></p>
                            <p>抽查比例：<span style="color:blue"><?php echo $v->health_check_count>0?intval($v->health_spot_count *100/$v->health_check_count):0  ?>%</span></p>
                            <p>抽查通过数：<span style="color:blue"><?php echo $v->health_spot_access_count ?></span></p>
                            <p>抽查通过比例：<span style="color:blue"><?php echo $v->health_spot_count>0?intval($v->health_spot_access_count *100/$v->health_spot_count):0  ?>%</span></p>
                        </td>
                        <td class="">
                            <p>回访数：<span style="color:blue"><?php echo $v->old_check_count ?></span></p>
                            <p>抽查数：<span style="color:blue"><?php echo $v->old_spot_count ?></span></p>
                            <p>抽查比例：<span style="color:blue"><?php echo $v->old_check_count>0?intval($v->old_spot_count *100/$v->old_check_count):0  ?>%</span></p>
                            <p>抽查通过数：<span style="color:blue"><?php echo $v->old_spot_access_count ?></span></p>
                            <p>抽查通过比例：<span style="color:blue"><?php echo $v->old_spot_count>0?intval($v->old_spot_access_count *100/$v->old_spot_count):0  ?>%</span></p>
                        </td>
                        <td class="">
                            <p>回访数：<span style="color:blue"><?php echo $v->hyp_check_count ?></span></p>
                            <p>抽查数：<span style="color:blue"><?php echo $v->hyp_spot_count ?></span></p>
                            <p>抽查比例：<span style="color:blue"><?php echo $v->hyp_check_count>0?intval($v->hyp_spot_count *100/$v->hyp_check_count):0  ?>%</span></p>
                            <p>抽查通过数：<span style="color:blue"><?php echo $v->hyp_spot_access_count ?></span></p>
                            <p>抽查通过比例：<span style="color:blue"><?php echo $v->hyp_spot_count>0?intval($v->hyp_spot_access_count *100/$v->hyp_spot_count):0  ?>%</span></p>
                        </td>
                        <td class="">
                            <p>回访数：<span style="color:blue"><?php echo $v->diab_check_count ?></span></p>
                            <p>抽查数：<span style="color:blue"><?php echo $v->diab_spot_count ?></span></p>
                            <p>抽查比例：<span style="color:blue"><?php echo $v->diab_check_count>0?intval($v->diab_spot_count *100/$v->diab_check_count):0  ?>%</span></p>
                            <p>抽查通过数：<span style="color:blue"><?php echo $v->diab_spot_access_count ?></span></p>
                            <p>抽查通过比例：<span style="color:blue"><?php echo $v->diab_spot_count>0?intval($v->diab_spot_access_count *100/$v->diab_spot_count):0  ?>%</span></p>
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
<script type="text/javascript">
    $(function () {
        $('#spot_start_time').datepicker({dateFormat: 'yy-mm-dd'});
        $('#spot_end_time').datepicker({dateFormat: 'yy-mm-dd'});
        $('#input_start_time').datepicker({dateFormat: 'yy-mm-dd'});
        $('#input_end_time').datepicker({dateFormat: 'yy-mm-dd'});
//        $('#spot_start_time , #spot_end_time,#input_start_time,#input_end_time').datepicker({dateFormat: 'yy-mm-dd'});
        $('#ncsubmit').click(function () {
            $('input[name="op"]').val('statistical');
            $('#formSearch').submit();
        });
        $("#spotdialog").dialog({
            resizable: false,
//            width:350,
//            height:250,
//            modal: true,
            autoOpen: false,
            close: function () {
                $("#formSearch").submit();
            },
            buttons: {
                "关闭": function () {
                    $(this).dialog("close");

                },
                "保存": function () {
                    console.log($("#spotdialog form").serialize());
                    $.ajax({ url: "index.php?act=healthplatform&op=ajax",
                        data: $("#spotdialog form").serialize(), dataType: 'json', success: function (data) {
                            if (data.success) {
                                success(data.msg);
                            } else {
                                error(data.msg);
                            }
                        }});
                }
            }
        });
        $("#spotdialog-message").dialog({
            resizable: false,
            autoOpen: false,
            buttons: {
                "关闭": function () {
                    $(this).dialog("close");
                }
            }
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
</style>
<div id="spotdialog" title="抽查">
    <span id="errormsg" style="color:red;width:100%;display:block;text-align: center;"></span>

    <form>
        <input type="hidden" id='spotid' name="spotid">

        <p>抽查时间：<input style="color:blue;" id="spotdate" name="spotdate" readonly
                       value="<?php echo date('Y-m-d', time()) ?>"></p>

        <p>抽查结果：<input id="spotresult_pass" name="spotresult" type="radio" value="通过" checked></input>
            <label for="spotresult_pass" style="cursor:pointer">通过</label>
            <input id="spotresult_false" name="spotresult" type="radio" value="虚报"></input>
            <label for="spotresult_false" style="cursor:pointer">虚报</label>
            <input id="spotresult_unknown" name="spotresult" type="radio" value="未确认"></input>
            <label for="spotresult_unknown" style="cursor:pointer">未确认</label></p>

        <p>原&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;因：<input style="color:blue;" id="reason" name="reason" value=""></p>
    </form>
</div>
