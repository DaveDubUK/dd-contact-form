<!--
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
-->

<!--[if gte IE 9]><style type="text/css">.gradient { filter: none; }</style><![endif]-->

<?php

    if(current_user_can(read)) {
	global $wpdb;

	echo '<div id="ddcf_contacts_wrapper">
			<h2>Contacts</h2>
			<form id="ddcf_management_form">
				<div class="ddcf_tools_panel">
					<div class="ddcf-tool-l">
						<div>
							<!--label for="ddcf_search_form">Search: </label-->
							<input  class="ddcf_pimped" type="text" name="ddcf_search_form" id="ddcf_search_form" value="'.__("Search by name").'">
						</div>
					</div><!-- end .ddcf-tool-l -->';
//					<div class="ddcf-tool-l">
//						<div>
//							<label for="ddcf_contact_types">Filter: </label>
//							<select class="ddcf_pimped" name="ddcf_contact_types" id="ddcf_contact_types">';
//									// get list of contact roles
//									$dbtable = $wpdb->prefix . "contact_roles";
//									$query = "SELECT COUNT(*) FROM ".$dbtable;
//									$count = $wpdb->get_var( $query );
//									for ($ij = 1; $ij <= $count; $ij++) {
//										$query = 'SELECT * FROM '.$dbtable.' WHERE contact_role_index = '.$ij;
//										$role_option = $wpdb->get_row($wpdb->prepare( $query ));
//										if($role_option->contact_role=="all contact types")
//											 echo '
//								<option value="'.strval($count).'" selected>'.$role_option->contact_role.'</option>';
//										else echo '
//								<option value="'.strval($count).'">'.$role_option->contact_role.'</option>';
//									}
//								echo '
//							</select>
//						</div>
//					</div><!-- .ddcf-tool-l -->
            echo '		</div><!-- .ddcf_tools_panel -->';          
//				<div class="ddcf_buttons_panel">
//                                    <!-- add user -->
//                                    <button id="ddcf_create_user" name="ddcf_create_user" class="ddcf_button">Add New Contact</button>
//                                    
//				</div><!-- .ddcf_buttons_panel -->

                        echo'

				<div class="ddcf_navigation_bar">
					<div class="ddcf-tool-l" id="ddcf_previous_page" name="ddcf_previous_page">'.__("<<  Previous  - - -", "ddcf_plugin").'</div>
					<div class="ddcf-tool-l" id="ddcf_page_info" name="ddcf_page_info"></div>
					<div class="ddcf-tool-l" id="ddcf_next_page" name="ddcf_next_page">'.__("- - -  Next  >>", "ddcf_plugin").'</div>
				</div>


				<input type="hidden" name="ddcf_session" id="ddcf_session" value="ddcf_manager_session" />
				<input type="hidden" name="ddcf_action" id="ddcf_action" value="initialise" />
				<input type="hidden" name="ddcf_action_arg" id="ddcf_action_arg" value="" />
				<input type="hidden" name="ddcf_num_results" id="ddcf_num_results" value="0" />
				<input type="hidden" name="ddcf_results_offset" id="ddcf_results_offset" value="0" />
				<input type="hidden" name="action" value="the_ajax_hook" /> <!-- this puts the action the_ajax_hook into the serialized form -->';
                                $mgrNonce=wp_create_nonce('ddcf_mgr_action');                                                                
                                echo '<input type="hidden" name="ddcf_mgr_nonce" id="ddcf_mgr_nonce" value="'.$mgrNonce.'" >
			</form>
                        
                        <div id="ddcf_contact_information" name="ddcf_contact_information"></div>
                        <div id="ddcf_create_contact_dialog" name="ddcf_create_contact_dialog"></div>

		</div><!-- end #ddcf_contacts_wrapper -->
        ';
    } else _e('You do not have permission to view this content.');
?>