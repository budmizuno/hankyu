<?php if(!empty($this->kanrenLinkHtml)) :?>
<div class="idx_box03 relation mb30">
	<h3 class="idx_icn19">関連リンク</h3>
	<?php echo $this->kanrenLinkHtml ?>
</div>
<!-- //関連リンク -->
<?php endif;?>
<?php 

?>
<?php if(!empty($this->categoryLinkHtml)) :?>
<div class="idx_box03 category mb30">
	<h3 class="idx_icn21">カテゴリーリンク</h3>
	<ul>
		<?php echo $this->categoryLinkHtml ?>
	</ul>
</div>
<!-- //カテゴリーリンク -->
<?php endif?>