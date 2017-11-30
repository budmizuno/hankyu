<!--目的地から探す -->
<?php 
	$mapReq['p_hatsu'] = $p_hatsuI;
	$mapReq['p_data_kind'] = '1';
	$mapFacetAry = new mapFacet;
	$mapFacetAry->defDisp($mapReq);
?>
<section class="blue DestIWrapper">

  <h2>目的地から探す</h2>
  <div id="tourMap" class="DestI">
    <p><img src="/attending/kaigai/images/Map_kaigaiBase.png"></p>
    <ul class="js_<?php e($kyotenId);?>">
    <?php if($mapFacetAry->f['BEU'] !=0):?>
    	<li class="Map_europe" id="tourBEU"><a href="#snav100" class="btn_popup">ヨーロッパ<span>[<?php echo $mapFacetAry->f['BEU'];?>]</span></a></li>
    <?php else:?>
    	<li class="Map_europe noFacet" id="tourBEU">ヨーロッパ<span>[0]</span></li>
    <?php endif?>
    <?php if($mapFacetAry->f['AAS'] !=0):?>
    	<li class="Map_asia" id="tourAAS"><a href="#snav100" class="btn_popup">アジア<span>[<?php echo $mapFacetAry->f['AAS'];?>]</span></a></li>
    <?php else:?>
    	<li class="Map_asia noFacet" id="tourAAS">アジア<span>[0]</span></li>
    <?php endif?>
    <?php if($mapFacetAry->f['CHT'] !=0):?>
    	<li class="Map_middle-east" id="tourCHT"><a href="#snav100" class="btn_popup">中近東<span>[<?php echo $mapFacetAry->f['CHT'];?>]</span></a></li>
    <?php else:?>
    	<li class="Map_middle-east noFacet" id="tourCHT">中近東<span>[0]</span></li>
    <?php endif?>
    <?php if($mapFacetAry->f['CAF'] !=0):?>
    	<li class="Map_africa" id="tourCAF"><a href="#snav100" class="btn_popup">アフリカ<span>[<?php echo $mapFacetAry->f['CAF'];?>]</span></a></li>
    <?php else:?>
    	<li class="Map_africa noFacet" id="tourCAF">アフリカ<span>[0]</span></li>
    <?php endif?>
    <?php if($mapFacetAry->f['FOC'] !=0):?>
    	<li class="Map_oceania" id="tourFOC"><a href="#snav100" class="btn_popup">オセアニア<span>[<?php echo $mapFacetAry->f['FOC'];?>]</span></a></li>
    <?php else:?>
    	<li class="Map_oceania noFacet" id="tourFOC">オセアニア<span>[0]</span></li>
    <?php endif?>
    <?php if($mapFacetAry->f['DNU'] !=0):?>
     	<li class="Map_north-america" id="tourDNU"><a href="#snav100" class="btn_popup">北米<span>[<?php echo $mapFacetAry->f['DNU'];?>]</span></a></li>
    <?php else:?>
     	<li class="Map_north-america noFacet" id="tourDNU">北米<span>[0]</span></li>
    <?php endif?>
    <?php if($mapFacetAry->f['HWI'] !=0):?>
    	<li class="Map_hawaii" id="tourHWI"><a href="<?php e($path16->HttpTop);?>/hawaii/">ハワイ<span>[<?php echo $mapFacetAry->f['HWI'];?>]</span></a></li>
    <?php else:?>
    	<li class="Map_hawaii noFacet" id="tourHWI">ハワイ<span>[0]</span></li>
    <?php endif?>
    <?php if($mapFacetAry->f['ESU'] !=0):?>
    	<li class="Map_latin-america" id="tourESU"><a href="#snav100" class="btn_popup">中南米<span>[<?php echo $mapFacetAry->f['ESU'];?>]</span></a></li>
    <?php else:?>
    	<li class="Map_latin-america noFacet" id="tourESU">中南米<span>[0]</span></li>
    <?php endif?>
    <?php if($mapFacetAry->f['MNA'] !=0):?>
    	<li class="Map_micronesia" id="tourMNA"><a href="#snav100" class="btn_popup">ミクロネシア<span>[<?php echo $mapFacetAry->f['MNA'];?>]</span></a></li>
    <?php else:?>
    	<li class="Map_micronesia noFacet" id="tourMNA">ミクロネシア<span>[0]</span></li>
    <?php endif?>
    <?php if($mapFacetAry->f['SPC'] !=0):?>
    	<li class="Map_s-pacific" id="tourSPC"><a href="#snav100" class="btn_popup">南太平洋<span>[<?php echo $mapFacetAry->f['SPC'];?>]</span></a></li>
    <?php else:?>
    	<li class="Map_s-pacific noFacet" id="tourSPC">南太平洋<span>[0]</span></li>
    <?php endif?>
    </ul>
  </div>
</section>

<div class="popup">
    <div id="snav100" class="snav100"></div>
</div>


<script type="text/javascript" src="/attending/kaigai/js/jquery.magnific-popup.js"></script>
<script type="text/javascript">
$(function () {
  $('.btn_popup').magnificPopup({
    type: 'inline',
    preloader: false,
    focus: '#username',
    modal: true,
    midClick: true,
    closeBtnInside: true
  });
  $("[id^=snav1]").on('click', function (e) {
  	e.stopPropagation();
  });    
});
function js_GlMapClose(evt){
	$.magnificPopup.close();
}
function mapSubmitLink(evt) {

	SAS_setCookie('SAS_VARS_TYPE','地図',0,'/','hankyu-travel.com','');
	$("#senmonSubmitFr").remove();
	var formObj = $("<form>").attr({ 
		id: "senmonSubmitFr",
		method: "post",
		action: ""
	});
	$(formObj).appendTo("body");
	var obj = evt.target || evt.srcElement;
	var myPath = location.href;
	var hostname = window.location.hostname ;

	var nameVar = $(obj).attr("data-name");
	$("#senmonSubmitFr").attr("action","http://"+hostname+"/"+nameVar);
	$("#senmonSubmitFr").trigger("submit");
	
}
</script>