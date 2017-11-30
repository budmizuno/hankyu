<div class="js_navDiv">
    <ul class="list_anchor taipei-list-anchor" id="js_freeNav">
        <?php if($categoryType != CATEGORY_TYPE_CITY 
            && $masterCsv['page_code'] != 'AAS-MO-MFM'
            && $masterCsv['page_code'] != 'MNA-GU_PW'
            && $masterCsv['page_code'] != 'MNA-SP'
            && $masterCsv['page_code'] != 'AAS-MV'):?>
        <li>
            <a class="menu-link-map" onclick="scrollMenuBtn(1)">
                <span>地図から探す</span>
            </a>
        </li>
        <?php endif;?>
        <li>
            <a class="menu-link-search" onclick="scrollMenuBtn(2)">
                <span>条件から探す</span>
            </a>
        </li>
        <li>
            <a class="menu-link-ichioshi" onclick="scrollMenuBtn(3)">
                <span>おすすめ商品を見る</span>
            </a>
        </li>
        <li>
            <a class="menu-link-tour-info" onclick="scrollMenuBtn(4)">
                <span>観光情報を見る<br></span>
            </a>
        </li>
    </ul>
</div>
<div class="change-start-point clearfix">
    <p class="change-start-point-text">出発地を変更する</p>
    <div class="change-point-from">
        <p><?php echo $def_kyotenName;?>発</p>
    </div>
</div>


<?php // ---------------------------------------------------------------------------------- ※以下、発地モーダル start ?>
<?php 
/*
 * コピー元ソース：シェア common16/inc/travelcomHeader.php
 */
?>
<div class="GlMenu js_HatsuMenuSenmon" style="display:none;">
<!-- <?php // ※下記、呼出のみでechoされる為コメントしておく ?>
<?php 
$tmpSearchActionForSenmonHeaderFacet = new SearchActionForSenmonHeaderFacet($naigai,$KyotenID,$rqPara, false);
?>
-->
<?php
$kyotenListAry =new MakeKyotenSimpleGList;
//出発地コード取得
$FacetRqArray = $tmpSearchActionForSenmonHeaderFacet->GetFacetRqArray($kyotenListAry);
//全拠点ファセット情報取得
$FasetdataAry = $tmpSearchActionForSenmonHeaderFacet->GetSolr($FacetRqArray,$rqPara);

// ※データ加工
$tmpFasetdataAry = array();
foreach ($FasetdataAry as $Fasetdata) {
	$tmpFasetdataAry[$Fasetdata['name']] = $Fasetdata['facet'];
}

?>

	<div class="GlMenuCtsSenmon">
        <div class="GlMenuIcon">
            <div class="GlMenuClose js_HatsuMenuClose"><a href="javascript:void(0)">閉じる</a></div>
        </div>
        <dl>
        <dt>出発地をお選びください</dt>
        <?php 
		$hatsuData = new HatsuSelectpanelSmpSenmon();
		echo $hatsuData->kenCodeHtml;
		?>
        
        </dl>
	</div>
</div>

<?php 

class HatsuSelectpanelSmpSenmon{
	function __construct() {
		global $GlobalMaster,$PageAttribute;


		$this->MakeKenHatsuAry('i');
		$this->MakeHtml();

	}

	//html作成
	function MakeHtml(){
		// ※ファセットデータ
		global $tmpFasetdataAry;
		global $kyotenId;
		
		if($this->bigKyotenAry){
			$dd ='';
			foreach($this->bigKyotenAry as $bigKyotenid => $kenAry){
				$dd .=<<<EOD
<dd class="bigkyoten">{$bigKyotenid}発</dd>
EOD;
				foreach($kenAry as $kencode => $kenmei){
				    $active = '';
				    if ($kyotenId == $kenmei['kyotenId']) {
				        $active = 'active';
				    }
				
					// ※ファセットがあるかどうか
					if (isset($tmpFasetdataAry[$kenmei['kyotenName']]) && $tmpFasetdataAry[$kenmei['kyotenName']]) {
					$dd .=<<<EOD
<dd class="{$active}"><a href="javascript:void(0)" onclick="SelectKenLink('{$kencode}');return false;">{$kenmei['kyotenName']}発</a></dd>
EOD;
					} else {
					$dd .=<<<EOD
<dd class="{$active} disabled">{$kenmei['kyotenName']}発</dd>
EOD;
						
					}
				}

			}
		}
		$this->kenCodeHtml = $dd;
	}

	//マスターから拠点発地に必用な配列作成
	function MakeKenHatsuAry($naigai){
		global $GlobalMaster;
		if(empty($GlobalMaster['kyotenUse'])){
			new GM_kyotenUse;
		}


		foreach($GlobalMaster['kyotenUse'] as $kencodeAry){
			if($kencodeAry['naigai'] == $naigai){
				$kenBase[$kencodeAry['bigKyoten']][$kencodeAry['kenCode']]=$kencodeAry;
			}
		}
		$this->bigKyotenAry=$kenBase;

	}

}

?>

<?php // ---------------------------------------------------------------------------------- ※以下、発地モーダル end ?>