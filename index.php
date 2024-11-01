<?php
/*
Plugin Name: Affiliate Coupon ReCalc
Plugin URI: http://www.stayonweb.com/wp/plugins/affiliates_crc/
Description: This plugin calculates the affiliate payout based on sales of coupon code. Requires 1) Affiliates PRO and 2) Directory Press
Author: Farhan Sabir
Version: 0.9beta
Author URI: http://farhansabir.com/
*/
/*
wp_die( $message, $title, $args );
admin_url( $path, $scheme ); 
add_query_arg( $param1, $param2, $old_query_or_uri);
is_blog_installed();
*/
include AFFILIATES_CRC_PATH . "affiliates_crc_functions.php";

register_activation_hook(__FILE__,'affiliates_crc_install');
register_deactivation_hook( __FILE__, 'affiliates_crc_remove' );

//CONSTANTINE
define( 'AFFILIATES_CRC_PATH', plugin_dir_path(__FILE__) );
define( 'AFFILIATES_CRC_MAINMENU', 'Affiliates ReCalc' );
define( 'AFFILIATES_CRC_SUBMENU_01', 'Import Coupon Orders' );
define( 'AFFILIATES_CRC_SUBMENU_02', 'Apply Coupon Credit' );
define( 'AFFILIATES_CRC_SUBMENU_03', 'Affiliates Calc' );
define( 'AFFILIATES_CRC_SUBMENU_01_DESC', 'Import Orders with Affiliate Coupons' );
define( 'AFFILIATES_CRC_SUBMENU_02_DESC', "Apply Coupon Credit to Affiliates" );
//include AFFILIATES_CRC_PATH . "affiliates_crc_options.inc.php";
add_action('admin_menu', 'affiliates_crc_mainmenu');

function affiliates_crc_mainmenu() {
	// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position ); 
   add_menu_page(AFFILIATES_CRC_MAINMENU, AFFILIATES_CRC_MAINMENU, 'manage_options', __FILE__, 'affiliates_crc_main_cb', 'affiliates_crc_cb', 999);
	include AFFILIATES_CRC_PATH . "affiliates_crc_main.inc.php";
	//affiliates_crc_validate_two();
}

//PART-3: Add Sub Menu Pages
add_action('admin_menu', 'register_affiliates_crc_submenu_page');

function register_affiliates_crc_submenu_page() {
	add_submenu_page( AFFILIATES_CRC_PATH . 'index.php', AFFILIATES_CRC_SUBMENU_01, AFFILIATES_CRC_SUBMENU_01, 'manage_options', 'affiliates-crc-01', 'affiliates_crc_01_cb' ); 
	add_submenu_page( AFFILIATES_CRC_PATH . 'index.php', AFFILIATES_CRC_SUBMENU_02, AFFILIATES_CRC_SUBMENU_02, 'manage_options', 'affiliates-crc-02', 'affiliates_crc_02_cb' ); 
}


function affiliates_crc_01_cb() {
	echo '<h1>' . AFFILIATES_CRC_SUBMENU_01  . '</h1>';
	echo '<h2>' . AFFILIATES_CRC_SUBMENU_01_DESC . '</h2>';
	include AFFILIATES_CRC_PATH . "affiliates_crc_01.inc.php";
	affiliates_crc_01_cbc();
}

function affiliates_crc_02_cb() {
	echo '<h1>' . AFFILIATES_CRC_SUBMENU_02  . '</h1>';
	echo '<h2>' . AFFILIATES_CRC_SUBMENU_02_DESC . '</h2>';
	include AFFILIATES_CRC_PATH . "affiliates_crc_02.inc.php";
	affiliates_crc_02_cbc();
}

//PART-3: Display Main Menu
function affiliates_crc_options() {
	include AFFILIATES_CRC_PATH . "affiliates_crc_functions.php";
}
?>