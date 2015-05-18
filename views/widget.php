<!-- This file is used to markup the public-facing widget. -->
<?php 

global $ultimatemember;
if (function_exists('um_fetch_user')) { 

	if ( $instance['um_widget_title'] != '' ) {
		echo '<h3 class="widget-title">' . $instance['um_widget_title'] . '</h3>';
	}
	
	um_fetch_user($um_member);
	$corner = um_get_option('profile_photocorner');
	
	if ( $um_widget_layout === 'profile_photo' ) { ?>
		
<!-- 		<div class="um-member"> -->
			<div class="um-featured-profile-only um-member-photo radius-<?php echo $corner; ?>"><a href="<?php echo um_user_profile_url(); ?>" title="<?php echo um_user('display_name'); ?>"><?php echo get_avatar( um_user('ID'), 512 ); ?></a></div>
			<div class="um-member-card">
				<div class="um-member-name"><a href="<?php echo um_user_profile_url(); ?>" title="<?php echo um_user('display_name'); ?>"><?php echo um_user('display_name'); ?></a></div>
				
				<?php do_action('um_members_after_user_name', um_user('ID'), $args); ?>
				
			</div>
<!-- 		</div> -->
	<?php	
	} elseif ( $um_widget_layout === 'cover_photo' ) { ?>
		
		<div class="um-member <?php echo um_user('account_status'); ?> with-cover">		
			<div class="um-member-cover" data-ratio="<?php echo um_get_option('profile_cover_ratio'); ?>">
				<div class="um-member-cover-e"><?php echo um_user('cover_photo', 300); ?></div>
			</div>
			
			<div class="um-member-photo radius-<?php echo $corner; ?>"><a href="<?php echo um_user_profile_url(); ?>" title="<?php echo um_user('display_name'); ?>"><?php echo get_avatar( um_user('ID'), 256 ); ?></a></div>
			<div class="um-member-card">
				<div class="um-member-name"><a href="<?php echo um_user_profile_url(); ?>" title="<?php echo um_user('display_name'); ?>"><?php echo um_user('display_name'); ?></a></div>
				
				<?php do_action('um_members_after_user_name', um_user('ID'), $args); ?>
				
			</div>
		</div>
		
		<?php 
		um_reset_user_clean();
	}
    
} 
?>
