<?php

/*
    This file is part of Davedub's Contact Form plugin for WordPress

    Created by David Wooldridge

    Davedub's Contact Form plugin is free software: you can
    redistribute it and / or modify it under the terms of the
    GNU General Public License as published by the
    Free Software Foundation, either version 3 of the License,
    or (at your option) any later version.

    Davedub's Contact Form plugin is distributed in the hope
    that it will be useful, but WITHOUT ANY WARRANTY; without
    even the implied warranty of MERCHANTABILITY or FITNESS FOR
    A PARTICULAR PURPOSE.  See the GNU General Public License
    for more details.

    You should have received a copy of the GNU General Public License
    along with the Contacts and Bookings plugin.
    If not, see <http://www.gnu.org/licenses/>.
*/


// another handy string helper (http://stackoverflow.com/questions/5696412/get-substring-between-two-strings-php)
function get_string_between($string, $start, $end){
	$string = " ".$string;
	$ini = strpos($string,$start);
	if ($ini == 0) return "";
	$ini += strlen($start);
	$len = strpos($string,$end,$ini) - $ini;
	return substr($string,$ini,$len);
}


// Add Ajax actions
add_action( 'wp_ajax_the_ajax_hook', 'receive_jquery_ajax_call' );
add_action( 'wp_ajax_nopriv_the_ajax_hook', 'receive_jquery_ajax_call' ); // need this to serve non logged in users

// Ajax call receiving function
function receive_jquery_ajax_call(){

	session_start();

	// check requested session type
	if($_POST['ddcf_session']=='ddcf_manager_session') {
		include 'ddManagerSession.php';
		die();
	} // end of ddcf_manager_session
	else if($_POST['ddcf_session']=='ddcf_contact_session') {
		// Contact form
		include 'ddContactSession.php';
		die();
	} // end if ddcf_contact_session
	else {
		// unexpected request
		$return = array(
			'ddcf_manager_nonce'=> wp_create_nonce('ddcf_manager_nonce'),
			'ddcf_contact_information' => 'unknown session type'
		);
		wp_send_json($return);
		die();
	}
}

// contact form shortcode
function ddcf_contact_form() {
	ob_start();// fix formatting
	include 'ddContactPage.php';
	return ob_get_clean();
}

// management page shortcode
function ddcf_management_page() {
	ob_start();// fix formatting
	include 'ddManagementPage.php';
	return ob_get_clean();
}

// dd contact form options pages
function ddcf_options_page() {
	include 'ddOptionsPage.php';
}

// enqueue and localise scripts
function ddcf_enqueue_front_end_pages () {
	wp_enqueue_script( 'my-ajax-handle', plugins_url().'/dd-contact-form/js/ajax.js', array( 'jquery' ) );
	wp_localize_script( 'my-ajax-handle', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
        
        //if (get_option(ddcf_start_date_time_check) || get_option(ddcf_end_date_time_check)) {
        wp_enqueue_script( 'ddcf_datetimepicker_script',
                         plugins_url().'/dd-contact-form/js/jquery-ui-timepicker-addon.js',
                         array( 'jquery',
                                'jquery-ui-core',
                                'jquery-ui-datepicker')
                        );
        //}        
        if (get_option(ddcf_captcha_type)=="reCaptcha") {
            wp_enqueue_script( 'ddcf_google_recaptcha',
                         'http://www.google.com/recaptcha/api/js/recaptcha_ajax.js');
        }
        
        global $post;
        if(has_shortcode( $post->post_content, 'dd_contact_form' ))                 
            wp_enqueue_script( 'ddcf_contact_form_script',
                         plugins_url().'/dd-contact-form/js/dd-contact-form.js',
                         array( 'jquery',
                                'jquery-ui-core',
                                'jquery-effects-core',
                                'jquery-effects-explode',
                                'jquery-ui-datepicker',
                                'jquery-ui-button' )
                        );
        if(has_shortcode( $post->post_content, 'dd_management_page' )&&current_user_can(read))                 
            wp_enqueue_script( 'ddcf_dashboard_script',
                                plugins_url().'/dd-contact-form/js/dd-contact-booking-dashboard.js',
                                array(  'jquery',
                                        'jquery-ui-core',
                                        'jquery-ui-accordion',
                                        'jquery-ui-dialog',
                                        'jquery-ui-button' )
                            );                            

        /* enqueue css styles */
        
        /* normalise */
	wp_enqueue_style('ddcf_normalise_style', plugins_url().'/dd-contact-form/css/normalise.css');
	
        /* jQuery UI */
        if((get_option(ddcf_jqueryui_theme)!="none")&&
           (get_option(ddcf_jqueryui_theme)!="custom")&&
           (get_option(ddcf_jqueryui_theme)!="")) 
		wp_enqueue_style('ddcf_jqueryui_theme_style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/'.get_option(ddcf_jqueryui_theme).'/jquery-ui.css');
	else if (get_option(ddcf_jqueryui_theme)=="custom") 
		wp_enqueue_style('ddcf_jqueryui_theme_style', plugins_url().'/dd-contact-form/css/jquery-ui-custom.min.css');
	else    wp_enqueue_style('ddcf_jqueryui_theme_style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/smoothness/jquery-ui.css');
      
        /* contact form base */
        if(has_shortcode( $post->post_content, 'dd_contact_form' ))
                wp_enqueue_style('ddcf_layout_style', plugins_url().'/dd-contact-form/css/style-dd-contact-booking.css');
        
        /* contact form appearance */
        if(get_option(ddcf_form_theme)!='') wp_enqueue_style('ddcf_colorisation_style', plugins_url().'/dd-contact-form/css/style-theme-'.get_option(ddcf_form_theme).'.css');
	else    wp_enqueue_style('ddcf_colorisation_style', plugins_url().'/dd-contact-form/css/style-theme-clean.css');          
        
        /* manager page only */
        if(has_shortcode( $post->post_content, 'dd_management_page' )&&current_user_can(read))                 
                wp_enqueue_style('ddcf_manager_layout_style', plugins_url().'/dd-contact-form/css/style-dashboard.css');
}

function ddcf_enqueue_back_end_pages () {
	wp_enqueue_script( 'my-ajax-handle', plugins_url().'/dd-contact-form/js/ajax.js', array( 'jquery' ) );
	wp_localize_script( 'my-ajax-handle', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
        wp_enqueue_script( 'ddcf_dashboard_script',
                            plugins_url().'/dd-contact-form/js/dd-contact-booking-dashboard.js',
                            array(  'jquery',
                                    'jquery-ui-core',
                                    'jquery-ui-accordion',
                                    'jquery-ui-dialog',
                                    'jquery-ui-button' )
			);        
}

function add_ddcf_options_to_menu() {
	add_options_page( 'Contact Form Options', 'DD Contact Form', 'administrator', '__FILE__', 'ddcf_options_page');
}

function ddcf_plugin_settings_link($links, $file) {
        // check to make sure we are on the correct plugin
        if ($file == 'dd-contact-form/ddContactForm.php') {
            $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=__FILE__">Settings</a>';
            array_unshift($links, $settings_link);
        }
        return $links;
}

function ddcf_admin_init() {

	// enqueue and localise script for ajax query handling
	wp_enqueue_script( 'my-ajax-handle', plugins_url().'/dd-contact-form/js/ajax.js', array( 'jquery' ) );
	wp_localize_script( 'my-ajax-handle', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));

	register_setting( 'ddcf_settings_group', 'ddcf_enquiries_email_one' );
	register_setting( 'ddcf_settings_group', 'ddcf_enquiries_email_two' );
	register_setting( 'ddcf_settings_group', 'ddcf_enquiries_email_three');
	register_setting( 'ddcf_settings_group', 'ddcf_enquiries_email_four' );
	register_setting( 'ddcf_settings_group', 'ddcf_enquiries_email_five' );
	register_setting( 'ddcf_settings_group', 'ddcf_email_confirmation' );
	register_setting( 'ddcf_settings_group', 'ddcf_email_confirmation_text' );
	register_setting( 'ddcf_settings_group', 'ddcf_booking_start' );
	register_setting( 'ddcf_settings_group', 'ddcf_booking_end' );
	register_setting( 'ddcf_settings_group', 'ddcf_start_date_check' );
	register_setting( 'ddcf_settings_group', 'ddcf_start_date_time_check' );
        register_setting( 'ddcf_settings_group', 'ddcf_end_date_check' );
	register_setting( 'ddcf_settings_group', 'ddcf_end_date_time_check' );
        register_setting( 'ddcf_settings_group', 'ddcf_dates_compulsory_check' );
        register_setting( 'ddcf_settings_group', 'ddcf_dates_category_filter_check' );
        register_setting( 'ddcf_settings_group', 'ddcf_dates_category_filter' );
	register_setting( 'ddcf_settings_group', 'ddcf_extra_question_one_check' );
	register_setting( 'ddcf_settings_group', 'ddcf_extra_question_two_check' );
	register_setting( 'ddcf_settings_group', 'ddcf_extra_question_one' );
	register_setting( 'ddcf_settings_group', 'ddcf_extra_question_two' );
	register_setting( 'ddcf_settings_group', 'ddcf_extra_dropdown_one_check' );
	register_setting( 'ddcf_settings_group', 'ddcf_extra_dropdown_two_check' );
        register_setting( 'ddcf_settings_group', 'ddcf_party_size_compulsory_check' );
        register_setting( 'ddcf_settings_group', 'ddcf_party_size_category_filter' );
        register_setting( 'ddcf_settings_group', 'ddcf_party_size_category_filter_check' );
	register_setting( 'ddcf_settings_group', 'ddcf_captcha_type' );
        register_setting( 'ddcf_settings_group', 'ddcf_recaptcha_public_key' );
        register_setting( 'ddcf_settings_group', 'ddcf_recaptcha_private_key' );
	register_setting( 'ddcf_settings_group', 'ddcf_recaptcha_theme' );
	register_setting( 'ddcf_settings_group', 'ddcf_extra_question_category_filter_check' );
	register_setting( 'ddcf_settings_group', 'ddcf_extra_question_category_filter' );
        register_setting( 'ddcf_settings_group', 'ddcf_questions_compulsory_check' );
	register_setting( 'ddcf_settings_group', 'ddcf_form_theme' );
	register_setting( 'ddcf_settings_group', 'ddcf_jqueryui_theme' );
        register_setting( 'ddcf_settings_group', 'ddcf_jqueryui_theme' );
	register_setting( 'ddcf_settings_group', 'ddcf_keep_records_check' );
        register_setting( 'ddcf_settings_group', 'ddcf_geoloc_key' );
        register_setting( 'ddcf_settings_group', 'ddcf_rec_updates_option_check' );
	register_setting( 'ddcf_settings_group', 'ddcf_rec_updates_message_check' );
	register_setting( 'ddcf_settings_group', 'ddcf_rec_updates_message' );
	register_setting( 'ddcf_settings_group', 'ddcf_thankyou_type' );
        register_setting( 'ddcf_settings_group', 'ddcf_thankyou_message' );
        register_setting( 'ddcf_settings_group', 'ddcf_thankyou_url' );
	register_setting( 'ddcf_settings_group', 'ddcf_tooltips_check' );
	register_setting( 'ddcf_settings_group', 'ddcf_email_header' );
	//register_setting( 'ddcf_settings_group', 'ddcf_bookable_category' );
        register_setting( 'ddcf_settings_group', 'ddcf_error_checking_method' );
}



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//                                              	Activation, Deactivation, Removal
//                                                          ( Database setup )


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function dd_contact_form_activation()
{
	// check DB for required tables and create if not present
	global $wpdb;
	require_once(ABSPATH.'wp-admin/includes/upgrade.php');

	// enquires table to log all recieved messages
	$table_name = $wpdb->prefix . "enquiries";
	$sql = 'CREATE TABLE IF NOT EXISTS ' .$table_name . '(
		enquiry_id INTEGER(10) NOT NULL AUTO_INCREMENT,
		enquiry_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		customer_name VARCHAR(64),
		email_address VARCHAR(64),
		email_subject TEXT,
		arrival_date DATETIME,
		departure_date DATETIME,
		post_title TEXT,
		num_adults INT,
		num_children INT,
		question_one TEXT,
		question_two TEXT,
		email_message LONGTEXT,
		receive_updates TINYINT,
		ip_address VARCHAR(32),
		city VARCHAR(32),
		region VARCHAR(32),
		country VARCHAR(32),
		contact_id INT,
		PRIMARY KEY  (enquiry_id) )';
	if(!dbDelta($sql)) trigger_error ( "Unable to update database" );



	// contact table to log contact name and type
	$table_name = $wpdb->prefix . "contact";
	$sql = 'CREATE TABLE IF NOT EXISTS ' .$table_name . '(
		contact_id INTEGER(10) NOT NULL AUTO_INCREMENT,
		contact_name TEXT,
		first_registered TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		contact_type TEXT,
		wordpress_id INT,
		PRIMARY KEY (contact_id) )';
	if(!dbDelta($sql)) trigger_error ( "Unable to update database" );


	// contact_info table for email addresses, phone numbers etc
	$table_name = $wpdb->prefix . "contact_info";
	$sql = 'CREATE TABLE IF NOT EXISTS ' .$table_name . '(
		info_id INTEGER(10) NOT NULL AUTO_INCREMENT,
		contact_id INT,
		contact_info TEXT,
		info_type TEXT,
		special_instructions TEXT,
		PRIMARY KEY (info_id) )';
	if(!dbDelta($sql)) trigger_error ( "Unable to update database" );


	// contact_info_methods table for email addresses, phone numbers etc
	$table_name = $wpdb->prefix . "contact_methods";
	$sql = 'CREATE TABLE IF NOT EXISTS ' .$table_name . '(
		contact_method_index INTEGER(10) NOT NULL AUTO_INCREMENT,
		contact_method TEXT,
		PRIMARY KEY (contact_method_index) )';
	if(!dbDelta($sql)) trigger_error ( "Unable to update database" );

	$populated = mysql_result(mysql_query('SELECT COUNT(*) FROM '.$table_name), 0);
	if(!$populated) {
		// types are: phone, email, address, website, skype, facebook, linkedin, twitter
		$sql = "INSERT INTO ".$table_name ." (contact_method_index, contact_method) VALUES
											   (null, 'phone'),
											   (null, 'email'),
											   (null, 'address'),
											   (null, 'website'),
											   (null, 'skype'),
											   (null, 'facebook'),
                                                                                           (null, 'google+'),
											   (null, 'linkedin'),
											   (null, 'twitter')";
		if(!dbDelta($sql)) trigger_error ( "Unable to update database" );
	}


	// contact_roles (associate, owner, client, guest, customer)
	$table_name = $wpdb->prefix . "contact_roles";
	$sql = 'CREATE TABLE IF NOT EXISTS ' .$table_name . '(
		contact_role_index INTEGER(10) NOT NULL AUTO_INCREMENT,
		contact_role TEXT,
		PRIMARY KEY (contact_role_index) )';
	if(!dbDelta($sql)) trigger_error ("Unable to update database");

	$populated = mysql_result(mysql_query('SELECT COUNT(*) FROM '.$table_name), 0);
	if(!$populated) {
		$sql = "INSERT INTO ".$table_name ." (contact_role_index, contact_role) VALUES
//											   (null, 'associate'),
//											   (null, 'client'),
//											   (null, 'guest'),
//											   (null, 'customer'),
											   (null, 'all contact types')";
		if(!dbDelta($sql)) trigger_error ("Unable to update database");
	}
}

//function dd_contact_form_deactivation()
//{
//
//}

function dd_contact_form_uninstall()
{
	// remove all settings
	unregister_setting( 'ddcf_settings_group', 'ddcf_enquiries_email_one' );
	unregister_setting( 'ddcf_settings_group', 'ddcf_enquiries_email_two' );
	unregister_setting( 'ddcf_settings_group', 'ddcf_enquiries_email_three');
	unregister_setting( 'ddcf_settings_group', 'ddcf_enquiries_email_four' );
	unregister_setting( 'ddcf_settings_group', 'ddcf_enquiries_email_five' );
	unregister_setting( 'ddcf_settings_group', 'ddcf_email_confirmation' );
	unregister_setting( 'ddcf_settings_group', 'ddcf_email_confirmation_text' );
	unregister_setting( 'ddcf_settings_group', 'ddcf_booking_start' );
	unregister_setting( 'ddcf_settings_group', 'ddcf_booking_end' );
	unregister_setting( 'ddcf_settings_group', 'ddcf_start_date_check' );
	unregister_setting( 'ddcf_settings_group', 'ddcf_start_date_time_check' );
        unregister_setting( 'ddcf_settings_group', 'ddcf_end_date_check' );
	unregister_setting( 'ddcf_settings_group', 'ddcf_end_date_time_check' );
        unregister_setting( 'ddcf_settings_group', 'ddcf_dates_compulsory_check' );
        unregister_setting( 'ddcf_settings_group', 'ddcf_dates_category_filter_check' );
        unregister_setting( 'ddcf_settings_group', 'ddcf_dates_category_filter' );
	unregister_setting( 'ddcf_settings_group', 'ddcf_extra_question_one_check' );
	unregister_setting( 'ddcf_settings_group', 'ddcf_extra_question_two_check' );
	unregister_setting( 'ddcf_settings_group', 'ddcf_extra_question_one' );
	unregister_setting( 'ddcf_settings_group', 'ddcf_extra_question_two' );
	unregister_setting( 'ddcf_settings_group', 'ddcf_extra_question_category_filter_check' );
	unregister_setting( 'ddcf_settings_group', 'ddcf_extra_question_category_filter' );
        unregister_setting( 'ddcf_settings_group', 'ddcf_questions_compulsory_check' );        
	unregister_setting( 'ddcf_settings_group', 'ddcf_extra_dropdown_one_check' );
	unregister_setting( 'ddcf_settings_group', 'ddcf_extra_dropdown_two_check' );
        unregister_setting( 'ddcf_settings_group', 'ddcf_party_size_compulsory_check' );
        unregister_setting( 'ddcf_settings_group', 'ddcf_party_size_category_filter' );
        unregister_setting( 'ddcf_settings_group', 'ddcf_party_size_category_filter_check' );
	unregister_setting( 'ddcf_settings_group', 'ddcf_captcha_type' );
        unregister_setting( 'ddcf_settings_group', 'ddcf_recaptcha_public_key' );
        unregister_setting( 'ddcf_settings_group', 'ddcf_recaptcha_private_key' );
	unregister_setting( 'ddcf_settings_group', 'ddcf_recaptcha_theme' );
	unregister_setting( 'ddcf_settings_group', 'ddcf_form_theme' );
	unregister_setting( 'ddcf_settings_group', 'ddcf_jqueryui_theme' );
        unregister_setting( 'ddcf_settings_group', 'ddcf_keep_records_check' );
	unregister_setting( 'ddcf_settings_group', 'ddcf_geo_ip_option_check' );
        unregister_setting( 'ddcf_settings_group', 'ddcf_geoloc_key' );
        unregister_setting( 'ddcf_settings_group', 'ddcf_rec_updates_option_check' );
	unregister_setting( 'ddcf_settings_group', 'ddcf_rec_updates_message_check' );
	unregister_setting( 'ddcf_settings_group', 'ddcf_rec_updates_message' );
	unregister_setting( 'ddcf_settings_group', 'ddcf_thankyou_type' );
        unregister_setting( 'ddcf_settings_group', 'ddcf_thankyou_message' );
        unregister_setting( 'ddcf_settings_group', 'ddcf_thankyou_url' );
	unregister_setting( 'ddcf_settings_group', 'ddcf_tooltips_check' );
	unregister_setting( 'ddcf_settings_group', 'ddcf_email_header' );
	//unregister_setting( 'ddcf_settings_group', 'ddcf_bookable_category' );
        unregister_setting( 'ddcf_settings_group', 'ddcf_error_checking_method' );
        delete_option('ddcf_settings_group','0.1');
        
        // remove all tables from DB
        global $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        $sql = "DROP TABLE IF EXISTS ". $wpdb->prefix ."enquiries;";
        $wpdb->query($sql);
        $sql = "DROP TABLE IF EXISTS " . $wpdb->prefix .'contact;';
	$wpdb->query($sql);
	$sql = "DROP TABLE IF EXISTS " . $wpdb->prefix .'contact_info;';
	$wpdb->query($sql);
	$sql = "DROP TABLE IF EXISTS " . $wpdb->prefix .'contact_methods;';
	$wpdb->query($sql);
	$sql = "DROP TABLE IF EXISTS " . $wpdb->prefix .'contact_roles;';
	$wpdb->query($sql);
}
?>