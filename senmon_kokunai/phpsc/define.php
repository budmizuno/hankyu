<?php
/*
#################################################################
	専門店共通で持つdefine設定ファイル 専門店
#################################################################
*/

/*---------------------------------
 各開発環境、検証環境、本番環境
---------------------------------*/
// 本番環境
define('PRODUCTUIN',1);
// 検証環境
define('TEST',2);
// バド様開発環境
define('BUD',3);
// りーふねっと様開発環境
define('LEAFNET',4);

/*---------------------------------
 ページの方面、国、都市タイプ
---------------------------------*/
define('CATEGORY_TYPE_DEST',1);
define('CATEGORY_TYPE_COUNTRY',2);
define('CATEGORY_TYPE_CITY',3);

/*---------------------------------
 CSVのパス
---------------------------------*/
// 量産化用CSV
define('MASTER_CSV_URL',$_SERVER['DOCUMENT_ROOT'].'/attending/senmon_kokunai/setting/master_senmon_kokunai_2017.csv');
define('MASTER_GUIDE_CSV_URL',$_SERVER['DOCUMENT_ROOT'].'/attending/senmon_kokunai/setting/master_senmon_kokunaii_guide_2017.csv');

/*---------------------------------
 CSVの項目名
---------------------------------*/
// CSVでよく使用される項目名
define('KEY_Q_CATEGORY','q_category');          // 表示ページ
define('KEY_Q_GROUP','q_group');                // グループ
define('KEY_TOUR_URL','tour_url');              // URL
define('KEY_Q_COURSE_NAME','p_course_name');    // コース名
define('KEY_Q_IMG_PATH','p_img1_filepath');     // 画像パス
define('KEY_Q_IMG_CAPTION','p_img1_caption');   // 画像キャプション
define('KEY_Q_POINT','p_point1');               // キャプション
define('KEY_Q_FLAG','q_flag');                  // 表示フラグ
define('KEY_Q_THEME','q_theme');                // 特集枠（見出し）
define('KEY_Q_PRICE','p_price');                // 金額
define('KEY_Q_BRIGHTCOVE_ID','p_brightcove_id');// ブライトコープID
define('KEY_Q_THETA_ID','p_theta_id');          // シータID
define('KEY_Q_THETA_URL','p_theta_url');        // シータURL




// 量産化用CSVの項目
define('KEY_MASTER_CSV_NAME_JA','senmon_name_ja');                  // 専門店名(日本語)
define('KEY_MASTER_CSV_NAME_EN','senmon_name_en');                  // 専門店名(英語)
define('KEY_MASTER_CSV_TAG_INFO','tag_country_common_info');        // 国の共通情報
define('KEY_MASTER_CSV_PAGE_CAPTION','page_caption');               //
define('KEY_MASTER_CSV_PAGE_CAPTION_SP','page_caption_sp');         //
define('KEY_MASTER_CSV_HOMEN','homen');                             //
define('KEY_MASTER_CSV_COUNTRY_LOWER','country');                   //
define('KEY_MASTER_CSV_DEST','Dest');                               //
define('KEY_MASTER_CSV_COUNTRY_LARGE','Country');                   //
define('KEY_MASTER_CSV_CITY_LARGE','City');                         //
define('KEY_MASTER_CSV_URL','URL');                                 //
define('KEY_MASTER_CSV_MAP_DEFAULT','map_default_display');         //
define('KEY_MASTER_CSV_PAGE_CODE','page_code');                     //
define('KEY_MASTER_CSV_RIGHT_BOX_TYPE','right_box_type');           //
define('KEY_MASTER_CSV_PAGE_COLOR','page_color');                   //
define('KEY_MASTER_CSV_TAG_RELATED_LINKS','tag_related_links');     //
define('KEY_MASTER_CSV_DIRNAME','dirname');                         //
define('KEY_MASTER_CSV_TOUR','csv_recommend');                      // おすすめツアーフリープランCSV
define('KEY_MASTER_CSV_KYOTEN_FREE','csv_free_area');               // 拠点自由枠のCSV
define('KEY_MASTER_CSV_KYOTEN_TOKUSYU','csv_special');              // 拠点特集枠のCSV
define('KEY_MASTER_CSV_KEY_WORD','csv_keyword');                    // 人気のキーワードCSV
define('KEY_MASTER_CSV_PHOTO','csv_photo');                         // 写真CSV
define('KEY_MASTER_CSV_TOURIST_INFOMATION','csv_tourist_information'); // 観光情報CSV
define('KEY_MASTER_CSV_GENCHI_HACCHAKU_TOUR', 'csv_genchi_hacchaku');// 現地発着ツアー
define('KEY_MASTER_CSV_BUS_TOUR', 'csv_bus_tour');                  // バスツアー
define('KEY_MASTER_CSV_GENCHI_HACCHAKU_TOUR_KYOTEN', 'key_jiyuzin_tour_data_kyoten');        // 現地発着ツアー 拠点指定
define('KEY_MASTER_CSV_GENCHI_HACCHAKU_TOUR_CSV',    'key_jiyuzin_tour_data_csv_path');      // 現地発着ツアー CSVパス指定(例)/csv/
define('KEY_MASTER_CSV_GENCHI_HACCHAKU_TOUR_TILTE',  'key_jiyuzin_tour_data_article_title'); // 現地発着ツアー q_category
define('KEY_MASTER_CSV_BUS_TOUR_KYOTEN', 'key_bus_tour_data_kyoten');            // バスツアー 拠点指定
define('KEY_MASTER_CSV_BUS_TOUR_CSV',    'key_bus_tour_data_csv_path');          // バスツアー CSVパス指定(例)/csv/
define('KEY_MASTER_CSV_BUS_TOUR_TILTE',  'key_bus_tour_data_article_title');     // バスツアー q_category
define('KEY_MASTER_CSV_BLOG_NUM_PC','genchi_blog_disp_num_pc');     // ブログの個数(縦表示ブログだけ適応)PC
define('KEY_MASTER_CSV_BLOG_NUM_SP','genchi_blog_disp_num_sp');     // ブログの個数(縦表示ブログだけ適応)スマホ
define('KEY_MASTER_CSV_BLOG_URL','blogGenchi_url');                 // ブログURL
define('KEY_MASTER_CSV_FIRST_LEVEL','first_level');                 // ディレクトリ名2（国、都道府県）
define('KEY_MASTER_CSV_GUIDE_PATH','guide_path');                   // ガイド枠のパス
define('KEY_MASTER_CSV_NEW_TOUR','blog_new_rss_url');               // 新着ツアー


// おすすめツアーフリープランCSVのグループ名(q_group)
define('TOUR_ICHIOSHI_TOUR','ツアーイチオシツアー');
define('TOUR_URESUZI_RANKING','ツアー売れ筋ランキング');
define('TOUR_TANTOSHA_OSUSUME','ツアー担当者おすすめ');
define('TOUR_HOTEL_RANKING','ツアーホテルランキング');
define('TOUR_CRISTAL_HEART','ツアークリスタルハート');
define('TOUR_FRIEND_TOUR','ツアーフレンドツアー');
define('FREEPLAN_ICHIOSHI_TOUR','フリープランイチオシツアー');
define('FREEPLAN_TANTOSHA_OSUSUME','フリープラン担当者おすすめ');
define('FREEPLAN_HOTEL_RANKING','フリープランホテルランキング');

// おすすめツアーフリープランCSV(csv_europe_tour2017.csvなど)のツアータブ、フリープランタブのフラグのCSV項目名
define('TOUR_TAB_FLAG_NAME','ツアータブ表示1・非表示0');
//define('FREE_PLAN_TAB_FLAG_NAME','フリープランタブ表示1・非表示0');
define('OSUSUME_COURSE','recommend_course');        // 配列のkey名  コースを表す
define('OSUSUME_CATEGORY_NUM','category_num');      // 配列のkey名 それぞれのカテゴリの数を表す
define('OSUSUME_FLAG','flag');


// 拠点自由CSV
define('TOUR_STRING','ツアー');              // 拠点特集の配列のキー
define('FREE_PLAN_STRING','フリープラン');    // 拠点特集の配列のキー
define('YOMIMONO_STRING','読み物');          // 拠点特集の配列のキー
define('SYOHIN_STRING','商品');              // 拠点特集の配列のキー

define('KYOTEN_TOKUSYU_Q_GROUP_STRING_TOUR_YOMIMONO_1','ツアー読み物 写真付き1列');          // 拠点特集のq_group
define('KYOTEN_TOKUSYU_Q_GROUP_STRING_TOUR_YOMIMONO_2','ツアー読み物 写真付き3列');          // 拠点特集のq_group
define('KYOTEN_TOKUSYU_Q_GROUP_STRING_TOUR_SHOHIN_1','ツアー商品枠 写真付き3列');          // 拠点特集のq_group
define('KYOTEN_TOKUSYU_Q_GROUP_STRING_TOUR_SHOHIN_2','ツアー商品枠 写真付き4列');          // 拠点特集のq_group
define('KYOTEN_TOKUSYU_Q_GROUP_STRING_FREEPLAN_YOMIMONO_1','フリープラン読み物 写真付き1列');          // 拠点特集のq_group
define('KYOTEN_TOKUSYU_Q_GROUP_STRING_FREEPLAN_YOMIMONO_2','フリープラン読み物 写真付き3列');          // 拠点特集のq_group
define('KYOTEN_TOKUSYU_Q_GROUP_STRING_FREEPLAN_SHOHIN_1','フリープラン商品枠 写真付き3列');          // 拠点特集のq_group
define('KYOTEN_TOKUSYU_Q_GROUP_STRING_FREEPLAN_SHOHIN_2','フリープラン商品枠 写真付き4列');          // 拠点特集のq_group

define('KYOTEN_TOKUSYU_DISPLAY_TYPE_YOMIMONO_1','YOMIMONO_1');          // 拠点特集の表示タイプ
define('KYOTEN_TOKUSYU_DISPLAY_TYPE_YOMIMONO_2','YOMIMONO_2');          // 拠点特集の表示タイプ
define('KYOTEN_TOKUSYU_DISPLAY_TYPE_SHOHIN_1','SHOHIN_1');          // 拠点特集の表示タイプ
define('KYOTEN_TOKUSYU_DISPLAY_TYPE_SHOHIN_2','SHOHIN_2');          // 拠点特集の表示タイプ


// 人気キーワードCSVのタイプ
define('KEYWORD_TOUR_STRING','キーワードから探す');
define('KEYWORD_FREEPLAN_STRING','キーワードから探す');

define('GENCHI_STRING','現地発着');
define('BUS_STRING','バス');

/*---------------------------------
 文字制限の文字数 バイト数なので日本語なら設定値*1/2になる
---------------------------------*/
define('STRING_LIMIT_ICHIOSHI_COURSE_NAME',130);                 // イチオシツアーのコース名
define('STRING_LIMIT_ICHIOSHI_CAPTION',150);                     // イチオシツアーのキャプション
define('STRING_LIMIT_BLOG_VERTICAL',32);                         // ブログの縦配置
define('STRING_LIMIT_BLOG_HORIZONTAL',50);                       // ブログの横配置
define('STRING_LIMIT_BRAND_COURSE_NAME',50);                    // ブランド枠のコース名
define('STRING_LIMIT_BRAND_CAPTION',90);                        // ブランド枠のキャプション
define('STRING_LIMIT_NEW_TOUR_TITLE',140);                       // 新着ツアー枠のタイトル
define('STRING_LIMIT_NEW_TOUR_TITLE_FREEPLAN',60);               // 新着ツアー枠のタイトル (フリープランver)
