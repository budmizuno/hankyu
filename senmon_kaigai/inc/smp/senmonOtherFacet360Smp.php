<!-- ほかの出発地を見る -->
<div class="tab-content-search mb20 bdColor">
	<h2 class="main-title like-main-title mainBgClr mb10 search_title">
        <span class="main-title-txt">近隣の出発地から探す</span>
    </h2>
  	<div id="kinrin_text">現在<?=$KyotenName;?>発<?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?>の<?php e($RqPara['p_bunrui'] != '030'? 'ツアー':'フリープラン'); ?>はお取扱いがございません。他の出発地にて取り扱っておりますのでご検討ください。</div>

	<div class="srchBox360b">
		<div class="SachBox senFacetBg">
			<div class="srchBg OtBox mapSachHeight">
				<?php echo $this->OtherFacet;?>
				<?php if(!empty($this->OtherFacetHtmlEtc)):?>
				<p class="otBtnEtc"><a href="javascript:void(0)" id="Js_otherFacetEtc" >出発地から探す</a></p>

				<?php endif; ?>
				<p class="otDTxt">※出発する地域の設定が、一時的にご選択の出発地に設定されます。</p>
			</div>
		</div>
	</div>
</div>
<input type="hidden" id="other_facet" value="<?php e(json_encode($this->OtherFacetHtmlEtc)); ?>">
<!-- ほかの出発地を見る -->
