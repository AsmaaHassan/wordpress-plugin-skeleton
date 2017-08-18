<?php
/*
 * Plugin Name: wordpress plugin skeleton
 * Plugin URI: https://github.com/iSuperMostafa/wordpress-plugin-skeleton
 * Description: A plugin sekelton with upload/read csv file
 * Version: 1.0
 * Author: @iSuperMostafa
 * Author URI: https://github.com/iSuperMostafa
 * License: GPL2
*/
$path = $_SERVER['DOCUMENT_ROOT'];
include_once $path . '/wp-includes/wp-db.php'; // include wpdb for db connection
include_once("Setup.php");  // include setup functions

/* =======================================================================
 *      register plugin activation - deactivation - uninstall
 * =======================================================================
*/

function pluginIsActive() {
    // Activation code here
    $setup = new Setup;
    $setup->cteateDummyTable();
}

function pluginIsInactive() {
    // Deactivation code here
    $setup = new Setup;
    $setup->dropDummyTable();
}

function pluginUninstalled() {
    // Uninstall code here
    $setup = new Setup;
    $setup->dropDummyTable();
}

register_activation_hook( __FILE__, 'pluginIsActive' );
register_deactivation_hook( __FILE__, 'pluginIsInactive' );
register_uninstall_hook( __FILE__, 'pluginUninstalled' );



/* =======================================================================
 *      register plugin shortcode
 * =======================================================================
*/

function pluginShortCode(){
    echo "Hello world!";
    die();// wordpress may print out a spurious zero
    // without this can be particularly bad if using json

}
// display the function on shortcode call
add_shortcode( 'wordpress-plugin-skeleton', 'pluginShortCode' );



/* =======================================================================
 *      register plugin endpoint
 * =======================================================================
*/

function dummyEndpoint(WP_REST_Request $request ) {
  $data = $request->get_json_params();
  return $data;
}

add_action( 'rest_api_init', function () {
  register_rest_route( 'ws', '/endpoint', array(
    'methods' => 'GET',
    'callback' => 'dummyEndpoint',
  ) );
} );




?>