<?php
if (empty($p_bunrui)) {
    $p_bunrui = '';
}
$clsObj = new SearchActionForSearchBoxDfree($naigai, $rqPara, $p_hatsu, $KyotenID, $p_hatsuAry->TgDataAry[$naigai],$p_bunrui);//検索ボックス
?>
<script type="text/javascript" src="<?=$PathSenmonLink;?>js/newSearch_freepland.js"></script>
<div class="left tab-content-search mb20 bdColor">
	<div class="simpleSrchBlk">
		<div class="smSearchD">
			<h3 class="tab-tt mainBgClr"><i class="sprite sprite-search"></i><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?> フリープラン検索<p class="clBtn"><a href="javascript:void(0);">クリア</a></p></h3>
			<form id="dSearchBox" name="dSearchBox" action="<?php e($path16->HttpTop)?>/search/dfree.php" method="post">
				<input type="hidden" id="MyNaigai" name="MyNaigai" value="d">
				<input type="hidden" value="030" name="p_bunrui[]">
				<input type="hidden" id="bus_bunrui" value="" name="p_bunrui[]">
				<input type="hidden" id="MyType" name="MyType" value="freeplan-d">
				<input type="hidden" id="def_p_hatsu" name="def_p_hatsu" value="<?=$p_hatsu?>">
				<input type="hidden" id="clear_flag" name="clear_flag" value="">
				<input type="hidden" id="cityCd" name="cityCd" value="">
				<div class="smSearchDFree_Blk">
					<ul>
						<li class="searchMds"><span class="IconBg">交通手段</span></li>
						<li class="inputWrapper1">
							<ul>
								<li class="transport_input">
									<input type="radio" value="" name="p_transport" id="transport_none" onclick="Change();" checked="checked">
									<label for="transport_none">指定なし</label>
							 	</li>
							 	<li class="transport_input">
									<input type="radio" value="3" name="p_transport" id="apt" onclick="Change();">
									<label for="apt">飛行機</label>
							 	</li>
							  	<li class="transport_input">
									<input type="radio" value="2" name="p_transport" id="trn" onclick="Change();">
									<label for="trn">列車</label>
							 	</li>
							  	<li class="transport_input">
							    	<input type="radio" value="1,4" name="p_transport" id="bs" onclick="Change();">
									<label for="bs">バス・船</label>
							 	</li>
							 </ul>
						</li>
					</ul>
					<div id="airplain" style="display:none;">
    					<ul>
    						<li class="searchMds"><span class="IconBg">出発空港</span></li>
    						<li>
    							<?=$clsObj->Values['p_dep_airport_code']?>
    						</li>
    					</ul>
    					<ul>
    						<li class="searchMds"><span class="IconBg">到着空港</span></li>
    						<li>
    							<?=$clsObj->Values['p_arr_airport_code']?>
    						</li>
    					</ul>
					</div>
					<ul id="trainbus">
						<li class="searchMds"><span class="IconBg">出発地</span></li>
						<li>
							<?=$clsObj->Values['p_hatsu_sub']?>
						</li>
					</ul>
					<ul>
						<li class="searchMds"><span class="IconBg">目的エリア</span></li>
						<li>
							<?=$clsObj->Values['Dest']?>
						</li>
					</ul>
					<ul>
						<li class="searchMds"><span class="searchArrw IconFont">都道府県</span></li>
						<li>
							<?=$clsObj->Values['Country']?>
						</li>
					</ul>
					<ul>
						<li class="searchMds"><span class="searchArrw IconFont">観光地</span></li>
						<li>
							<?=$clsObj->Values['City']?>
						</li>
					</ul>
					<ul>
						<li class="searchMds"><span class="IconBg">出発日</span></li>
						<li class="cal">
							<input type="text" id="p_dep_date" name="p_dep_date" placeholder="" value=""><img src="/sharing/common16/images/searchCal.png" class="js_dep_date_cal">
						</li>
					</ul>
					<ul>
						<li class="searchMds"><span class="IconBg">旅行日数</span></li>
						<li>
							<select name="p_kikan_min" id="p_kikan_min" class="p_min_day w55">
								<?=$clsObj->Values['p_kikan_min']?>
							</select>
							〜
							<select name="p_kikan_max" id="p_kikan_max" class="p_max_day w55">
								<?=$clsObj->Values['p_kikan_min']?>
							</select>
							日 </li>
					</ul>
					<p class="srchResult">現在の該当件数
					<span id="dp_hit_num_dfree">
						<?php echo (is_numeric($clsObj->ResObj->p_hit_num) ? number_format($clsObj->ResObj->p_hit_num) : $clsObj->ResObj->p_hit_num);?>
					</span>件です</p>
					<!--<p class="topSearchAdd"><a href="javascript:void(0);">検索条件を追加する</a></p>-->
					<p class="btn_simpleSrch">検索</p>
				</div>
				<?php if(isset($SettingData->SettingAey['MTRDispFlg'])):?>
				<input type="hidden" name="MTRDispFlg" value="<?=$SettingData->SettingAey['MTRDispFlg']?>" />
				<?php endif; ?>
				<select name="p_conductor" class="hideClass inpConductor" style="display:none;"></select>
			</form>
			<!-- //smSearchD	 -->
		</div>
	</div>
</div>
