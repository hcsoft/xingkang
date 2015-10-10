<?php
/**
 * 系统后台公共方法
 *
 * 包括系统后台父类
 *
 *
 * @copyright  Copyright (c) 2014-2020 SZGR Inc. (http://www.szgr.com.cn)
 * @license    http://www.szgr.com.cn
 * @link       http://www.szgr.com.cn
 * @since      File available since Release v1.1
 */
defined('InShopNC') or exit('Access Invalid!');
class SystemControl{

	/**
	 * 管理员资料 name id group
	 */
	protected $admin_info;

	/**
	 * 权限内容
	 */
	protected $permission;
	protected function __construct(){
		Language::read('common,layout');
		/**
		 * 验证用户是否登录
		 * $admin_info 管理员资料 name id
		 */
		$this->admin_info = $this->systemLogin();
		if ($this->admin_info['id'] != 1){
			// 验证权限
			$this->checkPermission();
		}
		//转码  防止GBK下用ajax调用时传汉字数据出现乱码
		if (($_GET['branch']!='' || $_GET['op']=='ajax') && strtoupper(CHARSET) == 'GBK'){
			$_GET = Language::getGBK($_GET);
		}
	}

	/**
	 * 取得当前管理员信息
	 *
	 * @param
	 * @return 数组类型的返回结果
	 */
	protected final function getAdminInfo(){
		return $this->admin_info;
	}

	/**
	 * 系统后台登录验证
	 *
	 * @param
	 * @return array 数组类型的返回结果
	 */
	protected final function systemLogin(){
		//取得cookie内容，解密，和系统匹配
		$user = unserialize(decrypt(cookie('sys_key'),MD5_KEY));
		if (!key_exists('gid',(array)$user) || !isset($user['sp']) || (empty($user['name']) || empty($user['id']))){
			@header('Location: index.php?act=login&op=login');exit;
		}else {
			$this->systemSetKey($user);
		}
		return $user;
	}

	/**
	 * 系统后台 会员登录后 将会员验证内容写入对应cookie中
	 *
	 * @param string $name 用户名
	 * @param int $id 用户ID
	 * @return bool 布尔类型的返回结果
	 */
	protected final function systemSetKey($user){
		setNcCookie('sys_key',encrypt(serialize($user),MD5_KEY),3600,'',null);
	}

	/**
	 * 验证当前管理员权限是否可以进行操作
	 *
	 * @param string $link_nav
	 * @return
	 */
	protected final function checkPermission($link_nav = null){
		if ($this->admin_info['sp'] == 1) return true;

		$act = $_GET['act']?$_GET['act']:$_POST['act'];
		$op = $_GET['op']?$_GET['op']:$_POST['op'];
		if (empty($this->permission)){
			$gadmin = Model('gadmin')->getby_gid($this->admin_info['gid']);
			$permission = decrypt($gadmin['limits'],MD5_KEY.md5($gadmin['gname']));
			$this->permission = $permission = explode('|',$permission);
		}else{
			$permission = $this->permission;
		}
		//显示隐藏小导航，成功与否都直接返回
		if (is_array($link_nav)){
			if (!in_array("{$link_nav['act']}.{$link_nav['op']}",$permission) && !in_array($link_nav['act'],$permission)){
				return false;
			}else{
				return true;
			}
		}

		//以下几项不需要验证
		$tmp = array('index','dashboard','login','common','cms_base');
		if (in_array($act,$tmp)) return true;
//		Log::record( $act, Log::SQL);
//		Log::record( json_encode( $permission), Log::SQL);
		if (in_array($act,$permission) || in_array("$act.$op",$permission)){
			return true;
		}else{
			$extlimit = array('ajax','export_step1');
			if ((substr($op,-4) == 'ajax' or in_array($op,$extlimit)) && (in_array($act,$permission) || strpos(serialize($permission),'"'.$act.'.'))){
				return true;
			}
			//带前缀的都通过
			foreach ($permission as $v) {
			    if (!empty($v) && strpos("$act.$op",$v.'_') !== false) {
					return true;break;
				}
			}
		}
//		Log::record( json_encode($permission), Log::ERR);
//		showMessage(json_encode($op),'','html','succ',0);
//		echo(json_encode($php_errormsg));
		showMessage(Language::get('nc_assign_right'),'','html','succ',0);
	}

	/**
	 * 取得后台菜单
	 *
	 * @param string $permission
	 * @return
	 */
	protected final function getNav($permission = '',&$top_nav,&$left_nav,&$map_nav){

		$act = $_GET['act']?$_GET['act']:$_POST['act'];
		$op = $_GET['op']?$_GET['op']:$_POST['op'];
		if ($this->admin_info['sp'] != 1 && empty($this->permission)){
			$gadmin = Model('gadmin')->getby_gid($this->admin_info['gid']);
			$permission = decrypt($gadmin['limits'],MD5_KEY.md5($gadmin['gname']));
			$this->permission = $permission = explode('|',$permission);
		}
		Language::read('common');
		$lang = Language::getLangContent();
		$array = require(BASE_PATH.'/include/menu.php');
		$array = $this->parseMenu($array);
		//管理地图
		$map_nav = $array['left'];
		unset($map_nav[0]);

		$model_nav = "<li><a class=\"link actived\" id=\"nav__nav_\" href=\"javascript:;\" onclick=\"openItem('_args_');\"><span> _text_</span></a></li>\n";
		$top_nav = '';

		//顶部菜单
		foreach ($array['top'] as $k=>$v) {
			$v['nav'] = $v['args'];
			$top_nav .= str_ireplace(array('_args_','_text_','_nav_'),$v,$model_nav);
		}
		$top_nav = str_ireplace("\n<li><a class=\"link actived\"","\n<li><a class=\"link\"",$top_nav);

		//左侧菜单
		$model_nav = "
          <ul id=\"sort__nav_\">
            <li>
              <dl>
                <dd>
                  <ol>
                    list_body
                  </ol>
                </dd>
              </dl>
            </li>
          </ul>\n";
		$left_nav = '';
		foreach ($array['left'] as $k=>$v) {
			$left_nav .= str_ireplace(array('_nav_'),array($v['nav']),$model_nav);
			$model_list = "<li nc_type='_pkey_'><a href=\"JavaScript:void(0);\" name=\"item__opact_\" id=\"item__opact_\" onclick=\"openItem('_args_');\">_text_</a></li>";
			$tmp_list = '';

			$current_parent = '';//当前父级key

			foreach ($v['list'] as $key=>$value) {
				$model_list_parent = '';
				$args = explode(',',$value['args']);
				if ($admin_array['admin_is_super'] != 1){
					if (!@in_array($args[1],$permission)){
						//continue;
					}
				}

				if (!empty($value['parent'])){
					if (empty($current_parent) || $current_parent != $value['parent']){
						$model_list_parent = "<li nc_type='parentli' dataparam='{$value['parent']}'><dt>{$value['parenttext']}</dt><dd style='display:block;'></dd></li>";
					}
					$current_parent = $value['parent'];
				}

				$value['op'] = $args[0];
				$value['act'] = $args[1];
				//$tmp_list .= str_ireplace(array('_args_','_text_','_op_'),$value,$model_list);
				$tmp_list .= str_ireplace(array('_args_','_text_','_opact_','_pkey_'),array($value['args'],$value['text'],$value['op'].$value['act'],$value['parent']),$model_list_parent.$model_list);
			}

			$left_nav = str_replace('list_body',$tmp_list,$left_nav);

		}
	}

	/**
	 * 过滤掉无权查看的菜单
	 *
	 * @param array $menu
	 * @return array
	 */
	private final function parseMenu($menu = array()){
		if ($this->admin_info['sp'] == 1) return $menu;
		foreach ($menu['left'] as $k=>$v) {
			foreach ($v['list'] as $xk=>$xv) {
				$tmp = explode(',',$xv['args']);
				//以下几项不需要验证
				$except = array('index','login','common');
//                $except = array();
				if (in_array($tmp[1],$except)) continue;
				if (!in_array($tmp[1],$this->permission) && !in_array($tmp[1].'.'.$tmp[0],$this->permission)){
					unset($menu['left'][$k]['list'][$xk]);
				}
			}
			if (empty($menu['left'][$k]['list'])) {
				unset($menu['top'][$k]);unset($menu['left'][$k]);
			}
		}
		return $menu;
	}

	/**
	 * 取得顶部小导航
	 *
	 * @param array $links
	 * @param 当前页 $actived
	 */
	protected final function sublink($links = array(), $actived = '', $file='index.php'){
		$linkstr = '';
		foreach ($links as $k=>$v) {
			parse_str($v['url'],$array);
			if (!$this->checkPermission($array)) continue;
			$href = ($array['op'] == $actived ? null : "href=\"{$file}?{$v['url']}\"");
			$class = ($array['op'] == $actived ? "class=\"current\"" : null);
			$lang = L($v['lang']);
			$linkstr .= sprintf('<li><a %s %s><span>%s</span></a></li>',$href,$class,$lang);
		}
		return "<ul class=\"tab-base\">{$linkstr}</ul>";
	}

	/**
	 * 记录系统日志
	 *
	 * @param $lang 日志语言包
	 * @param $state 1成功0失败null不出现成功失败提示
	 * @param $admin_name
	 * @param $admin_id
	 */
	protected final function log($lang = '', $state = 1, $admin_name = '', $admin_id = 0){
		if (!C('sys_log') || !is_string($lang)) return;
		if ($admin_name == ''){
			$admin = unserialize(decrypt(cookie('sys_key'),MD5_KEY));
			$admin_name = $admin['name'];
			$admin_id = $admin['id'];
		}
		$data = array();
		if (is_null($state)){
			$state = null;
		}else{
//			$state = $state ? L('nc_succ') : L('nc_fail');
			$state = $state ? '' : L('nc_fail');
		}
		$data['content'] 	= $lang.$state;
		$data['admin_name'] = $admin_name;
		$data['createtime'] = TIMESTAMP;
		$data['admin_id'] 	= $admin_id;
		$data['ip']			= getIp();
		$data['url']		= $_REQUEST['act'].'&'.$_REQUEST['op'];
		return Model('admin_log')->insert($data);
	}

    public final function exportdata($sql = ' select \'测试成功\' ', $titles = array('测试导出'), $sheetname ='导出'){
        require(BASE_PATH.'/include/ExcelWriterXML.php');
        $xml = new ExcelWriterXML($sheetname.'.xls');
        $xml->docAuthor('hcsoft');
        $format = $xml->addStyle('StyleHeader');
        $format->fontBold();
        $format->alignRotate(45);
        $sheet = $xml->addSheet($sheetname);
        foreach($titles as $i =>$v){
            $sheet->writeString(1,$i+1,$v);
        }
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        //查询sql
        try{
            $stmt = $conn->query($sql);
            $rowindex = 2;
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                foreach($row as $i=>$v){
                    $sheet->writeString($rowindex,$i+1,strval($v));
                }
                $rowindex = $rowindex+1;
            }
        }catch (Exception $e){
            $sheet->writeString(2,1,'导出异常!请与系统管理员联系!异常信息:'+$e->getMessage());
        }
        $xml->sendHeaders();
        $xml->writeData();
        die  ;
    }

    public final function exportxlsx($sqls = ' select \'测试成功\' ', $titles = array('测试导出'), $sheetname ='导出'
			,$codevalues = array()){
        require(BASE_PATH.'/include/PHPExcel.php');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("hcsoft");
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        $sheet->setTitle($sheetname);
        foreach($titles as $i =>$v){
            $sheet->setCellValue(chr($i+65).'1',$v);
        }
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        //查询sql
        try{
            $rowindex = 2;
            if(is_array($sqls)){
                foreach($sqls as $i=>$sql){
                    $stmt = $conn->query($sql);
                    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                        foreach($row as $i=>$v){
							$metadata = $stmt->getColumnMeta($i);
							$map = $codevalues[$metadata['name']];
							$cellstr = strval($v);
							if(isset($map)){
								$cellstr = strval($map[$v]);
							}
							$sheet->setCellValue(chr($i+65).strval($rowindex),$cellstr);
                        }
                        $rowindex = $rowindex+1;
                    }
                }
            }else{
//				echo $sqls;
                $stmt = $conn->query($sqls);
                while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                    foreach($row as $i=>$v){
						$metadata = $stmt->getColumnMeta($i);
						$map = $codevalues[$metadata['name']];
						$cellstr = strval($v);
						if(isset($map)){
							$cellstr = strval($map[$v]);
						}
//						$cellstr=json_encode($metadata);
//						$sheet->setCellValue(chr($i+65).strval($rowindex),$cellstr);
						$sheet->setCellValueExplicit(chr($i+65).strval($rowindex),$cellstr,PHPExcel_Cell_DataType::TYPE_STRING);

					}
                    $rowindex = $rowindex+1;
                }
            }
        }catch (Exception $e){
			throw $e;
//			Log::record($tsql,'SQL');
//			$sheet->setCellValue(2,1,'导出异常!请与系统管理员联系!异常信息:'+$e->getMessage());
//
//			$sheet->setCellValue(2,1,'导出异常!请与系统管理员联系!异常信息:'+$e->getMessage());
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$sheetname.'.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
	
	public final function exportxlsxwithoutsql($titles = array('测试导出'), $sheetname ='导出',$exportvalue){
        require(BASE_PATH.'/include/PHPExcel.php');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("hcsoft");
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        $sheet->setTitle($sheetname);
        foreach($titles as $i =>$v){
            $sheet->setCellValue(chr($i+65).'1',$v);
        }
        //查询sql
        try{
            $rowindex = 2;
            $serial = 1;
            foreach ($exportvalue as $k=>$value){
            	$cellindex = 1;
            	$sheet->setCellValue(chr(65).strval($rowindex),strval($serial));
            	foreach($value as $i=>$v){
					$cellstr = strval($v);
//					$sheet->setCellValue(chr($cellindex+65).strval($rowindex),$cellstr);
					$sheet->setCellValueExplicit(chr($cellindex+65).strval($rowindex),$cellstr,PHPExcel_Cell_DataType::TYPE_STRING);

					$cellindex = $cellindex + 1;
            	}
         		$rowindex = $rowindex+1;
         		$serial = $serial + 1;
            }
        }catch (Exception $e){
			throw $e;
//			Log::record($tsql,'SQL');
//			$sheet->setCellValue(2,1,'导出异常!请与系统管理员联系!异常信息:'+$e->getMessage());
//
//			$sheet->setCellValue(2,1,'导出异常!请与系统管理员联系!异常信息:'+$e->getMessage());
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$sheetname.'.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

	public final function exportxlsxbyObject($titles = array(),$propertys=array(),$propertymap = array(), $sheetname ='导出',$exportobjlist){
		require(BASE_PATH.'/include/PHPExcel.php');
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("hcsoft");
		$sheet = $objPHPExcel->setActiveSheetIndex(0);
		$sheet->setTitle($sheetname);
		foreach($titles as $i =>$v){
			$sheet->setCellValue(chr($i+65).'1',$v);
		}
		//查询sql
		try{
			$rowindex = 2;
			$serial = 1;
			foreach ($exportobjlist as $k=>$value){
				$cellindex = 1;
				$sheet->setCellValue(chr(65).strval($rowindex),strval($serial));
				foreach($propertys as $i=>$v){
					$cellstr = strval($value[$v]);
					if(! empty($propertymap[$v])){
						$cellstr = strval($propertymap[$v][$value[$v]]);
					}
					$sheet->setCellValueExplicit(chr($cellindex+65).strval($rowindex),$cellstr,PHPExcel_Cell_DataType::TYPE_STRING);
					$cellindex = $cellindex + 1;
				}
				$rowindex = $rowindex+1;
				$serial = $serial + 1;
			}
		}catch (Exception $e){
			throw $e;
//			Log::record($tsql,'SQL');
//			$sheet->setCellValue(2,1,'导出异常!请与系统管理员联系!异常信息:'+$e->getMessage());
//
//			$sheet->setCellValue(2,1,'导出异常!请与系统管理员联系!异常信息:'+$e->getMessage());
		}
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$sheetname.'.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}

    public final function exportxlsxbyArrayObject($titles = array(),$propertys=array(),$propertymap = array(), $sheetname ='导出',$exportobjlist){
        require(BASE_PATH.'/include/PHPExcel.php');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("hcsoft");
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        $sheet->setTitle($sheetname);
        foreach($titles as $i =>$v){
            $sheet->setCellValue(chr($i+65).'1',$v);
        }
        //查询sql
        try{
            $rowindex = 2;
            $serial = 1;
            foreach ($exportobjlist as $k=>$value){
                $cellindex = 1;
                $sheet->setCellValue(chr(65).strval($rowindex),strval($serial));
                foreach($propertys as $i=>$v){
                    $cellstr = strval($value->$v);
                    if(! empty($propertymap[$v])){
                        $cellstr = strval($propertymap[$v][$value->$v]);
                    }
                    $sheet->setCellValueExplicit(chr($cellindex+65).strval($rowindex),$cellstr,PHPExcel_Cell_DataType::TYPE_STRING);
                    $cellindex = $cellindex + 1;
                }
                $rowindex = $rowindex+1;
                $serial = $serial + 1;
            }
        }catch (Exception $e){
            throw $e;
//			Log::record($tsql,'SQL');
//			$sheet->setCellValue(2,1,'导出异常!请与系统管理员联系!异常信息:'+$e->getMessage());
//
//			$sheet->setCellValue(2,1,'导出异常!请与系统管理员联系!异常信息:'+$e->getMessage());
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$sheetname.'.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
	
	public function notEmpty($value){
		return (isset($value) &&  $value !='');
	}
    public final function prepareexportcsv($titles,$filename){
        $fp = fopen($filename,"a");
        foreach($titles as $i=>$v){
            $titles[$i] = mb_convert_encoding($v, 'GBK','UTF-8');
        }
        fputcsv($fp, $titles);
        return $fp;
    }
    public final function endexportcsv($filename,$csvname,$fp){
        fclose($fp);
        $fp1 = fopen($filename,"r");
        header('Content-Type: application/text');
        header('Content-Disposition: attachment;filename="'.$csvname.'.csv"');
        header('Cache-Control: max-age=0');
        fpassthru($fp1);
        fclose($fp1);
        unlink ($filename);
        exit();
    }
    public final function exportcsv($sqls = ' select \'测试成功\' ', $titles = array('测试导出'), $sheetname ='导出'
        ,$codevalues = array()){
//        $excel = new SimpleExcel('csv');
        $tmpfname = tempnam("./tmp/", '');
        $fp = fopen($tmpfname, 'w');


//        $excel->parser->loadFile('d:/test.csv');
//        $data = array();
        $row = array();
        foreach($titles as $i =>$v){
            array_push($row, Db::csv_encode($v));
        }
        fputcsv($fp, $row);
//        array_push($data,$row);
//        $excel->writer->addRow($row);
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        //查询sql
        try{
            $rowindex = 2;
            if(is_array($sqls)){
                foreach($sqls as $i=>$sql){
                    $stmt = $conn->query($sql);
                    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                        $row = array();
                        foreach($row as $i=>$v){
                            $metadata = $stmt->getColumnMeta($i);
                            $map = $codevalues[$metadata['name']];
                            $cellstr = strval($v);
                            if(isset($map)){
                                $cellstr = strval($map[$v]);
                            }
                            array_push($row, Db::csv_encode($cellstr));
//                            array_push($row, $cellstr);
                        }
//                        fputcsv($fp, $row);
                        fwrite($fp,join(',',$row)."\r\n");
//                        $excel->writer->saveFile('d:/test.csv');
                        $rowindex = $rowindex+1;
                    }
                }
            }else{
//				echo $sqls;
                $stmt = $conn->query($sqls);
                while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                    $row = array();
                    foreach($row as $i=>$v){
                        $metadata = $stmt->getColumnMeta($i);
                        $map = $codevalues[$metadata['name']];
                        $cellstr = strval($v);
                        if(isset($map)){
                            $cellstr = strval($map[$v]);
                        }
                        array_push($row, Db::csv_encode($cellstr));

                    }
                    fwrite($fp,join(',',$row)."\r\n");
//                    $excel->writer->addRow($row);
//                    $excel->writer->saveFile('d:/test.csv');
                    $rowindex = $rowindex+1;
                }
            }
        }catch (Exception $e){
            throw $e;

        }
//        $excel->writer->setData($data);
        self::endexportcsv($tmpfname,$sheetname,$fp);
//        $excel->writer->saveFile('php://output');

        exit;
    }


	public final function exportcsvbyObject($titles = array(),$propertys=array(),$propertymap = array(), $sheetname ='导出',$exportobjlist){
//		$excel = new SimpleExcel('csv');
        $tmpfname = tempnam("./tmp/", '');
        $fp = fopen($tmpfname, 'w');

		$row = array();
		foreach($titles as $i =>$v){
			array_push($row, Db::csv_encode($v));
		}
//		array_push($data,$row);
        fputcsv($fp, $row);

		//查询sql
		try{
//			$rowindex = 2;
//			$serial = 1;

			foreach ($exportobjlist as $k=>$value){
//				$cellindex = 1;
				$row = array();
				foreach($propertys as $i=>$v){
					$cellstr = strval($value[$v]);
					if(! empty($propertymap[$v])){
						$cellstr = strval($propertymap[$v][$value[$v]]);
					}
					array_push($row, Db::csv_encode($cellstr));
//					$cellindex = $cellindex + 1;
				}
//				array_push($data,$row);
                fwrite($fp,join(',',$row)."\r\n");
//				$rowindex = $rowindex+1;
//				$serial = $serial + 1;
			}
		}catch (Exception $e){
			throw $e;
		}
        self::endexportcsv($tmpfname,$sheetname,$fp);
	}


}
