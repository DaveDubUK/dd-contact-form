<!--
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
-->

<!--[if gte IE 9]><style type="text/css">.gradient { filter: none; }</style><![endif]-->

<?php
    if (current_user_can(read)) {
		//error_reporting(E_ERROR | E_PARSE);
		//session_start();
		echo '<div id="ddcf_contacts_wrapper">
				<form id="ddcf_management_form">
					<div id="ddcf_tools_panel">
						<div class="ddcf_tool_left">
							<div>
								<input type="text" id="ddcf_search_form" value="'.__("Search by name", "ddcf_plugin").'">
							</div>
						</div><!-- end .ddcf_tool_left -->
					</div><!-- #ddcf_tools_panel -->

					<div id="ddcf_page_info"></div>

					<div id="ddcf_navigation_bar">
						<div class="ddcf_tool_left" id="ddcf_previous_page">'.__("<<  Previous  - - -", "ddcf_plugin").'</div>
						<div class="ddcf_tool_left" id="ddcf_next_page">'.__("- - -  Next  >>", "ddcf_plugin").'</div>
					</div>

					<input type="hidden" name="ddcf_session" id="ddcf_session" value="ddcf_manager_session" />
					<input type="hidden" name="ddcf_action" id="ddcf_action" value="initialise" />
					<input type="hidden" name="ddcf_action_arg" id="ddcf_action_arg" value="" />
					<input type="hidden" name="ddcf_num_results" id="ddcf_num_results" value="0" />
					<input type="hidden" name="ddcf_results_offset" id="ddcf_results_offset" value="0" />
					<input type="hidden" name="action" value="the_ajax_hook" /> <!-- this puts the action the_ajax_hook into the serialized form -->
					<input type="hidden" name="ddcf_mgr_nonce" id="ddcf_mgr_nonce" value="'.wp_create_nonce('ddcf_mgr_action').'" >
				</form>
				<div id="ddcf_contact_information"></div>
			</div><!-- end #ddcf_contacts_wrapper -->';
    } else _e('You do not have permission to view this content.');
?>