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

 /** @var string Directory containing all of the site's files */
 $root_dir = dirname(__DIR__) . '/public';

 /** Load composer dependencies */
require_once( $root_dir . '/vendor/autoload.php');

/**
 * Use Dotenv to set required environment variables and load .env file in root
 */
$dotenv = new Dotenv\Dotenv($root_dir);
if (file_exists($root_dir . '/.env')) {
    $dotenv->load();
    $dotenv->required(['DB_NAME', 'DB_USER', 'DB_PASSWORD', 'WP_HOME', 'WP_SITEURL']);
}

// ========================
// Custom Content Directory
// ========================
define('WP_HOME', getenv('WP_HOME'));
define('WP_SITEURL', getenv('WP_SITEURL'));
define('CONTENT_DIR', dirname( __FILE__ ) . '/wp-content');
define('WP_CONTENT_DIR', dirname( __FILE__ ) . '/wp-content' );
define('WP_CONTENT_URL', getenv('WP_HOME') . '/wp-content' );

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', getenv('DB_NAME'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_CHARSET', 'utf8mb4');
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

 define('AUTH_KEY', getenv('AUTH_KEY'));
 define('SECURE_AUTH_KEY', getenv('SECURE_AUTH_KEY'));
 define('LOGGED_IN_KEY', getenv('LOGGED_IN_KEY'));
 define('NONCE_KEY', getenv('NONCE_KEY'));
 define('AUTH_SALT', getenv('AUTH_SALT'));
 define('SECURE_AUTH_SALT', getenv('SECURE_AUTH_SALT'));
 define('LOGGED_IN_SALT', getenv('LOGGED_IN_SALT'));
 define('NONCE_SALT', getenv('NONCE_SALT'));

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
define('WP_DEBUG', strtolower(getenv('WP_ENV')) == 'development' ? true  : false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
