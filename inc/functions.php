<?php
    /*
        This file is part of Davedub's Contact Form plugin for WordPress

        Author: David Wooldridge

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
    
    // debug helper
	function debug_to_console($data) {
		if (is_array( $data) )
		$output = "<script>console.log( 'Debug: " . implode( ',', $data) . "' );</script>";
		else
		$output = "<script>console.log( 'Debug: " . $data . "' );</script>";
		echo $output;
	}    

    // string helper (http://stackoverflow.com/questions/5696412/get-substring-between-two-strings-php)
    function get_string_between($string, $start, $end) {
    	$string = " ".$string;
    	$ini = strpos($string,$start);
    	if ($ini == 0) return "";
    	$ini += strlen($start);
    	$len = strpos($string,$end,$ini) - $ini;
    	return substr($string,$ini,$len);
    }

    // Add Ajax actions
    add_action('wp_ajax_the_ajax_hook', 'receive_jquery_ajax_call');
    add_action('wp_ajax_nopriv_the_ajax_hook', 'receive_jquery_ajax_call'); // need this to serve non logged in users

    // Ajax call receiving function
    function receive_jquery_ajax_call() {
        session_start();

        // check requested session type
        if ($_POST['ddcf_session']=='ddcf_manager_session') {
            include 'ddManagerSession.php';
            die(); // end of ddcf_manager_session
        } else if ($_POST['ddcf_session']=='ddcf_contact_session') {
            // Contact form
            include 'ddContactSession.php';
            die();
        } else if ($_POST['ddcf_session']=='ddcf_options_session') {
            
            // options ajax - updating css
            if (check_ajax_referer('ddcf_update_css_action', 'ddcf_update_css_nonce', false)) {
                // put passed css into db ready for next contact form load
                global $wpdb;
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                $errors = '';
                $table_name = $wpdb->prefix."custom_css";
                $data = stripslashes($_POST['ddcf_custom_css']); // filter_var($_POST['ddcf_custom_css'],FILTER_SANITIZE_STRING); //

                if (isset($_POST['ddcf_custom_css']) && current_user_can('edit_posts')) {
                    /* no sanitisation - because they're entering code whilst signed in, it's allowed. Just this once. */
                    /* tried using filter_var($_POST['ddcf_custom_css'],FILTER_SANITIZE_STRING); but it escaped the apos in the css gradient code */
                    $sql = "UPDATE ".$table_name ." SET custom_css_index = 1, custom_css_text = '".$data."'";
                    $success = $wpdb->update($table_name,
                                                array('custom_css_text' => $data),
                                                array('custom_css_index' => 1));
                    if (!$success) {
                        $errors.='Unable to update database';
                    }
                } else {
                    
                    if (!isset($_POST['ddcf_custom_css'])) $errors .= 'No CSS?';
                    else if (!current_user_can('edit_posts')) $errors .= 'Current user not able to edit posts';
                    else $errors = 'Unknown error';
                }

                $return = array('ddcf_error' => $errors,
                                'ddcf_custom_css' => $data);
            } else $return = array('ddcf_error' => '406');
            wp_send_json($return);
            die();
        } else {
            // unexpected request
            $return = array('ddcf_error' => 'Unexpected value');
            wp_send_json($return);
            die();
        }
    }

    // contact form shortcode
    function ddcf_contact_form($attributes) {
    	ob_start();// fix formatting
    	include 'ddContactPage.php';
    	return ob_get_clean();
    }

    // management page shortcode
    function ddcf_management_page() {
    	ob_start();// fix formatting
    	include 'ddManagerPage.php';
    	return ob_get_clean();
    }

    // dd contact form options pages
    function ddcf_options_page() {
    	include 'ddOptionsPage.php';
    }

    // wp_head action
    function ddcf_css_inject() {
        global $post;

        if (get_option(ddcf_custom_css_check) &&
            has_shortcode($post->post_content,'dd_contact_form')) {
            /* inject any custom css specified in the settings */
            global $wpdb;
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            $table_name = $wpdb->prefix . "custom_css";
            $query = "SELECT custom_css_text FROM ".$table_name." WHERE custom_css_index = 1";
            $custom_css = $wpdb->get_row($query);
            echo '<style type="text/css">'
                     .$custom_css->custom_css_text.
                 '</style>';
        }
    }

    // enqueue and localise scripts
    function ddcf_enqueue_front_end_pages () {
        global $post;
        
        if (has_shortcode($post->post_content,'dd_contact_form') || has_shortcode($post->post_content,'dd_manager_page')) {        

            wp_enqueue_script('ddcf_ajax_handle', plugins_url().'/dd-contact-form/js/dd-contact-form-ajax.js', array('jquery'));
            wp_localize_script('ddcf_ajax_handle','ddcf_ajax_script',array('ajaxurl' => admin_url('admin-ajax.php')));

            wp_enqueue_script('ddcf_datetimepicker_script',
                plugins_url().'/dd-contact-form/js/jquery-ui-timepicker-addon.js',
                array('jquery',
                      'jquery-ui-core',
                      'jquery-ui-datepicker'));

            if (get_option(ddcf_captcha_type)=="reCaptcha") {
                wp_enqueue_script('ddcf_google_recaptcha', 'http://www.google.com/recaptcha/api/js/recaptcha_ajax.js');
            }
            
            if (has_shortcode($post->post_content, 'dd_contact_form'))
                wp_enqueue_script('ddcf_contact_form_script',
                             plugins_url().'/dd-contact-form/js/dd-contact-form.js',
                             array('jquery',
                                    'jquery-ui-core',
                                    'jquery-effects-core',
                                    'jquery-effects-explode',
                                    'jquery-ui-datepicker',
                                    'jquery-ui-button'));
            if (has_shortcode($post->post_content, 'dd_manager_page')&&current_user_can(read))
                wp_enqueue_script('ddcf_dashboard_script',
                                    plugins_url().'/dd-contact-form/js/dd-contact-form-manager.js',
                                    array( 'jquery',
                                           'jquery-ui-core',
                                           'jquery-ui-accordion',
                                           'jquery-ui-dialog',
                                           'jquery-ui-button'));

            /* enqueue css styles */

            /* jQuery UI */
            if (!has_shortcode($post->post_content, 'dd_manager_page')) {
                if ((get_option(ddcf_jqueryui_theme)!="none")&&
                   (get_option(ddcf_jqueryui_theme)!="custom")&&
                   (get_option(ddcf_jqueryui_theme)!=""))
                        wp_enqueue_style('ddcf_jqueryui_theme_style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/'.get_option(ddcf_jqueryui_theme).'/jquery-ui.css');
                else if (get_option(ddcf_jqueryui_theme)=="custom")
                        wp_enqueue_style('ddcf_jqueryui_theme_style', plugins_url().'/dd-contact-form/css/jquery-ui-custom.min.css');
                else    wp_enqueue_style('ddcf_jqueryui_theme_style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/smoothness/jquery-ui.css');
            }

            /* contact form */
            if (has_shortcode($post->post_content, 'dd_contact_form')) {
                wp_enqueue_style('ddcf_layout_style', plugins_url().'/dd-contact-form/css/style-dd-contact-page.css');
            }

            /* manager page */
            if (has_shortcode($post->post_content, 'dd_manager_page')&&current_user_can(read)) {
                /* jQuery UI */
                wp_enqueue_style('ddcf_jqueryui_theme_style', plugins_url().'/dd-contact-form/css/jquery-ui-wp-similar.min.css');
                wp_enqueue_style('ddcf_manager_layout_style', plugins_url().'/dd-contact-form/css/style-dd-manager-page.css');
            }
        }
    }

    function ddcf_enqueue_back_end_pages () {
        /* ajax */
    	wp_enqueue_script('ddcf_ajax_handle', plugins_url().'/dd-contact-form/js/dd-contact-form-ajax.js', array('jquery'));
    	wp_localize_script('ddcf_ajax_handle','ddcf_ajax_script',array('ajaxurl' => admin_url('admin-ajax.php')));

        /* options page js */
        wp_enqueue_script('ddcf_options_page_script', 
            plugins_url().'/dd-contact-form/js/dd-contact-form-options.js',
            array('jquery', 'jquery-ui-core',
                            'jquery-effects-core',
                            'jquery-ui-core',
                            'jquery-effects-fade',
                            'jquery-ui-accordion',
                            'jquery-ui-tabs'));

        //wp_enqueue_style('ddcf_normalise_style', plugins_url().
        //    '/dd-contact-form/css/normalise.css');
        wp_enqueue_style('ddcf_options_page_style', plugins_url().
            '/dd-contact-form/css/style-dd-options-page.css');

        /* jQuery UI */
        wp_enqueue_style('ddcf_jqueryui_theme_style',
            plugins_url().'/dd-contact-form/css/jquery-ui-wp-similar.min.css');
    }

    function add_ddcf_options_to_menu() {
    	add_options_page('Contact Form Options', 'DD Contact Form', 'administrator', '__FILE__', 'ddcf_options_page');
    }

    function ddcf_plugin_settings_link($links, $file) {
        
        // check to make sure we are on the correct plugin
        if ($file == 'dd-contact-form/ddContactForm.php') {
            $settings_link = '<a href="options-general.php?page=__FILE__">Settings</a>';
            array_unshift($links, $settings_link);
        }
        return $links;
    }

    function ddcf_admin_init() {
		/* register settings */
		
        /* General Settings: Security settings */
        register_setting('ddcf_settings_group', 'ddcf_captcha_type');
		if (get_option('ddcf_captcha_type') == '')
			update_option('ddcf_captcha_type', 'Simple Addition');
        register_setting('ddcf_settings_group', 'ddcf_recaptcha_public_key');
        register_setting('ddcf_settings_group', 'ddcf_recaptcha_private_key');
        register_setting('ddcf_settings_group', 'ddcf_recaptcha_theme');
        
        /* General Settings: Privacy settings */
        register_setting('ddcf_settings_group', 'ddcf_save_user_details_check');
        register_setting('ddcf_settings_group', 'ddcf_offer_to_register_check');
        register_setting('ddcf_settings_group', 'ddcf_offer_to_register_custom_message_check');
        register_setting('ddcf_settings_group', 'ddcf_offer_to_register_custom_message');
		if (get_option('ddcf_offer_to_register_custom_message') == '')
			update_option('ddcf_offer_to_register_custom_message', 'Sign up for updates?');		
		
        /* General Settings: Geolocation settings */
        register_setting('ddcf_settings_group', 'ddcf_geolocation_check');
        register_setting('ddcf_settings_group', 'ddcf_geoloc_key');
        
        /* General Settings: Email settings */
        register_setting('ddcf_settings_group', 'ddcf_enquiries_email_one');
		if (get_option('ddcf_enquiries_email_one') == '')
			update_option('ddcf_enquiries_email_one', get_option('admin_email'));			
        register_setting('ddcf_settings_group', 'ddcf_enquiries_email_two');
        register_setting('ddcf_settings_group', 'ddcf_enquiries_email_three');
        register_setting('ddcf_settings_group', 'ddcf_enquiries_email_four');
        register_setting('ddcf_settings_group', 'ddcf_enquiries_email_five');
        register_setting('ddcf_settings_group', 'ddcf_email_confirmation_check');       
        register_setting('ddcf_settings_group', 'ddcf_email_confirmation_text');
		if (get_option('ddcf_email_confirmation_text') == '')
			update_option('ddcf_email_confirmation_text', 
                __('This is an automatically generated message to let you know your message has been received. Please do not reply to this email. We will be in touch shortly.'));	  
        register_setting('ddcf_settings_group', 'ddcf_email_header_check');       
        register_setting('ddcf_settings_group', 'ddcf_email_header_image_url');
        register_setting('ddcf_settings_group', 'ddcf_email_footer_check');
        register_setting('ddcf_settings_group', 'ddcf_email_footer_text');
        if (get_option('ddcf_email_footer_text') == '')
            update_option('ddcf_email_footer_text', '<p style="font-size:0.7em;">'.__('The information contained in this email is confidential and may contain proprietary information. It is meant solely for the intended recipient. Access to this email by anyone else is unauthorised. If you are not the intended recipient, any disclosure, copying, distribution or any action taken or omitted in reliance on this, is prohibited and may be unlawful. No liability or responsibility is accepted if information or data is, for whatever reason corrupted or does not reach its intended recipient. The views expressed in this email are, unless otherwise stated, those of the author and not those of the website owners or any of its subsidiaries or its management. The website owner and its subsidiaries reserves the right to monitor, intercept and block emails addressed to its users or take any other action in accordance with its email use policy.'));    
        
        /* User Interface: User feedback */
        register_setting('ddcf_settings_group', 'ddcf_thankyou_type');
		if (get_option('ddcf_thankyou_type') == '')
			update_option('ddcf_thankyou_type', 'ddcf_thankyou_message');        
        register_setting('ddcf_settings_group', 'ddcf_thankyou_message');
		if (get_option('ddcf_thankyou_message') == '')
			update_option('ddcf_thankyou_message', _('Thank you for your enquiry.<br /><br />A representative will be in touch shortly.'));  
        register_setting('ddcf_settings_group', 'ddcf_thankyou_url');
		if (get_option('ddcf_thankyou_url') == '')
			update_option('ddcf_thankyou_url', '/');
        register_setting('ddcf_settings_group', 'ddcf_error_checking_method');
		if (get_option('ddcf_error_checking_method') == '')
			update_option('ddcf_error_checking_method', 'realtime');
        
        /* User Interface: Appearance */
        register_setting('ddcf_settings_group', 'ddcf_input_css_classes');
        register_setting('ddcf_settings_group', 'ddcf_button_css_classes');
        register_setting('ddcf_settings_group', 'ddcf_button_styling');
        register_setting('ddcf_settings_group', 'ddcf_jqueryui_theme');
        
        /* Extra Information: Dates and Times */
        register_setting('ddcf_settings_group', 'ddcf_start_date_check');
        register_setting('ddcf_settings_group', 'ddcf_start_date');
        register_setting('ddcf_settings_group', 'ddcf_end_date_check');
        register_setting('ddcf_settings_group', 'ddcf_end_date');
        register_setting('ddcf_settings_group', 'ddcf_time_with_date_check');
        register_setting('ddcf_settings_group', 'ddcf_dates_compulsory_check');
        register_setting('ddcf_settings_group', 'ddcf_dates_category_filter_check');
        register_setting('ddcf_settings_group', 'ddcf_dates_category_filter');
        
        /* Extra Information: Numbers (e.g. Party size) */
    	register_setting('ddcf_settings_group', 'ddcf_extra_dropdown_one_check');
        register_setting('ddcf_settings_group', 'ddcf_extra_dropdown_one_label');
		if (get_option('ddcf_extra_dropdown_one_label') == '')
			update_option('ddcf_extra_dropdown_one_label', _('Adults'));		
    	register_setting('ddcf_settings_group', 'ddcf_extra_dropdown_two_check');
        register_setting('ddcf_settings_group', 'ddcf_extra_dropdown_two_label');
		if (get_option('ddcf_extra_dropdown_two_label') == '')
			update_option('ddcf_extra_dropdown_two_label', _('Children'));	
        register_setting('ddcf_settings_group', 'ddcf_numbers_compulsory_check');
        register_setting('ddcf_settings_group', 'ddcf_numbers_category_filter');
        register_setting('ddcf_settings_group', 'ddcf_dropdown_category_filter_check');

        /* Extra Information: Additional questions */
        register_setting('ddcf_settings_group', 'ddcf_extra_question_one_check');
        register_setting('ddcf_settings_group', 'ddcf_extra_question_one');
        register_setting('ddcf_settings_group', 'ddcf_extra_question_two_check');
        register_setting('ddcf_settings_group', 'ddcf_extra_question_two');
        register_setting('ddcf_settings_group', 'ddcf_questions_compulsory_check');
        register_setting('ddcf_settings_group', 'ddcf_extra_questions_category_filter_check');
        register_setting('ddcf_settings_group', 'ddcf_extra_questions_category_filter');
        
        /* Custom CSS: Use custom CSS */
        register_setting('ddcf_settings_group', 'ddcf_custom_css_check');
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //                                              	Activation, Deactivation, Removal
    //                                                          (Database setup)
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function dd_contact_form_activation()
    {
    	// check DB for required tables and create if not present
        //error_reporting(E_ERROR | E_PARSE);
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
                referrer_id TEXT,
                PRIMARY KEY  (enquiry_id))';
    	if (!dbDelta($sql)) trigger_error ("Unable to update database");

    	// contact table to log contact name and type
    	$table_name = $wpdb->prefix . "contact";
    	$sql = 'CREATE TABLE IF NOT EXISTS ' .$table_name . '(
                contact_id INTEGER(10) NOT NULL AUTO_INCREMENT,
                contact_name TEXT,
                first_registered TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                contact_type TEXT,
                wordpress_id INT,
                PRIMARY KEY (contact_id))';
    	if (!dbDelta($sql)) trigger_error ("Unable to update database");

    	// contact_info table for email addresses, phone numbers etc
    	$table_name = $wpdb->prefix . "contact_info";
    	$sql = 'CREATE TABLE IF NOT EXISTS ' .$table_name . '(
                info_id INTEGER(10) NOT NULL AUTO_INCREMENT,
                contact_id INT,
                contact_info TEXT,
                info_type TEXT,
                special_instructions TEXT,
                PRIMARY KEY (info_id))';
    	if (!dbDelta($sql)) trigger_error ("Unable to update database");

    	// contact_info_methods table for email addresses, phone numbers etc
    	$table_name = $wpdb->prefix . "contact_methods";
    	$sql = 'CREATE TABLE IF NOT EXISTS ' .$table_name . '(
    		    contact_method_index INTEGER(10) NOT NULL AUTO_INCREMENT,
    		    contact_method TEXT,
    		    PRIMARY KEY (contact_method_index))';
    	
        if (!dbDelta($sql)) {
            trigger_error ("Unable to update database");
        }
    	$populated = mysql_result(mysql_query('SELECT COUNT(*) FROM '.$table_name), 0);
    	
        if (!$populated) {
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
    		
            if (!dbDelta($sql)) {
                trigger_error ("Unable to update database");
            }
    	}

    	// contact_roles (associate, owner, client, guest, customer)
    	$table_name = $wpdb->prefix . "contact_roles";
    	$sql = 'CREATE TABLE IF NOT EXISTS ' .$table_name . '(
    		    contact_role_index INTEGER(10) NOT NULL AUTO_INCREMENT,
    		    contact_role TEXT,
    		    PRIMARY KEY (contact_role_index))';
    	
        if (!dbDelta($sql)) {
            trigger_error ("Unable to update database");
        }
    	$populated = mysql_result(mysql_query('SELECT COUNT(*) FROM '.$table_name), 0);
    	
        if (!$populated) {
    		$sql = "INSERT INTO ".$table_name ." (contact_role_index, contact_role) VALUES
    											   (null, 'all contact types')";
            if (!dbDelta($sql)) {
                trigger_error ("Unable to update database");
            }
    	}
        // TODO: add contact roles:
        // (null, 'associate'),
        // (null, 'client'),
        // (null, 'guest'),
        // (null, 'customer'),    
        
    	// stores the custom css specified on the settings page
    	$table_name = $wpdb->prefix . "custom_css";
    	$sql = 'CREATE TABLE IF NOT EXISTS ' .$table_name . '(
    		    custom_css_index INTEGER(10) NOT NULL,
    		    custom_css_text MEDIUMTEXT,
    		    PRIMARY KEY (custom_css_index))';
    	if (!dbDelta($sql)) trigger_error ("Unable to update database");
            $populated = mysql_result(mysql_query('SELECT COUNT(*) FROM '.$table_name), 0);
    	if (!$populated) {
                $sql = "INSERT INTO ".$table_name ." VALUES ('1', '/* You can add your own custom css here. */')";
                if (!dbDelta($sql)) trigger_error ("Unable to update database");
        }
		file_put_contents(__DIR__.'/my_loggg.txt', ob_get_contents());
    }

    function dd_contact_form_uninstall()
    {
    	// remove all settings
        /* General Settings: Security settings */
        unregister_setting('ddcf_settings_group', 'ddcf_captcha_type');
        unregister_setting('ddcf_settings_group', 'ddcf_recaptcha_public_key');
        unregister_setting('ddcf_settings_group', 'ddcf_recaptcha_private_key');
        unregister_setting('ddcf_settings_group', 'ddcf_recaptcha_theme');
        
        /* General Settings: Privacy settings */
        unregister_setting('ddcf_settings_group', 'ddcf_save_user_details_check');
        unregister_setting('ddcf_settings_group', 'ddcf_offer_to_register_check');
        unregister_setting('ddcf_settings_group', 'ddcf_offer_to_register_custom_message_check');
        unregister_setting('ddcf_settings_group', 'ddcf_offer_to_register_custom_message');
        
        /* General Settings: Geolocation settings */
        unregister_setting('ddcf_settings_group', 'ddcf_geolocation_check');
        unregister_setting('ddcf_settings_group', 'ddcf_geoloc_key');
        
        /* General Settings: Email settings */
        unregister_setting('ddcf_settings_group', 'ddcf_enquiries_email_one');
        unregister_setting('ddcf_settings_group', 'ddcf_enquiries_email_two');
        unregister_setting('ddcf_settings_group', 'ddcf_enquiries_email_three');
        unregister_setting('ddcf_settings_group', 'ddcf_enquiries_email_four');
        unregister_setting('ddcf_settings_group', 'ddcf_enquiries_email_five');
        unregister_setting('ddcf_settings_group', 'ddcf_email_confirmation_check');
        unregister_setting('ddcf_settings_group', 'ddcf_email_confirmation_text');
        unregister_setting('ddcf_settings_group', 'ddcf_email_header_check');
        unregister_setting('ddcf_settings_group', 'ddcf_email_header_image_url');
        unregister_setting('ddcf_settings_group', 'ddcf_email_footer_check');
        unregister_setting('ddcf_settings_group', 'ddcf_email_footer_text');
        
        /* User Interface: User feedback */
        unregister_setting('ddcf_settings_group', 'ddcf_thankyou_type');
        unregister_setting('ddcf_settings_group', 'ddcf_thankyou_message');
        unregister_setting('ddcf_settings_group', 'ddcf_thankyou_url');
        unregister_setting('ddcf_settings_group', 'ddcf_error_checking_method');
        
        /* User Interface: Appearance */
        unregister_setting('ddcf_settings_group', 'ddcf_input_css_classes');
        unregister_setting('ddcf_settings_group', 'ddcf_button_css_classes');
        unregister_setting('ddcf_settings_group', 'ddcf_button_styling');
        unregister_setting('ddcf_settings_group', 'ddcf_jqueryui_theme');
        
        /* Extra Information: Dates and Times */
        unregister_setting('ddcf_settings_group', 'ddcf_start_date_check');
        unregister_setting('ddcf_settings_group', 'ddcf_start_date');
        unregister_setting('ddcf_settings_group', 'ddcf_end_date_check');
        unregister_setting('ddcf_settings_group', 'ddcf_end_date');
        unregister_setting('ddcf_settings_group', 'ddcf_time_with_date_check');
        unregister_setting('ddcf_settings_group', 'ddcf_dates_compulsory_check');
        unregister_setting('ddcf_settings_group', 'ddcf_dates_category_filter_check');
        unregister_setting('ddcf_settings_group', 'ddcf_dates_category_filter');        
        
        /* Extra Information: Numbers (e.g. Party size) */
    	unregister_setting('ddcf_settings_group', 'ddcf_extra_dropdown_one_check');
        unregister_setting('ddcf_settings_group', 'ddcf_extra_dropdown_one_label');
    	unregister_setting('ddcf_settings_group', 'ddcf_extra_dropdown_two_check');
        unregister_setting('ddcf_settings_group', 'ddcf_extra_dropdown_two_label');
        unregister_setting('ddcf_settings_group', 'ddcf_numbers_compulsory_check');
        unregister_setting('ddcf_settings_group', 'ddcf_numbers_category_filter');
        unregister_setting('ddcf_settings_group', 'ddcf_dropdown_category_filter_check');

        /* Extra Information: Additional questions */
        unregister_setting('ddcf_settings_group', 'ddcf_extra_question_one_check');
        unregister_setting('ddcf_settings_group', 'ddcf_extra_question_one');
        unregister_setting('ddcf_settings_group', 'ddcf_extra_question_two_check');
        unregister_setting('ddcf_settings_group', 'ddcf_extra_question_two');
        unregister_setting('ddcf_settings_group', 'ddcf_questions_compulsory_check');
        unregister_setting('ddcf_settings_group', 'ddcf_extra_questions_category_filter_check');
        unregister_setting('ddcf_settings_group', 'ddcf_extra_questions_category_filter');
        
        /* Custom CSS: Use custom CSS */
        unregister_setting('ddcf_settings_group', 'ddcf_custom_css_check');
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
    	$sql = "DROP TABLE IF EXISTS " . $wpdb->prefix .'custom_css;';
    	$wpdb->query($sql);
    }
    ?>