<section class="blue newTourWrapper">
    <h2>新着海外ツアー</h2>
    <? $blog = new blogNew('kaigai',8);?>
    <section class="newTourBoxI">
     <?php if($blog->num):?>
        <p class="sbttl"></p>
        <ul class="clearfix">
            <?php echo $blog->html; ?>
        </ul>
        <?php if($blog->num >3):?>
        <p class="moreNewTourPls"><span>もっと見る</span></p>
        <?php endif;?>
      <?php else:?>
    <p class="noNewBlog">ただいま新着はございません</p>
    <?php endif;?>
    </section>
</section>