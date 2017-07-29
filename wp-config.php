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
define('DB_NAME', 'grupo_conexao');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         '(?>i?{?]lCP,3ibnrDE1[/8>R$7%%cNVt84G_G`>.$dL})Ums?sI+U$.7$iUh;`^');
define('SECURE_AUTH_KEY',  '~k;Vujb}Ux$h$TvhmNR1DWHEq0h|mw>T[)9Ne*:wI]?]<!XV/z8HVjIFC|N~9u.h');
define('LOGGED_IN_KEY',    'JIq,vb<ZhWn8aJm4*h.=M5c<IjaYuE@rq{%!p+M>@[*D<wp0n`;3KmxGn*4|?aCB');
define('NONCE_KEY',        'Y oB,&RTXvL>0=bUzX@7S|Da)dx?]F@o|/E~Suf0G=}*q]ht7.(% SBPSzByB4Y;');
define('AUTH_SALT',        '3{j3/8Icctt]GF?8@8>)~0CRTT@JnsV!V&#;6wT4#rjA][H9QZ.w;@hV<Tn}w fK');
define('SECURE_AUTH_SALT', '?Hr7kwz-`n 5,S)7JI#4%J}un BkNR^uZg?&,QC:Fj9*(A)2n/N@l4Lt9kQgFK77');
define('LOGGED_IN_SALT',   '07v9eRFFW,^>0fW3oNc`r-b`|zpp_v% ER|;{~A7@[llrBhGD5&?&K8^->;/%&_+');
define('NONCE_SALT',       'mujzX_O-_Fclh2Nk?.)c/X4jG`-A#4TnMvSmflO*bL|~&g^UH%|+SptqNnK[.q{[');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'gc_';

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

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
