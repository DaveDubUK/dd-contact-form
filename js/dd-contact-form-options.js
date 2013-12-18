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

function selectTextareaOne(isSelected) {
	if(isSelected) jQuery('#ddcf_extra_question_one').css("background-color","#ffffff")
										 .css("color","#000000");
										 //.prop('disabled', false);
	else jQuery('#ddcf_extra_question_one').css("background-color","#ececec")
										 .css("color","#cccccc");
										 //.prop('disabled', true);
}

function selectTextareaTwo(isSelected) {
	if(isSelected) jQuery('#ddcf_extra_question_two').css("background-color","#ffffff")
										 .css("color","#000000");
										 //.prop('disabled', false);
	else jQuery('#ddcf_extra_question_two').css("background-color","#ececec")
										 .css("color","#cccccc");
										 //.prop('disabled', true);
}

//function selectExtraDetailsFilter(isSelected) {
//	if(isSelected) jQuery('#ddcf_extra_details_category_filter').css("background-color","#ffffff")
//										 .css("color","#000000");
//	else jQuery('#ddcf_extra_details_category_filter').css("background-color","#ececec")
//										 .css("color","#cccccc");
//}

function selectExtraQuestionFilter(isSelected) {
	if(isSelected) jQuery('#ddcf_extra_question_category_filter').css("background-color","#ffffff")
										 .css("color","#000000");
	else jQuery('#ddcf_extra_question_category_filter').css("background-color","#ececec")
										 .css("color","#cccccc");
}

function selectRequestStartDate(isSelected) {
	if(isSelected) jQuery('#ddcf_start_date_time_check').css("background-color","#ffffff")
										 .css("opacity","1");
	else jQuery('#ddcf_start_date_time_check').css("background-color","#ececec")
										 .css("opacity","0.35");
}

function selectRequestEndDate(isSelected) {
	if(isSelected) jQuery('#ddcf_end_date_time_check').css("background-color","#ffffff")
										 .css("opacity","1");
	else jQuery('#ddcf_end_date_time_check').css("background-color","#ececec")
										 .css("opacity","0.35");
}

function selectRequestPartySizeFilter(isSelected) {
	if(isSelected) jQuery('#ddcf_party_size_category_filter').css("background-color","#ffffff")
										 .css("opacity","1");
	else jQuery('#ddcf_party_size_category_filter').css("background-color","#ececec")
										 .css("opacity","0.35");    
}

function selectRequestDatesFilter(isSelected) {
	if(isSelected) jQuery('#ddcf_dates_category_filter').css("background-color","#ffffff")
										 .css("opacity","1");
	else jQuery('#ddcf_dates_category_filter').css("background-color","#ececec")
										 .css("opacity","0.35");    
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

	// Enable accordian
	//jQuery( "#accordion" ).accordion({ active: 2, collapsible: true});


	//jQuery('#ddcf_captcha_options').css('visibility', 'visible');

	// enable accordions
	jQuery( "#accordion1" ).accordion({
								collapsible: true,
								active : 2
								});
	jQuery( "#accordion2" ).accordion({
								collapsible: true,
								active : 1
								});
                                                                
	jQuery( "#accordion3" ).accordion({
								collapsible: true,
								active : 2
								});                                                                

	// enable tabs
	jQuery( "#tabs" ).tabs();


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

//	/* keep form updated with state of extra details filter check selction */
//	selectExtraDetailsFilter(jQuery('#ddcf_extra_details_category_filter_check').is(":checked")); // first run through
//	jQuery('#ddcf_extra_details_category_filter_check').change(function() {
//		selectExtraDetailsFilter(jQuery(this).is(":checked"));
//	});

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
});