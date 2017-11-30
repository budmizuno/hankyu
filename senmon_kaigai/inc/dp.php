<h3 class="list-inline recommend-group center mt25 bdColor">
    <i class="icon-main icon-global-main mid mainBgClr"></i>
    <span class="font-20 mid">阪急交通社がおすすめする<?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?>の海外航空券・ホテル</span>
</h3>
<?php
$dp_link = 'http://www.hankyu-travel.com/dp/';
if (isset($masterCsv['dp_link']) && $masterCsv['dp_link'] != '' && $masterCsv['dp_link'] != '#N/A') {
    $dp_link = $masterCsv['dp_link'];
}
?>
<a href="<?php echo $dp_link;?>"><img class="mt20 mb60" src="<?=$PathSenmonLink;?>images/0110_dp_bn.jpg"></a>

<div class="list-inline journey bltai5 bdColor mb40 journey-taipei travel-info">
    <i class="icon-main icon-mark-main mid mainBgClr"></i>
    <span class="mid font-25 journey-info">旅の情報</span>
    <span class="mid font-14">旅行準備や旅行計画をはじめ旅行中にも役立つ情報が満載です！</span>
</div>
