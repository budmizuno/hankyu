<section class="DestIWrapper mb20" id="bltai1">
    <h2 class="main-title mainBgClr map_title">
         <span class="main-title-txt">地図から探す</span>
    </h2>
    <div class="DestI" ontouchstart="">
        <p>
            <img src="/attending/senmon_kaigai/images/smp/map/map_size.png"  alt="地図から探す">
        </p>
        
        <?php echo $mapHtml; //map?>
    </div>
    <?php if(!empty($popularCountryCityCsv)): ?>
        <p class="map-title-text"><span>人気の都市・観光地</span></p>
        <div class="map-title">
            <?php foreach ($popularCountryCityCsv as $value):?>
                <?php if ($value['q_category'] == $masterCsv[KEY_MASTER_CSV_NAME_JA] && $value['q_title'] != "") : ?>
                    <a class="map-link" href="<?=$value['tour_url'];?>"><?=$value['q_title'];?></a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</section>

<script>
<!--
$(function(){
    $('.map-list a').click(function(){
        if ( !$(this).is('[disabled]') ) {
            location.href = $(this).attr('href');
        }
    });
});
-->
</script>