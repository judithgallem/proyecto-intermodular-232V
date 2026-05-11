<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */
// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress_db' );

/** Database username */
define( 'DB_USER', 'wordpress_user' );

/** Database password */
define( 'DB_PASSWORD', 'wp_password' );

/** Database hostname */
define( 'DB_HOST', 'wordpress-back' );

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
define( 'AUTH_KEY',         '@M^^`A7eZB}|Q4N-9C$Mmr6Rd4W&Z*vi2%QJfzxN?dz.h0BSUTgW#s w_R!o~<N<' );
define( 'SECURE_AUTH_KEY',  'W)35:L %4Ap1kUs.wJa^bZt4WakFJMiUpRFk+gbC]okupcVmV8PT85>WMb1AE#,x' );
define( 'LOGGED_IN_KEY',    ')f:@)-,}(>M8OyX~b9x#EVn9o%-[1J2^n?TT=u0)8zZ aTl?2XGB[*R.kRyL-?p0' );
define( 'NONCE_KEY',        'mJ,JGd7W!e.:Vx@e%.-)<X#s!Z%AGir6N}$0nI)zvj9[sP*DJXI|H|/f3O$t^OO=' );
define( 'AUTH_SALT',        '83jszwjla(.Pu r|^Ws=m>d$VD6`i@Po0*k>&4:)7J]>d/B~%%UxAa_XRls{-Sa#' );
define( 'SECURE_AUTH_SALT', '}YUXuA*oJO o&]> =U#D=7OZOl^B=0N!blD9D!W64oDpSY`:=j-{OPbBpCMzi4hz' );
define( 'LOGGED_IN_SALT',   ';Z{USbj$*lZgz;^na(yM0/2OP9@~kSld6;S8~`n|HLsHjJz,rOd#|S<mAbPLXY=g' );
define( 'NONCE_SALT',       'iOD?jYM{XcOaT:W5+T)*IU:%+s%yd70lI%tIfW>Mi9 e?|4ltro(cfU9#9Bzi$%N' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */

if (
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'
) {
    $_SERVER['HTTPS'] = 'on';
}
define('FORCE_SSL_ADMIN', true);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
        define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
