<ul class="bn-menu bn-menu-new">
    <?php if($categoryType != CATEGORY_TYPE_CITY):?>
        <li><a class="menu-link menu-link-map" onclick="scrollMenuBtn(1)">地図から探す</a><i class="sprite sprite-arrow"></i></li>
    <?php endif;?>
    <li><a class="menu-link menu-link-search" onclick="scrollMenuBtn(2)">条件から探す</a><i class="sprite sprite-arrow"></i></li>
    <li><a class="menu-link menu-link-ichioshi" onclick="scrollMenuBtn(3)">おすすめ商品を見る</a><i class="sprite sprite-arrow"></i></li>
    <li><a class="menu-link menu-link-tour-info" onclick="scrollMenuBtn(4)">観光情報を見る</a><i class="sprite sprite-arrow"></i></li>
    <li>
        <a id="bltai4">出発地を変更する<span id="loStart"><?php echo $def_kyotenName;?>発</span></a>
        <div class="submenu">
            <?php // 出発地選択枠 ?>
        	<?php new SearchActionForSenmonHeaderFacet($naigai,$KyotenID,$rqPara);?>
            <div class="submenu-wr-close">
                <a class="submenu-close"><i class="icn-submenu-close"></i>閉じる</a>
            </div>
        </div>
    </li>
</ul>
