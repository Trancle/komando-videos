<?php
/*
Plugin Name: CAS Authentication
Version: 2.3.1
Plugin URI: http://github.com/sakuraiyuta/wordpress-cas
Description: This plugin is a modification of <a href="http://wordpress.org/extend/plugins/cas-authentication/">&quot;CAS Authentication plugin&quot; written by candrews, sms225</a>.
Author: Yuta Sakurai
Author URI: http://github.com/sakuraiyuta/wordpress-cas
License: GPLv2
 */

/* Copyright (C) 2010 Yuta Sakurai <sakurai.yuta@gmail.com>

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA */

add_action('admin_menu', 'cas_authentication_add_options_page');

$cas_authentication_opt = get_option('cas_authentication_options');

$cas_configured = true;

// try to configure the phpCAS client
if($cas_authentication_opt['include_path'] == '' || (include_once $cas_authentication_opt['include_path']) != true) {
    $cas_configured = false;
}

if($cas_authentication_opt['server_hostname'] == '' || intval($cas_authentication_opt['server_port']) == 0) {
    $cas_configured = false;
}

function initializeCas() {
    global $cas_authentication_opt, $cas_configured;
    if($cas_configured) {
        phpCAS::client($cas_authentication_opt['cas_version'], 
            $cas_authentication_opt['server_hostname'], 
            intval($cas_authentication_opt['server_port']),
            $cas_authentication_opt['server_path']);
         phpCAS::setCasServerCACert('/etc/ssl/certs/ca-certificates.crt');
    }
}

// plugin hooks into authentication system
add_action('wp_authenticate', array('CASAuthentication', 'authenticate'), 1, 2);
add_action('wp_logout', array('CASAuthentication', 'logout'), 1);
add_action('lost_password', array('CASAuthentication', 'disable_function'), 1); // may not be needed
add_action('retrieve_password', array('CASAuthentication', 'disable_function'), 1); // may not be needed
add_action('password_reset', array('CASAuthentication', 'disable_function'), 1); // may not be needed
add_action('allow_password_reset', array('CASAuthentication', 'disable_function'), 1);
add_filter('show_password_fields', array('CASAuthentication', 'show_password_fields'), 1);
add_filter('login_url', array('CASAuthentication', 'bypass_reauth'), 1);
//add_filter('auth_cookie_expiration', array('CASAuthentication', 'change_cookie_expire'), 1);

if(!class_exists('CASAuthentication')) {
    class CASAuthentication {

        function in_admin() {
            $redirect = $_POST['redirect_to'];

            if(empty($redirect)) {
                $redirect = $_GET['redirect_to'];
            }

            if(empty($redirect)) {
                return true;
            }

            if (strpos($redirect, site_url('/wp-admin/')) !== false) {
                return true;
            }

            return false;
        }

        function authenticate(&$uuid, &$password) {

            global $using_cookie, $cas_authentication_opt, $cas_configured;

            initializeCas();

            if(!$cas_configured) {
                die("cas-authentication plugin not configured");
            }

            if(self::in_admin() != true) {

                $auth_check = phpCAS::isAuthenticated();

                if($auth_check != 1) {
                    if( $_GET['auth'] == 'check') {
                        $auth_check = phpCAS::checkAuthentication();
                    } else {
                        $auth_check = phpCAS::forceAuthentication();
                    }
                }

                if($auth_check == 1) {
                    $user_email = phpCAS::getUser();
                    $k2_get_user_information = k2_get_user_information($user_email);
                    $uuid = $k2_get_user_information->uuid;
                    $k2_get_membership_status = k2_get_membership_status($uuid);
                    $k2_kvo_get = k2_kvo_get($uuid);
                    $k2_get_username = k2_get_username($uuid);
                    $password_gen = $uuid . 'D8jC@3qMtwClT-HJEW!hyp@W95P-z7';
                    $password = md5($password_gen);


                    if($k2_get_membership_status->level == 'premium') {
                        $user_role = 'premium_member';
                    } else if($k2_get_membership_status->level == 'basic') {
                        $user_role = 'basic_member';
                    } else {
                        $user_role = 'subscriber';
                    }

                    if (!function_exists('get_user_by')) {
                        die("Could not load user data");
                    }

                    $user = get_user_by('login', $k2_get_user_information->uuid);

                    if($user) {
                        // user already exists

                        update_user_meta($user->ID, 'user_email', $user_email);
                        update_user_meta($user->ID, 'first_name', $k2_kvo_get->kvos->first_name);
                        update_user_meta($user->ID, 'last_name', $k2_kvo_get->kvos->last_name);
                        update_user_meta($user->ID, 'cas_username', $k2_get_username->username);
                        update_user_meta($user->ID, 'membership_expiration', $k2_get_membership_status->expires_at);

                        /**
                         * This is done in reference to issue #3113
                         * We need to synchronize the password from Club to Wordpress
                         * If not, and the user has somehow reset the password only on the Wordpress side,
                         * the user is redirected to the Wordpress login page instead of their club account after logging in
                         */
                        wp_set_password($password, $user->ID);

                        $u = new WP_User($user->ID);
                        $u->remove_role(get_user_by('login', $k2_get_user_information->uuid)->roles[0]);
                        $u->add_role($user_role);

                        wp_set_auth_cookie($user->id, 'true');

                        return true;

                    } else {
                        // first time logging in

                        if($cas_authentication_opt['new_user'] == 1) {
                            // auto-registration is enabled

                            // User is not in the WordPress database
                            // they passed CAS and so are authorized
                            // add them to the database

                            $user_info = array();
                            $user_info['user_login'] = $k2_get_user_information->uuid;
                            $user_info['user_pass'] = $password;
                            $user_info['user_email'] = $user_email;
                            $user_info['first_name'] = $k2_kvo_get->kvos->first_name;
                            $user_info['last_name'] = $k2_kvo_get->kvos->last_name;
                            $user_info['role'] = $user_role;
                            $user_id = wp_insert_user($user_info);
                            
                            update_user_meta($user_id, 'cas_username', $k2_get_username->username);
                            update_user_meta($user_id, 'membership_expiration', $k2_get_membership_status->expires_at);
                            update_user_meta($user_id, 'account_creator', 'CAS');
                        }
                    }

                } else {
                    $redirect = $_POST['redirect_to'];

                    if(empty($redirect)) {
                        $redirect = $_GET['redirect_to'];
                    }
                    $redirect = $redirect . '?auth=checked';
                    header("Location: $redirect");
                }
            }
        }

        function change_cookie_expire() {
            return 2419200; // 28 days
        }

    /*
     We use the provided logout method
     */
        function logout() {
            
            wp_clear_auth_cookie();
            setcookie( 'PHPSESSID',        ' ', time() - YEAR_IN_SECONDS, '/',   COOKIE_DOMAIN );

            global $cas_configured;
            initializeCas();

            if(!$cas_configured) {
                die("cas-authentication not configured");
            }

            phpCAS::logoutWithUrl(get_settings('siteurl'));
            exit();
        }

        /*
         * Remove the reauth=1 parameter from the login URL, if applicable. This allows
         * us to transparently bypass the mucking about with cookies that happens in
         * wp-login.php immediately after wp_signon when a user e.g. navigates directly
         * to wp-admin.
         */
        function bypass_reauth($login_url) {
            initializeCas();

            if(self::in_admin()) {
                $login_url = remove_query_arg('reauth', $login_url);
                return $login_url;
            }
        }

    /*
     Don't show password fields on user profile page.
     */
        function show_password_fields($show_password_fields) {
            global $profileuser;
            $user_id = $profileuser->ID;
            $user = get_user_meta($user_id);

            if(isset($user['account_creator']) && $user['account_creator'] == 'CAS') {
                return false;
            } else {
                return true;
            }
        }

        function disable_function() {
            $login_req = $_POST['user_login'];
            $user_details = get_user_by('login', $login_req);

            $user_id = $user_details->ID;
            $user = get_user_meta($user_id);

            if(isset($user['account_creator']) && $user['account_creator'] == 'CAS') {
                $home = site_url();
                header("HTTP/1.1 403 Forbidden");
                header("Location: $home");
                exit();
            } else {
                return true;
            }
        }
    }
}

//----------------------------------------------------------------------------
//      ADMIN OPTION PAGE FUNCTIONS
//----------------------------------------------------------------------------

function cas_authentication_add_options_page() {
    if(function_exists('add_options_page')) {
        add_options_page('CAS Authentication', 'CAS Authentication', 8, basename(__FILE__), 'cas_authentication_options_page');
    }
} 

function cas_authentication_options_page() {
    global $wpdb;

    // Setup Default Options Array
    $optionarray_def = array(
        'new_user' => FALSE,
        'redirect_url' => '',
        'email_suffix' => 'yourschool.edu',
        'cas_version' => CAS_VERSION_1_0,
        'include_path' => '',
        'server_hostname' => 'yourschool.edu',
        'server_port' => '443',
        'server_path' => ''
    );

    if(isset($_POST['submit'])) {    
        // Options Array Update
        $optionarray_update = array (
            'new_user' => $_POST['new_user'],
            'redirect_url' => $_POST['redirect_url'],
            'include_path' => $_POST['include_path'],
            'cas_version' => $_POST['cas_version'],
            'server_hostname' => $_POST['server_hostname'],
            'server_port' => $_POST['server_port'],
            'server_path' => $_POST['server_path']
        );

        update_option('cas_authentication_options', $optionarray_update);
    }

    // Get Options
    $optionarray_def = get_option('cas_authentication_options');

?>
    <div class="wrap">
    <h2>CAS Authentication Options</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?page=' . basename(__FILE__); ?>&updated=true">
    <fieldset class="options">

     <h3>User registration options</h3>
    <table width="700px" cellspacing="2" cellpadding="5" class="editform">
       <tr>
       <td colspan="2">Checking <em>Auto-register new users</em> will automatically create a new user (with role of Subscriber) upon successful login of a new visitor to the site.</td>
       </tr>
       <tr valign="center"> 
       <th width="200px" scope="row"><input name="new_user" type="checkbox" id="new_user_inp" value="1" checked="<?php checked('1', $optionarray_def['new_user']); ?>" /> Auto-register new users?</th> 
       </tr>
    </table>

    <h3>phpCAS options</h3>
    <p>Note: Once you fill in these options, wordpress authentication will happen through CAS, even if you misconfigure it. To avoid being locked out of Wordpress, use a second browser to check your settings before you end this session as administrator. If you get an error in the other browser, correct your settings here. If you can not resolve the issue, disable this plug-in.</p>

    <h4>php CAS include path</h4>
    <table width="700px" cellspacing="2" cellpadding="5" class="editform">
                <tr>
                <td colspan="2">Full absolute path to CAS.php script</td>
                </tr>
        <tr valign="center"> 
        <th width="300px" scope="row">CAS.php path</th> 
        <td><input type="text" name="include_path" id="include_path_inp" value="<?php echo $optionarray_def['include_path']; ?>" size="35" /></td>
        </tr>
    </table>    

    <h4>phpCAS::client() parameters</h4>
    <table width="700px" cellspacing="2" cellpadding="5" class="editform">
        <tr valign="center"> 
            <th width="300px" scope="row">CAS verions</th> 
            <td><select name="cas_version" id="cas_version_inp">
                <option value="2.0" <?php echo ($optionarray_def['cas_version'] == '2.0')?'selected':''; ?>>CAS_VERSION_2_0</option>
                <option value="1.0" <?php echo ($optionarray_def['cas_version'] == '1.0')?'selected':''; ?>>CAS_VERSION_1_0</option>
             </td>
        </tr>
        <tr valign="center"> 
            <th width="300px" scope="row">server hostname</th> 
            <td><input type="text" name="server_hostname" id="server_hostname_inp" value="<?php echo $optionarray_def['server_hostname']; ?>" size="35" /></td>
        </tr>
        <tr valign="center"> 
            <th width="300px" scope="row">server port</th> 
            <td><input type="text" name="server_port" id="server_port_inp" value="<?php echo $optionarray_def['server_port']; ?>" size="35" /></td>
        </tr>
        <tr valign="center"> 
            <th width="300px" scope="row">server path</th> 
            <td><input type="text" name="server_path" id="server_path_inp" value="<?php echo $optionarray_def['server_path']; ?>" size="35" /></td>
        </tr>
    </table>
    </fieldset>
    <p />
    <div class="submit">
        <input type="submit" name="submit" value="<?php _e('Update Options') ?> &raquo;" />
    </div>
    </form>
<?php
}
?>