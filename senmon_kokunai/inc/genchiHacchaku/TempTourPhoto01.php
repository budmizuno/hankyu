<dl class="TourBox clear">
	<dt class="TourTtl">
		<a href="<?php e($GlobalTourData['tour_url']); ?>"><?php e($GlobalTourData['p_course_name']); ?></a>
	</dt>
	<dd class="Photo">
		<a href="<?php e($GlobalTourData['p_course_name']); ?>">
			<img src="<?php e($GlobalTourData['p_img1_filepath']); ?>" alt="<?php e($GlobalTourData['p_img1_caption']); ?>">
		</a>
	</dd>
	<dd class="Point"><?php e($GlobalTourData['p_point1']); ?></dd>
	<dd class="Point"><?php e($GlobalTourData['p_point2']); ?></dd>
	<dd class="Icon">
		<ul>
			<!-- <?php if(!empty($GlobalTourData['q_icon1'])): ?>
			<li><?php e($GlobalTourData['q_icon1']); ?></li>
			<?php else: ?>
			<?php endif; ?>
			<?php if(!empty($GlobalTourData['q_icon2'])): ?>
			<li><?php e($GlobalTourData['q_icon2']); ?></li>
			<?php else: ?>
			<?php endif; ?>
			<?php if(!empty($GlobalTourData['q_icon3'])): ?>
			<li><?php e($GlobalTourData['q_icon3']); ?></li>
			<?php else: ?>
			<?php endif; ?>
			<?php if(!empty($GlobalTourData['q_icon4'])): ?>
			<li><?php e($GlobalTourData['q_icon4']); ?></li>
			<?php else: ?>
			<?php endif; ?> -->
		</ul>
	</dd>
	<dd class="Price"><span>旅行代金：</span><?php e(isset($GlobalTourData['price_min_max']) ? $GlobalTourData['price_min_max'] : ''); ?></dd>
	<dd class="TourBtn">
		<a href="<?php e($GlobalTourData['tour_url']); ?>">
			<img src="/attending/kokunai/jiyuhjin/images/TourBtn.gif" alt="詳しくはこちら" class="fade">
		</a>
	</dd>
	<dd class="CnoKikan">
		<ul>
			<?php if(!empty($GlobalTourData['p_course_id'])): ?>
				<li><span>コース番号：</span><?php e($GlobalTourData['p_course_id']); ?></li>
			<?php else: ?>
				<li><span>コース番号：</span> - </li>
			<?php endif; ?>
		</ul>
	</dd>
</dl>
