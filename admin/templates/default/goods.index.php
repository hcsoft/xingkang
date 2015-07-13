<?php defined('InShopNC') or exit('Access Invalid!'); ?>
<link href="<?php echo ADMIN_TEMPLATES_URL; ?>/css/font/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
<link href="<?php echo ADMIN_TEMPLATES_URL; ?>/showLoading/css/showLoading.css" rel="stylesheet"/>
<!--[if IE 7]>
<link rel="stylesheet" href="<?php echo ADMIN_TEMPLATES_URL;?>/css/font/font-awesome/css/font-awesome-ie7.min.css">
<![endif]-->

<style>
	#editdialog table td{
		padding:5px 5px;
	}
	#editdialog table td input{
		width: 210px;
	}
	#editdialog {
        display: none;
    }
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><?php echo $lang['goods_index_goods']; ?></h3>
            <ul class="tab-base">
                <li><a href="JavaScript:void(0);"
                       class="current"><span><?php echo $lang['goods_index_all_goods']; ?></span></a></li>
                
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form method="get" name="formSearch" id="formSearch">
        <input type="hidden" name="act" value="goods">
        <input type="hidden" name="op" value="goods">
        <table class="tb-type1 noborder search">
            <tbody>
            <tr>
                <th><label for="search_goods_name"> <?php echo $lang['goods_index_name']; ?></label></th>
                <td><input type="text" value="<?php echo $output['search']['search_goods_name']; ?>"
                           name="search_goods_name" id="search_goods_name" class="txt"></td>
                <th><label for="search_commonid">商品编码</label></th>
                <td><input type="text" value="<?php echo $output['search']['search_commonid'] ?>" name="search_commonid"
                           id="search_commonid" class="txt"/></td>
                <th><label><?php echo $lang['goods_index_finance_cat_search']; ?></label></th>
                <td colspan="8">
                    <select name="classtype" id='classtype'>
                    <option value="">全部</option>
                    <option value="null" <?php if ('null' == $_GET['classtype']){ ?>selected<?php } ?>>未分类</option>
                    <?php foreach ($output['classtypes'] as $k => $v) { ?>
                        <option value="<?php echo $v->iClass_ID; ?>"
                                <?php if ($v->iClass_ID == $_GET['classtype']){ ?>selected<?php } ?>><?php echo $v->sClass_ID . $v->sClass_Name; ?></option>
                    <?php } ?>
                </select></td>
                <th><label for="sCustomer_ID">供应商名称</label></th>
                <td><input type="text" value="<?php echo $_GET['sCustomer_Name'] ?>" name="sCustomer_Name"
                           id="sCustomer_Name" class="txt"/></td>
                 <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search "
                       title="<?php echo $lang['nc_query']; ?>">&nbsp;</a></td>
            </tr>
            </tbody>
        </table>
    </form>
    
    <div style='position: relative;display: block;'>
    	<form method='post' id="form_goods">
        	<input type="hidden" name="form_submit" value="ok"/>
            <table class="table tb-type2 nobdb datatable">
                <thead>
                	 <tr class="thead">
		                
		                <th class="align-center">商品编码</th>
		                <th colspan="2"><?php echo $lang['goods_index_name']; ?></th>
		                <th class="align-center">规格</th>
		                <th>产地</th>
		                <th class="align-center">财务分类</th>
		                <th class="align-center">常规单位</th>
		                <th class="align-center">最小单位</th>
		                <th class="w48 align-center"><?php echo $lang['nc_handle']; ?> </th>
		            </tr>
                </thead>
                <tbody>
                	<?php if (!empty($output['goods_list']) && is_array($output['goods_list'])) { ?>
                    <?php foreach ($output['goods_list'] as $k => $v) { ?>
                    	<tr class="hover member">
                        	
                            <td class="w60 align-center" nowrap><?php echo $v->goods_commonid; ?></td>
                            <td class="w60 picture">
                            	<div class="size-56x56"><span class="thumb size-56x56"><i></i><img
                                        src="<?php 
                                        	$imgthumb = array();
                                        	$imgthumb['store_id'] = $v->store_id;
                                        	$imgthumb['goods_image'] = $v->goods_image;
                                        echo thumb($imgthumb, 60); ?>" onload="javascript:DrawImage(this,56,56);"/></span>
	                            </div>
	                        </td>
	                        <td class="goods-name w270"><p><span><?php echo $v->goods_name; ?></span></p>
	                        </td>
	                        
	                        <td><p>完整规格: <?php echo $v->sDrug_Spec; ?></p></td>
	                        <td><p><?php echo $v->brand_name; ?></p>
	
	                            <p><?php echo $v->gc_name; ?></p></p>
	                        </td>
	                        
	                        <td class="align-center"><?php
								foreach ($output['classtypes'] as $classtypesV) {
									//                        		echo $classtypesV->iClass_ID . '====' . $classtypesV->sClass_Name;
									if (intval($classtypesV->iClass_ID) == intval($v->iDrug_StatClass)) {
										echo $classtypesV->sClass_ID . '  ' . $classtypesV->sClass_Name;
										break;
									}
								}
								?><p></p><p><a
	                                    href="javascript:void(0)"
	                                    onclick="edit(<?php echo $v->goods_commonid ?>)">修改分类</a></p></td>
	                        <td>
	                        	<p><?php echo $v->sDrug_Unit; ?></p>
	                        	<p>进价: <?php echo number_format($v->fDS_BuyPrice,2); ?></p>
	                        	<p>零价: <?php echo number_format($v->fDS_RetailPrice,2); ?></p>
	                        	<p>实际库存: <?php echo number_format($v->fDS_SStock,0); ?></p>
	                        </td>
							<td >
	                        	<p><?php echo $v->sDrug_LeastUnit; ?></p>
	                        	<p>进价: <?php echo number_format($v->fDS_LeastBuyPrice,2); ?></p>
	                        	<p>零价: <?php echo number_format($v->fDS_LeastRetailPrice,2); ?></p>
	                        	<p>实际库存: <?php echo number_format($v->fDS_LeastSStock,0); ?></p>
	                        </td>
	                        
	                        
	                        <td class="align-center"><p><a
	                                   href="javascript:void(0)"
	                                   onclick="goods_stock_account('<?php echo $v->goods_commonid; ?>',
	                                   '<?php echo $v->goods_commonid . ',' . $v->goods_name . ',' . $v->brand_name . $v->gc_name; ?>');">库存</a></p>
	                                    
								<p><a href="javascript:void(0)"
									 onclick="goods_machine_account('<?php echo $v->goods_commonid; ?>','<?php echo $v->goods_name; ?>');">台账</a></p>
	                            <p><a href="javascript:void(0);"
	                                  onclick="goods_change_price('<?php echo $v->goods_commonid; ?>','<?php echo $v->goods_commonid . ',' . $v->goods_name; ?>');">调价记录</a></p>
	                                  </td>
	                        </td>
                        </tr>
                        <tr style="display:none;">
	                        <td colspan="12" style="padding:0px;">
	                            <div class="ncsc-goods-sku ps-container"></div>
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
                <?php if (!empty($output['goods_list']) && is_array($output['goods_list'])) { ?>
                    <tr>
                        <td colspan="10">
                            <div class="pagination"> <?php echo $output['page']; ?> </div>
                        </td>
                    </tr>
                <?php } ?>
                </tfoot>
            </table>
    	</form>
    </div>
</div>

<div id="editdialog" title="修改分类">
    <span id="errormsg" style="color:red;width:100%;display:block;text-align: center;"></span>
    <form>
        <input type="hidden" id='spotid' name="spotid">
        <input type="hidden" id='spotid' name="spotid">
        <table>
            <tr>
                <td>商品<br/>编号：</td>
                <td><input style="color:blue;" id="goods_commonid" name="goods_commonid" readonly></td>
            </tr>
            <tr>
                <td>商品<br/>名称：</td>
                <td><input style="color:blue;" id="goods_name" name="goods_name" readonly></td>
            </tr>
            <tr>
                <td>规格：</td>
                <td>
                	<p>完整规格: <input style="color:blue;" id="sDrug_Spec" name="sDrug_Spec" readonly></p>
                	<p>含量规格: <input style="color:blue;" id="sDrug_Content" name="sDrug_Content" readonly></p>
                	<p>包装规格: <input style="color:blue;" id="sDrug_PackSpec" name="sDrug_PackSpec" readonly></p>
                </td>
            </tr>
            <tr>
                <td>单位：</td>
                <td>
                	<p>常规单位:<input style="color:blue;" id="sDrug_Unit" name="sDrug_Unit" readonly></p>
					<p>最小单位:<input style="color:blue;" id="sDrug_LeastUnit" name="sDrug_LeastUnit" readonly></p>
                </td>
            </tr>
			<tr>
                <td>价格：</td>
                <td>
                	<input style="color:blue;" id="goods_price" name="goods_price" readonly>
                </td>
            </tr>
            <tr>
                <td>财务<br/>分类：</td>
                <td><select name="classtype" id="classtype">
                        <option value="">未分类</option>
                        <option value="11">011中成药</option>
                        <option value="12">012保健食品</option>
                        <option value="13">013化妆品</option>
                        <option value="14">014食品</option>
                        <option value="15">015外用</option>
                        <option value="16">016卫生用品</option>
                        <option value="17">017西药</option>
                        <option value="18">018医疗器械</option>
                        <option value="19">019疫苗</option>
                        <option value="21">021中药饮片</option>
                        <option value="22">022注射剂</option>
                        <option value="23">000其他</option>
                        <option value="24">024按摩</option>
                        <option value="25">025针灸</option>
                        <option value="26">026理疗</option>
                        <option value="27">027西医诊费</option>
                        <option value="28">028中医诊费</option>
                        <option value="29">029体检</option>
                        <option value="30">030化验</option>
                        <option value="31">031检查</option>
                    </select></td>
            </tr>
            <tr>
                <td>产地/厂商：</td>
                <td><input style="color:blue;" id="sDrug_Brand" name="sDrug_Brand" readonly></td>
            </tr>
        </table>
    </form>
</div>

<style>
    .goods-sub-dialog table {
        width: 100%;
    }

    .goods-sub-dialog table tbody tr td {
        text-align: right;
    }

    /*前3列居中*/
    .goods-sub-dialog table tbody tr td:first-child, #detaildialog table tbody tr td:first-child + td, #detaildialog table tbody tr td:first-child + td + td {
        text-align: center;
    }

    .goods-sub-dialog table td {
        border: solid 1px #808080;
        padding: 5px;
    }

    .goods-sub-dialog table th {
        white-space: pre;
        background-color: lightblue;
        border: solid 1px #808080;
        font-weight: bold;
        padding: 5px;
        text-align: center;
        font-size:12px;
    }
</style>
<!-- 台账 -->
<div id="machineAccountDialog" title="台账" class="goods-sub-dialog">
    <span class="errormsg" style="color:red;width:100%;display:block;text-align: center;font-weight: bold;"></span>
    <div>
        <table>
            <thead>
            	<tr>
            		<td colspan="14">
            			<form>
					        <input type="hidden" name="machineAccountDrugId" id="machineAccountDrugId">
					        <input type="hidden" name="machineAccountDrugName" id="machineAccountDrugName">
	            			<label>选择机构</label>
	            			<select name="orgid" id="orgid" class="orgSelect">
	            				<option value="" selected>全部</option>
		                        <?php
		                        $orgid = $_GET['orgid'];
		                        if (!isset($orgids)) {
		                            $orgids = array();
		                        }
		                        foreach ($output['treelist'] as $k => $v) {
		                            ?>
		                            <option value="<?php echo $v->id; ?>"
		                                    <?php if ($v->id == $orgid){ ?>selected<?php } ?>><?php echo $v->name; ?></option>
		                        <?php } ?>
		                    </select>
	            			<label for="query_start_time">台账日期</label>
	            			<input class="txt date" style="width:80px;" type="text"
	                           id="query_start_time" name="query_start_time">-
	                    	<input class="txt date" style="width:80px;" type="text"
	                           id="query_end_time" name="query_end_time"/>
	                        <a href="javascript:void(0);" onclick="ajaxGetGoodMachineAccount();" class="btn-search "
	                      	   title="<?php echo $lang['nc_query']; ?>">&nbsp;</a>
	                     </form>
            		</td>
            	</tr>
            	<tr>
					<th>单据流水</th>
		            <th>发生日期</th>
		            <th>相关单位</th>
		            <th>单据类型</th>
		            <th>入库数</th>
		            <th>业务出库</th>
		            <th>库房出库</th>
		            <th>业务结存</th>
		            <th>库房结存</th>
		            <th>进价</th>
		            <th>进价金额 </th>
		            <th>售价</th>
		            <th>售价金额</th>
		            <th>机构</th>
            	</tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <span class="datamsg">无数据!</span>
    </div>
</div>

<!-- 库存 -->
<div id="stockDialog" title="" class="goods-sub-dialog">
    <span class="errormsg" style="color:red;width:100%;display:block;text-align: center;font-weight: bold;"></span>
    <div>
        <table>
            <thead>
            	<tr>
            		<td colspan="20">
            			<form>
					        <input type="hidden" name="stockAccountDrugId" id="stockAccountDrugId">
					        <input type="hidden" name="stockAccountDrugName" id="stockAccountDrugName">
	            			<label>选择机构</label>
	            			<select name="stockorgid" id="stockorgid" class="orgSelect">
	            				<option value="" selected>全部</option>
		                        <?php
		                        $orgid = $_GET['orgid'];
		                        if (!isset($orgids)) {
		                            $orgids = array();
		                        }
		                        foreach ($output['treelist'] as $k => $v) {
		                            ?>
		                            <option value="<?php echo $v->id; ?>"
		                                    <?php if ($v->id == $orgid){ ?>selected<?php } ?>><?php echo $v->name; ?></option>
		                        <?php } ?>
		                    </select>
	                        <a href="javascript:void(0);" onclick="ajaxGetGoodStockAccount();" class="btn-search "
	                      	   title="<?php echo $lang['nc_query']; ?>">&nbsp;</a>
	                     </form>
            		</td>
            	</tr>
            	<tr>
					<th nowrap  rowspan="3" style="min-width:150px;">机构</th>
	                <th  nowrap colspan="5">常规单位</th>
	                <th nowrap  colspan="5">最小单位</th>
	                <th  nowrap rowspan="3">零价金额</th>
	                <th nowrap  rowspan="3">进价金额</th>
	                <th nowrap  rowspan="3">进销差</th>
	            </tr>
	            <tr>
	                <th nowrap  rowspan="2" style="min-width:40px;">单位</th>
	                <th nowrap  colspan="2" style="min-width:40px;">库存</th>
	                <th nowrap   rowspan="2" style="min-width:40px;">零价</th>
	                <th  nowrap rowspan="2" style="min-width:40px;">进价</th>
	                <th nowrap  rowspan="2" style="min-width:40px;">单位</th>
	                <th nowrap  colspan="2" style="min-width:40px;">库存</th>
	                <th nowrap   rowspan="2" style="min-width:40px;">零价</th>
	                <th  nowrap rowspan="2" style="min-width:40px;">进价</th>
	            <tr>
	                <th nowrap style="min-width:40px;">可售</th>
	                <th nowrap style="min-width:40px;">实际</th>
	                <th nowrap style="min-width:40px;">可售</th>
	                <th nowrap style="min-width:40px;">实际</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <span class="datamsg">无数据!</span>
    </div>
</div>

<!-- 调价记录 -->
<div id="changePriceDialog" title="调价记录" class="goods-sub-dialog">
    <span class="errormsg" style="color:red;width:100%;display:block;text-align: center;font-weight: bold;"></span>
    <div>
        <table>
            <thead>
            	<tr>
            		<td colspan="13">
            			<form>
					        <input type="hidden" name="changePriceDrugId" id="changePriceDrugId">
					        <input type="hidden" name="changePriceDrugName" id="changePriceDrugName">
	            			<label>选择机构</label>
	            			<select name="changePriceOrgid" id="changePriceOrgid" class="orgSelect">
	            				<option value="" selected>全部</option>
		                        <?php
		                        $orgid = $_GET['orgid'];
		                        if (!isset($orgids)) {
		                            $orgids = array();
		                        }
		                        foreach ($output['treelist'] as $k => $v) {
		                            ?>
		                            <option value="<?php echo $v->id; ?>"
		                                    <?php if ($v->id == $orgid){ ?>selected<?php } ?>><?php echo $v->name; ?></option>
		                        <?php } ?>
		                    </select>
	            			<label for="query_start_time">调价日期</label>
	            			<input class="txt date" style="width:80px;" type="text"
	                           id="query_start_time_change_price" name="query_start_time_change_price">-
	                    	<input class="txt date" style="width:80px;" type="text"
	                           id="query_end_time_change_price" name="query_end_time_change_price"/>
	                        <a href="javascript:void(0);" onclick="ajaxGetGoodChangePrice();" class="btn-search "
	                      	   title="<?php echo $lang['nc_query']; ?>">&nbsp;</a>
	                     </form>
            		</td>
            	</tr>
            	<tr>
		            <th>机构</th>
		            <th>调前价</th>
		            <th>调后价</th>
		            <th>调价日期</th>
		            <th>执行开始日期</th>
		            <th>执行结束日期</th>
		            <th>调价人</th>
		            <th>单位</th>
		            <th>项目类型</th>
		            <th>价格类型</th>
            	</tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <span class="datamsg">无数据!</span>
    </div>
</div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/i18n/zh-CN.js"
        charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.formautofill.js"></script>

<link rel="stylesheet" type="text/css"
      href="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/themes/smoothness/jquery.ui.css"/>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/common_select.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/dialog/dialog.js" id="dialog_js"
        charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.mousewheel.js"></script>

       	<link href='http://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css' />
		
		<noscript><link rel="stylesheet" type="text/css" href="css/noJS.css" /></noscript>
<script type="text/javascript" src="<?php echo ADMIN_TEMPLATES_URL; ?>/showLoading/js/jquery.showLoading.min.js"></script>		
<script type="text/javascript">
    var SITEURL = "<?php echo SHOP_SITE_URL; ?>";
    $(function () {
//        gcategoryInit("gcategory");
        $('#ncsubmit').click(function () {
//            $('input[name="op"]').val('goods');
            $('#formSearch').submit();
            console.log(123);
        });
	
		//台账窗口初始化
		$("#machineAccountDialog").dialog({
            resizable: false,
            maxHeight: 200,
            width: 1100,
            autoOpen: false,
            modal:true,
            height:500,
            buttons: {
                "关闭": function () {
                    $(this).dialog("close");
                }
            }
        });
        
        //库存窗口初始化
		$("#stockDialog").dialog({
            resizable: false,
            maxHeight: 200,
            width: 1100,
            autoOpen: false,
            modal:true,
            height:500,
            buttons: {
                "关闭": function () {
                    $(this).dialog("close");
                }
            }
        });
        //调价记录窗口初始化
		$("#changePriceDialog").dialog({
            resizable: false,
            maxHeight: 200,
            width: 1100,
            autoOpen: false,
            modal:true,
            height:500,
            buttons: {
                "关闭": function () {
                    $(this).dialog("close");
                }
            }
        });
        
        
		//格式化日期
//        $('input.date').datepicker({dateFormat: 'yy-mm-dd'});
//		$("#query_start_time").datepicker({defaultDate : +7})
        // 批量删除
        $('a[nctype="del_batch"]').click(function () {
            if (confirm('<?php echo $lang['nc_ensure_del'];?>')) {
                ajaxpost('form_goods', '', '', 'onerror');
            }
        });


        
        
        $("#editdialog").dialog({
            resizable: false,
            autoOpen: false,
            close: function () {
//                $('#formSearch').submit();
//                console.log($('#formSearch').submit());
				$('#ncsubmit').trigger('click');
            },
            buttons: {
                "关闭": function () {                	
                    $(this).dialog("close");
                },
                "保存": function () {
//                    console.log($("#editdialog form").serialize());
                    $.ajax({
                        url: "index.php?act=goods&op=goodsAjaxSaveStateClass",
                        data: $("#editdialog form").serialize(), dataType: 'json', success: function (data) {
                            if (data.success) {
                                success(data.msg);
                                
                            } else {
                                error(data.msg);
                            }
                        }
                    });
                }
            }
        });
    });
	//台账
	function ajaxGetGoodMachineAccount(){
		$("#machineAccountDialog .errormsg").html('');
		$("#machineAccountDialog .datamsg").html('正在查询....');
		console.log($("#machineAccountDialog form").serialize());
		$("body").showLoading();
        $.ajax({
            url: "index.php?act=goods&op=machineAccount",
            data: $("#machineAccountDialog form").serialize(), 
            dataType: 'json', 
            success: function (data) {
                console.log(data);
                $("#machineAccountDialog table tbody").html('');
                if (data.data && data.data.length > 0) {
                    $("#machineAccountDialog .datamsg").html('');
                    for (var i = 0; i < data.data.length; i++) {
                        var row = data.data[i];
                        var rowstr = '<tr>';
                        console.log(row);
                        var iId = row.iID;
                        if(iId == null) iId = '';
                        rowstr += '<td>' + iId + '</td>';
                        rowstr += '<td>' + datestr(row.dDate) + '</td>';
                        rowstr += '<td>' + textstr(row.sCustomer_Name) + '</td>';
                        rowstr += '<td>' + textstr(row.sPromt) + '</td>';
                        rowstr += '<td>' + textstr(row.sShowInNum) + '</td>';
                        rowstr += '<td>' + textstr(row.sShowOutONum) + '</td>';
                        rowstr += '<td>' + textstr(row.sShowOutNum) + '</td>';
                        rowstr += '<td>' + textstr(row.sShowSpareONum) + '</td>';
                        rowstr += '<td>' + textstr(row.sShowSpareNum) + '</td>';
                        rowstr += '<td>' + numtostr(row.fCostPrice) + '</td>';
                        rowstr += '<td>' + numtostr(row.fCostMoney) + '</td>';
                        rowstr += '<td>' + numtostr(row.fPrice) + '</td>';
                        rowstr += '<td>' + numtostr(row.fMoney) + '</td>';
                        rowstr += '<td>' + textstr(row.Name) + '</td>';
                        rowstr += '</tr>';
                        $("#machineAccountDialog table tbody").append(rowstr)
                    }
                } else {
                    $("#machineAccountDialog .datamsg").html('无数据!');
                }
                var sDrugName = $("#machineAccountDrugName").val();
                $("#machineAccountDialog").dialog("option", "title", '台账（药品名称：' + sDrugName + '）');
                $("#machineAccountDialog").dialog("open");
                $("body").hideLoading();
            }
        });
	}
	//库存
	function ajaxGetGoodStockAccount(){
		$("#stockDialog .errormsg").html('');
		$("#stockDialog .datamsg").html('正在查询....');
		console.log($("#stockDialog form").serialize());
		$("body").showLoading();
        $.ajax({
            url: "index.php?act=goods&op=stockAccount",
            data: $("#stockDialog form").serialize(), 
            dataType: 'json', 
            success: function (data) {
                console.log(data);
                $("#stockDialog table tbody").html('');
                var drugInfo = '';
                if (data.data && data.data.length > 0) {
                    $("#stockDialog .datamsg").html('');
                    for (var i = 0; i < data.data.length; i++) {
                        var row = data.data[i];
                        var rowstr = '<tr>';
                        console.log(row);
                        rowstr += '<td style="text-align:left;">' + textstr(row.Name) + '</td>';
                        rowstr += '<td>' + textstr(row.sDrug_Unit) + '</td>';
                        rowstr += '<td>' + numtostr(row.fDS_OStock) + '</td>';
                        rowstr += '<td>' + numtostr(row.fDS_SStock) + '</td>';
                        rowstr += '<td>' + numtostr(row.fDS_RetailPrice) + '</td>';
                        rowstr += '<td>' + numtostr(row.fDS_BuyPrice) + '</td>';
                        rowstr += '<td>' + textstr(row.sDrug_LeastUnit) + '</td>';
                        rowstr += '<td>' + numtostr(row.fDS_LeastOStock) + '</td>';
                        rowstr += '<td>' + numtostr(row.fDS_LeastSStock) + '</td>';
                        rowstr += '<td>' + numtostr(row.fDS_LeastRetailPrice) + '</td>';
                        rowstr += '<td>' + numtostr(row.fDS_LeastBuyPrice) + '</td>';
                        rowstr += '<td>' + numtostr(row.fDS_RetailPrice * row.fDS_OStock + row.fDS_LeastRetailPrice * row.fDS_LeastOStock) + '</td>';
                        rowstr += '<td>' + numtostr(row.fDS_BuyPrice * row.fDS_OStock + row.fDS_LeastBuyPrice * row.fDS_LeastOStock) + '</td>';
                        rowstr += '<td>' + numtostr(row.fDS_RetailPrice * row.fDS_OStock - row.fDS_BuyPrice * row.fDS_OStock + row.fDS_LeastRetailPrice * row.fDS_LeastOStock - row.fDS_LeastBuyPrice * row.fDS_LeastOStock) + '</td>';
                        rowstr += '</tr>';
                        $("#stockDialog table tbody").append(rowstr)
                    }
                } else {
                    $("#stockDialog .datamsg").html('无数据!');
                }
                
                var sDrugName = $("#stockAccountDrugName").val();
                $("#stockDialog").dialog("option", "title", '库存（药品信息：' + sDrugName + '）');
                $("#stockDialog").dialog("open");
                $("body").hideLoading();
            }
        });
	}
	
	//调价记录
	function ajaxGetGoodChangePrice(){
		$("#changePriceDialog .errormsg").html('');
		$("#changePriceDialog .datamsg").html('正在查询....');
		$("body").showLoading();
        $.ajax({
            url: "index.php?act=goods&op=goodsChangePrice",
            data: $("#changePriceDialog form").serialize(), 
            dataType: 'json', 
            success: function (data) {
                console.log(data);
                $("#changePriceDialog table tbody").html('');
                if (data.data && data.data.length > 0) {
                    $("#changePriceDialog .datamsg").html('');
                    for (var i = 0; i < data.data.length; i++) {
                        var row = data.data[i];
                        var rowstr = '<tr>';
                        var iPrice_type = '全部';
                        if(row.iPrice_Type == 0){
                        	iPrice_type = '零售价';
                        }else if(row.iPrice_Type == 1){
                        	iPrice_type = '特价';
                        }else if(row.iPrice_Type == 2){
                        	iPrice_type = '二件价';
                        }
                        console.log(row);
                        rowstr += '<td style="text-align:left;">' + textstr(row.Name) + '</td>';
                        rowstr += '<td>' + numtostr(row.fPrice_Before) + '</td>';
                        rowstr += '<td>' + numtostr(row.fPrice_After) + '</td>';
                        rowstr += '<td>' + datestr(row.dPrice_Date) + '</td>';
                        rowstr += '<td>' + datestr(row.dPrice_BeginDate) + '</td>';
                        rowstr += '<td>' + datestr(row.dPrice_EndDate) + '</td>';
                        rowstr += '<td>' + textstr(row.sPrice_Person) + '</td>';
                        rowstr += '<td>' + textstr(row.Unit) + '</td>';
                        rowstr += '<td>' + textstr(row.ItemType) + '</td>';
                        rowstr += '<td>' + iPrice_type + '</td>';
                        rowstr += '</tr>';
                        $("#changePriceDialog table tbody").append(rowstr)
                    }
                } else {
                    $("#changePriceDialog .datamsg").html('无数据!');
                }
                var sDrugName = $("#changePriceDrugName").val();
                $("#changePriceDialog").dialog("option", "title", '调价记录（药品信息：' + sDrugName + '）');
                $("#changePriceDialog").dialog("open");
                $("body").hideLoading();
            }
        });
	}
	function goods_machine_account(goods_commonid,sDrugName){
		$('input.date').datepicker({dateFormat: 'yy-mm-dd'});
        $("#machineAccountDrugId").val(goods_commonid);
        $("#machineAccountDrugName").val(sDrugName);
        var curDate = new Date();
        var year = curDate.getFullYear();
        var month = (curDate.getMonth() + 1) < 10 ? '0' + (curDate.getMonth() + 1) : curDate.getMonth() + 1;
        var day = curDate.getDay < 10 ? '0' + curDate.getDay : curDate.getDate();
        $("#query_start_time").val(year + '-' + month + '-01');
		$("#query_end_time").val(year + '-' + month + '-' + day);
        ajaxGetGoodMachineAccount();
	}
	function goods_stock_account(goods_commonid,sDrugName){
        $("#stockAccountDrugId").val(goods_commonid);
        $("#stockAccountDrugName").val(sDrugName);
        ajaxGetGoodStockAccount();
	}
	function goods_change_price(goods_commonid,sDrugName){
		$('input.date').datepicker({dateFormat: 'yy-mm-dd'});
        $("#changePriceDrugId").val(goods_commonid);
        $("#changePriceDrugName").val(sDrugName);
        var curDate = new Date();
        var year = curDate.getFullYear();
        var month = (curDate.getMonth() + 1) < 10 ? '0' + (curDate.getMonth() + 1) : curDate.getMonth() + 1;
        var day = curDate.getDay < 10 ? '0' + curDate.getDay : curDate.getDate();
        $("#query_start_time_change_price").val(year + '-' + month + '-01');
		$("#query_end_time_change_price").val(year + '-' + month + '-' + day);
        ajaxGetGoodChangePrice();
	}
    // 获得选中ID
    function getId() {
        var str = '';
        $('#form_goods').find('input[name="id[]"]:checked').each(function () {
            id = parseInt($(this).val());
            if (!isNaN(id)) {
                str += id + ',';
            }
        });
        if (str == '') {
            return false;
        }
        str = str.substr(0, (str.length - 1));
        return str;
    }
    
    
    function edit(goods_commonid){
    	$.getJSON("index.php?act=goods&op=financeAjaxGetDetail", {'goodscommonid': goods_commonid}, function (data) {
			data.classtype = data.iDrug_StatClass;
			
			if(data.goods_price != null){
				data.goods_price = '￥' + data.goods_price;
			}else{
				data.goods_price = '';
			}
            $("#editdialog form").autofill(data);
            $("#editdialog").dialog("open");
        });
    }
    
    function numtostr(numstr) {
        var num = parseFloat(numstr);
        if (num) {
            return "" + num.toFixed(2);
        } else {
            return "";
        }
    }
    function textstr(tstr) {
        if (tstr) {
            return tstr;
        } else {
            return '';
        }
    }
    
    function datestr(d){	
    	if(d != null && d != ''){
	    	var datetime = new Date(d);
    		var year = datetime.getFullYear();
		    var month = datetime.getMonth() + 1 < 10 ? "0" + (datetime.getMonth() + 1) : datetime.getMonth() + 1;
		    var date = datetime.getDate() < 10 ? "0" + datetime.getDate() : datetime.getDate();
		    var hour = datetime.getHours()< 10 ? "0" + datetime.getHours() : datetime.getHours();
		    var minute = datetime.getMinutes()< 10 ? "0" + datetime.getMinutes() : datetime.getMinutes();
		    var second = datetime.getSeconds()< 10 ? "0" + datetime.getSeconds() : datetime.getSeconds();
		    return year + "-" + month + "-" + date+" "+hour+":"+minute+":"+second;
    	}
    	return '';
    }
    function showmsg(msg) {
        $("#editdialog-message").html(msg);
        $("#editdialog").dialog("open");
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
