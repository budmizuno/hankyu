/**
 * 国内
 * 出発空港
 */

$(function(){

	// チェックボックスをクリックしたら
	$(this).on('click','.check_dep_airport', function(e){

		// チェックボックス集計してヒット件数取得
		getCheckBoxHitCountDepAirport();

		// activeをつけて背景色をつける
		$(this).closest("span").toggleClass("active");

	});

	// 確定ボタンをクリックしたら
	$(document).on("click",".decide-btn.dep_airport", function() {

		//チェックしてる配列作成
		var chkObj =[];
		var chkObj_text = [];

		var text = "";

		// フリープランなら
		if($(FormID+"#free_flag").val() != "")
		{

			// チェックしているなら
			$(FormID+'[name=p_dep_airport_code]:checked').each(function(){
				// 値
				chkObj.push($(this).data("value"));
				// 名称
				chkObj_text.push($(this).parent().text());
			});

			var html = '';
			// 選んだ出発地の表記のためにhtmlをループ
			for(var i=0;i<chkObj.length;i++)
			{
				html += '<li><div><input type="button" class="del_dep_airport" value="削除" data-value="'+ chkObj[i] +'"><a class="decided_link" href="#modal_dep_airport"><span class="decided_text" href="#modal_dep_airport">'+ chkObj_text[i] +'</span></a></div></li>';
			}

			// 確定ボタンの共通Action
			decideBtnActionAdd('dep_airport',html,true);
		}
		else
		{
			// チェックしているなら
			$(FormID+'[name=p_dep_airport_code]:checked').each(function(){
				// 全日空[123]の[123]の部分を削除
				var name = $(this).parent().text().substring(0,$(this).parent().text().indexOf("["));

				text = ( text == "" ) ? name : text + '／' + name;

			});

			var html = '<li><input type="button" class="del_dep_airport" value="全て削除" data-role="none" style="float:none"><a class="decided_link" href="#modal_dep_airport"><span class="decided_text" href="#modal_dep_airport">'+ text +'</span></a></li>';

			// 確定ボタンの共通Action
			decideBtnActionAdd('dep_airport',html,false);
		}


	});

	// 出発地の削除ボタンを押したらremove
	$('.search-box').on("click",".del_dep_airport", function() {

		// フリープランなら
		if($(FormID+"#free_flag").val() != "")
		{
			// 削除ボタンのクリアアクションの例外
			deleteButtonActionException('dep_airport','p_dep_airport_code',this);
		}
		else
		{
			clearDepAirport();
		}
	});




});

// チェックボックスを処理してヒット件数取得
function getCheckBoxHitCountDepAirport()
{

	//チェックしてる配列作成
	var chkObj =[];
	var hatsu_str = '';

	$(FormID+'[name=p_dep_airport_code]:checked').each(function(){
		chkObj.push($(this).data("value"));
	});

	hatsu_str = chkObj.join(',');

	// チェックした際の共通アクション
	checkboxAction('dep_airport','p_dep_airport_code',hatsu_str);
}

//モーダルビューの閉じるボタン、選び直しなどのクリア処理
function clearDepAirport()
{
	// フリープランなら
	if($(FormID+"#free_flag").val() != "")
	{
		// クリアの共通Action
		clearAction('dep_airport','p_dep_airport_code',true,true);
	}
	else
	{
		// クリアの共通Action
		clearAction('dep_airport','p_dep_airport_code',false,true);
	}
}




function depAirportInit()
{

	var options = {
			formObj:'#searchTour',
			kind:"Detail",
			p_data_kind:1,
			p_rtn_data:"p_dep_airport_name"
	}
	searchTour.requestProcess(options);	//Ajax通信実施

	var settings = {
		dataType: "json",
		success: function(json){
			searchTour.jsonData = json;
			//応答結果を編集
			html(json,'p_dep_airport_name');

		}
	}
	searchTour.ajaxProcess(settings);

	var html = function(json,myname){
		var for_name = 'p_dep_airport_rd';
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
				//continue;
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

			in_html += '<label for="' + for_name + idno + '" '+ c3 +'  ><input class="check_dep_airport" name="' + reqPara + '" type="' + type + '" id="' + for_name + idno + '" data-value="' + m.key + '"' + c2 + '>' + m.name + '[' + m.facet + ']</label>';

			in_html += '</span></li>';
		}


		in_html += '</ul>';

		$(FormID+"#modal_dep_airport .area-list").html(in_html);
	}

}
