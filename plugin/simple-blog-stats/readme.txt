=== Simple Blog Stats ===

Plugin Name: Simple Blog Stats
Plugin URI: https://perishablepress.com/simple-blog-stats/
Description: Provides shortcodes and template tags to display a variety of statistics about your site.
Tags: stats, statistics, analytics, posts, pages,  drafts, comments, categories, recent posts, tags, users
Author: Jeff Starr
Author URI: https://plugin-planet.com/
Donate link: https://monzillamedia.com/donate.html
Contributors: specialk
Requires at least: 4.1
Tested up to: 5.3
Stable tag: 20191105
Version: 20191105
Requires PHP: 5.6.20
Text Domain: simple-blog-stats
Domain Path: /languages
License: GPL v2 or later

Displays a wealth of useful statistics about your site.



== Description ==

[Simple Blog Stats](https://perishablepress.com/simple-blog-stats/) (SBS) provides shortcodes and tags to display site stats in posts, pages, and anywhere in your theme.

**Display Statistics**

* Total number of posts
* Total number of pages
* Total number of drafts
* Total number of comments
* Total number of media files
* Number of comments in moderation
* Number of approved comments
* Number of registered users
* Number of categories
* Number of tags
* Number of words for any post
* Number of words for all posts
* Display all blog stats in a list
* Display number of posts for any Custom Post Type
* Display list of counts for all Custom Post Types

__NEW!__ Display current number of logged-in users

**Plugin Features**

* Uses caching for better performance
* Provides shortcodes to display stats in Posts and Pages
* Provides template tags to display stats anywhere in your theme
* Configure text/markup to appear before and/or after each shortcode
* Built with the WP API for optimal performance and security
* Provides slick settings screen with toggling panels
* Provides option to restore default plugin settings
* Displays your stats with clean, valid markup
* Regularly updated and "future proof"
* NEW! Display stats via Dashboard widget

**More Statistics**

* Display date of most recent site update
* Display list of recent posts (configurable)
* Display list of recent comments (configurable)
* Display number of users per role (configurable)
* Display all blog stats in a nicely formatted list
* Configure all shortcodes via the plugin settings
* Eat a bowl of ice cream :)

**Privacy**

This plugin does not collect or store any user data. It does not set any cookies, and it does not connect to any third-party locations. Thus, this plugin does not affect user privacy in any way.

> Works perfectly with or without Gutenberg Block Editor



== Installation ==

**Installation**

1. Upload the plugin to your blog and activate
2. Visit the settings to configure your options

[More info on installing WP plugins](https://codex.wordpress.org/Managing_Plugins#Installing_Plugins)


**Usage**

Visit the SBS settings page to configure your shortcodes. Then copy/paste the shortcodes in any Post, Page, or Widget to display your stats. To display your stats anywhere in your theme template, visit the Template Tags section of the settings page.


**Shortcodes**

Visit the plugin settings page for a complete list of shortcodes. There you may customize the output of each shortcode. Here is a list of all SBS shortcodes:


	[sbs_posts]                    = number of posts
	[sbs_posts_alt]                = number of posts (for sites with LOTS of posts)
	[sbs_pages]                    = number of pages
	[sbs_drafts]                   = number of drafts
	[sbs_comments]                 = number of comments
	[sbs_moderated]                = moderated comments
	[sbs_approved]                 = approved comments
	[sbs_users]                    = number of users
	[sbs_cats]                     = number of categories
	[sbs_tags]                     = number of tags
	[sbs_tax tax="tax_name"]       = number of taxonomy terms
	[sbs_word_count id="1"]        = number of words in post 1
	[sbs_word_count_all]           = number of words in all posts
	[sbs_updated]                  = site last updated
	[sbs_latest_posts]             = displays recent posts
	[sbs_latest_comments]          = displays recent comments
	[sbs_roles role="all"]         = number of users per role
	[sbs_cpts_count]               = list of CPT counts
	[sbs_cpt_count cpt="post"]     = number of any post type
	[sbs_blog_stats]               = displays list of blog stats
	[sbs_logged_users]             = number of logged-in users
	[sbs_media_count type="image"] = number of images or videos in media library


Note that the `[sbs_posts]` shortcode accepts several attributes that can be used to customize your post stats:

* `cat` - limit by category (default: empty = all cats)
* `tag` - limit by tag (default: empty = all tags)
* `type` - limit by post type (default: empty = post)
* `status` - limit by post status (default: empty = publish)

So by default, `[sbs_posts]` with no attributes will display the total number of published posts in any category or tag. 

Here is an example that makes use of the attributes:

	[sbs_posts cat="sci-fi" tag="sequel" type="movie" status="draft"]

So this will display all drafts of the custom post type "movie" that are in the "sci-fi" category and tagged as "sequel".

For the `[sbs_posts_alt]` shortcode, it is not as flexible, but does provide a couple of attributes:

	[sbs_posts_alt type="page" status="draft"]

You can change the `type` and `status` of the posts that should be counted.


__Tip:__ you can display only the number (and no text) for certain shortcodes. For example:

	[sbs_cpt_count cpt="post" txt="null"]
	[sbs_roles role="subscriber" txt="null"]

By setting the value of `txt` to `null`, only the number is displayed. Or you can change the `txt` value to whatever you want.

More information about the possible values for these attributes:

* [cat](https://codex.wordpress.org/Class_Reference/WP_Query#Category_Parameters)
* [tag](https://codex.wordpress.org/Class_Reference/WP_Query#Tag_Parameters)
* [type](https://codex.wordpress.org/Class_Reference/WP_Query#Type_Parameters)
* [status](https://codex.wordpress.org/Class_Reference/WP_Query#Status_Parameters)


**Like the plugin?**

If you like Simple Blog Stats, please take a moment to [give a 5-star rating](https://wordpress.org/support/plugin/simple-blog-stats/reviews/?rate=5#new-post). It helps to keep development and support going strong. Thank you!


**Upgrades**

To upgrade SBS, remove the old version and replace with the new version. Or just click "Update" from the Plugins screen and let WordPress do it for you automatically.

__Note:__ uninstalling the plugin from the WP Plugins screen results in the removal of all settings from the WP database. 


**Restore Default Options**

To restore default plugin options, either uninstall/reinstall the plugin, or visit the plugin settings &gt; Restore Default Options.


**Uninstalling**

Simple Blog Stats cleans up after itself. All plugin settings will be removed from your database when the plugin is uninstalled via the Plugins screen.



== Upgrade Notice ==

To upgrade SBS, remove the old version and replace with the new version. Or just click "Update" from the Plugins screen and let WordPress do it for you automatically.

__Note:__ uninstalling the plugin from the WP Plugins screen results in the removal of all settings from the WP database. 



== Screenshots ==

1. Simple Blog Stats: Settings Screen (panels toggle open/closed)

More screenshots and information available at the [SBS Homepage](https://perishablepress.com/simple-blog-stats/).



== Frequently Asked Questions ==

**How do I use the count-word shortcode?**

There are three ways to count words:

	[sbs_word_count]         // displays word count of current post
	[sbs_word_count id="1"]  // displays word count of post with ID = 1
	[sbs_word_count_all]     // displays word count for all published posts


**How do I use the user-role shortcode?**

There are three ways to display user roles:

	[sbs_roles]                // displays list of roles and number of users
	[sbs_roles role="author"]  // displays number of users for specified role
	[sbs_roles role="all"]     // displays list of roles and number of users


**How to limit/customize the number of counted posts?**

The plugin provides a filter hook for customizing the total number of posts that are displayed using the `[sbs_posts]` shortcode. To do it, add the following snippet to your theme functions.php file:

	function sbs_get_posts_limit_custom($limit) { return 100; }
	add_filter('sbs_get_posts_limit', 'sbs_get_posts_limit_custom');

No changes need made; simply edit the `100` to whatever is desired and done.


**Got a question?**

Send any questions or feedback via my [contact form](https://perishablepress.com/contact/). Thanks! :)



== Support development of this plugin ==

I develop and maintain this free plugin with love for the WordPress community. To show support, you can [make a donation](https://monzillamedia.com/donate.html) or purchase one of my books: 

* [The Tao of WordPress](https://wp-tao.com/)
* [Digging into WordPress](https://digwp.com/)
* [.htaccess made easy](https://htaccessbook.com/)
* [WordPress Themes In Depth](https://wp-tao.com/wordpress-themes-book/)

And/or purchase one of my premium WordPress plugins:

* [BBQ Pro](https://plugin-planet.com/bbq-pro/) - Super fast WordPress firewall
* [Blackhole Pro](https://plugin-planet.com/blackhole-pro/) - Automatically block bad bots
* [Banhammer Pro](https://plugin-planet.com/banhammer-pro/) - Monitor traffic and ban the bad guys
* [GA Google Analytics Pro](https://plugin-planet.com/ga-google-analytics-pro/) - Connect your WordPress to Google Analytics
* [USP Pro](https://plugin-planet.com/usp-pro/) - Unlimited front-end forms

Links, tweets and likes also appreciated. Thanks! :)



== Changelog ==

If you like Simple Blog Stats, please take a moment to [give a 5-star rating](https://wordpress.org/support/plugin/simple-blog-stats/reviews/?rate=5#new-post). It helps to keep development and support going strong. Thank you!


**20191105**

* Updates styles for plugin settings page
* Tests on WordPress 5.3

**20190902**

* Fixes bug with count all words in posts
* Adds `number_format()` to format numbers with commas
* Updates some links to https
* Generates new default translation template
* Tests on WordPress 5.3 (alpha)

**20190429**

* Bumps [minimum PHP version](https://codex.wordpress.org/Template:Server_requirements) to 5.6.20
* Adds shortcode for count of images or videos in Media Library
* Fixes PHP Notices: "trying to get property of non-object"
* Tweaks plugin settings screen content
* Updates default translation template
* Tests on WordPress 5.2

**20190308**

* Adds new taxonomy shortcode,`[sbs_tax]`
* Adds alternate posts shortcode, `[sbs_posts_alt]`
* Adds check for admin user for settings shortcut link
* Tweaks plugin settings screen UI
* Generates new default translation template
* Tests on WordPress 5.1 and 5.2 (alpha)

**20190220**

* Just a version bump for compat with WP 5.1
* Full update coming soon :)

**20181114**

* Adds shortcode and dashboard widget for current number of logged-in users
* Adds `sbs_last_update` filter hook for date format
* Adds homepage link to Plugins screen
* Updates default translation template
* Tests on WordPress 5.0

**20180828**

* Fixes bug with caching and correct stats
* Adds new option to enable/disable caching
* Generates new default translation template
* Further tests on WP 4.9 and 5.0 (alpha)

**20180820**

* Implements transients to some count functions for better performance
* Adds filter hook `sbs_get_posts_limit` for `sbs_word_count_all` and `sbs_posts`
* Adds `txt` attribute to `[sbs_cpt_count]` and `[sbs_roles]`
* Fixes plural nouns on user role counts
* Adds `rel="noopener noreferrer"` to all [blank-target links](https://perishablepress.com/wordpress-blank-target-vulnerability/)
* Updates GDPR blurb and donate link
* Regenerates default translation template
* Further tests on WP versions 4.9 and 5.0 (alpha)

**20180508**

* Adds shortcode `[sbs_cpts_count]`, displays list of CPT counts
* Adds shortcode `[sbs_cpt_count cpt="post"]`, displays CPT count
* Tweaks some styles on the plugin settings page
* Generates new translation template
* Updates Show Support panel
* Updates plugin image files
* Tests on WordPress 5.0

**20171103**

* Fixes bug with new `[sbs_posts]` shortcode (see docs for info)
* Removes extra `manage_options` check for settings validation
* Tests on WordPress 4.9

**20171024**

* Adds new attributes for `[sbs_posts]` (see documentation for info)
* Adds extra `manage_options` capability check to modify settings
* Improves logic of `sbs_require_wp_version`
* Streamlines Support panel in plugin settings
* Regenerates default translation template
* Tests on WordPress 4.9

**20170801**

* Updates GPL license blurb
* Adds GPL license text file
* Tests on WordPress 4.9 (alpha)

**20170325**

* Refines display of settings panels
* Adds dashboard widget to display stats
* Adds user-role shortcode `[sbs_roles role="all"]`
* Updates show support panel in plugin settings
* Replaces global `$wp_version` with `get_bloginfo('version')`
* Regenerates default translation template
* Tests on WordPress version 4.8

**20161118**

* New shortcodes to count words in posts!
* Added shortcode to count words in any post: `[sbs_word_count]`
* Added shortcode to count words in all posts: `[sbs_word_count_all]`
* Added new shortcodes to `sbs_blog_stats()` function
* Updated plugin author URL
* Changed stable tag from trunk to latest version
* Added `&raquo;` to rate plugin link and home link
* Updated URL for rate this plugin links
* Removed default abbr styles
* Generated new default language template
* Tested on WordPress version 4.7 (beta)

**20160813**

* Streamlined and optimized the plugin settings page
* Replaced `_e()` with `esc_html_e()` or `esc_attr_e()`
* Replaced `__()` with `esc_html__()` or `esc_attr__()`
* Added plugin icons and larger banner image
* Replaced plugin icon on settings page
* Changed text-domain from "sbs" to "simple-blog-stats"
* Removed local translations in favor of [GlotPress](https://make.wordpress.org/polyglots/handbook/tools/glotpress-translate-wordpress-org/)
* Improved translation support
* Generated new translation template
* Added ellipsis to recent comment excerpt
* Tested on WordPress 4.6

**20160408**

* Replaced icon with retina version
* Added screenshot to readme/docs
* Added retina version of banner
* Reorganized and refreshed readme.txt
* Tested on WordPress version 4.5 beta

**20151111**

* Updated heading hierarchy in plugin settings
* Updated minimum version requirement
* Tested on WordPress 4.4 beta

**20150808**

* Tested on WordPress 4.3
* Updated minimum version requirement

**20150507**

* Tested with WP 4.2 + 4.3 (alpha)
* Changed a few "http" links to "https"

**20150315**

* Tested with latest version of WP (4.1)
* Increased minimum version to WP 3.8
* Removed deprecated screen_icon()
* Streamline/fine-tune plugin code
* Added $sbs_wp_vers for version check
* Added Text Domain and Domain Path to file header
* Added .pot translation template to /languages/

**20140925**

* Tested on latest version of WP (4.0)
* Increased min-version requirement to WP 3.7
* Added conditional check for min-version function

**20140305**

* Bugfix: replaced mysql_real_escape_string() with esc_attr(), resolves PHP error

**20140123**

* Tested with latest WordPress (3.8)
* Added trailing slash to load_plugin_textdomain()

**20131106**

* Removed closing `?>` from simple-blog-stats.php
* General code cleanup and maintenance
* Tested on latest version of WordPress 3.7
* Added "rate this plugin" links
* Added uninstall.php file
* Added i18n support
* Added line to prevent direct loading of script

**20130713**

* General code check n clean
* Improved localization support
* Overview and Updates admin panels toggled open by default

**20130104**

* Added margins to submit buttons

**20121029** 

* Initial release of new plugin

**20121028**

* Fine-tuned, tested, tested, etc.

**20121027**

* Rebuilt and renamed BlogStats PCC plugin

**20060828**

* Initial release of [BlogStats PCC](https://perishablepress.com/blogstats-pcc-plugin/)
