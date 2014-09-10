<section class="offcanvas-siderbars">
	<div class="container">
		<div class="row">
			<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div id="content" style="width: 100%;">
					<div class="content-top">
<div class="dark box productcarousel">
	<div class="box-heading">
		<h4>
			<span>热销商品</span>
		</h4>
	</div>
	<div class="box-content">
		<div class="box-products slide" id="productcarousel1">
			<div class="carousel-controls">
				<a class="carousel-control left icon-angle-left"
					href="#productcarousel1" data-slide="prev"></a> <a
					class="carousel-control right icon-angle-right"
					href="#productcarousel1" data-slide="next"></a>
			</div>
			<div class="carousel-inner ">
			
			<?php if(count($bestsell)>0){?>
				<?php foreach ($bestsell as $i => $good){?>
				<?php if($i%8==0){?>
				<div class="item <?php  if($i==0){echo 'active';}?>">
					<div class="row box-product">
					<?php }?>

						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
							<div class="product-block">

								<div class="image">
									<span class="product-label-special label">Sale</span> 
										<a class="img"
										href="<?php echo urlShop('goods', 'index', array('goods_id'=>$good['goods_id']));?>">
										<img
										src="<?php echo $good['img1'];?>"
										title="<?php echo $value['goods_name'];?>" alt="<?php echo $value['goods_name'];?>" /> </a>

									<div class="faceback hidden-xs hidden-sm">
										<a class="img back"
											href="<?php echo urlShop('goods', 'index', array('goods_id'=>$good['goods_id']));?>">
											<img
											src="<?php echo $good['img2'];?>"
											alt="<?php echo $value['goods_name'];?>">
										</a>
									</div>

									<a class="pav-colorbox cboxElement hidden-sm hidden-xs"
										href="<?php echo urlShop('goods', 'index', array('goods_id'=>$good['goods_id']));?>">
										Quick View</a>

								</div>
								<div class="product-meta">
									<h3 class="name">
										<a
											href="<?php echo urlShop('goods', 'index', array('goods_id'=>$good['goods_id']));?>"><?php echo $value['goods_name'];?></a>
									</h3>
									<div class="description"><?php echo $good['goods_jingle']?></div>

									<div class="rating">
										<img
											src="<?php echo SHOP_TEMPLATES_URL;?>/store/lexus/image/stars-<?php echo $good['evaluation_good_star']?>.png"
											alt="星级" />
									</div>
                      
									<div class="price">
										<span class="price-new"><?php echo $lang['currency'];?>
										  <?php if(intval($good['group_flag']) === 1) { ?>
					                      <?php echo $good['group_price']?>
					                      <?php } elseif(intval($good['xianshi_flag']) === 1) { ?>
					                      <?php echo ncPriceFormat($good['goods_price'] * $good['xianshi_discount'] / 10);?>
					                      <?php } else { ?>
					                      <?php echo $good['goods_price']?>
					                      <?php } ?>
										
										</span> <span class="price-old"><?php echo $lang['currency'].$good['goods_marketprice'];?></span>
										<span class="cart"> <input type="button" value=""
											onclick="addToCart('30');" class="button" />
										</span>

									</div>

									<div class="product-hover">
										<div class="wishlist pull-right">
											<a onclick="addToWishList('30');">加入购物车</a>
										</div>
										<div class="compare pull-right">
											<a class="pavicon-compare" onclick="addToCompare('30');">Add
												to Compare</a>
										</div>
									</div>
								</div>

							</div>
						</div>

					<?php if($i%8==7 || $i==count($bestsell)-1){?>
					</div>
				</div>
				<?php }?>
				<?php }?>
				<?php }?>
				
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$('#productcarousel1').carousel({interval:false,auto:false,pause:'hover'});
</script>
</div>
				</div>
			</section>
		</div>
	</div>
</section>