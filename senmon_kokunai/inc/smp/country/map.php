<section class="blue DestIWrapper mb40" id="bltai1">
    <h2 class="main-title mainBgClr">
        <i class="icon icon-map"></i>
         <span class="main-title-txt">地図から探す</span>
    </h2>
    <div class="DestI">
        <p>
            <img src="/attending/senmon_kaigai/images/map/smp/map_size.png"  alt="地図から探す">
        </p>
        
        <?php echo $mapHtml; //map?>

    </div>

     <?php if(!empty($popularCountryCity)): ?>
         <div class="map-title">
             <p class="map-title-text"><span>人気の都市・観光地</span></p>
             <?php foreach ($popularCountryCity as $value):?>
                 <a class="map-link" href="<?=$value['tour_url'];?>"><?=$value['q_title'];?></a>
             <?php endforeach; ?>
         </div>
     <?php endif; ?>.

</section>