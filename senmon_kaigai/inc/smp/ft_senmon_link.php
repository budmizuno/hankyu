<section class="menuArea menuAreaFooter">
    <section class="menu_info">

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
    <i class="h3-txt-line"></i>
    <span>他の都市・観光地から探す</span>
</h3>
<ul class="ft-list-menu">
    <li class="no_icn">
        <dl>
            <dt class="moreArea icn_close icn_more"><a rel="javasctipt:void(0);"><?php  echo( ($masterCsv[KEY_MASTER_CSV_HOMEN] == 'hawaii/')? 'ハワイ' : $myMasterCity['senmon_name'] ); ?></a></dt>
            <?php foreach($dispOtherCityList as $dispOtherCity) :?>
                <dd style="display: none;"><a href="<?php echo $dispOtherCity['url'];?>"><?php echo $dispOtherCity['name'];?></a></dd>
            <?php endforeach;?>
        </dl>
    </li>
</ul>
<?php endif;?>

        <h3 class="h3-txt">
             <i class="h3-txt-line"></i>
             <span>他の方面・国から探す</span>
        </h3>
        <ul>
            <li class="no_icn">
                <dl>
                    <dt class="moreArea icn_close icn_more"><a rel="javasctipt:void(0);">ヨーロッパ</a></dt>
                    <dd><a href="/europe/">ヨーロッパ</a></dt>
                    <dd><a href="/france/">フランス</a></dt>
                    <dd><a href="/uk/">イギリス</a></dt>
                    <dd><a href="/ireland/">アイルランド</a></dt>
                    <dd><a href="/italy/">イタリア</a></dt>
                    <dd><a href="/malta/">マルタ</a></dt>
                    <dd><a href="/greece/">ギリシャ</a></dt>
                    <dd><a href="/swiss/">スイス</a></dt>
                    <dd><a href="/germany/">ドイツ</a></dt>
                    <dd><a href="/holland/">オランダ</a></dt>
                    <dd><a href="/belgium/">ベルギー</a></dt>
                    <dd><a href="/spain/">スペイン</a></dt>
                    <dd><a href="/portugal/">ポルトガル</a></dt>
                    <dd><a href="/russia/">ロシア</a></dt>
                    <dd><a href="/northern-eur/">北欧</a></dt>
                    <dd><a href="/denmark/">デンマーク</a></dt>
                    <dd><a href="/norway/">ノルウェー</a></dt>
                    <dd><a href="/sweden/">スウェーデン</a></dt>
                    <dd><a href="/finland/">フィンランド</a></dt>
                    <dd><a href="/iceland/">アイスランド</a></dt>
                    <dd><a href="/east-eur/">東欧・中欧</a></dt>
                    <dd><a href="/austria/">オーストリア</a></dt>
                    <dd><a href="/czech/">チェコ</a></dt>
                    <dd><a href="/slovakia/">スロバキア</a></dt>
                    <dd><a href="/hungary/">ハンガリー</a></dt>
                    <dd><a href="/poland/">ポーランド</a></dt>
                    <dd><a href="/rumania/">ルーマニア</a></dt>
                    <dd><a href="/bulgaria/">ブルガリア</a></dt>
                    <dd><a href="/croatia-slovenia/">クロアチア・スロベニア</a></dt>
                    <dd><a href="/baltic/">バルト三国</a></dt>
                </dl>
                <dl>
                    <dt class="moreArea icn_close icn_more"><a rel="javasctipt:void(0);">アジア</a></dt>
                    <dd><a href="/asia/">アジア</a></dt>
                    <dd><a href="/china/">中国</a></dt>
                    <dd><a href="/korea/">韓国</a></dt>
                    <dd><a href="/taiwan/">台湾</a></dt>
                    <dd><a href="/hongkong/">香港</a></dt>
                    <dd><a href="/macau/">マカオ</a></dt>
                    <dd><a href="/thailand/">タイ</a></dt>
                    <dd><a href="/singapore/">シンガポール</a></dt>
                    <dd><a href="/malaysia/">マレーシア</a></dt>
                    <dd><a href="/philippines/">フィリピン</a></dt>
                    <dd><a href="/asian-beach/">インドネシア</a></dt>
                    <dd><a href="/vietnam/">ベトナム</a></dt>
                    <dd><a href="/cambodia/">カンボジア</a></dt>
                    <dd><a href="/laos/">ラオス</a></dt>
                    <dd><a href="/india/">インド</a></dt>
                    <dd><a href="/nepal/">ネパール</a></dt>
                    <dd><a href="/myanmar/">ミャンマー</a></dt>
                    <dd><a href="/srilanka/">スリランカ</a></dt>
                    <dd><a href="/maldives/">モルディブ</a></dt>
                </dl>
                 <dl>
                    <dt class="moreArea icn_close icn_more"><a rel="javasctipt:void(0);">中近東</a></dt>
                    <dd><a href="/middle-east/">中近東</a></dt>
                    <dd><a href="/turkey/">トルコ</a></dt>
                    <dd><a href="/jordan/">ヨルダン</a></dt>
                    <dd><a href="/israel/">イスラエル</a></dt>
                    <dd><a href="/uae/">アラブ首長国連邦</a></dt>
                    <dd><a href="/uzbekistan/">ウズベキスタン</a></dt>
                    <dd><a href="/iran/">イラン</a></dt>
                </dl>
                <dl>
                   <dt class="moreArea icn_close icn_more"><a rel="javasctipt:void(0);">アフリカ</a></dt>
                   <dd><a href="/africa/">アフリカ</a></dt>
                   <dd><a href="/egypt/">エジプト</a></dt>
                   <dd><a href="/tunisia/">チュニジア</a></dt>
                   <dd><a href="/morocco/">モロッコ</a></dt>
                   <dd><a href="/south-africa/">南アフリカ</a></dt>
                   <dd><a href="/kenya/">ケニア</a></dt>
                   <dd><a href="/botswana/">ボツワナ</a></dt>
                   <dd><a href="/jinbabue-zanvia/">ジンバブエ・ザンビア</a></dt>
                   <dd><a href="/tanzania/">タンザニア</a></dt>
               </dl>
                <dl>
                    <dt class="moreArea icn_close icn_more"><a rel="javasctipt:void(0);">北米</a></dt>
                    <dd><a href="/north-america/">北米</a></dt>
                    <dd><a href="/america/">アメリカ</a></dt>
                    <dd><a href="/canada/">カナダ</a></dt>
                </dl>
                <dl>
                    <dt class="moreArea icn_close icn_more"><a rel="javasctipt:void(0);">ハワイ</a></dt>
                    <dd><a href="/hawaii/">ハワイ</a></dt>
                    <dd><a href="/hawaii/oahu/">オアフ島（ホノルル）</a></dt>
                    <dd><a href="/hawaii/bigisland/">ハワイ島</a></dt>
                    <dd><a href="/hawaii/maui/">マウイ島</a></dt>
                    <dd><a href="/hawaii/kauai/">カウアイ島</a></dt>
                </dl>
                <dl>
                    <dt class="moreArea icn_close icn_more"><a rel="javasctipt:void(0);">ミクロネシア</a></dt>
                    <dd><a href="/micronesia/">ミクロネシア</a></dt>
                    <dd><a href="/guam/">グアム</a></dt>
                    <dd><a href="/saipan/">サイパン</a></dt>
                </dl>
                <dl>
                    <dt class="moreArea icn_close icn_more"><a rel="javasctipt:void(0);">南太平洋</a></dt>
                    <dd><a href="/s-pacific/">南太平洋</a></dt>
                    <dd><a href="/tahiti/">タヒチ</a></dt>
                    <dd><a href="/newcaledonia/">ニューカレドニア</a></dt>
                    <dd><a href="/fiji/">フィジー</a></dt>
                </dl>
                <dl>
                    <dt class="moreArea icn_close icn_more"><a rel="javasctipt:void(0);">オセアニア</a></dt>
                    <dd><a href="/oceania/">オセアニア</a></dt>
                    <dd><a href="/australia/">オーストラリア</a></dt>
                    <dd><a href="/newzealand/">ニュージーランド</a></dt>
                </dl>
                <dl>
                    <dt class="moreArea icn_close icn_more"><a rel="javasctipt:void(0);">中南米</a></dt>
                    <dd><a href="/latin-america/">中南米</a></dt>
                    <dd><a href="/mexico/">メキシコ</a></dt>
                    <dd><a href="/brazil/">ブラジル</a></dt>
                    <dd><a href="/ecuador/">エクアドル</a></dt>
                    <dd><a href="/venezuela/">ベネズエラ</a></dt>
                    <dd><a href="/peru/">ペルー</a></dt>
                    <dd><a href="/argentina/">アルゼンチン</a></dt>
                </dl>
            </li>
        </ul>
    </section>
</section>
