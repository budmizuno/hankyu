<?php
    // クリスタルハート説明文は拠点によって出し分ける
    $brandKyoten = '';
    switch ($kyotenId) {
        case 'tyo':     // 関東発
            $brandKyoten = 'cristal1';
            break;
        case 'osa':     // 関西発
        case 'hij':     // 広島発
        case 'okj':     // 岡山発
        case 'izo':     // 山陰発
        case 'ubj':     // 山口発
        case 'tak':     // 香川・徳島発
        case 'kcz':     // 高知発
        case 'myj':     // 松山発
        case 'fuk':     // 福岡発
        case 'ngs':     // 長崎発
        case 'kmj':     // 熊本発
        case 'oit':     // 大分発
        case 'kmi':     // 宮崎発
        case 'koj':     // 鹿児島発
        case 'oka':     // 沖縄発
            $brandKyoten = 'cristal2';
            break;
        case 'spk':     // 北海道発
        case 'sdj':     // 東北発
        case 'aoj':     // 青森発
        case 'ibr':     // 北関東発
        case 'mmj':     // 長野発
        case 'kij':     // 新潟発
        case 'szo':     // 静岡発
        case 'ngo':     // 名古屋発
        case 'hkr':     // 富山発
        case 'hkr':     // 石川・福井発
        case 'toy':     // 富山発
            $brandKyoten = 'cristal3';
            break;
        default:
            $brandKyoten = 'cristal1';
            break;
    }

    $cristal_caption_html = '';
    $text = array();

    $cristal_caption_html .=<<<EOD
    <div class="group-crystal-list clear color-black group-crystal-num mb20">
EOD;

    // 拠点によって表示内容を変える
    if ($brandKyoten == 'cristal1')
    {
        $count = 12;
        $text = array(
            '連泊、一筆書きなどゆとりある日程でお身体に配慮しています。',
            '最大28名様の定員制',
            '日本発着時の航空会社を指定',
            '航空機の並び席を確約※一部路線を除く',
            '専用バス1人2座席利用※一部コースを除く',
            'こだわりのホテルをご用意',
            '選べるメニューと現地でお好きにお楽しみいただく自由食',
            'スーツケース無料宅配サービス',
            '経験豊富な添乗員が同行',
            '観光に便利なトラベルイヤホン付',
            '記念旅行をお祝い',
            '全コース説明会を開催',
        );
    }
    elseif ($brandKyoten == 'cristal2')
    {
        $count = 5;
        $text = array(
            '日本発着の航空会社は指定です。',
            'ご宿泊は、厳選された指定ホテル、旅館からご用意いたします。',
            '経験豊富な添乗員がご案内いたします（個人プランを除く）。',
            '1グループ32名様、ゆとりの定員制です。',
            '全コースで旅行説明会を実施いたします。',
        );
    }
    elseif ($brandKyoten == 'cristal3')
    {
        $count = 4;
        $text = array(
            '日本発着の航空会社は指定です。',
            'ご宿泊は、厳選された指定ホテル、旅館からご用意いたします。',
            '経験豊富な添乗員がご案内いたします（個人プランを除く）。',
            '1グループ28名様、ゆとりの定員制です。',
        );
    }

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
        <?php // echo $cristal_caption_html;?>
    </div>
    <ul class="clearfix crystal-grey-list">

        <?php
            include_once($PathSenmonCommon . 'phpsc/mySearch.php');

            $_REQUEST = null;
            $_REQUEST['MyNaigai'] = $naigai;
            $_REQUEST['p_mainbrand'] ='03';
            $_REQUEST['p_mokuteki'] = $mokuteki;
            $_REQUEST['smpFlag'] = true;

            $obj = new LoadAction;	//ロード時の全て
            $resObj = $obj->dispObj;	//表示するもの全て格納

            if(!empty($resObj['html']))
            {
                echo ($resObj['html']);
            }
        ?>
    </ul>
    <p class="moreNewTourPls"><span>もっと見る</span></p>
    <p class="moreNewTourMns" style="display: none;"><span>閉じる</span></p>
</section>


<?php // ------------------------------------------------------------------------- 以下 モーダル表示タグ ?>
<div id="box-modal-content-crystal_tour" style="display:none;">
    <div id="modal-content" class="GlMenu">
		<div class="GlMenuIcon">
			<div id="modal-close" class="GlMenuClose">
				<a href="javascript:void(0)">閉じる</a>
			</div>
		</div>

		<div class="modal_body">
			<div class="modal_title">お約束ポイント</div>
	            <div class="modal_item"><div class="title"><span class="num">1.</span>連泊、一筆書きなどゆとりある日程でお身体に配慮しています。</div></div>
	            <div class="modal_item"><div class="title"><span class="num">2.</span>最大28名様の定員制</div></div>
	            <div class="modal_item"><div class="title"><span class="num">3.</span>日本発着時の航空会社を指定</div></div>
	            <div class="modal_item"><div class="title"><span class="num">4.</span>航空機の並び席を確約※一部路線を除く</div></div>
	            <div class="modal_item"><div class="title"><span class="num">5.</span>専用バス1人2座席利用※一部コースを除く</div></div>
	            <div class="modal_item"><div class="title"><span class="num">6.</span>こだわりのホテルをご用意</div></div>
	            <div class="modal_item"><div class="title"><span class="num">7.</span>選べるメニューと現地でお好きにお楽しみいただく自由食</div></div>
	            <div class="modal_item"><div class="title"><span class="num">8.</span>スーツケース無料宅配サービス</div></div>
	            <div class="modal_item"><div class="title"><span class="num">9.</span>経験豊富な添乗員が同行</div></div>
	            <div class="modal_item"><div class="title"><span class="num">10.</span>観光に便利なトラベルイヤホン付</div></div>
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
	position:fixed;
	top:0;
    display: block;
	width:100%;
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
