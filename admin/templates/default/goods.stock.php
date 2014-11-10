<?php defined('InShopNC') or exit('Access Invalid!'); ?>
<link href="<?php echo ADMIN_TEMPLATES_URL; ?>/css/font/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
<!--[if IE 7]>
<link rel="stylesheet" href="<?php echo ADMIN_TEMPLATES_URL;?>/css/font/font-awesome/css/font-awesome-ie7.min.css">
<![endif]-->
<style>

.datatable th, .datatable td {
    border: solid 1px #DEEFFB;
}
.subdatatable  th{
    text-align: center;
    word-break:keep-all;
}
.subdatatable  th, .subdatatable td {
    border: solid 1px #79a1fb;
}
.subdatatable .thead{
    background-color: #d3d3d3;
}
.ncsc-goods-sku.ps-container{
    background-color:transparent;
    border:none;
}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>库存管理</h3>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form method="get" name="formSearch" id="formSearch">
        <input type="hidden" name="act" value="goods">
        <input type="hidden" name="op" value="stock">
        <table class="tb-type1 noborder search">
            <tbody>
            <tr>
                <th><label>选择机构</label></th>
                <td colspan="1"><select name="orgid" id="orgid" class="orgSelect">
<!--                        <option value="" >全部</option>-->
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
                    </select></td>
                </td>
                <th><label for="search_goods_name"> <?php echo $lang['goods_index_name']; ?></label></th>
                <td><input type="text" value="<?php echo $output['search']['search_goods_name']; ?>"
                           name="search_goods_name" id="search_goods_name" class="txt"></td>
                <th><label for="search_commonid">商品编码</label></th>
                <td><input type="text" value="<?php echo $output['search']['search_commonid'] ?>" name="search_commonid"
                           id="search_commonid" class="txt"/></td>
                <td><input type="checkbox" value="true" <?php if($output['search']['allowzero'] =='true')  echo checked; ?> name="allowzero"
                           id="allowzero" /> <label for="allowzero">显示零库存</label></td>
                <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search "
                       title="<?php echo $lang['nc_query']; ?>">&nbsp;</a></td>
                <td class="w120">&nbsp;</td>
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
                    <li><?php echo $lang['goods_index_help1']; ?></li>
                    <li><?php echo $lang['goods_index_help2']; ?></li>
                </ul>
            </td>
        </tr>
        </tbody>
    </table>
    <form method='post' id="form_goods" action="<?php echo urlAdmin('goods', 'goods_del'); ?>">
        <input type="hidden" name="form_submit" value="ok"/>
        <table class="table tb-type2 datatable">
            <thead>
            <tr class="thead">
                <th nowrap rowspan="3" class="w24"></th>
                <th nowrap rowspan="3"  class="w24"></th>
                <th nowrap rowspan="3"  class="align-center">商品编码</th>
                <th nowrap  rowspan="3"  colspan=""><?php echo $lang['goods_index_name']; ?></th>
                <th nowrap  colspan="3"  class="align-center">规格</th>

                <th nowrap  rowspan="3" >厂家/产地</th>
<!--                <th class="align-center" >库存</th>-->
<!--                <th class="align-center" >最小单位库存</th>-->
<!--                <th class="align-center">常规单位价格</th>-->
<!--                <th class="align-center">最小单位价格</th>-->
<!--                <th class="align-center">金额</th>-->
<!--                <th class="align-center">进销差</th>-->

                <th  nowrap colspan="5" class="align-center" >常规单位</th>
                <th nowrap  colspan="5" class="align-center" >最小单位</th>
                <th  nowrap rowspan="3" class="align-center">零价金额</th>
                <th nowrap  rowspan="3" class="align-center">进价金额</th>
                <th nowrap  rowspan="3" class="align-center">进销差</th>
            </tr>
            <tr>
                <th nowrap  rowspan="2" class="align-center" >完整</th>
                <th nowrap  rowspan="2" class="align-center" >含量</th>
                <th nowrap  rowspan="2" class="align-center" >包装</th>
                <th nowrap  rowspan="2" class="align-center" >单位</th>
                <th nowrap  colspan="2" class="align-center" >库存</th>
                <th nowrap   rowspan="2" class="align-center">零价</th>
                <th  nowrap rowspan="2"  class="align-center">进价</th>
                <th nowrap  rowspan="2" class="align-center" >单位</th>
                <th nowrap  colspan="2" class="align-center" >库存</th>
                <th nowrap   rowspan="2" class="align-center">零价</th>
                <th  nowrap rowspan="2"  class="align-center">进价</th>
            <tr>
                <th nowrap  class="align-center" >可售</th>
                <th nowrap  class="align-center" >实际</th>
                <th nowrap  class="align-center" >可售</th>
                <th nowrap  class="align-center" >实际</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($output['goods_list']) && is_array($output['goods_list'])) {
                ?>
                <?php foreach ($output['goods_list'] as $k => $v) {                 ?>
                    <tr class="hover edit">
                        <td nowrap><input type="checkbox" name="id[]" value="<?php echo $v['goods_commonid']; ?>"
                                   class="checkitem"></td>
                        <td nowrap><i class="icon-plus-sign" style="cursor: pointer;" nctype="ajaxGoodsList"
                               data-comminid="<?php echo $v['goods_commonid']; ?>"
                               title="点击展开查看此商品全部规格；规格值过多时请横向拖动区域内的滚动条进行浏览。"></i></td>
                        <td nowrap class="w60 align-center"><?php echo $v['goods_commonid']; ?></td>
<!--                        <td class="w60 picture">-->
<!--                            <div class="size-56x56"><span class="thumb size-56x56"><i></i><img-->
<!--                                        src="--><?php //echo thumb($v, 60); ?><!--" onload="javascript:DrawImage(this,56,56);"/></span>-->
<!--                            </div>-->
<!--                        </td>-->
                        <td class="goods-name w270"><p><span><?php echo $v['goods_name']; ?></span></p>
                        </td>
                        <td><?php echo $v['sDrug_Spec']; ?></td>
                        <td><?php echo $v['sDrug_Content']; ?></td>
                        <td><?php echo $v['sDrug_PackSpec']; ?></td>


                        <td><p><?php echo $v['brand_name']; ?></p>

                            <p><?php echo $v['gc_name']; ?></p></p>
                        </td>
                        <td><?php echo $v['sDrug_Unit']; ?></td>
                        <td><?php echo number_format($v['fDS_OStock'],0); ?></td>
                        <td><?php echo number_format($v['fDS_SStock'],0); ?></td>
                        <td><?php echo number_format($v['fDS_RetailPrice'],3); ?></td>
                        <td><?php echo number_format($v['fDS_BuyPrice'],3); ?></td>
                        <td><?php echo $v['sDrug_LeastUnit'] ?></td>
                        <td><?php echo number_format($v['fDS_LeastOStock'],3); ?></td>
                        <td><?php echo number_format($v['fDS_LeastSStock'],3); ?></td>
                        <td><?php echo number_format($v['fDS_LeastRetailPrice'],3); ?></td>
                        <td><?php echo number_format($v['fDS_LeastBuyPrice'],3); ?></td>
                        <td><?php echo number_format($v['fDS_RetailPrice']*$v['fDS_OStock']+$v['fDS_LeastRetailPrice']*$v['fDS_LeastOStock'],3); ?></td>
                        <td><?php echo number_format($v['fDS_BuyPrice']*$v['fDS_OStock']+$v['fDS_LeastBuyPrice']*$v['fDS_LeastOStock'],3); ?></td>
                        <td><?php echo number_format($v['fDS_RetailPrice']*$v['fDS_OStock']-$v['fDS_BuyPrice']*$v['fDS_OStock'] +
                                $v['fDS_LeastRetailPrice']*$v['fDS_LeastOStock'] - $v['fDS_LeastBuyPrice']*$v['fDS_LeastOStock']
                                ,3); ?></td>


                    </tr>
                    <tr style="display:none;">
                        <td colspan="21">
                            <div><input onchange="rowzeroallowchange(this)" type="checkbox" class="rowzeroallow" id="rowzeroallow_<?php echo $k;?>"/> <label for="rowzeroallow_<?php echo $k;?>">显示零库存</label> </div>
                            <div class="ncsc-goods-sku ps-container"></div>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr class="no_data">
                    <td colspan="22">无数据<?php echo $lang['nc_no_record']; ?></td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot class="tfoot">
            <?php if (!empty($output['goods_list']) && is_array($output['goods_list'])) { ?>
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
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/common_select.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/dialog/dialog.js" id="dialog_js"
        charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.mousewheel.js"></script>
<script type="text/javascript">
    var SITEURL = "<?php echo SHOP_SITE_URL; ?>";
    $(function () {
//        gcategoryInit("gcategory");
        $('#ncsubmit').click(function () {
//            $('input[name="op"]').val('stock');
            $('#formSearch').submit();
        });

        // 违规下架批量处理
        $('a[nctype="lockup_batch"]').click(function () {
            str = getId();
            if (str) {
                goods_lockup(str);
            }
        });

        // 批量删除
        $('a[nctype="del_batch"]').click(function () {
            if (confirm('<?php echo $lang['nc_ensure_del'];?>')) {
                ajaxpost('form_goods', '', '', 'onerror');
            }
        });

        // ajax获取商品列表
        $('i[nctype="ajaxGoodsList"]').parent().parent().toggle(
            function () {

                var obj = $(this).find('i[nctype="ajaxGoodsList"]');
                $(obj).removeClass('icon-plus-sign').addClass('icon-minus-sign');

                var _parenttr = $(obj).parents('tr');
//                _parenttr.next().show();return;
                var _commonid = $(obj).attr('data-comminid');
                var _div = _parenttr.next().find('.ncsc-goods-sku');
//                if (_div.html() == '') {
                var param = {commonid: _commonid};
                _div.html('');
                    if($(this).children("input.rowzeroallow").length>0){
                        if($(this).children("input.rowzeroallow")[0].prop("checked")=="true"){
                            param['zeroallow']='true';
                        }
                    }
                    $.getJSON('index.php?act=goods&op=get_goods_stock_ajax', param, function (data) {
                        console.trace(data);

                        if (data != 'false' && data.length>0) {
                            var vtable = $('<table class="table tb-type2 nobdb subdatatable"></table>');
                            var vtrhead = $('<tr class="thead"></tr>')
                            $('<th rowspan="2">机构</th>').appendTo(vtrhead);
                            $('<th rowspan="2">批号</th>').appendTo(vtrhead);
                            $('<th rowspan="2">有效期</th>').appendTo(vtrhead);
                            $('<th colspan="2">常规单位库存</th>').appendTo(vtrhead);
                            $('<th colspan="2">最小单位库存</th>').appendTo(vtrhead);
//                            $('<th rowspan="2">零价金额</th>').appendTo(vtrhead);
//                            $('<th rowspan="2">进价金额</th>').appendTo(vtrhead);
//                            $('<th rowspan="2">进销差</th>').appendTo(vtrhead);
                            var vtrhead1 = $('<tr class="thead"></tr>');
                            $('<th>可售</th>').appendTo(vtrhead1);
                            $('<th>实际</th>').appendTo(vtrhead1);
                            $('<th>可售</th>').appendTo(vtrhead1);
                            $('<th>实际</th>').appendTo(vtrhead1);
                            vtrhead.appendTo(vtable);
                            vtrhead1.appendTo(vtable);
                            $.each(data, function (i, o) {
                                $('<tr><td class="align-left">' + o.OrgName + '</td>'+
                                 '<td class="align-center">' + o.sBS_Batch + '</td>'+
                                '<td class="align-center">'  + (""+ o.dBS_UsefulLife).substr(0,10) + '</td>'+
                                '<td class="align-right">' + Math.round(o.fBS_OStock) + '</td>'+
                                '<td class="align-right">' + Math.round(o.fBS_SStock) + '</td>'+
                                '<td class="align-right">' + Math.round(o.fBS_LeastOStock) + '</td>'+
                                '<td class="align-right">' + Math.round(o.fBS_LeastSStock) + '</td>'+
//                                '<td class="align-right">￥' + Math.round((o.fBS_OStock * o.fBS_RetailPrice  + o.fBS_LeastOStock * o.fBS_LeastRetailPrice  )*1000)/1000 + '</td>'+
//                                '<td class="align-right">￥' + Math.round((o.fBS_OStock * o.fBS_BuyPrice +  o.fBS_LeastOStock * o.fBS_LeastBuyPrice)*1000)/1000 + '</td>'+
//                                '<td class="align-right">￥' + Math.round((o.fBS_OStock * o.fBS_RetailPrice - o.fBS_OStock * o.fBS_BuyPrice + o.fBS_LeastOStock * o.fBS_LeastRetailPrice - o.fBS_LeastOStock * o.fBS_LeastBuyPrice)*1000)/1000 + '</td>'+
                                    '</tr>').appendTo(vtable);
                            });
                            vtable.appendTo(_div);
                            _parenttr.next().show();
                            // 计算div的宽度
                            _div.css('width', document.body.clientWidth - 54);
//                            _div.perfectScrollbar();
                        }
                    });
//                } else {
//                    _parenttr.next().show()
//                }
            },
            function () {
                var obj = $(this).find('i[nctype="ajaxGoodsList"]');
                $(obj).removeClass('icon-minus-sign').addClass('icon-plus-sign');
                $(obj).parents('tr').next().hide();
            }
        );

    });

    function rowzeroallowchange ( src) {
        var obj = $(src).parent().parent().parent().prev().find('i[nctype="ajaxGoodsList"]');
        var _parenttr = $(src).parent().parent().parent().prev();
        var _commonid = $(obj).attr('data-comminid');
        var _div = _parenttr.next().find('.ncsc-goods-sku');
//                if (_div.html() == '') {
        var param = {commonid: _commonid};

        if($(src).prop("checked")==true){
            param['zeroallow']='true';
        }
        _div.html('');
        $.getJSON('index.php?act=goods&op=get_goods_stock_ajax', param, function (data) {
            console.trace(data);

            if (data != 'false' && data.length>0) {
                var vtable = $('<table class="table tb-type2 nobdb subdatatable"></table>');
                var vtrhead = $('<tr class="thead"></tr>')
                $('<th rowspan="2">机构</th>').appendTo(vtrhead);
                $('<th rowspan="2">批号</th>').appendTo(vtrhead);
                $('<th rowspan="2">有效期</th>').appendTo(vtrhead);
                $('<th colspan="2">常规单位库存</th>').appendTo(vtrhead);
                $('<th colspan="2">最小单位库存</th>').appendTo(vtrhead);
//                            $('<th rowspan="2">零价金额</th>').appendTo(vtrhead);
//                            $('<th rowspan="2">进价金额</th>').appendTo(vtrhead);
//                            $('<th rowspan="2">进销差</th>').appendTo(vtrhead);
                var vtrhead1 = $('<tr class="thead"></tr>');
                $('<th>可售</th>').appendTo(vtrhead1);
                $('<th>实际</th>').appendTo(vtrhead1);
                $('<th>可售</th>').appendTo(vtrhead1);
                $('<th>实际</th>').appendTo(vtrhead1);
                vtrhead.appendTo(vtable);
                vtrhead1.appendTo(vtable);
                $.each(data, function (i, o) {
                    $('<tr><td class="align-left">' + o.OrgName + '</td>'+
                    '<td class="align-center">' + o.sBS_Batch + '</td>'+
                    '<td class="align-center">'  + (""+ o.dBS_UsefulLife).substr(0,10) + '</td>'+
                    '<td class="align-right">' + Math.round(o.fBS_OStock) + '</td>'+
                    '<td class="align-right">' + Math.round(o.fBS_SStock) + '</td>'+
                    '<td class="align-right">' + Math.round(o.fBS_LeastOStock) + '</td>'+
                    '<td class="align-right">' + Math.round(o.fBS_LeastSStock) + '</td>'+
//                                '<td class="align-right">￥' + Math.round((o.fBS_OStock * o.fBS_RetailPrice  + o.fBS_LeastOStock * o.fBS_LeastRetailPrice  )*1000)/1000 + '</td>'+
//                                '<td class="align-right">￥' + Math.round((o.fBS_OStock * o.fBS_BuyPrice +  o.fBS_LeastOStock * o.fBS_LeastBuyPrice)*1000)/1000 + '</td>'+
//                                '<td class="align-right">￥' + Math.round((o.fBS_OStock * o.fBS_RetailPrice - o.fBS_OStock * o.fBS_BuyPrice + o.fBS_LeastOStock * o.fBS_LeastRetailPrice - o.fBS_LeastOStock * o.fBS_LeastBuyPrice)*1000)/1000 + '</td>'+
                    '</tr>').appendTo(vtable);
                });
                vtable.appendTo(_div);
                _parenttr.next().show();
                // 计算div的宽度
                _div.css('width', document.body.clientWidth - 54);
//                            _div.perfectScrollbar();
            }
        });
//                } else {
//                    _parenttr.next().show()
//                }
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

    // 商品下架
    function goods_lockup(ids) {
        _uri = "<?php echo ADMIN_SITE_URL;?>/index.php?act=goods&op=goods_lockup&id=" + ids;
        CUR_DIALOG = ajax_form('goods_lockup', '违规下架理由', _uri, 350);
    }
</script> 
