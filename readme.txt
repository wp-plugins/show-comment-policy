=== Show Comment Policy ===
Tags: comments, blog, policy
Requires at least: 3.5
Tested up to: 3.9
Contributors: jp2112
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7EX9NB9TLFHVW
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display your site's comment policy above the comment form.

== Description ==

Show Comment Policy lets you display a custom message above your site's comment form. Display your blog comment policy or any other text you want your readers to see.

Use it for:

<ul>
<li>Comment policy</li>
<li>Posting rules</li>
<li>List of allowed HTML tags in comments</li>
</ul>

<h3>If you need help with this plugin</h3>

If this plugin breaks your site or just flat out does not work, please go to <a href="http://wordpress.org/plugins/show-comment-policy/#compatibility">Compatibility</a> and click "Broken" after verifying your WordPress version and the version of the plugin you are using.

Then, create a thread in the <a href="http://wordpress.org/support/plugin/show-comment-policy">Support</a> forum with a description of the issue. Make sure you are using the latest version of WordPress and the plugin before reporting issues, to be sure that the issue is with the current version and not with an older version where the issue may have already been fixed.

<strong>Please do not use the <a href="http://wordpress.org/support/view/plugin-reviews/show-comment-policy">Reviews</a> section to report issues or request new features.</strong>

== Installation ==

1. Upload plugin file through the WordPress interface.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Settings &raquo; Show Comment Policy, configure plugin.
4. Check a post or page that allows comments.

== Frequently Asked Questions ==

= How do I use the plugin? =

Go to Settings &raquo; Show Comment Policy and enter the text you want to see above the comment form. Make sure the "enabled" checkbox is checked. Note that the comment policy will only be displayed on pages/posts where the comments are open. If comments are closed, the policy will not appear. You may also includes shortcodes which will be processed for output.

= I entered some text but don't see anything on the page. =

Are you using another plugin that is also trying to use the <strong>comment_form_before</strong> hook?

Is the page/post cached?

= How can I style the output? =

The comment policy text is wrapped in div tags with class name "scp-comment-policy". Use this class in your local stylesheet to style the output how you want.

= I don't want the admin CSS. How do I remove it? =

Add this to your functions.php:

`remove_action('admin_head', 'insert_scp_admin_css');`

== Screenshots ==

1. Plugin settings page
2. The comments section showing the comment policy entered on the plugin settings page

== Changelog ==

= 0.1.1 =
- updated .pot file and readme

= 0.1.0 =
- fixed validation issue

= 0.0.9 =
- minor code optimizations

= 0.0.8 =
- code fix
- admin CSS and page updates

= 0.0.7 =
- updated support tab

= 0.0.6 =
- minor code fix

= 0.0.5 = 
- code fix

= 0.0.4 =
- option to hide output for logged in users
- now processes shortcodes
- code optimizations
- plugin settings page is now tabbed

= 0.0.3 =
- fix 2 for wp_kses

= 0.0.2 =
- fix for wp_kses

= 0.0.1 =
- created
- verified compatibility with 3.9

== Upgrade Notice ==

= 0.1.1 =
- updated .pot file and readme

= 0.1.0 =
- fixed validation issue

= 0.0.9 =
- minor code optimizations

= 0.0.8 =
- code fix; admin CSS and page updates

= 0.0.7 =
- updated support tab

= 0.0.6 =
- minor code fix

= 0.0.5 = 
- code fix

= 0.0.4 =
- option to hide output for logged in users; now processes shortcodes; code optimizations; plugin settings page is now tabbed

= 0.0.3 =
- fix 2 for wp_kses

= 0.0.2 =
- fix for wp_kses

= 0.0.1 =
created, verified compatibility with 3.9