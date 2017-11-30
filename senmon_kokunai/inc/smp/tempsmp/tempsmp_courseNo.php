<section class="blue courseNoWrapper">
	<h2>コース番号検索</h2>
	<form name="search_no" id="search_no" action="<?php e($path16->HttpTop)?>/course/index.php" method="post">
		<input type="hidden" id="kaigai" name="p_naigai" value="W" class="naigai" />
		<input class="courseSrch ttlCursSrchTxt" type="text" name="p_course_id" value="" id="p_course_id" placeholder="" />
		<div id="btnP"><input type="submit" value="検索" class="ttlCursSrchBtn" onclick="return c()"></div>
	</form>
</section>

<script>
$(function() {
	//全角英数字だったら半角に
	$('.ttlCursSrchTxt').change(function(){
		var txt  = $(this).val();
		var han = txt.replace(/[Ａ-Ｚａ-ｚ０-９]/g,function(s){return String.fromCharCode(s.charCodeAt(0)-0xFEE0)});
		$(this).val(han);
	});
	a();
	//フォーカス時にplaceholderを消す
	$("#search_no #p_course_id").focus(function(){
		$(this).attr('placeholder', '');
	});

	//フォーカスが外れた時、空ならplaceholderを表示
	$("#search_no #p_course_id").blur(function(){
				a();		

	});
});

function a(){
	$("#search_no #p_course_id").attr('placeholder','例）E509');
	}
function c(){
	$(".ttlCursSrchBtn").focus();
	ID = $("#search_no #p_course_id").val();
	if(ID.charAt(0) =='#'||ID.charAt(0) =='＃'){
		alert('コース番号の一文字目は英数字で入力ください');
		return false;
	}
	
	IdLen = ID.length;

	var array = ["-" , "−" , "の" , "ー" , "―" , "–" , "‐" , "－"];
	var index = -1;
	for (var i = 0; i < array.length; i++) {
		if(ID.indexOf(array[i]) != -1 ){
			index = ID.indexOf(array[i]);
			break;
		}
	}

	var indexPosition;
	if ( index != -1) {
		indexPosition = IdLen - index;

		if(indexPosition){
			ID = ID.slice(0,-indexPosition);
		}
		IdLen = ID.length;
		if(IdLen<2 || IdLen>=8){
			alert('ハイフン（-）またはひらがなの『の』より前のコース番号は\n2桁以上、7桁以下で入力してください');
			return false;
		}
	} else {
		if(IdLen<2 || IdLen>=8){
			alert('コース番号を2桁以上、7桁以下で入力してください');
			return false;
		}
	}
}		

</script>
