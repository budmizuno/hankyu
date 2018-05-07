<?php
    // 拠点によって出し分ける
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
        case 'toy':     // 富山発
        case 'hkr':     // 石川・福井発
            $brandKyoten = 'cristal3';
            break;
        default:
            $brandKyoten = 'cristal1';
            break;
    }

    $html = '';
    $text = array();

    $html .=<<<EOD
    <p class="list-inline group-crystal-title mb10">
        <span class="font-20 color-219b9e txt-bold bg-white group-crystal-title-txt">ご満足いただくためのお約束</span>
    </p>
    <ul class="group-crystal-list clear color-black group-crystal-num mb20">
EOD;

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

    for($i=0;$i<$count;$i++)
    {
        $number = $i + 1;
        $html .=<<<EOD
        <li class="top">
            <dl class="group-crystal-num-list list">
                <dt class="color-219b9e font-20 list-item">{$number}.</dt>
                <dd class="font-14 list-item">{$text[$i]}</dd>
            </dl>
        </li>
EOD;
    }

    $html .= '</ul>';

    // html出力
    echo $html;

 ?>
