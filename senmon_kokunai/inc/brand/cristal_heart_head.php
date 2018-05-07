<?php

    $html = '';
    $text = array();

    $html .=<<<EOD
    <p class="list-inline group-crystal-title mb10">
        <span class="font-20 color-219b9e txt-bold bg-white group-crystal-title-txt">ご満足いただくためのお約束</span>
    </p>
    <ul class="group-crystal-list clear color-black group-crystal-num mb20">
EOD;

    $count = 5;
    $text = array(
        'ご宿泊は、厳選された<span>指定ホテル、旅館</span>からご用意いたします。',
        '<span>経験豊富な添乗員</span>がご案内いたします（個人プランを除く）。<br>※添乗員歴350日以上',
        'お出かけの前に<span>添乗員がお電話</span>します。旅先の様子などのご質問を承ります。',
        '<span>午後5時30分</span>までに宿泊施設に到着するようにします。',
        '<span>1グループ32名様</span>、ゆとりの定員制です。',
    );

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
