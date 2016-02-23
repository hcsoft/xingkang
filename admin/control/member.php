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
defined ( 'InShopNC' ) or exit ( 'Access Invalid!' );
class memberControl extends SystemControl {
	const EXPORT_SIZE = 5000;
	public function __construct() {
		parent::__construct ();
		Language::read ( 'member' );
		$conn = require (BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
		$treesql = 'select  b.id , b.name,b.districtnumber,b.parentid pId from map_org_wechat a, Organization b where a.orgid = b.id ';
		$treestmt = $conn->query ( $treesql );
		$this->treedata_list = array ();
		$this->orgmap = array();
		while ( $row = $treestmt->fetch ( PDO::FETCH_OBJ ) ) {
			array_push ( $this->treedata_list, $row );
			$this->orgmap[$row->id] = $row->name;
		}
		Tpl::output ( 'treelist', $this->treedata_list );
		Tpl::output ( 'orgmap', $this->orgmap );
		
	}

	private function  memberlist($flag = true){
		$orderbys = array(
			array('txt'=>'预存余额','col'=> ' available_predeposit '),
			array('txt'=>'赠送余额','col'=> ' fConsumeBalance '),
			array('txt'=>'消费积分','col'=> ' member_points '));
		Tpl::output('orderbys',$orderbys);
		$model_member = Model ( 'member' );
        if(isset($_GET['containunreg']) and $_GET['containunreg'] != ''){

        }else{
            $condition ['containunreg']  = array('exp' , ' iMemberState <>99 ');
        }
		/**
		 * 检索条件
		 */
		if ($_GET['orgids']) {
			$condition ['CreateOrgID'] = array (
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
			$condition ['member_truename'] = array('like','%'.$_GET['name'].'%');
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
		
		if (isset($_GET['membersex']) and $_GET['membersex'] != '') {
			$condition ['member_sex'] = $_GET['membersex'];
		}
		
		if (isset($_GET['memberbirthday']) and $_GET['memberbirthday'] != '') {
			$condition ['member_birthday'] = array('exp' , ' MONTH(member_birthday) = '.$_GET['memberbirthday']);
		}
		
		if (isset($_GET['memberage']) and $_GET['memberage'] != '') {
			$memberage = $_GET['memberage'];
			if ($memberage == '0'){
				$condition ['iAge'] = array('exp' , ' iAge >= 65 ');
			}else if($memberage == '1'){
				$condition ['iAge'] = array('exp' , ' iAge <= 6');
			}else if($memberage == '99'){
				$definememberagestart = 0;
				$definememberageend = 100;
				if (isset($_GET['definememberagestart']) and $_GET['definememberagestart'] != ''){
					if(is_numberic($_GET['definememberagestart'])){
						$definememberage = $_GET['definememberagestart'];
					}
				}
				if (isset($_GET['definememberageend']) and $_GET['definememberageend'] != ''){
					if(is_numberic($_GET['definememberageend'])){
						$definememberageend = $_GET['definememberageend'];
					}
				}
				$condition ['iAge'] = array('exp' , ' iAge >=' . $definememberage .' and iAge <= ' . $definememberageend);
			}
		}
		if (isset($_GET['jifen']) and $_GET['jifen'] != '') {
			$jifen = $_GET['jifen'];
			if ($jifen == '0'){
				$condition ['member_points'] = array('exp' , ' member_points = 0 ');
			}else if($jifen == '1'){
				$condition ['member_points'] = array('exp' , ' member_points <= 1000');
			}else if($jifen == '2'){
				$condition ['member_points'] = array('exp' , ' member_points > 1000');
			}else if($jifen == '3'){
				$condition ['member_points'] = array('exp' , ' member_points > 3000');
			}else if($jifen == '4'){
				$condition ['member_points'] = array('exp' , ' member_points > 10000');
			}else if($jifen == '99'){
				$definejifen = 0;
				if (isset($_GET['definejifen']) and $_GET['definejifen'] != ''){
					if(is_numberic($_GET['definejifen'])){
						$definejifen = $_GET['definejifen'];
					}
				}
				$condition ['member_points'] = array('exp' , ' member_points =' . $definejifen);
			}
				
		}
		if(!isset($_GET['orderby'])){
			$_GET['orderby'] = '预存余额';
		}



		if(!isset($_GET['order'])){
			$ordersql = 'desc';
		}else{
			$ordersql = $_GET['order'];
		}
		if($_GET['orderby']){
			foreach($orderbys as $orderby){
				if($orderby['txt']==$_GET['orderby']){
					$order = $orderby['col'] .' ' . $ordersql;
					break;
				}
			}
		}
//		if ($_GET ['search_field_value'] != '') {
//			switch ($_GET ['search_field_name']) {
//				case 'member_name' :
//					$condition ['member_name'] = array (
//							'like',
//							'%' . trim ( $_GET ['search_field_value'] ) . '%'
//					);
//					break;
//				case 'member_email' :
//					$condition ['member_email'] = array (
//							'like',
//							'%' . trim ( $_GET ['search_field_value'] ) . '%'
//					);
//					break;
//				case 'member_truename' :
//					$condition ['member_truename'] = array (
//							'like',
//							'%' . trim ( $_GET ['search_field_value'] ) . '%'
//					);
//					break;
//			}
//		}
		if ($_GET ['member_id'] != '') {
			$condition ['member_id'] = array (
				'like',
				'%' . trim ( $_GET ['member_id'] ) . '%'
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
		/**
		 * 排序
		 */
//		$order = trim ( $_GET ['search_sort'] );
		if (empty ( $order )) {
			$order = 'member_id desc';
		}
		if(empty($flag)){
			$member_list = $model_member->getMemberList ( $condition, '*', 1000000, $order );
		}else{
			$member_list = $model_member->getMemberList ( $condition, '*', 10, $order );
		}
		/**
		 * 整理会员信息
		 */
//		if (is_array ( $member_list )) {
//			foreach ( $member_list as $k => $v ) {
//				$member_list [$k] ['member_time'] = $v ['member_time'] ? date ( 'Y-m-d H:i:s', $v ['member_time'] ) : '';
//				$member_list [$k] ['member_login_time'] = $v ['member_login_time'] ? date ( 'Y-m-d H:i:s', $v ['member_login_time'] ) : '';
//			}
//		}
		return array('list'=>$member_list,'md'=>$model_member);
	}

	
    private function  exportmemberlist($propertys,$propertymap,$fp,$flag = true){
        $orderbys = array(
            array('txt'=>'预存余额','col'=> ' available_predeposit '),
            array('txt'=>'赠送余额','col'=> ' fConsumeBalance '),
            array('txt'=>'消费积分','col'=> ' member_points '));
        Tpl::output('orderbys',$orderbys);
        $model_member = Model ( 'member' );
        if(isset($_GET['containunreg']) and $_GET['containunreg'] != ''){

        }else{
            $condition ['containunreg']  = array('exp' , ' iMemberState <>99 ');
        }
        /**
         * 检索条件
         */
        if ($_GET['orgids']) {
            $condition ['CreateOrgID'] = array (
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
            $condition ['member_truename'] = array('like','%'.$_GET['name'].'%');
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

        if(!isset($_GET['orderby'])){
            $_GET['orderby'] = '预存余额';
        }



        if(!isset($_GET['order'])){
            $ordersql = 'desc';
        }else{
            $ordersql = $_GET['order'];
        }
        if($_GET['orderby']){
            foreach($orderbys as $orderby){
                if($orderby['txt']==$_GET['orderby']){
                    $order = $orderby['col'] .' ' . $ordersql;
                    break;
                }
            }
        }
//		if ($_GET ['search_field_value'] != '') {
//			switch ($_GET ['search_field_name']) {
//				case 'member_name' :
//					$condition ['member_name'] = array (
//							'like',
//							'%' . trim ( $_GET ['search_field_value'] ) . '%'
//					);
//					break;
//				case 'member_email' :
//					$condition ['member_email'] = array (
//							'like',
//							'%' . trim ( $_GET ['search_field_value'] ) . '%'
//					);
//					break;
//				case 'member_truename' :
//					$condition ['member_truename'] = array (
//							'like',
//							'%' . trim ( $_GET ['search_field_value'] ) . '%'
//					);
//					break;
//			}
//		}
        if ($_GET ['member_id'] != '') {
            $condition ['member_id'] = array (
                'like',
                '%' . trim ( $_GET ['member_id'] ) . '%'
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
        /**
         * 排序
         */
//		$order = trim ( $_GET ['search_sort'] );
        if (empty ( $order )) {
            $order = 'member_id desc';
        }

        if(empty($flag)){
//            $member_list = $model_member->getMemberList ( $condition, , 100000, $order );
            $model_member->field('*')->where($condition)->page(1000000)->order($order)->exportcsv($propertys,$propertymap,$fp);
        }else{
//            $member_list = $model_member->getMemberList ( $condition, '*', 10, $order );
            $model_member->field('*')->where($condition)->page(10)->order($order)->exportcsv($propertys,$propertymap,$fp);
        }
        /**
         * 整理会员信息
         */
//		if (is_array ( $member_list )) {
//			foreach ( $member_list as $k => $v ) {
//				$member_list [$k] ['member_time'] = $v ['member_time'] ? date ( 'Y-m-d H:i:s', $v ['member_time'] ) : '';
//				$member_list [$k] ['member_login_time'] = $v ['member_login_time'] ? date ( 'Y-m-d H:i:s', $v ['member_login_time'] ) : '';
//			}
//		}
//        return array('list'=>$member_list,'md'=>$model_member);
    }

	private function  changeloglist($flag = true){
		$orderbys = array(
			array('txt'=>'预存余额','col'=> ' available_predeposit '),
			array('txt'=>'赠送余额','col'=> ' fConsumeBalance '),
			array('txt'=>'消费积分','col'=> ' member_points '));
		Tpl::output('orderbys',$orderbys);
		$model_member = Model ( );
		$model_member->table('member,__Center_MemberInfoChangeLog');
		$condition ['join'] = array('exp','member.member_id=__Center_MemberInfoChangeLog.memberid');
		/**
		 * 检索条件
		 */
		if ($_GET['orgids']) {
			$condition ['CreateOrgID'] = array (
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
			$condition ['member_truename'] = array('like','%'.$_GET['name'].'%');
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

		if(!isset($_GET['orderby'])){
			$_GET['orderby'] = '预存余额';
		}



		if(!isset($_GET['order'])){
			$ordersql = 'desc';
		}else{
			$ordersql = $_GET['order'];
		}
		if($_GET['orderby']){
			foreach($orderbys as $orderby){
				if($orderby['txt']==$_GET['orderby']){
					$order = $orderby['col'] .' ' . $ordersql;
					break;
				}
			}
		}
//		if ($_GET ['search_field_value'] != '') {
//			switch ($_GET ['search_field_name']) {
//				case 'member_name' :
//					$condition ['member_name'] = array (
//							'like',
//							'%' . trim ( $_GET ['search_field_value'] ) . '%'
//					);
//					break;
//				case 'member_email' :
//					$condition ['member_email'] = array (
//							'like',
//							'%' . trim ( $_GET ['search_field_value'] ) . '%'
//					);
//					break;
//				case 'member_truename' :
//					$condition ['member_truename'] = array (
//							'like',
//							'%' . trim ( $_GET ['search_field_value'] ) . '%'
//					);
//					break;
//			}
//		}
		if ($_GET ['member_id'] != '') {
			$condition ['member_id'] = array (
				'like',
				'%' . trim ( $_GET ['member_id'] ) . '%'
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
		/**
		 * 排序
		 */
//		$order = trim ( $_GET ['search_sort'] );
		if (empty ( $order )) {
			$order = 'member_id desc';
		}
		if(empty($flag)){
//			$member_list = $model_member->getMemberList ( $condition, '*', 100000, $order );

			$member_list = $model_member->field('*')->where($condition)->page(100000)->order($order)->select();
		}else{
			$member_list = $model_member->field('*')->where($condition)->page(10)->order($order)->select();
		}
		/**
		 * 整理会员信息
		 */
//		if (is_array ( $member_list )) {
//			foreach ( $member_list as $k => $v ) {
//				$member_list [$k] ['member_time'] = $v ['member_time'] ? date ( 'Y-m-d H:i:s', $v ['member_time'] ) : '';
//				$member_list [$k] ['member_login_time'] = $v ['member_login_time'] ? date ( 'Y-m-d H:i:s', $v ['member_login_time'] ) : '';
//			}
//		}
		return array('list'=>$member_list,'md'=>$model_member);
	}

	private function  unregisterlist($flag = true){
		$orderbys = array(
			array('txt'=>'预存余额','col'=> ' available_predeposit '),
			array('txt'=>'赠送余额','col'=> ' fConsumeBalance '),
			array('txt'=>'消费积分','col'=> ' member_points '));
		Tpl::output('orderbys',$orderbys);
		$model_member = Model ( );
		$model_member->table('member,__Center_MemberChangeLog log');
		$condition ['join'] = array('exp','member.member_id=log.MemberIDOld');
		/**
		 * 检索条件
		 */
		if ($_GET['orgids']) {
			$condition ['CreateOrgID'] = array (
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
			$condition ['member_truename'] = array('like','%'.$_GET['name'].'%');
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

		if(!isset($_GET['orderby'])){
			$_GET['orderby'] = '预存余额';
		}



		if(!isset($_GET['order'])){
			$ordersql = 'desc';
		}else{
			$ordersql = $_GET['order'];
		}
		if($_GET['orderby']){
			foreach($orderbys as $orderby){
				if($orderby['txt']==$_GET['orderby']){
					$order = $orderby['col'] .' ' . $ordersql;
					break;
				}
			}
		}
//		if ($_GET ['search_field_value'] != '') {
//			switch ($_GET ['search_field_name']) {
//				case 'member_name' :
//					$condition ['member_name'] = array (
//							'like',
//							'%' . trim ( $_GET ['search_field_value'] ) . '%'
//					);
//					break;
//				case 'member_email' :
//					$condition ['member_email'] = array (
//							'like',
//							'%' . trim ( $_GET ['search_field_value'] ) . '%'
//					);
//					break;
//				case 'member_truename' :
//					$condition ['member_truename'] = array (
//							'like',
//							'%' . trim ( $_GET ['search_field_value'] ) . '%'
//					);
//					break;
//			}
//		}
		if ($_GET ['member_id'] != '') {
			$condition ['member_id'] = array (
				'like',
				'%' . trim ( $_GET ['member_id'] ) . '%'
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
		/**
		 * 排序
		 */
//		$order = trim ( $_GET ['search_sort'] );
		if (empty ( $order )) {
			$order = 'member_id desc';
		}
		if(empty($flag)){
//			$member_list = $model_member->getMemberList ( $condition, '*', 100000, $order );

			$member_list = $model_member->field('member.*,log.dChangeDate,log.sMemo,log.UpdatePerson,log.fRecharge logfRecharge,log.fConsume logfConsume,log.fScale logfScale ')->where($condition)->page(100000)->order($order)->select();
		}else{
			$member_list = $model_member->field('member.*,log.dChangeDate,log.sMemo,log.UpdatePerson,log.fRecharge logfRecharge,log.fConsume logfConsume,log.fScale logfScale ')->where($condition)->page(10)->order($order)->select();
		}
		/**
		 * 整理会员信息
		 */
//		if (is_array ( $member_list )) {
//			foreach ( $member_list as $k => $v ) {
//				$member_list [$k] ['member_time'] = $v ['member_time'] ? date ( 'Y-m-d H:i:s', $v ['member_time'] ) : '';
//				$member_list [$k] ['member_login_time'] = $v ['member_login_time'] ? date ( 'Y-m-d H:i:s', $v ['member_login_time'] ) : '';
//			}
//		}
		return array('list'=>$member_list,'md'=>$model_member);
	}
	/**
	 * 会员管理
	 */
	public function memberOp() {
		$lang = Language::getLangContent ();
		$conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
		$data = $this->memberlist();
		$member_list = $data['list'];	
// 		var_dump($member_list);
		Tpl::output ( 'member_id', trim ( $_GET ['member_id'] ) );
		Tpl::output ( 'search_sort', trim ( $_GET ['search_sort'] ) );
		Tpl::output ( 'search_field_name', trim ( $_GET ['search_field_name'] ) );
		Tpl::output ( 'search_field_value', trim ( $_GET ['search_field_value'] ) );
		Tpl::output ( 'member_list', $member_list );
		Tpl::output ( 'page', $data['md']->showpage());
		Tpl::showpage ( 'member.index' );
	}
	
	public function member2Op() {
		$lang = Language::getLangContent ();
		$conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
// 		$mo
		$model_member = Model ( 'member' );
		$condition ['member_id'] = intval ( $_GET ['member_id'] );
		$member_array = $model_member->getMemberInfo ( $condition );
// 		var_dump($member_array['member_truename']);
		$initsql ='select a.FileNo,b.Name,a.Sex,a.Birthday,a.IDNumber,a.TEL,b.Address from PersonalInfo a,HealthFile b where 1=1 and a.FileNo = b.FileNo ';

		if((isset($_REQUEST['queryname']) and $_REQUEST['queryname'] != '')){
			$initsql = $initsql . ' and Name like \'%'.$_REQUEST['queryname'].'%\'';
		}
		if((isset($_REQUEST['queryidnumber']) and $_REQUEST['queryidnumber'] != '')){
			$initsql = $initsql . ' and IDNumber = \''.$_REQUEST['queryidnumber'].'\'';
		}
		if((isset($_REQUEST['queryfileno']) and $_REQUEST['queryfileno'] != '')){
			$initsql = $initsql . ' and a.FileNo = \''.$_REQUEST['queryfileno'].'\'';
		}
		if((isset($_REQUEST['querytel']) and $_REQUEST['querytel'] != '')){
			$initsql = $initsql . ' and a.TEL = \''.$_REQUEST['querytel'].'\'';
		}
		$stmt = $conn->query($initsql);
// 		var_dump($initsql);
		$data_list = array();
		while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
			array_push($data_list, $row);
		}
// 		$treedata_list = array();
//530100
		$pid = 5301;
// 		$t = $this->getTreeData($pid);
		$treesql = 'select  ID as id, Name as name, ParentID as pId from District where 1=1 and ID like \''.$pid.'%\'';
		$treestmt = $conn->query($treesql);
		$treedata_list = array();
		
		while ($row = $treestmt->fetch(PDO::FETCH_OBJ)) {
			array_push($treedata_list, $row);
		}
		Tpl::output('treedata', $treedata_list);
// 		$this->getTreeData();
// 		var_dump($sql);
// 		$stmt = $conn->query($sql);
// 		$data_list = array();
// 		while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
// 			array_push($data_list, $row);
// 		}$member_array['member_truename']
		Tpl::output ( 'member_id', $member_array['member_id'] );
		Tpl::output ( 'member_truename', $member_array['member_truename'] );
		Tpl::output ( 'member_idnumber', $member_array['sIDCard'] );
		Tpl::output ( 'member_list', $data_list );
		if((isset($_REQUEST['queryfileno']) and $_REQUEST['queryfileno'] != '')){
			Tpl::output ( 'fileno',$_REQUEST['queryfileno'] );
			Tpl::showpage ( 'member.list2' );
		}else {
			Tpl::showpage ( 'member.list' );
		}
		
	}
	
	public function gethealthfiledetailOp() {
		$lang = Language::getLangContent ();
		$conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
		$initsql ='select a.*,b.* from PersonalInfo a,HealthFile b where 1=1 and a.FileNo = b.FileNo ' ;
		if((isset($_REQUEST['fileno']) and $_REQUEST['fileno'] != '')){
			$sql = $initsql . ' and b.FileNo = \''.$_REQUEST['fileno'].'\'';
			//echo $sql;
			$stmt = $conn->query($sql);
			$data_list = array();
			while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
				array_push($data_list, $row);
			}
			if(!empty($data_list)){
				
				echo json_encode(array('success' => true, 'msg' => '查询成功!' ,'data'=>$data_list[0]));
				exit;
				//return $data_list[0];
			}
		}
		echo json_encode(array('success' => false, 'msg' => '查询失败!' ));
		exit;
	}
	
	public function changelogOp() {
		$lang = Language::getLangContent ();
		$data = $this->changeloglist();
		$member_list = $data['list'];
		Tpl::output ( 'member_id', trim ( $_GET ['member_id'] ) );
		Tpl::output ( 'search_sort', trim ( $_GET ['search_sort'] ) );
		Tpl::output ( 'search_field_name', trim ( $_GET ['search_field_name'] ) );
		Tpl::output ( 'search_field_value', trim ( $_GET ['search_field_value'] ) );
		Tpl::output ( 'member_list', $member_list );
		Tpl::output ( 'page', $data['md']->showpage () );
		Tpl::showpage ( 'member.changelog' );
	}

	public function unregisterlogOp() {
		$lang = Language::getLangContent ();
		$data = $this->unregisterlist();
		$member_list = $data['list'];
		Tpl::output ( 'member_id', trim ( $_GET ['member_id'] ) );
		Tpl::output ( 'search_sort', trim ( $_GET ['search_sort'] ) );
		Tpl::output ( 'search_field_name', trim ( $_GET ['search_field_name'] ) );
		Tpl::output ( 'search_field_value', trim ( $_GET ['search_field_value'] ) );
		Tpl::output ( 'member_list', $member_list );
		Tpl::output ( 'page', $data['md']->showpage () );
		Tpl::showpage ( 'member.unregister' );
	}

	public function mbdataexportOp(){
//        $fp =  tmpfile();
        $tmpfname = tempnam("./tmp/", '');

		$titles = [
			'卡号',
			'姓名',
			'电话',
			'地址',
			'身份证',
			'生日',
			'医保卡',
			'健康档案',
			'卡类型',
			'卡级别',
			'建卡时间',
			'建卡机构',
			'办卡渠道',
			'推荐人',
			'末次消费日期',
			'末次消费地点',
			'储值余额',
			'赠送余额',
			'消费积分'];
        $fp=$this::prepareexportcsv($titles,$tmpfname);
        $propertys = [
            'member_id',
            'member_truename',
            'sLinkPhone',
            'sAddress',
            'sIDCard',
            'member_birthday',
            'MediCardID',
            'FileNo',
            'CardType',
            'CardGrade',
            'dCreateDate',
            'CreateOrgID',
            'GetWay',
            'Referrer',
            'LastPayDate',
            'LastPayOrgName',
            'available_predeposit',
            'fConsumeBalance',
            'member_points'];
        $propertymap = [
            'CardType'=> array('0'=>'普通卡','1'=>'储值卡'),
            'CardGrade'=> array('0'=>'健康卡','1'=>'健康金卡','2'=>'健康钻卡'),
            'CreateOrgID' => $this->orgmap,
        ];
        $this->exportmemberlist($propertys,$propertymap,$fp,false);
        $this::endexportcsv($tmpfname,"会员",$fp);
        exit;
	}
	
	/**
	 * 会员修改
	 */
	public function member_editOp() {
		$lang = Language::getLangContent ();
		$model_member = Model ( 'member' );
		/**
		 * 保存
		 */
		if (chksubmit ()) {
			/**
			 * 验证
			 */
			$obj_validate = new Validate ();
			$obj_validate->validateparam = array (
					array (
							"input" => $_POST ["member_email"],
							"require" => "true",
							'validator' => 'Email',
							"message" => $lang ['member_edit_valid_email'] 
					) 
			);
			$error = $obj_validate->validate ();
			if ($error != '') {
				showMessage ( $error );
			} else {
				$update_array = array ();
				$update_array ['member_id'] = intval ( $_POST ['member_id'] );
				if (! empty ( $_POST ['member_passwd'] )) {
					$update_array ['member_passwd'] = md5 ( $_POST ['member_passwd'] );
				}
				$update_array ['member_email'] = trim ( $_POST ['member_email'] );
				$update_array ['member_truename'] = trim ( $_POST ['member_truename'] );
				$update_array ['member_sex'] = trim ( $_POST ['member_sex'] );
				$update_array ['member_qq'] = trim ( $_POST ['member_qq'] );
				$update_array ['member_ww'] = trim ( $_POST ['member_ww'] );
				$update_array ['inform_allow'] = trim ( $_POST ['inform_allow'] );
				$update_array ['is_buy'] = trim ( $_POST ['isbuy'] );
				$update_array ['is_allowtalk'] = trim ( $_POST ['allowtalk'] );
				$update_array ['member_state'] = trim ( $_POST ['memberstate'] );
				if (! empty ( $_POST ['member_avatar'] )) {
					$update_array ['member_avatar'] = $_POST ['member_avatar'];
				}
				$result = $model_member->updateMember ( $update_array, intval ( $_POST ['member_id'] ) );
				if ($result) {
					$url = array (
							array (
									'url' => 'index.php?act=member&op=member',
									'msg' => $lang ['member_edit_back_to_list'] 
							),
							array (
									'url' => 'index.php?act=member&op=member_edit&member_id=' . intval ( $_POST ['member_id'] ),
									'msg' => $lang ['member_edit_again'] 
							) 
					);
					$this->log ( L ( 'nc_edit,member_index_name' ) . '[ID:' . $_POST ['member_id'] . ']', 1 );
					showMessage ( $lang ['member_edit_succ'], $url );
				} else {
					showMessage ( $lang ['member_edit_fail'] );
				}
			}
		}
		$condition ['member_id'] = intval ( $_GET ['member_id'] );
		$member_array = $model_member->infoMember ( $condition );
		
		Tpl::output ( 'member_array', $member_array );
		Tpl::showpage ( 'member.edit' );
	}
	
	/**
	 * 新增会员
	 */
	public function member_addOp() {
		$lang = Language::getLangContent ();
		$model_member = Model ( 'member' );
		/**
		 * 保存
		 */
		if (chksubmit ()) {
			/**
			 * 验证
			 */
			$obj_validate = new Validate ();
			$obj_validate->validateparam = array (
					array (
							"input" => $_POST ["member_email"],
							"require" => "true",
							'validator' => 'Email',
							"message" => $lang ['member_edit_valid_email'] 
					) 
			);
			$error = $obj_validate->validate ();
			if ($error != '') {
				showMessage ( $error );
			} else {
				$insert_array = array ();
				$insert_array ['member_name'] = trim ( $_POST ['member_name'] );
				$insert_array ['member_passwd'] = trim ( $_POST ['member_passwd'] );
				$insert_array ['member_email'] = trim ( $_POST ['member_email'] );
				$insert_array ['member_truename'] = trim ( $_POST ['member_truename'] );
				$insert_array ['member_sex'] = trim ( $_POST ['member_sex'] );
				$insert_array ['member_qq'] = trim ( $_POST ['member_qq'] );
				$insert_array ['member_ww'] = trim ( $_POST ['member_ww'] );
				// 默认允许举报商品
				$insert_array ['inform_allow'] = '1';
				if (! empty ( $_POST ['member_avatar'] )) {
					$insert_array ['member_avatar'] = trim ( $_POST ['member_avatar'] );
				}
				
				$result = $model_member->addMember ( $insert_array );
				if ($result) {
					$url = array (
							array (
									'url' => 'index.php?act=member&op=member',
									'msg' => $lang ['member_add_back_to_list'] 
							),
							array (
									'url' => 'index.php?act=member&op=member_add',
									'msg' => $lang ['member_add_again'] 
							) 
					);
					$this->log ( L ( 'nc_add,member_index_name' ) . '[	' . $_POST ['member_name'] . ']', 1 );
					showMessage ( $lang ['member_add_succ'], $url );
				} else {
					showMessage ( $lang ['member_add_fail'] );
				}
			}
		}
		Tpl::showpage ( 'member.add' );
	}
	
	/**
	 * ajax操作
	 */
	public function ajaxOp() {
		switch ($_GET ['branch']) {
			/**
			 * 验证会员是否重复
			 */
			case 'check_user_name' :
				$model_member = Model ( 'member' );
				$condition ['member_name'] = trim ( $_GET ['member_name'] );
				$condition ['no_member_id'] = intval ( $_GET ['member_id'] );
				$list = $model_member->infoMember ( $condition );
				if (empty ( $list )) {
					echo 'true';
					exit ();
				} else {
					echo 'false';
					exit ();
				}
				break;
			/**
			 * 验证邮件是否重复
			 */
			case 'check_email' :
				$model_member = Model ( 'member' );
				$condition ['member_email'] = trim ( $_GET ['member_email'] );
				$condition ['no_member_id'] = intval ( $_GET ['member_id'] );
				$list = $model_member->infoMember ( $condition );
				if (empty ( $list )) {
					echo 'true';
					exit ();
				} else {
					echo 'false';
					exit ();
				}
				break;
		}
	}
	
	
	public function consumesumOp() {
		$conn = require (BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
		if (! isset ( $_GET ['search_type'] )) {
			$_GET ['search_type'] = '0';
		}
		$sqlarray = array (
				'membername' => 'member.sName as "membername"',
				'memberid' => ' member.sMemberID as "memberid" ',
				'year' => ' year(a.dCO_Date) as "year" ',
				'month' => ' left(convert(varchar,dCO_Date,112),6) as  "month" ',
				'day' => ' convert(varchar,dCO_Date,112) as "day" ',
				'OrgID' => ' org.name as "OrgID" ' 
		);
		$sqlarray1 = array (
			'membername' => 'member.sName as "membername"',
			'memberid' => ' member.sMemberID as "memberid" ',
			'year' => ' year(a.RechargeDate) as "year" ',
			'month' => ' left(convert(varchar,RechargeDate,112),6) as  "month" ',
			'day' => ' convert(varchar,RechargeDate,112) as "day" ',
			'OrgID' => ' org.name as "OrgID" '
		);
		$sqlarray2 = array (
			'membername' => '  "membername"',
			'memberid' => ' "MemberID" ',
			'year' => '   "year" ',
			'month' => '    "month" ',
			'day' => '   "day" ',
			'OrgID' => '   "OrgID" '
		);
		$config = array (
				'sumcol' => array (
						'OrgID' => array (
								name => 'OrgID',
								'text' => '机构' 
						),
						'member' => array (
								'text' => '会员',
								name => 'member',
								'cols' => array (
										0 => array (
												name => 'memberid',
												'text' => '会员号码' 
										),
										1 => array (
												name => 'membername',
												'text' => '会员名称' 
										) 
								) 
						),
						'year' => array (
								'text' => '年',
								name => 'year',
								uncheck => 'month,day' 
						),
						'month' => array (
								'text' => '月',
								name => 'month',
								uncheck => 'year,day' 
						),
						'day' => array (
								'text' => '日',
								name => 'day',
								uncheck => 'year,month' 
						) 
				) 
		);
		Tpl::output ( 'config', $config );
		
		// 处理汇总字段
		$sumtype = $_GET ['sumtype'];
		if ($sumtype == null) {
			$sumtype = array (
					0 => "OrgID" 
			);
			$_GET ['sumtype'] = $sumtype;
		}
		$checked = $_GET ['checked'];
		$page = new Page ();
		$page->setEachNum ( 10 );
		$page->setNowPage ( $_REQUEST ["curpage"] );
		$sql = 'from Center_CheckOut a  left join  Center_MemberInfo member on a.sMemberID = member.sMemberID ,
             Organization org
          where isnull(a.smemberid , \'\') <> \'\'
           and a.orgid = org.id ';
		
		if ($_GET ['query_start_time']) {
			$sql = $sql . ' and a.dCO_Date >=\'' . $_GET ['query_start_time'] . '\'';
		}
		
		if ($_GET ['query_end_time']) {
			$sql = $sql . ' and a.dCO_Date < dateadd(day,1,\'' . $_GET ['query_end_time'] . '\')';
		}
		
		// 处理树的参数
		if ($_GET ['orgids']) {
			$sql = $sql . ' and a.OrgID in ( ' . implode ( ',', $_GET ['orgids'] ) . ')';
		}

		$msql = 'from center_MemberRecharge a left join  Center_MemberInfo member on a.MemberID = member.sMemberID ,
             Organization org
          where [type]=2
           and a.orgid = org.id ';

		if ($_GET ['query_start_time']) {
			$msql = $msql . ' and a.RechargeDate >=\'' . $_GET ['query_start_time'] . '\'';
		}

		if ($_GET ['query_end_time']) {
			$msql = $msql . ' and a.RechargeDate < dateadd(day,1,\'' . $_GET ['query_end_time'] . '\')';
		}

		// 处理树的参数
		if ($_GET ['orgids']) {
			$msql = $msql . ' and a.OrgID in ( ' . implode ( ',', $_GET ['orgids'] ) . ')';
		}
		
		$search_type = $_GET ['search_type'];
		// echo $search_type;
		$colconfig = $config;
		// var_dump($config[intval($search_type)]);
		$displaycol = array ();
		$displaytext = array ();
		$sumcol = array ();
		$sumcol1 = array ();
		$sumcol2 = array();
		$totalcol = array ();
		$totalcol1 = array ();
		$groupbycol = array ();
		$groupbycol1 = array ();
		foreach ( $sumtype as $i => $v ) {
			// var_dump($colconfig['sumcol'][$v]);
			if (isset ( $colconfig ['sqlwher'] )) {
				$sql = $sql . $colconfig ['sqlwher'];
			}
			if (isset ( $colconfig ['sumcol'] [$v] )) {
				if (isset ( $colconfig ['sumcol'] [$v] ['cols'] )) {
					
					foreach ( $colconfig ['sumcol'] [$v] ['cols'] as $item ) {
						// echo $item['name'] . '<br>';
						array_push ( $sumcol, $sqlarray [$item ['name']] );
						array_push ( $sumcol1, $sqlarray1 [$item ['name']] );
						array_push ( $sumcol2, $sqlarray2 [$item ['name']] );
						array_push ( $displaycol, $item ['name'] );
						array_push ( $displaytext, $item ['text'] );
						$itemsplit = explode ( ' as ', $sqlarray [$item ['name']] );
						$itemsplit1 = explode ( ' as ', $sqlarray [$item ['name']] );
						array_push ( $totalcol, ' null as ' . $itemsplit [1] );
						array_push ( $totalcol1, ' null as ' . $itemsplit1 [1] );
						$str = strtolower ( str_replace ( ' ', '', trim ( $itemsplit [0] ) ) );
						if (substr ( $str, 0, 4 ) != 'sum(' && substr ( $str, 0, 6 ) != 'count(')
							array_push ( $groupbycol, $itemsplit [0] );
						$str1 = strtolower ( str_replace ( ' ', '', trim ( $itemsplit1 [0] ) ) );
						if (substr ( $str1, 0, 4 ) != 'sum(' && substr ( $str1, 0, 6 ) != 'count(')
							array_push ( $groupbycol1, $itemsplit1 [0] );
					}
				} else {
					$item = $colconfig ['sumcol'] [$v];
					array_push ( $sumcol, $sqlarray [$item ['name']] );
					array_push ( $sumcol1, $sqlarray1 [$item ['name']] );
					array_push ( $sumcol2, $sqlarray2 [$item ['name']] );
					array_push ( $displaycol, $item ['name'] );
					array_push ( $displaytext, $item ['text'] );
					$itemsplit = explode ( ' as ', $sqlarray [$item ['name']] );
					$itemsplit1 = explode ( ' as ', $sqlarray1 [$item ['name']] );
					array_push ( $totalcol, ' null as ' . $itemsplit [1] );
					array_push ( $totalcol1, ' null as ' . $itemsplit1 [1] );
					$str = strtolower ( str_replace ( ' ', '', trim ( $itemsplit [0] ) ) );
					if (substr ( $str, 0, 4 ) != 'sum(' && substr ( $str, 0, 6 ) != 'count(')
						array_push ( $groupbycol, $itemsplit [0] );
					$str1 = strtolower ( str_replace ( ' ', '', trim ( $itemsplit1 [0] ) ) );
					if (substr ( $str1, 0, 4 ) != 'sum(' && substr ( $str1, 0, 6 ) != 'count(')
						array_push ( $groupbycol1, $itemsplit1 [0] );
				}
			}
		}
		array_push ( $displaytext, '消费金额' );
		// var_dump($totalcol);
		$totalcol [0] = '\'总计：\' as ' . explode ( ' as ', $totalcol [0] )[1];
		// var_dump($totalcol);
		$totalcolstr = join ( ',', $totalcol );
		$totalcolstr1 = join ( ',', $totalcol1 );
		$sumcolstr = join ( ',', $sumcol );
		$sumcolstr1 = join ( ',', $sumcol1 );
		$sumcolstr2 = join ( ',', $sumcol2 );
		$groupbycolstr = join ( ',', $groupbycol );
		$groupbycolstr1 = join ( ',', $groupbycol1 );
		// echo $sumcolstr;
		$tsql = " select $sumcolstr2 ,sum(getmoney) getmoney
				 from (
					select $sumcolstr ,sum(fCO_GetMoney) getmoney
                        $sql group by $groupbycolstr
                  union all
                  select $sumcolstr1 ,sum(-RechargeMoney) getmoney
                        $msql group by $groupbycolstr1
                        ) zzz
                         group by $sumcolstr2 order by $sumcolstr2";

//		 echo $tsql;
		// 处理合计
		$totalsql = " select $totalcolstr ,  sum(getmoney) getmoney
                        from (
						select $sumcolstr ,sum(fCO_FactMoney) getmoney
							$sql group by $groupbycolstr
					  union all
					  select $sumcolstr1 ,sum(-RechargeMoney) getmoney
							$msql group by $groupbycolstr1
                        ) zzz ";
//		echo $totalsql;
		if (isset ( $_GET ['export'] ) && $_GET ['export'] == 'true') {
			$this->exportxlsx ( array (
					0 => $tsql,
					1 => $totalsql 
			), $displaytext, '消费汇总' );
		}
		$stmt = $conn->query ( $tsql );
		$data_list = array ();
		while ( $row = $stmt->fetch ( PDO::FETCH_OBJ ) ) {
			array_push ( $data_list, $row );
		}
		
		// echo $totalsql;
		$totalstmt = $conn->query ( $totalsql );
		while ( $row = $totalstmt->fetch ( PDO::FETCH_OBJ ) ) {
			array_push ( $data_list, $row );
		}
		Tpl::output ( 'data_list', $data_list );
		// --0:期初入库 1:采购入库 2:购进退回 3:盘盈 5:领用 12:盘亏 14:领用退回 50:采购计划
		Tpl::output ( 'page', $page->show () );
		// 处理需要显示的列
		$col = array ();
		foreach ( $sumtype as $i => $v ) {
			if (isset ( $sumtypestr [$v] )) {
				foreach ( $sumtypestr [$v] as $key => $item ) {
					$col [$key] = $item;
				}
			}
		}
		// var_dump($col);
		Tpl::output ( 'displaycol', $displaycol );
		Tpl::output ( 'displaytext', $displaytext );
		Tpl::showpage ( 'member.consume.sum' );
	}
	
	public function rechargesumOp() {
		$conn = require (BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
		if (! isset ( $_GET ['search_type'] )) {
			$_GET ['search_type'] = '0';
		}
		$sqlarray = array (
				'ChargePerson' => 'person.sPerson_Name as "ChargePerson"',
				'type' => ' type.name as "type" ',
				'state' => ' state.name as "state" ',
				'year' => ' year(a.RechargeDate) as "year" ',
				'month' => ' left(convert(varchar,RechargeDate,112),6) as  "month" ',
				'day' => ' convert(varchar,RechargeDate,112) as "day" ',
				'OrgID' => ' org.name as "OrgID" ' 
		);
		// $config = array(0 => array('sumcol' => array('iBuy_Type' => array(name => 'iBuy_Type', 'text' => '单据类型', map => $this->types),
		// 'customname' => array(name => 'customname', 'text' => '供货企业'),
		// 'good' => array('text' => '商品',
		// 'cols' => array(0 => array(name => 'sDrug_TradeName', 'text' => '商品名称')
		// , 1 => array(name => 'sDrug_Spec', 'text' => '规格')
		// , 2 => array(name => 'sDrug_Unit', 'text' => '单位')
		// , 3 => array(name => 'sDrug_Brand', 'text' => '产地厂牌')
		// , 4 => array(name => 'drugcount', 'text' => '数量'))),
		// 'year' => array('text' => '年', name=>'year',uncheck=>'month,day' ),
		// 'month' => array('text' => '月', name=>'month',uncheck=>'year,day'),
		// 'day' => array('text' => '日', name=>'day',uncheck=>'year,month')
		// )));
		$config = array (
				'sumcol' => array (
						'OrgID' => array (
								name => 'OrgID',
								'text' => '机构' ,
								value =>0
						),
						'member' => array (
								'name' =>'member',
								'text' => '会员',
								value =>1,
								'cols' => array (
										0 => array (
												name => 'sMemberID',
												'text' => '会员卡号' 
										),
										1 => array (
												name => 'member_truename',
												'text' => '姓名' 
										),
										2 => array (
												name => 'member_sex',
												'text' => '性别' 
										),
										3 => array (
												name => 'Mobile',
												'text' => '联系电话'
										),
										4 => array (
												name => 'member_Birthday',
												'text' => '生日' 
										)
										
								) 
						),
						'Referrer' => array (
								name => 'Referrer',
								value =>2,
								'text' => '推荐人' 
						),
						'iYear' => array (
								'text' => '年',
								name => 'iYear',
								value =>3,
								uncheck => 'iMonth,dPayDate' 
						),
						'iMonth' => array (
								'text' => '月',
								name => 'iMonth',
								value =>4,
								uncheck => 'iYear,dPayDate' 
						),
						'dPayDate' => array (
								'text' => '日',
								name => 'dPayDate',
								value =>5,
								uncheck => 'iYear,iMonth' 
						) 
				) 
		);
		// $config = array('sumcol' => array('OrgID' => array(name => 'OrgID', 'text' => '机构'),
		// 'ChargePerson' => array(name => 'ChargePerson', 'text' => '收款人'),
		// 'type' => array(name => 'type', 'text' => '类型'),
		// 'state' => array(name => 'state', 'text' => '状态'),
		// 'year' => array('text' => '年', name=>'year',uncheck=>'month,day' ),
		// 'month' => array('text' => '月', name=>'month',uncheck=>'year,day'),
		// 'day' => array('text' => '日', name=>'day',uncheck=>'year,month'),
		// ));
		Tpl::output ( 'config', $config );
		
		// 处理汇总字段
		$sumtype = $_GET ['sumtype'];
		if ($sumtype == null) {
			$sumtype = array (
					0 => "OrgID" 
			);
			$_GET ['sumtype'] = $sumtype;
		}
		$checked = $_GET ['checked'];
		$page = new Page ();
		$page->setEachNum ( 10 );
		$page->setNowPage ( $_REQUEST ["curpage"] );
		$startnum = 0;
		$endnum = 1000000;
		
		if ($_GET ['query_start_time']) {
			$starttime =  $_GET ['query_start_time'] ;
		}
		
		if ($_GET ['query_end_time']) {
			$endtime = $_GET ['query_end_time'];
		}
		
		// 处理树的参数
		if ($_GET ['orgids']) {
			$orgids= implode ( ',', $_GET ['orgids'] );
		}
		
		$search_type = $_GET ['search_type'];
		// echo $search_type;
		$colconfig = $config;
		// var_dump($config[intval($search_type)]);
		$displaycol = array ();
		$displaytext = array ();
		$sumcol = array ();
		$totalcol = array ();
		$groupbycol = array ();
		
		$sumtypeparam = array(0=>'0',1=>'0',2=>'0',3=>'0',4=>'0',5=>'0');


		foreach ( $sumtype as $i => $v ) {
			// var_dump($colconfig['sumcol'][$v]);
			if (isset ( $colconfig ['sqlwher'] )) {
				$sql = $sql . $colconfig ['sqlwher'];
			}
			if (isset ( $colconfig ['sumcol'] [$v] )) {
				if (isset ( $colconfig ['sumcol'] [$v] ['cols'] )) {
					$sumtypeparam[$colconfig ['sumcol'] [$v]['value']] ='1';
					foreach ( $colconfig ['sumcol'] [$v] ['cols'] as $item ) {
						// echo $item['name'] . '<br>';
						
						array_push ( $sumcol, $sqlarray [$item ['name']] );
						array_push ( $displaycol, $item ['name'] );
						array_push ( $displaytext, $item ['text'] );
						$itemsplit = explode ( ' as ', $sqlarray [$item ['name']] );
						array_push ( $totalcol, ' null as ' . $itemsplit [1] );
						$str = strtolower ( str_replace ( ' ', '', trim ( $itemsplit [0] ) ) );
						if (substr ( $str, 0, 4 ) != 'sum(' && substr ( $str, 0, 6 ) != 'count(')
							array_push ( $groupbycol, $itemsplit [0] );
					}
				} else {
					$item = $colconfig ['sumcol'] [$v];
					$sumtypeparam[$item ['value']] ='1';
					array_push ( $sumcol, $sqlarray [$item ['name']] );
					array_push ( $displaycol, $item ['name'] );
					array_push ( $displaytext, $item ['text'] );
					$itemsplit = explode ( ' as ', $sqlarray [$item ['name']] );
					array_push ( $totalcol, ' null as ' . $itemsplit [1] );
					$str = strtolower ( str_replace ( ' ', '', trim ( $itemsplit [0] ) ) );
					if (substr ( $str, 0, 4 ) != 'sum(' && substr ( $str, 0, 6 ) != 'count(')
						array_push ( $groupbycol, $itemsplit [0] );
				}
			}
		}
        $exporttitle =array('序号');
        $exporttitle=  array_merge ($exporttitle,$displaytext);
        $exportproperty =array();
        $exportproperty=  array_merge ($exportproperty,$displaycol);

        $exporttitle = array_merge($exporttitle , array('普卡消费金额','期初预存','日常充值','日常下账','赠送金额','赠送下账','预存下账','赠送下账',
			'积分下账','扣减积分','赠送积分'));
        $exportproperty = array_merge($exportproperty , array('pkmoney','fRechargeInit','fRechargeAdd','fRechargeBuy','GiveMoney','GiveSaleMoney','fRecharge',
            'fConsume','fScaleToMoney','fScale','fAddScale'));
		$param1 = implode('', $sumtypeparam);
//		array_push ( $displaytext, '充值下账信息' );
//		array_push ( $displaytext, '诊疗购买信息' );
		
		
		$tsql = "SET NOCOUNT ON; Exec pFMemberPayStat '$param1','$orgids','$starttime','$endtime','','$startnum','$endnum';SET NOCOUNT off; ";
//		 echo $tsql;
		$stmt = $conn->prepare ( $tsql );
		$stmt->execute ();
		$data_list = array ();
        $sumrow = (object)array();
        foreach($exportproperty as $i=>$v){
            if(in_array($v , $displaycol)){
                $sumrow->$v = '';
            }else{
                $sumrow->$v = 0;
            }
            if($v == $displaycol[0]){
                $sumrow->$v = '总计:';
            }
        }
		while ( $row = $stmt->fetchObject () ) {
			array_push ( $data_list, $row );
            //计算合计
            foreach($exportproperty as $i=>$v){
				if(!in_array($v , $displaycol)){
					$sumrow->$v = $sumrow->$v + $row->$v;
				}
            }
		}
        array_push ( $data_list, $sumrow );

		if(isset($_GET['export']) && $_GET['export']=='true'){
            $this->exportxlsxbyArrayObject($exporttitle,$exportproperty,array(),'充值下账汇总',$data_list);
		}
//        echo json_encode($exportproperty);
        Tpl::output ( 'data_list', $data_list );
        Tpl::output('page', $page->show());
        Tpl::output ( 'displaycol', $displaycol );
        Tpl::output ( 'displaytext', $displaytext );
		Tpl::showpage ( 'member.recharge.sum' );
	}

	public function psresetOp()
	{
		try {
			$conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
			$cardid = $_REQUEST['cardid'];
			$sql = " update shopnc_member set  member_passwd = '000000' where member_id = ? ";
			$stmt = $conn->prepare($sql);
			$stmt->execute(array($cardid));

			echo json_encode(array('success' => true, 'msg' => '重置成功!'));
		} catch (Exception $e) {
			echo json_encode(array('success' => false, 'msg' => '异常!'.$e->getMessage()));
		}
		exit;
	}

	public function member_check_moneydetailOp(){
		$this->member_moneydetailOp();
	}


	public function member_moneydetailOp(){
		try {
			$conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
			$cardid = $_REQUEST['cardid1'];
			$datestart = date('2000-1-1',time());
			$dateend = date('2030-12-31',time());
			$sql = " SET NOCOUNT ON; exec pFMemberAccount '$cardid', '$datestart','$dateend' ;SET NOCOUNT off;";
			$stmt = $conn->prepare($sql);

			$stmt->execute(array($cardid));
			$data_list = array ();
			$initrecharge = 0;
			$initconsume = 0 ;
			$initscale = 0;
			while ( $row = $stmt->fetchObject () ) {
				array_push ( $data_list, $row );
				if($row->DateType==1){
					$initrecharge = floatval( $row->InitRecharge);
					$initconsume =floatval( $row->InitConsume );
					$initscale = floatval($row->InitScale);
				}else if($row->DateType < 7 ){
					$initrecharge = $initrecharge +floatval( $row->fRecharge);
					$initconsume = $initconsume +floatval( $row->fConsume);
					$initscale = $initscale + floatval($row->fScale) +floatval( $row->fAddScale);
					$row->InitRecharge = $initrecharge;
					$row->InitConsume = $initconsume;
					$row->InitScale = $initscale;
				}
			}
			echo json_encode(array('success' => true, 'msg' => '查询成功!' ,'data'=>$data_list ,'sql'=>$sql));
		} catch (Exception $e) {
			echo json_encode(array('success' => false, 'msg' => '异常!'.$e->getMessage()));
		}
		exit;
	}


	public function checkOp()
	{
        if(isset($_GET['export']) && $_GET['export']=='true'){
            $exportflag = true;
        }else{
            $exportflag = false;
        }
        $exporttitle = array();
        array_push($exporttitle,'序号');
        array_push($exporttitle,'卡号');
        array_push($exporttitle,'姓名');
        array_push($exporttitle,'机构编码');
        array_push($exporttitle,'机构名称');
        array_push($exporttitle,'储值余额');
        array_push($exporttitle,'计算储值余额');
        array_push($exporttitle,'赠送余额');
        array_push($exporttitle,'计算赠送余额');
        array_push($exporttitle,'积分余额');
        array_push($exporttitle,'计算积分余额');
        $propertyarray = array();
        array_push($propertyarray,'rownum');
        array_push($propertyarray,'member_id');
        array_push($propertyarray,'member_truename');
        array_push($propertyarray,'orgid');
        array_push($propertyarray,'orgname');
        array_push($propertyarray,'available_predeposit');
        array_push($propertyarray,'calc_predeposit');
        array_push($propertyarray,'fConsumeBalance');
        array_push($propertyarray,'calc_consume');
        array_push($propertyarray,'member_points');
        array_push($propertyarray,'calc_points');

        $orderbys = array(
			array('txt'=>'会员编号','col'=> ' member_id '),
			array('txt'=>'机构编码','col'=> ' orgid '),
			array('txt'=>'预存余额','col'=> ' available_predeposit '),
			array('txt'=>'赠送余额','col'=> ' fConsumeBalance '),
			array('txt'=>'消费积分','col'=> ' member_points '));
		Tpl::output('orderbys',$orderbys);
		$conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
		//处理数据
		$page = new Page();
		$page->setEachNum(10);
		$page->setNowPage($_REQUEST["curpage"]);
		$startnum = $page->getEachNum() * ($page->getNowPage() - 1);
		$endnum = $page->getEachNum() * ($page->getNowPage());
		$sql = '';

		if ($_GET['orgids']) {
			$sql = $sql . ' and CreateOrgID in ( ' . implode(',', $_GET['orgids']) . ')';
		}

		if (isset($_GET['cardtype']) and $_GET['cardtype'] != '') {
			$condition ['cardtype'] = $_GET['cardtype'];
			$sql = $sql . ' and cardtype  =  \''.$_GET['cardtype'].'\'';
		}

		if (isset($_GET['cardgrade']) and $_GET['cardgrade'] != '') {
			$sql = $sql . ' and cardgrade  =  \''.$_GET['cardgrade'].'\'';
		}




		if(!isset($_GET['order'])){
			$ordersql = 'desc';
		}else{
			$ordersql = $_GET['order'];
		}
		if($_GET['orderby']){
			foreach($orderbys as $orderby){
				if($orderby['txt']==$_GET['orderby']){
					$order = $orderby['col'] .' ' . $ordersql;
					break;
				}
			}
		}
		if ($_GET ['search_field_value'] != '') {
			switch ($_GET ['search_field_name']) {
				case 'member_name' :
					$sql = $sql . ' and member_name  like  \'%'.$_GET['search_field_value'].'%\'';
					break;
				case 'member_email' :
					$sql = $sql . ' and member_email  like  \'%'.$_GET['search_field_value'].'%\'';
					break;
				case 'member_truename' :
					$sql = $sql . ' and member_truename  like  \'%'.$_GET['search_field_value'].'%\'';
					break;
			}
		}
		if ($_GET ['member_id'] != '') {
			$sql = $sql . ' and member_id  like  \'%'.$_GET['member_id'].'%\'';
		}
		switch ($_GET ['search_state']) {
			case 'no_informallow' :
				$sql = $sql . ' and inform_allow  =  \'2\' ';

				break;
			case 'no_isbuy' :
				$sql = $sql . ' and is_buy  =  \'0\' ';
				break;
			case 'no_isallowtalk' :
				$sql = $sql . ' and is_allowtalk  =  \'0\' ';
				break;
			case 'no_memberstate' :
				$sql = $sql . ' and member_state  =  \'0\' ';
				break;
		}
//		if (!$_GET ['flag1'] && !$_GET ['flag2'] && !$_GET ['flag3']){
//			$_GET ['flag1'] = '1';
//		}

		if ($_GET ['flag1'] && $_GET ['flag1'] == '1'){
			$flag1 = 1;
		}else{
			$flag1 = 0 ;
		}

		if ($_GET ['flag2'] && $_GET ['flag2'] == '1'){
			$flag2 = 1;
		}else{
			$flag2 = 0 ;
		}

		if ($_GET ['flag3'] && $_GET ['flag3'] == '1'){
			$flag3 = 1;
		}else{
			$flag3 = 0 ;
		}

		/**
		 * 排序
		 */
		$orderbysql = ' member_id ';
		$ordersql = 'desc';

		if(!isset($_GET['orderby'])){
			$_GET['orderby'] = '会员编号';
		}
		if(!isset($_GET['order'])){
			$ordersql = 'desc';
		}else{
			$ordersql = $_GET['order'];
		}
		foreach($orderbys as $orderby){
			if($orderby['txt']==$_GET['orderby']){
				$orderbysql = $orderby['col'];
				break;
			}
		}

		$paramsql = $sql . ' order by ' .$orderbysql .$ordersql;
		$paramsql = str_replace('\'','\'\'',$paramsql);
        if($exportflag){
            $startnum = 0;
            $endnum = 1000000;
        }
		$tsql = "SET NOCOUNT ON; Exec p_query_member_check '$paramsql','$startnum','$endnum',$flag1,$flag2,$flag3;SET NOCOUNT off; ";
//		echo $tsql;
		$stmt = $conn->query($tsql);
		//第一次获得总页数
		$total = $stmt->fetch(PDO::FETCH_NUM);
		$page->setTotalNum($total[0]);
		//第二次获得数据
		$stmt->nextRowset();
		$data_list = array();
        if($exportflag){
            $tmpfname = tempnam("./tmp/", '');
            $fp = $this::prepareexportcsv($exporttitle,$tmpfname);
            while ($tmp = $stmt->fetch(PDO::FETCH_OBJ)) {
                $row = array();
                foreach($propertyarray as $i=>$v){
                    $cellstr = mb_convert_encoding(strval($tmp->$v), 'GBK','UTF-8');
                    if(! empty($propertymap[$v])){
                        $cellstr = mb_convert_encoding(strval($propertymap[$v][$tmp[$v]]), 'GBK','UTF-8');
                    }
                    array_push($row,  Db::csv($cellstr));
                }
                fwrite($fp,join(',',$row)."\r\n");
            }
            $this::endexportcsv($tmpfname,"会员储值积分对账",$fp);

        }else{
            while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                array_push($data_list, $row);
            }
        }
		Tpl::output('data_list', $data_list);
		Tpl::output('orderbys',$orderbys);
		Tpl::output('page', $page->show());
		Tpl::showpage ( 'member.check' );
	}



	public function modifymoneyOp()
	{
		try {
			$conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
			$cardid = $_REQUEST['member_id'];
			$new_available_predeposit = $_REQUEST['new_available_predeposit'];
			$new_fConsumeBalance = $_REQUEST['new_fConsumeBalance'];
			$new_member_points = $_REQUEST['new_member_points'];
			if($new_available_predeposit != ''){
				$sql = " update shopnc_member set  available_predeposit = ? where member_id = ? ";
				$stmt = $conn->prepare($sql);
				$stmt->execute(array($new_available_predeposit,$cardid));
			}

			if($new_fConsumeBalance != ''){
				$sql = " update shopnc_member set  fConsumeBalance = ? where member_id = ? ";
				$stmt = $conn->prepare($sql);
				$stmt->execute(array($new_fConsumeBalance,$cardid));
			}
			if($new_member_points != ''){
				$sql = " update shopnc_member set member_points = ? where member_id = ? ";
				$stmt = $conn->prepare($sql);
				$stmt->execute(array($new_member_points,$cardid));
			}
			echo json_encode(array('success' => true, 'msg' => '保存成功!'));
		} catch (Exception $e) {
			echo json_encode(array('success' => false, 'msg' => '异常!'.$e->getMessage()));
		}
		exit;
	}


	public function changebaseinfoOp()
	{
		//spotcheck_spot
		try {
			$conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
			$id = $_REQUEST['change_id'];
			$changelog = array();
			$changestr = '';
			$admin_info = $this->getAdminInfo();
			$opt = $admin_info['name'];
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

            //修改卡类型
            $newCardType = $_REQUEST["newCardType"];
            if ($newCardType == '0' || !empty($newCardType)) {
                $sql = " update shopnc_member set CardType = '$newCardType' where member_id = '$id'";
                $conn->exec($sql);
                $changelog['newCardType'] = $newCardType;
                $changelog['oldCardType'] = $_REQUEST["oldCardType"];
                $oldcardtext = $_REQUEST["oldCardType"] == '0' ? '普通卡' :'储值卡';
                $newcardtext = $newCardType == '0' ? '普通卡' :'储值卡';
                $changestr .= ',卡类型由(' . $oldcardtext. ')改为(' . $newcardtext . ')';
            }
			//修改性别
			$newsex = $_REQUEST["newsex"];
			if ( !empty($newsex)) {
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
//				$sql = " update shopnc_member set member_id = '$newid' where member_id = '$id'";
//				$conn->exec($sql);

				$logsql = " exec pFChangeMemberLog 0,'$opt','会员信息修改','$id','$newid';  ";

				$conn->exec($logsql);
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

			$sql = " insert into Center_MemberInfoChangeLog (id,memberid,optdate,changelog,changestr,opt) values(newid(),'$id',getdate(),'$changelogstr','$changestr','$opt')";
			$conn->exec($sql);

			echo json_encode(array('success' => true, 'msg' => '保存成功!'));
		} catch (Exception $e) {
			echo json_encode(array('success' => false, 'msg' => '异常!' . $e->getMessage()));
		}
		exit;
	}


	public function unregisterOp()
	{
		//spotcheck_spot
		try {
			$conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
			$id = $_REQUEST['unregister_id'];
			$memo = $_REQUEST['unregister_memo'];
			$admin_info = $this->getAdminInfo();
			$opt = $admin_info['name'];
			if (!empty($id)) {
				$logsql = " exec pFChangeMemberLog 1,'$opt','$memo','$id',null;  ";
				$conn->exec($logsql);
			}

			echo json_encode(array('success' => true, 'msg' => '注销成功!','sql'=>$logsql));
		} catch (Exception $e) {
			echo json_encode(array('success' => false, 'msg' => '异常!' . $e->getMessage()));
		}
		exit;
	}
	/**
	 * 未使用
	 * @param string $flag
	 */
	private function  memberlistnew($flag = true){
		$conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
		$page = new Page();
		$page->setEachNum(10);
		$page->setNowPage($_REQUEST["curpage"]);
		$startnum = $page->getEachNum() * ($page->getNowPage() - 1);
		$endnum = $page->getEachNum() * ($page->getNowPage());
		$orderbys = array(
				array('txt'=>'预存余额','col'=> ' a.available_predeposit '),
				array('txt'=>'赠送余额','col'=> ' a.fConsumeBalance '),
				array('txt'=>'消费积分','col'=> ' a.member_points '),
				array('txt'=>'消费次数','col'=> ' consumenum ')
		);
		Tpl::output('orderbys',$orderbys);
		$where = 'where 1=1 ';
// 		$model_member = Model ( 'member' );
		if(isset($_GET['containunreg']) and $_GET['containunreg'] != ''){
	
		}else{
// 			$condition ['containunreg']  = array('exp' , ' iMemberState <>99 ');
			$where = $where .' and a.iMemberState <>99 ';
		}
		/**
		 * 检索条件
		 */
		if ($_GET['orgids']) {
// 			$condition ['CreateOrgID'] = array (
// 					'in',
// 					$_GET['orgids']
// 			);
			$where = $where .' and a.CreateOrgID in ('.implode(',', $_GET['orgids']).')';
		}
	
		if (isset($_GET['cardtype']) and $_GET['cardtype'] != '') {
			$where = $where .' and a.cardtype=\''.$_GET['cardtype'].'\'';
// 			$condition ['cardtype'] = $_GET['cardtype'];
		}
	
		if (isset($_GET['cardgrade']) and $_GET['cardgrade'] != '') {
// 			$condition ['cardgrade'] = $_GET['cardgrade'];
			$where = $where .' and a.cardgrade=\''.$_GET['cardgrade'].'\'';
		}
	
		if (isset($_GET['idnumber']) and $_GET['idnumber'] != '') {
// 			$condition ['sIDCard'] = $_GET['idnumber'];
			$where = $where .' and a.sIDCard=\''.$_GET['idnumber'].'\'';
		}
		if (isset($_GET['tel']) and $_GET['tel'] != '') {
// 			$condition ['sLinkPhone'] = $_GET['tel'];
			$where = $where .' and a.sLinkPhone=\''.$_GET['tel'].'\'';
		}
		if (isset($_GET['name']) and $_GET['name'] != '') {
			$where = $where .' and a.member_truename like \'%'.$_GET['tel'].'%\'';
// 			$condition ['member_truename'] = array('like','%'.$_GET['name'].'%');
		}
		if (isset($_GET['birthday']) and $_GET['birthday'] != '') {
			$where = $where .' and a.member_birthday=\''.$_GET['birthday'].'\'';
// 			$condition ['member_birthday'] = $_GET['birthday'];
		}
	
		if (isset($_GET['createcard_begin']) and $_GET['createcard_begin'] != '') {
			$where = $where .' and a.createcard_begin >=\''.$_GET['createcard_begin'].'\'';
// 			$condition ['createcard_begin'] = array('exp' , ' dCreateDate >= \''.$_GET['createcard_begin'].'\'');
		}
		if (isset($_GET['createcard_end']) and $_GET['createcard_end'] != '') {
			$where = $where .' and a.createcard_end <=dateadd(day,1,\''.$_GET['createcard_end'].'\')';
// 			$condition ['createcard_end'] = array('exp' , ' dCreateDate < dateadd(day,1,\''.$_GET['createcard_end'].'\')');
		}
	
		if (isset($_GET['hasfile']) and $_GET['hasfile'] != '') {
			if($_GET['hasfile']=='-1'){
				$where = $where .' and (hasfile = -1 or hasfile is null) ';
// 				$condition ['hasfile']  = array('exp' , ' ( hasfile = -1 or hasfile is null ) ');
			}else{
				$where = $where .' and a.hasfile='.$_GET['hasfile'];
// 				$condition ['hasfile'] = $_GET['hasfile'];
			}
		}
		
		if (isset($_GET['membersex']) and $_GET['membersex'] != '') {
			$where = $where.' and a.member_sex =\''.$_GET['membersex'].'\'';
// 			$condition ['member_sex'] = $_GET['membersex'];
		}
		
		if (isset($_GET['memberbirthday']) and $_GET['memberbirthday'] != '') {
			$where = $where.' and MONTH(a.member_birthday) ='.$_GET['memberbirthday'];
// 			$condition ['member_birthday'] = array('exp' , ' MONTH(member_birthday) = '.$_GET['memberbirthday']);
		}
		
		if (isset($_GET['memberage']) and $_GET['memberage'] != '') {
			$memberage = $_GET['memberage'];
			if ($memberage == '0'){
				$where = $where.' and a.iAge >= 65';
// 				$condition ['iAge'] = array('exp' , ' iAge >= 65 ');
			}else if($memberage == '1'){
				$where = $where.' and a.iAge <=6 ';
// 				$condition ['iAge'] = array('exp' , ' iAge <= 6');
			}else if($memberage == '99'){
				$definememberagestart = 0;
				$definememberageend = 100;
				if (isset($_GET['definememberagestart']) and $_GET['definememberagestart'] != ''){
					if(is_numberic($_GET['definememberagestart'])){
						$definememberage = $_GET['definememberagestart'];
					}
				}
				if (isset($_GET['definememberageend']) and $_GET['definememberageend'] != ''){
					if(is_numberic($_GET['definememberageend'])){
						$definememberageend = $_GET['definememberageend'];
					}
				}
				$where = $where.' and a.iAge >='.$definememberage.' and a.iAge <='.$definememberageend;
// 				$condition ['iAge'] = array('exp' , ' iAge >=' . $definememberage .' and iAge <= ' . $definememberageend);
			}
		}
		if (isset($_GET['jifen']) and $_GET['jifen'] != '') {
			$jifen = $_GET['jifen'];
			if ($jifen == '0'){
				$where = $where.' and a.member_points =0 ';
// 				$condition ['member_points'] = array('exp' , ' member_points = 0 ');
			}else if($jifen == '1'){
				$where = $where.' and a.member_points <=1000 ';
// 				$condition ['member_points'] = array('exp' , ' member_points <= 1000');
			}else if($jifen == '2'){
				$where = $where.' and a.member_points >1000 ';
// 				$condition ['member_points'] = array('exp' , ' member_points > 1000');
			}else if($jifen == '3'){
				$where = $where.' and a.member_points >3000 ';
// 				$condition ['member_points'] = array('exp' , ' member_points > 3000');
			}else if($jifen == '4'){
				$where = $where.' and a.member_points >10000 ';
// 				$condition ['member_points'] = array('exp' , ' member_points > 10000');
			}else if($jifen == '99'){
				$definejifen = 0;
				if (isset($_GET['definejifen']) and $_GET['definejifen'] != ''){
					if(is_numberic($_GET['definejifen'])){
						$definejifen = $_GET['definejifen'];
					}
				}
				$where = $where.' and a.member_points ='.$definejifen;
// 				$condition ['member_points'] = array('exp' , ' member_points =' . $definejifen);
			}
				
		}
	
		if(!isset($_GET['orderby'])){
			$_GET['orderby'] = '预存余额';
		}
	
	
	
		if(!isset($_GET['order'])){
			$ordersql = 'desc';
		}else{
			$ordersql = $_GET['order'];
		}
		$order = ' order by ';
		if($_GET['orderby']){
			foreach($orderbys as $orderby){
				if($orderby['txt']==$_GET['orderby']){
					$order = $order.$orderby['col'] .' ' . $ordersql;
					break;
				}
			}
		}
		//		if ($_GET ['search_field_value'] != '') {
		//			switch ($_GET ['search_field_name']) {
		//				case 'member_name' :
		//					$condition ['member_name'] = array (
		//							'like',
		//							'%' . trim ( $_GET ['search_field_value'] ) . '%'
		//					);
		//					break;
		//				case 'member_email' :
		//					$condition ['member_email'] = array (
		//							'like',
		//							'%' . trim ( $_GET ['search_field_value'] ) . '%'
		//					);
		//					break;
		//				case 'member_truename' :
		//					$condition ['member_truename'] = array (
		//							'like',
		//							'%' . trim ( $_GET ['search_field_value'] ) . '%'
		//					);
		//					break;
		//			}
		//		}
		if ($_GET ['member_id'] != '') {
// 			$condition ['member_id'] = array (
// 					'like',
// 					'%' . trim ( $_GET ['member_id'] ) . '%'
// 			);
			$where = $where .' and a.member_id like \'%'.trim ($_GET['member_id']).'%\'';
		}
		switch ($_GET ['search_state']) {
			case 'no_informallow' :
				$where = $where .' and a.inform_allow=\'2\'';
// 				$condition ['inform_allow'] = '2';
				break;
			case 'no_isbuy' :
// 				$condition ['is_buy'] = '0';
				$where = $where .' and a.is_buy=\'0\'';
				break;
			case 'no_isallowtalk' :
// 				$condition ['is_allowtalk'] = '0';
				$where = $where .' and a.is_allowtalk=\'0\'';
				break;
			case 'no_memberstate' :
// 				$condition ['member_state'] = '0';
				$where = $where .' and a.no_memberstate=\'0\'';
				break;
		}
		/**
		 * 排序
		 */
		//		$order = trim ( $_GET ['search_sort'] );
		if (empty ( $order )) {
			$order = 'member_id desc';
		}
		$groupby = ' group by a.member_id,a.available_predeposit,a.fConsumeBalance,a.member_points ';
		$sqlall = ' a.member_id,a.available_predeposit,a.fConsumeBalance,a.member_points,count(b.iCO_ID) as consumenum from shopnc_member a LEFT JOIN Center_CheckOut b on a.member_id = b.sMemberID and b.fCO_IncomeMoney >0 '.$where.$groupby.$order;
		$countsql = 'select count(*) from shopnc_member a '.$where;
		$stmt = $conn->query($countsql);
		$total = $stmt->fetch(PDO::FETCH_NUM);
		$page->setTotalNum($total[0]);
		$pagesql = 'select * from (select top '.$endnum.' row_number() over( order by  a.member_id)rownum, '.$sqlall.')zzzz where rownum>'.$startnum;
		$stmt = $conn->query($pagesql);
		
		$member_ids = array();
		$consume_list = array();
		while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
			array_push($member_ids, $row->member_id);
			array_push($consume_list, $row);
		}
// 		$consume_list=object_array($consume_list);
// 		$consume_list = array_under_reset($consume_list,'member_id');
		$return_list = array();
		if($member_ids){
			$sql = 'select * from shopnc_member where member_id in (\'' . implode('\',\'',$member_ids) . '\') ';
			$stmt = $conn->query($sql);
			$data_list = array();
			while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
				array_push($data_list, $row);
			}
			$data_list=object_array($data_list);
			$data_list = array_under_reset($data_list,'member_id');
			foreach ($consume_list as $v){
				$row = mergeObject($data_list[$v->member_id],$v);
				array_push($return_list, $row);
// 				var_dump($row);
			}
			$return_list=object_array($return_list);
// 			$return_list = $consume_list+$data_list;
// 			foreach ($data_list as $key){
// 				$key['consumenum'] = $consume_list[$key['member_id']]['consumenum'];
// 				array_push($return_list, $key);
// 			}
		}
		return array('list'=>$return_list,'md'=>$page);
	}
	
	/*
	 * 递归获取行政区划树，不使用
	 */
	private function getTreeData($pid,$result =array()){
		//var_dump($pid);
		if($pid=='' || $pid ==null){
			$pid= $_GET['pid'];
		}
		//var_dump($pid);
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
		$treesql = 'select  ID as id, Name as name, ParentID as pId from District where 1=1 and ParentID =\''.$pid.'\'';
        $treestmt = $conn->query($treesql);
        $treedata_list = array();

        while ($row = $treestmt->fetch(PDO::FETCH_OBJ)) {
            array_push($treedata_list, $row);
        }
        $result = array_merge($result,$treedata_list);
        if(!empty($treedata_list)){
        	foreach ($treedata_list as $v){
        		$this->getTreeData($v->id,$result);
        	}
        }
        return $result;
        
    }
    public function ajax_savehealthfileOp()
    {
    	//spotcheck_spot
    	try {
    		$conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
    		//校验身份证号
    		$IDNumber = $_GET['IDNumber'];
    		
    		$Name = $_GET['Name'];
    		$Address = $_GET['Address'];
    		$ResidenceAddress = $_GET['ResidenceAddress'];
    		$TEL = $_GET['TEL'];
    		$Village = $_GET['Village'];
    		$districtid = $_GET['districtid'];
    		$FileNo = $this->getfileno($districtid);
    		$FileNoSub = substr($FileNo, 10);
    		$pid = $_GET['pid'];
    		$sql ="select * from District where ID ='$pid' ";
    		$stmt = $conn->query($sql); 
    		$data_list = array();
    		while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
    			array_push($data_list, $row);
    		}
    		if(!empty($data_list)){
    			$Township = $data_list[0]->Name;
    		}
    		$user_id = $this->admin_info['id'];
    		$user_name = $this->admin_info['name'];
    		$Doctor = $_GET['Doctor'];
    		$PaperFileNo =  $_GET['PaperFileNo'];
    		$Nation =  $_GET['Nation'];
    		
    		$Sex = $_GET['Sex'];
    		$Birthday = $_GET['Birthday'];
    		
    		$WorkUnit = $_GET['WorkUnit'];
    		$Folk = $_GET['Folk'];
    		$ResideType = $_GET['ResideType'];
    		$farmStatus = $_GET['farmStatus'];
    		$TownStatus = $_GET['TownStatus'];
    		$sql = "insert into HealthFile(FileNo,Name,Address,ResidenceAddress,TEL,Township,Village,BuildUnit,BuildPerson,Doctor,BuildDate,DistrictNumber,InputPersonID,InputDate,PaperFileNo,Nation) 
    				values('$FileNo','$Name','$Address','$ResidenceAddress','$TEL','$Township','$Village','系统后端','$user_name','$Doctor',getdate(),'$districtid','$user_id',getdate(),'$PaperFileNo','$Nation')";
    		$conn->exec($sql);
    		$sql = "insert into PersonalInfo(ID,FileNo,FileNoSub,Sex,Birthday,IDNumber,WorkUnit,TEL,Folk,ResideType,InputPersonID,InputDate,farmStatus,TownStatus)
    		values(NEWID(),'$FileNo','$FileNoSub','$Sex','$Birthday','$IDNumber','$WorkUnit','$TEL','$Folk','$ResideType','$user_id',getdate(),'$farmStatus','$TownStatus')";
    		$conn->exec($sql);
//     		$spotid = $_REQUEST['spotid'];
//     		$spotdate = $_REQUEST['spotdate'];
//     		$result = $_REQUEST['spotresult'] == null ?"":$_REQUEST['spotresult'];
//     		$reason = $_REQUEST['reason'] == null ?"":$_REQUEST['reason'];
//     		$sql = " insert into spotcheck_spot (spotid,spotdate,result,reason,inputdate) values('$spotid','$spotdate','$result','$reason',getdate())";
//     		$conn->exec($sql);
    		echo json_encode(array('success' => true, 'msg' => '保存成功!'));
    	} catch (Exception $e) {
    		echo json_encode(array('success' => false, 'msg' => '异常!'.$e->getMessage()));
    	}
    	exit;
    }
    
    public function getfileno($districtnumber)
    {
    	try {
    		$conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
    		$sql = 'select * from HealthFileKey where DistrictNumber=\''.$districtnumber.'\'';
    		$stmt = $conn->query($sql);
    		$key_list = array();
    		$key = 1;
    		while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
    			array_push($key_list, $row);
    		}
    		if(!empty($key_list)){
    			$fialkey = $key_list[0]->MaxKey;
    			$filekey = $fialkey +1;
    			$sql ='update HealthFileKey set MaxKey ='.$filekey.' where DistrictNumber=\''.$districtnumber.'\'';
    			$conn->exec($sql);
    		}else{
    			$sql = "insert into HealthFileKey values('$districtnumber',1)";
    			$conn->exec($sql);
    			$filekey = 1;
    		}
    		//int spaces = 5 - strKey.length();
    		$length = 5- strlen($filekey);
    		if ($length < 0) {
    			$msg = "流水号超长！！！！！";
    			echo json_encode(array('success' => false, 'msg' => '异常!'.$e->$msg));
    		}
    		
    		for($i = 0; $i < $length; $i++) {
    			$filekey = "0".$filekey;
    		}
    		return $districtnumber.$filekey;
    	} catch (Exception $e) {
    		echo json_encode(array('success' => false, 'msg' => '异常!'.$e->getMessage()));
    	}
    	exit;
    }
    public function ajax_bandhealthfileOp(){
    	//spotcheck_spot
    	try {
    		$conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
    		$member_id = $_REQUEST['member_id'];
    		$fileno = $_REQUEST['fileno'];
    		$model_member = Model ( 'member' );
    		$param = array();
    		$param['FileNo'] = $fileno;
    		$model_member->updateMember($param,$member_id);
    		echo json_encode(array('success' => true, 'msg' => '关联成功!'));
    	} catch (Exception $e) {
    	echo json_encode(array('success' => false, 'msg' => '异常!'.$e->getMessage()));
    	}
    	exit;
    }
    
    public function ajax_loadmemberOp(){
    	//spotcheck_spot
    	try {
    		$conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
    		$member_id = $_REQUEST['member_id'];
    		$sql = "select member_truename as Name,case when member_sex=1 then '男' else '女' end as Sex,member_birthday as Birthday,sAddress as Address,
    		sIDCard as IDNumber,sFolk as Folk 
    		from shopnc_member where 1=1 and member_id = '$member_id'";
    		$stmt = $conn->query($sql);
    		$row = $stmt->fetch(PDO::FETCH_OBJ);
    		echo json_encode(array('success' => true, 'msg' => '查询成功!' ,'data'=>$row));
    	} catch (Exception $e) {
    		echo json_encode(array('success' => false, 'msg' => '异常!'.$e->getMessage()));
    	}
    	exit;
    }
    
    public function ajax_checkidnumberOp(){
    	//spotcheck_spot
    	try {
    		$conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
    		$idnumber = $_REQUEST['idnumber'];
    		$sql ="select * from PersonalInfo where IDNumber ='$idnumber'";
    		$stmt = $conn->query($sql);
    		$row = $stmt->fetch(PDO::FETCH_OBJ);
    		if(!empty($row)){
    			echo json_encode(array('result' => true));
    		}else{
    			echo json_encode(array('result' => false));
    		}
    	} catch (Exception $e) {
    		echo json_encode(array('success' => false, 'msg' => '异常!'.$e->getMessage()));
    	}
    	exit;
    }
}
