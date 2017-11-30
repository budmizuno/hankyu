<?php // 売れ筋ランキング ?>
<?php if(isset($osusumeCsv[OSUSUME_CATEGORY_NUM][TOUR_URESUZI_RANKING]) && 2 < $osusumeCsv[OSUSUME_CATEGORY_NUM][TOUR_URESUZI_RANKING]): ?>
    <h2 class="list-inline main-title mb20 mt20 mainBgClr uresuzi_title">
        <span class="mid main-title-txt"><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?>ツアー 売れ筋ランキング</span>
    </h2>
    <div class="wr-block">
        <div class="frame slider-sly slider-sly-small">
            <ul class="clearfix uresuzi">

                <?php $num=1; ?>
                <?php foreach($osusumeCsv[OSUSUME_COURSE] as $value):?>
                    <?php // 売れ筋 ?>
                    <?php if($value[KEY_Q_GROUP] == TOUR_URESUZI_RANKING):?>
                        <?php if(10 < $num) break; // 10記事まで ?>
                        <?php
                            $iconClass= "icon icon-num icon-num".$num;
                            if($num == 1)
                            {
                                $iconClass= "icon icon-num icon-num1-small";
                            }
                        ?>

                        <li class="pos-rel">
                            <?php if(!empty($value[KEY_Q_BRIGHTCOVE_ID])): // ブライトコープ動画があるなら ?>
                                <div class="block-banner-top">
                                    <div class="block-banner-topbox"></div>
                                    <video data-video-id="<?=$value[KEY_Q_BRIGHTCOVE_ID];?>" data-account="5097191270001" data-player="default" data-embed="default" data-application-id class="video-js" controls width="200px" height="150px"></video>
                                    <script src="//players.brightcove.net/5097191270001/default_default/index.min.js"></script>
                                </div>
                            <?php elseif(!empty($value[KEY_Q_THETA_ID]) && !empty($value[KEY_Q_THETA_URL])): // シータ動画があるなら ?>
                                <div style="width:200px; height:150px;">
                                    <div class="thum">
                                        <blockquote data-mode="click2play" data-width="200px" data-height="150px" class="ricoh-theta-spherical-image" >
                                            <a href="<?=$value[KEY_Q_THETA_URL];?>"></a>
                                        </blockquote>
                                        <script async src="https://bud-international.theta360.biz/widgets.js" charset="utf-8"></script>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <a href="<?=$value[KEY_TOUR_URL];?>">
                                <?php if(empty($value[KEY_Q_BRIGHTCOVE_ID]) && empty($value[KEY_Q_THETA_ID]) && empty($value[KEY_Q_THETA_URL])): // ブライトコープ動画もシータ動画もないなら ?>
                                    <img src="<?=$senmon_func->imagePathConvert(IMG_TYPE_URESUZI_RANKING, $value[KEY_Q_IMG_PATH], false);?>" alt="<?=$value[KEY_Q_IMG_CAPTION];?>" class="img_uresuzi">
                                <?php endif; ?>
                                <i class="<?=$iconClass;?>"></i>
                                <p class="sly3-ct"><?php if($kyotenId == 'index') echo '<span>'.$value['p_hatsu_sub_name'].'</span>'; ?><?=$value[KEY_Q_COURSE_NAME];?></p>
                                <p class="sly3-price"><?=$value['p_price']?></p>
                            </a>
                        </li>
                        <?php $num++; ?>
                <?php endif;?>
                <?php endforeach;?>
            </ul>
        </div>
        <div class="btn-group">
            <a href="#" class="prev"><i class="sprite sprite-slider-prev"></i></a>
            <a href="#" class="next"><i class="sprite sprite-slider-next"></i></a>
        </div>
        <ul class="pages uresuzi-pages"></ul>
    </div>
<?php elseif((!isset($osusumeData[OSUSUME_CATEGORY_NUM][TOUR_URESUZI_RANKING]) || 0 == $osusumeData[OSUSUME_CATEGORY_NUM][TOUR_URESUZI_RANKING]) && !in_array($kyotenId, $uresuzi_ac_off_kyoten)): ?>
<?php // AC（アクティブコア）のタグを貼り付け ?>
<script language="JavaScript" src="/sharing/common16/js/ppz.js" charset="UTF-8"></script>
<script language="JavaScript" src="<?=$PathSenmonLink;?>js/ppz_draw.js" charset="UTF-8"></script>

<h2 class="list-inline main-title mb20 mt20 mainBgClr uresuzi_title" style="display: none;">
    <span class="mid main-title-txt"><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?>ツアー 売れ筋ランキング</span>
</h2>
<!-- レコメンド表示場所 -->
<div id="ppz_recommend_pckokunaisenmon03" class="mb20"></div>
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
    var ppz_pckokunaisenmon03 = new _PPZ();
    ppz_pckokunaisenmon03.cid = 21017;
    ppz_pckokunaisenmon03.rid = 28;
    ppz_pckokunaisenmon03.customer_id = '<?php e($customerId); ?>'; //顧客IDを代入

    ppz_pckokunaisenmon03.v02 = '0';//内外区分：国内
    ppz_pckokunaisenmon03.v04 = '<?php e($masterCsv[KEY_MASTER_CSV_DEST]); ?>';//目的地（方面）フラグを確認
    ppz_pckokunaisenmon03.v08 = "<?php e($recoHatsuComma);?>"; // ↑↑myHatsuに記載している出発地フラグをカンマ区切りにて記載して下さい。
    ppz_pckokunaisenmon03.v11 = '<?php e($masterCsv[KEY_MASTER_CSV_COUNTRY_LARGE]); ?>';//目的地（都道府県）フラグを確認

    ppz_pckokunaisenmon03.rows = 10;                               //表示したいMAX件数を設定(最大20件)
    ppz_pckokunaisenmon03.cb = 'ppz_pckokunaisenmon03_ranking';               //ここの関数でレコメンド表示を作成してください。(ppz_draw.js)
    ppz_pckokunaisenmon03.div_id = 'ppz_recommend_pckokunaisenmon03';      //レコメンド表示を行うタグのIDを指定(divのIDと一致させます)
    ppz_pckokunaisenmon03.alt_html = null;
    ppz_pckokunaisenmon03.request();
</script>
<?php endif; ?>
