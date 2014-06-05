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
define('DB_NAME', 'opentech_blog');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

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
define('AUTH_KEY',         'e&AC~fDN^asSnV^z`r##E{@n#E5CC9Ymm5yq<Ok3~i9:/`w74!E}xOsdh^clxU6c');
define('SECURE_AUTH_KEY',  'c*LGzX3iz|_grnmCt|d?%dpN:rR(a(W@R@e_1plu@9~IMiX^_lh-R(=r]UxtJ3?f');
define('LOGGED_IN_KEY',    '*J|EMQnI >&}R.+eqES$2}|+We)2`/G:M%b/3YvC|~EQY4UZ3>T<hqY!;DdJ!FdF');
define('NONCE_KEY',        '^+]bn{,z 2D!2^6rH*>evg&~m.9q]3?(I+/0IlyndpD9k,!RX3C/s~N%ELpa+9 *');
define('AUTH_SALT',        's|Mtx*8J^b/xruvYe5xMz(ZyPW4ZI5+?FE?j`*BK5XTtrH}OU!O8[{El>i#ZQKGD');
define('SECURE_AUTH_SALT', 'kb=]|$lo ZDoA|+&o<BkXsB6;a_7$~j~i:7_~!=kT_Ytzld!v|q.Xjdd~_h1XCr%');
define('LOGGED_IN_SALT',   'Pnw*(km^T&qsx-3hBnevXj$(&6_?bbLy I/k;z-lG+Z^TcO<K|dOv7,=v95GlORu');
define('NONCE_SALT',       '%eLdS.]^UfQyK@GK+X2)xR/$@A-fg$-zOX%|1^~+ LEX_A%]>{AB4w#o])6&ieb|');

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
