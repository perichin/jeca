<?php
/*-----------------------------------------------------------------------------------*/
/*	Register widgets
/*-----------------------------------------------------------------------------------*/ 

if(!function_exists('thr_register_widgets')) :
	function thr_register_widgets(){
			
			//Include widget classes
	 		require_once('widgets/posts.php');
	 		require_once('widgets/video.php');
	 		require_once('widgets/adsense.php');

			register_widget('THR_Posts_Widget');
			register_widget('THR_Video_Widget');
			register_widget('THR_Adsense_Widget');
	}
endif;

?>