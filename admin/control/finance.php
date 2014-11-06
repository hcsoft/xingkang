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

class financeControl extends SystemControl
{

    public function __construct()
    {
        parent::__construct();
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        $treesql = 'select  b.id , b.name,b.districtnumber,b.parentid pId from map_org_wechat a, Organization b where a.orgid = b.id ';
        $treestmt = $conn->query($treesql);
        $this->treedata_list = array();
        while ($row = $treestmt->fetch(PDO::FETCH_OBJ)) {
            array_push($this->treedata_list, $row);
        }
        Tpl::output('treelist', $this->treedata_list);
        $this->getTreeData();
        $stmt = $conn->query(' select * from Center_codes  where type=\'iCO_Type\' order by code ');
        $this->types = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($this->types, $row);
        }
//        $this->types = array(0 => '期初入库', 1 => '采购入库', 2 => '购进退回', 3 => '盘盈', 5 => '领用', 12 => '盘亏', 14 => '领用退回', 50 => '采购计划',);
        Tpl::output('types', $this->types);
        $this->goodtype = array(0 => '药品', 1 => '卫生用品', 2 => '诊疗项目', 3 => '特殊材料');
        Tpl::output('goodtype', $this->goodtype);


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

    private function getTreeData()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');

        //查询机构树的类型
        $treesql = 'select  b.id , b.name,b.districtnumber,b.parentid pId from map_org_wechat a, Organization b where a.orgid = b.id ';
        $treestmt = $conn->query($treesql);
        $treedata_list = array();

        while ($row = $treestmt->fetch(PDO::FETCH_OBJ)) {
            array_push($treedata_list, $row);
        }
        $idmap = Array();
        //处理树选择节点
        $checkednode = $_GET['checkednode'];
        $checkednodearray = array();
        if (isset($checkednode)) {
            $checkednodearray = explode(',', $checkednode);
        }
        //处理父节点
        $root = array(id => -1, name => "全部", open => true, halfCheck => false);
        if ($checkednode && isset($checkednode) && count($checkednode) > 0) {
            $root['checked'] = true;
        }
        array_push($treedata_list, (object)$root);
        for ($i = 0; $i < count($treedata_list); $i++) {
            $item = $treedata_list[$i];
            $idmap[$item->id] = $item->id;
        }
        for ($i = 0; $i < count($treedata_list); $i++) {
            $item = $treedata_list[$i];
            if ($item->id >= 0) {
                $item->id = -(1000 + $item->id);
            }
            if (!isset($idmap[$item->pid])) {
                $item->pId = -1;
            }
        }

        foreach ($treedata_list as &$v) {
            if (in_array($v->id, $checkednodearray)) {
                $v->checked = true;
            }
        }

        Tpl::output('treedata', $treedata_list);
    }

    public function financesumOp()
    {
        $conn = require(BASE_DATA_PATH . '/../core/framework/db/mssqlpdo.php');
        if (!isset($_GET['search_type'])) {
            $_GET['search_type'] = '0';
        }
        $sqlarray = array('Section' => 'a.StatSection as "Section"',
            'execSection' => ' ',
            'Doctor' => ' a.DoctorName as "Doctor" ',
            'year' => ' year(a.dSale_MakeDate) as "year" ',
            'month' => ' month(a.dSale_MakeDate) as  "month" ',
            'day' => ' day(a.dSale_MakeDate) as "day" ',
            'OrgID' => ' org.name as "OrgID" ' ,
            'dSale_MakeDate' =>' replace( CONVERT( CHAR(10), a.dSale_MakeDate, 102), \'.\', \'-\') as "dSale_MakeDate" ',
            'dSale_GatherDate' =>' replace( CONVERT( CHAR(10), a.dSale_GatherDate , 102), \'.\', \'-\') as "dSale_GatherDate" ',
        );
        $config = array('sumcol' => array('OrgID' => array(name => 'OrgID', 'text' => '机构'),
            'Section' => array(name => 'Section', 'text' => '统计科室'),
//            'execSection' => array(name => 'execSection', 'text' => '执行科室'),
            'Doctor' => array(name => 'Doctor', 'text' => '医生'),
            'dSale_MakeDate' => array('text' => '制单日期', name=>'dSale_MakeDate' ),
            'dSale_GatherDate' => array('text' => '结算日期', name=>'dSale_GatherDate'),
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
        $sql = 'from Center_ClinicSale a  , shopnc_goods_common good , Center_Class class , Organization org
                where  a.iDrug_ID = good.goods_commonid and good.iDrug_StatClass = class.iClass_ID and  a.orgid = org.id ';

        if ($_GET['query_start_time']) {
            $sql = $sql . ' and a.dSale_MakeDate >=\'' . $_GET['query_start_time'] . '\'';
        }

        if ($_GET['query_end_time']) {
            $sql = $sql . ' and a.dSale_MakeDate < dateadd(day,1,\'' . $_GET['query_end_time'] . '\')';
        }

        if ($_GET['gather_start_time']) {
            $sql = $sql . ' and a.dSale_GatherDate >=\'' . $_GET['gather_start_time'] . '\'';
        }

        if ($_GET['gather_end_time']) {
            $sql = $sql . ' and a.dSale_GatherDate < dateadd(day,1,\'' . $_GET['gather_end_time'] . '\')';
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
        array_push($displaytext, '收入');
        array_push($displaytext, '成本金额');
        array_push($displaytext, '毛利');
        array_push($displaytext, '毛利率');
//        var_dump($totalcol);
        $totalcol[0] = '\'总计：\' as ' . explode(' as ', $totalcol[0])[1];
//        var_dump($totalcol);
        $totalcolstr = join(',', $totalcol);
        $sumcolstr = join(',', $sumcol);
        $groupbycolstr = join(',', $groupbycol);
//        echo $sumcolstr;
        $tsql = " select $sumcolstr ,
                    sum(fSale_TaxFactMoney) taxmoney ,
                    sum(fSale_NoTaxMoney) notaxmoney ,
                    sum(fSale_TaxFactMoney) -sum(fSale_NoTaxMoney)  grossprofit,
                    case when sum(fSale_TaxFactMoney) =0 then 0 else (sum(fSale_TaxFactMoney) -sum(fSale_NoTaxMoney))/sum(fSale_TaxFactMoney) end  grossprofitrate
                        $sql group by $groupbycolstr order by $groupbycolstr ";
//        echo $tsql;
        $totalsql = " select $totalcolstr , count(1) cliniccount
                        $sql ";
        if(isset($_GET['export']) && $_GET['export']=='true'){
            $this->exportxlsx(array(0=>$tsql,1=>$totalsql),$displaytext,'收入统计');
        }

//        echo $tsql;
        $stmt = $conn->query($tsql);
        $data_list = array();
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($data_list, $row);
        }
        //处理合计

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
        Tpl::showpage('finance.sum');
    }
}
