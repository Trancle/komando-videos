=== Rundiz PostOrder ===
Contributors: okvee
Tags: posts, order, sort, re-arrange, re arrange, rearrange, re_arrange, sortable, sort posts, order posts
Requires at least: 4.0
Tested up to: 4.7
Stable tag: 0.7
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9HQE4GVV4KTZE
License: MIT
License URI: http://opensource.org/licenses/MIT

Re-order posts to what you want.

== Description ==
If you want to customize the post order to the other than date, id, name. For example: You want to re-arrange it to display as what you want in your agency or company website.<br>
This plugin allow you to re-arrange the post order as you wish.

Re-arrange or re-order the posts but not interfere with sticky posts. Make your web sites different.

You can re-order the single post (move up/down) or multiple posts (sortable items). Move up or down action is very helpful when you have many posts and many pages to display and you want the old post to list up to the top.
You can also disable custom post order in some category or all everywhere by adding `rd_postorder_is_working` and `rd_postorder_admin_is_working` filters and its value is boolean.

It's clean!<br>
My plugins are always restore everything to its default value and cleanup. I love clean db and don't let my plugins left junk in your db too.

It's completely free!<br>
It's not the "pay for premium feature" or freemium. It's free and no ADs. However, if you like it please donate to help me buy some food.

= System requirement =
PHP 5.4 or higher<br>
WordPress 4.0 or higher

== Installation ==
1. Upload "rd-postorder" folder to the "/wp-content/plugins/" directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Done.

== Frequently Asked Questions ==
= Is multisite support? =
Yes, of course.

= Support for re-order the pages or custom post type? =
No, it doesn't support for now.

= Does it gonna be mess if I uninstall this plugin? =
No, on uninstallation or delete the plugin, it will be reset the *menu_order* to zero which is WordPress default value for post content.

= How to disable custom order in some category? =
In the category.php or archive.php file, make your condition to category you want and then add this filter hook. `add_filter('rd_postorder_is_working', '__return_false');`<br>
If you want to enable, just remove the filter or change from `__return_false` to `__return_true`.

= How to disable custom order in admin list post page? =
Same as disable custom order in the front-end. Add this filter hook into your theme or plugin. `add_filter('rd_postorder_admin_is_working', '__return_false');`

== Screenshots ==
1. Front end re-order with sticky post.
2. Admin re-order page.
3. Re-ordering action.

== Changelog ==
= 0.7 =
2016-11-13

* Fix current page input that was not work.

= 0.6.1 =
2106-10-23

* Add debugger class to debug uninstallation.

= 0.6 =
2016-10-23

* Tested with multisite enabled and it works!
* Fix uninstall for multisite enabled.

= 0.5 =
2016-10-22

* Add support for filters `rd_postorder_is_working` and `rd_postorder_admin_is_working`

= 0.4 =
2016-10-19

* Change from buttons actions to bulk actions
* Add manually change order numbers
* Move help text to help screen

= 0.3 =
2016-10-13

* Fix uninstall error
* Fix single quote in the input array but this maybe the cause of sort items wrong number.

= 0.2 =
2016-10-13

* Fix ajax replace list table
* Fix PHP notices

= 0.1 =
2016-10-11

* The beginning.

== Upgrade Notice ==

