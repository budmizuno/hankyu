<?php
/*
#################################################################
	各ページで持つ設定ファイル 専門店
#################################################################
*/

date_default_timezone_set('Asia/Tokyo');
//include_once($PathSharing14 . 'phpsc/class_searchBox.php');
include_once($PathSharing14 . 'phpsc/kyotenArticlesInfo.php');

/**************************************
 *  表示拠点　
 *  $SetData->dispKyotenId  setDispKyoten.phpで判定している
 ***************************************/
if (!empty($SetData->dispKyotenId)) {
    $kyotenId = $SetData->dispKyotenId;
} else {
    $kyotenId = preg_replace('/\.php.*/', '', basename($_SERVER["SCRIPT_NAME"]));
}

/**************************************
 * 共通設定
 ***************************************/

$naigai = $SettingData->PageAttribute;

if (empty($GlobalMaster['Senmon'])) {
    new GM_Senmon;
}

$MyPath = substr($PathRelativeMyDir, 1);

if ($GlobalMaster['Senmon'][$MyPath]) {
    $mokutekiCode = $GlobalMaster['Senmon'][$MyPath]['req'];
    $houmenCode = $GlobalMaster['Senmon'][$MyPath]['homen'];
    $senmonName = $GlobalMaster['Senmon'][$MyPath]['senmon_name'];
    if ($MyPath == 'thailand/' || $MyPath == 'swiss/' || $MyPath == 'holland/' || $MyPath == 'belgium/' || $MyPath == 'portugal/' || $MyPath == 'egypt/' || $MyPath == 'peru/' || $MyPath == 'newzealand/') {
        $senmonPagetype = 'B';
    } else {
        $senmonPagetype = $GlobalMaster['Senmon'][$MyPath]['page_type'];
    }
    $senmonRightBoxtype = $GlobalMaster['Senmon'][$MyPath]['right_box_type'];
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

if (empty($GlobalMaster['kyotenUse'])) {
    new GM_kyotenUse;
}
if ($kyotenId != 'index') {
    foreach ($GlobalMaster['kyotenUse'] as $kyotendata) {
        if ($kyotendata['kyotenId'] == $kyotenId && $naigai == $kyotendata['naigai']) {
            $kyotenName = $kyotendata['kyotenName'] . '発';
            break;
        }
    }
} else {
    $kyotenName = '';
}


/**************************************
 * 検索ボックスの設定
 ***************************************/
//sharing/common14/phpsc/class_searchBox.php

if ($kyotenId == 'index') {
    $KyotenID = 'top';
} else {
    $KyotenID = $kyotenId;
}

if ($naigai == 'd') {
    $searchBoxtemp = $PathSenmonCommon . 'sharing/inc/Searchbox_d.php';
//    $searchBoxtempD2 = $PathSharing14 . 'inc/Searchbox_d02.php';
} else {
    if ($senmonPagetype == 'A') {
        $searchBoxtemp = $_SERVER['DOCUMENT_ROOT'] . '/attending/senmon_kaigai/sharing/inc/Searchbox_i.php';
    } else {
        //国までしかない専門店用
        $searchBoxtemp = $_SERVER['DOCUMENT_ROOT'] . '/attending/senmon_kaigai/sharing/inc/Searchbox_i.php';
    }
}

$rqPara['p_mokuteki'] = $mokuteki;

/**************************************
 * ファセットの数
 ***************************************/
$facetPara['p_mokuteki'] = $mokutekiCode;

/**************************************
 * map
 ***************************************/
if (empty($GlobalMaster['kyotenUse'])) {
    new GM_kyotenUse;
}
//拠点名取得
$mapKyotenName = '';
if ($kyotenId != 'index') {
    foreach ($GlobalMaster['kyotenUse'] as $dataAry) {
        if ($dataAry['kyotenId'] == $kyotenId && $dataAry['naigai'] == $naigai) {
            $mapKyotenName = $dataAry['kyotenName'];

        }
    }
}

//内外
$msNaigai = $naigai;
//url
$msPath = $MyPath;
//方面
$msHomen = $houmenCode;
//出発地（101,130,133）
$msHatsu = $p_hatsu;

// 都市ページではmap使用しないので
if($categoryType != CATEGORY_TYPE_CITY)
{
    //引数　国内海外　方面　パス　出発地パラメータ　マップタイプ　拠点名
    $MapDisplay = new MapLink($msNaigai, $msHomen, $msPath, $msHatsu,  null, $mapKyotenName);
    //$MapDisplay->setKyotenFlg("", !empty($KyotenMainId) ? $KyotenMainId : '');
    //ファセット取得
    $MapDisplay->getFacet();
    $mapHtml = $MapDisplay->display();
    $mapAllFacet = $MapDisplay->selectKyotenFacet;

}

/**************************************
 * ニューサーチのツアーの設定
 ***************************************/
$tourNaigai = $naigai;
if ($kyotenId == 'index') {
    $tourKyotenID = 'top';
} else {
    $tourKyotenID = $kyotenId;
}
$tourRqPara = array(
    'p_mokuteki' => $mokutekiCode
);


/**************************************
 *  setting情報のartとcsvのデータ取得
 *     戻り値：$GlobalTourList
 ***************************************/

$myPath = '/' . isset($MyPath) ? $MyPath : '';
getGlobalTourList($myPath, $kyotenId);


/*******************************************************
 * ようこそテキスト
 * cmsToHtmlYoukoso　csvで入力したテキストを表示する
 ******************************************************/
class cmsToHtmlYoukoso
{

    function __construct()
    {
        $this->getCsv();
    }

    //csvからデータ取得
    function getCsv()
    {
        global $PathArticles, $naigai, $senmonName;

        $File = $PathArticles . 'contents_' . $naigai . '/csv/csv_author_comments.csv';
        $dataAry = $this->ReadCsv($File);

        foreach ($dataAry as $data) {
            if ($data['q_category'] == $senmonName && $data['q_group'] == 'ようこそテキスト') {
                if (!empty($data['q_point'])) {
                    $this->txt = strip_tags(rtrim($data['q_point']), '<br><br /><br/>');
                } else {
                    $this->txt = '';
                }
                break;
            }
        }

    }

    //csvファイルの読み込み
    function ReadCsv($File)
    {

        $handle = fopen($File, "r");
        if ($handle) {
            $num = 0;
            while (!feof($handle)) {
                $buffer = rtrim(fgets($handle, 9999));    //日本語ファイルはfgetcsv使うのやめておく
                $buffer = str_replace('"', '', $buffer);    //ダブルクォーテーション不要
                //空白行はサヨナラ
                if (empty($buffer)) {
                    continue;
                }
                //1行目も日本語名なのでいらない
                if ($num == 1) {
                    ++$num;
                    continue;
                }

                $data = explode("\t", $buffer);
                if ($num == 0) {
                    $keyAry = array();
                    foreach ($data as $no => $val) {
                        if (empty($val)) {
                            continue;
                        }
                        $keyAry[$no] = $val;
                    }
                    ++$num;
                } else {
                    foreach ($keyAry as $no => $key) {
                        $csvdata[$key] = $data[$no];
                    }
                    $CsvAry[] = $csvdata;
                }
            }
            fclose($handle);
        }
        return $CsvAry;
    }
}


/*******************************************************
 * 人気の観光地
 * cmsToHtmlKankouchi　csvで入力したツアーを表示する
 ******************************************************/
//@include_once($HbosSystemDir . "special.php");
class cmsToHtmlKankouchi
{
    var $dspattern = "/\/tour\/search_d\.php|\/tour\/search_i\.php|\/tour\/detail_d\.php|\/tour\/detail_i\.php|\/tour_d\/|\/tour_i\//";

    function __construct()
    {
        global $kyotenId;
        $this->getCsv();

        if (!empty($this->tourList)) {

            if ($kyotenId != 'index') {
                $this->actSoap();
                $this->makeHtml();
            } else {
                $this->html = '';
            }

        } else {
            $this->html = '';
        }
    }

    function makeHtml()
    {
        global $senmonName;

        $dataAry = $this->dataReduction();
        $tourAry = $dataAry['artKankou'][$senmonName];
        if (!is_array($tourAry)) {
            $this->html = '';
            return false;
        }
        $li = '';

        foreach ($tourAry as $ttl => $GlobalTourData) {

            if (strpos($GlobalTourData['price_min_max'], '受付終了') !== false || $GlobalTourData['price_min_max'] == 0 || $GlobalTourData['price_min_max'] == '') {
                //URLがDSかどうか判定
                if ($GlobalTourData['url_type'] != 'noDS') {
                    //料金なしはとばす
                    continue;
                    //$GlobalTourData['price_min_max'] = '受付終了';
                }
            }

            $li .= <<<EOD
		<li><a href="{$GlobalTourData['tour_url']}">{$GlobalTourData['q_title']}</a></li>
EOD;
            $tourNum++;

        }
        if (!empty($li)) {
            $this->html = $li;
        }


    }

    //元とSOAPデータの必用なとこだけ合体
    function dataReduction()
    {
        global $CmsPhotoHttp;
        global $SettingData;


        //取得データから必要なパラメータを入れていく
        //xmlデータと取得データの整理
        $mk = '円';

        //記事データをくるくる
        foreach ($this->tourList as $k => $v) {

            foreach ($v as $k1 => $v1) {
                foreach ($v1 as $k2 => $v2) {
                    $KeyUrl = $v2['tour_url'];
                    $tourAry[$k][$k1][$k2]['tour_url'] = $v2['tour_url'];
                    $tourAry[$k][$k1][$k2]['q_title'] = $v2['q_title'];
                    //DSのURLなら金額を入れる
                    if (preg_match($this->dspattern, $v2['tour_url'])) {

                        //金額は最初に入れておく
                        $min = $this->soapObj->special_response[$KeyUrl]->p_price_min;
                        $max = $this->soapObj->special_response[$KeyUrl]->p_price_max;

                        $tourAry[$k][$k1][$k2]['price_min_max'] = dispPrice($min, $max, $mk);
                    } //DS以外ならフラグ立てておく
                    else {
                        $tourAry[$k][$k1][$k2]['url_type'] = 'noDS';
                    }
                }
            }

        }
        unset($this->soapObj);
        return $tourAry;
    }


    //SOAPアクセス
    function actSoap()
    {
        global $PathBudDataResSoap;
        global $SpecialWebaccess;
        global $inUrlList;

        # ************************
        # 特集IFでSOAP情報取得
        # ************************
        //特集IFでURLから各パラメータを取得
        $UrlList = '';

        foreach ($this->tourList as $kyoten => $kyotenObj) {
            foreach ($kyotenObj as $key1 => $val1) {
                foreach ($val1 as $key2 => $val2) {
                    //DSのURLかどうか
                    if (is_numeric($key2) && !empty($val2['tour_url']) && preg_match($this->dspattern, $val2['tour_url'])) {
                        $UrlList[$val2['tour_url']]['tour_url'] = $val2['tour_url'];
                    }
                }
            }
        }
        //特集IFをたたく
        if (is_array($UrlList)) {
            $inUrlList = $UrlList;
            //SOQP情報取得
            $this->soapObj = new SoapSpecial();
        }

    }

    //csvからデータ取得
    function getCsv()
    {
        global $PathArticles, $naigai, $senmonName, $p_hatsu, $p_hatsuAry;

        $File = $PathArticles . 'contents_' . $naigai . '/csv/csv_popular_dest.csv';
        $dataAry = $this->ReadCsv($File);
        $cnt = 0;
        $this->tourList = '';
        foreach ($dataAry as $data) {
            if ($data['q_category'] == $senmonName && $data['q_group'] == '人気の観光地') {
                if (!empty($data['q_title']) && !empty($data['tour_url'])) {
                    $obj2 = $data['tour_url'];
                    $tmp = $obj2;
                    //p　br　&nbsp;をとる
                    $tmp = strip_tags($tmp, '<strong>');
                    $tmp = str_replace('&nbsp;', '', $tmp);
                    $tmp = trim($tmp);
                    if (empty($tmp)) {
                        $obj2 = $tmp;
                    }
                    $this->tourList['artKankou'][$senmonName][$cnt]['q_title'] = trim($data['q_title']);

                    $urlpara = trim(strval(strip_tags($obj2, '<strong>')));
                    //DSのURLかどうか
                    if (preg_match($this->dspattern, $urlpara)) {

                        //発地パラ追加
                        if ($naigai == 'd') {
                            if (empty($p_hatsu)) {
                                //全発地
                                $paradata = '';
                                foreach ($p_hatsuAry->TgDataAry['d'] as $para) {
                                    foreach ($para as $code => $name) {
                                        if (empty($paradata)) {
                                            $paradata = $code;
                                        } else {
                                            $paradata .= ',' . $code;
                                        }
                                    }
                                }
                                $para = '&p_hatsu_sub=' . $paradata;
                            } else {
                                //選択発地だけ
                                $para = '&p_hatsu_sub=' . $p_hatsu;
                            }
                        } else {
                            if (empty($p_hatsu)) {
                                //全発地
                                $paradata = '';
                                foreach ($p_hatsuAry->TgDataAry['i'] as $para) {
                                    foreach ($para as $code => $name) {
                                        if (empty($paradata)) {
                                            $paradata = $code;
                                        } else {
                                            $paradata .= ',' . $code;
                                        }
                                    }
                                }

                                $para = '&p_hatsu=' . $paradata;
                            } else {
                                //選択発地だけ
                                $para = '&p_hatsu=' . $p_hatsu;
                            }

                        }
                        $url = $urlpara . $para;
                    } else {
                        $url = $urlpara;
                    }

                    $this->tourList['artKankou'][$senmonName][$cnt]['tour_url'] = $url;
                    $cnt++;
                }
            }
        }

    }

    //csvファイルの読み込み
    function ReadCsv($File)
    {

        $handle = fopen($File, "r");
        if ($handle) {
            $num = 0;
            while (!feof($handle)) {
                $buffer = rtrim(fgets($handle, 9999));    //日本語ファイルはfgetcsv使うのやめておく
                $buffer = str_replace('"', '', $buffer);    //ダブルクォーテーション不要
                //空白行はサヨナラ
                if (empty($buffer)) {
                    continue;
                }
                //1行目も日本語名なのでいらない
                if ($num == 1) {
                    ++$num;
                    continue;
                }

                $data = explode("\t", $buffer);
                if ($num == 0) {
                    $keyAry = array();
                    foreach ($data as $no => $val) {
                        if (empty($val)) {
                            continue;
                        }
                        $keyAry[$no] = $val;
                    }
                    ++$num;
                } else {
                    foreach ($keyAry as $no => $key) {
                        $csvdata[$key] = $data[$no];
                    }
                    $CsvAry[] = $csvdata;
                }
            }
            fclose($handle);
        }
        return $CsvAry;
    }
}


/*******************************************************
 * おすすめツアー
 * cmsToHtmlOsusume　csvで入力したツアーを表示する
 ******************************************************/
class cmsToHtmlOsusume
{

    public $numFlg;//ツアーがあるかないかの判定用
    public $html;//表示用

    function __construct($csv = '', $tour_max, $temp, $temp2 = '')
    {
        global $GlobalTourList, $SettingData, $AttendingPath, $kyotenId;

        $this->temp = $temp;
        $this->temp2 = $temp2;
        if (!empty($csv)) {
            $this->csv = $csv;
        }

        if ($kyotenId != 'index') {
            if ($this->csv) {
                $this->getCms();
                $this->makeHtml($tour_max);
            }
        } else {
            //indexページはcsvを全国から取得
            $this->getCsvForTop();
            $this->makeHtmlTop($tour_max);
        }
    }

    //各拠点のcsvを更新日順でツアーの配列にする（トップ用）
    function getCsvForTop()
    {
        global $SettingData, $PathArticles, $GlobalMaster;

        //ブログ記事設定項目を読み込む
        if (!is_array($SettingData->ArticleConfig)) {
            return false;
        }
        if (empty($GlobalMaster['Kyoten'])) {
            new GM_Kyoten;
        }
        //設定ファイル(setting)から
        foreach ($SettingData->ArticleConfig as $artKey => $artConfigAry) {
            if ($artConfigAry['kyoten_di'] === 'ALL_i') {
                foreach ($GlobalMaster['Kyoten'] as $no => $kyotenDataAry) {
                    $kyoten_di_Ary[$artKey][] = $kyotenDataAry['GChildID'] . '_i' . $artConfigAry['blog_path'];
                    //$kyoten_di_Ary[$artKey][] = $kyotenDataAry['GChildID'] . '_d'. $artConfigAry['blog_path'];
                }
            } elseif ($artConfigAry['kyoten_di'] === 'ALL_d') {
                foreach ($GlobalMaster['Kyoten'] as $no => $kyotenDataAry) {
                    $kyoten_di_Ary[$artKey][] = $kyotenDataAry['GChildID'] . '_d' . $artConfigAry['blog_path'];
                }

            }
        }

        //ここから拠点ごとのブログをくるくる
        if (is_array($kyoten_di_Ary)) {
            //複数拠点のブログの場合
            foreach ($kyoten_di_Ary as $artKey => $kyoten_diAry) {

                //記事拡張子
                $extension = '/*.' . $SettingData->ArticleConfig[$artKey]['file_type'];
                //記事タイトル
                $article_title_cms = $SettingData->ArticleConfig[$artKey]['article_title'];
                if (strpos($article_title_cms, ',') !== false) {
                    //記事タイトルが複数指定の場合配列にしとく
                    $article_title_cms = explode(',', $article_title_cms);
                }

                foreach ($kyoten_diAry as $no => $kyoten_di_data) {
                    $artDir = $PathArticles . $kyoten_di_data;

                    //ディレクトリがない			または	直下に記事ファイル(.art .csv)がない
                    if (!file_exists($artDir) || is_array(glob($artDir . $extension)) === false) {
                        continue;
                    }
                    //CSVの更新日時で配列に

                    if (strpos($extension, 'csv') !== false) {
                        if (!empty($artDir) && strrpos($_SERVER['DOCUMENT_ROOT'], '/var/www/html/cms') !== FALSE) {

                            if (file_exists($artDir)) {
                                $FileUpDate = filemtime($artDir);
                                //$update = date("Y/m/d",$FileUpDate);
                                $kyotenAry = explode('/', $kyoten_di_data);
                                $artCsvFileList[$FileUpDate . '-' . $kyotenAry[0]][$artKey][$article_title_cms] = $artDir;
                            }
                        }

                    }
                }
            }
        }
        unset($kyoten_di_Ary);
        //更新日でソート
        if (is_array($artCsvFileList)) {
            krsort($artCsvFileList);

            foreach ($artCsvFileList as $time => $artCsvFile) {
                //特集IFから返却された値も入る
                $tourData = new pageArticlesInfo($artFileList, $artCsvFile, '');
                //更新が新たしい順の拠点ごとのツアーリスト
                if (is_array($tourData->tourList)) {
                    $this->tourObjTop[$time] = $tourData->tourList[$artKey][$article_title_cms];
                }
                unset($tourData);
            }
        }
    }

    //トップ用
    function makeHtmlTop($tour_max)
    {
        global $GlobalMaster;
        if (is_array($this->tourObjTop)) {
            $tourNum = '';
            $KyotenName = '';

            foreach ($this->tourObjTop as $time => $tourAry) {
                foreach ($tourAry as $GlobalTourData) {
                    if (empty($GlobalTourData['tour_url'])) {
                        continue;
                    }

                    //最大表示数を超えたら終わり
                    if (!empty($tour_max)) {
                        if ($tourNum >= $tour_max) {
                            break;
                        }
                    }
                    if (strpos($GlobalTourData['price_min_max'], '受付終了') !== false || $GlobalTourData['price_min_max'] == 0 || $GlobalTourData['price_min_max'] == '') {
                        //URLがDSかどうか判定
                        if ($GlobalTourData['url_type'] != 'noDS') {
                            //料金なしはとばす
                            continue;
                            //$GlobalTourData['price_min_max'] = '受付終了';
                        }
                    }
                    $timeAry = explode('-', $time);
                    $kyotenidAry = explode('_', $timeAry[1]);
                    if (empty($GlobalMaster['kyotenUse'])) {
                        new GM_kyotenUse;
                    }
                    //拠点名取得
                    foreach ($GlobalMaster['kyotenUse'] as $dataAry) {
                        if ($dataAry['kyotenId'] == $kyotenidAry[0] && $dataAry['naigai'] == 'i') {
                            $KyotenName = $dataAry['kyotenName'];
                        }
                    }
                    ob_start();
                    include($this->temp);
                    $Html .= ob_get_contents();
                    ob_end_clean();

                    $tourNum++;
                    $this->numFlg = 1;
                    continue 2;
                }
            }
            $this->html = $Html;
        } else {
            $this->numFlg = '';
        }

    }

    //拠点用
    function makeHtml($tour_max)
    {
        global $kyotenId, $GlobalMaster, $Sharing14;

        $this->numFlg = '';
        if (!empty($this->csvdata)) {
            $tourAry = $this->csvdata;
        }
        //$tourNum=0;
        $imgNo = '';
        $htmlI = '';
        $htmlD = '';
        $imgALT = '';

        if (is_array($tourAry)) {

            $tourNum = '';
            $tourNum2 = '';
            $html1 = '';
            $html2 = '';
            $html = '';
            foreach ($tourAry as $key => $GlobalTourData) {

                if (empty($GlobalTourData['tour_url'])) {
                    continue;
                }
                //最大表示数を超えたら終わり
                if (!empty($tour_max)) {
                    if ($tourNum >= $tour_max) {
                        break;
                    }
                }
                if (strpos($GlobalTourData['price_min_max'], '受付終了') !== false || $GlobalTourData['price_min_max'] == 0 || $GlobalTourData['price_min_max'] == '') {
                    //URLがDSかどうか判定
                    if ($GlobalTourData['url_type'] != 'noDS') {
                        //料金なしはとばす
                        continue;
                        //$GlobalTourData['price_min_max'] = '受付終了';
                    }
                }
                if ($GlobalTourData['q_group'] == '写真枠（160×120）' || $GlobalTourData['q_group'] == 'テキスト枠') {

                    if ($GlobalTourData['q_group'] == '写真枠（160×120）') {
                        ob_start();
                        include($this->temp);
                        $Html1 .= ob_get_contents();
                        ob_end_clean();
                        $tourNum++;
                    } elseif ($GlobalTourData['q_group'] == 'テキスト枠') {
                        ob_start();
                        include($this->temp2);
                        $Html2 .= ob_get_contents();
                        ob_end_clean();
                        $tourNum2++;
                    }
                    $this->numFlg = 1;
                }

            }
            $this->html = $Html1 . $Html2;
        } else {
            $this->numFlg = '';
            $this->html = '';
        }
    }


    //拠点用cmsからデータ取得
    function getCms()
    {
        global $GlobalTourList, $SettingData;

        //記事タイトル取得
        $set_art_title = $SettingData->ArticleConfig["$this->csv"]['article_title'];

        if (!is_array($GlobalTourList["$this->csv"]["$set_art_title"])) {
            $this->csvdata = '';
        } else {
            //ツアーリスト
            $this->csvdata = $GlobalTourList["$this->csv"]["$set_art_title"];
        }
    }

}

/*******************************************************
 * 人気キーワード
 * csvで入力したデータを表示する
 ******************************************************/
function cmsKeywordCsv($id, $tour_max = '', $title_max_width = '', $tmp_name = '')
{
    global $GlobalTourList, $SettingData, $AttendingPath, $kyotenId;

    //記事テンプレート取得
    $incPath = $AttendingIncPath . $tmp_name;
    //記事タイトル取得
    $set_art_title = $SettingData->ArticleConfig["$id"]['article_title'];
    //拠点IDを取得
    $kyoten_id = $SettingData->ArticleConfig["$id"]['kyoten_di'];

    if (!is_array($GlobalTourList["$id"]["$set_art_title"])) {
        //該当記事のデータが無いときは何もしない
        return false;
    }
    //海外・国内、タイトル毎のツアーリスト
    $tourAry = $GlobalTourList["$id"]["$set_art_title"];
    $tourCnt = count($tourAry);

    if (!is_array($tourAry)) {
        return false;
    }
    $li = '';
    foreach ($tourAry as $GlobalTourData) {

        if ($GlobalTourData['q_group'] != 'キーワードから探す') {
            continue;
        }
        if (empty($GlobalTourData['tour_url']) && empty($GlobalTourData['q_keyword'])) {
            continue;
        }

        //最大表示数を超えたら終わり
        if (!empty($tour_max)) {
            if ($tourNum >= $tour_max) {
                break;
            }
        }
        if (strpos($GlobalTourData['price_min_max'], '受付終了') !== false || $GlobalTourData['price_min_max'] == 0 || $GlobalTourData['price_min_max'] == '') {
            //URLがDSかどうか判定
            if ($GlobalTourData['url_type'] != 'noDS') {
                //料金なしはとばす
                continue;
                //$GlobalTourData['price_min_max'] = '受付終了';
            }
        }
        $li .= <<<EOD
		<li>・<a href="{$GlobalTourData['tour_url']}">{$GlobalTourData['q_keyword']}</a></li>
EOD;
        $tourNum++;

    }

    if (!empty($li)) {
        include_once($tmp_name);
    }

}

/**************************************
 *  関連リンクのパス
 ***************************************/
$kanrenPath = str_replace('/' . basename(getcwd()), '', $AttendingPath);

?>
