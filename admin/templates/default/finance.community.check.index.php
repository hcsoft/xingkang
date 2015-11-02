<?php defined('InShopNC') or exit('Access Invalid!'); ?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>社会考核</h3>
            <ul class="tab-base">
                <li><a href="index.php?act=finance&op=communitycheck"><span>考核指标设置</span></a></li>
                <li><a href="index.php?act=finance&op=communitycheckSummary"><span>考核结果汇总</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/jquery.ui.js"></script>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/i18n/zh-CN.js"
            charset="utf-8"></script>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.validation.min.js"></script>
    <link rel="stylesheet" type="text/css"
          href="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/themes/smoothness/jquery.ui.css"/>
    <link href="<?php echo RESOURCE_SITE_URL; ?>/js/ztree/css/zTreeStyle/zTreeStyle.css" rel="stylesheet"
          type="text/css"/>
    <link href="<?php echo RESOURCE_SITE_URL; ?>/js/multiselect/jquery.multiselect.css" rel="stylesheet"
          type="text/css"/>
    <script type="text/javascript"
            src="<?php echo RESOURCE_SITE_URL; ?>/js/ztree/js/jquery.ztree.all-3.5.min.js"></script>
    <script type="text/javascript"
            src="<?php echo RESOURCE_SITE_URL; ?>/js/multiselect/jquery.multiselect.min.js"></script>
    <div style="margin-top:5px;">
    	计划起日期：<input name="dPlanBegin" type="text" class="txt dPlanBegin" value="<?php echo date('Y-m-01', strtotime(date("Y-m-d")));?>"/>
                    计划止日期：<input name="dPlanEnd" type="text" class="txt dPlanEnd" value="<?php echo date('Y-m-d', strtotime(date('Y-m-01', strtotime(date("Y-m-d"))) . ' +1 month -1 day'));?>"/>
        <?php
		if($output['total'][0] == 0){
		?>
		<a href="JavaScript:void(0);" class="btn" onclick="centerCheckPlainSubmit()"><span><?php echo $lang['nc_submit'];?></span></a>
		<?php
		}else{
		?>
		<?php
		}
		?>
    </div>
    
    <table class="table tb-type2" id="prompt">
        <tbody>
        <tr class="space odd">
            <th colspan="12">
                <div class="title">
                    <h5><?php echo $lang['nc_prompts']; ?></h5>
                    <span class="arrow">
                    </span></div>
            </th>
        </tr>
        <tr>
        	<td><span style="color:red;">填写完成各医疗机构的门诊人次任务指标和门诊收入任务指标时提交保存数据，每个月限制填写一次。</span></td>
        </tr>
        </tbody>
    </table>
    <form method="post" id="form_centercheckplain" name="form_centercheckplain">
        <table class="table tb-type2 nobdb">
            <thead>
            <tr class="thead">
                <th>&nbsp;</th>
                <th class="align-center">机构名称</th>
                <th class="align-center">门诊人次任务指标</th>
                <th class="align-center">门诊收入任务指标</th>
                <th class="align-center">备注</th>
            </tr>
            <tbody>
            <?php if (!empty($output['centerCheckPlaindata']) && is_array($output['centerCheckPlaindata'])) { ?>
                <?php foreach ($output['centerCheckPlaindata'] as $k => $v) { ?>
                    <tr class="hover member">
                        <td class="w24"><input value="<?php echo $v->id; ?>" name="orgId" style="display:none;"/></td>
                        <td><?php echo $v->name; ?></td>
                        <td class="align-center"><input value="<?php echo number_format($v->fObject1,0); ?>" name="fObject1" type="text" class="txt" <?php if($output['total'][0] > 0) echo 'readonly'; else '';?>/></td>
                        <td class="align-center"><input value="<?php echo number_format($v->fObject2,2); ?>" name="fObject2" type="text" class="txt" <?php if($output['total'][0] > 0) echo 'readonly'; else '';?>/></td>
                        <td class="align-center"><input style="width:400px;" value="<?php echo $v->sMemo; ?>" name="sMemo" type="text" class="txt"/></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr class="no_data">
                    <td colspan="11"><?php echo $lang['nc_no_record'] ?></td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot class="tfoot">
            <?php if (!empty($output['centerCheckPlaindata']) && is_array($output['centerCheckPlaindata'])) { ?>
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

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/i18n/zh-CN.js"
        charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.formautofill.js"></script>

<link rel="stylesheet" type="text/css"
      href="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/themes/smoothness/jquery.ui.css"/>
<div id="AlertMessage" title="信息提示">
	<p id="AlertMessageBody"  class="msgbody">保存成功</p>
</div>
<div id="AlertMessageError" title="信息提示">
	<p id="AlertMessageBodyError"  class="msgbody">保存失败</p>
</div>
<script type="text/javascript">
	function centerCheckPlainSubmit(){
		var datas = [];
		$.each($('#form_centercheckplain table tbody tr'),function(){
			var json = {
				orgId : $(this).find('input[name=orgId]').val(),
				fObject1 : $(this).find('input[name=fObject1]').val(),
				fObject2 : $(this).find('input[name=fObject2]').val(),
				sMemo : $(this).find('input[name=sMemo]').val(),
			};
			datas.push(json);
		});
		console.log(datas);
		var dPlanBegin = $('.dPlanBegin').val();
		var dPlanEnd = $('.dPlanEnd').val();
		$.ajax({
			url: "index.php?act=finance&op=saveCenterCheckPlain",
            data: {"data":datas,"dPlanBegin":dPlanBegin,"dPlanEnd":dPlanEnd}, 
            dataType: 'json', 
            success: function (data) {
            	$('#AlertMessage').dialog('open');
            },
            error:function(data){
            	console.log(data);
            	$('#AlertMessageError').dialog('open');
            }
		});
	}
	$(function() {
	    $( ".dPlanBegin" ).datepicker("disable").attr("readonly","readonly");
	    $( ".dPlanEnd" ).datepicker("disable").attr("readonly","readonly");
	    $('#AlertMessage').dialog({
			autoOpen: false,
			width: 300,
			modal: true,
			buttons: {
				"关闭": function() {
					location.reload();
					$(this).dialog("close");
				}
			}
		});
		$('#AlertMessageError').dialog({
			autoOpen: false,
			width: 300,
			modal: true,
			buttons: {
				"关闭": function() {
					$(this).dialog("close");
				}
			}
		});
	});
</script>
