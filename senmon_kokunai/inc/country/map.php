<div class="wr-banner2">
    <div class="left">
        <div class="bn-title bn-title-swiss mainBgClr">
            <h1 class="bn-title-ltxt color-white"><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?></h1>
            <p class="bn-title-stxt TxtColor"><?=$masterCsv[KEY_MASTER_CSV_NAME_EN];?></p>
        </div>
        <input type="hidden" id="category_type" value="<?=$categoryType;?>">
        <input type="hidden" id="senmon_name" value="<?=$senmonNameEnLower;?>">
        <ul class="clearfix" id="banner">

            <?php foreach ($photoCsv as $value):?>

                <?php if($value[KEY_Q_CATEGORY] == $masterCsv[KEY_MASTER_CSV_NAME_JA]): ?>
                    <li>
                        <img src="<?=$value[KEY_Q_IMG_PATH];?>" alt="<?=$value[KEY_Q_IMG_CAPTION];?>">
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>

        </ul>
        <p class="txtBannerContent"><?=$masterCsv[KEY_MASTER_CSV_PAGE_CAPTION];?></p>
    </div>
    <div class="right">
        <p class="mainBgClr"><i class="sprite sprite-flag"></i>目的地から探す</p>
<!--        <a href="#" class="wr-banner2-a">ヨーロッパへ戻る</a> -->

        <?php echo $mapHtml; //map?>

        <?php if(0 < count($popularCountryCityCsv)): ?>
            <div class="wr-banner2-bottom wr-banner2-bottom0">
                <img src="<?=$PathSenmonLink;?>images/map_note_swiss.jpg" alt="">
                <ul>
                    <?php
                        foreach ($popularCountryCityCsv as $item):
                            if ($item['q_category'] == $masterCsv[KEY_MASTER_CSV_NAME_JA] && $item['q_title'] != "") {
                                ?>
                                <li>
                                    <a href="<?php echo $item['tour_url']; ?>"><?php echo $item['q_title']; ?></a>
                                </li>
                            <?php }
                        endforeach;
                    ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>
<div class="clearfix"></div>
