=== Plugin Name ===
Contributors: Nick  Bettison - LINICKX, sc0ttkclark
Donate link: http://www.linickx.com/index.php?content=donate
Tags: login, logout, authentication, cookie, subdomains
Requires at least: 2.6
Tested up to: 2.7.1
Stable tag: 1.4

Changes the cookie default path to / (i.e. the whole domain.com not just domain.com/blog) with an option to go across subdomains

== Description ==

If you want to integrate the wordpress authentication magic into another script within your website you may come across authentication issues because your code cannot read the wordpress cookie.

By default the wordpress cookie exactly matches the URL of your installation, this plugin removes any subfolders from the cookie so that your whole domain has access to it.

For Example if you have wordpress installed in http://www.mydomain.com/wordpress/ any php stored in http://www.mydomain.com/mymagiccode/ cannot see the cookie due to browser security. This plugin changes the path to http://www.mydomain.com/ so that any php code on your site can access the cookie, so in our above example http://www.mydomain.com/mymagiccode/checkauthentication.php can now check the cookie to see if you have logged into wordpress.

NEW: Now you can allow cookies to be used across subdomains! Here are some examples that you can now access cookies from:

* http://www.mydomain.com/
* http://test.mydomain.com/
* http://www.mydomain.com/blog/
* http://test.mydomain.com/new/
* http://forum.mydomain.com/getwordpresslogin.php

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `root-cookie.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Log out
1. Log in
1. Done :o)

== Change LOG ==
* 1.0 : Original Release.
* 1.1 : Added the safety hook 'if(!function_exists('wp_setcookie')' since WP2.1 crashed out.
* 1.2 : Added logout function, as default on didn't work, thanks Aja! ( http://www.ajalapus.com/  )
* 1.3 : Updated to support new WP2.6 Cookies, credz to Scott Kingsley Clark  (http://www.vizioninteractive.com) for kicking my ass into getting this done!
* 1.4 : WordPress 2.7 Compatability Update, credz to Edward Laverick (http://www.rndout.com/) for Raising.
* 1.5 : Added options panel to allow for cookies to be used across subdomains.

== Support ==

Comments on my website are welcome, but please post [in this WordPress Forum](http://wordpress.org/tags/root-cookie?forum_id=10#postform)
