<div class="banner1">
    <div class="bn-title mainBgClr">
        <h1 class="bn-title-ltxt color-white"><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?></h1>
        <p class="bn-title-stxt TxtColor"><?=$masterCsv[KEY_MASTER_CSV_NAME_EN];?></p>
    </div>
    <input type="hidden" id="category_type" value="<?=$categoryType;?>">
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
