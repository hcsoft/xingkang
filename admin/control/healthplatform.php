<?php
/**
 * 统计管理
 *
 * @copyright  Copyright (c) 2014-2020 SZGR Inc. (http://www.szgr.com.cn)
 * @license    http://www.szgr.com.cn
 * @link       http://www.szgr.com.cn
 * @since      File available since Release v1.1
 */
defined('InShopNC') or exit('Access Invalid!');
require(BASE_DATA_PATH . '/../core/framework/db/mssql.php');
class healthplatformControl extends SystemControl
{

    public function __construct()
    {
        parent::__construct();
        Language::read('stat');
        import('function.statistics');
        import('function.datehelper');
        $model = Model('stat');
        //存储参数
        $this->search_arr = $_REQUEST;
        //处理搜索时间
        if (in_array($_REQUEST['op'], array('list'))) {
            $this->search_arr = $model->dealwithSearchTime($this->search_arr);
            //获得系统年份
            $year_arr = getSystemYearArr();
            //获得系统月份
            $month_arr = getSystemMonthArr();
            //获得本月的周时间段
            $week_arr = getMonthWeekArr($this->search_arr['week']['current_year'], $this->search_arr['week']['current_month']);
            Tpl::output('year_arr', $year_arr);
            Tpl::output('month_arr', $month_arr);
            Tpl::output('week_arr', $week_arr);
        }
        Tpl::output('search_arr', $this->search_arr);
    }

    /**
     * 新增会员
     */
    public function indexOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        $page = new Page();
        $page->setEachNum(10);

        $page->setNowPage($_REQUEST["curpage"]);
        $startnum = $page->getEachNum() * ($page->getNowPage() - 1);
        $endnum = $page->getEachNum() * ($page->getNowPage());
        $sql = 'from spotcheck_main a  left join spotcheck_spot spot on a.id = spot.spotid, export_main b , healthfile hf, personalinfo info
         where a.checktype = b.id and b.type=21 and a.fileno = hf.fileno and hf.fileno = info.fileno ';
        if ($_REQUEST['search_phone']) {
            $sql = $sql . ' and a.phone like \'%' . $_REQUEST['search_phone'] . '%\'';
        }


        if ($_REQUEST['search_name']) {
            $sql = $sql . ' and hf.name like \'%' . $_REQUEST['search_name'] . '%\'';
        }
        if ($_REQUEST['spot_start_time']) {
            $sql = $sql . ' and a.checkdate >=\'' . $_REQUEST['spot_start_time'] . '\'';
        }

        if ($_REQUEST['spot_end_time']) {
            $sql = $sql . ' and a.checkdate < dateadd(day,1,\'' . $_REQUEST['spot_end_time'] . '\')';
        }

        if ($_REQUEST['input_start_time']) {
            $sql = $sql . ' and a.inputdate >=\'' . $_REQUEST['input_start_time'] . '\'';
        }

        if ($_REQUEST['input_end_time']) {
            $sql = $sql . ' and a.inputdate < dateadd(day,1,\'' . $_REQUEST['input_end_time'] . '\')';
        }


        if ($_REQUEST['search_spot']) {
            $search_spot = $_REQUEST['search_spot'];
            if ($search_spot == 10) {
                $sql = $sql . ' and  exists ( select 1 from spotcheck_spot where spotid = a.id)';
            } elseif ($search_spot == 20) {
                $sql = $sql . ' and not exists ( select 1 from spotcheck_spot where spotid = a.id)';
            }
        }

        if ($_REQUEST['search_result']) {
            $search_result = $_REQUEST['search_result'];
            if ($search_result && count($search_result)>0) {
                $sql = $sql . ' and  spot.result = \''.$search_result.'\'';
            }
        }

        if ($_REQUEST['search_spottype']) {
            $search_result = $_REQUEST['search_spottype'];
            if ($search_result && count($search_result)>0) {
                $sql = $sql . ' and  a.checktype = \''.$search_result.'\'';
            }
        }


        $countsql = " select count(*)  $sql ";
//        echo $countsql;
        $stmt = $conn->query($countsql);
//        echo $countsql;
        $total = $stmt->fetch(PDO::FETCH_NUM);
        $page->setTotalNum($total[0]);
        $tsql = "SELECT * FROM  ( SELECT  * FROM (SELECT TOP $endnum row_number() over( order by  a.inputdate) rownum,
                        a.id,
                        a.checkdate
                        ,a.checkopt
                        ,b.name  'checktype'
                        ,a.checktable
                        ,a.checktableid
                        ,a.fileno
                        ,a.name
                        ,a.sex
                        ,a.birthday
                        ,a.phone
                        ,a.address
                        ,a.itype
                        ,a.getgift
                        ,a.bischronic
                        ,a.bisservant
                        ,a.serversatisfaction
                        ,a.oftenbuydrug
                        ,a.acceptreason
                        ,a.advices
                        ,a.elseneed
                        ,a.remark
                        ,a.inputdate
                        ,a.updatedate
                            $sql order by  a.inputdate)zzzz where rownum>$startnum )zzzzz order by rownum";
        $stmt = $conn->query($tsql);
        $data_list = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($data_list, $row);
            $newstmt = $conn->query(" select * from spotcheck_spot where spotid = '$row->id'");
            $row->spotinfo = $newstmt->fetch(PDO::FETCH_OBJ);
        }
        Tpl::output('data_list', $data_list);
        Tpl::output('page', $page->show());
        Tpl::showpage('healthplatform.index');
    }


    public function ajaxOp()
    {
        //spotcheck_spot
        try {
            $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
            $id = $_REQUEST['id'];
            $spotid = $_REQUEST['spotid'];
            $spotdate = $_REQUEST['spotdate'];
            $result = $_REQUEST['spotresult'] == null ?"":$_REQUEST['spotresult'];
            $reason = $_REQUEST['reason'] == null ?"":$_REQUEST['reason'];
            $sql = " insert into spotcheck_spot (spotid,spotdate,result,reason,inputdate) values('$spotid','$spotdate','$result','$reason',getdate())";
            $conn->exec($sql);
            echo json_encode(array('success' => true, 'msg' => '保存成功!'));
        } catch (Exception $e) {
            echo json_encode(array('success' => false, 'msg' => '异常!'.$e->getMessage()));
        }
        exit;
    }

    public function statisticalOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        $sql = ' from spotcheck_main a  left join spotcheck_spot spot on a.id = spot.spotid,
         export_main b , healthfile hf, personalinfo info,
         Organization org , sam_taxempcode emp
         where a.checktype = b.id and b.type=21 and a.fileno = hf.fileno
         and hf.fileno = info.fileno and a.checkopt = emp.loginname
         and emp.org_id = org.id  ';
        if ($_REQUEST['search_phone']) {
            $sql = $sql . ' and a.phone like \'%' . $_REQUEST['search_phone'] . '%\'';
        }


        if ($_REQUEST['search_name']) {
            $sql = $sql . ' and hf.name like \'%' . $_REQUEST['search_name'] . '%\'';
        }
        if ($_REQUEST['spot_start_time']) {
            $sql = $sql . ' and a.checkdate >=\'' . $_REQUEST['spot_start_time'] . '\'';
        }

        if ($_REQUEST['spot_end_time']) {
            $sql = $sql . ' and a.checkdate < dateadd(day,1,\'' . $_REQUEST['spot_end_time'] . '\')';
        }

        if ($_REQUEST['input_start_time']) {
            $sql = $sql . ' and a.inputdate >=\'' . $_REQUEST['input_start_time'] . '\'';
        }

        if ($_REQUEST['input_end_time']) {
            $sql = $sql . ' and a.inputdate < dateadd(day,1,\'' . $_REQUEST['input_end_time'] . '\')';
        }


        if ($_REQUEST['search_spot']) {
            $search_spot = $_REQUEST['search_spot'];
            if ($search_spot == 10) {
                $sql = $sql . ' and  exists ( select 1 from spotcheck_spot where spotid = a.id)';
            } elseif ($search_spot == 20) {
                $sql = $sql . ' and not exists ( select 1 from spotcheck_spot where spotid = a.id)';
            }
        }

        if ($_REQUEST['search_result']) {
            $search_result = $_REQUEST['search_result'];
            if ($search_result && count($search_result)>0) {
                $sql = $sql . ' and  spot.result = \''.$search_result.'\'';
            }
        }

        if ($_REQUEST['search_spottype']) {
            $search_result = $_REQUEST['search_spottype'];
            if ($search_result && count($search_result)>0) {
                $sql = $sql . ' and  a.checktype = \''.$search_result.'\'';
            }
        }

        $tsql = "select org.id , org.name ,
            sum(case when spot.spotid is not null then 1 else 0 end) all_spot_count,
            sum(case when spot.spotid is not null and spot.result = '通过' then 1 else 0 end) all_spot_access_count,
            sum(1) all_check_count,

            sum(case when a.checktype=13 and spot.spotid is not null then 1 else 0 end) health_spot_count,
            sum(case when a.checktype=13 and spot.spotid is not null and spot.result = '通过' then 1 else 0 end) health_spot_access_count,
            sum(case when a.checktype=13 then 1 else 0 end) health_check_count,

            sum(case when a.checktype=14 and spot.spotid is not null then 1 else 0 end) old_spot_count,
            sum(case when a.checktype=14 and spot.spotid is not null and spot.result = '通过' then 1 else 0 end) old_spot_access_count,
            sum(case when a.checktype=14 then 1 else 0 end) old_check_count,

            sum(case when a.checktype=15 and spot.spotid is not null then 1 else 0 end) hyp_spot_count,
            sum(case when a.checktype=15 and spot.spotid is not null and spot.result = '通过' then 1 else 0 end) hyp_spot_access_count,
            sum(case when a.checktype=15 then 1 else 0 end) hyp_check_count,

            sum(case when a.checktype=16 and spot.spotid is not null then 1 else 0 end) diab_spot_count,
            sum(case when a.checktype=16 and spot.spotid is not null and spot.result = '通过' then 1 else 0 end) diab_spot_access_count,
            sum(case when a.checktype=16 then 1 else 0 end) diab_check_count

           $sql group by  org.id , org.name ";
//        echo $tsql;
        $stmt = $conn->query($tsql);
        $data_list = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($data_list, $row);
            $newstmt = $conn->query(" select * from spotcheck_spot where spotid = '$row->id'");
            $row->spotinfo = $newstmt->fetch(PDO::FETCH_OBJ);
        }
        Tpl::output('data_list', $data_list);
        Tpl::showpage('healthplatform.statistical');
    }

    public function testOp()
    {
        $test = Model('member');
        Tpl::output('test', var_dump($test->tableInfo('sam_taxempcode')));
        Tpl::showpage('healthplatform.test');
    }

}
