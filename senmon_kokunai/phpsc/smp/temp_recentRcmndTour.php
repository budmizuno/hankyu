<div id="ppz_kokunai_free">

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


<div id="ppz_recommend_spkokunaisenmon02"></div>
<script type="text/javascript">
    var ppz_recommend_myHatsu = "<?php e($recoHatsu);?>";
    var ppz_recommend_myHatsuSub = "";
    var ppz_spkokunaisenmon02 = new _PPZ();
    ppz_spkokunaisenmon02.cid = 21203;
    ppz_spkokunaisenmon02.rid = 14;
    ppz_spkokunaisenmon02.customer_id = '<?php e($customerId); ?>'; //顧客IDを代入

	ppz_spkokunaisenmon02.v02 = '0';//内外区分：国内
	ppz_spkokunaisenmon02.v04 = '<?php e($masterCsv[KEY_MASTER_CSV_DEST]); ?>';// 目的地（方面）フラグを確認
	ppz_spkokunaisenmon02.v08 = "<?php e($recoHatsuComma);?>"; // ↑↑myHatsuに記載している出発地フラグをカンマ区切りにて記載して下さい。

    ppz_spkokunaisenmon02.rows = 20;                               //表示したいMAX件数を設定(最大20件)
    ppz_spkokunaisenmon02.cb = 'ppz_spkokunaisenmon02_recommend';               //ここの関数でレコメンド表示を作成してください。(ppz_draw.js)
    ppz_spkokunaisenmon02.div_id = 'ppz_recommend_spkokunaisenmon02';      //レコメンド表示を行うタグのIDを指定(divのIDと一致させます)
    ppz_spkokunaisenmon02.alt_html = '';
    ppz_spkokunaisenmon02.request();

</script>
</div>
