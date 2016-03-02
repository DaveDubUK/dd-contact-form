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

var gLastOpenAccordionHTML = '';
var gGreyOut = '#cccccc';

/* Contact form stuff */
function ddcfDoCaptcha(php_message) {

    if (php_message.ddcf_captcha_type === 'None') {
        /* not advised */
        return;
    }

    else if (php_message.ddcf_captcha_type === 'reCaptcha') {
        // re-initialise google reCaptcha
        if (Recaptcha) Recaptcha.destroy();
		Recaptcha.create(php_message.ddcf_recaptcha_public_key, "ddcf_google_recaptcha", {
			theme: php_message.ddcf_recaptcha_theme,
			callback: Recaptcha.focus_response_field
		});
        return;
    }
    else { /* default to simple additon */
        // reinitialse the simple addition captcha
        jQuery('#ddcf_captcha_one').html(php_message.ddcf_captcha_two_value);
        jQuery('#ddcf_captcha_two').html(php_message.ddcf_captcha_one_value);
        return;
    }
}

function ddcfInitialiseSession() {
	jQuery.post(ddcf_ajax_script.ajaxurl, jQuery("#ddcf_contact_form").serializeArray(),
		function(php_message) {
			//jQuery("#ddcf_error_reporting").html(php_message.ddcf_error); // debug only
			jQuery("#ddcf_session_initialised").val("true");
			ddcfDoCaptcha(php_message);
		}
	);
}

function ddcfSubmit() {
    jQuery.post(ddcf_ajax_script.ajaxurl, jQuery("#ddcf_contact_form").serializeArray(),
		function(php_message) {
			jQuery("#ddcf_error_reporting").html('');
			jQuery("#ddcf_throbber").css("display", "none");
			jQuery("#ddcf_contact_form_contents").fadeIn(800);

			if (php_message.ddcf_error === 'Success!') {

				if (jQuery("#ddcf_thankyou_type").val() === 'ddcf_thankyou_url') {
					/* redirect to thankyou page */
					var redirect_page = jQuery("#ddcf_thankyou_url").val();
					window.location.replace(redirect_page);
				} else {
					jQuery("#ddcf_contact_captcha_add").val('');//(php_message.ddcf_contact_captcha_add);
					jQuery("#recaptcha_response_field").val('');//.html(php_message.ddcf_contact_captcha_add);

					if (php_message.ddcf_thankyou_message === '')
						php_message.ddcf_thankyou_message = _('Thank you. A representative will be in touch shortly.');

					// hide the form and replace with ddcf_thankyou_message
					success_report = "<div id='ddcf_thankyou_message_container' name='ddcf_thankyou_message_container'>" +
									 php_message.ddcf_thankyou_message +
									 "</div>";
					jQuery("#ddcf_session_initialised").val("uninitialised");
					jQuery("#ddcf_contact_form_contents").html(success_report);
				}
			} else {
				// let the user have another go at the captcha
				jQuery("#ddcf_contact_captcha_add").val('');
				jQuery('#ddcf_contact_captcha_fb').html('&nbsp;');
				jQuery("#recaptcha_response_field").val('');
				jQuery('#recaptcha_response_field_fb').html('&nbsp;');
				jQuery("#ddcf_error_reporting").html(php_message.ddcf_error+'<br />');
				ddcfDoCaptcha(php_message);
			}
		}
	);
}

/* Options page stuff */
function ddcfSubmitCSS() {
	jQuery.post(ddcf_ajax_script.ajaxurl, jQuery("#ddcf_options_form").serializeArray(),
		function(php_message) {
			jQuery('#ddcf_sneaky_ajax').html(''); /* hide the ajax related inputs again */
			if (!php_message.ddcf_error) {
				jQuery('#ddcf_custom_css').val(php_message.ddcf_custom_css)
				jQuery('#ddcf_options_feedback_text').html('<p>CSS Updated</p>');
				window.setTimeout(ddcfFinishSubmitCSS, 700);
			} else {
				jQuery('#ddcf_options_feedback_text').html('<p>Problem updating CSS: ' +
                                                            php_message.ddcf_error +
                                                           '<br/>The CSS was not updated.</p>');
				window.setTimeout(ddcfFinishSubmitCSS, 1500);
			}
		}
	);
}

function ddcfFinishSubmitCSS () {
	jQuery('#ddcf_options_feedback_text').css('display', 'none');
	jQuery('#ddcf_options_feedback').css('display', 'none');
	jQuery('#ddcf_custom_css').css("background-color","#ececec").css("opacity","1.0");
	jQuery('#ddcf_update_css_btn').prop('disabled', false);
}


/* Manager page stuff */

/* add a new contact to the database */
function ddcfAddContact() {
	// add new contact then display only that contact
	jQuery("#ddcf_action").val('ddcf_add_contact_action');
}

/* take a set of results and produce the html output for an accordion */
function ddcfAccordionise(jsonData) {
	data = jQuery.parseJSON(jsonData);
	var accordionHtml = '<div id="accordion">';
	jQuery.each(data, function(i, item) {
		accordionHtml += '<h3 style="font:15px; letter-spacing:normal; margin-bottom:0px; ">'+item.contact_name+' ('+item.contact_id+')</h3>';
		accordionHtml += '<div>';
		accordionHtml += '<strong>Name:</strong> ' +item.contact_name+'<br /><br />';
		accordionHtml += '<strong>ID:</strong> ' +item.contact_id+'<br /><br />';
		accordionHtml += '<strong>First Registered:</strong> ' +item.first_registered+'<br /><br />';
		accordionHtml += '<strong>Contact type:</strong> ' +item.contact_type+'<br /><br />';
		if (item.wordpress_id > 0) accordionHtml += '<strong>Wordpress ID:</strong>F ' +item.wordpress_id+'<br /><br />';
		accordionHtml += '<div id="ddcf_entry_throbber"></div>';
		accordionHtml += '</div>';
	});
	accordionHtml += '</div>';
	return accordionHtml;
}

function ddcfGetUserInfo(ui) {
	var finalOutput = jQuery('.ui-accordion-content-active').html();
	jQuery('.ui-accordion-content-active').html('<div id="ddcf_image_container"><img class="ddcf_img" /></div>');
	jQuery('#accordion').accordion("refresh");
	jQuery("#ddcf_action").val('ddcf_dox_user');
	jQuery("#ddcf_action_arg").val(ui.newHeader.text());
	jQuery.post(ddcf_ajax_script.ajaxurl, jQuery("#ddcf_management_form").serializeArray(),
		function(php_message) {
			jQuery("#ddcf_action").val('');
			jQuery("#ddcf_action_arg").val('');
			jQuery('#ddcf_manager_nonce').val(php_message.ddcf_manager_nonce);

			var data = jQuery.parseJSON(php_message.ddcf_contact_details);

			if (data) {
				finalOutput	+= '<strong>Contact Details:</strong><br /><br />';
				jQuery.each(data, function(i, item) {
					finalOutput	+= item.info_type + ': ' +
								   item.contact_info + ' (' +
								   item.special_instructions +
								   ')<br /><br />';
					}
				);
			}
			data = jQuery.parseJSON(php_message.ddcf_contact_enquiries);

			if (data) {
				finalOutput	+= '<strong>Email Enquiries:</strong><br /><br />';
				jQuery.each(data, function(i, item) {
					finalOutput	+= 'Date: ' + item.enquiry_date + '<br /><br />Name: ' +
								   item.customer_name +
                                   '<br />Email: ' + item.email_address +
                                   '<br />Subject: ' + item.email_subject +
                                   '<br />Arrival Date: ' + item.start_date +
                                   '<br />Departure Date: ' + item.end_date +
                                   '<br />Post title: ' + item.post_title +
                                   '<br />Dropdown one: ' + item.num_adults +
                                   '<br />Dropdown two: ' + item.num_children +
                                   '<br />Question 1: ' + item.question_one +
                                   '<br />Question 2: ' + item.question_two +
                                   '<br />Receive Updates: ' + item.receive_updates +
                                   '<br />IP address: ' + item.ip_address  +
                                   '<br />Country: ' + item.country  +
                                   '<br />Region: ' + item.region  +
                                   '<br />City: ' + item.city  +
                                   '<br />Enquiry ID: ' + item.enquiry_id +
                                   '<br />Message: ' + item.email_message +
                                   '<br /><br />';
                    }
                );
			}
			jQuery('#ddcf_entry_throbber').html('');
			jQuery('.ui-accordion-content-active').html(finalOutput);
			jQuery('#accordion').accordion("refresh");
		}
	);
}

// helpers for results navigtion links
function ddcfShowNext(show) {

	if (show) jQuery('#ddcf_next_page').css('color', 'inherit')
								       .css('cursor', 'pointer')
		                               .css('text-decoration', 'none');
	else jQuery('#ddcf_next_page').css('color', gGreyOut)
								  .css('cursor', 'default')
		                          .css('text-decoration', 'none');
}
function ddcfShowPrevious(show) {

	if (show) jQuery('#ddcf_previous_page').css('color', 'inherit')
									       .css('cursor', 'pointer')
		                                   .css('text-decoration', 'none');
	else jQuery('#ddcf_previous_page').css('color', gGreyOut)
									  .css('cursor', 'default')
		                              .css('text-decoration', 'none');
}

function ddcfShowUser(str) {
	jQuery('#ddcf_contact_information').html('<div id="ddcf_image_container"></div>');
	jQuery("#ddcf_action_arg").val(str);
	jQuery('#ddcf_page_info').html('querying database...');
	jQuery.post(ddcf_ajax_script.ajaxurl, jQuery("#ddcf_management_form").serializeArray(),
		function(php_message) {

			// results navigation logic
			var nResults = parseInt(php_message.ddcf_num_results);
			var nOffset = parseInt(jQuery('#ddcf_results_offset').val());
			var nShowing = gResultsPerPage;

			jQuery("#ddcf_action").val('');
			jQuery("#ddcf_action_arg").val('');
			jQuery('#ddcf_manager_nonce').val(php_message.ddcf_manager_nonce);
				jQuery('#ddcf_total_num_results').val(nResults);

			if (nResults <= gResultsPerPage) {
				nShowing = nResults;
				ddcfShowNext(false);
				ddcfShowPrevious(false);
			}

			if (nResults > gResultsPerPage) {
				nShowing = nOffset + gResultsPerPage;

                if (nShowing > nResults) nShowing = nResults;

                if (nOffset > 0) ddcfShowPrevious(true);
				else ddcfShowPrevious(false);

                if ((nOffset+gResultsPerPage) < nResults) ddcfShowNext(true);
				else ddcfShowNext(false);
			}

			if (nResults > 0) nOffset++;//zero based in code, one's based from user's perspective so adjust for display
			jQuery('#ddcf_page_info').html('Showing results '+(nOffset).toString()+' to '+(nShowing).toString()+' of '+(nResults).toString()+' results');

			if (php_message.ddcf_contact_information === 'manager session initialised') {
				// whoops - user page refresh before expected search term submission - re-initiailise
				jQuery('#ddcf_contact_information').html('Please enter a search term or select a filter');
				jQuery('#ddcf_page_info').html('refreshed - lost search terms');
			} else {
				jQuery('#ddcf_contact_information').html(ddcfAccordionise(php_message.ddcf_contact_information));
				jQuery("#accordion").accordion({ collapsible: true, active: false });
				jQuery("#accordion").accordion({activate: function(event, ui) {

					if (ui.oldPanel.prevObject.attr('id')) ui.oldPanel.first().html(gLastOpenAccordionHTML);
					gLastOpenAccordionHTML = ui.newPanel.first().html();

					if (ui.newHeader.length > 0) ddcfGetUserInfo(ui);
				}});
			}
		}
	);
}

function ddcfInitialiseManagerSession() {
	jQuery.post(ddcf_ajax_script.ajaxurl, jQuery("#ddcf_management_form").serializeArray(),
		function(php_message) {
			jQuery('#ddcf_action').val('ddcf_filter_action');
			ddcfSearch('all contact types');
		}
	);
}