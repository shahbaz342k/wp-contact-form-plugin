<?php
/*
Plugin Name: My Custom Contact Form
Description: This plugin is used for contact form 
Author: Mohd Shahbaz
Plugin URI: http://localhost/
Author URI: http://localhost/
Version: 1.0.0
*/

define( 'SHWPCF_PLUGIN', __FILE__ );
define( 'SHWPCF_PLUGIN_DIR', untrailingslashit( dirname( SHWPCF_PLUGIN ) ) );
define( 'SHWPCF_PLUGIN_BASENAME', plugin_basename( SHWPCF_PLUGIN ) );
define( 'SHWPCF_PLUGIN_NAME', trim( dirname( SHWPCF_PLUGIN_BASENAME ), '/' ) );
define( 'SHWP_PLUGIN_URL',plugins_url().'/'.SHWPCF_PLUGIN_NAME );

// create table on activation plugin
if (!function_exists('sh_contact_plugin_database_table')) {
	function sh_contact_plugin_database_table(){
	    global $table_prefix, $wpdb;
	    $tblname = 'sh_contact';
	    $table_name = $table_prefix.$tblname;
	    //echo $table_name;

	    #Check to see if the table exists already, if not, then create it

	    if($wpdb->get_var( "show tables like '$table_name'" ) != $table_name) {

			$sql ='CREATE TABLE '.$table_name.' ( `id` int(11) NOT NULL AUTO_INCREMENT,
				    `name` varchar(100) NOT NULL,
				    `email` varchar(100) NOT NULL,
				    `mobile` varchar(255) NOT NULL,
				    `subject` varchar(255) NULL,
				    `message` text NOT NULL,
				    `timestamp` DATETIME NULL DEFAULT NULL,
				    PRIMARY KEY (id) )';
	        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
	        dbDelta($sql);
	    }
	}
	register_activation_hook( __FILE__, 'sh_contact_plugin_database_table' );
}
// end create table

// Delete table on deacativate plugin
if (!function_exists('delete_sh_contact_table')) {
	function delete_sh_contact_table() {
		global $wpdb;
		$tblname = 'sh_contact';
	    $table = $wpdb->prefix.$tblname;
		$sql = "DROP TABLE IF EXISTS ".$table." ";
		$wpdb->query($sql);
		delete_option("my_plugin_db_version");
	}
	register_deactivation_hook( __FILE__, 'delete_sh_contact_table' );
}
// end Delete table

// create a new option in wp admin options menu and added the page and fields
add_action('admin_menu', 'sh_contact_form_plugin_setup_menu');
function sh_contact_form_plugin_setup_menu(){
	//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function = '', $icon_url = '', $position = null )
	add_menu_page( 'My Contact Form', 'My Contact Form', 'manage_options', 'sh-contact-form', 'sh_contact_settings', 'dashicons-email-alt' );

}
if (!function_exists('sh_contact_settings')) {
	function sh_contact_settings(){
		require_once SHWPCF_PLUGIN_DIR . '/admin/contact_data.php';
	}
}



add_action('admin_menu', 'show_shortcode_page');
function show_shortcode_page(){
	//add_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', int $position = null );
	add_submenu_page( 'sh-contact-form', 'Contact Shortcode', 'Shortcode', 'manage_options', 'sh-contact-shortcode', 'sh_shortcode_pg');

}
function sh_shortcode_pg(){
	echo "<h2>Copy below shortcode and paste on the page where you want to display the form</h2>
		<p><strong>[sh_display_contact_form]</strong></p>";
		
}

// end wp admin create and show page

// contact form html code
require_once SHWPCF_PLUGIN_DIR . '/form.php';
// end contact form html code

// create function for adding js and localize the object of ajax url 
add_action('wp_enqueue_scripts', 'my_sh_ajax'); 
function my_sh_ajax() {   
	wp_enqueue_script('sh-contact-script', SHWP_PLUGIN_URL.'/js/contact_ajax.js',array('jquery'));   
	wp_enqueue_style('sh-contact-style', SHWP_PLUGIN_URL.'/css/style.css');   
	wp_localize_script( 'sh-contact-script', 'shcontactAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) ); 
}
// end enquee script

// inlcude form submit file
require_once SHWPCF_PLUGIN_DIR . '/form_submit.php';



?> 

