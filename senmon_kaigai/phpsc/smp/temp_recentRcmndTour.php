
<?php if($categoryType == CATEGORY_TYPE_DEST): ?>
<div id="ppz_kaigai_free">

<script language="JavaScript" src="/sharing/common16/js/ppz_sp.js" charset="UTF-8"></script>
<script language="JavaScript" src="<?=$PathSenmonLink;?>js/ppz_draw_sp.js" charset="UTF-8"></script>

<?php
$customerId= "";
if(!empty($_COOKIE['LCNU'])){
	$customerId = $_COOKIE['LCNU'];
    $customerId = base64_decode($customerId);
}
?>

<?php // include_once(dirname(__FILE__) . '/recomendSmp_footer.php'); ?>
<!-- <div style="margin-bottom: 20px;">
	<a href="<?php // e($path16->HttpsTop);?>/remind/">最近見たツアー
		<?php // new recomendSmp_footer; ?>
	</a>
</div> -->

<!-- レコメンド表示場所 -->
<div id="ppz_recommend_spkaigaisenmon02"></div>
<!-- レコメンド表示場所ここまで -->
<script type="text/javascript">
    var ppz_recommend_myHatsu = "<?php e($p_hatsu)?>";
    var ppz_recommend_myHatsuSub = "";
    var ppz_spkaigaisenmon02 = new _PPZ();
    ppz_spkaigaisenmon02.cid = 21203;
    ppz_spkaigaisenmon02.rid = 13;
    ppz_spkaigaisenmon02.customer_id = '<?php e($customerId); ?>'; //顧客IDを代入

	ppz_spkaigaisenmon02.v02 = '1';//内外区分：海外
	ppz_spkaigaisenmon02.v04 = '<?php e($masterCsv[KEY_MASTER_CSV_DEST]); ?>';//目的地（方面）フラグを確認
	ppz_spkaigaisenmon02.v08 = "<?php e($p_hatsu);?>"; // ↑↑myHatsuに記載している出発地フラグをカンマ区切りにて記載して下さい。

    ppz_spkaigaisenmon02.rows = 20;                               //表示したいMAX件数を設定(最大20件)
    ppz_spkaigaisenmon02.cb = 'ppz_spkaigaisenmon02_recommend';               //ここの関数でレコメンド表示を作成してください。(ppz_draw.js)
    ppz_spkaigaisenmon02.div_id = 'ppz_recommend_spkaigaisenmon02';      //レコメンド表示を行うタグのIDを指定(divのIDと一致させます)
    ppz_spkaigaisenmon02.alt_html = '';
    ppz_spkaigaisenmon02.request();
</script>
</div>
<?php endif;?>
