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

var gLastOpenAccordionHTML = '';
var gGreyOut = '#cccccc';
var gResultsPerPage = 25;

/* Contact form stuff */
function do_captcha(php_message) {
    
    if(php_message.ddcf_captcha_type==='None') {
        /* not advised */
        return;
    }
    
    else if(php_message.ddcf_captcha_type==='reCaptcha') {
        // re-initialise google reCaptcha
        if(Recaptcha) Recaptcha.destroy();
        Recaptcha.create(php_message.ddcf_recaptcha_public_key, "ddcf_google_recaptcha",
        {
                theme: php_message.ddcf_recaptcha_theme,
                callback: Recaptcha.focus_response_field
        });
        return;
    }
    else { /* default to simple additon */
        // reinitialse the simple add captcha        
        jQuery('#ddcf_captcha_one').html(php_message.ddcf_captcha_two_value);
        jQuery('#ddcf_captcha_two').html(php_message.ddcf_captcha_one_value);
        //?jQuery("#ddcf_contact_captcha_add").html(php_message.ddcf_contact_captcha_add);?
        return;
    }
}


function initialise_session(){
	jQuery.post(the_ajax_script.ajaxurl, jQuery("#ddcf_contact_form").serializeArray()
				,
				function(php_message){
					jQuery(this).css("display", "none");
					jQuery('#screen').css("display", "none");
                                        jQuery("#error_reporting").html(php_message.ddcf_error);

					jQuery("#ddcf_session_initialised").val("true");
					jQuery("#ddcf_contact_form_contents").css('visibility','visible');
                                                                               
                                        do_captcha(php_message);
    				});
}

function submit(){
	jQuery.post(the_ajax_script.ajaxurl, jQuery("#ddcf_contact_form").serializeArray()
				,
				function(php_message){

					jQuery("#error_reporting").html('');
                                        jQuery("#ddcf_contact_form_contents").fadeIn( "slow");

					if(php_message.ddcf_error==='Success!') {

						if(jQuery("#ddcf_thankyou_type").val()==='ddcf_thankyou_url') {
                                                    /* redirect to thankyou page */
                                                    var redirect_page = jQuery("#ddcf_thankyou_url").val();
                                                    window.location.replace(redirect_page);
                                                }
                                                else {
                                                    jQuery("#ddcf_contact_captcha_add").val('');//(php_message.ddcf_contact_captcha_add);
                                                    jQuery("#recaptcha_response_field").val('');//.html(php_message.ddcf_contact_captcha_add);

                                                    if(php_message.ddcf_thankyou_message==='')
                                                            php_message.ddcf_thankyou_message = _('Thank you. A representative will be in touch shortly.');

                                                    // hide the form and replace with ddcf_thankyou_message
                                                    success_report = "<div style='width:100%;text-align:center;min-height:150px;font-size:1.1em;'>\n\
                                                                        \n\<P>&nbsp;&nbsp;&nbsp;&nbsp;"
                                                                        +php_message.ddcf_thankyou_message
                                                                    +"</div>";
                                                    jQuery("#ddcf_session_initialised").val("uninitialised");
                                                    jQuery("#ddcf_contact_form").html(success_report);
                                                }
					} else {
						// have another go then...
                                                jQuery("#error_reporting").html(php_message.ddcf_error);
						do_captcha(php_message);
					}
				});
}









/* Manager page stuff */

/* add a new contact to the database */
function addContact() {
	// add new contact then display only that contact
	jQuery("#ddcf_action").val('ddcf_add_contact_action');

}

/* take a set of results and produce the html output for an accordion */
function accordionise(jsonData) {

		data = jQuery.parseJSON(jsonData);

		var accordionHtml = '<div id="accordion">';

		jQuery.each(data, function(i, item) {

			accordionHtml += '<h3 style="font:15px; letter-spacing:normal; margin-bottom:0px; ">'+item.contact_name+' ('+item.contact_id+')</h3>';
			accordionHtml += '<div>';
			accordionHtml += '<strong>Name:</strong> ' +item.contact_name+'<br /><br />';
			accordionHtml += '<strong>ID:</strong> ' +item.contact_id+'<br /><br />';
			accordionHtml += '<strong>First Registered:</strong> ' +item.first_registered+'<br /><br />';
			accordionHtml += '<strong>Contact type:</strong> ' +item.contact_type+'<br /><br />';
			if(item.wordpress_id>0)
				accordionHtml += '<strong>Wordpress ID:</strong>F ' +item.wordpress_id+'<br /><br />';
			accordionHtml += '<div id="ddcf_entry_throbber"></div>';
			accordionHtml += '</div>';
		});

		accordionHtml += '</div>';
		return accordionHtml;
}


function doxUser(ui) {
	var finalOutput = jQuery('.ui-accordion-content-active').html();
	jQuery('.ui-accordion-content-active').html('<div class="ddcf_image_container"><img class="ddcf_img" /></div>');
	jQuery('#accordion').accordion( "refresh" );
	jQuery("#ddcf_action").val('ddcf_dox_user');
	jQuery("#ddcf_action_arg").val(ui.newHeader.text());
	jQuery.post(the_ajax_script.ajaxurl, jQuery("#ddcf_management_form").serializeArray()
				,
				function(php_message){
					jQuery("#ddcf_action").val('');
					jQuery("#ddcf_action_arg").val('');
					jQuery('#ddcf_manager_nonce').val(php_message.ddcf_manager_nonce);

					var data = jQuery.parseJSON(php_message.ddcf_contact_details);
					if(data) {
						finalOutput	+= '<strong>Contact Details:</strong><br /><br />';
						jQuery.each(data, function(i, item) {
							finalOutput	+=item.info_type + ': '
										+ item.contact_info + ' ('
										+ item.special_instructions + ')<br /><br />';
						});
					}

//					data = jQuery.parseJSON(php_message.ddcf_contact_notes);
//					if(data) {
//						finalOutput	+= '<strong>Contact Notes</strong><br />';
//						jQuery.each(data, function(i, item) {
//							finalOutput	+=item.note_date + ': '
//										+ item.note + '<br />';
//						});
//					}

//					data = jQuery.parseJSON(php_message.ddcf_contact_relations);
//					if(data) {
//						finalOutput	+= '<strong>Contact Relations</strong><br />';
//						jQuery.each(data, function(i, item) {
//							finalOutput	+=item.contact_relationship + ': '
//										+ item.contact_relative_one_id + ': '
//										+ item.contact_relative_two_id + '<br />';
//						});
//					}

//					data = jQuery.parseJSON(php_message.ddcf_contact_bookables);
//					if(data) {
//						finalOutput	+= '<strong>Contact Owned Bookables</strong><br />';
//							jQuery.each(data, function(i, item) {
//								finalOutput	+= '<a href="'+item.guid+'">'+item.post_title + '</a><br />';
//								});
//					}

					data = jQuery.parseJSON(php_message.ddcf_contact_enquiries);
					if(data) {
						finalOutput	+= '<strong>Email Enquiries:</strong><br /><br />';
						jQuery.each(data, function(i, item) {
							finalOutput	+='Date: '
										+ item.enquiry_date   + '<br />Name: '
										+ item.customer_name  + '<br />Email: '
										+ item.email_address  + '<br />Subject: '
										+ item.email_subject  + ' Arrival Date: '
										+ item.arrival_date  + ' Departure Date: '
										+ item.departure_date  + '<br />'
										+ item.post_title  + ' Num Adults: '
										+ item.num_adults  + ' Num Children: '
										+ item.num_children  + '<br />Question 1: '
										+ item.question_one  + '<br />Question 2: '
										+ item.question_two  + '<br />Message: '
										+ item.email_message  + '<br />Receive Updates: '
										+ item.receive_updates  + ' IP address: '
										+ item.ip_address  + ' Country: '
										+ item.country  + ' Region: '
										+ item.region  + ' City: '
										+ item.city  + ' Enquiry ID: '
										+ item.enquiry_id   + '<br /><br /><br />';
						});
					}
					jQuery('#ddcf_entry_throbber').html('');
					jQuery('.ui-accordion-content-active').html(finalOutput);
					jQuery('#accordion').accordion( "refresh" );
				});
}


// helpers for results navigtion links
function showNext(show){
	if(show) {
		jQuery('#ddcf_next_page').css('color', 'inherit')
								.css('cursor', 'pointer')
		                        .css('text-decoration', 'none');

	}
	else {
		jQuery('#ddcf_next_page').css('color', gGreyOut)
								.css('cursor', 'default')
		                        .css('text-decoration', 'none');

	}
}
function showPrevious(show) {
	if(show) {
		jQuery('#ddcf_previous_page').css('color', 'inherit')
									.css('cursor', 'pointer')
		                            .css('text-decoration', 'none');

	}
	else {
		jQuery('#ddcf_previous_page').css('color', gGreyOut)
									.css('cursor', 'default')
		                            .css('text-decoration', 'none');

	}
}


function showUser(str) {
	jQuery('#ddcf_contact_information').html('<div class="ddcf_image_container"></div>');
	jQuery("#ddcf_action_arg").val(str);
	jQuery('#ddcf_page_info').html('querying database...');
	jQuery.post(the_ajax_script.ajaxurl, jQuery("#ddcf_management_form").serializeArray()
				,
				function(php_message){
					jQuery("#ddcf_action").val('');
					jQuery("#ddcf_action_arg").val('');
					jQuery('#ddcf_manager_nonce').val(php_message.ddcf_manager_nonce);

					// results navigation logic
					var nResults = parseInt(php_message.ddcf_num_results);
					var nOffset = parseInt(jQuery('#ddcf_results_offset').val());
					var nShowing = gResultsPerPage;
					if(nResults<=gResultsPerPage) {
						nShowing = nResults;
						showNext(false);
						showPrevious(false);
					}
					if(nResults>gResultsPerPage) {
						nShowing = nOffset+gResultsPerPage;
						if(nShowing>nResults) nShowing = nResults;
						if(nOffset>0) showPrevious(true);
						else showPrevious(false);
						if((nOffset+gResultsPerPage)<nResults) showNext(true);
						else showNext(false);
					}

					if(nResults>0) nOffset++;//zero based in code, one's based from user's perspective so adjust for display
					jQuery('#ddcf_page_info').html('Showing results '+(nOffset).toString()+' to '+(nShowing).toString()+' of '+(nResults).toString()+' results');
					if(php_message.ddcf_contact_information==='manager session initialised') {
						// whoops - user page refresh before expected search term submission - re-initiailise
						jQuery('#ddcf_contact_information').html('Please enter a search term or select a filter');
						jQuery('#ddcf_page_info').html('refreshed - lost search terms');
					}
					else {
						jQuery('#ddcf_contact_information').html(accordionise(php_message.ddcf_contact_information));
						jQuery("#accordion").accordion({ collapsible: true, active: false });
						jQuery("#accordion").accordion({activate: function(event, ui) {
							if(ui.oldPanel.prevObject.attr('id')) ui.oldPanel.first().html(gLastOpenAccordionHTML);
							gLastOpenAccordionHTML = ui.newPanel.first().html();
							if(ui.newHeader.length>0) {
								doxUser(ui);
								jQuery('#ddcf_edit_user').button({ disabled: false })
										  .click(function () {
										  jQuery('#ddcf_edit_contact_dialog').dialog("open");
								});
								jQuery('#ddcf_add_user_details').button({ disabled: false })
										  .click(function () {
										  jQuery('#ddcf_add_contact_info_dialog').dialog("open");
								});
								jQuery('#ddcf_add_relation').button({ disabled: false })
										  .click(function () {
										  jQuery('#ddcf_add_contact_rel_dialog').dialog("open");
								});
								jQuery('#ddcf_add_note').button({ disabled: false })
										  .click(function () {
										  jQuery('#ddcf_add_contact_note_dialog').dialog("open");
								});
							}
							else {
								jQuery('.ddcf_button').button({ disabled: true });
								jQuery('#ddcf_create_user').button({ disabled: false });
							}
						}});
						//ui.newHeader // jQuery object, activated header
						//ui.oldHeader // jQuery object, previous header
						//ui.newContent // jQuery object, activated content
						//ui.oldContent // jQuery object, previous content
					}
				});
}


function initialise_manager_session(){
	jQuery.post(the_ajax_script.ajaxurl, jQuery("#ddcf_management_form").serializeArray()
				,
				function(php_message){
                                        /* Silent acknowledgement of nonce accepted */
					/*jQuery('#ddcf_manager_nonce').val(php_message.ddcf_manager_nonce);*/
					jQuery('#ddcf_action').val('ddcf_filter_action');
                                        //jQuery('#ddcf_action_arg').val('all contact types')
					showUser('all contact types');
				});
}


















// Works? - populates the form from returned POST vars
//deserialize($('#ddcf_contact_form'),php_message, false);

/*function deserialize(element, data, clearForm)
{
	var splits = decodeURIComponent(data).split('&'),
	i = 0,
	split = null,
	key = null,
	value = null,
	splitParts = null;

	if (clearForm)
	{
		$('input[type="checkbox"],input[type="radio"]', element).removeAttr('checked');
		$('select,input[type="text"],input[type="password"],input[type="hidden"],textarea', element).val('');
	}

	var kv = {};
	while(split = splits[i++]){

		splitParts = split.split('=');
		key = splitParts[0] || '';
		value = (splitParts[1] || '').replace(/\+/g, ' ');

		if (key != ''){
			if( key in kv ){
				if( $.type(kv[key]) !== 'array' )
				kv[key] = [kv[key]];

				kv[key].push(value);
				}else
				kv[key] = value;
			}
		}

		for( key in kv ){
			value = kv[key];

			$('input[type="checkbox"][name="'+ key +'"][value="'+ value +'"],input[type="radio"][name="'+ key +'"][value="'+ value +'"]', element).attr('checked', 'checked');
			$('select[name="'+ key +'"],input[type="text"][name="'+ key +'"],input[type="password"][name="'+ key +'"],input[type="hidden"][name="'+ key +'"],textarea[name="'+ key +'"]', element).val(value);
	}
}*/