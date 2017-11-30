<?php
########################################################################
#
#  絞り込み検索のajax先PHP
#
#  @copyright  2014 BUD International
#  @version    1.0.0
########################################################################
//正しいheaderを出力しないと、IEでエラーになる
header("Content-Type: text/html; charset=UTF-8");

/********
* include
*********/
include_once($_SERVER['DOCUMENT_ROOT'] .'/sharing/phpsc/path.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/sharing/phpsc/read_master.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/sharing/phpsc/func.php');

include_once(dirname(__FILE__) . '/phpsc/smp/mySearch.php');
include_once(dirname(__FILE__) . '/phpsc/smp/SetSolrParamCustom.php');
include_once($SharingPSPath .'FacetRewrite.php');
include_once(dirname(__FILE__) . '/phpsc/smp/GetFacet.php');

//種別が設定されていないものは無効
if( empty($_REQUEST['kind']) ){exit;}

//リクエストパラメータ初期化、設定
$Set = new SetSolrParam();
$ReqPara = $Set->solrReqParamAry[$Set->naigai];

//リクエストパラメータ特殊処理（自パラメータ除く等）
$Sspc = new SetSolrParamCustom($_REQUEST,$ReqPara,$Set->naigai);
$ReqPara = $Sspc->ReqPara;

/*
echo "<pre>";
print_r($ReqPara);
echo "</pre>";
*/

//リクエスト
$SolrObj = new CoreSolrAccess($ReqPara,$Set->naigai);

/*
echo "<pre>";
print_r($SolrObj->Obj->facet_counts->facet_fields);
echo "</pre>";
*/



//返却データ切り替え
if(strpos($_REQUEST['kind'], 'Box')  !== false){
	#=======================
	#	検索BOXのボタン押下時(出発地、出発日、目的地、旅行日数、こだわりの条件)
	#=======================
	//呼び出しクラス名
	$myClass = $_REQUEST['kind'];
	//パラメータ名
	$myParam = str_replace('Box_' ,'',$_REQUEST['kind']);
	//リクエストパラメータ値
	$myParamValueAry = $SolrObj->solrReqParamAry[$Set->naigai];
	//必要なファセットのみ加工
	$FacetRewriteObj = new FacetRewrite($SolrObj->Obj->facet_counts->facet_fields,$Set->naigai,'array');
	$facet = $FacetRewriteObj->retFacet;

	//検索BOX項目生成
	$boxFile =  dirname(__FILE__) . '/phpsc/BoxSmp/' . $myClass . '.php';
	print_r($boxFile);
	if(is_file($boxFile)){
		include_once($boxFile);
		if(class_exists($myClass)){	//クラスが存在したら実行
			$Box = new $myClass($facet,$myParamValueAry);
			$Box->make();
			echo $Box->html;
		}
	}
}
else if(strpos($_REQUEST['kind'], 'GetList')  !== false){
	#=======================
	#	検索ボタン押下時
	#=======================
	$obj = new LoadAction;	//ロード時の全て
	$resObj = $obj->dispObj;	//表示するもの全て格納
	$jsonObj = json_encode($resObj);
	echo $jsonObj;
}
else if(strpos($_REQUEST['kind'], 'GetHitNum')  !== false){
	#=======================
	#	該当商品の件数のみ
	#=======================
	if($SolrObj->Obj->response->p_hit_num !== NULL && $SolrObj->Obj->response->p_hit_num > 0){
		//$jsonObj['p_hit_num'] = $SolrObj->Obj->response->p_hit_num;
		$jsonObj['p_hit_num'] = is_numeric($SolrObj->Obj->response->p_hit_num) ? number_format($SolrObj->Obj->response->p_hit_num) : $SolrObj->Obj->response->p_hit_num;
	}else{
		$jsonObj['p_hit_num'] = 0;
	}
	$jsonObj = json_encode($jsonObj);
	echo $jsonObj;
}
else if(strpos($_REQUEST['kind'], 'Detail')  !== false){

	#=======================
	#	検索条件の項目ボタン押下時
	#=======================
	$Kd = new GetFacet($_REQUEST,$SolrObj,$Set->naigai);

	$kindSub = (isset($_REQUEST['kindSub'])) ? $_REQUEST['kindSub'] : null;
	if($kindSub == 'TF'){
		$jsonObj = $Kd->outTFAry;
	}elseif($kindSub == 'ReqOnly'){
		$jsonObj = $Kd->outSelectOnlyAry;
	}else{
		$jsonObj = $Kd->outAry;
	}
	//件数も入れる
	$jsonObj['p_hit_num'] = $SolrObj->Obj->response->p_hit_num;
	//ボタン制御用も入れる
	$jsonObj['TF'] = $Kd->outTFAry;


	$jsonObj = json_encode($jsonObj);

	echo $jsonObj;

}
else if(strpos($_REQUEST['kind'], 'Date')  !== false){
	#=======================
	#	日付応答
	#=======================
	//print_r($SolrObj->Obj->facet_counts->facet_fields->p_dep_day);
	//print_r($SolrObj->Obj->facet_counts->facet_fields->p_dep_month);

	//必要なファセットのみ加工
	$FacetRewriteObj = new FacetRewrite($SolrObj->Obj->facet_counts->facet_fields,$Set->naigai,'array');
	$facet = $FacetRewriteObj->retFacet;

	//祝日データ取得
	$holiday = new GetHoliday;

	$jsonObj = $facet;
	$jsonObj['holiday'] = $holiday->data;
	$jsonObj = json_encode($jsonObj);
	echo $jsonObj;

}

class GetHoliday{

	var $data;

	function __construct() {

		//XMLファイルのディレクトリを設定する
		if(strpos($_SERVER['HTTP_HOST'],'www-dev.hankyu-travel.com') !== false || strpos($_SERVER['HTTP_HOST'],'www-cms.hankyu-travel.com') !== false){
			$dir = "/mnt/protected/users/itec-test/m_holiday/";
			$dir = "/var/www_env/sites/home/users/bud-tokyo/hbos_system_dev/";
		}else{
			$dir = "/mnt/protected/users/itec/m_holiday/";
		}
		//XMLファイルのディレクトリを設定する
		$holiday_csv = $dir . "m_holiday.csv";

		//祝日ファイルを読む
		$handle = fopen($holiday_csv, "r");
		if ($handle) {
			while (!feof($handle)) {
				$buffer = rtrim(fgets($handle, 9999));	//日本語ファイルはfgetcsv使うのやめておく
				$buffer = str_replace('"', '', $buffer);	//ダブルクォーテーション不要
				//空白行はサヨナラ
				if(empty($buffer)){
					continue;
				}
				$holidayAry[] = $buffer;
			}
			fclose($handle);
		}
		 $this->data = $holidayAry;
	}


}

?>
