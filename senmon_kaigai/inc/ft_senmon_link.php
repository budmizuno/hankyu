<?php
$dispOtherCityList = array();
if ($categoryType == CATEGORY_TYPE_CITY && count($myMasterCity) > 0) {
    foreach ($myMasterCity as $key => $masterCity) {
        if (is_array($masterCity) && $masterCity['page_type'] != 'DS') {
            $dispOtherCityList[] = array('url' => $masterCity['url'], 'name' => $masterCity['senmon_name']);
        }
    }
}
?>
<?php if (count($dispOtherCityList) > 0) :?>
<h3 class="h3-txt mt20">
    <i class="sprite sprite-global"></i>
    <span>他の都市・観光地から探す</span>
</h3>
<ul class="ft-list-menu mb30">
    <?php foreach($dispOtherCityList as $dispOtherCity) :?>
        <li><a href="<?php echo $dispOtherCity['url'];?>"><?php echo $dispOtherCity['name'];?></a></li>
    <?php endforeach;?>
</ul>
<?php endif;?>

<h3 class="h3-txt mt20">
    <i class="sprite sprite-global"></i>
    <span>他の方面・国から探す</span>
</h3>
<ul class="ft-list-menu1 mb30 ft-list-menu2 ft-list-menu2 ft-list-menu3">
    <li>
        <span><a href="/asia/">アジア</a></span>
        <ul>
            <li><a href="/china/">中国</a></li>
            <li><a href="/korea/">韓国</a></li>
            <li><a href="/taiwan/">台湾</a></li>
            <li><a href="/hongkong/">香港</a></li>
            <li><a href="/macau/">マカオ</a></li>
            <li><a href="/thailand/">タイ</a></li>
            <li><a href="/singapore/">シンガポール</a></li>
            <li><a href="/malaysia/">マレーシア</a></li>
            <li><a href="/philippines/">フィリピン</a></li>
            <li><a href="/asian-beach/">インドネシア</a></li>
            <li><a href="/vietnam/">ベトナム</a></li>
            <li><a href="/cambodia/">カンボジア</a></li>
            <li><a href="/laos/">ラオス</a></li>
        </ul>
    </li>
    <li>
        <span class="sp_space">&nbsp;</span>
        <ul>
            <li><a href="/india/">インド</a></li>
            <li><a href="/nepal/">ネパール</a></li>
            <li><a href="/myanmar/">ミャンマー</a></li>
            <li><a href="/srilanka/">スリランカ</a></li>
            <li><a href="/maldives/">モルディブ</a></li>
        </ul>
    </li>
    <li>
        <span><a href="/europe/">ヨーロッパ</a></span>
        <ul>
            <li><a href="/france/">フランス</a></li>
            <li><a href="/uk/">イギリス</a></li>
            <li><a href="/ireland/">アイルランド</a></li>
            <li><a href="/italy/">イタリア</a></li>
            <li><a href="/malta/">マルタ</a></li>
            <li><a href="/greece/">ギリシャ</a></li>
            <li><a href="/swiss/">スイス</a></li>
            <li><a href="/germany/">ドイツ</a></li>
            <li><a href="/holland/">オランダ</a></li>
            <li><a href="/belgium/">ベルギー</a></li>
            <li><a href="/spain/">スペイン</a></li>
            <li><a href="/portugal/">ポルトガル</a></li>
            <li><a href="/russia/">ロシア</a></li>
        </ul>
    </li>
    <li>
        <span class="sp_space">&nbsp;</span>
        <ul>
            <li><a href="/northern-eur/">北欧</a></li>
            <li><a href="/denmark/">デンマーク</a></li>
            <li><a href="/norway/">ノルウェー</a></li>
            <li><a href="/sweden/">スウェーデン</a></li>
            <li><a href="/finland/">フィンランド</a></li>
            <li><a href="/iceland/">アイスランド</a></li>
        </ul>
        <span class="sp_space">&nbsp;</span>
        <ul>
            <li><a href="/east-eur/">東欧・中欧</a></li>
            <li><a href="/austria/">オーストリア</a></li>
            <li><a href="/czech/">チェコ</a></li>
            <li><a href="/slovakia/">スロバキア</a></li>
            <li><a href="/hungary/">ハンガリー</a></li>
            <li><a href="/poland/">ポーランド</a></li>
            <li><a href="/rumania/">ルーマニア</a></li>
            <li><a href="/bulgaria/">ブルガリア</a></li>
            <li><a href="/croatia-slovenia/">クロアチア・スロベニア</a></li>
            <li><a href="/baltic/">バルト三国</a></li>
        </ul>
    </li>
    <li>
        <span><a href="/africa/">アフリカ</a></span>
        <ul>
            <li><a href="/egypt/">エジプト</a></li>
            <li><a href="/tunisia/">チュニジア</a></li>
            <li><a href="/morocco/">モロッコ</a></li>
            <li><a href="/south-africa/">南アフリカ</a></li>
            <li><a href="/kenya/">ケニア</a></li>
            <li><a href="/botswana/">ボツワナ</a></li>
            <li><a href="/jinbabue-zanvia/">ジンバブエ・ザンビア</a></li>
            <li><a href="/tanzania/">タンザニア</a></li>
        </ul>
        <span class="mt30"><a href="/middle-east/">中近東</a></span>
        <ul>
            <li><a href="/turkey/">トルコ</a></li>
            <li><a href="/jordan/">ヨルダン</a></li>
            <li><a href="/israel/">イスラエル</a></li>
            <li><a href="/uae/">アラブ首長国連邦</a></li>
            <li><a href="/uzbekistan/">ウズベキスタン</a></li>
            <li><a href="/iran/">イラン</a></li>
        </ul>
    </li>
    <li>
        <span><a href="/north-america/">北米</a></span>
        <ul>
            <li><a href="/america/">アメリカ</a></li>
            <li><a href="/canada/">カナダ</a></li>
        </ul>
        <span class="mt30"><a href="/hawaii/">ハワイ</a></span>
        <ul>
            <li><a href="/hawaii/oahu/">オアフ島（ホノルル）</a></li>
            <li><a href="/hawaii/bigisland/">ハワイ島</a></li>
            <li><a href="/hawaii/maui/">マウイ島</a></li>
            <li><a href="/hawaii/kauai/">カウアイ島</a></li>
        </ul>
        <span class="mt30"><a href="/latin-america/">中南米</a></span>
        <ul>
            <li><a href="/mexico/">メキシコ</a></li>
            <li><a href="/brazil/">ブラジル</a></li>
            <li><a href="/ecuador/">エクアドル</a></li>
            <li><a href="/venezuela/">ベネズエラ</a></li>
            <li><a href="/peru/">ペルー</a></li>
            <li><a href="/argentina/">アルゼンチン</a></li>
        </ul>
    </li>
    <li>
        <span><a href="/oceania/">オセアニア</a></span>
        <ul>
            <li><a href="/australia/">オーストラリア</a></li>
            <li><a href="/newzealand/">ニュージーランド</a></li>
        </ul>
        <span class="mt30"><a href="/s-pacific/">南太平洋</a></span>
        <ul>
            <li><a href="/tahiti/">タヒチ</a></li>
            <li><a href="/newcaledonia/">ニューカレドニア</a></li>
            <li><a href="/fiji/">フィジー</a></li>
        </ul>
        <span class="mt60"><a href="/micronesia/">ミクロネシア</a></span>
        <ul>
            <li><a href="/guam/">グアム</a></li>
            <li><a href="/saipan/">サイパン</a></li>
        </ul>
    </li>
</ul>
