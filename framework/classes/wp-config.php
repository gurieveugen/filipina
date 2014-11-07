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
define('DB_NAME', 'filipina');

/** MySQL database username */
define('DB_USER', 'filipina');

/** MySQL database password */
define('DB_PASSWORD', 'giuseppebello2014');

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
define('AUTH_KEY',         '.(WBgg~Fz:Sn>MR+P};Xof/8(2YZ|O_g?4ekV|A`;%DH!m@6TL][-+`fHXiebL)L');
define('SECURE_AUTH_KEY',  'e+m/@ZM3s+wSJ-<XbJEbusYRc#-2I)<%^}E+1/c7EFu0M6Xt}MrG$Dz8-ya ihLA');
define('LOGGED_IN_KEY',    'O^(89vF6:sHJad(DkHDfz{la5$0qP>S+L[uP8LUDa}fq_/XfF+{D]8B^& 7&SMjU');
define('NONCE_KEY',        'Hz%{VJ+@V+ui}+6NDfU,cTb1ScYAC)DEm<5UMY;p^AcKWOh:WeuD;8xjAzTurp^v');
define('AUTH_SALT',        'Gd0!{j%uhr:WeIf#jQK&?a1u!fh_/tM#V/=5/0YXkn*O2???<O{Ydlsk]z](UC`+');
define('SECURE_AUTH_SALT', 'y5:W|2gK7J$z|$u&m:s?pqk`]A).GpD~*StRJYcI0drlfbC[d0cL>a2>no;}Py:1');
define('LOGGED_IN_SALT',   'yYM&]D]A]eQLoRz0-F4mXDG,gjdk4+Pcx$7hI9vjk(i+{y+HB-n4>K@>}=[%g|9{');
define('NONCE_SALT',       'T)m+}HjQH}WU)+a|B@HK:QvlwVt*nX?8{M!m_u$C=G-_s<aZL*Ga+7*ej;xVAGRM');

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
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
