<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'www_tyger');

/** MySQL database username */
define('DB_USER', 'tyger');

/** MySQL database password */
define('DB_PASSWORD', 'O^4^IRmwf~W6KT5z');

/** MySQL hostname */
define('DB_HOST', 'sql007.weststar.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/** production, development */
//define('SERVER_ENVIRONMENT', 'production');
define('SERVER_ENVIRONMENT', 'development');
define('SKIP_ACCELERATOR', true);

define('PRODUCTION_ASSETS', false); // Set this false if you are on a local machine
//define('LOCAL_ASSET_PATH', 'http://localhost-assets.komando.com:8001'); // Path to local assets (IGNORED IF PRODUCTION_ASSETS is true)
define('LOCAL_ASSET_PATH', 'http://static.komando.com/websites/common/v2'); // Path to local assets (IGNORED IF PRODUCTION_ASSETS is true)

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'XDa-};. ]#n+@Vsjun/!Tk>nlf`@&O.@zgP%9T!RVg*WF#E`{nZG`pt-g3btw6O1');
define('SECURE_AUTH_KEY', 'a3QwX0/HaIk^E+HfR?))bqUQ<A<3t&Fs6/A4#w@B]hp+Z^=:/UW6}k-Mc{1 /d1J');
define('LOGGED_IN_KEY', 'a0kLx$}6(um0waXnA/!8?$+#4|%yPug=~t^1-pFf$ |I,:Wn5-{tERlcvfjG)I-.');
define('NONCE_KEY', '|II<6U_[ry+2>+&0%ST-o<a-3buJP+:>zC|>+%aOuGa%RKzVcg-Jj`==)GDbQ9X#');
define('AUTH_SALT', '-MQrGSZ~9Fj@1li#cjG6Cj;$EKX4;u#b4(7/3Q#js,B)Zcj7tt.)<jDAzW=@IZW-');
define('SECURE_AUTH_SALT', 'U`;!7=s`r0C];_m0CB{4&9-wa%JIA2jCgS!#*j`?0&H_M[9qQ6|:AXXfbFLgJ(Ih');
define('LOGGED_IN_SALT', 'QfT77_xl+|7e[5XMD;3XJv.>=q#1,t^f:gyVV1{-/Htht-lbLrR3P#~_02Gd>;}n');
define('NONCE_SALT', '5x|-Q&`YTQQw_d@Mxgs5O`H*SR0x4/q,8U12m^-*-[E&C}Vp`(7C_~BJL)S[V/n<');

/** CAS Defines */
define('USERAPI_URL', 'http://userapi-stg1.komando.com');
define('USERAPI_KEY_ID', '0c9115cc-c003-4a8a-928e-20a7b0b0888b');
define('USERAPI_KEY_SECRET', 'c7f21a8605e4094e484cb124324947fe621cb184cf73c061f6792f305e47713aaf1f3b7304b61b3629e0f4fbc61bcad92725288d4e7635932624d2a0ceffddb0b6af521b8e080bb8b81eb7776dd866549e1ea4fce24734826dbb29b4d65892f5a9fc32ee7ff67eab802d207e91ce439ce2e46f17a2beac89d4908aa7babc1cf14edffee38b8cedeacd499deb488274c0783f31f77a83c7ffbe78963c33eff10526bb39905db40b68ea578c18eba6d663d5422973a8e6de056742e14dc07dcbeeb50fe12118f09ba73f99aa647f33359ea5c44bf6a1a2c642936c093c55ee5eec870ee161133139a3b0732973cf3eba2c19d53a9ca19dcc23fc81360bd433299e');

/** Podcast Defines */
define('PODCAST_SHOW_TOKEN_ID', '1');
define('DIGITAL_MINUTE_SHOW_TOKEN_ID', '2');
define('PODCAST_URI', 'http://podcast.komando.com');
define('PODCAST_SHARED_SECRET', '7852797a2aa1a73241e8951a3722a4640e23e3a16cc02aa2d0b6be986b180426');
define('DIGITAL_MINUTE_SHARED_SECRET', '6c224277fcff12d18b4031932d5ef8de1276fe3b59c26b0793b858bf440f9de1');

define('TWITTER_CONSUMER_KEY', 'fjMQ0Orv5PukoGPMVWvslA');
define('TWITTER_CONSUMER_SECRET', 'VlLCqG2FlNToVcauI3DTuwFuXkPPc3BTn5r79gqfk');
define('TWITTER_ACCESS_TOKEN', '22194235-d7KvsbUj0igdU7b49NUEFo6H0Acl6XuTtzfoH4KRu');
define('TWITTER_ACCESS_TOKEN_SECRET', 'n9Tt93OkqTjlh4nd3SJB1P7saNRxdijTzqQOZs5VXuAdr');

/** Google Analytics API Defines */
define('GOOGLE_CLIENT_ID', '362204809071-vebmhmvcs7761dp78p5000o9fclfauk7.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', 'oJJZ7eMqPBngp8N-GPvA7Ddo');
define('GOOGLE_REDIRECT_URI', 'http://www-stg1.komando.com/wp-content/plugins/k2-google-analytics/k2-google-analytics.php');
define('GOOGLE_DEVELOPER_KEY', 'AIzaSyAal_CjPkIsfJgJ2rPHsJMzJmWUFEji60Y');
define('GOOGLE_REFRESH_TOKEN', '1/U6e_heMTJ2RHWOYl7DK39DQZz7jaqZ8jgBA67UoH7JY');
define('GOOGLE_HOSTNAME', 'www.komando.com');


define('CACHE_UPDATE', 'stg1-201404281526');

/** BitGravity Secret for Secure URLs */
define('BITGRAVITY_SECRET', '4bd9b8ce3ff65da');


/** Misc URIs */
define('CLUB_BASE_URI', 'https://club-stg1.komando.com');
define('SHOP_BASE_URI', '//shop-stg1.komando.com');
define('VIDEOS_BASE_URI', '//videos.komando.com');
define('AUTH_BASE_URI', '//auth-stg1.komando.com');
define('FORUM_BASE_URI', '//forum-stg1.komando.com');
define('NEWS_BASE_URI', '//news.komando.com');
define('STATION_FINDER_BASE_URI', 'http://station-finder.komando.com');
define('AFFILIATES_BASE_URI', '//affiliates.weststar.com');


define('ANTENNAWEB_HOST', 'komando.antennaweb.decisionmark.com');


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
    define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');