/**
 * 海外
 * ブランド
 */

$(function(){

	// チェックボックスをクリックしたら
	$(this).on('click','.check_mainbrand', function(e){

		// activeをつけて背景色をつける
		$(this).closest("span").toggleClass("active");

		// チェックボックス集計してヒット件数取得
		getCheckBoxHitCountMainbrand();

	});

	// 確定ボタンをクリックしたら
	$(document).on("click",".decide-btn.mainbrand", function() {

		//チェックしてる配列作成
		var chkObj =[];
		var chkObj_text = [];

		var text = "";

		// チェックしているなら
		$(FormID+'[name=p_mainbrand]:checked').each(function(){
			// エコノミー[123]の[123]の部分を削除
			var name = $(this).parent().text().substring(0,$(this).parent().text().indexOf("["));

			text = ( text == "" ) ? name : text + '／' + name;

		});

		var html = '<li><input type="button" class="del_mainbrand" value="全て削除" data-role="none" style="float:none"><span class="decided_text" href="#modal_mainbrand">'+ text +'</span></li>';

		// 確定ボタンの共通Action
		decideBtnActionAdd('mainbrand',html,false);

	});

	// 出発地の削除ボタンを押したらremove
	$('.search-box').on("click",".del_mainbrand", function() {

		clearMainbrand();

	});




});

// チェックボックスを処理してヒット件数取得
function getCheckBoxHitCountMainbrand()
{

	//チェックしてる配列作成
	var chkObj =[];
	var hatsu_str = '';

	$(FormID+'[name=p_mainbrand]:checked').each(function(){
		chkObj.push($(this).data("value"));
	});

	hatsu_str = chkObj.join(',');

	// チェックした際の共通アクション
	checkboxAction('mainbrand','p_mainbrand',hatsu_str);
}

//モーダルビューの閉じるボタン、選び直しなどのクリア処理
function clearMainbrand()
{
	// クリアの共通Action
	clearAction('mainbrand','p_mainbrand',false,true);
}



function mainbrandInit()
{
	var options = {
			formObj:'#searchTour',
			kind:"Detail",
			p_data_kind:1,
			p_rtn_data:"p_mainbrand"
	}
	searchTour.requestProcess(options);	//Ajax通信実施

	var settings = {
		dataType: "json",
		success: function(json){
			searchTour.jsonData = json;
			//応答結果を編集
			html(json,'p_mainbrand');
		}
	}
	searchTour.ajaxProcess(settings);

	var html = function(json,myname){
		var for_name = 'p_mainbrand';
		var type = 'checkbox';
		var c = c2 = '';
		var in_html = '';
		var dataCnt = searchTour.count(json[myname]);
		var reqPara = searchTour.getReqParam(myname);
		in_html += '<ul>';
		for (var i = 0; i < dataCnt; i++){
			var m = json[myname][i];
			var idno = ("0"+i).slice(-2);
			if(m.facet < 1){
				c = '';
				c2 = ' disabled';
				c3 = 'style="color:#9d9c9c"';
			}else{
				if(m.check == true){
					c = 'active';
					c2 = ' checked';
				}else{
					c = c2 = '';
				}
				c3 = '';
			}
			in_html += '<li><span class="'+ c +'">';

			in_html += '<label for="' + for_name + idno + '" '+ c3 +'  ><input class="check_mainbrand" name="' + reqPara + '" type="' + type + '" id="' + for_name + idno + '" data-value="' + m.key + '"' + c2 + '>' + m.name + '[' + m.facet + ']</label>';

			in_html += '</span></li>';
		}

		in_html += '</ul>';

		$(FormID+"#modal_mainbrand .area-list").html(in_html);
	}

}
