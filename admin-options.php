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
		  <label for="access_token"><?php _e('Access Token') ?></label>
		</th>
		<td>
		  <input name="access_token" type="text" id="access_token" value="<?php esc_attr_e(get_option('access_token')); ?>" class="regular-text ltr"/>
		</td>
	  </tr>
	  <tr>
		<th scope="row">
		  <label for="album_name"><?php _e('Album Name') ?></label>
		</th>
		<td>
		  <input name="album_name" type="text" id="album_name" value="<?php esc_attr_e(get_option('album_name')); ?>" class="regular-text ltr"/>
		</td>
	  </tr>
	</table>
	<?php submit_button(); ?>
	<p>To get the app ID and app secret, go to your <a target="blank" href="https://developers.facebook.com/apps/">app in Facebook Developers</a>.</p>
	<p>To get your access token, go to the <a target="blank" href="https://developers.facebook.com/tools/explorer/">Graph API Explorer</a>.</p>
  </form>
</div>
