<h3 class="recommend-group mb10 bdColor">
    <i class="icon br50 icon-global mainBgClr"></i>
    <p class="">阪急交通社がおすすめする<?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?>の海外航空券・ホテル</p>
</h3>
<?php
$dp_link = 'http://www.hankyu-travel.com/dpsp/';
if (isset($masterCsv['dp_link_sp']) && $masterCsv['dp_link_sp'] != '' && $masterCsv['dp_link_sp'] != '#N/A') {
    $dp_link = $masterCsv['dp_link_sp'];
}
?>
<a href="<?php echo $dp_link;?>" class="plpr4"><img class="mt10" src="<?=$PathSenmonLink;?>images/smp/dp.jpg"></a>
