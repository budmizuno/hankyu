<form id="searchTour" name="searchTour" method="post">
<input type="hidden" id="MyNaigai" name="MyNaigai" value="d">
<input type="hidden" id="p_naigai" name="p_naigai" value="J">
<?php // フリープランかどうかのフラグ?>
<?php if($FREE_FLAG):?>
	<input type="hidden" id="free_flag" name="free_flag" value="1">
<?php else:?>
	<input type="hidden" id="free_flag" name="free_flag" value="">
<?php endif;?>

<?php // 検索画面か再検索画面かのフラグ ?>
<?php if($SEARCH_DETAIL_FLAG):?>
	<input type="hidden" id="search_detail_flag" name="search_detail_flag" value="1">
<?php else:?>
	<input type="hidden" id="search_detail_flag" name="search_detail_flag" value="">
<?php endif;?>

<input type="hidden" id="freeplan_date_init_flag" name="freeplan_date_init_flag" value="false">
<input type="hidden" id="p_category_page" name="p_category_page" value="<?=$categoryType; ?>">
<?php // カテゴリページの出発地 ?>
<input type="hidden" id="p_hatsu_name_category" name="p_hatsu_category" value="<?=$def_kyotenName; ?>">
<input type="hidden" id="p_hatsu_category" name="p_hatsu_category" value="<?=$p_hatsu; ?>">
<input type="hidden" id="def_mokuteki" name="def_mokuteki" value="<?=$mokuteki; ?>">
<input type="hidden" id="def_dest_name" name="def_dest_name" value="<?=$def_dest_name; ?>">
<input type="hidden" id="def_country_name" name="def_country_name" value="<?=$def_country_name; ?>">
<input type="hidden" id="def_city_name" name="def_city_name" value="<?=$def_city_name; ?>">

<input type="hidden" id="p_hatsu_sub" name="p_hatsu_sub" value="<?php e(isset($_POST['p_hatsu_sub']) ? $_POST['p_hatsu_sub'] : ''); ?>">
<input type="hidden" id="p_dep_date" name="p_dep_date" value="<?php e(isset($_POST['p_dep_date']) ? $_POST['p_dep_date'] : ''); ?>">
<input type="hidden" id="ViewMonth" name="ViewMonth" value="">
<input type="hidden" id="p_mokuteki" name="p_mokuteki" value="<?php e(isset($_POST['p_mokuteki']) ? $_POST['p_mokuteki'] : ''); ?>">
<input type="hidden" id="p_mokuteki_kind" name="p_mokuteki_kind" value="2">
<input type="hidden" id="p_kikan_min" name="p_kikan_min" value="<?php e(isset($_POST['p_kikan_min']) ? $_POST['p_kikan_min'] : ''); ?>">
<input type="hidden" id="p_kikan_max" name="p_kikan_max" value="<?php e(isset($_POST['p_kikan_max']) ? $_POST['p_kikan_max'] : ''); ?>">
<input type="hidden" id="p_price_min" name="p_price_min" value="<?php e(isset($_POST['p_price_min']) ? $_POST['p_price_min'] : ''); ?>">
<input type="hidden" id="p_price_max" name="p_price_max" value="<?php e(isset($_POST['p_price_max']) ? $_POST['p_price_max'] : ''); ?>">
<input type="hidden" id="p_price_flg" name="p_price_flg" value="<?php e(isset($_POST['p_price_flg']) ? $_POST['p_price_flg'] : ''); ?>">
<input type="hidden" id="p_conductor" name="p_conductor" value="<?php e(isset($_POST['p_conductor']) ? $_POST['p_conductor'] : ''); ?>">
<input type="hidden" id="p_carr" name="p_carr" value="<?php e(isset($_POST['p_carr']) ? $_POST['p_carr'] : ''); ?>">
<input type="hidden" id="p_transport" name="p_transport" value="<?php e(isset($_POST['p_transport']) ? $_POST['p_transport'] : ''); ?>">
<input type="hidden" id="p_mainbrand" name="p_mainbrand" value="<?php e(isset($_POST['p_mainbrand']) ? $_POST['p_mainbrand'] : ''); ?>">
<input type="hidden" id="p_sort" name="p_sort" value="<?php e(isset($_POST['p_sort']) ? $_POST['p_sort'] : ''); ?>">
<input type="hidden" id="p_bunrui" name="p_bunrui" value="<?php e(isset($_POST['p_bunrui']) ? $_POST['p_bunrui'] : ''); ?>">
<input type="hidden" id="p_data_kind" name="p_data_kind" value="<?php e(isset($_POST['p_data_kind']) ? $_POST['p_data_kind'] : ''); ?>">
<input type="hidden" id="p_rtn_data" name="p_rtn_data" value="<?php e(isset($_POST['p_rtn_data']) ? $_POST['p_rtn_data'] : ''); ?>">
<input type="hidden" id="p_start_line" name="p_start_line" value="<?php e(isset($_POST['p_start_line']) ? $_POST['p_start_line'] : ''); ?>">
<input type="hidden" id="p_rtn_count" name="p_rtn_count" value="<?php e(isset($_POST['p_rtn_count']) ? $_POST['p_rtn_count'] : ''); ?>">
<input type="hidden" id="p_bus_boarding_code" name="p_bus_boarding_code" value="<?php e(isset($_POST['p_bus_boarding_code']) ? $_POST['p_bus_boarding_code'] : ''); ?>">
<input type="hidden" id="p_stock" name="p_stock" value="<?php e(isset($_POST['p_stock']) ? $_POST['p_stock'] : ''); ?>">
<input type="hidden" id="p_decide" name="p_decide" value="<?php e(isset($_POST['p_decide']) ? $_POST['p_decide'] : ''); ?>">
<input type="hidden" id="p_hotel_code" name="p_hotel_code" value="<?php e(isset($_POST['p_hotel_code']) ? $_POST['p_hotel_code'] : ''); ?>">
<input type="hidden" id="p_accommodation_code" name="p_accommodation_code" value="<?php e(isset($_POST['p_accommodation_code']) ? $_POST['p_accommodation_code'] : ''); ?>">
<input type="hidden" id="p_course_id" name="p_course_id" value="<?php e(isset($_POST['p_course_id']) ? $_POST['p_course_id'] : ''); ?>">
<input type="hidden" id="p_hei" name="p_hei" value="<?php e(isset($_POST['p_hei']) ? $_POST['p_hei'] : ''); ?>">
<input type="hidden" id="p_type" name="p_type" value="<?php e(isset($_POST['p_type']) ? $_POST['p_type'] : ''); ?>">
<input type="hidden" id="p_brand" name="p_brand" value="<?php e(isset($_POST['p_brand']) ? $_POST['p_brand'] : ''); ?>">
<input type="hidden" id="p_brandx" name="p_brandx" value="<?php e(isset($_POST['p_brandx']) ? $_POST['p_brandx'] : ''); ?>">
<input type="hidden" id="p_web_conclusion_flag" name="p_web_conclusion_flag" value="<?php e(isset($_POST['p_web_conclusion_flag']) ? $_POST['p_web_conclusion_flag'] : ''); ?>">
<input type="hidden" id="p_dep_airport_code" name="p_dep_airport_code" value="<?php e(isset($_POST['p_dep_airport_code']) ? $_POST['p_dep_airport_code'] : ''); ?>">
<input type="hidden" id="p_arr_airport_code" name="p_arr_airport_code" value="<?php e(isset($_POST['p_arr_airport_code']) ? $_POST['p_arr_airport_code'] : ''); ?>">
<input type="hidden" id="p_genti" name="p_genti" value="<?php e(isset($_POST['p_genti']) ? $_POST['p_genti'] : ''); ?>">
<input type="hidden" id="p_ins_prefecture_code" name="p_ins_prefecture_code" value="<?php e(isset($_POST['p_ins_prefecture_code']) ? $_POST['p_ins_prefecture_code'] : ''); ?>">
<input type="hidden" id="p_ins_area_code" name="p_ins_area_code" value="<?php e(isset($_POST['p_ins_area_code']) ? $_POST['p_ins_area_code'] : ''); ?>">
<input type="hidden" id="p_stay_number" name="p_stay_number" value="<?php e(isset($_POST['p_stay_number']) ? $_POST['p_stay_number'] : ''); ?>">
<input type="hidden" id="p_stay_term_to" name="p_stay_term_to" value="<?php e(isset($_POST['p_stay_term_to']) ? $_POST['p_stay_term_to'] : ''); ?>">
<input type="hidden" id="p_syohaku_hotel_code" name="p_syohaku_hotel_code" value="<?php e(isset($_POST['p_syohaku_hotel_code']) ? $_POST['p_syohaku_hotel_code'] : ''); ?>">
<!--
<input type="hidden" id="p_hatsu" name="p_hatsu" value="<?php e(isset($_POST['p_hatsu']) ? $_POST['p_hatsu'] : ''); ?>">
 -->
<input type="hidden" id="p_course_no" name="p_course_no" value="<?php e(isset($_POST['p_course_no']) ? $_POST['p_course_no'] : ''); ?>">
<input type="hidden" id="p_hotel_name_free" name="p_hotel_name_free" value="<?php e(isset($_POST['p_hotel_name_free']) ? $_POST['p_hotel_name_free'] : ''); ?>">
<input type="hidden" id="p_early_discount_flag" name="p_early_discount_flag" value="<?php e(isset($_POST['p_early_discount_flag']) ? $_POST['p_early_discount_flag'] : ''); ?>">
<input type="hidden" id="from_dir" name="from_dir" value="">
<input type="hidden" id="from_key_dir" name="from_key_dir" value="">

<input type="hidden" id="p_free_word" name="p_free_word" value="<?php e(isset($_POST['p_free_word']) ? $_POST['p_free_word'] : ''); ?>">
<?php // トップ検索画面から再検索画面に出発地と目的地の名称（ヒット件数込み）を渡す ?>
<input type="hidden" id="p_hatsu_detail" name="p_hatsu_detail" value="<?php e(isset($_POST['p_hatsu_detail']) ? $_POST['p_hatsu_detail'] : ''); ?>">
<input type="hidden" id="p_hatsu_detail_param" name="p_hatsu_detail_param" value="<?php e(isset($_POST['p_hatsu_detail_param']) ? $_POST['p_hatsu_detail_param'] : ''); ?>">
<input type="hidden" id="p_mokuteki_detail" name="p_mokuteki_detail" value="<?php e(isset($_POST['p_mokuteki_detail']) ? $_POST['p_mokuteki_detail'] : ''); ?>">
<?php // トップ検索画面から再検索画面に出発空港と到着空港の名称（ヒット件数込み）を渡す ?>
<input type="hidden" id="p_dep_airport_detail" name="p_dep_airport_detail" value="<?php e(isset($_POST['p_dep_airport_detail']) ? $_POST['p_dep_airport_detail'] : ''); ?>">
<input type="hidden" id="p_arr_airport_detail" name="p_arr_airport_detail" value="<?php e(isset($_POST['p_arr_airport_detail']) ? $_POST['p_arr_airport_detail'] : ''); ?>">
<?php // 旅行代金はデフォルトで設定されているので、下部フッターの表示の際に操作されていたら表示するようにする ?>
<input type="hidden" id="p_price_min_default" value="">
<input type="hidden" id="p_price_max_default" value="">

<?php // 出発地、目的地の追加するかどうかのフラグ ?>
<input type="hidden" id="p_hatsu_add_flag" name="p_hatsu_add_flag" value="">
<input type="hidden" id="p_mokuteki_add_flag" name="p_mokuteki_add_flag" value="">

<?php // 再検索で閉じるボタンを押した際に、初回表示をもってくるのでそのためのバックアップ ?>
<input type="hidden" id="close_btn_flag" name="close_btn_flag" value="">

</form>
