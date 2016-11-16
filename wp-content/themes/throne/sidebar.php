<?php global $thr_sidebar_opts; ?>
<aside id="sidebar" class="sidebar <?php echo $thr_sidebar_opts['use_sidebar']; ?>">
	<?php
		if(is_active_sidebar($thr_sidebar_opts['sidebar'])){
				dynamic_sidebar($thr_sidebar_opts['sidebar']);
		}

		if(is_active_sidebar($thr_sidebar_opts['sticky_sidebar'])){
			echo '<div class="thr_sticky">';
				dynamic_sidebar($thr_sidebar_opts['sticky_sidebar']);
			echo '</div>';
		}
	?>
</aside>