<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/hightchart/highcharts.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/hightchart/highcharts-3d.js"></script>
<style>
    .info-panel dl {
        width: 45%;
        float: left;
        margin: 30px 2% 0 0;
        border: solid 1px #DEEFFB;
        overflow: hidden;
    }

    table.listtable {
        width: 90%;
        margin: 5%;
    }

    table.listtable tr {
        height: 30px;
    }

    table.listtable th {
        font-weight: bold;
        font-size: 15px;
        background-color: #d3d3d3;
    }

    table.listtable th, table.listtable td {
        border: solid 1px #DEEFFB;
        line-height: 30px;
        height: 30px;
        text-align: center;
    }

    div.showdiv {
        width: 100%;
        height: 300px;
        overflow: auto;
    }

    span.spantable {
        display: table;
    }

    span.spantr {
        display: table-row;
    }

    span.spanth, span.spantd {
        display: table-cell;
    }
    table.detailtable{
        display: block;
    }
    table.detailtable th {
        font-weight: bold;
        font-size: 15px;
        text-align: center;
    }
    table.detailtable td,table.detailtable th{
        padding:5px;
    }

    table.detailtable td, table.detailtable th {
        border: solid 1px #DEEFFB;
    }

    tr.detailtr {
        cursor: pointer;
    }

    div.floatdiv {
        position: absolute;
        visibility: visible
    }

    span.floatspan {
        position: absolute;
        white-space: nowrap;
        font-family: 'Lucida Grande', 'Lucida Sans Unicode', Arial, Helvetica, sans-serif;
        font-size: 12px;
        color: rgb(51, 51, 51);
        margin-left: 0px;
        margin-top: 0px;
        left: 8px;
        top: 8px;
        z-index: 1;
    }

    body .ui-tooltip {
        border-width: 2px;
    }
    .ui-corner-all, .ui-corner-bottom, .ui-corner-right, .ui-corner-br {
        border-bottom-right-radius: 4px;
    }
    .ui-corner-all, .ui-corner-bottom, .ui-corner-left, .ui-corner-bl {
        border-bottom-left-radius: 4px;
    }
    .ui-corner-all, .ui-corner-top, .ui-corner-right, .ui-corner-tr {
        border-top-right-radius: 4px;
    }
    .ui-corner-all, .ui-corner-top, .ui-corner-left, .ui-corner-tl {
        border-top-left-radius: 4px;
    }
    /*.ui-widget-content {*/
        /*border: 1px solid #aaaaaa;*/
        /*color: #222222;*/
    /*}*/
    .ui-tooltip {
        padding: 8px;
        position: absolute;
        z-index: 9999;
        max-width: 300px;
        -webkit-box-shadow: 0 0 5px #aaa;
        box-shadow: 0 0 5px #aaa;
    }

    .ui-helper-hidden-accessible {
        border: 0;
        clip: rect(0 0 0 0);
        height: 1px;
        margin: -1px;
        overflow: hidden;
        padding: 0;
        position: absolute;
        width: 1px;
    }
    .mytooltip{
        z-index: 99999;
        opacity: 0.9;
        background-color: #fff;
    }
    td.head{
        text-align: left;
        text-indent: 20px;
    }
    .rightpanel{
        position: absolute;
        right:10px;
        /*width:300px;*/
        padding:5px;
    }
    .selectradio{
        display: none;
    }
    .selectradio + label{
        color:#008000;
    }

    .selectradio:checked + label{
        color:red;
    }
    .member{
        position: relative;
    }
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>系统总览</h3>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <div class="info-panel">
        <dl class="member">
            <dt>
            <div id="orgtabs-1" class="showdiv">

            </div>
            <div id="orgtabs-2" class="showdiv">

            </div>
            <div id="orgtabs-3" class="showdiv">
                <h1 style="text-align: center">社区建设</h1>
            </div>
            </dt>
            <dd>
                <ul>
                    <li class="w33pre none"><a href="#orgtabs-1">饼图</a></li>
                    <li class="w33pre none"><a href="#orgtabs-2">柱状图</a></li>
                    <li class="w34pre none"><a href="#orgtabs-3">列表</a></li>
                </ul>
            </dd>
        </dl>
        <dl class="member">
            <div class="rightpanel" text="sale">
                <input type="radio" class="selectradio" onclick="updatesale(this,2)" id="sale_id1" name="sale_id" value="1" checked><label for="sale_id1">全部</label>
                <input type="radio" class="selectradio" onclick="updatesale(this,2)" id="sale_id2" name="sale_id" value="2"><label for="sale_id2">今天</label>
                <input type="radio" class="selectradio" onclick="updatesale(this,2)" id="sale_id3" name="sale_id" value="3"><label for="sale_id3">本月</label>
                <input type="radio" class="selectradio" onclick="updatesale(this,2)" id="sale_id4" name="sale_id" value="4"><label for="sale_id4">本年</label>
                <input type="radio" class="selectradio" onclick="updatesale(this,2)" id="sale_id5" name="sale_id" value="5"><label for="sale_id5">昨天</label>
                <input type="radio" class="selectradio" onclick="updatesale(this,2)" id="sale_id6" name="sale_id" value="6"><label for="sale_id6">上月</label>
                <input type="radio" class="selectradio" onclick="updatesale(this,2)" id="sale_id7" name="sale_id" value="7"><label for="sale_id7">上年</label>
            </div>
            <dt>
            <div id="saletabs-1" class="showdiv">

            </div>
            <div id="saletabs-2" class="showdiv">

            </div>
            <div id="saletabs-3" class="showdiv">

            </div>
            </dt>
            <dd>
                <ul>
                    <li class="w33pre none"><a href="#saletabs-1">饼图</a></li>
                    <li class="w33pre none"><a href="#saletabs-2">柱状图</a></li>
                    <li class="w34pre none"><a href="#saletabs-3">列表</a></li>
                </ul>
            </dd>

        </dl>

        <dl class="member">
            <dt>
            <div id="membernumbertabs-1" class="showdiv">

            </div>
            <div id="membernumbertabs-2" class="showdiv">

            </div>
            <div id="membernumbertabs-3" class="showdiv">
                <h1 style="text-align: center">会员分布情况</h1>
            </div>
            </dt>
            <dd>
                <ul>
                    <li class="w33pre none"><a href="#membernumbertabs-1">饼图</a></li>
                    <li class="w33pre none"><a href="#membernumbertabs-2">柱状图</a></li>
                    <li class="w34pre none"><a href="#membernumbertabs-3">列表</a></li>
                </ul>
            </dd>
        </dl>

        <dl class="member">
            <div class="rightpanel" text="healthfile">
                <input type="radio" class="selectradio" onclick="updatehealthfile(this,0)" id="healthfile_id1" name="healthfile_id" value="1" checked><label for="healthfile_id1">全部</label>
                <input type="radio" class="selectradio" onclick="updatehealthfile(this,0)" id="healthfile_id2" name="healthfile_id" value="2"><label for="healthfile_id2">今天</label>
                <input type="radio" class="selectradio" onclick="updatehealthfile(this,0)" id="healthfile_id3" name="healthfile_id" value="3"><label for="healthfile_id3">本月</label>
                <input type="radio" class="selectradio" onclick="updatehealthfile(this,0)" id="healthfile_id4" name="healthfile_id" value="4"><label for="healthfile_id4">本年</label>
                <input type="radio" class="selectradio" onclick="updatehealthfile(this,0)" id="healthfile_id5" name="healthfile_id" value="5"><label for="healthfile_id5">昨天</label>
                <input type="radio" class="selectradio" onclick="updatehealthfile(this,0)" id="healthfile_id6" name="healthfile_id" value="6"><label for="healthfile_id6">上月</label>
                <input type="radio" class="selectradio" onclick="updatehealthfile(this,0)" id="healthfile_id7" name="healthfile_id" value="7"><label for="healthfile_id7">上年</label>
            </div>
            <dt>
            <div id="healthtabs-1" class="showdiv">

            </div>
            <div id="healthtabs-2" class="showdiv">

            </div>
            <div id="healthtabs-3" class="showdiv">

            </div>
            </dt>
            <dd>
                <ul>
                    <li class="w33pre none"><a href="#healthtabs-1">饼图</a></li>
                    <li class="w33pre none"><a href="#healthtabs-2">柱状图</a></li>
                    <li class="w34pre none"><a href="#healthtabs-3">列表</a></li>
                </ul>
            </dd>

        </dl>

        <dl class="member">
            <div class="rightpanel" text="member">
                <input type="radio" class="selectradio" onclick="updatemember(this,0)" id="member_id1" name="member_id" value="1" checked><label for="member_id1">全部</label>
                <input type="radio" class="selectradio" onclick="updatemember(this,0)" id="member_id2" name="member_id" value="2"><label for="member_id2">今天</label>
                <input type="radio" class="selectradio" onclick="updatemember(this,0)" id="member_id3" name="member_id" value="3"><label for="member_id3">本月</label>
                <input type="radio" class="selectradio" onclick="updatemember(this,0)" id="member_id4" name="member_id" value="4"><label for="member_id4">本年</label>
                <input type="radio" class="selectradio" onclick="updatemember(this,0)" id="member_id5" name="member_id" value="5"><label for="member_id5">昨天</label>
                <input type="radio" class="selectradio" onclick="updatemember(this,0)" id="member_id6" name="member_id" value="6"><label for="member_id6">上月</label>
                <input type="radio" class="selectradio" onclick="updatemember(this,0)" id="member_id7" name="member_id" value="7"><label for="member_id7">上年</label>
            </div>
            <dt>
            <div id="membertabs-1" class="showdiv">

            </div>
            <div id="membertabs-2" class="showdiv">

            </div>
            <div id="membertabs-3" class="showdiv">

            </div>
            </dt>
            <dd>
                <ul>
                    <li class="w33pre none"><a href="#membertabs-1">饼图</a></li>
                    <li class="w33pre none"><a href="#membertabs-2">柱状图</a></li>
                    <li class="w34pre none"><a href="#membertabs-3">列表</a></li>
                </ul>
            </dd>

        </dl>

        <dl class="member">
            <div class="rightpanel" text="healthfilespot">
                <input type="radio" class="selectradio" onclick="updatehealthspot(this,0)" id="healthspot_id1" name="healthspot_id" value="1" checked><label for="healthspot_id1">全部</label>
                <input type="radio" class="selectradio" onclick="updatehealthspot(this,0)" id="healthspot_id2" name="healthspot_id" value="2"><label for="healthspot_id2">今天</label>
                <input type="radio" class="selectradio" onclick="updatehealthspot(this,0)" id="healthspot_id3" name="healthspot_id" value="3"><label for="healthspot_id3">本月</label>
                <input type="radio" class="selectradio" onclick="updatehealthspot(this,0)" id="healthspot_id4" name="healthspot_id" value="4"><label for="healthspot_id4">本年</label>
                <input type="radio" class="selectradio" onclick="updatehealthspot(this,0)" id="healthspot_id5" name="healthspot_id" value="5"><label for="healthspot_id5">昨天</label>
                <input type="radio" class="selectradio" onclick="updatehealthspot(this,0)" id="healthspot_id6" name="healthspot_id" value="6"><label for="healthspot_id6">上月</label>
                <input type="radio" class="selectradio" onclick="updatehealthspot(this,0)" id="healthspot_id7" name="healthspot_id" value="7"><label for="healthspot_id7">上年</label>
            </div>
            <dt>
            <div id="healthspottabs-1" class="showdiv">

            </div>
            <div id="healthspottabs-2" class="showdiv">

            </div>
            <div id="healthspottabs-3" class="showdiv">

            </div>
            </dt>
            <dd>
                <ul>
                    <li class="w33pre none"><a href="#healthspottabs-1">饼图</a></li>
                    <li class="w33pre none"><a href="#healthspottabs-2">柱状图</a></li>
                    <li class="w34pre none"><a href="#healthspottabs-3">列表</a></li>
                </ul>
            </dd>

        </dl>

        <dl class="member">
            <div class="rightpanel" text="consume">
                <input type="radio" class="selectradio" onclick="updateconsume(this,2)" id="consume_id1" name="consume_id" value="1" checked><label for="consume_id1">全部</label>
                <input type="radio" class="selectradio" onclick="updateconsume(this,2)" id="consume_id2" name="consume_id" value="2"><label for="consume_id2">今天</label>
                <input type="radio" class="selectradio" onclick="updateconsume(this,2)" id="consume_id3" name="consume_id" value="3"><label for="consume_id3">本月</label>
                <input type="radio" class="selectradio" onclick="updateconsume(this,2)" id="consume_id4" name="consume_id" value="4"><label for="consume_id4">本年</label>
                <input type="radio" class="selectradio" onclick="updateconsume(this,2)" id="consume_id5" name="consume_id" value="5"><label for="consume_id5">昨天</label>
                <input type="radio" class="selectradio" onclick="updateconsume(this,2)" id="consume_id6" name="consume_id" value="6"><label for="consume_id6">上月</label>
                <input type="radio" class="selectradio" onclick="updateconsume(this,2)" id="consume_id7" name="consume_id" value="7"><label for="consume_id7">上年</label>
            </div>
            <dt>
            <div id="consumetabs-1" class="showdiv">

            </div>
            <div id="consumetabs-2" class="showdiv">

            </div>
            <div id="consumetabs-3" class="showdiv">

            </div>
            </dt>
            <dd>
                <ul>
                    <li class="w33pre none"><a href="#consumetabs-1">饼图</a></li>
                    <li class="w33pre none"><a href="#consumetabs-2">柱状图</a></li>
                    <li class="w34pre none"><a href="#consumetabs-3">列表</a></li>
                </ul>
            </dd>

        </dl>


        <dl class="member">
            <div class="rightpanel" text="healthbusiness">
                <input type="radio" class="selectradio" onclick="updatehealthbusiness(this,0)" id="healthbusiness_id1" name="healthbusiness_id" value="1" checked><label for="healthbusiness_id1">全部</label>
                <input type="radio" class="selectradio" onclick="updatehealthbusiness(this,0)" id="healthbusiness_id2" name="healthbusiness_id" value="2"><label for="healthbusiness_id2">今天</label>
                <input type="radio" class="selectradio" onclick="updatehealthbusiness(this,0)" id="healthbusiness_id3" name="healthbusiness_id" value="3"><label for="healthbusiness_id3">本月</label>
                <input type="radio" class="selectradio" onclick="updatehealthbusiness(this,0)" id="healthbusiness_id4" name="healthbusiness_id" value="4"><label for="healthbusiness_id4">本年</label>
                <input type="radio" class="selectradio" onclick="updatehealthbusiness(this,0)" id="healthbusiness_id5" name="healthbusiness_id" value="5"><label for="healthbusiness_id5">昨天</label>
                <input type="radio" class="selectradio" onclick="updatehealthbusiness(this,0)" id="healthbusiness_id6" name="healthbusiness_id" value="6"><label for="healthbusiness_id6">上月</label>
                <input type="radio" class="selectradio" onclick="updatehealthbusiness(this,0)" id="healthbusiness_id7" name="healthbusiness_id" value="7"><label for="healthbusiness_id7">上年</label>
            </div>
            <dt>
            <div id="healthbusinesstabs-1" class="showdiv">

            </div>
            <div id="healthbusinesstabs-2" class="showdiv">

            </div>
            <div id="healthbusinesstabs-3" class="showdiv">

            </div>
            </dt>
            <dd>
                <ul>
                    <li class="w33pre none"><a href="#healthbusinesstabs-1">饼图</a></li>
                    <li class="w33pre none"><a href="#healthbusinesstabs-2">柱状图</a></li>
                    <li class="w34pre none"><a href="#healthbusinesstabs-3">列表</a></li>
                </ul>
            </dd>

        </dl>


        <dl class="member">
            <div class="rightpanel" text="spot">
                <input type="radio" class="selectradio" onclick="updatespot(this,0)" id="spot_id1" name="spot_id" value="1" checked><label for="spot_id1">全部</label>
                <input type="radio" class="selectradio" onclick="updatespot(this,0)" id="spot_id2" name="spot_id" value="2"><label for="spot_id2">今天</label>
                <input type="radio" class="selectradio" onclick="updatespot(this,0)" id="spot_id3" name="spot_id" value="3"><label for="spot_id3">本月</label>
                <input type="radio" class="selectradio" onclick="updatespot(this,0)" id="spot_id4" name="spot_id" value="4"><label for="spot_id4">本年</label>
                <input type="radio" class="selectradio" onclick="updatespot(this,0)" id="spot_id5" name="spot_id" value="5"><label for="spot_id5">昨天</label>
                <input type="radio" class="selectradio" onclick="updatespot(this,0)" id="spot_id6" name="spot_id" value="6"><label for="spot_id6">上月</label>
                <input type="radio" class="selectradio" onclick="updatespot(this,0)" id="spot_id7" name="spot_id" value="7"><label for="spot_id7">上年</label>
            </div>
            <dt>
            <div id="spottabs-1" class="showdiv">

            </div>
            <div id="spottabs-2" class="showdiv">

            </div>
            <div id="spottabs-3" class="showdiv">

            </div>
            </dt>
            <dd>
                <ul>
                    <li class="w33pre none"><a href="#spottabs-1">饼图</a></li>
                    <li class="w33pre none"><a href="#spottabs-2">柱状图</a></li>
                    <li class="w34pre none"><a href="#spottabs-3">列表</a></li>
                </ul>
            </dd>

        </dl>





<!--        <dl class="member">-->
<!--            <dt>-->
<!--            <div id="atabs-1" class="showdiv">-->
<!---->
<!--            </div>-->
<!--            <div id="atabs-2" class="showdiv">-->
<!---->
<!--            </div>-->
<!--            <div id="atabs-3" class="showdiv">-->
<!---->
<!--            </div>-->
<!--            </dt>-->
<!--            <dd>-->
<!--                <ul>-->
<!--                    <li class="w33pre none"><a href="#atabs-1">饼图</a></li>-->
<!--                    <li class="w33pre none"><a href="#atabs-2">柱状图</a></li>-->
<!--                    <li class="w34pre none"><a href="#atabs-3">列表</a></li>-->
<!--                </ul>-->
<!--            </dd>-->
<!---->
<!--        </dl>-->
<!--        <dl class="member">-->
<!--            <dt>-->
<!--            <div id="btabs-1" class="showdiv">-->
<!---->
<!--            </div>-->
<!--            <div id="btabs-2" class="showdiv">-->
<!---->
<!--            </div>-->
<!--            <div id="btabs-3" class="showdiv">-->
<!---->
<!--            </div>-->
<!--            </dt>-->
<!--            <dd>-->
<!--                <ul>-->
<!--                    <li class="w33pre none"><a href="#btabs-1">饼图</a></li>-->
<!--                    <li class="w33pre none"><a href="#btabs-2">柱状图</a></li>-->
<!--                    <li class="w34pre none"><a href="#btabs-3">列表</a></li>-->
<!--                </ul>-->
<!--            </dd>-->
<!---->
<!--        </dl>-->

        <div class=" clear"></div>
    </div>
</div>
<div class="highcharts-tooltip floatdiv" style="display:none;">
    <span class="floatspan">

    </span>
</div>
<script type="text/javascript">
    var normal = ['week_add_member', 'week_add_product'];
    var work = ['store_joinin', 'store_expired', 'store_expire', 'brand_apply', 'cashlist', 'groupbuy_verify_list', 'points_order', 'complain_new_list', 'complain_handle_list', 'product_verify', 'inform_list', 'refund', 'return', 'cms_article_verify', 'cms_picture_verify', 'circle_verify', 'check_billno', 'pay_billno'];
    var colorarray=[
        [ '#90ed7d', '#f7a35c', '#8085e9',     '#f15c80', '#e4d354', '#8085e8', '#8d4653', '#91e8e1','#7cb5ec', '#434348'],
         ['#2f7ed8', '#0d233a', '#8bbc21', '#910000', '#1aadce', '#492970', '#f28f43', '#77a1e5', '#c42525', '#a6c96a'],
             ['#7cb5ec', '#434348', '#90ed7d', '#f7a35c', '#8085e9',     '#f15c80', '#e4d354', '#8085e8', '#8d4653', '#91e8e1']
    ];
    var charts = {};
    $(document).ready(function () {
        $.getJSON("index.php?act=dashboard&op=chart", function (data) {
            console.log(data);
            //初始化图表
            var orgchartcfg = getData(data['orgdata'], 'name', 'num', ['机构编码', '机构名称'], '共?家机构');
            initchart(orgchartcfg, '社区建设', '社区数量', '行政区划', 'orgtabs', '共?家机构',{ formatter: showhtml,useHTML: true},2,0);

            var salechartcfg = getData(data['saledata'], 'name', 'num', ['机构编码', '机构名称'], '总金额为￥{point.y}元');
            initchart(salechartcfg, '收入汇总', '金额', '社区', 'saletabs', getmoney,{ pointFormat: '总金额为￥{point.y}元',useHTML: true},0,2);

            var memberchartcfg = getData(data['memberdata'], 'name', 'num', ['机构编码', '机构名称'], '总充值金额为￥{point.y}元');
            initchart(memberchartcfg, '会员充值情况', '充值金额', '社区', 'membertabs', getincome,{ pointFormat: '总充值金额为￥{point.y}元',useHTML: true},0,2);

            var consumechartcfg = getData(data['consumedata'], 'name', 'num', ['机构编码', '机构名称'], '总消费金额为￥{point.y}元');
            initchart(consumechartcfg, '会员消费情况', '消费金额', '社区', 'consumetabs', getconsume,{ pointFormat: '总消费金额为￥{point.y}元',useHTML: true},0,2);
//            var salechartcfg = getData(data['saledata'], 'name', 'num');
//            initchart(salechartcfg,'消费情况','消费金额','社区','saletabs');
            //初始化标签
            var healthfilechartcfg = getData(data['healthdata'], 'name', 'num', ['机构编码', '机构名称'], '总档案数为{point.y}份');
            initchart(healthfilechartcfg, '健康档案', '档案数量', '社区', 'healthtabs', gethealthfile,{ pointFormat: '总档案数为{point.y}份',useHTML: true},0,0);

            var spotchartcfg = getData(data['spotdata'], 'name', 'num', ['机构编码', '机构名称'], '总回访数为{point.y}次');
            initchart(spotchartcfg, '会员回访情况', '回访数', '社区', 'spottabs', getspot,{ pointFormat: '总回访数为{point.y}次',useHTML: true},0,0);

            var healthspotchartcfg = getData(data['healthspotdata'], 'name', 'num', ['机构编码', '机构名称'], '总回访数为{point.y}次');
            initchart(healthspotchartcfg, '档案回访情况', '回访数', '社区', 'healthspottabs', getspot,{ pointFormat: '总回访数为{point.y}次',useHTML: true},0,0);

            var healthbusinessdata = getData(data['healthbusinessdata'], 'name', 'num', ['机构编码', '机构名称'], '总业务数为{point.y}次');
            initchart(healthbusinessdata, '业务开展情况', '业务数量', '社区', 'healthbusinesstabs', gethealthbusiness,{ pointFormat: '总业务为{point.y}次',useHTML: true},0,0);

            var membernumber = getData(data['membernumber'], 'name', 'num', ['机构编码', '机构名称'], '总会员数为{point.y}');
            initchart(membernumber, '会员分布情况', '数量', '社区', 'membernumbertabs', gethealthbusiness,{ pointFormat: '总会员数为{point.y}',useHTML: true},0,0);

            $(".member").tabs();
            $(".detailtr").tooltip({content:gettext,track :true,tooltipClass:'mytooltip'});
        });

    });
    function test() {
        return 'aaaa';
    }
    function initchart(orgchartcfg, titletext, numtext, ytext, tabname, counttext,tooltip,colorindex ,dot) {
        console.log(orgchartcfg);
        console.log(counttext);
        var colors = [];
        if(colorindex>=0 && colorarray[colorindex]){
            colors = colorarray[colorindex]
        }else{
            colors = colorarray[2];
        }
        console.log(colorindex);
        console.log(colors);
        //初始化饼图
        charts[titletext+'_1'] = new Highcharts.Chart({
            chart: {
                renderTo: tabname + '-1',
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45,
                    beta: 0
                }
            },
            colors:colors,
            title: {
                text: titletext
            },
            tooltip: tooltip,

            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    depth: 35,
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}'
                    }
                }
            },
            series: [{
                type: 'pie',
                name: titletext,
                data: orgchartcfg.piedata
            }]
        });
        //初始化柱状图
        charts[titletext+'_2'] = new Highcharts.Chart({
            chart: {
                renderTo: tabname + '-2',
                type: 'column',
                margin: 75,
                options3d: {
                    enabled: true,
                    alpha: 15,
                    beta: 15,
                    depth: 50,
                    viewDistance: 25
                }
            },
            title: {
                text: titletext
            },
            tooltip: tooltip,
            plotOptions: {
                column: {
                    depth: 25
                }
            },
            xAxis: {
                categories: orgchartcfg.colcat
            },
            yAxis: {
                opposite: true,
                labels: {
                    rotation: 0,
                    step: 0
                },
                title: {
                    text: numtext,
                    rotation: 0
                }
            },
            series: [{
                name: ytext,
                data: orgchartcfg.piedata
            }]
        });
        //初始化列表
        var html = '';
        var sum = 0;
        for (var i = 0; i < orgchartcfg.piedata.length; i++) {
            console.log(orgchartcfg.piedata[i]);
            var txt = '';
            console.log(typeof(counttext));
            if(typeof(counttext) =='function'){
                txt = counttext(orgchartcfg.piedata[i]);
            }else{
                txt = counttext;
            }
            html += '<tr class="detailtr" title="" counttext="'+txt+'" count="'+orgchartcfg.piedata[i].details.length+'" text=\''+escape(orgchartcfg.piedata[i].detailhtml)+'\'>' +
            '<td class="head" >' + orgchartcfg.piedata[i].name + '</td>' +
            '<td>' + orgchartcfg.piedata[i].y + '</td></tr>';
            sum +=orgchartcfg.piedata[i].y;
        }
        html+= '<tr><td class="head" style="font-weight: bold;font-size: 16px;" >合计:</td><td>' + sum.toFixed(dot) + '</td></tr>';
        html = '<span style="text-align: center;font-size:18px;margin-top: 30px;">' + titletext + '</span><table class="listtable"><tr><th>' + ytext + '</th><th>' + numtext + '</th></tr>' + html + '</table>';
//        $( document ).tooltip();

        $("#" + tabname + "-3").html(html);
    }

    function updatesale(obj,dot){
        var opt = $(obj).parent().attr("text");
        var type = $(obj).val();
        $.getJSON("index.php?act=dashboard&op=chartdetail",{'opt':opt,'type':type}, function (data) {
            console.log(data);
            var salechartcfg = getData(data, 'name', 'num', ['机构编码', '机构名称'], '总金额为￥{point.y}元');
            updatechart(salechartcfg, '收入汇总', '金额', '社区', 'saletabs', getmoney,{ pointFormat: '总金额为￥{point.y}元',useHTML: true},0,dot);

        });
    }
    function updatemember(obj,dot){
        var opt = $(obj).parent().attr("text");
        var type = $(obj).val();
        $.getJSON("index.php?act=dashboard&op=chartdetail",{'opt':opt,'type':type}, function (data) {
            console.log(data);
            var memberchartcfg = getData(data, 'name', 'num', ['机构编码', '机构名称'], '总充值金额为￥{point.y}元');
            updatechart(memberchartcfg, '会员充值情况', '充值金额', '社区', 'membertabs', getincome,{ pointFormat: '总充值金额为￥{point.y}元',useHTML: true},0,dot);
        });
    }

     function updateconsume(obj,dot){
        var opt = $(obj).parent().attr("text");
        var type = $(obj).val();
        $.getJSON("index.php?act=dashboard&op=chartdetail",{'opt':opt,'type':type}, function (data) {
            console.log(data);
            var consumechartcfg = getData(data, 'name', 'num', ['机构编码', '机构名称'], '总消费金额为￥{point.y}元');
            updatechart(consumechartcfg, '会员消费情况', '消费金额', '社区', 'consumetabs', getconsume,{ pointFormat: '总消费金额为￥{point.y}元',useHTML: true},0,dot);
        });
    }
    function updatehealthfile(obj,dot){
        var opt = $(obj).parent().attr("text");
        var type = $(obj).val();
        $.getJSON("index.php?act=dashboard&op=chartdetail",{'opt':opt,'type':type}, function (data) {
            console.log(data);
            var healthfilechartcfg = getData(data, 'name', 'num', ['机构编码', '机构名称'], '总档案数为{point.y}份');
            updatechart(healthfilechartcfg, '健康档案', '档案数量', '社区', 'healthtabs', gethealthfile,{ pointFormat: '总档案数为{point.y}份',useHTML: true},0,dot);
        });
    }
    function updatespot(obj,dot){
        var opt = $(obj).parent().attr("text");
        var type = $(obj).val();
        $.getJSON("index.php?act=dashboard&op=chartdetail",{'opt':opt,'type':type}, function (data) {
            console.log(data);
            var spotchartcfg = getData(data,  'name', 'num', ['机构编码', '机构名称'], '总回访数为{point.y}次');
            updatechart(spotchartcfg, '会员回访情况', '回访数', '社区', 'spottabs', getspot,{ pointFormat: '总回访数为{point.y}次',useHTML: true},0,dot);
        });
    }

    function updatehealthspot(obj,dot){
        var opt = $(obj).parent().attr("text");
        var type = $(obj).val();
        $.getJSON("index.php?act=dashboard&op=chartdetail",{'opt':opt,'type':type}, function (data) {
            console.log(data);
            var healthspotchartcfg = getData(data, 'name', 'num', ['机构编码', '机构名称'], '总回访数为{point.y}次');
            updatechart(healthspotchartcfg, '档案回访情况', '回访数', '社区', 'healthspottabs', getspot,{ pointFormat: '总回访数为{point.y}次',useHTML: true},0,dot);
        });
    }

    function updatehealthbusiness(obj,dot){
        var opt = $(obj).parent().attr("text");
        var type = $(obj).val();
        $.getJSON("index.php?act=dashboard&op=chartdetail",{'opt':opt,'type':type}, function (data) {
            console.log(data);
            var healthbusinessdata = getData(data, 'name', 'num', ['机构编码', '机构名称'], '总业务数为{point.y}次');
            updatechart(healthbusinessdata, '业务开展情况', '业务数量', '社区', 'healthbusinesstabs', gethealthbusiness,{ pointFormat: '总业务为{point.y}次',useHTML: true},0,dot);
        });
    }

    function updatechart(data, titletext, numtext, ytext, tabname, counttext,tooltip,colorindex,dot) {
        //更新饼图
        charts[titletext+'_1'].series[0].setData(data.piedata);
        //更新柱状图
        charts[titletext+'_2'].series[0].setData(data.piedata);
        //初更新列表
        var html = '';
        var sum = 0;
        var piedata = data.piedata;
        for (var i = 0; i < piedata.length; i++) {
            console.log(piedata[i]);
            var txt = '';
            console.log(typeof(counttext));
            if(typeof(counttext) =='function'){
                txt = counttext(piedata[i]);
            }else{
                txt = counttext;
            }
            html += '<tr class="detailtr" title="" counttext="'+txt+'" count="'+piedata[i].details.length+'" text=\''+escape(piedata[i].detailhtml)+'\'>' +
            '<td class="head" >' + piedata[i].name + '</td>' +
            '<td>' + piedata[i].y + '</td></tr>';
            sum +=piedata[i].y;
        }
        html+= '<tr><td class="head" style="font-weight: bold;font-size: 16px;" >合计:</td><td>' + sum.toFixed(dot) + '</td></tr>';
        html = '<span style="text-align: center;font-size:18px;margin-top: 30px;">' + titletext + '</span><table class="listtable"><tr><th>' + ytext + '</th><th>' + numtext + '</th></tr>' + html + '</table>';
        $( document ).tooltip();
        $("#" + tabname + "-3").html(html);
    }

    function gettotal(data){
        console.log(data);
        return data;
    }
    function getmoney(data){
        return '总销售金额为￥'+data.y+"元";
    }

    function getincome(data){
        return '总充值金额为￥'+data.y+"元";
    }

    function getconsume(data){
        return '总消费金额为￥'+data.y+"元";
    }
    function gethealthfile(data){
        return '总档案数为'+data.y+"份";
    }
    function getspot(data){
        return '总回访数为'+data.y+"次";
    }
    function gethealthbusiness(data){
        return '总业务数为'+data.y+"次";
    }

    function gettext(obj){
        var counttext = $(this).attr("counttext").replace(/\?/, $(this).attr("count"));
        return '<b>' + $(this).children(":first-child").text()+ '</b>&nbsp;&nbsp;' + counttext +unescape($(this).attr("text"));
    }
    function getData(data, catname, numname, detailtitles,counttext) {
        var ret = {colcat: [], coldata: [], piedata: []};
        for (var i = 0; i < data.length; i++) {
            console.log(data[i]);
            if(data[i][catname]!='总计:'){
                ret.colcat.push(data[i][catname]);
                ret.coldata.push(parseFloat(data[i][numname]));
                var html = '';
                var count = 0;
                if (data[i]['details'] && data[i]['details'].length && data[i]['details'].length > 0) {
                    html = '<table class="detailtable"><tr>';
                    for (var j = 0; j < detailtitles.length; j++) {
                        html += '<th nowrap>' + detailtitles[j] + '</th>';
                    }
                    html += '</tr>';
                    for (var j = 0; j < data[i]['details'].length; j++) {
                        count++;
                        var item = data[i]['details'][j];
                        html += '<tr>';
                        for (var m = 0; m < item.length; m++) {
                            html += '<td >' + item[m] + '</td>';
                        }
                        html += '</tr>';
                    }
                    html += '</table>';
                };
                num = parseFloat(data[i][numname]);
                if (num<0){
                    num = 0 ;
                }
                ret.piedata.push({
                    name: data[i][catname],
                    counttext: counttext,
                    y: parseFloat(data[i][numname]),
                    details: data[i]['details'],
                    detailhtml: html
                })
            }
        }
        return ret;
    }
    function showhtml() {
        var counttext = this.point.counttext.replace(/\?/, this.point.details.length);
        return '<b>' + this.key + '</b>&nbsp;&nbsp;' + counttext + this.point.detailhtml;
    }
    function showtablehtml(html) {
        $("div.floatdiv>span.floatspan").html(html);
        $("div.floatdiv").show();
    }
</script>
<!--
<script type="text/javascript" charset="utf-8" src="http://www.szgr.com.cn/update/update2014.js"></script>-->
