<?php
// ホテルランキングを出力する
class setHotelRanking
{
    function __construct($type){

        $this->makeHtml($type);
    }

    public function makeHtml($type)
    {
        global $osusumeCsv, $masterCsv, $senmon_func, $kyotenId;

        if(isset($osusumeCsv[OSUSUME_CATEGORY_NUM][$type]) && 0 < $osusumeCsv[OSUSUME_CATEGORY_NUM][$type]) {
        } else {
        	return;
        }

        $list_id = 'hotel_rank_list_';
        if ($type == TOUR_HOTEL_RANKING) {
            $list_id .= 'tour';
        } else {
            $list_id .= 'freeplan';
        }

?>
<section class="mb20">
<h2 class="main-title mainBgClr mb10">
    <span class="main-title-txt"><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?>おすすめホテルランキング</span>
</h2>
<div class="wr-block">
    <div class="frame swiper-container" id="<?php echo $list_id;?>">
        <ul class="clearfix swiper-wrapper">
			<?php
			$num = 1;
			foreach ($osusumeCsv[OSUSUME_COURSE] as $value) {
                if($value[KEY_Q_GROUP] == $type)
                {
                    $p_hatsu_name = '';
                    if($kyotenId == 'index'){
                        $p_hatsu_name = '<span>'.$value['p_hatsu_name'].'</span>';
                    }
			?>
			<li class="swiper-slide">
				<a href="<?php echo $value[KEY_TOUR_URL] ?>">
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
                    <?php if(empty($value[KEY_Q_BRIGHTCOVE_ID]) && empty($value[KEY_Q_THETA_ID]) && empty($value[KEY_Q_THETA_URL])): // ブライトコープ動画もシータ動画もないなら ?>
					    <img src="<?php echo $senmon_func->imagePathConvert(IMG_TYPE_HOTEL_RANKING_L, $value[KEY_Q_IMG_PATH], false); ?>" alt="<?php echo $value[KEY_Q_IMG_CAPTION] ?>" width="300px">
                    <?php endif; ?>
					<p class="sly3-ct"><?=$p_hatsu_name;?><?php echo $value[KEY_Q_COURSE_NAME] ?></p>
					<p class="sly3-price"><?php echo $value['p_price'] ?></p>
                    <i class="icon icon-num icon-num<?php echo $num ?>"></i>
				</a>
			</li>
			<?php
			if ($num >= 10) break;
			$num++;
			?>
			<?php }} ?>
        </ul>
        <div class="swiper-scrollbar"></div>
    </div>
</div>
</section>

<?php
    }
}
?>
