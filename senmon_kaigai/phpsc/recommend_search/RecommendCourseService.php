<?php

/**
 *
 * @author kumamoto
 *
 * おすすめコース取得クラス
 *
 */
require_once 'ApiClient.php';

include_once($HbosSystemDir . "special.php");


class RecommendCourseService {

	// 説明行の削除行数
	private $delete_row = 2;

	private $recommndCategoryArray = array();

	// CSVからJsonに変換して、表示する。特集IFを使用している
	public function ConvertCSVtoJsonTokushuIF(){

		global $masterCsv,$severEnvironment,$getCsvItem,$inUrlList;

		$list = array();
		$labels = array();
		$keys = array();
		$newArray = array();
		$flagArray = array();
		$tour_url_list = array();
		$count = 0;
		$number = 1;
		$addCount = 0;

		// おすすめCSVのtour_url ここから

		// CSVのデータを取得。タブで区切る
		$list = $this->readCSV($getCsvItem->getCsvPath($masterCsv[KEY_MASTER_CSV_TOUR]), "\t");

		// 1行目はp_course_id、2行目はコース番号などの説明項目なので表示の際に省くために-2する。
		$count = count($list) - $this->delete_row;

		if ($count <= 0) {
//			return array();
		}

		// パラメーターのために先頭要素を抜き出す。そして$listから省く。
		$labels = array_shift($list);
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

				$d = array_combine($keys, $list[$j]);
				if ($masterCsv[KEY_MASTER_CSV_NAME_JA] != null && $masterCsv[KEY_MASTER_CSV_NAME_JA] !== $d['q_category']) {
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
		if (count($newArray) > 0) {
			foreach((array)$newArray as $key => $data) {
				if (isset($data['tour_url']) && !empty($data['tour_url'])) {
					$tour_url_list[$key] = $data['tour_url'];
				}
				$newArray[$key]['p_price'] = '';
			}
		}

		// おすすめCSVのtour_url ここまで

		// 拠点特集CSVのtour_url ここから

		$list = array();
		$labels = array();
		$keys = array();
		$newArrayKyoten = array();
		$tour_url_list_kyoten = array();
		$count = 0;
		$number = 1;
		$addCount = 0;

		// CSVのデータを取得。タブで区切る
		$list = $this->readCSV($getCsvItem->getCsvPath($masterCsv[KEY_MASTER_CSV_KYOTEN_TOKUSYU]), "\t");


		// 1行目はp_course_id、2行目はコース番号などの説明項目なので表示の際に省くために-2する。
		$count = count($list) - $this->delete_row;

		if ($count <= 0) {
//			return array();
		}

		// パラメーターのために先頭要素を抜き出す。そして$listから省く。
		$labels = array_shift($list);
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

				$d = array_combine($keys, $list[$j]);
				if ($masterCsv[KEY_MASTER_CSV_NAME_JA] != null && isset($d['q_category']) && $masterCsv[KEY_MASTER_CSV_NAME_JA] !== $d['q_category']) {
					continue;
				}

//				if (isset($d['tour_url']) && strlen($d['tour_url']) > 0) {
					$newArrayKyoten[$addCount] = $d;
					$addCount++;
//				}
			}
		}


		// 特集IFから取得するための「tour_url」を取得する
		if (count($newArrayKyoten) > 0) {
			foreach((array)$newArrayKyoten as $key => $data) {
				if (isset($data['tour_url']) && !empty($data['tour_url'])) {
					$tour_url_list_kyoten[$key] = $data['tour_url'];
				}
			}
		}

		// 拠点特集CSVのtour_url ここまで



		$tour_url_list_key = array_keys($tour_url_list);
		$tour_url_list_kyoten_key = array_keys($tour_url_list_kyoten);

		$tour_url_merge = array();
		$tour_url_merge = array_merge($tour_url_list, $tour_url_list_kyoten);

		$key_master = array();

		$tour_url_list_count = count($tour_url_list);
		// マージしたkeyとおすすめCSV,拠点特集CSVのそれぞれのキーを関連させる
		foreach ((array)$tour_url_merge as $key => $value) {
			if($key < $tour_url_list_count){
				$key_master[$key] = $tour_url_list_key[$key];
			}
			else{
				$key_master[$key] = $tour_url_list_kyoten_key[$key-$tour_url_list_count];
			}
		}



		if(empty($tour_url_merge)) return array();

		if($severEnvironment == LEAFNET){
			// 特集IFからオススメコースの詳細情報を取得する
			$special_tour_list = $this->search($tour_url_merge);
		}else {

			// 重複値のkeyを取得
			$multi_array = array();
			// 値を入れるkeyだけ取得
			$multi_array_slice = array();
			foreach ((array)$tour_url_merge as $key => $value) {
				foreach ((array)$tour_url_merge as $key2 => $value2) {
					if($value == $value2 && $key != $key2){
						if(!in_array($key2.','.$key, $multi_array)){
							$multi_array[] = $key.','.$key2;
							$multi_array_slice[] = $key2;
						}
					}
				}
			}

			sort($multi_array_slice);
			//配列で重複している物を削除する
			$multi_array_slice = array_unique($multi_array_slice);
			//キーが飛び飛びになっているので、キーを振り直す
			$multi_array_slice = array_values($multi_array_slice);

			// DS商品でないものや海外のツアー
			$no_ds_array =  array();
			$sub_naigai_array = array();
			foreach ($tour_url_merge as $key => $value) {
				if(strpos($value,'search_d') !== false || strpos($value,'detail_d') !== false ){
					$sub_naigai_array[] = $key;
				}
				if(strpos($value,'search') === false && strpos($value,'detail') === false ){
					$no_ds_array[] = $key;
					$tour_url_merge[$key] = '';	// DS商品でないものでもspecialで値が返ってくる時があるので空にする
				}
			}


			// 50本ごとに区切れる特集IFの関数を使う
			$inUrlList = $tour_url_merge;
			//SOQP情報取得
			$this->soapObj = new SoapSpecial();

			$special_tour_list = $this->soapObj->result->return->p_ab_special_response;

			$sub_special_tour_list = $this->soapObj->result->return->p_dome_special_response;


			// 空や重複地、他内外の配列
			$kari_array = array_merge($no_ds_array, $sub_naigai_array,$multi_array_slice);

			//配列で重複している物を削除する
			$kari_array = array_unique($kari_array);
			//キーが飛び飛びになっているので、キーを振り直す
			$kari_array = array_values($kari_array);

			sort($kari_array);

			// ''だけ該当keyに入れる
			foreach ($kari_array as $value) {
				array_splice($special_tour_list, $value, 0, '');
			}

			// 他内外のツアーが複数かどうか
			$sub_special_tour_list_array =  array();
			if(is_array($sub_special_tour_list)){
				$sub_special_tour_list_array = $sub_special_tour_list;
			}else{
				$sub_special_tour_list_array[] =  $sub_special_tour_list;
			}

			// 他内外のツアーをいれる
			foreach ((array)$sub_naigai_array as $key => $value) {
				$special_tour_list[$value] = $sub_special_tour_list_array[$key];
			}

			// 重複値をコピー
			foreach ((array)$multi_array as $value) {
				$array = explode(",", $value);
				$special_tour_list[$array[1]] = $special_tour_list[$array[0]];
			}
		}

		// 特集IFの返却値をおすすめと拠点特集の配列に分ける
		foreach ((array)$special_tour_list as $key => $value) {
			if($key < $tour_url_list_count){
				$special_tour_list_osusume[$key_master[$key]] = $value;
			}
			else{
				$special_tour_list_kyoten[$key_master[$key]] = $value;
			}
		}

		$return_array = array();
		$return_array['osusume'][OSUSUME_COURSE] = $this->getMergeArray($special_tour_list_osusume,$newArray,1);
		$return_array['osusume'][OSUSUME_CATEGORY_NUM]= $this->recommndCategoryArray;
		$return_array['osusume'][OSUSUME_FLAG]= $flagArray;

		$return_array['kyoten'] = $this->getMergeArray($special_tour_list_kyoten,$newArrayKyoten,2);

		return $return_array;
	}

	private function getMergeArray($special_tour_list,$newArray,$kind){

		foreach((array)$special_tour_list as $key => $special_tour)
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
			if((!isset($newArray[$key]['p_hatsu_name']) || strlen($newArray[$key]['p_hatsu_name']) == 0) && !empty($special_tour->p_hatsu_name))
			{
				$p_hatsu_name = $special_tour->p_hatsu_name;
				$newArray[$key]['p_hatsu_name'] = $p_hatsu_name;
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

		$returnArray = array();
		foreach ((array)$newArray as $key => $value) {
			// 受付終了なら
			if($value['p_price'] == '受付終了円') continue;
			// 商品でtour_urlがないなら
			if(preg_match('/商品/',$value['q_group']) && $value['tour_url'] == '') continue;
			// 商品リンク（/tour/search_i.php,/tour/detail_i.php）で金額がなしなら
			if (strpos($value['tour_url'],'search') !== false && empty($value['p_price'])) continue;
			if (strpos($value['tour_url'],'detail') !== false && empty($value['p_price'])) continue;

			// おすすめCSVなら
			if($kind == 1){
				// カテゴリの数を計算
				if (isset($this->recommndCategoryArray[$value['q_group']])) {
					$this->recommndCategoryArray[$value['q_group']]++;
				} else {
					$this->recommndCategoryArray[$value['q_group']] = 1;
				}
			}

			$returnArray[] = $newArray[$key];
		}

		return $returnArray;
	}

/*
	// CSVからJsonに変換して、表示する
	public function ConvertCSVtoJson($csv_path, $display_q_category = null)
	{
		global $severEnvironment;

		// CSVのデータを取得。タブで区切る
		$list = $this->readCSV($csv_path, "\t");

		// 1行目はp_course_id、2行目はコース番号などの説明項目なので表示の際に省くために-2する。
		$count = count($list) - $this->delete_row;

		if ($count <= 0) {
			return array();
		}

		// パラメーターのために先頭要素を抜き出す。そして$listから省く。
		$labels = array_shift($list);
		$number = 1;
		// keyを入れていく
		foreach ($labels as $label) {
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

				$d = array_combine($keys, $list[$j]);
				if ($display_q_category != null && $display_q_category !== $d['q_category']) {
					continue;
				}

				if (isset($d['tour_url']) && strlen($d['tour_url']) > 0) {
					// カテゴリの数を計算
					if (isset($this->recommndCategoryArray[$d['q_group']])) {
						$this->recommndCategoryArray[$d['q_group']]++;
					} else {
						$this->recommndCategoryArray[$d['q_group']] = 1;
					}

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

		if($severEnvironment == LEAFNET){
			// 特集IFからオススメコースの詳細情報を取得する
			$special_tour_list = $this->search($tour_url_list);
		}else {
			// 50本ごとに区切れる特集IFの関数を使う
			$inUrlList = $tour_url_list;
			//SOQP情報取得
			$this->soapObj = new SoapSpecial();

			$special_tour_list = $this->soapObj->result->return->p_dome_special_response;
		}


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
					$newArray[$key]['p_point1'] = $p_point1;
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
				if((!isset($newArray[$key]['p_hatsu_name']) || strlen($newArray[$key]['p_hatsu_name']) == 0) && !empty($special_tour->p_hatsu_name))
				{
					$p_hatsu_name = $special_tour->p_hatsu_name;
					$newArray[$key]['p_hatsu_name'] = $p_hatsu_name;
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

			$returnArray[] = $newArray[$key];
		}


		$recommend[OSUSUME_COURSE] = $returnArray;
		$recommend[OSUSUME_CATEGORY_NUM]= $this->recommndCategoryArray;
		$recommend[OSUSUME_FLAG]= $flagArray;

		return $recommend;
//		echo json_encode($recommend);
	}

	// CSVからJsonに変換して、表示する。拠点特集で使用
	public function ConvertCSVtoJsonKyoten($csv_path, $display_q_category = null)
	{
		global $severEnvironment;

		// CSVのデータを取得。タブで区切る
		$list = $this->readCSV($csv_path, "\t");

		// 1行目はp_course_id、2行目はコース番号などの説明項目なので表示の際に省くために-2する。
		$count = count($list) - $this->delete_row;

		if ($count <= 0) {
			return array();
		}

		// パラメーターのために先頭要素を抜き出す。そして$listから省く。
		$labels = array_shift($list);
		$number = 1;
		// keyを入れていく
		foreach ($labels as $label) {
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

				$d = array_combine($keys, $list[$j]);
				if ($display_q_category != null && !isset($d['q_category']) && $display_q_category !== $d['q_category']) {
					continue;
				}

//				if (isset($d['tour_url']) && strlen($d['tour_url']) > 0) {
					$newArray[$addCount] = $d;
					$addCount++;
//				}
			}
		}

		// 特集IFから取得するための「tour_url」を取得する
		$tour_url_list = array();
		if (count($newArray) > 0) {
			foreach($newArray as $key => $data) {
				if (isset($data['tour_url']) && !empty($data['tour_url'])) {
					$tour_url_list[$key] = $data['tour_url'];
				}
			}
		}

		if($severEnvironment == LEAFNET){
			// 特集IFからオススメコースの詳細情報を取得する
			$special_tour_list = $this->search($tour_url_list);
		}else {
			// 50本ごとに区切れる特集IFの関数を使う
			$inUrlList = $tour_url_list;
			//SOQP情報取得
			$this->soapObj = new SoapSpecial();

			$special_tour_list = $this->soapObj->result->return->p_dome_special_response;
		}

		// APIから取得できたツアーがなかったら
		if(empty($special_tour_list) )
		{
			// CVSから取得したまま返却
			return $newArray;
		}

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
				$newArray[$key]['p_point1'] = $p_point1;
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

		$returnArray = array();
		foreach ($newArray as $key => $value) {
			// 受付終了なら
			if(isset($value['p_price']) && $value['p_price'] == '受付終了円') continue;
			// 商品でtour_urlがないなら
			if(preg_match('/商品/',$value['q_group']) && $value['tour_url'] == '') continue;
			// 商品リンク（/tour/search_i.php,/tour/detail_i.php）で金額がなしなら
			if (strpos($value['tour_url'],'search') !== false && empty($value['p_price'])) continue;
			if (strpos($value['tour_url'],'detail') !== false && empty($value['p_price'])) continue;

			$returnArray[] = $newArray[$key];
		}


		return $returnArray;
	}
*/
	// 人気の都市・観光地の商品を調べる
	public function set_popular_country_city($tour_url_list,$popularCountryCityCsv)
	{
		// 特集IFからオススメコースの詳細情報を取得する
		$special_tour_list = $this->search($tour_url_list);

		// オススメコースの詳細情報から金額の下限と上限を取得する
		if (count($special_tour_list) > 0) {
			foreach((array)$special_tour_list as $key => $special_tour)
			{
				if(empty($special_tour->p_price_min) && empty($special_tour->p_price_max)){
					// コースがないため削除
					unset($popularCountryCityCsv[$key]);
				}
			}
			// indexを詰める
			$popularCountryCityCsv = array_values($popularCountryCityCsv);
		}

		return $popularCountryCityCsv;
	}


	// 特集IFからオススメコースの詳細情報を取得する
	private function search($tour_url_list) {
		$apiClient = new ApiClient($tour_url_list, dirname(__FILE__).'/WSSearchSpecialService_was.wsdl');
		return $apiClient->request();
	}

	/**
	 * CSVのデータを全取得する。
	 * @param unknown $file_path CSVファイルのファイルパス：例："/data/csv.php" とか "http://www.xxx.jp/data/data.csv"
	 * @param unknown $delimiter 項目区切りのディリミタ('"', "\t" など）
	 * @return multitype:
	 */
	private function readCSV ($file_path, $delimiter)
	{
		//返却リスト初期化
		$list = array();

		//ファイルを開く
		if (file_exists($file_path)) {
			if ( (($handle = fopen($file_path, "r")) != FALSE) )
			{
				//ファイルの各行について・・・。空になるまでWhile()
				while (($LineData = fgets($handle, 4096)) !== false)
				{
					// 改行コードを削除
					$LineData = str_replace(array("\r", "\n"), '', $LineData);

					// タブで区切る
					$list[] = explode($delimiter, $LineData);
				}

				fclose($handle);

			} else {
			}
		}

		return $list;
	}


}

?>
