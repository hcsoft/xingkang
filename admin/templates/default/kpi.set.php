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
        /*bottom: 0;*/
        border-right: 1px solid #fff;
        padding-top: 7px;
    }
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>考核指标设置</h3>
        </div>
    </div>
    <div class="fixed-empty"></div>

    <form method="get" name="formSearch" id="formSearch">
        <input type="hidden" value="kpi" name="act">
        <input type="hidden" value="kpiset" name="op">
        <input type="hidden" id='export' name="export" value="false">
        <table class="tb-type1 noborder search">
            <tbody>
            <tr>
                <th><label for="search_goods_name"> 商品名称</label></th>
                <td><input type="text" value="<?php echo $_GET['search_goods_name']; ?>"
                           name="search_goods_name" id="search_goods_name" class="txt"></td>


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
                    <span class="arrow"></span>
                </div>
            </th>
        </tr>
        </tbody>
    </table>
    <div style='position: relative;display: block;'>
        <form method="post" id="form_member" style="overflow: visible;min-height: 212px;">
            <input type="hidden" name="form_submit" value="ok"/>
            <table class="table tb-type2 nobdb datatable">
                <thead>

                <tr class="thead">
                    <th class="align-center">考核对象</th>
                    <th class="align-center">考核指标</th>
                    <th class="align-center">类型</th>
                    <th class="align-center">方法</th>
                    <th class="align-center">顺序</th>
                </tr>
                </thead>
                <tbody>
                <tpl>123</tpl>
                <notempty name="cfg">
                    <tr class="hover member">
                        <volist name="cfg" id="vo">
                            <td class=" align-center">
                                {$vo.target}
                            </td>

                            <td class=" align-center">
                                {$vo.name}
                            </td>
                            <td class=" align-center">
                                {$vo.type}
                            </td>
                            <td class=" align-center">
                                {$vo.method}
                            </td>
                            <td class=" align-center">
                                {$vo.ord}
                            </td>
                        </volist>
                    </tr>
                </notempty>
                <empty name="cfg">
                    <tr class="no_data">
                        <td colspan="5"><?php echo $lang['nc_no_record'] ?></td>
                    </tr>
                </empty>
                </tbody>
            </table>
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
        $("#formSearch input").keypress(function (event) {
            if (event.keyCode == 13) {
                $('#ncsubmit').click();
            }
        });
        $('#ncexport').click(function () {
            $("#export").val('true');
            $('#formSearch').submit();
        });
    });

</script>

