=== Plugin Name ===
Contributors: wordpressdotorg
Donate link: 
Tags: importer, wordpress
Requires at least: 3.0
Tested up to: 3.1
Stable tag: 0.2

Import posts, pages, comments, custom fields, categories, tags and more from a WordPress export file.

== Description ==

Import posts, pages, comments, custom fields, categories, tags and more from a WordPress export file.

== Installation ==

1. Upload the `wordpress-importer` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to the Tools -> Import screen, click on WordPress

== Changelog ==

= 0.3 =
* Use an XML Parser if possible
* Proper import support for nav menus
* ... and more, see [Trac ticket #15197](http://core.trac.wordpress.org/ticket/15197)

= 0.1 =
* Initial release

== Upgrade Notice ==

= 0.3 =
Upgrade for a more robust and reliable experience when importing WordPress export files, and for compatibility with WordPress 3.1.

== Filters ==

The importer has a couple of filters to allow you to completely enable/block certain features:
* `import_allow_create_users`: return false if you only want to allow mapping to existing users
* `import_allow_fetch_attachments`: return false if you do not wish to allow importing and downloading of attachments
* `import_attachment_size_limit`: return an integer value for the maximum file size in bytes to save (default is 0, which is unlimited)
