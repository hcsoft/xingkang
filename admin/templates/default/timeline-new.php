<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" media="all" rel="stylesheet">
    <link href="<?php echo RESOURCE_SITE_URL; ?>/js/timeline/styles.css" rel="stylesheet" type="text/css">
</head>
<body style="padding-top: 0px;">
<div style="background-color: #ffffff;width:100%;position: relative;top:0;padding:20px;box-sizing: border-box;">


    <table style="width:100%;font-size:18px;">
        <tr class="hover member" style="background: rgb(255, 255, 255);">
            <td class="w24"></td>
            <td class="w48 picture">
                <div class="size-44x44"><span class="thumb size-44x44"><i></i><img
                            src="http://localhost/xingkang/data/upload/shop/common/default_user_portrait.gif?0.14009400 1433639342"
                            onload="javascript:DrawImage(this,44,44);" width="44" height="44"></span></div>
            </td>
            <td>
                <p class="name"><!--会员名:<strong></strong>-->
                    <strong>卡号:</strong> 680003569</p>

                <p class="name"><!--会员名:<strong></strong>-->
                    <strong>姓名:</strong> 赵凌云</p>

                <p class="name"> <strong>电话:</strong>15825258054</p>

                <p class="name"> <strong>地址:</strong>五华区滇缅大道616号</p>


            </td>
            <td><p class="name"> <strong>身份证:</strong> 532101195810200929</p>

                <p class="name"> <strong>医保卡:</strong>8261527381</p>

                <p class="name"> <strong>健康档案:</strong>530122001003123</p>
            </td>

            <td><p class="name"> <strong>卡类型:</strong> 储值卡</p>

                <p class="name"> <strong>卡级别:</strong> 健康卡</p>

            </td>
            <td><p class="name"> <strong>办卡渠道:</strong> 店内宣传</p>

                <p class="name"> <strong>推荐人:</strong>王三</p>

            </td>
            <td><p class="name"> <strong>末次消费日期:</strong> <a href="javascript:setdata('消费')">2013-12-11</a></p>

                <p class="name"> <strong>末次消费地点:</strong> 盛高社区</p>
                <p class="name"> <a href="javascript:setdata()">显示全部</a></p>
            </td>
            <td class="">
                <p class="name"><strong>储值余额:</strong>&nbsp;<font class="red">8403.20</font>&nbsp;元</p>
                <p class="name"><strong>赠送余额:</strong> <font class="red">0.00</font>&nbsp;元           </p>
                <p class="name"><strong>消费积分:</strong> <font class="red">0</font></p>
            </td>
        </tr>
    </table>
</div>
<div class="timeline animated" id="content">

</div>

<script>
    var bgs=['primary','warning','info'];
    var data = [
    {'date':'6月5日','time':'上午 9:53',bg:'primary','title':'会员卡消费100元',type:'消费',ico : 'credit-card'},
    {'date':'6月3日','time':'下午 1:53',bg:'warning','title':'会员卡充值80元',type:'充值',ico : 'camera'},
    {'date':'6月1日','time':'上午 11:22',bg:'info','title':'会员卡消费33元',type:'消费',ico : 'video-camera'},
    {'date':'5月8日','time':'下午 5:42',bg:'primary','title':'会员卡消费231元',type:'消费',ico : 'quote-right'},
    {'date':'4月22日','time':'上午 10:35',bg:'warning','title':'会员卡消费174元',type:'消费',ico : 'credit-card'},
    {'date':'4月5日','time':'上午 9:48',bg:'info','title':'孕产妇体检',type:'孕产妇',ico : 'camera'},
    {'date':'4月1日','time':'上午 10:09',bg:'info','title':'建立孕产妇保健手册',type:'孕产妇',ico : 'quote-right'},
    {'date':'1月20日','time':'上午 10:23',bg:'info','title':'建立居民健康档案',type:'档案',ico : 'video-camera'},
    ]
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
                <h2>\
                '+data[i].title+'\
                </h2>\
                </div>\
                </div>\
                </div>';
            }
        }
        console.log("htmlstr",htmlstr)
        document.getElementById("content").innerHTML = htmlstr;
    }
    setdata();


</script>
</body>
</html>
