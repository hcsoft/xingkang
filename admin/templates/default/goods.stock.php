<?php defined('InShopNC') or exit('Access Invalid!'); ?>
<link href="<?php echo ADMIN_TEMPLATES_URL; ?>/css/font/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
<!--[if IE 7]>
<link rel="stylesheet" href="<?php echo ADMIN_TEMPLATES_URL;?>/css/font/font-awesome/css/font-awesome-ie7.min.css">
<![endif]-->
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
                <th><label for="search_goods_name"> <?php echo $lang['goods_index_name']; ?></label></th>
                <td><input type="text" value="<?php echo $output['search']['search_goods_name']; ?>"
                           name="search_goods_name" id="search_goods_name" class="txt"></td>
                <th><label for="search_commonid">商品编码</label></th>
                <td><input type="text" value="<?php echo $output['search']['search_commonid'] ?>" name="search_commonid"
                           id="search_commonid" class="txt"/></td>
                <th><label><?php echo $lang['goods_index_class_name']; ?></label></th>
                <td id="gcategory" colspan="8"><input type="hidden" id="cate_id" name="cate_id" value=""
                                                      class="mls_id"/>
                    <input type="hidden" id="cate_name" name="cate_name" value="" class="mls_names"/>
                    <select class="querySelect">
                        <option><?php echo $lang['nc_please_choose']; ?>...</option>
                        <?php if (!empty($output['goods_class']) && is_array($output['goods_class'])) { ?>
                            <?php foreach ($output['goods_class'] as $val) { ?>
                                <option value="<?php echo $val['gc_id']; ?>"
                                        <?php if ($output['search']['cate_id'] == $val['gc_id']){ ?>selected<?php } ?>><?php echo $val['gc_name']; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select></td>

            </tr>
            <tr>
                <th><label><?php echo $lang['goods_index_brand']; ?></label></th>
                <td><select name="search_brand_id">
                        <option value=""><?php echo $lang['nc_please_choose']; ?>...</option>
                        <?php if (!empty($output['brand_list']) && is_array($output['brand_list'])) { ?>
                            <?php foreach ($output['brand_list'] as $k => $v) { ?>
                                <option value="<?php echo $v['brand_id']; ?>"
                                        <?php if ($output['search']['search_brand_id'] == $v['brand_id']){ ?>selected<?php } ?>><?php echo $v['brand_name']; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select></td>
                <th><label><?php echo $lang['goods_index_show']; ?></label></th>
                <td><select name="search_state">
                        <option value=""><?php echo $lang['nc_please_choose']; ?>...</option>
                        <?php foreach ($output['state'] as $key => $val) { ?>
                            <option value="<?php echo $key; ?>"
                                    <?php if ($output['search']['search_state'] != '' && $output['search']['search_state'] == $key){ ?>selected<?php } ?>><?php echo $val; ?></option>
                        <?php } ?>
                    </select></td>
                <th><label>等待审核</label></th>
                <td><select name="search_verify">
                        <option value=""><?php echo $lang['nc_please_choose']; ?>...</option>
                        <?php foreach ($output['verify'] as $key => $val) { ?>
                            <option value="<?php echo $key; ?>"
                                    <?php if ($output['search']['search_verify'] != '' && $output['search']['search_verify'] == $key){ ?>selected<?php } ?>><?php echo $val; ?></option>
                        <?php } ?>
                    </select></td>
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
        <table class="table tb-type2">
            <thead>
            <tr class="thead">
                <th class="w24"></th>
                <th class="w24"></th>
                <th class="align-center">商品编码</th>
                <th colspan="2"><?php echo $lang['goods_index_name']; ?></th>
                <th class="align-center">规格</th>
                <th class="align-center">单位</th>
                <th>厂家场地</th>
                <th class="align-center">价格</th>
                <th class="align-center" colspan="2">库存</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($output['goods_list']) && is_array($output['goods_list'])) {
                ?>
                <?php foreach ($output['goods_list'] as $k => $v) {                 ?>
                    <tr class="hover edit">
                        <td><input type="checkbox" name="id[]" value="<?php echo $v['goods_commonid']; ?>"
                                   class="checkitem"></td>
                        <td><i class="icon-plus-sign" style="cursor: pointer;" nctype="ajaxGoodsList"
                               data-comminid="<?php echo $v['goods_commonid']; ?>"
                               title="点击展开查看此商品全部规格；规格值过多时请横向拖动区域内的滚动条进行浏览。"></i></td>
                        <td class="w60 align-center"><?php echo $v['goods_commonid']; ?></td>
                        <td class="w60 picture">
                            <div class="size-56x56"><span class="thumb size-56x56"><i></i><img
                                        src="<?php echo thumb($v, 60); ?>" onload="javascript:DrawImage(this,56,56);"/></span>
                            </div>
                        </td>
                        <td class="goods-name w270"><p><span><?php echo $v['goods_name']; ?></span></p>
                        </td>
                        <td><p>完整规格: <span style="color:blue;"><?php echo $v['sDrug_Spec']; ?></span></p>

                            <p>含量规格: <span style="color:blue;"><?php echo $v['sDrug_Content']; ?></span></p>

                            <p>包装规格: <span style="color:blue;"><?php echo $v['sDrug_PackSpec']; ?></span></p></td>
                        <td><p>常规单位: <span style="color:blue;"><?php echo $v['sDrug_Unit']; ?></span></p>

                            <p>最小单位: <span style="color:blue;"><?php echo $v['sDrug_LeastUnit']; ?></span></p></td>
                        <td><p><?php echo $v['brand_name']; ?></p>

                            <p><?php echo $v['gc_name']; ?></p></p>
                        </td>
                        <td class="align-center"><?php echo $lang['currency'] . number_format($v['goods_price'], 2) ?></td>
                        <td class="align-center"><p>可售库存：<span style="color:blue;"><span style="color:blue;"><?php echo number_format($v['fDS_AdjustNum'],0); ?></span></p>
                            <p>实际库存：<span style="color:blue;"><?php echo number_format($v['fDS_LeastAdjustNum'],0); ?></span></p>
                        </td>
                        <td class="align-center"><p>零价金额：<span style="color:blue;"><?php echo number_format($v['fDS_LeastRetailPrice'],3); ?></span></p>
                            <p>进价金额：<span style="color:blue;"><?php echo number_format($v['fDS_BuyPrice'],3); ?></span></p>
                        </td>
                        <td class="align-center"><p>进销差：<span style="color:blue;"><?php echo number_format($v['fDS_LeastSStock'],0); ?></span></p>
                        </td>

                    </tr>
                    <tr style="display:none;">
                        <td colspan="20">
                            <div class="ncsc-goods-sku ps-container"></div>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr class="no_data">
                    <td colspan="15">无数据<?php echo $lang['nc_no_record']; ?></td>
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
        gcategoryInit("gcategory");
        $('#ncsubmit').click(function () {
            $('input[name="op"]').val('stock');
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
        $('i[nctype="ajaxGoodsList"]').toggle(
            function () {
                $(this).removeClass('icon-plus-sign').addClass('icon-minus-sign');
                var _parenttr = $(this).parents('tr');
                var _commonid = $(this).attr('data-comminid');
                var _div = _parenttr.next().find('.ncsc-goods-sku');
                if (_div.html() == '') {
                    $.getJSON('index.php?act=goods&op=get_goods_stock_ajax', {commonid: _commonid}, function (data) {
                        console.trace(data);
                        if (data != 'false') {
                            var _ul = $('<ul class="ncsc-goods-sku-list"></ul>');
                            $.each(data, function (i, o) {
                                $('<li style="width:200px;"><div style="" title="库房：' + o.DepotName + '"><p>库房：<span style="color:blue;">' + o.DepotName + '</span></p>'+
                                    '<p>批号：<span style="color:blue;"><em title="￥' + o.sBS_Batch + '">' + o.sBS_Batch + '</em></span></div>'+
                                    '<p>有效期至：<span style="color:blue;"><em title="￥' + o.dBS_UsefulLife + '">' + (""+ o.dBS_UsefulLife).substr(0,10) + '</em></span></div>'+
                                    '<p>库存进价：<span style="color:blue;"><em title="￥' + o.fBS_BuyPrice + '">￥' + Math.round(o.fBS_BuyPrice*1000)/1000 + '</em></span></div>'+
                                    '<p>库存零价：<span style="color:blue;"><em title="￥' + o.fBS_LeastBuyPrice + '">￥' + Math.round(o.fBS_LeastBuyPrice*1000)/1000 + '</em></span></div>'+
                                    '<p>库存数量：<span style="color:blue;"><em title="' + o.fBS_SStock + '">' + Math.round(o.fBS_SStock) + '</em></span></div>'+
                                    '<p>零价金额：<span style="color:blue;"><em title="￥' + o.fBS_RetailPrice + '">￥' + Math.round(o.fBS_RetailPrice*1000)/1000 + '</em></span></div>'+
                                    '<p>进价金额：<span style="color:blue;"><em title="￥' + o.fBS_RetailPrice + '">￥' + Math.round(o.fBS_RetailPrice*1000)/1000 + '</em></span></div>'+
                                    '<p>进销差：<span style="color:blue;"><em title="￥' + o.fBS_LeastSStock + '">' + Math.round(o.fBS_LeastSStock) + '</em></span></div>'+
                                    '</li>').appendTo(_ul);
                            });
                            _ul.appendTo(_div);
                            _parenttr.next().show();
                            // 计算div的宽度
                            _div.css('width', document.body.clientWidth - 54);
//                            _div.perfectScrollbar();
                        }
                    });
                } else {
                    _parenttr.next().show()
                }
            },
            function () {
                $(this).removeClass('icon-minus-sign').addClass('icon-plus-sign');
                $(this).parents('tr').next().hide();
            }
        );
    });

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
