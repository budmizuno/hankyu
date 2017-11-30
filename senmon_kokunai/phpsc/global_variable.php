<?php
/*
#################################################################
	専門店共通で持つグローバル変数設定ファイル 専門店
#################################################################
*/

// 専門店共通で持つ各CSVの読み込みクラス
include_once(dirname(__FILE__) . '/getCsvItemClass.php');

// CSVのツアーに絞り込みAPIの情報を付加させるためのファイル
include_once(dirname(__FILE__) . '/recommend_search/RecommendCourseService.php');

// CSVのツアーに絞り込みAPIの情報を付加させるためのファイル
include_once(dirname(__FILE__) . '/senmon_func.php');

// memcache
include_once(dirname(__FILE__) . '/SenmonMemCache.php');


$senmon_func = new Senmon_Func();

// memcache
$SenmonMemCache = new SenmonMemCache();

/*---------------------------------
  グローバル変数の初期化
---------------------------------*/
$severEnvironment = 0;              // サーバー環境
$PathSenmonCommon = '';             // 専門店のパス
$PathSenmonLink = '';               // CSSファイルやJSファイルを読み込む際に使用
$pageCode = '';                     // ページコード BEU-IT など
$masterCsv = array();               // 量産化用CSVから取得したMASTER情報
$popularCountryCityCsv = array();   // 人気の都市観光地CSVから取得
$photoCsv = array();                // 写真CSVから取得
$illustMessageCsv = array();        // イラストメッセージCSVから取得
$osusumeCsv = array();              // おすすめツアーフリープランCSVから取得
$kyotenFreeCsv = array();           // 拠点自由枠のCSVから取得
$kyotenTokusyuCsv = array();        // 拠点特集枠のCSVから取得
$guideCsv = array();                // ガイドCSVから取得
$keyWordCsv = array();              // 人気のキーワードCSVから取得
$touristInfomationCsv = array();    // 観光情報CSV
$categoryType = 0;                  // ページの方面、国、都市タイプ
$senmonNameEnLower = '';            // 英語専門店名の全部小文字
$p_hatsu = '';                      // 発地 例：101,130,133,134
$mokuteki = '';                     // 目的地コード
$def_kyotenName = '未選択';         // 表示拠点名
$KyotenName = '未選択';
$contents_header_country_to_dest = array('tokyo'); // 都道府県のコンテンツヘッダー（写真スクロールと地図）を方面にする都道府県配列
$uresuzi_ac_off_kyoten = array(    // 売れ筋ランキングのアクティブコアを利用した自動表示はオフする拠点
    'tyo',
    'hkr',
    'toy',
    'izo',
    'okj',
    'hij',
    'ubj',
    'tak',
    'myj',
    'kcz',
    'fuk',
    'ngs',
    'kmj',
    'oit',
    'kmi',
    'koj',
    'oka',
);

// サーバー環境。各開発環境、本番環境の設定
switch ($_SERVER['HTTP_HOST']) {
    // 本番環境
    case 'www.hankyu-travel.com':
        $severEnvironment = PRODUCTUIN;
        break;
    // 検証環境
    case 'www-cms.hankyu-travel.com':
        $severEnvironment = TEST;
        break;
    // バド様開発環境
    case 'oflex-659-1.kagoya.net':
        $severEnvironment = BUD;
        break;
    // りーふねっと様開発環境
    case 'test-hankyu-travel.leafnet.jp':
        $severEnvironment = LEAFNET;
        break;
    default:
        $severEnvironment = LEAFNET;
        break;
}

// りーふねっと開発環境なら
if($severEnvironment == LEAFNET)
{
    // Log確認用のファイル
    include_once(dirname(__FILE__) . '/writeLogClass.php');
    $writeLog = new writeLog();
}


// 専門店のパス
$naigai = $SettingData->PageAttribute;
$PathSenmonCommon = $_SERVER['DOCUMENT_ROOT'].'/attending/senmon_kaigai/';
if ($naigai == 'd') {
    $PathSenmonCommon = $_SERVER['DOCUMENT_ROOT'].'/attending/senmon_kokunai/';
}

// 表示拠点
//if (!empty($SetData->dispKyotenId)) {
//    $kyotenId = $SetData->dispKyotenId;
//} else {
//    $kyotenId = preg_replace('/\.php.*/', '', basename($_SERVER["SCRIPT_NAME"]));
//}


$kyotenId = preg_replace('/\.php.*/', '', basename($_SERVER['SCRIPT_NAME']));
if ( ($kyotenId == 'index' || $kyotenId == '') && !empty($SetData->dispKyotenId)) {
    $kyotenId =$SetData->dispKyotenId;
}

if(empty($GlobalMaster['kyotenUse'])){
	new GM_kyotenUse;
}

foreach($GlobalMaster['kyotenUse'] as $data){
	if($data['kyotenId']==$kyotenId){
		$KyotenName = $data['kyotenName'];
		break;
	}
}
switch($kyotenId){
	case 'spk':
		$def_kyotenName ='北海道';
		break;
	case 'aoj':
		$def_kyotenName ='青森';
		break;
	case 'sdj':
		$def_kyotenName ='東北';
		break;
	case 'tyo':
		$def_kyotenName ='関東';
		break;
	case 'ibr':
		$def_kyotenName ='北関東';
		break;
	case 'kij':
		$def_kyotenName ='新潟';
		break;
	case 'mmj':
		$def_kyotenName ='長野';
		break;
	case 'ngo':
		$def_kyotenName ='名古屋';
		break;
	case 'hkr':
		$def_kyotenName ='石川・福井';
		break;
	case 'szo':
		$def_kyotenName ='静岡';
		break;
	case 'osa':
		$def_kyotenName ='関西';
		break;
	case 'okj':
		$def_kyotenName ='岡山';
		break;
	case 'izo':
		$def_kyotenName ='山陰';
		break;
	case 'hij':
		$def_kyotenName ='広島';
		break;
	case 'ubj':
		$def_kyotenName ='山口';
		break;
	case 'tak':
		$def_kyotenName ='香川・徳島';
		break;
	case 'myj':
		$def_kyotenName ='松山';
		break;
	case 'kcz':
		$def_kyotenName ='高知';
		break;
	case 'fuk':
		$def_kyotenName ='福岡';
		break;
	case 'ngs':
		$def_kyotenName ='長崎';
		break;
	case 'kmj':
		$def_kyotenName ='熊本';
		break;
	case 'oit':
		$def_kyotenName ='大分';
		break;
	case 'kmi':
		$def_kyotenName ='宮崎';
		break;
	case 'koj':
		$def_kyotenName ='鹿児島';
		break;
	case 'oka':
		$def_kyotenName ='沖縄';
		break;
    case 'toy':
        $def_kyotenName ='富山';
        break;
	default:
		$def_kyotenName=$KyotenName;
		break;
}


//出発地p_hatsu
if ($naigai == 'd') {
    $p_hatsuAry = new HierarchyMagoKyotenTabCode_p_hatsu_sub;
} else {
    $p_hatsuAry = new HierarchyMagoKyotenTabCode_p_hatsu;
}
//p_hatsuの生成
$p_hatsu = '';
if (isset($p_hatsuAry->TgDataAry[$naigai][$kyotenId])) {
    $p_hatsu = bindingHatsuAry($p_hatsuAry->TgDataAry[$naigai][$kyotenId]);//例：101,130,133,134
} else {
    $p_hatsu = bindingHatsuAry('');//例：101,130,133,134
}

// CSSファイルやJSファイルを読み込む際に使用
$PathSenmonLink = '/attending/senmon_kaigai/';
if ($naigai == 'd') {
	$PathSenmonLink = '/attending/senmon_kokunai/';
}

// CSVの配列を取得するクラス
$getCsvItem = new getCsvItemClass();

// 量産化用CSVから取得したMASTER情報
$masterCsv = $getCsvItem->getMasterCsv();


// ページコード
$pageCode = $masterCsv[KEY_MASTER_CSV_PAGE_CODE];

// 方面か国か都市か判断する
// 方面
if($masterCsv[KEY_MASTER_CSV_RIGHT_BOX_TYPE] == 'homen')
{
    $categoryType = CATEGORY_TYPE_DEST;
}
// 国
else if($masterCsv[KEY_MASTER_CSV_RIGHT_BOX_TYPE] == 'country')
{
    $categoryType = CATEGORY_TYPE_COUNTRY;
}
// 都市
else if($masterCsv[KEY_MASTER_CSV_RIGHT_BOX_TYPE] == 'city')
{
    $categoryType = CATEGORY_TYPE_CITY;
}

/*---------------------------------
  ぱんくずセット
---------------------------------*/

$SettingData->BreadCrumb16 = $senmon_func->getBreadCrumbSenmon();       // 上部
$SettingData->BreadCrumbFooter16 = $senmon_func->getBreadCrumbSenmon(); // 下部

/*---------------------------------
  タグラインセット
---------------------------------*/

$SettingData->Tagline16 = $senmon_func->getTagLine16();       // 上部

/*---------------------------------
  目的地コードの作成 ここから
---------------------------------*/

$dest_code = $masterCsv[KEY_MASTER_CSV_DEST];
$country_code = $masterCsv[KEY_MASTER_CSV_COUNTRY_LARGE];
$city_code = $masterCsv[KEY_MASTER_CSV_CITY_LARGE];


$tmpmokuteki = $dest_code . '-' . $country_code . '-' . $city_code;
if(empty($dest_code) && empty($country_code) && empty($city_code)){
    //全てNull
    $tmpmokuteki = '';
}

$mokutekiAry = explode('-',$tmpmokuteki);
foreach($mokutekiAry as $no => $para){

    if($no == 0 && !empty($para)){
    //方面

        $homenArySenmon = explode(',',$para);
        //方面が複数(国内の中部北陸だけでしょうbreak!
        if(count($homenArySenmon) > 1){
            foreach($homenArySenmon as $homen){
                if(empty($mokuteki)){
                    $mokuteki = $homen . '--';
                }
                else{
                    $mokuteki .= ',' . $homen . '--';
                }
            }
            break;
        }else{
            $homen_no = $para;
        }
    }elseif($no == 1){
    //国
        if(empty($para)){
            //国が空
            if(empty($mokuteki)){
                $mokuteki = $homen_no . '--';
                }
            else{
                $mokuteki = ',' .$homen_no . '--';
            }
            continue;
        }
        $countryAry = explode(',',$para);
        //パラメータが複数あるものはきっとこれで終わり
        if(count($countryAry) > 1){
            foreach($countryAry as $country){
                if(empty($mokuteki)){
                    $mokuteki = $homen_no . '-' . $country . '-';
                }
                else{
                    $mokuteki .= ',' . $homen_no . '-' . $country . '-';
                }
            }
            break;
        }
        else{
            //単体なのでまだあるかも
            $country_no = $para;
        }
    }elseif($no == 2){
    //都市

        if(empty($para)){
        //都市が空なら終了〜
            $mokuteki = $homen_no . '-' . (isset($country_no) ? $country_no : '') . '-';
            break;
        }

        //パラメータが複数あるものはきっとこれで終わり
        $cityAry = explode(',',$para);
        if(count($cityAry) > 1){
            foreach($cityAry as $city){
                if(empty($mokuteki)){
                    $mokuteki = $homen_no . '-' . $country_no . '-' . $city;
                }
                else{
                    $mokuteki .= ',' . $homen_no . '-' . $country_no . '-' . $city;
                }
            }
        }else{
            //単体でおわり！
            $mokuteki = $homen_no . '-' . $country_no . '-' . $para;
        }
    }
}
/*---------------------------------
  目的地コードの作成 ここまで
---------------------------------*/

// CSVのツアーに絞り込みAPIの情報を付加させるためのクラス
$getCsvCourse = new RecommendCourseService();

$senmonNameEnLower = mb_strtolower($masterCsv[KEY_MASTER_CSV_NAME_EN]);

/*---------------------------------
  人気の都市・観光地 ここから
---------------------------------*/
// memcacheからデータ取得
$popularCountryCityCsv = $SenmonMemCache->getPopularData(serialize($senmonNameEnLower.$kyotenId));
// // memcacheにあるなら
if ($popularCountryCityCsv != false && !empty($popularCountryCityCsv)) {
    $popularCountryCityCsv = unserialize($popularCountryCityCsv);
}else{
    // 人気の都市観光地CSVから取得
    $popularCountryCityCsv = $getCsvItem->getPopularCountryCityCsv($PathArticles.'contents_d/csv/csv_popular_dest.csv');

    if (count($popularCountryCityCsv) > 0) {
        $tour_url_array = array();
        foreach ($popularCountryCityCsv as $key => $value) {
            $popular_tour_url = $value['tour_url'];

            // "/tour/search_i.php","/tour/search_d.php"があればp_hatsu付与。なければ付与しないの仕様追加で
            if (strpos($popular_tour_url, '/tour/search_i.php') === false && strpos($popular_tour_url, '/tour/search_d.php') === false) {
                continue;
            }

            if (strpos($popular_tour_url, '?') === false) {
                $popular_tour_url .= '?';
            } else {
                $popular_tour_url .= '&';
            }
            switch($kyotenId) {
                case 'spk': //北海道（北海道）
                    $popular_tour_url .= 'p_hatsu_sub=101011,101012,101013,101014,101015,101016,101017,101018,101319,101320,101321,101322,101323';
                    break;
                case 'aoj': //青森発（青森）
                    $popular_tour_url .= 'p_hatsu_sub=102022';
                    break;
                case 'sdj': //東北発（岩手、宮城、秋田、山形、福島）
                    $popular_tour_url .= 'p_hatsu_sub=102021,102023,102024,102025,102026';
                    break;
                case 'ibr': //北関東発（茨城、栃木、群馬）
                    $popular_tour_url .= 'p_hatsu_sub=104046,104047,104048';
                    break;
                case 'tyo': //関東（埼玉、千葉、東京、神奈川、山梨）
                    $popular_tour_url .= 'p_hatsu_sub=104041,104042,104043,104044,104045,104049,105052';
                    break;
                case 'toy': //富山発（富山）
                    $popular_tour_url .= 'p_hatsu_sub=103032';
                    break;
                case 'hkr': //石川・福井発（石川、福井）
                    $popular_tour_url .= 'p_hatsu_sub=103031,103033';
                    break;
                case 'mmj': //長野発（長野）
                    $popular_tour_url .= 'p_hatsu_sub=105053';
                    break;
                case 'ngo': //名古屋発（岐阜、愛知、三重）
                    $popular_tour_url .= 'p_hatsu_sub=111111,111113,111114';
                    break;
                case 'szo': //静岡発（静岡）
                    $popular_tour_url .= 'p_hatsu_sub=111112';
                    break;
                case 'osa': //関西発（滋賀、京都、大阪、兵庫、奈良、和歌山）
                    $popular_tour_url .= 'p_hatsu_sub=106061,106062,106063,106064,106065,106066';
                    break;
                case 'izo': //山陰発（鳥取、島根）
                    $popular_tour_url .= 'p_hatsu_sub=107074,107075';
                    break;
                case 'okj': //岡山発（岡山）
                    $popular_tour_url .= 'p_hatsu_sub=107071';
                    break;
                case 'hij': //広島発（広島）
                    $popular_tour_url .= 'p_hatsu_sub=107072';
                    break;
                case 'ubj': //山口発（山口）
                    $popular_tour_url .= 'p_hatsu_sub=107073';
                    break;
                case 'tak': //香川・徳島発（香川、徳島）
                    $popular_tour_url .= 'p_hatsu_sub=108083,108084';
                    break;
                case 'myj': //松山発（愛媛）
                    $popular_tour_url .= 'p_hatsu_sub=108081';
                    break;
                case 'kcz': //高知発（高知）
                    $popular_tour_url .= 'p_hatsu_sub=108082';
                    break;
                case 'fuk': //福岡発（福岡、佐賀）
                    $popular_tour_url .= 'p_hatsu_sub=109090,109091,109094';
                    break;
                case 'ngs': //長崎発（長崎）
                    $popular_tour_url .= 'p_hatsu_sub=109092';
                    break;
                case 'kmj': //熊本発（熊本）
                    $popular_tour_url .= 'p_hatsu_sub=109095';
                    break;
                case 'oit': //大分発（大分）
                    $popular_tour_url .= 'p_hatsu_sub=109093';
                    break;
                case 'kmi': //宮崎発（宮崎）
                    $popular_tour_url .= 'p_hatsu_sub=109096';
                    break;
                case 'koj': //鹿児島発（鹿児島）
                    $popular_tour_url .= 'p_hatsu_sub=109097';
                    break;
                case 'oka': //沖縄発（沖縄）
                    $popular_tour_url .= 'p_hatsu_sub=110000';
                    break;
                case 'kij': // 新潟
                    $popular_tour_url .= 'p_hatsu_sub=105051';
                    break;
                default:
                    $popular_tour_url .= 'p_hatsu=1,2,3,4,5,6,7';
                    break;
            }
            $popularCountryCityCsv[$key]['tour_url'] = $popular_tour_url;

            $tour_url_array[$key] = $popular_tour_url;
        }
    }


    // 人気の都市・観光地の商品を調べる
    $popularCountryCityCsv = $getCsvCourse->set_popular_country_city($tour_url_array,$popularCountryCityCsv);
    // memcacheに設定
    $SenmonMemCache->setPopularData(serialize($senmonNameEnLower.$kyotenId), serialize($popularCountryCityCsv));
}

/*---------------------------------
  人気の都市・観光地 ここまで
---------------------------------*/

// おすすめCSVと拠点特集CSVを特集IFとマージして取得
$tokushu_if = $getCsvCourse->ConvertCSVtoJsonTokushuIF();

// 写真CSVから取得

$photoCsv = $getCsvItem->getCsv($masterCsv[KEY_MASTER_CSV_PHOTO], null);


// イラストメッセージCSVから取得
$illustMessageCsv = $getCsvItem->getCsv($PathArticles.'###KYOTEN###/csv/csv_author_comments.csv', null);

// おすすめツアーフリープランCSVから取得
//$osusumeCsv = $getCsvCourse->ConvertCSVtoJson($getCsvItem->getCsvPath($masterCsv[KEY_MASTER_CSV_TOUR]), $masterCsv[KEY_MASTER_CSV_NAME_JA]);
$osusumeCsv = $tokushu_if['osusume'];

// 拠点自由枠のCSVから取得
$kyotenFreeCsv = $getCsvItem->getKyotenFreeCsv($masterCsv[KEY_MASTER_CSV_KYOTEN_FREE], $masterCsv[KEY_MASTER_CSV_NAME_JA]);

// 拠点特集枠のCSVから取得
$kyotenTokusyuCsv = $getCsvItem->getKyotenTokusyuCsv($masterCsv[KEY_MASTER_CSV_KYOTEN_TOKUSYU], $masterCsv[KEY_MASTER_CSV_NAME_JA]);

// 人気のキーワードCSVから取得
$keyWordCsv = $getCsvItem->getCsv($masterCsv[KEY_MASTER_CSV_KEY_WORD], $masterCsv[KEY_MASTER_CSV_NAME_JA]);

// ガイドCSVから取得
$guideCsv = $getCsvItem->getCsv(MASTER_GUIDE_CSV_URL, $masterCsv[KEY_MASTER_CSV_NAME_JA]);

// 観光情報CSVから取得
if ($_is_smp && isset($masterCsv[KEY_MASTER_CSV_TOURIST_INFOMATION])) {
    $touristInfomationCsv = $getCsvItem->getCsv($masterCsv[KEY_MASTER_CSV_TOURIST_INFOMATION], null);
}


// タイトル画像のURLを変換
if (count($photoCsv) > 0) {
    foreach ($photoCsv as $key => $photo) {
        // 画像ソースがないなら
        if(empty($photo['p_img1_filepath']))
        {
            //削除
            unset($photoCsv[$key]);
            continue;
        }
        $photoCsv[$key][KEY_Q_IMG_PATH] = $senmon_func->imagePathConvert(IMG_TYPE_TITLE_IMAGE, $photo[KEY_Q_IMG_PATH], false);
    }
}
//Indexを詰める
$photoCsv = array_merge($photoCsv);

// 方面ページのみ写真用のデータを取得
$mapImgData = array();
$contetns_header_bool = false;
foreach ($contents_header_country_to_dest as $value) {
    if($senmonNameEnLower == $value){
        $contetns_header_bool = true;
    }
}
if ($categoryType == CATEGORY_TYPE_DEST) {
    $MasterSenmonList = $senmon_func->getMasterSenmonList2017();
    if (empty($GlobalMaster['SenmonMap2017'])) {
        include_once(dirname(__FILE__) . '/GM_SenmonMap2017.php');
        new GM_SenmonMap2017;
    }
    $masterCountry = $GlobalMaster['SenmonMap2017'][$naigai][$masterCsv[KEY_MASTER_CSV_HOMEN]];

    $photoArray = array();
	$pageCaptionArray = array();
    $num = 1;
    $homen = $masterCsv[KEY_MASTER_CSV_HOMEN];
    foreach ($masterCountry[$masterCsv[KEY_MASTER_CSV_HOMEN]] as $key => $value) {

        // 北海道と沖縄の場合
        if($homen === "hokkaido/" || $homen === "okinawa/")
        {
            if(isset($value['map_type']) && $value['map_type'] == 'city' && $value['map_not_display'] != 1)
            {
                $photoArray[$num] = $value['senmon_name'];

				foreach($MasterSenmonList as $_no => $MasterSenmon) {
                    if ($value['senmon_name'] == $MasterSenmon['senmon_name_ja']) {
                        $pageCaptionArray[$num] = $MasterSenmon['page_caption'];
                    }
                }

                $num++;
            }
        }
        else
        {
            if(isset($value['map_type']) && $value['map_type'] == 'country' && $value['map_not_display'] != 1)
            {
                $photoArray[$num] = $value['senmon_name'];

				foreach($MasterSenmonList as $_no => $MasterSenmon) {
                    if ($MasterSenmon['right_box_type'] == 'country' && $value['senmon_name'] == $MasterSenmon['senmon_name_ja']) {
                        $pageCaptionArray[$num] = $MasterSenmon['page_caption'];
                    }
                }

                $num++;
            }
        }
    }

    $mapImgData['photoArray'] = $photoArray;
    $mapImgData['photoDataCSV'] = $photoCsv;
	$mapImgData['pageCaptionArray'] = $pageCaptionArray;

}elseif ($contetns_header_bool == true) {
    $MasterSenmonList = $senmon_func->getMasterSenmonList2017();
    if (empty($GlobalMaster['SenmonMap2017'])) {
        include_once(dirname(__FILE__) . '/GM_SenmonMap2017.php');
        new GM_SenmonMap2017;
    }
    $masterCountry = $GlobalMaster['SenmonMap2017'][$naigai][$masterCsv[KEY_MASTER_CSV_HOMEN]];

    $photoArray = array();
	$pageCaptionArray = array();
    $num = 1;

    foreach ($masterCountry[$masterCsv[KEY_MASTER_CSV_COUNTRY_LOWER]] as $key => $value) {

        if(isset($value['map_type']) && $value['map_type'] == 'city' && $value['map_not_display'] != 1)
        {
            $photoArray[$num] = $value['senmon_name'];

			foreach($MasterSenmonList as $_no => $MasterSenmon) {
                if ($value['senmon_name'] == $MasterSenmon['senmon_name_ja']) {
                    $pageCaptionArray[$num] = $MasterSenmon['page_caption'];
                }
            }

            $num++;
        }
    }

    $mapImgData['photoArray'] = $photoArray;
    $mapImgData['photoDataCSV'] = $photoCsv;
	$mapImgData['pageCaptionArray'] = $pageCaptionArray;
}
