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
	.datatable thead th{
       	text-align:center;
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
            <h3>门诊收入分析</h3>
        </div>
    </div>
    <div class="fixed-empty"></div>

    <form method="get" name="formSearch" id="formSearch">
        <input type="hidden" value="community" name="act">
        <input type="hidden" value="clinicstatistic" name="op">
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
                <th><label for="query_start_time">结算日期</label></th>
                <td><input class="txt date" type="text" value="<?php echo $_GET['query_start_time']; ?>"
                           id="query_start_time" name="query_start_time">
                    <input class="txt date" type="text" value="<?php echo $_GET['query_end_time']; ?>" id="query_end_time"
                           name="query_end_time"/></td>
                <th><label>汇总类型</label></th>
                <td colspan="1" id="sumtypetr">
                	<input type='checkbox' name='statisticOrgID'  id='sumtype_statisticOrgID' <?php if ($_GET['statisticOrgID']) {?> checked <?php } ?>>
                    <label for='sumtype_statisticOrgID'>机构</label>
                    <input type='checkbox' name='statisticSection'  id='sumtype_statisticSection' <?php if ($_GET['statisticSection']) {?> checked <?php } ?>>
                    <label for='sumtype_statisticSection'>科室</label>
                    <input type='checkbox' name='statisticDoctor'  id='sumtype_statisticDoctor' <?php if ($_GET['statisticDoctor']) {?> checked <?php } ?>>
                    <label for='sumtype_statisticDoctor'>医生</label>
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
        <table class="table tb-type2 nobdb datatable">
            <thead>
            	<tr>
            		<th rowspan="2">分机机构</th>
            		<th rowspan="2">科室</th>
            		<th rowspan="2">医生</th>
            		<th colspan="3">中医</th>
            		<th colspan="3">西医</th>
            		<th colspan="3">药房</th>
            		<th colspan="3">推拿</th>
            		<th colspan="3">计免</th>
            		<th colspan="3">检验</th>
            		<th colspan="3">成人体检</th>
            		<th colspan="3">儿童体检</th>
            		<th colspan="3">妇科</th>
            		<th colspan="3">口腔</th>
            		<th colspan="3">慢病处方</th>
            		<th colspan="3">验光配镜</th>
            	</tr>
            	<tr>
            		<th>收入</th>
            		<th>人次</th>
            		<th>客单价</th>
            		<th>收入</th>
            		<th>人次</th>
            		<th>客单价</th>
            		<th>收入</th>
            		<th>人次</th>
            		<th>客单价</th>
            		<th>收入</th>
            		<th>人次</th>
            		<th>客单价</th>
            		<th>收入</th>
            		<th>人次</th>
            		<th>客单价</th>
            		<th>收入</th>
            		<th>人次</th>
            		<th>客单价</th>
            		<th>收入</th>
            		<th>人次</th>
            		<th>客单价</th>
            		<th>收入</th>
            		<th>人次</th>
            		<th>客单价</th>
            		<th>收入</th>
            		<th>人次</th>
            		<th>客单价</th>
            		<th>收入</th>
            		<th>人次</th>
            		<th>客单价</th>
            		<th>收入</th>
            		<th>人次</th>
            		<th>客单价</th>
            		<th>收入</th>
            		<th>人次</th>
            		<th>客单价</th>
            	</tr>
            </thead>
            <tbody>
            	<?php if (!empty($output['data_list']) && is_array($output['data_list'])) { ?>
                <?php foreach ($output['data_list'] as $k => $v) { ?>
                	<tr class="hover member">
	               		<td><?php echo $v->Name ?></td>
	               		<td><?php echo $v->sStatSection ?></td>
	               		<td><?php echo $v->sDoctor ?></td>
	                    <td><?php echo number_format($v->ZY_fCO_IncomeMoney,2) ?></td>
	                    <td><?php echo number_format($v->ZYRenCi,0) ?></td>
	                    <td><?php 
	                    	if ($v->ZYRenCi != null and number_format($v->ZYRenCi,0) != 0) 
	                    		echo number_format($v->ZY_fCO_IncomeMoney,2)/number_format($v->ZYRenCi,0);
	                    	else
	                    		echo number_format(0,2);
	                    ?></td>
	                    <td><?php echo number_format($v->XY_fCO_IncomeMoney,2) ?></td>
	                    <td><?php echo number_format($v->XYRenCi,0) ?></td>
	                    <td><?php if ($v->XYRenCi != null and number_format($v->XYRenCi,0) != 0) 
	                    		echo number_format($v->XY_fCO_IncomeMoney/$v->XYRenCi,2);
	                    	else
	                    		echo number_format(0,2);
	                    ?></td>
	                    <td><?php echo number_format($v->YF_fCO_IncomeMoney,2) ?></td>
	                    <td><?php echo number_format($v->YFRenCi,0) ?></td>
	                    <td><?php 
	                    	if ($v->YFRenCi != null and number_format($v->YFRenCi,0) != 0) 
	                    		echo number_format($v->YF_fCO_IncomeMoney/$v->YFRenCi,2);
	                    	else
	                    		echo number_format(0,2);
	                    ?></td>
	                    <td><?php echo number_format($v->TN_fCO_IncomeMoney,2) ?></td>
	                    <td><?php echo number_format($v->TNRenCi,0) ?></td>
	                    <td><?php 
	                    	if ($v->TNRenCi != null and number_format($v->TNRenCi,0) != 0)
	                    		echo number_format($v->TN_fCO_IncomeMoney/$v->TNRenCi,2);
	                    	else
	                    		echo number_format(0,2);
	                    ?></td>
	                    <td><?php echo number_format($v->JM_fCO_IncomeMoney,2) ?></td>
	                    <td><?php echo number_format($v->JMRenCi,0) ?></td>
	                    <td><?php 
	                    	if ($v->JMRenCi != null and number_format($v->JMRenCi,0) != 0)
	                    		echo number_format($v->JM_fCO_IncomeMoney/$v->JMRenCi,2);
	                    	else
	                    		echo number_format(0,2);
	                    ?></td>
	                    <td><?php echo number_format($v->JY_fCO_IncomeMoney,2) ?></td>
	                    <td><?php echo number_format($v->JYRenCi,0) ?></td>
	                    <td><?php 
	                    	if ($v->JYRenCi != null and number_format($v->JYRenCi,0) != 0)
	                    		echo number_format($v->JY_fCO_IncomeMoney/$v->JYRenCi,2);
	                    	else
	                    		echo number_format(0,2);
	                    ?></td>
	                    <td><?php echo number_format($v->CRTJ_fCO_IncomeMoney,2) ?></td>
	                    <td><?php echo number_format($v->CRTJRenCi,0) ?></td>
	                    <td><?php 
	                    	if ($v->CRTJRenCi != null and number_format($v->CRTJRenCi,0) != 0)
	                    		echo number_format($v->CRTJ_fCO_IncomeMoney/$v->CRTJRenCi,2);
	                    	else
	                    		echo number_format(0,2);
	                    ?></td>
	                    <td><?php echo number_format($v->ETTJ_fCO_IncomeMoney,2) ?></td>
	                    <td><?php echo number_format($v->ETTJRenCi,0) ?></td>
	                    <td><?php 
	                    	if ($v->ETTJRenCi != null and number_format($v->ETTJRenCi,0) != 0)
	                    		echo number_format($v->ETTJ_fCO_IncomeMoney/$v->ETTJRenCi,2);
	                    	else
	                    		echo number_format(0,2);
	                    ?></td>
	                    <td><?php echo number_format($v->FK_fCO_IncomeMoney,2) ?></td>
	                    <td><?php echo number_format($v->FKRenCi,0) ?></td>
	                    <td><?php 
	                    	if ($v->FKRenCi != null and number_format($v->FKRenCi,0) != 0)
	                    		echo number_format($v->FK_fCO_IncomeMoney/$v->FKRenCi,2);
	                    	else
	                    		echo number_format(0,2);
	                    ?></td>
	                    <td><?php echo number_format($v->KQ_fCO_IncomeMoney,2) ?></td>
	                    <td><?php echo number_format($v->KQRenCi,0) ?></td>
	                    <td><?php 
	                    	if ($v->KQRenCi != null and number_format($v->KQRenCi,0) != 0)
	                    		echo number_format($v->KQ_fCO_IncomeMoney/$v->KQRenCi,2);
	                    	else
	                    		echo number_format(0,2);
	                    ?></td>
	                    <td><?php echo number_format($v->MBCF_fCO_IncomeMoney,2) ?></td>
	                    <td><?php echo number_format($v->MBCFRenCi,0) ?></td>
	                    <td><?php 
	                    	if ($v->MBCFRenCi != null and number_format($v->MBCFRenCi,0) != 0)
	                    		echo number_format($v->MBCF_fCO_IncomeMoney/$v->MBCFRenCi,2);
	                    	else
	                    		echo number_format(0,2);
	                    ?></td>
	                    <td><?php echo number_format($v->YGPJ_fCO_IncomeMoney,2) ?></td>
	                    <td><?php echo number_format($v->YGPJRenCi,0) ?></td>
	                    <td><?php 
	                    	if ($v->YGPJRenCi != null and number_format($v->YGPJRenCi,0) != 0)
	                    		echo number_format($v->YGPJ_fCO_IncomeMoney/$v->YGPJRenCi,2);
	                    	else
	                    		echo number_format(0,2);
	                    ?></td>
                    </tr>
                <?php } ?>
                <?php } else { ?>
	                <tr class="no_data">
	                    
	                </tr>
	            <?php } ?>
        	</tbody>
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

        //结算日期
        $('input.date').datepicker({dateFormat: 'yy-mm-dd'});
        $('#ncsubmit').click(function () {
            var sumtypes =$(":checkbox[name='sumtype[]'][checked]");
            console.log(sumtypes);
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

