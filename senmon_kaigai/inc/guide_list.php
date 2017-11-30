<?php if (isset($guideCsv) && count($guideCsv) > 0) : ?>
<?php
	// データの並び替えを行う(sortの昇順)
	$sort = array();
	foreach ((array) $guideCsv as $key => $value) {
		$sort[$key] = $value['sort'];
	}
	array_multisort($sort, SORT_ASC, $guideCsv);
?>
<ul class="frame-guide color-black mt40 mb20 clear">
	<?php foreach ($guideCsv as $data) : ?>
	<a href="<?php echo $data['guide_url'];?>">
		<li>
		<img src="<?php echo $senmon_func->imagePathConvert(IMG_TYPE_GUIDE_LIST, $data['guide_image1'], false);?>" alt="<?php echo $data['guide_image_alt1'];?>">
		<p class="frame-guide-title center"><?php echo $data['name'];?></p>
		<p class="center frame-guide-txt"><?php echo $data['caption'];?></p>
		</li>
	</a>
	<?php endforeach;?>
</ul>
<?php endif;?>