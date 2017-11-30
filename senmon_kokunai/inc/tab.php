<?php if($categoryType == CATEGORY_TYPE_DEST): // 方面?>
    <?php if(isset($osusumeCsv[OSUSUME_FLAG][TOUR_TAB_FLAG_NAME]) && $osusumeCsv[OSUSUME_FLAG][TOUR_TAB_FLAG_NAME] == 1): // タブの表示フラグが立っているなら ?>
        <?php $tabClass =  isGenchiHacchaku() == true ? 'tab-menu-3': '';?>
        <ul class="tab-menu <?=$tabClass;?> mb20">
            <li class="tab_tour active"><span><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?> ツアー</span></li>
            <li class="tab_freeplan"><span><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?> フリープラン</span></li>

            <?php // 現地発着タブの表示判定
            if(isGenchiHacchaku()):?>
                <li class="tab_genchihacchaku"><span><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?>発着ツアー</span></li>
            <?php endif;?>
        </ul>
    <?php endif; ?>

<?php elseif ($categoryType == CATEGORY_TYPE_COUNTRY): // 都道府県?>
    <?php if(isset($osusumeCsv[OSUSUME_FLAG][TOUR_TAB_FLAG_NAME]) && $osusumeCsv[OSUSUME_FLAG][TOUR_TAB_FLAG_NAME] == 1): // タブの表示フラグが立っているなら ?>
            <?php // 現地発着タブの表示判定
            if(isGenchiHacchaku()):?>
                    <ul class="tab-menu tab-menu-3 mb20">
                        <li class="tab_tour active"><span><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?> ツアー</span></li>
                        <li class="tab_freeplan"><span><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?> フリープラン</span></li>
                        <li class="tab_genchihacchaku"><span><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?>発着ツアー</span></li>
                    </ul>
            <?php else:?>
                    <ul class="tab-menu mb20">
                        <li class="tab_tour active"><span><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?> ツアー</span></li>
                        <li class="tab_freeplan"><span><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?> フリープラン</span></li>
                    </ul>
            <?php endif;?>
    <?php endif; ?>

<?php else:// 観光都市?>
    <?php if(isset($osusumeCsv[OSUSUME_FLAG][TOUR_TAB_FLAG_NAME]) && $osusumeCsv[OSUSUME_FLAG][TOUR_TAB_FLAG_NAME] == 1): // タブの表示フラグが立っているなら ?>
        <ul class="tab-menu mb20">
            <li class="tab_tour active"><span><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?> ツアー</span></li>
            <li class="tab_freeplan"><span><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?> フリープラン</span></li>
        </ul>
    <?php  endif; ?>
<?php endif;?>
