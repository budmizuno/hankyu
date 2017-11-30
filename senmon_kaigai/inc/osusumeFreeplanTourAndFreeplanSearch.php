<div class="clear">
    <div id="rBox"></div>
    <div id="SubWinBox-Fp" class="SubWinBox"></div>
    <div id="overlay"></div>
    <?php include_once($PathSenmonCommon . 'inc/temp_searchbox.php');//検索?>
    <div class="tab-content-slider right">
        <div class="frame mb10 slider-sly" id="sly2">
            <ul class="clearfix osusume">
                <?php if(isset($osusumeCsv[OSUSUME_CATEGORY_NUM][FREEPLAN_ICHIOSHI_TOUR]) && 0 < $osusumeCsv[OSUSUME_CATEGORY_NUM][FREEPLAN_ICHIOSHI_TOUR]): ?>
                    <?php $num=0;?>
                    <?php foreach($osusumeCsv[OSUSUME_COURSE] as $value):?>
                        <?php // イチオシツアー ?>
                        <?php if($value['q_group'] == FREEPLAN_ICHIOSHI_TOUR):?>
                            <?php if(10 <= $num) break; // 10記事まで ?>
                            <li>
                                <?php if(!empty($value[KEY_Q_BRIGHTCOVE_ID])): // ブライトコープ動画があるなら ?>
                                    <div class="block-banner-top">
                                        <div class="block-banner-topbox"></div>
                                        <video data-video-id="<?=$value[KEY_Q_BRIGHTCOVE_ID];?>" data-account="5097191270001" data-player="default" data-embed="default" data-application-id class="video-js" controls width="380px" height="285px"></video>
                                        <script src="//players.brightcove.net/5097191270001/default_default/index.min.js"></script>
                                    </div>
                                <?php elseif(!empty($value[KEY_Q_THETA_ID]) && !empty($value[KEY_Q_THETA_URL])): // シータ動画があるなら ?>
                                    <div style="width:380px; height:285px;">
                                        <div class="thum">
                                            <blockquote data-mode="click2play" data-width="380px" data-height="285px" class="ricoh-theta-spherical-image" >
                                                <a href="<?=$value[KEY_Q_THETA_URL];?>"></a>
                                            </blockquote>
                                            <script async src="https://bud-international.theta360.biz/widgets.js" charset="utf-8"></script>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <a href="<?=$value['tour_url'];?>">
                                    <?php if(empty($value[KEY_Q_BRIGHTCOVE_ID]) && empty($value[KEY_Q_THETA_ID]) && empty($value[KEY_Q_THETA_URL])): // ブライトコープ動画もシータ動画もないなら ?>
                                        <img src="<?=$senmon_func->imagePathConvert(IMG_TYPE_ICHIOSHI, $value[KEY_Q_IMG_PATH], false);?>" alt="<?=$value['p_img1_caption'];?>" class="img_ichioshi">
                                    <?php endif; ?>
                                    <div class="slider-wr-content">
                                        <p class="slider-title">
                                            <?php echo stringControl($value['p_course_name'],STRING_LIMIT_ICHIOSHI_COURSE_NAME);?>
                                        </p>
                                        <p class="slider-content">
                                            <?php echo stringControl($value['p_point1'],STRING_LIMIT_ICHIOSHI_CAPTION);?>
                                        </p>
                                        <p class="slider-price"><?=$value['p_price'];?></p>
                                    </div>
                                </a>
                            </li>
                            <?php $num++;?>
                        <?php endif;?>
                    <?php endforeach;?>
                <?php else: // イチオシツアーがCSVにない場合最安値ツアー表示 ?>
                    <?php
                        include_once($PathSenmonCommon . 'phpsc/mySearch.php');

                        $_REQUEST = null;
                        $_REQUEST['MyNaigai'] = $naigai;
                        $_REQUEST['p_mokuteki'] = $mokuteki;
                        $_REQUEST['p_bunrui'] ='030';
                        $_REQUEST['p_hatsu'] = $p_hatsu;

                        $obj = new LoadAction;	//ロード時の全て
                        $resObj = $obj->dispObj;	//表示するもの全て格納

                    ?>
                    <?php if(!empty($resObj['html'])):?>
                        <?php echo ($resObj['html']); ?>
                    <?php else: // 最安値ツアーもないとき?>
                        <script type="text/javascript">
                            // 左枠の高さを変更
                            $("#tab_ct_freeplan #sly1").css('height','527px');
                        </script>

                        <?php
                            $facetPara['p_bunrui'] = '030';
                            $othertemp = $PathSenmonCommon . 'sharing/inc/senmonOtherFacet360.php';
                            new SearchActionForFacet($naigai, $KyotenID, $facetPara,$othertemp);//拠点のファセット数
                        ?>

                    <?php endif;?>
                <?php endif;?>
            </ul>
        </div>
        <div class="btn-group">
            <a href="#" class="prev"><i class="sprite sprite-slider-prev"></i></a>
            <a href="#" class="next"><i class="sprite sprite-slider-next"></i></a>
        </div>
        <ul class="pages osusume-pages"></ul>
    </div>
    <?php // 近隣から探す枠でのモーダル ?>
    <div class="otherFacetEtcSelectpanel">
        <div class="otherFacetEtcPanel FClear" id="otherFacetEtc">
        </div>
    </div>
    <script type="text/javascript">
        // モーダルに入れる
        if(typeof $('#tab_ct_freeplan #other_facet').val() !== 'undefined' && 0 < $('#tab_ct_freeplan #other_facet').val().length){
            var other_facet_modal = $.parseJSON($('#tab_ct_freeplan #other_facet').val());
            $("#tab_ct_freeplan #otherFacetEtc").html(other_facet_modal);
        }
    </script>
</div>
