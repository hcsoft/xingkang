<?php
/**
 * 控制台
 *
 *
 *
 *
 * @copyright  Copyright (c) 2014-2020 SZGR Inc. (http://www.szgr.com.cn)
 * @license    http://www.szgr.com.cn
 * @link       http://www.szgr.com.cn
 * @since      File available since Release v1.1
 */
defined('InShopNC') or exit('Access Invalid!');

class dashboardControl extends SystemControl
{
    public function __construct()
    {
        parent::__construct();
        Language::read('dashboard');
    }

    /**
     * 欢迎页面
     */
    public function welcomeOp()
    {
        /**
         * 管理员信息
         */
        $model_admin = Model('admin');
        $tmp = $this->getAdminInfo();
        $condition['admin_id'] = $tmp['id'];
        $admin_info = $model_admin->infoAdmin($condition);
        $admin_info['admin_login_time'] = date('Y-m-d H:i:s', ($admin_info['admin_login_time'] == '' ? time() : $admin_info['admin_login_time']));
        /**
         * 系统信息
         */
        $version = C('version');
        $setup_date = C('setup_date');
        $statistics['os'] = PHP_OS;
        $statistics['web_server'] = $_SERVER['SERVER_SOFTWARE'];
        $statistics['php_version'] = PHP_VERSION;
        $statistics['sql_version'] = Db::getServerInfo();
        $statistics['shop_version'] = $version;
        $statistics['setup_date'] = substr($setup_date, 0, 10);
        Tpl::output('statistics', $statistics);
        Tpl::output('admin_info', $admin_info);
        Tpl::showpage('welcome');
    }

    /**
     * 关于我们
     */
    public function aboutusOp()
    {

        Tpl::showpage('aboutus');
    }

    /**
     * 统计
     */
    public function statisticsOp()
    {
        $statistics = array();
        // 本周开始时间点
        $tmp_time = mktime(0, 0, 0, date('m'), date('d'), date('Y')) - (date('w') == 0 ? 7 : date('w') - 1) * 24 * 60 * 60;
        /**
         * 会员
         */
        $model_member = Model('member');
        // 会员总数
        $statistics['member'] = $model_member->getMemberCount(array());
        // 新增会员数
        $statistics['week_add_member'] = $model_member->getMemberCount(array('member_time' => array('egt', $tmp_time)));
        // 预存款提现
        $statistics['cashlist'] = Model('predeposit')->getPdCashCount(array('pdc_payment_state' => 0));

        /**
         * 店铺
         */
        $model_store = Model('store');
        // 店铺总数
        $statistics['store'] = Model('store')->getStoreCount(array());
        // 店铺申请数
        $statistics['store_joinin'] = Model('store_joinin')->getStoreJoininCount(array('joinin_state' => array('in', array(10, 11))));
        // 即将到期
        $statistics['store_expire'] = $model_store->getStoreCount(array('store_state' => 1, 'store_end_time' => array('between', array(TIMESTAMP, TIMESTAMP + 864000))));
        // 已经到期
        $statistics['store_expired'] = $model_store->getStoreCount(array('store_state' => 1, 'store_end_time' => array('between', array(1, TIMESTAMP))));

        /**
         * 商品
         */
        $model_goods = Model('goods');
        // 商品总数
        $statistics['goods'] = $model_goods->getGoodsCommonCount(array());
        // 新增商品数
        $statistics['week_add_product'] = $model_goods->getGoodsCommonCount(array('goods_addtime' => array('egt', $tmp_time)));
        // 等待审核
        $statistics['product_verify'] = $model_goods->getGoodsCommonWaitVerifyCount(array());
        // 举报
        $statistics['inform_list'] = Model('inform')->getInformCount(array('inform_state' => 1));
        // 品牌申请
        $statistics['brand_apply'] = Model('brand')->getBrandCount(array('brand_apply' => '0'));

        /**
         * 交易
         */
        $model_order = Model('order');
        $model_refund_return = Model('refund_return');
        $model_complain = Model('complain');
        // 订单总数
        $statistics['order'] = $model_order->getOrderCount(array());
        // 退款
        $statistics['refund'] = $model_refund_return->getRefundReturn(array('refund_type' => 1, 'refund_state' => 2));
        // 退货
        $statistics['return'] = $model_refund_return->getRefundReturn(array('refund_type' => 2, 'refund_state' => 2));
        // 投诉
        $statistics['complain_new_list'] = $model_complain->getComplainCount(array('complain_state' => 10));
        // 带仲裁
        $statistics['complain_handle_list'] = $model_complain->getComplainCount(array('complain_state' => 40));

        /**
         * 运营
         */
        // 团购数量
        $statistics['groupbuy_verify_list'] = Model('groupbuy')->getGroupbuyCount(array('state' => 10));
        // 积分订单
        $statistics['points_order'] = Model()->cls()->table('points_order')->where(array('point_orderstate' => array('in', array(11, 20))))->count();
        //待审核账单
        $model_bill = Model('bill');
        $condition = array();
        $condition['ob_state'] = BILL_STATE_STORE_COFIRM;
        $statistics['check_billno'] = $model_bill->getOrderBillCount($condition);
        //待支付账单
        $condition = array();
        $condition['ob_state'] = BILL_STATE_SYSTEM_CHECK;
        $statistics['pay_billno'] = $model_bill->getOrderBillCount($condition);
        /**
         * CMS
         */
        if (C('cms_isuse')) {
            // 文章审核
            $statistics['cms_article_verify'] = Model('cms_article')->getCmsArticleCount(array('article_state' => 2));
            // 画报审核
            $statistics['cms_picture_verify'] = Model('cms_picture')->getCmsPictureCount(array('picture_state' => 2));
        }
        /**
         * 圈子
         */
        if (C('circle_isuse')) {
            $statistics['circle_verify'] = Model('circle')->getCircleUnverifiedCount();
        }

        echo json_encode($statistics);
        exit;
    }

    private function orgchartOp($type)
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');


        //查询社区数量
        $orgsql = 'select c.id ,  c.name , count(*) as num
                    from map_org_wechat a, Organization b , District c
                    where a.orgid = b.id and left(b.DistrictNumber,6) = c.id group by c.id, c.name order by count(*) desc  ';
        $orgstmt = $conn->query($orgsql);
        $orgdata_list = array();
        while ($row = $orgstmt->fetch(PDO::FETCH_OBJ)) {
            $detailsql = ' select id,name from  Organization  where DistrictNumber like \'' . $row->id . '%\' and id in (select orgid from map_org_wechat) ';
            $detailstmt = $conn->query($detailsql);
            $detail_list = array();
            while ($detailrow = $detailstmt->fetch(PDO::FETCH_NUM)) {
                array_push($detail_list, $detailrow);
            }
            $row->details = $detail_list;
            array_push($orgdata_list, $row);
        }
        return $orgdata_list;
    }


    private function salechartOp($type)
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        //查询销售情况
        $datesql = '';
        if ($type == '2') {
            $datesql = ' and checkout.dCO_Date>= \'' . date('Y-m-d', time()) . '\' and checkout.dCO_Date< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        } else if ($type == '3') {
            $datesql = ' and checkout.dCO_Date>= \'' . date('Y-m-1', time()) . '\' and checkout.dCO_Date< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        } else if ($type == '4') {
            $datesql = ' and checkout.dCO_Date>= \'' . date('Y-1-1', time()) . '\' and checkout.dCO_Date< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        } else if ($type == '5') {
            $datesql = ' and checkout.dCO_Date>= \'' . date('Y-m-d', strtotime('-1 day')) . '\' and checkout.dCO_Date< \'' . date('Y-m-d', time()) . '\'';
        } else if ($type == '6') {
            $datesql = ' and checkout.dCO_Date>= \'' . date('Y-m-d', strtotime('-1 month')) . '\' and checkout.dCO_Date< \'' . date('Y-m-1', time()) . '\'';
        } else if ($type == '7') {
            $datesql = ' and checkout.dCO_Date>= \'' . date('Y-m-d', strtotime('-1 year')) . '\' and checkout.dCO_Date< \'' . date('Y-1-1', time()) . '\'';
        }
        $sql = "select  b.id , b.name , sum(fCO_IncomeMoney) as num
                    from  Center_CheckOut checkout left join  Organization b   on checkout.OrgID = b.id
                    where checkout.OrgID in (select orgid from map_org_wechat) $datesql
              group by b.id , b.name order by sum(fCO_IncomeMoney) desc   ";
//        throw new Exception($sql);

        $stmt = $conn->query($sql);
        $salelist = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $row->details = array();
            array_push($salelist, $row);
        }
        return $salelist;
    }

    private function memberchartOp($type)
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        //查询充值情况
        $datesql = '';
        if ($type == '2') {
            $datesql = ' and checkout.RechargeDate>= \'' . date('Y-m-d', time()) . '\' and checkout.RechargeDate< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        } else if ($type == '3') {
            $datesql = ' and checkout.RechargeDate>= \'' . date('Y-m-1', time()) . '\' and checkout.RechargeDate< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        } else if ($type == '4') {
            $datesql = ' and checkout.RechargeDate>= \'' . date('Y-1-1', time()) . '\' and checkout.RechargeDate< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        } else if ($type == '5') {
            $datesql = ' and checkout.RechargeDate>= \'' . date('Y-m-d', strtotime('-1 day')) . '\' and checkout.RechargeDate< \'' . date('Y-m-d', time()) . '\'';
        } else if ($type == '6') {
            $datesql = ' and checkout.RechargeDate>= \'' . date('Y-m-d', strtotime('-1 month')) . '\' and checkout.RechargeDate< \'' . date('Y-m-1', time()) . '\'';
        } else if ($type == '7') {
            $datesql = ' and checkout.RechargeDate>= \'' . date('Y-m-d', strtotime('-1 year')) . '\' and checkout.RechargeDate< \'' . date('Y-1-1', time()) . '\'';
        }
        $sql = "select  b.id , b.name , sum(RechargeMoney) as num
                    from  center_MemberRecharge checkout left join  Organization b  on checkout.OrgID = b.id
                    where checkout.OrgID in (select orgid from map_org_wechat) and checkout.[type]=1 $datesql
              group by b.id , b.name having sum(RechargeMoney) >0 order by sum(RechargeMoney) desc   ";
        $stmt = $conn->query($sql);
        $memberlist = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $row->details = array();
            array_push($memberlist, $row);
        }
        return $memberlist;
    }

    private function consumechartOp($type)
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        //查询充值情况
        $datesql = '';
        if ($type == '2') {
            $datesql = ' and checkout.RechargeDate>= \'' . date('Y-m-d', time()) . '\' and checkout.RechargeDate< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        } else if ($type == '3') {
            $datesql = ' and checkout.RechargeDate>= \'' . date('Y-m-1', time()) . '\' and checkout.RechargeDate< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        } else if ($type == '4') {
            $datesql = ' and checkout.RechargeDate>= \'' . date('Y-1-1', time()) . '\' and checkout.RechargeDate< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        } else if ($type == '5') {
            $datesql = ' and checkout.RechargeDate>= \'' . date('Y-m-d', strtotime('-1 day')) . '\' and checkout.RechargeDate< \'' . date('Y-m-d', time()) . '\'';
        } else if ($type == '6') {
            $datesql = ' and checkout.RechargeDate>= \'' . date('Y-m-d', strtotime('-1 month')) . '\' and checkout.RechargeDate< \'' . date('Y-m-1', time()) . '\'';
        } else if ($type == '7') {
            $datesql = ' and checkout.RechargeDate>= \'' . date('Y-m-d', strtotime('-1 year')) . '\' and checkout.RechargeDate< \'' . date('Y-1-1', time()) . '\'';
        }
        $sql = "select  b.id , b.name , sum(-RechargeMoney) as num
                    from  center_MemberRecharge checkout left join  Organization b  on checkout.OrgID = b.id
                    where checkout.OrgID in (select orgid from map_org_wechat) and checkout.[type]=2 $datesql
              group by b.id , b.name having sum(-RechargeMoney) >0 order by sum(-RechargeMoney) desc   ";
        $stmt = $conn->query($sql);
        $consumelist = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $row->details = array();
            array_push($consumelist, $row);
        }
        return $consumelist;
    }

    private function spotchartOp($type)
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        //查询回访情况
        $datesql = '';
        if ($type == '2') {
            $datesql = ' and checkout.checkdate>= \'' . date('Y-m-d', time()) . '\' and checkout.checkdate< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        } else if ($type == '3') {
            $datesql = ' and checkout.checkdate>= \'' . date('Y-m-1', time()) . '\' and checkout.checkdate< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        } else if ($type == '4') {
            $datesql = ' and checkout.checkdate>= \'' . date('Y-1-1', time()) . '\' and checkout.checkdate< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        } else if ($type == '5') {
            $datesql = ' and checkout.checkdate>= \'' . date('Y-m-d', strtotime('-1 day')) . '\' and checkout.checkdate< \'' . date('Y-m-d', time()) . '\'';
        } else if ($type == '6') {
            $datesql = ' and checkout.checkdate>= \'' . date('Y-m-d', strtotime('-1 month')) . '\' and checkout.checkdate< \'' . date('Y-m-1', time()) . '\'';
        } else if ($type == '7') {
            $datesql = ' and checkout.checkdate>= \'' . date('Y-m-d', strtotime('-1 year')) . '\' and checkout.checkdate< \'' . date('Y-1-1', time()) . '\'';
        }
        $sql = "select  b.id , b.name , count(1) as num
                    from  spotcheck_main checkout left join  Organization b  on checkout.OrgID = b.id
                    where checkout.OrgID in (select orgid from map_org_wechat) and checktype like '5%' $datesql
              group by b.id , b.name having count(1)  >0 order by count(1)  desc   ";

        $stmt = $conn->query($sql);
        $spotlist = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $row->details = array();
            array_push($spotlist, $row);
        }
        return $spotlist;
    }

    private function healthfilechartOp($type)
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        //查询健康档案情况
        $datesql = '';
        if ($type == '2') {
            $datesql = ' and hf.InputDate>= \'' . date('Y-m-d', time()) . '\' and hf.InputDate< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        } else if ($type == '3') {
            $datesql = ' and hf.InputDate>= \'' . date('Y-m-1', time()) . '\' and hf.InputDate< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        } else if ($type == '4') {
            $datesql = ' and hf.InputDate>= \'' . date('Y-1-1', time()) . '\' and hf.InputDate< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        } else if ($type == '5') {
            $datesql = ' and hf.InputDate>= \'' . date('Y-m-d', strtotime('-1 day')) . '\' and hf.InputDate< \'' . date('Y-m-d', time()) . '\'';
        } else if ($type == '6') {
            $datesql = ' and hf.InputDate>= \'' . date('Y-m-d', strtotime('-1 month')) . '\' and hf.InputDate< \'' . date('Y-m-1', time()) . '\'';
        } else if ($type == '7') {
            $datesql = ' and hf.InputDate>= \'' . date('Y-m-d', strtotime('-1 year')) . '\' and hf.InputDate< \'' . date('Y-1-1', time()) . '\'';
        }
        $sql = "select  b.id , b.name , count(1) as num
                    from  healthfile hf , sam_taxempcode checkout left join  Organization b  on checkout.org_id = b.id
                    where hf.inputpersonid = checkout.loginname and checkout.org_id in (select orgid from map_org_wechat) $datesql
              group by b.id , b.name having count(1)  >0 order by count(1)  desc  ";
        $stmt = $conn->query($sql);
        $healthfilelist = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $row->details = array();
            array_push($healthfilelist, $row);
        }

        return $healthfilelist;
    }

    private function healthfilespotchartOp($type)
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        //查询健康档案回访情况
        $datesql = '';
        if ($type == '2') {
            $datesql = ' and checkout.checkdate>= \'' . date('Y-m-d', time()) . '\' and checkout.checkdate< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        } else if ($type == '3') {
            $datesql = ' and checkout.checkdate>= \'' . date('Y-m-1', time()) . '\' and checkout.checkdate< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        } else if ($type == '4') {
            $datesql = ' and checkout.checkdate>= \'' . date('Y-1-1', time()) . '\' and checkout.checkdate< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        } else if ($type == '5') {
            $datesql = ' and checkout.checkdate>= \'' . date('Y-m-d', strtotime('-1 day')) . '\' and checkout.checkdate< \'' . date('Y-m-d', time()) . '\'';
        } else if ($type == '6') {
            $datesql = ' and checkout.checkdate>= \'' . date('Y-m-d', strtotime('-1 month')) . '\' and checkout.checkdate< \'' . date('Y-m-1', time()) . '\'';
        } else if ($type == '7') {
            $datesql = ' and checkout.checkdate>= \'' . date('Y-m-d', strtotime('-1 year')) . '\' and checkout.checkdate< \'' . date('Y-1-1', time()) . '\'';
        }
        $sql = "select  b.id , b.name , count(1) as num
                    from  spotcheck_main checkout left join  Organization b  on checkout.OrgID = b.id
                    where checkout.OrgID in (select orgid from map_org_wechat) and checktype like '0%' $datesql
              group by b.id , b.name having count(1)  >0 order by count(1)  desc   ";
        $stmt = $conn->query($sql);
        $healthfilespotlist = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $row->details = array();
            array_push($healthfilespotlist, $row);
        }

        return $healthfilespotlist;
    }

    private function healthbusinesschartOp($type)
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        //查询业务开展数量情况
        $datesql = '';
        if ($type == '2') {
            $datesql = ' and a.InputDate>= \'' . date('Y-m-d', time()) . '\' and a.InputDate< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        } else if ($type == '3') {
            $datesql = ' and a.InputDate>= \'' . date('Y-m-1', time()) . '\' and a.InputDate< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        } else if ($type == '4') {
            $datesql = ' and a.InputDate>= \'' . date('Y-1-1', time()) . '\' and a.InputDate< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        } else if ($type == '5') {
            $datesql = ' and a.InputDate>= \'' . date('Y-m-d', strtotime('-1 day')) . '\' and a.InputDate< \'' . date('Y-m-d', time()) . '\'';
        } else if ($type == '6') {
            $datesql = ' and a.InputDate>= \'' . date('Y-m-d', strtotime('-1 month')) . '\' and a.InputDate< \'' . date('Y-m-1', time()) . '\'';
        } else if ($type == '7') {
            $datesql = ' and a.InputDate>= \'' . date('Y-m-d', strtotime('-1 year')) . '\' and a.InputDate< \'' . date('Y-1-1', time()) . '\'';
        }
        $sql = "select id , name ,sum(num) num
                from(
                select c.id , c.name ,count(1) num from MedicalExam  a  , sam_taxempcode b ,   Organization c
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by c.id , c.name
                union all
                select c.id , c.name ,count(1) num from HealthFileMaternal  a  , sam_taxempcode b ,   Organization c
                where a.InputPersonID = b.loginname and   b.org_id = c.id  and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by c.id , c.name
                union all
                select c.id , c.name ,count(1) num from FirstVistBeforeBorn  a  , sam_taxempcode b ,   Organization c
                where a.InputPersonID = b.loginname and   b.org_id = c.id  and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by c.id , c.name
                union all
                select c.id , c.name ,count(1) num from VisitBeforeBorn  a  , sam_taxempcode b ,   Organization c
                where a.InputPersonID = b.loginname and   b.org_id = c.id  and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by c.id , c.name
                union all
                select c.id , c.name ,count(1) num from VisitAfterBorn  a  , sam_taxempcode b ,   Organization c
                where a.InputPersonID = b.loginname and   b.org_id = c.id  and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by c.id , c.name
                union all
                select c.id , c.name ,count(1) num from HealthFileChildren  a  , sam_taxempcode b ,   Organization c
                where a.InputPersonID = b.loginname and   b.org_id = c.id  and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by c.id , c.name
                union all
                select c.id , c.name ,count(1) num from ChildrenMediExam  a  , sam_taxempcode b ,   Organization c
                where a.InputPersonID = b.loginname and   b.org_id = c.id  and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by c.id , c.name
                union all
                select c.id , c.name ,count(1) num from ChildrenMediExam3_6  a  , sam_taxempcode b ,   Organization c
                where a.InputPersonID = b.loginname and   b.org_id = c.id  and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by c.id , c.name
                union all
                select c.id , c.name ,count(1) num from HypertensionVisit  a  , sam_taxempcode b ,   Organization c
                where a.InputPersonID = b.loginname and   b.org_id = c.id  and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by c.id , c.name
                union all
                select c.id , c.name ,count(1) num from DiabetesVisit  a  , sam_taxempcode b ,   Organization c
                where a.InputPersonID = b.loginname and   b.org_id = c.id  and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by c.id , c.name
                union all
                select c.id , c.name ,count(1) num from FuriousVisit  a  , sam_taxempcode b ,   Organization c
                where a.InputPersonID = b.loginname and   b.org_id = c.id  and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by c.id , c.name
                ) uniontable
                group by id ,name
                order by sum(num) desc   ";
        $stmt = $conn->query($sql);
        $healthbusinesslist = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $row->details = array();
            array_push($healthbusinesslist, $row);
        }

        return $healthbusinesslist;
    }

    public function chartOp()
    {
        $statistics = array();

        //查询社区数量
        $statistics['orgdata'] = $this->orgchartOp('1');
        //查询销售情况
        $statistics['saledata'] = $this->salechartOp('1');
        //查询充值情况
        $statistics['memberdata'] = $this->memberchartOp('1');
        //查询充值情况
        $statistics['consumedata'] = $this->consumechartOp('1');
        //查询回访情况
        $statistics['spotdata'] = $this->spotchartOp('1');
        //查询健康档案情况
        $statistics['healthdata'] = $this->healthfilechartOp('1');
        //查询健康档案回访情况
        $statistics['healthspotdata'] = $this->healthfilespotchartOp('1');
        //查询业务开展数量情况
        $statistics['healthbusinessdata'] = $this->healthbusinesschartOp('1');
        echo json_encode($statistics);
        exit;
    }

    public function chartdetailOp()
    {
        $func = $_GET['opt'] . 'chartOp';
        echo json_encode($this->$func($_GET['type']));
    }

    private function businessCount($type)
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        //查询业务开展数量情况
        $timenum = 5; //必须能被60整除
        $timetype = 'Y-m-d H:i:s';
        $timestr = $type * $timenum + " second ";

        $now = getdate();
        $seconds = $now['seconds'];
        if ($seconds < $timenum) {
            $begitime = 0;
        } else {
            $begitime = $seconds - $seconds % $timenum;
        }
        $endtime = $begitime + $timenum;

        $begindatetime = new DateTime();
        date_time_set($begindatetime, 0, 0, $begitime);
        date_add($begindatetime, date_interval_create_from_date_string($timestr));
        $enddatetime = new DateTime();
        date_time_set($enddatetime, 0, 0, $endtime);
        date_add($enddatetime, date_interval_create_from_date_string($timestr));
        $strbegin = date_format($begindatetime, $timetype);
        $strend = date_format($enddatetime, $timetype);
        $datesql = ' and a.InputDate>= \'' . $strbegin . '\' and a.InputDate< \'' . $strend . '\'';
        $sql = "select  sum(num) num
                from(
                select count(1) num from MedicalExam  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname  and b.org_id in (select orgid from map_org_wechat)
                $datesql
                union all
                select count(1) num from HealthFileMaternal  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                union all
                select count(1) num from FirstVistBeforeBorn  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname  and b.org_id in (select orgid from map_org_wechat)
                $datesql
                union all
                select count(1) num from VisitBeforeBorn  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                union all
                select count(1) num from VisitAfterBorn  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                union all
                select count(1) num from HealthFileChildren  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                union all
                select count(1) num from ChildrenMediExam  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                union all
                select count(1) num from ChildrenMediExam3_6  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                union all
                select count(1) num from HypertensionVisit  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                union all
                select count(1) num from DiabetesVisit  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                union all
                select count(1) num from FuriousVisit  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                ) uniontable   ";
        $stmt = $conn->query($sql);
        $ret = array();
        $ret['begintime'] = $begitime;
        $ret['num'] = $stmt->fetch(PDO::FETCH_NUM)[0];
//        $ret['sql'] = $sql;
        return $ret;
    }
    private function businessCounttest($type){
      $ret = array();
      $now = getdate();
      $seconds = $now['seconds'];
      if ($seconds < $timenum) {
          $begitime = 0;
      } else {
          $begitime = $seconds - $seconds % 5;
      }
      $ret['begintime'] = $begitime;
      $ret['num'] = rand();
      return $ret;
    }

    public function busidataOp(){
       echo json_encode($this->businessCount(0));
       exit;
    }


    public function newchartOp()
    {
        $ret = array();
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
//        1,新增档案数 /档案总数
        $stmt = $conn->query("select count(*) from healthfile where status=0");
        $ret["file_count"] = $stmt->fetch(PDO::FETCH_NUM)[0];
        $stmt = $conn->query("select count(*) from healthfile where inputdate>=convert(date,getdate()) and inputdate<dateadd(day,1,convert(date,getdate())) and status=0");
        $ret["file_new"] = $stmt->fetch(PDO::FETCH_NUM)[0];
//        2,孕妇新增建册 /新增结案/未结案总数
        $stmt = $conn->query("select count(*) from HealthFileMaternal where IsClosed=0");
        $ret["pregnant_count"] = $stmt->fetch(PDO::FETCH_NUM)[0];
        $stmt = $conn->query("select count(*) from HealthFileMaternal where IsClosed=0 and inputdate>=convert(date,getdate()) and inputdate<dateadd(day,1,convert(date,getdate()))");
        $ret["pregnant_new"] = $stmt->fetch(PDO::FETCH_NUM)[0];
        $stmt = $conn->query("select count(*) from HealthFileMaternal where IsClosed=1 and inputdate>=convert(date,getdate()) and inputdate<dateadd(day,1,convert(date,getdate()))");
        $ret["pregnant_close"] = $stmt->fetch(PDO::FETCH_NUM)[0];
//        3,高血压/糖尿病/重性精神病 总数
        $stmt = $conn->query("select count(*) from healthfile a,  personalinfo b ,diseasehistory c  where a.fileno = b.fileno and a.status = 0 and b.id = c.personalinfoid and c.diseaseid = 2");
        $ret["chronic_hyp"] = $stmt->fetch(PDO::FETCH_NUM)[0];
        $stmt = $conn->query("select count(*) from healthfile a,  personalinfo b ,diseasehistory c  where a.fileno = b.fileno and a.status = 0 and b.id = c.personalinfoid and c.diseaseid = 3");
        $ret["chronic_diab"] = $stmt->fetch(PDO::FETCH_NUM)[0];
        $stmt = $conn->query("select count(*) from healthfile a,  personalinfo b ,diseasehistory c  where a.fileno = b.fileno and a.status = 0 and b.id = c.personalinfoid and c.diseaseid = 8");
        $ret["chronic_holergasia"] = $stmt->fetch(PDO::FETCH_NUM)[0];
//        4,传染病报告数
        $ret["infectious_count"] = 0;
        $ret["infectious_new"] = 0;
//        5,公卫开展业务数
        $ret['busi_counts'] = array(
            $this->businessCount(-9),
            $this->businessCount(-8),
            $this->businessCount(-7),
            $this->businessCount(-6),
            $this->businessCount(-5),
            $this->businessCount(-4),
            $this->businessCount(-3),
            $this->businessCount(-2),
            $this->businessCount(-1),
            $this->businessCount(0));
//        6, 当天各个医疗机构的收入柱状图
        $stmt = $conn->query(" select  b.id , b.name , sum(fCO_IncomeMoney) as num
                    from  Center_CheckOut checkout left join  Organization b   on checkout.OrgID = b.id
                    where checkout.OrgID in (select orgid from map_org_wechat) and
                    dCO_Date>=convert(date,getdate()) and dCO_Date<dateadd(day,1,convert(date,getdate()))
              group by b.id , b.name order by  b.id desc   ");
        $ret['income_counts'] = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($ret['income_counts'], $row);
        }
//        7, 当天各个医疗机构的门诊住院人次柱状图
        $stmt = $conn->query("  select id,name, sum(num) num
                                from (
                                select org.id ,org.name , count(1) as num from Center_ClinicLog a  , Organization org  where  a.orgid = org.id
                                group by org.id ,org.name
                                union all
                                select org.id ,org.name , count(1) as num  from Center_InpatientLog a  , Organization org  where  a.orgid = org.id
                                group by org.id ,org.name
                                ) sumtable group by id ,name order by id");
        $ret['preperson_counts'] = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($ret['preperson_counts'], $row);
        }
//        8,门诊和住院收入的当月折线30天
        $stmt = $conn->query(" select  year(dCO_Date) syear, month(dCO_Date) smonth,day(dCO_Date) sday, sum(fCO_IncomeMoney) as num
                    from  Center_CheckOut checkout left join  Organization b   on checkout.OrgID = b.id
                    where checkout.OrgID in (select orgid from map_org_wechat) and
                    dCO_Date>=dateadd(day,-30,convert(date,getdate())) and dCO_Date<dateadd(day,1,convert(date,getdate()))
                    group by  year(dCO_Date), month(dCO_Date),day(dCO_Date)
                     order by  year(dCO_Date), month(dCO_Date),day(dCO_Date)    ");
        $ret['income_30days'] = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($ret['income_30days'], $row);
        }
//        9. 妇保业务
        //妇保业务总数
        $datesql = ' ';
        $stmt = $conn->query(
            "select  sum(num) num
                from(
                select count(1) num from HealthFileMaternal  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                union all
                select count(1) num from FirstVistBeforeBorn  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname  and b.org_id in (select orgid from map_org_wechat)
                $datesql
                union all
                select count(1) num from VisitBeforeBorn  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                union all
                select count(1) num from VisitAfterBorn  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                ) uniontable   "
        );
        $ret["pregnantbusi_count"] = $stmt->fetch(PDO::FETCH_NUM)[0];
        //妇保业务当月数
        $datesql = ' and a.InputDate>= \'' . date('Y-m-1', time()) . '\' and a.InputDate< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        $stmt = $conn->query(
            "select  sum(num) num
                from(
                select count(1) num from HealthFileMaternal  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                union all
                select count(1) num from FirstVistBeforeBorn  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname  and b.org_id in (select orgid from map_org_wechat)
                $datesql
                union all
                select count(1) num from VisitBeforeBorn  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                union all
                select count(1) num from VisitAfterBorn  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                ) uniontable   "
        );
        $ret["pregnantbusi_monthcount"] = $stmt->fetch(PDO::FETCH_NUM)[0];
        $datesql = ' and a.InputDate>= \'' . date('Y-m-d',strtotime('-14 day')) . '\' and a.InputDate< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        $stmt = $conn->query( "select  year(inputdate) syear, month(inputdate) smonth,day(inputdate) sday,  sum(num) num
                from(
                select a.inputdate ,count(1) num from HealthFileMaternal  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by a.inputdate
                union all
                select a.inputdate ,count(1) num from FirstVistBeforeBorn  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname  and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by a.inputdate
                union all
                select a.inputdate ,count(1) num from VisitBeforeBorn  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by a.inputdate
                union all
                select a.inputdate ,count(1) num from VisitAfterBorn  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by a.inputdate
                ) uniontable
                 group by  year(inputdate), month(inputdate),day(inputdate)
                     order by  year(inputdate), month(inputdate),day(inputdate)   ");
        $ret['pregnantbusi_14day'] = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($ret['pregnantbusi_14day'], $row);
        }
//        10,儿保业务
        //儿保业务总数
        $datesql = ' ';
        $stmt = $conn->query(
            "select  sum(num) num
                from(
                select count(1) num from HealthFileChildren  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                union all
                select count(1) num from ChildrenMediExam  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                union all
                select count(1) num from ChildrenMediExam3_6  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                ) uniontable   "
        );
        $ret["childbusi_count"] = $stmt->fetch(PDO::FETCH_NUM)[0];
        //儿保业务当月数
        $datesql = ' and a.InputDate>= \'' . date('Y-m-1', time()) . '\' and a.InputDate< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        $stmt = $conn->query(
            "select  sum(num) num
                from(
                select count(1) num from HealthFileChildren  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                union all
                select count(1) num from ChildrenMediExam  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                union all
                select count(1) num from ChildrenMediExam3_6  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                ) uniontable   "
        );
        $ret["childbusi_monthcount"] = $stmt->fetch(PDO::FETCH_NUM)[0];
        //儿保14天数据
        $datesql = ' and a.InputDate>= \'' . date('Y-m-1',strtotime('-14 day')) . '\' and a.InputDate< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        $stmt = $conn->query( "select  year(inputdate) syear, month(inputdate) smonth,day(inputdate) sday,  sum(num) num
                from(
                select a.inputdate,count(1) num from HealthFileChildren  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by a.inputdate
                union all
                select a.inputdate, count(1) num from ChildrenMediExam  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by a.inputdate
                union all
                select a.inputdate, count(1) num from ChildrenMediExam3_6  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by a.inputdate
                ) uniontable
                 group by  year(inputdate), month(inputdate),day(inputdate)
                     order by  year(inputdate), month(inputdate),day(inputdate)   ");
        $ret['childbusi_14day'] = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($ret['childbusi_14day'], $row);
        }
//        11,慢病业务
        //慢病业务总数
        $datesql = ' ';
        $stmt = $conn->query(
            "select  sum(num) num
                from(
                select count(1) num from HypertensionVisit  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                union all
                select count(1) num from DiabetesVisit  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                union all
                select count(1) num from FuriousVisit  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                ) uniontable   "
        );
        $ret["chronicbusi_count"] = $stmt->fetch(PDO::FETCH_NUM)[0];
        //慢病业务当月数
        $datesql = ' and a.InputDate>= \'' . date('Y-m-1', time()) . '\' and a.InputDate< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        $stmt = $conn->query(
            "select  sum(num) num
                from(
                select count(1) num from HypertensionVisit  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                union all
                select count(1) num from DiabetesVisit  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                union all
                select count(1) num from FuriousVisit  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                ) uniontable   "
        );
        $ret["chronicbusi_monthcount"] = $stmt->fetch(PDO::FETCH_NUM)[0];
        //慢病14天数据
        $datesql = ' and a.InputDate>= \'' . date('Y-m-1',strtotime('-14 day')) . '\' and a.InputDate< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        $stmt = $conn->query( "select  year(inputdate) syear, month(inputdate) smonth,day(inputdate) sday,  sum(num) num
                from(
                select a.inputdate,count(1) num from HypertensionVisit  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by a.inputdate
                union all
                select a.inputdate, count(1) num from DiabetesVisit  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by a.inputdate
                union all
                select a.inputdate, count(1) num from FuriousVisit  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by a.inputdate
                ) uniontable
                 group by  year(inputdate), month(inputdate),day(inputdate)
                     order by  year(inputdate), month(inputdate),day(inputdate)   ");
        $ret['chronicbusi_14day'] = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($ret['chronicbusi_14day'], $row);
        }
//        12,分娩业务
        $datesql = ' ';
        $stmt = $conn->query(
            "   select count(1) num from ChildBirthRecord  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)   "
        );
        $ret["childbirth_count"] = $stmt->fetch(PDO::FETCH_NUM)[0];
        //分娩业务当月数
        $datesql = ' and a.InputDate>= \'' . date('Y-m-1', time()) . '\' and a.InputDate< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        $stmt = $conn->query(   "select count(1) num from ChildBirthRecord  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql  "
        );
        $ret["childbirth_monthcount"] = $stmt->fetch(PDO::FETCH_NUM)[0];
        //分娩14天数据
        $datesql = ' and a.InputDate>= \'' . date('Y-m-1',strtotime('-14 day')) . '\' and a.InputDate< \'' . date('Y-m-d', strtotime('+1 day')) . '\'';
        $stmt = $conn->query( "select  year(inputdate) syear, month(inputdate) smonth,day(inputdate) sday,  count(1) num
                from ChildBirthRecord  a  , sam_taxempcode b
                where a.InputPersonID = b.loginname   and b.org_id in (select orgid from map_org_wechat)
                $datesql
                group by  year(inputdate), month(inputdate),day(inputdate)
                     order by  year(inputdate), month(inputdate),day(inputdate)   ");
        $ret['childbirth_14day'] = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($ret['childbirth_14day'], $row);
        }
//        13,卫生监督
        $ret['health_14day'] = array();
//        14,档案分布
        //男女比例
        $stmt = $conn->query("select b.sex, count(*) from healthfile  a, personalinfo b  where status=0 and a.fileno = b.fileno group by b.sex order by sex");
        $result = $stmt->fetch(PDO::FETCH_NUM);
        $ret["file_male_count"] = $result[0];
        $ret["file_fmale_count"] = $result[1];
        //老年人比例
        $stmt = $conn->query("select  count(*) from healthfile  a, personalinfo b  where status=0 and a.fileno = b.fileno
                    and b.birthday <=convert(datetime,convert(nvarchar,(YEAR(getdate())-64))+'-01-01')
                    ");
        $ret["file_old_count"] = $stmt->fetch(PDO::FETCH_NUM)[0];
        //孕产妇比例
        $stmt = $conn->query("select  count(*) from healthfile  a, personalinfo b  where status=0 AND a.fileno =b.fileno and
                      a.fileno in (select fileno from HealthFileMaternal where IsClosed = 0 )
                    ");
        $ret["file_pregnant_count"] = $stmt->fetch(PDO::FETCH_NUM)[0];
        //儿童比例
        $stmt = $conn->query("select  count(*) from healthfile  a, personalinfo b  where status=0 and a.fileno = b.fileno
                    and b.birthday >=convert(datetime,convert(nvarchar,(YEAR(getdate())-7))+'-01-01')
                    ");
        $ret["file_child_count"] = $stmt->fetch(PDO::FETCH_NUM)[0];
        //慢病比例
        $stmt = $conn->query("select  count(*) from healthfile  a, personalinfo b  where status=0 and a.fileno = b.fileno
                    and b.id in( select  personalinfoid from diseasehistory  where  diseaseid in(2,3,8) )
                    ");
        $ret["file_chronic_count"] = $stmt->fetch(PDO::FETCH_NUM)[0];
        $ret["file_other_count"] = $ret["file_count"] - $ret["file_old_count"] -  $ret["file_pregnant_count"] - $ret["file_child_count"] - $ret["file_chronic_count"];
        echo json_encode($ret);
        exit;
    }
}
