<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" media="all" rel="stylesheet">
    <link href="<?php echo RESOURCE_SITE_URL; ?>/js/timeline/styles.css" rel="stylesheet" type="text/css">
    <link href="<?php echo ADMIN_TEMPLATES_URL; ?>/showLoading/css/showLoading.css" rel="stylesheet"/>
    <script type="text/javascript" src="<?php echo ADMIN_TEMPLATES_URL; ?>/showLoading/js/jquery.showLoading.min.js"></script>	
</head>
<body style="padding-top: 0px;">
<div style="background-color: #ffffff;width:100%;position: relative;top:0;padding:20px;box-sizing: border-box;">


    <table style="width:100%;font-size:18px;">
        <tr class="hover member" style="background: rgb(255, 255, 255);">
            <td class="w24"></td>
            <td class="w48 picture">
                <div class="size-44x44"><span class="thumb size-44x44"><i></i><img
                            src="<?php echo UPLOAD_SITE_URL?>/shop/common/default_user_portrait.gif?0.14009400 1433639342"
                            onload="javascript:DrawImage(this,44,44);" width="44" height="44"></span></div>
            </td>
            <td>
                <p class="name"><!--会员名:<strong></strong>-->
                    <strong>卡号:</strong><?php echo $output['member_id']; ?></p>

                <p class="name"><!--会员名:<strong></strong>-->
                    <strong>姓名:</strong> <?php echo $output['member_truename']; ?></p>

                <p class="name"> <strong>电话:</strong><?php echo $output['sLinkPhone']; ?></p>

                <p class="name"> <strong>地址:</strong><?php echo $output['sAddress']; ?></p>


            </td>
            <td><p class="name"> <strong>身份证:</strong> <?php echo $output['sIDCard']; ?></p>

                <p class="name"> <strong>医保卡:</strong><?php echo $output['MediCardID']; ?></p>

                <p class="name"> <strong>健康档案:</strong><a href="javascript:setdata('档案')"><?php echo $output['FileNo']; ?></a></p>
            </td>

            <td><p class="name"> <strong>卡类型:</strong> <?php if ($output['CardType'] == 0) {
                                    echo '普通卡';
                                } elseif ($output['CardType'] == 1) {
                                    echo '储值卡';
                                } ?></p>
                <p class="name"> <strong>卡级别:</strong> <?php if ($output['CardGrade'] == 0) {
                                    echo '健康卡';
                                } elseif ($output['CardGrade'] == 1) {
                                    echo '健康金卡';
                                } elseif ($output['CardGrade'] == 2) {
                                    echo '健康钻卡';
                                } ?></p>
                <p class="name"> <strong>办卡渠道:</strong> <?php echo $output['GetWay']; ?></p>
                <p class="name"> <strong>推荐人:</strong><?php echo $output['Referrer']; ?></p>
            </td>
            <td class="">
                <p class="name"><strong>储值余额:</strong>&nbsp;<font class="red"><?php echo $output['available_predeposit']; ?></font>&nbsp;元</p>
                <p class="name"><strong>赠送余额:</strong> <font class="red"><?php echo number_format($output['fConsumeBalance'],2); ?></font>&nbsp;元           </p>
                <p class="name"><strong>消费积分:</strong> <font class="red"><?php echo $output['member_points']; ?></font></p>
            </td>
            <td>
                <p class="name"> <strong>末次消费日期:</strong> <?php echo $output['LastPayDate']; ?></p>
                <p class="name"> <strong>末次消费地点:</strong> <?php echo $output['LastPayOrgName']; ?></p>
                <p class="name"> <strong>充值次数:</strong> <a href="javascript:setdata('充值')">
                	<?php echo $output['ServiceIndexCount'][0]->RechargeCount; ?>次</a></p>
                <p class="name"> <strong>消费次数:</strong> <a href="javascript:setdata('消费')">
					<?php echo $output['ServiceIndexCount'][0]->ConsumeCount; ?>次</a></p>
            </td>
            <td>
                <p class="name"> <strong>门诊次数:</strong> <a href="javascript:setdata('门诊')">
                	<?php echo $output['ServiceIndexCount'][0]->OutpatientCount; ?>次</a></p>
                <p class="name"> <strong>住院次数:</strong> <a href="javascript:setdata('住院')">
                	<?php echo $output['ServiceIndexCount'][0]->InpatientCount; ?>次</a></p>
                <p class="name"> <strong>健康体检:</strong> <a href="javascript:setdata('健康体检')">
                	<?php echo $output['ServiceIndexCount'][0]->MedicalExamCount; ?>次
                </a></p>
                <p class="name"> <strong>儿童体检:</strong> <a href="javascript:setdata('儿童体检')">
					<?php echo $output['ServiceIndexCount'][0]->ChildrenCount; ?>次</a></p>
            </td>
            <td>
                <p class="name"> <strong>孕产妇体检:</strong> <a href="javascript:setdata('孕产妇体检')">
                	<?php echo $output['ServiceIndexCount'][0]->WomanCount; ?>次</a></p>
                <p class="name"> <strong>老年人体检:</strong> <a href="javascript:setdata('老年人体检')">
                	<?php echo $output['ServiceIndexCount'][0]->MedicalOldExamCount; ?>次</a></p>
                <p class="name"> <strong>高血压随访:</strong> <a href="javascript:setdata('高血压随访')">
                	<?php echo $output['ServiceIndexCount'][0]->HypertensionCount; ?>次</a></p>
                <p class="name"> <strong>糖尿病随访:</strong> <a href="javascript:setdata('糖尿病随访')">
                	<?php echo $output['ServiceIndexCount'][0]->DiabetesCount; ?>次</a></p>
            </td>
            <td>
                <p class="name"> <a href="javascript:setdata()">显示全部</a></p>
            </td>
        </tr>
    </table>
</div>
<div class="timeline animated" id="content">

</div>

<style>
.form_tbl {
	border-collapse: collapse;
	width:100%;
}

.form_tbl td {
	border-top: 1px solid #DEEFFB;
	border-left: 1px solid #DEEFFB;
	border-right: 1px solid #DEEFFB;
	height:25px;
	line-height:25px;
	padding:5px;
}
</style>

<div id="pmhsdetaildialog" title="公共卫生详细信息">
  	<div id="tabs" style="margin-top:-15px;">
	  <ul>
	    <li><a href="#key-value-list">键值列表</a></li>
	    <li id="showTitle"><a href="#pmhs-standard-display">标准公卫显示</a></li>
	  </ul>
	  <div id="key-value-list">
	  	<input placeholder="元素名称" id="locationCond"/><button onclick="locationFun()">定位</button><a href="#" style="display:none;" id="locationSearch"></a>
	    <div class="key-value-html">
	    </div>
	  </div>
	  <div id="pmhs-standard-display">
	  </div>
	</div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/i18n/zh-CN.js"
        charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.formautofill.js"></script>

<link rel="stylesheet" type="text/css"
      href="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/themes/smoothness/jquery.ui.css"/>
<?php 
	echo "<script>";
	echo "var bgs=['primary','warning','info'];";
	$data = " var data = [";
	if (!empty($output['ServiceIndexInfo']) && is_array($output['ServiceIndexInfo'])) {
		foreach ($output['ServiceIndexInfo'] as $k => $v) {
			$date1 = $v->ServiceDate;
			$tmpMonthDay = date('Y年m月d日',strtotime($date1));
			$tmpHours = date('A H:i',strtotime($date1));
			$flag = substr($tmpHours,0,2);
			if($flag == 'AM'){
				$tmpHours = '上午' . substr($tmpHours,2,strlen($tmpHours)-2);
			}else if($flag = 'PM'){
				$tmpHours = '下午' . substr($tmpHours,2,strlen($tmpHours)-2);
			}
			$data = $data . "{'id':'" . $v->Id . "','date':'" . $tmpMonthDay . "','time':'" . $tmpHours . "',bg:'" . $v->Bg . "','title':'" . $v->Title . "',type:'" . $v->Type . "',ico : '" . $v->ICO ."',typeOther:'" . $v->TypeOther . "'},";
		}
	}
	$data = substr($data,1,strlen($data)) . "]";
	echo $data;
	echo "</script>";
?>
<script>
	function locationFun(){
		var cond = $('#locationCond').val();
		$('#locationSearch').attr('href','#' + cond);
		document.getElementById('locationSearch').click();
//		
	}
	function locationFunHis(){
		var cond = $('#locationCondHis').val();
		$('#locationSearchHis').attr('href','#' + cond);
		document.getElementById('locationSearchHis').click();
//		
	}
	function linkDetailInfo(id,type,title){
		console.log(id + ':' + type);
		
		id = id.replace(/(^\s*)|(\s*$)/g, "");
		$("body").showLoading();
		$.ajax({
        	url: "index.php?act=dashboard&op=timeline&loaddetailinfo=1&businessId=" + id + "&businessType=" + type,
        	dataType: 'json',
            success: function (data) {
            	console.dir(data);
                if (data.success) {
                	if(type != '20' && type != '21' && type != '22' && type != '23'){
                		var filldata = {};
	                	if(data.data.length > 0){
	                		filldata = data.data[0];
	                	}
	                	console.log(filldata)
	                	var keyvaluehtml = data.keyvaluehtml;
	                	var displayhtml = data.standarddisplayhtml;
	                	$.each(filldata,function(key,val){
	//                		console.log(key + ':' + val);
	                		if(val == null) val = '';
	                		keyvaluehtml = keyvaluehtml.replace('${' + key + '}',val);
	                		displayhtml = displayhtml.replace('${' + key + '}',val);
	                	});
	                	$('#pmhsdetaildialog #showTitle a').html('公卫标准显示');
	                	$('#pmhsdetaildialog #showTitle').show();
	                	$('#pmhsdetaildialog #key-value-list .key-value-html').html(keyvaluehtml);
	                	$('#pmhsdetaildialog #pmhs-standard-display').html(displayhtml);
	                	$('#pmhsdetaildialog').dialog("option","title", title);
						$("#pmhsdetaildialog").dialog("open");
                	}else{
                		var filldata = {};
	                	if(data.data.length > 0){
	                		filldata = data.data[0];
	                	}
	                	var keyvaluehtml = data.keyvaluehtml;
	                	var displayhtml = data.standarddisplayhtml;
	                	$.each(filldata,function(key,val){
	//                		console.log(key + ':' + val);
	                		if(val == null) val = '';
	                		keyvaluehtml = keyvaluehtml.replace('${' + key + '}',val);
//	                		displayhtml = displayhtml.replace('${' + key + '}',val);
	                	});
	                	console.log(displayhtml);
	                	
	                	$('#pmhsdetaildialog #showTitle a').html('处方明细');
	                	var tbodyinfo = '';
	                	if(type == '21'){
	                		$('#pmhsdetaildialog #showTitle').hide();
	                	}else{
	                		$('#pmhsdetaildialog #showTitle').show();
	                		
	                		if(data.hisdata.length > 0){
	                			$.each(data.hisdata,function(i,v){
	                				tbodyinfo = tbodyinfo + '<tr>';
	                				if(i == data.hisdata.length -1){
	                					tbodyinfo = tbodyinfo + '<tr><td style="border-bottom: 1px solid #DEEFFB;">' + (i+1) + '</td>';
	                				}else{
	                					tbodyinfo = tbodyinfo + '<tr><td>' + (i+1) + '</td>';
	                				}
	                				
	                				$.each(v,function(key,val){
	                					if(val == null) val = '';
	                					if(i == data.hisdata.length -1){
	                						tbodyinfo = tbodyinfo + '<td style="border-bottom: 1px solid #DEEFFB;">' + val + '</td>';
	                					}else{
	                						tbodyinfo = tbodyinfo + '<td>' + val + '</td>';
	                					}
	                				});
	                				tbodyinfo = tbodyinfo + '</tr>';
	                			});
	                		}
	                	}
	                	
	                	$('#pmhsdetaildialog #key-value-list .key-value-html').html(keyvaluehtml);
	                	$('#pmhsdetaildialog #pmhs-standard-display').html(displayhtml);
	                	if(type != '21'){
	                		$('#pmhsdetaildialog #pmhs-standard-display table tbody').html(tbodyinfo);
	                	}
	                	$('#pmhsdetaildialog').dialog("option","title", title);
						$("#pmhsdetaildialog").dialog("open");
						$('#pmhsdetaildialog #pmhs-standard-display table tr:first-child td ').each(function(a,b,c){
							console.log(a,b,c);
							var text = $(this).text();
							if(text=='序号'){
								console.log(a+',')
							}else if(text=='项目名称'){
								console.log(a+',')
							}else if(text=='处方数量' && a==25){
								console.log(a+',')
							}else if(text=='单位'){
								console.log(a+',')
							}else if(text=='医生'){
								console.log(a+',')
							}else if(text=='科室'){
								console.log(a+',')
							}else if(text=='机构'){
								console.log(a+',')
							}else{
								$(this).hide();
							}
						});
						$('#pmhsdetaildialog #pmhs-standard-display table tr:not(:first-child) ').each(function(a,b,c){
							var tr = $(this);
							tr.children().each(function(idx,ele){
								if(idx == 0 || idx == 2  || idx == 13  || idx == 14  || idx == 18  || idx == 19  || idx == 25){

								}else{
									$(this).hide();
								}
							});
						});
                	}
                	$("body").hideLoading();
                } else {
                    //...
                    $("body").hideLoading();
                }
            }
        });
		
	}
    function setdata(type){
        var htmlstr = '';
        for(var i = 0 ;i <data.length;i++){
            if(!type || (type && data[i].type==type)){
                htmlstr += '<div class="timeline-row active">\
                <div class="timeline-time">\
                <small>'+data[i].date+'</small>'+data[i].time+'\
                </div>\
                <div class="timeline-icon">\
                <div class="bg-'+data[i].bg+'" style="height:34px;">\
                <i class="fa fa-'+data[i].ico+' ?>" style="line-height: 34px"></i>\
                </div>\
                </div>\
                <div class="panel timeline-content">\
                <div class="panel-body">\
                <a href="javascript:void();" style="color:#000;text-decoration:none;" onclick="linkDetailInfo(\'' + data[i].id + '\',\'' + data[i].typeOther + '\',\'' + data[i].title + '\')">\
                <h2>\
                '+data[i].title+'\
                </h2>\
                </a>\
                </div>\
                </div>\
                </div>';
            }
        }
        document.getElementById("content").innerHTML = htmlstr;
    }
    setdata();

	$(function () {
		$( "#tabs" ).tabs();
		$("#pmhsdetaildialog").dialog({
            resizable: false,
            height: 600,
            width: 1100,
//            height:250,
            modal: true,
            autoOpen: false,
            buttons: {
                "关闭": function () {
                    $(this).dialog("close");
                }
            }
        });
	});
</script>
</body>
</html>
