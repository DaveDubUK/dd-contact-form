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
var ddcfGreyOutOpacity = "0.35";

/* General Settings: Security */
// highlight google recaptcha boxes only when recaptacha is the selected
function ddcfSelectCaptchaType(captchaType) {

    // captcha type field formatting
    switch (captchaType) {

        case 0:
            jQuery('#ddcf_recaptcha_private_key').css("background-color", "#ffffff").css("opacity", "1");
            jQuery('#ddcf_recaptcha_public_key').css("background-color", "#ffffff").css("opacity", "1");
            jQuery('#ddcf_recaptcha_theme').css("background-color", "#ffffff").css("opacity", "1");
            return;

        default:
            jQuery('#ddcf_recaptcha_private_key').css("background-color", ddcfGreyOutColor).css("opacity", ddcfGreyOutOpacity);
            jQuery('#ddcf_recaptcha_public_key').css("background-color", ddcfGreyOutColor).css("opacity", ddcfGreyOutOpacity);
            jQuery('#ddcf_recaptcha_theme').css("background-color", ddcfGreyOutColor).css("opacity", ddcfGreyOutOpacity);
            return;
    }
}

/* General Settings: Privacy */
// 'Save details' checkbox handling
function ddcfSelectSaveUserDetails() {

    if (jQuery('#ddcf_save_user_details_check').is(":checked"))
        jQuery('#ddcf_offer_to_register_check').css("background-color", "#ffffff").css("opacity", "1");
    else
        jQuery('#ddcf_offer_to_register_check').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);

    ddcfSelectRegisterUsers();
    ddcfSaveGeolocation();
}
// 'Offer to register contact form users ' checkbox handling
function ddcfSelectRegisterUsers() {

    if (jQuery('#ddcf_save_user_details_check').is(":checked")) {
        jQuery('#ddcf_offer_to_register_check').css("background-color", "#ffffff").css("opacity", "1");
    } else {
        jQuery('#ddcf_offer_to_register_check').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
    }
    ddcfSelectRegisterUsersMessage();
}
//
function ddcfSelectRegisterUsersMessage() {

    if (jQuery('#ddcf_save_user_details_check').is(":checked")) {

        if (jQuery('#ddcf_offer_to_register_check').is(":checked")) {
            jQuery('#ddcf_offer_to_register_custom_message_check').css("background-color", "#ffffff").css("opacity", "1");

            if (jQuery('#ddcf_offer_to_register_custom_message_check').is(":checked"))
                jQuery('#ddcf_offer_to_register_custom_message').css("background-color", "#ffffff").css("opacity", "1");
            else
                jQuery('#ddcf_offer_to_register_custom_message').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
        } else {
            jQuery('#ddcf_offer_to_register_custom_message_check').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
            jQuery('#ddcf_offer_to_register_custom_message').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
        }
    } else {
        jQuery('#ddcf_offer_to_register_custom_message_check').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
        jQuery('#ddcf_offer_to_register_custom_message').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
    }
}

function ddcfSaveGeolocation() {

    if (jQuery('#ddcf_save_user_details_check').is(":checked")) {
        jQuery('#ddcf_geolocation_check').css("background-color", "#ffffff").css("opacity", "1");

        if (jQuery('#ddcf_geolocation_check').is(':checked'))
            jQuery('#ddcf_geolocation_key').css("background-color", "#ffffff").css("opacity", "1");
        else
            jQuery('#ddcf_geolocation_key').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
    } else {
        jQuery('#ddcf_geolocation_check').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
        jQuery('#ddcf_geolocation_key').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
    }
}

/* General Settings: Email */
function ddcfSelectSendEmailConfimation(isSelected) {

    if (isSelected)
        jQuery('#ddcf_email_confirmation_text').css("background-color", "#ffffff").css("opacity", "1");
    else
        jQuery('#ddcf_email_confirmation_text').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
}

function ddcfIncludeHeaderImage(isSelected) {

    if (isSelected)
        jQuery('#ddcf_email_header_image_url').css("background-color", "#ffffff").css("opacity", "1");
    else
        jQuery('#ddcf_email_header_image_url').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
}

function ddcfIncludeFooterText(isSelected) {

    if (isSelected)
        jQuery('#ddcf_email_footer_text').css("background-color", "#ffffff").css("opacity", "1");
    else
        jQuery('#ddcf_email_footer_text').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
}

/* User Interface: User Feedback */
function ddcfThankYouType(thankYouType) {

    switch (thankYouType) {

        case 8:
            jQuery('#ddcf_thankyou_url').css("background-color", "#ffffff").css("opacity", "1");
            jQuery('#ddcf_thankyou_message').css("background-color", ddcfGreyOutColor).css("opacity", ddcfGreyOutOpacity);
            return;

        case 3:
            jQuery('#ddcf_thankyou_url').css("background-color", ddcfGreyOutColor).css("opacity", ddcfGreyOutOpacity);
            jQuery('#ddcf_thankyou_message').css("background-color", "#ffffff").css("opacity", "1");
            return;
    }
}

/* Extra Information: Dates and Times */
function ddcfSelectRequestStartDate() {
    ddcdSelectRequestTimes();
    ddcfSelectDatesRequired();
    ddcfSelectDatesFilter();
}

function ddcfSelectRequestEndDate() {
    ddcdSelectRequestTimes();
    ddcfSelectDatesRequired();
    ddcfSelectDatesFilter();
}

function ddcdSelectRequestTimes() {
    
    if (jQuery('#ddcf_start_date_check').is(":checked") || jQuery('#ddcf_end_date_check').is(":checked"))
        jQuery('#ddcf_time_with_date_check').css("background-color", "#ffffff").css("opacity", "1");
    else
        jQuery('#ddcf_time_with_date_check').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
}

function ddcfSelectDatesRequired() {

    if (jQuery('#ddcf_start_date_check').is(":checked") || jQuery('#ddcf_end_date_check').is(":checked"))
        jQuery('#ddcf_dates_compulsory_check').css("background-color", "#ffffff").css("opacity", "1");
    else
        jQuery('#ddcf_dates_compulsory_check').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);

}

function ddcfSelectDatesFilter() {

    if ((jQuery('#ddcf_start_date_check').is(":checked") || jQuery('#ddcf_end_date_check').is(":checked"))) {
        jQuery('#ddcf_dates_category_filter_check').css("background-color", "#ffffff").css("opacity", "1");

        if (jQuery('#ddcf_dates_category_filter_check').is(":checked"))
            jQuery('#ddcf_dates_category_filter').css("background-color", "#ffffff").css("opacity", "1");
        else
            jQuery('#ddcf_dates_category_filter').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
    } else {
        jQuery('#ddcf_dates_category_filter_check').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
        jQuery('#ddcf_dates_category_filter').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
    }
}

/* Extra Information: Numbers dropdowns (e.g. Party Size) */
function ddcfSelectDropdownOneLabel() {

	if (jQuery('#ddcf_extra_dropdown_one_check').is(":checked"))
		jQuery('#ddcf_extra_dropdown_one_label').css("background-color", "#ffffff").css("opacity", "1");
	else
		jQuery('#ddcf_extra_dropdown_one_label').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
    ddcfSelectNumbersFilter();
	ddcfNumbersCompulsory();
}

function ddcfSelectDropdownTwoLabel() {

	if (jQuery('#ddcf_extra_dropdown_two_check').is(":checked"))
		jQuery('#ddcf_extra_dropdown_two_label').css("background-color", "#ffffff").css("opacity", "1");
	else
		jQuery('#ddcf_extra_dropdown_two_label').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
	ddcfSelectNumbersFilter();
	ddcfNumbersCompulsory();
}

function ddcfNumbersCompulsory() {

	if (jQuery('#ddcf_extra_dropdown_one_check').is(":checked") || jQuery('#ddcf_extra_dropdown_two_check').is(":checked"))
		jQuery('#ddcf_numbers_compulsory_check').css("background-color", "#ffffff").css("opacity", "1");
	else
		jQuery('#ddcf_numbers_compulsory_check').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
}

function ddcfSelectNumbersFilter() {

	if (jQuery('#ddcf_dropdown_category_filter_check').is(":checked")) {

		if (jQuery('#ddcf_extra_dropdown_one_check').is(":checked") || jQuery('#ddcf_extra_dropdown_two_check').is(":checked")) {
			jQuery('#ddcf_numbers_category_filter').css("background-color", "#ffffff").css("opacity", "1");
			jQuery('#ddcf_dropdown_category_filter_check').css("background-color", "#ffffff").css("opacity", "1");
		} else {
			jQuery('#ddcf_numbers_category_filter').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
			jQuery('#ddcf_dropdown_category_filter_check').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
		}
	} else {

		if (jQuery('#ddcf_extra_dropdown_one_check').is(":checked") || jQuery('#ddcf_extra_dropdown_two_check').is(":checked")) {
			jQuery('#ddcf_dropdown_category_filter_check').css("background-color", "#ffffff").css("opacity", "1");
		} else {
			jQuery('#ddcf_dropdown_category_filter_check').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
		}
		jQuery('#ddcf_numbers_category_filter').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
	}
}

/* Extra Information: Additional Questions */
function ddcfSelectTextareaOne() {

	if (jQuery('#ddcf_extra_question_one_check').is(":checked"))
		jQuery('#ddcf_extra_question_one').css("background-color", "#ffffff").css("opacity", "1");
    else
		jQuery('#ddcf_extra_question_one').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
	ddcfQuestionsCompulsory();
	ddcfSelectAdditionalQuestionsFilter();
}

function ddcfSelectTextareaTwo() {

	if (jQuery('#ddcf_extra_question_two_check').is(":checked"))
		jQuery('#ddcf_extra_question_two').css("background-color", "#ffffff").css("opacity", "1");
    else
		jQuery('#ddcf_extra_question_two').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
	ddcfQuestionsCompulsory();
	ddcfSelectAdditionalQuestionsFilter();
}

function ddcfQuestionsCompulsory() {

	if (jQuery('#ddcf_extra_question_one_check').is(":checked") || jQuery('#ddcf_extra_question_two_check').is(":checked"))
		jQuery('#ddcf_questions_compulsory_check').css("background-color", "#ffffff").css("opacity", "1");
	else
		jQuery('#ddcf_questions_compulsory_check').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
}

function ddcfSelectAdditionalQuestionsFilter() {

	if (jQuery('#ddcf_extra_questions_category_filter_check').is(":checked")) {

		if (jQuery('#ddcf_extra_question_one_check').is(":checked") || jQuery('#ddcf_extra_question_two_check').is(":checked")) {
			jQuery('#ddcf_extra_questions_category_filter').css("background-color", "#ffffff").css("opacity", "1");
			jQuery('#ddcf_extra_questions_category_filter_check').css("background-color", "#ffffff").css("opacity", "1");
		} else {
			jQuery('#ddcf_extra_questions_category_filter').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
			jQuery('#ddcf_extra_questions_category_filter_check').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
		}
	} else {

		if (jQuery('#ddcf_extra_question_one_check').is(":checked") || jQuery('#ddcf_extra_question_two_check').is(":checked")) {
			jQuery('#ddcf_extra_questions_category_filter_check').css("background-color", "#ffffff").css("opacity", "1");
		} else {
			jQuery('#ddcf_extra_questions_category_filter_check').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
		}
		jQuery('#ddcf_extra_questions_category_filter').css("background-color", "#ececec").css("opacity", ddcfGreyOutOpacity);
	}
}

/* custom css enabled check formatting */
function ddcfSelectCustomCSS(isSelected) {
    if (isSelected) {
        jQuery('#ddcf_custom_css').css("background-color", "#ffffff").css("opacity", "1");
        jQuery('#ddcf_update_css_btn');
    } else {
        jQuery('#ddcf_custom_css').css("background-color", ddcfGreyOutColor).css("opacity", ddcfGreyOutOpacity);
        jQuery('#ddcf_update_css_btn');
    }
}

jQuery(document).ready(function($) {

    // enable accordions
    jQuery("#accordion1").accordion({
        collapsible: true,
        active: false
    });
    jQuery("#accordion2").accordion({
        collapsible: true,
        active: false
    });

    jQuery("#accordion3").accordion({
        collapsible: true,
        active: false
    });
    // enable tabs
    jQuery("#tabs").tabs();

    // have to hide the ajax related inputs otherwise WP won't submit the settings form properly
    ddcfSneakyAjax = jQuery('#ddcf_sneaky_ajax').html();
    jQuery('#ddcf_sneaky_ajax').html('');

    /* General Settings: Security */
    ddcfSelectCaptchaType(jQuery('#ddcf_captcha_type option:selected').index());
    jQuery('#ddcf_captcha_type').change(function() {
        ddcfSelectCaptchaType(jQuery('#ddcf_captcha_type option:selected').index());
    });

    /* General Settings: Privacy */
    ddcfSelectSaveUserDetails();
    jQuery('#ddcf_save_user_details_check').change(function() {
        ddcfSelectSaveUserDetails();
    });

    ddcfSelectRegisterUsers(jQuery('#ddcf_offer_to_register_check').is(":checked"));
    jQuery('#ddcf_offer_to_register_check').change(function() {
        ddcfSelectRegisterUsers(jQuery(this).is(":checked"));
    });

    ddcfSelectRegisterUsersMessage();
    jQuery('#ddcf_offer_to_register_custom_message_check').change(function() {
        ddcfSelectRegisterUsersMessage();
    });

    ddcfSaveGeolocation(jQuery('#ddcf_geolocation_check').is(":checked"));
    jQuery('#ddcf_geolocation_check').change(function() {
        ddcfSaveGeolocation(jQuery('#ddcf_geolocation_check').is(":checked"));
    });

    /* General Settings: Email */
    ddcfSelectSendEmailConfimation(jQuery('#ddcf_email_confirmation_check').is(":checked"));
    jQuery('#ddcf_email_confirmation_check').change(function() {
        ddcfSelectSendEmailConfimation(jQuery(this).is(":checked"));
    });

    ddcfIncludeHeaderImage(jQuery('#ddcf_email_header_check').is(":checked"));
    jQuery('#ddcf_email_header_check').change(function() {
        ddcfIncludeHeaderImage(jQuery(this).is(":checked"));
    });

    ddcfIncludeFooterText(jQuery('#ddcf_email_footer_check').is(":checked"));
    jQuery('#ddcf_email_footer_check').change(function() {
        ddcfIncludeFooterText(jQuery(this).is(":checked"));
    });

    /* User Interface: User Feedback */
    ddcfThankYouType(jQuery("input[name='ddcf_thankyou_type']:radio:checked").index());
    jQuery("input[name=ddcf_thankyou_type]:radio").change(function() {
        ddcfThankYouType(jQuery("input[name='ddcf_thankyou_type']:radio:checked").index());
    });

    /* Extra Information: Dates & Times */
    ddcfSelectRequestStartDate();
    jQuery('#ddcf_start_date_check').change(function() {
        ddcfSelectRequestStartDate();
    });

    ddcfSelectRequestEndDate();
    jQuery('#ddcf_end_date_check').change(function() {
        ddcfSelectRequestEndDate();
    });

    ddcfSelectDatesRequired();
    jQuery('#ddcf_dates_compulsory_check').change(function() {
        ddcfSelectDatesRequired();
    });

    ddcfSelectDatesFilter();
    jQuery('#ddcf_dates_category_filter_check').change(function() {
        ddcfSelectDatesFilter();
    });

    /* Extra Information: Numbers dropdowns (e.g. Party Size) */
    ddcfSelectDropdownOneLabel();
    jQuery('#ddcf_extra_dropdown_one_check').change(function() {
        ddcfSelectDropdownOneLabel();
    });

    ddcfSelectDropdownTwoLabel();
    jQuery('#ddcf_extra_dropdown_two_check').change(function() {
        ddcfSelectDropdownTwoLabel();
    });

    ddcfSelectNumbersFilter();
    jQuery('#ddcf_dropdown_category_filter_check').change(function() {
        ddcfSelectNumbersFilter();
    });

	/* Extra Information: Additional Questions */
    ddcfSelectTextareaOne();
    jQuery('#ddcf_extra_question_one_check').change(function() {
        ddcfSelectTextareaOne();
    });

    ddcfSelectTextareaTwo();
    jQuery('#ddcf_extra_question_two_check').change(function() {
        ddcfSelectTextareaTwo();
    });

    ddcfSelectAdditionalQuestionsFilter();
    jQuery('#ddcf_extra_questions_category_filter_check').change(function() {
        ddcfSelectAdditionalQuestionsFilter();
    });

    /* send current custom css to db before saving settings page */
    jQuery('#ddcf_options_form').submit(function(event) {
        jQuery('#ddcf_options_feedback_text').html('<p>Saving Changes...</p>');
        jQuery('#ddcf_options_feedback_text').css('display', 'block');
        jQuery('#ddcf_options_feedback').css('display', 'block');
        jQuery('#ddcf_custom_css').css("background-color", ddcfGreyOutColor).css("opacity", ddcfGreyOutOpacity);
        jQuery('#ddcf_update_css_btn');
        jQuery('#ddcf_sneaky_ajax').html(ddcfSneakyAjax); /* pop the ajax related inputs back into the DOM just for a moment */
        jQuery.post(ddcf_ajax_script.ajaxurl, jQuery("#ddcf_options_form").serializeArray());
        jQuery('#ddcf_sneaky_ajax').html('');
    });

    // update css button
    jQuery('#ddcf_update_css_btn').click(function(event) {
        jQuery('#ddcf_sneaky_ajax').html(ddcfSneakyAjax); /* pop the ajax related inputs back into the DOM at the last moment */
        jQuery('#ddcf_options_feedback_text').html('<p>Updating...</p>');
        jQuery('#ddcf_options_feedback_text').css('display', 'block');
        jQuery('#ddcf_options_feedback').css('display', 'block');
        jQuery('#ddcf_custom_css').css("background-color", ddcfGreyOutColor).css("opacity", ddcfGreyOutOpacity);
        jQuery('#ddcf_update_css_btn');
        event.preventDefault();
        ddcfSubmitCSS();
    });

    /* grey out custom css if not selected */
    ddcfSelectCustomCSS(jQuery('#ddcf_custom_css_check').is(":checked"));
    jQuery('#ddcf_custom_css_check').change(function() {
        var custom_css_checked = jQuery(this).is(":checked");
        if (custom_css_checked) {
            jQuery('#ddcf_custom_css').css("background-color", ddcfGreyOutColor).css("opacity", ddcfGreyOutOpacity);
            jQuery('#ddcf_options_feedback_text').css('display', 'block');
            jQuery('#ddcf_options_feedback_text').html('<p>The CSS cannot be updated until you have saved your changes.<br /><br />Please click &#39;Save Changes&#39; to continue.</p>');
        } else ddcfSelectCustomCSS(custom_css_checked);
    });
});