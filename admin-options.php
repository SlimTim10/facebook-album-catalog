<div class="wrap">
  <h1><?php _e('Facebook Album Catalog Settings'); ?></h1>
  <form method="post" action="options.php">
	<?php settings_fields('facebook-album-catalog'); ?>
	<?php do_settings_sections('facebook-album-catalog'); ?>
	<table class="form-table">
	  <tr>
		<th scope="row">
		  <label for="app_id"><?php _e('App ID') ?></label>
		</th>
		<td>
		  <input name="app_id" type="text" id="app_id" value="<?php esc_attr_e(get_option('app_id')); ?>" class="regular-text ltr"/>
		</td>
	  </tr>
	  <tr>
		<th scope="row">
		  <label for="app_secret"><?php _e('App Secret') ?></label>
		</th>
		<td>
		  <input name="app_secret" type="text" id="app_secret" value="<?php esc_attr_e(get_option('app_secret')); ?>" class="regular-text ltr"/>
		</td>
	  </tr>
	  <tr>
		<th scope="row">
		  <label for="page_id"><?php _e('Page ID') ?></label>
		</th>
		<td>
		  <input name="page_id" type="text" id="page_id" value="<?php esc_attr_e(get_option('page_id')); ?>" class="regular-text ltr"/>
		</td>
	  </tr>
	  <tr>
		<th scope="row">
		  <label for="items_per_page"><?php _e('Items Per Page') ?></label>
		</th>
		<td>
		  <input name="items_per_page" type="text" id="items_per_page" value="<?php esc_attr_e(get_option('items_per_page')); ?>" class="regular-text ltr"/>
		</td>
	  </tr>
	</table>
	<?php submit_button(); ?>
	<p>To get the app ID and app secret, go to your <a target="blank" href="https://developers.facebook.com/apps/">app in Facebook Developers</a>.</p>
  </form>
</div>
