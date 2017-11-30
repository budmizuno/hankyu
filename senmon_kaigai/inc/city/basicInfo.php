<?php // 基本情報 ?>
<?php include($PathSenmonCommon . 'phpsc/up_mokuteki.php'); ?>
<input type="hidden" id="guide_more_url_display" value="<?=$masterCsv[KEY_MASTER_CSV_GUIDE_MORE_URL_DISPLAY];?>">
<h2 class="list-inline main-title mb20 mt20 mainBgClr">
    <span class="mid main-title-txt"><?=$def_country_name;?>の基本情報</span>
</h2>
<ul class="basic-information clear color-blue font-14">
    <?php // 都市の基本情報リンク ?>
    <?php echo $masterCsv[KEY_MASTER_CSV_TAG_INFO]; ?>
</ul>

<div class="inner clear">
    <?php
        // 観光情報
        if (file_exists($AttendingPath.'/inc/CityInfo.php')) {
            include($AttendingPath.'/inc/CityInfo.php');
        // マカオはCountryInfo.phpを読み込む
        }elseif (file_exists($AttendingPath.'/inc/CountryInfo.php')) {
            include_once($AttendingPath.'/inc/CountryInfo.php');
        }
    ?>

    <?php include_once($PathSenmonCommon . 'sharing/phpsc/GenchiBlog.php');//現地情報ブログ?>

</div>
