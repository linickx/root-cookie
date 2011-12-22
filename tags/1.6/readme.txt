=== Plugin Name ===
Contributors: linickx, sc0ttkclark
Donate link: http://www.linickx.com/donate
Tags: login, logout, authentication, cookie, subdomains, root, path
Requires at least: 3.2
Tested up to: 3.3
Stable tag: 1.6

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
1. Optinally enable subdomains support in admin dashboard
1. Log out
1. Log in
1. Done :o)

== Screenshots ==

1. The Admin interface, optional for if you want to change the domain.

== Changelog ==

= 1.6 = 
* WordPress 3.3 Compatability Update, hat tip for Joe Auty (http://www.netmusician.org) for Rasing.
* Logout Enhancement
* Bug fix "undefined method WP_Error::get_items"
* Contextual Help

= 1.5 =
* Added options panel to allow for cookies to be used across subdomains.
* Subdomain can be guessed or manually set
* root cookie News & Tutorials added to admin page

= 1.4 =
* WordPress 2.7 Compatability Update, credz to Edward Laverick (http://www.rndout.com/) for Raising.

= 1.3 =
* Updated to support new WP2.6 Cookies, credz to Scott Kingsley Clark  (http://www.vizioninteractive.com) for kicking my ass into getting this done!

= 1.2 =
*  Added logout function, as default on didn't work, thanks Aja! ( http://www.ajalapus.com/  )

= 1.1 =
* Added the safety hook 'if(!function_exists('wp_setcookie')' since WP2.1 crashed out.

= 1.0 =
* Original Release.

== Frequently Asked Questions ==

= What is root cookie path support? =
This is the basic functionality of the plugin, it removes the path from the cookie.
e.g
cookie set as www.domain.com/wordpress becomes www.doamin.com 

= How to I enable root cookie path support? =
You don't need to, by default the path is removed from the cookie just by enabling it

= What is root cookie subdomain support? =
This is new as of version 1.5, root cookie can now change the wordpress cookie to be accesiable across subdomains.
e.g.
cookie set as www.domain.com becomains domain.com

= How to I enable root cookie subdomain support? =
In the wordpress administrator dashboard, select root cookie and tick the box!

= How do I manually set the domain / subdomain of the cookie? =
In the wordpress administrator dashboard, select root cookie and unselect "Allow Cookies to go across All Subdomains" and in the "Domain Name" box type in you domain such as domain.com or domain.co.uk

= root cookie subdomains doesn't work for me! =
Try maunally setting the cookie, see above.

== Multi-blog Installs ==

It is possible to use this plugin such that if you are logged into one WordPress site, then you are auto-magically logged into the second, but there are some caveats!

1. The Username & Password _MUST_ be the same on Both!
1. The Security Keys http://codex.wordpress.org/Editing_wp-config.php#Security_Keys Need to be the same on Both!


== Support ==

Comments on my website are welcome, but please post [in this WordPress Forum](http://wordpress.org/tags/root-cookie?forum_id=10#postform)

== Upgrade Notice ==

Optional, but recommended.
