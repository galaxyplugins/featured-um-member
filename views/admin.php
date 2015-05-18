<!-- This file is used to markup the administration form of the widget. -->
<div class="wrapper">
    <div class="option">
      	<label for="um_widget_title">
        	<?php _e('Widget title', PLUGIN_LOCALE); ?>
		</label>
      	<input type="text" id="<?php echo $this->get_field_id('um_widget_title'); ?>" name="<?php echo $this->get_field_name('um_widget_title'); ?>" value="<?php echo $instance['um_widget_title']; ?>" class="text-input" />
    </div>
    
	<div class="option">
		<label for="um_member">
	        <?php _e('Featured UM Member', PLUGIN_LOCALE); ?>
	    </label>
		<select class="ui search selection dropdown" id="<?php echo $this->get_field_id('um_member'); ?>" name="<?php echo $this->get_field_name('um_member'); ?>">
		  	<?php 
				foreach ( $site_users as $site_user ) {
					echo "<option value='" . esc_html( $site_user->ID ) . "'";
					if ( esc_html( $site_user->ID ) === $instance['um_member'] ) {
						echo " selected='selected' ";
					}
					echo ">" . esc_html( $site_user->display_name ) . "</option>";
				}
			?>
		</select>
	</div>
    <div class="option">      
      	<p>
			<label for="<?php echo $this->get_field_id('um_widget_layout'); ?>">
				<input class="" id="<?php echo $this->get_field_id('um_widget_layout'); ?>" name="<?php echo $this->get_field_name('um_widget_layout'); ?>" type="radio" value="profile_photo" <?php if($instance['um_widget_layout'] === 'profile_photo'){ echo 'checked="checked"'; } ?> />
				<?php _e('Use only the profile image', PLUGIN_LOCALE); ?>
			</label>
			<label for="<?php echo $this->get_field_id('um_widget_layout'); ?>">
				<input class="" id="<?php echo $this->get_field_id('um_widget_layout'); ?>" name="<?php echo $this->get_field_name('um_widget_layout'); ?>" type="radio" value="cover_photo" <?php if($instance['um_widget_layout'] === 'cover_photo'){ echo 'checked="checked"'; } ?> />
				<?php _e('Use cover image & profile image', PLUGIN_LOCALE); ?>
			</label>
		</p>
    </div>

	<script>  
	(function ($) {
		"use strict";
		$('.dropdown.search').dropdown();
	}(jQuery));
	</script>
</div><!-- /wrapper -->