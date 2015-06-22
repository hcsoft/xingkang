
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo RESOURCE_SITE_URL; ?>/js/timeline/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
    <section class="main">

        <ul class="timeline">
            <?php
                $data = Array(
                    Array('date'=>'2015-06-02',title=>'会员卡充值',ico=>'credit-card',content=>'充值金额100元 , 充值时间13:20'),
                    Array('date'=>'2015-06-02',title=>'会员消费' , ico=>'credit-card',content=>'消费金额200元 , 消费时间13:20'),
                    Array('date'=>'2015-06-02',title=>'会员卡充值',ico=>'credit-card',content=>'充值金额100元 , 充值时间13:20'),
                    Array('date'=>'2015-06-02',title=>'会员卡充值',ico=>'credit-card',content=>'充值金额100元 , 充值时间13:20'),
                    Array('date'=>'2015-06-02',title=>'会员卡充值',ico=>'credit-card',content=>'充值金额100元 , 充值时间13:20'),
                    Array('date'=>'2015-06-02',title=>'会员卡充值',ico=>'credit-card',content=>'充值金额100元 , 充值时间13:20'),
                    Array('date'=>'2015-06-02',title=>'会员卡充值',ico=>'credit-card',content=>'充值金额100元 , 充值时间13:20')
                );
                foreach ($data as $k => $v){
            ?>
            <li class="event">
                <input type="radio" name="tl-group" <?php if($k==0){?> checked <?php } ?> />
                <label></label>
                <div class="thumb"><i class="fa fa-<?php echo $v['ico'] ?>" style="  height: 60%;  width: 60%;  margin-top: 20%;  font-size: 60px;"></i><span><?php echo $v['date']?></span></div>
                <div class="content-perspective">
                    <div class="content">
                        <div class="content-inner">
                            <h3><?php echo $v['title']?></h3>
                            <p><?php echo $v['content']?></p>
                        </div>
                    </div>
                </div>
            </li>

            <?php } ?>
        </ul>
    </section>

</div><!-- /container -->

</body>
</html>
