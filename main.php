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

/*
 * # wordpres-plugin-skeleton
 * wordpress plugin skeleton with upload file and read from csv file example.
 * points covered:
 * 1- include custom css and js files to your plugin.
 * 2- include libs based on another libs.
 * 3- plugin install, active and inactive.
 * 4- create plugin shortcode.
 * 5- using wpdb "to make db CRUD".
 * 6- create plugin menu and submenu in wp-admin.
 * 7- create wordpress endpoint.
 * 8- add custom column to users_meta and add it to users profile and all users panel.
 * 9- add field to the add user form - update user - update profile in wordpress.
 * 10- upload file.
 * 11- read from csv file.
 * 12- create custom ajax for our plugin.
 * 13- using date picker in our pluin. 
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
    $helper = new Helper;
    echo $helper->sayHelloForm();
}
// display the function on shortcode call
add_shortcode( 'wordpress-plugin-skeleton', 'pluginShortCode' );

function sayHello(){
	$helper = new Helper;
    echo $helper->sayHelloPost();
    die();// wordpress may print out a spurious zero
    // without this can be particularly bad if using json
}

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


/*  =======================================
 *      register new styles and scripts
 *  =======================================
 */
// register scripts and styles on initialization
add_action('init', 'registerStylesAndScripts');
function registerStylesAndScripts() {
    // custome styles
    wp_register_script( 'pluginJs', plugins_url('/assets/scripts.js', __FILE__), array('jquery'));
    wp_register_style( 'pluginCss', plugins_url('/assets/style.css', __FILE__));
    // libs
    // jQuery
    wp_register_script( 'jquery', plugins_url('/assets/lib/jQuery.js', __FILE__), array('jquery'), '3.2.1');
    // jquery-ui-1.12.1
    wp_register_script( 'jQueryUiJs', plugins_url('/assets/lib/jquery-ui-1.12.1/jquery-ui.min.js', __FILE__), array('jquery'));
    wp_register_style( 'jQueryUiCss', plugins_url('/assets/lib/jquery-ui-1.12.1/jquery-ui.min.css', __FILE__));
    // bootstrap-datepicker-1.6.4
    wp_register_script( 'datePickerJs', plugins_url('/assets/lib/bootstrap-datepicker-1.6.4/bootstrap-datepicker.min.js', __FILE__), array('jquery'));
    wp_register_style( 'datePickerCss', plugins_url('/assets/lib/bootstrap-datepicker-1.6.4/bootstrap-datepicker3.min.css', __FILE__));
}

// now we can use the scripts registered above
add_action('wp_enqueue_scripts', 'enqueueStylesAndScripts');
function enqueueStylesAndScripts(){
	// custom plugin css - js
   wp_enqueue_script('pluginJs');
   wp_enqueue_style('pluginCss');
   // jquery
   wp_enqueue_script('jquery');
   // jquery ui
   wp_enqueue_style('jQueryUiCss');
   wp_enqueue_script('jQueryUiJs');
   //date picker
   wp_enqueue_style('datePickerCss');
   wp_enqueue_script('datePickerJs');
   // defining the ajax script in our plugin
   wp_localize_script( 'pluginJs', 'customAjaxScript', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        //'query_vars' => json_encode( $wp_query->query )
        ) );
}
// defining the ajax function 
add_action( 'wp_ajax_sayHello', 'sayHello' );
add_action( 'wp_ajax_nopriv_sayHello', 'sayHello' );

?>