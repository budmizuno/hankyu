<?php
// 担当者おすすめを出力する
class setTantoshaOsusume
{
    function __construct($type){

        $this->makeHtml($type);
    }

    public function makeHtml($type)
    {
        global $osusumeCsv, $masterCsv, $senmon_func,$kyotenId,$PathSenmonCommon;

        $title = '';
        $list_id = 'tantosha_list_';
        if ($type == TOUR_TANTOSHA_OSUSUME) {
            $list_id .= 'tour';
            $title = '担当者おすすめ ' . $masterCsv[KEY_MASTER_CSV_NAME_JA] . '旅行';
        } else {
            $list_id .= 'freeplan';
            $title = '担当者おすすめ ' . $masterCsv[KEY_MASTER_CSV_NAME_JA] . 'フリープラン';
        }

        ?>
<?php // 担当者おすすめ ?>
<?php if(isset($osusumeCsv[OSUSUME_CATEGORY_NUM][$type]) && 0 < $osusumeCsv[OSUSUME_CATEGORY_NUM][$type]): ?>
    <h2 class="main-title like-main-title mainBgClr mb10 tantosha_title">
        <span class="main-title-txt"><?=$title;?></span>
    </h2>
    <div class="wr-block">
        <div class="frame swiper-container" id="<?php echo $list_id;?>">
            <ul class="clearfix tantoshaOsusume swiper-wrapper">

                <?php $num=0;?>
                <?php foreach($osusumeCsv[OSUSUME_COURSE] as $value):?>
                    <?php // 担当者おすすめ ?>
                    <?php if($value['q_group'] == $type):?>
                        <?php if(10 <= $num) break; // 10記事まで ?>
                        <?php
                            $p_hatsu_name = '';
                            if($kyotenId == 'index'){
                                $p_hatsu_name = '<span>'.$value['p_hatsu_sub_name'].'</span>';
                            }
                        ?>
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
                                    <img src="<?=$senmon_func->imagePathConvert(IMG_TYPE_TANTOSHA_OSUSUME, $value[KEY_Q_IMG_PATH], false);?>" alt="<?=$value['p_img1_caption'];?>">
                                <?php endif; ?>
                                <p class="sly3-ct"><?=$p_hatsu_name;?><?=$value['p_course_name'];?></p>
                                <p class="sly3-price"><?=$value[KEY_Q_PRICE];?></p>
                            </a>
                        </li>
                        <?php $num++;?>
                    <?php endif;?>
                <?php endforeach;?>
            </ul>
            <div class="swiper-scrollbar"></div>
        </div>
    </div>
<?php elseif($kyotenId == 'index'): // イチオシツアーがCSVにない かつ botの場合は最新２７拠点表示 ?>
    <?php
    include_once($PathSenmonCommon . 'inc/smp/saishinTop10.php');
    new setSaishinTop10($type);
    ?>
<?php endif; ?>
<?php
    }
}
?>
