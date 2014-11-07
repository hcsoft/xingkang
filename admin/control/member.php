<?php
/**
 * 会员管理
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

class memberControl extends SystemControl{
	const EXPORT_SIZE = 5000;
	public function __construct(){
		parent::__construct();
		Language::read('member');
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        $treesql = 'select  b.id , b.name,b.districtnumber,b.parentid pId from map_org_wechat a, Organization b where a.orgid = b.id ';
        $treestmt = $conn->query($treesql);
        $this->treedata_list = array();
        while ($row = $treestmt->fetch(PDO::FETCH_OBJ)) {
            array_push($this->treedata_list, $row);
        }
        Tpl::output('treelist', $this->treedata_list);
	}

	/**
	 * 会员管理
	 */
	public function memberOp(){
		$lang	= Language::getLangContent();
		$model_member = Model('member');
		/**
		 * 检索条件
		 */
		if ($_GET['search_field_value'] != '') {
    		switch ($_GET['search_field_name']){
    			case 'member_name':
    				$condition['member_name'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    			case 'member_email':
    				$condition['member_email'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    			case 'member_truename':
    				$condition['member_truename'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    		}
		}
        if ($_GET['member_id'] != '') {
            $condition['member_id'] = array('like', '%' . trim($_GET['member_id']) . '%');
        }
		switch ($_GET['search_state']){
			case 'no_informallow':
				$condition['inform_allow'] = '2';
				break;
			case 'no_isbuy':
				$condition['is_buy'] = '0';
				break;
			case 'no_isallowtalk':
				$condition['is_allowtalk'] = '0';
				break;
			case 'no_memberstate':
				$condition['member_state'] = '0';
				break;
		}
		/**
		 * 排序
		 */
		$order = trim($_GET['search_sort']);
		if (empty($order)) {
		    $order = 'member_id desc';
		}
		$member_list = $model_member->getMemberList($condition, '*', 10, $order);
		/**
		 * 整理会员信息
		 */
		if (is_array($member_list)){
			foreach ($member_list as $k=> $v){
				$member_list[$k]['member_time'] = $v['member_time']?date('Y-m-d H:i:s',$v['member_time']):'';
				$member_list[$k]['member_login_time'] = $v['member_login_time']?date('Y-m-d H:i:s',$v['member_login_time']):'';
			}
		}

		Tpl::output('member_id',trim($_GET['member_id']));
        Tpl::output('search_sort',trim($_GET['search_sort']));
		Tpl::output('search_field_name',trim($_GET['search_field_name']));
		Tpl::output('search_field_value',trim($_GET['search_field_value']));
		Tpl::output('member_list',$member_list);
		Tpl::output('page',$model_member->showpage());
		Tpl::showpage('member.index');
	}

	/**
	 * 会员修改
	 */
	public function member_editOp(){
		$lang	= Language::getLangContent();
		$model_member = Model('member');
		/**
		 * 保存
		 */
		if (chksubmit()){
			/**
			 * 验证
			 */
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			array("input"=>$_POST["member_email"], "require"=>"true", 'validator'=>'Email', "message"=>$lang['member_edit_valid_email']),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$update_array = array();
				$update_array['member_id']			= intval($_POST['member_id']);
				if (!empty($_POST['member_passwd'])){
					$update_array['member_passwd'] = md5($_POST['member_passwd']);
				}
				$update_array['member_email']		= trim($_POST['member_email']);
				$update_array['member_truename']	= trim($_POST['member_truename']);
				$update_array['member_sex'] 		= trim($_POST['member_sex']);
				$update_array['member_qq'] 			= trim($_POST['member_qq']);
				$update_array['member_ww']			= trim($_POST['member_ww']);
				$update_array['inform_allow'] 		= trim($_POST['inform_allow']);
				$update_array['is_buy'] 			= trim($_POST['isbuy']);
				$update_array['is_allowtalk'] 		= trim($_POST['allowtalk']);
				$update_array['member_state'] 		= trim($_POST['memberstate']);
				if (!empty($_POST['member_avatar'])){
					$update_array['member_avatar'] = $_POST['member_avatar'];
				}
				$result = $model_member->updateMember($update_array,intval($_POST['member_id']));
				if ($result){
					$url = array(
					array(
					'url'=>'index.php?act=member&op=member',
					'msg'=>$lang['member_edit_back_to_list'],
					),
					array(
					'url'=>'index.php?act=member&op=member_edit&member_id='.intval($_POST['member_id']),
					'msg'=>$lang['member_edit_again'],
					),
					);
					$this->log(L('nc_edit,member_index_name').'[ID:'.$_POST['member_id'].']',1);
					showMessage($lang['member_edit_succ'],$url);
				}else {
					showMessage($lang['member_edit_fail']);
				}
			}
		}
		$condition['member_id'] = intval($_GET['member_id']);
		$member_array = $model_member->infoMember($condition);

		Tpl::output('member_array',$member_array);
		Tpl::showpage('member.edit');
	}

	/**
	 * 新增会员
	 */
	public function member_addOp(){
		$lang	= Language::getLangContent();
		$model_member = Model('member');
		/**
		 * 保存
		 */
		if (chksubmit()){
			/**
			 * 验证
			 */
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			array("input"=>$_POST["member_email"], "require"=>"true", 'validator'=>'Email', "message"=>$lang['member_edit_valid_email']),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$insert_array = array();
				$insert_array['member_name']	= trim($_POST['member_name']);
				$insert_array['member_passwd']	= trim($_POST['member_passwd']);
				$insert_array['member_email']	= trim($_POST['member_email']);
				$insert_array['member_truename']= trim($_POST['member_truename']);
				$insert_array['member_sex'] 	= trim($_POST['member_sex']);
				$insert_array['member_qq'] 		= trim($_POST['member_qq']);
				$insert_array['member_ww']		= trim($_POST['member_ww']);
                //默认允许举报商品
                $insert_array['inform_allow'] 	= '1';
				if (!empty($_POST['member_avatar'])){
					$insert_array['member_avatar'] = trim($_POST['member_avatar']);
				}

				$result = $model_member->addMember($insert_array);
				if ($result){
					$url = array(
					array(
					'url'=>'index.php?act=member&op=member',
					'msg'=>$lang['member_add_back_to_list'],
					),
					array(
					'url'=>'index.php?act=member&op=member_add',
					'msg'=>$lang['member_add_again'],
					),
					);
					$this->log(L('nc_add,member_index_name').'[	'.$_POST['member_name'].']',1);
					showMessage($lang['member_add_succ'],$url);
				}else {
					showMessage($lang['member_add_fail']);
				}
			}
		}
		Tpl::showpage('member.add');
	}

	/**
	 * ajax操作
	 */
	public function ajaxOp(){
		switch ($_GET['branch']){
			/**
			 * 验证会员是否重复
			 */
			case 'check_user_name':
				$model_member = Model('member');
				$condition['member_name']	= trim($_GET['member_name']);
				$condition['no_member_id']	= intval($_GET['member_id']);
				$list = $model_member->infoMember($condition);
				if (empty($list)){
					echo 'true';exit;
				}else {
					echo 'false';exit;
				}
				break;
				/**
			 * 验证邮件是否重复
			 */
			case 'check_email':
				$model_member = Model('member');
				$condition['member_email'] = trim($_GET['member_email']);
				$condition['no_member_id'] = intval($_GET['member_id']);
				$list = $model_member->infoMember($condition);
				if (empty($list)){
					echo 'true';exit;
				}else {
					echo 'false';exit;
				}
				break;
		}
	}

    public function consumesumOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        if (!isset($_GET['search_type'])) {
            $_GET['search_type'] = '0';
        }
        $sqlarray = array('membername' => 'member.sName as "membername"',
            'memberid' => ' member.sMemberID as "memberid" ',
            'year' => ' year(a.dCO_Date) as "year" ',
            'month' => ' left(convert(varchar,dCO_Date,112),6) as  "month" ',
            'day' => ' convert(varchar,dCO_Date,112) as "day" ',
            'OrgID' => ' org.name as "OrgID" '
        );
        $config = array('sumcol' => array('OrgID' => array(name => 'OrgID', 'text' => '机构'),
            'member' => array('text' => '会员', name=>'member',
                'cols' => array(0 => array(name => 'memberid', 'text' => '会员号码')
                , 1 => array(name => 'membername', 'text' => '会员名称'))),
            'year' => array('text' => '年', name=>'year',uncheck=>'month,day' ),
            'month' => array('text' => '月', name=>'month',uncheck=>'year,day'),
            'day' => array('text' => '日', name=>'day',uncheck=>'year,month'),
        ));
        Tpl::output('config', $config);

        //处理汇总字段
        $sumtype = $_GET['sumtype'];
        if ($sumtype == null) {
            $sumtype = array(0 => "OrgID");
            $_GET['sumtype'] = $sumtype;
        }
        $checked = $_GET['checked'];
        $page = new Page();
        $page->setEachNum(10);
        $page->setNowPage($_REQUEST["curpage"]);
        $sql = 'from Center_CheckOut a  , Center_codes ico, Center_codes gather,Center_codes state,Center_codes tag,
            Center_Person person , Organization org , Center_MemberInfo member
          where a.iCO_Type = ico.code and ico.type=\'iCO_Type\'
           and  a.iCO_GatherType = gather.code and gather.type=\'iCO_GatherType\'
           and  a.iCO_State = state.code and state.type=\'iCO_State\'
           and  a.iCO_Tag = tag.code and tag.type=\'iCO_Tag\'
           and a.orgid = org.id
           and a.iCO_MakePerson = person.iPerson_ID
           and a.sMemberID = member.sMemberID';

        if ($_GET['query_start_time']) {
            $sql = $sql . ' and a.dCO_Date >=\'' . $_GET['query_start_time'] . '\'';
        }

        if ($_GET['query_end_time']) {
            $sql = $sql . ' and a.dCO_Date < dateadd(day,1,\'' . $_GET['query_end_time'] . '\')';
        }

        //处理树的参数
        if ($_GET['orgids']) {
            $sql = $sql . ' and a.OrgID in ( ' . implode(',', $_GET['orgids']) . ')';
        }

        $search_type = $_GET['search_type'];
//        echo $search_type;
        $colconfig = $config;
//        var_dump($config[intval($search_type)]);
        $displaycol = array();
        $displaytext = array();
        $sumcol = array();
        $totalcol = array();
        $groupbycol = array();
        foreach ($sumtype as $i => $v) {
//            var_dump($colconfig['sumcol'][$v]);
            if(isset($colconfig['sqlwher'])){
                $sql = $sql . $colconfig['sqlwher'];
            }
            if (isset($colconfig['sumcol'][$v])) {
                if (isset($colconfig['sumcol'][$v]['cols'])) {

                    foreach ($colconfig['sumcol'][$v]['cols'] as $item) {
//                        echo $item['name'] . '<br>';
                        array_push($sumcol, $sqlarray[$item['name']]);
                        array_push($displaycol, $item['name']);
                        array_push($displaytext, $item['text']);
                        $itemsplit = explode(' as ', $sqlarray[$item['name']]);
                        array_push($totalcol, ' null as ' . $itemsplit[1]);
                        $str = strtolower(str_replace(' ', '', trim($itemsplit[0])));
                        if (substr($str, 0, 4) != 'sum(' && substr($str, 0, 6) != 'count(')
                            array_push($groupbycol, $itemsplit[0]);
                    }
                } else {
                    $item = $colconfig['sumcol'][$v];
                    array_push($sumcol, $sqlarray[$item['name']]);
                    array_push($displaycol, $item['name']);
                    array_push($displaytext, $item['text']);
                    $itemsplit = explode(' as ', $sqlarray[$item['name']]);
                    array_push($totalcol, ' null as ' . $itemsplit[1]);
                    $str = strtolower(str_replace(' ', '', trim($itemsplit[0])));
                    if (substr($str, 0, 4) != 'sum(' && substr($str, 0, 6) != 'count(')
                        array_push($groupbycol, $itemsplit[0]);
                }
            }
        }
        array_push($displaytext, '消费金额');
//        var_dump($totalcol);
        $totalcol[0] = '\'总计：\' as ' . explode(' as ', $totalcol[0])[1];
//        var_dump($totalcol);
        $totalcolstr = join(',', $totalcol);
        $sumcolstr = join(',', $sumcol);
        $groupbycolstr = join(',', $groupbycol);
//        echo $sumcolstr;
        $tsql = " select $sumcolstr ,sum(fCO_GetMoney) getmoney
                        $sql group by $groupbycolstr order by $groupbycolstr ";
//        echo $tsql;
        //处理合计
        $totalsql = " select $totalcolstr ,  sum(fCO_GetMoney) getmoney
                        $sql ";
        if(isset($_GET['export']) && $_GET['export']=='true'){
            $this->exportxlsx(array(0=>$tsql,1=>$totalsql),$displaytext,'消费汇总');
        }
        $stmt = $conn->query($tsql);
        $data_list = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($data_list, $row);
        }

//        echo $totalsql;
        $totalstmt = $conn->query($totalsql);
        while ($row = $totalstmt->fetch(PDO::FETCH_OBJ)) {
            array_push($data_list, $row);
        }
        Tpl::output('data_list', $data_list);
        //--0:期初入库 1:采购入库 2:购进退回 3:盘盈 5:领用 12:盘亏 14:领用退回 50:采购计划
        Tpl::output('page', $page->show());
        //处理需要显示的列
        $col = array();
        foreach ($sumtype as $i => $v) {
            if (isset($sumtypestr[$v])) {
                foreach ($sumtypestr[$v] as $key => $item) {
                    $col[$key] = $item;
                }
            }
        }
//        var_dump($col);
        Tpl::output('displaycol', $displaycol);
        Tpl::output('displaytext', $displaytext);
        Tpl::showpage('member.consume.sum');
    }

    public function rechargesumOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        if (!isset($_GET['search_type'])) {
            $_GET['search_type'] = '0';
        }
        $sqlarray = array('ChargePerson' => 'person.sPerson_Name as "ChargePerson"',
            'type' => ' type.name as "type" ',
            'state' => ' state.name as "state" ',
            'year' => ' year(a.RechargeDate) as "year" ',
            'month' => ' left(convert(varchar,RechargeDate,112),6) as  "month" ',
            'day' => ' convert(varchar,RechargeDate,112) as "day" ',
            'OrgID' => ' org.name as "OrgID" '
        );
        $config = array('sumcol' => array('OrgID' => array(name => 'OrgID', 'text' => '机构'),
            'ChargePerson' => array(name => 'ChargePerson', 'text' => '收款人'),
            'type' => array(name => 'type', 'text' => '类型'),
            'state' => array(name => 'state', 'text' => '状态'),
            'year' => array('text' => '年', name=>'year',uncheck=>'month,day' ),
            'month' => array('text' => '月', name=>'month',uncheck=>'year,day'),
            'day' => array('text' => '日', name=>'day',uncheck=>'year,month'),
        ));
        Tpl::output('config', $config);

        //处理汇总字段
        $sumtype = $_GET['sumtype'];
        if ($sumtype == null) {
            $sumtype = array(0 => "OrgID");
            $_GET['sumtype'] = $sumtype;
        }
        $checked = $_GET['checked'];
        $page = new Page();
        $page->setEachNum(10);
        $page->setNowPage($_REQUEST["curpage"]);
        $sql = 'from Center_MemberRecharge a  , Organization org ,Center_codes state,Center_codes type ,Center_Person person
            where  a.orgid = org.id
            and a.State = state.code and state.type=\'recharge_State\'
            and a.Type =  type.code and type.type=\'recharge_Type\'
            and a.ChargePerson = person.iPerson_ID and state in (0,1) ';

        if ($_GET['query_start_time']) {
            $sql = $sql . ' and a.RechargeDate >=\'' . $_GET['query_start_time'] . '\'';
        }

        if ($_GET['query_end_time']) {
            $sql = $sql . ' and a.RechargeDate < dateadd(day,1,\'' . $_GET['query_end_time'] . '\')';
        }

        //处理树的参数
        if ($_GET['orgids']) {
            $sql = $sql . ' and a.OrgID in ( ' . implode(',', $_GET['orgids']) . ')';
        }

        $search_type = $_GET['search_type'];
//        echo $search_type;
        $colconfig = $config;
//        var_dump($config[intval($search_type)]);
        $displaycol = array();
        $displaytext = array();
        $sumcol = array();
        $totalcol = array();
        $groupbycol = array();
        foreach ($sumtype as $i => $v) {
//            var_dump($colconfig['sumcol'][$v]);
            if(isset($colconfig['sqlwher'])){
                $sql = $sql . $colconfig['sqlwher'];
            }
            if (isset($colconfig['sumcol'][$v])) {
                if (isset($colconfig['sumcol'][$v]['cols'])) {
                    foreach ($colconfig['sumcol'][$v]['cols'] as $item) {
//                        echo $item['name'] . '<br>';
                        array_push($sumcol, $sqlarray[$item['name']]);
                        array_push($displaycol, $item['name']);
                        array_push($displaytext, $item['text']);
                        $itemsplit = explode(' as ', $sqlarray[$item['name']]);
                        array_push($totalcol, ' null as ' . $itemsplit[1]);
                        $str = strtolower(str_replace(' ', '', trim($itemsplit[0])));
                        if (substr($str, 0, 4) != 'sum(' && substr($str, 0, 6) != 'count(')
                            array_push($groupbycol, $itemsplit[0]);
                    }
                } else {
                    $item = $colconfig['sumcol'][$v];
                    array_push($sumcol, $sqlarray[$item['name']]);
                    array_push($displaycol, $item['name']);
                    array_push($displaytext, $item['text']);
                    $itemsplit = explode(' as ', $sqlarray[$item['name']]);
                    array_push($totalcol, ' null as ' . $itemsplit[1]);
                    $str = strtolower(str_replace(' ', '', trim($itemsplit[0])));
                    if (substr($str, 0, 4) != 'sum(' && substr($str, 0, 6) != 'count(')
                        array_push($groupbycol, $itemsplit[0]);
                }
            }
        }
        array_push($displaytext, '充值金额');
        array_push($displaytext, '赠送金额');
        array_push($displaytext, '产生金额');
//        var_dump($totalcol);
        $totalcol[0] = '\'总计：\' as ' . explode(' as ', $totalcol[0])[1];
//        var_dump($totalcol);
        $totalcolstr = join(',', $totalcol);
        $sumcolstr = join(',', $sumcol);
        $groupbycolstr = join(',', $groupbycol);
//        echo $sumcolstr;
        $tsql = " select $sumcolstr , sum(RechargeMoney) rechargemMoney,sum(GiveMoney) givemoney , sum(RechargeMoney+GiveMoney) allmoney
                        $sql group by $groupbycolstr order by $groupbycolstr ";
        //处理合计
        $totalsql = " select $totalcolstr , count(1) cliniccount
                        $sql ";
        if(isset($_GET['export']) && $_GET['export']=='true'){
            $this->exportxlsx(array(0=>$tsql,1=>$totalsql),$displaytext,'充值下账汇总');
        }
        $stmt = $conn->query($tsql);
        $data_list = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($data_list, $row);
        }

//        echo $totalsql;
        $totalstmt = $conn->query($totalsql);
        while ($row = $totalstmt->fetch(PDO::FETCH_OBJ)) {
            array_push($data_list, $row);
        }
        Tpl::output('data_list', $data_list);
        //--0:期初入库 1:采购入库 2:购进退回 3:盘盈 5:领用 12:盘亏 14:领用退回 50:采购计划
        Tpl::output('page', $page->show());
        //处理需要显示的列
        $col = array();
        foreach ($sumtype as $i => $v) {
            if (isset($sumtypestr[$v])) {
                foreach ($sumtypestr[$v] as $key => $item) {
                    $col[$key] = $item;
                }
            }
        }
//        var_dump($col);
        Tpl::output('displaycol', $displaycol);
        Tpl::output('displaytext', $displaytext);
        Tpl::showpage('member.recharge.sum');
    }

}
