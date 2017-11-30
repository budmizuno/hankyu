<?php

if (empty($SettingData->ArticleConfig) || !is_array($SettingData->ArticleConfig)) {
    $SettingData->ArticleConfig = array();
}
if (!defined('KEY_SENMON_BUS_TOUR')) {
    define('KEY_SENMON_BUS_TOUR','senmon_bus_tour1');
}
if (!defined('KEY_SENMON_JIYUJIN_TOUR')) {
    define('KEY_SENMON_JIYUJIN_TOUR','senmon_jiyujin_tour1');
}

//
$option_jiyujin_tour = null;
$option_bus_tour = null;

// 専門店マスタに現地発着ツアーのデータがある場合
if (strlen($masterCsv[KEY_MASTER_CSV_GENCHI_HACCHAKU_TOUR_KYOTEN]) > 0
        && strlen($masterCsv[KEY_MASTER_CSV_GENCHI_HACCHAKU_TOUR_CSV]) > 0) {
    $option_jiyujin_tour = array(
            'kyoten_di' => $masterCsv[KEY_MASTER_CSV_GENCHI_HACCHAKU_TOUR_KYOTEN],
            'blog_path' => $masterCsv[KEY_MASTER_CSV_GENCHI_HACCHAKU_TOUR_CSV],
            'group_id' => 3,
            'article_title' => $masterCsv[KEY_MASTER_CSV_GENCHI_HACCHAKU_TOUR_TILTE],
            'file_type' => 'csv',
    );
    $SettingData->ArticleConfig[KEY_SENMON_JIYUJIN_TOUR] = $option_jiyujin_tour;
}
//var_dump($SettingData->ArticleConfig);
// 専門店マスタにバスツアーのデータがある場合
if (strlen($masterCsv[KEY_MASTER_CSV_BUS_TOUR_KYOTEN]) > 0
        && strlen($masterCsv[KEY_MASTER_CSV_BUS_TOUR_CSV]) > 0) {
    $option_bus_tour = array(
            'kyoten_di' => $masterCsv[KEY_MASTER_CSV_BUS_TOUR_KYOTEN],
            'blog_path' => $masterCsv[KEY_MASTER_CSV_BUS_TOUR_CSV],
            'group_id' => 1,
            'article_title' => $masterCsv[KEY_MASTER_CSV_BUS_TOUR_TILTE],
            'file_type' => 'csv',
    );
    $SettingData->ArticleConfig[KEY_SENMON_BUS_TOUR] = $option_bus_tour;
}

$jiyujin_link_url = '/kokunai/jiyuhjin/';
switch ($senmonNameEnLower) {
    case 'hokkaido':
        $jiyujin_link_url .= 'spk.php';
        break;
    case 'tohoku':
        $jiyujin_link_url .= 'sdj.php';
        break;
    case 'kanto':
        $jiyujin_link_url .= 'tyo.php';
        break;
    case 'kansai':
        $jiyujin_link_url .= 'osa.php';
        break;
    case 'tokyo':
        $jiyujin_link_url .= 'tyo.php';
        break;
    case 'kanagawa':
        $jiyujin_link_url .= 'tyo.php';
        break;
    case 'osaka':
        $jiyujin_link_url .= 'osa.php';
        break;
    case 'hyogo':
        $jiyujin_link_url .= 'osa.php';
        break;
    case 'kyoto':
        $jiyujin_link_url .= 'osa.php';
        break;
    case 'aichi':
        $jiyujin_link_url .= 'ngo.php';
        break;
    case 'fukuoka':
        $jiyujin_link_url .= 'fuk.php';
        break;
    case 'hiroshima':
        $jiyujin_link_url .= 'hij.php';
        break;
}


?>

<?php include_once($PathSenmonCommon . 'phpsc/view.php'); ?>

<div class="box_genchihacchaku">
    <h2 class="list-inline main-title mainBgClr genchi-title">
        <span class="mid main-title-txt">現地集合解散ツアー</span>
    </h2>
    <div class="tour-info">
        <p>思い立った時にお気軽にご参加いただける、日本各地のおすすめ観光地をめぐる魅力あるコースを中心にご案内しています。文学・歴史・社会、体験などをテーマに普段行きづらい場所も専門ガイドがご案内します。</p>
        <div class="border-orange mt10 mb10"></div>
    </div>
    <div class="OsusumeTour contentTourDiv">

        <?php //現地発着ツアー枠 ?>
        <?php $display_tour_num = 0;?>
        <?php cmsToHtml(KEY_SENMON_JIYUJIN_TOUR,'','','','TempTourPhoto01.php',$display_tour_num); /*写真付き*/ ?>

        <?php if(5 <= $display_tour_num): // 5商品以上なら?>
            <div class="clear mt40">
                <a href="<?php echo $jiyujin_link_url;?>"><p class="bg-yellow btn-heritage right btn-view center btn-heritage-link list-inline btn-view-link">
                        <span class="txt-bold mid">現地集合解散ツアーをもっと見る</span>
                        <i class="icon icon-view mid"></i>
                </p>
                </a>
            </div>
        <?php endif;?>
    </div>
</div>
<div class="box_bus_tour">
    <h2 class="list-inline main-title mt20 mainBgClr bus-title">
        <span class="mid main-title-txt"><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?>発着バスツアー</span>
    </h2>
    <div class="BusTour contentTourDiv">

        <?php $display_bus_tour_num = 0;?>
        <?php cmsToHtml(KEY_SENMON_BUS_TOUR,'','','','TempPcTourPhoto01.php',$display_bus_tour_num); /*ツアー*/ ?>

        <?php if(5 <= $display_bus_tour_num):// 5商品以上なら?>
            <div class="clear mt40">
                <a href="<?php echo $masterCsv['bus_tour_url'];?>"><p class="bg-yellow btn-heritage right btn-view center btn-heritage-link list-inline btn-view-link">
                    <span class="txt-bold mid"><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?>発着のバスツアーをもっと見る</span>
                    <i class="icon icon-view mid"></i>
                </p>
                </a>
            </div>
        <?php endif;?>
    </div>
</div>


<?php if ($display_tour_num <= 0) :?>
<script>
<!--
$(function() {
    if ($('.box_genchihacchaku').length > 0) {
        $('.box_genchihacchaku').hide();
    }
});
-->
</script>
<?php endif;?>
<?php if ($display_bus_tour_num <= 0) :?>
<script>
<!--
$(function() {
    if ($('.box_bus_tour').length > 0) {
        $('.box_bus_tour').hide();
    }
});
-->
</script>
<?php endif;?>

<?php if ($display_tour_num <= 0 && $display_bus_tour_num <= 0) :?>
<script>
<!--
$(function() {
    if ($('#tab_ct_genchihacchaku').length > 0) {
        $('#tab_ct_genchihacchaku').hide();
    }
    if ($('.tab_genchihacchaku').length > 0) {
        $('.tab_genchihacchaku').hide();
    }
    <?php if ($kyotenId != 'index') :?>
    if ($('.tab-menu').length > 0) {
        $('.tab-menu').removeClass('tab-menu-3');
        $('.tab-menu').addClass('tab-menu-2');
    }
    <?php endif;?>
});
-->
</script>
<?php else :?>
<script>
<!--
$(function() {
    // 現地発着タブがないなら
    if ($('.tab_genchihacchaku').length == 0) {
        <?php if(0 < $display_tour_num):  ?>
            $("#tab_ct_tour .box_genchihacchaku").show();
        <?php endif; ?>
        <?php if(0 < $display_bus_tour_num):  ?>
            $("#tab_ct_tour .box_bus_tour").show();
        <?php endif; ?>
        if (0 < $('#tab_ct_genchihacchaku').length) $('#tab_ct_genchihacchaku').hide();
    }
    else{
        <?php if(0 < $display_tour_num):  ?>
            $("#tab_ct_tour .box_genchihacchaku").hide();
        <?php endif; ?>
        <?php if(0 < $display_bus_tour_num):  ?>
            $("#tab_ct_tour .box_bus_tour").hide();
        <?php endif; ?>
    }
});
-->
</script>
<?php endif;?>
