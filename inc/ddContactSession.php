<?php

/*
    This file is part of Davedub's Contact Form plugin for WordPress

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

		// helper functions
		function initialise_form($message) {

			// Which Captcha are we using?
			$captcha_type = get_option(ddcf_captcha_type);
			if(!$captcha_type) $captcha_type = 'Simple Add'; // for first run, no setting set yet
			switch($captcha_type)
			{
			case 'None':
				$return = array(
					'ddcf_error' => $message
				);
				wp_send_json($return);
				die();
				break;
			case 'Simple Add':
				$_SESSION['bacc_one']=rand(1,15);
				$_SESSION['bacc_two']=rand(1,15);
				$return = array(
					'ddcf_captcha_one_value'		=> $_SESSION['bacc_one'],
					'ddcf_captcha_two_value'		=> $_SESSION['bacc_two'],
					'ddcf_error' => $message
				);
				wp_send_json($return);
				die();
				break;
			case 'reCaptcha':
                                if($message=='reCaptcha Error') {
                                    $user_feedback = _('The reCaptcha is incorrect. Please try again', 'ddcf-plugin');
                                    $return = array(
					'ddcf_error' => $user_feedback
                                    );
                                }
				else $return = array(
					'ddcf_error' => $message
				);
				wp_send_json($return);
				die();
				break;
			default:
				$return = array(
					'ddcf_error' => 'Default: '+$message
				);
				wp_send_json($return);
				die();
				break;
			}
		}
		// helper funtion to set the mail type after sending
		function set_html_content_type()
		{
			return 'text/html';
		}


//////////////////////////////////
/* 			Start Here			*/
//////////////////////////////////

	if($_POST['ddcf_session_initialised']=='uninitialised') {
		$check = check_ajax_referer('ddcf_contact_initialise_action','ddcf_init_nonce',false);
		if($check) initialise_form('');
		else die();
	}


	$check = check_ajax_referer('ddcf_contact_submit_action','ddcf_submit_nonce',false);



	if($check) {

		// Captcha passed?
		switch(get_option(ddcf_captcha_type))
		{
		case 'None':
			$return = array(
				'ddcf_error' => 'Success!',
				'ddcf_thankyou_message' => get_option(ddcf_thankyou_message)
			);
			break;
		case 'reCaptcha':
			require_once('recaptchalib.php');
			$privatekey = get_option(ddcf_recaptcha_private_key);
			$response = recaptcha_check_answer ($privatekey,
                                                            $_SERVER["REMOTE_ADDR"],
                                                            $_POST["recaptcha_challenge_field"],
                                                            $_POST["recaptcha_response_field"]);
			if (!$response->is_valid) {
				initialise_form('reCaptcha Error');
				die();
				break;
			}
			else
			{
				$return = array(
					'ddcf_error' => 'Success!',
					'ddcf_thankyou_message' => get_option(ddcf_thankyou_message)
				);
			}
			break;
		default:
		        // default case 'Simple Add':
			if($_POST["ddcf_contact_captcha_add"] == ($_SESSION['bacc_one'] +$_SESSION['bacc_two']) )
			{
				$return = array(
					'ddcf_error' => 'Success!',
					'ddcf_thankyou_message' => get_option(ddcf_thankyou_message)
				);
				break;
			}
			else initialise_form('Captcha wrong - try this one');
		}
	}
	else initialise_form('nocheck');

	// good to here? then verify form inputs

	// visual inputs
	$errors = '';
	if(isset($_POST['ddcf_contact_name'])&&!empty($_POST["ddcf_contact_name"]))
		$ddcf_contact_name = filter_var($_POST["ddcf_contact_name"],FILTER_SANITIZE_STRING);
	else $errors.='The name was not set<br />';
	if(isset($_POST['ddcf_contact_email'])&&!empty($_POST["ddcf_contact_email"])) {
		$ddcf_contact_email = filter_var($_POST['ddcf_contact_email'], FILTER_SANITIZE_EMAIL);
		if (!filter_var($ddcf_contact_email, FILTER_VALIDATE_EMAIL)) $errors.='The email format was not recognised<br />';
	}
	else $errors.='The email was not set<br />';
	if(isset($_POST['ddcf_contact_subject'])&&!empty($_POST["ddcf_contact_subject"])) $ddcf_contact_subject = filter_var($_POST['ddcf_contact_subject'], FILTER_SANITIZE_STRING);
	else $errors.='The subject was not set<br />';
	if(isset($_POST['ddcf_arrival_date'])&&!empty($_POST["ddcf_arrival_date"])) $ddcf_arrival_date = filter_var($_POST['ddcf_arrival_date'], FILTER_SANITIZE_STRING);
	else $errors.='The booking start date was not set<br />';
	if(isset($_POST['ddcf_departure_date'])&&!empty($_POST["ddcf_departure_date"])) $ddcf_departure_date = filter_var($_POST['ddcf_departure_date'], FILTER_SANITIZE_STRING);
	else $errors.='The booking end date was not set<br />';
	if(isset($_POST['ddcf_num_children'])&&!empty($_POST["ddcf_num_children"])) $ddcf_num_children = filter_var($_POST['ddcf_num_children'], FILTER_SANITIZE_STRING);
	else $ddcf_num_children = '0';
	if(isset($_POST['ddcf_num_adults'])&&!empty($_POST["ddcf_num_adults"])) $ddcf_num_adults = filter_var($_POST['ddcf_num_adults'], FILTER_SANITIZE_STRING);
	else $errors.='The number of adults was not set<br />';
	if(isset($_POST['ddcf_question_one'])&&!empty($_POST["ddcf_question_one"])) $ddcf_question_one = filter_var($_POST['ddcf_question_one'], FILTER_SANITIZE_STRING);
	else $errors.='Question one was not answered<br />';
	if(isset($_POST['ddcf_question_two'])&&!empty($_POST["ddcf_question_two"])) filter_var($ddcf_question_two = $_POST['ddcf_question_two'], FILTER_SANITIZE_STRING);
	else $errors.='Question two was not answered<br />';
	if(isset($_POST['ddcf_contact_message'])&&!empty($_POST["ddcf_contact_message"])) filter_var($ddcf_contact_message = $_POST['ddcf_contact_message'], FILTER_SANITIZE_STRING);
	else $errors.='There was no message<br />';
	if(isset($_POST['ddcf_newsletter_signup'])&&!empty($_POST["ddcf_newsletter_signup"])) $ddcf_newsletter_signup = true;
	else $ddcf_newsletter_signup = false;
	// hidden inputs
	if(isset($_POST['ddcf_post_title'])&&!empty($_POST["ddcf_post_title"])) $ddcf_post_title = filter_var($_POST['ddcf_post_title'], FILTER_SANITIZE_STRING);
	else $errors.='There was a problem with the Wordpress page<br />';
	if(isset($_POST['ddcf_ip_address'])&&!empty($_POST["ddcf_ip_address"]))
		$ddcf_ip_address = filter_var($_POST['ddcf_ip_address'], FILTER_SANITIZE_STRING);
	else $ddcf_ip_address = 'not set';
	if(isset($_POST['ddcf_country'])&&!empty($_POST["ddcf_country"]))
		$ddcf_country = filter_var($_POST['ddcf_country'], FILTER_SANITIZE_STRING);
	else $ddcf_country = 'not set';
	if(isset($_POST['ddcf_region'])&&!empty($_POST["ddcf_region"])) $ddcf_region = filter_var($_POST['ddcf_region'], FILTER_SANITIZE_STRING);
	else $ddcf_ip_address = 'not set';
	if(isset($_POST['ddcf_city'])&&!empty($_POST["ddcf_city"])) $ddcf_city = filter_var($_POST['ddcf_city'], FILTER_SANITIZE_STRING);
	else $ddcf_city = 'not set';


	// providing there were no verification errors
	// we log the enquiry, send out the emails and
	// send the success message back to the user
	if($errors.length>0)
		initialise_form($errors);
	else {

		// log contact, contact detail (email address) and enquiry to database

		global $wpdb;

		// first check for an existing contact id for this email address
		// if not, create a new contact and retrieve the new contact_id
		$table = $wpdb->prefix .'contact_info';
		$ddcf_contact_id = $wpdb->get_var( $wpdb->prepare( "SELECT contact_id FROM ".$table." WHERE contact_info = %s" , $ddcf_contact_email  ));
		if($ddcf_contact_id=='') {
			// create a new contact
			$table = $wpdb->prefix .'contact';
			$data = array(
				'contact_id' => NULL,
				'contact_name' => $ddcf_contact_name,
				//'first_registered' => NULL,
				'contact_type' => 'customer'
			);
			$wpdb->insert( $table, $data );

                        // now get our new contact id
                        //$mysql_query = $wpdb->prepare("SELECT contact_id FROM ".$table." WHERE first_registered IN (SELECT MAX( first_registered )) AND contact_name = %s LIMIT 1", $ddcf_contact_name);
                        $mysql_query = $wpdb->prepare("SELECT contact_id FROM ".$table." WHERE contact_name = %s ORDER BY first_registered DESC LIMIT 1", $ddcf_contact_name);
			$ddcf_contact_id = $wpdb->get_var($mysql_query);

			// create a contact info entry with new id and supplied email
    			$table = $wpdb->prefix .'contact_info';
			$data = array(
				'info_id' => NULL,
				'contact_id' => $ddcf_contact_id,
				'contact_info' => $ddcf_contact_email,
				'info_type' => 'email',
				'special_instructions' => 'from contact form enquiry'
			);
			$wpdb->insert( $table, $data );
		}

		// now we have a valid contact_id we can log the enquiry
		$table = $wpdb->prefix .'enquiries';
		$data = array(
			'enquiry_id'      => NULL,
			//'enquiry_date'  => NULL,
			'customer_name'   => $ddcf_contact_name,
			'email_address'   => $ddcf_contact_email,
			'email_subject'   => $ddcf_contact_subject,
			'arrival_date'    => $ddcf_arrival_date,
			'departure_date'  => $ddcf_departure_date,
			'post_title'      => $ddcf_post_title,
			'num_adults'      => $ddcf_num_adults,
			'num_children'    => $ddcf_num_children,
			'question_one'    => $ddcf_question_one,
			'question_two'    => $ddcf_question_two,
			'email_message'   => $ddcf_contact_message,
			'receive_updates' => $ddcf_newsletter_signup,
			'ip_address'      => $ddcf_ip_address,
			'city'            => $ddcf_city,
			'region'          => $ddcf_region,
			'country'         => $ddcf_country,
			'contact_id'      => $ddcf_contact_id
		);
		if ($wpdb->insert( $table, $data )) $fail = false;
		else $fail = true;




		// compose the email/s - first to the responder/s
		$headers[]  = 'From: '.$ddcf_contact_name.' <'.$ddcf_contact_email.'>'; //
		$headers[] .= 'Reply-To: '.$ddcf_contact_name.' <'.$ddcf_contact_email.'>';
		$headers[] .= 'Sender: '.$ddcf_contact_name.' <'.$ddcf_contact_email.'>'; //
		$headers[] .= 'MIME-Version: 1.0';
		$headers[] .= 'Content-type: text/html';

		$final_subject = get_bloginfo('name').' : '.$ddcf_contact_subject;

		$message_heading = '<div style="width:640px"><br />';
		if(get_option(ddcf_email_header)) $message_heading .= '<img src="'.get_option(ddcf_email_header).'" /><br /><br />';
		$final_message .= $ddcf_contact_name .' has made an enquiry:<br /><br />';
		if(get_option(ddcf_start_date_check)) $final_message .= 'Booking Start: '.$ddcf_arrival_date.'<br /><br />';
		if(get_option(ddcf_end_date_check)) $final_message .= 'Booking End: '.$ddcf_departure_date.'<br /><br />';
		if(get_option(ddcf_extra_dropdown_one_check)) $final_message .= $ddcf_num_adults.' adults<br /><br />';
		if(get_option(ddcf_extra_dropdown_two_check)) $final_message .= $ddcf_num_children.' children<br /><br />';
		if(get_option(ddcf_extra_question_one_check)) $final_message .= get_option(ddcf_extra_question_one).' '.$ddcf_question_one.'<br /><br />';
		if(get_option(ddcf_extra_question_two_check)) $final_message .= get_option(ddcf_extra_question_two).' '.$ddcf_question_two.'<br /><br />';
		$final_message .= 'Message:<br /><br/>'.$ddcf_contact_message.'<br /><br /><br />';


        $footer_legals = '<p style="font-size:0.7em;">The information contained in this email is confidential and may contain proprietary information. It is meant solely for the intended recipient. Access to this email by anyone else is unauthorised. If you are not the intended recipient, any disclosure, copying, distribution or any action taken or omitted in reliance on this, is prohibited and may be unlawful. No liability or responsibility is accepted if information or data is, for whatever reason corrupted or does not reach its intended recipient. The views expressed in this email are, unless otherwise stated, those of the author and not those of the website owners or any of its subsidiaries or its management. The website owner and its subsidiaries reserves the right to monitor, intercept and block emails addressed to its users or take any other action in accordance with its email use policy.';

        if(get_option(ddcf_geo_ip_option_check)) $geoloc = ' Email country of origin is '.$ddcf_country.'. ';
        else $geoloc = '';

		$message_footer = "This email was auto-generated by the <a href=\'http://davedub.co.uk\'>DaveDub</a> Contact Form for Wordpress.</p></div>";



		// send the email/s
		add_filter( 'wp_mail_content_type', 'set_html_content_type' );

				// first (conditionally) send to the user
				if(get_option('ddcf_email_confirmation', false)) {
					$customer_headers[]  = 'From: noreply <noreply@'.preg_replace('/^www\./','',$_SERVER['SERVER_NAME']).'>';
					$customer_headers[] .= 'Reply-To: noreply <noreply@'.preg_replace('/^www\./','',$_SERVER['SERVER_NAME']).'>';
					$customer_headers[] .= 'Sender: noreply <noreply@'.preg_replace('/^www\./','',$_SERVER['SERVER_NAME']).'>';
					$customer_headers[] .= 'MIME-Version: 1.0';
					$customer_headers[] .= 'Content-type: text/html';
					$customer_subject = 'Your message has been received at '.get_bloginfo('title');
					$customer_message = 'Hi '.$ddcf_contact_name.',<br /><br />';
					$customer_message .= get_option(ddcf_email_confirmation_text).'<br /><br />';
					wp_mail($ddcf_contact_email, $customer_subject, $message_heading.$customer_message.$footer_legals.$message_footer, $customer_headers);
				}
                // then send to our email recipients
                $email_sent = false;
                if(get_option(ddcf_enquiries_email_one) &&
                   wp_mail(get_option(ddcf_enquiries_email_one), $final_subject, $message_heading.$final_message.$footer_legals.$geoloc.$message_footer, $headers))
                    	$email_sent = true;
                if(get_option(ddcf_enquiries_email_two) &&
                   wp_mail(get_option(ddcf_enquiries_email_two), $final_subject, $message_heading.$final_message.$footer_legals.$geoloc.$message_footer, $headers))
                    	$email_sent = true;
                if(get_option(ddcf_enquiries_email_three) &&
                   wp_mail(get_option(ddcf_enquiries_email_three), $final_subject, $message_heading.$final_message.$footer_legals.$geoloc.$message_footer, $headers))
                    	$email_sent = true;
                if(get_option(ddcf_enquiries_email_four) &&
                   wp_mail(get_option(ddcf_enquiries_email_four), $final_subject, $message_heading.$final_message.$footer_legals.$geoloc.$message_footer, $headers))
                    	$email_sent = true;
                if(get_option(ddcf_enquiries_email_five) &&
                   wp_mail(get_option(ddcf_enquiries_email_five), $final_subject, $message_heading.$final_message.$footer_legals.$geoloc.$message_footer, $headers))
                    	$email_sent = true;
                if(!$email_sent) {
                    // fallback - no email sent, so forward to WP admin email address with warning
                    wp_mail(get_option( 'admin_email' ), $final_subject, 'Please set contact form message recipient email address in the plugin settings to stop receiving these messages<br />'.$message_heading.$final_message.$footer_legals.$geoloc.$message_footer, $headers);
                }

		remove_filter( 'wp_mail_content_type', 'set_html_content_type' ); // reset content-type

		// finally send Ajax message back to user to complete process
		wp_send_json($return);
		die();// wordpress may print out a spurious zero without this - can be particularly bad if using json
	}
?>