<?php // クリスタルハート?>
<?php if(isset($osusumeCsv[OSUSUME_CATEGORY_NUM][TOUR_CRISTAL_HEART]) && 0 < $osusumeCsv[OSUSUME_CATEGORY_NUM][TOUR_CRISTAL_HEART]): ?>

    <div class="group-crystal border-cccccc mt40 pos-rel">
       <?php
           // クリスタルハートの説明部分
           include_once($PathSenmonCommon . 'inc/brand/cristal_heart_head.php');
       ?>
        <ul class="group-crystal-list clear">

            <?php foreach($osusumeCsv[OSUSUME_COURSE] as $value):?>

                <?php // クリスタルハート ?>
                <?php if($value['q_group'] == TOUR_CRISTAL_HEART):?>
                    <li class="top feature-list">
                        <?php if(!empty($value[KEY_Q_BRIGHTCOVE_ID])): // ブライトコープ動画があるなら ?>
                            <div class="block-banner-top">
                                <div class="block-banner-topbox"></div>
                                <video data-video-id="<?=$value[KEY_Q_BRIGHTCOVE_ID];?>" data-account="5097191270001" data-player="default" data-embed="default" data-application-id class="video-js" controls width="290px" height="225px"></video>
                                <script src="//players.brightcove.net/5097191270001/default_default/index.min.js"></script>
                            </div>
                        <?php elseif(!empty($value[KEY_Q_THETA_ID]) && !empty($value[KEY_Q_THETA_URL])): // シータ動画があるなら ?>
                            <div style="width:290px; height:225px;">
                                <div class="thum">
                                    <blockquote data-mode="click2play" data-width="290px" data-height="225px" class="ricoh-theta-spherical-image" >
                                        <a href="<?=$value[KEY_Q_THETA_URL];?>"></a>
                                    </blockquote>
                                    <script async src="https://bud-international.theta360.biz/widgets.js" charset="utf-8"></script>
                                </div>
                            </div>
                        <?php endif; ?>
                        <a href="<?=$value['tour_url'];?>">
                            <?php if(empty($value[KEY_Q_BRIGHTCOVE_ID]) && empty($value[KEY_Q_THETA_ID]) && empty($value[KEY_Q_THETA_URL])): // ブライトコープ動画もシータ動画もないなら ?>
                                <img src="<?=$senmon_func->imagePathConvert(IMG_TYPE_BRAND, $value[KEY_Q_IMG_PATH], false);?>" alt="<?=$value['p_img1_caption'];?>">
                            <?php endif; ?>
                            <p class="font-14 txt-bold mt10 mb10">
                                <?php echo $value['p_course_name'];?>
                            </p>
                            <p class="mb10 feature-list-content">
                                <?php echo $value['p_point1'];?>
                            </p>
                            <p class="font-16 color-red txt-bold"><?=$value['p_price']?></p>
                        </a>
                    </li>

            <?php endif;?>
            <?php endforeach;?>
        </ul>
    </div>
<? endif; ?>
<!--group crystal-->

<!--フレンドツアー-->
<?php if(isset($osusumeCsv[OSUSUME_CATEGORY_NUM][TOUR_FRIEND_TOUR]) && 0 < $osusumeCsv[OSUSUME_CATEGORY_NUM][TOUR_FRIEND_TOUR]): ?>
    <div class="group-crystal border-cccccc mt40 pos-rel group-friend">
        <?php
            // フレンドツアーの説明部分
            include_once($PathSenmonCommon . 'inc/brand/friend_tour_head.php');
        ?>
        <ul class="group-crystal-list clear">

            <?php foreach($osusumeCsv[OSUSUME_COURSE] as $value):?>
                <?php // フレンドツアー ?>
                <?php if($value['q_group'] == TOUR_FRIEND_TOUR):?>
                    <li class="top feature-list">
                        <?php if(!empty($value[KEY_Q_BRIGHTCOVE_ID])): // ブライトコープ動画があるなら ?>
                            <div class="block-banner-top">
                                <div class="block-banner-topbox"></div>
                                <video data-video-id="<?=$value[KEY_Q_BRIGHTCOVE_ID];?>" data-account="5097191270001" data-player="default" data-embed="default" data-application-id class="video-js" controls width="290px" height="225px"></video>
                                <script src="//players.brightcove.net/5097191270001/default_default/index.min.js"></script>
                            </div>
                        <?php elseif(!empty($value[KEY_Q_THETA_ID]) && !empty($value[KEY_Q_THETA_URL])): // シータ動画があるなら ?>
                            <div style="width:290px; height:225px;">
                                <div class="thum">
                                    <blockquote data-mode="click2play" data-width="290px" data-height="225px" class="ricoh-theta-spherical-image" >
                                        <a href="<?=$value[KEY_Q_THETA_URL];?>"></a>
                                    </blockquote>
                                    <script async src="https://bud-international.theta360.biz/widgets.js" charset="utf-8"></script>
                                </div>
                            </div>
                        <?php endif; ?>
                        <a href="<?=$value['tour_url'];?>">
                            <?php if(empty($value[KEY_Q_BRIGHTCOVE_ID]) && empty($value[KEY_Q_THETA_ID]) && empty($value[KEY_Q_THETA_URL])): // ブライトコープ動画もシータ動画もないなら ?>
                                <img src="<?=$senmon_func->imagePathConvert(IMG_TYPE_BRAND, $value[KEY_Q_IMG_PATH], false);?>" alt="<?=$value['p_img1_caption'];?>">
                            <?php endif; ?>
                            <p class="font-14 txt-bold mt10 mb10">
                                <?php echo $value['p_course_name'];?>
                            </p>
                            <p class="mb10 feature-list-content">
                                <?php echo $value['p_point1'];?>
                            </p>
                            <p class="font-16 color-red txt-bold"><?=$value['p_price']?></p>
                        </a>
                    </li>
            <?php endif;?>
            <?php endforeach;?>
        </ul>
    </div>
<?php endif;?>
<!--group friend-->
