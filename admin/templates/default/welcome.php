<!DOCTYPE html>
<!--
BeyondAdmin - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.2.0
Version: 1.0.0
Purchase: http://wrapbootstrap.com
-->

<html xmlns="http://www.w3.org/1999/xhtml">
<!-- Head -->
<head>
    <meta charset="utf-8"/>
    <title>仪表板</title>

    <meta name="description" content="Dashboard"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="shortcut icon" href="<?php echo RESOURCE_SITE_URL; ?>/bootstrap/img/favicon.png" type="image/x-icon">


    <!--Basic Styles-->
    <link href="<?php echo RESOURCE_SITE_URL; ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
    <link id="bootstrap-rtl-link" href="" rel="stylesheet"/>
    <link href="<?php echo RESOURCE_SITE_URL; ?>/bootstrap/css/font-awesome.min.css" rel="stylesheet"/>
    <link href="<?php echo RESOURCE_SITE_URL; ?>/bootstrap/css/weather-icons.min.css" rel="stylesheet"/>

    <!--Fonts-->
    <link href="http://fonts.useso.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300"
          rel="stylesheet" type="text/css">
    <link href='http://fonts.useso.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
    <!--Beyond styles-->
    <link id="beyond-link" href="<?php echo RESOURCE_SITE_URL; ?>/bootstrap/css/beyond.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="<?php echo RESOURCE_SITE_URL; ?>/bootstrap/css/demo.min.css" rel="stylesheet"/>
    <link href="<?php echo RESOURCE_SITE_URL; ?>/bootstrap/css/typicons.min.css" rel="stylesheet"/>
    <link href="<?php echo RESOURCE_SITE_URL; ?>/bootstrap/css/animate.min.css" rel="stylesheet"/>
    <link id="skin-link" href="" rel="stylesheet" type="text/css"/>

    <!--Skin Script: Place this script in head to load scripts for skins and rtl support-->
    <script src="<?php echo RESOURCE_SITE_URL; ?>/bootstrap/js/skins.js"></script>
    <style>
        body:before {
            background-color: #EEF9FE;
        }
    </style>
</head>
<!-- /Head -->
<!-- Body -->

<body>
<!-- Loading Container -->
<div class="loading-container">
    <div class="loader"></div>
</div>
<!--  /Loading Container -->
<!-- Navbar -->

<!-- Main Container -->
<div class="main-container container-fluid" style="padding:10px;">
    <!-- Page Container -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="databox bg-white radius-bordered">
                        <div class="databox-left bg-themesecondary">
                            <div class="databox-piechart">
                                <div data-toggle="easypiechart" class="easyPieChart" data-barcolor="#fff"
                                     data-linecap="butt" data-percent="50" data-animate="500" data-linewidth="3"
                                     data-size="47" data-trackcolor="rgba(255,255,255,0.1)"><span class="white font-90">50%</span>
                                </div>
                            </div>
                        </div>
                        <div class="databox-right">
                            <span class="databox-title themesecondary">档案</span>
                            <div class="databox-text darkgray">
                                新增数<span id="file_new" class="databox-number themesecondary " style="display: inline-block"> 0</span>
                                总数<span id="file_count" class="databox-number themesecondary" style="display: inline-block"> 0</span>
                            </div>
                            <div class="databox-stat themesecondary radius-bordered">
                                <i class="stat-icon icon-lg fa fa-tasks"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="databox bg-white radius-bordered">
                        <div class="databox-left bg-themethirdcolor">
                            <div class="databox-piechart">
                                <div data-toggle="easypiechart" class="easyPieChart" data-barcolor="#fff"
                                     data-linecap="butt" data-percent="15" data-animate="500" data-linewidth="3"
                                     data-size="47" data-trackcolor="rgba(255,255,255,0.2)"><span class="white font-90">15%</span>
                                </div>
                            </div>
                        </div>
                        <div class="databox-right">
                            <span class="databox-title themethirdcolor">孕产妇</span>
                            <div class="databox-text darkgray">
                                新建册<span id="pregnant_new" class="databox-number themethirdcolor " style="display: inline-block"> 0</span>
                                新结案<span id="pregnant_close" class="databox-number themethirdcolor " style="display: inline-block"> 0</span>
                                未结案<span id="pregnant_count" class="databox-number themethirdcolor " style="display: inline-block"> 0</span>
                            </div>
                            <div class="databox-stat themethirdcolor radius-bordered">
                                <i class="stat-icon  icon-lg fa fa-envelope-o"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="databox bg-white radius-bordered">
                        <div class="databox-left bg-themeprimary">
                            <div class="databox-piechart">
                                <div id="users-pie" data-toggle="easypiechart" class="easyPieChart" data-barcolor="#fff"
                                     data-linecap="butt" data-percent="76" data-animate="500" data-linewidth="3"
                                     data-size="47" data-trackcolor="rgba(255,255,255,0.1)"><span class="white font-90">76%</span>
                                </div>
                            </div>
                        </div>
                        <div class="databox-right">
                            <span class="databox-title themeprimary">慢病</span>
                            <div class="databox-text darkgray">
                                高血压<span id="chronic_hyp" class="databox-number themeprimary " style="display: inline-block">0</span>
                                糖尿病<span id="chronic_diab" class="databox-number themeprimary " style="display: inline-block">0</span>
<!--                                重性精神病<span id="chronic_holergasia" class="databox-number themeprimary " style="display: inline-block">1207</span>-->
                            </div>

                            <div class="databox-state bg-themeprimary">
                                <i class="fa fa-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="databox bg-white radius-bordered">
                        <div class="databox-left bg-palegreen">
                            <div class="databox-piechart">
                                <div id="users-pie" data-toggle="easypiechart" class="easyPieChart" data-barcolor="#fff"
                                     data-linecap="butt" data-percent="76" data-animate="500" data-linewidth="3"
                                     data-size="47" data-trackcolor="rgba(255,255,255,0.1)"><span class="white font-90">76%</span>
                                </div>
                            </div>
                        </div>
                        <div class="databox-right">
                            <span class="databox-title palegreen">传染病</span>
                            <div class="databox-text darkgray">
                                传染病报告<span id="infectious_new" class="databox-number palegreen " style="display: inline-block">0</span>
                            </div>
                            <div class="databox-state bg-themeprimary">
                                <i class="fa fa-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-xs-12">
                    <div class="dashboard-box">
                        <div class="box-header">
                            <div class="deadline">
                                今年剩余天数: 243
                            </div>
                        </div>
                        <div class="box-progress">
                            <div class="progress-handle">20 天</div>
                            <div class="progress progress-xs progress-no-radius bg-whitesmoke">
                                <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                     aria-valuemax="100" style="width: 20%">
                                </div>
                            </div>
                        </div>
                        <div class="box-tabbs">
                            <div class="tabbable">
                                <ul class="nav nav-tabs tabs-flat  nav-justified" id="myTab11">
                                    <li class="active">
                                        <a data-toggle="tab" href="#realtime">
                                            实时公卫业务人次
                                        </a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#visits">
                                            机构门诊住院收入
                                        </a>
                                    </li>

                                    <li>
                                        <a data-toggle="tab" id="contacttab" href="#bandwidth">
                                            机构门诊住院人次
                                        </a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#sales">
                                            当月收入
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content tabs-flat no-padding">
                                    <div id="realtime" class="tab-pane active padding-5 animated fadeInUp">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div id="dashboard-chart-realtime"
                                                     class="chart chart-lg no-margin"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="visits" class="tab-pane  animated fadeInUp">
                                        <div class="row">
                                            <div class="col-lg-12 chart-container">
                                                <div id="dashboard-chart-visits" class="chart chart-lg no-margin"
                                                     style="width:100%"></div>
                                            </div>
                                        </div>

                                    </div>

                                    <div id="bandwidth" class="tab-pane padding-10 animated fadeInUp">
                                        <div class="row">
                                            <div class="col-lg-12 chart-container">
                                                <div id="dashboard-bandwidth-chart" class="chart chart-lg no-margin"
                                                     style="width:100%"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="sales" class="tab-pane padding-10 animated fadeInUp">
                                        <div class="row">
                                            <div class="col-lg-12 chart-container">
                                                <div id="dashboard-income-30days-chart" class="chart chart-lg no-margin"
                                                     style="width:100%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col=lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div
                        class="databox databox-lg databox-inverted radius-bordered databox-shadowed databox-graded databox-vertical">
                        <div class="databox-top bg-palegreen no-padding">
                            <div class="databox-stat white bg-palegreen font-120">
                                <i class="stat-icon fa fa-caret-down icon-xlg"></i>
                            </div>
                            <div class="horizontal-space space-lg"></div>
                            <div class="databox-sparkline no-margin">
                                                <span data-sparkline="compositebar" data-height="82px" data-width="100%"
                                                      data-barcolor="#b0dc81"
                                                      data-barwidth="10px" data-barspacing="5px"
                                                      data-fillcolor="false" data-linecolor="#fff" data-spotradius="3"
                                                      data-linewidth="2"
                                                      data-spotcolor="#fafafa" data-minspotcolor="#fafafa"
                                                      data-maxspotcolor="#fff"
                                                      data-highlightspotcolor="#fff" data-highlightlinecolor="#fff"
                                                      data-composite="7, 6, 5, 7, 9, 10, 8, 7, 6, 6, 4, 7, 8">
                                                    8,4,1,2,4,6,2,4,4,8,10,7,10
                                                </span>
                            </div>
                        </div>
                        <div class="databox-bottom no-padding">
                            <div class="databox-row">
                                <div class="databox-cell cell-6 text-align-left">
                                    <span class="databox-text">妇保业务情况</span>
                                    <span class="databox-number">23,657</span>
                                </div>
                                <div class="databox-cell cell-6 text-align-right">
                                    <span class="databox-text">3月份</span>
                                    <span class="databox-number font-70">1,257</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col=lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div
                        class="databox databox-lg databox-inverted radius-bordered databox-shadowed databox-graded databox-vertical">
                        <div class="databox-top bg-orange no-padding">
                            <div class="databox-stat white bg-orange font-120">
                                <i class="stat-icon fa fa-caret-up icon-xlg"></i>
                            </div>
                            <div class="horizontal-space space-lg"></div>
                            <div class="databox-sparkline no-margin">
                                                <span data-sparkline="compositebar" data-height="82px" data-width="100%"
                                                      data-barcolor="#fb7d64"
                                                      data-barwidth="10px" data-barspacing="5px"
                                                      data-fillcolor="false" data-linecolor="#fff" data-spotradius="3"
                                                      data-linewidth="2"
                                                      data-spotcolor="#fafafa" data-minspotcolor="#fafafa"
                                                      data-maxspotcolor="#fff"
                                                      data-highlightspotcolor="#fff" data-highlightlinecolor="#fff"
                                                      data-composite="7, 6, 5, 7, 9, 10, 8, 6,2,4,1,2,7">
                                                    10,7,10,8,4,6, 6, 4, 7, 8 ,4,4,8
                                                </span>
                            </div>
                        </div>
                        <div class="databox-bottom no-padding">
                            <div class="databox-row">
                                <div class="databox-cell cell-6 text-align-left">
                                    <span class="databox-text">儿保业务情况</span>
                                    <span class="databox-number">76,109</span>
                                </div>
                                <div class="databox-cell cell-6 text-align-right">
                                    <span class="databox-text">3月份</span>
                                    <span class="databox-number font-70">7,540</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col=lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div
                        class="databox databox-lg databox-inverted radius-bordered databox-shadowed databox-graded databox-vertical">
                        <div class="databox-top bg-azure no-padding">
                            <div class="databox-stat white bg-azure font-120">
                                <i class="stat-icon fa fa-caret-up icon-xlg"></i>
                            </div>
                            <div class="horizontal-space space-lg"></div>
                            <div class="databox-sparkline no-margin">
                                                <span data-sparkline="compositebar" data-height="82px" data-width="100%"
                                                      data-barcolor="#3bcbef"
                                                      data-barwidth="10px" data-barspacing="5px"
                                                      data-fillcolor="false" data-linecolor="#fff" data-spotradius="3"
                                                      data-linewidth="2"
                                                      data-spotcolor="#fafafa" data-minspotcolor="#fafafa"
                                                      data-maxspotcolor="#fff"
                                                      data-highlightspotcolor="#fff" data-highlightlinecolor="#fff"
                                                      data-composite="8,4,1,2,4,6,2,4,4,8,10,7,7">
                                                    7, 6, 5, 7, 9, 10, 8, 7, 6, 6, 4, 7, 8
                                                </span>
                            </div>
                        </div>
                        <div class="databox-bottom no-padding">
                            <div class="databox-row">
                                <div class="databox-cell cell-6 text-align-left">
                                    <span class="databox-text">慢病业务情况</span>
                                    <span class="databox-number">541</span>
                                </div>
                                <div class="databox-cell cell-6 text-align-right">
                                    <span class="databox-text">3月份</span>
                                    <span class="databox-number font-70">123</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col=lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div
                        class="databox databox-lg databox-inverted radius-bordered databox-shadowed databox-graded databox-vertical">
                        <div class="databox-top bg-themethirdcolor no-padding">
                            <div class="databox-stat white bg-themethirdcolor font-120">
                                <i class="stat-icon fa fa-caret-up icon-xlg"></i>
                            </div>
                            <div class="horizontal-space space-lg"></div>
                            <div class="databox-sparkline no-margin">
                                                <span data-sparkline="compositebar" data-height="82px" data-width="100%"
                                                      data-barcolor="#ddd"
                                                      data-barwidth="10px" data-barspacing="5px"
                                                      data-fillcolor="false" data-linecolor="#fff" data-spotradius="3"
                                                      data-linewidth="2"
                                                      data-spotcolor="#fafafa" data-minspotcolor="#fafafa"
                                                      data-maxspotcolor="#fff"
                                                      data-highlightspotcolor="#fff" data-highlightlinecolor="#fff"
                                                      data-composite="8,4,1,2,4,6,2,4,4,8,10,7,7">
                                                    7, 6, 5, 7, 9, 10, 8, 7, 6, 6, 4, 7, 8
                                                </span>
                            </div>
                        </div>
                        <div class="databox-bottom no-padding">
                            <div class="databox-row">
                                <div class="databox-cell cell-6 text-align-left">
                                    <span class="databox-text">分娩情况</span>
                                    <span class="databox-number">2234</span>
                                </div>
                                <div class="databox-cell cell-6 text-align-right">
                                    <span class="databox-text">3月份</span>
                                    <span class="databox-number font-70">123</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
            <div class="databox databox-xxlg databox-vertical databox-inverted">
                <div class="databox-top bg-whitesmoke no-padding">
                    <div class="databox-row row-2 bg-orange no-padding">
                        <div class="databox-cell cell-1 text-align-center no-padding padding-top-5">
                            <span class="databox-number white"><i class="fa fa-bar-chart-o no-margin"></i></span>
                        </div>
                        <div class="databox-cell cell-8 no-padding padding-top-5 text-align-left">
                            <span class="databox-number white">卫生监督</span>
                        </div>
                        <div class="databox-cell cell-3 text-align-right padding-10">
                            <span class="databox-text white">3月13日</span>
                        </div>
                    </div>
                    <div class="databox-row row-4">
                        <div class="databox-cell cell-6 no-padding padding-10 padding-left-20 text-align-left">
                            <span class="databox-number orange no-margin">534,908</span>
                            <span class="databox-text sky no-margin">全部</span>
                        </div>
                        <div class="databox-cell cell-2 no-padding padding-10 text-align-left">
                            <span class="databox-number orange no-margin">4,129</span>
                            <span class="databox-text darkgray no-margin">本周</span>
                        </div>
                        <div class="databox-cell cell-2 no-padding padding-10 text-align-left">
                            <span class="databox-number orange no-margin">329</span>
                            <span class="databox-text darkgray no-margin">昨天</span>
                        </div>
                        <div class="databox-cell cell-2 no-padding padding-10 text-align-left">
                            <span class="databox-number orange no-margin">104</span>
                            <span class="databox-text darkgray no-margin">今天</span>
                        </div>
                    </div>
                    <div class="databox-row row-6 no-padding">
                        <div class="databox-sparkline">
                                            <span data-sparkline="line" data-height="126px" data-width="100%"
                                                  data-fillcolor="#37c2e2" data-linecolor="#37c2e2"
                                                  data-spotcolor="#fafafa" data-minspotcolor="#fafafa"
                                                  data-maxspotcolor="#ffce55"
                                                  data-highlightspotcolor="#f5f5f5 " data-highlightlinecolor="#f5f5f5"
                                                  data-linewidth="2" data-spotradius="0">
                                                5,7,6,5,9,4,3,7,2
                                            </span>
                        </div>
                    </div>
                </div>
                <div class="databox-bottom bg-sky no-padding">
                    <div class="databox-cell cell-2 text-align-center no-padding padding-top-5">
                        <span class="databox-header white">周一</span>
                    </div>
                    <div class="databox-cell cell-2 text-align-center no-padding padding-top-5">
                        <span class="databox-header white">周二</span>
                    </div>
                    <div class="databox-cell cell-2 text-align-center no-padding padding-top-5">
                        <span class="databox-header white">周三</span>
                    </div>
                    <div class="databox-cell cell-2 text-align-center no-padding padding-top-5">
                        <span class="databox-header white">周四</span>
                    </div>
                    <div class="databox-cell cell-2 text-align-center no-padding padding-top-5">
                        <span class="databox-header white">周五</span>
                    </div>
                    <div class="databox-cell cell-2 text-align-center no-padding padding-top-5">
                        <span class="databox-header white">周六</span>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
            <div class="databox databox-xxlg databox-vertical databox-shadowed bg-white radius-bordered padding-5">
                <div class="databox-top">
                    <div class="databox-row row-12">
                        <div class="databox-cell cell-3 text-center">
                            <div class="databox-number number-xxlg sonic-silver">164</div>
                            <div class="databox-text storm-cloud">档案</div>
                        </div>
                        <div class="databox-cell cell-9 text-align-center">
                            <div class="databox-row row-6 text-left">
                                <span class="badge badge-palegreen badge-empty margin-left-5">78</span>
                                <span class="databox-inlinetext uppercase darkgray margin-left-5">男性</span>
                                <span class="badge badge-yellow badge-empty margin-left-5">86</span>
                                <span class="databox-inlinetext uppercase darkgray margin-left-5">女性</span>
                            </div>
                            <div class="databox-row row-6">
                                <div class="progress bg-yellow progress-no-radius">
                                    <div class="progress-bar progress-bar-palegreen" role="progressbar"
                                         aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 78%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="databox-bottom">
                    <div class="databox-row row-12">
                        <div class="databox-cell cell-7 text-center  padding-5">
                            <div id="dashboard-pie-chart-sources" class="chart"></div>
                        </div>
                        <div class="databox-cell cell-5 text-center no-padding-left padding-bottom-30">
                            <div class="databox-row row-2 bordered-bottom bordered-ivory padding-10">
                                <span class="databox-text sonic-silver pull-left no-margin">类型</span>
                                <span class="databox-text sonic-silver pull-right no-margin uppercase">比例</span>
                            </div>
                            <div class="databox-row row-2 bordered-bottom bordered-ivory padding-10">
                                <span class="badge badge-blue badge-empty pull-left margin-5"></span>
                                <span class="databox-text darkgray pull-left no-margin hidden-xs">老年人</span>
                                <span class="databox-text darkgray pull-right no-margin uppercase">46%</span>
                            </div>
                            <div class="databox-row row-2 bordered-bottom bordered-ivory padding-10">
                                <span class="badge badge-orange badge-empty pull-left margin-5"></span>
                                <span class="databox-text darkgray pull-left no-margin hidden-xs">孕产妇</span>
                                <span class="databox-text darkgray pull-right no-margin uppercase">21%</span>
                            </div>
                            <div class="databox-row row-2 bordered-bottom bordered-ivory padding-10">
                                <span class="badge badge-pink badge-empty pull-left margin-5"></span>
                                <span class="databox-text darkgray pull-left no-margin hidden-xs">儿童</span>
                                <span class="databox-text darkgray pull-right no-margin uppercase">12%</span>
                            </div>
                            <div class="databox-row row-2 bordered-bottom bordered-ivory padding-10">
                                <span class="badge badge-palegreen badge-empty pull-left margin-5"></span>
                                <span class="databox-text darkgray pull-left no-margin hidden-xs">慢病</span>
                                <span class="databox-text darkgray pull-right no-margin uppercase">11%</span>
                            </div>
                            <div class="databox-row row-2 padding-10">
                                <span class="badge badge-yellow badge-empty pull-left margin-5"></span>
                                <span class="databox-text darkgray pull-left no-margin hidden-xs">其他</span>
                                <span class="databox-text darkgray pull-right no-margin uppercase">10%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<!--Basic Scripts-->
<script src="<?php echo RESOURCE_SITE_URL; ?>/bootstrap/js/jquery-2.0.3.min.js"></script>
<script src="<?php echo RESOURCE_SITE_URL; ?>/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo RESOURCE_SITE_URL; ?>/bootstrap/js/slimscroll/jquery.slimscroll.min.js"></script>

<!--Beyond Scripts-->
<script src="<?php echo RESOURCE_SITE_URL; ?>/bootstrap/js/beyond.js"></script>


<!--Page Related Scripts-->
<!--Sparkline Charts Needed Scripts-->
<script src="<?php echo RESOURCE_SITE_URL; ?>/bootstrap/js/charts/sparkline/jquery.sparkline.js"></script>
<script src="<?php echo RESOURCE_SITE_URL; ?>/bootstrap/js/charts/sparkline/sparkline-init.js"></script>

<!--Easy Pie Charts Needed Scripts-->
<script src="<?php echo RESOURCE_SITE_URL; ?>/bootstrap/js/charts/easypiechart/jquery.easypiechart.js"></script>
<script src="<?php echo RESOURCE_SITE_URL; ?>/bootstrap/js/charts/easypiechart/easypiechart-init.js"></script>

<!--Flot Charts Needed Scripts-->
<script src="<?php echo RESOURCE_SITE_URL; ?>/bootstrap/js/charts/flot/jquery.flot.js"></script>
<script src="<?php echo RESOURCE_SITE_URL; ?>/bootstrap/js/charts/flot/jquery.flot.resize.js"></script>
<script src="<?php echo RESOURCE_SITE_URL; ?>/bootstrap/js/charts/flot/jquery.flot.time.js"></script>
<script src="<?php echo RESOURCE_SITE_URL; ?>/bootstrap/js/charts/flot/jquery.flot.pie.js"></script>
<script src="<?php echo RESOURCE_SITE_URL; ?>/bootstrap/js/charts/flot/jquery.flot.tooltip.js"></script>
<script src="<?php echo RESOURCE_SITE_URL; ?>/bootstrap/js/charts/flot/jquery.flot.orderBars.js"></script>
<script src="<?php echo RESOURCE_SITE_URL; ?>/js/moment.js"></script>
<script src="<?php echo RESOURCE_SITE_URL; ?>/js/moment-timezone-with-data.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/hightchart/highcharts.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/hightchart/highcharts-3d.js"></script>
<script>
    $(function () {
      //初始化
        /*Sets Themed Colors Based on Themes*/
        themeprimary = getThemeColorFromCss('themeprimary');
        themesecondary = getThemeColorFromCss('themesecondary');
        themethirdcolor = getThemeColorFromCss('themethirdcolor');
        themefourthcolor = getThemeColorFromCss('themefourthcolor');
        themefifthcolor = getThemeColorFromCss('themefifthcolor');

        $('#dashboard-bandwidth-chart')
          .data('width', $('.box-tabbs')
              .width() - 20);
        $.getJSON("index.php?act=dashboard&op=newchart", function (data) {
            // console.log(data);
            //1,新增档案数 /档案总数;
            $("#file_new").html(data["file_new"]);
            $("#file_count").html(data["file_count"]);
            //2,孕妇新增建册 /新增结案/未结案总数
            $("#pregnant_count").html(data["pregnant_count"]);
            $("#pregnant_new").html(data["pregnant_new"]);
            $("#pregnant_close").html(data["pregnant_close"]);
            //3,高血压/糖尿病/重性精神病 总数
            $("#chronic_hyp").html(data["chronic_hyp"]);
            $("#chronic_diab").html(data["chronic_diab"]);
            $("#chronic_holergasia").html(data["chronic_holergasia"]);
            //4,传染病报告数
            $("#infectious_new").html(data["infectious_new"]);
            //5,公卫开展业务数
            var busi_counts = data['busi_counts'];
            var updateInterval = 60000;
            function getBusiRealTimeData(data) {
                if(data){
                  if(busi_counts[busi_counts.length-1].begintime == data.begintime){
                      busi_counts[busi_counts.length-1] = data;
                  }else{
                    // busi_counts.splice(0,1);
                    busi_counts.push(data);
                  }
                }
                var res = [];
                for (var i = 0; i < busi_counts.length; ++i) {
                  res.push([moment.tz(busi_counts[i].begintime, "Africa/Abidjan"), busi_counts[i].num]);
                }
                return res;
            }
            var getSeriesObj = function (cudata) {
                return [
                    {
                        data: getBusiRealTimeData(cudata),
                        lines: {
                            show: true,
                            lineWidth: 1,
                            fill: true,
                            fillColor: {
                                colors: [
                                    {
                                        opacity: 0
                                    }, {
                                        opacity: 1
                                    }
                                ]
                            },
                            steps: false
                        },
                        shadowSize: 0
                    }
                ];
            };

            var realtimeplot = $.plot("#dashboard-chart-realtime", getSeriesObj(), {
                yaxis: {
                    color: '#f3f3f3',
                    min: 0,
                    minTickSize:1,
                    tickFormatter:function(val, axis) {
                        return val.toFixed(0);
                    }
                    // max: 9,

                },
                xaxis: {
                    mode: "time",
                    timeformat: "%H:%M",
                    color: '#f3f3f3'
                    // min: 0,
                    // max: 100
                },
                grid: {
                    hoverable: true,
                    clickable: false,
                    borderWidth: 0,
                    aboveData: false
                },
                colors: [themeprimary]
            });
            function update() {
                $.getJSON("index.php?act=dashboard&op=busidata", function (data) {

                  realtimeplot.setData(getSeriesObj(data));
                  realtimeplot.setupGrid();
                  realtimeplot.draw();
                  setTimeout(update, updateInterval);
                });
            }
            update();
            // 6, 当天各个医疗机构的收入柱状图
            var income_counts = data['income_counts'];
            var income_data = [];
            for (var i =0 ;i<income_counts.length;i++){
                income_data.push([{name:income_counts[i].name,y:income_counts[i].num}]);
            }
//            income_data = [{name:"保健院",y:100},{name:"测试",y:200}];
            $('#dashboard-chart-visits').highcharts({
                chart: {
                    type: 'column',
                    style : "width:100%;height:100%;",
                    reflow:false
                },
                title: {
                    text: '机构门诊收入'
                },
                xAxis: {
                    type: 'category'
                },
                yAxis: {
                    title: {
                        text: '收入金额'
                    }
                },
                legend: {
                    enabled: false
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y}元'
                        }
                    }
                },

                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span><b>{point.y}</b>元<br/>'
                },

                series: [{
                    name: '收入',
                    colorByPoint: true,
                    data: income_data
                }]
            });
            //7, 当天各个医疗机构的门诊住院人次柱状图

            var preperson_counts = data['preperson_counts'];
            var preperson_data = [];
            for (var i =0 ;i<preperson_counts.length;i++){
                preperson_data.push([{name:preperson_counts[i].name,num:preperson_counts[i].num}]);
            }
//            preperson_data=[{name:"保健院",y:100},{name:"测试",y:200}];
            $('#dashboard-bandwidth-chart').highcharts({
                chart: {
                    type: 'column',
                    style : "width:100%;height:100%;",
                    reflow:false
                },
                title: {
                    text: '机构门诊人次'
                },
                xAxis: {
                    type: 'category'
                },
                yAxis: {
                    title: {
                        text: '人次'
                    }
                },
                legend: {
                    enabled: false
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y}人次'
                        }
                    }
                },

                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span><b>{point.y}</b>人次<br/>'
                },

                series: [{
                    name: '人次',
                    colorByPoint: true,
                    data: income_data
                }]
            });

            //8,门诊和住院收入的当月折线30天

            var income_30days = data['income_30days'];
            var income_30days_data = [];
            for (var i =0 ;i<income_30days_data.length;i++){
                income_30days_data.push([new Date(income_30days_data[i]['syear']+'-'+income_30days_data[i]['smonth']+'-'+income_30days_data[i]['sday']),
                    income_30days_data[i].num]);
            }
//            income_30days_data = [[new Date('2015-01-01'),100],[new Date('2015-01-02'),300],[new Date('2015-01-03'),200],[new Date('2015-01-04'),400]]
            var income_30days_chart = $.plot("#dashboard-income-30days-chart",
                [{
                data: income_30days_data,
                lines: {
                    show: true,
                    lineWidth: 1,
                    fill: true,
                    fillColor: {
                        colors: [
                            {
                                opacity: 0
                            }, {
                                opacity: 1
                            }
                        ]
                    },
                    steps: false
                },
                shadowSize: 0
            }], {
                yaxis: {
                    color: '#f3f3f3',
                    min: 0,
                    minTickSize:1,
                    tickFormatter:function(val, axis) {
                        return val.toFixed(0);
                    }
                    // max: 9,

                },
                xaxis: {
                    mode: "time",
                    timeformat: "%m-%d",
                    color: '#f3f3f3',
                    labelWidth:100
                    // min: 0,
                    // max: 100
                },
                grid: {
                    hoverable: true,
                    clickable: false,
                    borderWidth: 0,
                    aboveData: false
                },
                colors: [themesecondary]
            });


            //-------------------------Visitor Sources Pie Chart----------------------------------------//
            var data = [
                {
                    data: [[1, 21]],
                    color: '#fb6e52'
                },
                {
                    data: [[1, 12]],
                    color: '#e75b8d'
                },
                {
                    data: [[1, 11]],
                    color: '#a0d468'
                },
                {
                    data: [[1, 10]],
                    color: '#ffce55'
                },
                {
                    data: [[1, 46]],
                    color: '#5db2ff'
                }
            ];
            var placeholder = $("#dashboard-pie-chart-sources");
            placeholder.unbind();

            $.plot(placeholder, data, {
                series: {
                    pie: {
                        innerRadius: 0.45,
                        show: true,
                        stroke: {
                            width: 4
                        }
                    }
                }
            });

            //------------------------------Visit Chart------------------------------------------------//


            //------------------------------Real-Time Chart-------------------------------------------//



            //-------------------------Initiates Easy Pie Chart instances in page--------------------//
            InitiateEasyPieChart.init();

            //-------------------------Initiates Sparkline Chart instances in page------------------//
            InitiateSparklineCharts.init();
        });



    });

</script>
<!--Google Analytics::Demo Only-->


</body>
<!--  /Body -->
</html>
