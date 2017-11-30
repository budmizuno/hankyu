<?php
	// 画像処理
	preg_match('/p_photo_mno=(.*)$/', $paramAry->imgFilepath,$Array);
	if (isset($Array[1])) {
    	$filepath = $Array[1];
    	$filepath = '//x.hankyu-travel.com/photo_db/image_search_kikan5.php?p_photo_mno='.$filepath;
	} else {
	    $filepath = $paramAry->imgFilepath;
	}
?>
<li class="swiper-slide">
    <a href="<?php e($paramAry->url);?>">
        <img src="<?php e($filepath);?>" alt="<?php e($paramAry->imgCaption);?>">
        <p class="sly3-ct">
            <span>
            <?php
                $p_hatsu_name_arr = array();
                if(count(($paramAry->p_hatsu_name)) > 0)
                {
                    $p_hatsu_name_arr = explode(',', $paramAry->p_hatsu_name[0]);
                    if(count($p_hatsu_name_arr) > 0){
                        e($p_hatsu_name_arr[1].'発');
                    }else{
                        e($p_hatsu_name_arr[0].'発');
                    }
                }
            ?>
            </span>
            <?php e($paramAry->p_course_name);?>        
        </p>
        <p class="sly3-price"><?php e($paramAry->priceMinMax);?></p>
    </a>
</li>
