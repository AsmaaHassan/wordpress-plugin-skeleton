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
include_once("dbHelper.php");  // include setup functions
include_once("Helper.php");  // include Helper functions

/* =======================================================================
 *      register plugin activation - deactivation - uninstall
 * =======================================================================
*/

function pluginIsActive() {
    // Activation code here
    $setup = new dbHelper;
    $setup->cteateDummyTable();
}

function pluginIsInactive() {
    // Deactivation code here
    $setup = new dbHelper;
    $setup->dropDummyTable();
}

function pluginUninstalled() {
    // Uninstall code here
    $setup = new dbHelper;
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
// more info: https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
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




/* =======================================================================
 *      add dummy_meta field to uses form
 * =======================================================================
*/
//add field to the add user form - update user - update profile in wordpress
add_action('user_new_form','addNewFieldToUsersForm');
add_action('show_user_profile', 'addNewFieldToUsersForm');
add_action('edit_user_profile', 'addNewFieldToUsersForm');

function addNewFieldToUsersForm($user) {
    $helper = new Helper;
    echo $helper->addDummymetaField($user);
}


/* =======================================================================
 *      add dummy_meta column to $wpdb->usermeta;
 * =======================================================================
*/
//Save new field for user in users_meta table
add_action('user_register', 'saveDummymeta');
add_action('edit_user_profile_update', 'saveDummymeta');

function saveDummymeta($user_id, $dummy_meta) {

    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    if (isset($_POST['dummy_meta'])) {
        update_user_meta($user_id, 'dummy_meta', $dummy_meta);
    }
}

//add field to the update user form in wordpress admin panel
function showDummymetaInUpdateUser( $contactmethods ) {
    $contactmethods['dummy_meta'] = 'dummy meta';
    return $contactmethods;
}
add_filter( 'user_contactmethods', 'showDummymetaInUpdateUser', 10, 1 );

//add field to the all users in wordpress admin panel
function showDummymetaInAllUsersPanel( $column ) {
    $column['dummy_meta'] = 'dummy meta';
    return $column;
}
add_filter( 'manage_users_columns', 'showDummymetaInAllUsersPanel' );

function addDummymetaToUsersMeta( $val, $column_name, $user_id ) {
    switch ($column_name) {
        case 'dummy_meta' :
            return get_the_author_meta( 'dummy_meta', $user_id );
            break;
        default:
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'addDummymetaToUsersMeta', 10, 3 );




/* =======================================================================
 *      add plugin menu and submenu to wp-admin
 * =======================================================================
*/
// create menu to read and add dummy data into $wpdb->dummyTable 
// that one we've created on plugin install/active from csv file
add_action( 'admin_menu', 'pluginMenu' );

function pluginMenu() {
    add_menu_page( 'pluginMenu', 'pluginMenu', 'manage_options', 'myplugin/myplugin-admin-page.php', 'dummyDataPanel', 'dashicons-tickets', 6  );
    add_submenu_page( 'myplugin/myplugin-admin-page.php', 'pluginSubmenu', 'pluginSubmenu', 'manage_options', 'myplugin/myplugin-admin-sub-page.php', 'addDummyData' ); 
}

function dummyDataPanel(){
    $helper = new Helper;
    echo $helper->showAllDummyData();
}

// upload file
define('SAVEQUERIES', true);
define( 'MY_PLUGIN_ROOT' , dirname(__FILE__) );
function addDummyData(){
    $helper = new Helper;
    echo $helper->uploadFileForm(); // display upload file form
    echo $helper->fetchReadFile(); // read file content
}


?>