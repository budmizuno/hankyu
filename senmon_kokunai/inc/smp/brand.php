<?php

    $count = 5;
    $text = array(
        'ご宿泊は、厳選された<span>指定ホテル、旅館</span>からご用意いたします。',
        '<span>経験豊富な添乗員</span>がご案内いたします（個人プランを除く）。※添乗員歴350日以上',
        'お出かけの前に<span>添乗員がお電話</span>します。旅先の様子などのご質問を承ります。',
        '<span>午後5時30分</span>までに宿泊施設に到着するようにします。',
        '<span>1グループ32名様</span>、ゆとりの定員制です。',
    );

    $cristal_caption_html = '';
    $cristal_caption_html .=<<<EOD
    <div class="group-crystal-list clear color-black group-crystal-num mb20">
    <p><span>ご満足いただくためのお約束</span></p>
EOD;

    for($i=0;$i<$count;$i++)
    {
        $number = $i + 1;
        $cristal_caption_html .=<<<EOD
        <dl class="group-crystal-num-list list">
                <dt class="color-219b9e font-20 list-item">{$number}.</dt>
                <dd class="font-14 list-item">{$text[$i]}</dd>
            </dl>
EOD;
    }
    $cristal_caption_html .= '</div>';
?>

<?php // ------------------------------------------------------------------------------ クリスタルハート ?>
<?php if(isset($osusumeCsv[OSUSUME_CATEGORY_NUM][TOUR_CRISTAL_HEART]) && 0 < $osusumeCsv[OSUSUME_CATEGORY_NUM][TOUR_CRISTAL_HEART]) { ?>
<section class="blue photoSearchWrapper wrapper-crystal js_TwomoreFour mb15">
    <div class="crystal-grey">
        <div class="crystal-header">
            <p class="crystal-header-img">
                <img src="/attending/senmon_kaigai/images/smp/pic_crystal_logo.png" alt="crystal">
            </p>
            <p class="list-inline group-crystal-title mb10">
                <span class="font-20 color-219b9e txt-bold bg-white group-crystal-title-txt">大切な人と大切な時間を…。時間や気持ちに配慮した、ゆとりの旅をご提供</span>
            </p>
        </div>
        <?php echo $cristal_caption_html;?>
    </div>
    <ul class="clearfix crystal-grey-list">
        <?php
        foreach($osusumeCsv[OSUSUME_COURSE] as $value) {
            if($value['q_group'] == TOUR_CRISTAL_HEART) {
        ?>

        <li style="/* display: none; */">
            <?php if(!empty($value[KEY_Q_BRIGHTCOVE_ID])): // ブライトコープ動画があるなら ?>
                <div class="block-banner-top" style="height:35vw;">
                    <div class="block-banner-topbox"></div>
                    <video data-video-id="<?=$value[KEY_Q_BRIGHTCOVE_ID];?>" data-account="5097191270001" data-player="default" data-embed="default" data-application-id class="video-js" controls width="100%" height="100%"></video>
                    <script src="//players.brightcove.net/5097191270001/default_default/index.min.js"></script>
                </div>
            <?php elseif(!empty($value[KEY_Q_THETA_ID]) && !empty($value[KEY_Q_THETA_URL])): // シータ動画があるなら ?>
                <div style="width:auto; height:35vw;">
                    <div class="thum" style="height:35vw;">
                        <blockquote data-mode="click2play" data-width="auto" data-height="100%" class="ricoh-theta-spherical-image" >
                            <a href="<?=$value[KEY_Q_THETA_URL];?>"></a>
                        </blockquote>
                        <script async src="https://bud-international.theta360.biz/widgets.js" charset="utf-8"></script>
                    </div>
                </div>
            <?php endif; ?>
            <a href="<?=$value['tour_url'];?>">
                <dl>
                    <?php if(empty($value[KEY_Q_BRIGHTCOVE_ID]) && empty($value[KEY_Q_THETA_ID]) && empty($value[KEY_Q_THETA_URL])): // ブライトコープ動画もシータ動画もないなら ?>
                        <dt>
                            <img src="<?=$senmon_func->imagePathConvert(IMG_TYPE_BRAND, $value[KEY_Q_IMG_PATH], false);?>" alt="<?=$value['p_img1_caption'];?>">
                        </dt>
                    <?php endif; ?>
                    <dd><?php echo $value['p_course_name'];?></dd>
                    <dd class="price"><?php echo $value['p_price'];?></dd>
                </dl>
            </a>
        </li>

        <?php
            }
        }
        ?>
    </ul>
    <p class="moreNewTourPls"><span>もっと見る</span></p>
    <p class="moreNewTourMns" style="display: none;"><span>閉じる</span></p>
</section>
<?php } ?>


<?php // ------------------------------------------------------------------------- 以下 モーダル表示タグ ?>
<div id="box-modal-content-crystal_tour" style="display:none;">
    <div id="modal-content" class="GlMenu">
        <div class="GlMenuIcon">
            <div id="modal-close" class="GlMenuClose">
                <a href="javascript:void(0)">閉じる</a>
            </div>
        </div>
        <div class="modal_body">
            <div class="modal_title">旅づくりのポイント</div>
            <div class="modal_item"><div class="title"><span class="num">1.</span>連泊、一筆書きなどゆとりある日程でお身体に配慮しています。</div></div>
            <div class="modal_item"><div class="title"><span class="num">2.</span>最大28名様の定員制</div></div>
            <div class="modal_item"><div class="title"><span class="num">3.</span>日本発着時の航空会社を指定</div></div>
            <div class="modal_item"><div class="title"><span class="num">4.</span>航空機の並び席を確約※一部路線を除く</div></div>
            <div class="modal_item"><div class="title"><span class="num">5.</span>専用バス1人2座席利用※一部コースを除く</div></div>
            <div class="modal_item"><div class="title"><span class="num">6.</span>こだわりのホテルをご用意</div></div>
            <div class="modal_item"><div class="title"><span class="num">7.</span>選べるメニューと現地でお好きにお楽しみいただく自由食</div></div>
            <div class="modal_item"><div class="title"><span class="num">8.</span>スーツケース無料宅配サービス</div></div>
            <div class="modal_item"><div class="title"><span class="num">9.</span>経験豊富な添乗員が同行</div></div>
            <div class="modal_item"><div class="title"><span class="num">10.</span>観光に便利なトラベルイヤホン付選</div></div>
            <div class="modal_item"><div class="title"><span class="num">11.</span>記念旅行をお祝い</div></div>
            <div class="modal_item"><div class="title"><span class="num">12.</span>全コース説明会を開催</div></div>
        </div>
        <div class="GlMenuIcon">
            <div id="modal-close" class="GlMenuClose bottomClose">
                <a href="javascript:void(0)">閉じる</a>
            </div>
        </div>
    </div>
</div>


<?php // ------------------------------------------------------------------------- 以下 style ?>
<style>

/* 背景 */
#box-modal-content-friend_tour,
#box-modal-content-crystal_tour {
    position: fixed;
    top: 0;
    display: block;
    width: 100%;
    height: 100%;
    z-index: 1030;
    overflow: scroll;
}
#box-modal-content-friend_tour #modal-content,
#box-modal-content-crystal_tour #modal-content {
    margin: 0 auto;
    position: relative;
    top: 40px;
    margin-bottom: 40px;
    display: block;
}
/* モーダルボディ */
.modal_body {
padding: 5px 10px 5px 10px;

font-family: "ヒラギノ角ゴ ProN W3","Hiragino kaku Gothic ProN","メイリオ","Meiryo","MS Pゴシック","MS PGothic",sans-serif;
}

/* タイトル */
.modal_title {
color: white;
background-color: #0666cd;
font-size: 20px;
font-weight: bold;
padding: 10px 10px 5px 10px;
}

/* 各表示アイテム */
.modal_item {
border: solid #b1cfef;
border-width: 1px;
margin: 10px 0px 0px 0px;
padding: 5px 5px 10px 10px;

/* height: 400px; */
}
/* アイテムタイトル */
.modal_item .title {
color: #0666cd;
background-color: white;
font-size: 18px;
font-weight: bold;
padding: 5px 0px 0px 0px;
}
/* 番号 */
.modal_item .num {
color: red;
}
/* 説明 */
.modal_item .desc {
font-size: 15px;
padding: 5px 0px 0px 0px;
}
/* 画像 */
.modal_item img {
height: 100px;
float: right;
}

/* 画像を右表示 */
.modal_item img.migi {
float: right;
margin-left: 10px;
margin-bottom: 10px;;
}
/* 画像を左表示 */
.modal_item img.hidari {
float: left;
margin-right: 10px;
margin-bottom: 10px;;
}


#box-modal-content-crystal_tour .modal_title {
    background-color: #219b9e;
}
#box-modal-content-crystal_tour .modal_item .title {
    color: #219b9e;
}

</style>

<?php // ------------------------------------------------------------------------- 以下 js ?>
<script>


$(function() {

/**
 * ブランド：クリスタルハートの詳細ボタン押下
 */
$("#id_modal_detail_crystal_tour").click(function() {

    // 背景表示設定
    $("body").append('<div id="modal-overlay"></div>');
    $("#modal-overlay").fadeIn("slow");

    // 指定タグ表示
    $("#box-modal-content-crystal_tour").fadeIn("slow");
    $('#box-modal-content-crystal_tour').scrollTop(0);

    // 閉じるイベント
    $("#modal-overlay,#modal-close").unbind().click(function(){
        $("#box-modal-content-crystal_tour").fadeOut("slow",function(){
            $('#modal-overlay').remove();
        });
    });
});


});

</script>
