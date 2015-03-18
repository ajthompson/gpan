<?php
/** 
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'gpan');

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
define('AUTH_KEY',         'XTk/>ON81z=BI-ncn3XHN9 {sB3rK:@BGc>5Y2d_3UkQ]0xzUx}S<AtzJhy&(WjU');
define('SECURE_AUTH_KEY',  '9];hA4o%:dcDU;a$S!?Nb-yH#m4G3%CQ_ L5r}#:^[xK|DZK:]H#4Ua0cQ2lICo}');
define('LOGGED_IN_KEY',    '_=dH>3?o8PH<(z[pn^aRke0(71#hJp@eQQGz`3LX01*-IeHXIgb?>6Rk;5gySP<k');
define('NONCE_KEY',        'KS@YH:zaYSS_u7% )s[ZLs.Y(.$]{rOGvj,#C;u#rcB0~,Ow4=P*q%ooEVUi z7q');
define('AUTH_SALT',        'c}qA&!w5Z4TWrO=<)yyP&z1xq(6t:%/QKv1*Yi#oAR-S+XVFUv.IBrZBgd[<A0YX');
define('SECURE_AUTH_SALT', '{@@_3_-,1H7^#;/)_n@9{D]xeB+Y/-]3R4:$$8X_z+b>Z&i<u{y4~ IUDB_rycDg');
define('LOGGED_IN_SALT',   'nfVja[4_a-FgQiXc!OX38y.KQ_(7mb^Q~NypYHl J00B0:m|E4+HB-7(^8huTnmx');
define('NONCE_SALT',       'q,xhep-|rfm;DDAg*j>-WJxBD7m$hz@0OxDh_7yeHt`@g).v4`@!=4`|oz<Sgg4X');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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

