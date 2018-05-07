<!-- 現地情報ブログ -->

<?php

	// 方面ページなら
	//global $senmonNameEnLower;
	if($categoryType == CATEGORY_TYPE_DEST && $senmonNameEnLower != 'hokkaido' && $senmonNameEnLower != 'okinawa')
	{
		$blog = new blogDisp('GenchiSenmon',$naigai,6);//海外現地情報
	}
	// 国、都市ページなら
	else
	{
		// ブログの個数。
		$disp_blog_num_max;
		// 国ページなら
		if(($categoryType == CATEGORY_TYPE_DEST && ($senmonNameEnLower == 'hokkaido' || $senmonNameEnLower == 'okinawa')) || 
			$categoryType == CATEGORY_TYPE_COUNTRY){
			// ベスト7があれば
			if(!empty($masterCsv[KEY_MASTER_CSV_BEST_SEVEN_DISPLAY])){
				$disp_blog_num_max = 5;
			}
			else{
				$disp_blog_num_max = 3;
			}
		// 都市ページなら
		}else{
			$disp_blog_num_max = 3;
		}
		
		
		if (isset($masterCsv[KEY_MASTER_CSV_BLOG_NUM_PC]) && is_numeric($masterCsv[KEY_MASTER_CSV_BLOG_NUM_PC])) {
			$disp_blog_num_max = $masterCsv[KEY_MASTER_CSV_BLOG_NUM_PC];
		}
		
		$blog = new blogDisp('GenchiSenmon',$naigai,$disp_blog_num_max);//海外現地情報
	}

?>

<?php if(!empty($blog->Blog)):?>
	<?php // 方面ページなら ?>
	<?php if($categoryType == CATEGORY_TYPE_DEST && $senmonNameEnLower != 'hokkaido' && $senmonNameEnLower != 'okinawa'):?>
		<p class="font-18 txt-bold list-inline mb10"><i class="icon icon-kokunai-link mid mr10"></i><span class="font-18 mid">現地情報ブログ</span></p>
		<ul class="list-home-link  clearfix">
			<?php echo $blog->Blog;?>
		</ul>
		<p class="btn-more"><a href="<?php e($blog->LinkUrl)?>"><span>記事一覧を見る</span> <span class="icon icon-arr-left"></span></a></p>
	<?php // 国、都市ページなら?>
	<?php else:?>
		<div class="aside right">
	        <p class="bg-fef2d2 border-cccccc aside-index-title center">現地生情報ブログ</p>
	        <div class="border-cccccc">
	            <div class="aside-index">
					<?php echo $blog->Blog;?>
	            </div>
				<p class="btn_more_right"><a href="<?php e($blog->LinkUrl)?>"><span>記事一覧を見る</span> <span class="icon icon-arr-left"></span></a></p>
			</div>
	    </div>
	<?php endif; ?>
<?php else:?>
	<?php // 方面ページなら ?>
	<?php if($categoryType == CATEGORY_TYPE_DEST):?>
	<?php // 国、都市ページなら ?>
	<?php else:?>
		<div class="aside right nodata">
	    </div>
	<?php endif; ?>
<?php endif; ?>
<!-- 現地情報ブログ -->
