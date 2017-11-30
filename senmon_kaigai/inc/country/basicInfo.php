<?php // 国の基本情報リンク ?>
<?php if (!empty($masterCsv[KEY_MASTER_CSV_COUNTRY_COMMON_INFO_DISPLAY])) :?>
    <h2 class="list-inline main-title mb20 mainBgClr">
        <span class="mid main-title-txt"><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?>の基本情報</span>
    </h2>
    <?php echo $masterCsv[KEY_MASTER_CSV_TAG_INFO]; ?>
<?php endif;?>

<input type="hidden" id="country" value="<?=$masterCsv[KEY_MASTER_CSV_COUNTRY_LOWER];?>">
<div class="inner clear">
    <?php if(!empty($masterCsv[KEY_MASTER_CSV_BEST_SEVEN_DISPLAY])): // ベスト7があるなら ?>
        <div class="content left bg-none">
            <p class="mb5"><img src="<?=$PathSenmonLink;?>images/ttl_tourism_guide_swiss1.jpg" alt=""></p>
            <div id="RankigPht02">
                <ol class="tourism-guide list-inline clear">
                    <?php
                        // CSVデータ読み込み(ranking7用)
                        include_once($_SERVER['DOCUMENT_ROOT'] .'/attending/guide/phpsc/readCsv.php');
                    ?>
                    <?php
                        $guide_key = str_replace("/guide/", "", $masterCsv[KEY_MASTER_CSV_GUIDE_PATH]);
                        $guide_key = str_replace("/", "", $guide_key);
                        new readCsv($guide_key);
                    ?>
                </ol>
            </div>
            <?php
                // おすすめ情報 / 観光地情報
                if (file_exists($DocumentRootOld ."attending".$masterCsv[KEY_MASTER_CSV_GUIDE_PATH]."inc/index_sightseeing.php")) {
                    include($DocumentRootOld ."attending".$masterCsv[KEY_MASTER_CSV_GUIDE_PATH]."inc/index_sightseeing.php");
                }
            ?>
        </div>
    <?php elseif (!empty($masterCsv[KEY_MASTER_CSV_SIGHTSEEING_INFO_DISPLAY])): // 観光情報なら?>
        <?php
            if (file_exists($AttendingPath.'/inc/CountryInfo.php')) {
                include($AttendingPath.'/inc/CountryInfo.php');
            }
            elseif (file_exists($AttendingPath.'/inc/CityInfo.php')) {
                include($AttendingPath.'/inc/CityInfo.php');
            }
        ?>
    <?php endif; ?>
    
    <?php include_once($PathSenmonCommon . 'sharing/phpsc/GenchiBlog.php');//現地情報ブログ?>
</div>
