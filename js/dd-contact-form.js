/*
    This file is part of Davedub's Contact Form plugin for WordPress

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
//var gbacHeight;
//var gbacDatesArea;
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
	jQuery('#ddcf_contact_captcha_add_fb').html('&nbsp;');
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


    /* Simple Add Captcha */
    if (jQuery('#ddcf_contact_captcha_add').length !== 0) { // element found
        gbacCaptcha = jQuery.trim(jQuery("#ddcf_contact_captcha_add").val());
        if (gbacCaptcha !== gbacCaptchaDefault && gbacCaptcha !== '') {
            var bacNumOne = parseInt(jQuery.trim(jQuery("#ddcf_captcha_one").html(), 10));
            var bacNumTwo = parseInt(jQuery.trim(jQuery("#ddcf_captcha_two").html(), 10));
            if ((bacNumOne + bacNumTwo) === parseInt(gbacCaptcha, 10)) {
                if (gbacErrorCheckingMethod === 'realtime')
                    jQuery('#ddcf_contact_captcha_add_fb').html('&#10004;');
            }
            else {
                if (gbacErrorCheckingMethod === 'realtime')
                    jQuery('#ddcf_contact_captcha_add_fb').html('<a style="color:red">&#10008;</a>');
                if (andSubmit)
                    errors += 'The Captcha answer is incorrect<br />';
                bChecksOut = false;
            }
        } else {
            if (makeChanges && gbacCaptcha === '')
                jQuery("#ddcf_contact_captcha_add").val(gbacCaptchaDefault);
            if (gbacErrorCheckingMethod === 'realtime')
                jQuery('#ddcf_contact_captcha_add_fb').html('&nbsp;');
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
        } else if (jQuery("#recaptcha_response_field").val() !== gbacreCaptchaDefault) {
            gbacreCaptcha = jQuery.trim(jQuery("#recaptcha_response_field").val());
        } else {
            //gbacreCaptcha = jQuery("#recaptcha_response_field").val();
            if (andSubmit)
                errors += 'reCaptcha not set<br />';
            bChecksOut = false;
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

    gbacWidth = jQuery("#ddcf_contact_form_contents").width();
    /*jQuery("#error_reporting").html('form width: '+gbacWidth+'px'); */

    /* do adjustments */

    /* big buttons on a small screen */
    if(gbacWidth<=300){
                jQuery('#ddcf_button_area').css('width', '100%');
                jQuery('.ddcf_button').css('width', '100%').css('margin', '0.5em 0');
    }
    else {
                jQuery('#ddcf_button_area').css('width', 'auto');
                jQuery('.ddcf_button').css('width', 'auto').css('margin', '0 0 0.7em 0.6em');
    }
    if(gbacWidth<=400){// show / hide datepickers
                jQuery('#ddcf_checkbox_area').css('width', '100%');
                jQuery('.ddcf_dates_align').css('display','none');
                jQuery('.ddcf_table_span_right').css('float','none');
    }
    else {
                jQuery('#ddcf_checkbox_area').css('width', 'auto');
                jQuery('.ddcf_dates_align').css('display','block');
                jQuery('.ddcf_table_span_right').css('float','none');
    }
    /* flip between 1 and 2 cols at 715px */
    if(gbacWidth<=715) {
		jQuery('#ddcf_contact_form_top_left').css('width', '100%');
		jQuery('#ddcf_contact_form_top_right').css('width', '100%').css('float', 'left');

		if(gbacWidth<=505) {
			jQuery('.ddcf_dropdowns_container').css('min-width', '7em');
			jQuery('label[for="ddcf_arrival_date"]').css('padding', '0.5em 0em 0em 1.5em');
			jQuery('label[for="ddcf_departure_date"]').css('padding', '0.5em 0em 0em 1.5em');
			jQuery('#ddcf_num_adults_fb').css('top', '2.2em');
			jQuery('#ddcf_num_children_fb').css('top', '2.2em');

			/* padding on text inputs (affects width, so we adjust the width too) */
			jQuery('.ddcf_contact_text_input').css('width', '90%').css('padding', '0.5em 2% 0.6em 2%');
			jQuery('#ddcf_contact_message').css('width', '91%').css('padding', '0.5em 2% 0.6em 2%');
			jQuery('.ddcf_question').css('width', '91%').css('padding', '0.5em 2% 0.6em 2%');

			/* keep feedback markers on their marks */
			jQuery('#ddcf_contact_name_fb').css('right', '6%').css('top', '12%');
			jQuery('#ddcf_contact_email_fb').css('right', '6%').css('top', '12%');
			jQuery('#ddcf_contact_subject_fb').css('right', '6%').css('top', '12%');
			jQuery('#ddcf_question_one_fb').css('right', '5.5%').css('top', '6%');
			jQuery('#ddcf_question_two_fb').css('right', '5.5%').css('top', '55%');
			jQuery('#ddcf_contact_message_fb').css('right', '5.5%').css('top', '3.5%');

		} else {
			jQuery('.ddcf_dropdowns_container').css('min-width', '12.7em');
			jQuery('label[for="ddcf_arrival_date"]').css('padding', '0.5em 0em 0em 2.5em');
			jQuery('label[for="ddcf_departure_date"]').css('padding', '0.5em 0em 0em 2.5em');
			jQuery('#ddcf_num_adults_fb').css('top', '0.27em');
			jQuery('#ddcf_num_children_fb').css('top', '0.27em');

			/* padding on text inputs (affects width, so we adjust the width too) */
			jQuery('.ddcf_contact_text_input').css('width', '94%').css('padding', '0.5em 3% 0.6em 3%');
			jQuery('#ddcf_contact_message').css('width', '94%').css('padding', '0.5em 1% 0.6em 1%');
			jQuery('.ddcf_question').css('width', '94%').css('padding', '0.5em 1% 0.6em 1%');

			/* keep feedback markers on their marks */
			jQuery('#ddcf_contact_name_fb').css('right', '3.5%').css('top', '12%');
			jQuery('#ddcf_contact_email_fb').css('right', '3.5%').css('top', '12%');
			jQuery('#ddcf_contact_subject_fb').css('right', '3.5%').css('top', '12%');
			jQuery('#ddcf_question_one_fb').css('right', '3.7%').css('top', '6%');
			jQuery('#ddcf_question_two_fb').css('right', '3.7%').css('top', '55%');
			jQuery('#ddcf_contact_message_fb').css('right', '3.7%').css('top', '3.5%');
		}
    }
    else {
		jQuery('#ddcf_contact_form_top_left').css('width', '40%');
		jQuery('#ddcf_contact_form_top_right').css('width', '60%').css('float', 'right');


		if(gbacWidth<=820) {
			jQuery('.ddcf_dropdowns_container').css('min-width', '7em');
			jQuery('label[for="ddcf_arrival_date"]').css('padding', '0.5em 0em 0em 1.5em');
			jQuery('label[for="ddcf_departure_date"]').css('padding', '0.5em 0em 0em 1.5em');
			jQuery('#ddcf_num_adults_fb').css('top', '2.2em');
			jQuery('#ddcf_num_children_fb').css('top', '2.2em');

			/* padding on text inputs (affects width, so we adjust the width too) */
			jQuery('.ddcf_contact_text_input').css('width', '94%').css('padding', '0.5em 3% 0.6em 3%');
			jQuery('#ddcf_contact_message').css('width', '96%').css('padding', '0.5em 1% 0.6em 1%');
			jQuery('.ddcf_question').css('width', '96%').css('padding', '0.5em 1% 0.6em 1%');

			/* keep feedback markers on their marks */
			jQuery('#ddcf_contact_name_fb').css('right', '0%').css('top', '12%');
			jQuery('#ddcf_contact_email_fb').css('right', '0%').css('top', '12%');
			jQuery('#ddcf_contact_subject_fb').css('right', '0%').css('top', '12%');
			jQuery('#ddcf_question_one_fb').css('right', '2.5%').css('top', '6%');
			jQuery('#ddcf_question_two_fb').css('right', '2.5%').css('top', '55%');
			jQuery('#ddcf_contact_message_fb').css('right', '2.5%').css('top', '3.5%');


		} else {
			jQuery('.ddcf_dropdowns_container').css('min-width', '12.7em');
			jQuery('label[for="ddcf_arrival_date"]').css('padding', '0.5em 0em 0em 2.5em');
			jQuery('label[for="ddcf_departure_date"]').css('padding', '0.5em 0em 0em 2.5em');
			jQuery('#ddcf_num_adults_fb').css('top', '0.27em');
			jQuery('#ddcf_num_children_fb').css('top', '0.27em');

			/* padding on text inputs (affects width, so we adjust the width too) */
			jQuery('.ddcf_contact_text_input').css('width', '100%').css('padding', '0.5em 3% 0.6em 3%');
			jQuery('#ddcf_contact_message').css('width', '96%').css('padding', '0.5em 1% 0.6em 1%');
			jQuery('.ddcf_question').css('width', '96%').css('padding', '0.5em 1% 0.6em 1%');

			/* keep feedback markers on their marks */
			jQuery('#ddcf_contact_name_fb').css('right', '-3.5%').css('top', '12%');
			jQuery('#ddcf_contact_email_fb').css('right', '-3.5%').css('top', '12%');
			jQuery('#ddcf_contact_subject_fb').css('right', '-3.5%').css('top', '12%');
			jQuery('#ddcf_question_one_fb').css('right', '2.5%').css('top', '6%');
			jQuery('#ddcf_question_two_fb').css('right', '2.5%').css('top', '55%');
			jQuery('#ddcf_contact_message_fb').css('right', '2.5%').css('top', '3.5%');
		}
    }
//    if((gbacWidth<=890&&gbacWidth>600)||(gbacWidth<=535&&gbacWidth>=300)){
//                if(jQuery('.ddcf_dropdowns_align').length>0) {
//                    jQuery('.ddcf_dates_align').css('width', '48%');
//                }
//                else {
//                    jQuery('.ddcf_dates_align').css('width', '57%');
//                }
//                if(jQuery('.ddcf_dates_align').length>0) {
//                    jQuery('.ddcf_dropdowns_align').css('width', '53%');
//                }
//                else {
//                    jQuery('.ddcf_dropdowns_align').css('width', '57%');
//                }
//                jQuery('#ddcf_num_adults_fb').css('right', '64%').css('top', '30%');
//    }
//    else {
//                if(jQuery('.ddcf_dropdowns_align').length>0) {
//                    jQuery('.ddcf_dates_align').css('width', '65%');
//                    }
//                else {
//                    jQuery('.ddcf_dates_align').css('width', '40%');
//                }
//                if(jQuery('.ddcf_dates_align').length>0) {
//                    jQuery('.ddcf_dropdowns_align').css('width', '62%');
//                }
//                else {
//                    jQuery('.ddcf_dropdowns_align').css('width', '57%');
//                }
//                jQuery('#ddcf_num_adults_fb').css('right', '62%').css('top', '12%');

//                if(gbacWidth<=300) jQuery('#ddcf_arrival_date_fb').css('right', '5.5%').css('top', '15%');
//                else jQuery('#ddcf_arrival_date_fb').css('right', '13%').css('top', '15%');
//                if(gbacWidth<=480) {
//                    jQuery('#ddcf_departure_date_fb').css('right', '5.5%').css('top', '48%');
//                    /*jQuery('#ddcf_arrival_date_fb').css('right', '5.5%').css('top', '2%');*/
//                }
//                else {
//                    jQuery('#ddcf_departure_date_fb').css('right', '13%').css('top', '48%');
//                    /*jQuery('#ddcf_arrival_date_fb').css('right', '5.5%').css('top', '2%');*/
//                }
//    }

    /* enlarge top right section if necessary */
    var tableHeightPX = jQuery(".ddcf_details_table").css('height');
    var tableHeightSt = tableHeightPX.replace("px","");
    var tableHeight = parseInt(tableHeightSt,10);
    var tableTop = 1.15*(tableHeight/2.0); // just above halfway
    jQuery(".ddcf_details_table").css('margin-top', -tableTop);
    if(tableHeight>153)
        jQuery("#ddcf_contact_form_top_right").css('height', tableHeight).css('margin-bottom', '-1.1em');
    else
        jQuery("#ddcf_contact_form_top_right").css('height', "153px").css('margin-bottom', '0em');
}


jQuery(document).ready(function ($) {

	// tooltips (wip)
	if(jQuery('#ddcf-show-tooltips').val()==='ddcf_tooltips_check')
	{
		var tooltips = $( "[title]" ).tooltip(
				{ position: { my: "right bottom", at: "right top" }},
				{ tooltipClass: 'ddcf_tooltip_styling' }
			);
	}
	else $('[title]').removeAttr('title');

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
        if(gbacErrorCheckingMethod=='onsubmit') {
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
		jQuery('.ddcf_contact_text_input_verify').css('display', 'inline');
	}
	else {
		jQuery('.ddcf_contact_text_input_verify').css('display', 'none');
	}

	// set up captcha etc (js / jQuery via WP / Ajax / php ;-) :-O
	initialise_session();

        // adjust screen elements after given time to allow for setting up of captcha (multiple of 500mS)
        gbacInitialTimer = 2500;

	// start monitoring input
	setInterval(function(){checkForm(false,false);},500);
});

//http://www.catswhocode.com/blog/10-wordpress-dashboard-hacks