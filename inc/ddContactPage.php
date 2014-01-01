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

<!-- prepare php vars -->
<?php
    // clear any POST data
    unset($_POST); 
    // get any shortcode attributes
    extract(shortcode_atts(array(
            'num_adults' => 'unset',
            'num_children' => 'unset',        
            'arrival_date' => 'unset',
            'departure_date' => 'unset',            
            'question_one' => 'unset',
            'question_two' => 'unset',
    ),$attributes));
    // user prefs, with defaults
    $start_date_check = get_option(ddcf_start_date_check);
    if(!$start_date_check) $start_date_check = false;
    $start_date_check = $start_date_check && ($arrival_date!='false');
    $end_date_check = get_option(ddcf_end_date_check);
    if(!$end_date_check) $end_date_check = false;
    $end_date_check = $end_date_check && ($departure_date!='false');
    $extra_dropdown_one_check = get_option(ddcf_extra_dropdown_one_check);
    if(!$extra_dropdown_one_check) $extra_dropdown_one_check = false;
    $extra_dropdown_one_check = $extra_dropdown_one_check && ($num_adults!='false');
    $extra_dropdown_two_check = get_option(ddcf_extra_dropdown_two_check);
    if(!$extra_dropdown_two_check) $extra_dropdown_two_check = false;
    $extra_dropdown_two_check = $extra_dropdown_two_check && ($num_children!='false');    
    $ddcf_captcha_type = get_option(ddcf_captcha_type);
    if(!$ddcf_captcha_type) $ddcf_captcha_type = "Simple Addition";
    // local vars
    $ddcf_form_density = 0;    
?>

<!--[if gte IE 9]><style type="text/css">.gradient { filter: none; }</style><![endif]-->

<div id="ddcf_contact_form_wrapper" name="ddcf_contact_form_wrapper">
    
    <div id="ddcf_throbber" name="ddcf_throbber"></div>
    
	<div id="ddcf_contact_form_contents" name="ddcf_contact_form_contents">

		<!-- form user feedback area -->
		<div id="ddcf_error_reporting" name="ddcf_error_reporting"></div>

		<form action="" method="" id="ddcf_contact_form" name="ddcf_contact_form">

			<!-- top left -->
			<div id="ddcf_contact_form_top_left" class="ddcf_float_left">
				<div class="ddcf_float_left">
                                    <div class="ddcf_markable_container">
					<input type="text" name="ddcf_contact_name" value="<?php _e('Name','ddcf_plugin') ?>" id="ddcf_contact_name" class="ddcf_input_base ddcf_contact_text_input wpcf7-text" title="<?php _e('Please enter your name','ddcf_plugin') ?>" />
					<div id="ddcf_contact_name_fb" class="ddcf_contact_input_verify"></div>
                                    </div>
				</div>
				<div class="ddcf_float_left ddcf_markable_container">
					<input type="text" name="ddcf_contact_email" value="<?php _e('Email Address','ddcf_plugin') ?>" id="ddcf_contact_email" class="ddcf_input_base ddcf_contact_text_input wpcf7-email" title="<?php _e('Please enter your email address','ddcf_plugin') ?>" />
					<div id="ddcf_contact_email_fb" class="ddcf_contact_input_verify"></div>
				</div>
				<br />
				<div class="ddcf_float_left ddcf_markable_container">
					<input type="text" name="ddcf_contact_subject" value="<?php _e('Subject','ddcf_plugin') ?>" id="ddcf_contact_subject" class="ddcf_input_base ddcf_contact_text_input wpcf7-text" title="<?php _e('Email Subject','ddcf_plugin') ?>" />
					<div id="ddcf_contact_subject_fb" class="ddcf_contact_input_verify"></div>
				</div>
				<br />
			</div> <!-- top left -->


			<!-- top right -->
			
                            <?php                                    
                                    if($start_date_check
                                        ||$end_date_check
                                        ||$extra_dropdown_one_check
                                        ||$extra_dropdown_two_check
                                        ||$ddcf_captcha_type=='Simple Addition'
                                        ||$ddcf_captcha_type=='reCaptcha') 
                                        {
                                        echo '<div id="ddcf_contact_form_top_right" name="ddcf_contact_form_top_right">
                                                <div id="ddcf_middle_bit" name="ddcf_middle_bit">';
                                        
                                        if(($ddcf_captcha_type=='reCaptcha')
                                            &&!$start_date_check
                                            &&!$end_date_check
                                            &&!$extra_dropdown_one_check
                                            &&!$extra_dropdown_two_check) {
                                                echo '<div class="ddcf_details_row" name="ddcf_captcha_details_row" id="ddcf_captcha_details_row">
                                                        <div class="ddcf_details_row_division ddcf_full_width">';
                                                if(get_option(ddcf_recaptcha_public_key)) {
                                                    echo '<div id="ddcf_contact_form_captcha" name="ddcf_contact_form_captcha" class="ddcf_float_right">
                                                            <div id="ddcf_google_recaptcha" name="ddcf_google_recaptcha" ></div>
                                                            <span id="ddcf_contact_recaptcha_fb" name="ddcf_contact_recaptcha_fb" class="ddcf_contact_input_verify"></span>
                                                         </div>';
                                                }
                                                else _e('Missing Google reCaptcha keys - please see plugin options', 'ddcf_plugin');
                                                // finish off row and row division
                                                echo 	'</div>
                                                    </div>';
                                                if($ddcf_form_density<5) $ddcf_form_density = 5;
                                        } // end reCaptcha                                        
                                        
                                        
                                        if($start_date_check||$end_date_check||$extra_dropdown_one_check||$extra_dropdown_two_check) {
                                            echo '<div class="ddcf_details_row" name="ddcf_extra_details_row" id="ddcf_extra_details_row">';                                            
                                            
                                            if($start_date_check||$end_date_check) {

                                                // - Booking Dates -

                                                // gather necessary user prefs
                                                $start_date_time_check = get_option(ddcf_start_date_time_check);
                                                if(!$start_date_time_check) $start_date_time_check = false;
                                                $end_date_time_check   = get_option(ddcf_end_date_time_check);
                                                if(!$end_date_time_check) $end_date_time_check = false;                                              

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
                                                    if($extra_dropdown_one_check||$extra_dropdown_two_check )
                                                         echo '<div class="ddcf_details_row_division ddcf_half_width" name="ddcf_dates_details_row_division" id="ddcf_dates_details_row_division">';
                                                    else echo '<div class="ddcf_details_row_division ddcf_full_width" name="ddcf_dates_details_row_division" id="ddcf_dates_details_row_division">';
                                                    if($start_date_check&&$end_date_check) {
                                                        if($start_date_time_check||$end_date_time_check)
                                                            echo'   <div id="ddcf_dates_container" name="ddcf_dates_container">      
                                                                        <span class="ddc_span_datetime">                                                                            
                                                                            <div name="ddcf_arrival_date_fb" id="ddcf_arrival_date_fb" class="ddcf_contact_input_verify"></div>
                                                                            <input type="text" class="ddcf_input_base ddcf_datetime_picker wpcf7-text" name="ddcf_arrival_date" id="ddcf_arrival_date" value="'. __('Click here','ddcf_plugin') .'" title="'. __('Click here','ddcf_plugin') .'" />
                                                                            <label for="ddcf_arrival_date">'.__("Booking Start:","ddcf_plugin").'</label>
                                                                        </span>
                                                                        <span class="ddc_span_datetime">
                                                                            <div name="ddcf_departure_date_fb" id="ddcf_departure_date_fb" class="ddcf_contact_input_verify"></div>
                                                                            <input type="text" class="ddcf_input_base ddcf_datetime_picker wpcf7-text" name="ddcf_departure_date" id="ddcf_departure_date" value="'. __('Click here','ddcf_plugin') .'" title="'. __('Click here','ddcf_plugin') .'" />
                                                                            <label for="ddcf_departure_date">'.__("Booking End:","ddcf_plugin").'</label>
                                                                        </span>
                                                                    </div><!-- #ddcf_dates_container-->';
                                                        else
                                                            echo'   <div id="ddcf_dates_container" name="ddcf_dates_container">      
                                                                        <span class="ddcf_span_date">
                                                                            <div id="ddcf_arrival_date_fb" name="ddcf_arrival_date_fb" class="ddcf_contact_input_verify"></div>
                                                                            <input type="text" class="ddcf_input_base ddcf_date_picker wpcf7-text" name="ddcf_arrival_date" id="ddcf_arrival_date" value="'. __('Click here','ddcf_plugin') .'" title="'. __('Click here','ddcf_plugin') .'" />
                                                                            <label for="ddcf_arrival_date">'.__("Booking Start:","ddcf_plugin").'</label>
                                                                        </span>
                                                                        <span class="ddcf_span_date">
                                                                            <div name="ddcf_departure_date_fb" id="ddcf_departure_date_fb" class="ddcf_contact_input_verify"></div>
                                                                            <input type="text" class="ddcf_input_base ddcf_date_picker wpcf7-text" name="ddcf_departure_date" id="ddcf_departure_date" value="'. __('Click here','ddcf_plugin') .'" title="'. __('Click here','ddcf_plugin') .'" />
                                                                            <label for="ddcf_departure_date">'.__("Booking End:","ddcf_plugin").'</label>
                                                                        </span>
                                                                    </div><!-- #ddcf_dates_container-->';
                                                    }
                                                    else if($start_date_check) {
                                                        if($start_date_time_check||$end_date_time_check)
                                                            echo'      <span class="ddc_span_datetime">
                                                                            <div id="ddcf_arrival_date_fb" name="ddcf_arrival_date_fb" class="ddcf_contact_input_verify"></div>
                                                                            <input type="text" class="ddcf_input_base ddcf_datetime_picker wpcf7-text" name="ddcf_arrival_date" id="ddcf_arrival_date" value="'. __('Click here','ddcf_plugin') .'" title="'. __('Click here','ddcf_plugin') .'" />
                                                                            <label for="ddcf_arrival_date">'.__("Booking Start:","ddcf_plugin").'</label>
                                                                        </span>';
                                                        else
                                                            echo'       <span class="ddcf_span_date">
                                                                            <div id="ddcf_arrival_date_fb" name="ddcf_arrival_date_fb" class="ddcf_contact_input_verify"></div>
                                                                            <input type="text" class="ddcf_input_base ddcf_date_picker wpcf7-text" name="ddcf_arrival_date" id="ddcf_arrival_date" value="'. __('Click here','ddcf_plugin') .'" title="'. __('Click here','ddcf_plugin') .'" />
                                                                            <label for="ddcf_arrival_date">'.__("Booking Start:","ddcf_plugin").'</label>
                                                                        </span>';
                                                    }
                                                    else if($end_date_check) {
                                                        if($start_date_time_check||$end_date_time_check)
                                                            echo'      <span class="ddc_span_datetime">
                                                                            <div id="ddcf_departure_date_fb" name="ddcf_departure_date_fb" class="ddcf_contact_input_verify"></div>
                                                                            <input type="text" class="ddcf_input_base ddcf_datetime_picker wpcf7-text" name="ddcf_departure_date" id="ddcf_departure_date" value="'. __('Click here','ddcf_plugin') .'" title="'. __('Click here','ddcf_plugin') .'" />
                                                                            <label for="ddcf_departure_date">'.__("Booking End:","ddcf_plugin").'</label>
                                                                        </span>';
                                                        else
                                                            echo'       <span class="ddcf_span_date">
                                                                            <div id="ddcf_departure_date_fb" name="ddcf_departure_date_fb" class="ddcf_contact_input_verify"></div>
                                                                            <input type="text" class="ddcf_input_base ddcf_date_picker wpcf7-text" name="ddcf_departure_date" id="ddcf_departure_date" value="'. __('Click here','ddcf_plugin') .'" title="'. __('Click here','ddcf_plugin') .'" />
                                                                            <label for="ddcf_departure_date">'.__("Booking End:","ddcf_plugin").'</label>
                                                                        </span>';
                                                    }                                                
                                                    echo '<input type="hidden" name="ddcf_dates_category_filter" id="ddcf_dates_category_filter" value="'; 
                                                    if(get_option('ddcf_dates_category_filter_check')) 
                                                        echo get_option('ddcf_dates_category_filter').'" />';
                                                    else echo 'unset" />';

                                                    if(($start_date_time_check||$end_date_time_check)&&$row_density<4) $row_density=4;
                                                    else if($row_density<2) $row_density=2;
                                                } // if(!wrong_category)

                                                    echo '</div>'; // end ddcf_details_row_division 

                                            } // if($start_date_check||$end_date_check)



                                        // start of dropdowns
                                        if($extra_dropdown_one_check||$extra_dropdown_two_check) {

                                               if($start_date_time_check||$end_date_time_check||$start_date_check||$end_date_check)
                                                    echo '<div class="ddcf_details_row_division ddcf_half_width" name="ddcf_party_size_details_row_division" id="ddcf_party_size_details_row_division">';
                                               else echo '<div class="ddcf_details_row_division ddcf_full_width" name="ddcf_party_size_details_row_division" id="ddcf_party_size_details_row_division">';                                             


                                            // gather necessary user prefs                                           
                                               
                                               
                                            $ddcf_num_adults = get_option(ddcf_num_adults);
                                            if(!$ddcf_num_adults) $ddcf_num_adults = false;
                                            $ddcf_num_adults = $ddcf_num_adults && ($num_adults!='false');
                                            
                                            $ddcf_num_children = get_option(ddcf_num_children);
                                            if(!$ddcf_num_children) $ddcf_num_children = false;
                                            $ddcf_num_children = $ddcf_num_children && ($num_children!='false');

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

                                                if($extra_dropdown_one_check&&$extra_dropdown_two_check) {
                                                    echo'<div name="ddcf_dropdowns_align" id="ddcf_dropdowns_align">
                                                            <span class="ddcf_table_span_dropdown ddcf_input_base">
                                                                    <div id="ddcf_num_adults_fb" class="ddcf_contact_input_verify"></div>
                                                                    <select name="ddcf_num_adults" id="ddcf_num_adults" class="ddcf_dropdown wpcf7-text">
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
                                                                    <label for="ddcf_num_adults">'.__("Adults:", "ddcf_plugin").'</label>
                                                            </span>
                                                            <span class="ddcf_table_span_dropdown ddcf_input_base">
                                                                    <div id="ddcf_num_children_fb" class="ddcf_contact_input_verify"></div>
                                                                    <select name="ddcf_num_children" id="ddcf_num_children"  class="ddcf_dropdown wpcf7-text">
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
                                                                    <label for="ddcf_num_children">'.__("Children:","ddcf_plugin").'</label>
                                                            </span>
                                                    </div>';
                                                }
                                                else if($extra_dropdown_one_check) {
                                                    echo'<div name="ddcf_dropdowns_align" id="ddcf_dropdowns_align">
                                                            <span class="ddcf_table_span_dropdown ddcf_input_base">
                                                                    <div id="ddcf_num_adults_fb" class="ddcf_contact_input_verify"></div>
                                                                    <select name="ddcf_num_adults" id="ddcf_num_adults" class="ddcf_dropdown wpcf7-text">
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
                                                                    <label for="ddcf_num_adults">'.__("Adults:", "ddcf_plugin").'</label>
                                                            </span>
                                                    </div>';
                                                }
                                                else if($extra_dropdown_two_check) {
                                                    echo'<div name="ddcf_dropdowns_align" id="ddcf_dropdowns_align">
                                                            <span class="ddcf_table_span_dropdown ddcf_input_base">
                                                                    <div id="ddcf_num_children_fb" class="ddcf_contact_input_verify"></div>
                                                                    <select name="ddcf_num_children" id="ddcf_num_children"  class="ddcf_dropdown wpcf7-text">
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
                                                                    <label for="ddcf_num_children">'.__("Children:","ddcf_plugin").'</label>
                                                            </span>
                                                    </div>';
                                                }

                                                echo '<input type="hidden" name="ddcf_party_size_category_filter" id="ddcf_party_size_category_filter" value="'; 
                                                if(get_option('ddcf_party_size_category_filter_check')) 
                                                    echo get_option('ddcf_party_size_category_filter').'" />';
                                                else echo 'unset" />';
                                                echo '</div>';

                                                if($row_density==2) $row_density = 6;
                                                else if($row_density==4) $row_density = 7;
                                                else if($row_density<1) $row_density = 1;
                                            } // end of dropdowns
                                        } // endif($extra_dropdown_one_check||$extra_dropdown_two_check)
                                    echo '</div>'; // end #ddcf_extra_details_row
                                   }//if($start_date_check||$end_date_check||$extra_dropdown_one_check||$extra_dropdown_two_check) 
                                   if($ddcf_form_density<$row_density) $ddcf_form_density = $row_density; 

                                   
                                   
                                   // Simple Add Captcha
                                   if($ddcf_captcha_type=='Simple Addition') {
                                           // default to Simple Addition
                                           echo '<div class="ddcf_details_row" name="ddcf_captcha_details_row" id="ddcf_captcha_details_row">
                                                   <div class="ddcf_details_row_division ddcf_full_width">
                                                       <span name="ddcf_span_captcha_add" id="ddcf_span_captcha_add">
                                                           <!--div id="ddcf_contact_form_captcha" name="ddcf_contact_form_captcha" class="ddcf_float_right"-->
                                                               <!--div id="ddcf_simple_add_captcha" name="ddcf_simple_add_captcha"-->
                                                               <div name="ddcf_captcha_question" id="ddcf_captcha_question">What is <span id="ddcf_captcha_one">?</span> plus <span id="ddcf_captcha_two">?</span> ?</div>
                                                                   <input type="text" name="ddcf_contact_captcha_add" value="" id="ddcf_contact_captcha_add" class="wpcf7-text ddcf_input_base" title="'.__('Please add the two numbers').'"/>
                                                                   <div id="ddcf_contact_captcha_fb" name="ddcf_contact_captcha_fb" class="ddcf_contact_input_verify"></div>

                                                               <!--/div-->
                                                           <!--/div-->
                                                       </span>
                                                   </div>
                                               </div>';
                                           if($ddcf_form_density<3) $ddcf_form_density = 3;
                                   }  //<!-- end simple addition captcha -->                                         

                               echo'   </div>'; // end #ddcf_middle_bit
                               echo'</div> '; // end #ddcf_contact_form_top_right
                               }  // end if($start_date_check||$end_date_check||$extra_dropdown_one_check||$extra_dropdown_two_check||$ddcf_captcha_type!="none")
                          
                        

		//	<!-- Full width components below -->
                        
                        
                        
                                if($ddcf_captcha_type=='reCaptcha'
                                         &&($start_date_check
                                         ||$end_date_check
                                         ||$extra_dropdown_one_check
                                         ||$extra_dropdown_two_check)) {
                                         echo '<div class="ddcf_float_left">';
                                         if(get_option(ddcf_recaptcha_public_key)) {
                                                 echo '<div id="ddcf_contact_form_captcha" name="ddcf_contact_form_captcha" class="ddcf_float_right">
                                                         <div id="ddcf_google_recaptcha" name="ddcf_google_recaptcha" ></div>
                                                         <span id="ddcf_contact_recaptcha_fb" name="ddcf_contact_recaptcha_fb" class="ddcf_contact_input_verify"></span>
                                                      </div>';
                                         }
                                         else    _e('Missing Google reCaptcha keys - please see plugin options', 'ddcf_plugin');
                                         // finish off row and row division
                                         echo '</div>';
                                 } // end reCaptcha                          


                                //<!-- extra questions -->
				
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
                                        if(get_option(ddcf_extra_question_one_check) && get_option(ddcf_extra_question_two_check)
                                         && $question_one!='false' && $question_two!='false')

                                            echo '
                                        <div class="ddcf_float_left ddcf_markable_container">
                                            <div style="position:relative; width:100%; overflow:hidden">
                                                <input type="text" id="ddcf_question_one" name="ddcf_question_one" class="ddcf_input_base ddcf_question wpcf7-text" value="'.get_option(ddcf_extra_question_one).'" title="'.get_option(ddcf_extra_question_one).'" />
                                                <div name="ddcf_question_one_fb" id="ddcf_question_one_fb" class="ddcf_contact_input_verify"></div>
                                            </div>
                                        </div>
                                        <div class="ddcf_float_left ddcf_markable_container">
                                            <div style="position:relative; width:100%; overflow:hidden">
                                                <input type="text" id="ddcf_question_two" name="ddcf_question_two" class="ddcf_input_base ddcf_question wpcf7-text" value="'.get_option(ddcf_extra_question_two).'" title="'.get_option(ddcf_extra_question_two).'" />
                                                <div name="ddcf_question_two_fb" id="ddcf_question_two_fb" class="ddcf_contact_input_verify"></div>
                                            </div>
                                        </div><!-- both questions --> ';

                                        else if(get_option(ddcf_extra_question_one_check) && $question_one!='false') echo '
                                        <div class="ddcf_float_left ddcf_markable_container">
                                                <input type="text" id="ddcf_question_one" name="ddcf_question_one" class="ddcf_input_base ddcf_question wpcf7-text" value="'.get_option(ddcf_extra_question_one).'" title="'.get_option(ddcf_extra_question_one).'" />
                                                <div name="ddcf_question_two_fb" id="ddcf_question_one_fb" class="ddcf_contact_input_verify"></div>
                                        </div><!-- extra question 1 -->';


                                        else if(get_option(ddcf_extra_question_two_check) && $question_two!='false') echo '
                                        <div class="ddcf_float_left ddcf_markable_container">
                                                <input type="text" id="ddcf_question_two" name="ddcf_question_two" class="ddcf_input_base ddcf_question wpcf7-text" value="'.get_option(ddcf_extra_question_two).'" title="'.get_option(ddcf_extra_question_two).'" />
                                                <div id="ddcf_question_two_fb" class="ddcf_contact_input_verify"></div>
                                        </div><!-- extra question 2 -->';

                                        if((get_option(ddcf_extra_question_one_check) && $question_one!='false')
                                         ||(get_option(ddcf_extra_question_two_check) && $question_two!='false')) {
                                                echo '<input type="hidden" name="ddcf_extra_question_category_filter" id="ddcf_extra_question_category_filter" value="'; 
                                                if(get_option('ddcf_extra_question_category_filter_check')) 
                                                    echo get_option('ddcf_extra_question_category_filter').'" />';
                                                else echo 'unset" />';
                                         }
                                    }
                                }
                        ?>

                        
                        
                        <!-- message area -->
                        <div id="ddcf_message" class="ddcf_full_width ddcf_float_left ddcf_markable_container">
                                        <textarea name="ddcf_contact_message" value="<?php _e('Message','ddcf_plugin') ?>" title="<?php _e('Please enter your message','ddcf_plugin') ?>" id="ddcf_contact_message" name="ddcf_contact_message"  class="ddcf_input_base wpcf7-textarea" /><?php _e('Message','ddcf_plugin') ?></textarea>
                                        <div id="ddcf_contact_message_fb" class="ddcf_contact_input_verify"></div>
                        </div><!-- message area -->

                                
                                
                                
                                
                        <!-- bottom bar - Signup option, reset and send mail buttons -->
                        <div id="ddcf_opts_and_buttons" name="ddcf_opts_and_buttons" class="ddcf_float_left">
                            <div id="ddcf_checkbox_area" name="ddcf_checkbox_area">
                                <?php
                                if(get_option(ddcf_rec_updates_option_check)) {
                                        $updates_message = get_option('ddcf_rec_updates_message');
                                        if(!$updates_message) $updates_message = __('Receive updates? ','ddcf_plugin');
                                        echo $updates_message.'&nbsp;&nbsp;<input type="checkbox" name="ddcf_newsletter_signup" id="ddcf_newsletter_signup" class="ddcf_input_base" value="ddcf_newsletter_signup" checked>';
                                }?>
                            </div> <!-- #ddcf_checkbox_area -->
                            
                            <div id="ddcf_button_area">
                            <?php
                            $buttons_theme = get_option(ddcf_btn_style);
                            if($buttons_theme=='ddcf_btn_style_themed') echo'
                                <input class="ddcf_button wpcf7-submit" id="ddcf_contact_reset" name="ddcf_contact_reset" type="submit" value="'.__('Reset','ddcf_plugin').'">
                                <input class="ddcf_button wpcf7-submit" id="ddcf_contact_send" name="ddcf_contact_send" type="submit" value="'.__('Send','ddcf_plugin').'">';
                            else echo'
                                <button class="ddcf_button" id="ddcf_contact_reset" name="ddcf_contact_reset" type="reset" value="" >'.__('Reset','ddcf_plugin').'</button>
                                <button class="ddcf_button" id="ddcf_contact_send" name="ddcf_contact_send" type="submit" value="" >'.__('Send','ddcf_plugin').'</button>';
                            echo '<input type="hidden" id="ddcf_btn_style" name="ddcf_btn_style" value="'.$buttons_theme.'" />';
                            ?>        
                            </div>
                        </div><!-- bottom bar - Signup option, reset and send mail buttons -->

			<!-- geolocation -->
			<?php
                            $ddcf_ip_address='notset';$country='notset';$region='notset';$city='notset';
                            if(get_option(ddcf_geo_ip_option_check)){

                                /* get the IP address */
                                $ddcf_ip_address=$_SERVER['REMOTE_ADDR'];
                                
                                include('ip2locationlite.class.php');

                                // Load the class
                                $ipLite = new ip2location_lite;
                                $ddcf_geoloc_key = get_option(ddcf_geoloc_key);
                                if($ddcf_geoloc_key) {

                                    $ipLite->setKey($ddcf_geoloc_key);

                                    // Get errors and locations
                                    $locations = $ipLite->getCity($ddcf_ip_address);
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
    //                                $ddcf_ip_address=$_SERVER['REMOTE_ADDR'];
    //                                $xmlurl='http://api.ipinfodb.com/v3/ip-country/?key=6329e8aa0ee61bf5726bcf82f3dae21b73f11d9b9dafcd3b60c86cd8224b91c1&ip='.$ddcf_ip_address.'&timezone=false';
    //                                $xml=simplexml_load_string($xmlurl);
    //                                if($xml->Status=='OK'){
    //                                    $country=$xml->CountryName;$region=$xml->RegionName;$city=$xml->City;
    //                                }
                                }
                                else
                                {
                                    $country ='unknown';
                                    $region='Geolocation API key missing';
                                    $city ='See plugin settings';
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
			<input type="hidden" name="ddcf_ip_address" id="ddcf_ip_address" value="<?php echo $ddcf_ip_address?>" />
			<input type="hidden" name="ddcf_country" id="ddcf_country" value="<?php echo $country?>" />
			<input type="hidden" name="ddcf_region" id="ddcf_region" value="<?php echo $region?>" />
			<input type="hidden" name="ddcf_city" id="ddcf_city" value="<?php echo $city?>" />
			<input type="hidden" name="action" id="action" value="the_ajax_hook" /> <!-- this puts the action the_ajax_hook into the serialized form -->
			<input type="hidden" name="ddcf_session" id="ddcf_session" value="ddcf_contact_session">
			<input type="hidden" name="ddcf_session_initialised" id="ddcf_session_initialised" value="uninitialised">
                        <input type="hidden" name="ddcf_form_density" id="ddcf_form_density" value="<?php echo $ddcf_form_density ?>">
                        <input type="hidden" name="ddcf_referrer_id" id="ddcf_referrer_id" value="<?php echo $_SERVER['HTTP_REFERER'] ?>">
                        
                        <!-- shortcode attributes -->
                        <input type="hidden" name="num_adults" id="num_adults" value="<?php echo $num_adults ?>">                        
                        <input type="hidden" name="num_children" id="num_children" value="<?php echo $num_children ?>">
                        <input type="hidden" name="arrival_date" id="arrival_date" value="<?php echo $arrival_date ?>">
                        <input type="hidden" name="departure_date" id="departure_date" value="<?php echo $departure_date ?>">
                        <input type="hidden" name="question_one" id="question_one" value="<?php echo $question_one ?>">
                        <input type="hidden" name="question_two" id="question_two" value="<?php echo $question_two ?>">

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