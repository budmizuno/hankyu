<script type="text/javascript" src="<?=$PathSenmonLink;?>js/newSearch.js"></script>
<link type="text/css" rel="stylesheet" href="/sharing/common16/css/subWinBox.css"/>
<div class="tab-content-search mb10 bdColor">
    <p class="tab-tt mainBgClr"><i class="sprite sprite-search"></i><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?> ツアー検索</p>
    <?php new SearchActionForSearchBox($naigai, $rqPara, $p_hatsu, $KyotenID, $p_hatsuAry->TgDataAry[$naigai], $searchBoxtemp);//検索ボックス?>
</div>
