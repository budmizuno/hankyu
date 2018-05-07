<link type="text/css" rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/cupertino/jquery-ui.min.css"/>
<script type="text/javascript" src="https://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
<script type="text/javascript">

    /*----閉じるボタン------------------------------------------------------------*/
    $(document).on('click', '.selectClose', function () {
        $(this).parents('#rBox').fadeOut("fast");
        $('#overlay').fadeOut("fast");
        void(0);
        return false;
    });

</script>
<div class="searchBlk tab-content-search left tab-content-search-pink-2 bdColor">
    <h2 class="tab-tt mainBgClr"><i class="sprite sprite-search"></i><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?> フリープラン検索<p class="clBtn"><a href="javascript:void(0);">クリア</a></p></h2>
    <form name="iSearchBox-freeplan" method="post" id="iSearchBox-freeplan" action="/search/ifree.php">
        <input name="MyNaigai" type="hidden" value="i"/>
        <input name="flgiFree" type="hidden" value="1"/>
        <input name="p_conductor" type="hidden" value=""/>
        <input name="p_mainbrand" type="hidden" value="">
        <input name="p_bunrui" type="hidden" value="030">
        <input id="p_price_min" type="hidden" name="p_price_min" value="">
        <input id="p_price_max" type="hidden" name="p_price_max" value="">
        <input id="def_p_mokuteki" name="def_p_mokuteki" type="hidden" value="<?=$mokuteki;?>">
        <input id="p_mokuteki" name="p_mokuteki" type="hidden" value="<?=$mokuteki;?>">
        <input id="def_p_hatsu" type="hidden" name="def_p_hatsu" value="<?php e($def_p_hatsu); ?>"
               data-val="<?php e($def_kyotenName); ?>">
        <input id="p_hatsu" type="hidden" value="<?php e($def_p_hatsu); ?>" name="p_hatsu">
        <input id="p_category" name="p_category" type="hidden" value="<?=$categoryType;?>">
        <input id="p_search_country" name="p_search_country" type="hidden" value="<?=$masterCsv[KEY_MASTER_CSV_SEARCH_COUNTRY];?>">
        <input id="p_except_country" name="p_except_country" type="hidden" value="<?=$except_country_text;?>">
        <table>
            <tr>
                <th><span class="IconBg">出発地</span></th>
                <td>
                    <input type="text" value="<?php e($def_kyotenName); ?>" id="preHatsu" name="preHatsu"
                           autocomplete="off" data-code="<?php e($def_p_hatsu); ?>"
                           def-data-code="<?php e($def_p_hatsu); ?>" readonly>
                </td>
            </tr>
            <tr>
                <th><span class="IconBg">目的エリア</span></th>
                <td class="rootBox">
                    <strong></strong>
                    <input type="hidden" value="" id="preDest_free" name="preDest_free" autocomplete="off" data-code="<?=$SettingData->Dest;?>"
                           readonly>
                </td>
            </tr>
            <tr>
                <th><span class="th-arrow IconFont">国</span></th>
                <td class="rootBox">
                    <?php if($categoryType == CATEGORY_TYPE_DEST): // 方面ページなら ?>
                        <input type="text" value="" id="preCountry_free" name="preCountry_free" autocomplete="off" data-code="" readonly>
                    <?php elseif($categoryType == CATEGORY_TYPE_COUNTRY): // 国ページなら ?>
                        <?php if(!empty($masterCsv[KEY_MASTER_CSV_SEARCH_COUNTRY])): // 複数選択の場合 ?>
                            <input type="text" value="" id="preCountry_free" name="preCountry_free" autocomplete="off" data-code="" readonly>
                        <?php else:?>
                            <?php if($except_country_text != '' && $masterCsv[KEY_MASTER_CSV_NAME_JA] != 'マカオ'): // 例外国の場合 ?>
                                <input type="text" value="" id="preCountry_free" name="preCountry_free" autocomplete="off" data-code="" readonly>
                            <?php else:?>
                                <strong></strong>
                                <input type="hidden" value="" id="preCountry_free" name="preCountry_free" autocomplete="off" data-code="<?=$country_code;?>" readonly>
                            <?php endif;?>
                        <?php endif;?>
                    <?php else: // 都市ページなら ?>
                        <strong></strong>
                        <input type="hidden" value="" id="preCountry_free" name="preCountry_free" autocomplete="off" data-code="<?=$country_code;?>" readonly>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th><span class="th-arrow IconFont">都市</span></th>
                <td class="rootBox">
                    <?php if($masterCsv[KEY_MASTER_CSV_NAME_JA] == 'マカオ'): ?>
                        <strong><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?></strong>
                        <input type="hidden" value="" id="preCity_free" name="preCity_free" autocomplete="off" data-code="<?=$city_code;?>" readonly>
                    <?php else:?>
                        <input type="text" value="" id="preCity_free" name="preCity_free" autocomplete="off" data-code="" readonly>
                    <?php endif;?>
                </td>
            </tr>
            <tr>
                <th><span class="IconBg">出発日</span></th>
                <td>
                    <input name="p_dep_date" id="p_dep_date_eu" type="text" value="" placeholder=" 例）<?php echo date("Y/m/d"); ?>" class="jq-placeholder"/><img src="/sharing/common16/images/searchCal.png" class="js_dep_date_cal">
                </td>
            </tr>
            <tr>
                <th><span class="IconBg">旅行日数</span></th>
                <td>
                    <div class="list-inline">
                        <span class="dayAssignBox tb-com font-14 txt-center" id="kikan_minmax">旅行</span>
                        <p class="dayClearBtn tb-link txt-center"><a href="javascript:void(0);"
                                                                     class="color-blue">日数を<br>クリア</a>
                        </p>
                        <input id="p_kikan_min" type="hidden" name="p_kikan_min" value="">
                        <input id="p_kikan_max" type="hidden" name="p_kikan_max" value="">
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="tb-month" id="kikan_list">
                        <tr class="FClear kikan_list">
                            <td data-val="2" id="123">2日</td>
                            <td data-val="3">3日</td>
                            <td data-val="4">4日</td>
                            <td data-val="5">5日</td>
                        </tr>
                        <tr class="FClear kikan_list">
                            <td data-val="6">6日</td>
                            <td data-val="7">7日</td>
                            <td data-val="8">8日</td>
                            <td data-val="9">9日〜</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="addBox" style="display: none;">
                        <table class="co">
                            <tr>
                                <th><span>航空会社</span></th>
                                <td>
                                    <ul class="spec">
                                        <li class="retCarr">指定しない</li>
                                    </ul>
                                    <p class="btn"><a href="javascript:void(0);" class="Box_p_carr">航空会社を選択する</a></p>
                                </td>
                            </tr>
                        </table>
                        <table class="seat">
                            <tr>
                                <th><span>座席クラス</span></th>
                                <td>
                                    <ul class="FClear">
                                        <li>
                                            <label for="p_seatclass01">
                                                <input id="p_seatclass01" type="checkbox" name="p_seatclass" value="0"
                                                       class="">
                                                エコノミー</label>
                                        </li>
                                        <li>
                                            <label for="p_seatclass02">
                                                <input id="p_seatclass02" type="checkbox" name="p_seatclass" value="3"
                                                       class="">
                                                プレミアムエコノミー</label>
                                        </li>
                                        <li>
                                            <label for="p_seatclass03">
                                                <input id="p_seatclass03" type="checkbox" name="p_seatclass" value="1"
                                                       class="">
                                                ビジネス</label>
                                        </li>
                                        <li>
                                            <label for="p_seatclass04">
                                                <input id="p_seatclass04" type="checkbox" name="p_seatclass" value="2"
                                                       class="">
                                                ファースト</label>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        </table>
                        <table class="hotelGrade">
                            <tr>
                                <th><span>ホテルグレード</span></th>
                                <td>
                                    <ul class="FClear">
                                        <li>
                                            <label for="p_bunrui_none">
                                                <input id="p_bunrui_none" type="radio" name="p_bunrui" value="">
                                                指定なし</label>
                                        </li>
                                        <li>
                                            <label for="p_bunrui_ht3">
                                                <input id="p_bunrui_ht3" type="radio" name="p_bunrui" value="ht3">
                                                スタンダードクラス</label>
                                        </li>
                                        <li>
                                            <label for="p_bunrui_ht2">
                                                <input id="p_bunrui_ht2" type="radio" name="p_bunrui" value="ht2">
                                                スーペリアクラス</label>
                                        </li>
                                        <li>
                                            <label for="p_bunrui_ht1">
                                                <input id="p_bunrui_ht1" type="radio" name="p_bunrui" value="ht1">
                                                デラックスクラス以上</label>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        </table>
                        <table class="hotel">
                            <tr>
                                <th><span>ホテル指定</span></th>
                                <td>
                                    <ul class="spec">
                                        <li class="retHotelName">指定しない</li>
                                    </ul>
                                    <p class="btn"><a href="javascript:void(0);" class="Box_p_hotel_code">ホテルを選択する</a>
                                    </p></td>
                            </tr>
                        </table>
                        <table class="priceSet">
                            <tr>
                                <th><span>旅行代金</span></th>
                                <td>
                                    <div class="sliderBox">
                                        <div class="txtPric"
                                             style="width:200px; line-height:180%; overflow:hidden; clear:both; margin-bottom:7px;font-size:14px;">
                                            1,000〜1,000,000円以上
                                        </div>
                                        <div id="slider" style="width:183px; margin:0 0 7px 7px; clear:both;"></div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <table class="discount">
                            <tr>
                                <th><span>割引</span></th>
                                <td>
                                    <ul class="FClear">
                                        <li>
                                            <label for="p_discount01">
                                                <input id="p_discount01" type="checkbox" name="p_discount" value="1">
                                                早期割引</label>
                                        </li>
                                        <li>
                                            <label for="p_discount02">
                                                <input id="p_discount02" type="checkbox" name="p_discount" value="2">
                                                子供割引</label>
                                        </li>
                                        <li>
                                            <label for="p_discount03">
                                                <input id="p_discount03" type="checkbox" name="p_discount" value="3">
                                                学生割引</label>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        </table>
                        <table class="oil">
                            <tr>
                                <th><span>燃油サーチャージ</span></th>
                                <td>
                                    <ul class="FClear">
                                        <li>
                                            <label for="p_total_amount_divide02">
                                                <input id="p_total_amount_divide02" type="radio"
                                                       name="p_total_amount_divide" value="1">
                                                込</label>
                                        </li>
                                        <li>
                                            <label for="p_total_amount_divide03">
                                                <input id="p_total_amount_divide03" type="radio"
                                                       name="p_total_amount_divide" value="2">
                                                なし</label>
                                        </li>
                                        <li>
                                            <label for="p_total_amount_divide01">
                                                <input id="p_total_amount_divide01" type="radio"
                                                       name="p_total_amount_divide" value="0">
                                                別</label>
                                        </li>
                                        <li>
                                            <label for="p_total_amount_divide04">
                                                <input id="p_total_amount_divide04" type="radio"
                                                       name="p_total_amount_divide" value="" checked="checked">
                                                全て</label>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <p class="srchResult pt10 txt-center">現在の該当件数<span class="txt-red">0000</span>件です</p>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="txt-center pb0 w50 pb10">
                    <p class="topSearchAdd"><a href="javascript:void(0);">検索条件を追加する</a></p>
                    <button type="button" class="btn_simpleSrch bt-search"><i class="sprite sprite-search"></i>検索
                    </button>
                </td>
            </tr>
        </table>
    </form>
</div>
<script type="text/javascript" src="/attending/senmon_kaigai/js/searchIFree.js?ver=20170111"></script>
<script type="text/javascript">
    var postData = {};
    $(".searchTour").searchTour('init', {inPost: postData});
</script>
