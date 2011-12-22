<?php
/*
Plugin Name: root Cookie
Plugin URI: http://www.linickx.com/3495/root-cookie-1-6-two-years-in-the-making
Description: Changes the cookie default path to / (i.e. the whole domain.com not just domain.com/blog) with an option to go across subdomains
Author: Nick [LINICKX] Bettison and Vizion Interactive, Inc
Version: 1.6
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
 * @param bool $remember Whether to remember the user
 */
	function wp_set_auth_cookie($user_id, $remember = false, $secure = '') {
		if ( $remember ) {
			$expiration = $expire = time() + apply_filters('auth_cookie_expiration', 1209600, $user_id, $remember);
		} else {
			$expiration = time() + apply_filters('auth_cookie_expiration', 172800, $user_id, $remember);
			$expire = 0;
		}
		
		if ( '' === $secure )
			$secure = is_ssl();		

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
	$rootcookie_subdomain_manual = get_option('rootcookie_subdomain_manual');

	if($subdomain==1)
		{
			# Use Scotts implementation
			$info = get_bloginfo('url');
			$info = parse_url($info);
			$info = $info['host'];
			$exp = explode('.',$info);
			if(count($exp)==3){$domain = '.'.$exp[1].'.'.$exp[2];}
			elseif(count($exp)==2){$domain = '.'.$info;}
			elseif(3<count($exp)){$exp = array_reverse($exp); $domain = '.'.$exp[1].'.'.$exp[0];}
			else{$domain = COOKIE_DOMAIN;}
		}
	elseif (!is_null($rootcookie_subdomain_manual))
                {
			# Use manual domain name setting
                        $domain = $rootcookie_subdomain_manual;
                }
	else
		{
			# Default
			$domain = COOKIE_DOMAIN;
	}

	setcookie($auth_cookie_name, $auth_cookie, $expire, ROOT_COOKIE, $domain, $secure, true);
	/** Duplicate of above - Created by Find & Replace
	setcookie($auth_cookie_name, $auth_cookie, $expire, ROOT_COOKIE, $domain, $secure, true);
	 **/
	setcookie(LOGGED_IN_COOKIE, $logged_in_cookie, $expire, ROOT_COOKIE, $domain, $secure_logged_in_cookie, true);
	if ( COOKIEPATH != SITECOOKIEPATH )
		setcookie(LOGGED_IN_COOKIE, $logged_in_cookie, $expire, SITECOOKIEPATH, COOKIE_DOMAIN, $secure_logged_in_cookie, true);
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
	$rootcookie_subdomain_manual = get_option('rootcookie_subdomain_manual');

	# As ABOVE!
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
	elseif (!is_null($rootcookie_subdomain_manual)) 
		{
			$domain = $rootcookie_subdomain_manual;
		}
	else
		{
			$domain = COOKIE_DOMAIN;
	}

	/** Clear All possible cookies **/

	setcookie(AUTH_COOKIE, ' ', time() - 31536000, ADMIN_COOKIE_PATH, COOKIE_DOMAIN);
	setcookie(AUTH_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, COOKIE_DOMAIN);
	setcookie(AUTH_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, $domain);
	setcookie(AUTH_COOKIE, ' ', time() - 31536000, ADMIN_COOKIE_PATH, $domain);
	
	setcookie(SECURE_AUTH_COOKIE, ' ', time() - 31536000, ADMIN_COOKIE_PATH, COOKIE_DOMAIN);
	setcookie(SECURE_AUTH_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, COOKIE_DOMAIN);
	setcookie(SECURE_AUTH_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, $domain);
	setcookie(SECURE_AUTH_COOKIE, ' ', time() - 31536000, ADMIN_COOKIE_PATH, $domain);

	setcookie(AUTH_COOKIE, ' ', time() - 31536000, PLUGINS_COOKIE_PATH, COOKIE_DOMAIN);
	setcookie(AUTH_COOKIE, ' ', time() - 31536000, PLUGINS_COOKIE_PATH, $domain);
	
	setcookie(SECURE_AUTH_COOKIE, ' ', time() - 31536000, PLUGINS_COOKIE_PATH, COOKIE_DOMAIN);
	setcookie(SECURE_AUTH_COOKIE, ' ', time() - 31536000, PLUGINS_COOKIE_PATH, $domain);
	
	setcookie(LOGGED_IN_COOKIE, ' ', time() - 31536000, COOKIEPATH, COOKIE_DOMAIN);
	setcookie(LOGGED_IN_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, COOKIE_DOMAIN);
	setcookie(LOGGED_IN_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, $domain);
	setcookie(LOGGED_IN_COOKIE, ' ', time() - 31536000, COOKIEPATH, $domain);
	
	setcookie(LOGGED_IN_COOKIE, ' ', time() - 31536000, SITECOOKIEPATH, COOKIE_DOMAIN);
	setcookie(LOGGED_IN_COOKIE, ' ', time() - 31536000, SITECOOKIEPATH, $domain);
	
	// Old cookies
	setcookie(AUTH_COOKIE, ' ', time() - 31536000, COOKIEPATH, COOKIE_DOMAIN);
	setcookie(AUTH_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, COOKIE_DOMAIN);
	setcookie(AUTH_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, $domain);
	setcookie(AUTH_COOKIE, ' ', time() - 31536000, COOKIEPATH, $domain);
	
	setcookie(AUTH_COOKIE, ' ', time() - 31536000, SITECOOKIEPATH, COOKIE_DOMAIN);
	setcookie(AUTH_COOKIE, ' ', time() - 31536000, SITECOOKIEPATH, $domain);

	setcookie(SECURE_AUTH_COOKIE, ' ', time() - 31536000, COOKIEPATH, COOKIE_DOMAIN);
	setcookie(SECURE_AUTH_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, COOKIE_DOMAIN);
	setcookie(SECURE_AUTH_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, $domain);
	setcookie(SECURE_AUTH_COOKIE, ' ', time() - 31536000, COOKIEPATH, $domain);
	
	setcookie(SECURE_AUTH_COOKIE, ' ', time() - 31536000, SITECOOKIEPATH, COOKIE_DOMAIN);
	setcookie(SECURE_AUTH_COOKIE, ' ', time() - 31536000, SITECOOKIEPATH, $domain);
	
	// Even older cookies
	setcookie(USER_COOKIE, ' ', time() - 31536000, COOKIEPATH, COOKIE_DOMAIN);
	setcookie(USER_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, COOKIE_DOMAIN);
	setcookie(USER_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, $domain);
	setcookie(USER_COOKIE, ' ', time() - 31536000, COOKIEPATH, $domain);
	
	setcookie(PASS_COOKIE, ' ', time() - 31536000, COOKIEPATH, COOKIE_DOMAIN);
	setcookie(PASS_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, COOKIE_DOMAIN);
	setcookie(PASS_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, $domain);
	setcookie(PASS_COOKIE, ' ', time() - 31536000, COOKIEPATH, $domain);
	
	setcookie(USER_COOKIE, ' ', time() - 31536000, SITECOOKIEPATH, COOKIE_DOMAIN);
	setcookie(USER_COOKIE, ' ', time() - 31536000, SITECOOKIEPATH, $domain);

	setcookie(PASS_COOKIE, ' ', time() - 31536000, SITECOOKIEPATH, COOKIE_DOMAIN);
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
		global $rootcookie_admin_hook;
		
		$rootcookie_admin_hook = add_options_page('root Cookie Options', 'root Cookie ', 'manage_options' , 'root-cookie', 'rootcookie_options');
	}
function rootcookie_menu_help($contextual_help, $screen_id, $screen) 
	{
		global $rootcookie_admin_hook;

		if ($screen_id == $rootcookie_admin_hook) {
			$contextual_help = file_get_contents(WP_PLUGIN_DIR . '/root-cookie/admin-options-help.inc.php'); // the help html
		}
	return $contextual_help;
	}
function rootcookie_options ()
	{
	
		// Read in existing option value from database
		$rootcookie_subdomain_on = get_option('rootcookie_subdomain');
		$rootcookie_subdomain_manual = get_option('rootcookie_subdomain_manual');
		$rootcookie_donate = get_option('rootcookie_donate');

		$checked=false;
		if($rootcookie_subdomain_on==1){$checked=true;}
		
		$donate=false;
		if($rootcookie_donate==1){$donate=true;}
	
		// See if the user has posted us some information
		// If they did, this hidden field will be set to 'Y'
		if( $_POST['rootcookie_submit_hidden'] == 'Y' )
			{
				if(isset($_POST['rootcookie_subdomain']))
					{
						# This enables the guessing that the domain written by Scott
						$rootcookie_subdomain_on = 1;
						$checked=true;
					} else {
						$rootcookie_subdomain_on = 0;
                                                $checked=false;

						# Implement  a manual domain method for .co.uk or .co.jp etc
						if(isset($_POST['rootcookie_subdomain_manual'])) {
							$rootcookie_subdomain_manual = $_POST['rootcookie_subdomain_manual'];
							update_option('rootcookie_subdomain_manual', $rootcookie_subdomain_manual );
						}


					}
				
				if(isset($_POST['rootcookie_donate'])) {
					
					$rootcookie_donate = 1;
					$donate=true;
					update_option('rootcookie_donate', $rootcookie_donate );
				}

				
				update_option('rootcookie_subdomain', $rootcookie_subdomain_on );
				echo '<div class="updated"><p><strong>'._('Options saved.').'</strong></p></div>';

				// Re-Read Val so Form Prints Correctly.
				$rootcookie_subdomain_on = get_option('rootcookie_subdomain');
				$rootcookie_subdomain_manual = get_option('rootcookie_subdomain_manual');
			}
?>
<div class="wrap">
<?php echo "<h2>" . __( 'root Cookie Options', 'rootcookie_trans_domain' ) . "</h2>"; ?>
<form name="plugin_options" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<input type="hidden" name="rootcookie_submit_hidden" value="Y" />

<p>

<table class="form-table">

<tr valign="top">
	<th scope="row"><?php _e("Allow Cookies to go across All Subdomains:", 'rootcookie_trans_domain' ); ?> </th>
	<td><input type="checkbox" name="rootcookie_subdomain" value="<?php echo $rootcookie_subdomain_on; ?>"<?php if($checked){echo " CHECKED";} ?> /><span class="description">Tick this box and we'll try to <b>guess</b> your domain and enable the cookie.</span></td>
</tr>

<?php
if(!$checked){
?>
<tr valign="top">
<th scope="row">OR</th>
<td><!-- ... --></td>
</tr>

<tr valign="top">
      <th scope="row"><?php _e('Domain Name') ?></th>
      <td><input name="rootcookie_subdomain_manual" id="rootcookie_subdomain_manual" class="regular-text" value="<?php echo $rootcookie_subdomain_manual; ?>" /><span class="description"><b>Optional:</b> Put you domain name in here example <code>linickx.co.uk</code> and we'll set the cookie to that.</span>
      </td>
    </tr>
<?php
}
?>
</table>

</p><hr />
<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'rootcookie_trans_domain' ) ?>" />
</p>

<?php
	if ( !$donate ) {
		?>
<div style="float:right; text-align:center" >
<a href="http://www.linickx.com/donate">
<img src="<?php echo plugins_url( 'root-cookie/donate.png' , dirname(__FILE__) )?>" alt="donate" /> <br />
<small>Buy the author a beer to say thanks!</small>
</a> <br />
<small>
<input type="checkbox" name="rootcookie_donate" value="1" ><em>Tick, yep done that!</em>
</small>
</div>
<?php
	}
	?>

</form>

<?php
	# Let's tell users about RK :)
	$lnx_feed = fetch_feed('http://www.linickx.com/tag/root-cookie/feed');
	echo "<h3>Root Cookie News</h3>";
	echo "<ul>";
	if (isset($lnx_feed->errors)) { 
		echo '<li><b>Error Downloading Feed</b>. Looks like you are going to have to visit <a href="http://www.linickx.com/tag/root-cookie/feed">http://www.linickx.com/tag/root-cookie/feed</a> manually to keep up with the news! </li>';
	} else {
		
		foreach ($lnx_feed->get_items() as $item){
				printf('<li><a href="%s">%s</a></li>',$item->get_permalink(), $item->get_title());
		}
	}
	echo "</ul>";
?>
<p><small><a href="http://www.linickx.com/archives/tag/root-cookie/feed">Subcribe to this feed</a></small></p>
</div>
<?php
	}

// Run all actions and hooks at the end to keep it tidy
	
	if (is_admin()) { // only run admin stuff if we're an admin.
		add_action('admin_menu', 'rootcookie_menu');
		add_action('contextual_help', 'rootcookie_menu_help', 10, 3);
	}
	
register_activation_hook( __FILE__,'rootcookie_activate');
?>
