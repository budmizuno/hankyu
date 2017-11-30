<script language="JavaScript" src="/sharing/common16/js/ppz.js" charset="UTF-8"></script>
<script language="JavaScript" src="<?=$PathSenmonLink;?>js/ppz_draw.js" charset="UTF-8"></script>

<?php
$customerId= "";
if(isset($_COOKIE['LCNU'])){
	if(!empty($_COOKIE['LCNU'])){
		$customerId = $_COOKIE['LCNU'];
        $customerId = base64_decode($customerId);
	}
}
?>

<?php if(!empty($customerId) ) : //会員IDを持ってたらレコメンド?>
	<!-- レコメンド表示場所 -->
	<div id="ppz_recommend_pckokunaisenmon01"></div>
	<!-- レコメンド表示場所ここまで -->
	<script type="text/javascript">
		var ppz_recommend_myHatsu = "<?php e($recoHatsu);?>";
		var ppz_recommend_myHatsuSub = "";
		var ppz_pckokunaisenmon01 = new _PPZ();
		ppz_pckokunaisenmon01.cid = 21017;
		ppz_pckokunaisenmon01.rid = 24;
		ppz_pckokunaisenmon01.customer_id = '<?php e($customerId); ?>'; //顧客IDを代入
		ppz_pckokunaisenmon01.rows = 10;                               //表示したいMAX件数を設定(最大20件)
		ppz_pckokunaisenmon01.cb = 'ppz_pckokunaisenmon01_remind';               //ここの関数でレコメンド表示を作成してください。(ppz_draw.js)
		ppz_pckokunaisenmon01.div_id = 'ppz_recommend_pckokunaisenmon01';      //レコメンド表示を行うタグのIDを指定(divのIDと一致させます)
		ppz_pckokunaisenmon01.alt_html = '';
		ppz_pckokunaisenmon01.request();
	</script>
<?php else : //会員じゃなかったら閲覧履歴?>
	<?php new History('',15);//最近見たツアー（閲覧履歴）?>
<?php endif;?>

<!-- レコメンド表示場所 -->
<div id="ppz_recommend_pckokunaisenmon02"></div>
<!-- レコメンド表示場所ここまで -->
<script type="text/javascript">
    var ppz_recommend_myHatsu = "<?php e($recoHatsu);?>";
    var ppz_recommend_myHatsuSub = "";
    var ppz_pckokunaisenmon02 = new _PPZ();
    ppz_pckokunaisenmon02.cid = 21017;
    ppz_pckokunaisenmon02.rid = 26;
    ppz_pckokunaisenmon02.customer_id = '<?php e($customerId); ?>'; //顧客IDを代入

    ppz_pckokunaisenmon02.v02 = '0';//内外区分：国内
    ppz_pckokunaisenmon02.v04 = '<?php e($masterCsv[KEY_MASTER_CSV_DEST]); ?>';// 目的地（方面）フラグを確認
	ppz_pckokunaisenmon02.v08 = "<?php e($recoHatsuComma);?>"; // ↑↑myHatsuに記載している出発地フラグをカンマ区切りにて記載して下さい。


    ppz_pckokunaisenmon02.rows = 20;                               //表示したいMAX件数を設定(最大20件)
    ppz_pckokunaisenmon02.cb = 'ppz_pckokunaisenmon02_recommend';               //ここの関数でレコメンド表示を作成してください。(ppz_draw.js)
    ppz_pckokunaisenmon02.div_id = 'ppz_recommend_pckokunaisenmon02';      //レコメンド表示を行うタグのIDを指定(divのIDと一致させます)
    ppz_pckokunaisenmon02.alt_html = '';
    ppz_pckokunaisenmon02.request();
</script>
