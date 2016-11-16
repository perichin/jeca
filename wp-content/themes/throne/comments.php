<?php

// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', THEME_SLUG); ?></p>
	<?php
		return;
	}
?>

<div id="post-comments-<?php the_ID(); ?>" class="comments_main">

<?php if ( have_comments() ) : ?>
     <div class="comments_holder">
        <h3 class="comment_title underlined_heading"><span><?php comments_number(__thr('no_comments'), __thr('one_comment'), __thr('comments_number')); ?></span><a href="#respond" class="button_respond"><i class="icon-bubbles"></i><?php echo __thr('leave_a_comment'); ?></a>  </h3> 
        
        <div class="clear"></div>     
                       
        <ul class="comment-list">
            <?php $args = array(
                'avatar_size' => 64,
                'reply_text' => __thr('reply_comment'),
                'format' => 'html5'
            );?>
            <?php wp_list_comments($args); ?>
        </ul><!--END comment-list-->
    		
    		<div class="navigation">
  			   <?php paginate_comments_links(); ?> 
 			</div>
    </div><!--END comments holder -->
<?php endif; ?>

<?php if(comments_open()) : ?>
    <div id="comments" class="comment_post">
    <div class="comment-form-wrapper">
    <?php 

    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );

    $comment_form_args = array(
        'comment_notes_after' => '',
        'cancel_reply_link' => __thr( 'cancel_reply_link' ),
        'label_submit'      => __thr( 'comment_submit' ),
        'title_reply' => __thr( 'leave_a_reply' ),
        'must_log_in' => '<p class="must-log-in">' . sprintf( __thr('must_log_in'), wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ) ) . '</p>',
        'logged_in_as' => '<p class="logged-in-as">' . sprintf(__thr( 'logged_in_as' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>',
        'comment_notes_before' => '<p class="comment-notes">' . __thr( 'comment_notes_before' ) .'</p>',
        'comment_field' =>  '<p class="comment-form-comment"><label for="comment">' . __thr( 'comment_field' ) .'</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true">' .'</textarea></p>',
        'fields' => apply_filters( 'comment_form_default_fields', array(
            'author' =>
              '<p class="comment-form-author">' .
              '<label for="author">' . __thr( 'comment_name' ) . ( $req ? '<span class="required"> *</span>' : '' ) . '</label> ' .
              '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
              '" size="30"' . $aria_req . ' /></p>',

            'email' =>
              '<p class="comment-form-email"><label for="email">' . __thr( 'comment_email' ) . ( $req ? '<span class="required"> *</span>' : '' ).'</label> '  .
              '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
              '" size="30"' . $aria_req . ' /></p>',

            'url' =>
              '<p class="comment-form-url"><label for="url">' .
              __thr( 'comment_website' ) . '</label>' .
              '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
              '" size="30" /></p>'
            )
          ),
        );

    ?>
<?php comment_form($comment_form_args); ?>

</div> <!-- end of comment-form-wrapper -->
</div><!--END post form --> 
<?php endif; ?>

</div>