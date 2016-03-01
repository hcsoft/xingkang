<?php
/**
 * 载入权限
 *
 * @copyright  Copyright (c) 2014-2020 SZGR Inc. (http://www.szgr.com.cn)
 * @license    http://www.szgr.com.cn
 * @link       http://www.szgr.com.cn
 * @since      File available since Release v1.1
 */
defined('InShopNC') or exit('Access Invalid!');
$_limit =  array(
	array('name'=>'图表', 'child'=>array(
		array('name'=>'图表', 'op'=>'chartpage', 'act'=>'dashboard'),
		array('name'=>'整体业务', 'op'=>'welcome', 'act'=>'dashboardnew')
	)),
	array('name'=>$lang['nc_config'], 'child'=>array(
		array('name'=>$lang['nc_web_set'], 'op'=>null, 'act'=>'setting'),
		array('name'=>$lang['nc_web_account_syn'], 'op'=>null, 'act'=>'account'),
		array('name'=>$lang['nc_upload_set'], 'op'=>null, 'act'=>'upload'),
		array('name'=>$lang['nc_seo_set'], 'op'=>'seo', 'act'=>'setting'),
		array('name'=>$lang['nc_pay_method'], 'op'=>null, 'act'=>'payment'),
		array('name'=>$lang['nc_message_set'], 'op'=>null, 'act'=>'message'),
		array('name'=>$lang['nc_admin_express_set'], 'op'=>null, 'act'=>'express'),
	    array('name'=>$lang['nc_admin_offpay_area_set'], 'op'=>null, 'act'=>'offpay_area'),
	    array('name'=>$lang['nc_admin_clear_cache'], 'op'=>null, 'act'=>'cache'),
	    array('name'=>$lang['nc_admin_perform_opt'], 'op'=>null, 'act'=>'perform'),
	    array('name'=>$lang['nc_admin_search_set'], 'op'=>null, 'act'=>'search'),
	    array('name'=>$lang['nc_admin_log'], 'op'=>null, 'act'=>'admin_log'),
		)),
	array('name'=>$lang['nc_goods'], 'child'=>array(
		array('name'=>$lang['nc_goods_manage'], 'op'=>null, 'act'=>'goods'),
        array('name'=>'库存管理', 'op'=>'stock', 'act'=>'goods'),
        array('name'=>'库存汇总', 'op'=>'stocksum', 'act'=>'goods'),
		array('name'=>$lang['nc_class_manage'], 'op'=>null, 'act'=>'goods_class'),
		array('name'=>$lang['nc_brand_manage'], 'op'=>null, 'act'=>'brand'),
		array('name'=>$lang['nc_type_manage'], 'op'=>null, 'act'=>'type'),
		array('name'=>$lang['nc_spec_manage'], 'op'=>null, 'act'=>'spec'),
		array('name'=>$lang['nc_album_manage'], 'op'=>null, 'act'=>'goods_album'),
		array('name'=>'商品调价审核', 'op'=>'changeprice', 'act'=>'goods'),
		array('name'=>'商品汇总', 'op'=>'goods', 'act'=>'goodssum_goods')
		)),
	array('name'=>$lang['nc_store'], 'child'=>array(
		array('name'=>$lang['nc_store_manage'], 'op'=>null, 'act'=>'store'),
		array('name'=>$lang['nc_store_grade'], 'op'=>null, 'act'=>'store_grade'),
		array('name'=>$lang['nc_store_class'], 'op'=>null, 'act'=>'store_class'),
		array('name'=>$lang['nc_domain_manage'], 'op'=>null, 'act'=>'domain'),
		array('name'=>$lang['nc_s_snstrace'], 'op'=>null, 'act'=>'sns_strace'),
		)),
	array('name'=>$lang['nc_member'], 'child'=>array(
		array('name'=>$lang['nc_member_manage'], 'op'=>'member', 'act'=>'member'),
		array('name'=>'会员导出', 'op'=>'mbdataexport', 'act'=>'member'),
		array('name'=>'修改余额', 'op'=>'modifymoney', 'act'=>'member'),
		array('name'=>'密码重置', 'op'=>'psreset', 'act'=>'member'),
		array('name'=>'会员信息修改', 'op'=>'changebaseinfo', 'act'=>'member'),
		array('name'=>'会员注销', 'op'=>'unregister', 'act'=>'member'),
		array('name'=>'修改日志', 'op'=>'changelog', 'act'=>'member'),
		array('name'=>'注销日志', 'op'=>'unregisterlog', 'act'=>'member'),
		array('name'=>$lang['nc_member_notice'], 'op'=>null, 'act'=>'notice'),
		array('name'=>$lang['nc_member_pointsmanage'], 'op'=>null, 'act'=>'points'),
		array('name'=>$lang['nc_binding_manage'], 'op'=>null, 'act'=>'sns_sharesetting'),
		array('name'=>$lang['nc_member_album_manage'], 'op'=>null, 'act'=>'sns_malbum'),
	    array('name'=>$lang['nc_snstrace'], 'op'=>null, 'act'=>'snstrace'),
		array('name'=>$lang['nc_member_tag'], 'op'=>null, 'act'=>'sns_member'),
		array('name'=>$lang['nc_member_predepositmanage'], 'op'=>null, 'act'=>'predeposit'),
        array('name'=>'消费汇总', 'op'=>'consumesum', 'act'=>'member'),
        array('name'=>'充值下账汇总', 'op'=>'rechargesum', 'act'=>'member'),
        array('name'=>'会员储值积分对账', 'op'=>'check', 'act'=>'member'),
		array('name'=>'会员健康档案查看', 'op'=>'member2', 'act'=>'member'),
		array('name'=>'查看会员健康档案查看', 'op'=>'gethealthfiledetail', 'act'=>'member'),
		array('name'=>'快速建档', 'op'=>'ajax_loadmember', 'act'=>'member'),
		array('name'=>'关联健康档案', 'op'=>'ajax_bandhealthfile', 'act'=>'member'),
		array('name'=>'身份证号码校验', 'op'=>'ajax_checkidnumber', 'act'=>'member'),
		array('name'=>'保存健康档案', 'op'=>'ajax_savehealthfile', 'act'=>'member'),
		)),
	array('name'=>$lang['nc_trade'], 'child'=>array(
		array('name'=>$lang['nc_order_manage'], 'op'=>null, 'act'=>'order'),
		array('name'=>'退款管理', 'op'=>null, 'act'=>'refund'),
		array('name'=>'退货管理', 'op'=>null, 'act'=>'return'),
		array('name'=>$lang['nc_consult_manage'], 'op'=>null, 'act'=>'consulting'),
		array('name'=>$lang['nc_inform_config'], 'op'=>null, 'act'=>'inform'),
		array('name'=>$lang['nc_goods_evaluate'], 'op'=>null, 'act'=>'evaluate'),
		array('name'=>$lang['nc_complain_config'], 'op'=>null, 'act'=>'complain'),
		)),
	array('name'=>$lang['nc_website'], 'child'=>array(
		array('name'=>$lang['nc_article_class'], 'op'=>null, 'act'=>'article_class'),
		array('name'=>$lang['nc_article_manage'], 'op'=>null, 'act'=>'article'),
		array('name'=>$lang['nc_document'], 'op'=>null, 'act'=>'document'),
		array('name'=>$lang['nc_navigation'], 'op'=>null, 'act'=>'navigation'),
		array('name'=>$lang['nc_adv_manage'], 'op'=>null, 'act'=>'adv'),
		array('name'=>$lang['nc_web_index'], 'op'=>null, 'act'=>'web_config|web_api'),
		array('name'=>$lang['nc_admin_res_position'], 'op'=>null, 'act'=>'rec_position'),
		)),
	array('name'=>$lang['nc_operation'], 'child'=>array(
		array('name'=>$lang['nc_operation_set'], 'op'=>null, 'act'=>'operation'),
		array('name'=>$lang['nc_groupbuy_manage'], 'op'=>null, 'act'=>'groupbuy'),
		array('name'=>$lang['nc_activity_manage'], 'op'=>null, 'act'=>'activity'),
		array('name'=>$lang['nc_promotion_xianshi'], 'op'=>null, 'act'=>'promotion_xianshi'),
		array('name'=>$lang['nc_promotion_mansong'], 	'op'=>null, 'act'=>'promotion_mansong'),
		array('name'=>$lang['nc_promotion_bundling'], 'op'=>null, 'act'=>'promotion_bundling'),
		array('name'=>'推荐展位', 'op'=>null, 'act'=>'promotion_bundling'),
		array('name'=>$lang['nc_pointprod'], 'op'=>null, 'act'=>'pointprod|pointorder'),
		array('name'=>$lang['nc_voucher_price_manage'], 	'op'=>null, 'act'=>'voucher'),
	    array('name'=>$lang['nc_bill_manage'], 'op'=>null, 'act'=>'bill'),
		)),
	array('name'=>$lang['nc_stat'], 'child'=>array(
		array('name'=>$lang['nc_statmember'], 'op'=>null, 'act'=>'stat_member'),
		array('name'=>$lang['nc_statstore'], 'op'=>null, 'act'=>'stat_store'),
		array('name'=>$lang['nc_stattrade'], 'op'=>null, 'act'=>'stat_trade'),
		array('name'=>$lang['nc_statmarketing'], 'op'=>null, 'act'=>'stat_marketing'),
		array('name'=>$lang['nc_stataftersale'], 	'op'=>null, 'act'=>'stat_aftersale'),
		)),
    array('name'=>'业务督导', 'child'=>array(
        array('name'=>'呼叫中心', 'op'=>'call', 'act'=>'healthplatform'),
        array('name'=>'回访日志', 'op'=>'calllog', 'act'=>'healthplatform'),
        array('name'=>'回访抽查', 'op'=>'index', 'act'=>'healthplatform'),
		array('name'=>'睡眠顾客查询', 'op'=>'sleep', 'act'=>'healthplatform'),
    	array('name'=>'消费频次提醒', 'op'=>'consume', 'act'=>'healthplatform'),
		array('name'=>'顾客生日提醒', 'op'=>'birthday', 'act'=>'healthplatform'),
        array('name'=>'统计', 'op'=>'statistical', 'act'=>'healthplatform'),
        array('name'=>'测试', 'op'=>'test', 'act'=>'healthplatform'),
    )),
    array('name'=>'仓库', 'child'=>array(
        array('name'=>'仓库单据明细', 'op'=>'detail', 'act'=>'storehouse'),
        array('name'=>'仓库单据汇总', 'op'=>'sum', 'act'=>'storehouse')
    )),
    array('name'=>'社区', 'child'=>array(
        array('name'=>'收入明细查询', 'op'=>'incomedetail', 'act'=>'community'),
        array('name'=>'收入汇总查询', 'op'=>'incomesum', 'act'=>'community'),
        array('name'=>'就诊明细查询', 'op'=>'prescriptiondetail', 'act'=>'community'),
        array('name'=>'就诊情况汇总', 'op'=>'prescriptionsum', 'act'=>'community'),
        array('name'=>'门诊收入分析', 'op'=>'clinicstatistic', 'act'=>'community'),
        array('name'=>'消费汇总', 'op'=>'consumesum', 'act'=>'member'),
        array('name'=>'充值下账汇总', 'op'=>'rechargesum', 'act'=>'member'),
        array('name'=>'健康档案查询', 'op'=>'query', 'act'=>'healthfile'),
        array('name'=>'健康档案汇总', 'op'=>'sum', 'act'=>'healthfile')
    )),
    array('name'=>'财务', 'child'=>array(
        array('name'=>'销售明细查询', 'op'=>'saledetail', 'act'=>'finance'),
        array('name'=>'销售明细财务分类管理', 'op'=>'saledetailmanager', 'act'=>'finance'),
        array('name'=>'门诊收入统计', 'op'=>'financesum', 'act'=>'finance'),
        array('name'=>'住院收入统计', 'op'=>'financeinsum', 'act'=>'finance'),
    	array('name'=>'药品汇总', 'op'=>'goodssum_goods', 'act'=>'goods'),
        array('name'=>'单品毛利分析', 'op'=>'financegoodsum', 'act'=>'finance'),
        array('name'=>'仓库单据明细', 'op'=>'detail', 'act'=>'storehouse'),
        array('name'=>'仓库单据汇总', 'op'=>'sum', 'act'=>'storehouse'),
        array('name'=>'社区考核', 'op'=>'communitycheck', 'act'=>'finance')
    )),
	array('name'=>'绩效考核', 'child'=>array(
		array('name'=>'绩效考核指标', 'op'=>'kpi', 'act'=>'kpi'),
		array('name'=>'绩效考核指标设置', 'op'=>'kpiset', 'act'=>'kpi')
	))
);

if (C('mobile_isuse') !== NULL){
	$_limit[] = array('name'=>$lang['nc_mobile'], 'child'=>array(
		array('name'=>$lang['nc_mobile_adset'], 'op'=>NULL, 'act'=>'mb_ad'),
		array('name'=>$lang['nc_mobile_catepic'], 'op'=>NULL, 'act'=>'mb_category'),
		array('name'=>$lang['nc_mobile_feedback'], 'op'=>NULL, 'act'=>'feedback'),
		array('name'=>$lang['nc_mobile_update_cache'], 'op'=>NULL, 'act'=>'mb_cache')
		));
}

if (C('microshop_isuse') !== NULL){
	$_limit[] = array('name'=>$lang['nc_microshop'], 'child'=>array(
		array('name'=>$lang['nc_microshop_manage'], 'op'=>'manage', 'act'=>'microshop'),
		array('name'=>$lang['nc_microshop_goods_manage'], 'op'=>'goods|goods_manage', 'act'=>'microshop'),//op值重复(goods_manage,goodsclass_list,personal_manage...)是为了无权时，隐藏该菜单
		array('name'=>$lang['nc_microshop_goods_class'], 'op'=>'goodsclass|goodsclass_list', 'act'=>'microshop'),
		array('name'=>$lang['nc_microshop_personal_manage'], 'op'=>'personal|personal_manage', 'act'=>'microshop'),
		array('name'=>$lang['nc_microshop_personal_class'], 'op'=>'personalclass|personalclass_list', 'act'=>'microshop'),
		array('name'=>$lang['nc_microshop_store_manage'], 'op'=>'store|store_manage', 'act'=>'microshop'),
		array('name'=>$lang['nc_microshop_comment_manage'], 'op'=>'comment|comment_manage', 'act'=>'microshop'),
		array('name'=>$lang['nc_microshop_adv_manage'], 'op'=>'adv|adv_manage', 'act'=>'microshop')
		));
}

if (C('cms_isuse') !== NULL){
	$_limit[] = array('name'=>$lang['nc_cms'], 'child'=>array(
		array('name'=>$lang['nc_cms_manage'], 'op'=>null, 'act'=>'cms_manage'),
		array('name'=>$lang['nc_cms_index_manage'], 'op'=>null, 'act'=>'cms_index'),
		array('name'=>$lang['nc_cms_article_manage'], 'op'=>null, 'act'=>'cms_article|cms_article_class'),
		array('name'=>$lang['nc_cms_picture_manage'], 'op'=>null, 'act'=>'cms_picture|cms_picture_class'),
		array('name'=>$lang['nc_cms_special_manage'], 'op'=>null, 'act'=>'cms_special'),
		array('name'=>$lang['nc_cms_navigation_manage'], 'op'=>null, 'act'=>'cms_navigation'),
		array('name'=>$lang['nc_cms_tag_manage'], 'op'=>null, 'act'=>'cms_tag'),
		array('name'=>$lang['nc_cms_comment_manage'], 'op'=>null, 'act'=>'cms_comment')
		));
}

if (C('circle_isuse') !== NULL){
	$_limit[] = array('name'=>$lang['nc_circle'], 'child'=>array(
		array('name'=>$lang['nc_circle_setting'], 'op'=>'index', 'act'=>'circle_setting'),
		array('name'=>'成员头衔设置', 'op'=>'index', 'act'=>'circle_memberlevel'),
		array('name'=>$lang['nc_circle_classmanage'], 'op'=>null, 'act'=>'circle_class'),
		array('name'=>$lang['nc_circle_manage'], 'op'=>null, 'act'=>'circle_manage'),
		array('name'=>$lang['nc_circle_thememanage'], 'op'=>null, 'act'=>'circle_theme'),
		array('name'=>$lang['nc_circle_membermanage'], 'op'=>null, 'act'=>'circle_member'),
		array('name'=>'圈子举报管理', 'op'=>null, 'act'=>'circle_inform'),
		array('name'=>$lang['nc_circle_advmanage'],'op'=>'adv_manage', 'act'=>'circle_setting')
		));
}
return $_limit;
?>
