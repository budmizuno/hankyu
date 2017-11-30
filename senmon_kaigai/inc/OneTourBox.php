<?php
	// 画像処理
	preg_match('/p_photo_mno=(.*)$/', $paramAry->imgFilepath,$Array);
	if (isset($Array[1])) {
	    $filepath = $Array[1];
	}
	$filepath = '//x.hankyu-travel.com/photo_db/image_search_kikan3.php?p_photo_mno='.$filepath;
?>

<li class="swiper-slide">
	<a href="<?php e($paramAry->url);?>">
		<img src="<?php e($filepath);?>" alt="<?php e($paramAry->imgCaption);?>">
		<div class="slider-wr-content">
			<p class="slider-title">
				<?php if($kyotenId == 'index'): ?>
					<span>
						<?php
							$p_hatsu_name_arr = array();
							if(count(($paramAry->p_hatsu_name)) > 0)
							{
								$p_hatsu_name_arr = explode(',', $paramAry->p_hatsu_name[0]);
								if(count($p_hatsu_name_arr) > 0){
									e($p_hatsu_name_arr[1].'発');
								}else{
									e($p_hatsu_name_arr[0].'発');
								}
							}
						?>
					</span>
				<?php endif; ?>
				<?php
					// ブランド指定なら
					if(isset($_REQUEST['p_mainbrand'])){
						echo stringControl($paramAry->p_course_name,STRING_LIMIT_BRAND_COURSE_NAME);
					}
					else{
						e($paramAry->p_course_name);
					}
				 ?>
			</p>
			<p class="slider-content">
				<?php
					// ブランド指定なら
					if(isset($_REQUEST['p_mainbrand'])){
						echo stringControl($paramAry->p_course_name,STRING_LIMIT_BRAND_CAPTION);
					}
					else{
						e($paramAry->p_point1);
					}
				 ?>
			</p>
			<p class="slider-price price"><?php e($paramAry->priceMinMax);?></p>
		</div>
	</a>
</li>
