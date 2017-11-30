<?php
/*
#################################################################
	全国の出発地の情報
	/sharing/inc/setDispKyoten.php のdepAreaLinkクラスを持ってきている
#################################################################
*/

function depAreaLink(){
	global $GlobalMaster;
	$depLinkAry; //7大拠点ごとの拠点発配列

	if(empty($GlobalMaster['kyotenUse'])){
		new GM_kyotenUse;
	}

	foreach($GlobalMaster['kyotenUse'] as $dataAry){
		if($dataAry['naigai'] == 'i'){
			$depLinkAry[$dataAry['bigKyotenId']][$dataAry['kyotenId']]['bigKyoten']=$dataAry['bigKyoten'];
			$depLinkAry[$dataAry['bigKyotenId']][$dataAry['kyotenId']]['kyotenName']=$dataAry['kyotenName'];
		}
	}

	$dl='';
	$liAll = '';
	foreach($depLinkAry as $bigKyotenId => $dataAry){
		$li ='';

		foreach($dataAry as  $kyotenId =>$data){
			$li .=<<<EOD
			<li class="{$kyotenId}"><a href="{$kyotenId}.php">{$data['kyotenName']}発</a></li>
EOD;
		}
	$liAll .= $li;
	}
	echo $liAll;
}

 ?>


<!-- 出発地ごとの情報を見る -->
<div class="botMap">
	<h2><img src="/sharing/common16/images/icn_bot_flag.png" alt="">出発地ごとの情報を見る</h2>
	<ul>
		<?php depAreaLink();?>
	</ul>
</div>
<!-- 出発地ごとの情報を見る -->
