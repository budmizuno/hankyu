
<script>
<!--
var mapDataJson = <?php echo json_encode($mapImgData) // jsonで入れる ?>;
-->
</script>
<div class="wr-banner3 mb20" id="wr-banner3">
    <div class="bn-title bn-title-europe mainBgClr">
        <h1 class="bn-title-ltxt color-white"><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?></h1>
        <p class="bn-title-stxt TxtColor"><?=$masterCsv[KEY_MASTER_CSV_NAME_EN];?></p>
    </div><!-- bn-title bn-title-europe mainBgClr -->
    <div class="wr-child-banner left">
        <input type="hidden" id="category_type" value="<?=$categoryType;?>">
        <input type="hidden" id="page_type" value="<?=$pageCode;?>">
        <input type="hidden" id="map_default_display" value="<?=$masterCsv[KEY_MASTER_CSV_MAP_DEFAULT];?>">
        <input type="hidden" id="page_caption" value="<?=$masterCsv[KEY_MASTER_CSV_PAGE_CAPTION];?>">
        <div class="wr-image-banner bdColor">
            <ul id="banner" style="min-height: 255px; background-color: #fff;">
            </ul>
        </div>
        <div class="wr-txt-banner ">
            <p class="txt-banner-title" id="banner-title"></p>
            <p id="banner-content"></p>
        </div>
    </div><!-- // wr-child-banner left -->
    <div class="right wr-child-banner-info">
        <p class="wr-child-banner-com"><?=$masterCsv[KEY_MASTER_CSV_PAGE_CAPTION];?></p>

        <?php echo $mapHtml; //map?>

        <?php if(0 < count($popularCountryCityCsv)): ?>
            <div class="wr-banner2-bottom">
                <dl>
                    <dt>
                        <ul>
                            <li class="box_popular_title"><p class="popular_title">人気の都市・観光地</p></li>
                        </ul>
                    </dt>
                    <dd>
                        <ul>
                            <?php
                                foreach ($popularCountryCityCsv as $item):
                                    if ($item['q_category'] == $masterCsv[KEY_MASTER_CSV_NAME_JA] && $item['q_title'] != "") {
                                        ?>
                                        <li>
                                            <a href="<?php echo $item['tour_url']; ?>"><?php echo $item['q_title']; ?></a>
                                        </li>
                                    <?php }
                                endforeach;
                            ?>
                        </ul>
                    </dd>
                </dl>
            </div>
        <?php endif; ?>
    </div><!-- // right wr-child-banner-info -->
</div><!-- // wr-banner3 mb20 -->
