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
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>系统概览</h3>
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
    $(document).ready(function () {
        $.getJSON("index.php?act=dashboard&op=chart", function (data) {
            console.log(data);
            //初始化图表
            var orgchartcfg = getData(data['orgdata'], 'name', 'num', ['机构编码', '机构名称'], '共?家机构');
            initchart(orgchartcfg, '社区建设', '社区数量', '行政区划', 'orgtabs', '共?家机构',{ formatter: showhtml,useHTML: true});

            var salechartcfg = getData(data['saledata'], 'name', 'num', ['机构编码', '机构名称'], '总销售金额为￥{point.y}元');
            initchart(salechartcfg, '销售情况', '销售金额', '社区', 'saletabs', getmoney,{ pointFormat: '总销售金额为￥{point.y}元',useHTML: true});

//            var salechartcfg = getData(data['saledata'], 'name', 'num');
//            initchart(salechartcfg,'消费情况','消费金额','社区','saletabs');
            //初始化标签
            $(".member").tabs();
            $(".detailtr").tooltip({content:gettext,track :true,tooltipClass:'mytooltip'});
//            $(".detailtr").tooltip("open");
        });

    });
    function test() {
        return 'aaaa';
    }
    function initchart(orgchartcfg, titletext, numtext, ytext, tabname, counttext,tooltip) {
        console.log(orgchartcfg);
        console.log(counttext);
        //初始化饼图
        var orgchart_1 = new Highcharts.Chart({
            chart: {
                renderTo: tabname + '-1',
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45,
                    beta: 0
                }
            },

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
        var orgchart_2 = new Highcharts.Chart({
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
        for (var i = 0; i < orgchartcfg.piedata.length; i++) {
            console.log(orgchartcfg.piedata[i]);
            var txt = '';
            console.log(typeof(counttext));
            if(typeof(counttext) =='function'){
                txt = counttext(orgchartcfg.piedata[i]);
            }else{
                txt = counttext;
            }
            html += '<tr class="detailtr" title="" counttext="'+txt+'" count="'+orgchartcfg.piedata[i].details.length+'" text=\''+escape(orgchartcfg.piedata[i].detailhtml)+'\'><td class="head" >' + orgchartcfg.piedata[i].name + '</td><td>' + orgchartcfg.piedata[i].y + '</td></td>';
        }
        html = '<span style="text-align: center;font-size:18px;margin-top: 30px;">' + titletext + '</span><table class="listtable"><tr><th>' + ytext + '</th><th>' + numtext + '</th></tr>' + html + '</table>';
//        $( document ).tooltip();

        $("#" + tabname + "-3").html(html);
    }

    function getmoney(data){
        return '总销售金额为￥'+data.y+"元";
    }

    function gettext(obj){
        var counttext = $(this).attr("counttext").replace(/\?/, $(this).attr("count"));
        return '<b>' + $(this).children(":first-child").text()+ '</b>&nbsp;&nbsp;' + counttext +unescape($(this).attr("text"));
    }
    function getData(data, catname, numname, detailtitles,counttext) {
        var ret = {colcat: [], coldata: [], piedata: []};
        for (var i = 0; i < data.length; i++) {
            console.log(data[i]);
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
            }
            ;
            ret.piedata.push({
                name: data[i][catname],
                counttext: counttext,
                y: parseFloat(data[i][numname]),
                details: data[i]['details'],
                detailhtml: html
            })
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
