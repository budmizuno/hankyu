<?php

/*
#################################################################
	専門店共通で持つ各CSVの読み込みクラス
#################################################################
*/

// 専門店共通で持つCSV読み込みファイル
include_once(dirname(__FILE__) . '/common_readCsv.php');
include_once(dirname(__FILE__) . '/SenmonMemCache.php');

class getCsvItemClass
{
    private $commonReadCsv;
    public $masterCsvAllData;

    function __construct()
    {
        $this->commonReadCsv = new common_readCsv();

    }

    // 量産化CSVから取得するCSVのパスに拠点によって出しわけがある場合があるため対応
    public function getCsvPath($path)
    {
        global $naigai,$kyotenId;

        $newPath = $path;

        if(strpos($path,'###KYOTEN###') !== false)
        {
            // 文字列を置き換える
            if ($kyotenId === 'index') {
                $newPath = str_replace('###KYOTEN###','contents_'.$naigai , $path);
            } else {
                $newPath = str_replace('###KYOTEN###',$kyotenId.'_'.$naigai , $path);
            }
        }

        return $newPath;
    }

    function getCsv($filePath, $p_category = null)
    {
        global $SenmonMemCache;

        // ガイドCSVの場合memcacheから取得
        if($filePath == MASTER_GUIDE_CSV_URL){
            // memcacheからデータ取得
            $csvData = $SenmonMemCache->getGuideCsvData(serialize('master_senmon_kokunai_guide_2017'));
            // memcacheにあるなら
            if ($csvData != false && !empty($csvData)) {
                $csvData = unserialize($csvData);
            }else{
                $csvData = $this->commonReadCsv->readCsv($this->getCsvPath($filePath));
                // memcacheに設定
                $SenmonMemCache->setGuideCsvData(serialize('master_senmon_kokunai_guide_2017'), serialize($csvData));
            }

        }else{
            $csvData = $this->commonReadCsv->readCsv($this->getCsvPath($filePath));
        }

        $_csvData = array();
        if (is_array($csvData) && count($csvData) > 0) {
            foreach ($csvData as $value) {

                if ($p_category != null && isset($value[KEY_Q_CATEGORY]) && $value[KEY_Q_CATEGORY] !== $p_category) {
                    continue;
                }
                $_csvData[] = $value;
            }
        }

        return $_csvData;
    }

    // 専門店の量産化用CSVから基本情報を取得
    public function getMasterCsv()
    {
        // 現在のスクリプトのパスから/index.phpや/tyo.phpを削除
        $dirname = preg_replace('/\/.{1,5}\.php$/','',$_SERVER['SCRIPT_NAME']);

        $this->getMasterCsvAllData();

        $array = array();
        if (is_array($this->masterCsvAllData) && count($this->masterCsvAllData) > 0) {
            foreach ($this->masterCsvAllData as $value) {
                if($dirname == $value[KEY_MASTER_CSV_DIRNAME])
                {
                    $array = $value;
                    break;
                }
            }
        }

        /*
        // memcacheからデータ取得
        $SenmonMemCache = new SenmonMemCache();

        $array = $SenmonMemCache->getMasterCsvData(serialize(array($dirname)));
        if ($array != false && !empty($array)) {
            $array = unserialize($array);
        }
        if (!is_array($array) || count($array) <= 0) {
            $this->getMasterCsvAllData();

            $array = array();
            if (is_array($this->masterCsvAllData) && count($this->masterCsvAllData) > 0) {
                foreach ($this->masterCsvAllData as $value) {
                    if($dirname == $value[KEY_MASTER_CSV_DIRNAME])
                    {
                        $array = $value;
                        break;
                    }
                }
            }
            $SenmonMemCache->setMasterCsvData(serialize(array($dirname)), serialize($array));
        }
        */
        return $array;
    }

    public function getMasterCsvAllData()
    {
        global $SenmonMemCache;

        // memcacheから取得
        $this->masterCsvAllData = $SenmonMemCache->getMasterCsvData(serialize('master_senmon_kokunai_2017'));
        if ($this->masterCsvAllData != false && !empty($this->masterCsvAllData)) {
            $this->masterCsvAllData = unserialize($this->masterCsvAllData);
        }
        else{
            $this->masterCsvAllData = $this->commonReadCsv->readCsv(MASTER_CSV_URL);
            $SenmonMemCache->setMasterCsvData(serialize('master_senmon_kokunai_2017'), serialize($this->masterCsvAllData));
        }
    }

    // 拠点自由枠CSVから取得
    public function getKyotenFreeCsv($filePath, $p_category = null)
    {
        $csvData = $this->commonReadCsv->readCsv($this->getCsvPath($filePath));

        $array = array();

        if (is_array($csvData) && count($csvData) > 0) {
        foreach ($csvData as $value) {

                if ($p_category != null && $value[KEY_Q_CATEGORY] !== $p_category) {
                    continue;
                }

                if (empty($value[KEY_TOUR_URL]) && empty($value[KEY_Q_BRIGHTCOVE_ID]) && empty($value[KEY_Q_THETA_ID])) {
                    continue;
                }

                if(!empty($value[KEY_Q_FLAG]))
                {
                // 同一フラグ内で一番上のタイトルを優先
                    if(empty($array[$value[KEY_Q_GROUP]][$value[KEY_Q_FLAG]][KEY_Q_THEME])  && !empty($value[KEY_Q_THEME]))
                    {
                        // 文字列の長さを制限
                        if(40 < mb_strlen($value[KEY_Q_THEME],"UTF-8"))
                        {
                            $value[KEY_Q_THEME] = mb_substr($value[KEY_Q_THEME],0,40,"UTF-8").'...';
                        }
                        $array[$value[KEY_Q_GROUP]][$value[KEY_Q_FLAG]][KEY_Q_THEME] = $value[KEY_Q_THEME];
                    }

                    $array[$value[KEY_Q_GROUP]][$value[KEY_Q_FLAG]][KEY_Q_IMG_PATH][] = $value[KEY_Q_IMG_PATH];
                    $array[$value[KEY_Q_GROUP]][$value[KEY_Q_FLAG]][KEY_Q_IMG_CAPTION][] = $value[KEY_Q_IMG_CAPTION];
                    $array[$value[KEY_Q_GROUP]][$value[KEY_Q_FLAG]][KEY_TOUR_URL][] = $value[KEY_TOUR_URL];
                    $array[$value[KEY_Q_GROUP]][$value[KEY_Q_FLAG]][KEY_Q_GROUP][] = $value[KEY_Q_GROUP];
                    $array[$value[KEY_Q_GROUP]][$value[KEY_Q_FLAG]][KEY_Q_BRIGHTCOVE_ID][] = isset($value[KEY_Q_BRIGHTCOVE_ID]) ? $value[KEY_Q_BRIGHTCOVE_ID] : null;
                    $array[$value[KEY_Q_GROUP]][$value[KEY_Q_FLAG]][KEY_Q_THETA_ID][] = isset($value[KEY_Q_THETA_ID]) ? $value[KEY_Q_THETA_ID] : null;
                    $array[$value[KEY_Q_GROUP]][$value[KEY_Q_FLAG]][KEY_Q_THETA_URL][] = isset($value[KEY_Q_THETA_URL]) ? $value[KEY_Q_THETA_URL] : null;

                }
            }
        }

        return $array;

    }

    // 拠点特集のCSVから取得
    public function getKyotenTokusyuCsv($filePath, $p_category = null)
    {
        global $getCsvCourse,$tokushu_if;

        // 金額をAPI通して取得する
//        $csvData = $getCsvCourse->ConvertCSVtoJsonKyoten($this->getCsvPath($filePath), $p_category);
        $csvData = $tokushu_if['kyoten'];

        $array = array();
        $num = array();

        if (is_array($csvData) && count($csvData) > 0) {
        foreach ($csvData as $key => $value) {

            if ($p_category != null && $value[KEY_Q_CATEGORY] !== $p_category) {
                continue;
            }

            if(!empty($value[KEY_Q_FLAG]))
            {

                // ツアーかフリープランか
                if(strpos($value[KEY_Q_GROUP],TOUR_STRING) !== false)
                {
                    $type = TOUR_STRING;
                }
                else
                {
                    $type = FREE_PLAN_STRING;
                }

                // 読み物か商品か
                if(strpos($value[KEY_Q_GROUP],YOMIMONO_STRING) !== false)
                {
                    $type2 = YOMIMONO_STRING;
                }
                else
                {
                    $type2 = SYOHIN_STRING;
                }

                // それぞれの拠点フラグの個数
                if(empty($num[$type][$value[KEY_Q_FLAG]][$type2]))
                {
                    $num[$type][$value[KEY_Q_FLAG]][$type2] = 0;
                }

                // 同一フラグ内で一番上のタイトルを優先
                if(empty($array[$type][$value[KEY_Q_FLAG]][KEY_Q_THEME]) && !empty($value[KEY_Q_THEME]))
                {
                    // 文字列の長さを制限
                    if(40 < mb_strlen($value[KEY_Q_THEME],"UTF-8"))
                    {
                        $value[KEY_Q_THEME] = mb_substr($value[KEY_Q_THEME],0,20, "UTF-8").'...';
                    }
                    $array[$type][$value[KEY_Q_FLAG]][KEY_Q_THEME] = $value[KEY_Q_THEME];
                }

                    $display_type = KYOTEN_TOKUSYU_DISPLAY_TYPE_YOMIMONO_1;
                    if ($value[KEY_Q_GROUP] === KYOTEN_TOKUSYU_Q_GROUP_STRING_TOUR_YOMIMONO_2
                            || $value[KEY_Q_GROUP] === KYOTEN_TOKUSYU_Q_GROUP_STRING_FREEPLAN_YOMIMONO_2)
                    {
                        $display_type = KYOTEN_TOKUSYU_DISPLAY_TYPE_YOMIMONO_2;
                    }
                    else if ($value[KEY_Q_GROUP] === KYOTEN_TOKUSYU_Q_GROUP_STRING_TOUR_SHOHIN_1
                            || $value[KEY_Q_GROUP] === KYOTEN_TOKUSYU_Q_GROUP_STRING_FREEPLAN_SHOHIN_1)
                    {
                        $display_type = KYOTEN_TOKUSYU_DISPLAY_TYPE_SHOHIN_1;
                    }
                    else if ($value[KEY_Q_GROUP] === KYOTEN_TOKUSYU_Q_GROUP_STRING_TOUR_SHOHIN_2
                            || $value[KEY_Q_GROUP] === KYOTEN_TOKUSYU_Q_GROUP_STRING_FREEPLAN_SHOHIN_2)
                    {
                        $display_type = KYOTEN_TOKUSYU_DISPLAY_TYPE_SHOHIN_2;
                    }


                $array[$type][$value[KEY_Q_FLAG]][$type2][$num[$type][$value[KEY_Q_FLAG]][$type2]][KEY_Q_COURSE_NAME] = $value[KEY_Q_COURSE_NAME];
                $array[$type][$value[KEY_Q_FLAG]][$type2][$num[$type][$value[KEY_Q_FLAG]][$type2]]['p_point1'] = $value['p_point1'];
                $array[$type][$value[KEY_Q_FLAG]][$type2][$num[$type][$value[KEY_Q_FLAG]][$type2]][KEY_Q_IMG_PATH] = $value[KEY_Q_IMG_PATH];
                $array[$type][$value[KEY_Q_FLAG]][$type2][$num[$type][$value[KEY_Q_FLAG]][$type2]][KEY_Q_IMG_CAPTION] = $value[KEY_Q_IMG_CAPTION];
                $array[$type][$value[KEY_Q_FLAG]][$type2][$num[$type][$value[KEY_Q_FLAG]][$type2]][KEY_TOUR_URL] = $value[KEY_TOUR_URL];
                $array[$type][$value[KEY_Q_FLAG]][$type2][$num[$type][$value[KEY_Q_FLAG]][$type2]][KEY_Q_GROUP] = $value[KEY_Q_GROUP];
                $array[$type][$value[KEY_Q_FLAG]][$type2][$num[$type][$value[KEY_Q_FLAG]][$type2]]['display_type'] = $display_type;
                $array[$type][$value[KEY_Q_FLAG]][$type2][$num[$type][$value[KEY_Q_FLAG]][$type2]][KEY_Q_PRICE] = isset($value[KEY_Q_PRICE]) ? $value[KEY_Q_PRICE] : null;
                $array[$type][$value[KEY_Q_FLAG]][$type2][$num[$type][$value[KEY_Q_FLAG]][$type2]][KEY_Q_BRIGHTCOVE_ID] = isset($value[KEY_Q_BRIGHTCOVE_ID]) ? $value[KEY_Q_BRIGHTCOVE_ID] : null;
                $array[$type][$value[KEY_Q_FLAG]][$type2][$num[$type][$value[KEY_Q_FLAG]][$type2]][KEY_Q_THETA_ID] = isset($value[KEY_Q_THETA_ID]) ? $value[KEY_Q_THETA_ID] : null;
                $array[$type][$value[KEY_Q_FLAG]][$type2][$num[$type][$value[KEY_Q_FLAG]][$type2]][KEY_Q_THETA_URL] = isset($value[KEY_Q_THETA_URL]) ? $value[KEY_Q_THETA_URL] : null;

                $num[$type][$value[KEY_Q_FLAG]][$type2]++;

            }
        }
        }

        return $array;
    }

    // 人気の都市観光地CSVから取得
    public function getPopularCountryCityCsv($filePath)
    {
        global $masterCsv;

        $csvData = $this->commonReadCsv->readCsv($filePath);

        $array = array();
        if (is_array($csvData) && count($csvData) > 0) {
            foreach ($csvData as $value) {

                if($masterCsv[KEY_MASTER_CSV_NAME_JA] == $value[KEY_Q_CATEGORY] && !empty($value['q_title']))
                {
                    $array[] = $value;
                }
            }
        }

        return $array;
    }




}
