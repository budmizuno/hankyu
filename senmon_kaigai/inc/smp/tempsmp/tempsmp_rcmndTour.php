<?php
$customerId= "";
if($_COOKIE['LCNU']){
	if(!empty($_COOKIE['LCNU'])){
		$customerId = $_COOKIE['LCNU'];
		$customerId = base64_decode($customerId);
	}
}
?>
<script language="JavaScript" src="/sharing/common16/js/ppz_sp.js" charset="UTF-8"></script>
<script language="JavaScript" src="/attending/kaigai/js/ppz_draw_sp.js" charset="UTF-8"></script>
<div id="ppz_recommend_sptop01"></div>
<script type="text/javascript">
var ppz_recommend_myHatsu = "<?php e($p_hatsu_for_rcmnd)?>";
var ppz_sptop01 = new _PPZ();
ppz_sptop01.cid = 21203;
ppz_sptop01.rid = 6;
ppz_sptop01.customer_id = '<?php e($customerId); ?>'; //顧客IDを代入
ppz_sptop01.v02 = '1';//内外区分：海外
ppz_sptop01.rows = 20;
ppz_sptop01.cb = 'ppz_sptop01_personal'; //ここの関数でレコメンド表示を作成してください。(ppz_draw.js)
ppz_sptop01.div_id = 'ppz_recommend_sptop01'; //レコメンド表示を行うタグのIDを指定(divのIDと一致させます)
ppz_sptop01.alt_html = '';
ppz_sptop01.request();
</script>
