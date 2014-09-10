<?php
/**
 * 营销分析
 *
 * @copyright  Copyright (c) 2014-2020 SZGR Inc. (http://www.szgr.com.cn)
 * @license    http://www.szgr.com.cn
 * @link       http://www.szgr.com.cn
 * @since      File available since Release v1.1
 */
defined('InShopNC') or exit('Access Invalid!');

class stat_marketingControl extends SystemControl{
    private $links = array(
        array('url'=>'act=stat_marketing&op=promotion','lang'=>'stat_promotion'),
        array('url'=>'act=stat_marketing&op=group','lang'=>'stat_group'),
    );
    private $search_arr;//处理后的参数
    public function __construct(){
        parent::__construct();
        Language::read('stat');
        import('function.statistics');
        import('function.datehelper');
        $model = Model('stat');
        //存储参数
		$this->search_arr = $_REQUEST;
		//处理搜索时间
		if (in_array($this->search_arr['op'],array('promotion','group'))){
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
	public function promotionOp(){
		if(!$this->search_arr['search_type']){
			$this->search_arr['search_type'] = 'day';
		}
		$model = Model('stat');
		//获得搜索的开始时间和结束时间
		$searchtime_arr = $model->getStarttimeAndEndtime($this->search_arr);
		$where = array();
		$where['add_time'] = array('between',$searchtime_arr);
		//$where['order_state'] = array(array('neq',ORDER_STATE_CANCEL),array('neq',ORDER_STATE_NEW),'and');
		$where['order_state'] = array('neq',ORDER_STATE_NEW);//去除未支付订单
		$where['refund_state'] = array('exp',"!(order_state = '".ORDER_STATE_CANCEL."' and refund_state = 0)");//没有参与退款的取消订单，不记录到统计中
		$where['payment_code'] = array('exp',"!([order].payment_code='offline' and order_state <> '".ORDER_STATE_SUCCESS."')");//货到付款订单，订单成功之后才计入统计
		$where['goods_type'] = array('in',array(2,3,4));
		//下单量
		$field = ' goods_type,count(DISTINCT [order].order_id) as ordernum,SUM(goods_num) as goodsnum,SUM(goods_pay_price) as orderamount';
		$statlist_tmp = $model->statByOrderGoods($where, $field, 0, 0, 'goods_type', 'goods_type');
		//优惠类型数组
		$goodstype_arr = array(2=>'团购',3=>'限时折扣',4=>'优惠套装');
		$statlist = array();
		$statcount = array('ordernum'=>0,'goodsnum'=>0,'orderamount'=>0.00);
		$stat_arr = array();
		$stat_json = array('ordernum'=>'','goodsnum'=>'','orderamount'=>'');
		if ($statlist_tmp){
		    foreach((array)$statlist_tmp as $k=>$v){
    		    $statcount['ordernum'] += intval($v['ordernum']);
    		    $statcount['goodsnum'] += intval($v['goodsnum']);
    		    $statcount['orderamount'] += floatval($v['orderamount']);
    		}
    	    foreach((array)$statlist_tmp as $k=>$v){
    	        $v['ordernumratio'] = round($v['ordernum']/$statcount['ordernum'],4)*100;
    	        $v['goodsnumratio'] = round($v['goodsnum']/$statcount['goodsnum'],4)*100;
    	        $v['orderamountratio'] = round($v['orderamount']/$statcount['orderamount'],4)*100;
    	        $statlist_tmp2[$v['goods_type']] = $v;
    	        $stat_arr['ordernum'][] = array('p_name'=>$goodstype_arr[$v['goods_type']],'allnum'=>$v['ordernumratio']);
    	        $stat_arr['goodsnum'][] = array('p_name'=>$goodstype_arr[$v['goods_type']],'allnum'=>$v['goodsnumratio']);
    	        $stat_arr['orderamount'][] = array('p_name'=>$goodstype_arr[$v['goods_type']],'allnum'=>$v['orderamountratio']);
    		}
    		foreach ($goodstype_arr as $k=>$v){
    		    if ($statlist_tmp2[$k]){
    		        $statlist_tmp2[$k]['goodstype_text'] = $v;
    		        $statlist[] = $statlist_tmp2[$k];    		        
    		    } else {
    		        $statlist[] = array('goodstype_text'=>$k,'goodstype_text'=>$v,'ordernum'=>0,'goodsnum'=>0,'orderamount'=>0.00);
    		    }
    		}
    		$stat_json['ordernum'] = getStatData_Pie(array('title'=>'下单量','name'=>'下单量(%)','label_show'=>false,'series'=>$stat_arr['ordernum']));
    		$stat_json['goodsnum'] = getStatData_Pie(array('title'=>'下单商品数','name'=>'下商品数(%)','label_show'=>false,'series'=>$stat_arr['goodsnum']));
    		$stat_json['orderamount'] = getStatData_Pie(array('title'=>'下单金额','name'=>'下单金额(%)','label_show'=>false,'series'=>$stat_arr['orderamount']));
		}
		Tpl::output('statcount',$statcount);
		Tpl::output('statlist',$statlist);
		Tpl::output('stat_json',$stat_json);
		Tpl::output('searchtime',implode('|',$searchtime_arr));
    	Tpl::output('top_link',$this->sublink($this->links, 'promotion'));
    	Tpl::showpage('stat.marketing.promotion');
	}
	/**
	 * 促销销售趋势分析
	 */
	public function promotiontrendOp(){
	    //优惠类型数组
		$goodstype_arr = array(2=>'团购',3=>'限时折扣',4=>'优惠套装');
		
	    $model = Model('stat');
		$where = array();
		$searchtime_arr = explode('|',$_GET['t']);
		$where['add_time'] = array('between',$searchtime_arr);
		//$where['order_state'] = array(array('neq',ORDER_STATE_CANCEL),array('neq',ORDER_STATE_NEW),'and');
		$where['order_state'] = array('neq',ORDER_STATE_NEW);//去除未支付订单
		$where['refund_state'] = array('exp',"!(order_state = '".ORDER_STATE_CANCEL."' and refund_state = 0)");//没有参与退款的取消订单，不记录到统计中
		$where['payment_code'] = array('exp',"!([order].payment_code='offline' and order_state <> '".ORDER_STATE_SUCCESS."')");//货到付款订单，订单成功之后才计入统计
		$where['goods_type'] = array('in',array(2,3,4));
		$field = ' goods_type';
		switch ($this->search_arr['stattype']){
		    case 'orderamount':
		        $field .= " ,SUM(goods_pay_price) as orderamount";
		        $caption = '下单金额';
		        break;
		    case 'goodsnum':
		        $field .= " ,SUM(goods_num) as goodsnum";
		        $caption = '下单商品数';
		        break;
		    default:
		        $field .= " ,count(DISTINCT [order].order_id) as ordernum";
		        $caption = '下单量';
		        break;
		}
		if($this->search_arr['search_type'] == 'day'){
			//构造横轴数据
			for($i=0; $i<24; $i++){
				//横轴
				$stat_arr['xAxis']['categories'][] = "$i";
				foreach ($goodstype_arr as $k=>$v){
				    $statlist[$k][$i] = 0;
				}
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
				foreach ($goodstype_arr as $k=>$v){
				    $statlist[$k][$i] = 0;
				}
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
				foreach ($goodstype_arr as $k=>$v){
				    $statlist[$k][$i] = 0;
				}
			}
            $group = 'day(FROM_UNIXTIME(add_time))';
			$field .= ' ,day(FROM_UNIXTIME(add_time)) as timeval ';
		}
		//查询数据
		$statlist_tmp = $model->statByOrderGoods($where, $field, 0, '', 'timeval','goods_type,'.$group);
		//整理统计数组
	    if($statlist_tmp){
			foreach($statlist_tmp as $k => $v){
			    //将数据按照不同的促销方式分组
			    foreach ($goodstype_arr as $t_k=>$t_v){
			        if ($t_k == $v['goods_type']){
				        switch ($this->search_arr['stattype']){
                		    case 'orderamount':
                		        $statlist[$t_k][$v['timeval']] = round($v[$this->search_arr['stattype']],2);
                		        break;
                		    case 'goodsnum':
                		        $statlist[$t_k][$v['timeval']] = intval($v[$this->search_arr['stattype']]);
                		        break;
                		    default:
                		        $statlist[$t_k][$v['timeval']] = intval($v[$this->search_arr['stattype']]);
                		        break;
                		}
			        }
			    }
			}
		}
	    foreach ($goodstype_arr as $k=>$v){
		    $tmp = array();
		    $tmp['name'] = $v;
		    $tmp['data'] = array_values($statlist[$k]);
		    $stat_arr['series'][] = $tmp;    
		}
		//得到统计图数据
		$stat_arr['title'] = $caption.'统计';
        $stat_arr['yAxis'] = $caption;
		$stat_json = getStatData_LineLabels($stat_arr);
		Tpl::output('stat_json',$stat_json);
		Tpl::showpage('stat.linelabels','null_layout');
	}

	/**
	 * 团购统计
	 */
	public function groupOp(){
		if(!$this->search_arr['search_type']){
			$this->search_arr['search_type'] = 'day';
		}
		$model = Model('stat');
		//获得搜索的开始时间和结束时间
		$searchtime_arr = $model->getStarttimeAndEndtime($this->search_arr);
	    Tpl::output('statcount',$statcount);
		Tpl::output('statlist',$statlist);
		Tpl::output('stat_json',$stat_json);
		Tpl::output('searchtime',implode('|',$searchtime_arr));
    	Tpl::output('top_link',$this->sublink($this->links, 'group'));
    	Tpl::showpage('stat.marketing.group');
	}
	/**
	 * 团购统计
	 */
	public function grouplistOp(){
	    $model = Model('groupbuy');
		$where = array();
		$searchtime_arr = explode('|',$_GET['t']);
		$where['start_time'] = array('exp',"!(end_time < {$searchtime_arr[0]} or start_time > {$searchtime_arr[1]})");
		$where['state'] = array('in',array(10,20,30));
		$gname = trim($_GET['gname']);
		if ($gname){
		    $where['groupbuy_name'] = array('like',"%{$gname}%");
		}
		$grouplist = $model->getGroupbuyList($where,10,'start_time asc');
		Tpl::output('grouplist',$grouplist);
		Tpl::output('show_page',$model->showpage(2));
		Tpl::output('searchtime',$_GET['t']);
    	Tpl::showpage('stat.marketing.grouplist','null_layout');
	}
	/**
	 * 团购商品统计
	 */
	public function groupgoodsOp(){
	    $model = Model('stat');
		$where = array();
		$searchtime_arr = explode('|',$_GET['t']);
		$where['add_time'] = array('between',$searchtime_arr);
		$where['goods_type'] = 2;
		$goodsname = trim($_GET['goodsname']);
		if ($goodsname){
		    $where['goods_name'] = array('like',"%{$goodsname}%");
		}
		$field = " goods_id,goods_name";
		$field .= " ,SUM(order_goods.goods_num) as goodsnum";
		$field .= " ,SUM(order_goods.goods_pay_price) as goodsamount";
		$field .= " ,SUM(IF([order].order_state='".ORDER_STATE_CANCEL."',goods_num,0)) as cancelgoodsnum";
		$field .= " ,SUM(IF([order].order_state='".ORDER_STATE_CANCEL."',goods_pay_price,0)) as cancelgoodsamount";
		$field .= " ,SUM(IF([order].order_state<>'".ORDER_STATE_CANCEL."' and [order].order_state<>'".ORDER_STATE_NEW."',goods_num,0)) as finishgoodsnum";
		$field .= " ,SUM(IF([order].order_state<>'".ORDER_STATE_CANCEL."' and [order].order_state<>'".ORDER_STATE_NEW."',goods_pay_price,0)) as finishgoodsamount";
	    if (!trim($this->search_arr['orderby'])){
		    $this->search_arr['orderby'] = 'goodsnum desc';
		}
		$orderby = trim($this->search_arr['orderby']).',goods_id desc';
		//统计记录总条数
		$count_arr = $model->statByOrderGoods($where, 'count(DISTINCT goods_id) as countnum');
		$countnum = intval($count_arr[0]['countnum']);
		if ($this->search_arr['exporttype'] == 'excel'){
		    $statlist_tmp = $model->statByOrderGoods($where, $field, 0, 0, $orderby, 'goods_id');
		} else {
		    $statlist_tmp = $model->statByOrderGoods($where, $field, array(10,$countnum), 0, $orderby, 'goods_id');
		}
		$statheader = array();
        $statheader[] = array('text'=>'商品名称','key'=>'goods_name','class'=>'alignleft');
        $statheader[] = array('text'=>'下单商品数','key'=>'goodsnum','isorder'=>1);
        $statheader[] = array('text'=>'下单金额','key'=>'goodsamount','isorder'=>1);
        $statheader[] = array('text'=>'取消商品数','key'=>'cancelgoodsnum','isorder'=>1);
        $statheader[] = array('text'=>'取消金额','key'=>'cancelgoodsamount','isorder'=>1);
        $statheader[] = array('text'=>'完成商品数','key'=>'finishgoodsnum','isorder'=>1);
        $statheader[] = array('text'=>'完成金额','key'=>'finishgoodsamount','isorder'=>1);
        foreach ((array)$statlist_tmp as $k=>$v){
            $tmp = $v;
            foreach ($statheader as $h_k=>$h_v){
                $tmp[$h_v['key']] = $v[$h_v['key']];
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
			foreach ($statheader as $k=>$v){
			    $excel_data[0][] = array('styleid'=>'s_title','data'=>$v['text']);			    
			}
			//data
			foreach ($statlist as $k=>$v){
    			foreach ($statheader as $h_k=>$h_v){
    			    $excel_data[$k+1][] = array('data'=>$v[$h_v['key']]);			    
    			}
			}
			$excel_data = $excel_obj->charset($excel_data,CHARSET);
			$excel_obj->addArray($excel_data);
		    $excel_obj->addWorksheet($excel_obj->charset('团购商品统计',CHARSET));
		    $excel_obj->generateXML($excel_obj->charset('团购商品统计',CHARSET).date('Y-m-d-H',time()));
			exit();
        } else {
            Tpl::output('statheader',$statheader);
    		Tpl::output('statlist',$statlist);
    		Tpl::output('show_page',$model->showpage(2));
    		Tpl::output('searchtime',$_GET['t']);
    		Tpl::output('orderby',$this->search_arr['orderby']);
    		Tpl::output('actionurl',"index.php?act={$this->search_arr['act']}&op={$this->search_arr['op']}&t={$this->search_arr['t']}");
        	Tpl::showpage('stat.listandorder','null_layout');
        }
	}
}
