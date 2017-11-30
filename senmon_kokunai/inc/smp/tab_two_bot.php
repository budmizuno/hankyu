<div class="wr-tab mb20">
    <ul class="tab-menu mb20 two_tab disable" id="tab_<?php echo $tabType; ?>">
        <li class="<?php echo $tabType=='tour' ? 'active' : ''; ?>" data-link="#tab_tour">
            <span><span><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?><br>ツアー</span></span>
        </li>
        <li class="<?php echo $tabType=='freePlan' ? 'active' : ''; ?>" data-link="#tab_freePlan">
            <span><span><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?><br>フリープラン</span></span>
        </li>
    </ul>
</div>