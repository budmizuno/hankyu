<?php if(isset($osusumeCsv[OSUSUME_FLAG][TOUR_TAB_FLAG_NAME]) && $osusumeCsv[OSUSUME_FLAG][TOUR_TAB_FLAG_NAME] == 1): // タブの表示フラグが立っているなら ?>
    <div class="mb20">
        <ul class="tab-menu" id="bltai3">
            <li class="active"><span><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?> ツアー</span></li>
            <li><span><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?> フリープラン</span></li>
        </ul>
    </div>
<?php endif; ?>
