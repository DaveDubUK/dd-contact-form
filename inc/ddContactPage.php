<!--
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
-->

<!-- clear POST data, just in case of browser refresh or back button presses -->
<?php unset($_POST); ?>

<!--[if gte IE 9]><style type="text/css">.gradient { filter: none; }</style><![endif]-->

<div id="ddcf_contact_form_wrapper" name="ddcf_contact_form_wrapper">
	<div id="ddcf_contact_form_contents" name="ddcf_contact_form_contents">

		<!-- form user feedback area -->
		<div id="error_reporting" name="error_reporting"></div>

		<form action="" method="" id="ddcf_contact_form" name="ddcf_contact_form">

			<!-- top left -->
			<div id="ddcf_contact_form_top_left" class="ddcf_float_left">
				<div class="ddcf_float_left ddcf_markable_container">
					<input type="text" name="ddcf_contact_name" value="<?php _e('Name','ddcf_plugin') ?>" id="ddcf_contact_name" class="ddcf_input_base ddcf_contact_text_input" title="<?php _e('Please enter your name','ddcf_plugin') ?>" />
					<div id="ddcf_contact_name_fb" class="ddcf_contact_input_verify"></div>
				</div>
				<div class="ddcf_float_left ddcf_markable_container">
					<input type="text" name="ddcf_contact_email" value="<?php _e('Email Address','ddcf_plugin') ?>" id="ddcf_contact_email" class="ddcf_input_base ddcf_contact_text_input" title="<?php _e('Please enter your email address','ddcf_plugin') ?>" />
					<div id="ddcf_contact_email_fb" class="ddcf_contact_input_verify"></div>
				</div>
				<br />
				<div class="ddcf_float_left ddcf_markable_container">
					<input type="text" name="ddcf_contact_subject" value="<?php _e('Subject','ddcf_plugin') ?>" id="ddcf_contact_subject" class="ddcf_input_base ddcf_contact_text_input" title="<?php _e('Email Subject','ddcf_plugin') ?>" />
					<div id="ddcf_contact_subject_fb" class="ddcf_contact_input_verify"></div>
				</div>
				<br />
			</div> <!-- top left -->


			<!-- top right -->
			<div id="ddcf_contact_form_top_right" class="ddcf_float_right ddcf_markable_container">
                            <?php
                            	    // gather user prefs. If none set, set defaults
                                    $start_date_check = get_option(ddcf_start_date_check);
                                    if(!$start_date_check) $start_date_check = false;
                                    $end_date_check   = get_option(ddcf_end_date_check);
                                    if(!$end_date_check) $end_date_check = false;
                                    $start_date_time_check = get_option(ddcf_start_date_time_check);
                                    if(!$start_date_time_check) $start_date_time_check = false;
                                    $end_date_time_check   = get_option(ddcf_end_date_time_check);
                                    if(!$end_date_time_check) $end_date_time_check = false;
                                    $extra_dropdown_one_check = get_option(ddcf_extra_dropdown_one_check);
                                    if(!$extra_dropdown_one_check) $extra_dropdown_one_check = false;
                                    $extra_dropdown_two_check = get_option(ddcf_extra_dropdown_two_check);
                                    if(!$extra_dropdown_two_check) $extra_dropdown_two_check = false;
                                    $ddcf_captcha_type = get_option(ddcf_captcha_type);
                                    if(!$ddcf_captcha_type) $ddcf_captcha_type = "Simple Addition";
                                    $ddcf_num_adults = get_option(ddcf_num_adults);
                                    if(!$ddcf_num_adults) $ddcf_num_adults = false;
                                    $ddcf_num_children = get_option(ddcf_num_children);
                                    if(!$ddcf_num_children) $ddcf_num_children = false;
                                    
                                    $ddcf_form_density = 0;
                            ?>

                            <table name="ddcf_details_table" id="ddcf_details_table">

                                <?php
                                        if($start_date_check||$end_date_check||$extra_dropdown_one_check||$extra_dropdown_two_check) {
                                        echo '<tr class="ddcf_details_table_row" >';

                                        // start of booking dates

                                        // first check if dates options should be limited to certain category
                                        $wrong_category = true;
                                        if(get_option(ddcf_dates_category_filter_check)) {
                                            // is this page / post in the correct category?
                                            $selected_cat = get_option(ddcf_dates_category_filter);
                                            //$selected_category = get_category($selected_cat);
                                            global $post;
                                            $cats = wp_get_post_categories($post->ID);
                                            if ($cats) {
                                              foreach ($cats as $cat) {
                                                $category = get_category($cat);
                                                if ($selected_cat == $category->term_id ) {
                                                  $wrong_category = false;
                                                }
                                              }
                                            }
                                        } else $wrong_category = false;
                                        
                                        if(!$wrong_category) {
                                            if($start_date_check||$end_date_check) {

                                                    if($extra_dropdown_one_check||$extra_dropdown_two_check )
                                                         echo '<td class="ddcf_details_table_row_division" name="ddcf_dates_row_division" id="ddcf_dates_row_division"> ';
                                                    else echo '<td class="ddcf_details_table_row_division" name="ddcf_dates_row_division" id="ddcf_dates_row_division" colspan="2"> ';

                                                    if($start_date_check&&$end_date_check) {
                                                        if($start_date_time_check||$end_date_time_check)
                                                            echo'
                                                                    <div id="ddcf_dates_align" name="ddcf_dates_align">
                                                                        <span class="ddcf_table_span_datetime">
                                                                            <label for="ddcf_arrival_date">'.__("Booking Start:","ddcf_plugin").'</label>
                                                                            <div class="ddcf_dates_container">
                                                                                <input type="text" class="ddcf_input_base ddcf_datetime_picker" name="ddcf_arrival_date" id="ddcf_arrival_date" value="'. __('Click here','ddcf_plugin') .'" title="'. __('Click here','ddcf_plugin') .'" />
                                                                                <div id="ddcf_arrival_date_fb" class="ddcf_contact_input_verify"></div>
                                                                             </div>
                                                                        </span><br />
                                                                        <span class="ddcf_table_span_datetime">
                                                                            <label for="ddcf_departure_date">'.__("Booking End:","ddcf_plugin").'</label>
                                                                            <div class="ddcf_dates_container">
                                                                                <input type="text" class="ddcf_input_base ddcf_datetime_picker" name="ddcf_departure_date" id="ddcf_departure_date" value="'. __('Click here','ddcf_plugin') .'" title="'. __('Click here','ddcf_plugin') .'" />
                                                                                <div id="ddcf_departure_date_fb" class="ddcf_contact_input_verify"></div>
                                                                            </div>
                                                                        </span>
                                                                     </div>';
                                                        else
                                                            echo'   <div id="ddcf_dates_align" name="ddcf_dates_align">
                                                                        <span class="ddcf_table_span_date">
                                                                            <label for="ddcf_arrival_date">'.__("Booking Start:","ddcf_plugin").'</label>
                                                                            <div class="ddcf_dates_container">
                                                                                <input type="text" class="ddcf_input_base ddcf_date_picker" name="ddcf_arrival_date" id="ddcf_arrival_date" value="'. __('Click here','ddcf_plugin') .'" title="'. __('Click here','ddcf_plugin') .'" />
                                                                                <div id="ddcf_arrival_date_fb" class="ddcf_contact_input_verify"></div>
                                                                            </div>
                                                                        </span><br />
                                                                        <span class="ddcf_table_span_date">
                                                                            <label for="ddcf_departure_date">'.__("Booking End:","ddcf_plugin").'</label>
                                                                            <div class="ddcf_dates_container">
                                                                                <input type="text" class="ddcf_input_base ddcf_date_picker" name="ddcf_departure_date" id="ddcf_departure_date" value="'. __('Click here','ddcf_plugin') .'" title="'. __('Click here','ddcf_plugin') .'" />
                                                                                <div id="ddcf_departure_date_fb" class="ddcf_contact_input_verify"></div>
                                                                            </div>
                                                                        </span></div>';
                                                    }
                                                    else if($start_date_check) {
                                                        if($start_date_time_check||$end_date_time_check)
                                                            echo'      <span class="ddcf_table_span_datetime">
                                                                            <label for="ddcf_arrival_date">'.__("Booking Start:","ddcf_plugin").'</label>
                                                                            <input type="text" class="ddcf_input_base ddcf_datetime_picker" name="ddcf_arrival_date" id="ddcf_arrival_date" value="'. __('Click here','ddcf_plugin') .'" title="'. __('Click here','ddcf_plugin') .'" />
                                                                            <div id="ddcf_arrival_date_fb" class="ddcf_contact_input_verify"></div>
                                                                        </span>';
                                                        else
                                                            echo'       <span class="ddcf_table_span_date">
                                                                            <label for="ddcf_arrival_date">'.__("Booking Start:","ddcf_plugin").'</label>
                                                                            <input type="text" class="ddcf_input_base ddcf_date_picker" name="ddcf_arrival_date" id="ddcf_arrival_date" value="'. __('Click here','ddcf_plugin') .'" title="'. __('Click here','ddcf_plugin') .'" />
                                                                            <div id="ddcf_arrival_date_fb" class="ddcf_contact_input_verify"></div>
                                                                        </span>';
                                                    }
                                                    else if($end_date_check) {
                                                        if($start_date_time_check||$end_date_time_check)
                                                            echo'      <span class="ddcf_table_span_datetime">
                                                                            <label for="ddcf_departure_date">'.__("Booking End:","ddcf_plugin").'</label>
                                                                            <input type="text" class="ddcf_input_base ddcf_datetime_picker" name="ddcf_departure_date" id="ddcf_departure_date" value="'. __('Click here','ddcf_plugin') .'" title="'. __('Click here','ddcf_plugin') .'" />
                                                                            <div id="ddcf_departure_date_fb" class="ddcf_contact_input_verify"></div>
                                                                        </span>';
                                                        else
                                                            echo'       <span class="ddcf_table_span_date">
                                                                            <label for="ddcf_departure_date">'.__("Booking End:","ddcf_plugin").'</label>
                                                                            <input type="text" class="ddcf_input_base ddcf_date_picker" name="ddcf_departure_date" id="ddcf_departure_date" value="'. __('Click here','ddcf_plugin') .'" title="'. __('Click here','ddcf_plugin') .'" />
                                                                            <div id="ddcf_departure_date_fb" class="ddcf_contact_input_verify"></div>
                                                                        </span>';
                                                    }
                                                    echo '</td>';
                                                    
                                                    echo '<input type="hidden" name="ddcf_dates_category_filter" id="ddcf_dates_category_filter" value="'; 
                                                    if(get_option('ddcf_dates_category_filter_check')) 
                                                        echo get_option('ddcf_dates_category_filter').'" />';
                                                    else echo 'unset" />';
                                                    
                                                    if($start_date_time_check||$end_date_time_check) $ddcf_form_density=3;
                                                    else $ddcf_form_density=2;
                                            } // end of booking dates
                                        } // if !$wrong_category


                                            // start of dropdowns
                                            if($extra_dropdown_one_check||$extra_dropdown_two_check) {

                                                // first check if limited to certain category
                                                $wrong_category = true;
                                                if(get_option(ddcf_party_size_category_filter_check)) {
                                                    // is this page / post in the correct category?
                                                    $selected_cat = get_option(ddcf_party_size_category_filter);
                                                    //$selected_category = get_category($selected_cat);
                                                    global $post;
                                                    $cats = wp_get_post_categories($post->ID);
                                                    if ($cats) {
                                                      foreach ($cats as $cat) {
                                                        $category = get_category($cat);
                                                        if ($selected_cat == $category->term_id ) {
                                                          $wrong_category = false;
                                                        }
                                                      }
                                                    }
                                                } else $wrong_category = false;

                                                if(!$wrong_category) {

                                                    if($start_date_check||$end_date_check)
                                                         echo '<td class="ddcf_details_table_row_division"> ';
                                                    else echo '<td class="ddcf_details_table_row_division" colspan="2"> ';

                                                    if($extra_dropdown_one_check&&$extra_dropdown_two_check) {
                                                        echo'<div class="ddcf_dropdowns_align">
                                                                <span class="ddcf_table_span_dropdown">
                                                                        <label for="ddcf_num_adults">'.__("Adults:", "ddcf_plugin").'</label>
                                                                        <select name="ddcf_num_adults" id="ddcf_num_adults" class="ddcf_input_base ddcf_dropdown">
                                                                                        <option value="0" '; if($ddcf_num_adults==0) echo "selected"; echo '>'.__("None").'</option>
                                                                                        <option value="1" '; if($ddcf_num_adults==1) echo "selected"; echo '>1</option>
                                                                                        <option value="2" '; if($ddcf_num_adults==2) echo "selected"; echo '>2</option>
                                                                                        <option value="3" '; if($ddcf_num_adults==3) echo "selected"; echo '>3</option>
                                                                                        <option value="4" '; if($ddcf_num_adults==4) echo "selected"; echo '>4</option>
                                                                                        <option value="5" '; if($ddcf_num_adults==5) echo "selected"; echo '>5</option>
                                                                                        <option value="6" '; if($ddcf_num_adults==6) echo "selected"; echo '>6</option>
                                                                                        <option value="7" '; if($ddcf_num_adults==7) echo "selected"; echo '>7</option>
                                                                                        <option value="8" '; if($ddcf_num_adults==8) echo "selected"; echo '>8</option>
                                                                                        <option value="9" '; if($ddcf_num_adults==9) echo "selected"; echo '>9</option>
                                                                                        <option value="10" '; if($ddcf_num_adults==10) echo "selected"; echo '>10+</option>
                                                                        </select>
                                                                        <div id="ddcf_num_adults_fb" class="ddcf_contact_input_verify"></div>
                                                                        </div>
                                                                </span><br />
                                                                <span class="ddcf_table_span_dropdown">
                                                                <!--div id="ddcf_num_children_selector" class="ddcf_markable_container" title="'. __('Number of Children','ddcf_plugin') .'"-->
                                                                        <label for="ddcf_num_children">'.__("Children:","ddcf_plugin").'</label>
                                                                        <select name="ddcf_num_children" id="ddcf_num_children" class="ddcf_input_base ddcf_dropdown">
                                                                                        <option value="0" '; if($ddcf_num_children==0) echo "selected"; echo '>'.__("None").'</option>
                                                                                        <option value="1" '; if($ddcf_num_children==1) echo "selected"; echo '>1</option>
                                                                                        <option value="2" '; if($ddcf_num_children==2) echo "selected"; echo '>2</option>
                                                                                        <option value="3" '; if($ddcf_num_children==3) echo "selected"; echo '>3</option>
                                                                                        <option value="4" '; if($ddcf_num_children==4) echo "selected"; echo '>4</option>
                                                                                        <option value="5" '; if($ddcf_num_children==5) echo "selected"; echo '>5</option>
                                                                                        <option value="6" '; if($ddcf_num_children==6) echo "selected"; echo '>6</option>
                                                                                        <option value="7" '; if($ddcf_num_children==7) echo "selected"; echo '>7</option>
                                                                                        <option value="8" '; if($ddcf_num_children==8) echo "selected"; echo '>8</option>
                                                                                        <option value="9" '; if($ddcf_num_children==9) echo "selected"; echo '>9</option>
                                                                                        <option value="10" '; if($ddcf_num_children==10) echo "selected"; echo '>10+</option>
                                                                        </select>
                                                                        <div id="ddcf_num_children_fb" class="ddcf_contact_input_verify"></div>
                                                                        </div>
                                                                <!--/div-->
                                                            </span></div>';
                                                    }
                                                    else if($extra_dropdown_one_check) {
                                                        echo'

                                                                <span class="ddcf_table_span_dropdown">
                                                                    <div id="ddcf_num_adults_selector" class="ddcf_markable_container" title="'. __('Number of Adults','ddcf_plugin') .'">
                                                                            <label for="ddcf_num_adults">'.__("Adults:","ddcf_plugin").'</label>
                                                                            <select name="ddcf_num_adults" id="ddcf_num_adults" class="ddcf_input_base ddcf_dropdown">
                                                                                            <option value="0" '; if($ddcf_num_adults==0) echo "selected"; echo '>'.__("None").'</option>
                                                                                            <option value="1" '; if($ddcf_num_adults==1) echo "selected"; echo '>1</option>
                                                                                            <option value="2" '; if($ddcf_num_adults==2) echo "selected"; echo '>2</option>
                                                                                            <option value="3" '; if($ddcf_num_adults==3) echo "selected"; echo '>3</option>
                                                                                            <option value="4" '; if($ddcf_num_adults==4) echo "selected"; echo '>4</option>
                                                                                            <option value="5" '; if($ddcf_num_adults==5) echo "selected"; echo '>5</option>
                                                                                            <option value="6" '; if($ddcf_num_adults==6) echo "selected"; echo '>6</option>
                                                                                            <option value="7" '; if($ddcf_num_adults==7) echo "selected"; echo '>7</option>
                                                                                            <option value="8" '; if($ddcf_num_adults==8) echo "selected"; echo '>8</option>
                                                                                            <option value="9" '; if($ddcf_num_adults==9) echo "selected"; echo '>9</option>
                                                                                            <option value="10" '; if($ddcf_num_adults==10) echo "selected"; echo '>10+</option>
                                                                            </select>
                                                                            <div id="ddcf_num_adults_fb" class="ddcf_contact_input_verify"></div>
                                                                    </div>
                                                                </span>';
                                                    }
                                                    else if($extra_dropdown_two_check) {
                                                        echo'

                                                                <span class="ddcf_table_span_dropdown">
                                                                    <div id="ddcf_num_children_selector" class="ddcf_markable_container" title="'. __('Number of Children','ddcf_plugin') .'">
                                                                            <!--div class="ddcf_dropdowns_container"-->
                                                                            <label for="ddcf_num_children">'.__("Children:","ddcf_plugin").'</label>
                                                                            <select name="ddcf_num_children" id="ddcf_num_children" class="ddcf_input_base ddcf_dropdown">
                                                                                            <option value="0" '; if($ddcf_num_children==0) echo "selected"; echo '>'.__("None").'</option>
                                                                                            <option value="1" '; if($ddcf_num_children==1) echo "selected"; echo '>1</option>
                                                                                            <option value="2" '; if($ddcf_num_children==2) echo "selected"; echo '>2</option>
                                                                                            <option value="3" '; if($ddcf_num_children==3) echo "selected"; echo '>3</option>
                                                                                            <option value="4" '; if($ddcf_num_children==4) echo "selected"; echo '>4</option>
                                                                                            <option value="5" '; if($ddcf_num_children==5) echo "selected"; echo '>5</option>
                                                                                            <option value="6" '; if($ddcf_num_children==6) echo "selected"; echo '>6</option>
                                                                                            <option value="7" '; if($ddcf_num_children==7) echo "selected"; echo '>7</option>
                                                                                            <option value="8" '; if($ddcf_num_children==8) echo "selected"; echo '>8</option>
                                                                                            <option value="9" '; if($ddcf_num_children==9) echo "selected"; echo '>9</option>
                                                                                            <option value="10" '; if($ddcf_num_children==10) echo "selected"; echo '>10+</option>
                                                                            </select>
                                                                            <div id="ddcf_num_children_fb" class="ddcf_contact_input_verify"></div>
                                                                            </div>
                                                                    </div>
                                                                </span>';
                                                    }
                                                    echo '</td>';
                                                    echo '<input type="hidden" name="ddcf_party_size_category_filter" id="ddcf_party_size_category_filter" value="'; 
                                                    if(get_option('ddcf_party_size_category_filter_check')) 
                                                        echo get_option('ddcf_party_size_category_filter').'" />';
                                                    else echo 'unset" />';
                                                    
                                                    if($ddcf_form_density==2) $ddcf_form_density = 6;
                                                    else if($ddcf_form_density==3) $ddcf_form_density = 7;
                                                    else $ddcf_form_density = 1;
                                                } // end of dropdowns
                                            }
                                            echo '
                                            </tr>';
                                    } // endif row 1 - dates and numbers



                                    // row 2 - Captcha
                                    if($ddcf_captcha_type=='None') {
                                            // not the best idea...
                                    }
                                    else if($ddcf_captcha_type=='reCaptcha') {
                                            echo '
                                                <tr class="ddcf_details_table_row">
                                                    <td class="ddcf_details_table_row_division" colspan="2">';
                                            if(get_option(ddcf_recaptcha_public_key)) {
                                                echo '<div class="ddcf_contact_form_slab">
                                                        <div id="ddcf_contact_form_captcha" name="ddcf_contact_form_captcha">
                                                            <div id="ddcf_google_recaptcha" name="ddcf_google_recaptcha"></div>
                                                            <span id="ddcf_contact_recaptcha_fb" name="ddcf_contact_recaptcha_fb" class="ddcf_contact_input_verify"></span>
                                                        </div>
                                                      </div>';
                                            }
                                            else _e('Missing Google reCaptcha keys - please see plugin options', 'ddcf_plugin');

                                            // finish off row
                                            echo 	'</td>
                                                </tr>';
                                            if($ddcf_form_density<5) $ddcf_form_density = 5;
                                    } // end reCaptcha

                                    else {
                                            // default to Simple Addition
                                            echo '
                                                <tr class="ddcf_details_table_row">
                                                    <td class="ddcf_details_table_row_division" colspan="2">
                                                        <span name="ddcf_table_span_captcha_add" id="ddcf_table_span_captcha_add">
                                                            <div id="ddcf_contact_form_captcha" name="ddcf_contact_form_captcha" class="ddcf_contact_form_slab ddcf_float_right">
                                                                <div id="ddcf_simple_add_captcha" name="ddcf_simple_add_captcha">
                                                                <p>
                                                                    What does <span id="ddcf_captcha_one">?</span> plus <span id="ddcf_captcha_two">?</span> equal?
                                                                    <input type="text" name="ddcf_contact_captcha_add" value="" id="ddcf_contact_captcha_add" class="ddcf_input_base" title="Please add the two numbers"/>
                                                                    <span id="ddcf_contact_captcha_fb" name="ddcf_contact_captcha_fb" class="ddcf_contact_input_verify"></span>
                                                                </p>
                                                                </div>
                                                            </div>
                                                        </span>
                                                    </td>
                                                </tr>';
                                            if($ddcf_form_density<4) $ddcf_form_density = 4;
                                    }
				?>
                                <!-- end simple addition captcha -->
                            </table>
			</div> <!-- top right -->


			<!-- Full width components below -->
			<!--div id="ddcf_contact_form_bottom"-->

                                <!-- extra questions -->
				<?php
                                        // first check if limited to certain category
                                        $wrong_category = true;
                                        $ddcf_party_size_category_filter = '';
                                        if(get_option(ddcf_extra_question_category_filter_check)) {
                                            // is this page / post in the correct category?
                                            $selected_cat = get_option(ddcf_extra_question_category_filter);
                                            //$selected_category = get_category($selected_cat);
                                            global $post;
                                            $cats = wp_get_post_categories($post->ID);
                                            if ($cats) {
                                              foreach ($cats as $cat) {
                                                $category = get_category($cat);
                                                if ($selected_cat == $category->term_id ) {
                                                  $wrong_category = false;
                                                  $ddcf_party_size_category_filter = $selected_cat;
                                                }
                                              }
                                            }
                                        } else $wrong_category = false;

                                        if(!$wrong_category) {
                                            if(get_option(ddcf_extra_question_one_check)||get_option(ddcf_extra_question_two_check))
                                            {
                                                if(get_option(ddcf_extra_question_one_check) && get_option(ddcf_extra_question_two_check)) echo '
                                                <div class="ddcf_contact_form_slab ddcf_float_left ddcf_markable_container">
                                                    <div style="position:relative; width:100%; overflow:hidden">
                                                        <input type="text" id="ddcf_question_one" name="ddcf_question_one" class="ddcf_input_base ddcf_question" value="'.get_option(ddcf_extra_question_one).'" title="'.get_option(ddcf_extra_question_one).'" />
                                                        <div name="ddcf_question_one_fb" id="ddcf_question_one_fb" class="ddcf_contact_input_verify"></div>
                                                    </div>
                                                    <div style="position:relative; width:100%; overflow:hidden">
                                                        <input type="text" id="ddcf_question_two" name="ddcf_question_two" class="ddcf_input_base ddcf_question" value="'.get_option(ddcf_extra_question_two).'" title="'.get_option(ddcf_extra_question_two).'" />
                                                        <div name="ddcf_question_two_fb" id="ddcf_question_two_fb" class="ddcf_contact_input_verify"></div>
                                                    </div>
                                                </div><!-- ddcf_contact_form_slab --> ';

                                                else if(get_option(ddcf_extra_question_one_check)) echo '
                                                <div class="ddcf_contact_form_slab ddcf_float_left ddcf_markable_container">
                                                        <input type="text" id="ddcf_question_one" name="ddcf_question_one" class="ddcf_input_base ddcf_question" value="'.get_option(ddcf_extra_question_one).'" title="'.get_option(ddcf_extra_question_one).'" />
                                                        <div name="ddcf_question_two_fb" id="ddcf_question_one_fb" class="ddcf_contact_input_verify"></div>
                                                </div><!-- extra question 1 -->';


                                                else if(get_option(ddcf_extra_question_two_check)) echo '
                                                <div class="ddcf_contact_form_slab ddcf_float_left ddcf_markable_container">
                                                        <input type="text" id="ddcf_question_two" name="ddcf_question_two" class="ddcf_input_base ddcf_question" value="'.get_option(ddcf_extra_question_two).'" title="'.get_option(ddcf_extra_question_two).'" />
                                                        <div id="ddcf_question_two_fb" class="ddcf_contact_input_verify"></div>
                                                </div><!-- extra question 2 -->';
                                                
                                                echo '<input type="hidden" name="ddcf_extra_question_category_filter" id="ddcf_extra_question_category_filter" value="'; 
                                                if(get_option('ddcf_extra_question_category_filter_check')) 
                                                    echo get_option('ddcf_extra_question_category_filter').'" />';
                                                else echo 'unset" />';                                                   
                                            }
                                        }
				?>

				<!-- message area -->
				<div id="ddcf-message" class="ddcf_full_width ddcf_float_left ddcf_markable_container">
						<textarea name="ddcf_contact_message" value="<?php _e('Message','ddcf_plugin') ?>" title="<?php _e('Please enter your message','ddcf_plugin') ?>" id="ddcf_contact_message" name="ddcf_contact_message"  class="ddcf_input_base" /><?php _e('Message','ddcf_plugin') ?></textarea>
						<div id="ddcf_contact_message_fb" class="ddcf_contact_input_verify"></div>
				</div><!-- message area -->

				<!-- bottom bar - Signup option, reset and send mail buttons -->
				<div id="ddcf_opts_and_buttons" class="ddcf_float_left">
						<div id="ddcf_checkbox_area">
							<?php
							if(get_option(ddcf_rec_updates_option_check)) {
                                                                $updates_message = get_option('ddcf_rec_updates_message');
                                                                if($updates_message)
                                                                    echo $updates_message.'&nbsp;&nbsp;<input type="checkbox" name="ddcf_newsletter_signup" id="ddcf_newsletter_signup" class="ddcf_input_base" value="ddcf_newsletter_signup" checked>';
                                                                else echo _('Receive email updates from us in the future?','ddcf_plugin').'&nbsp;&nbsp;<input type="checkbox" name="ddcf_newsletter_signup" id="ddcf_newsletter_signup" class="ddcf_input_base" value="ddcf_newsletter_signup" checked>';
							}?>
						</div> <!-- .ddcf_float_left -->
						<div id="ddcf_button_area">
							<button class="ddcf_button" id="ddcf_contact_reset" value="Reset" ><?php _e('Reset','ddcf_plugin'); ?></button>
                                                        <button class="ddcf_button" id="ddcf_contact_send"  value="Send" > <?php _e('Send','ddcf_plugin'); ?></button>
							<!--div id="ddcf_contact_send_fb" class="ddcf_contact_input_verify"></div-->
						</div> <!-- .ddcf_float_right -->
				</div><!-- bottom bar - Signup option, reset and send mail buttons -->
			<!--/div> < bottom section -->

			<!-- geolocation -->
			<?php
                            $ip_address='notset';$country='notset';$region='notset';$city='notset';
                            if(get_option(ddcf_geo_ip_option_check)){

                                include('ip2locationlite.class.php');

                                // Load the class
                                $ipLite = new ip2location_lite;
                                $ddcf_geoloc_key = get_option(ddcf_geoloc_key);
                                if($ddcf_geoloc_key) {

                                    $ipLite->setKey($ddcf_geoloc_key);

                                    // Get errors and locations
                                    $ip_address=$_SERVER['REMOTE_ADDR'];
                                    $locations = $ipLite->getCity($ip_address);
                                    //$errors = $ipLite->getError();

                                    // Getting the result
                                    if (!empty($locations) && is_array($locations)) {
                                      foreach ($locations as $field => $val) {
                                          if($field=="countryName")     $country=ucwords(strtolower($val));
                                          else if($field=="regionName") $region=ucfirst(strtolower($val));
                                          else if($field=="cityName")   $city=ucfirst(strtolower($val));
                                      }
                                    }
    //                                Old version - updated 7-10-2013
    //                                $ip_address=$_SERVER['REMOTE_ADDR'];
    //                                $xmlurl='http://api.ipinfodb.com/v3/ip-country/?key=6329e8aa0ee61bf5726bcf82f3dae21b73f11d9b9dafcd3b60c86cd8224b91c1&ip='.$ip_address.'&timezone=false';
    //                                $xml=simplexml_load_string($xmlurl);
    //                                if($xml->Status=='OK'){
    //                                    $country=$xml->CountryName;$region=$xml->RegionName;$city=$xml->City;
    //                                }
                                }
                                else
                                {
                                    $country='Geolocation API key missing - see plugin settings for more information';
                                    $region='';
                                    $city='';
                                }
                            }
                        ?>
                        <input type="hidden" name="ddcf_thankyou_url" id="ddcf_thankyou_url" value="<?php echo get_option('ddcf_thankyou_url');?>" />
                        <input type="hidden" name="ddcf_thankyou_type" id="ddcf_thankyou_type" value="<?php echo get_option('ddcf_thankyou_type');?>" />
                        <input type="hidden" name="ddcf_thankyou_message" id="ddcf_thankyou_message" value="<?php echo get_option('ddcf_thankyou_message');?>" />
                        <input type="hidden" name="ddcf_questions_compulsory_check" id="ddcf_questions_compulsory_check" value="<?php echo get_option('ddcf_questions_compulsory_check');?>" />
                        <input type="hidden" name="ddcf_party_size_compulsory_check" id="ddcf_party_size_compulsory_check" value="<?php echo get_option('ddcf_party_size_compulsory_check');?>" />
                        <input type="hidden" name="ddcf_dates_compulsory_check" id="ddcf_dates_compulsory_check" value="<?php echo get_option('ddcf_dates_compulsory_check');?>" />
                        <!-- category filtering fields are tucked into their relevant divs above -->
                        <input type="hidden" name="ddcf_post_title" id="ddcf_post_title" value="<?php echo the_title();?>" />
			<input type="hidden" name="ddcf_ip_address" id="ddcf_ip_address" value="<?php echo $ip_address?>" />
			<input type="hidden" name="ddcf_country" id="ddcf_country" value="<?php echo $country?>" />
			<input type="hidden" name="ddcf_region" id="ddcf_region" value="<?php echo $region?>" />
			<input type="hidden" name="ddcf_city" id="ddcf_city" value="<?php echo $city?>" />
			<input type="hidden" name="action" id="action" value="the_ajax_hook" /> <!-- this puts the action the_ajax_hook into the serialized form -->
			<input type="hidden" name="ddcf_session" id="ddcf_session" value="ddcf_contact_session">
			<input type="hidden" name="ddcf_session_initialised" id="ddcf_session_initialised" value="uninitialised">
                        <input type="hidden" name="ddcf_form_density" id="ddcf_form_density" value="<?php echo $ddcf_form_density ?>">
                        <input type="hidden" name="ddcf_referrer_id" id="ddcf_referrer_id" value="<?php echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'not given' ?>">

			<?php
                            /* create nonces */
                            echo '<input type="hidden" name="ddcf_init_nonce" id="ddcf_init_nonce" value="'.wp_create_nonce('ddcf_contact_initialise_action').'" >';	
                            echo '<input type="hidden" name="ddcf_submit_nonce" id="ddcf_submit_nonce" value="'.wp_create_nonce('ddcf_contact_submit_action').'" >';
                            
                            /* set the error checking method as per options */
                            if(get_settings(ddcf_error_checking_method)=="")
                                echo  '<input type="hidden" name="ddcf_error_checking_method" id="ddcf_error_checking_method" value="realtime">';
                            else if(get_settings(ddcf_error_checking_method)=="realtime")
                                echo  '<input type="hidden" name="ddcf_error_checking_method" id="ddcf_error_checking_method" value="realtime">';
                            else
                                echo '<input type="hidden" name="ddcf_error_checking_method" id="ddcf_error_checking_method" value="onsubmit">';
                            
                            /* set any category filters for the additional form element options */
                            $categories = get_the_category();
                            if($categories){
                                foreach ($categories as $category) {
                                    if (($category->cat_name == get_settings(ddcf_dates_category_filter)) && get_settings(ddcf_dates_category_filter_check))
                                        echo '<input type="hidden" name="ddcf_dates_category" id="ddcf_dates_category" value="' . get_settings(ddcf_dates_category_filter) . '">';
                                    if (($category->cat_name == get_settings(ddcf_party_size_category_filter)) && get_settings(ddcf_party_size_category_filter_check))
                                        echo '<input type="hidden" name="ddcf_party_size_category" id="ddcf_party_size_category" value="' . get_settings(ddcf_party_size_category_filter) . '">';
                                    if (($category->cat_name == get_settings(ddcf_extra_question_category_filter)) && get_settings(ddcf_extra_question_category_filter_check))
                                        echo '<input type="hidden" name="ddcf_extra_question_category" id="ddcf_extra_question_category" value="' . get_settings(ddcf_extra_question_category_filter) . '">';
                                }
                            }
                        ?>

		</form>
	</div> <!-- .ddcf-contact-form -->
</div> <!-- .ddcf-contact-form-wrapper -->