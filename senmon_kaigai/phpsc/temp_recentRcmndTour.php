<script language="JavaScript" src="/sharing/common16/js/ppz.js" charset="UTF-8"></script>
<script language="JavaScript" src="<?=$PathSenmonLink;?>js/ppz_draw.js" charset="UTF-8"></script>

<?php
$customerId= "";
if(!empty($_COOKIE['LCNU'])){
	$customerId = $_COOKIE['LCNU'];
    $customerId = base64_decode($customerId);
}
?>

<?php if(!empty($customerId) ) : //会員IDを持ってたらレコメンド?>
	<!-- レコメンド表示場所 -->
	<div id="ppz_recommend_pckaigaisenmon01"></div>
	<!-- レコメンド表示場所ここまで -->
	<script type="text/javascript">
	    var ppz_recommend_myHatsu = "<?php e($p_hatsu)?>";
	    var ppz_recommend_myHatsuSub = "";
	    var ppz_pckaigaisenmon01 = new _PPZ();
	    ppz_pckaigaisenmon01.cid = 21017;
	    ppz_pckaigaisenmon01.rid = 23;
	    ppz_pckaigaisenmon01.customer_id = '<?php e($customerId); ?>'; //顧客IDを代入

	    ppz_pckaigaisenmon01.rows = 10;                               //表示したいMAX件数を設定(最大20件)
	    ppz_pckaigaisenmon01.cb = 'ppz_pckaigaisenmon01_remind';               //ここの関数でレコメンド表示を作成してください。(ppz_draw.js)
	    ppz_pckaigaisenmon01.div_id = 'ppz_recommend_pckaigaisenmon01';      //レコメンド表示を行うタグのIDを指定(divのIDと一致させます)
	    ppz_pckaigaisenmon01.alt_html = '';
	    ppz_pckaigaisenmon01.request();
	</script>
</script>
<?php else : //会員じゃなかったら閲覧履歴?>
	<?php new History('',15);//最近見たツアー（閲覧履歴）?>
<?php endif;?>


<!-- レコメンド表示場所 -->
<div id="ppz_recommend_pckaigaisenmon02"></div>
<!-- レコメンド表示場所ここまで -->
<script type="text/javascript">
    var ppz_recommend_myHatsu = "<?php e($p_hatsu)?>";
    var ppz_recommend_myHatsuSub = "";
    var ppz_pckaigaisenmon02 = new _PPZ();
    ppz_pckaigaisenmon02.cid = 21017;
    ppz_pckaigaisenmon02.rid = 25;
    ppz_pckaigaisenmon02.customer_id = '<?php e($customerId); ?>'; //顧客IDを代入

    ppz_pckaigaisenmon02.v02 = '1';//内外区分：海外
    ppz_pckaigaisenmon02.v04 = '<?php e($masterCsv[KEY_MASTER_CSV_DEST]); ?>';//目的地フラグを確認
	ppz_pckaigaisenmon02.v08 = "<?php e($p_hatsu);?>"; // ↑↑myHatsuに記載している出発地フラグをカンマ区切りにて記載して下さい。


    ppz_pckaigaisenmon02.rows = 20;                               //表示したいMAX件数を設定(最大20件)
    ppz_pckaigaisenmon02.cb = 'ppz_pckaigaisenmon02_recommend';               //ここの関数でレコメンド表示を作成してください。(ppz_draw.js)
    ppz_pckaigaisenmon02.div_id = 'ppz_recommend_pckaigaisenmon02';      //レコメンド表示を行うタグのIDを指定(divのIDと一致させます)
    ppz_pckaigaisenmon02.alt_html = null;
    ppz_pckaigaisenmon02.request();

</script>
