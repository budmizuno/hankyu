<?php if (isset($guideCsv) && count($guideCsv) > 0) : ?>
<?php
	// データの並び替えを行う(sortの昇順)
	$sort = array();
	foreach ((array) $guideCsv as $key => $value) {
		$sort[$key] = $value['sort'];
	}
	array_multisort($sort, SORT_ASC, $guideCsv);
?>
<section class="blue ichishiWrapperOne tabiInfoWrapper js-moreEight">
	<ul class="clearfix">
		<?php foreach ($guideCsv as $data) : ?>
				<li>
					<a href="<?php echo $data['guide_url'];?>">
					<img src="<?php echo $senmon_func->imagePathConvert(IMG_TYPE_GUIDE_LIST, $data['guide_image1'], false);?>" alt="<?php echo $data['guide_image_alt1'];?>">
					<p class="frame-guide-txt"><?php echo $data['name'];?></p>
					</a>
				</li>
			
		<?php endforeach;?>
	</ul>
	<p class="moreNewTourPls">
		<span>もっと見る</span>
	</p>
	<p class="moreNewTourMns" style="display: none;">
		<span>閉じる</span>
	</p>
</section>
<?php endif;?>
