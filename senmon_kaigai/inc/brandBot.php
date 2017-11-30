<?php // クリスタルハート?>
<div class="brand_bot border-cccccc mt40 pos-rel">
    <div class="brand_bot_head">
        <div class="brand_bot_img"></div>
        <span>大切な人と大切な時間を…。時間や気持ちに配慮した、ゆとりの旅をご提供</span>
    </div>

   <?php
       // クリスタルハートの説明部分
//       include_once($PathSenmonCommon . 'inc/brand/cristal_heart_head.php');
   ?>
    <ul class="group-crystal-list clear">
        <?php
            include_once($PathSenmonCommon . 'phpsc/mySearch.php');

            $_REQUEST = null;
            $_REQUEST['MyNaigai'] = $naigai;
            $_REQUEST['p_mokuteki'] = $mokuteki;
            $_REQUEST['p_mainbrand'] ='03';

            $obj = new LoadAction;	//ロード時の全て
            $resObj = $obj->dispObj;	//表示するもの全て格納

            if(!empty($resObj['html']))
            {
                echo ($resObj['html']);
            }
        ?>
    </ul>
</div>
<!--group crystal-->

<!--フレンドツアー-->
<div class="group-crystal border-cccccc mt20 pos-rel group-friend">
    <?php
        // フレンドツアーの説明部分
        include_once($PathSenmonCommon . 'inc/brand/friend_tour_head.php');
    ?>
    <ul class="group-crystal-list clear">
        <?php
            include_once($PathSenmonCommon . 'phpsc/mySearch.php');

            $_REQUEST = null;
            $_REQUEST['MyNaigai'] = $naigai;
            $_REQUEST['p_mokuteki'] = $mokuteki;
            $_REQUEST['p_mainbrand'] ='06';

            $obj = new LoadAction;	//ロード時の全て
            $resObj = $obj->dispObj;	//表示するもの全て格納

            if(!empty($resObj['html']))
            {
                echo ($resObj['html']);
            }
        ?>
    </ul>
</div>
<!--group friend-->
