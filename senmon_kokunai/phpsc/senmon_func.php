<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/attending/senmon_kokunai/phpsc/common_readCsv.php');

if (!defined( 'MASTER_SESNMON_2017_CSV_URL' )) {
    define('MASTER_SESNMON_2017_CSV_URL',$_SERVER['DOCUMENT_ROOT'] . '/attending/senmon_kokunai/setting/master_senmon_kokunai_2017.csv');
}

/*
http://x.hankyu-travel.com/photo_db/image_search_kikan2.php?p_photo_mno=00000-ALLUP-99147.jpg・・・w200px ✕ h150px
http://x.hankyu-travel.com/photo_db/image_search_kikan3.php?p_photo_mno=00000-ALLUP-99147.jpg・・・w400px ✕ h300px
http://x.hankyu-travel.com/photo_db/image_search_kikan5.php?p_photo_mno=00000-ALLUP-99147.jpg・・・w800px ✕ h600px
*/
define('IMG_PATH_KIKAN_2',  '//x.hankyu-travel.com/photo_db/image_search_kikan2.php?p_photo_mno=');
define('IMG_PATH_KIKAN_3',  '//x.hankyu-travel.com/photo_db/image_search_kikan3.php?p_photo_mno=');
define('IMG_PATH_KIKAN_5',  '//x.hankyu-travel.com/photo_db/image_search_kikan5.php?p_photo_mno=');

define('IMG_TYPE_URESUZI_RANKING',  1); // 売れ筋ランキング
define('IMG_TYPE_HOTEL_RANKING_S',  2); // ホテルランキング（小）
define('IMG_TYPE_HOTEL_RANKING_M',  3); // ホテルランキング（中）
define('IMG_TYPE_HOTEL_RANKING_L',  4); // ホテルランキング（大）
define('IMG_TYPE_ICHIOSHI',         5); // イチオシ
define('IMG_TYPE_TANTOSHA_OSUSUME', 6); // 担当者おすすめ
define('IMG_TYPE_KYOTEN_SP_S',      7); // 拠点特集枠（小）
define('IMG_TYPE_KYOTEN_SP_M',      8); // 拠点特集枠（中）
define('IMG_TYPE_KYOTEN_SP_L',      9); // 拠点特集枠（大）
define('IMG_TYPE_KYOTEN_FREE',     10); // 拠点自由枠
define('IMG_TYPE_BRAND',           11); // ブランド枠
define('IMG_TYPE_GUIDE_LIST',      12); // 観光ガイド一覧
define('IMG_TYPE_TITLE_IMAGE',     13); // タイトル画像

class Senmon_Func {
	// CSV読み込みオブジェクト
	private $commonReadCsv = null;

	// 専門店用設定ファイルのデータ一覧
	public $senmon_data_list = array();

	// 表示中ページの専門店用設定ファイルのデータ
	public $my_senmon_data = array();

	function __construct($dir_name = null) {
		$this->commonReadCsv = new common_readCsv();
	    if ($dir_name != null) {
		    $this->senmon_data_list = $this->getMasterSenmonList2017();
		    $this->my_senmon_data = $this->getMyMasterSenmon2017($dir_name);
		}
	}

	// 専門店の基本設定
	public function getMasterSenmonList2017()
	{
		if (count($this->senmon_data_list) != 0) {
			return $this->senmon_data_list;
		}

	    return $this->commonReadCsv->readCsv(MASTER_SESNMON_2017_CSV_URL);
	}

	// 専門店の基本設定
	private function getMyMasterSenmon2017($dir_name)
	{
		$data = array();
		if (count($this->senmon_data_list) > 0) {
			foreach ($this->senmon_data_list as $value) {
		        if($dir_name === $value['dirname'])
		        {
		             $data = $value;
		             break;
		        }
			}
		}
		return $data;
	}

	function getMyCategoryType() {
		if (isset($this->my_senmon_data['right_box_type'])) {
			if ($this->my_senmon_data['right_box_type'] == 'homen') {
				return CATEGORY_TYPE_DEST;
			} else if ($this->my_senmon_data['right_box_type'] == 'country') {
				return CATEGORY_TYPE_COUNTRY;
			} else if ($this->my_senmon_data['right_box_type'] == 'city') {
				return CATEGORY_TYPE_CITY;
			}
		}
		return false;
	}


    /*
    【PCの場合の各写真の出し分け】
    image_search_kikan2
        「ランキング」「ホテルランキング（小）」
    image_search_kikan3
        「イチオシ」「担当者おすすめ◯◯旅行」「拠点特集枠（大）」「拠点特集枠（中）」「拠点特集枠（小）」
        「ホテルランキング（大）」「ホテルランキング（中）」「ブランド枠」「観光ガイド一覧」
    image_search_kikan5
        「拠点自由枠」

    【スマホの場合の各写真の出し分け】
    image_search_kikan2
        なし
    image_search_kikan3
        「担当者おすすめ◯◯旅行」「拠点特集枠（中）」「拠点特集枠（小）」「ブランド枠」「観光ガイド一覧」
    image_search_kikan5
        「イチオシ」「ランキング」「拠点自由枠」「拠点特集枠（大）」「ホテルランキング（大）」「ホテルランキング（中）」「ホテルランキング（小）」
    */
    public function imagePathConvert($type, $filepath, $is_smp = null) {
        global $_is_smp;

        // // 「http://」または「https://」が含まれていれば何もせず返却
        // if (strpos($filepath, 'http://') !== false || strpos($filepath, 'https://') !== false) {
        //     return $filepath;
        //
        // // 「http://」または「https://」が含まれていれば何もせず返却
        // }else if (strpos($filepath, '/') !== false) {
        //     return $filepath;
        // }

        // '/'が含まれていれば
        if (strpos($filepath, '/') !== false) {
            // 画像ファイルだけ抽出
            preg_match('/p_photo_mno=(.*)$/', $filepath,$Array);
            $filepath = $Array[1];
        }

        if ($is_smp == null) {
            // 引数の$is_smpがNullかつ$_is_smpが入っていれば$_is_smpを利用。
            // どちらもデータがない場合はPCの判定にする
            $is_smp = (isset($_is_smp) ? $_is_smp : false);
        }

        if ($_is_smp == true) {
            // スマホの場合

            switch ($type) {
                // 「担当者おすすめ◯◯旅行」「拠点特集枠（中）」「拠点特集枠（小）」「ブランド枠」「観光ガイド一覧」
                case IMG_TYPE_TANTOSHA_OSUSUME:
                case IMG_TYPE_KYOTEN_SP_M:
                case IMG_TYPE_KYOTEN_SP_S:
                case IMG_TYPE_BRAND:
                case IMG_TYPE_GUIDE_LIST:
                    $filepath = IMG_PATH_KIKAN_3 . $filepath;
                    break;

                // 「イチオシ」「ランキング」「拠点自由枠」「拠点特集枠（大）」「ホテルランキング（大）」「ホテルランキング（中）」「ホテルランキング（小）」
                case IMG_TYPE_ICHIOSHI:
                case IMG_TYPE_URESUZI_RANKING:
                case IMG_TYPE_KYOTEN_FREE:
                case IMG_TYPE_KYOTEN_SP_L:
                case IMG_TYPE_HOTEL_RANKING_L:
                case IMG_TYPE_HOTEL_RANKING_M:
                case IMG_TYPE_HOTEL_RANKING_S:
                case IMG_TYPE_TITLE_IMAGE:
                    $filepath = IMG_PATH_KIKAN_5 . $filepath;
                    break;
                default:
                    $filepath = IMG_PATH_KIKAN_3 . $filepath;
                    break;
            }

        } else {
            // PCの場合

            switch ($type) {
                // 「ランキング」「ホテルランキング（小）」
                case IMG_TYPE_URESUZI_RANKING:
                case IMG_TYPE_HOTEL_RANKING_S:
                    $filepath = IMG_PATH_KIKAN_2 . $filepath;
                    break;

                // 「イチオシ」「担当者おすすめ◯◯旅行」「拠点特集枠（大）」「拠点特集枠（中）」「拠点特集枠（小）」
                // 「ホテルランキング（大）」「ホテルランキング（中）」「ブランド枠」「観光ガイド一覧」
                case IMG_TYPE_ICHIOSHI:
                case IMG_TYPE_TANTOSHA_OSUSUME:
                case IMG_TYPE_KYOTEN_SP_L:
                case IMG_TYPE_KYOTEN_SP_M:
                case IMG_TYPE_KYOTEN_SP_S:
                case IMG_TYPE_HOTEL_RANKING_L:
                case IMG_TYPE_HOTEL_RANKING_M:
                case IMG_TYPE_BRAND:
                case IMG_TYPE_GUIDE_LIST:
                    $filepath = IMG_PATH_KIKAN_3 . $filepath;
                    break;

                // 「拠点自由枠」
                case IMG_TYPE_KYOTEN_FREE:
                case IMG_TYPE_TITLE_IMAGE:
                    $filepath = IMG_PATH_KIKAN_5 . $filepath;
                    break;

                // 上記以外
                default:
                    $filepath = IMG_PATH_KIKAN_3 . $filepath;
                    break;
            }
        }
        return $filepath;
    }

    #=======================
    #    パンくず生成処理
    #=======================
    function getBreadCrumbSenmon(){
        global $path16;
        global $masterCsv;
        global $naigai;
        global $kyotenId;
        global $_is_smp;

        $op = '&gt;';
        $breadCrumbInner = '';

        if ($_is_smp == false) {
            $breadCrumbInner .= <<<EOD
<li typeof="v:Breadcrumb"><a href="{$path16->HttpsTop}/" rel="v:url" property="v:title">阪急交通社トップ</a>{$op}</li>
EOD;

            if ($naigai == 'i') {
                $breadCrumbInner .= <<<EOD
<li typeof="v:Breadcrumb"><a href="/kaigai/" rel="v:url" property="v:title">海外旅行</a>{$op}</li>
EOD;
            } else if ($naigai == 'd') {
                $breadCrumbInner .= <<<EOD
<li typeof="v:Breadcrumb"><a href="/kokunai/" rel="v:url" property="v:title">国内旅行</a>{$op}</li>
EOD;
            }


            if ($masterCsv['right_box_type'] == 'homen') {
                $breadCrumbInner .= <<<EOD
<li><strong>{$this->getKyotenNameByKyotenId($kyotenId)} {$masterCsv['senmon_name_ja']}</strong></li>
EOD;
            } else if ($masterCsv['right_box_type'] == 'country') {
                $breadCrumbInner .= <<<EOD
<li><strong>{$this->getKyotenNameByKyotenId($kyotenId)} {$masterCsv['senmon_name_ja']}</strong></li>
EOD;
            } else if ($masterCsv['right_box_type'] == 'city') {
                $breadCrumbInner .= <<<EOD
<li typeof="v:Breadcrumb"><a href="/{$masterCsv['first_level']}" rel="v:url" property="v:title">{$masterCsv['first_level_name']}</a>{$op}</li>
EOD;
                $breadCrumbInner .= <<<EOD
<li><strong>{$this->getKyotenNameByKyotenId($kyotenId)} {$masterCsv['senmon_name_ja']}</strong></li>
EOD;
            }
        } else {
            $breadCrumbInner .= <<<EOD
<li typeof="v:Breadcrumb"><a href="{$path16->HttpsTop}/" rel="v:url" property="v:title">トップ</a>{$op}</li>
EOD;

            if ($naigai == 'i') {
                $breadCrumbInner .= <<<EOD
<li typeof="v:Breadcrumb"><a href="/kaigai/" rel="v:url" property="v:title">海外</a>{$op}</li>
EOD;
            } else if ($naigai == 'd') {
                $breadCrumbInner .= <<<EOD
<li typeof="v:Breadcrumb"><a href="/kokunai/" rel="v:url" property="v:title">国内</a>{$op}</li>
EOD;
            }


            if ($masterCsv['right_box_type'] == 'homen') {
                $breadCrumbInner .= <<<EOD
<li><strong>{$masterCsv['senmon_name_ja']}</strong></li>
EOD;
            } else if ($masterCsv['right_box_type'] == 'country') {
                $breadCrumbInner .= <<<EOD
<li><strong>{$masterCsv['senmon_name_ja']}</strong></li>
EOD;
            } else if ($masterCsv['right_box_type'] == 'city') {
                $breadCrumbInner .= <<<EOD
<li typeof="v:Breadcrumb"><a href="/{$masterCsv['first_level']}" rel="v:url" property="v:title">{$masterCsv['first_level_name']}</a>{$op}</li>
EOD;
                $breadCrumbInner .= <<<EOD
<li><strong>{$masterCsv['senmon_name_ja']}</strong></li>
EOD;
            }
        }
        return $breadCrumbInner;
    }

    function getKyotenNameByKyotenId($id){
        $def_kyotenName = '';
        switch($id){
            case 'spk':
                $def_kyotenName ='北海道発';
                break;
            case 'aoj':
                $def_kyotenName ='青森発';
                break;
            case 'sdj':
                $def_kyotenName ='東北発';
                break;
            case 'tyo':
                $def_kyotenName ='関東発';
                break;
            case 'ibr':
                $def_kyotenName ='北関東発';
                break;
            case 'kij':
                $def_kyotenName ='新潟発';
                break;
            case 'mmj':
                $def_kyotenName ='長野発';
                break;
            case 'ngo':
                $def_kyotenName ='名古屋発';
                break;
            case 'hkr':
                $def_kyotenName ='北陸発';
                break;
            case 'szo':
                $def_kyotenName ='静岡発';
                break;
            case 'osa':
                $def_kyotenName ='関西発';
                break;
            case 'okj':
                $def_kyotenName ='岡山発';
                break;
            case 'izo':
                $def_kyotenName ='山陰発';
                break;
            case 'hij':
                $def_kyotenName ='広島発';
                break;
            case 'ubj':
                $def_kyotenName ='山口発';
                break;
            case 'tak':
                $def_kyotenName ='香川・徳島発';
                break;
            case 'myj':
                $def_kyotenName ='松山発';
                break;
            case 'kcz':
                $def_kyotenName ='高知発';
                break;
            case 'fuk':
                $def_kyotenName ='福岡発';
                break;
            case 'ngs':
                $def_kyotenName ='長崎発';
                break;
            case 'kmj':
                $def_kyotenName ='熊本発';
                break;
            case 'oit':
                $def_kyotenName ='大分発';
                break;
            case 'kmi':
                $def_kyotenName ='宮崎発';
                break;
            case 'koj':
                $def_kyotenName ='鹿児島発';
                break;
            case 'oka':
                $def_kyotenName ='沖縄発';
                break;
            case 'toy':
                $def_kyotenName ='富山発';
                break;
        }
        return $def_kyotenName;
    }
    
    #=======================
    #    タグライン生成処理
    #=======================
    function getTagLine16(){
        global $SettingData;
        return str_replace('h1', 'h2', $SettingData->Tagline16);
    }
}

?>
