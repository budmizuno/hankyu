<div class="clear">
    <div class="right">
        <?php include_once($PathSenmonCommon . 'sharing/inc/senmonSearchD360.php');//検索	?>
        <?php include_once($PathSenmonCommon . 'inc/temp_courseNoBlogTop.php');//コース番号・新着?>
    </div>
    <?php // イチオシツアー 27拠点分のCSVをなめて最新更新順10本を表示
        include_once($PathSenmonCommon . 'inc/saishinTop10.php');
        $setSaishinTop10 = new setSaishinTop10(TOUR_ICHIOSHI_TOUR);
        // 最安値ツアーを表示
        if ($setSaishinTop10->ichioshi_flg === false) {
    ?>
    <div class="tab-content-slider right">
        <div class="frame mb10 slider-sly" id="sly2">
            <ul class="clearfix osusume">
                <?php
                    include_once($PathSenmonCommon . 'phpsc/mySearch.php');

                    $_REQUEST = null;
                    $_REQUEST['MyNaigai'] = $naigai;
                    $_REQUEST['p_mokuteki'] = $mokuteki;

                    $obj = new LoadAction;	//ロード時の全て
                    $resObj = $obj->dispObj;	//表示するもの全て格納

                    if(!empty($resObj['html']))
                    {
                        echo ($resObj['html']);
                    }
                ?>
            </ul>
        </div>
        <div class="btn-group">
            <a href="#" class="prev"><i class="sprite sprite-slider-prev"></i></a>
            <a href="#" class="next"><i class="sprite sprite-slider-next"></i></a>
        </div>
        <ul class="pages osusume-pages"></ul>
    </div>
    <?php
        }
    ?>
</div>
