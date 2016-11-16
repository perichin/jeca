<form class="search_form" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
	<input name="s" class="search_input" size="20" type="text" value="<?php echo __thr('search_form'); ?>" onfocus="(this.value == '<?php echo __thr('search_form'); ?>') && (this.value = '')" onblur="(this.value == '') && (this.value = '<?php echo __thr('search_form'); ?>')" placeholder="<?php echo __thr('search_form'); ?>" />
	<i class="icon-magnifier"></i>
</form>