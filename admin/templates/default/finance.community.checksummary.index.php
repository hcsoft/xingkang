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
    	<form method="get" name="formSearch" id="formSearch">
	        <input type="hidden" value="finance" name="act">
	        <input type="hidden" value="communitycheckSummary" name="op">
	                   汇总日期：<input name="summaryBegin" type="text" class="txt summaryBegin" value="<?php echo $_GET['summaryBegin']; ?>""/>
	        <a href="javascript:void(0);" id="ncsubmit" class="btn-search "
                                   title="<?php echo $lang['nc_query']; ?>">&nbsp;</a>
        </form>
    	
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
        </tbody>
    </table>
    <form method="post" id="form_centercheckplain" name="form_centercheckplain">
        <table class="table tb-type2 nobdb">
            <thead>
            <tr class="thead">
                <th>&nbsp;</th>
                <th class="align-center">机构名称</th>
                <th class="align-center">门诊人次任务指标</th>
                <th class="align-center">门诊人次完成任务指标</th>
                <th class="align-center">门诊收入任务指标</th>
                <th class="align-center">门诊收入完成任务指标</th>
            </tr>
            <tbody>
            <?php if (!empty($output['centerCheckSummarydata']) && is_array($output['centerCheckSummarydata'])) { ?>
                <?php foreach ($output['centerCheckSummarydata'] as $k => $v) { ?>
                    <tr class="hover member">
                        <td class="w24"><input value="<?php echo $v->id; ?>" name="orgId" style="display:none;"/></td>
                        <td><?php echo $v->name; ?></td>
                        <td class="align-center"><?php echo number_format($v->fObject1,0); ?></td>
                        <td class="align-center"><?php echo number_format($v->fComplet1,0); ?></td>
                        <td class="align-center"><?php echo number_format($v->fObject2,2); ?></td>
                        <td class="align-center"><?php echo number_format($v->fComplet2,2); ?></td>
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
      
<script type="text/javascript">
	$(function() {
	    $( ".summaryBegin" ).datepicker({ dateFormat: 'yy-mm-dd' });
	    $('#ncsubmit').click(function () {
            $('#formSearch').submit();
        });
	});
</script>