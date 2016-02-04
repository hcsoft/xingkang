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
            <h3>药品汇总</h3>
        </div>
    </div>
    <div class="fixed-empty"></div>

    <form method="get" name="formSearch" id="formSearch">
        <input type="hidden" value="finance" name="act">
        <input type="hidden" value="goodssum" name="op">
        <input type="hidden" id ='export' name="export" value="false">
        <input type="hidden" name="search_type" id="search_type" value="<?php echo $_GET['search_type']?>"/>
        <input type="hidden" name="checked" id="checked" value="<?php echo $_GET['checked']?>"/>
        <table class="tb-type1 noborder search">
            <tbody>
            <tr>
            	<th><label for="search_goods_name"> 商品名称</label></th>
	            <td><input type="text" value="<?php echo $_GET['search_goods_name']; ?>"
	                       name="search_goods_name" id="search_goods_name" class="txt"></td>
	
	            <th><label>项目类型</label></th>
	            <td colspan="1">
	                <select name="itemtype" id='itemtype'>
	                    <option value="">全部</option>
	                    <?php foreach ($output['goodtype'] as $k => $v) { ?>
	                        <option value="<?php echo $v; ?>"
	                                <?php if ($v == $_GET['itemtype']){ ?>selected<?php } ?>><?php echo $v; ?></option>
	                    <?php } ?>
	                </select>
	            </td>
	            <th><label for="query_start_time">制单日期</label></th>
	            <td><input class="txt date" type="text" value="<?php echo $_GET['query_start_time']; ?>"
	                       id="query_start_time" name="query_start_time">
	                <input class="txt date" type="text" value="<?php echo $_GET['query_end_time']; ?>"
	                       id="query_end_time"
	                       name="query_end_time"/></td>
	            <th><label>财务分类</label></th>
	            <td>
	            	<select name="classtypes[]" id="classtypes" class="orgSelect" multiple>
	                    <?php
	                    $classtypes = $_GET['classtypes'];
	                    if (!isset($classtypes)) {
	                        $classtypes = array();
	                    }
	                    foreach ($output['classtypes'] as $k => $v) {
	                        ?>
	                        <option value="<?php echo $v->iClass_ID; ?>"
	                                <?php if (in_array($v->iClass_ID, $classtypes)){ ?>selected<?php } ?>><?php echo $v->sClass_Name; ?></option>
	                    <?php } ?>
	                </select>
	            </td>
	            <th><label>毛利分类</label></th>
	            <td>
	            	<select name="search_excutetype">
	            		<option value="" <?php if ('' == $_GET['search_excutetype']){ ?>selected<?php } ?> >全部</option>
						<option value="1" <?php if ('1' == $_GET['search_excutetype']){ ?>selected<?php } ?> >推荐商品</option>
						<option value="2" <?php if ('2' == $_GET['search_excutetype']){ ?>selected<?php } ?> >负毛利商品</option>
						<option value="0" <?php if ('0' == $_GET['search_excutetype']){ ?>selected<?php } ?> >其他</option>
					</select>
	            </td>
            </tr>
            <tr>
                <th><label for="search_commonid">商品编码</label></th>
	            <td><input type="text" value="<?php echo $_GET['search_commonid'] ?>" name="search_commonid"
	                       id="search_commonid" class="txt"/></td>
	            <th><label>选择机构</label></th>
	            <td><select name="orgids[]" id="orgids" class="orgSelect" multiple>
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
	                </select>
	            </td>
	            
                <th><label>汇总类型</label></th>
                <td colspan="1" id="sumtypetr">
                    <?php foreach ($output['config']['sumcol'] as $k => $v) { ?>
                        <input type='checkbox' name='sumtype[]'  id='sumtype_<?php echo $v['name']; ?>' <?php if(in_array( $v['name'],$_GET['sumtype'])) echo 'checked'; ?>
                               value='<?php echo $v['name']; ?>' onclick="sumuncheck('sumtype_','<?php echo $v['uncheck']; ?>')" >

                        <label for='sumtype_<?php echo $v['name']; ?>'><?php echo $v['text']; ?></label>
                    <?php } ?>
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
                <?php foreach ($output['displaytext'] as $k => $v) {
                    ?>
                    <th class="align-center"><?php echo $v?></th>
                <?php  }?>
            </tr>
            <tbody>
            <?php if (!empty($output['data_list']) && is_array($output['data_list'])) { ?>
                <?php foreach ($output['data_list'] as $k => $v) { ?>
                    <tr class="hover member">
                        <td class=" align-center">
                            <?php echo $k+1?>
                        </td>
                        <?php foreach ($output['displaycol'] as $key => $item) {
                            ?>
                            <th class="align-left"><?php if(stripos($item,'Num') >0 )  echo number_format($v->$item,0); elseif(stripos($item,'Price') >0) echo number_format($v->$item,3); else  echo $v->$item;?></th>
                        <?php  }?>
                       	
                        <td class=" align-right">
                            <?php echo number_format($v->fSale_TaxFactMoney, 3)?>
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
                    <td colspan="17">
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
        $("#classtypes").multiselect(
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
            var sumtypes =$(":checkbox[name='sumtype[]'][checked]");
            if(sumtypes.length<=0){
                $("#sumtype_good").attr("checked", true);
                sumtypes =$(":checkbox[name='sumtype[]'][checked]");
            }
            var search_type_select = $('input[name="search_type_select"]:checked').val();
            $("#search_type").val($('input[name="search_type_select"]:checked').val());
            checked[search_type_select] = [];
            for(var i =0 ;i < sumtypes.length;i++){
                checked[search_type_select].push( $(sumtypes[i]).val());
            }
            $("#checked").val(makechecked(checked));
            $('#formSearch').submit();
        });
        $("#formSearch input").keypress(function(event){
            if(event.keyCode==13){
                $('#ncsubmit').click();
            }
        });
        $('#ncexport').click(function () {
            $("#export").val('true');
            var sumtypes =$(":checkbox[name='sumtype[]'][checked]");
            if(sumtypes.length<=0){
                $("#sumtype_good").attr("checked", true);
                sumtypes =$(":checkbox[name='sumtype[]'][checked]");
            }
            var search_type_select = $('input[name="search_type_select"]:checked').val();
            $("#search_type").val($('input[name="search_type_select"]:checked').val());
            checked[search_type_select] = [];
            for(var i =0 ;i < sumtypes.length;i++){
                checked[search_type_select].push( $(sumtypes[i]).val());
            }
            $("#checked").val(makechecked(checked));
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

