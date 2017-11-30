<?php
include_once($_SERVER['DOCUMENT_ROOT'] .'/sharing/phpsc/path.php');
include_once($SharingPSPath . 'read_blog.php');
/*******************************************************
 *	「func.php」の「blogToHtml」あたりで
 *	 呼び出してくれたら動くかと思います。
 ******************************************************/
/*
<!-- 重要なお知らせ -->
	<?php echo $importantBlog->blogToHtmlNew;?>
	<div class="impInfo">
		<p>重要なお知らせ</p>
		<ul>
			<li>GetBlogList</li>
		</ul>
	</div>
	<!-- 重要なお知らせ -->
*/

//重要なお知らせ
$importantBlog = new GetimportantBlogList;
$MasterKyotenObj =new MakeKyotenSimpleGList;
$kyotenListAry = $MasterKyotenObj->TgDataAry;

class GetimportantBlogList {
	##################################################
	#	重要なお知らせ取得_リニューアル版
	##################################################
	//public function blogToHtmlNew($knd) {

	public function blogToHtmlNew($knd, $naigai = '', $num = '', $width = '', $DateType = 0, $InfoLink = 0) {
		global $severEnvironment;

		// 重要なお知らせ
		if (strpos($knd, '重要なお知らせ') !== false) {

			$XmlUrl = '';
			if($severEnvironment == PRODUCTUIN){
				$XmlUrl = "http://blog.hankyu-travel.com/news/index.xml";
			}else{
				$XmlUrl = "http://blog.hankyu-travel.com/news/index.xml";
			}
			$Blog = $this->RetTagNewSenmon($XmlUrl, 'li', $width, $num, $DateType);

			if (empty($Blog)) {
				return;
			}
$knd = '重要なお知らせ';
			$html =<<< EOD
<div class="menu-warning mb20">
	<p><i class="sprite sprite-warning"></i>{$knd}</p>
	<ul>
		{$Blog}
	</ul>
</div>

EOD;

			// 表示して終わり
		echo $html;
		return;
		}
	}

	##################################################
	#	ニュースリリース
	##################################################
	public function blogToNews($naigai = '', $num = '', $width = '', $DateType = 0, $InfoLink = 0) {
		global $severEnvironment;

		$XmlUrl = '';
		$XmlUrl = "http://heiweb:heibud@blog.hankyu-travel.com/newsrelease/index.xml";

		$Blog = $this->RetTagNewSenmon($XmlUrl, 'li', $width, $num, $DateType);

		if (empty($Blog)) {
			return;
		}

		$html =<<< EOD
			<div class="idx_box03 news mb60">
			  <h3 class="idx_icn33">ニュースリリース</h3>
			  <ul>
				{$Blog}
			  </ul>
			  <p class="btn-more btn_newsrelease"><a href="http://blog.hankyu-travel.com/newsrelease/" target="_blank"><span>ニュースリリースをもっと見る</span> <span class="icon icon-arr-left"></span></a></p>
			</div>
EOD;

		// 表示して終わり
		echo $html;
		return;
	}

	##################################################
	#	重要なお知らせ取得_リニューアル版
	##################################################
	//public function blogToHtmlBottom($knd) {

	public function blogToHtmlBottom($knd, $naigai = '', $num = '', $width = '', $DateType = 0, $InfoLink = 0) {
		global $GlobalVpcFlg;
		// 重要なお知らせ
		if (strpos($knd, 'お客様へのお知らせ') !== false) {

			//$XmlUrl = "http://mac:macmac@blog.hankyu-travel.com/kaigai_2012/news/index.xml";
			$XmlUrl = "http://mac:macmac@blog.hankyu-travel.com/customer_news/index.xml";

			//スマホの場合
			if($GlobalVpcFlg== false && judg_smartphone() != 0){
				//ブログ呼び出し
				$Blog = $this->RetTagNew($XmlUrl, 'dd', $width, $num, '0');

				if (empty($Blog)) {
					return;
				}
				$knd = '重要なお知らせ';
				$html =<<< EOD
				<section class="notice">
					<dl>
						<dt>{$knd}</dt>
						{$Blog}
					</dl>
				</section>
EOD;
			} else {
			//PCの場合

				//ブログ呼び出し
				$Blog = $this->RetTagNew($XmlUrl, 'li', $width, $num, '0');


				if (empty($Blog)) {
					return;
				}

				$knd = '重要なお知らせ';
				$html =<<< EOD
				<div class="idx_box03 news mb30 FClear">

					<h3 class="idx_icn22">お客様へのお知らせ</h3>
					<ul>
						{$Blog}
					</ul>
				</div>
EOD;
			}
			// 表示して終わり
		echo $html;
		return;
		}
	}

	##################################################
	#	ブログ記事リンク生成_リニューアル版
	##################################################
	private function RetTagNew ($URL = NULL, $TagName = 'li', $Width = '', $Row = '', $DateType = 0) {
		// 呼び出し
		global $SharingPSPath;


		// 変数
		$Html = "";															// 空データ
		list($attribute, $kyoten, $directory) = $this->CreateBlogValue();	// 表示判定用

		// XMLの連想配列取得
		$XmlParse = RssParser14::RetAry($URL);


		// 記事が存在するか
		if($XmlParse !== false && !empty($XmlParse) && !empty($XmlParse->channel->item)) {


			// 記事の数だけ処理
			foreach ($XmlParse->channel->item as $Obj) {
				// 表示判定
				// 全部空だと除外
				if (empty($Obj->pubnews_cate) && empty($Obj->pubnews_dep) && empty($Obj->pubnews_dis)) {
					continue;
				}
				else {

					// カテゴリ判定
					if ($this->CheckBlogValue($Obj->pubnews_cate, $attribute) === false) {
						continue;
					}
					// 発地判定
					if ($this->CheckBlogValue($Obj->pubnews_dep , $kyoten	) === false) {
						continue;
					}
					// ディレクトリ判定
					if ($this->CheckBlogValue($Obj->pubnews_dis	  , $directory) === false) {
						continue;
					}
				}

				// タイトルのトリミング
				$Title = MyEcho($Obj->title);
				if (!empty($Width)) {
					$Title = mb_strimwidth($Title, 0, $Width, '…', 'UTF-8');
				}
				// HTMLのテンプレートパターン
				// 更新日無し
				if ($DateType === 0) {
					$Html .= <<< EOD
<{$TagName}><a href="{$Obj->link}">{$Title}</a></{$TagName}>
EOD;
				}
				// 更新日あり
				else {
					// 更新日の加工
					$UpDate = date($DateType, strtotime($Obj->pubDate));
					$Html .= <<< EOD
<{$TagName}><span class="BlogUpdate">{$UpDate}</span><span class="BlogLink"><a href="{$Obj->link}">{$Title}</a></span></{$TagName}>
EOD;
				}
				//$Html .= "<br />";
				// どこで止める？
				if (!empty($Row)) {
					$Cnt++;
					if ($Row <= $Cnt) {
						break;
					}
				}
			}
		}


		// 返り値
		return $Html;
	}

	##################################################
	#	ブログ記事リンク生成_リニューアル版 出発地 + 海外の方面、国、国内の方面、県の切り分け 専門店で使用
	##################################################
	private function RetTagNewSenmon ($URL = NULL, $TagName = 'li', $Width = '', $Row = '', $DateType = 0) {
		// 呼び出し
		global $SharingPSPath,$masterCsv,$naigai,$categoryType,$getCsvItem;


		// 変数
		$Html = "";															// 空データ
		list($attribute, $kyoten, $directory) = $this->CreateBlogValue();	// 表示判定用

		$kaigai_homen = '';
		$kaigai_country = '';
		$kokunai_homen = '';
		$kokunai_country = '';

		if($categoryType == CATEGORY_TYPE_DEST){
			$kokunai_homen = $masterCsv[KEY_MASTER_CSV_NAME_JA];
		}elseif($categoryType == CATEGORY_TYPE_COUNTRY){
			$kokunai_country = $masterCsv[KEY_MASTER_CSV_NAME_JA];
		}elseif($masterCsv[KEY_MASTER_CSV_HOMEN] == 'hokkaido/' || $masterCsv[KEY_MASTER_CSV_HOMEN] == 'okinawa/'){
			// 国を取得
	        $countryDir = preg_replace('/\/([\w]+?)$/','',$masterCsv[KEY_MASTER_CSV_DIRNAME]);

	        $array = array();
	        if (is_array($getCsvItem->masterCsvAllData) && count($getCsvItem->masterCsvAllData) > 0) {
	            foreach ($getCsvItem->masterCsvAllData as $value) {
	                if($countryDir == $value[KEY_MASTER_CSV_DIRNAME])
	                {
	                    $array = $value;
	                    break;
	                }
	            }
	        }

	        $kokunai_homen = $array[KEY_MASTER_CSV_NAME_JA];
		}


		// XMLの連想配列取得
		$XmlParse = RssParser14::RetAry($URL);


		// 記事が存在するか
		if($XmlParse !== false && !empty($XmlParse) && !empty($XmlParse->channel->item)) {


			// 記事の数だけ処理
			foreach ($XmlParse->channel->item as $Obj) {
				// 表示判定
				// 全部空だと除外
				if (empty($Obj->pubnews_cate) && empty($Obj->pubnews_dep) && empty($Obj->pubnews_dis) &&
					empty($Obj->pubnews_ove) && empty($Obj->pubnews_cou) && empty($Obj->pubnews_dom) && empty($Obj->pubnews_pre)) { // 専門店の海外の方面、国、国内の方面、県の切り分け
					continue;
				}
				else {

					// カテゴリ判定
					if ($this->CheckBlogValue($Obj->pubnews_cate, $attribute) === false) {
						continue;
					}
					// 発地判定
					if ($this->CheckBlogValue($Obj->pubnews_dep , $kyoten	) === false) {
						continue;
					}
					// ディレクトリ判定
					if ($this->CheckBlogValue($Obj->pubnews_dis	  , $directory) === false) {
						continue;
					}

					if($categoryType == CATEGORY_TYPE_DEST || $categoryType == CATEGORY_TYPE_CITY){
						// 国内の方面
						if ($this->CheckBlogValueSenmon($Obj->pubnews_dom,	$Obj->pubnews_cate  , $kokunai_homen) === false) continue;
					}else{
						// 国内の国
						if ($this->CheckBlogValueSenmon($Obj->pubnews_pre,	$Obj->pubnews_cate  , $kokunai_country) === false) continue;
					}
				}

				// タイトルのトリミング
				$Title = MyEcho($Obj->title);
				if (!empty($Width)) {
					$Title = mb_strimwidth($Title, 0, $Width, '…', 'UTF-8');
				}
				// HTMLのテンプレートパターン
				// 更新日無し
				if ($DateType === 0) {
					$Html .= <<< EOD
<{$TagName}><a href="{$Obj->link}">{$Title}</a></{$TagName}>
EOD;
				}
				// 更新日あり
				else {
					// 更新日の加工
					$UpDate = date($DateType, strtotime($Obj->pubDate));
					$Html .= <<< EOD
<{$TagName}><span class="BlogUpdate">{$UpDate}</span><span class="BlogLink"><a href="{$Obj->link}">{$Title}</a></span></{$TagName}>
EOD;
				}
				//$Html .= "<br />";
				// どこで止める？
				if (!empty($Row)) {
					$Cnt++;
					if ($Row <= $Cnt) {
						break;
					}
				}
			}
		}


		// 返り値
		return $Html;
	}

	##################################################
	#	ブログ記事の表示判定用
	##################################################
	private function CheckBlogValue($xmlVal, $setVal) {
		// カテゴリ「ALL」は全表示なのでOK
		if (strpos($xmlVal, "ALL") !== false || strpos($xmlVal, "専門店") !== false) {
			return true;
		}

		// 空白判定
		if (empty($xmlVal)) {
			return true;
		}

		// カンマ区切りらしいので
		$xmlVal = explode(",", $xmlVal);

		// 値が存在するか
		foreach ($xmlVal as $val) {
			if (empty($val)) {
				continue;
			}
			if (strpos($val, '.php') !== false) {
				 $filename = basename($val);
				 $val=str_replace($filename,"",$val);
			}
			//if (strpos($setVal, $val) !== false) {/*2014/2/28修正　海外フリーとかが海外でもtrueになるため*/

			if ($setVal== $val) {

				//専門店は海外と国内のブログは非表示
				if($setVal=='海外'){
					if($this->isSenmon('i')){
						return false;
					}
				}
				if($setVal=='国内'){
					if($this->isSenmon('d')){
						return false;
					}
				}

				return true;
			}
		}
		return false;
		//return in_array($setVal, $xmlVal);
	}

	##################################################
	#	ブログ記事の表示判定用 海外の方面、国、国内の方面、県の切り分け
	##################################################
	private function CheckBlogValueSenmon($xmlVal,$category, $setVal) {

		// カテゴリ「ALL」は全表示なのでOK
		if (strpos($category, "ALL") !== false || strpos($category, "専門店") !== false) return true;

		if($xmlVal == $setVal) return true;

		return false;
	}

	##################################################
	#	カテゴリID、発地ID、ディレクトリの値作成
	##################################################
	private function CreateBlogValue() {
		// グローバル
		global $kyotenListAry;
		global $SettingData;

		// カテゴリ日本名
		$categoryName = array(
			"top"		=> "トップ",
			"i"			=> "海外",
			"p"		=> "海外フリープラン",
			"d"		=> "国内",
			"f"			=> "国内フリープラン",
			"a"			=> "航空券",
			"h"			=> "宿",
			"b"		=> "バス",
//			""			=> "DS海外",
//			""			=> "DS国内",
//			""			=> "DS航空券",
		);
		// カテゴリID、発地IDは「SettingData」より取得
		// ディレクトリはURLより取得
		$attribute	= $categoryName[$SettingData->PageAttribute];
		$kyoten	= $SettingData->SettingAey['kyotenId'];
		$directory = mb_substr(dirname($_SERVER["REQUEST_URI"] . "index.php") , 1);

		$directory	= (!empty($directory)) ? "/" . $directory . "/" : "/";

		// settingデータやURL階層が「koronis」内だと不十分なので
		// 仮データを上書き
		//$attribute = "";
		//$kyoten 	= "";
		//$directory = "";
		/*if(empty($GlobalMaster['kyotenUse'])){
			new GM_kyotenUse;
		}

		if(is_array($GlobalMaster['kyotenUse'])){
			foreach($GlobalMaster['kyotenUse'] as $data){

				print_r($data);

			}
		}*/
		// 発地IDから日本語名に変換
		if (is_array($kyotenListAry)) {

			foreach ($kyotenListAry as $kyotencode => $kyotenname) {
				if ($kyoten == $kyotencode) {
					$kyoten = $kyotenname . "発";
					break;
				}
			}
		}

		// 返り値
		return array($attribute, $kyoten, $directory);
	}

	//専門店国内・海外か
	function isSenmon($naigai){
		global $GlobalMaster;
		global $PathRelativeMyDir;

		$flg = false;
		$relativeMyDir = mb_substr($PathRelativeMyDir,1);

		if($relativeMyDir){
			if(count($GlobalMaster['Senmon'][$relativeMyDir])>0){
				if($GlobalMaster['Senmon'][$relativeMyDir]['naigai']==$naigai){
					//専門店
					$flg = true;
				}
			}
		}
		return $flg;
	}



}

class RssParser14{
	#=======================
	#	(1)連想配列で返すタイプ
	#=======================
	/*
		引数
			$URL：feed（xml）を指定
			XMLをパースしただけです。

			もし、名前空間付きを扱う必要が出てきた場合は、修正が必要。
			Googleの検索Wordは「名前空間　childrenメソッド」です。
			↓こんな感じで取得できるはず↓
			$simplexml->item[$i]->children("hatena", true)->bookmarkcount
	*/
	function RetAry($URL=NULL){
		if($URL == NULL){
			return false;
		}
		if($XmlContents = @file_get_contents($URL)){
			$Xml = simplexml_load_string($XmlContents, 'SimpleXMLElement', LIBXML_NOCDATA );
				return $Xml;

		}else{
			return false;
		}

	}

	#=======================
	#	(2)<タグ>で返すタイプ
	#=======================
	/*
		引数
			$URL：feed（xml）を指定
			$TagName：返してほしいタグ名　※属性値を含めることはできません
			$Width：文字数
			$DateType：更新日を返す＝'書式（例：'n月j日'）、返さない＝0（デフォルト）
			$Row：行数

			もしも更新日を返すように指定したら、<span class="BlogUpdate"><span class="BlogLink">でマークアップされます。
	*/
	function RetTag($URL=NULL, $TagName='li', $Width='', $Row=5, $DateType=0){
		$XmlParse = self::RetAry($URL);
		if($XmlParse === false || empty($XmlParse) || empty($XmlParse->channel->item)){
			return false;
		}

		foreach($XmlParse->channel->item as $Obj){
			/*--- タイトルのトリミング ---*/
			$Title = MyEcho($Obj->title);
			if(!empty($Width)){
				$Title = mb_strimwidth($Title, 0, $Width, '…', 'UTF-8');
			}
			/*--- HTMLのテンプレートパターン ---*/
			//更新日無し
			if($DateType === 0){
				$Html .=<<< EOD
<{$TagName}><a href="{$Obj->link}">{$Title}</a></{$TagName}>

EOD;
			}
			elseif($DateType === 1){
				/*----20150630追加----*/
				$UpDate = date('m月d日', strtotime($Obj->pubDate));
				$img = $Obj->pubImage;
				$Html .=<<< EOD
<{$TagName}><a href="{$Obj->link}"><dl><dt>{$img}</dt><dd class="date">{$UpDate}</dd><dd class="text">{$Title}</dd></dl></a></{$TagName}>

EOD;
			}
			/*----ここまで-----*/
			//更新日あり
			else{
				//更新日の加工
				$UpDate = date($DateType, strtotime($Obj->pubDate));
				$Html .=<<< EOD
<{$TagName}><span class="BlogUpdate">{$UpDate}</span><span class="BlogLink"><a href="{$Obj->link}">{$Title}</a></span></{$TagName}>

EOD;
			}
			/*どこで止める？*/
			if(!empty($Row)){
				$Cnt++;
				if($Row <= $Cnt){
					break;
				}
			}
		}
		return $Html;
	}

}

/*******************************************************
 * blogDisp　現地情報 ブログを表示する
 *
 * 引数
 * 返り値
 *		$knd：表示の関数名
 *		$num：出したい数（デフォルト5本）
 *		$width：幅（デフォルト56byte分）
 *		$DateType：更新日を返す＝'書式（例：'n月j日'）、返さない＝0（デフォルト）
 ******************************************************/

class blogDisp{

	function __construct($knd,$naigai='',$num=5, $width=56, $DateType=0){
		global $SharingIncPath,$BlogHttp,$BlogDom;

		$this->naigai =$naigai;
		$this->num =$num;
		$this->naigai =$naigai;
		$this->DateType =$DateType;
		$BlogDom = rtrim($BlogHttp, '/');
		$this->$knd();

	}
	//総合トップ用
	function GenchiTop(){
		global $BlogDom;

		if($this->naigai == 'i'){
			$XmlUrl = $BlogDom . '/kaigai/index.xml';
			$LinkUrl = str_replace(basename($XmlUrl), '', $XmlUrl);
		}
		elseif($this->naigai == 'd'){
			$XmlUrl = $BlogDom . '/kokunai/index.xml';
			$LinkUrl = str_replace(basename($XmlUrl), '', $XmlUrl);
		}
		$Blog = RssParser14::RetTag($XmlUrl, 'li', $this->width, $this->num, $this->DateType);
		if(empty($Blog)){
			return;
		}
		echo $Blog;
	}
	//専門店用
	function GenchiSenmon(){
		global $BlogDom,$PathRelativeMyDir,$GlobalMaster,$categoryType,$masterCsv,$senmonNameEnLower;
		$MyPath = substr($PathRelativeMyDir, 1);
		$MasterKey = 'Senmon';
		//マスターを持ってなければ、マスターを作るクラス実行
		if(empty($GlobalMaster[$MasterKey])){
			$ClassName = 'GM_' . $MasterKey;
			new $ClassName;
		}

		// 量産化CSVから取得
		$blogGenchi_url = $masterCsv[KEY_MASTER_CSV_BLOG_URL];

		if(empty($blogGenchi_url)){
			return;
		}

		$XmlUrl = 'https://tabicoffret.com/rss/domestic' . $blogGenchi_url;

//		$XmlUrl = $BlogDom . $blogGenchi_url;
		$this->LinkUrl = str_replace('index.xml', '', $XmlUrl);
		$this->LinkUrl = str_replace('rss/', '', $this->LinkUrl);
		$XmlParse = RssParser14::RetAry($XmlUrl);
		if(is_object($XmlParse)){
			$count ='';
			foreach($XmlParse->channel->item as $Obj){

				if($count >= $this->num){
					break;
				}
				if($Obj->link){
					$xmlData[$count]['time'] = MyEcho(date("m月d日", strtotime($Obj->pubDate)));
					$xmlData[$count]['ttl'] = MyEcho($Obj->title);
					$xmlData[$count]['link'] =MyEcho($Obj->link);
					$xmlData[$count]['image'] =$Obj->pubImage;

					$count++;
				}
				else{
					$xmlData ='';
				}
			}
		}
		$li = '';
		if(is_array($xmlData)){
			foreach($xmlData as $data){
				if($this->naigai == 'i'){
					// 方面ページタイプなら
					if($categoryType == CATEGORY_TYPE_DEST  )
					{
						// コース名は文字制限かける
						$dataTtl = stringControl($data['ttl'],STRING_LIMIT_BLOG_HORIZONTAL);

						$li .=<<< EOD
		<li><a href="{$data['link']}">{$data['image']}</a><p>{$data['time']}</p><a href="{$data['link']}">{$dataTtl}</a></li>
EOD;
					}
					// 国ページタイプなら
					elseif($categoryType == CATEGORY_TYPE_COUNTRY)
					{
						// コース名は文字制限かける
						$dataTtl = stringControl($data['ttl'],STRING_LIMIT_BLOG_VERTICAL);

						$li .=<<< EOD
						<div class=" aside-index-info mt10">
							<p class="ebook-group-title font-14">{$data['time']}</p>
							<a href="{$data['link']}">
								{$data['image']}
								<p class="font-14 mt5">{$dataTtl}</p>
							</a>
						</div>
EOD;
					}
				}
				elseif($this->naigai == 'd'){
					// コース名は文字制限かける
					$dataTtl = stringControl($data['ttl'],STRING_LIMIT_BLOG_VERTICAL);
					$dataTtl = htmlspecialchars_decode($dataTtl);

					if($categoryType == CATEGORY_TYPE_DEST && $senmonNameEnLower != 'hokkaido' && $senmonNameEnLower != 'okinawa'){
						$li .=<<< EOD
						<li><a href="{$data['link']}" target="_blank">{$data['image']}</a><p>{$data['time']}</p><a href="{$data['link']}" target="_blank">{$dataTtl}</a></li>
EOD;
					}else{
						$li .=<<< EOD
						<div class="aside-index-info mt10">
							<p class="ebook-group-title font-14">{$data['time']}</p>
							<a href="{$data['link']}" target="_blank">
								{$data['image']}
								<p class="font-14 mt5">{$dataTtl}</p>
							</a>
						</div>
EOD;
					}
				}
			}
			$this->Blog= $li;
		}
		else{
			$this->Blog ='';
			return;
		}


	}
}
