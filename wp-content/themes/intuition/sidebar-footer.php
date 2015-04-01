<?php if(is_active_sidebar('first-footer-widget-area')): ?>
	<div class="column col3 footer_column">
		<?php dynamic_sidebar('first-footer-widget-area'); ?>
	</div>
<?php endif; ?>

<?php if(is_active_sidebar('second-footer-widget-area')): ?>
	<div class="column col3 widget_second">
		<?php dynamic_sidebar('second-footer-widget-area'); ?>
	</div>
<?php endif; ?>

<?php if(is_active_sidebar('third-footer-widget-area')): ?>
	<div class="column col3 col-last widget_last">
		<?php dynamic_sidebar('third-footer-widget-area'); ?>
	</div>
<?php endif; ?>