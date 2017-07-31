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
define('DB_NAME', 'wordpress');

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
define('AUTH_KEY',         ']loAxunxu)m+anxxzePk:sK.!qNL&_]qETQ@K)+SYgR*K%nT$9G-N;ABt5,n{aQ~');
define('SECURE_AUTH_KEY',  'RlB LJ9s& uTQKsuc|F_nT)R8$Xb<_R@5ePqQM^+qiLhM=}}%^+{]ChuE;yPPnj1');
define('LOGGED_IN_KEY',    '$+TSjI78MA,p/`Obhsww.K)g)6)[t=!q536o)D+ntSTIO:}0/l%cs_[h2rM@(Z(`');
define('NONCE_KEY',        'xb:9Z[Dd@PEL9*-o<wY0;:v7g~q*af@E/YYyZ7hoRvzknu?U]zCIPMYkgZKJG&>1');
define('AUTH_SALT',        ';x0_0L;Y6qA(}FU(3@O]Ba=)7k!X0fVQv/@Zdl4=;XYYeDv??0{j{j)XQ1Oian|z');
define('SECURE_AUTH_SALT', '!_s$qo;8]]T#FNGy8,S]v}QTVpVcx=XV$KLg;HO-<7&3*P8@yLL(2:gCe?3TT) L');
define('LOGGED_IN_SALT',   '*l7l(o=tC.cc2dYZ dX|i=!DiW}Y)B/Q(b5HbY|cDsnZ?Gg~+y9UM_H;`;P3$2gH');
define('NONCE_SALT',       'vtVito5uIHIWz@<Nkr.Bn9jNLCa0h=V8mK7.*A_9AfhJO5$2*0dmQqfOK<EO/#j7');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
