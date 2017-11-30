<?php
$tabClass = 'two_tab';
$hacchakuHtml = '';
// 現地発着タブの表示判定
if(isGenchiHacchakuShow())
{
    $tabClass = '';
    $active = '';
    if($tabType == 'hacchakuTour'){
        $active = 'active';
    }

    $hacchakuHtml .=<<<EOD
    <li class="{$active}" data-link="#tab_hacchakuTour">
        <span><span>{$masterCsv[KEY_MASTER_CSV_NAME_JA]}<br>発着<br>ツアー</span></span>
    </li>
EOD;
}
?>

<div class="wr-tab mb20">
    <ul class="tab-menu mb20 disable <?=$tabClass;?>" id="tab_<?php echo $tabType; ?>">
        <li class="<?php echo $tabType=='tour' ? 'active' : ''; ?>" data-link="#tab_tour">
            <span><span><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?><br>ツアー</span></span>
        </li>
        <li class="<?php echo $tabType=='freePlan' ? 'active' : ''; ?>" data-link="#tab_freePlan">
            <span><span><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?><br>フリー<br>プラン</span></span>
        </li>
        <?php echo($hacchakuHtml);?>
    </ul>
</div>
