/**
 * 海外
 * 添乗員
 */

$(function(){

	$(this).on('click','.check_conductor', function(e){

		// activeを外して背景色を戻す
		$(this).closest("li").toggleClass("selected");

		$(FormID+'#p_conductor').val($(this).data("value"));

		var text = ($(this).data("value")　== 1) ? '同行する':'同行しない';

		html = '<li><div><input type="button" class="del_conductor" value="削除"><a class="decided_link" href="#modal_conductor"><span class="decided_text" href="#modal_conductor">'+ text +'</span></a></div></li>';

	    // 確定ボタンの共通Action
		decideBtnActionAdd('conductor',html,false);

	});



	// 出発地の削除ボタンを押したらremove
	$('.search-box').on("click",".del_conductor", function() {

		clearConductor();

	});

});


// モーダルビューの閉じるボタン、選び直しなどのクリア処理
function clearConductor()
{
	// クリアの共通Action
	clearAction('conductor','p_conductor',false,true);
}


function initConductor()
{
	var options = {
		formObj:'#searchTour',
		kind:"Detail",
		p_data_kind:1,
		p_rtn_data:"p_conductor"
	}
	searchTour.requestProcess(options);	//Ajax通信実施

	var settings = {
		dataType: "json",
		success: function(json){
			searchTour.jsonData = json;
			//応答結果を編集
			html(json,'p_conductor');

		}
	}
	searchTour.ajaxProcess(settings);

	var html = function(json,myname){

		var in_html = '';
		var dataCnt = searchTour.count(json[myname]);
		in_html += '<ul>';
		for (var i = 0; i < dataCnt; i++){
			var m = json[myname][i];
			if(m.facet < 1){
				in_html += '<li><span><label style="color:#9d9c9c"><input type="radio" class="check_conductor" data-value="'+m.key+'" disabled>' + m.name + '[' + m.facet + ']</label></span></li>';
			}else{
				if(m.check == true){
					in_html += '<li><span><label><input type="radio" class="check_conductor" data-value="'+m.key+'" checked>' + m.name + '[' + m.facet + ']</label></span></li>';
				}else{
					in_html += '<li><span><label><input type="radio" class="check_conductor" data-value="'+m.key+'">' + m.name + '[' + m.facet + ']</label></span></li>';
				}
			}
		}

		in_html += '</ul>';

		$(FormID+"#modal_conductor .area-list").html(in_html);
	}
}
