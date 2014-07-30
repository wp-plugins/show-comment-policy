<?php
/*
Plugin Name: Show Comment Policy
Plugin URI: http://www.jimmyscode.com/wordpress/show-comment-policy/
Description: Display your comment policy above the comments form on posts or pages.
Version: 0.1.0
Author: Jimmy Pe&ntilde;a
Author URI: http://www.jimmyscode.com/
License: GPLv2 or later
*/

if (!defined('SCP_PLUGIN_NAME')) {
	define('SCP_PLUGIN_NAME', 'Show Comment Policy');
	// plugin constants
	define('SCP_VERSION', '0.1.0');
	define('SCP_SLUG', 'show-comment-policy');
	define('SCP_LOCAL', 'scp');
	define('SCP_OPTION', 'scp');
	define('SCP_OPTIONS_NAME', 'scp_options');
	define('SCP_PERMISSIONS_LEVEL', 'manage_options');
	define('SCP_PATH', plugin_basename(dirname(__FILE__)));
	/* default values */
	define('SCP_DEFAULT_ENABLED', true);
	define('SCP_DEFAULT_TEXT', '');
	define('SCP_DEFAULT_DISPLAY_ON_POSTS', false);
	define('SCP_DEFAULT_DISPLAY_ON_PAGES', false);
	define('SCP_DEFAULT_NONLOGGEDIN', false);
	/* option array member names */
	define('SCP_DEFAULT_ENABLED_NAME', 'enabled');
	define('SCP_DEFAULT_TEXT_NAME', 'texttoshow');
	define('SCP_DEFAULT_DISPLAY_ON_POSTS_NAME', 'displayonposts');
	define('SCP_DEFAULT_DISPLAY_ON_PAGES_NAME', 'displayonpages');
	define('SCP_DEFAULT_NONLOGGEDIN_NAME', 'nonloggedinonly');
}
	// oh no you don't
	if (!defined('ABSPATH')) {
		wp_die(__('Do not access this file directly.', scp_get_local()));
	}

	// localization to allow for translations
	add_action('init', 'scp_translation_file');
	function scp_translation_file() {
		$plugin_path = scp_get_path() . '/translations';
		load_plugin_textdomain(scp_get_local(), '', $plugin_path);
	}
	// tell WP that we are going to use new options
	// also, register the admin CSS file for later inclusion
	add_action('admin_init', 'scp_options_init');
	function scp_options_init() {
		register_setting(SCP_OPTIONS_NAME, scp_get_option(), 'scp_validation');
		register_scp_admin_style();
	}
	// validation function
	function scp_validation($input) {
		// sanitize/validate all form fields
		if (!empty($input)) {
			$input[SCP_DEFAULT_ENABLED_NAME] = (bool)$input[SCP_DEFAULT_ENABLED_NAME];
			$input[SCP_DEFAULT_TEXT_NAME] = wp_kses_post(force_balance_tags($input[SCP_DEFAULT_TEXT_NAME]));
			$input[SCP_DEFAULT_DISPLAY_ON_POSTS_NAME] = (bool)$input[SCP_DEFAULT_DISPLAY_ON_POSTS_NAME];
			$input[SCP_DEFAULT_DISPLAY_ON_PAGES_NAME] = (bool)$input[SCP_DEFAULT_DISPLAY_ON_PAGES_NAME];
			$input[SCP_DEFAULT_NONLOGGEDIN_NAME] = (bool)$input[SCP_DEFAULT_NONLOGGEDIN_NAME];
		}
		return $input;
	} 
	// add Settings sub-menu
	add_action('admin_menu', 'scp_plugin_menu');
	function scp_plugin_menu() {
		add_options_page(SCP_PLUGIN_NAME, SCP_PLUGIN_NAME, SCP_PERMISSIONS_LEVEL, scp_get_slug(), 'scp_page');
	}
	// plugin settings page
	// http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/
	function SCP_page() {
		// check perms
		if (!current_user_can(SCP_PERMISSIONS_LEVEL)) {
			wp_die(__('You do not have sufficient permission to access this page', scp_get_local()));
		}
		?>
		<div class="wrap">
			<h2 id="plugintitle"><img src="<?php echo scp_getimagefilename('policy.png'); ?>" title="" alt="" height="64" width="64" align="absmiddle" /> <?php echo SCP_PLUGIN_NAME; _e(' by ', scp_get_local()); ?><a href="http://www.jimmyscode.com/">Jimmy Pe&ntilde;a</a></h2>
			<div><?php _e('You are running plugin version', scp_get_local()); ?> <strong><?php echo SCP_VERSION; ?></strong>.</div>

			<?php /* http://code.tutsplus.com/tutorials/the-complete-guide-to-the-wordpress-settings-api-part-5-tabbed-navigation-for-your-settings-page--wp-24971 */ ?>
			<?php $active_tab = (isset($_GET['tab']) ? $_GET['tab'] : 'settings'); ?>

			<h2 class="nav-tab-wrapper">
			  <a href="?page=<?php echo scp_get_slug(); ?>&tab=settings" class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>"><?php _e('Settings', scp_get_local()); ?></a>
				<a href="?page=<?php echo scp_get_slug(); ?>&tab=support" class="nav-tab <?php echo $active_tab == 'support' ? 'nav-tab-active' : ''; ?>"><?php _e('Support', scp_get_local()); ?></a>
			</h2>

			<form method="post" action="options.php">
			<?php settings_fields(SCP_OPTIONS_NAME); ?>
			<?php $options = scp_getpluginoptions(); ?>
			<?php update_option(scp_get_option(), $options); ?>
			<?php if ($active_tab == 'settings') { ?>
			<h3 id="settings"><img src="<?php echo scp_getimagefilename('settings.png'); ?>" title="" alt="" height="61" width="64" align="absmiddle" /> <?php _e('Plugin Settings', scp_get_local()); ?></h3>
				<table class="form-table" id="theme-options-wrap">
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Is plugin enabled? Uncheck this to turn it off temporarily.', scp_get_local()); ?>" for="<?php echo scp_get_option(); ?>[<?php echo SCP_DEFAULT_ENABLED_NAME; ?>]"><?php _e('Plugin enabled?', scp_get_local()); ?></label></strong></th>
						<td><input type="checkbox" id="<?php echo scp_get_option(); ?>[<?php echo SCP_DEFAULT_ENABLED_NAME; ?>]" name="<?php echo scp_get_option(); ?>[<?php echo SCP_DEFAULT_ENABLED_NAME; ?>]" value="1" <?php checked('1', scp_checkifset(SCP_DEFAULT_ENABLED_NAME, SCP_DEFAULT_ENABLED, $options)); ?> /></td>
					</tr>
					<?php scp_explanationrow(__('Is plugin enabled? Uncheck this to turn it off temporarily.', scp_get_local())); ?>
					<?php scp_getlinebreak(); ?>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Choose where to display the comment policy', scp_get_local()); ?>"><?php _e('Choose where to display the comment policy', scp_get_local()); ?></label></strong></th>
						<td><input type="checkbox" id="<?php echo scp_get_option(); ?>[<?php echo SCP_DEFAULT_DISPLAY_ON_POSTS_NAME; ?>]" name="<?php echo scp_get_option(); ?>[<?php echo SCP_DEFAULT_DISPLAY_ON_POSTS_NAME; ?>]" value="1" <?php checked('1', scp_checkifset(SCP_DEFAULT_DISPLAY_ON_POSTS_NAME, SCP_DEFAULT_DISPLAY_ON_POSTS, $options)); ?> /> Posts
						<input type="checkbox" id="<?php echo scp_get_option(); ?>[<?php echo SCP_DEFAULT_DISPLAY_ON_PAGES_NAME; ?>]" name="<?php echo scp_get_option(); ?>[<?php echo SCP_DEFAULT_DISPLAY_ON_PAGES_NAME; ?>]" value="1" <?php checked('1', scp_checkifset(SCP_DEFAULT_DISPLAY_ON_PAGES_NAME, SCP_DEFAULT_DISPLAY_ON_PAGES, $options)); ?> /> Pages</td>
					</tr>
					<?php scp_explanationrow(__('Where to display comment policy? On Posts, Pages, or both?', scp_get_local())); ?>
					<?php scp_getlinebreak(); ?>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Enter comment policy text', scp_get_local()); ?>" for="<?php echo scp_get_option(); ?>[<?php echo SCP_DEFAULT_TEXT_NAME; ?>]"><?php _e('Enter comment policy text', scp_get_local()); ?></label></strong></th>
						<td><textarea rows="12" cols="75" id="<?php echo scp_get_option(); ?>[<?php echo SCP_DEFAULT_TEXT_NAME; ?>]" name="<?php echo scp_get_option(); ?>[<?php echo SCP_DEFAULT_TEXT_NAME; ?>]"><?php echo scp_checkifset(SCP_DEFAULT_TEXT_NAME, SCP_DEFAULT_TEXT, $options); ?></textarea></td>
					</tr>
					<?php scp_explanationrow(__('Type the comment policy text you want to display above the comments form on your site. HTML allowed.', scp_get_local())); ?>
					<?php scp_getlinebreak(); ?>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Show to non-logged-in users only?', scp_get_local()); ?>" for="<?php echo scp_get_option(); ?>[<?php echo SCP_DEFAULT_NONLOGGEDIN_NAME; ?>]"><?php _e('Show to non-logged-in users only?', scp_get_local()); ?></label></strong></th>
						<td><input type="checkbox" id="<?php echo scp_get_option(); ?>[<?php echo SCP_DEFAULT_NONLOGGEDIN_NAME; ?>]" name="<?php echo scp_get_option(); ?>[<?php echo SCP_DEFAULT_NONLOGGEDIN_NAME; ?>]" value="1" <?php checked('1', scp_checkifset(SCP_DEFAULT_NONLOGGEDIN_NAME, SCP_DEFAULT_NONLOGGEDIN, $options)); ?> /></td>
					</tr>
					<?php scp_explanationrow(__('Check this box to display the comment policy to non-logged-in users only.', scp_get_local())); ?>
					</table>
				<?php submit_button(); ?>
			<?php } else { ?>
			<h3 id="support"><img src="<?php echo scp_getimagefilename('support.png'); ?>" title="" alt="" height="64" width="64" align="absmiddle" /> <?php _e('Support', scp_get_local()); ?></h3>
				<div class="support">
				<?php echo scp_getsupportinfo(scp_get_slug(), scp_get_local()); ?>
				</div>
			<?php } ?>
			</form>
		</div>
		<?php }

	// main function and action
  add_action('comment_form_before','scp_commentpolicylink');
  function scp_commentpolicylink() {
		$options = scp_getpluginoptions();
		if (!empty($options)) {
			$enabled = (bool)$options[SCP_DEFAULT_ENABLED_NAME];
			$nonloggedonly = $options[SCP_DEFAULT_NONLOGGEDIN_NAME];
		} else {
			$enabled = SCP_DEFAULT_ENABLED;
			$nonloggedonly = SCP_DEFAULT_NONLOGGEDIN;
		}

		$output = '';
		
		if ($enabled) {
			if (is_user_logged_in() && $nonloggedonly) {
				// user is logged on but we don't want to show it to logged in users
				$output = '<!-- ' . SCP_PLUGIN_NAME . ': ' . __('Set to show to non-logged-in users only, and current user is logged in.', scp_get_local()) . ' -->';
			} else {
				$want_on_posts = $options[SCP_DEFAULT_DISPLAY_ON_POSTS_NAME];
				$want_on_pages = $options[SCP_DEFAULT_DISPLAY_ON_PAGES_NAME];
				$text = $options[SCP_DEFAULT_TEXT_NAME];
			
				// check if we want it on posts
				if ($want_on_posts) {
					if (is_single()) {
						if (comments_open()) {
							$output = '<div class="scp-comment-policy">' . do_shortcode($text) . '</div>';
						}
					}
				} // end posts check
				if ($want_on_pages) {
					if (is_page()) {
						if (comments_open()) {
							$output = '<div class="scp-comment-policy">' . do_shortcode($text) . '</div>';
						}
					}
				} // end pages check
			}
			echo $output;
		} // end enabled
	}

	// show admin messages to plugin user
	add_action('admin_notices', 'scp_showAdminMessages');
	function scp_showAdminMessages() {
		// http://wptheming.com/2011/08/admin-notices-in-wordpress/
		global $pagenow;
		if (current_user_can(SCP_PERMISSIONS_LEVEL)) { // user has privilege
			if ($pagenow == 'options-general.php') { // we are on Settings menu
				if (isset($_GET['page'])) {
					if ($_GET['page'] == scp_get_slug()) { // we are on this plugin's settings page
						$options = scp_getpluginoptions();
						if (!empty($options)) {
							$enabled = (bool)$options[SCP_DEFAULT_ENABLED_NAME];
							if (!$enabled) {
								echo '<div id="message" class="error">' . SCP_PLUGIN_NAME . ' ' . __('is currently disabled.', scp_get_local()) . '</div>';
							}
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
				if (isset($_GET['page'])) {
					if ($_GET['page'] == scp_get_slug()) { // we are on this plugin's settings page
						scp_admin_styles();
					}
				}
			}
		}
	}
	// add helpful links to plugin page next to plugin name
	// http://bavotasan.com/2009/a-settings-link-for-your-wordpress-plugins/
	// http://wpengineer.com/1295/meta-links-for-wordpress-plugins/
	add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'scp_plugin_settings_link');
	add_filter('plugin_row_meta', 'scp_meta_links', 10, 2);
	
	function scp_plugin_settings_link($links) {
		return scp_settingslink($links, scp_get_slug(), scp_get_local());
	}
	function scp_meta_links($links, $file) {
		if ($file == plugin_basename(__FILE__)) {
			$links = array_merge($links,
			array(
				sprintf(__('<a href="http://wordpress.org/support/plugin/%s">Support</a>', scp_get_local()), scp_get_slug()),
				sprintf(__('<a href="http://wordpress.org/extend/plugins/%s/">Documentation</a>', scp_get_local()), scp_get_slug()),
				sprintf(__('<a href="http://wordpress.org/plugins/%s/faq/">FAQ</a>', scp_get_local()), scp_get_slug())
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
			plugins_url(scp_get_path() . '/css/admin.css'),
			array(),
			SCP_VERSION . "_" . date('njYHis', filemtime(dirname(__FILE__) . '/css/admin.css')),
			'all');
	}
	// when plugin is activated, create options array and populate with defaults
	register_activation_hook(__FILE__, 'scp_activate');
	function scp_activate() {
		$options = scp_getpluginoptions();
		update_option(scp_get_option(), $options);
		
		// delete option when plugin is uninstalled
		register_uninstall_hook(__FILE__, 'uninstall_scp_plugin');
	}
	function uninstall_scp_plugin() {
		delete_option(scp_get_option());
	}
		
	// generic function that returns plugin options from DB
	// if option does not exist, returns plugin defaults
	function scp_getpluginoptions() {
		return get_option(scp_get_option(), 
			array(
				SCP_DEFAULT_ENABLED_NAME => SCP_DEFAULT_ENABLED, 
				SCP_DEFAULT_TEXT_NAME => SCP_DEFAULT_TEXT, 
				SCP_DEFAULT_DISPLAY_ON_POSTS_NAME => SCP_DEFAULT_DISPLAY_ON_POSTS, 
				SCP_DEFAULT_DISPLAY_ON_PAGES_NAME => SCP_DEFAULT_DISPLAY_ON_PAGES, 
				SCP_DEFAULT_NONLOGGEDIN_NAME => SCP_DEFAULT_NONLOGGEDIN
			));
	}
	
// encapsulate these and call them throughout the plugin instead of hardcoding the constants everywhere
	function scp_get_slug() { return SCP_SLUG; }
	function scp_get_local() { return SCP_LOCAL; }
	function scp_get_option() { return SCP_OPTION; }
	function scp_get_path() { return SCP_PATH; }
	
	function scp_settingslink($linklist, $slugname = '', $localname = '') {
		$settings_link = sprintf( __('<a href="options-general.php?page=%s">Settings</a>', $localname), $slugname);
		array_unshift($linklist, $settings_link);
		return $linklist;
	}
	function scp_getsupportinfo($slugname = '', $localname = '') {
		$output = __('Do you need help with this plugin? Check out the following resources:', $localname);
		$output .= '<ol>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/extend/plugins/%s/">Documentation</a>', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/plugins/%s/faq/">FAQ</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/support/plugin/%s">Support Forum</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://www.jimmyscode.com/wordpress/%s">Plugin Homepage / Demo</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/extend/plugins/%s/developers/">Development</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/plugins/%s/changelog/">Changelog</a><br />', $localname), $slugname) . '</li>';
		$output .= '</ol>';
		
		$output .= sprintf( __('If you like this plugin, please <a href="http://wordpress.org/support/view/plugin-reviews/%s/">rate it on WordPress.org</a>', $localname), $slugname);
		$output .= sprintf( __(' and click the <a href="http://wordpress.org/plugins/%s/#compatibility">Works</a> button. ', $localname), $slugname);
		$output .= '<br /><br /><br />';
		$output .= __('Your donations encourage further development and support. ', $localname);
		$output .= '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7EX9NB9TLFHVW"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" alt="Donate with PayPal" title="Support this plugin" width="92" height="26" /></a>';
		$output .= '<br /><br />';
		return $output;
	}
	function scp_checkifset($optionname, $optiondefault, $optionsarr) {
		return (isset($optionsarr[$optionname]) ? $optionsarr[$optionname] : $optiondefault);
	}
	function scp_getlinebreak() {
	  echo '<tr valign="top"><td colspan="2"></td></tr>';
	}
	function scp_explanationrow($msg = '') {
		echo '<tr valign="top"><td></td><td><em>' . $msg . '</em></td></tr>';
	}
	function scp_getimagefilename($fname = '') {
		return plugins_url(scp_get_path() . '/images/' . $fname);
	}
?>