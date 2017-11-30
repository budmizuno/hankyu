<input type="hidden" id="category_type" value="<?=$categoryType;?>">
<input type="hidden" id="senmon_name" value="<?=$senmonNameEnLower;?>">
<?php if(isset($osusumeCsv[OSUSUME_FLAG][TOUR_TAB_FLAG_NAME]) && $osusumeCsv[OSUSUME_FLAG][TOUR_TAB_FLAG_NAME] == 1): // タブの表示フラグが立っているなら ?>
<?php
$tabClass = 'two_tab';
$hacchakuHtml = '';
// 現地発着タブの表示判定
if(isGenchiHacchaku())
{
    $tabClass = '';
    $hacchakuHtml .=<<<EOD
    <li class="tab_genchihacchaku"><span><span>{$masterCsv[KEY_MASTER_CSV_NAME_JA]}<br>発着<br>ツアー</span></span></li>
EOD;
}

 ?>
<div class="wr-tab mb20">
    <ul class="tab-menu mb20 <?=$tabClass;?>" id="bltai3">
        <li class="tab_tour active"><span><span><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?><br>ツアー</span></span></li>
        <li class="tab_freeplan"><span><span><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?><br>フリー<?php echo (isGenchiHacchaku() ? '<br>' : '')?>プラン</span></span></li>
        <?php echo($hacchakuHtml);?>
    </ul>

</div>
<?php endif; ?>
