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
define('DB_NAME', 'davedub_alpha');

/** MySQL database username */
define('DB_USER', 'davedub_alpha');

/** MySQL database password */
define('DB_PASSWORD', '_alpha#2013');

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
define('AUTH_KEY',         ')qwq6djfwD^&&vgHJCv-!jLoj!ZEz#T,Bx3MRLr0T9q/^ul!(->At4T?H|{VL|8:');
define('SECURE_AUTH_KEY',  'S^1NI*vIO=n>HBT@!+YBno/JS-:d7+ 5X@rh$@l[RzW#N@|S{DFM|qb]d2 z6(<7');
define('LOGGED_IN_KEY',    'P>u5P%G`+n&Y3+qV7l8*KgA 0+ vSVMEgl-Ta)wM)6KgX7Hxna8}:ppiaSqNVTlu');
define('NONCE_KEY',        '5T)P_M_-iDm`DZ%-pgzFy$L]^uJV(lzc#pD3]}|R2wk74VrgZ+i[P+k^2&LPx$]G');
define('AUTH_SALT',        'Jx#b<7~cB7T&3(3zkNxro@p#^c&w3+lZgf L+3{t.(492$;#6[)_eU<.L4v-,o=8');
define('SECURE_AUTH_SALT', 'a+rxJ!S%TEBWnI4<gcQ%<b!!y<!j4.a|T|<euH{CD@;7&ijx>Ocd#tfdMa^C<+Zu');
define('LOGGED_IN_SALT',   'Uc*K%=`?&ihG=MFt{]S0(JbV2JgUQ?$|-rwDt=tdvk]/^j[|C|cpFWe(Vdrt*HM2');
define('NONCE_SALT',       '[e,!kD?8?8K~I5d3dw-2N~+F3Ny?Ft-F~y]ECb>Z1mO,B*RpIJk.zyP$ieydNS78');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'dda_';

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
