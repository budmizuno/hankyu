
<script>
<!--
// トップに戻るボタン追加
$(function() {$('.containner').append('<div class="idx_ancBtn"><img src="/sharing/common16/images/idx_ancBtn.png"></div>');});
-->
</script>

<?php include_once($PathSenmonCommon . 'inc/ft_senmon_link.php');//他の方面?>

<?php include_once($PathSharing16.'/inc/temp_other.php');//リンク/企業・学校・団体のお客様へ/公式SNSアカウント?>

<?php new cmsToHtmlKanrenLinks('temp_kanrenLinks.php');//関連リンク・カテゴリーリンク 引数:テンプレ?>

<?php include_once($PathSenmonCommon . 'sharing/inc/temp_otherHatsu.php');//他の出発地の情報を見る?>

<?php include_once($PathSharing16.'inc/temp_GuestInfo.php');//お客様へのお知らせ?>

<p class="txt_d">※写真・イラストは全てイメージです。ご旅行中に必ずしも同じ角度・高度・天候での風景をご覧いただけるとは限りませんのでご了承ください</p>

<script type="text/javascript" src="/sharing/js/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php e($Sharing14);?>js/common.js"></script>

<script type="text/javascript" src="<?=$PathSenmonLink;?>js/category.js"></script>
<script type="text/javascript" src="<?=$PathSenmonLink;?>js/jquery.bxslider.js"></script>
<script type="text/javascript" src="<?=$PathSenmonLink;?>js/plugins.js"></script>
<script type="text/javascript" src="<?=$PathSenmonLink;?>js/sly.js"></script>
<script type="text/javascript" src="<?=$PathSenmonLink;?>js/clicktag.js"></script>
<script type="text/javascript" src="<?=$PathSenmonLink;?>js/common.js"></script>
<script type="text/javascript" src="/sharing/js/jquery.effects.core.js"></script>

<?php // 方面ページだけの読み込み ?>
<?php if($categoryType == CATEGORY_TYPE_DEST):?>
    <script type="text/javascript" src="/attending/kokunai/jiyuhjin/js/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="/attending/kokunai/jiyuhjin/js/jquery.flexslider.js"></script>
    <!-- <script type="text/javascript" src="/attending/kokunai/jiyuhjin/js/index.js"></script> -->
    <script type="text/javascript" src="/attending/senmon_kokunai/js/kokunai/jiyuhjin/js/index.js"></script>
    <!-- <script type="text/javascript" src="/attending/kokunai/jiyuhjin/js/tyo.js"></script> -->
    <script type="text/javascript" src="/attending/guide/sharing/js/jquery_img.js"></script>
    <script>
    $(function() {$(".containner a.menu-link[href^=#]").unbind();});
    </script>
<?php endif;?>

<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1008215138;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "qsfvCN69zwIQ4sjg4AM";
var google_conversion_value = 0;
/* ]]> */
</script>
<div style="display:none;">
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:none;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/1008215138/?label=qsfvCN69zwIQ4sjg4AM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
</div>
