<?php
/*
Plugin Name: Show Comment Policy
Plugin URI: http://www.jimmyscode.com/wordpress/show-comment-policy/
Description: Display your comment policy above the comments form on posts or pages.
Version: 0.0.1
Author: Jimmy Pe&ntilde;a
Author URI: http://www.jimmyscode.com/
License: GPLv2 or later
*/

	define('SCP_PLUGIN_NAME', 'Show Comment Policy');
	// plugin constants
	define('SCP_VERSION', '0.0.1');
	define('SCP_SLUG', 'show-comment-policy');
	define('SCP_LOCAL', 'scp');
	define('SCP_OPTION', 'scp');
	define('SCP_OPTIONS_NAME', 'scp_options');
	define('SCP_PERMISSIONS_LEVEL', 'manage_options');
	define('SCP_PATH', plugin_basename(dirname(__FILE__)));
	/* default values */
	define('SCP_DEFAULT_ENABLED', true);
	define('SCP_DEFAULT_TEXT', '');
	define('SCP_DEFAULT_DISPLAY_ON_POSTS', true);
	define('SCP_DEFAULT_DISPLAY_ON_PAGES', true);
	/* option array member names */
	define('SCP_DEFAULT_ENABLED_NAME', 'enabled');
	define('SCP_DEFAULT_TEXT_NAME', 'texttoshow');
	define('SCP_DEFAULT_DISPLAY_ON_POSTS_NAME', 'displayonposts');
	define('SCP_DEFAULT_DISPLAY_ON_PAGES_NAME', 'displayonpages');
	
	// oh no you don't
	if (!defined('ABSPATH')) {
		wp_die(__('Do not access this file directly.', SCP_LOCAL));
	}

	// delete option when plugin is uninstalled
	register_uninstall_hook(__FILE__, 'uninstall_scp_plugin');
	function uninstall_scp_plugin() {
		delete_option(SCP_OPTION);
	}
	// localization to allow for translations
	add_action('init', 'scp_translation_file');
	function scp_translation_file() {
		$plugin_path = plugin_basename(dirname(__FILE__) . '/translations');
		load_plugin_textdomain(SCP_LOCAL, '', $plugin_path);
	}
	// tell WP that we are going to use new options
	// also, register the admin CSS file for later inclusion
	add_action('admin_init', 'scp_options_init');
	function scp_options_init() {
		register_setting(SCP_OPTIONS_NAME, SCP_OPTION, 'scp_validation');
		register_scp_admin_style();
	}
	// validation function
	function scp_validation($input) {
		// sanitize textarea
		$input[SCP_DEFAULT_TEXT_NAME] = wp_kses(force_balance_tags($input[SCP_DEFAULT_TEXT_NAME]));
		return $input;
	} 

	// add Settings sub-menu
	add_action('admin_menu', 'scp_plugin_menu');
	function scp_plugin_menu() {
		add_options_page(SCP_PLUGIN_NAME, SCP_PLUGIN_NAME, SCP_PERMISSIONS_LEVEL, SCP_SLUG, 'scp_page');
	}
	// plugin settings page
	// http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/
	function SCP_page() {
		// check perms
		if (!current_user_can(SCP_PERMISSIONS_LEVEL)) {
			wp_die(__('You do not have sufficient permission to access this page', SCP_LOCAL));
		}
		?>
		<div class="wrap">
			<h2 id="plugintitle"><img src="<?php echo plugins_url(plugin_basename(dirname(__FILE__) . '/images/policy.png')) ?>" title="" alt="" height="64" width="64" align="absmiddle" /> <?php echo SCP_PLUGIN_NAME; _e(' by ', SCP_LOCAL); ?><a href="http://www.jimmyscode.com/">Jimmy Pe&ntilde;a</a></h2>
			<div><?php _e('You are running plugin version', SCP_LOCAL); ?> <strong><?php echo SCP_VERSION; ?></strong>.</div>
			<form method="post" action="options.php">
			<?php settings_fields(SCP_OPTIONS_NAME); ?>
			<?php $options = scp_getpluginoptions(); ?>
			<?php update_option(SCP_OPTION, $options); ?>
			<h3 id="settings"><img src="<?php echo plugins_url(plugin_basename(dirname(__FILE__) . '/images/settings.png')) ?>" title="" alt="" height="61" width="64" align="absmiddle" /> <?php _e('Plugin Settings', SCP_LOCAL); ?></h3>
				<?php submit_button(); ?>

				<table class="form-table" id="theme-options-wrap">
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Is plugin enabled? Uncheck this to turn it off temporarily.', SCP_LOCAL); ?>" for="<?php echo SCP_OPTION; ?>[<?php echo SCP_DEFAULT_ENABLED_NAME; ?>]"><?php _e('Plugin enabled?', SCP_LOCAL); ?></label></strong></th>
						<td><input type="checkbox" id="<?php echo SCP_OPTION; ?>[<?php echo SCP_DEFAULT_ENABLED_NAME; ?>]" name="<?php echo SCP_OPTION; ?>[<?php echo SCP_DEFAULT_ENABLED_NAME; ?>]" value="1" <?php checked('1', $options[SCP_DEFAULT_ENABLED_NAME]); ?> /></td>
					</tr>
					<tr valign="top"><td colspan="2"><?php _e('Is plugin enabled? Uncheck this to turn it off temporarily.', SCP_LOCAL); ?></td></tr>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Choose where to display the comment policy', SCP_LOCAL); ?>"><?php _e('Choose where to display the comment policy', SCP_LOCAL); ?></label></strong></th>
						<td><input type="checkbox" id="<?php echo SCP_OPTION; ?>[<?php echo SCP_DEFAULT_DISPLAY_ON_POSTS_NAME; ?>]" name="<?php echo SCP_OPTION; ?>[<?php echo SCP_DEFAULT_DISPLAY_ON_POSTS_NAME; ?>]" value="1" <?php checked('1', $options[SCP_DEFAULT_DISPLAY_ON_POSTS_NAME]); ?> /> Posts
						<input type="checkbox" id="<?php echo SCP_OPTION; ?>[<?php echo SCP_DEFAULT_DISPLAY_ON_PAGES_NAME; ?>]" name="<?php echo SCP_OPTION; ?>[<?php echo SCP_DEFAULT_DISPLAY_ON_PAGES_NAME; ?>]" value="1" <?php checked('1', $options[SCP_DEFAULT_DISPLAY_ON_PAGES_NAME]); ?> /> Pages</td>
					</tr>
					<tr valign="top"><td colspan="2"><?php _e('Where to display comment policy? On Posts, Pages, or both?', SCP_LOCAL); ?></td></tr>					
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Enter comment policy text', SCP_LOCAL); ?>" for="<?php echo SCP_OPTION; ?>[<?php echo SCP_DEFAULT_TEXT_NAME; ?>]"><?php _e('Enter comment policy text', SCP_LOCAL); ?></label></strong></th>
						<td><textarea rows="12" cols="75" id="<?php echo SCP_OPTION; ?>[<?php echo SCP_DEFAULT_TEXT_NAME; ?>]" name="<?php echo SCP_OPTION; ?>[<?php echo SCP_DEFAULT_TEXT_NAME; ?>]"><?php echo $options[SCP_DEFAULT_TEXT_NAME]; ?></textarea></td>
					</tr>
					<tr valign="top"><td colspan="2"><?php _e('Type the comment policy text you want to display above the comments form on your site. HTML allowed.', SCP_LOCAL); ?></td></tr>
					</table>
				<?php submit_button(); ?>
			</form>
			<hr />
			<h3 id="support"><img src="<?php echo plugins_url(plugin_basename(dirname(__FILE__) . '/images/support.png')) ?>" title="" alt="" height="64" width="64" align="absmiddle" /> Support</h3>
				<div class="support">
				<?php echo '<a href="http://wordpress.org/extend/plugins/' . SCP_SLUG . '/">' . __('Documentation', SCP_LOCAL) . '</a> | ';
					echo '<a href="http://wordpress.org/plugins/' . SCP_SLUG . '/faq/">' . __('FAQ', SCP_LOCAL) . '</a><br />';
					_e('If you like this plugin, please ', SCP_LOCAL);
					echo '<a href="http://wordpress.org/support/view/plugin-reviews/' . SCP_SLUG . '/">';
					_e('rate it on WordPress.org', SCP_LOCAL);
					echo '</a> ';
					_e('and click the ', SCP_LOCAL);
					echo '<a href="http://wordpress.org/plugins/' . SCP_SLUG .  '/#compatibility">';
					_e('Works', SCP_LOCAL);
					echo '</a> ';
					_e('button. For support please visit the ', SCP_LOCAL);
					echo '<a href="http://wordpress.org/support/plugin/' . SCP_SLUG . '">';
					_e('forums', SCP_LOCAL);
					echo '</a>.';
				?>
				<br /><br />
				<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7EX9NB9TLFHVW"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" alt="Donate with PayPal" title="Donate with PayPal" width="92" height="26" /></a>
				</div>
		</div>
		<?php }

	// main function and action
  add_action('comment_form_before','scp_commentpolicylink');
  function scp_commentpolicylink() {
		$options = scp_getpluginoptions();
		$enabled = $options[SCP_DEFAULT_ENABLED_NAME];
		if ($enabled) {
			$want_on_posts = $options[SCP_DEFAULT_DISPLAY_ON_POSTS_NAME];
			$want_on_pages = $options[SCP_DEFAULT_DISPLAY_ON_PAGES_NAME];
			$text = $options[SCP_DEFAULT_TEXT_NAME];
		
			// check if we want it on posts
			if ($want_on_posts) {
				if (is_single()) {
					if (comments_open()) {
						echo '<div class="scp-comment-policy">' . $text . '</div>';
					}
				}
			} // end posts check
			if ($want_on_pages) {
				if (is_page()) {
					if (comments_open()) {
						echo '<div class="scp-comment-policy">' . $text . '</div>';
					}
				}
			} // end pages check
		} // end enabled
	}
		
	// show admin messages to plugin user
	add_action('admin_notices', 'scp_showAdminMessages');
	function scp_showAdminMessages() {
		// http://wptheming.com/2011/08/admin-notices-in-wordpress/
		global $pagenow;
		if (current_user_can(SCP_PERMISSIONS_LEVEL)) { // user has privilege
			if ($pagenow == 'options-general.php') { // we are on Settings menu
				if ($_GET['page'] == SCP_SLUG) { // we are on this plugin's settings page
					$options = scp_getpluginoptions();
					if ($options != false) {
						$enabled = $options[SCP_DEFAULT_ENABLED_NAME];
						if (!$enabled) {
							echo '<div id="message" class="error">' . SCP_PLUGIN_NAME . ' ' . __('is currently disabled.', SCP_LOCAL) . '</div>';
						}
					}
				}
			} // end page check
		} // end privilege check
	} // end admin msgs function
	// enqueue admin CSS if we are on the plugin options page
	add_action('admin_head', 'insert_scp_admin_css');
	function insert_scp_admin_css() {
		global $pagenow;
		if (current_user_can(SCP_PERMISSIONS_LEVEL)) { // user has privilege
			if ($pagenow == 'options-general.php') { // we are on Settings menu
				if ($_GET['page'] == SCP_SLUG) { // we are on this plugin's settings page
					scp_admin_styles();
				}
			}
		}
	}
	// add settings link on plugin page
	// http://bavotasan.com/2009/a-settings-link-for-your-wordpress-plugins/
	add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'scp_plugin_settings_link');
	function scp_plugin_settings_link($links) {
		$settings_link = '<a href="options-general.php?page=' . SCP_SLUG . '">' . __('Settings', SCP_LOCAL) . '</a>';
		array_unshift($links, $settings_link);
		return $links;
	}
	// http://wpengineer.com/1295/meta-links-for-wordpress-plugins/
	add_filter('plugin_row_meta', 'scp_meta_links', 10, 2);
	function scp_meta_links($links, $file) {
		$plugin = plugin_basename(__FILE__);
		// create link
		if ($file == $plugin) {
			$links = array_merge($links,
				array(
					'<a href="http://wordpress.org/support/plugin/' . SCP_SLUG . '">' . __('Support', SCP_LOCAL) . '</a>',
					'<a href="http://wordpress.org/extend/plugins/' . SCP_SLUG . '/">' . __('Documentation', SCP_LOCAL) . '</a>',
					'<a href="http://wordpress.org/plugins/' . SCP_SLUG . '/faq/">' . __('FAQ', SCP_LOCAL) . '</a>'
			));
		}
		return $links;
	}
	// enqueue/register the admin CSS file
	function scp_admin_styles() {
		wp_enqueue_style('scp_admin_style');
	}
	function register_scp_admin_style() {
		wp_register_style('scp_admin_style',
			plugins_url(SCP_PATH . '/css/admin.css'),
			array(),
			SCP_VERSION . "_" . date('njYHis', filemtime(dirname(__FILE__) . '/css/admin.css')),
			'all');
	}
	// when plugin is activated, create options array and populate with defaults
	register_activation_hook(__FILE__, 'scp_activate');
	function scp_activate() {
		$options = scp_getpluginoptions();
		update_option(SCP_OPTION, $options);
	}
	// generic function that returns plugin options from DB
	// if option does not exist, returns plugin defaults
	function scp_getpluginoptions() {
		return get_option(SCP_OPTION, 
			array(
				SCP_DEFAULT_ENABLED_NAME => SCP_DEFAULT_ENABLED, 
				SCP_DEFAULT_TEXT_NAME => SCP_DEFAULT_TEXT, 
				SCP_DEFAULT_DISPLAY_ON_POSTS_NAME => SCP_DEFAULT_DISPLAY_ON_POSTS, 
				SCP_DEFAULT_DISPLAY_ON_PAGES_NAME => SCP_DEFAULT_DISPLAY_ON_PAGES, 
			));
	}
?>