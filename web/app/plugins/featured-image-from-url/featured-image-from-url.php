<?php

/*
 * Plugin Name: Featured Image From URL
 * Description: Use an external image as Featured Image of your post/page/custom post type (WooCommerce). Includes Auto Set (External Post), Product Gallery, Social Tags and more.
 * Version: 1.7.2
 * Author: Marcel Jacques Machado 
 * Author URI: http://featuredimagefromurl.com/
 */

define('FIFU_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FIFU_INCLUDES_DIR', FIFU_PLUGIN_DIR . '/includes');
define('FIFU_ADMIN_DIR', FIFU_PLUGIN_DIR . '/admin');

require_once( FIFU_INCLUDES_DIR . '/thumbnail.php' );
require_once( FIFU_INCLUDES_DIR . '/thumbnail-category.php' );
require_once( FIFU_INCLUDES_DIR . '/external-post.php' );
require_once( FIFU_INCLUDES_DIR . '/convert-url.php' );
require_once (FIFU_INCLUDES_DIR . '/woo.php');

if (is_admin()) {
    require_once( FIFU_ADMIN_DIR . '/meta-box.php' );
    require_once( FIFU_ADMIN_DIR . '/menu.php' );
    require_once( FIFU_ADMIN_DIR . '/column.php' );
    require_once( FIFU_ADMIN_DIR . '/category.php' );
}

register_deactivation_hook(__FILE__, 'fifu_deactivate');

function fifu_deactivate() {
    update_option('fifu_woocommerce', 'toggleoff');
    shell_exec('sh ../wp-content/plugins/featured-image-from-url/scripts/disableWoocommerce.sh');
}
