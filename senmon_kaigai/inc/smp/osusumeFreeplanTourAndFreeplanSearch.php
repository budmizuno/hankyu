<?php // コース検索枠 ?>
<?php
    include_once($PathSenmonCommon . 'inc/smp/searchCourse.php');
    new setSearch(FREE_PLAN_STRING);
 ?>

<div class="wr-block mb20">
    <div class="frame swiper-container" id="osusume_freeplan_list">

        <?php if ($kyotenId == 'index'): // botの場合は絞り込みAPIから取得 ?>
            <ul class="clearfix swiper-wrapper">
                <?php
                    include_once($PathSenmonCommon . 'phpsc/mySearch.php');

                    $_REQUEST = null;
                    $_REQUEST['MyNaigai'] = $naigai;
                    $_REQUEST['p_mokuteki'] = $mokuteki;
                    $_REQUEST['p_bunrui'] ='030';
                    $_REQUEST['smpFlag'] = true;
                    $_REQUEST['ichioshi_flag'] = true;

                    $obj = new LoadAction;	//ロード時の全て
                    $resObj = $obj->dispObj;	//表示するもの全て格納

                    if(!empty($resObj['html']))
                    {
                        echo ($resObj['html']);
                    }
                ?>
            </ul>
            <div class="swiper-scrollbar"></div>
        <?php elseif(isset($osusumeCsv[OSUSUME_CATEGORY_NUM][FREEPLAN_ICHIOSHI_TOUR]) && 0 < $osusumeCsv[OSUSUME_CATEGORY_NUM][FREEPLAN_ICHIOSHI_TOUR]): ?>
            <ul class="clearfix swiper-wrapper">
                <?php $num=0;?>
                <?php foreach($osusumeCsv[OSUSUME_COURSE] as $value):?>
                    <?php // イチオシツアー ?>
                    <?php if($value['q_group'] == FREEPLAN_ICHIOSHI_TOUR):?>
                        <?php if(10 <= $num) break; // 10記事まで ?>

                        <li class="swiper-slide">
                            <?php if(!empty($value[KEY_Q_BRIGHTCOVE_ID])): // ブライトコープ動画があるなら ?>
                                <div class="block-banner-top" style="height:50vw;">
                                    <div class="block-banner-topbox"></div>
                                    <video data-video-id="<?=$value[KEY_Q_BRIGHTCOVE_ID];?>" data-account="5097191270001" data-player="default" data-embed="default" data-application-id class="video-js" controls width="100%" height="100%"></video>
                                    <script src="//players.brightcove.net/5097191270001/default_default/index.min.js"></script>
                                </div>
                            <?php elseif(!empty($value[KEY_Q_THETA_ID]) && !empty($value[KEY_Q_THETA_URL])): // シータ動画があるなら ?>
                                <div style="width:auto; height:50vw;">
                                    <div class="thum" style="height:50vw;">
                                        <blockquote data-mode="click2play" data-width="auto" data-height="100%" class="ricoh-theta-spherical-image" >
                                            <a href="<?=$value[KEY_Q_THETA_URL];?>"></a>
                                        </blockquote>
                                        <script async src="https://bud-international.theta360.biz/widgets.js" charset="utf-8"></script>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <a href="<?=$value['tour_url'];?>">
                                <?php if(empty($value[KEY_Q_BRIGHTCOVE_ID]) && empty($value[KEY_Q_THETA_ID]) && empty($value[KEY_Q_THETA_URL])): // ブライトコープ動画もシータ動画もないなら ?>
                                    <img src="<?=$senmon_func->imagePathConvert(IMG_TYPE_ICHIOSHI, $value[KEY_Q_IMG_PATH], false);?>" alt="<?=$value['p_img1_caption'];?>">
                                <?php endif; ?>
                                <p class="sly3-ct"><?php echo $value['p_course_name'];?></p>
                                <p class="sly3-price"><?=$value['p_price'];?></p>
                            </a>
                        </li>

                        <?php $num++;?>
                    <?php endif;?>
                <?php endforeach;?>
            </ul>
            <div class="swiper-scrollbar"></div>
        <?php else: // イチオシツアーがCSVにない場合最安値ツアー表示 ?>
            <?php
                include_once($PathSenmonCommon . 'phpsc/mySearch.php');

                $_REQUEST = null;
                $_REQUEST['MyNaigai'] = $naigai;
                $_REQUEST['p_mokuteki'] = $mokuteki;
                $_REQUEST['p_bunrui'] ='030';
                $_REQUEST['p_hatsu'] = $p_hatsu;
                $_REQUEST['smpFlag'] = true;
                $_REQUEST['ichioshi_flag'] = true;

                $obj = new LoadAction;	//ロード時の全て
                $resObj = $obj->dispObj;	//表示するもの全て格納
            ?>
            <?php if(!empty($resObj['html'])):?>
                <ul class="clearfix swiper-wrapper">
                    <?php echo ($resObj['html']); ?>
                </ul>
                <div class="swiper-scrollbar"></div>
            <?php else: // 最安値ツアーもないとき?>
                <script type="text/javascript">
                    // 左枠の高さを変更
                    $("#sly1").css('height','527px');
                </script>

                <?php
                    $facetPara['p_bunrui'] = '030';
                    $othertemp = $PathSenmonCommon . 'inc/smp/senmonOtherFacet360Smp.php';
                    new SearchActionForFacet($naigai, $KyotenID, $facetPara,$othertemp);//拠点のファセット数
                ?>

            <?php endif;?>
        <?php endif;?>
    </div>
</div>
<?php // 近隣から探す枠でのモーダル ?>
<div class="GlMenu js_KinrinHatsuMenuSenmon">
    <div class="GlMenuCtsSenmon">
        <div class="GlMenuIcon">
            <div class="GlMenuClose js_HatsuMenuClose"><a href="javascript:void(0)">閉じる</a></div>
        </div>
        <dl>
        <dt>出発地をお選びください</dt>
        </dl>

    </div>
</div>
<script type="text/javascript">
    // モーダルに入れる
    if(typeof $('.free_plan #other_facet').val() !== 'undefined' && 0 < $('.free_plan #other_facet').val().length){
        var other_facet_modal = $.parseJSON($('.free_plan #other_facet').val());
        $(".free_plan .js_KinrinHatsuMenuSenmon .GlMenuCtsSenmon dl").append(other_facet_modal);
    }
</script>
