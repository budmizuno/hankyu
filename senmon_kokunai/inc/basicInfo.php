<input type="hidden" id="country" value="<?=$masterCsv[KEY_MASTER_CSV_COUNTRY_LOWER];?>">
<div id="basicInfo">
    <?php //人気スポット、おすすめ情報、観光地情報 ?>
    <?php
        if (file_exists($DocumentRootOld ."attending".$masterCsv[KEY_MASTER_CSV_GUIDE_PATH]."inc/index_sightseeing_d.php")) {
            include($DocumentRootOld ."attending".$masterCsv[KEY_MASTER_CSV_GUIDE_PATH]."inc/index_sightseeing_d.php");
        }
    ?>
</div>

<?php // 現地情報ブログ ?>
<?php include_once($PathSenmonCommon . 'sharing/phpsc/GenchiBlog.php');//現地情報ブログ?>
<div class="FClear"></div>
