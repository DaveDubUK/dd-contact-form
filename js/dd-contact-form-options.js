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

var ddcfSneakyAjax;
var ddcfGreyOutColor = "#555";

function selectCustomCSS(isSelected) {
	if(isSelected) {
            jQuery('#ddcf_custom_css').css("background-color","#ffffff").css("opacity","1");
            jQuery('#ddcf_update_css_btn').prop('disabled', false);            
        }
	else {
            jQuery('#ddcf_custom_css').css("background-color",ddcfGreyOutColor).css("opacity","0.35");
            jQuery('#ddcf_update_css_btn').prop('disabled', true); 
        }
}

function selectTextareaOne(isSelected) {
	if(isSelected) jQuery('#ddcf_extra_question_one').css("background-color","#ffffff").css("opacity","1");
	else jQuery('#ddcf_extra_question_one').css("background-color","#ececec").css("opacity","0.35");
}

function selectTextareaTwo(isSelected) {
	if(isSelected) jQuery('#ddcf_extra_question_two').css("background-color","#ffffff").css("opacity","1");
	else jQuery('#ddcf_extra_question_two').css("background-color","#ececec").css("opacity","0.35");
}

function selectExtraQuestionFilter(isSelected) {
	if(isSelected) jQuery('#ddcf_extra_question_category_filter').css("background-color","#ffffff").css("opacity","1");
	else jQuery('#ddcf_extra_question_category_filter').css("background-color","#ececec").css("opacity","0.35");
}

function selectRequestStartDate(isSelected) {
	if(isSelected||jQuery('#ddcf_end_date_check').is(":checked")) {
            jQuery('#ddcf_start_date_time_check').css("background-color","#ffffff").css("opacity","1");
        }
	else {
            jQuery('#ddcf_start_date_time_check').css("background-color","#ececec").css("opacity","0.35");
        }
}

function selectRequestEndDate(isSelected) {
	if(isSelected||jQuery('#ddcf_start_date_check').is(":checked")) {
            jQuery('#ddcf_start_date_time_check').css("background-color","#ffffff").css("opacity","1");
        }
	else {
            jQuery('#ddcf_start_date_time_check').css("background-color","#ececec") .css("opacity","0.35");
        }
}

function selectRequestPartySizeFilter(isSelected) {
	if(isSelected) jQuery('#ddcf_party_size_category_filter').css("background-color","#ffffff").css("opacity","1");
	else jQuery('#ddcf_party_size_category_filter').css("background-color","#ececec").css("opacity","0.35");
}

function selectRequestDatesFilter(isSelected) {
	if(isSelected) jQuery('#ddcf_dates_category_filter').css("background-color","#ffffff").css("opacity","1");
	else jQuery('#ddcf_dates_category_filter').css("background-color","#ececec").css("opacity","0.35");
}

function selectSaveUserDetails(isSelected) {
	if(isSelected) {
            jQuery('#ddcf_rec_updates_option_check').css("background-color","#ffffff").css("opacity","1");
            jQuery('#ddcf_rec_updates_message_check').css("background-color","#ffffff").css("opacity","1");
            jQuery('#ddcf_rec_updates_message').css("background-color","#ffffff").css("opacity","1");
            jQuery('#ddcf_geo_ip_option_check').css("background-color","#ffffff").css("opacity","1");
            jQuery('#ddcf_geoloc_key').css("background-color","#ffffff").css("opacity","1");
        }
	else {
            jQuery('#ddcf_rec_updates_option_check').css("background-color","#ececec").css("opacity","0.35");
            jQuery('#ddcf_rec_updates_message_check').css("background-color","#ececec").css("opacity","0.35");
            jQuery('#ddcf_rec_updates_message').css("background-color","#ececec").css("opacity","0.35");
            jQuery('#ddcf_geo_ip_option_check').css("background-color","#ececec").css("opacity","0.35");
            jQuery('#ddcf_geoloc_key').css("background-color","#ececec").css("opacity","0.35");
        } 
}

function selectRegisterUsers(isSelected) {
    if (isSelected) {
        if (jQuery('#ddcf_rec_updates_message_check').is(":checked"))
            jQuery('#ddcf_rec_updates_message').css("background-color", "#ffffff").css("opacity", "1");
        jQuery('#ddcf_rec_updates_message_check').css("background-color", "#ffffff").css("opacity", "1");
        return;
    } else {
        jQuery('#ddcf_rec_updates_message').css("background-color", "#ececec").css("opacity", "0.35");
        jQuery('#ddcf_rec_updates_message_check').css("background-color", "#ececec").css("opacity", "0.35");
    }
}

function selectRegisterUsersMessage(isSelected) {
	if(jQuery('#ddcf_rec_updates_option_check').is(":checked")) {
                if(isSelected) jQuery('#ddcf_rec_updates_message').css("background-color","#ffffff").css("opacity","1");
                else           jQuery('#ddcf_rec_updates_message').css("background-color","#ececec").css("opacity","0.35");
        } else {
                jQuery('#ddcf_rec_updates_message').css("background-color","#ececec").css("opacity","0.35");
                jQuery('#ddcf_rec_updates_message_check').css("background-color","#ececec").css("opacity","0.35");
        }
}



jQuery(document).ready(function ($) {

	// enable accordions
	jQuery( "#accordion1" ).accordion({
                                    collapsible: true,
                                    active : false
                                    });
	jQuery( "#accordion2" ).accordion({
                                    collapsible: true,
                                    active : false
                                    });
                                                                
	jQuery( "#accordion3" ).accordion({
                                    collapsible: true,
                                    active : false
                                    });                                                                
	// enable tabs
	jQuery( "#tabs" ).tabs();
        
        // have to hide the ajax related inputs otherwise WP won't submit the settings form properly
        ddcfSneakyAjax = jQuery('#ddcf_sneaky_ajax').html();
        jQuery('#ddcf_sneaky_ajax').html('');
  
        
        /* send current custom css to db before saving settings page */
        jQuery('#ddcf_options_form').submit(function( event ) {
                            jQuery('#ddcf_options_feedback_text').html('<p>Saving Changes...</p>');
                            jQuery('#ddcf_options_feedback_text').css('display', 'block');
                            jQuery('#ddcf_options_feedback').css('display', 'block');
                            jQuery('#ddcf_custom_css').css("background-color", ddcfGreyOutColor).css("opacity","0.35");
                            jQuery('#ddcf_update_css_btn').prop('disabled', true);
                            jQuery('#ddcf_sneaky_ajax').html(ddcfSneakyAjax); /* pop the ajax related inputs back into the DOM just for a moment */
                            jQuery.post(ddcf_ajax_script.ajaxurl, jQuery("#ddcf_options_form").serializeArray());
                            jQuery('#ddcf_sneaky_ajax').html('');
          });          
        
	// update css button
	jQuery('#ddcf_update_css_btn')
                .click(function( event ) {
                            jQuery('#ddcf_sneaky_ajax').html(ddcfSneakyAjax); /* pop the ajax related inputs back into the DOM at the last moment */
                            jQuery('#ddcf_options_feedback_text').html('<p>Updating...</p>');
                            jQuery('#ddcf_options_feedback_text').css('display', 'block');
                            jQuery('#ddcf_options_feedback').css('display', 'block');
                            jQuery('#ddcf_custom_css').css("background-color", ddcfGreyOutColor).css("opacity","0.35");
                            jQuery('#ddcf_update_css_btn').prop('disabled', true);
                            event.preventDefault();
                            submitCSS();
                    });    

	// keep extra question text boxes updated
	selectTextareaOne(jQuery('#ddcf_extra_question_one_check').is(":checked")); // first run through
	jQuery('#ddcf_extra_question_one_check').change(function() {
		selectTextareaOne(jQuery(this).is(":checked"));
	});
	selectTextareaTwo(jQuery('#ddcf_extra_question_two_check').is(":checked")); // first run through
	jQuery('#ddcf_extra_question_two_check').change(function() {
		selectTextareaTwo(jQuery(this).is(":checked"));
	});

	/* keep form updated with state of extra question filter check selction */
	selectExtraQuestionFilter(jQuery('#ddcf_extra_question_category_filter_check').is(":checked")); // first run through
	jQuery('#ddcf_extra_question_category_filter_check').change(function() {
		selectExtraQuestionFilter(jQuery(this).is(":checked"));
	});
        
        /* keep form updated with state of dates filter check selction */
	selectRequestDatesFilter(jQuery('#ddcf_dates_category_filter_check').is(":checked")); // first run through
	jQuery('#ddcf_dates_category_filter_check').change(function() {
		selectRequestDatesFilter(jQuery(this).is(":checked"));
	});
        
        /* keep form updated with state of party size filter check selction */
	selectRequestPartySizeFilter(jQuery('#ddcf_party_size_category_filter_check').is(":checked")); // first run through
	jQuery('#ddcf_party_size_category_filter_check').change(function() {
		selectRequestPartySizeFilter(jQuery('#ddcf_party_size_category_filter_check').is(":checked"));
	});        

	/* grey out start and end time checkboxes when date not selected */
	selectRequestStartDate(jQuery('#ddcf_start_date_check').is(":checked")); // first run through
	jQuery('#ddcf_start_date_check').change(function() {
		selectRequestStartDate(jQuery(this).is(":checked"));
	});
	selectRequestEndDate(jQuery('#ddcf_end_date_check').is(":checked")); // first run through
	jQuery('#ddcf_end_date_check').change(function() {
		selectRequestEndDate(jQuery(this).is(":checked"));
	});

	/* grey out customer message stuff when not offering to register Contact Form users */
	selectRegisterUsers(jQuery('#ddcf_rec_updates_option_check').is(":checked")); // first run through
	jQuery('#ddcf_rec_updates_option_check').change(function() {
		selectRegisterUsers(jQuery(this).is(":checked"));
        });     
	selectRegisterUsersMessage(jQuery('#ddcf_rec_updates_message_check').is(":checked")); // first run through
	jQuery('#ddcf_rec_updates_message_check').change(function() {
		selectRegisterUsersMessage(jQuery(this).is(":checked"));
	});
        /* grey out privacy settings if not saving user details to DB */
        selectSaveUserDetails(jQuery('#ddcf_keep_records_check').is(":checked")); // first run through
	jQuery('#ddcf_keep_records_check').change(function() {
		selectSaveUserDetails(jQuery(this).is(":checked"));
	});
        
        /* grey out custom css if not selected */
        selectCustomCSS(jQuery('#ddcf_custom_css_check').is(":checked")); // first run through
	jQuery('#ddcf_custom_css_check').change(function() {
                var custom_css_enabled = jQuery(this).is(":checked");
                if(custom_css_enabled) 
                {
                    jQuery('#ddcf_custom_css').css("background-color", ddcfGreyOutColor).css("opacity","0.35");
                    jQuery('#ddcf_options_feedback_text').css('display', 'block');
                    jQuery('#ddcf_options_feedback_text').html('<p>The CSS cannot be updated until you have saved your changes.<br /><br />Please click &#39;Save Changes&#39; to continue.</p>');
                }
		else selectCustomCSS(custom_css_enabled);
	});
});