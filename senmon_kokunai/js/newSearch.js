var sbcTour = {
    FormID: "#dSearchBoxTour"	//例：#dSearchBoxTour
    , Naigai: 'd'
    , SetTg: ''	//例：Dest	//ファセットを返したいパラメータ
    , ErrMes: '必須項目を設定してください'
    , DepDateSel: ''	//出発日セレクタ
    , SubWinID: 'SubWinBox'
    , TgIdName: '#SubWinBox'	//オーバーレイセレクタ
    , TGOverlaySelector: ':has("#SubWinBox")'
    , SetDepDateToday: ''	//出発日の入力例

    /*--------------------
     ajaxする
     ----------------------*/
    , SendSearch: function (TgSelector, TgType, AddVar) {
        /*変数の定義*/
        this.FormID = '#dSearchBoxTour';
        this.SetTg = '';
//        var BaseName = '/sharing/__br/phpsc/ajax_searchBox.php';
        var BaseName = '/attending/senmon_kokunai/sharing/phpsc/ajax_searchBox.php';

        if ($(this.FormID + " input[type=hidden][name=MyType]").val() == "bus") {
            BaseName = '/sharing/__br/phpsc/ajax_searchBox_bus.php';
        }
        var ReSearchSelector = this.FormID + ' input[type=text], ' + this.FormID + ' input[type=hidden], ' + this.FormID + ' input[type=checkbox]:checked, ' + this.FormID + ' input[type=radio]:checked';
        var ReSearchSelectorSel = this.FormID + ' select';
        //var forWMDepDate = this.FormID+' input[name=p_dep_date]';
        var DisabledSel = '';	//触れなくするプルダウン

        var ObjA = new Object();
        var ObjB = new Object();
        var paramObj = new Object();
        //自分の名前は何？
        var TgName = $(TgSelector).attr('name');

        //自分自身がp_hatsuだったとき、方面・国・都市をクリアする必要がある
        if (TgName == 'p_hatsu' || TgName == 'p_hatsu_sub') {
            this.ClearDCC('Dest,Country,City');
            this.ClearBUS();
            this.ClearKikan();
            /*this.ClearCarr();
             this.ClearDepAP();
             this.ClearKikan();
             //値があったら、必須はhidden、無かったらview
             var RQSelector = this.FormID+' #RQp_hatsu';
             $(RQSelector).toggle($(TgSelector).val() == false);
             */
            //固定するのは目的地
            DisabledSel = this.FormID + ' #preDest';
            $(DisabledSel).attr('disabled', 'disabled');

        }
        if (TgName == 'p_bus_boarding_code') {
            this.ClearDCC('Dest,Country,City');
            //this.ClearKikan();
            //値があったら、必須はhidden、無かったらview
            //var RQSelector = this.FormID+' #RQp_hatsu';
            //$(RQSelector).toggle($(TgSelector).val() == false);
            //固定するのは目的地
            DisabledSel = this.FormID + ' #preDest';
            $(DisabledSel).attr('disabled', 'disabled');
        }
        //自分がdestなら、国都市クリア
        if (TgName == 'preDest') {
            if(TgType != 4){
                this.ClearDCC('Country,City');
                this.ClearKikan();
            }
            /*this.ClearBUS();
             this.ClearCarr();
             this.ClearKikan();
             //値があったら、必須はhidden、無かったらview
             var RQSelector = this.FormID+' #RQ'+TgName;
             $(RQSelector).toggle($(TgSelector).val() == false);
             */
            //固定するのは国
            DisabledSel = this.FormID + ' #preCountry';
            $(DisabledSel).attr('disabled', 'disabled');

        }
        //自分がCountryなら、都市クリア
        if (TgName == 'preCountry') {
            this.ClearDCC('City');
            this.ClearKikan();
            /*this.ClearBUS();
             this.ClearCarr();
             this.ClearKikan();
             */
            //固定するのは都市
            DisabledSel = this.FormID + ' #preCity';
            $(DisabledSel).attr('disabled', 'disabled');

        }
        /*
         //他もバスクリア
         if(TgName == 'preCity' || TgName == 'p_kikan_min' || TgName == 'p_kikan_max'){
         this.ClearBUS();
         }
         //キャリアもクリア
         if(TgName == 'preCity'){
         this.ClearCarr();
         this.ClearKikan();
         }
         //出発空港
         if(TgName == 'p_dep_airport_code'){
         this.ClearDCC('Dest,Country,City');
         this.ClearKikan();
         }
         //交通手段
         if(TgName == 'p_transport[]'){
         this.ClearKikan();
         }
         */
        var ObjA = FncValueSetAry(ReSearchSelector, ',');	//input型
        var ObjB = FncValueSetSelectAry(ReSearchSelectorSel, ',');	//selsect型
        var paramObj = $.extend(ObjA, ObjB);	//配列の結合
        /*Ajax*/
        switch (TgType) {
            case 0:	//通常
                $.ajax({
                    data: paramObj
                    , dataType: 'script'
                    , url: BaseName
                });
                break;

            case 1:	//自分自身が何か＋ファセット返して欲しいのは何か
                paramObj['SetParam'] = TgName;
                if (this.SetTg == 99) {
                    this.SetTg = '';
                }
                paramObj['RetParam'] = this.SetTg;
                $.ajax({
                    data: paramObj
                    , dataType: 'script'
                    , url: BaseName
                    , success: function (html) {
                        var aaa = html;
                    }

                });
                break;
            case 2:	//出発日前へ次へ
                paramObj['SetParam'] = TgName;
                paramObj['ViewMonth'] = AddVar;
                $.ajax({
                    data: paramObj
                    , dataType: 'script'
                    , url: BaseName
                });
                break;
            case 3:	//自分自身が何か＋ファセット返して欲しいのは何か
                if (TgName == 'p_bus_boarding_code') {
                    var busV = AddVar['p_bus_boarding_code'];
                    paramObj['SetParam'] = TgName;
                    paramObj[TgName] = busV;
                    paramObj['RetParam'] = 0;
                }

                $.ajax({
                    data: paramObj
                    , dataType: 'script'
                    , url: BaseName
                    , success: function (html) {


                        if (AddVar['preDest'] && TgName == 'p_bus_boarding_code') {

                            var dV = AddVar['preDest'];
                            $('#dSearchBoxTour').find('#preDest').val(dV).removeAttr("disabled");
                        }

                        else {
                            if (AddVar['p_dep_date']) {
                                $('#dSearchBoxTour').find('#p_dep_date').val(AddVar['p_dep_date']).trigger('change');

                            }
                        }
                    }
                });
                break;

            case 4:	//ブラウザバック
                paramObj['SetParam'] = TgName;
                paramObj['browser_back_flag'] = true;

                if (TgName == 'preDest') {
                    paramObj['RetParam'] = 1;
                }
                else if (TgName == 'preCountry') {
                    paramObj['RetParam'] = 2;
                }

                var formid = AddVar['formid'];
                $.ajax({
                    data: paramObj
                    , dataType: 'script'
                    , url: BaseName
                    , success: function (html) {
                    }
                    , complete: function () {
                        if (TgName == 'preDest') {
                            if (AddVar['preCountry']) {
                                if ($(formid + ' #preCountry')[0]) {
                                    $(AddVar['formid'] + ' #preCountry').val(AddVar['preCountry']).prop("selected", true);
                                    //sbcTour.SendSearch($(formid).find('#preCountry'), 4, AddVar);
                                }
                            }

                        }
                        else if (TgName == 'preCountry') {

                            if (AddVar['preCity']) {

                                if ($(formid + ' #preCity')[0]) {
                                    $(AddVar['formid'] + ' #preCity').val(AddVar['preCity']).prop("selected", true);
                                    //sbcTour.SendSearch($(AddVar['formid']).find('#p_conductor'), 1);
                                }
                            }
                        }
                        //void(0);
                        //return false;
                    }

                });
                break;

        }
        //固定戻す
        if (DisabledSel) {
            $(DisabledSel).attr('disabled', false);
        }
        //出発日戻す
        //sbcTour.WatermarkDep(forWMDepDate);

    }
    , DepDate: function (DepDateSel) {
        //まだボックスが出てなければ表示させる
        if (!$('body').is(this.TGOverlaySelector)) {
            //メッセージボックスを作る
            MakeOverLay('auto', 700, 'body', this.SubWinID, this.SubWinID);	//高さ、幅、どこに作る、ID、Class
            if (!this.FormID) {
                this.FormID = '#' + $(DepDateSel).parents('form').attr('id');
            }
            // IE6でselectとobjectが前面に来る対策 - 非表示にする
            //$("select,object").css("visibility","hidden");
            $(this.TgIdName).hide();	//Ajax終わるまで隠しておく
            //通信
            this.SendSearch(DepDateSel, 1);
            //入れ物の位置を設定
            var Offset = $(DepDateSel).position();
            var scrollTop = $('body').scrollTop();
            if (scrollTop == 0) {
                scrollTop = $('html').scrollTop();
            }
            var top  = Math.floor(($(window).height() - $(this.TgIdName).height()) / 2) + scrollTop -200;
//            var top = 1279; //custom by Lamnv
            var left = Math.floor(($(window).width() - $(this.TgIdName).width()) / 2);
            if (top < 0) {
                top = 0;
            }
            $(this.TgIdName)
                .css({
                    "top": top
                    , "left": left
                }).fadeIn();
        }
    }
    , DelSubWinforSenmon: function () {
        if ($('body').is(sbcTour.TGOverlaySelector)) {
            $(sbcTour.TgIdName).fadeOut("fast", function () {
                $(sbcTour.TgIdName).remove();
            });
            //IE6対策を元に戻す
            //$("select,object").css("visibility","visible");
        }
    }
    , clearSet: function () {

        $("#dSearchBoxTour,#dSearchBoxTour").find("textarea, :text, select").val("").end().find(":checked").prop("checked", false);
        $("#dSearchBoxTour,#dSearchBoxTour").find("#p_hatsu_eu option:first,#p_hatsu_sub option:first").prop('selected', true);
    }
    /*出発日でAjaxしなきゃ*/
    , WatermarkAjaxDep: function (DepDateSel) {
        var FormID = this.FormID;
        if (!this.FormID) {
            FormID = '#' + $(DepDateSel).parents('form').attr('id');
        }

        /*if (DepDateSel.selector) {
         var b=TgSelector.selector.split(" ")
         FormID=b[0];
         }*/


        //リクエストしなおし
        if ($('select[name=p_conductor]', FormID)[0].type == 'select-one') {
            this.SendSearch($('select[name=p_conductor]', FormID), 1);
        }
        else if ($('input[name=p_conductor]', FormID)[0].type == 'hidden') {
            this.SendSearch($('input[name=p_conductor]', FormID), 1);
        }
        else if ($('select[name=	p_kikan_min]', FormID)[0].type == 'select-one') {
            this.SendSearch($('select[name=p_kikan_min]', FormID), 1);
        }

        //this.WatermarkDep(DepDateSel);
    },
    setConditions: function (req) {
        var req_obj = new Object();
        for (i in req) {
            var trimVal = req[i];
            if (typeof(trimVal) != "undefined") {
                req_obj[i] = trimVal.replace(/\,+$/, "");
            }
        }
        var url = location.href;
        this._locChange('', url, req_obj);
    },
    _locChange: function (id, url, params) {
        if ('pushState' in history) {
            history.pushState(params, '', url);
        } else {
            //Unsupported
        }
    },
    _onLocChanged: function (e) {
        if (!e.state) {
            return;
        }
        var parames = e.state;

        for (i in parames) {

            var trimVal = parames[i];
            if (typeof(trimVal) != "undefined" && trimVal != null) {
                parames[i] = trimVal;
            }
        }
        return parames;
    }

    /*送信するとき*/
    , Submit: function (ClickBtn) {

        /*エラーの条件*/
        /*変数の定義*/
        this.FormID = '#dSearchBoxTour';

        var ReSearchSelector = this.FormID + ' input[type=text], ' + this.FormID + ' input[type=hidden]';
        var ReSearchSelectorSel = this.FormID + ' select';

        var ObjA = new Object();
        var ObjB = new Object();
        var paramObj = new Object();

        var ObjA = FncValueSetAry(ReSearchSelector, ',');	//input型
        var ObjB = FncValueSetSelectAry(ReSearchSelectorSel, ',');	//selsect型
        var paramObj = $.extend(ObjA, ObjB);	//配列の結合

        /*出発日処理*/
        //var forWMDepDate = this.FormID+' input[name=p_dep_date]';
        //this.WatermarkOutDep(forWMDepDate);

        /*必須チェック*/
        //出発地
        /*var checkHatsuVal = false;
         if(paramObj['MyNaigai'] == 'i'){
         checkHatsuVal = paramObj['p_hatsu'];
         }
         else if(paramObj['MyNaigai'] == 'd'){
         checkHatsuVal = paramObj['p_hatsu_sub'];
         }*/

        //ちぇっくちぇっく
//		if(!checkHatsuVal || !paramObj['preDest']){
        /*if(!checkHatsuVal){
         alert(sbcTour.ErrMes);
         void(0);
         return false;
         }*/

        /*サイトカタリストの処理*/
        //utilityJs.SAS_setCookie('SAS_VARS_TYPE', '検索', '', '/', 'hankyu-travel.com', '');
        SAS_setCookie('SAS_VARS_TYPE', '検索', '', '/', '133.18.4.189', '');

        /*目的地の処理*/
        var MokutekiVal = '';
        if (paramObj['preDest']) {
            var DestSplit = paramObj['preDest'].split(',');
            if (DestSplit.length > 1) {	//複数方面
                jQuery.each(DestSplit, function (i, val) {
                    if (i > 0) {
                        MokutekiVal += ',';
                    }
                    MokutekiVal += val + '--';
                });
            }
            else {
                //方面はひとつ、国が複数
                if (paramObj['preCountry']) {
                    var CountrySplit = paramObj['preCountry'].split(',');
                    if (CountrySplit.length > 1) {	//複数国
                        jQuery.each(CountrySplit, function (i, val) {
                            if (i > 0) {
                                MokutekiVal += ',';
                            }
                            MokutekiVal += paramObj['preDest'] + '-' + val + '-';
                        });
                    }
                    else {
                        if((paramObj['fksCity'] && paramObj['fksCity'].length > 0 && !paramObj['preCity']) || (paramObj['preCity'] && paramObj['preCity'].length <= 0)){
                            var citySplit = paramObj['fksCity'].split(',');
                            if(citySplit.length > 1){ //複数都市
                                jQuery.each(citySplit, function (i, val) {
                                    if (i > 0) {
                                        MokutekiVal += ',';
                                    }
                                    MokutekiVal += paramObj['preDest'] + '-' + paramObj['preCountry'] + '-' + val;
                                });
                            }
                        }else{
                            MokutekiVal = paramObj['preDest'] + '-' + paramObj['preCountry'] + '-' + paramObj['preCity'];
                        }
                    }
                }
                //国はひとつ
                else {
                    MokutekiVal = paramObj['preDest'] + '-' + paramObj['preCountry'] + '-' + paramObj['preCity'];
                }
            }
        }

        MokutekiVal = MokutekiVal.replace(/undefined/ig, '');
        if (MokutekiVal == '--') {
            MokutekiVal = '';
        }
        var ApStr = '<input type="hidden" name="p_mokuteki" value="' + MokutekiVal + '" />';
        paramObj['p_mokuteki'] = MokutekiVal;
        paramObj['formid'] = this.FormID;
        this.setConditions(paramObj);

        $(this.FormID).append(ApStr);
        $(this.FormID).submit();
        void(0);
        return false;
    }
    /*バスをクリアする*/
    , ClearBUS: function () {
        //バスセレクタ
        var BusSel = this.FormID + ' select[id=p_bus_boarding_code]';
        /*バスは全共通*/
        if ($(BusSel).attr('id')) {
            $(':gt(0)', BusSel).remove();
            sbcTour.SetTg = 3;
        }
    }
    /*日数をクリアする*/
    , ClearKikan: function () {
        //バスセレクタ
        var KikanMinSel = this.FormID + ' #p_kikan_min';
        var KikanMaxSel = this.FormID + ' #p_kikan_max';
        // バスは全共通
        if ($(KikanMinSel).attr('id')) {
            $(':gt(0)', KikanMinSel).remove();
        }
        if ($(KikanMaxSel).attr('id')) {
            $(':gt(0)', KikanMaxSel).remove();
        }
    }
    , ClearDCC: function (Type) {
        //方面・国・都市セレクタ
        var formID = this.FormID;
        var destObj = $(formID + " #preDest");
        var countryObj = $(formID + " #preCountry");
        var cityObj = $(formID + " #preCity");

        var TypeAry = Type.split(',');
        jQuery.each(TypeAry, function (i, str) {
            switch (str) {
                case 'Dest':
                    if (destObj.attr('type') != 'hidden') {
                        if ($(':first', destObj).val() == '') {
                            $(':gt(0)', destObj).remove();
                            if (sbcTour.SetTg === '') {
                                sbcTour.SetTg = 0;
                            }
                            var RQSelector = sbcTour.FormID + ' #RQpreDest';
                            $(RQSelector).show();
                        }
                        else {
                            if (sbcTour.SetTg === '') {
                                sbcTour.SetTg = 99;
                            }
                        }
                    }
                    break;
                case 'Country':
                    if (countryObj.attr('type') != 'hidden') {
                        if ($(':first', countryObj).val() == '') {
                            //if($(testForm).find("#preCountry").eq(0).val() == ''){
                            $(':gt(0)', countryObj).remove();
                            if (sbcTour.SetTg === '') {
                                sbcTour.SetTg = 1;
                            }
                        }
                        else {
                            if (sbcTour.SetTg === '') {
                                sbcTour.SetTg = 99;
                            }
                        }
                    }
                    break;
                case 'City':
                    if (cityObj.attr('type') != 'hidden') {
                        if ($(':first', cityObj).val() == '') {
                            $(':gt(0)', cityObj).remove();
                            if (sbcTour.SetTg === '') {
                                sbcTour.SetTg = 2;
                            }
                        }
                        else {
                            if (sbcTour.SetTg === '') {
                                sbcTour.SetTg = 99;
                            }
                        }
                    }
                    break;
            }
        });
    }

};
//--------------------
//	日付を押したとき（出発日）
//----------------------
var SWDateTour = function (SetVal) {
    var DepDateSel = sbcTour.FormID + ' input[name=p_dep_date]';
    $(DepDateSel).val(SetVal).removeClass('NS_Watermark');	//セットして
    sbcTour.DelSubWinforSenmon();	//閉じる
    //IE6対策を元に戻す
    //$("select,object").css("visibility","visible");

}
//--------------------
//	前へ次へ（出発日）
//----------------------
var NextBackBtnActionTour = function (DepDate) {
//function NextBackBtnAction (DepDate){
    var DepDateSel = sbcTour.FormID + ' input[name=p_dep_date]';
    $(DepDateSel).focus();
    //通信
    sbcTour.SendSearch(DepDateSel, 2, DepDate);
    void(0);
    return false;
}

/*
 *日付取得 yyyy/mm/dd
 */
var getYYYYMMDD = function (nextDays) {
    var date = new Date();
    date.setDate(date.getDate() + nextDays);
    var year = date.getFullYear();
    var month = date.getMonth() + 1;
    var day = date.getDate();
    if (month < 10) {
        return year + "/0" + month + "/" + day;
    }
    return year + "/" + month + "/" + day;
}
var Change = function () {
    radio = document.getElementsByName('p_transport');
    if (radio[0].checked) {
        document.getElementById('airplain').style.display = "";
        document.getElementById('trainbus').style.display = "none";
        $('select').attr('selectedIndex', '0').children('select').removeAttr('select');
    }
    window.onload = Change;
}
/*
 *初期化
 */
$(function () {

    sbcTour.DelSubWinforSenmon();
//    sbcTour.clearSet();
    /*検索枠Ｃｏｍｂｏｂｏｘデータをロードする*/
    //setInitSelect();
    var req = {};
    if ('pushState' in history) {
        setTimeout(function () {
            //Get post data or default
            //req = methods.getPostParames();
            window.addEventListener('popstate', function (e) {
                if (e.state != null) {
                    //Get history data
                    req = sbcTour._onLocChanged(e);
                }
            });
            if (typeof window.history.state !== "undefined") {
                //Get history data
                req = sbcTour._onLocChanged(window.history);
            }

            if (req) {
                if (req.MyNaigai == 'd') {
                    changeSearch('.smSearchD');
                }
                else {
                    changeSearch('.smSearchI');
                }
                var formID = req.formid;
                for (i in req) {

                    var slecter = $(formID + ' input[name="' + i + '"]')[0];
                    if (slecter && $(slecter).attr('type') == 'hidden') {
                        $(formID + ' input[name="' + i + '"]').val(req[i]);
                    } else if (slecter && $(slecter).attr('name') == 'p_dep_date') {
                        var dispText = '';
                        var dateVal = String(req[i]);
                        if (dateVal) {
                            if (dateVal.indexOf('/') != -1) {
                                var DateAry = dateVal.split('/');
                                if (typeof(DateAry[2]) == 'undefined' || DateAry[2] == '') {
                                    dateVal = DateAry[0] + ( '0' + DateAry[1] ).slice(-2);
                                } else {
                                    dateVal = DateAry[0] + ("0" + DateAry[1]).slice(-2) + ("0" + DateAry[2]).slice(-2);
                                }
                            }
                            dateVal.match(/([0-9]{4})([0-9]{2})([0-9]{2})?/);
                            var year = RegExp.$1
                            var month = RegExp.$2;
                            var day = RegExp.$3;
                            if (year) {
                                var dispText = year + '/';
                                dispText += month;
                            }
                            if (day) {
                                dispText += '/' + day;
                            }
                        }
                        $(formID + ' input[name="' + i + '"]').val(dispText);
                    } else if (slecter && $(slecter).attr('type') == 'text') {
                        $(formID + ' input[name="' + i + '"]').val(req[i]);
                    }
                    else {
                        if (i != 'preCountry' && i != 'preCity') {
                            $(formID + ' select[name="' + i + '"]').val(req[i]).prop('selected', true);


                        }
                    }
                }

                if (req['preCountry']) {
                    sbcTour.SendSearch($(formID + ' input[name="preDest"]'), 4, req);
                    void(0);
                    return false;
                }
                else {
                    if (req['preDest']) {
                        sbcTour.SendSearch($(formID + ' input[name="preDest"]'), 4,req);
                    }
                    else {
                        sbcTour.SendSearch($(formID + ' select[name="p_conductor"]'), 4,req);
                    }
                }

            }


            //初回リクエスト
            //methods.initRequest(req);
        }, 100);
    }


//    var formAry = $("form");
    var formID = null;
//    for (var i = 0; i < formAry.length; i++) {
            formID = "#dSearchBoxTour";
//        if ($(formID) && ) {
            /*----- 色々触ったらAjax -----*/
            $("#dSearchBoxTour select").change(function () {
                sbcTour.SendSearch(this, 1);
                void(0);


                return false;
            });

            /*----- 出発日は特別 -----*/
            $('#dSearchBoxTour input[name=p_dep_date]').click(function () {

                sbcTour.DepDate(this);
            });

            $('#dSearchBoxTour .js_dep_date_cal').click(function () {
                //$(formID+' input[name=p_dep_date]').trigger("click");
                sbcTour.DepDate($(this).parent('td').find('input[name=p_dep_date]'));
            });

            /*----- 出発日ウォーターマーク -----*/
            var forWMDepDate = formID + ' input[name=p_dep_date]';
            /*変更があったときもね*/
            $(forWMDepDate).change(function () {
                sbcTour.WatermarkAjaxDep(this);
            });
            /*出発日の透析文字を設定する*/
            $(forWMDepDate).attr("placeholder", "例）" + getYYYYMMDD(0));
            /*----------検索---------*/
            $("#dSearchBoxTour .btn_simpleSrch").click(function () {
                sbcTour.Submit(this);
                void(0);
                return false;
            });
            $('#dSearchBoxTour input[type=radio]').click(function () {
                sbcTour.SendSearch(this, 1);
            });
            $('#dSearchBoxTour input[type=checkbox]').click(function () {
                //バスの場合の特別処理
                /*バスは全共通*/
                if ($(this).val() == '1' && $(this).attr('name') == 'p_transport' && $("#bus_bunrui").is("*")) {
                    var BusChecked = $(this).attr('checked');
                    if (BusChecked === false) {
                        $('#bus_bunrui').val('');
                    }
                    else {
                        $('#bus_bunrui').val('813');
                    }
                }
                sbcTour.SendSearch(this, 1);
            });

//        }

//    }


    /*----- 外側クリック対策 -----*/
    $('html').click(function (depEvent) {
        var TargetClass = $(depEvent.target).attr('class');
        var TargetName = $(depEvent.target).attr('name');
        if (TargetClass !== 'SW_CalNext' && TargetClass !== 'SW_CalBack' && TargetName !== 'p_dep_date' && TargetClass !== 'js_dep_date_cal') {
            if ($('html').is(sbcTour.TGOverlaySelector)) {
                //ウィンドウ消す
                sbcTour.DelSubWinforSenmon();
                var forWMDepDate = 'input[name=p_dep_date]';
                jQuery.each($(forWMDepDate), function (i, val) {
                    sbcTour.WatermarkAjaxDep(this);
                });
            }
        }

    });


});
