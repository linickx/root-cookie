<?php
/*
Plugin Name: root Cookie Path
Plugin URI: http://www.linickx.com/archives/831/root-cookie-path-14-an-update-for-wordpress-27
Description: Changes the cookie default path to / (i.e. the whole domain.com not just domain.com/blog) with an option to go across subdomains
Author: Nick [LINICKX] Bettison and Vizion Interactive, Inc
Version: 1.5
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
	
	$subdomain = get_option('rootcookie_subdomain');
	if($subdomain==1)
		{
			$info = get_bloginfo('url');
			$info = parse_url($info);
			$info = $info['host'];
			$exp = explode('.',$info);
			if(count($exp)==3){$domain = '.'.$exp[1].'.'.$exp[2];}
			elseif(count($exp)==2){$domain = '.'.$info;}
			elseif(3<count($exp)){$exp = array_reverse($exp); $domain = '.'.$exp[1].'.'.$exp[0];}
			else{$domain = COOKIE_DOMAIN;}
		}
	else{$domain = COOKIE_DOMAIN;}

	// Set httponly if the php version is >= 5.2.0
	if ( version_compare(phpversion(), '5.2.0', 'ge') ) {
		setcookie($auth_cookie_name, $auth_cookie, $expire, ROOT_COOKIE, $domain, $secure);
		/** Duplicate of above - Created by Find & Replace
		setcookie($auth_cookie_name, $auth_cookie, $expire, ROOT_COOKIE, $domain, $secure);
		*/
		setcookie(LOGGED_IN_COOKIE, $logged_in_cookie, $expire, ROOT_COOKIE, $domain);
		if ( COOKIEPATH != SITECOOKIEPATH )
			setcookie(LOGGED_IN_COOKIE, $logged_in_cookie, $expire, ROOT_COOKIE, $domain, false, true);
	} else {
		$cookie_domain = $domain;
		if ( !empty($cookie_domain) )
			$cookie_domain .= '; HttpOnly';
		setcookie($auth_cookie_name, $auth_cookie, $expire, ROOT_COOKIE, $cookie_domain, $secure);
		/** Duplicate of above - Created by Find & Replace
		setcookie($auth_cookie_name, $auth_cookie, $expire, ROOT_COOKIE, $cookie_domain, $secure);
		*/
		setcookie(LOGGED_IN_COOKIE, $logged_in_cookie, $expire, ROOT_COOKIE, $cookie_domain);
		if ( ROOT_COOKIE != SITECOOKIEPATH )
			setcookie(LOGGED_IN_COOKIE, $logged_in_cookie, $expire, ROOT_COOKIE, $domain);
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
	
	$subdomain = get_option('rootcookie_subdomain');
	if($subdomain==1)
		{
			$info = get_bloginfo('url');
			$info = parse_url($info);
			$info = $info['host'];
			$exp = explode('.',$info);
			if(count($exp)==3){$domain = '.'.$exp[1].'.'.$exp[2];}
			elseif(count($exp)==2){$domain = '.'.$info;}
			elseif(3<count($exp)){$exp = array_reverse($exp); $domain = '.'.$exp[1].'.'.$exp[0];}
			else{$domain = COOKIE_DOMAIN;}
		}
	else{$domain = COOKIE_DOMAIN;}

	setcookie(AUTH_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, $domain);
	setcookie(SECURE_AUTH_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, $domain);
	setcookie(AUTH_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, $domain);
	setcookie(SECURE_AUTH_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, $domain);
	setcookie(LOGGED_IN_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, $domain);
	setcookie(LOGGED_IN_COOKIE, ' ', time() - 31536000, SITECOOKIEPATH, $domain);

	// Old cookies
	setcookie(AUTH_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, $domain);
	setcookie(AUTH_COOKIE, ' ', time() - 31536000, SITECOOKIEPATH, $domain);
	setcookie(SECURE_AUTH_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, $domain);
	setcookie(SECURE_AUTH_COOKIE, ' ', time() - 31536000, SITECOOKIEPATH, $domain);

	// Even older cookies
	setcookie(USER_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, $domain);
	setcookie(PASS_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, $domain);
	setcookie(USER_COOKIE, ' ', time() - 31536000, SITECOOKIEPATH, $domain);
	setcookie(PASS_COOKIE, ' ', time() - 31536000, SITECOOKIEPATH, $domain);
}
endif;

function rootcookie_activate ()
	{
		$opt_val = get_option('rootcookie_subdomain');
		if($opt_val!=1)
			{
				delete_option('rootcookie_subdomain');
				add_option('rootcookie_subdomain',0);
			}
	}
function rootcookie_menu ()
	{
		add_options_page('root Cookie Path Options', 'root Cookie Path', 8, __FILE__, 'rootcookie_options');
	}
function rootcookie_options ()
	{
		// variables for the field and option names
		$opt_name = 'rootcookie_subdomain';
		$hidden_field_name = 'rootcookie_submit_hidden';
	
		// Read in existing option value from database
		$opt_val = get_option( $opt_name );
		$checked=false;
		if($opt_val==1){$checked=true;}
	
		// See if the user has posted us some information
		// If they did, this hidden field will be set to 'Y'
		if( $_POST[ $hidden_field_name ] == 'Y' )
			{
				$opt_val = 0;
				if(isset($_POST['cookiepath_subdomain']))
					{
						$opt_val = 1;
					}
				update_option( $opt_name, $opt_val );
				echo '<div class="updated"><p><strong>'._('Options saved.', 'cookiepath_trans_domain' ).'</strong></p></div>';
			}
?>
<div class="wrap">
<?php echo "<h2>" . __( 'root Cookie Path Options', 'rootcookie_trans_domain' ) . "</h2>"; ?>
<form name="plugin_options" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y" />
<p><?php _e("Allow Cookies to go across All Subdomains:", 'rootcookie_trans_domain' ); ?> 
<input type="checkbox" name="<?php echo $opt_name; ?>" value="<?php echo $opt_val; ?>"<?php if($checked){echo " CHECKED";} ?> />
</p><hr />
<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'rootcookie_trans_domain' ) ?>" />
</p>
</form>
</div>
<?php
	}

// Run all actions and hooks at the end to keep it tidy
add_action('admin_menu', 'rootcookie_menu');
register_activation_hook( __FILE__,'rootcookie_activate');
?>
