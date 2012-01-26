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
/*Pull Config file from iSENSE*/

define('CONF_DIR','http://' . $_SERVER['REMOTE_ADDR'] . '/includes/conf/');
$config_file = CONF_DIR;
$config_file .= ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') ? "debugging.xml" : "production.xml";
$config = simplexml_load_file($config_file);
define('DB_USER', (string) $config->database->user);
define('DB_PASSWORD', (string) $config->database->pass);
define('DB_HOST', (string) $config->database->host);
define('DB_PORT', (string) $config->database->port);
define('DB_NAME', (string) $config->database->name);
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('WP_HOME','http://' . $_SERVER['REMOTE_ADDR'] . '/blog');
define('WP_SITEURL','http://' . $_SERVER['REMOTE_ADDR'] . '/blog');

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
/*
define('AUTH_KEY',         'put your unique phrase here');
define('SECURE_AUTH_KEY',  'put your unique phrase here');
define('LOGGED_IN_KEY',    'put your unique phrase here');
define('NONCE_KEY',        'put your unique phrase here');
define('AUTH_SALT',        'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT',   'put your unique phrase here');
define('NONCE_SALT',       'put your unique phrase here');
*/
define('AUTH_KEY',         't;5j[-T,:-a13P7B#C{--y.-Q+S^/elk6rxsC(:83~O@E}-t7:|}fkmoB|YWCKu<');
define('SECURE_AUTH_KEY',  'ViM%>KWd:nc)+-,`W}s<clI{+zrY2QuvK$ufi.IbysGf?f!o}6s;Ni]t|{1+UU$;');
define('LOGGED_IN_KEY',    'trD$Atooe&RwxZTO.v,E)^gNF*~UB3MF#ua-C%J-+ZTmEdEQV6AK%DP&-LIy++<P');
define('NONCE_KEY',        'P9*v}l|V2*~PF/o1Dc(o3>h4EG_{V3ljH=p0aC{NlsUB|0-?5R/qB4IY{3i OeW|');
define('AUTH_SALT',        '3c7w>nj4OX:^)Kwxpuvazu^[6aUYE5vh8?>%$B=om4,<1ruy=xNK=?TAR2{^RK8J');
define('SECURE_AUTH_SALT', 'f++MV|:(MknOR6MR 9KaUQ=E$3|.rVZ~V6em-&[@6:@LFGde00&N;1ecEmF-yhRj');
define('LOGGED_IN_SALT',   '$S=t=#(E+c`|5=iG?;b80ux5Pa]*^z[~Ar)+[`JE]]5H=2|Z[3}8*H1`,StO{-P{');
define('NONCE_SALT',       'CC4m:< DQbk5A:>~:E3h+5kFD%2;JjeTjPAxZh6/@Ibh%V}f-e{0Nsq==ScLb2Hb');

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
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de.mo to wp-content/languages and set WPLANG to 'de' to enable German
 * language support.
 */
define ('WPLANG', '');

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
