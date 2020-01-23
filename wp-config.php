<?php
define('WP_CACHE', false); // Added by WP Rocket
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
/**
*  The name of the database for WordPress
*/
define('DB_NAME', 'dev_wp1');
/**
*  MySQL database username
*/
define('DB_USER', 'dev_wp1');
/**
*  MySQL database username
*/
define('DB_PASSWORD', 'u9GXdqRYlg');
/**
*  MySQL hostname
*/
define('DB_HOST', 'localhost');
/**
*  Database Charset to use in creating database tables.
*/
define('DB_CHARSET', 'utf8mb4');
/**
*  The Database Collate type. Don't change this if in doubt.
*/
define('DB_COLLATE', '');
/**
*  WordPress Database Table prefix.
*  You can have multiple installations in one database if you give each a unique
*  prefix. Only numbers, letters, and underscores please!
*/
$table_prefix = 'wp_';
/**
*  when you want to secure logins and the admin area so that both passwords and cookies are never sent in the clear. This is the most secure option
*/
//define('FORCE_SSL_ADMIN', true);
/**
*  disallow unfiltered HTML for everyone, including administrators and super administrators. To disallow unfiltered HTML for all users, you can add this to wp-config.php:
*/
define('DISALLOW_UNFILTERED_HTML', false);
/**
*  
*/
define('ALLOW_UNFILTERED_UPLOADS', false);
/**
*  The easiest way to manipulate core updates is with the WP_AUTO_UPDATE_CORE constant
*/
define('WP_AUTO_UPDATE_CORE', true);
/**
*  Authentication Unique Keys and Salts.
*  Change these to different unique phrases!
*  You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
*  You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
*  @since 2.6.0
*/
define('AUTH_KEY', '-Kw>Hja/V{?y.n#Pk{@)w5p,2`I0b7E+2_@9wCDg27Q<@DNN<eJR418RC@MryoYF');
define('SECURE_AUTH_KEY', 'P 7v@ rr*KDFr(!4[aXzN%!i7hkzEo>A]X[IzIo+ntsCUhI&a{L|a/$i0k}8g(, ');
define('LOGGED_IN_KEY', '? N=_d|@q,]~m>ik(<g,_dWnbUketsI}PB77kf)Iu h3|ut:GWCD^u>{E3cG4Yyx');
define('NONCE_KEY', 'ZP|3YT=>GCM$hOyt,oA]7/btb[nZ;0;EreX|,p~F1dmT]>_J>L7qu,]OPh/Muhn+');
define('AUTH_SALT', '72<#(mwm{[R^]Uc&LdAp-9,3q4kQ=]!(`y!_R]A|Anbsbz^i>U!BXfDdgM^nNX^y');
define('SECURE_AUTH_SALT', 'hp.rj99[1tOi=]}8%}1$mXVOdvzgU,4$t>[TXUU4V7=D_bY#v^22I7&@,hVKG1Jz');
define('LOGGED_IN_SALT', 'B5HH]cG&5=2Oqt-BS}([1.!NpCFxObd:a!JnWD4v5@h~g)*>7g3:]h,oSn)b2$2?');
define('NONCE_SALT', 'c>rMFFjQQ-Dqk*@g:Q z:o)BmCHi%9dFed]Xj*c1%^:#$`q&)R/qul&;a#xFpij;');
/**
*  For developers: WordPress debugging mode.
*  Change this to true to enable the display of notices during development.
*  It is strongly recommended that plugin and theme developers use WP_DEBUG
*  in their development environments.
*/
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);
/**
*  For developers: WordPress Script Debugging
*  Force Wordpress to use unminified JavaScript files
*/
define('SCRIPT_DEBUG', false);
/**
*  WordPress Localized Language, defaults to English.
*  Change this to localize WordPress. A corresponding MO file for the chosen
*  language must be installed to wp-content/languages. For example, install
*  de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
*  language support.
*/
define('WPLANG', '');
/**
*  Setup Multi site
*/
define('WP_ALLOW_MULTISITE', false);
/**
*  Memory Limit
*/
define('WP_MEMORY_LIMIT', '400M');
/**
*  Max Memory Limit
*/
define('WP_MAX_MEMORY_LIMIT', '2560M');
/**
*  Post Autosave Interval
*/
define('AUTOSAVE_INTERVAL', 60);
/**
*  Disable / Enable Post Revisions and specify revisions max count
*/
define('WP_POST_REVISIONS', true);
/**
*  this constant controls the number of days before WordPress permanently deletes 
*  posts, pages, attachments, and comments, from the trash bin
*/
define('EMPTY_TRASH_DAYS', 30);
/**
*  Make sure a cron process cannot run more than once every WP_CRON_LOCK_TIMEOUT seconds
*/
define('WP_CRON_LOCK_TIMEOUT', 60);
/* That's all, stop editing! Happy blogging. */
/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
define( 'WPMS_ON', true);
define( 'WPMS_SMTP_PASS', 'hej12354' );