<?php // 売れ筋ランキング ?>
<?php if(isset($osusumeCsv[OSUSUME_CATEGORY_NUM][TOUR_URESUZI_RANKING]) && 2 < $osusumeCsv[OSUSUME_CATEGORY_NUM][TOUR_URESUZI_RANKING]): ?>
    <h2 class="main-title like-main-title mainBgClr mb10 uresuzi_title">
        <span class="main-title-txt"><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?>ツアー売れ筋ランキング</span>
    </h2>
    <div class="wr-block mb20">
        <div class="frame swiper-container" id="uresuzi_list">
            <ul class="clearfix uresuzi swiper-wrapper">

                <?php $num=1; ?>
                <?php foreach($osusumeCsv[OSUSUME_COURSE] as $value):?>
                    <?php // 売れ筋 ?>
                    <?php if($value['q_group'] == TOUR_URESUZI_RANKING):?>
                        <?php if(10 < $num) break; // 10記事まで ?>
                        <?php
                            $iconClass= "icon icon-num icon-num".$num;
                            if($num == 1)
                            {
                                $iconClass= "icon icon-num icon-num1-small";
                            }
                        ?>
                        <li class="pos-rel swiper-slide">
                            <a href="<?=$value['tour_url'];?>">
                                <i class="<?=$iconClass;?>"></i>
                                <?php if(!empty($value[KEY_Q_BRIGHTCOVE_ID])): // ブライトコープ動画があるなら ?>
                                    <div class="block-banner-top" style="height:50vw;">
                                        <div class="block-banner-topbox"></div>
                                        <video data-video-id="<?=$value[KEY_Q_BRIGHTCOVE_ID];?>" data-account="5097191270001" data-player="default" data-embed="default" data-application-id class="video-js" controls width="100%" height="100%" autoplay loop muted></video>
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
                                <?php if(empty($value[KEY_Q_BRIGHTCOVE_ID]) && empty($value[KEY_Q_THETA_ID]) && empty($value[KEY_Q_THETA_URL])): // ブライトコープ動画もシータ動画もないなら ?>
                                    <img src="<?=$senmon_func->imagePathConvert(IMG_TYPE_URESUZI_RANKING, $value[KEY_Q_IMG_PATH], false);?>" alt="<?=$value['p_img1_caption'];?>">
                                <?php endif; ?>
                                <p class="sly3-ct"><?php if($kyotenId == 'index') echo '<span>'.$value['p_hatsu_sub_name'].'</span>'; ?><?=$value['p_course_name'];?></p>
                                <p class="sly3-price"><?=$value['p_price']?></p>
                            </a>
                        </li>
                        <?php $num++; ?>
                <?php endif;?>
                <?php endforeach;?>
            </ul>
            <div class="swiper-scrollbar"></div>
        </div>
    </div>
<?php elseif((!isset($osusumeCsv[OSUSUME_CATEGORY_NUM][TOUR_URESUZI_RANKING]) || 0 == $osusumeCsv[OSUSUME_CATEGORY_NUM][TOUR_URESUZI_RANKING]) && !in_array($kyotenId, $uresuzi_ac_off_kyoten)): ?>
<?php // AC（アクティブコア）のタグを貼り付け ?>
<script language="JavaScript" src="/sharing/common16/js/ppz_sp.js"></script>
<script language="JavaScript" src="<?=$PathSenmonLink;?>js/ppz_draw_sp.js"></script>  <!--//表示組み立てを作成します。-->

<h2 class="main-title like-main-title mainBgClr mb10 uresuzi_title" style="display: none;">
    <span class="main-title-txt"><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?>ツアー売れ筋ランキング</span>
</h2>
<!-- レコメンド表示場所 -->
<div id="ppz_recommend_spkokunaisenmon03"></div>
<!-- レコメンド表示場所ここまで -->

<?php
$customerId= "";
if(isset($_COOKIE['LCNU'])){
    if(!empty($_COOKIE['LCNU'])){
        $customerId = $_COOKIE['LCNU'];
        $customerId = base64_decode($customerId);
    }
}
?>

<script type="text/javascript">
    var ppz_recommend_myHatsu = "<?php e($recoHatsu);?>";
    var ppz_recommend_myHatsuSub = "";
    var ppz_spkokunaisenmon03 = new _PPZ();
    ppz_spkokunaisenmon03.cid = 21203;
    ppz_spkokunaisenmon03.rid = 16;
    ppz_spkokunaisenmon03.customer_id = '<?php e($customerId); ?>'; //顧客IDを代入

    ppz_spkokunaisenmon03.v02 = '0';//内外区分：国内
    ppz_spkokunaisenmon03.v04 = '<?php e($masterCsv[KEY_MASTER_CSV_DEST]); ?>';//目的地（方面）フラグを確認
    ppz_spkokunaisenmon03.v08 = "<?php e($recoHatsuComma);?>"; // ↑↑myHatsuに記載している出発地フラグをカンマ区切りにて記載して下さい。
    ppz_spkokunaisenmon03.v11 = '<?php e($masterCsv[KEY_MASTER_CSV_COUNTRY_LARGE]); ?>';//目的地（都道府県）フラグを確認

    ppz_spkokunaisenmon03.rows = 10;                               //表示したいMAX件数を設定(最大20件)
    ppz_spkokunaisenmon03.cb = 'ppz_spkokunaisenmon03_ranking';               //ここの関数でレコメンド表示を作成してください。(ppz_draw.js)
    ppz_spkokunaisenmon03.div_id = 'ppz_recommend_spkokunaisenmon03';      //レコメンド表示を行うタグのIDを指定(divのIDと一致させます)
    ppz_spkokunaisenmon03.alt_html = '';
    ppz_spkokunaisenmon03.request();
</script>

<?php endif; ?>
