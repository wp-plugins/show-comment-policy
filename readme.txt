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

== Installation ==

1. Upload plugin file through the WordPress interface.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Settings &raquo; Show Comment Policy, configure plugin.
4. Check a post or page that allows comments.

== Frequently Asked Questions ==

= How do I use the plugin? =

Go to Settings &raquo; Show Comment Policy and enter the text you want to see above the comment form. Make sure the "enabled" checkbox is checked. Note that the comment policy will only be displayed on pages/posts where the comments are open. If comments are closed, the policy will not appear.

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

== Changelog ==

= 0.0.1 =
- created
- verified compatibility with 3.9

== Upgrade Notice ==

= 0.0.1 =
created, verified compatibility with 3.9