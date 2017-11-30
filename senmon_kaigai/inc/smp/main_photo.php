<div class="banner">
    <div class="bn-title mainBgClr">
        <h1 class="bn-title-ltxt color-white"><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?></h1>
        <p class="bn-title-stxt TxtColor"><?=$masterCsv[KEY_MASTER_CSV_NAME_EN];?></p>
    </div>
    <div id="" class="frame" style="margin-bottom: 0;">
        <ul class="clearfix swiper-wrapper" style="margin-bottom: 0;">
            <?php foreach ($photoCsv as $value):?>
                <?php if ($categoryType == CATEGORY_TYPE_DEST) :?>
                    <?php if($value[KEY_Q_CATEGORY] == $masterCsv['map_default_display']): ?>
                        <li class="swiper-slide">
                        <img src="<?=$value[KEY_Q_IMG_PATH];?>" alt="<?=$value[KEY_Q_IMG_CAPTION];?>">
                        </li>
                        <?php break;?>
                    <?php endif; ?>
                <?php else :?>
                    <?php if($value[KEY_Q_CATEGORY] == $masterCsv[KEY_MASTER_CSV_NAME_JA]): ?>
                        <li class="swiper-slide">
                        <img src="<?=$value[KEY_Q_IMG_PATH];?>" alt="<?=$value[KEY_Q_IMG_CAPTION];?>">
                        </li>
                        <?php break;?>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
    <p class="txtBannerContent"><?=$masterCsv[KEY_MASTER_CSV_PAGE_CAPTION_SP];?></p>
</div>


