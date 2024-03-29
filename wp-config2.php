<?php
/**
	* The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'komandov1');

/** MySQL database username */
define('DB_USER', 'root'); 

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'L|q^K]ii}*LCrb!M)/cUokS([N-&rMjWpyydsy^>QnH-&&n<Dt=}JkxHucUVVhuWq[nxsHN_zNNDE!r@};hV&M)TM{BXQuYy(^%m!?!@GBxKkChUU{|teGj@Mq?+rdAA');
define('SECURE_AUTH_KEY', '+pIAYU$wi(PaW{g|=Cb|&KAcqGH%|a-gbThpd_O]n_oaKB^&)gEmnWS/[IfzrNo*(C|Ghh!Gv+N%vOeWz$/oq>eR{Towi(s;IHyCFX(TS{dWk*fhQu+tJgf=}rfABLUW');
define('LOGGED_IN_KEY', 'tgG(&<}GJ@+ayq*mvcqL/cU<tx^tkmmIxnDT={nddlov?+GJj&$r>@JXNq]m$U>Ta/ZXxuG)/yIyHtyqufXIWkJT((WWKNB*/BVcLAr!!l(V+PBG/CEs<FqlMmpYGY=/');
define('NONCE_KEY', 'haJMZfW%tO-_?iQ?U&u]lUDO/o(vmw(un&pOuR@!E)oon/Lc<kia!Um^UbS/]vnEO!Rt!}{dK=&BYMQcfI<|-_*!K|tetMbii?hh=}k[N<X/xg&JpM}zGo<FV?j&CKlS');
define('AUTH_SALT', 'V$aRB%Q?KPUM@EGz$QA{pr=WrCceKG%XelJv$LTBgntsG%(ecz?-WdMERhR(tuWDQXmV}*=_b}[ih<WC){lmvX]cZuK)So_^!w[PSGxwd&B);nkL!_Vl}QsL}KHmcNbg');
define('SECURE_AUTH_SALT', 'tR%ZysbsfX{qh(M;o>IDFuWgfk^O!k)xcd(BP/*!%O[g-}s{G}YN<FGF=f]^Je}Hc_/gZi!V[*tJu*fJ{&)c|*<=!@tK$ip=il|>]Wk=J*zca{&-*!exIvjuB>p?H;/F');
define('LOGGED_IN_SALT', 'MKg@@@sPTFp{l(Bp^iMp;]RBi!+|!^}x@AoE_BQq{aIqk*IM|%GfGAL*TYRxMChywX)|E$VVzLC;ViACFHV$$B{{ioN}|nYTXUbSVuj];dK)@HfepCH[r[mYAKpCMvie');
define('NONCE_SALT', 'i_}XCTM+oG]B_ww;Vw^[kaSp|>P<[M;zaCKLcrlQW%EVV-sI<?GahNEey_vNLk^r{>J-SKWhwKgWT{W(HxFb!$!jUkwSZ+Cw!I*I|b]<g_*eTDAu_HCeUkNWXYg;{ZJW');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);
////////////////////Custom////////////////////////////
define('WP_MEMORY_LIMIT', '256M');

//Custom Configuration
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);
define( 'WP_POST_REVISIONS', false );

$subfolder = '/prj/raghav/komandov1/';

define('WP_HOME', 'http://' . $_SERVER['HTTP_HOST'] . $subfolder);
define('WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST'] . $subfolder);
define('DOMAIN_CURRENT_SITE', $_SERVER['HTTP_HOST'] . $subfolder); 
define('SUB_DIR','');
define('HTTP_STR','http://');
//////////////////////Custom//////////////////////////

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
