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
    // only display on singlular pages / posts (i.e. don't display on a list of blogs) */
    if (!is_singular()) return;

    // get any shortcode attributes
    /*extract(shortcode_atts(array(
            'num_adults' => 'unset',
            'num_children' => 'unset',
            'arrival_date' => 'unset',
            'departure_date' => 'unset',
            'question_one' => 'unset',
            'question_two' => 'unset',
    ), $attributes));*/
    
    // user prefs
    $start_date_check = get_option(ddcf_start_date_check);

	if (!$start_date_check) $start_date_check = false;
    $start_date_check = $start_date_check && ($start_date != 'false');
    $end_date_check = get_option(ddcf_end_date_check);

	if (!$end_date_check) $end_date_check = false;
    $end_date_check = $end_date_check && ($end_date!='false');
    $extra_dropdown_one_check = get_option(ddcf_extra_dropdown_one_check);
	if (!$extra_dropdown_one_check) $extra_dropdown_one_check = false;
    $extra_dropdown_two_check = get_option(ddcf_extra_dropdown_two_check);

	if (!$extra_dropdown_two_check) $extra_dropdown_two_check = false;
    $ddcf_captcha_type = get_option(ddcf_captcha_type);

	if (!$ddcf_captcha_type) $ddcf_captcha_type = "Simple Addition";

	// local formatting aid variable
    $ddcf_form_density = 0;
?>
<!--[if gte IE 9]>
<style type="text/css">.gradient { filter: none; }</style>
<![endif]-->

<!-- default field values: used when form has been submitted, then page refreshed -->
<div style='display:none' id='ddcf_contact_name_default'><?php _e('Name', 'ddcf_plugin') ?></div>
<div style='display:none' id='ddcf_contact_email_default'><?php _e('Email Address', 'ddcf_plugin') ?></div>
<div style='display:none' id='ddcf_contact_subject_default'><?php _e('Subject', 'ddcf_plugin') ?></div>
<div style='display:none' id='ddcf_contact_message_default'><?php _e('Message', 'ddcf_plugin') ?></div>
<div style='display:none' id='ddcf_start_date_default'><?php _e('Click here', 'ddcf_plugin') ?></div>
<div style='display:none' id='ddcf_end_date_default'><?php _e('Click here', 'ddcf_plugin') ?></div>
<div style='display:none' id='ddcf_question_one_default'><?php echo get_option(ddcf_extra_question_one) ?></div>
<div style='display:none' id='ddcf_question_two_default'><?php echo get_option(ddcf_extra_question_two) ?></div>

<div id="ddcf_contact_form_wrapper">
    <div id="ddcf_throbber"></div>
    <div id="ddcf_contact_form_contents">
        <!-- form user feedback area -->
        <div id="ddcf_error_reporting"></div>
        <form action="#" id="ddcf_contact_form" name="ddcf_contact_form">
            <!-- top left -->
            <div id="ddcf_contact_form_top_left" class="ddcf_float_left">
                <div class="ddcf_float_left">
                    <div class="ddcf_markable_container">
                        <input type="text" name="ddcf_contact_name" id="ddcf_contact_name" class="ddcf_input_base ddcf_contact_text_input <?php echo get_option(ddcf_input_css_classes) ?>" title="<?php _e('Please enter your name','ddcf_plugin') ?>" />
                        <div id="ddcf_contact_name_fb" class="ddcf_contact_input_verify"></div>
                    </div>
                </div>
                <div class="ddcf_float_left ddcf_markable_container">
                    <input type="text" name="ddcf_contact_email" id="ddcf_contact_email" class="ddcf_input_base ddcf_contact_text_input  <?php echo get_option(ddcf_input_css_classes) ?>" title="<?php _e('Please enter your email address','ddcf_plugin') ?>" />
                    <div id="ddcf_contact_email_fb" class="ddcf_contact_input_verify"></div>
                </div>
                <br />
                <div class="ddcf_float_left ddcf_markable_container">
                    <input type="text" name="ddcf_contact_subject" id="ddcf_contact_subject" class="ddcf_input_base ddcf_contact_text_input  <?php echo get_option(ddcf_input_css_classes) ?>" title="<?php _e('Email Subject','ddcf_plugin') ?>" />
                    <div id="ddcf_contact_subject_fb" class="ddcf_contact_input_verify"></div>
                </div>
                <br />
            </div>
            <!-- top left -->
            <!-- top right -->
            <?php

                // check if datepickers are limited to certain category
                $dates_category_filter = true;

                if (get_option(ddcf_dates_category_filter_check)) {
                    // is this page / post in the specified category?
                    $selected_cat = get_option(ddcf_dates_category_filter);
                    global $post;
                    $cats = wp_get_post_categories($post->ID);
                    
                    if ($cats) {
                        foreach ($cats as $cat) {
                            $category = get_category($cat);
                            
                            if ($selected_cat == $category->term_id) $dates_category_filter = false;
                        }
                    }
                } else $dates_category_filter = false;

                // check if dropdowns are limited to certain category
                $dropdown_category_filter = true;
                
                if (get_option(ddcf_dropdown_category_filter_check)) {
                    // is this page / post in the correct category?
                    $selected_cat = get_option(ddcf_numbers_category_filter);
                    //$selected_category = get_category($selected_cat);
                    global $post;
                    $cats = wp_get_post_categories($post->ID);
                    
                    if ($cats) {
                        foreach ($cats as $cat) {
                            $category = get_category($cat);
                            
                            if ($selected_cat == $category->term_id) $dropdown_category_filter = false;
                        }
                    }
                } else $dropdown_category_filter = false;

                // check if extra questions are limited to certain category
                $questions_category_filter = true;
                
                if (get_option(ddcf_extra_questions_category_filter_check)) {
					// is this page / post in the correct category?
					$selected_cat = get_option(ddcf_extra_questions_category_filter);
					//$selected_category = get_category($selected_cat);
					global $post;
					$cats = wp_get_post_categories($post->ID);

					if ($cats) {
                        foreach ($cats as $cat) {
                            $category = get_category($cat);

                            if ($selected_cat == $category->term_id) $questions_category_filter = false;
                        }
					}
                } else $questions_category_filter = false;

                $display_dates_section = ($start_date_check || $end_date_check) && !$dates_category_filter;
                $display_dropdowns_section = ($extra_dropdown_one_check || $extra_dropdown_two_check) && !$dropdown_category_filter;
                $display_captcha_section = $ddcf_captcha_type == 'Simple Addition' || $ddcf_captcha_type == 'reCaptcha';

				if ($display_dates_section || $display_dropdowns_section || $display_captcha_section) {
                    echo '<div id="ddcf_contact_form_top_right">
                            <div id="ddcf_middle_bit">';

                    // google recaptach only
                    if (($ddcf_captcha_type == 'reCaptcha') &&
                        !$start_date_check &&
                        !$end_date_check &&
                        !$extra_dropdown_one_check &&
                        !$extra_dropdown_two_check) {
                            echo '<div class="ddcf_details_row" id="ddcf_captcha_details_row">
                                    <div class="ddcf_details_row_division ddcf_full_width">';

							if (get_option(ddcf_recaptcha_public_key)) {
                                echo '<div id="ddcf_contact_form_captcha" class="ddcf_float_right">
                                         <div id="ddcf_google_recaptcha"></div>
                                         <span id="ddcf_contact_recaptcha_fb" class="ddcf_contact_input_verify"></span>
                                      </div>';
                            }
                            else _e('Missing Google reCaptcha keys - please see dd contact form plugin options', 'ddcf_plugin');
                            // finish off row and row division
                            echo 	'</div>
                                </div>';

							if ($ddcf_form_density < 5) $ddcf_form_density = 5;
                    } // end google recaptach only

                    if ($display_dates_section || $display_dropdowns_section) {
                        echo '<div class="ddcf_details_row" id="ddcf_extra_details_row">';

                        if ($start_date_check || $end_date_check) {

                            // - Booking Dates -

                            // gather necessary user prefs
                            $time_with_date_check = get_option(ddcf_time_with_date_check);
                            if (!$time_with_date_check) $time_with_date_check = false;

                            if (!$dates_category_filter) {

                                if ($display_dropdowns_section)
                                     echo '<div class="ddcf_details_row_division ddcf_half_width" id="ddcf_dates_details_row_division">';
                                else echo '<div class="ddcf_details_row_division ddcf_full_width" id="ddcf_dates_details_row_division">';

                                if ($start_date_check && $end_date_check) {

                                    if ($time_with_date_check)
                                        echo '   <div id="ddcf_dates_container">
                                                    <span class="ddc_span_datetime">
                                                        <div id="ddcf_start_date_fb" class="ddcf_contact_input_verify"></div>
                                                        <input type="text" class="ddcf_input_base ddcf_datetime_picker '.get_option(ddcf_input_css_classes).'" name="ddcf_start_date" id="ddcf_start_date" title="'. __('Click here','ddcf_plugin') .'" />
                                                        <label for="ddcf_start_date" class="ddcf_label">'.__("Start Date:","ddcf_plugin").'</label>
                                                    </span>
                                                    <span class="ddc_span_datetime">
                                                        <div id="ddcf_end_date_fb" class="ddcf_contact_input_verify"></div>
                                                        <input type="text" class="ddcf_input_base ddcf_datetime_picker '.get_option(ddcf_input_css_classes).'" id="ddcf_end_date" name="ddcf_end_date" title="'. __('Click here','ddcf_plugin') .'" />
                                                        <label for="ddcf_end_date" class="ddcf_label">'.__("End Date:","ddcf_plugin").'</label>
                                                    </span>
                                                </div><!-- #ddcf_dates_container-->';
                                    else
                                        echo '   <div id="ddcf_dates_container">
                                                    <span class="ddcf_span_date">
                                                        <div id="ddcf_start_date_fb" class="ddcf_contact_input_verify"></div>
                                                        <input type="text" class="ddcf_input_base ddcf_date_picker '.get_option(ddcf_input_css_classes).'" name="ddcf_start_date" id="ddcf_start_date" title="'. __('Click here','ddcf_plugin') .'" />
                                                        <label for="ddcf_start_date" class="ddcf_label">'.__("Start Date:","ddcf_plugin").'</label>
                                                    </span>
                                                    <span class="ddcf_span_date">
                                                        <div id="ddcf_end_date_fb" class="ddcf_contact_input_verify"></div>
                                                        <input type="text" class="ddcf_input_base ddcf_date_picker '.get_option(ddcf_input_css_classes).'" id="ddcf_end_date" name="ddcf_end_date" title="'. __('Click here','ddcf_plugin') .'" />
                                                        <label for="ddcf_end_date" class="ddcf_label">'.__("End Date:","ddcf_plugin").'</label>
                                                    </span>
                                                </div><!-- #ddcf_dates_container-->';
                                } else if ($start_date_check) {

                                    if ($time_with_date_check)
                                        echo '      <span class="ddc_span_datetime">
                                                        <div id="ddcf_start_date_fb" class="ddcf_contact_input_verify"></div>
                                                        <input type="text" class="ddcf_input_base ddcf_datetime_picker '.get_option(ddcf_input_css_classes).'" id="ddcf_start_date" name="ddcf_start_date" title="'. __('Click here','ddcf_plugin') .'" />
                                                        <label for="ddcf_start_date" class="ddcf_label">'.__("Start Date:","ddcf_plugin").'</label>
                                                    </span>';
                                    else
                                        echo '       <span class="ddcf_span_date">
                                                        <div id="ddcf_start_date_fb" class="ddcf_contact_input_verify"></div>
                                                        <input type="text" class="ddcf_input_base ddcf_date_picker '.get_option(ddcf_input_css_classes).'" id="ddcf_start_date" name="ddcf_start_date" title="'. __('Click here','ddcf_plugin') .'" />
                                                        <label for="ddcf_start_date" class="ddcf_label">'.__("Start Date:","ddcf_plugin").'</label>
                                                    </span>';
                                } else if ($end_date_check) {

                                    if ($time_with_date_check)
                                        echo '      <span class="ddc_span_datetime">
                                                        <div id="ddcf_end_date_fb" class="ddcf_contact_input_verify"></div>
                                                        <input type="text" class="ddcf_input_base ddcf_datetime_picker '.get_option(ddcf_input_css_classes).'" name="ddcf_end_date" id="ddcf_end_date" title="'. __('Click here','ddcf_plugin') .'" />
                                                        <label for="ddcf_end_date" class="ddcf_label">'.__("End Date:","ddcf_plugin").'</label>
                                                    </span>';
                                    else
                                        echo '       <span class="ddcf_span_date">
                                                        <div id="ddcf_end_date_fb" class="ddcf_contact_input_verify"></div>
                                                        <input type="text" class="ddcf_input_base ddcf_date_picker '.get_option(ddcf_input_css_classes).'" name="ddcf_end_date" id="ddcf_end_date" title="'. __('Click here','ddcf_plugin') .'" />
                                                        <label for="ddcf_end_date" class="ddcf_label">'.__("End Date:","ddcf_plugin").'</label>
                                                    </span>';
                                }
                                echo '</div><!-- end ddcf_details_row_division -->';
                                echo '<input type="hidden" name="ddcf_dates_category_filter" id="ddcf_dates_category_filter" value="';
                                
                                if (get_option('ddcf_dates_category_filter_check'))
                                    echo get_option('ddcf_dates_category_filter').'" />';
                                else echo 'unset" />';

                                if ($time_with_date_check && $row_density < 4) $row_density = 4;
                                else if ($row_density < 2) $row_density = 2;
                                // end if (!$dates_category_filter)
                            }
                            
                        } // end if ($start_date_check || $end_date_check)

                        // start of dropdowns
                        if ($extra_dropdown_one_check || $extra_dropdown_two_check) {

                            if ($start_date_check || $end_date_check)
                                echo '<div class="ddcf_details_row_division ddcf_half_width" id="ddcf_party_size_details_row_division">';
                            else echo '<div class="ddcf_details_row_division ddcf_full_width" id="ddcf_party_size_details_row_division">';

                            if (!$dropdown_category_filter) {

                                if ($extra_dropdown_one_check || $extra_dropdown_two_check) {
                                    echo '<div id="ddcf_dropdowns_align">';

                                    if ($extra_dropdown_one_check ) {
                                        echo '
                                            <span class="ddcf_span_dropdown '.get_option(ddcf_input_css_classes).'">
                                                <div id="ddcf_dropdown_one_fb" class="ddcf_contact_input_verify"></div>
                                                <select name="ddcf_dropdown_one" id="ddcf_dropdown_one" class="ddcf_dropdown">
                                                    <option value="0">'.__("None").'</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                    <option value="10">10+</option>
                                                </select>
                                                <label for="ddcf_dropdown_one" class="ddcf_label">'.get_option(ddcf_extra_dropdown_one_label).'</label>
                                            </span>';
                                    }

                                    if ($extra_dropdown_two_check ) {
                                        echo '
                                            <span class="ddcf_span_dropdown '.get_option(ddcf_input_css_classes).'">
                                                <div id="ddcf_dropdown_two_fb" class="ddcf_contact_input_verify"></div>
                                                <select name="ddcf_dropdown_two" id="ddcf_dropdown_two"  class="ddcf_dropdown">
                                                    <option value="0">'.__("None").'</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                    <option value="10">10+</option>
                                                </select>
                                                <label for="ddcf_dropdown_two" class="ddcf_label">'.get_option(ddcf_extra_dropdown_two_label).'</label>
                                            </span>';
                                    }
                                    echo '</div>';
                                } else if ($extra_dropdown_one_check) {
                                    echo '<div id="ddcf_dropdowns_align">
                                            <span class="ddcf_span_dropdown '.get_option(ddcf_input_css_classes).'">
                                                <div id="ddcf_dropdown_one_fb" class="ddcf_contact_input_verify"></div>
                                                <select name="ddcf_dropdown_one" id="ddcf_dropdown_one" class="ddcf_dropdown">
                                                    <option value="0">'.__("None").'</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                    <option value="10">10+</option>
                                                </select>
                                                <label for="ddcf_dropdown_one" class="ddcf_label">'.get_option(ddcf_extra_dropdown_one_label).'</label>
                                            </span>
                                    </div>';
                                } else if ($extra_dropdown_two_check) {
                                    echo '<div id="ddcf_dropdowns_align">
                                            <span class="ddcf_span_dropdown '.get_option(ddcf_input_css_classes).'">
                                                <div id="ddcf_dropdown_two_fb" class="ddcf_contact_input_verify"></div>
                                                <select name="ddcf_dropdown_two" id="ddcf_dropdown_two"  class="ddcf_dropdown">
                                                    <option value="0">'.__("None").'</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                    <option value="10">10+</option>
                                                </select>
                                                <label for="ddcf_dropdown_two" class="ddcf_label">'.get_option(ddcf_extra_dropdown_two_label).'</label>
                                            </span>
                                    </div>';
                                }

                                echo '<input type="hidden" name="ddcf_numbers_category_filter" id="ddcf_numbers_category_filter" value="';
                                if (get_option('ddcf_dropdown_category_filter_check')) echo get_option('ddcf_numbers_category_filter').'" />';
                                else echo 'unset" />';
                                echo '</div>';

                                if ($row_density == 2) $row_density = 6;
                                else if ($row_density == 4) $row_density = 7;
                                else if ($row_density < 1) $row_density = 1;
                            } // end of dropdowns
                        } // endif ($extra_dropdown_one_check||$extra_dropdown_two_check)
                        echo '</div>'; // end #ddcf_extra_details_row
                    }//if ($start_date_check||$end_date_check||$extra_dropdown_one_check||$extra_dropdown_two_check)
                
                    if ($ddcf_form_density < $row_density) $ddcf_form_density = $row_density;

                    // Simple Add Captcha
                    if ($ddcf_captcha_type=='Simple Addition') {
                           // default to Simple Addition
                           echo '<div class="ddcf_details_row" id="ddcf_captcha_details_row">
                                   <div class="ddcf_details_row_division ddcf_full_width">
                                       <div id="ddcf_captcha_add">
                                            <div id="ddcf_captcha_question">What is <span id="ddcf_captcha_one">?</span> plus <span id="ddcf_captcha_two">?</span> ?</div>
                                            <input type="text" name="ddcf_contact_captcha_add" value="" id="ddcf_contact_captcha_add" class="ddcf_input_base '.get_option(ddcf_input_css_classes).'" title="'.__('Please add the two numbers').'"/>
                                            <div id="ddcf_contact_captcha_fb" class="ddcf_contact_input_verify"></div>
                                       </div>
                                   </div>
                               </div>';
                           if ($ddcf_form_density < 3) $ddcf_form_density = 3;
                    } //<!-- end simple addition captcha -->

                    echo '  </div>'; // end #ddcf_middle_bit
                    echo '</div> '; // end #ddcf_contact_form_top_right
                }  // end if ($start_date_check||$end_date_check||$extra_dropdown_one_check||$extra_dropdown_two_check||$ddcf_captcha_type!="none")

                //	<!-- Full width components below -->
                if ($ddcf_captcha_type=='reCaptcha' &&
                    ((($start_date_check || $end_date_check) && !$dates_category_filter) ||
                     (($extra_dropdown_one_check || $extra_dropdown_two_check) && !$dropdown_category_filter))) {
                     echo '<div class="ddcf_float_left">';
                     
                     if (get_option(ddcf_recaptcha_public_key)) {
                        echo '<div id="ddcf_contact_form_captcha" class="ddcf_float_right">
                                  <div id="ddcf_google_recaptcha"></div>
                                  <span id="ddcf_contact_recaptcha_fb" class="ddcf_contact_input_verify"></span>
                              </div>';
                     }
                     else _e('Missing Google reCaptcha keys - please see dd contact form plugin options', 'ddcf_plugin');
                     // finish off row and row division
                     echo '</div>';
                } // end reCaptcha

                //<!-- extra questions -->

                if (!$questions_category_filter) {

					if (get_option(ddcf_extra_question_one_check) || get_option(ddcf_extra_question_two_check)) {

						if (get_option(ddcf_extra_question_one_check) && get_option(ddcf_extra_question_two_check) &&
						    $question_one != 'false' && $question_two != 'false')
							echo '
								<div class="ddcf_float_left ddcf_markable_container">
									<input type="text" id="ddcf_question_one" name="ddcf_question_one" class="ddcf_input_base ddcf_contact_text_input ddcf_question '.get_option(ddcf_input_css_classes).'" value="'.get_option(ddcf_extra_question_one).'" title="'.get_option(ddcf_extra_question_one).'" />
									<div id="ddcf_question_one_fb" class="ddcf_contact_input_verify"></div>
								</div>
								<div class="ddcf_float_left ddcf_markable_container">
									<input type="text" id="ddcf_question_two" name="ddcf_question_two" class="ddcf_input_base ddcf_contact_text_input ddcf_question '.get_option(ddcf_input_css_classes).'" value="'.get_option(ddcf_extra_question_two).'" title="'.get_option(ddcf_extra_question_two).'" />
									<div id="ddcf_question_two_fb" class="ddcf_contact_input_verify"></div>
								</div><!-- both questions --> ';

						else if (get_option(ddcf_extra_question_one_check) && $question_one != 'false')
							echo '
								<div class="ddcf_float_left ddcf_markable_container">
                                    <input type="text" id="ddcf_question_one" name="ddcf_question_one" class="ddcf_input_base ddcf_question '.get_option(ddcf_input_css_classes).'" value="'.get_option(ddcf_extra_question_one).'" title="'.get_option(ddcf_extra_question_one).'" />
                                    <div id="ddcf_question_one_fb" class="ddcf_contact_input_verify"></div>
								</div><!-- extra question 1 -->';

						else if (get_option(ddcf_extra_question_two_check) && $question_two != 'false')
							echo '
								<div class="ddcf_float_left ddcf_markable_container">
									<input type="text" id="ddcf_question_two" name="ddcf_question_two" class="ddcf_input_base ddcf_question '.get_option(ddcf_input_css_classes).'" value="'.get_option(ddcf_extra_question_two).'" title="'.get_option(ddcf_extra_question_two).'" />
									<div id="ddcf_question_two_fb" class="ddcf_contact_input_verify"></div>
								</div><!-- extra question 2 -->';

						if ((get_option(ddcf_extra_question_one_check) && $question_one != 'false') ||
							(get_option(ddcf_extra_question_two_check) && $question_two != 'false')) {
                            echo '<input type="hidden" name="ddcf_extra_questions_category_filter" id="ddcf_extra_questions_category_filter" value="';

                            if (get_option('ddcf_extra_questions_category_filter_check'))
                                echo get_option('ddcf_extra_questions_category_filter').'" />';
                            else echo 'unset" />';
						}
					}
                }
            ?>
            <!-- message area -->
            <div id="ddcf_message" class="ddcf_full_width ddcf_float_left ddcf_markable_container">
                <textarea title="<?php _e('Please enter your message','ddcf_plugin') ?>" id="ddcf_contact_message" name="ddcf_contact_message"  class="ddcf_input_base <?php _e(get_option(ddcf_input_css_classes)) ?>"></textarea>
                <div id="ddcf_contact_message_fb" class="ddcf_contact_input_verify"></div>
            </div>
            <!-- message area -->
            <!-- bottom bar - Signup option, reset and send mail buttons -->
            <div id="ddcf_opts_and_buttons" class="ddcf_float_left">
                <div id="ddcf_checkbox_area">
                    <?php
                        if (get_option(ddcf_offer_to_register_check)) {
                            $updates_message = get_option('ddcf_offer_to_register_custom_message');

                            if (!$updates_message) $updates_message = __('Receive updates? ','ddcf_plugin');
                            echo $updates_message.'&nbsp;&nbsp;<input type="checkbox" name="ddcf_newsletter_signup" id="ddcf_newsletter_signup" checked>';
                        }
                    ?>
                </div>
                <!-- #ddcf_checkbox_area -->
                <div id="ddcf_button_area">
                    <?php
                        $buttons_theme = get_option(ddcf_button_styling);
                        if ($buttons_theme=='ddcf_button_styling_themed') echo '
                            <input class="ddcf_button '.get_option(ddcf_button_css_classes).'" id="ddcf_contact_reset" name="ddcf_contact_reset" type="submit" value="'.__('Reset','ddcf_plugin').'">
                            <input class="ddcf_button '.get_option(ddcf_button_css_classes).'" id="ddcf_contact_send" name="ddcf_contact_send" type="submit" value="'.__('Send','ddcf_plugin').'">';
                        else echo '
                            <button class="ddcf_button '.get_option(ddcf_button_css_classes).'" id="ddcf_contact_reset" name="ddcf_contact_reset" type="reset" value="" >'.__('Reset','ddcf_plugin').'</button>
                            <button class="ddcf_button '.get_option(ddcf_button_css_classes).'" id="ddcf_contact_send" name="ddcf_contact_send" type="submit" value="" >'.__('Send','ddcf_plugin').'</button>';
                        echo '<input type="hidden" id="ddcf_button_styling" name="ddcf_button_styling" value="'.$buttons_theme.'" />';
                    ?>
                </div>
            </div>
            <!-- bottom bar - Signup option, reset and send mail buttons -->
            <!-- geolocation -->
            <?php
                $ddcf_ip_address = __('not set');
                $country = __('not set');
                $region = __('not set');
                $city = __('not set');

                if (get_option(ddcf_geolocation_check)) {
                    /* get the IP address */
                    $ddcf_ip_address = $_SERVER['REMOTE_ADDR'];
                    include('ip2locationlite.class.php');
                    // Load the class
                    $ipLite = new ip2location_lite;
                    $ddcf_geoloc_key = get_option(ddcf_geoloc_key);
                    if ($ddcf_geoloc_key) {
                        $ipLite->setKey($ddcf_geoloc_key);
                        $locations = $ipLite->getCity($ddcf_ip_address);
                        // Get the result
                        if (!empty($locations) && is_array($locations)) {
                            foreach ($locations as $field => $val) {
                                if ($field == "countryName") $country = ucwords(strtolower($val));
                                else if ($field == "regionName") $region = ucfirst(strtolower($val));
                                else if ($field == "cityName") $city = ucfirst(strtolower($val));
                            }
                        }
                    } else {
                        $country = __('Geolocation API key missing');
                        $region = __('See contact form');
                        $city = __('plugin settings');
                    }
                }
            ?>
            <input type="hidden" name="ddcf_thankyou_url" id="ddcf_thankyou_url" value="<?php echo get_option('ddcf_thankyou_url');?>" />
            <input type="hidden" name="ddcf_thankyou_type" id="ddcf_thankyou_type" value="<?php echo get_option('ddcf_thankyou_type');?>" />
            <input type="hidden" name="ddcf_thankyou_message" id="ddcf_thankyou_message" value="<?php echo get_option('ddcf_thankyou_message');?>" />
            <input type="hidden" name="ddcf_questions_compulsory_check" id="ddcf_questions_compulsory_check" value="<?php echo get_option('ddcf_questions_compulsory_check');?>" />
            <input type="hidden" name="ddcf_numbers_compulsory_check" id="ddcf_numbers_compulsory_check" value="<?php echo get_option('ddcf_numbers_compulsory_check');?>" />
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
            <!-- shortcode attributes 
            <input type="hidden" name="dropdown_numbers_one" id="dropdown_numbers_one" value="<?php echo $dropdownOneValue ?>">
            <input type="hidden" name="dropdown_numbers_two" id="dropdown_numbers_two" value="<?php echo $dropdownTwoValue ?>">
            <input type="hidden" name="start_date" id="start_date" value="<?php echo $start_date ?>">
            <input type="hidden" name="end_date" id="end_date" value="<?php echo $end_date ?>">
            <input type="hidden" name="question_one" id="question_one" value="<?php echo $question_one ?>">
            <input type="hidden" name="question_two" id="question_two" value="<?php echo $question_two ?>">-->

            <?php
                // create nonces
                echo '<input type="hidden" name="ddcf_init_nonce" id="ddcf_init_nonce" value="'.wp_create_nonce('ddcf_contact_initialise_action').'" >';
                echo '<input type="hidden" name="ddcf_submit_nonce" id="ddcf_submit_nonce" value="'.wp_create_nonce('ddcf_contact_submit_action').'" >';

                // set the error checking method as per options
                if (get_settings(ddcf_error_checking_method)=="" ||
                    get_settings(ddcf_error_checking_method)=="realtime")
                    echo  '<input type="hidden" name="ddcf_error_checking_method" id="ddcf_error_checking_method" value="realtime">';
                else
                    echo '<input type="hidden" name="ddcf_error_checking_method" id="ddcf_error_checking_method" value="onsubmit">';

                // set any category filters for the additional form element options
                $categories = get_the_category();

                if ($categories) {
                    foreach ($categories as $category) {
                        
                        if (($category->cat_name == get_settings(ddcf_dates_category_filter)) && get_settings(ddcf_dates_category_filter_check))
                            echo '<input type="hidden" name="ddcf_dates_category" id="ddcf_dates_category" value="' . get_settings(ddcf_dates_category_filter) . '">';
                        
                        if (($category->cat_name == get_settings(ddcf_numbers_category_filter)) && get_settings(ddcf_dropdown_category_filter_check))
                            echo '<input type="hidden" name="ddcf_party_size_category" id="ddcf_party_size_category" value="' . get_settings(ddcf_numbers_category_filter) . '">';
                        
                        if (($category->cat_name == get_settings(ddcf_extra_questions_category_filter)) && get_settings(ddcf_extra_questions_category_filter_check))
                            echo '<input type="hidden" name="ddcf_extra_question_category" id="ddcf_extra_question_category" value="' . get_settings(ddcf_extra_questions_category_filter) . '">';
                    }
                }
            ?>
        </form>
    </div><!-- #ddcf_contact_form_contents -->
</div><!-- #ddcf_contact_form_wrapper -->