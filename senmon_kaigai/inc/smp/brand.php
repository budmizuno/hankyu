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

    // 拠点によって表示内容を変える
    if ($brandKyoten == 'cristal1')
    {
        $count = 12;
        $text = array(
            '連泊、一筆書きなど<span>ゆとりある日程</span>でお身体に配慮しています。',
            '最大28名様の<span>定員制</span>',
            '日本発着時の航空会社を指定',
            '航空機の<span>並び席を確約※一部路線を除く</span>',
            '専用バス<span>1人2座席</span>利用<span>※一部コースを除く</span>',
            '<span>こだわりのホテル</span>をご用意',
            '<span>選べるメニュー</span>と現地でお好きにお楽しみいただく自由食',
            'スーツケース<span>無料宅配</span>サービス',
            '<span>経験豊富な添乗員</span>が同行',
            '観光に便利なトラベルイヤホン付',
            '記念旅行をお祝い',
            '全コース説明会を開催',
        );
    }
    elseif ($brandKyoten == 'cristal2')
    {
        $count = 5;
        $text = array(
            '日本発着の<span>航空会社は指定</span>です。',
            'ご宿泊は、<span>厳選された指定ホテル</span>からご用意いたします。',
            '<span>経験豊富な添乗員</span>がご案内いたします（個人プランを除く）。',
            '<span>1グループ28名様</span>、ゆとりの定員制です。',
            '全コースで<span>旅行説明会を実施</span>いたします。',
        );
    }
    elseif ($brandKyoten == 'cristal3')
    {
        $count = 4;
        $text = array(
            '日本発着の<span>航空会社は指定</span>です。',
            'ご宿泊は、<span>厳選された指定ホテル</span>からご用意いたします。',
            '<span>経験豊富な添乗員</span>がご案内いたします（個人プランを除く）。',
            '<span>1グループ28名様</span>、ゆとりの定員制です。',
        );
    }

    $display_none = '';
    if($brandKyoten == 'cristal1'){
        $display_none = 'style="display:none;"';
    }

    $cristal_caption_html .=<<<EOD
    <div class="group-crystal-list clear color-black group-crystal-num mb20 " $display_none>
EOD;
    // 関東以外
    if($brandKyoten != 'cristal1'){
        $cristal_caption_html .= '<p><span>ご満足いただくためのお約束</span></p>';
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
        <?php // 関東発のみ ?>
        <?php if ($kyotenId == 'tyo') :?>
            <div style="text-align: right;">
                <a class="btn-full-detail" id="id_modal_detail_crystal_tour">ご満足いただくためのお約束ポイントを見る</a>
            </div>
        <?php else:?>
            <?php echo $cristal_caption_html;?>
        <?php endif;?>
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

<?php // ------------------------------------------------------------------------------ フレンドツアー ?>
<?php if(isset($osusumeCsv[OSUSUME_CATEGORY_NUM][TOUR_FRIEND_TOUR]) && 0 < $osusumeCsv[OSUSUME_CATEGORY_NUM][TOUR_FRIEND_TOUR]) { ?>
<section class="blue photoSearchWrapper wrapper-crystal js_TwomoreFour">
    <div class="crystal-blue">
        <div class="crystal-header">
            <p class="crystal-header-img">
                <img src="/attending/senmon_kaigai/images/smp/pic_deliver_logo.png" alt="crystal">
            </p>
            <p class="list-inline group-crystal-title mb10">
                感動の旅をお届けしたい。<br>ヨーロッパを中心にした価値ある旅をお求めの方へ
            </p>
        </div>
        <div style="text-align: right;">
            <a class="btn-full-detail" id="id_modal_detail_friend_tour" style="width: 72%;">旅づくりのポイントはこちら</a>
        </div>
    </div>
    <ul class="clearfix crystal-grey-list">
        <?php
        foreach($osusumeCsv[OSUSUME_COURSE] as $value) {
            if($value['q_group'] == TOUR_FRIEND_TOUR) {
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
    <p class="moreNewTourPls">
        <span>もっと見る</span>
    </p>
    <p class="moreNewTourMns" style="display: none;"><span>閉じる</span></p>
</section>
<?php } ?>






<?php // ------------------------------------------------------------------------- 以下 モーダル表示内容 ?>
<?php
/**
 * モーダル表示用データ
 */
$__modalDatas = array(
    array(
        'no' => '1',
        'title' => '旅情をたっぷり満喫できる、こだわりのゆったりとした日程でご案内',
        'desc' => '人気の都市をゆっくりと満喫したい。そんなお客様の声に応え、連泊を基本としたお客様本位のゆったりとした日程を心がけています。また、フリータイムも街を知る大切なポイント。自由な旅をご堪能いただくために、フリータイムも可能な限りご用意しています。
<br><br>※現地事情や利用航空会社のスケジュールなどの影響で、ツアーにより「出発が早朝になる場合」「ホテル到着が深夜になる場合」「乗り継ぎ都市での待ち時間が長い場合」などがあります。予めご了承ください。',
        'class' => 'migi',
        'img' => '/attending/senmon_kaigai/images/smp/smp_friend_mw_photo_01.jpg',
    ),
    array(
        'no' => '2',
        'title' => '平均添乗回数300回以上の経験と現地事情に詳しい添乗員が同行',
        'desc' => '当社では主力添乗員に、経験豊富なフレンドツアー専属の添乗員を採用しています。ヨーロッパをはじめとした各方面をそれぞれ専門に添乗し、観光時のきめ細かいご案内など、質の高いサービスをご提供できる添乗員ばかりです。現地の最新情報にも精通しておりますので、フリータイム時のお食事やショッピングなどのアドバイスもおまかせください。',
        'class' => 'hidari',
        'img' => '/attending/senmon_kaigai/images/smp/smp_friend_mw_photo_02.jpg',
    ),
    array(
        'no' => '3',
        'title' => 'フリータイムにも最適な好立地の快適ホテルを厳選',
        'desc' => 'ツアー中にご宿泊いただくホテルは、今までフレンドツアーをご利用いただいたお客様からのアンケート結果や、当社社員による現地調査をもとに選定しています。場所、設備、サービスなど、全般にわたってご満足いただけるホテルを厳選していますので、旅の疲れを癒すやすらぎのひとときを快適なホテルでお過ごしいただけます。一部コースでは、さらに快適なデラックスクラスをご用意しています。',
        'class' => 'migi',
        'img' => '/attending/senmon_kaigai/images/smp/smp_friend_mw_photo_03.jpg',
    ),
    array(
        'no' => '4',
        'title' => 'こだわりのお食事・レストラン',
        'desc' => 'フレンドツアーでは、ツアー中のお食事の内容を事前に必ずチェック。前菜からメインディッシュ、デザートまで、質・量ともに充実したお食事をご用意しています。旅の醍醐味ともいえる各地の郷土料理や、名物料理を盛り込んだバラエティ豊かなメニューはご好評いただいています。
また、ご旅行を元気にお楽しみいただくために、野菜たっぷりのスープやサラダ・温野菜などをご用意し、バランスの取れたお食事を心がけています。',
        'class' => 'hidari',
        'img' => '/attending/senmon_kaigai/images/smp/smp_friend_mw_photo_04.jpg',
    ),
    array(
        'no' => '5',
        'title' => 'ツアーの人数を限定しています',
        'desc' => '最少催行人員は6・8・15名様の少人数から出発保証します。
少人数から出発できますので、ツアーキャンセルが少なく、安心してお申し込みいただけます。さらに一部コースでは、催行決定済みの出発日を多数ご用意！ もちろん、全コース添乗員同行です。',
        'class' => 'migi',
        'img' => '/attending/senmon_kaigai/images/smp/smp_friend_mw_photo_05.jpg',
    ),
    array(
        'no' => '6',
        'title' => '大型バス、ファーストクラス列車の利用など移動もゆったり',
        'desc' => '乗り心地がよく、よりゆったりと旅をお楽しみいただける大型バスを利用し、安全・快適な旅をご提供しています。一部のコースではトイレ付きバスを利用しますので、長距離移動の際も、トイレの心配なくご旅行をお楽しみいただけます。また、いずれのコースでも定期的にトイレ休憩をお取りします。',
        'class' => 'hidari',
        'img' => '/attending/senmon_kaigai/images/smp/smp_friend_mw_photo_06.jpg',
    ),
    array(
        'no' => '7',
        'title' => '全コースで便利なトラベルイヤホン・サービス付き',
        'desc' => '美術館や遺跡などで、より充実した観光をお楽しみいただくために、ガイドの声を耳元で聞くことができるトラベルイヤホンガイドサービスを実施しています。',
        'class' => 'migi',
        'img' => '/attending/senmon_kaigai/images/smp/smp_friend_mw_photo_07.jpg',
    ),
    array(
        'no' => '8',
        'title' => 'ご昼食・ご夕食の際、テーブルウォーターをご用意',
        'desc' => '日本と違って、外国ではほとんどの国で水道水を直接飲むことはありません。お食事の時にわざわざ水を注文するのも毎回お金が必要になります。フレンドツアーでは、ご昼食・ご夕食の際にはテーブルウォーターをご用意します。',
        'class' => 'hidari',
        'img' => '/attending/senmon_kaigai/images/smp/smp_friend_mw_photo_08.jpg',
    ),
    array(
        'no' => '9',
        'title' => '日程中「ほっ」とタイムをご用意',
        'desc' => 'ホテルに到着後、次の出発やお食事までに原則1時間以上、お部屋で「ほっ」とくつろげる休憩タイム。この時間があることで着替えたり、シャワーを浴びたりリフレッシュ、観光中に買ったものも整理することが出来ます。バス移動もぎゅうぎゅう詰めでない「フレンドツアー」では日程も詰め込みすぎません。
<br><br>「ほっ」とタイムがあるから観光で疲れきって、夕食時に舟を漕ぐといったこともございません。「フレンドツアー」の“ゆとり”はこんなところにも隠れています。',
        'class' => 'migi',
        'img' => '/attending/senmon_kaigai/images/smp/smp_friend_mw_photo_09.jpg',
    ),
    array(
        'no' => '10',
        'title' => '航空会社を厳選',
        'desc' => 'フレンドツアーは長年の実績により、各航空会社と確かな信頼関係を築いています。航空会社を厳選し、皆さまに便利で快適な空の旅をご提供できるよう心がけています。',
        'class' => 'hidari',
        'img' => '/attending/senmon_kaigai/images/smp/smp_friend_mw_photo_10.jpg',
    ),
);

?>

<?php // ------------------------------------------------------------------------- 以下 モーダル表示タグ ?>
<div id="box-modal-content-friend_tour" style="display:none;">
    <div id="modal-content" class="GlMenu">
        <div class="GlMenuIcon">
            <div id="modal-close" class="GlMenuClose">
                <a href="javascript:void(0)">閉じる</a>
            </div>
        </div>

        <div class="modal_body">
            <div class="modal_title">旅づくりのポイント</div>
            <?php
            foreach ($__modalDatas as $__modalData) {
            ?>
            <div class="modal_item">
                <div class="title"><span class="num"><?php echo $__modalData['no'] ?>.</span><?php echo $__modalData['title'] ?></div>
                <div class="desc"><img class="<?php echo $__modalData['class'] ?>" src="<?php echo $__modalData['img'] ?>" alt=""><?php echo $__modalData['desc'] ?></div>
            </div>
            <?php
            }
            ?>
        </div>
        <div class="GlMenuIcon">
            <div id="modal-close" class="GlMenuClose bottomClose">
                <a href="javascript:void(0)">閉じる</a>
            </div>
        </div>
    </div>
</div>

<div id="box-modal-content-crystal_tour" style="display:none;">
    <div id="modal-content" class="GlMenu">
        <div class="GlMenuIcon">
            <div id="modal-close" class="GlMenuClose">
                <a href="javascript:void(0)">閉じる</a>
            </div>
        </div>

        <div class="modal_body">
            <div class="modal_title">お約束ポイント</div>
            <div class="modal_item"><div class="title"><span class="num">1.</span>連泊、一筆書きなど<span class="kyouchou">ゆとりある日程</span>でお身体に配慮しています。</div></div>
            <div class="modal_item"><div class="title"><span class="num">2.</span>最大28名様の<span class="kyouchou">定員制</span></div></div>
            <div class="modal_item"><div class="title"><span class="num">3.</span>日本発着時の航空会社を指定</div></div>
            <div class="modal_item"><div class="title"><span class="num">4.</span>航空機の<span class="kyouchou">並び席を確約※一部路線を除く</span></div></div>
            <div class="modal_item"><div class="title"><span class="num">5.</span>専用バス<span class="kyouchou">1人2座席</span>利用<span class="kyouchou">※一部コースを除く</span></div></div>
            <div class="modal_item"><div class="title"><span class="num">6.</span><span class="kyouchou">こだわりのホテル</span>をご用意</div></div>
            <div class="modal_item"><div class="title"><span class="num">7.</span><span class="kyouchou">選べるメニュー</span>と現地でお好きにお楽しみいただく自由食</div></div>
            <div class="modal_item"><div class="title"><span class="num">8.</span>スーツケース<span class="kyouchou">無料宅配</span>サービス</div></div>
            <div class="modal_item"><div class="title"><span class="num">9.</span><span class="kyouchou">経験豊富な添乗員</span>が同行</div></div>
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
/* 強調　*/
.modal_item .kyouchou {
color: #e60012;
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
 * ブランド：フレンドツアーの詳細ボタン押下
 */
$("#id_modal_detail_friend_tour").click(function() {

    // 背景表示設定
    $("body").append('<div id="modal-overlay"></div>');
    $("#modal-overlay").fadeIn("slow");

    // 指定タグ表示
    $("#box-modal-content-friend_tour").fadeIn("slow");
    $('#box-modal-content-friend_tour').scrollTop(0);

    // 閉じるイベント
    $("#modal-overlay,#modal-close").unbind().click(function(){
        $("#box-modal-content-friend_tour").fadeOut("slow",function(){
            $('#modal-overlay').remove();
        });
    });
});

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
