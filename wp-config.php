<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp_booking_hotel' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'OZ]Nt$GOfUExOzr$xNs4%5gjS}FNAZ9-h]$!3kw%^Cb5DpSV0hzk`Mi+@w*cXjng' );
define( 'SECURE_AUTH_KEY',  'kQ`FkZd_yB!XPg5w_M>JSM^6Hk>gB]29XCI(SQjXCQ}PNZjV@ONaW[t#,=ba9x0}' );
define( 'LOGGED_IN_KEY',    'lBM;H>O};><t1iFW3tFv*}/iU8.^?~/529BdWJ]:/.e.z/w>Z`i]0Ab[Fnj%c$Bl' );
define( 'NONCE_KEY',        '|I]|J6)dxviQ7=z3(?=i2ACKH,zz:O!LiF8:zGw8uNW7!J5S$Bf7)YuB9Sk-;p$q' );
define( 'AUTH_SALT',        '{|Y`z16Bb$:)!J+@>p<1:eGjtX?`]a#>&Sxai0dgu0>X_Zv.a|mH&vWp|<47kBxX' );
define( 'SECURE_AUTH_SALT', 'Np%Zrll}3L!cp{J|;rM(z!G_s3(KQIL$Xh-8ZF7h+b;fTg%vpu0%Z{O/ap3n,doz' );
define( 'LOGGED_IN_SALT',   '?Pc(Zy~0*BgEpS<.&5.TtX!i;1?sll%Fy1azy=L9@~5=u Nin_qBU=F?0(?.7nR7' );
define( 'NONCE_SALT',       'fIzamo.`mBjA!~h[@!epVS,c`TgOxi?bQ9hVZVxoMDPxC8a+C>J@su4qsamVB-[H' );

/**#@-*/

/**
 * WordPress database table prefix.
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
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
