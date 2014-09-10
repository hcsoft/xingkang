$(function(){
	$.ajax({
		url:ApiUrl+"/index.php?act=map&op=map",
		type:'get',
		dataType:'json',
		success:function(result){
		var map = new BMap.Map("l-map");  
		
		var store_locations = result.datas.store_locations;
		alert(store_locations.length);
		for (var i=0;i<store_locations.length;i++){
			var locations =  store_locations[i].location.split(",");
			var store_name = store_locations[i].store_name;
			var area_info = store_locations[i].area_info;
			var phone = store_locations[i].phone;
			var lng = locations[0];
			var lat = locations[1];	
			
			
			createMark = function(lng, lat, store_name,area_info,phone) {  
				var _point =  new BMap.Point(lng, lat);
				var _icon = new BMap.Icon("../images/mylocation.png", new BMap.Size(20,20));
	            var _marker = new BMap.Marker(_point,{icon:_icon}); 
	            var content="<b>店铺名称:"+store_name+"</b><br>";  
	            content+="<span><strong>地址：</strong>"+area_info+"</span><br>";  
	            content+="<span><strong>电话：</strong>"+phone+"</span><br>";  
	           
	            
	            
	            
	            
	            
	            
	            
				//map.centerAndZoom(_point, 15);
				_marker.addEventListener("click", function(){   
					_marker.openInfoWindow(new BMap.InfoWindow(content,  {
						width: 60,     // 信息窗口宽度
						height: 40,     // 信息窗口高度
						title: ""  // 信息窗口标题
					}));
					map.centerAndZoom(_point,15);
				});
				return _marker;
	        };  
			
	        var marker = createMark(lng,lat,store_name,area_info,phone);  
	        map.addOverlay(marker);  
//			
//			var mypoint = new BMap.Point(ln,la);
//			var myIcon = new BMap.Icon("../images/mylocation.png", new BMap.Size(20,20));
//			var marker_s = new BMap.Marker(mypoint, { icon: myIcon });
//			map.addOverlay(marker_s);
//			map.centerAndZoom(mypoint, 15);
//			marker_s.addEventListener("click", function(){   
//				marker_s.openInfoWindow(new BMap.InfoWindow(area_info,  {
//					width: 60,     // 信息窗口宽度
//					height: 40,     // 信息窗口高度
//					title: store_name  // 信息窗口标题
//				}));
//				map.centerAndZoom(mypoint,15);
//			});
			
		}
		
		var address = result.datas.me_location.content.address;
		var x = result.datas.me_location.content.point.x;
		var y  = result.datas.me_location.content.point.y;
		var mypoint = new BMap.Point(x,y);
		//自定义点的图标
		var myIcon = new BMap.Icon("../images/mylocation.png", new BMap.Size(20,20));
		var marker = new BMap.Marker(mypoint, { icon: myIcon });
		map.addOverlay(marker);
		map.centerAndZoom(mypoint, 15);
		marker.addEventListener("click", function(){   
			marker.openInfoWindow(new BMap.InfoWindow(address,  {
				width: 60,     // 信息窗口宽度
				height: 40,     // 信息窗口高度
				title: '当前位置'  // 信息窗口标题
			}));
			map.centerAndZoom(mypoint,15);
		});
		
		
		
		}
	});
});

