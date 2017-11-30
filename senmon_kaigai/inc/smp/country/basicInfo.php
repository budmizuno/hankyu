<?php // 国の基本情報リンク ※下記データはcsv /var/www/html/hankyu-travel.com/attending/senmon_kaigai/setting/master_senmon_kaigai_2017.csv の tag_country_common_info にある ?>
<?php if (!empty($masterCsv[KEY_MASTER_CSV_COUNTRY_COMMON_INFO_DISPLAY])) :?>
    <h2 class="list-inline main-title mb20 mainBgClr">
    <?php if ($categoryType == CATEGORY_TYPE_COUNTRY) :?>
        <span class="mid main-title-txt"><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?>の基本情報</span>
    <?php else :?>
        <span class="mid main-title-txt"><?=$def_country_name;?>の基本情報</span>
    <?php endif;?>
    </h2>
    <?php echo $masterCsv[KEY_MASTER_CSV_TAG_INFO]; ?>
<?php endif;?>

<?php if ($touristInfomationCsv != null && count($touristInfomationCsv) > 0) :?>

<section class="blue wrapper-sight-seeing mb15">
    <ul>
		<?php
		foreach($touristInfomationCsv as $touristInfomation) {
			if ($touristInfomation['name'] == $masterCsv[KEY_MASTER_CSV_NAME_JA]) {
		?>

		<a href="<?php echo $touristInfomation['guide_url']; ?>">
			<li>
				<p class="sight-seeing-img">
					<img src="<?php echo $senmon_func->imagePathConvert(IMG_TYPE_BRAND, $touristInfomation['guide_image1']); ?>" alt="<?php echo $touristInfomation['guide_image_alt1']; ?>">
				</p>
				<p class="sight-seeing-text">
					<?php echo $touristInfomation['caption']; ?>
				</p>
			</li>
		</a>

		<?php
		    }
		}
		?>
    </ul>
</section>

<?php endif;?>
