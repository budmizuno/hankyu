<!-- 現地情報ブログ -->

<?php

	// ブログの個数。デフォルトは5個
	$disp_blog_num_max = 5;
	if (isset($masterCsv[KEY_MASTER_CSV_BLOG_NUM_SP]) && is_numeric($masterCsv[KEY_MASTER_CSV_BLOG_NUM_SP])) {
		$disp_blog_num_max = $masterCsv[KEY_MASTER_CSV_BLOG_NUM_SP];
	}

	$blog = new blogDisp('GenchiSenmon',$naigai,$disp_blog_num_max);//国内現地情報

?>

<?php if(!empty($blog->Blog)):?>
	<p class="sbttl">現地情報ブログ</p>
	<ul class="list-home-link">
		<?php echo $blog->Blog;?>
	</ul>
	<p class="btmLink"><a href="<?php e($blog->LinkUrl)?>" target="_blank">記事一覧を見る</a></p>
<?php endif; ?>
<!-- 現地情報ブログ -->
