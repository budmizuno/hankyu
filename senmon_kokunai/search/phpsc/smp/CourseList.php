<?php 
########################################################################
#
#  商品一覧を表示するために応答データを処理する
# 
#  @copyright  2014 BUD International
#  @version    1.0.0
########################################################################

/********
* include
*********/
include_once($_SERVER['DOCUMENT_ROOT'] . '/sharing/phpsc/path.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/sharing/phpsc/read_master.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/sharing/phpsc/func.php');


class CourseList{
	public $dispTour;				//返却 加工された商品データ
	private $naigai;				//内外

	#=======================
	#	クラス読み込み時
	#=======================
	function __construct($docs,$naigai) {
		
		//初期化
		$this->init($naigai);
		
		//表示用に加工
		$this->make($docs);

		//$this->returnData();
		
	}

	#=======================
	#	初期化
	#=======================
	function init($naigai){
		$this->naigai = $naigai;
		$this->dispTour = new stdClass;
	}
	
	#=======================
	#	応答パラメータ毎に編集
	#=======================
	function make($docs){
		
		global $PathHttpTopN;
		
		foreach($docs as $no => $paramAry){
			
			//一度全て入れる
			$this->dispTour->$no = $paramAry;

			//URL
			$this->dispTour->$no->url = $PathHttpTopN . '/tour/detail_d.php?p_course_id=' . $paramAry->p_course_id . '&p_hei=' . $paramAry->p_hei;
			//料金ヨリマデ
			$this->dispTour->$no->priceMinMax = YoriMade($paramAry->p_price_min, $paramAry->p_price_max, '円', '0');
			//方面表示
			$this->mokuteki($paramAry,$no);
			//画像の初期化
			$this->dispTour->$no->imgFilepath = '/sharing/common14/images/noimage200.png';
			$this->dispTour->$no->imgCaption = 'NoImage';

			//個別処理
			foreach($paramAry as $name => $val){
				switch($name){
					
					case 'p_hatsu_name':
					case 'p_total_amount_divide':
					case 'p_conductor_disp':
					case 'p_brand_name':
						//パラメータ毎のメソッド
						$this->$name($val,$no);
						break;

					case 'p_img1_filepath':
					case 'p_img2_filepath':
					case 'p_img3_filepath':
						//画像関連の処理
						$this->imgMake($val,$name,$no);

						break;
					case 'p_course_name':
					case 'p_sub_title':
					case 'p_point1':
					case 'p_point2':
					case 'p_point3':
					case 'p_point4':
					case 'p_img1_caption':
					case 'p_img2_caption':
					case 'p_img3_caption':
					case 'p_boarding_place':
					case 'p_transport_name':
							$tmp = mb_convert_kana($val, "KV","UTF-8");//半角ｶﾅを全角カナ
							$this->dispTour->$no->$name = str_replace(array("\r\n","\n","\r"), '<br />', $tmp);//改行をタグに変換
						break;
						
					default:
						break;
				}
			}
		}
	}

	#=======================
	#	出発地
	#=======================
	function p_hatsu_name($val,$no){
		$tmpAry = array();
		if(is_array($val)){
			foreach($val as $key => $val){
				$data = explode(',', $val);
				//一意のリストへ
				$tmpAry[] = $data[1];
			}
			$this->dispTour->$no->deptName = implode('、', array_unique($tmpAry));
		}
	}
	#=======================
	#	総額表示区分 海外のみ
	#=======================
	function p_total_amount_divide($val,$no){
		
		//燃油アイコン 海外のみ
		if(isset($val)){
			switch($val){
				case 0:
					$this->dispTour->$no->surchargeIcon = 'icon_air_nenyu_no.gif';
					$this->dispTour->$no->surchargeAlt = '燃油サーチャージ別';
					break;
				case 1:
					$this->dispTour->$no->surchargeIcon = 'icon_air_nenyu.gif';
					$this->dispTour->$no->surchargeAlt = '燃油サーチャージ込み';
					break;
				case 2:
					$this->dispTour->$no->surchargeIcon = 'icon_air_nenyu_none.gif';
					$this->dispTour->$no->surchargeAlt = '燃油サーチャージなし';
			}
		}
	}
	#=======================
	#	添乗員/フリープラン 国内のみ
	#=======================
	function p_conductor_disp($val,$no){
		
		if(isset($val) && $val > 0){
			switch($val){
				case 1:
					$this->dispTour->$no->conductorIcon = 'icon_tour_conductor.gif';
					$this->dispTour->$no->conductorAlt = '添乗員付き';
					break;
				case 2:
					$this->dispTour->$no->conductorIcon = 'icon_freeplan.gif';
					$this->dispTour->$no->conductorAlt = 'フリープラン';
					break;
			}
		}
	}
	#=======================
	#	ブランドロゴ
	#=======================
	function p_brand_name($val,$no){
		global $GlobalMaster;

		if(empty($GlobalMaster['p_mainbrand'])){
			new GM_p_mainbrand($this->naigai);
		}
		$CheckBrand = array_flip($GlobalMaster['p_mainbrand']);
		if(empty($CheckBrand[$val])){
			$this->dispTour->$no->brandIconNo = '05';
		}
		else{
			$this->dispTour->$no->brandIconNo = $CheckBrand[$val];
		}
	}
	#=======================
	#	画像の処理
	#=======================
	function imgMake($val,$name,$no){
		global $CmsPhotoHttp;
		$img = '';
		$imgCaption = '';
	
		$img = $this->dispTour->$no->p_img1_filepath;
		$imgCaption = $this->dispTour->$no->p_img1_caption;
		if($img == NULL){
			$img = $this->dispTour->$no->p_img2_filepath;
			$imgCaption = $this->dispTour->$no->p_img2_caption;
		}
		if($img == NULL){
			$img = $this->dispTour->$no->p_img3_filepath;
			$imgCaption = $this->dispTour->$no->p_img3_caption;
		}
		//それでもNULLならNoImg
		if($img == NULL){
			$this->dispTour->$no->imgFilepath = '/sharing/common14/images/noimage200.png';
			$this->dispTour->$no->imgCaption = 'NoImage';
		}
		else{
			$this->dispTour->$no->imgFilepath = 'http://x.hankyu-travel.com/photo_db/image_search_kikan2.php?p_photo_mno=' . $img;
			$this->dispTour->$no->imgCaption = MyEcho($imgCaption);
		}
	}
	#=======================
	#	国・都市 表示用
	#=======================
	function mokuteki($paramAry,$no){
		$countryAry = NULL;
		$destAry = NULL;
		
		if($this->naigai == 'i'){
			$tmpCountry = $paramAry->p_country_name;
			$tmpCity = $paramAry->p_city_cn;
		}
		else{
			$tmpCountry = $paramAry->p_prefecture_name;
			$tmpCity = $paramAry->p_region_cn;
		}
		if(is_array($tmpCountry)){
			foreach($tmpCountry as $val){
				$data = explode(',', $val);
				$countryAry[$data[1]] = MyEcho($data[2]);
			}
		}
		if(is_array($tmpCity)){
			foreach($tmpCity as $val){
				$data = explode(',', $val);
				$destAry[$data[0]][$data[1]][$data[2]] = $data[3];
			}
		}
		//書き出し用に回す
		if(is_array($destAry)){
			foreach($destAry as $countries){
				foreach($countries as $countryID => $cities){
					if(!empty($Mokuteki)){
						$dispTour->$no->mokuteki .= '、';
					}
					$this->dispTour->$no->mokuteki .= $countryAry[$countryID] . '／';
					$cityStr = NULL;
					foreach($cities as $cityName){
						if(!empty($cityStr)){
							$cityStr .= '・';
						}
						$cityStr .= $cityName;
					}
					$this->dispTour->$no->mokuteki .= $cityStr;
				}
			}
		}
	}
}

?>
