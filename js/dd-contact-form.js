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

// user details
var gbacContactName;
var gbacContactEmail;
var gbacContactSubject;
var gbacContactMessage;
var gbacArrivalDate  = '';
var gbacDepartureDate = '';
var gbacNumAdults = '';
var gbacNumChildren = '';
var gbacQuestionOne = '';
var gbacQuestionTwo = '';
var gbacRecieveUpdates = '';
var gbacCaptcha = '';
var gbacreCaptcha = '';
// defaults
var gbacContactNameDefault;
var gbacContactEmailDefault;
var gbacContactSubjectDefault;
var gbacContactMessageDefault;
var gbacArrivalDateDefault;
var gbacDepartureDateDefault;
var gbacNumAdultsDefault;
var gbacNumChildrenDefault;
var gbacQuestionOneDefault;
var gbacQuestionTwoDefault;
var gbacRecieveUpdatesDefault;
var gbacCaptchaDefault;
var gbacreCaptchaDefault;
// resizing vars
var gbacWidth;
var gbacInitialTimer;
var gbacErrorCheckingMethod;
var gbacQuestionsCompulsoryCheck;
var gbacPartySizeCompulsoryCheck;
var gbaDatesCompulsoryCheck;

function resetForm()
{
	gbacContactName='';
	jQuery("#ddcf_contact_name").val(gbacContactNameDefault);
	jQuery('#ddcf_contact_name_fb').html('&nbsp;');

	gbacContactEmail='';
	jQuery("#ddcf_contact_email").val(gbacContactEmailDefault);
	jQuery('#ddcf_contact_email_fb').html('&nbsp;');

	gbacContactSubject='';
	jQuery("#ddcf_contact_subject").val(gbacContactSubjectDefault);
	jQuery('#ddcf_contact_subject_fb').html('&nbsp;');

	gbacContactMessage='';
	jQuery("#ddcf_contact_message").val(gbacContactMessageDefault);
	jQuery('#ddcf_contact_message_fb').html('&nbsp;');

	gbacQuestionOne='';
	jQuery("#ddcf_question_one").val(gbacQuestionOneDefault);
	jQuery('#ddcf_question_one_fb').html('&nbsp;');

	gbacQuestionTwo='';
	jQuery("#ddcf_question_two").val(gbacQuestionTwoDefault);
	jQuery('#ddcf_question_two_fb').html('&nbsp;');

	// reset dates
	gbacArrivalDate  = '';
	gbacDepartureDate  = '';
	jQuery("#ddcf_arrival_date").val(gbacArrivalDateDefault);
	jQuery("#ddcf_departure_date").val(gbacDepartureDateDefault);
	jQuery('#ddcf_arrival_date_fb').html('&nbsp;');
	jQuery('#ddcf_departure_date_fb').html('&nbsp;');

	// reset captcha answers (but not questions)
	gbacCaptcha = '';
	jQuery("#ddcf_contact_captcha_add").val(gbacCaptchaDefault);
	jQuery('#ddcf_contact_captcha_fb').html('&nbsp;');
	gbacreCaptcha = '';
	jQuery("#recaptcha_response_field").val(gbacreCaptchaDefault);
	jQuery('#recaptcha_response_field_fb').html('&nbsp;');

        // reset Rec. Updates option
	gbacRecieveUpdates = '';
	if(gbacRecieveUpdatesDefault) jQuery("#ddcf_newsletter_signup").prop('checked', true);
	else jQuery("#ddcf_newsletter_signup").prop('checked', false);

	// reset the person count dropdowns
	gbacNumAdults = '';
	jQuery("#ddcf_num_adults").val(gbacNumAdultsDefault);
	jQuery('#ddcf_num_adults_fb').html('&nbsp;');
	gbacNumChildren = '';
	jQuery("#ddcf_num_children").val(gbacNumChildrenDefault);
	jQuery('#ddcf_num_children_fb').html('&nbsp;');

	// clear the user feedback div contents
	jQuery('#error_reporting').html('');
}

function validateEmail(email)
{
	// from http://www.paulund.co.uk/regular-expression-to-validate-email-address
	var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
	var valid = emailReg.test(email);
	if(!valid) return false;
    else return true;
}


function checkForm(andSubmit,makeChanges)
{
    var bChecksOut = true;
    var errors = '';

    /* just give a little settling down time before initial resizing */
    if (gbacInitialTimer > 0) {
        gbacInitialTimer = gbacInitialTimer - 500;
        if (gbacInitialTimer <= 0)
            adjust_for_size();
    }

    /* Check out each form element in turn. Report any problems */

    /* Name */
    var szName = jQuery("#ddcf_contact_name").val();
    if (szName !== gbacContactNameDefault && szName !== '') {
        gbacContactName = jQuery.trim(szName);
        if (gbacErrorCheckingMethod === 'realtime')
            jQuery('#ddcf_contact_name_fb').html('&#10004;');
    } else {
        if (makeChanges && szName === '')
            jQuery("#ddcf_contact_name").val(gbacContactNameDefault);
        if (gbacErrorCheckingMethod === 'realtime')
            jQuery('#ddcf_contact_name_fb').html('&nbsp;');
        if (andSubmit)
            errors += 'Name not set<br />';
        bChecksOut = false;
    }


    /* Email */
    var szEmailEntry = jQuery.trim(jQuery("#ddcf_contact_email").val());
    if (szEmailEntry !== gbacContactEmailDefault && szEmailEntry !== '') {
        if (!validateEmail(szEmailEntry)) {
            if (gbacErrorCheckingMethod === 'realtime')
                jQuery('#ddcf_contact_email_fb').html('<a style="color:red">&#10008;</a>');
            if (andSubmit)
                errors += 'Email format not recognised<br />';
            bChecksOut = false;
        } else {
            gbacContactEmail = szEmailEntry;
            if (makeChanges)
                jQuery("#ddcf_contact_email").val(gbacContactEmail);
            if (gbacErrorCheckingMethod === 'realtime')
                jQuery('#ddcf_contact_email_fb').html('&#10004;');
        }
    } else {
        if (makeChanges && szEmailEntry === '')
            jQuery("#ddcf_contact_email").val(gbacContactEmailDefault);
        if (gbacErrorCheckingMethod === 'realtime')
            jQuery('#ddcf_contact_email_fb').html('&nbsp;');
        if (andSubmit)
            errors += 'Email address not set<br />';
        bChecksOut = false;
    }


    /* Subject */
    var szSubjectLine = jQuery("#ddcf_contact_subject").val();
    if (szSubjectLine !== gbacContactSubjectDefault && szSubjectLine !== '') {
        gbacContactSubject = jQuery.trim(szSubjectLine);
        if (gbacErrorCheckingMethod === 'realtime')
            jQuery('#ddcf_contact_subject_fb').html('&#10004;');
    } else {
        if (makeChanges && szSubjectLine === '')
            jQuery("#ddcf_contact_subject").val(gbacContactSubjectDefault);
        if (gbacErrorCheckingMethod === 'realtime')
            jQuery('#ddcf_contact_subject_fb').html('&nbsp;');
        if (andSubmit)
            errors+='Subject not set<br />';
        bChecksOut = false;
    }


    /* Message */
    var szMessage = jQuery("#ddcf_contact_message").val();
    if (szMessage !== gbacContactMessageDefault && szMessage !== '') {
        gbacContactMessage = jQuery.trim(szMessage);
        if (gbacErrorCheckingMethod === 'realtime')
            jQuery('#ddcf_contact_message_fb').html('&#10004;');
    } else {
        if (makeChanges && szMessage === '')
            jQuery("#ddcf_contact_message").val(gbacContactMessageDefault);
        if (gbacErrorCheckingMethod === 'realtime')
            jQuery('#ddcf_contact_message_fb').html('&nbsp;');
        if (andSubmit)
            errors += 'Please write a message<br />';
        bChecksOut = false;
    }


    /* Question One */
    if (jQuery('#ddcf_question_one').length !== 0) { // element found
        var szQuestionOne = jQuery("#ddcf_question_one").val();
        if (szQuestionOne !== gbacQuestionOneDefault && szQuestionOne !== '') {
            gbacQuestionOne = jQuery.trim(szQuestionOne);
            if (gbacErrorCheckingMethod === 'realtime')
                jQuery('#ddcf_question_one_fb').html('&#10004;');
        } else {
            if (makeChanges && szQuestionOne === '')
                jQuery("#ddcf_question_one").val(gbacQuestionOneDefault);
            if (gbacErrorCheckingMethod === 'realtime')
                jQuery('#ddcf_question_one_fb').html('&nbsp;');
            if (gbacQuestionsCompulsoryCheck) {
                if (andSubmit)
                    errors += 'Please answer the question: ' + gbacQuestionOneDefault + '<br />';
                bChecksOut = false;
            }
        }
    }


    /* Question Two */
    if (jQuery('#ddcf_question_two').length !== 0) { // element found
        var szQuestionTwo = jQuery("#ddcf_question_two").val();
        if (szQuestionTwo !== gbacQuestionTwoDefault && szQuestionTwo !== '') {
            gbacQuestionTwo = jQuery.trim(szQuestionTwo);
            if (gbacErrorCheckingMethod === 'realtime')
                jQuery('#ddcf_question_two_fb').html('&#10004;');
        } else {
            if (makeChanges && szQuestionTwo === '')
                jQuery("#ddcf_question_two").val(gbacQuestionTwoDefault);
            if (gbacErrorCheckingMethod === 'realtime')
                jQuery('#ddcf_question_two_fb').html('&nbsp;');
            if (gbacQuestionsCompulsoryCheck) {
                if (andSubmit)
                    errors += 'Please answer the question: ' + gbacQuestionTwoDefault + '<br />';
                bChecksOut = false;
            }
        }
    }


    /* Datepickers (only check if shown - i.e. width > 400px )*/
    gbacWidth = jQuery("#ddcf_contact_form_contents").width();
    if(gbacWidth>400) {
		if (jQuery('#ddcf_arrival_date').length !== 0) { // element found
			var dtArrivalDate = jQuery("#ddcf_arrival_date").val();
			if (dtArrivalDate !== gbacArrivalDateDefault && dtArrivalDate !== '') {
				gbacArrivalDate = jQuery.trim(dtArrivalDate);
				if (gbacErrorCheckingMethod === 'realtime')
					jQuery('#ddcf_arrival_date_fb').html('&#10004;');
			} else {
				if (makeChanges && dtArrivalDate === '')
					jQuery("#ddcf_arrival_date").val(gbacArrivalDateDefault);
				if (gbacErrorCheckingMethod === 'realtime')
					jQuery('#ddcf_arrival_date_fb').html('&nbsp;');
				gbacWidth = jQuery("#ddcf_contact_form_contents").width();
				if (gbaDatesCompulsoryCheck&&(gbacWidth>300)) {
					if (andSubmit)
						errors += 'Please enter a booking start date<br />';
					bChecksOut = false;
				}
			}
		}
		if (jQuery('#ddcf_departure_date').length !== 0) { // element found
			var dtDepartureDate = jQuery("#ddcf_departure_date").val();
			if (dtDepartureDate !== gbacDepartureDateDefault && dtDepartureDate !== '') {
				gbacDepartureDate = jQuery.trim(dtDepartureDate);
				if (gbacErrorCheckingMethod === 'realtime')
					jQuery('#ddcf_departure_date_fb').html('&#10004;');
			} else {
				if (makeChanges && dtDepartureDate === '')
					jQuery("#ddcf_departure_date").val(gbacDepartureDateDefault);
				if (gbacErrorCheckingMethod === 'realtime')
					jQuery('#ddcf_departure_date_fb').html('&nbsp;');
				if (gbaDatesCompulsoryCheck&&(gbacWidth>300)) {
					if (andSubmit)
						errors += 'Please enter a booking end date<br />';
					bChecksOut = false;
				}
			}
		}
	}


    /* Number of people */
    if (jQuery('#ddcf_num_adults').length !== 0) { // element found
        var iNumAdults = jQuery("#ddcf_num_adults").val();
        if (iNumAdults !== gbacNumAdultsDefault && iNumAdults !== '') {
            gbacNumAdults = jQuery.trim(iNumAdults);
            if (gbacErrorCheckingMethod === 'realtime') {
                jQuery('#ddcf_num_adults_fb').html('&#10004;');
                jQuery('#ddcf_num_children_fb').html('&#10004;');
            }
        } else {
            if (makeChanges && iNumAdults === '')
                jQuery("#ddcf_num_adults").val(gbacNumAdultsDefault);
            if (gbacErrorCheckingMethod === 'realtime') {
                jQuery('#ddcf_num_adults_fb').html('&nbsp;');
                jQuery('#ddcf_num_children_fb').html('&nbsp;');
            }
            if (gbacPartySizeCompulsoryCheck) {
                if (andSubmit)
                    errors += 'Please select at least one adult<br />';
                bChecksOut = false;
            }
        }
    } // gbacNumChildren?


    /* Simple Addition Captcha */
    if (jQuery('#ddcf_contact_captcha_add').length !== 0) { // element found
        gbacCaptcha = jQuery.trim(jQuery("#ddcf_contact_captcha_add").val());
        if (gbacCaptcha !== gbacCaptchaDefault && gbacCaptcha !== '') {
            var ddcfNumOne = parseInt(jQuery.trim(jQuery("#ddcf_captcha_one").html(), 10));
            var ddcfNumTwo = parseInt(jQuery.trim(jQuery("#ddcf_captcha_two").html(), 10));
            if ((ddcfNumOne + ddcfNumTwo) === parseInt(gbacCaptcha, 10)) {
                if (gbacErrorCheckingMethod === 'realtime')
                    jQuery('#ddcf_contact_captcha_fb').html('&#10004;');
            }
            else {
                if (gbacErrorCheckingMethod === 'realtime')
                    jQuery('#ddcf_contact_captcha_fb').html('<a style="color:red">&#10008;</a>');
                if (andSubmit)
                    errors += 'The Captcha answer is incorrect<br />';
                bChecksOut = false;
            }
        } else {
            if (makeChanges && gbacCaptcha === '')
                jQuery("#ddcf_contact_captcha_add").val(gbacCaptchaDefault);
            if (gbacErrorCheckingMethod === 'realtime')
                jQuery('#ddcf_contact_captcha_fb').html('&nbsp;');
            if (andSubmit)
                errors += 'Please answer the captcha question<br />';
            bChecksOut = false;
        }
    }


    /* google reCaptcha: only check for *some* input - i.e. on the fly value checking TBA */
    if (jQuery('#recaptcha_response_field').length !== 0) { // element found
        if (jQuery('#recaptcha_response_field').val() === "") {
            if (makeChanges)
                jQuery("#recaptcha_response_field").val(gbacreCaptchaDefault);
            if (andSubmit)
                errors += 'reCaptcha not set<br />';
            bChecksOut = false;
            if (gbacErrorCheckingMethod === 'realtime')
                jQuery('#ddcf_contact_captcha_fb').html('&nbsp;');
        } else if (jQuery("#recaptcha_response_field").val() !== gbacreCaptchaDefault) {
            gbacreCaptcha = jQuery.trim(jQuery("#recaptcha_response_field").val());
            if (gbacErrorCheckingMethod === 'realtime')
                jQuery('#ddcf_contact_captcha_fb').html('&#10004;');
        } else {
            //gbacreCaptcha = jQuery("#recaptcha_response_field").val();
            if (andSubmit)
                errors += 'reCaptcha not set<br />';
            bChecksOut = false;
            if (gbacErrorCheckingMethod === 'realtime')
                jQuery('#ddcf_contact_captcha_fb').html('&nbsp;');
        }
    }

    /* Enable send button if form checks out and in realtime checking mode */
    if (bChecksOut) {
        if (gbacErrorCheckingMethod === 'realtime') {
            jQuery('#ddcf_contact_send').button({disabled: false});
            jQuery('#ddcf_contact_send_fb').html('&nbsp;');
        }
    }
    else {
        if (gbacErrorCheckingMethod === 'realtime') {
            jQuery('#ddcf_contact_send').button({disabled: true});
            jQuery('#ddcf_contact_send_fb').html('&nbsp;');
        }
    }


    if (andSubmit) {

        if ((jQuery('#ddcf_arrival_date').length !== 0) && (jQuery('#ddcf_departure_date').length !== 0)) { // element found
            // check dates out
            var datepickerBegin = jQuery("#ddcf_arrival_date").datepicker('getDate');
            var datepickerEnd = jQuery("#ddcf_departure_date").datepicker('getDate');

            var difference = (datepickerEnd - datepickerBegin) / (86400000 * 1);
            //alert ('datepickerBegin: '+datepickerBegin+'\ndatepickerEnd: '+datepickerEnd+'\nDifference is '+difference.toString());
            if (difference < 0) {
                errors += 'The end date must come after the start date<br />';
                bChecksOut = false;
            }
            else if (difference === 0) {
                //errors+= 'The booking dates are the same - please set the booking date<br />';
                //bChecksOut = false;
            }
        }
        if (bChecksOut) {
            jQuery('#error_reporting').html('');
            jQuery("#ddcf_contact_form_contents").fadeOut("slow"); //.css('display', 'none');
            submit();
        }
    }
    if (errors.length > 0)
        jQuery('#error_reporting').html(errors);

    return bChecksOut;
}

function adjust_for_size() {

    gbacWidth = jQuery("#ddcf_contact_form_wrapper").width();
    /*jQuery("#error_reporting").html('Form width: '+gbacWidth+'px'); */

    /* tiny screens - big buttons, squash simple additon captcha */
    if(gbacWidth<=300){
                jQuery('#ddcf_button_area').css('width', '100%');
                jQuery('.ddcf_button').css('width', '100%').css('margin', '0.5em 0');
                jQuery('#ddcf_table_span_captcha_add').css('width', 'auto');
                jQuery('#ddcf_contact_captcha_fb').css('bottom', '-2em').css('right', '-1em');
    } else {
                jQuery('#ddcf_button_area').css('width', 'auto');
                jQuery('.ddcf_button').css('width', 'auto').css('margin', '0 0 0.7em 0.6em');
                jQuery('#ddcf_table_span_captcha_add').css('width', '22em');
                jQuery('#ddcf_contact_captcha_fb').css('bottom', '0em').css('right', '-0.5em');
    }

    /* small screens - show / hide datepickers, set width of signup checkbox at bottom */
    if(gbacWidth<=375){
                jQuery('#ddcf_checkbox_area').css('width', '100%');
                jQuery('.ddcf_table_span_date').css('display','none');
                jQuery('.ddcf_table_span_datetime').css('display','none');
    }
    else {
                jQuery('#ddcf_checkbox_area').css('width', 'auto');
                jQuery('.ddcf_table_span_date').css('display','inline-block');
                jQuery('.ddcf_table_span_datetime').css('display','inline-block');
    }

    /* flip between 1 and 2 columns */
    var flip_width = 600;
    if(gbacWidth<=flip_width) {
		jQuery('#ddcf_contact_form_top_left').css('width', '100%');
		jQuery('#ddcf_contact_form_top_right').css('width', '100%').css('float', 'left').css('margin-top', '0em');
                jQuery('#ddcf_dates_align').css('max-width','22em');
                jQuery('.ddcf_table_span_date').css('float','left');
                jQuery('.ddcf_table_span_datetime').css('float','left');
    }
    else {      // width > flip_width
		jQuery('#ddcf_contact_form_top_left').css('width', '35%');
		jQuery('#ddcf_contact_form_top_right').css('width', '65%').css('float', 'right').css('margin-top', '0em');
                jQuery('#ddcf_dates_align').css('max-width','100em');
                jQuery('.ddcf_table_span_date').css('float','none');
                jQuery('.ddcf_table_span_datetime').css('float','none');
    }

    /* set top right section height and reposition accordingly */
    var topLeftHeight = jQuery("#ddcf_contact_form_top_left").height();
    var tableHeight = jQuery("#ddcf_details_table").height();
    if(tableHeight>topLeftHeight)
        jQuery("#ddcf_contact_form_top_right").css('height', tableHeight);
    else
        jQuery("#ddcf_contact_form_top_right").css('height', topLeftHeight);
    var tableTop = tableHeight/2.0;
    jQuery("#ddcf_details_table").css('margin-top', -tableTop);
}


jQuery(document).ready(function ($) {

	// tweak on resize
	jQuery(window).resize(function() {adjust_for_size();});

	// Get the default values in case of form reset
	gbacContactNameDefault = jQuery.trim(jQuery('#ddcf_contact_name').val());
	gbacContactEmailDefault = jQuery.trim(jQuery('#ddcf_contact_email').val());
	gbacContactSubjectDefault = jQuery.trim(jQuery('#ddcf_contact_subject').val());
	gbacContactMessageDefault = jQuery.trim(jQuery('#ddcf_contact_message').val());
	gbacQuestionOneDefault = jQuery.trim(jQuery('#ddcf_question_one').val());
	gbacQuestionTwoDefault = jQuery.trim(jQuery('#ddcf_question_two').val());
	gbacArrivalDateDefault = jQuery.trim(jQuery('#ddcf_arrival_date').val());
	gbacDepartureDateDefault = jQuery.trim(jQuery('#ddcf_departure_date').val());
	gbacNumAdultsDefault = jQuery.trim(jQuery('#ddcf_num_adults').val());
	gbacNumChildrenDefault = jQuery.trim(jQuery('#ddcf_num_children').val());
	gbacRecieveUpdatesDefault = jQuery.trim(jQuery('#ddcf_newsletter_signup').is(':checked'));
	gbacCaptchaDefault = jQuery.trim(jQuery('#ddcf_contact_captcha_add').val());
	gbacreCaptchaDefault = jQuery.trim(jQuery('#recaptcha_response_field').val());
	gbacErrorCheckingMethod = jQuery.trim(jQuery('#ddcf_error_checking_method').val());
	gbacQuestionsCompulsoryCheck = jQuery.trim(jQuery('#ddcf_questions_compulsory_check').val());
	gbacPartySizeCompulsoryCheck = jQuery.trim(jQuery('#ddcf_party_size_compulsory_check').val());
	gbaDatesCompulsoryCheck = jQuery.trim(jQuery('#ddcf_dates_compulsory_check').val());

	// focus and unfocus handling:

	// Contact name text field
	jQuery('#ddcf_contact_name').focusout(function(){checkForm(false,true);})
							   .focus(function() {
									if(jQuery("#ddcf_contact_name").val()===gbacContactNameDefault) jQuery("#ddcf_contact_name").val("");});

	// email text field
	jQuery('#ddcf_contact_email').focusout(function(){checkForm(false,true);})
							    .focus(function() {
									if(jQuery("#ddcf_contact_email").val()===gbacContactEmailDefault) jQuery("#ddcf_contact_email").val("");});

	// subject text field
	jQuery('#ddcf_contact_subject').focusout(function(){checkForm(false,true);})
							      .focus(function() {
										if(jQuery("#ddcf_contact_subject").val()===gbacContactSubjectDefault) jQuery("#ddcf_contact_subject").val("");});

	// message text field
	jQuery('#ddcf_contact_message').focusout(function(){checkForm(false,true);})
							   .focus(function() {
										if(jQuery("#ddcf_contact_message").val()===gbacContactMessageDefault) jQuery("#ddcf_contact_message").val(""); });


	// question one field
	jQuery('#ddcf_question_one').focusout(function(){checkForm(false,true);})
							   	.focus(function() {
										if(jQuery("#ddcf_question_one").val()===gbacQuestionOneDefault) jQuery("#ddcf_question_one").val(""); });

	// question two field
	jQuery('#ddcf_question_two').focusout(function(){checkForm(false,true);})
							   .focus(function() {
									if(jQuery("#ddcf_question_two").val()===gbacQuestionTwoDefault) jQuery("#ddcf_question_two").val(""); });


	// contact form booking functionality */
	jQuery(".ddcf_date_picker").datepicker({ timeFormat: "HH:mm", dateFormat : "yy-mm-dd", minDate: 1 });
	jQuery(".ddcf_datetime_picker").datetimepicker({ timeFormat: "HH:mm", dateFormat : "yy-mm-dd", minDate: 1 });
	jQuery("#ddcf_arrival_date").focusout(function(){checkForm(false,true);})
								.focus(function() {
									if(jQuery("#ddcf_arrival_date").val()===gbacArrivalDateDefault) jQuery("#ddcf_arrival_date").val(""); });
	jQuery("#ddcf_departure_date").focusout(function(){checkForm(false,true);})
									.focus(function() {
									if(jQuery("#ddcf_departure_date").val()===gbacDepartureDateDefault) jQuery("#ddcf_departure_date").val(""); });

	// bind unfocus event for num adults / children
	jQuery('#ddcf_num_adults').focusout(function(){checkForm(false,true);});
	jQuery('#ddcf_num_children').focusout(function(){checkForm(false,true);});


	// Reset Button
	jQuery('#ddcf_contact_reset').button()
	                        .click(function( event ) {
								event.preventDefault();
								resetForm();
							});
	// Submit Button
        if(gbacErrorCheckingMethod==='onsubmit') {
            jQuery('#ddcf_contact_send').button({ disabled: false })
                                                            .click(function( event ) {
                                                                    event.preventDefault();
                                                                    checkForm(true,true);
                                                            });
        }
        else { // assume 'realtime'
            jQuery('#ddcf_contact_send').button({ disabled: true })
                                                            .click(function( event ) {
                                                                    event.preventDefault();
                                                                    checkForm(true,true);
                                                            });
        }

	/* take control of Enter Key presses - run checkForm9ture) (could just forward them to the next input, like tab :-) */
	/* src: http://stackoverflow.com/questions/2335553/jquery-how-to-catch-enter-key-and-change-event-to-tab */
	jQuery('input').keydown( function(e) {
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key === 13) {
            e.preventDefault();
            return e.keyCode = 9; //set event key to tab
        }
    });

    jQuery(window).bind('beforeunload', function(){
		resetForm();
	});

	// show realtime error checking?
	if(jQuery('#ddcf_error_checking_method').val()==='realtime') {
		jQuery('.ddcf_contact_input_verify').css('display', 'inline');
	}
	else {
		jQuery('.ddcf_contact_input_verify').css('display', 'none');
	}

	// set up captcha etc (js / jQuery via WP / Ajax / php ;-) :-O
	initialise_session();

        // adjust screen elements after given time to allow for setting up of captcha (multiple of 500mS)
        gbacInitialTimer = 2500;

	// start monitoring input
	setInterval(function(){checkForm(false,false);},500);
});