<?php

/**
 *
 * @author kumamoto
 *
 * おすすめコース取得クラス
 *
 */

// 専門店共通で持つCSV読み込みファイル
include_once(dirname(__FILE__) . '../../common_readCsv.php');

require_once 'ApiClient.php';

include_once($HbosSystemDir . "special.php");

class RecommendCourseServiceBot {

    // 説明行の削除行数
    private $delete_row = 2;
    private $recommndCategoryArray = array();
    private $commonReadCsv;

    function __construct() {
        $this->commonReadCsv = new common_readCsv();
    }

    // 量産化CSVから取得するCSVのパスに拠点によって出しわけがある場合があるため対応
    public function getCsvPath($path, $kyotenId=null)
    {
        global $naigai;
        if ($kyotenId === null)
        {
            $kyotenId = $GLOBALS['kyotenId'];
        }
        $newPath = $path;

        if (strpos($path,'###KYOTEN###') !== false)
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


    // 最新CSV10本の中から、それぞれの最新を1本づつ取得して、配列にする
    public function createRequestList($filePaths, $type, $p_category = null,$key) {

        $list = array();
        $values = array();
        $get_key = ($key * (-1)) - 1;
        foreach ($filePaths as $filePath) {
            $csvData = $this->commonReadCsv->readCsv($filePath);

            $_csvData = array();
            if (is_array($csvData) && count($csvData) > 0) {
                foreach ($csvData as $value) {
                    if ($p_category != null && $value[KEY_Q_CATEGORY] !== $p_category) {
                        continue;
                    }
                    $_csvData[] = $value;
                }
            }
            $_value_array = array();
            $value = null;
            // $typeで一番最後の行のツアーを取得する
            foreach($_csvData as $_value) {
                // グループ名が一緒で、ツアーURLにtourの文字列が含まれている。
                if($_value[KEY_Q_GROUP] == $type && strstr($_value[KEY_TOUR_URL], 'tour')) {
                    $_value_array[] = $_value;
                }
            }
            // 最後から指定の要素を取り出す
            $_array = array_slice($_value_array, $get_key, 1);
            if (isset($_array[0])) {
                $values[] = $_array[0];
            }
            // if ($value !== null) {
            //     $sortIndex = array_search(filemtime($filePath), $updatedDates);
            //     array_splice($values, $sortIndex, 0, array($value));
            // }
        }

        if (count($values) >= 1) {
            $labels = array_keys((array)$values[0]);
            array_splice($values, 0, 0, array($labels));
            array_splice($values, 1, 0, array($labels)); // 日本語ラベルのダミーを挿入
        }

        return $values;
    }

    // 配列からJsonに変換して、表示する
    public function ConvertCSVtoJsonBot($list, $display_q_category = null)
    {
        // 1行目はp_course_id、2行目はコース番号などの説明項目なので表示の際に省くために-2する。
        $count = count($list) - $this->delete_row;

        if ($count <= 0) {
            return array();
        }

        // パラメーターのために先頭要素を抜き出す。そして$listから省く。
        $labels = array_shift($list);
        $number = 1;
        // keyを入れていく
        foreach ((array)$labels as $label) {
            if(empty($label))
            {
                // p_point1,p_point2,p_point3のそれぞれの次の列に、それの文字数を表示するカラムがある。しかし項目名が空であるためここで設定する。
                $label = 'p_point'.$number.'_word_count';

                $number++;
            }
            $keys[] = $label;
        }
        // 2行目のコース番号の行を省く
        array_shift($list);
        // $keyと$listを用いて連想配列にする
        $newArray = array();
        $flagArray = array();
        $addCount = 0;
        for ($j = 0; $j < $count; $j++) {
            // $list[$j]が配列なら
            if (is_array($list[$j]))
            {
                // keyの数が合わない場合
                if (count($keys) != count($list[$j])) {
                    // 要素数を合わせるため要素の最後に空値を入れていく
                    for($k=0;$k<count($keys);$k++) {
                        $list[$j][] = '';
                        // 要素数が等しくなったら
                        if(count($keys) == count($list[$j])){
                            break;
                        }
                    }
                }

                if($keys == null || $list[$j] == null) continue;

                $d = array_combine($keys, $list[$j]);
                if ($display_q_category != null && $display_q_category !== $d['q_category']) {
                    continue;
                }

                if (isset($d['tour_url']) && strlen($d['tour_url']) > 0) {

                    $newArray[$addCount] = $d;
                    $addCount++;
                }
                // おすすめCSV(csv_europe_tour2017.csvなど)のツアータブ、フリープランタブのフラグのCSV項目名
                if($d['q_group'] == TOUR_TAB_FLAG_NAME)
                {
                    $flagArray[TOUR_TAB_FLAG_NAME] = $d[KEY_Q_FLAG];
                }

            }
        }

        // 特集IFから取得するための「tour_url」を取得する
        $tour_url_list = array();
        if (count($newArray) > 0) {
            foreach($newArray as $key => $data) {
                if (isset($data['tour_url']) && !empty($data['tour_url'])) {
                    $tour_url_list[$key] = $data['tour_url'];
                }
                $newArray[$key]['p_price'] = '';
            }
        }

        // 特集IFからオススメコースの詳細情報を取得する
        $special_tour_list = $this->searchBot($tour_url_list);

        // オススメコースの詳細情報から金額の下限と上限を取得する
        if (count($special_tour_list) > 0) {
            foreach($special_tour_list as $key => $special_tour)
            {
                $p_course_id = '';
                $q_category = '';
                $q_dest = '';
                $q_country = '';
                $q_city = '';
                $p_course_name = '';
                $p_point1 = '';
                $p_point2 = '';
                $p_point3 = '';
                $p_img1_filepath = '';
                $p_img1_caption = '';
                $q_icon1 = '';
                $q_icon2 = '';
                $p_hatsu_sub_name = '';

                $p_price_min = '';
                $p_price_max = '';
                $price = '';
                if((!isset($newArray[$key]['p_course_id']) || strlen($newArray[$key]['p_course_id']) == 0) && !empty($special_tour->p_course_id))
                {
                    $p_course_id = $special_tour->p_course_id;
                    $newArray[$key]['p_course_id'] = $p_course_id;
                }
                if((!isset($newArray[$key]['q_category']) || strlen($newArray[$key]['q_category']) == 0) && !empty($special_tour->q_category))
                {
                    $q_category = $special_tour->q_category;
                    $newArray[$key]['q_category'] = $q_category;
                }
                if((!isset($newArray[$key]['q_dest']) || strlen($newArray[$key]['q_dest']) == 0) && !empty($special_tour->q_dest))
                {
                    $q_dest = $special_tour->q_dest;
                    $newArray[$key]['q_dest'] = $q_dest;
                }
                if((!isset($newArray[$key]['q_country']) || strlen($newArray[$key]['q_country']) == 0) && !empty($special_tour->q_country))
                {
                    $q_country = $special_tour->q_country;
                    $newArray[$key]['q_country'] = $q_country;
                }
                if((!isset($newArray[$key]['q_city']) || strlen($newArray[$key]['q_city']) == 0) && !empty($special_tour->q_city))
                {
                    $q_city = $special_tour->q_city;
                    $newArray[$key]['q_city'] = $q_city;
                }
                if((!isset($newArray[$key]['p_course_name']) || strlen($newArray[$key]['p_course_name']) == 0) && !empty($special_tour->p_course_name))
                {
                    $p_course_name = $special_tour->p_course_name;
                    $newArray[$key]['p_course_name'] = $p_course_name;
                }
                if((!isset($newArray[$key]['p_point1']) || strlen($newArray[$key]['p_point1']) == 0) && !empty($special_tour->p_point1))
                {
                    $p_point1 = $special_tour->p_point1;
                    $newArray[$key]['p_point1'] = mb_convert_kana($p_point1, "KV","UTF-8"); // 半角ｶﾅを全角カナ
                }
                if((!isset($newArray[$key]['p_point2']) || strlen($newArray[$key]['p_point2']) == 0) && !empty($special_tour->p_point2))
                {
                    $p_point2 = $special_tour->p_point2;
                    $newArray[$key]['p_point2'] = $p_point2;
                }
                if((!isset($newArray[$key]['p_point3']) || strlen($newArray[$key]['p_point3']) == 0) && !empty($special_tour->p_point3))
                {
                    $p_point3 = $special_tour->p_point3;
                    $newArray[$key]['p_point3'] = $p_point3;
                }
                if(!empty($newArray[$key]['p_img1_filepath']) && !strstr($newArray[$key]['p_img1_filepath'], 'http'))
                {
                    $p_img1_filepath = $newArray[$key]['p_img1_filepath'];
                    $p_img1_filepath = 'http://x.hankyu-travel.com/cms_photo_image/image_search_kikan2.php?p_photo_mno=' . $p_img1_filepath;
                    $newArray[$key]['p_img1_filepath'] = $p_img1_filepath;
                }
                if((!isset($newArray[$key]['p_img1_filepath']) || strlen($newArray[$key]['p_img1_filepath']) == 0) && !empty($special_tour->p_img1_filepath))
                {
                    $p_img1_filepath = $special_tour->p_img1_filepath;

                    if (!strstr($p_img1_filepath, 'http')) {
                        $p_img1_filepath = 'http://x.hankyu-travel.com/cms_photo_image/image_search_kikan2.php?p_photo_mno=' . $p_img1_filepath;
                    }
                    $newArray[$key]['p_img1_filepath'] = $p_img1_filepath;
                }
                if((!isset($newArray[$key]['p_img1_caption']) || strlen($newArray[$key]['p_img1_caption']) == 0) && !empty($special_tour->p_img1_caption))
                {
                    $p_img1_caption = $special_tour->p_img1_caption;
                    $newArray[$key]['p_img1_caption'] = $p_img1_caption;
                }
                if((!isset($newArray[$key]['q_icon1']) || strlen($newArray[$key]['q_icon1']) == 0) && !empty($special_tour->q_icon1))
                {
                    $q_icon1 = $special_tour->q_icon1;
                    $newArray[$key]['q_icon1'] = $q_icon1;
                }
                if((!isset($newArray[$key]['q_icon2']) || strlen($newArray[$key]['q_icon2']) == 0) && !empty($special_tour->q_icon2))
                {
                    $q_icon2 = $special_tour->q_icon2;
                    $newArray[$key]['q_icon2'] = $q_icon2;
                }
                if((!isset($newArray[$key]['p_hatsu_sub_name']) || strlen($newArray[$key]['p_hatsu_sub_name']) == 0) && !empty($special_tour->p_dome_departure_info->p_hatsu_sub_name))
                {
                    $p_hatsu_sub_name = $special_tour->p_dome_departure_info->p_hatsu_sub_name;
                    $newArray[$key]['p_hatsu_sub_name'] = $p_hatsu_sub_name;
                }

                if((!isset($newArray[$key]['p_price_min']) || strlen($newArray[$key]['p_price_min']) == 0) && !empty($special_tour->p_price_min))
                {
                    $p_price_min = $special_tour->p_price_min;
                }
                if(!empty($special_tour->p_price_max))
                {
                    $p_price_max = $special_tour->p_price_max;
                }

                // 表示形式にする
				if(!empty($p_price_min) && !empty($p_price_max))
				{
				    if (is_numeric($p_price_min) && is_numeric($p_price_max)) {
					    $price = number_format($p_price_min).'～'.number_format($p_price_max).'円';
				    } else {
					    $price = $p_price_min.'～'.$p_price_max.'円';
				    }
				}
				else if(!empty($p_price_min))
				{
				    if (is_numeric($p_price_min)) {
					    $price = number_format($p_price_min).'円';
				    } else {
					    $price = $p_price_min.'円';
				    }
				}
				else if(!empty($p_price_max))
				{
				    if (is_numeric($p_price_max)) {
					    $price = number_format($p_price_max).'円';
				    } else {
					    $price = $p_price_max.'円';
				    }
				}

                $newArray[$key]['p_price'] = $price;

            }
        }

        $returnArray = array();
        foreach ($newArray as $key => $value) {
            // 受付終了なら
            if($value['p_price'] == '受付終了円') continue;
            // 商品でtour_urlがないなら
			if(preg_match('/商品/',$value['q_group']) && $value['tour_url'] == '') continue;
            // 商品リンク（/tour/search_i.php,/tour/detail_i.php）で金額がなしなら
            if (strpos($value['tour_url'],'search') !== false && empty($value['p_price'])) continue;
            if (strpos($value['tour_url'],'detail') !== false && empty($value['p_price'])) continue;

            // カテゴリの数を計算
            if (isset($this->recommndCategoryArray[$value['q_group']])) {
                $this->recommndCategoryArray[$value['q_group']]++;
            } else {
                $this->recommndCategoryArray[$value['q_group']] = 1;
            }

            $returnArray[] = $newArray[$key];
        }

        $recommend[OSUSUME_COURSE] = $returnArray;
        $recommend[OSUSUME_CATEGORY_NUM]= $this->recommndCategoryArray;
        $recommend[OSUSUME_FLAG]= $flagArray;

        return $recommend;
//		echo json_encode($recommend);
    }

    // 特集IFからオススメコースの詳細情報を取得する
    private function searchBot($tour_url_list) {
        $apiClient = new ApiClient($tour_url_list, dirname(__FILE__).'/WSSearchSpecialService_was.wsdl');
        return $apiClient->request();
    }

}

?>
