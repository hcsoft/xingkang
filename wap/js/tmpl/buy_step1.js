$(function() {
	var key = getcookie('key');
	var ifcart = GetQueryString('ifcart');
	if(ifcart==1){
		var cart_id = GetQueryString('cart_id');
		var data = {key:key,ifcart:1,cart_id:cart_id};
	}else{
		var goods_id = GetQueryString("goods_id");
		var number = GetQueryString("buynum");
		var cart_id = goods_id+'|'+number;
		var data = {key:key,cart_id:cart_id};
	}


	$.ajax({//提交订单信息
		type:'post',
		url:ApiUrl+'/index.php?act=member_buy&op=buy_step1',
		dataType:'json',
		data:data,
		success:function(result){
			var data = result.datas;
			if(typeof(data.error )!='undefined'){
				location.href = WapSiteUrl;    
			}
			
			var htmldata = '';
			var total_price = '';
			var i = 0;
			var j = 0;
			$.each(data.store_cart_list,function(k,v){//循环店铺
				if(i==0){
					htmldata+=	'<li>';
				}else{
					htmldata+=	'<li class="bd-t-cc">';
				}
				i++;
				htmldata+='<p class="buys-yt-tlt">店铺名称：'+v.store_name+'</p>';
						$.each(v.goods_list,function(k1,v1){//循环商品列表
							if(j==0){
								htmldata+=	'<div class="buys1-pdlist">';
							}else{
								htmldata+=	'<div class="buys1-pdlist bd-t-de">';
							}
							j++;
				
							htmldata+='<div class="clearfix">'
												+'<a class="img-wp" href="'+WapSiteUrl+'/tmpl/product_detail.html?goods_id='+v1.goods_id+'">'
													+'<img src="'+v1.goods_image_url+'"/>'
												+'</a>'
												+'<div class="buys1-pdlcnt">'
													+'<p><a class="buys1-pdlc-name" href="'+WapSiteUrl+'/tmpl/product_detail.html?goods_id='+v1.goods_id+'">'+v1.goods_name+'</a></p>'
													+'<p>单价(元)：￥'+v1.goods_price+'</p>'
													+'<p>数量：'+v1.goods_num+'</p>'
												+'</div>'
											+'</div>'
										+'</div>';
						});
						htmldata+= '<div class="shop-total"><p>运费：￥<span id="store'+k+'"></span></p>';
						if(v.store_mansong_rule_list != null){
							htmldata+= '<p>满级送-'+v.store_mansong_rule_list.desc+':-'+v.store_mansong_rule_list.discount+'</p>';
						}
						
						htmldata+='<p><select name="voucher" store_id="'+k+'">';
						htmldata+='<option value="0">请选择...</option>';
						$.each(v.store_voucher_list,function(k2,v2){
							htmldata+='<option value="'+v2.voucher_t_id+'|'+k+'|'+v2.voucher_price+'">'+v2.voucher_title+'</option>'
						});
						htmldata+='</select>:￥-<span id="sv'+k+'">0.00</span></p>';
						if(v.store_mansong_rule_list != null){
							var sp_total = eval(v.store_goods_total-v.store_mansong_rule_list.discount);
							htmldata+='<p class="clr-c07">本店合计：￥<span id="st'+k+'" store_price="'+sp_total+'" class="store_total">'+sp_total+'</span></p>';	
						}else{
							var sp_total = v.store_goods_total;
							htmldata+='<p class="clr-c07">本店合计：￥<span id="st'+k+'" store_price="'+sp_total+'" class="store_total">'+sp_total+'</span></p>';	
						}
						htmldata+='</div>';
						htmldata+='</li>';
						total_price = eval(parseInt(sp_total)+total_price);
			});
			
			$("#deposit").before(htmldata);//订单列表
			if(data.address_info == ''){//收获地址是否存在
                //如果是发票 就是buys1-invoice-cnt
                var thisPrarent = $(".buys1-address-cnt");
                hideDetail(thisPrarent);
				//填写收获地址
			}else{		
				$('#true_name').html(data.address_info.true_name);
				$('#address').html(data.address_info.area_info+' '+data.address_info.address);
				$('#mob_phone').html(data.address_info.mob_phone);
			}


			
			$('#total_price').html(total_price);
			$('input[name=total_price]').val(total_price);
			if(data.available_predeposit != null){//预存款
				$('.pre-deposit-wp').show();
				$('#available_predeposit').html(data.available_predeposit);
				$('input[name=available_predeposit]').val(data.available_predeposit);
			}			
			
			if(data.ifshow_offpay){//支付方式
				$('#offline').show();
			}else{
				$('#offline').hide();
			}
			
			$('#inv_content').html(data.inv_info.content);
			//$('#inv_content').html(data.inv_info.inv_title+"&nbsp;"+data.inv_info.inv_content);//发票信息
			$('input[name=address_id]').val(data.address_info.address_id);
			$('input[name=area_id]').val(data.address_info.area_id);
			$('input[name=city_id]').val(data.address_info.city_id);
			$('input[name=freight_hash]').val(data.freight_hash);
			$('input[name=vat_hash]').val(data.vat_hash);
			$('input[name=offpay_hash]').val(data.offpay_hash);
			$('input[name=invoice_id]').val(data.inv_info.inv_id);
			
			var area_id = data.address_info.area_id;
			var city_id = data.address_info.city_id;
			var freight_hash = data.freight_hash;
			
			$.ajax({//保存地址
				type:'post',
				url:ApiUrl+'/index.php?act=member_buy&op=change_address',
				data:{key:key,area_id:area_id,city_id:city_id,freight_hash:freight_hash},
				dataType:'json',
				success:function(result){
					if(result.datas.state == 'success'){
						var sp_s_total = 0;
						$.each(result.datas.content,function(k,v){
							$('#store'+k).html(v);
	        				var sp_toal = parseInt($('#st'+k).attr('store_price'));//店铺商品价格
	        				sp_s_total = v+sp_s_total;
	        				$('#st'+k).html(eval(sp_toal+v));
						});	

						var total_price = eval(parseInt($('input[name=total_price]').val())+sp_s_total);
						$('#total_price').html(total_price);						
						//$('input[name=total_price]').val(total_price);
						
						$('input[name=allow_offpay]').val(result.datas.allow_offpay);
						$('input[name=offpay_hash]').val(result.datas.offpay_hash);
					}
				}
			});
			
			$('select[name=voucher]').change(function(){//选择代金券				
				var store_id = $(this).attr('store_id');
				var varr = $(this).val();
				if(varr == 0){
					var store_price = 0;
				}else{
					var store_price = parseInt(varr.split('|')[2]);
				}
				var store_total_price = parseInt($('#st'+store_id).attr('store_price'));
				var store_tran = parseInt($('#store'+store_id).html());
				store_total = eval(store_total_price - store_price + store_tran);
				$("#sv"+store_id).html(store_price);
				$("#st"+store_id).html(store_total);
				
				var total_price = '';
				$('.store_total').each(function(){
					total_price=eval(parseInt($(this).html())+total_price);
				});
				$('#total_price').html(total_price);
			});
		}
	});
	
	$.ajax({//获取区域列表
		type:'post',
		url:ApiUrl+'/index.php?act=member_address&op=area_list',
		data:{key:key},
		dataType:'json',
		success:function(result){
			checklogin(result.login);
			var data = result.datas;
			var prov_html = '';
			for(var i=0;i<data.area_list.length;i++){
				prov_html+='<option value="'+data.area_list[i].area_id+'">'+data.area_list[i].area_name+'</option>';
			}
			$("select[name=prov]").append(prov_html);
		}
	});
	
	$.ajax({//获取发票内容
		type:'post',
		url:ApiUrl+'/index.php?act=member_invoice&op=invoice_content_list',
		data:{key:key},
		dataType:'json',
		success:function(result){
			checklogin(result.login);
			var data = result.datas;
			var html = '';
			$.each(data.invoice_content_list,function(k,v){
				html+='<option value="'+v+'">'+v+'</option>';
			});
			$('#inc_content').append(html);
		}
	});
	
	$("select[name=prov]").change(function(){//选择省市
		var prov_id = $(this).val();
		$.ajax({
			type:'post',
			url:ApiUrl+'/index.php?act=member_address&op=area_list',
			data:{key:key,area_id:prov_id},
			dataType:'json',  	
			success:function(result){
				checklogin(result.login);
				var data = result.datas;
				var city_html = '<option value="">请选择...</option>';
				for(var i=0;i<data.area_list.length;i++){
					city_html+='<option value="'+data.area_list[i].area_id+'">'+data.area_list[i].area_name+'</option>';
				}
				$("select[name=city]").html(city_html);
				$("select[name=region]").html('<option value="">请选择...</option>');
			}
		});
	});
	
	$("select[name=city]").change(function(){//选择城市
		var city_id = $(this).val();
		$.ajax({
			type:'post',
			url:ApiUrl+'/index.php?act=member_address&op=area_list',
			data:{key:key,area_id:city_id},
			dataType:'json',  	
			success:function(result){
				checklogin(result.login);
				var data = result.datas;
				var region_html = '<option value="">请选择...</option>';
				for(var i=0;i<data.area_list.length;i++){
					region_html+='<option value="'+data.area_list[i].area_id+'">'+data.area_list[i].area_name+'</option>';
				}
				$("select[name=region]").html(region_html);
			}
		});
	});	
	
	$.ajax({//获取发票列表
		type:'post',
		url:ApiUrl+'/index.php?act=member_invoice&op=invoice_list',
		data:{key:key},
		dataType:'json',
		success:function(result){
			checklogin(result.login);
			var invoice = result.datas.invoice_list;
			if(invoice.length>0){
				var html = '';
				$.each(invoice,function(k,v){
					html+= '<li>'
								+'<label>'
									+'<input type="radio" name="invoice" class="rdo inv-radio" checked="checked" value="'+v.inv_id+'"/>'
									+'<span class="mr5 rdo-span" id="inv_'+v.inv_id+'">'+v.inv_title+'&nbsp;&nbsp;'+v.inv_content+'</span>'
								+'</label>'
								+'<a class="del-invoice" href="javascript:void(0);" inv_id="'+v.inv_id+'">[删除]</a>'
							+'</li>';
				});
				
				$('#invoice_add').before(html);
				
				$('.del-invoice').click(function(){
                    var $this = $(this);
					var inv_id = $(this).attr('inv_id');
					$.ajax({
						type:'post',
						url:ApiUrl+'/index.php?act=member_invoice&op=invoice_del',
						data:{key:key,inv_id:inv_id},
						success:function(result){
							if(result){
								$this.parent('li').remove();
							}
							return false;
						}
					});
				});
			}
		}
	});
	
    $(".head-invoice").click(function (){
        $(this).parent().find(".inv-tlt-sle").prop("checked",true);
    });
    $(".buys1-edit-address").click(function(){//修改收获地址
        var self = this;
        $.ajax({
        	url:ApiUrl+"/index.php?act=member_address&op=address_list",
        	type:'post',
        	data:{key:key},
        	dataType:'json',
        	success:function(result){
        		var data = result.datas;
        		var html = '';
        		for(var i=0;i<data.address_list.length;i++){
        			html+='<li class="current">'
			                    +'<label>'
			                        +'<input type="radio" name="address" checked="checked" class="rdo address-radio" value="'+data.address_list[i].address_id+'"/>'
			                        +'<span class="mr5 rdo-span"><span class="true_name_'+data.address_list[i].address_id+'">'+data.address_list[i].true_name+'</span> <span class="address_id_'+data.address_list[i].address_id+'">'+data.address_list[i].area_info+' '+data.address_list[i].address+'</span> <span class="mob_phone_'+data.address_list[i].address_id+'">'+data.address_list[i].mob_phone+'</span></span>'
			                    +'</label>'
			                    +'<a class="del-address" href="javascript:void(0);" address_id="'+data.address_list[i].address_id+'">[删除]</a>'
                    		+'</li>';
        		}
        		$('li[class=current]').remove();
        		$('#addresslist').before(html);
        		
        		$('.del-address').click(function(){
                    var $this = $(this);
        			var address_id = $(this).attr('address_id');
        			$.ajax({
        				type:'post',
        				url:ApiUrl+'/index.php?act=member_address&op=address_del',
        				data:{key:key,address_id:address_id},
        				dataType:'json',
        				success:function(result){
        					$this.parent('li').remove();
        				}
        			});
        		});
        	}
        });
        var thisPrarent = $(this).parents(".buys1-address-cnt");
        hideDetail(thisPrarent);
    });
    $(".buys1-edit-invoice").click(function(){
        var self = this;

        var thisPrarent = $(this).parents(".buys1-invoice-cnt");
        hideDetail(thisPrarent);
    });
    
	$.sValid.init({//地址验证
        rules:{
        	vtrue_name:"required",
        	vmob_phone:"required",
            vprov:"required",
            vcity:"required",
            vregion:"required",
            vaddress:"required",
        },
        messages:{
        	vtrue_name:"姓名必填！",
        	vmob_phone:"手机号必填！",
            vprov:"省份必填！",
            vcity:"城市必填！",
            vregion:"区县必填！",
            vaddress:"街道必填！",
        },
        callback:function (eId,eMsg,eRules){
            if(eId.length >0){
                var errorHtml = "";
                $.map(eMsg,function (idx,item){
                    errorHtml += "<p>"+idx+"</p>";
                });
                $(".error-tips").html(errorHtml).show();
            }else{
                 $(".error-tips").html("").hide();
            }
        }  
    });
	
    $(".save-address").click(function (){//更换收获地址	
        var self = this;
        var selfPr
        //获取address_id
        var addressRadio = $('.address-radio');
        var address_id;
        for(var i =0;i<addressRadio.length;i++){
            if(addressRadio[i].checked){
                address_id = addressRadio[i].value;
            }
        }
    	
        if(address_id>0){//变更地址
        	var area_id = $("input[name=area_id]").val();
        	var city_id = $("input[name=city_id]").val();
        	var freight_hash = $("input[name=freight_hash]").val();
        	$.ajax({
        		type:'post', 
        		url:ApiUrl+'/index.php?act=member_buy&op=change_address',
        		data:{key:key,area_id:area_id,city_id:city_id,freight_hash:freight_hash},
        		dataType:'json',
        		success:function(result){
        			var data = result.datas;
        			var sp_s_total = 0;
        			$.each(data.content,function(k,v){
						$('#store'+k).html(v);
        				var sp_toal = parseInt($('#st'+k).attr('store_price'));//店铺商品价格
        				sp_s_total = v+sp_s_total;
        				$('#st'+k).html(eval(sp_toal+v));
        			});
        			
					var total_price = eval(parseInt($('input[name=total_price]').val())+sp_s_total);
					$('#total_price').html(total_price);	
        			
        			$("input[name=address_id]").val(address_id);
        			$('#address').html($('.address_id_'+address_id).html());
        			$('#true_name').html($('.true_name_'+address_id).html());
        			$('#mob_phone').html($('.mob_phone_'+address_id).html());
        			return false;
        		}
        	});
        }else{//保存地址
			if($.sValid()){
				var index = $('select[name=prov]')[0].selectedIndex;
				var aa = $('select[name=prov]')[0].options[index].innerHTML;
				
				
				var true_name = $('input[name=true_name]').val();
				var mob_phone = $('input[name=mob_phone]').val();
				var tel_phone = $('input[name=tel_phone]').val();
				var city_id = $('select[name=city]').val();
				var area_id = $('select[name=region]').val();
				var address = $('textarea[name=address]').val();
				
				var prov_index = $('select[name=prov]')[0].selectedIndex;
				var city_index = $('select[name=city]')[0].selectedIndex;
				var region_index = $('select[name=region]')[0].selectedIndex;	
				var area_info = $('select[name=prov]')[0].options[prov_index].innerHTML+' '+$('select[name=city]')[0].options[city_index].innerHTML+' '+$('select[name=region]')[0].options[region_index].innerHTML;

				//ajax 提交收货地址
				$.ajax({
					type:'post', 
					url:ApiUrl+'/index.php?act=member_address&op=address_add',
					data:{key:key,true_name:true_name,mob_phone:mob_phone,tel_phone:tel_phone,city_id:city_id,area_id:area_id,address:address,area_info:area_info},
					dataType:'json',
					success:function(result){
						if(result){
							$.ajax({//获取收货地址信息
								type:'post',
								url:ApiUrl+'/index.php?act=member_address&op=address_info',
								data:{key:key,address_id:result.datas.address_id},
								dataType:'json',
								success:function(result1){
									var data1 = result1.datas;
									$('#true_name').html(data1.address_info.true_name);
									$('#address').html(data1.address_info.area_info+' '+data1.address_info.address);
									$('#mob_phone').html(data1.address_info.mob_phone);
									
									$('input[name=address_id]').val(data1.address_info.address_id);
									$('input[name=area_id]').val(data1.address_info.area_id);
									$('input[name=city_id]').val(data1.address_info.city_id);
									
									var area_id = data1.address_info.area_id;
									var city_id = data1.address_info.city_id;
									var freight_hash = $('input[name=freight_hash]').val();
									
									$.ajax({//保存收货地址
										type:'post', 
										url:ApiUrl+'/index.php?act=member_buy&op=change_address',
										data:{key:key,area_id:area_id,city_id:city_id,freight_hash:freight_hash},
										dataType:'json',
										success:function(result){
											var data = result.datas;																						
											var sp_s_total = 0;
											$.each(result.datas.content,function(k,v){
												$('#store'+k).html(v);
						        				var sp_toal = parseInt($('#st'+k).attr('store_price'));//店铺商品价格
						        				sp_s_total = v+sp_s_total;
						        				$('#st'+k).html(eval(sp_toal+v));
											});	

											var total_price = eval(parseInt($('input[name=total_price]').val())+sp_s_total);
											$('#total_price').html(total_price);	
											return false;
										}
									});
								}
							});
						}
					}
				});
			}else{
				return false;
			}
        }
        
        var thisPrarent = $(this).parents(".buys1-address-cnt");
        showDetial(thisPrarent);
    });
    $(".save-invoice").click(function (){//保存发票信息
        var self = this;
        //获取address_id
        var invRadio = $('.inv-radio');
        var inv_id;
        for(var i =0;i<invRadio.length;i++){
            if(invRadio[i].checked){
            	inv_id = invRadio[i].value;
            }
        }
        
        if(inv_id>0){//选择发票信息
        	var inv_info = $('#inv_'+inv_id).html();
        	$('#inv_content').html(inv_info);//发票信息
        	$("input[name=invoice_id]").val(inv_id);
        }else{//添加发票信息
            var invtRadio = $('input[name=inv_title_select]');
            var inv_title_select;
            for(var i =0;i<invtRadio.length;i++){
                if(invtRadio[i].checked){
                	inv_title_select = invtRadio[i].value;
                }
            }
            
            var inv_content = $('select[name=inv_content]').val();
            if(inv_title_select == 'company'){
            	var inv_title = $("input[name=inv_title]").val();
            	var data = {key:key,inv_title_select:inv_title_select,inv_title:inv_title,inv_content:inv_content};
            	var html = '公司  ';
            }else{
            	var data = {key:key,inv_title_select:inv_title_select,inv_content:inv_content};
            	var html = '个人  ';
            }
            $.ajax({
            	type:'post',
            	url:ApiUrl+'/index.php?act=member_invoice&op=invoice_add',
            	data:data,
            	dataType:'json',
            	success:function(result){
            		if(result.datas.inv_id>0){
    					var html1 = '<li>'
										+'<label>'
											+'<input type="radio" name="invoice" class="rdo inv-radio" checked="checked" value="'+result.datas.inv_id+'"/>'
											+'<span class="mr5 rdo-span" id="inv_'+result.datas.inv_id+'">'+html+'&nbsp;&nbsp;'+inv_content+'</span>'
										+'</label>'
										+'<a class="del-invoice" href="javascript:void(0);" inv_id="'+result.datas.inv_id+'">[删除]</a>'
									+'</li>';
    					
    					$('#invoice_add').before(html1);
            			$('#inv_content').html(html+inv_content);//发票信息
            			$('input[name=invoice_id]').val(result.datas.inv_id);
            			
            			
        				$('.del-invoice').click(function(){
                            var $this = $(this);
        					var inv_id = $(this).attr('inv_id');
        					$.ajax({
        						type:'post',
        						url:ApiUrl+'/index.php?act=member_invoice&op=invoice_del',
        						data:{key:key,inv_id:inv_id},
        						success:function(result){
        							if(result){
        								$this.parent('li').remove();
        							}
        							return false;
        						}
        					});
        				});
            		}     		
            	}
            });
            
        }
        
        var thisPrarent = $(this).parents(".buys1-invoice-cnt");
        showDetial(thisPrarent);
    });
    $(".no-invoice").click(function (){
        $('#inv_content').html("不需要发票");
        $('input[name=invoice_id]').val('');
        var thisPrarent = $(this).parents(".buys1-invoice-cnt");
        showDetial(thisPrarent);
    });
    
    $('#pguse').click(function(){//验证密码
    	var loginpassword = $("input[name=loginpassword]").val();
    	if(loginpassword == ''){
    		$('.password_error_tip').show();
    		$('.password_error_tip').html('登录密码不能为空');
    		return false;
    	}
    	$.ajax({
    		type:'post',
    		url:ApiUrl+'/index.php?act=member_buy&op=check_password',
    		data:{key:key,password:loginpassword},
    		dataType:'json',
    		success:function(result){
    			if(result.datas == 1){
    				$('input[name=passwd_verify]').val('1');
    				$('#pd').hide();
    			}else{
    				$('#pd').show();
    				$('.password_error_tip').show();
    				$('.password_error_tip').html(result.datas.error);
    			}
    		}
    	});
    });
    
    $('#usepdpy').click(function(){//验证密码切换
    	if($(this).attr('checked')){
    		$('#pd').show();
    	}else{
    		$('#pd').hide();
    	}
    });
    
    
    $('#buy_step2').click(function(){//提交订单step2
    	var data = {};
    	data.key = key;
    	if(ifcart == 1){//购物车订单
    		data.ifcart = ifcart;
    	}
    	data.cart_id = cart_id;
    	
    	var address_id = $('input[name=address_id]').val();
    	data.address_id = address_id;

    	var vat_hash = $('input[name=vat_hash]').val();
    	data.vat_hash = vat_hash;
    		
    	var offpay_hash = $('input[name=offpay_hash]').val();
    	data.offpay_hash = offpay_hash;
    	
        //获取address_id
        var payRadio = $('input[name=buy-type]');
        var pay_name;
        for(var i =0;i<payRadio.length;i++){
            if(payRadio[i].checked){
            	pay_name = payRadio[i].value;
            }
        }
        data.pay_name = pay_name;
        
        var invoice_id = $('input[name=invoice_id]').val();
        data.invoice_id = invoice_id;
        
        var voucher = new Array();
        $("select[name=voucher]").each(function(){
        	var store_id = $(this).attr('store_id');
        	voucher[store_id] = $(this).val();
        });
        data.voucher = voucher;
        
        var available_predeposit = parseInt($('input[name=available_predeposit]').val());
        if(available_predeposit>0){
            if($('#usepdpy').prop('checked')){//使用预存款
            	var passwd_verify = parseInt($('input[name=passwd_verify]').val());
            	if(passwd_verify != 1){//验证密码失败       		
            		return false;
            	}
            	
            	var pd_pay = 1;
            	data.pd_pay = pd_pay;
            	var passwd = $('input[name=loginpassword]').val();
            	data.password = passwd;
            }else{
            	var pd_pay = 0;
            	data.pd_pay = pd_pay;
            }
        }else{
        	var pd_pay = 0;
        	data.pd_pay = pd_pay;
        }


        $.ajax({
        	type:'post',
        	url:ApiUrl+'/index.php?act=member_buy&op=buy_step2',
        	data:data,
        	dataType:'json',
        	success:function(result){
        		//return false;
        		checklogin(result.login);
        		//if(result.datas.error != ''){
        			//return false;
        		//}
        		if(result.datas.pay_sn.pay_sn != ''){
        			location.href = WapSiteUrl+'/tmpl/member/order_list.html'; 
        		}
        		return false;
        	}
        });
    });
    
    function showDetial(parent){
        $(parent).find(".buys1-edit-btn").show();
        $(parent).find(".buys1-hide-list").addClass("hide");
        $(parent).find(".buys1-hide-detail").removeClass("hide");
    }
    function hideDetail(parent){
        $(parent).find(".buys1-edit-btn").hide();
        $(parent).find(".buys1-hide-list").removeClass("hide");
        $(parent).find(".buys1-hide-detail").addClass("hide");
    }
});