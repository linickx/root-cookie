<?php
/*
Plugin Name: root Cookie Path
Plugin URI: http://www.linickx.com/archives/831/root-cookie-path-14-an-update-for-wordpress-27
Description: Changes the cookie default path to / (i.e. the whole domain.com not just domain.com/blog)
Author: Nick [LINICKX] Bettison
Version: 1.4
Author URI: http://www.linickx.com
License: Free to use non-commercially.
Warranties: None.

== The Changelog has been moved to readme.txt ==
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
*/


# OK, so we rock up and setup a constant....
define('ROOT_COOKIE', '/' );

# Then we paste the WP functions from /wp-includes/pluggable.php
# ...
# and to finish we replace COOKIEPATH, PLUGINS_COOKIE_PATH  and ADMIN_COOKIE_PATH with ROOT_COOKIE, job done!

if ( !function_exists('wp_set_auth_cookie') ) :
/**
 * Sets the authentication cookies based User ID.
 *
 * The $remember parameter increases the time that the cookie will be kept. The
 * default the cookie is kept without remembering is two days. When $remember is
 * set, the cookies will be kept for 14 days or two weeks.
 *
 * @since 2.5
 *
 * @param int $user_id User ID
 * @param bool $remember Whether to remember the user or not
 */
function wp_set_auth_cookie($user_id, $remember = false, $secure = '') {
	if ( $remember ) {
		$expiration = $expire = time() + 1209600;
	} else {
		$expiration = time() + 172800;
		$expire = 0;
	}

	if ( '' === $secure )
		$secure = is_ssl() ? true : false;

	if ( $secure ) {
		$auth_cookie_name = SECURE_AUTH_COOKIE;
		$scheme = 'secure_auth';
	} else {
		$auth_cookie_name = AUTH_COOKIE;
		$scheme = 'auth';
	}

	$auth_cookie = wp_generate_auth_cookie($user_id, $expiration, $scheme);
	$logged_in_cookie = wp_generate_auth_cookie($user_id, $expiration, 'logged_in');

	do_action('set_auth_cookie', $auth_cookie, $expire, $expiration, $user_id, $scheme);
	do_action('set_logged_in_cookie', $logged_in_cookie, $expire, $expiration, $user_id, 'logged_in');

	// Set httponly if the php version is >= 5.2.0
	if ( version_compare(phpversion(), '5.2.0', 'ge') ) {
		setcookie($auth_cookie_name, $auth_cookie, $expire, ROOT_COOKIE, COOKIE_DOMAIN, $secure);
		/** Duplicate of above - Created by Find & Replace
		setcookie($auth_cookie_name, $auth_cookie, $expire, ROOT_COOKIE, COOKIE_DOMAIN, $secure);
		*/
		setcookie(LOGGED_IN_COOKIE, $logged_in_cookie, $expire, ROOT_COOKIE, COOKIE_DOMAIN);
		if ( COOKIEPATH != SITECOOKIEPATH )
			setcookie(LOGGED_IN_COOKIE, $logged_in_cookie, $expire, ROOT_COOKIE, COOKIE_DOMAIN, false, true);
	} else {
		$cookie_domain = COOKIE_DOMAIN;
		if ( !empty($cookie_domain) )
			$cookie_domain .= '; HttpOnly';
		setcookie($auth_cookie_name, $auth_cookie, $expire, ROOT_COOKIE, $cookie_domain, $secure);
		/** Duplicate of above - Created by Find & Replace
		setcookie($auth_cookie_name, $auth_cookie, $expire, ROOT_COOKIE, $cookie_domain, $secure);
		*/
		setcookie(LOGGED_IN_COOKIE, $logged_in_cookie, $expire, ROOT_COOKIE, $cookie_domain);
		if ( ROOT_COOKIE != SITECOOKIEPATH )
			setcookie(LOGGED_IN_COOKIE, $logged_in_cookie, $expire, ROOT_COOKIE, COOKIE_DOMAIN);
	}		
}

endif;

if ( !function_exists('wp_clear_auth_cookie') ) :
/**
 * Removes all of the cookies associated with authentication.
 *
 * @since 2.5
 */
function wp_clear_auth_cookie() {
	do_action('clear_auth_cookie');

	setcookie(AUTH_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, COOKIE_DOMAIN);
	setcookie(SECURE_AUTH_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, COOKIE_DOMAIN);
	setcookie(AUTH_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, COOKIE_DOMAIN);
	setcookie(SECURE_AUTH_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, COOKIE_DOMAIN);
	setcookie(LOGGED_IN_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, COOKIE_DOMAIN);
	setcookie(LOGGED_IN_COOKIE, ' ', time() - 31536000, SITECOOKIEPATH, COOKIE_DOMAIN);

	// Old cookies
	setcookie(AUTH_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, COOKIE_DOMAIN);
	setcookie(AUTH_COOKIE, ' ', time() - 31536000, SITECOOKIEPATH, COOKIE_DOMAIN);
	setcookie(SECURE_AUTH_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, COOKIE_DOMAIN);
	setcookie(SECURE_AUTH_COOKIE, ' ', time() - 31536000, SITECOOKIEPATH, COOKIE_DOMAIN);

	// Even older cookies
	setcookie(USER_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, COOKIE_DOMAIN);
	setcookie(PASS_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, COOKIE_DOMAIN);
	setcookie(USER_COOKIE, ' ', time() - 31536000, SITECOOKIEPATH, COOKIE_DOMAIN);
	setcookie(PASS_COOKIE, ' ', time() - 31536000, SITECOOKIEPATH, COOKIE_DOMAIN);
}
endif;

?>
