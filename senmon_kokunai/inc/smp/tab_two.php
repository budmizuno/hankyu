<?php if(isset($osusumeCsv[OSUSUME_FLAG][TOUR_TAB_FLAG_NAME]) && $osusumeCsv[OSUSUME_FLAG][TOUR_TAB_FLAG_NAME] == 1): // タブの表示フラグが立っているなら ?>
<div class="wr-tab mb20">
    <ul class="tab-menu mb20 two_tab" id="bltai3">
        <li class="active">
            <span><span><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?><br>ツアー</span></span>
        </li>
        <li>
            <span><span><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?><br>フリープラン</span></span>
        </li>
    </ul>
</div>
<?php endif; ?>
