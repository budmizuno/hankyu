/**
 * 海外
 * 旅行期間
 */

$(function(){

	// チェックボックスをクリックしたら
	$(this).on('click','.check_kikan', function(e){

	    var $buttons, $focusElement, $selected, checked, childId, count, nrOfSelected, started;
	    $focusElement = $(this);
	    $buttons = $focusElement.parents('ul:first').find('li');
	    $selected = $buttons.filter('.selected');
	    nrOfSelected = $selected.length;
	    checked = $focusElement.is(':checked');
	    if ((nrOfSelected > 1) || ((nrOfSelected === 0 || nrOfSelected === $buttons.length) && checked)) {
	    	$buttons.removeClass('selected').find(':checked').prop('checked', false);
	        $focusElement.prop('checked', true).closest("li").addClass('selected');
	    }
	    else if (checked)
	    {
	    	started = false;
	        childId = '#' + $focusElement.attr('id');
	        $buttons.each(function(index, element) {
	        var $element, startOrEnd;
	        $element = $(element);
	        startOrEnd = $element.hasClass('selected') || $element.find(childId).length > 0;
	        if (started && startOrEnd)
	        {
	        	started = false;
	        }
	        else if (startOrEnd)
	        {
	        	started = true;
	        }

	        if (started || startOrEnd)
	        {
	        	return $element.addClass('selected').find('input').prop('checked', true);
	        }
	        else
	        {
	        	return $element.removeClass('selected').find('input').prop('checked', false);
	        }
	        });
	    }
	    else
	    {
	    	$focusElement.closest("li").removeClass('selected');
	        count = 0;
	        $(FormID+'[id^=term]').each(function(event) {
	        	if ($(this).css('display') !== 'none') {
	        		if ($(this).is(':checked')) {
	        			return count++;
	                }
	            }
	       });

	       if (count === 0) {
	    	   $(FormID+'#termAll').prop('checked', true).closest("li").addClass('selected');
	       }
	   }

	    var from, term,html;
	    term = {
	    		from: '',
	    		to: ''
	    };

	    // フリープランなら
	    if($(FormID+"#free_flag").val() != "")
	    {
		    // チェックされたものを調べる
		    $selected = $(FormID+'.search_kikan2 ul li.selected');

	        if (($selected != null ? $selected.length : void 0) === 1)
	        {
	            from = $selected.find('input').attr('id').replace('term', '');
	            term.from = from;
	            term.to = from;
	            if (term.to > 8)
	            {
	            	// 9日以上なので空にする
	            	term.to = '';
	            	$(FormID+'#daysManuInfo').html('9日間以上');
	            }
	            else
	            {
	            	$(FormID+'#daysManuInfo').html($selected.find('label').text() + '間');
	            }
	        }
	        else if (($selected != null ? $selected.length : void 0) > 1)
	        {
	            term.from = $selected.first().find('input').attr('id').replace('term', '');
	            term.to = $selected.last().find('input').attr('id').replace('term', '');
	            if (term.from < 9 && term.to == 9)
	            {
	            	// 9日以上なので空にする
	            	term.to = '';
	            	$(FormID+'#daysManuInfo').html(term.from + '日間以上');
	            }
	            else
	            {
	            	$(FormID+'#daysManuInfo').html(term.from + '～' + $selected.last().find('label').text() + '間');
	            }
	        }
	        else
	        {
	        	$(FormID+'#daysManuInfo').html('全て');
	        }

			$(FormID+'#p_kikan_min').val(term.from);
			$(FormID+'#p_kikan_max').val(term.to);

			checkFooterSearch();
	    }
	    else
	    {
		    // チェックされたものを調べる
		    $selected = $(FormID+'.area-list.sep2 ul li.selected');

		    if (($selected != null ? $selected.length : void 0) === 1)
		    {
		    	from = $selected.find('input').attr('id').replace('term', '');
		        term.from = from;
		        term.to = from;
		    }
		    else if (($selected != null ? $selected.length : void 0) > 1)
		    {
		    	term.from = $selected.first().find('input').attr('id').replace('term', '');
		        term.to = $selected.last().find('input').attr('id').replace('term', '');
		    }

			$(FormID+'#p_kikan_min').val(term.from);
			$(FormID+'#p_kikan_max').val(term.to);

			// チェックした際の共通アクション
			checkboxAction('kikan','p_kikan_min',term.from);
	    }



	});

	// 確定ボタンをクリックしたら
//	$(".decide-btn").click(function(e){
	$(document).on("click",".decide-btn.kikan", function() {

	    $selected = $(FormID+'.area-list.sep2 ul li.selected');

	    var from, term,html;
	    term = {
	    		from: null,
	    		to: null
	    };


	    if (($selected != null ? $selected.length : void 0) === 1)
	    {
	    	from = $selected.find('input').attr('id').replace('term', '');
	        term.from = from;
	        term.to = from;

	        if(from == 1)
	        {
		        html = '<li><div><input type="button" class="del_kikan" value="削除"><a class="decided_link" href="#modal_kikan"><span class="decided_text" href="#modal_kikan">日帰り</span></a></div></li>';
	        }
	        else
	        {
		        html = '<li><div><input type="button" class="del_kikan" value="削除"><a class="decided_link" href="#modal_kikan"><span class="decided_text" href="#modal_kikan">'+ from + '日間</span></a></div></li>';
	        }
	    }
	    else if (($selected != null ? $selected.length : void 0) > 1)
	    {
	    	term.from = $selected.first().find('input').attr('id').replace('term', '');
	        term.to = $selected.last().find('input').attr('id').replace('term', '');

	        html = '<li><div><input type="button" class="del_kikan" value="削除"><a class="decided_link" href="#modal_kikan"><span class="decided_text" href="#modal_kikan">'+ term.from + '～' + term.to + '日間</span></a></div></li>';
	    }

	    // 確定ボタンの共通Action
		decideBtnActionAdd('kikan',html,false);

	});

	// 出発地の削除ボタンを押したらremove。もしくはフリープランでの日数をクリアボタンを押したら
	$('.search-box').on("click",".del_kikan,.clear_btn", function() {

		clearKikan();

	});





});



// モーダルビューの閉じるボタン、選び直しなどのクリア処理
function clearKikan()
{
    // フリープランなら
    if($(FormID+"#free_flag").val() != "")
    {
	    $(FormID+'.search_kikan2 ul li.selected').each(function(event) {
	    	$(this).removeClass('selected');
	    });

	    $(FormID+'#daysManuInfo').html('全て');

		$(FormID+'#p_kikan_min').val("");
		$(FormID+'#p_kikan_max').val("");

		checkFooterSearch();
    }
    else
    {
    	// 複数なので例外処理
    	$(FormID+'#p_kikan_min').val("");
    	$(FormID+'#p_kikan_max').val("");

    	// クリアの共通Action
    	clearAction('kikan','',false,true);
    }


}



function initKikan()
{
	var options = {
		formObj:'#searchTour',
		kind:"Detail",
		p_data_kind:1,
		p_rtn_data:"p_kikan"
	}
	searchTour.requestProcess(options);	//Ajax通信実施

	var settings = {
		dataType: "json",
		success: function(json){
			//searchTour.jsonData = json;
			//応答結果を編集
			if(json.ErrMes){
				//エラー
			}else{
				//表示

				html(json.p_kikan);

			}
		}
	}
	searchTour.ajaxProcess(settings);

	var html = function(kikanAry){

		var minKikan = 0;
		var maxKikan = 14;

		var for_name = 'p_kikan_cb';
		var type = 'checkbox';
		var c = c2 = '';
		var in_html = '';
		var name = '';
		var dataCnt = searchTour.count(kikanAry);

		var naigai = $(FormID+"#MyNaigai").val();

		var html = '';

		for (var i = minKikan; i < dataCnt; i++){
			var m = kikanAry[i];
			var idno = ("0"+i).slice(-2);


			var name = m.name + '日間';
			if(i == 0)
			{
				name = '日帰り';
			}


			if(m.facet < 1){
				html += '<li><span><label style="color:#9d9c9c"><input type="checkbox" id="term'+m.key+'" class="check_kikan" data-value="'+m.key+'" disabled>'+name+'['+m.facet+']</label></span></li>';

			}else{

				if(m.check == true){

					html += '<li class="selected"><span><label><input type="checkbox" id="term'+m.key+'" class="check_kikan" data-value="'+m.key+'" checked>'+name+'['+m.facet+']</label></span></li>';

				}else{

					html += '<li><span><label><input type="checkbox" id="term'+m.key+'" class="check_kikan" data-value="'+m.key+'">'+name+'['+m.facet+']</label></span></li>';
				}
			}
		}

		$(FormID+".area-list.sep2 .cf").html(html);
	}

}
