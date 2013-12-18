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

// bac options pages
function ddcf_options_page() {
	include 'ddOptionsPage.php';
}

// enqueue and localise scripts for ajax
function ddcf_enqueue_front_end_pages () {
	wp_enqueue_script( 'my-ajax-handle', plugins_url().'/dd-contact-form/js/ajax.js', array( 'jquery' ) );
	wp_localize_script( 'my-ajax-handle', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
}
function ddcf_enqueue_back_end_pages () {
	wp_enqueue_script( 'my-ajax-handle', plugins_url().'/dd-contact-form/js/ajax.js', array( 'jquery' ) );
	wp_localize_script( 'my-ajax-handle', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
}

function ddcf_contacts_dashboard_widget_function() {
	echo '<!--a href="manager/">Click here for the contact manager page...</a-->';}

/*function ddcf_bookings_dashboard_widget_function() {
	echo '<a href="../ddcf-manager/">Click here</a>';}*/

function ddcf_add_dashboard_widgets() {
	wp_add_dashboard_widget('ddcf_contacts_dashboard_widget', 'Bookings and Contacts', 'ddcf_contacts_dashboard_widget_function');
	//wp_add_dashboard_widget('ddcf_bookings_dashboard_widget', 'BaC: Bookings', 'ddcf_bookings_dashboard_widget_function');

	// Globalize the metaboxes array, this holds all the widgets for wp-admin
	global $wp_meta_boxes;

	// Get the regular dashboard widgets array
	// (which has our new widget already but at the end)
	$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

	// Backup and delete our new dashboard widget from the end of the array
	$ddcf_widget_backup = array('ddcf_contacts_dashboard_widget' => $normal_dashboard['ddcf_contacts_dashboard_widget']);
	unset($normal_dashboard['ddcf_contacts_dashboard_widget']);

	// Merge the two arrays together so our widget is at the beginning
	$sorted_dashboard = array_merge($ddcf_widget_backup, $normal_dashboard);

	// Save the sorted array back into the original metaboxes
	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
}


// Remove useless wdgets
function example_remove_dashboard_widgets() {
	/* remove_meta_box( 'dashboard_quick_press',    'dashboard', 'side' );
	//remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_plugins',        'dashboard', 'normal' );
	//remove_meta_box( 'dashboard_recent_comments','dashboard', 'normal' );
	remove_meta_box( 'dashboard_recent_drafts',  'dashboard', 'side' );
	remove_meta_box( 'dashboard_primary',        'dashboard', 'side' );
	remove_meta_box( 'dashboard_secondary',      'dashboard', 'side' );


	complete list of wdgets
	$wp_meta_boxes['dashboard']['normal']['high']['dashboard_browser_nag']
	$wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']
	$wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']
	$wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']
	$wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']

	$wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']
	$wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']
	$wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']
	$wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']
	*/
}

function add_ddcf_options_to_menu() {
	add_options_page( 'Contact Form Options', 'DD Contact Form', 'administrator', '__FILE__', 'ddcf_options_page');
}

function ddcf_admin_init() {

	// enqueue and localise script for ajax query handling
	wp_enqueue_script( 'my-ajax-handle', plugins_url().'/dd-contact-form/js/ajax.js', array( 'jquery' ) );
	wp_localize_script( 'my-ajax-handle', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));

	register_setting( 'ddcf-settings-group', 'ddcf_enquiries_email_one' );
	register_setting( 'ddcf-settings-group', 'ddcf_enquiries_email_two' );
	register_setting( 'ddcf-settings-group', 'ddcf_enquiries_email_three');
	register_setting( 'ddcf-settings-group', 'ddcf_enquiries_email_four' );
	register_setting( 'ddcf-settings-group', 'ddcf_enquiries_email_five' );
	register_setting( 'ddcf-settings-group', 'ddcf_email_confirmation' );
	register_setting( 'ddcf-settings-group', 'ddcf_email_confirmation_text' );
	register_setting( 'ddcf-settings-group', 'ddcf_booking_start' );
	register_setting( 'ddcf-settings-group', 'ddcf_booking_end' );
	register_setting( 'ddcf-settings-group', 'ddcf_start_date_check' );
	register_setting( 'ddcf-settings-group', 'ddcf_start_date_time_check' );
        register_setting( 'ddcf-settings-group', 'ddcf_end_date_check' );
	register_setting( 'ddcf-settings-group', 'ddcf_end_date_time_check' );
        register_setting( 'ddcf-settings-group', 'ddcf_dates_compulsory_check' );
        register_setting( 'ddcf-settings-group', 'ddcf_dates_category_filter_check' );
        register_setting( 'ddcf-settings-group', 'ddcf_dates_category_filter' );
	register_setting( 'ddcf-settings-group', 'ddcf_extra_question_one_check' );
	register_setting( 'ddcf-settings-group', 'ddcf_extra_question_two_check' );
	register_setting( 'ddcf-settings-group', 'ddcf_extra_question_one' );
	register_setting( 'ddcf-settings-group', 'ddcf_extra_question_two' );
	register_setting( 'ddcf-settings-group', 'ddcf_extra_dropdown_one_check' );
	register_setting( 'ddcf-settings-group', 'ddcf_extra_dropdown_two_check' );
        register_setting( 'ddcf-settings-group', 'ddcf_party_size_compulsory_check' );
        register_setting( 'ddcf-settings-group', 'ddcf_party_size_category_filter' );
        register_setting( 'ddcf-settings-group', 'ddcf_party_size_category_filter_check' );
	register_setting( 'ddcf-settings-group', 'ddcf_captcha_type' );
        register_setting( 'ddcf-settings-group', 'ddcf_recaptcha_public_key' );
        register_setting( 'ddcf-settings-group', 'ddcf_recaptcha_private_key' );
	register_setting( 'ddcf-settings-group', 'ddcf_recaptcha_theme' );
	register_setting( 'ddcf-settings-group', 'ddcf_extra_question_category_filter_check' );
	register_setting( 'ddcf-settings-group', 'ddcf_extra_question_category_filter' );
	//register_setting( 'ddcf-settings-group', 'ddcf_extra_details_category_filter_check' );
	//register_setting( 'ddcf-settings-group', 'ddcf_extra_details_category_filter' );
        register_setting( 'ddcf-settings-group', 'ddcf_questions_compulsory_check' );
	register_setting( 'ddcf-settings-group', 'ddcf_form_theme' );
	register_setting( 'ddcf-settings-group', 'ddcf_jqueryui_theme' );
	register_setting( 'ddcf-settings-group', 'ddcf_geo_ip_option_check' );
        register_setting( 'ddcf-settings-group', 'ddcf_geoloc_key' );
        register_setting( 'ddcf-settings-group', 'ddcf_rec_updates_option_check' );
	register_setting( 'ddcf-settings-group', 'ddcf_rec_updates_message_check' );
	register_setting( 'ddcf-settings-group', 'ddcf_rec_updates_message' );
	register_setting( 'ddcf-settings-group', 'ddcf_thankyou_type' );
        register_setting( 'ddcf-settings-group', 'ddcf_thankyou_message' );
        register_setting( 'ddcf-settings-group', 'ddcf_thankyou_url' );
	register_setting( 'ddcf-settings-group', 'ddcf_tooltips_check' );
	register_setting( 'ddcf-settings-group', 'ddcf_email_header' );
	//register_setting( 'ddcf-settings-group', 'ddcf_bookable_category' );
        register_setting( 'ddcf-settings-group', 'ddcf_error_checking_method' );

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
		// types are: phone, email, address, website, skype, facebook, linkedin, twitter
		$sql = "INSERT INTO ".$table_name ." (contact_role_index, contact_role) VALUES
//											   (null, 'associate'),
//											   (null, 'client'),
//											   (null, 'guest'),
//											   (null, 'customer'),
											   (null, 'all contact types')";
		if(!dbDelta($sql)) trigger_error ("Unable to update database");
	}

	// contact relations
//	$table_name = $wpdb->prefix . "contact_relations";
//	$sql = 'CREATE TABLE IF NOT EXISTS ' .$table_name . '(
//		contact_relation_index INTEGER(10) NOT NULL AUTO_INCREMENT,
//		contact_relative_one_id INT,
//		contact_relative_two_id INT,
//		contact_relationship TEXT,
//		PRIMARY KEY (contact_relation_index) )';
//	if(!dbDelta($sql)) trigger_error ("Unable to update database");


	// contact notes
	/*$table_name = $wpdb->prefix . "contact_note";
	$sql = 'CREATE TABLE IF NOT EXISTS ' .$table_name . '(
		note_id INTEGER(10) NOT NULL AUTO_INCREMENT,
		contact_id INT,
		note_author_id INT,
		note_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		note TEXT,
		PRIMARY KEY (note_id) )';
	if(!dbDelta($sql)) trigger_error ( "Unable to update database" );*/


	// bookings stuff - TODO
	/*$table_name = $wpdb->prefix . "booking";
	$sql = 'CREATE TABLE IF NOT EXISTS ' .$table_name . '(
		booking_id INTEGER(10) NOT NULL AUTO_INCREMENT,
		bookable_id INT,
		guest_id INT,
		agent_id INT,
		arrival_date DATETIME,
		departure_date DATETIME,
		booking_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		booking_status TEXT,
		notes TEXT,
		PRIMARY KEY (booking_id) )';
	if(!dbDelta($sql)) trigger_error ( "Unable to update database" );


	$table_name = $wpdb->prefix . "booking_status_types";
	$sql = 'CREATE TABLE IF NOT EXISTS ' .$table_name . '(
	booking_status_type_index	 INTEGER(10) NOT NULL AUTO_INCREMENT,
	booking_status_type TEXT,
	PRIMARY KEY (booking_status_type_index) )';
	if(!dbDelta($sql)) trigger_error ( "Unable to update database" );

	$populated = mysql_result(mysql_query('SELECT COUNT(*) FROM '.$table_name), 0);
	if(!$populated) {
		// types are: confirmed, deposit paid, booked, held
		$sql = "INSERT INTO ".$table_name ."   (booking_status_type_index, booking_status_type) VALUES
											   (null, 'confirmed'),
											   (null, 'deposit paid'),
											   (null, 'booked'),
											   (null, 'held')";
		if(!dbDelta($sql)) trigger_error ( "Unable to update database" );
	}


	$table_name = $wpdb->prefix . "bookable_rates";
	$sql = 'CREATE TABLE IF NOT EXISTS ' .$table_name . '(
		rate_id INTEGER(10) NOT NULL AUTO_INCREMENT,
		bookable_id INT,
		booking_rate DECIMAL,
		booking_rate_string TEXT,
		booking_period_start DATETIME,
		booking_period_end DATETIME,
		booking_period_label TEXT,
		booking_period TEXT,
		PRIMARY KEY (rate_id) )';
	if(!dbDelta($sql)) trigger_error ( "Unable to update database" );


	$table_name = $wpdb->prefix . "bookable_rates_period";
	$sql = 'CREATE TABLE IF NOT EXISTS ' .$table_name . '(
	booking_period_index	 INTEGER(10) NOT NULL AUTO_INCREMENT,
	booking_period TEXT,
	PRIMARY KEY (booking_period_index) )';
	if(!dbDelta($sql)) trigger_error ( "Unable to update database" );

	$populated = mysql_result(mysql_query('SELECT COUNT(*) FROM '.$table_name), 0);
	if(!$populated) {
		// booking_period are by the; minute, hour, day, week, month, year or a long lease)
		$sql = "INSERT INTO ".$table_name ." (booking_period_index, booking_period) VALUES
											   (null, 'minute'),
											   (null, 'hour'),
											   (null, 'week'),
											   (null, 'month'),
											   (null, 'year'),
											   (null, 'long lease')";
		if(!dbDelta($sql)) trigger_error ( "Unable to update database" );
	}



	$kplv_special = true;
	$table_name = $wpdb->prefix . "bookable";
	if($kplv_special) {
		$sql = 'CREATE TABLE IF NOT EXISTS ' .$table_name . '(
			bookable_id INTEGER(10) NOT NULL AUTO_INCREMENT,
			owner_id INT,
			bookable_post_id INT,
			PRIMARY KEY (rate_id) )';
		if(!dbDelta($sql)) trigger_error ( "Unable to update database" );
	}
	else {
		$sql = 'CREATE TABLE IF NOT EXISTS ' .$table_name . '(
			bookable_id INTEGER(10) NOT NULL AUTO_INCREMENT,
			owner_id INT,
			bookable_post_id INT,
			PRIMARY KEY (bookable_id) )';
		if(!dbDelta($sql)) trigger_error ( "Unable to update database" );
	}*/
}

//function dd_contact_form_deactivation()
//{
//
//}

function dd_contact_form_uninstall()
{
	// this doesn't seem to work...
	delete_option('ddcf-settings-group','0.1');
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
//	$sql = "DROP TABLE IF EXISTS " . $wpdb->prefix .'contact_relations;';
//	$wpdb->query($sql);
//	$sql = "DROP TABLE IF EXISTS " . $wpdb->prefix .'contact_note;';
//	$wpdb->query($sql);
//	$sql = "DROP TABLE IF EXISTS " . $wpdb->prefix .'booking;';
//	$wpdb->query($sql);
//	$sql = "DROP TABLE IF EXISTS " . $wpdb->prefix .'booking_status_types;';
//	$wpdb->query($sql);
//	$sql = "DROP TABLE IF EXISTS " . $wpdb->prefix .'bookable_rates;';
//	$wpdb->query($sql);
//	$sql = "DROP TABLE IF EXISTS " . $wpdb->prefix .'bookable_rates_period;';
//	$wpdb->query($sql);
}

/* Adds a box to the main column on the Post and Page edit screens 
function ddcf_add_custom_box() {
    $screens = array( 'post', 'page' );
    foreach ($screens as $screen) {
        add_meta_box(
            'ddcf_sectionid',
            __( 'Bookings and Contacts', 'ddcf-plugin' ),
            'ddcf_inner_custom_box',
            $screen
        );
    }
}*/

/* Prints the meta box content 
function ddcf_inner_custom_box( $post ) {

  // Use nonce for verification
  wp_nonce_field( plugin_basename( __FILE__ ), 'ddcf_noncename' );

  // The actual fields for data entry
  // Use get_post_meta to retrieve an existing value from the database and use the value for the form
  $value = get_post_meta( $post->ID, '_my_meta_value_key', true );
  echo '<label for="ddcf_owner_field">';
       _e("Owner ID", 'ddcf-plugin' );
  echo '</label> ';
  echo '<input type="text" id="ddcf_owner_field" name="ddcf_owner_field" value="'.esc_attr($value).'" size="25" />';
}*/

/* When the post is saved, saves our custom data */
//function ddcf_save_postdata( $post_id ) {
//
//  // First we need to check if the current user is authorised to do this action.
//  if ( 'page' == $_POST['post_type'] ) {
//    if ( ! current_user_can( 'edit_page', $post_id ) )
//        return;
//  } else {
//    if ( ! current_user_can( 'edit_post', $post_id ) )
//        return;
//  }
//
//  // Secondly we need to check if the user intended to change this value.
//  if ( ! isset( $_POST['ddcf_noncename'] ) || ! wp_verify_nonce( $_POST['ddcf_noncename'], plugin_basename( __FILE__ ) ) )
//      return;
//
//  // Thirdly we can save the value to the database
//  //if saving in a custom table, get post_ID
//  $post_ID = $_POST['post_ID'];
//  //sanitize user input
//  $mydata = sanitize_text_field( $_POST['ddcf_owner_field'] );
//
//  // Do something with $mydata
//  // either using
//  add_post_meta($post_ID, 'bookable_owner_id', $mydata, true) or
//    update_post_meta($post_ID, 'bookable_owner_id', $mydata);
//  // or a custom table
//}


?>