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
    <link href="<?php echo RESOURCE_SITE_URL; ?>/js/flipcount.css" rel="stylesheet"/>
    <style>
        body:before {
            background-color: #EEF9FE;
        }

        .progress {
            line-height: inherit;
            color: inherit;
            padding: inherit;
            margin: inherit;
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
                                     data-linecap="butt" data-percent="0" data-animate="500" data-linewidth="3"
                                     data-size="47" data-trackcolor="rgba(255,255,255,0.1)" id="file_pie_rate"
                                     title="新增档案占比"><span class="white font-90">0%</span>
                                </div>
                            </div>
                        </div>
                        <div class="databox-right">
                            <span class="databox-title themesecondary">档案</span>

                            <div class="databox-text darkgray">
                                新增数<span id="file_new" class="databox-number themesecondary flip-counter small       "
                                         style="display: inline-block"> 0</span>
                                总数<span id="file_count" class="databox-number themesecondary"
                                        style="display: inline-block"> 0</span>
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
                                     data-linecap="butt" data-percent="0" data-animate="500" data-linewidth="3"
                                     data-size="47" data-trackcolor="rgba(255,255,255,0.2)" id="pregnant_pie_rate"
                                     title="新建册占比"><span class="white font-90">0%</span>
                                </div>
                            </div>
                        </div>
                        <div class="databox-right">
                            <span class="databox-title themethirdcolor">孕产妇</span>

                            <div class="databox-text darkgray">
                                新建册<span id="pregnant_new" class="databox-number themethirdcolor "
                                         style="display: inline-block"> 0</span>
                                新结案<span id="pregnant_close" class="databox-number themethirdcolor "
                                         style="display: inline-block"> 0</span>
                                未结案<span id="pregnant_count" class="databox-number themethirdcolor "
                                         style="display: inline-block"> 0</span>
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
                                <div data-toggle="easypiechart" class="easyPieChart" data-barcolor="#fff"
                                     data-linecap="butt" data-percent="0" data-animate="500" data-linewidth="3"
                                     data-size="47" data-trackcolor="rgba(255,255,255,0.1)" id="chronic_pie_rate"
                                     title="高血压占比"><span class="white font-90">0%</span>
                                </div>
                            </div>
                        </div>
                        <div class="databox-right">
                            <span class="databox-title themeprimary">慢病</span>

                            <div class="databox-text darkgray">
                                高血压<span id="chronic_hyp" class="databox-number themeprimary "
                                         style="display: inline-block">0</span>
                                糖尿病<span id="chronic_diab" class="databox-number themeprimary "
                                         style="display: inline-block">0</span>
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
                                <div data-toggle="easypiechart" class="easyPieChart" data-barcolor="#fff"
                                     data-linecap="butt" data-percent="0" data-animate="500" data-linewidth="3"
                                     data-size="47" data-trackcolor="rgba(255,255,255,0.1)" id="infectious_pie_rate"
                                     title="传染病报告占比"><span class="white font-90">0%</span>
                                </div>
                            </div>
                        </div>
                        <div class="databox-right">
                            <span class="databox-title palegreen">传染病</span>

                            <div class="databox-text darkgray">
                                传染病报告<span id="infectious_new" class="databox-number palegreen "
                                           style="display: inline-block">0</span>
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
                                今年剩余天数: <span id="year_last_day"></span>
                            </div>
                        </div>
                        <div class="box-progress" id="year_days">
                            <div class="progress-handle" >0 天</div>
                            <div class="progress progress-xs progress-no-radius bg-whitesmoke">
                                <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                     aria-valuemax="100" style="width: 0%">
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
                                            当天机构门诊住院收入
                                        </a>
                                    </li>

                                    <li>
                                        <a data-toggle="tab" id="contacttab" href="#bandwidth">
                                            机构门诊住院人次
                                        </a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#sales">
                                            全县30天收入情况
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
                            <div class="databox-sparkline no-margin" id="pregnantbusi_14day">

                            </div>
                        </div>
                        <div class="databox-bottom no-padding">
                            <div class="databox-row">
                                <div class="databox-cell cell-6 text-align-left">
                                    <span class="databox-text">妇保业务情况</span>
                                    <span class="databox-number" id="pregnantbusi_count">0</span>
                                </div>
                                <div class="databox-cell cell-6 text-align-right">
                                    <span class="databox-text">本月</span>
                                    <span class="databox-number font-70" id="pregnantbusi_monthcount">0</span>
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
                            <div class="databox-sparkline no-margin" id="childbusi_14day">

                            </div>
                        </div>
                        <div class="databox-bottom no-padding">
                            <div class="databox-row">
                                <div class="databox-cell cell-6 text-align-left">
                                    <span class="databox-text">儿保业务情况</span>
                                    <span class="databox-number" id="childbusi_count">0</span>
                                </div>
                                <div class="databox-cell cell-6 text-align-right">
                                    <span class="databox-text">本月</span>
                                    <span class="databox-number font-70" id="childbusi_monthcount">0</span>
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
                            <div class="databox-sparkline no-margin" id="chronicbusi_14day">
                            </div>
                        </div>
                        <div class="databox-bottom no-padding">
                            <div class="databox-row">
                                <div class="databox-cell cell-6 text-align-left">
                                    <span class="databox-text">慢病业务情况</span>
                                    <span class="databox-number" id="chronicbusi_count">0</span>
                                </div>
                                <div class="databox-cell cell-6 text-align-right">
                                    <span class="databox-text">本月</span>
                                    <span class="databox-number font-70" id="chronicbusi_monthcount">0</span>
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
                            <div class="databox-sparkline no-margin" id="childbirth_14day">
                            </div>
                        </div>
                        <div class="databox-bottom no-padding">
                            <div class="databox-row">
                                <div class="databox-cell cell-6 text-align-left">
                                    <span class="databox-text">分娩情况</span>
                                    <span class="databox-number" id="childbirth_count">0</span>
                                </div>
                                <div class="databox-cell cell-6 text-align-right">
                                    <span class="databox-text">本月</span>
                                    <span class="databox-number font-70" id="childbirth_monthcount">0</span>
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
                            <span class="databox-text white" id="today"></span>
                        </div>
                    </div>
                    <div class="databox-row row-4">
                        <div class="databox-cell cell-6 no-padding padding-10 padding-left-20 text-align-left">
                            <span class="databox-number orange no-margin">0</span>
                            <span class="databox-text sky no-margin">全部</span>
                        </div>
                        <div class="databox-cell cell-2 no-padding padding-10 text-align-left">
                            <span class="databox-number orange no-margin">0</span>
                            <span class="databox-text darkgray no-margin">本周</span>
                        </div>
                        <div class="databox-cell cell-2 no-padding padding-10 text-align-left">
                            <span class="databox-number orange no-margin">0</span>
                            <span class="databox-text darkgray no-margin">昨天</span>
                        </div>
                        <div class="databox-cell cell-2 no-padding padding-10 text-align-left">
                            <span class="databox-number orange no-margin">0</span>
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
                            <div class="databox-number number-xxlg sonic-silver" id="allcount">0</div>
                            <div class="databox-text storm-cloud">档案</div>
                        </div>
                        <div class="databox-cell cell-9 text-align-center">
                            <div class="databox-row row-6 text-left">
                                <span class="badge badge-palegreen badge-empty margin-left-5"></span>
                                <span class="databox-inlinetext uppercase darkgray margin-left-5">男性</span>
                                <span class="badge badge-yellow badge-empty margin-left-5"></span>
                                <span class="databox-inlinetext uppercase darkgray margin-left-5">女性</span>
                            </div>
                            <div class="databox-row row-6">
                                <div class="progress bg-yellow progress-no-radius">
                                    <div class="progress-bar progress-bar-palegreen" id="male_fmale_rate"
                                         role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
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
                                    <span class="databox-text darkgray pull-right no-margin uppercase" id="oldman_rate">0%</span>
                                </div>
                                <div class="databox-row row-2 bordered-bottom bordered-ivory padding-10">
                                    <span class="badge badge-orange badge-empty pull-left margin-5"></span>
                                    <span class="databox-text darkgray pull-left no-margin hidden-xs">孕产妇</span>
                                    <span class="databox-text darkgray pull-right no-margin uppercase"
                                          id="pregnant_rate">0%</span>
                                </div>
                                <div class="databox-row row-2 bordered-bottom bordered-ivory padding-10">
                                    <span class="badge badge-pink badge-empty pull-left margin-5"></span>
                                    <span class="databox-text darkgray pull-left no-margin hidden-xs">儿童</span>
                                    <span class="databox-text darkgray pull-right no-margin uppercase" id="child_rate">0%</span>
                                </div>
                                <div class="databox-row row-2 bordered-bottom bordered-ivory padding-10">
                                    <span class="badge badge-palegreen badge-empty pull-left margin-5"></span>
                                    <span class="databox-text darkgray pull-left no-margin hidden-xs">慢病</span>
                                    <span class="databox-text darkgray pull-right no-margin uppercase"
                                          id="chronic_rate">0%</span>
                                </div>
                                <div class="databox-row row-2 padding-10">
                                    <span class="badge badge-yellow badge-empty pull-left margin-5"></span>
                                    <span class="databox-text darkgray pull-left no-margin hidden-xs">其他</span>
                                    <span class="databox-text darkgray pull-right no-margin uppercase" id="other_rate">100%</span>
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
    <script src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/jquery.ui.js"></script>
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
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/flipcounter.js"></script>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/modernizr.custom.21954.js"></script>
    <script>
        function doTime(func) {
            doOneTime(func);
            delayrun(func);
        }
        function doOneTime(func) {
            setTimeout(func, 0);
        }
        function delayrun(func) {
            setTimeout(function () {
                func();
            }, 1000);
        }
        function getnum(obj) {
            return parseFloat(obj.toFixed(1));
        }
        function changecounter() {
            window.myCounter.add(123456)
        }
        var dayofyear = moment().dayOfYear();
        var year_rate = dayofyear*100/365;
        $("#year_last_day").html(365-dayofyear);
        $("#year_days > div:first-child").html(dayofyear+"天");
        $("#year_days > div:first-child").css("left",year_rate+'%');
        $("#year_days > div:first-child").css("margin-left",'-30px');
        $("#year_days > div:first-child+div >div").attr("aria-valuenow",year_rate)
        $("#year_days > div:first-child+div >div").css("width",year_rate+'%');
        $(function () {
            //初始化
            /*Sets Themed Colors Based on Themes*/
            var themeprimary = getThemeColorFromCss('themeprimary');
            var themesecondary = getThemeColorFromCss('themesecondary');
            var themethirdcolor = getThemeColorFromCss('themethirdcolor');
            var themefourthcolor = getThemeColorFromCss('themefourthcolor');
            var themefifthcolor = getThemeColorFromCss('themefifthcolor');

            $('#dashboard-bandwidth-chart')
                .data('width', $('.box-tabbs')
                        .width() - 20);
            $.getJSON("index.php?act=dashboard&op=cacheChart", function (data) {
                console.log(data);
                //1,新增档案数 /档案总数;
                $("#file_new").html(data["file_new"][0]["num"]);
                $("#file_count").html(data["file_count"][0]["num"]);
                var file_pie_rate = parseInt(data["file_new"][0]["num"]) * 100 / parseInt(data["file_count"][0]["num"]);
                $("#file_pie_rate").data("percent", file_pie_rate);
                InitiateEasyPieChart.update("#file_pie_rate");
                //2,孕妇新增建册 /新增结案/未结案总数
                $("#pregnant_count").html(data["pregnant_count"][0]['num']);
                $("#pregnant_new").html(data["pregnant_new"][0]['num']);
                $("#pregnant_close").html(data["pregnant_close"][0]['num']);
                var pregnant_pie_rate = parseInt(data["pregnant_new"][0]['num']) * 100 / parseInt(data["pregnant_count"][0]['num']);
                $("#pregnant_pie_rate").data("percent", pregnant_pie_rate);
                InitiateEasyPieChart.update("#pregnant_pie_rate");
                //3,高血压/糖尿病/重性精神病 总数
                $("#chronic_hyp").html(data["chronic_hyp"][0]['num']);
                $("#chronic_diab").html(data["chronic_diab"][0]['num']);
                $("#chronic_holergasia").html(data["chronic_holergasia"][0]['num']);
                var chronic_all = parseInt(data["chronic_hyp"][0]['num']) + parseInt(data["chronic_diab"][0]['num']);
                var chronic_pie_rate = parseInt(data["chronic_hyp"][0]['num']) * 100 / chronic_all;
                $("#chronic_pie_rate").data("percent", chronic_pie_rate);
                InitiateEasyPieChart.update("#chronic_pie_rate");
                //4,传染病报告数
                $("#infectious_new").html(data["infectious_new"][0]['num']);
                $("#infectious_pie_rate").data("percent", 0);
                InitiateEasyPieChart.update("#infectious_pie_rate");
                //5,公卫开展业务数
                var busi_counts = data['busi_counts'];
                var updateInterval = 60000;

                function getBusiRealTimeData(data) {
                    if (data) {
                        if (busi_counts[busi_counts.length - 1]['time'] == data['time']) {
                            busi_counts[busi_counts.length - 1] = data;
                        } else {
                            // busi_counts.splice(0,1);
                            busi_counts.push(data);
                        }
                    }
                    var res = [];
                    for (var i = 0; i < busi_counts.length; ++i) {
                        res.push([moment.tz(busi_counts[i]['time'], "Africa/Abidjan"), busi_counts[i]['num']]);
                    }
                    console.log(res);
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
                        minTickSize: 1,
                        tickFormatter: function (val, axis) {
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

                // 6, 当天各个医疗机构的收入柱状图
                var income_counts = data['income_counts'];
                var income_data = [];
                for (var i = 0; i < income_counts.length; i++) {
                    income_data.push({name: income_counts[i].name, y: parseFloat(income_counts[i]['num'])});
                }
                $('#dashboard-chart-visits').highcharts({
                    chart: {
                        type: 'column',
                        style: "width:100%;height:100%;",
                        reflow: false
                    },
                    title: {
                        text: '当天机构门诊住院收入'
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
                // 7, 当天各个医疗机构的门诊住院人次柱状图
                var preperson_counts = data['preperson_counts'];
                var preperson_data = [];
                for (var i = 0; i < preperson_counts.length; i++) {
                    preperson_data.push({name: preperson_counts[i].name, y: parseFloat(preperson_counts[i].num)});
                }
                $('#dashboard-bandwidth-chart').highcharts({
                    chart: {
                        type: 'column',
                        style: "width:100%;height:100%;",
                        reflow: false
                    },
                    title: {
                        text: '年内机构门诊人次'
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
                        data: preperson_data
                    }]
                });

                //8,门诊和住院收入的当月折线30天
                var income_30days = data['income_30days'];
                var income_30days_data = [];
                for (var i = 0; i < income_30days.length; i++) {
                    income_30days_data.push([moment.tz(income_30days[i]['syear'] + '-' + income_30days[i]['smonth'] + '-' + income_30days[i]['sday'], "Africa/Abidjan"),
                        parseFloat(income_30days[i]['num'])]);
                }
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
                            minTickSize: 1,
                            tickFormatter: function (val, axis) {
                                return val.toFixed(0);
                            }
                            // max: 9,

                        },
                        xaxis: {
                            mode: "time",
                            timeformat: "%m-%d",
                            color: '#f3f3f3',
                            labelWidth: 100
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
                //9. 妇保业务
                var pregnantbusi_count = data['pregnantbusi_count'][0]['num'];
                var pregnantbusi_monthcount = data['pregnantbusi_monthcount'][0]['num'];
                var pregnantbusi_14day = data['pregnantbusi_14day'];
                $("#pregnantbusi_count").html(pregnantbusi_count);
                $("#pregnantbusi_monthcount").html(pregnantbusi_monthcount);
                var pregnantbusi_14day_str = '';
                for (var i = 0; i < pregnantbusi_14day.length; i++) {
                    pregnantbusi_14day_str += ',' + pregnantbusi_14day[i]['num'];
                }
                pregnantbusi_14day_str = pregnantbusi_14day_str.substring(1);
                $("#pregnantbusi_14day").html(
                    '<span data-sparkline="compositebar" data-height="82px" data-width="100%" ' +
                    'data-barcolor="#b0dc81"' +
                    'data-barwidth="10px" data-barspacing="5px"' +
                    'data-fillcolor="false" data-linecolor="#fff" data-spotradius="3"' +
                    'data-linewidth="2"' +
                    'data-spotcolor="#fafafa" data-minspotcolor="#fafafa"' +
                    'data-maxspotcolor="#fff"' +
                    'data-highlightspotcolor="#fff" data-highlightlinecolor="#fff"' +
                    'data-composite="' + pregnantbusi_14day_str + '">' +
                    pregnantbusi_14day_str +
                    '</span>'
                );
                InitiateSparklineCharts.update("#pregnantbusi_14day");

                //10. 儿保业务
                var childbusi_count = data['childbusi_count'][0]['num'];
                var childbusi_monthcount = data['childbusi_monthcount'][0]['num'];
                var childbusi_14day = data['childbusi_14day'];
                $("#childbusi_count").html(childbusi_count);
                $("#childbusi_monthcount").html(childbusi_monthcount);
                var childbusi_14day_str = '';
                for (var i = 0; i < childbusi_14day.length; i++) {
                    childbusi_14day_str += ',' + childbusi_14day[i]['num'];
                }
                childbusi_14day_str = childbusi_14day_str.substring(1);
                $("#childbusi_14day").html(
                    '<span data-sparkline="compositebar" data-height="82px" data-width="100%" ' +
                    'data-barcolor="#fb7d64"' +
                    'data-barwidth="10px" data-barspacing="5px"' +
                    'data-fillcolor="false" data-linecolor="#fff" data-spotradius="3"' +
                    'data-linewidth="2"' +
                    'data-spotcolor="#fafafa" data-minspotcolor="#fafafa"' +
                    'data-maxspotcolor="#fff"' +
                    'data-highlightspotcolor="#fff" data-highlightlinecolor="#fff"' +
                    'data-composite="' + childbusi_14day_str + '">' +
                    childbusi_14day_str +
                    '</span>'
                );
                InitiateSparklineCharts.update("#childbusi_14day");
                //11. 慢病业务
                var chronicbusi_count = data['chronicbusi_count'][0]['num'];
                var chronicbusi_monthcount = data['chronicbusi_monthcount'][0]['num'];
                var chronicbusi_14day = data['chronicbusi_14day'];
                $("#chronicbusi_count").html(chronicbusi_count);
                $("#chronicbusi_monthcount").html(chronicbusi_monthcount);
                var chronicbusi_14day_str = '';
                for (var i = 0; i < chronicbusi_14day.length; i++) {
                    chronicbusi_14day_str += ',' + chronicbusi_14day[i]['num'];
                }
                chronicbusi_14day_str = chronicbusi_14day_str.substring(1);
                $("#chronicbusi_14day").html(
                    '<span data-sparkline="compositebar" data-height="82px" data-width="100%" ' +
                    'data-barcolor="#3bcbef"' +
                    'data-barwidth="10px" data-barspacing="5px"' +
                    'data-fillcolor="false" data-linecolor="#fff" data-spotradius="3"' +
                    'data-linewidth="2"' +
                    'data-spotcolor="#fafafa" data-minspotcolor="#fafafa"' +
                    'data-maxspotcolor="#fff"' +
                    'data-highlightspotcolor="#fff" data-highlightlinecolor="#fff"' +
                    'data-composite="' + chronicbusi_14day_str + '">' +
                    chronicbusi_14day_str +
                    '</span>'
                );
                InitiateSparklineCharts.update("#chronicbusi_14day");
                //12. 分娩情况
                var childbirth_count = data['childbirth_count'][0]['num'];
                var childbirth_monthcount = data['childbirth_monthcount'][0]['num'];
                var childbirth_14day = data['childbirth_14day'];
                $("#childbirth_count").html(childbirth_count);
                $("#childbirth_monthcount").html(childbirth_monthcount);
                var childbirth_14day_str = '';
                for (var i = 0; i < childbirth_14day.length; i++) {
                    childbirth_14day_str += ',' + childbirth_14day[i]['num'];
                }
                childbirth_14day_str = childbirth_14day_str.substring(1);
                $("#childbirth_14day").html(
                    '<span data-sparkline="compositebar" data-height="82px" data-width="100%" ' +
                    'data-barcolor="#b0dc81"' +
                    'data-barwidth="10px" data-barspacing="5px"' +
                    'data-fillcolor="false" data-linecolor="#fff" data-spotradius="3"' +
                    'data-linewidth="2"' +
                    'data-spotcolor="#fafafa" data-minspotcolor="#fafafa"' +
                    'data-maxspotcolor="#fff"' +
                    'data-highlightspotcolor="#fff" data-highlightlinecolor="#fff"' +
                    'data-composite="' + childbirth_14day_str + '">' +
                    childbirth_14day_str +
                    '</span>'
                );
                InitiateSparklineCharts.update("#childbirth_14day");
                //13,卫生监督
                var today = new Date();
                $("#today").html(today.getMonth() + "月" + today.getDate() + "日");
                //14,档案分布
                var file_male_count = parseInt(data['file_male_count'][0]['num']);
                var all = parseInt(data['file_count'][0]['num']);
                $("#allcount").html(all);
                var male_fmale_rate = (file_male_count * 100 ) / all;
                $("#male_fmale_rate").css("width", male_fmale_rate + "%");
                var file_old_count = parseInt(data['file_old_count'][0]['num']);
                var file_pregnant_count = parseInt(data['file_pregnant_count'][0]['num']);
                var file_child_count = parseInt(data['file_child_count'][0]['num']);
                var file_chronic_count = parseInt(data['file_chronic_count'][0]['num']);
                var file_other_count = all -file_old_count-file_pregnant_count-file_child_count-file_chronic_count ;

                var oldman_rate = getnum((file_old_count * 100) / all);
                $("#oldman_rate").html(oldman_rate + "%");
                var pregnant_rate = getnum((file_pregnant_count * 100) / all);
                $("#pregnant_rate").html(pregnant_rate + "%");
                var child_rate = getnum((file_child_count * 100) / all);
                $("#child_rate").html(child_rate + "%");
                var chronic_rate = getnum((file_chronic_count * 100) / all);
                $("#chronic_rate").html(chronic_rate + "%");
                var other_rate = getnum((file_other_count * 100) / all);
                $("#other_rate").html(other_rate + "%");

                var chartdata = [
                    {
                        data: [[1, pregnant_rate]],
                        color: '#fb6e52'
                    },
                    {
                        data: [[1, child_rate]],
                        color: '#e75b8d'
                    },
                    {
                        data: [[1, chronic_rate]],
                        color: '#a0d468'
                    },
                    {
                        data: [[1, other_rate]],
                        color: '#ffce55'
                    },
                    {
                        data: [[1, oldman_rate]],
                        color: '#5db2ff'
                    }
                ];
                var placeholder = $("#dashboard-pie-chart-sources");
//                    placeholder.unbind();
                $.plot(placeholder, chartdata, {
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
            });
            return ;
            //1,新增档案数 /档案总数;
            doTime(function () {
                $.getJSON("index.php?act=dashboard&op=newchart", {charttype: 1}, function (data) {
                    $("#file_new").html(data["file_new"]);
//                     window.myCounter = new flipCounter('file_new', {value: parseInt(data["file_new"]), inc: 11111, pace: 1000, auto: false});
//                    window.myCounter.add(12345);
                    $("#file_count").html(data["file_count"]);
                    var rate = parseInt(data["file_new"]) * 100 / parseInt(data["file_count"]);
                    $("#file_pie_rate").data("percent", rate);
                    InitiateEasyPieChart.update("#file_pie_rate");
                });
            });
            //2,孕妇新增建册 /新增结案/未结案总数
            doTime(function () {
                $.getJSON("index.php?act=dashboard&op=newchart", {charttype: 2}, function (data) {
                    $("#pregnant_count").html(data["pregnant_count"]);
                    $("#pregnant_new").html(data["pregnant_new"]);
                    $("#pregnant_close").html(data["pregnant_close"]);
                    var rate = parseInt(data["pregnant_new"]) * 100 / parseInt(data["pregnant_count"]);
                    $("#pregnant_pie_rate").data("percent", rate);
                    InitiateEasyPieChart.update("#pregnant_pie_rate");
                });
            });
            //3,高血压/糖尿病/重性精神病 总数
            doTime(function () {
                $.getJSON("index.php?act=dashboard&op=newchart", {charttype: 3}, function (data) {
                    $("#chronic_hyp").html(data["chronic_hyp"]);
                    $("#chronic_diab").html(data["chronic_diab"]);
                    $("#chronic_holergasia").html(data["chronic_holergasia"]);
                    var all = parseInt(data["chronic_hyp"]) + parseInt(data["chronic_diab"]);
                    var rate = parseInt(data["chronic_hyp"]) * 100 / all;
                    $("#chronic_pie_rate").data("percent", rate);
                    InitiateEasyPieChart.update("#chronic_pie_rate");
                });
            });
            //4,传染病报告数
            doTime(function () {
                $.getJSON("index.php?act=dashboard&op=newchart", {charttype: 4}, function (data) {
                    $("#infectious_new").html(data["infectious_new"]);
                    $("#infectious_pie_rate").data("percent", 0);
                    InitiateEasyPieChart.update("#infectious_pie_rate");
                });
            });
            //5,公卫开展业务数
            doOneTime(function () {
                $.getJSON("index.php?act=dashboard&op=newchart", {charttype: 5}, function (data) {
                    var busi_counts = data['busi_counts'];
                    var updateInterval = 60000;



                    function update() {
                        $.getJSON("index.php?act=dashboard&op=busidata", function (data) {

                            realtimeplot.setData(getSeriesObj(data));
                            realtimeplot.setupGrid();
                            realtimeplot.draw();
                            setTimeout(update, updateInterval);
                        });
                    }

                    update();
                });
            });
            // 6, 当天各个医疗机构的收入柱状图
            doOneTime(function () {
                $.getJSON("index.php?act=dashboard&op=newchart", {charttype: 6}, function (data) {
                    var income_counts = data['income_counts'];
                    var income_data = [];
                    for (var i = 0; i < income_counts.length; i++) {
                        income_data.push({name: income_counts[i].name, y: parseFloat(income_counts[i].num)});
                    }
                    $('#dashboard-chart-visits').highcharts({
                        chart: {
                            type: 'column',
                            style: "width:100%;height:100%;",
                            reflow: false
                        },
                        title: {
                            text: '当天机构门诊住院收入'
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
                });
            });
            // 7, 当天各个医疗机构的门诊住院人次柱状图
            doOneTime(function () {
                $.getJSON("index.php?act=dashboard&op=newchart", {charttype: 7}, function (data) {
                    var preperson_counts = data['preperson_counts'];
                    var preperson_data = [];
                    for (var i = 0; i < preperson_counts.length; i++) {
                        preperson_data.push({name: preperson_counts[i].name, y: parseFloat(preperson_counts[i].num)});
                    }
                    $('#dashboard-bandwidth-chart').highcharts({
                        chart: {
                            type: 'column',
                            style: "width:100%;height:100%;",
                            reflow: false
                        },
                        title: {
                            text: '年内机构门诊人次'
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
                            data: preperson_data
                        }]
                    });
                });
            });

            //8,门诊和住院收入的当月折线30天
            doOneTime(function () {
                $.getJSON("index.php?act=dashboard&op=newchart", {charttype: 8}, function (data) {
                    var income_30days = data['income_30days'];
                    var income_30days_data = [];
                    for (var i = 0; i < income_30days.length; i++) {
                        income_30days_data.push([moment.tz(income_30days[i]['syear'] + '-' + income_30days[i]['smonth'] + '-' + income_30days[i]['sday'], "Africa/Abidjan"),
                            parseFloat(income_30days[i].num)]);
                    }
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
                                minTickSize: 1,
                                tickFormatter: function (val, axis) {
                                    return val.toFixed(0);
                                }
                                // max: 9,

                            },
                            xaxis: {
                                mode: "time",
                                timeformat: "%m-%d",
                                color: '#f3f3f3',
                                labelWidth: 100
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
                });
            });
            //9. 妇保业务
            doOneTime(function () {
                $.getJSON("index.php?act=dashboard&op=newchart", {charttype: 9}, function (data) {
                    var pregnantbusi_count = data['pregnantbusi_count'];
                    var pregnantbusi_monthcount = data['pregnantbusi_monthcount'];
                    var pregnantbusi_14day = data['pregnantbusi_14day'];
                    $("#pregnantbusi_count").html(pregnantbusi_count);
                    $("#pregnantbusi_monthcount").html(pregnantbusi_monthcount);
                    var pregnantbusi_14day_str = '';
                    for (var i = 0; i < pregnantbusi_14day.length; i++) {
                        pregnantbusi_14day_str += ',' + pregnantbusi_14day[i]['num'];
                    }
                    pregnantbusi_14day_str = pregnantbusi_14day_str.substring(1);
                    $("#pregnantbusi_14day").html(
                        '<span data-sparkline="compositebar" data-height="82px" data-width="100%" ' +
                        'data-barcolor="#b0dc81"' +
                        'data-barwidth="10px" data-barspacing="5px"' +
                        'data-fillcolor="false" data-linecolor="#fff" data-spotradius="3"' +
                        'data-linewidth="2"' +
                        'data-spotcolor="#fafafa" data-minspotcolor="#fafafa"' +
                        'data-maxspotcolor="#fff"' +
                        'data-highlightspotcolor="#fff" data-highlightlinecolor="#fff"' +
                        'data-composite="' + pregnantbusi_14day_str + '">' +
                        pregnantbusi_14day_str +
                        '</span>'
                    );
                    InitiateSparklineCharts.update("#pregnantbusi_14day");

                });
            });
            //10. 儿保业务
            doOneTime(function () {
                $.getJSON("index.php?act=dashboard&op=newchart", {charttype: 10}, function (data) {
                    var childbusi_count = data['childbusi_count'];
                    var childbusi_monthcount = data['childbusi_monthcount'];
                    var childbusi_14day = data['childbusi_14day'];
                    $("#childbusi_count").html(childbusi_count);
                    $("#childbusi_monthcount").html(childbusi_monthcount);
                    var childbusi_14day_str = '';
                    for (var i = 0; i < childbusi_14day.length; i++) {
                        childbusi_14day_str += ',' + childbusi_14day[i]['num'];
                    }
                    childbusi_14day_str = childbusi_14day_str.substring(1);
                    $("#childbusi_14day").html(
                        '<span data-sparkline="compositebar" data-height="82px" data-width="100%" ' +
                        'data-barcolor="#fb7d64"' +
                        'data-barwidth="10px" data-barspacing="5px"' +
                        'data-fillcolor="false" data-linecolor="#fff" data-spotradius="3"' +
                        'data-linewidth="2"' +
                        'data-spotcolor="#fafafa" data-minspotcolor="#fafafa"' +
                        'data-maxspotcolor="#fff"' +
                        'data-highlightspotcolor="#fff" data-highlightlinecolor="#fff"' +
                        'data-composite="' + childbusi_14day_str + '">' +
                        childbusi_14day_str +
                        '</span>'
                    );
                    InitiateSparklineCharts.update("#childbusi_14day");
                });
            });
            //11. 慢病业务
            doOneTime(function () {
                $.getJSON("index.php?act=dashboard&op=newchart", {charttype: 11}, function (data) {
                    var chronicbusi_count = data['chronicbusi_count'];
                    var chronicbusi_monthcount = data['chronicbusi_monthcount'];
                    var chronicbusi_14day = data['chronicbusi_14day'];
                    $("#chronicbusi_count").html(chronicbusi_count);
                    $("#chronicbusi_monthcount").html(chronicbusi_monthcount);
                    var chronicbusi_14day_str = '';
                    for (var i = 0; i < chronicbusi_14day.length; i++) {
                        chronicbusi_14day_str += ',' + chronicbusi_14day[i]['num'];
                    }
                    chronicbusi_14day_str = chronicbusi_14day_str.substring(1);
                    $("#chronicbusi_14day").html(
                        '<span data-sparkline="compositebar" data-height="82px" data-width="100%" ' +
                        'data-barcolor="#3bcbef"' +
                        'data-barwidth="10px" data-barspacing="5px"' +
                        'data-fillcolor="false" data-linecolor="#fff" data-spotradius="3"' +
                        'data-linewidth="2"' +
                        'data-spotcolor="#fafafa" data-minspotcolor="#fafafa"' +
                        'data-maxspotcolor="#fff"' +
                        'data-highlightspotcolor="#fff" data-highlightlinecolor="#fff"' +
                        'data-composite="' + chronicbusi_14day_str + '">' +
                        chronicbusi_14day_str +
                        '</span>'
                    );
                    InitiateSparklineCharts.update("#chronicbusi_14day");
                });
            });
            //12. 分娩情况
            doOneTime(function () {
                $.getJSON("index.php?act=dashboard&op=newchart", {charttype: 12}, function (data) {
                    var childbirth_count = data['childbirth_count'];
                    var childbirth_monthcount = data['childbirth_monthcount'];
                    var childbirth_14day = data['childbirth_14day'];
                    $("#childbirth_count").html(childbirth_count);
                    $("#childbirth_monthcount").html(childbirth_monthcount);
                    var childbirth_14day_str = '';
                    for (var i = 0; i < childbirth_14day.length; i++) {
                        childbirth_14day_str += ',' + childbirth_14day[i]['num'];
                    }
                    childbirth_14day_str = childbirth_14day_str.substring(1);
                    $("#childbirth_14day").html(
                        '<span data-sparkline="compositebar" data-height="82px" data-width="100%" ' +
                        'data-barcolor="#b0dc81"' +
                        'data-barwidth="10px" data-barspacing="5px"' +
                        'data-fillcolor="false" data-linecolor="#fff" data-spotradius="3"' +
                        'data-linewidth="2"' +
                        'data-spotcolor="#fafafa" data-minspotcolor="#fafafa"' +
                        'data-maxspotcolor="#fff"' +
                        'data-highlightspotcolor="#fff" data-highlightlinecolor="#fff"' +
                        'data-composite="' + childbirth_14day_str + '">' +
                        childbirth_14day_str +
                        '</span>'
                    );
                    InitiateSparklineCharts.update("#childbirth_14day");
                });
            });
            //13,卫生监督
            doOneTime(function () {
                $.getJSON("index.php?act=dashboard&op=newchart", {charttype: 13}, function (data) {
                    var today = new Date();
                    $("#today").html(today.getMonth() + "月" + today.getDate() + "日");
                });
            });
            //14,档案分布
            doOneTime(function () {
                $.getJSON("index.php?act=dashboard&op=newchart", {charttype: 14}, function (data) {
                    var file_male_count = parseInt(data['file_male_count']);
                    var all = parseInt(data['all']);
                    $("#allcount").html(all);
                    var male_fmale_rate = (file_male_count * 100 ) / all;
                    $("#male_fmale_rate").css("width", male_fmale_rate + "%");
                    var file_old_count = parseInt(data['file_old_count']);
                    var file_pregnant_count = parseInt(data['file_pregnant_count']);
                    var file_child_count = parseInt(data['file_child_count']);
                    var file_chronic_count = parseInt(data['file_chronic_count']);
                    var file_other_count = parseInt(data['file_other_count']);

                    var oldman_rate = getnum((file_old_count * 100) / all);
                    $("#oldman_rate").html(oldman_rate + "%");
                    var pregnant_rate = getnum((file_pregnant_count * 100) / all);
                    $("#pregnant_rate").html(pregnant_rate + "%");
                    var child_rate = getnum((file_child_count * 100) / all);
                    $("#child_rate").html(child_rate + "%");
                    var chronic_rate = getnum((file_chronic_count * 100) / all);
                    $("#chronic_rate").html(chronic_rate + "%");
                    var other_rate = getnum((file_other_count * 100) / all);
                    $("#other_rate").html(other_rate + "%");

                    var chartdata = [
                        {
                            data: [[1, pregnant_rate]],
                            color: '#fb6e52'
                        },
                        {
                            data: [[1, child_rate]],
                            color: '#e75b8d'
                        },
                        {
                            data: [[1, chronic_rate]],
                            color: '#a0d468'
                        },
                        {
                            data: [[1, other_rate]],
                            color: '#ffce55'
                        },
                        {
                            data: [[1, oldman_rate]],
                            color: '#5db2ff'
                        }
                    ];
                    var placeholder = $("#dashboard-pie-chart-sources");
//                    placeholder.unbind();
                    $.plot(placeholder, chartdata, {
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
                });
            });
        });
    </script>
    <!--Google Analytics::Demo Only-->


</body>
<!--  /Body -->
</html>
