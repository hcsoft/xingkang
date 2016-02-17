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
        $conn = require (BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        $treesql = 'select  b.id , b.name,b.districtnumber,b.parentid pId from map_org_wechat a, Organization b where a.orgid = b.id ';
        $treestmt = $conn->query ( $treesql );
        $this->treedata_list = array ();
        while ( $row = $treestmt->fetch ( PDO::FETCH_OBJ ) ) {
            array_push ( $this->treedata_list, $row );
        }
        Tpl::output ( 'treelist', $this->treedata_list );
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
        $sql = 'from spotcheck_main a  left join spotcheck_spot spot on a.id = spot.spotid, spotcheck_type b , healthfile hf, personalinfo info
         where a.checktype = b.code  and a.fileno = hf.fileno and hf.fileno = info.fileno ';
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
            if ($search_result && count($search_result) > 0) {
                $sql = $sql . ' and  spot.result = \'' . $search_result . '\'';
            }
        }

        if ($_REQUEST['search_spottype']) {
            $search_result = $_REQUEST['search_spottype'];
            if ($search_result && count($search_result) > 0) {
                $sql = $sql . ' and  a.checktype = \'' . $search_result . '\'';
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
                        ,b.value  'checktype'
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
                        ,a.inputperson
                        ,a.orgid
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
            $result = $_REQUEST['spotresult'] == null ? "" : $_REQUEST['spotresult'];
            $reason = $_REQUEST['reason'] == null ? "" : $_REQUEST['reason'];
            $sql = " insert into spotcheck_spot (spotid,spotdate,result,reason,inputdate) values('$spotid','$spotdate','$result','$reason',getdate())";
            $conn->exec($sql);
            echo json_encode(array('success' => true, 'msg' => '保存成功!'));
        } catch (Exception $e) {
            echo json_encode(array('success' => false, 'msg' => '异常!' . $e->getMessage()));
        }
        exit;
    }


    public function statisticalOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        $sql = ' from spotcheck_main a  left join spotcheck_spot spot on a.id = spot.spotid,
         spotcheck_type b , healthfile hf, personalinfo info,
         Organization org , sam_taxempcode emp
         where a.checktype = b.code and a.fileno = hf.fileno
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
            if ($search_result && count($search_result) > 0) {
                $sql = $sql . ' and  spot.result = \'' . $search_result . '\'';
            }
        }

        if ($_REQUEST['search_spottype']) {
            $search_result = $_REQUEST['search_spottype'];
            if ($search_result && count($search_result) > 0) {
                $sql = $sql . ' and  a.checktype = \'' . $search_result . '\'';
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


    public function calldetailajaxOp()
    {

        exit();
    }


    public function savecallajaxOp()
    {
        //spotcheck_spot
        try {
            $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
            $id = $_REQUEST['callid'];
            $admin_info = $this->getAdminInfo();
            $opt = $admin_info['id'];
            $spotdate = $_REQUEST['spotdate'];
            $result = $_REQUEST['spotresult'] == null ? "" : $_REQUEST['spotresult'];
            $reason = $_REQUEST['reason'] == null ? "" : $_REQUEST['reason'];
            $remark = $_REQUEST['remark'] == null ? "" : $_REQUEST['remark'];
            $status = 0;
            if ($result == '待核实') {
                $status = '-2';
            } else if ($result == '真档') {
                $status = '1';
            } else if ($result == '假档') {
                $status = '-1';
            } else if ($result == '未接电话') {
                $status = '-3';
            }

            $sql = " update shopnc_member set checkstate = '$status' where member_id = '$id'";
            $conn->exec($sql);

            $changelog = array();
            $changestr = '';

            //修改名字
            $newname = $_REQUEST['newname'];
            if (!empty($newname)) {
                $sql = " update shopnc_member set member_truename = '$newname' where member_id = '$id'";
                $conn->exec($sql);
                $changelog['newname'] = $newname;
                $changelog['oldname'] = $_REQUEST["oldname"];
                $changestr .= ',姓名由(' . $_REQUEST["oldname"] . ')改为(' . $newname . ')';
            }
            //修改电话
            $newtel = $_REQUEST["newtel"];
            if (!empty($newtel)) {
                $sql = " update shopnc_member set sLinkPhone = '$newtel' where member_id = '$id'";
                $conn->exec($sql);
                $changelog['newtel'] = $newtel;
                $changelog['oldtel'] = $_REQUEST["oldtel"];
                $changestr .= ',电话由(' . $_REQUEST["oldtel"] . ')改为(' . $newtel . ')';
            }
            //修改生日
            $newbirthday = $_REQUEST["newbirthday"];
            if (!empty($newbirthday)) {
                $sql = " update shopnc_member set member_birthday = '$newbirthday' where member_id = '$id'";
                $conn->exec($sql);
                $changelog['newbirthday'] = $newbirthday;
                $changelog['oldbirthday'] = $_REQUEST["oldbirthday"];
                $changestr .= ',生日由(' . $_REQUEST["oldbirthday"] . ')改为(' . $newbirthday . ')';
            }
            //修改身份证
            $newidcard = $_REQUEST["newidcard"];
            if (!empty($newidcard)) {
                $sql = " update shopnc_member set sIDCard = '$newidcard' where member_id = '$id'";
                $conn->exec($sql);
                $changelog['newidcard'] = $newidcard;
                $changelog['oldidcard'] = $_REQUEST["oldidcard"];
                $changestr .= ',身份证由(' . $_REQUEST["oldidcard"] . ')改为(' . $newidcard . ')';
            }
            //修改性别
            $newsex = $_REQUEST["newsex"];
            if (!empty($newsex)) {
                $sql = " update shopnc_member set member_sex = '$newsex' where member_id = '$id'";
                $conn->exec($sql);
                $changelog['newsex'] = $newsex;
                $changelog['oldsex'] = $_REQUEST["oldsex"];
                $oldstr = $changelog['oldsex'] == '1' ? '男':'女';
                $newstr = $changelog['newsex'] == '1' ? '男':'女';
                $changestr .= ',性别由(' . $oldstr. ')改为(' . $newstr . ')';
            }
            //修改会员卡号
            $newid = $_REQUEST["newid"];
            if (!empty($newid)) {
                $logsql = " exec pFChangeMemberLog 0,'$opt','会员信息修改','$id','$newid';  ";
                $conn->exec($logsql);
                $sql = " update shopnc_call_main set memberid = '$newid' where memberid = '$id'";
                $conn->exec($sql);
                $changelog['newid'] = $newid;
                $changelog['oldid'] = $_REQUEST["oldid"];
                $changestr .= ',会员卡号由(' . $_REQUEST["oldid"] . ')改为(' . $newid . ')';
                $id = $newid;
            }
            //生成更新日志
            $changelogstr = json_encode($changelog);
            //生成中文日志
            if (count($changestr) > 0) {
                $changestr = substr($changestr, 1);
            }

            $hasfile = $_REQUEST['hasfile'];
            if (!empty($hasfile)) {
                $sql = " update shopnc_member set hasfile = $hasfile where member_id = '$id'";
                $conn->exec($sql);
            }
            $sql = " insert into shopnc_call_main (id,memberid,spotdate,status,result,inputdate,spotopt,remark,changelog,changestr,hasfile) values(newid(),'$id','$spotdate','$result','$reason',getdate(),'$opt','$remark','$changelogstr','$changestr',$hasfile)";
            $conn->exec($sql);

            echo json_encode(array('success' => true, 'msg' => '保存成功!'));
        } catch (Exception $e) {
            echo json_encode(array('success' => false, 'msg' => '异常!' . $e->getMessage()));
        }
        exit;
    }


    public function callOp()
    {
        $lang = Language::getLangContent();
        $orderbys = array(
            array('txt' => '卡号', 'col' => ' member_id '),
            array('txt' => '预存余额', 'col' => ' available_predeposit '),
            array('txt' => '赠送余额', 'col' => ' fConsumeBalance '),
            array('txt' => '消费积分', 'col' => ' member_points '));
        Tpl::output('orderbys', $orderbys);
        $model_member = Model('member');
        /**
         * 检索条件
         */
        if ($_GET['orgids']) {
            $condition ['CreateOrgID'] = array(
                'in',
                $_GET['orgids']
            );
        }

        if (isset($_GET['cardtype']) and $_GET['cardtype'] != '') {
            $condition ['cardtype'] = $_GET['cardtype'];
        }

        if (isset($_GET['cardgrade']) and $_GET['cardgrade'] != '') {
            $condition ['cardgrade'] = $_GET['cardgrade'];
        }


        if (isset($_GET['idnumber']) and $_GET['idnumber'] != '') {
            $condition ['sIDCard'] = $_GET['idnumber'];
        }
        if (isset($_GET['tel']) and $_GET['tel'] != '') {
            $condition ['sLinkPhone'] = $_GET['tel'];
        }
        if (isset($_GET['name']) and $_GET['name'] != '') {
            $condition ['member_truename'] = array('like', '%' . $_GET['name'] . '%');
        }
        if (isset($_GET['birthday']) and $_GET['birthday'] != '') {
            $condition ['member_birthday'] = $_GET['birthday'];
        }
        if (isset($_GET['createcard_begin']) and $_GET['createcard_begin'] != '') {
            $condition ['createcard_begin'] = array('exp' , ' dCreateDate >= \''.$_GET['createcard_begin'].'\'');
        }
        if (isset($_GET['createcard_end']) and $_GET['createcard_end'] != '') {
            $condition ['createcard_end'] = array('exp' , ' dCreateDate < dateadd(day,1,\''.$_GET['createcard_end'].'\')');
        }

        if (isset($_GET['hasfile']) and $_GET['hasfile'] != '') {
            if($_GET['hasfile']=='-1'){
                $condition ['hasfile']  = array('exp' , ' ( hasfile = -1 or hasfile is null ) ');
            }else{
                $condition ['hasfile'] = $_GET['hasfile'];
            }
        }

        if (!isset($_GET['orderby'])) {
            $_GET['orderby'] = '卡号';
        }


        if (!isset($_GET['order'])) {
            $ordersql = 'asc';
        } else {
            $ordersql = $_GET['order'];
        }
        if ($_GET['orderby']) {
            foreach ($orderbys as $orderby) {
                if ($orderby['txt'] == $_GET['orderby']) {
                    $order = $orderby['col'] . ' ' . $ordersql;
                    break;
                }
            }
        }
        if ($_GET ['search_field_value'] != '') {
            switch ($_GET ['search_field_name']) {
                case 'member_name' :
                    $condition ['member_name'] = array(
                        'like',
                        '%' . trim($_GET ['search_field_value']) . '%'
                    );
                    break;
                case 'member_email' :
                    $condition ['member_email'] = array(
                        'like',
                        '%' . trim($_GET ['search_field_value']) . '%'
                    );
                    break;
                case 'member_truename' :
                    $condition ['member_truename'] = array(
                        'like',
                        '%' . trim($_GET ['search_field_value']) . '%'
                    );
                    break;
            }
        }
        if ($_GET ['member_id'] != '') {
            $condition ['member_id'] = array(
                'like',
                '%' . trim($_GET ['member_id']) . '%'
            );
        }
        switch ($_GET ['search_state']) {
            case 'no_informallow' :
                $condition ['inform_allow'] = '2';
                break;
            case 'no_isbuy' :
                $condition ['is_buy'] = '0';
                break;
            case 'no_isallowtalk' :
                $condition ['is_allowtalk'] = '0';
                break;
            case 'no_memberstate' :
                $condition ['member_state'] = '0';
                break;
        }
        $field = '*';
        if ($_GET ['status'] != '') {
            $status = $_GET ['status'];
//            if($status == '1'){
//                $condition['status']= array('exp',"EXISTS ( select 1 from call_main where memberid = member_id and status = '待核实') ");
//            }else  if($status == '2'){
//                $condition['status']= array('exp',"EXISTS ( select 1 from call_main where memberid = member_id and status = '真档') ");
//            }else  if($status == '3'){
//                $condition['status']= array('exp',"EXISTS ( select 1 from call_main where memberid = member_id and status = '假档') ");
//            }else  if($status == '4'){
//                $condition['status']= array('exp',"EXISTS ( select 1 from call_main where memberid = member_id and status = '未接电话') ");
//            }

            if ($status == '1') {
                $condition['checkstate'] = '-2';
            } else if ($status == '2') {
                $condition['checkstate'] = '1';
            } else if ($status == '3') {
                $condition['checkstate'] = '-1';
            } else if ($status == '4') {
                $condition['checkstate'] = '-3';
            } else if ($status == '5') {
                $field = 'member.*,call_main.changestr,call_main.status [call_status] ,call_main.result ';
//                $condition['field'] = '\'123\' changestr ';
                $condition['status'] = array('exp', " call_main.memberid = member.member_id  ");
            }
        }
        /**
         * 排序
         */
//		$order = trim ( $_GET ['search_sort'] );
        if (empty ($order)) {
            $order = 'member_id desc';
        }
        if ($status == '5') {
            $member_list = $model_member->getMemberListNew($condition, $field, 10, $order);
        } else {
            $member_list = $model_member->getMemberList($condition, $field, 10, $order);
        }
        /**
         * 整理会员信息
         */
        if (is_array($member_list)) {
            foreach ($member_list as $k => $v) {
                $member_list [$k] ['member_time'] = $v ['member_time'] ? date('Y-m-d H:i:s', $v ['member_time']) : '';
                $member_list [$k] ['member_login_time'] = $v ['member_login_time'] ? date('Y-m-d H:i:s', $v ['member_login_time']) : '';
            }
        }


        Tpl::output('member_id', trim($_GET ['member_id']));
        Tpl::output('search_sort', trim($_GET ['search_sort']));
        Tpl::output('search_field_name', trim($_GET ['search_field_name']));
        Tpl::output('search_field_value', trim($_GET ['search_field_value']));
        Tpl::output('member_list', $member_list);
        Tpl::output('page', $model_member->showpage());
        if ($status == '5') {
            Tpl::showpage('healthplatform.calllog');
        } else {
            Tpl::showpage('healthplatform.call');
        }
    }

    public function calllogOp()
    {
        $lang = Language::getLangContent();
        $orderbys = array(
            array('txt' => '卡号', 'col' => ' member_id '),
            array('txt' => '预存余额', 'col' => ' available_predeposit '),
            array('txt' => '赠送余额', 'col' => ' fConsumeBalance '),
            array('txt' => '消费积分', 'col' => ' member_points '));
        Tpl::output('orderbys', $orderbys);
        $model_member = Model('member');
        /**
         * 检索条件
         */
        if ($_GET['orgids']) {
            $condition ['CreateOrgID'] = array(
                'in',
                $_GET['orgids']
            );
        }

        if (isset($_GET['cardtype']) and $_GET['cardtype'] != '') {
            $condition ['cardtype'] = $_GET['cardtype'];
        }

        if (isset($_GET['cardgrade']) and $_GET['cardgrade'] != '') {
            $condition ['cardgrade'] = $_GET['cardgrade'];
        }


        if (isset($_GET['idnumber']) and $_GET['idnumber'] != '') {
            $condition ['sIDCard'] = $_GET['idnumber'];
        }
        if (isset($_GET['tel']) and $_GET['tel'] != '') {
            $condition ['sLinkPhone'] = $_GET['tel'];
        }
        if (isset($_GET['name']) and $_GET['name'] != '') {
            $condition ['member_truename'] = array('like', '%' . $_GET['name'] . '%');
        }
        if (isset($_GET['birthday']) and $_GET['birthday'] != '') {
            $condition ['member_birthday'] = $_GET['birthday'];
        }

        if (isset($_GET['createcard_begin']) and $_GET['createcard_begin'] != '') {
            $condition ['createcard_begin'] = array('exp' , ' dCreateDate >= \''.$_GET['createcard_begin'].'\'');
        }
        if (isset($_GET['createcard_end']) and $_GET['createcard_end'] != '') {
            $condition ['createcard_end'] = array('exp' , ' dCreateDate < dateadd(day,1,\''.$_GET['createcard_end'].'\')');
        }
        if (isset($_GET['hasfile']) and $_GET['hasfile'] != '') {
            if($_GET['hasfile']=='-1'){
                $condition ['hasfile']  = array('exp' , ' ( hasfile = -1 or hasfile is null ) ');
            }else{
                $condition ['hasfile'] = $_GET['hasfile'];
            }
        }
        if (!isset($_GET['orderby'])) {
            $_GET['orderby'] = '卡号';
        }


        if (!isset($_GET['order'])) {
            $ordersql = 'asc';
        } else {
            $ordersql = $_GET['order'];
        }
        if ($_GET['orderby']) {
            foreach ($orderbys as $orderby) {
                if ($orderby['txt'] == $_GET['orderby']) {
                    $order = $orderby['col'] . ' ' . $ordersql;
                    break;
                }
            }
        }
        if ($_GET ['search_field_value'] != '') {
            switch ($_GET ['search_field_name']) {
                case 'member_name' :
                    $condition ['member_name'] = array(
                        'like',
                        '%' . trim($_GET ['search_field_value']) . '%'
                    );
                    break;
                case 'member_email' :
                    $condition ['member_email'] = array(
                        'like',
                        '%' . trim($_GET ['search_field_value']) . '%'
                    );
                    break;
                case 'member_truename' :
                    $condition ['member_truename'] = array(
                        'like',
                        '%' . trim($_GET ['search_field_value']) . '%'
                    );
                    break;
            }
        }
        if ($_GET ['member_id'] != '') {
            $condition ['member_id'] = array(
                'like',
                '%' . trim($_GET ['member_id']) . '%'
            );
        }
        switch ($_GET ['search_state']) {
            case 'no_informallow' :
                $condition ['inform_allow'] = '2';
                break;
            case 'no_isbuy' :
                $condition ['is_buy'] = '0';
                break;
            case 'no_isallowtalk' :
                $condition ['is_allowtalk'] = '0';
                break;
            case 'no_memberstate' :
                $condition ['member_state'] = '0';
                break;
        }

        $field = 'member.*,call_main.changestr,call_main.status [call_status] ,call_main.result,call_main.remark,call_main.spotdate,call_main.inputdate ';

        $condition['status'] = array('exp', " call_main.memberid = member.member_id  ");

        /**
         * 排序
         */
//		$order = trim ( $_GET ['search_sort'] );
        if (empty ($order)) {
            $order = 'member_id desc';
        }
        $member_list = $model_member->getMemberListNew($condition, $field, 10, ' call_main.inputdate desc');
        /**
         * 整理会员信息
         */
        if (is_array($member_list)) {
            foreach ($member_list as $k => $v) {
                $member_list [$k] ['member_time'] = $v ['member_time'] ? date('Y-m-d H:i:s', $v ['member_time']) : '';
                $member_list [$k] ['member_login_time'] = $v ['member_login_time'] ? date('Y-m-d H:i:s', $v ['member_login_time']) : '';
            }
        }


        Tpl::output('member_id', trim($_GET ['member_id']));
        Tpl::output('search_sort', trim($_GET ['search_sort']));
        Tpl::output('search_field_name', trim($_GET ['search_field_name']));
        Tpl::output('search_field_value', trim($_GET ['search_field_value']));
        Tpl::output('member_list', $member_list);
        Tpl::output('page', $model_member->showpage());
        Tpl::showpage('healthplatform.calllog');
    }

    public function sleepOp()
    {
        $lang = Language::getLangContent();
        $orderbys = array(
            array('txt' => '卡号', 'col' => ' member_id '),
            array('txt' => '预存余额', 'col' => ' available_predeposit '),
            array('txt' => '赠送余额', 'col' => ' fConsumeBalance '),
            array('txt' => '消费积分', 'col' => ' member_points '));
        Tpl::output('orderbys', $orderbys);
        $model_member = Model('member');
        /**
         * 检索条件
         */
        if ($_GET['orgids']) {
            $condition ['CreateOrgID'] = array(
                'in',
                $_GET['orgids']
            );
        }

        if (isset($_GET['cardtype']) and $_GET['cardtype'] != '') {
            $condition ['cardtype'] = $_GET['cardtype'];
        }

        if (isset($_GET['cardgrade']) and $_GET['cardgrade'] != '') {
            $condition ['cardgrade'] = $_GET['cardgrade'];
        }


        if (isset($_GET['idnumber']) and $_GET['idnumber'] != '') {
            $condition ['sIDCard'] = $_GET['idnumber'];
        }
        if (isset($_GET['tel']) and $_GET['tel'] != '') {
            $condition ['sLinkPhone'] = $_GET['tel'];
        }
        if (isset($_GET['name']) and $_GET['name'] != '') {
            $condition ['member_truename'] = array('like', '%' . $_GET['name'] . '%');
        }
        if (isset($_GET['birthday']) and $_GET['birthday'] != '') {
            $condition ['member_birthday'] = $_GET['birthday'];
        }
        if (isset($_GET['createcard_begin']) and $_GET['createcard_begin'] != '') {
            $condition ['createcard_begin'] = array('exp' , ' dCreateDate >= \''.$_GET['createcard_begin'].'\'');
        }
        if (isset($_GET['createcard_end']) and $_GET['createcard_end'] != '') {
            $condition ['createcard_end'] = array('exp' , ' dCreateDate < dateadd(day,1,\''.$_GET['createcard_end'].'\')');
        }

        if (!isset($_GET['orderby'])) {
            $_GET['orderby'] = '卡号';
        }


        if (!isset($_GET['order'])) {
            $ordersql = 'asc';
        } else {
            $ordersql = $_GET['order'];
        }
        if ($_GET['orderby']) {
            foreach ($orderbys as $orderby) {
                if ($orderby['txt'] == $_GET['orderby']) {
                    $order = $orderby['col'] . ' ' . $ordersql;
                    break;
                }
            }
        }
        if ($_GET ['search_field_value'] != '') {
            switch ($_GET ['search_field_name']) {
                case 'member_name' :
                    $condition ['member_name'] = array(
                        'like',
                        '%' . trim($_GET ['search_field_value']) . '%'
                    );
                    break;
                case 'member_email' :
                    $condition ['member_email'] = array(
                        'like',
                        '%' . trim($_GET ['search_field_value']) . '%'
                    );
                    break;
                case 'member_truename' :
                    $condition ['member_truename'] = array(
                        'like',
                        '%' . trim($_GET ['search_field_value']) . '%'
                    );
                    break;
            }
        }
        if ($_GET ['member_id'] != '') {
            $condition ['member_id'] = array(
                'like',
                '%' . trim($_GET ['member_id']) . '%'
            );
        }
        switch ($_GET ['search_state']) {
            case 'no_informallow' :
                $condition ['inform_allow'] = '2';
                break;
            case 'no_isbuy' :
                $condition ['is_buy'] = '0';
                break;
            case 'no_isallowtalk' :
                $condition ['is_allowtalk'] = '0';
                break;
            case 'no_memberstate' :
                $condition ['member_state'] = '0';
                break;
        }

        $field = '*, (select max(dCO_Date) from Center_CheckOut where sMemberID = member_id ) lastdate ';
        $sleepunit = $_REQUEST['sleepunit'];
        if ($sleepunit == '1') {
            $dateunit = 'year';
        } else if ($sleepunit == '3') {
            $dateunit = 'day';
        } else {
            $dateunit = 'month';
        }
        $sleepnum = $_REQUEST['sleepnum'];
        if (empty($sleepnum)) {
            $sleepnum = '40';
            $_GET['sleepnum'] = 40;
        }

        $haspay = $_REQUEST['haspay'];
        $order = ' member_id desc ';
        if (!empty($haspay)) {
            if ($haspay == '1') {
                $condition['haspay'] = array('exp', " not   exists (select 1 from Center_CheckOut where sMemberID = member_id  )  ");
            } else if ($haspay == '2') {
                $condition['haspay'] = array('exp', "  exists (select 1 from Center_CheckOut where sMemberID = member_id  )  ");
                $order = '(select max(dCO_Date) from Center_CheckOut where sMemberID = member_id ) desc';
            }
        }

        $condition['status'] = array('exp', " dCreateDate is  not null and dCreateDate < convert(date, dateadd($dateunit,-$sleepnum,getdate())) and  not exists (select 1 from Center_CheckOut where sMemberID = member_id and  dCO_Date >= convert(date, dateadd($dateunit,-$sleepnum,getdate())) )   ");

        /**
         * 排序
         */
//		$order = trim ( $_GET ['search_sort'] );
        if (empty ($order)) {
            $order = 'member_id desc';
        }
        $member_list = $model_member->getMemberList($condition, $field, 10, $order);
        /**
         * 整理会员信息
         */
        if (is_array($member_list)) {
            foreach ($member_list as $k => $v) {
                $member_list [$k] ['member_time'] = $v ['member_time'] ? date('Y-m-d H:i:s', $v ['member_time']) : '';
                $member_list [$k] ['member_login_time'] = $v ['member_login_time'] ? date('Y-m-d H:i:s', $v ['member_login_time']) : '';
            }
        }


        Tpl::output('member_id', trim($_GET ['member_id']));
        Tpl::output('search_sort', trim($_GET ['search_sort']));
        Tpl::output('search_field_name', trim($_GET ['search_field_name']));
        Tpl::output('search_field_value', trim($_GET ['search_field_value']));
        Tpl::output('member_list', $member_list);
        Tpl::output('page', $model_member->showpage());
        Tpl::showpage('healthplatform.sleep');
    }


    public function birthdayOp()
    {
        $lang = Language::getLangContent();
        $orderbys = array(
            array('txt' => '卡号', 'col' => ' member_id '),
            array('txt' => '预存余额', 'col' => ' available_predeposit '),
            array('txt' => '赠送余额', 'col' => ' fConsumeBalance '),
            array('txt' => '消费积分', 'col' => ' member_points '));
        Tpl::output('orderbys', $orderbys);
        $model_member = Model('member');
        /**
         * 检索条件
         */
        if ($_GET['orgids']) {
            $condition ['CreateOrgID'] = array(
                'in',
                $_GET['orgids']
            );
        }

        if (isset($_GET['cardtype']) and $_GET['cardtype'] != '') {
            $condition ['cardtype'] = $_GET['cardtype'];
        }

        if (isset($_GET['cardgrade']) and $_GET['cardgrade'] != '') {
            $condition ['cardgrade'] = $_GET['cardgrade'];
        }


        if (isset($_GET['idnumber']) and $_GET['idnumber'] != '') {
            $condition ['sIDCard'] = $_GET['idnumber'];
        }
        if (isset($_GET['tel']) and $_GET['tel'] != '') {
            $condition ['sLinkPhone'] = $_GET['tel'];
        }
        if (isset($_GET['name']) and $_GET['name'] != '') {
            $condition ['member_truename'] = array('like', '%' . $_GET['name'] . '%');
        }
        if (isset($_GET['birthday_begin']) and $_GET['birthday_begin'] != '') {
            $condition ['birthday_begin'] =  array('exp' , ' dbo.birthday( member_birthday ) >= dbo.birthday( '. $_GET['birthday_begin']); ;
        }

        if (isset($_GET['birthday_end']) and $_GET['birthday_end'] != '') {
            $condition ['birthday_end'] =  array('exp' , '  dbo.birthday( member_birthday ) <= dbo.birthday( '. $_GET['birthday_end']); ;
        }

        if (!empty($_GET['dayrange'])){
            $dayrange = $_GET['dayrange'];
            if($dayrange == "1"){
                $condition ['dayrange'] = array('exp' , '  dbo.birthday(member_birthday)   = convert(date,getdate()) ');
            }else if($dayrange == "2"){
                $condition ['dayrange'] = array('exp' , ' dbo.birthday(member_birthday)   = convert(date,dateadd(day,1,getdate())) ');
            }else if($dayrange == "3"){
                $condition ['dayrange'] = array('exp' , ' dbo.birthday(member_birthday)  >= convert(date,getdate()) and  dbo.birthday(member_birthday) <= convert(date,dateadd(day,7,getdate())) ');
            }else if($dayrange == "4"){
                $condition ['dayrange'] = array('exp' , ' dbo.birthday(member_birthday)  >= convert(date,getdate()) and  dbo.birthday(member_birthday) <= convert(date,dateadd(day,30,getdate())) ');
            }
        }
        if (isset($_GET['createcard_begin']) and $_GET['createcard_begin'] != '') {
            $condition ['createcard_begin'] = array('exp' , ' dCreateDate >= \''.$_GET['createcard_begin'].'\'');
        }
        if (isset($_GET['createcard_end']) and $_GET['createcard_end'] != '') {
            $condition ['createcard_end'] = array('exp' , ' dCreateDate < dateadd(day,1,\''.$_GET['createcard_end'].'\')');
        }

        if (!isset($_GET['orderby'])) {
            $_GET['orderby'] = '卡号';
        }


        if (!isset($_GET['order'])) {
            $ordersql = 'asc';
        } else {
            $ordersql = $_GET['order'];
        }
        if ($_GET['orderby']) {
            foreach ($orderbys as $orderby) {
                if ($orderby['txt'] == $_GET['orderby']) {
                    $order = $orderby['col'] . ' ' . $ordersql;
                    break;
                }
            }
        }
        if ($_GET ['search_field_value'] != '') {
            switch ($_GET ['search_field_name']) {
                case 'member_name' :
                    $condition ['member_name'] = array(
                        'like',
                        '%' . trim($_GET ['search_field_value']) . '%'
                    );
                    break;
                case 'member_email' :
                    $condition ['member_email'] = array(
                        'like',
                        '%' . trim($_GET ['search_field_value']) . '%'
                    );
                    break;
                case 'member_truename' :
                    $condition ['member_truename'] = array(
                        'like',
                        '%' . trim($_GET ['search_field_value']) . '%'
                    );
                    break;
            }
        }
        if ($_GET ['member_id'] != '') {
            $condition ['member_id'] = array(
                'like',
                '%' . trim($_GET ['member_id']) . '%'
            );
        }
        switch ($_GET ['search_state']) {
            case 'no_informallow' :
                $condition ['inform_allow'] = '2';
                break;
            case 'no_isbuy' :
                $condition ['is_buy'] = '0';
                break;
            case 'no_isallowtalk' :
                $condition ['is_allowtalk'] = '0';
                break;
            case 'no_memberstate' :
                $condition ['member_state'] = '0';
                break;
        }

        $field = '*, (select max(dCO_Date) from Center_CheckOut where sMemberID = member_id ) lastdate ';


        $haspay = $_REQUEST['haspay'];
        $order = ' member_id desc ';
        if (!empty($haspay)) {
            if ($haspay == '1') {
                $condition['haspay'] = array('exp', " not   exists (select 1 from Center_CheckOut where sMemberID = member_id  )  ");
            } else if ($haspay == '2') {
                $condition['haspay'] = array('exp', "  exists (select 1 from Center_CheckOut where sMemberID = member_id  )  ");
                $order = '(select max(dCO_Date) from Center_CheckOut where sMemberID = member_id ) desc';
            }
        }

//        $condition['status'] = array('exp', " dCreateDate is  not null and dCreateDate < convert(date, dateadd($dateunit,-$sleepnum,getdate())) and  not exists (select 1 from Center_CheckOut where sMemberID = member_id and  dCO_Date >= convert(date, dateadd($dateunit,-$sleepnum,getdate())) )   ");

        /**
         * 排序
         */
//		$order = trim ( $_GET ['search_sort'] );
        if (empty ($order)) {
            $order = 'member_id desc';
        }
        $member_list = $model_member->getMemberList($condition, $field, 10, $order);
        /**
         * 整理会员信息
         */
        if (is_array($member_list)) {
            foreach ($member_list as $k => $v) {
                $member_list [$k] ['member_time'] = $v ['member_time'] ? date('Y-m-d H:i:s', $v ['member_time']) : '';
                $member_list [$k] ['member_login_time'] = $v ['member_login_time'] ? date('Y-m-d H:i:s', $v ['member_login_time']) : '';
            }
        }


        Tpl::output('member_id', trim($_GET ['member_id']));
        Tpl::output('search_sort', trim($_GET ['search_sort']));
        Tpl::output('search_field_name', trim($_GET ['search_field_name']));
        Tpl::output('search_field_value', trim($_GET ['search_field_value']));
        Tpl::output('member_list', $member_list);
        Tpl::output('page', $model_member->showpage());
        Tpl::showpage('healthplatform.birthday');
    }

    /*
     * 消费频次提醒
     */
    public function consumeOp()
    {
    	$conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
    	$lang = Language::getLangContent();
    	
//     	$orderbys = array(
//     			array('txt' => '卡号', 'col' => ' member_id '),
//     			array('txt' => '预存余额', 'col' => ' available_predeposit '),
//     			array('txt' => '赠送余额', 'col' => ' fConsumeBalance '),
//     			array('txt' => '消费积分', 'col' => ' member_points '));
//     	Tpl::output('orderbys', $orderbys);
//     	$model_member = Model('member');
    	$sql = "select a.member_id,a.member_truename,a.member_sex,a.member_birthday,a.Mobile,a.sLinkPhone,a.sAddress,a.sIDCard, count(b.iCO_ID) as consumnum 
    			from 	shopnc_member a left join Center_CheckOut b   on  a.member_id = b.sMemberID and b.fCO_IncomeMoney >0 ";
    	$where = " where 1=1 ";
    	$joinwhere = '';
    	$groupby = ' group by a.member_id,a.member_truename,a.member_sex,a.member_birthday,a.Mobile,a.sLinkPhone,a.sAddress,a.sIDCard ';
    	$having = '';
    	/**
    	 * 检索条件
    	*/
    	//消费机构
    	if ($_GET['orgids']) {
    		$where = $where . ' and b.OrgID in ( ' . implode(',', $_GET['orgids']) . ')';
    		 
    	}
    	if (isset($_GET['member_id']) and $_GET['member_id'] != '') {
    		$where = $where . ' and a.member_id =\'' . $_GET['member_id'] . '\'';
    	}
    	if (isset($_GET['cardtype']) and $_GET['cardtype'] != '') {
    		$where = $where . ' and a.cardtype =\'' . $_GET['cardtype'] . '\'';
    	}
    
    	if (isset($_GET['cardgrade']) and $_GET['cardgrade'] != '') {
    		$where = $where . ' and a.cardgrade =\'' . $_GET['cardgrade'] . '\'';
    	}
    
    
    	if (isset($_GET['idnumber']) and $_GET['idnumber'] != '') {
    		$where = $where . ' and a.sIDCard =\'' . $_GET['idnumber'] . '\'';
    	}
    	if (isset($_GET['tel']) and $_GET['tel'] != '') {
    		$where = $where . ' and a.sLinkPhone =\'' . $_GET['tel'] . '\'';
    	}
    	if (isset($_GET['name']) and $_GET['name'] != '') {
    		$where = $where . ' and a.member_truename like \'%' . $_GET['name'] . '%\'';
    	}
//     	if (isset($_GET['birthday']) and $_GET['birthday'] != '') {
//     		$condition ['member_birthday'] = $_GET['birthday'];
//     	}
    	if (!isset($_GET['dcodate_begin']) or $_GET['dcodate_begin'] == '') {
    		$_GET['dcodate_begin'] =date('Y-m-d',time());    		
    	}
    	$joinwhere = $joinwhere . ' and b.dCO_Date >=\'' . $_GET['dcodate_begin'] . '\'';
    	if (!isset($_GET['dcodate_end']) or $_GET['dcodate_end'] == '') {
    		$_GET['dcodate_end'] =date('Y-m-d',time());
    	}
    	$joinwhere = $joinwhere . ' and b.dCO_Date <=\'' . $_GET['dcodate_end'] . '\'';
    	if (!isset($_GET['orderby'])) {
    		$_GET['orderby'] = '卡号';
    	}
    
    
    	if (!isset($_GET['order'])) {
    		$ordersql = 'asc';
    	} else {
    		$ordersql = $_GET['order'];
    	}
//     	if ($_GET['orderby']) {
//     		foreach ($orderbys as $orderby) {
//     			if ($orderby['txt'] == $_GET['orderby']) {
//     				$order = $orderby['col'] . ' ' . $ordersql;
//     				break;
//     			}
//     		}
//     	}
    	
    
//     	$field = '*, (select max(dCO_Date) from Center_CheckOut where sMemberID = member_id ) lastdate ';
//     	$sleepunit = $_REQUEST['sleepunit'];
//     	if ($sleepunit == '1') {
//     		$dateunit = 'year';
//     	} else if ($sleepunit == '3') {
//     		$dateunit = 'day';
//     	} else {
//     		$dateunit = 'month';
//     	}
//     	$consumnum = $_REQUEST['consumnum'];
//     	if (empty($consumnum) && $consumnum !=0) {
//     		$consumnum = '3';
//     		$_GET['consumnum'] = 3;
    		
//     	}
		if($_GET['consumnumstart'] == ''){
			$_GET['consumnumstart'] = 1;
		}
		if($_GET['consumnumend'] == ''){
			$_GET['consumnumend'] = 3;
		}
    	if (isset($_GET['consumnumstart']) and $_GET['consumnumstart'] != '') {
    		$consumnumstart = $_GET['consumnumstart'];
    		$having = " having count((b.iCO_ID)) >= $consumnumstart";
    	}
    	if (isset($_GET['consumnumend']) and $_GET['consumnumend'] != '') {
    		$consumnumend = $_GET['consumnumend'];
    		$having = " $having and count((b.iCO_ID)) <= $consumnumend";
    	}
    
//     	$haspay = $_REQUEST['haspay'];
//     	$order = ' member_id desc ';
//     	if (!empty($haspay)) {
//     		if ($haspay == '1') {
//     			$condition['haspay'] = array('exp', " not   exists (select 1 from Center_CheckOut where sMemberID = member_id  )  ");
//     		} else if ($haspay == '2') {
//     			$condition['haspay'] = array('exp', "  exists (select 1 from Center_CheckOut where sMemberID = member_id  )  ");
//     			$order = '(select max(dCO_Date) from Center_CheckOut where sMemberID = member_id ) desc';
//     		}
//     	}
    
//     	$condition['status'] = array('exp', " dCreateDate is  not null and dCreateDate < convert(date, dateadd($dateunit,-$sleepnum,getdate())) and  not exists (select 1 from Center_CheckOut where sMemberID = member_id and  dCO_Date >= convert(date, dateadd($dateunit,-$sleepnum,getdate())) )   ");
    
    	/**
    	 * 排序
    	*/
    	//		$order = trim ( $_GET ['search_sort'] );
//     	if (empty ($order)) {
//     		$order = 'member_id desc';
//     	}
//     	$member_list = $model_member->getMemberList($condition, $field, 10, $order);
    	$page = new Page();
    	$page->setEachNum(10);
    	$page->setNowPage($_REQUEST["curpage"]);
    	$startnum = $page->getEachNum() * ($page->getNowPage() - 1);
    	$endnum = $page->getEachNum() * ($page->getNowPage());
    	$allsql = $sql.$joinwhere.$where.$groupby.$having;
    	$countsql = " select count(*)  from ($allsql) ccc ";
    	$stmt = $conn->query($countsql);
    	$total = $stmt->fetch(PDO::FETCH_NUM);
    	$page->setTotalNum($total[0]);
    	$pagesql = 'select * from (select top '. $endnum.' row_number() over( order by  member_id)rownum ,* from ('.$allsql.')zz1 )zz2 where rownum>'.$startnum;
    	if (isset($_GET['export']) && $_GET['export'] == 'true') {
    		$this->exportxlsx(array(0 => $allsql), $displaytext, '消费频次提醒');
    	}
    	$stmt = $conn->query($pagesql);
    	$data_list = array();
    	while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
    		array_push($data_list, $row);
    	}
    	Tpl::output('data_list', $data_list);
    	Tpl::output('page', $page->show());
    	Tpl::showpage('healthplatform.consume');
    }


}
