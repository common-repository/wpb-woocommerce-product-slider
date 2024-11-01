<?php
/**
 * Plugin Name: WPB Product Slider for WooCommerce 
 * Plugin URI: https://wpbean.com/downloads/wpb-woocommerce-product-slider-pro/
 * Description: WPB product slider for WooCommerce comes with different styles for product slider. It can show slider of latest, featured, category, tags and selected products.
 * Author: wpbean
 * Version: 2.2.1
 * Author URI: https://wpbean.com
 * Text Domain: wpb-wps
 * Domain Path: /languages
*/

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'is_plugin_active' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}


/**
 * Define constants
 */

if ( ! defined( 'WPB_WPS_FREE_INIT' ) ) {
    define( 'WPB_WPS_FREE_INIT', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'WPB_WPS_URI' ) ) {
    define( 'WPB_WPS_URI', WP_CONTENT_URL. '/plugins/wpb-woocommerce-product-slider' );
}

if ( ! defined( 'WPB_WPS_PLUGIN_DIR' ) ) {
    define( 'WPB_WPS_PLUGIN_DIR', plugin_dir_path(__FILE__) );
}

if ( ! defined( 'WPB_WPS_PLUGIN_DIR_FILE' ) ) {
    define( 'WPB_WPS_PLUGIN_DIR_FILE', __FILE__ );
}

if ( ! defined( 'WPB_WPS_TEXTDOMAIN' ) ) {
    define( 'WPB_WPS_TEXTDOMAIN', 'wpb-wps' );
}

/**
 * This version can't be activate if premium version is active
 */

if ( defined( 'WPB_WPS_PREMIUM' ) ) {
    function wpb_wps_install_free_admin_notice() {
        ?>
        <div class="error">
            <p><?php esc_html_e( 'You can\'t activate the free version of WPB Product Slider for WooCommerce while you are using the premium one.', 'wpb-wps' ); ?></p>
        </div>
    <?php
    }

    add_action( 'admin_notices', 'wpb_wps_install_free_admin_notice' );
    deactivate_plugins( plugin_basename( __FILE__ ) );
    return;
}


/**
 * Plugin Activation redirect 
 */

if( !function_exists( 'wpb_wps_free_activation_redirect' ) ){
	function wpb_wps_free_activation_redirect( $plugin ) {
	    if( $plugin == plugin_basename( __FILE__ ) ) {
	        exit( wp_redirect( admin_url( 'admin.php?page=wpb-wps-about' ) ) );
	    }
	}
}
add_action( 'activated_plugin', 'wpb_wps_free_activation_redirect' );



/**
 * Plugin Action Links
 */

if( !function_exists( 'wpb_wps_add_action_links' ) ){
	function wpb_wps_add_action_links ( $links ) {

		$links[] = '<a href="'. esc_url( get_admin_url( null, 'admin.php?page=wpb-wps-settings') ) .'">'. esc_html__( 'Settings', 'wpb-wps' ) .'</a>';
		$links[] = '<a href="https://wpbean.com/support/" target="_blank">'. esc_html__( 'Support', 'wpb-wps' ) .'</a>';
		$links[] = '<a style="color: #27ae60;font-weight: bold;" target="_blank" href="'. esc_url( 'https://wpbean.com/downloads/wpb-woocommerce-product-slider-pro/' ) .'">'. esc_html__( 'Upgrade to PRO!', 'wpb-wps' ) .'</a>';

		return $links;
	}
}


/**
 * Pro version discount
 */

function wpb_wps_pro_discount_admin_notice() {
    $user_id = get_current_user_id();
    if ( !get_user_meta( $user_id, 'wpb_wps_pro_discount_dismissed' ) ){
        printf('<div class="wpb-wps-discount-notice updated" style="padding: 30px 20px;border-left-color: #27ae60;border-left-width: 5px;margin-top: 20px;"><p style="font-size: 18px;line-height: 32px">%s <a target="_blank" href="%s">%s</a>! %s <b>%s</b></p><a href="%s">%s</a></div>', esc_html__( 'Get a 10% exclusive discount on the premium version of the', 'wpb-wps' ), 'https://wpbean.com/downloads/wpb-woocommerce-product-slider-pro/', esc_html__( 'WPB WooCommerce Product Slider', 'wpb-wps' ), esc_html__( 'Use discount code - ', 'wpb-wps' ), '10PERCENTOFF', esc_url( add_query_arg( 'wpb-wps-pro-discount-admin-notice-dismissed', 'true' ) ), esc_html__( 'Dismiss', 'wpb-wps' ));
    }
}


function wpb_wps_pro_discount_admin_notice_dismissed() {
    $user_id = get_current_user_id();
    if ( isset( $_GET['wpb-wps-pro-discount-admin-notice-dismissed'] ) ){
        add_user_meta( $user_id, 'wpb_wps_pro_discount_dismissed', 'true', true );
    }
}

/**
 * Plugin Deactivation
 */

function wpb_wps_lite_plugin_deactivation() {
  $user_id = get_current_user_id();
  if ( get_user_meta( $user_id, 'wpb_wps_pro_discount_dismissed' ) ){
  	delete_user_meta( $user_id, 'wpb_wps_pro_discount_dismissed' );
  }
}


/**
 * Plugin Init
 */

function wpb_wps_free_plugin_init(){
	load_plugin_textdomain( WPB_WPS_TEXTDOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	register_deactivation_hook( plugin_basename( __FILE__ ), 'wpb_wps_lite_plugin_deactivation' );
	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wpb_wps_add_action_links' );
	//add_action( 'admin_notices', 'wpb_wps_pro_discount_admin_notice' );
	add_action( 'admin_init', 'wpb_wps_pro_discount_admin_notice_dismissed' );

	require_once dirname( __FILE__ ) . '/inc/wpb-scripts.php';
	require_once dirname( __FILE__ ) . '/inc/wpb-wps-widgets.php';
	require_once dirname( __FILE__ ) . '/inc/wpb-wps-shortcodes.php';
	require_once dirname( __FILE__ ) . '/inc/wpb-wps-functions.php';
	require_once dirname( __FILE__ ) . '/admin/settings/class.settings-api.php';
	require_once dirname( __FILE__ ) . '/admin/settings/wpb-wps-settings.php';

	if( is_admin() ){
		require_once dirname( __FILE__ ) . '/inc/DiscountPage/DiscountPage.php';
		new WPBean_WC_Products_Slider_DiscountPage();
	}
}
add_action( 'plugins_loaded', 'wpb_wps_free_plugin_init' );