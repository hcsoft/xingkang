<?php
/**
 * 售后分析
 *
 * @copyright  Copyright (c) 2014-2020 SZGR Inc. (http://www.szgr.com.cn)
 * @license    http://www.szgr.com.cn
 * @link       http://www.szgr.com.cn
 * @since      File available since Release v1.1
 */
defined('InShopNC') or exit('Access Invalid!');

class stat_aftersaleControl extends SystemControl{
    private $links = array(
        array('url'=>'act=stat_aftersale&op=refund','lang'=>'stat_refund'),
        array('url'=>'act=stat_aftersale&op=evalstore','lang'=>'stat_evalstore'),
    );
    public function __construct(){
        parent::__construct();
        Language::read('stat');
        import('function.statistics');
        import('function.datehelper');
        
        $model = Model('stat');
        //存储参数
		$this->search_arr = $_REQUEST;
		//处理搜索时间
		if (in_array($this->search_arr['op'],array('refund'))){
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
	 * 促销分析
	 */
	public function refundOp(){
	    $where = array();
		if(!$this->search_arr['search_type']){
			$this->search_arr['search_type'] = 'day';
		}
		$model = Model('stat');
		
		//获得搜索的开始时间和结束时间
		$searchtime_arr = $model->getStarttimeAndEndtime($this->search_arr);
		
		$field = ' SUM(refund_amount) as amount ';
		if($this->search_arr['search_type'] == 'day'){
			//构造横轴数据
			for($i=0; $i<24; $i++){
				$stat_arr['xAxis']['categories'][] = "$i";
				$statlist[$i] = 0;
			}
            $group = 'HOUR(FROM_UNIXTIME(add_time))';
			$field .= ' ,HOUR(FROM_UNIXTIME(add_time)) as timeval ';
		}
	    if($this->search_arr['search_type'] == 'week'){
			//构造横轴数据
	        for($i=1; $i<=7; $i++){
	            $tmp_weekarr = getSystemWeekArr();
				//横轴
				$stat_arr['xAxis']['categories'][] = $tmp_weekarr[$i];
				unset($tmp_weekarr);
				$statlist[$i] = 0;
			}
            $group = 'WEEKDAY(FROM_UNIXTIME(add_time))+1';
			$field .= ' ,WEEKDAY(FROM_UNIXTIME(add_time))+1 as timeval ';
		}
	    if($this->search_arr['search_type'] == 'month'){
			//计算横轴的最大量（由于每个月的天数不同）
			$dayofmonth = date('t',$searchtime_arr[0]);
		    //构造横轴数据
			for($i=1; $i<=$dayofmonth; $i++){
				//横轴
				$stat_arr['xAxis']['categories'][] = $i;
				$statlist[$i] = 0;
			}
            $group = 'day(FROM_UNIXTIME(add_time))';
			$field .= ' ,day(FROM_UNIXTIME(add_time)) as timeval ';
		}
		$where = array();
		$where['add_time'] = array('between',$searchtime_arr);		
		$statlist_tmp = $model->statByRefundreturn($where, $field, 0, 0, 'timeval asc', $group);
		if ($statlist_tmp){
    	    foreach((array)$statlist_tmp as $k=>$v){
    	        $statlist[$v['timeval']] = floatval($v['amount']);
    		}
		}
		//得到统计图数据
		$stat_arr['legend']['enabled'] = false;
		$stat_arr['series'][0]['name'] = '退款金额';
		$stat_arr['series'][0]['data'] = array_values($statlist);
		$stat_arr['title'] = '退款金额统计';
        $stat_arr['yAxis'] = '金额';
		$stat_json = getStatData_LineLabels($stat_arr);
		Tpl::output('stat_json',$stat_json);
		Tpl::output('searchtime',implode('|',$searchtime_arr));
    	Tpl::output('top_link',$this->sublink($this->links, 'refund'));
    	Tpl::showpage('stat.aftersale.refund');
	}
	/**
	 * 促销分析
	 */
	public function refundlistOp(){
	    $model = Model('refund_return');
	    $refundstate_arr = $model->getRefundStateArray();
		$where = array();
		$searchtime_arr = explode('|',$this->search_arr['t']);
		$where['add_time'] = array('between',$searchtime_arr);
		if ($this->search_arr['exporttype'] == 'excel'){
		    $refundlist_tmp = $model->getRefundReturnList($where, 0, $fields);
		} else {
		    $refundlist_tmp = $model->getRefundReturnList($where, 10, $fields);
		}
		$statheader = array();
        $statheader[] = array('text'=>'订单编号','key'=>'order_sn');
        $statheader[] = array('text'=>'退款编号','key'=>'refund_sn');
        $statheader[] = array('text'=>'店铺名','key'=>'store_name','class'=>'alignleft');
        $statheader[] = array('text'=>'商品名称','key'=>'goods_name','class'=>'alignleft');
        $statheader[] = array('text'=>'买家会员名','key'=>'buyer_name');
        $statheader[] = array('text'=>'申请时间','key'=>'add_time');
        $statheader[] = array('text'=>'退款金额','key'=>'refund_amount');
        $statheader[] = array('text'=>'卖家审核','key'=>'seller_state');
        $statheader[] = array('text'=>'平台确认','key'=>'refund_state');
        foreach ((array)$refundlist_tmp as $k=>$v){
            $tmp = $v;
            foreach ((array)$statheader as $h_k=>$h_v){
                $tmp[$h_v['key']] = $v[$h_v['key']];
                if ($h_v['key'] == 'add_time'){
                    $tmp[$h_v['key']] = @date('Y-m-d',$v['add_time']);
                }
                if ($h_v['key'] == 'refund_state'){
                    $tmp[$h_v['key']] = $v['seller_state']==2 ? $refundstate_arr['admin'][$v['refund_state']]:'无';
                }
                if ($h_v['key'] == 'seller_state'){
                    $tmp[$h_v['key']] = $refundstate_arr['seller'][$v['seller_state']];
                }
                if ($h_v['key'] == 'goods_name'){
                    $tmp[$h_v['key']] = '<a href="'.urlShop('goods', 'index', array('goods_id' => $v['goods_id'])).'" target="_blank">'.$v['goods_name'].'</a>';
                }
            }
            $statlist[] = $tmp;
        }
	    if ($this->search_arr['exporttype'] == 'excel'){
            //导出Excel
			import('libraries.excel');
		    $excel_obj = new Excel();
		    $excel_data = array();
		    //设置样式
		    $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
			//header
			foreach ((array)$statheader as $k=>$v){
			    $excel_data[0][] = array('styleid'=>'s_title','data'=>$v['text']);			    
			}
			//data
			foreach ((array)$statlist as $k=>$v){
    			foreach ((array)$statheader as $h_k=>$h_v){
    			    $excel_data[$k+1][] = array('data'=>$v[$h_v['key']]);			    
    			}
			}
			$excel_data = $excel_obj->charset($excel_data,CHARSET);
			$excel_obj->addArray($excel_data);
		    $excel_obj->addWorksheet($excel_obj->charset('退款记录',CHARSET));
		    $excel_obj->generateXML($excel_obj->charset('退款记录',CHARSET).date('Y-m-d-H',time()));
			exit();
        } else {
            Tpl::output('statheader',$statheader);
    		Tpl::output('statlist',$statlist);
    		Tpl::output('show_page',$model->showpage(2));
    		Tpl::output('searchtime',$_GET['t']);
    		Tpl::output('orderby',$orderby);
    		Tpl::output('actionurl',"index.php?act={$this->search_arr['act']}&op={$this->search_arr['op']}&t={$this->search_arr['t']}");
        	Tpl::showpage('stat.listandorder','null_layout');
        }
	}
	/**
	 * 店铺动态评分统计
	 */
	public function evalstoreOp(){
	    //店铺分类
		$model_store_class = Model('store_class');
		$parent_list = $model_store_class->getTreeClassList(2);
		if (is_array($parent_list)){
			foreach ($parent_list as $k => $v){
				$parent_list[$k]['sc_name'] = str_repeat("&nbsp;",$v['deep']*2).$v['sc_name'];
			}
		}
		Tpl::output('class_list',$parent_list);
		$model = Model('stat');
		$where = array();
		if($this->search_arr['store_class']){
		    $sonclass_list = $model_store_class->getChildClass($this->search_arr['store_class']);
		    $search_classid = array();
		    foreach((array)$sonclass_list as $k=>$v){
		        $search_classid[] = $v['sc_id'];
		    }
		    $where['sc_id'] = array('in',$search_classid);
		}
		if (trim($this->search_arr['storename'])){
		    $where['seval_storename'] = array('like',"%".trim($this->search_arr['storename'])."%");
		}
		$field = ' seval_storeid, seval_storename';
		$field .= ' ,(SUM(seval_desccredit)/COUNT(*)) as avgdesccredit';
		$field .= ' ,(SUM(seval_servicecredit)/COUNT(*)) as avgservicecredit';
		$field .= ' ,(SUM(seval_deliverycredit)/COUNT(*)) as avgdeliverycredit';		
		if (trim($this->search_arr['orderby'])){
		    $orderby = trim($this->search_arr['orderby']);
		} else {
		    $orderby = 'avgdesccredit desc';
		}
		
		//查询评论的店铺总数
		$count_arr = $model->statByStoreAndEvaluatestore($where, 'count(DISTINCT evaluate_store.seval_storeid) as countnum');
		$countnum = intval($count_arr[0]['countnum']);
	    if ($this->search_arr['exporttype'] == 'excel'){
		    $statlist_tmp = $model->statByStoreAndEvaluatestore($where, $field, 0, 0, $orderby, 'seval_storeid');
		} else {
		    $statlist_tmp = $model->statByStoreAndEvaluatestore($where, $field, array(10,$countnum), 0, $orderby, 'seval_storeid');
		}
		foreach((array)$statlist_tmp as $k=>$v){
		    $tmp = $v;
		    $tmp['avgdesccredit'] = round($v['avgdesccredit'],2);
		    $tmp['avgservicecredit'] = round($v['avgservicecredit'],2);
		    $tmp['avgdeliverycredit'] = round($v['avgdeliverycredit'],2);
		    $statlist[] = $tmp;
		}
	    //导出Excel
        if ($this->search_arr['exporttype'] == 'excel'){
            //导出Excel
			import('libraries.excel');
		    $excel_obj = new Excel();
		    $excel_data = array();
		    //设置样式
		    $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
			//header
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>'店铺名称');
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>'描述相符度');
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>'服务态度');
		    $excel_data[0][] = array('styleid'=>'s_title','data'=>'发货速度');
			//data
			foreach ((array)$statlist as $k=>$v){
				$excel_data[$k+1][] = array('data'=>$v['seval_storename']);
				$excel_data[$k+1][] = array('data'=>$v['avgdesccredit']);
				$excel_data[$k+1][] = array('data'=>$v['avgservicecredit']);
				$excel_data[$k+1][] = array('data'=>$v['avgdeliverycredit']);
			}
			$excel_data = $excel_obj->charset($excel_data,CHARSET);
			$excel_obj->addArray($excel_data);
		    $excel_obj->addWorksheet($excel_obj->charset('店铺动态评分统计',CHARSET));
		    $excel_obj->generateXML($excel_obj->charset('店铺动态评分统计',CHARSET).date('Y-m-d-H',time()));
			exit();
        }
		Tpl::output('statlist',$statlist);
		Tpl::output('show_page',$model->showpage(2));
		Tpl::output('top_link',$this->sublink($this->links, 'evalstore'));
		Tpl::showpage('stat.aftersale.evalstore');
	}
}
