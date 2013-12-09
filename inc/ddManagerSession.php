<?php /*
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


		// nonce ok?
                $check = check_ajax_referer('ddcf_mgr_action','ddcf_mgr_nonce',false);
		
                if($check) {
                    
			// react depending on requested ddcf_action
			$ddcf_action = $_POST['ddcf_action'];
			$ddcf_action_arg = $_POST['ddcf_action_arg'];
			$ddcf_search_form = $_POST['ddcf_search_form'];
			$ddcf_num_results = 'none';
			$ddcf_results_offset = $_POST['ddcf_results_offset'];

			global $wpdb;

			if($ddcf_action=="ddcf_search_action") {

				if($ddcf_search_form!='') {
					// get the list of contacts for the given name from ddcf_search_form
					$dbtable = $wpdb->prefix . "contact";
					$query = "SELECT COUNT(*) FROM ".$dbtable.' WHERE contact_name LIKE "%'.$ddcf_search_form.'%"';
					$ddcf_num_results = $wpdb->get_var( $query );
					$query = 'SELECT * FROM '.$dbtable.' WHERE contact_name LIKE "%'.$ddcf_search_form.'%" ORDER BY contact_name LIMIT '.$ddcf_results_offset.', 25';
					$contacts = $wpdb->get_results($query);
					if($contacts) {
						$contacts_json_encoded = json_encode($contacts);
						$return = array(
							'ddcf_manager_nonce'=> wp_create_nonce('ddcf_manager_nonce'),
							'ddcf_num_results' => $ddcf_num_results,
							'ddcf_contact_information' => $contacts_json_encoded
						);
						wp_send_json($return);
						die();
					}
					else {
						$error_message = 'No results for '.$_POST['ddcf_search_form'].' using: '.$query;
						$return = array(
							'ddcf_manager_nonce'=> wp_create_nonce('ddcf_manager_nonce'),
							'ddcf_num_results' => $ddcf_num_results,
							'ddcf_contact_information' => $error_message
						);
						wp_send_json($return);
						die();
					}
				} // if ddcf_search_form
				else {// blank search box
					$dbtable = $wpdb->prefix . "contact";
					$query = "SELECT COUNT(*) FROM ".$dbtable.' WHERE contact_name LIKE "%'.$ddcf_search_form.'%"';
					$ddcf_num_results = $wpdb->get_var( $query );
					if($ddcf_search_form!='') $query = 'SELECT * FROM '.$dbtable.' WHERE contact_name LIKE "%'.$ddcf_search_form.'%" ORDER BY contact_name LIMIT '.$ddcf_results_offset.', 25';
					else $query = 'SELECT * FROM '.$dbtable.' ORDER BY contact_name LIMIT '.$ddcf_results_offset.', 25';
					$contacts = $wpdb->get_results($query);
					if($contacts) {
						$contacts_json_encoded = json_encode($contacts);
						$return = array(
							'ddcf_manager_nonce'=> wp_create_nonce('ddcf_manager_nonce'),
							'ddcf_num_results' => $ddcf_num_results,
							'ddcf_contact_information' => $contacts_json_encoded
						);
						wp_send_json($return);
						die();
					}
					else {
						$error_message = 'No results for '.$_POST['ddcf_search_form'].' using: '.$query;
						$return = array(
							'ddcf_manager_nonce'=> wp_create_nonce('ddcf_manager_nonce'),
							'ddcf_num_results' => $ddcf_num_results,
							'ddcf_contact_information' => $error_message
						);
						wp_send_json($return);
						die();
					}
				} // end blank search box
			} // end if search action

			else if($ddcf_action=="ddcf_filter_action") {
				// get list of contact roles
				$dbtable = $wpdb->prefix . "contact_roles";
				$query = "SELECT COUNT(*) FROM ".$dbtable;
				$count = $wpdb->get_var( $query );
                                
                                // if using limited number of contact roles 
                                if($count<=1) {
                                        // prepare list of contacts in selected category
                                        $dbtable = $wpdb->prefix . "contact";
                                        $query = "SELECT COUNT(*) FROM ".$dbtable;
                                        $ddcf_num_results = $wpdb->get_var( $query );
                                        $query='SELECT * FROM '.$dbtable.' ORDER BY contact_name LIMIT '.$ddcf_results_offset.', 25';
                                        $contacts = $wpdb->get_results($query);
                                        $contacts_json_encoded = json_encode($contacts);
                                        $return = array(
                                                //'ddcf_manager_nonce'=> wp_create_nonce('ddcf_manager_nonce'),
                                                'ddcf_num_results' => $ddcf_num_results,
                                                'ddcf_contact_information' => $contacts_json_encoded
                                        );
                                        wp_send_json($return);
                                        die();
                                }                                
                                
				for ($ij = 1; $ij <= $count; $ij++) {
					$query = $wpdb->prepare('SELECT * FROM '.$dbtable.' WHERE contact_role_index = %d',$ij);// ty http://make.wordpress.org/core/2012/12/12/php-warning-missing-argument-2-for-wpdb-prepare/
                                        //$preparedQuery = $wpdb->prepare( '%s', $query 
					$role_option = $wpdb->get_row($query); 

                                        if($ddcf_action_arg=='all contact types') {
						// prepare list of contacts in selected category
						$dbtable = $wpdb->prefix . "contact";
                                                $query = "SELECT COUNT(*) FROM ".$dbtable;
                                                $ddcf_num_results = $wpdb->get_var( $query );
                                                $query='SELECT * FROM '.$dbtable.' ORDER BY contact_name LIMIT '.$ddcf_results_offset.', 25';
						$contacts = $wpdb->get_results($query);
						$contacts_json_encoded = json_encode($contacts);
						$return = array(
							//'ddcf_manager_nonce'=> wp_create_nonce('ddcf_manager_nonce'),
							'ddcf_num_results' => $ddcf_num_results,
							'ddcf_contact_information' => $contacts_json_encoded
						);
						wp_send_json($return);
						die();
					}
                                        else if($role_option->contact_role==$ddcf_action_arg) {
						// prepare list of contacts in selected category
						$dbtable = $wpdb->prefix . "contact";
                                                $query = "SELECT COUNT(*) FROM ".$dbtable.' WHERE contact_type = "'.$ddcf_action_arg.'"';
                                                $ddcf_num_results = $wpdb->get_var( $query );
                                                $query='SELECT * FROM '.$dbtable.' WHERE contact_type = "'.$ddcf_action_arg.'" ORDER BY contact_name LIMIT '.$ddcf_results_offset.', 25';
						$contacts = $wpdb->get_results($query);
						$contacts_json_encoded = json_encode($contacts);
						$return = array(
							//'ddcf_manager_nonce'=> wp_create_nonce('ddcf_manager_nonce'),
							'ddcf_num_results' => $ddcf_num_results,
							'ddcf_contact_information' => $contacts_json_encoded
						);
						wp_send_json($return);
						die();
					}
				}
				$return = array(
					'ddcf_manager_nonce'=> wp_create_nonce('ddcf_manager_nonce'),
					'ddcf_num_results' => '0',
					'count' => $count,
					'query' => $query,
					'role opt' => $role_option,
					'ddcf_action_arg' => $ddcf_action_arg,
					'ddcf_contact_information' => 'no contact role match error'
				);
				wp_send_json($return);
				die();
			}//  ddcf_filter_action

			else if($ddcf_action=="ddcf_dox_user") {

				// gather contact details, contact notes, contact relations, details of any properties owned and a list of past enquiries
				$ddcf_action_arg = $_POST['ddcf_action_arg'];
				$start = strpos($ddcf_action_arg,'(');
				$end = strpos($ddcf_action_arg,')');
				$contact_id = get_string_between($ddcf_action_arg, "(", ")");

				$dbtable = $wpdb->prefix . "contact_info";
				$query = 'SELECT * FROM '.$dbtable.' WHERE contact_id = '.$contact_id;
				$ddcf_contact_details = $wpdb->get_results($query);
				$ddcf_contact_details_json = json_encode($ddcf_contact_details);

//				$dbtable = $wpdb->prefix . "contact_note";
//				$query = 'SELECT * FROM '.$dbtable.' WHERE contact_id = '.$contact_id;
//				$ddcf_contact_notes = $wpdb->get_results($query);
//				$ddcf_contact_notes_json     = json_encode($ddcf_contact_notes);
//
//				$dbtable = $wpdb->prefix . "contact_relations";
//				$query = 'SELECT * FROM '.$dbtable.' WHERE contact_relative_one_id = '.$contact_id.' OR  contact_relative_two_id = '.$contact_id;
//				$contact_relations = $wpdb->get_results($query);
//				$ddcf_contact_relations_json = json_encode($ddcf_contact_relations);

				// get list of any owned bookables, as specified in settings (
				// get_option('ddcf_bookable_category')
				// meta inf on posts called owner id (bookable_owner_id)
//				$dbtable = $wpdb->prefix . "postmeta";
//				$query = 'SELECT post_id FROM '.$dbtable.' WHERE meta_value = '.$contact_id.' AND meta_key = "bookable_owner_id"';
//				$ddcf_qone = $query;
//				$contact_bookable_list = $wpdb->get_results($query);
//
//				$count = 0;
//				$ddcf_qtwo = 'Queries: ';
//				$ddcf_contact_bookables = array();
//				foreach($contact_bookable_list as $bookable) {
//					// get the post name and a link to the post
//					$dbtable = $wpdb->prefix . "posts";
//					$query = 'SELECT post_title, guid FROM '.$dbtable.' WHERE ID = '.$bookable->post_id;
//					$ddcf_qtwo .= $query.' : ';
//					$result = $wpdb->get_row($query);
//					$new_array = array('guid'=>$result->guid, 'post_title'=>$result->post_title);
//					$ddcf_contact_bookables[$count] = $new_array;
//					$count++;
//				}
//				$ddcf_contact_bookables_json = json_encode($ddcf_contact_bookables);

				// and finally our list of enquiries
				$dbtable = $wpdb->prefix . "enquiries";
				$query = 'SELECT * FROM '.$dbtable.' WHERE contact_id = '.$contact_id;
				$ddcf_contact_enquiries = $wpdb->get_results($query);
				$ddcf_contact_enquiries_json = json_encode($ddcf_contact_enquiries);

				// send it all back
				$return = array(
					//'ddcf_manager_nonce'     => wp_create_nonce('ddcf_manager_nonce'),
					'ddcf_contact_details'   => $ddcf_contact_details_json,
//					'ddcf_contact_notes'     => $ddcf_contact_notes_json,
//					'ddcf_contact_relations' => $ddcf_contact_relations_json,
//					'ddcf_contact_bookables' => $ddcf_contact_bookables_json,
					'ddcf_contact_enquiries' => $ddcf_contact_enquiries_json
				);
				wp_send_json($return);
				die();
			}

		} // ddcf_manager_nonce ok check
                else if ($ddcf_action=="initialise") {
			$return = array(
				//'ddcf_manager_nonce'=> wp_create_nonce('ddcf_manager_nonce'),
				'ddcf_contact_information' => 'server ready'
			);
			wp_send_json($return);
			die();
		}
                else {
			$return = array(
				//'ddcf_manager_nonce'=> wp_create_nonce('ddcf_manager_nonce'),
				'ddcf_contact_information' => 'unknown action requested'
			);
			wp_send_json($return);
			die();
		}
?>