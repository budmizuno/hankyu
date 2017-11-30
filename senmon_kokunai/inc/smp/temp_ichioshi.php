<!-- イチオシ -->
<?php /*
<?php if(!empty($html)):?>
	<?php if($kyotenId != 'index'): //拠点用 ?>
		<div class="idx_box02 recommended mb30 OnFR">
			<h2 class="idx_icn08 idx_bar01">担当者イチオシ国内旅行</h2>
			<div class="idx_box02 recommended_d">
			<?php echo $html; ?>
			</div>
		</div>
	<?php else: //bot用　?>
		<div class="idx_box03 recommended mb30 FClear">
			<h2 class="idx_icn08 idx_bar01">担当者イチオシ国内旅行<span>旬の旅行をご紹介♪</span></h2>
			<div class="idx_box03 recommended_i recommended_i_bot">
			<?php echo $html; ?>
			</div>
		</div>
	<?php endif;?>
<?php endif;?>
<!-- イチオシ -->
*/ ?>

            <div id="ppz_recommend_sptop01">
                <section class="blue rcmnd4u">
                    <h2>あなたへのおすすめツアー</h2>
                    <div id="TourSlide" class="rcmndTourBox swiper-container swiper-container-horizontal">
                        <ul class="swiper-wrapper" style="transition-duration: 0ms; transform: translate3d(-138px, 0px, 0px);">
                        <?php echo $html; ?>
<!--                             
                            <li class="tour swiper-slide swiper-slide-prev" style="margin-right: 10px;">
                                <a href="javascript:void(0)" onclick="ppz_sptop01._click('http://www.hankyu-travel.com/tour/detail_d.php?p_course_id=7J055&amp;p_hei=60','751607')">
                                    <p>
                                        <img src="../../senmon_kokunai/images/smp/pic_re_tour.jpg" alt="往復JR新幹線で行く！Go!Go！広島フリープラン　日帰り">
                                    </p>
                                    <dl>
                                        <dt>往復JR新幹線で行く！Go!Go！広島フリープラン　日帰り</dt>
                                        <dd>5,550円</dd>
                                    </dl>
                                </a>
                            </li>
                            <li class="tour swiper-slide swiper-slide-active" style="margin-right: 10px;">
                                <a href="javascript:void(0)" onclick="ppz_sptop01._click('http://www.hankyu-travel.com/tour/detail_i.php?p_course_id=TT25121&amp;p_hei=10','845612')">
                                    <p>
                                        <img src="../../senmon_kokunai/images/smp/pic_re_tour.jpg" alt="１-２月出発限定！ＧＯ！ＧＯ！台湾３日間（基本ホテルプラン）">
                                    </p>
                                    <dl>
                                        <dt>１-２月出発限定！ＧＯ！ＧＯ！台湾３日間（基本ホテルプラン）</dt>
                                        <dd>25,000円</dd>
                                    </dl>
                                </a>
                            </li>
 -->
                        </ul>
                        <div class="swiper-scrollbar" style="opacity: 0; transition-duration: 400ms;">
                            <div class="swiper-scrollbar-drag" style="transition-duration: 0ms; transform: translate3d(16.9066px, 0px, 0px); width: 39.2037px;">
                            </div>
                        </div>
                    </div>
                </section>
            </div>
