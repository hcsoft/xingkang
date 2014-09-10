<section class="offcanvas-siderbars">
	<div class="container">
		<div class="row">
			<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div id="content" style="width: 100%;">
					<div class="content-top">


<section id="pav-slideshow" class="pav-slideshow">
	<div class="container">
		<div class="row">
			<div class="layerslider-wrapper" style="max-width: 1170px;">
				<div class="bannercontainer banner-boxed"
					style="padding: 0; margin: 0;">
					<div id="sliderlayer8580" class="rev_slider boxedbanner"
						style="width: 100%; height: 450px;">
						
						<?php if(count($imglist['slideshow'])>0){?>
						<ul>
							<?php foreach ($imglist['slideshow'] as $i => $img){?>
							<?php if($img['img']!=''){?>
							<li data-masterspeed="700" data-transition="random"
								data-slotamount="7" >
								<img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_SLIDE.DS.$img['img'];?>">

								<!-- THE MAIN IMAGE IN THE SLIDE -->
								<div
									class="caption large_text lfb easeOutExpo   easeOutExpo "
									data-x="112" data-y="110" data-speed="300" data-start="668"
									data-easing="easeOutExpo"><?php echo $img['text1']?></div> <!-- THE MAIN IMAGE IN THE SLIDE -->
								<div
									class="caption medium_text lfb easeOutExpo   easeOutExpo "
									data-x="112" data-y="163" data-speed="300" data-start="1323"
									data-easing="easeOutExpo"><?php echo $img['text2']?></div> <!-- THE MAIN IMAGE IN THE SLIDE -->
								<div
									class="caption small_text lfb easeOutExpo   easeOutExpo "
									data-x="113" data-y="209" data-speed="300" data-start="2026"
									data-easing="easeOutExpo"><?php echo $img['text3']?></div> <!-- THE MAIN IMAGE IN THE SLIDE -->
								<div
									class="caption small_text lfb  easeOutExpo   easeOutExpo "
									data-x="115" data-y="236" data-speed="300" data-start="2754"
									data-easing="easeOutExpo"><?php echo $img['text4']?></div> <!-- THE MAIN IMAGE IN THE SLIDE -->
								<a href="<?php echo $img['url']?>">
								<div
									class="caption tp-button btn-theme-primary lfb easeOutExpo   easeOutExpo "
									data-x="113" data-y="277" data-speed="300" data-start="3246"
									data-easing="easeOutExpo">更多</div></a>
							</li>
							<?php }?>
							<?php }?>

						</ul>
						<?php }?>
					</div>
				</div>


			</div>


			<!--
			##############################
			 - ACTIVATE THE BANNER HERE -
			##############################
			-->
			<script type="text/javascript">

				var tpj=jQuery;
				 
				if (tpj.fn.cssOriginal!=undefined)
					tpj.fn.css = tpj.fn.cssOriginal;

					tpj('#sliderlayer8580').revolution(
						{
							delay:9000,
							startheight:450,
							startwidth:1170,


							hideThumbs:0,

							thumbWidth:100,						
							thumbHeight:50,
							thumbAmount:5,

							navigationType:"bullet",				
							navigationArrows:"verticalcentered",				
														navigationStyle:"round",			 
							 					
							navOffsetHorizontal:0,
							navOffsetVertical:0, 	

							touchenabled:"on",			
							onHoverStop:"off",						
							shuffle:"off",	
							stopAtSlide:-1,						
							stopAfterLoops:-1,						

							hideCaptionAtLimit:0,				
							hideAllCaptionAtLilmit:0,				
							hideSliderAtLimit:0,			
							fullWidth:"off",
							shadow:0	 
						});
			</script>
		</div>
	</div>
</section>
</div>
				</div>
			</section>
		</div>
	</div>
</section>