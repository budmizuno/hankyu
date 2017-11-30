<div class="clear">
    <div id="rBox"></div>
    <div id="SubWinBox-Fp" class="SubWinBox"></div>
    <div id="overlay"></div>
    <?php include_once($PathSenmonCommon . 'inc/temp_searchbox.php');//検索?>
    <div class="tab-content-slider right">
        <div class="frame mb10 slider-sly" id="sly2">
            <ul class="clearfix osusume">
                <?php
                    include_once($PathSenmonCommon . 'phpsc/mySearch.php');

                    $_REQUEST = null;
                    $_REQUEST['MyNaigai'] = $naigai;
                    $_REQUEST['p_mokuteki'] = $mokuteki;
                    $_REQUEST['p_bunrui'] ='030';

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
</div>
