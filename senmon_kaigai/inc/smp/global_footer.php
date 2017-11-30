
<?php include_once($PathSenmonCommon . 'inc/smp/ft_senmon_link.php');//他の方面?>

<?php include_once($PathSenmonCommon.'inc/smp/tempsmp/tempsmp_company.php');//企業・学校・団体のお客様?>
<?php include_once($PathSenmonCommon.'inc/smp/tempsmp/tempsmp_sns.php');//SNS?>
<?php include_once($PathSenmonCommon.'inc/smp/tempsmp/tempsmp_mailclub.php');//メルマガ?>
<?php include_once($PathSenmonCommon.'inc/smp/tempsmp/tempsmp_news.php');//ニュースリリース?>

<p class="btn_pc_jump"><a href="<?php e($top->realUrlForPC);?>"><span class="text">PCサイトを見る<img src="/sharing/common16/images/smp/btn_pc_jump_img.jpg" alt="PCサイトを見る"></span></a></p>

<?php include_once($PathSharing16.'inc/temp_smpGuestInfo.php');//お客様へのお知らせ?>

<!--jsここから-->
<script type="text/javascript" src="<?=$PathSenmonLink;?>js/smp/common14.js"></script>
<link type="text/css" rel="stylesheet" href="/sharing/common16/css/swiper.css" />
<!--jsここまで-->
