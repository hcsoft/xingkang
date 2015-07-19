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
        bottom: 0;
        border-right: 1px solid #fff;
        padding-top: 7px;
    }
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>就诊情况汇总</h3>
        </div>
    </div>
    <div class="fixed-empty"></div>

    <form method="get" name="formSearch" id="formSearch">
        <input type="hidden" value="community" name="act">
        <input type="hidden" value="prescriptionsum" name="op">
        <input type="hidden" id ='export' name="export" value="false">
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
                <td colspan="1" id="sumtypetr">
                    <input type='checkbox' name='statisticSection'  id='sumtype_statisticSection' <?php if ($_GET['statisticSection']) {?> checked <?php } ?>>
                    <label for='sumtype_statisticSection'>科室</label>
                    <input type='checkbox' name='statisticDoctor'  id='sumtype_statisticDoctor' <?php if ($_GET['statisticDoctor']) {?> checked <?php } ?>>
                    <label for='sumtype_statisticDoctor'>医生</label>
                    <input type='checkbox' name='statisticYear'  id='sumtype_statisticYear' <?php if ($_GET['statisticYear']) {?> checked <?php } ?>>
                    <label for='sumtype_statisticYear'>年</label>
                    <input type='checkbox' name='statisticMonth'  id='sumtype_statisticMonth' <?php if ($_GET['statisticMonth']) {?> checked <?php } ?>>
                    <label for='sumtype_statisticMonth'>月</label>
                    <input type='checkbox' name='statisticDay'  id='sumtype_statisticDay' <?php if ($_GET['statisticDay']) {?> checked <?php } ?>>
                    <label for='sumtype_statisticDay'>日</label>
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
    <form method="post" id="form_member" style='position: relative;'>
        <input type="hidden" name="form_submit" value="ok"/>
        <table class="table tb-type2 nobdb datatable">
            <thead>
            <tr class="thead">
                <th class="align-center">序号</th>
                <th style="min-width:150px;">分支机构</th>
                <?php if (!empty($output['data_list']) && is_array($output['data_list'])) { ?>
            	<?php if (property_exists($output['data_list'][0],sStatSection)){ ?>
            		<th style="min-width:100px;">科室</th>
            	<?php } ?>
            	<?php if (property_exists($output['data_list'][0],sDoctor)){ ?>
            		<th style="min-width:100px;">医生</th>
            	<?php } ?>
            	<?php if (property_exists($output['data_list'][0],sYear)){ ?>
            		<th style="min-width:100px;">年</th>
            	<?php } ?>
            	<?php if (property_exists($output['data_list'][0],sMonth)){ ?>
            		<th style="min-width:100px;">月</th>
            	<?php } ?>
            	<?php if (property_exists($output['data_list'][0],sDate)){ ?>
            		<th style="min-width:100px;">日</th>
            	<?php }} ?>
            	<th rowspan="2" style="min-width:80px;">人次</th>
            </tr>
            <tbody>
            <?php if (!empty($output['data_list']) && is_array($output['data_list'])) { ?>
                <?php foreach ($output['data_list'] as $k => $v) { ?>
                    <tr class="hover member">
                        <td class=" align-center">
                            <?php echo $k+1?>
                        </td>
                        <td><?php echo $v->Name ?></td>
                        <?php if (property_exists($v,sStatSection)){ ?>
	            			<td><?php echo $v->sStatSection ?></td>
	            		<?php } ?>
	            		<?php if (property_exists($v,sDoctor)){ ?>
	            			<td><?php echo $v->sDoctor ?></td>
	            		<?php } ?>
	               		<?php if (property_exists($v,sYear)){ ?>
	            			<td><?php echo $v->sYear ?></td>
	            		<?php } ?>
	            		<?php if (property_exists($v,sMonth)){ ?>
	            			<td><?php echo $v->sMonth ?></td>
	            		<?php } ?>
	            		<?php if (property_exists($v,sDate)){ ?>
	            			<td><?php echo $v->sDate ?></td>
	            		<?php } ?>
                        <td class=" align-right">
                            <?php echo number_format($v->RenCi, 0)?>
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
    var config = <?php echo json_encode($output[config]);?>;

    var checked = getchecked('<?php echo $_GET['checked'];?>');
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
    });
    function makechecked(arr){
        var retarr = [];
        for (var row in checked){
            if(checked[row])
                retarr.push(row+':'+checked[row].join(','));
        }
        return retarr.join(";");
    }
    function getchecked(str){
        var ret = {};
        var data = str.split(";");
        for(var idx in data){
            var strs = data[idx].split(":");
            if(strs.length>1){
                var values = strs[1].split(",");
                ret[strs[0]] = values;
            }
        }
        return ret;
    }

    function sumuncheck(pre,ids){
        if(ids){
            var idarray = ids.split(",");
            for(var i = 0 ;i <idarray.length;i++){
                $("#"+pre+idarray[i]).prop("checked",false);
            }
        }
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

