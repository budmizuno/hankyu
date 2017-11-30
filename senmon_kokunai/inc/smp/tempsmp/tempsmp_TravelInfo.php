<p class="sbttl">旅行の旅ガイド</p>
<?php
$c = 0;
$html="";
foreach ($this->html as $data){
	if($c >= $num){
		break;
	}
	$html .= $data;
	$c++;
}
echo $html;
?>
<p class="btmLink"><a href="<?php e($path16->HttpTop);?>/guide/">観光ガイドを見る</a></p>
<!-- 旅行の旅ガイド -->
