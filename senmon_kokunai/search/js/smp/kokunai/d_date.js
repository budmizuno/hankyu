/**
 * 国内
 * 出発日
 *
 * 出発日だけ他とやり方が異なるので注意。
 * hankyu-travel.com/e-very/tokyo/のやり方を流用している
 */


function initDate( )
{
	CommonCalendarService.initPagerMonthButtons();
	// ファセットの再取得とカレンダーの再描画
	CommonCalendarService.getCalendarFacet();
	CommonCalendarService.drawCalendar(CalendarService.createThisMonthYyyymm());

}

// フリープランの初回起動用
function initDateFreeplan(){
	// selectorも初期化
	CommonCalendarService.selecterinit();
	CommonCalendarService.initPagerMonthButtons();
	CommonCalendarService.bindSelectAllButton();
	CommonCalendarService.bindClearCalendarButton();
	CommonCalendarService.bindPagerButtons();
	// ファセットの再取得とカレンダーの再描画
	CommonCalendarService.getCalendarFacet();
	CommonCalendarService.drawCalendar(CalendarService.createThisMonthYyyymm());

	// 一応どちらにも入れておく
	$(".tour #freeplan_date_init_flag").val('true');
	$(".free_plan #freeplan_date_init_flag").val('true');
}

$(function(){

	var startup = new Startup();
	startup.start();

});

(function() {

	var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
	    __hasProp = {}.hasOwnProperty,
	    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

	this.Startup = (function() {

		function Startup() {
			this.start = __bind(this.start, this);

		    // カレンダーの準備
			CommonCalendarService.init();
		}

		Startup.prototype.start = function(data) {

		};

		return Startup;

	})();

	this.Parameter = (function() {

		function Parameter() {}

		Parameter.tmpDate = null;     // 出発日データ

		return param;
	});

	var compareDate = function(year1, month1, day1, year2, month2, day2) {
		var dt1 = new Date(year1, month1 - 1, day1);
		var dt2 = new Date(year2, month2 - 1, day2);
		var diff = dt1 - dt2;
		var diffDay = diff;
		return diffDay;
	}

	// ##-----------------------------------------------
	//   0パディング（出発日のところなどで使用する）
	// ##-----------------------------------------------
	//
	Number.prototype.zeroPad = Number.prototype.zeroPad || function(base) {
	  var len, nr;
	  nr = this;
	  len = (String(base).length - String(nr).length) + 1;
	  if (len > 0) {
	    return new Array(len).join('0') + nr;
	  } else {
	    return nr;
	  }
	};


	// ##-----------------------------------------------
	//   カレンダー関連関数
	// ##-----------------------------------------------
	//
	this.CalendarService = (function() {
	  function CalendarService() {}

	  CalendarService.monthBackground = $('.tbl_bg').css('background-image');

	  CalendarService.holidays = null;

	  CalendarService.calcAffectedDays = function(yyyymm) {
	    var affectedDays, calc, date, fullYear, month;
	    fullYear = parseInt(yyyymm.substr(0, 4), 10);
	    month = parseInt(yyyymm.substr(4, 2), 10) - 1;
	    date = new Date();
	    date.setDate(1);
	    date.setMonth(month);
	    date.setFullYear(fullYear);
	    calc = new Date();
	    calc.setDate(1);
	    calc.setMonth(month);
	    calc.setFullYear(fullYear);

	    affectedDays = 0;
	    while (calc.getMonth() === date.getMonth()) {
	      calc.setDate(calc.getDate() + 1);
	      affectedDays++;
	    }
	    return affectedDays;
	  };

	  CalendarService.createDateStructure = function(yyyymm) {
	    return {
	      yyyymm: yyyymm,
	      yyyy: yyyymm.substr(0, 4),
	      mm: yyyymm.substr(4, 2),
	      m: parseInt(yyyymm.substr(4, 2), 10)
	    };
	  };

	  CalendarService.createThisMonthYyyymm = function() {
	    var date, fullYear, month, stDate;
	    date = new Date(); // 現在の日付
	    date.setDate(date.getDate() + 5);	// 国内は現在の日付+5とする
	    commonDate = new Date(2016, 1 - 1, 1);  //

	    if(date.getTime() < commonDate.getTime()) { //今年より前だったら
	      date = commonDate;
	    }

	    fullYear = date.getFullYear();
	    month = (date.getMonth() + 1).zeroPad(10);

	    return "" + fullYear + month;
	  };

	  // 取得するスタート日付
	  CalendarService.createLimitDay = function() {
		  var date, fullYear, month, stDate,day;
		  date = new Date(); // 現在の日付
		  date.setDate(date.getDate() + 5);	// 国内は現在の日付+5とする
		  commonDate = new Date(2016, 1 - 1, 1);  //

		  if(date.getTime() < commonDate.getTime()) { //今年より前だったら
		    date = commonDate;
		  }

		  fullYear = date.getFullYear();
		  month = (date.getMonth() + 1).zeroPad(10);
		  day = date.getDate().zeroPad(10);

		  return "" + fullYear + month + day;
	  };

	  CalendarService.updateDateView = function() {
	    var dateText, _ref, _ref1;
	    dateText = '';
	    if (((_ref = Parameter.tmpDate) != null ? _ref.length : void 0) === 8) {
	      dateText = "" + (Parameter.tmpDate.substr(0, 4)) + "年" + (Parameter.tmpDate.substr(4, 2)) + "月" + (Parameter.tmpDate.substr(6, 2)) + "日";
	    } else if (((_ref1 = Parameter.tmpDate) != null ? _ref1.length : void 0) === 6) {
	      dateText = "" + (Parameter.tmpDate.substr(0, 4)) + "年" + (Parameter.tmpDate.substr(4, 2)) + "月";
	    }

	    var html = "";
		  html = '<li><div><input type="button" class="del_date" value="削除" data-value="'+ Parameter.tmpDate +'"><a class="decided_link" href="#modal_date"><span class="decided_text" href="#modal_date">'+ dateText +'</span></a></div></li>';

	    return html;
	  };

	  return CalendarService;
	})();

	// ##-----------------------------------------------
	//   ベースカレンダー関数
	// ##-----------------------------------------------
	//
	this.CommonCalendarService = (function(_super) {
	  __extends(CommonCalendarService, _super);

	  function CommonCalendarService() {
	    return CommonCalendarService.__super__.constructor.apply(this, arguments);
	  }

	  CommonCalendarService.selectors = {
	    pagerPrev: FormID+'.calPagerPrev > a',
	    pagerNext: FormID+'.calPagerNext > a',
	    pagerMonth: FormID+'.calPagerMonth > a',
	    tablePrev: FormID+'.tbl_prev',
	    tableNext: FormID+'.tbl_next',
	    table: FormID+'.SW_SD_Month',
	    caption: FormID+'.SW_SD_Caption',
	    yyyy: FormID+'.calYYYY',
	    mm: FormID+'.calMM',
	    m: FormID+'.calM',
	    selectAll: FormID+'.SW_SD_Caption > a',
	    weekdayCaption: FormID+'.calWeekdayCaption',
	    row: FormID+'.SW_SD_Month > tbody > tr:not(.calWeekdayCaption)',
	    tbody: FormID+'.SW_SD_Month > tbody',
	    dateButtons: FormID+'[id^=date_]',
	    clearCalendar: FormID+'#clearCalendar, #clearCalendarNotInModal',
	  };

	  CommonCalendarService.init = function() {

//	    this.api = api;
//	    CommonCalendarService.getHolidays();
	    this.initPagerMonthButtons();
	    this.bindSelectAllButton();
	    this.bindClearCalendarButton();
	    this.bindPagerButtons();
	    this.getCalendarFacet();
	    return this.drawCalendar(CalendarService.createThisMonthYyyymm());
	  };

	  CommonCalendarService.selecterinit = function() {

		  CommonCalendarService.selectors = {
			pagerPrev: FormID+'.calPagerPrev > a',
			pagerNext: FormID+'.calPagerNext > a',
			pagerMonth: FormID+'.calPagerMonth > a',
			tablePrev: FormID+'.tbl_prev',
			tableNext: FormID+'.tbl_next',
			table: FormID+'.SW_SD_Month',
			caption: FormID+'.SW_SD_Caption',
			yyyy: FormID+'.calYYYY',
			mm: FormID+'.calMM',
			m: FormID+'.calM',
			selectAll: FormID+'.SW_SD_Caption > a',
			weekdayCaption: FormID+'.calWeekdayCaption',
			row: FormID+'.SW_SD_Month > tbody > tr:not(.calWeekdayCaption)',
			tbody: FormID+'.SW_SD_Month > tbody',
			dateButtons: FormID+'[id^=date_]',
			clearCalendar: FormID+'#clearCalendar, #clearCalendarNotInModal',
		  };

	  };

	  CommonCalendarService.calendarContent = function(response) {
		  var html, row, _i, _len, _ref, _ref1;

	      // 祝日設定
	      if(response.holiday != null)
	      {
	    	  _ref = response.holiday;

	    	  $.each(_ref, function(k, v) {
	    		  var date = v;

		          $(FormID+'#date_'+date).addClass('sun');
		      });
	      }


	      if(response.p_dep_month != null)
	      {
	    	  var selectMonth;
	    	  if(Parameter.tmpDate == undefined)
	    	  {
	    		  // 該当の月
	    		  selectMonth = CalendarService.createThisMonthYyyymm();
	    	  }
	    	  else
	    	  {
	    		  selectMonth = Parameter.tmpDate;
	    	  }

	    	  var check = false;
		      $.each(response.p_dep_month, function(k, v)
		      {
		    	  if(selectMonth == k.substr(0, 6))
		    	  {
		    		  check = true;
		    	  }
		      });

		      if(!check)
		      {
		    	  // すべての日を選択をできないようにする
		    	  $(FormID+".SW_SD_Caption a").addClass("disabled");
		      }
		      else
		      {
		    	  $(FormID+".SW_SD_Caption a").removeClass("disabled");
		      }
	       }


	       if (response.p_dep_day != null) {
	    	   _ref = response.p_dep_day;

	    	   $(FormID+'[id^=date_]').each(function(event) {
	    		   $(this).removeClass('calendar_link');
	    	   });

	    	   selectedDay = CalendarService.createLimitDay();

	    	   $.each(_ref, function(k, v) {
	    		   var date = k;
	    		   var count = v;

	    		   // 取得する日付より遅かったら
	    		   if(selectedDay <= date)
	    		   {
	    			   $(FormID+'#date_'+ date).addClass('calendar_link');
	    		   }
	    	   });
	       }

	       return '';
	  };

	  CommonCalendarService.initPagerMonthButtons = function() {
	    var index;
	    index = 0;
	    return $(this.selectors.pagerMonth).each(function(ind,value) {
	      if(ind == 0)
	      {
	    	  $(this).html("");
		      $(this).attr({
			        'id': ""
			  });
		      return true;
	      }
	    	  　
	      var currentYyyymm, m, yyyy, yyyymm;
	      yyyymm = CalendarService.createThisMonthYyyymm();
	      yyyy = parseInt(yyyymm.substr(0, 4), 10);
	      m = parseInt(yyyymm.substr(4, 2), 10);
	      m += index;
	      if (m > 12) {
	        yyyy++;
	        m -= 12;
	      }
	      currentYyyymm = yyyy.toString() + m.zeroPad(10);
	      $(this).html(m + "月");
	      if (index === 0) {
	        $(this).parent().addClass('selected');
	      } else {
	        $(this).parent().removeClass('selected');
	      }
	      $(this).attr({
	        'id': "pager_" + currentYyyymm
	      });

	      return index++;
	    });

	  };

	  CommonCalendarService.drawCalendar = function(yyyymm) {

	    var cells, ds, row, rows, start;
	    ds = this.createDateStructure(yyyymm);
	    $(this.selectors.yyyy).html(ds.yyyy);
	    $(this.selectors.mm).html(ds.mm);
	    $(this.selectors.m).html(ds.m);
//	    CalendarService.updateMonthBackground(yyyymm);
	    cells = this.createFormattedCells(ds.yyyymm);
	    rows = [];
	    start = 0;
	    while (start < cells.length) {
	      row = ['<tr>', cells.slice(start, start + 7).join(''), '</tr>'].join('');
	      rows.push(row);
	      start += 7;
	    }
	    $(this.selectors.row).remove();
	    $(this.selectors.tbody).append(rows.join(''));
	    $(this.selectors.dateButtons).unbind();
	    if (CalendarService.createThisMonthYyyymm() === $(this.selectors.yyyy).html() + $(this.selectors.mm).html()) {
	      $(this.selectors.tablePrev).css({
	        'visibility': 'hidden'
	      });
	      $(this.selectors.pagerPrev).css({
	        'visibility': 'hidden'
	      });
	    } else {
	      $(this.selectors.tablePrev).css({
	        'visibility': 'visible'
	      });
	      $(this.selectors.pagerPrev).css({
	        'visibility': 'visible'
	      });
	    }

	    return this.bindDateButtons();
	  };

	  CommonCalendarService.createFormattedCells = function(yyyymm) {
	    var affectedDays, bottomPadding, cellClasses, cellId, cells, date, ds, dummy, i, template, topPadding, _i, _j, _k, _ref, _ref1;
	    ds = this.createDateStructure(yyyymm);
	    date = new Date();

	    date.setDate(1);
	    date.setMonth(ds.m -1);
	    date.setFullYear(parseInt(ds.yyyy, 10));

	    affectedDays = this.calcAffectedDays(ds.yyyymm);
	    topPadding = date.getDay();

	    bottomPadding = 7 - (topPadding + affectedDays) % 7;

	    if (bottomPadding === 7) {
	      bottomPadding = 0;
	    }
	    cells = [];
	    if (topPadding > 0) {
	      for (i = _i = 0, _ref = topPadding - 1; 0 <= _ref ? _i <= _ref : _i >= _ref; i = 0 <= _ref ? ++_i : --_i) {
	        cellClasses = [];
	        if (i === 0) {
	          cellClasses.push('sun');
	        }
	        dummy = "<td class='" + (cellClasses.join(' ')) + "'>&nbsp;</td>";
	        cells.push(dummy);
	      }
	    }

	    for (i = _j = 1; 1 <= affectedDays ? _j <= affectedDays : _j >= affectedDays; i = 1 <= affectedDays ? ++_j : --_j) {
	      date.setDate(i);
	      cellClasses = [];
	      switch (date.getDay()) {
	        case 0:
	          cellClasses.push('sun');
	          break;
	        case 6:
	          cellClasses.push('sat');
	      }
	      if ($.inArray(ds.yyyymm + i.zeroPad(10), CalendarService.holidays) !== -1) {
	        cellClasses.push('hol');
	      }
	      cellId = "date_" + (ds.yyyymm + i.zeroPad(10));
	      template = "<td class='" + (cellClasses.join(' ')) + "' id='" + cellId + "'>" + i + "</td>";
	      cells.push(template);
	    }
	    if (bottomPadding > 0) {
	      for (i = _k = 0, _ref1 = bottomPadding - 1; 0 <= _ref1 ? _k <= _ref1 : _k >= _ref1; i = 0 <= _ref1 ? ++_k : --_k) {
	        cellClasses = [];
	        if (i === (bottomPadding - 1)) {
	          cellClasses.push('sat');
	        }
	        dummy = "<td class='" + (cellClasses.join(' ')) + "'>&nbsp;</td>";
	        cells.push(dummy);
	      }
	    }
	    return cells;
	  };

	  // カレンダーの日付を押した際の動作
	  CommonCalendarService.bindDateButtons = function() {
		  return $(this.selectors.dateButtons).click(function(event) {
			  var checkCount;
			  // ファセットが存在している
			  if ($(this).hasClass('calendar_link')) {
				  if ($(this).hasClass('selected')) {
					  checkCount = 0;
					  $(CommonCalendarService.selectors.dateButtons).each(function(event) {
						  if ($(this).hasClass('selected')) {
							  return checkCount++;
						  }
					  });
					  if (checkCount >= 2) {
						  $(CommonCalendarService.selectors.dateButtons).removeClass('selected');
						  $(this).addClass('selected');
						  Parameter.tmpDate = $(this).attr('id').replace('date_', '');
					  }
					  else
					  {
						  $(CommonCalendarService.selectors.dateButtons).removeClass('selected');
						  Parameter.tmpDate = '';
					  }
				  }
				  else
				  {
					  $(CommonCalendarService.selectors.dateButtons).removeClass('selected');
					  $(this).addClass('selected');
					  Parameter.tmpDate = $(this).attr('id').replace('date_', '');
				  }


				  // チェックした際の共通アクション
				  checkboxAction('date','p_dep_date',Parameter.tmpDate);

			  }
		  });
	  };

	  CommonCalendarService.bindSelectAllButton = function() {
	    return $(this.selectors.selectAll).click(function(event) {
	      var noCheckCount, yyyymm;
	      event.preventDefault();
	      noCheckCount = 0;

	      // disabledがあるなら終了
	      if($(this).hasClass("disabled"))
	      {
	    	  return false;
	      }

	      $(CommonCalendarService.selectors.dateButtons).each(function(event) {
	        if (!$(this).hasClass('selected')) {
	          return noCheckCount++;
	        }
	      });
	      if (noCheckCount > 0) {
	        yyyymm = '';
	        $(CommonCalendarService.selectors.dateButtons).each(function(event) {
	          if (yyyymm === '') {
	            yyyymm = $(this).attr('id').replace('date_', '').substr(0, 6);
	          }
	          return $(this).addClass('selected');
	        });
	        Parameter.tmpDate = yyyymm;
	      } else {
	        $(CommonCalendarService.selectors.dateButtons).each(function(event) {
	          return $(this).removeClass('selected');
	        });
	        Parameter.tmpDate = '';
	      }
//	      if (Startup.clientType !== 'sp') {
//	        return EventBinderService.onChange();
//	      }

		  // チェックした際の共通アクション
		  checkboxAction('date','p_dep_date',Parameter.tmpDate);

	    });
	  };

	  CommonCalendarService.bindClearCalendarButton = function() {
	    return $(this.selectors.clearCalendar).click(function(event) {
	      event.preventDefault();
	      $(CommonCalendarService.selectors.dateButtons).each(function(event) {
	        return $(this).removeClass('selected');
	      });
	      Parameter.tmpDate = null;
	      Parameter.date = null;
//	      CalendarService.updateDateView();

//	      CommonCalendarService.getCalendarFacet();

//	      EventBinderService.getDateFacet();
//	      return EventBinderService.onChange();
	    });
	  };

	  CommonCalendarService.prev = function() {
	    var prev;
	    prev = function() {
	      var currentM, currentYyyy, prevM, prevYyyy, prevYyyymm;
	      currentYyyy = $(CommonCalendarService.selectors.yyyy).html();
	      currentM = $(CommonCalendarService.selectors.m).html();
	      if (CalendarService.createThisMonthYyyymm() === currentYyyy + parseInt(currentM, 10).zeroPad(10)) {
	        return;
	      }
	      prevYyyy = parseInt(currentYyyy, 10);
	      prevM = parseInt(currentM, 10) - 1;
	      if (prevM < 1) {
	        prevYyyy--;
	        prevM = 12;
	      }
	      prevYyyymm = prevYyyy.toString() + prevM.zeroPad(10);
	      CommonCalendarService.drawCalendar(prevYyyymm);
	      //$(CommonCalendarService.selectors.dateButtons).each(function(event) {
	      //  return $(this).addClass('selected');
	      //});
	      Parameter.tmpDate = prevYyyymm;

	      return CommonCalendarService.drawPager(Parameter.tmpDate);
	    };
	    prev();
	    return CommonCalendarService.getCalendarFacet();
	  };

	  CommonCalendarService.next = function() {
	    var next;
	    next = function() {
	      var currentM, currentYyyy, nextM, nextYyyy, nextYyyymm;
	      currentYyyy = $(CommonCalendarService.selectors.yyyy).html();
	      currentM = $(CommonCalendarService.selectors.m).html();
	      nextYyyy = parseInt(currentYyyy, 10);
	      nextM = parseInt(currentM, 10) + 1;

	      if (nextM > 12) {
	        nextYyyy++;
	        nextM = 1;
	      }
	      nextYyyymm = nextYyyy.toString() + nextM.zeroPad(10);
	      CommonCalendarService.drawCalendar(nextYyyymm);
	      //$(CommonCalendarService.selectors.dateButtons).each(function(event) {
	      //  return $(this).addClass('selected');
	      //});
	      Parameter.tmpDate = nextYyyymm;
	      return CommonCalendarService.drawPager(Parameter.tmpDate);
	    };
	    next();
	    return CommonCalendarService.getCalendarFacet();
	  };

	  CommonCalendarService.bindPagerButtons = function() {
	    $(this.selectors.pagerPrev).click(function(event) {
	      event.preventDefault();
	      return CommonCalendarService.prev();
	    });
	    $(this.selectors.pagerNext).click(function(event) {
	      event.preventDefault();
	      return CommonCalendarService.next();
	    });
	    $(this.selectors.tablePrev).click(function(event) {
	      event.preventDefault();
	      return CommonCalendarService.prev();
	    });
	    $(this.selectors.tableNext).click(function(event) {
	      event.preventDefault();
	      return CommonCalendarService.next();
	    });
	    return $(this.selectors.pagerMonth).click(function(event) {
	      var yyyymm;
	      event.preventDefault();
	      yyyymm = $(this).attr('id').replace('pager_', '');
	      CommonCalendarService.drawPager(yyyymm);
	      //$(CommonCalendarService.selectors.dateButtons).each(function(event) {
	      //  return $(this).addClass('selected');
	      //});
	      Parameter.tmpDate = yyyymm;

	      return CommonCalendarService.getCalendarFacet();
	    });
	  };

	//■検索　日付（画面表示時）
	//$(document).on('pageshow','#search_date_page',function(e) {
	CommonCalendarService.getCalendarFacet= function()
	{
		if(Parameter.tmpDate == undefined)
		{
			// 取得するスタート月
			$(FormID+"#ViewMonth").val(CalendarService.createThisMonthYyyymm());
		}
		else
		{
			$(FormID+"#ViewMonth").val(Parameter.tmpDate);
		}

		var options = {
			formObj:'#searchTour',
			kind:"Date",
			p_data_kind:4,
			p_rtn_data:'p_conductor'
		}
		searchTour.requestProcess(options);	//Ajax通信実施

		var settings = {
			dataType: "json",
			success: function(json){

				searchTour.jsonData = json;

				// 取得できるとき
				if(json.p_dep_day != undefined)
				{
					//応答結果を編集
					html(json,'p_date');
				}
				else
				{
					// すべての日を選択をできないようにする
			    	$(FormID+".SW_SD_Caption a").addClass("disabled");
				}

			}
		}
		searchTour.ajaxProcess(settings);
/*
		setTimeout(function(){
			getHitNum('date');
		}, 1000);
*/
		var html = function(json,myname){

			for(n in json.p_dep_month){
				var tgDate = n;
				break;
			}

			var for_name = 'p_date_rd';
			var type = 'checkbox';
			var c = c2 = '';
			var in_html = '';
			var dataCnt = searchTour.count(json[myname]);
			var reqPara = searchTour.getReqParam(myname);

			tgDate.match(/([0-9]{4})([0-9]{2})([0-9]{2})/);
			var year = RegExp.$1
			var month = RegExp.$2;
//			month = '06';

			CommonCalendarService.calendarContent(json);
//			calendarDisp(year+month,json);

			//get today
			var today = new Date();
				now_year = today.getFullYear();
				now_month = today.getMonth() + 1;
				now_month = ("0"+now_month).slice(-2);
			now_ym = now_year + now_month;
			var ym_diff = compareDate(year,month,1,now_year,now_month,1);
			if(ym_diff > 0){
				$(FormID+".pagePrev").show();
			}else{
				$(FormID+".pagePrev").hide();
			}
		}
	};

	  CommonCalendarService.drawPager = function(yyyymm) {
	    var index, thisMonthYyyymm;
	    thisMonthYyyymm = CalendarService.createThisMonthYyyymm();
	    CommonCalendarService.drawCalendar(yyyymm);
	    if (yyyymm === thisMonthYyyymm) {
	      return CommonCalendarService.initPagerMonthButtons();
	    } else {
	      index = 0;
	      return $(CommonCalendarService.selectors.pagerMonth).each(function(event) {
	        var currentM, currentYyyy, currentYyyymm;
	        if (index === 1) {
	          $(this).parent().addClass('selected');
	        } else {
	          $(this).parent().removeClass('selected');
	        }
	        switch (index) {
	          case 0:
	            currentYyyy = parseInt($(CommonCalendarService.selectors.yyyy).html(), 10);
	            currentM = parseInt($(CommonCalendarService.selectors.m).html(), 10);
	            currentM--;
	            if (currentM < 1) {
	              currentYyyy--;
	              currentM += 12;
	            }
	            break;
	          case 2:
	            currentYyyy = parseInt($(CommonCalendarService.selectors.yyyy).html(), 10);
	            currentM = parseInt($(CommonCalendarService.selectors.m).html(), 10);
	            currentM++;
	            if (currentM > 12) {
	              currentYyyy++;
	              currentM -= 12;
	            }
	            break;
	          default:
	            currentYyyy = parseInt($(CommonCalendarService.selectors.yyyy).html(), 10);
	            currentM = parseInt($(CommonCalendarService.selectors.m).html(), 10);
	        }
	        currentYyyymm = currentYyyy.toString() + parseInt(currentM, 10).zeroPad(10);
	        $(this).attr({
	          'id': "pager_" + currentYyyymm
	        });
	        $(this).html(currentM+"月");

	        return index++;
	      });
	    }
	  };

	  return CommonCalendarService;
	})(CalendarService);

	// 確定ボタンを押したら
	$(document).on("click",".decide-btn.date", function() {

		  var html = CalendarService.updateDateView();

		  // 確定ボタンの共通Action
		  decideBtnActionAdd('date',html,false);

	});

	// 削除ボタンを押したらremove
	$(document).on("click",".del_date", function() {

		clearDate();

	});

}).call(this);

// モーダルビューの閉じるボタン、選び直しなどのクリア処理
function clearDate()
{
	// クリアの共通Action
	clearAction('date','p_dep_date',false,true);
}
