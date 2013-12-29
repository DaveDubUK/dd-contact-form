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

// user details
var ddcfContactName;
var ddcfContactEmail;
var ddcfContactSubject;
var ddcfContactMessage;
var ddcfArrivalDate  = '';
var ddcfDepartureDate = '';
var ddcfNumAdults = '';
var ddcfNumChildren = '';
var ddcfQuestionOne = '';
var ddcfQuestionTwo = '';
var ddcfRecieveUpdates = '';
var ddcfCaptcha = '';
var ddcfreCaptcha = '';
// defaults
var ddcfContactNameDefault;
var ddcfContactEmailDefault;
var ddcfContactSubjectDefault;
var ddcfContactMessageDefault;
var ddcfArrivalDateDefault;
var ddcfDepartureDateDefault;
var ddcfNumAdultsDefault;
var ddcfNumChildrenDefault;
var ddcfQuestionOneDefault;
var ddcfQuestionTwoDefault;
var ddcfReceiveUpdatesDefault;
var ddcfCaptchaDefault;
var ddcfreCaptchaDefault;
// resizing vars
var ddcfWidth;
var ddcfInitialTimer;
var ddcfErrorCheckingMethod;
var ddcfQuestionsCompulsoryCheck;
var ddcfPartySizeCompulsoryCheck;
var ddcfDatesCompulsoryCheck;

function resetForm()
{
	ddcfContactName='';
	jQuery("#ddcf_contact_name").val(ddcfContactNameDefault);
	jQuery('#ddcf_contact_name_fb').html('&nbsp;');

	ddcfContactEmail='';
	jQuery("#ddcf_contact_email").val(ddcfContactEmailDefault);
	jQuery('#ddcf_contact_email_fb').html('&nbsp;');

	ddcfContactSubject='';
	jQuery("#ddcf_contact_subject").val(ddcfContactSubjectDefault);
	jQuery('#ddcf_contact_subject_fb').html('&nbsp;');

	ddcfContactMessage='';
	jQuery("#ddcf_contact_message").val(ddcfContactMessageDefault);
	jQuery('#ddcf_contact_message_fb').html('&nbsp;');

	ddcfQuestionOne='';
	jQuery("#ddcf_question_one").val(ddcfQuestionOneDefault);
	jQuery('#ddcf_question_one_fb').html('&nbsp;');

	ddcfQuestionTwo='';
	jQuery("#ddcf_question_two").val(ddcfQuestionTwoDefault);
	jQuery('#ddcf_question_two_fb').html('&nbsp;');

	// reset dates
	ddcfArrivalDate  = '';
	ddcfDepartureDate  = '';
	jQuery("#ddcf_arrival_date").val(ddcfArrivalDateDefault);
	jQuery("#ddcf_departure_date").val(ddcfDepartureDateDefault);
	jQuery('#ddcf_arrival_date_fb').html('&nbsp;');
	jQuery('#ddcf_departure_date_fb').html('&nbsp;');

	// reset captcha answers (but not questions)
	ddcfCaptcha = '';
	jQuery("#ddcf_contact_captcha_add").val(ddcfCaptchaDefault);
	jQuery('#ddcf_contact_captcha_fb').html('&nbsp;');
	ddcfreCaptcha = '';
	jQuery("#recaptcha_response_field").val(ddcfreCaptchaDefault);
	jQuery('#recaptcha_response_field_fb').html('&nbsp;');

        // reset Rec. Updates option
	ddcfRecieveUpdates = '';
	if(ddcfReceiveUpdatesDefault) jQuery("#ddcf_newsletter_signup").prop('checked', true);
	else jQuery("#ddcf_newsletter_signup").prop('checked', false);

	// reset the person count dropdowns
	ddcfNumAdults = '';
	jQuery("#ddcf_num_adults").val(ddcfNumAdultsDefault);
	jQuery('#ddcf_num_adults_fb').html('&nbsp;');
	ddcfNumChildren = '';
	jQuery("#ddcf_num_children").val(ddcfNumChildrenDefault);
	jQuery('#ddcf_num_children_fb').html('&nbsp;');

	// clear the user feedback div contents
	jQuery('#ddcf_error_reporting').html('');
        
        // reset important hidden fields
        jQuery("#ddcf_init_nonce").val('');
        jQuery("#ddcf_submit_nonce").val('');
        jQuery("#ddcf_session_initialised").val('uninitialised');
        jQuery("#ddcf_form_density").val(0);        
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
    if (ddcfInitialTimer > 0) {
        ddcfInitialTimer = ddcfInitialTimer - 500;
        if (ddcfInitialTimer <= 0)
            adjust_for_size();
    }

    /* Check out each form element in turn. Report any problems */

    /* Name */
    var szName = jQuery("#ddcf_contact_name").val();
    if (szName !== ddcfContactNameDefault && szName !== '') {
        ddcfContactName = jQuery.trim(szName);
        if (ddcfErrorCheckingMethod === 'realtime')
            jQuery('#ddcf_contact_name_fb').html('&#10004;');
    } else {
        if (makeChanges && szName === '')
            jQuery("#ddcf_contact_name").val(ddcfContactNameDefault);
        if (ddcfErrorCheckingMethod === 'realtime')
            jQuery('#ddcf_contact_name_fb').html('&nbsp;');
        if (andSubmit)
            errors += 'Name not set<br />';
        bChecksOut = false;
    }


    /* Email */
    var szEmailEntry = jQuery.trim(jQuery("#ddcf_contact_email").val());
    if (szEmailEntry !== ddcfContactEmailDefault && szEmailEntry !== '') {
        if (!validateEmail(szEmailEntry)) {
            if (ddcfErrorCheckingMethod === 'realtime')
                jQuery('#ddcf_contact_email_fb').html('<p style="color:red;">&#10008;</p>');
            if (andSubmit)
                errors += 'Email format not recognised<br />';
            bChecksOut = false;
        } else {
            ddcfContactEmail = szEmailEntry;
            if (makeChanges)
                jQuery("#ddcf_contact_email").val(ddcfContactEmail);
            if (ddcfErrorCheckingMethod === 'realtime')
                jQuery('#ddcf_contact_email_fb').html('&#10004;');
        }
    } else {
        if (makeChanges && szEmailEntry === '')
            jQuery("#ddcf_contact_email").val(ddcfContactEmailDefault);
        if (ddcfErrorCheckingMethod === 'realtime')
            jQuery('#ddcf_contact_email_fb').html('&nbsp;');
        if (andSubmit)
            errors += 'Email address not set<br />';
        bChecksOut = false;
    }


    /* Subject */
    var szSubjectLine = jQuery("#ddcf_contact_subject").val();
    if (szSubjectLine !== ddcfContactSubjectDefault && szSubjectLine !== '') {
        ddcfContactSubject = jQuery.trim(szSubjectLine);
        if (ddcfErrorCheckingMethod === 'realtime')
            jQuery('#ddcf_contact_subject_fb').html('&#10004;');
    } else {
        if (makeChanges && szSubjectLine === '')
            jQuery("#ddcf_contact_subject").val(ddcfContactSubjectDefault);
        if (ddcfErrorCheckingMethod === 'realtime')
            jQuery('#ddcf_contact_subject_fb').html('&nbsp;');
        if (andSubmit)
            errors+='Subject not set<br />';
        bChecksOut = false;
    }


    /* Message */
    var szMessage = jQuery("#ddcf_contact_message").val();
    if (szMessage !== ddcfContactMessageDefault && szMessage !== '') {
        ddcfContactMessage = jQuery.trim(szMessage);
        if (ddcfErrorCheckingMethod === 'realtime')
            jQuery('#ddcf_contact_message_fb').html('&#10004;');
    } else {
        if (makeChanges && szMessage === '')
            jQuery("#ddcf_contact_message").val(ddcfContactMessageDefault);
        if (ddcfErrorCheckingMethod === 'realtime')
            jQuery('#ddcf_contact_message_fb').html('&nbsp;');
        if (andSubmit)
            errors += 'Please write a message<br />';
        bChecksOut = false;
    }


    /* Question One */
    if (jQuery('#ddcf_question_one').length !== 0) { // element found
        var szQuestionOne = jQuery("#ddcf_question_one").val();
        if (szQuestionOne !== ddcfQuestionOneDefault && szQuestionOne !== '') {
            ddcfQuestionOne = jQuery.trim(szQuestionOne);
            if (ddcfErrorCheckingMethod === 'realtime')
                jQuery('#ddcf_question_one_fb').html('&#10004;');
        } else {
            if (makeChanges && szQuestionOne === '')
                jQuery("#ddcf_question_one").val(ddcfQuestionOneDefault);
            if (ddcfErrorCheckingMethod === 'realtime')
                jQuery('#ddcf_question_one_fb').html('&nbsp;');
            if (ddcfQuestionsCompulsoryCheck) {
                if (andSubmit)
                    errors += 'Please answer the question: ' + ddcfQuestionOneDefault + '<br />';
                bChecksOut = false;
            }
        }
    }


    /* Question Two */
    if (jQuery('#ddcf_question_two').length !== 0) { // element found
        var szQuestionTwo = jQuery("#ddcf_question_two").val();
        if (szQuestionTwo !== ddcfQuestionTwoDefault && szQuestionTwo !== '') {
            ddcfQuestionTwo = jQuery.trim(szQuestionTwo);
            if (ddcfErrorCheckingMethod === 'realtime')
                jQuery('#ddcf_question_two_fb').html('&#10004;');
        } else {
            if (makeChanges && szQuestionTwo === '')
                jQuery("#ddcf_question_two").val(ddcfQuestionTwoDefault);
            if (ddcfErrorCheckingMethod === 'realtime')
                jQuery('#ddcf_question_two_fb').html('&nbsp;');
            if (ddcfQuestionsCompulsoryCheck) {
                if (andSubmit)
                    errors += 'Please answer the question: ' + ddcfQuestionTwoDefault + '<br />';
                bChecksOut = false;
            }
        }
    }


    /* Datepickers (only check if shown - i.e. width > 400px )*/
    ddcfWidth = jQuery("#ddcf_contact_form_contents").width();
    if(ddcfWidth>400) {
		if (jQuery('#ddcf_arrival_date').length !== 0) { // element found
			var dtArrivalDate = jQuery("#ddcf_arrival_date").val();
			if (dtArrivalDate !== ddcfArrivalDateDefault && dtArrivalDate !== '') {
				ddcfArrivalDate = jQuery.trim(dtArrivalDate);
				if (ddcfErrorCheckingMethod === 'realtime')
					jQuery('#ddcf_arrival_date_fb').html('&#10004;');
			} else {
				if (makeChanges && dtArrivalDate === '')
					jQuery("#ddcf_arrival_date").val(ddcfArrivalDateDefault);
				if (ddcfErrorCheckingMethod === 'realtime')
					jQuery('#ddcf_arrival_date_fb').html('&nbsp;');
				ddcfWidth = jQuery("#ddcf_contact_form_contents").width();
				if (ddcfDatesCompulsoryCheck&&(ddcfWidth>300)) {
					if (andSubmit)
						errors += 'Please enter a booking start date<br />';
					bChecksOut = false;
				}
			}
		}
		if (jQuery('#ddcf_departure_date').length !== 0) { // element found
			var dtDepartureDate = jQuery("#ddcf_departure_date").val();
			if (dtDepartureDate !== ddcfDepartureDateDefault && dtDepartureDate !== '') {
				ddcfDepartureDate = jQuery.trim(dtDepartureDate);
				if (ddcfErrorCheckingMethod === 'realtime')
					jQuery('#ddcf_departure_date_fb').html('&#10004;');
			} else {
				if (makeChanges && dtDepartureDate === '')
					jQuery("#ddcf_departure_date").val(ddcfDepartureDateDefault);
				if (ddcfErrorCheckingMethod === 'realtime')
					jQuery('#ddcf_departure_date_fb').html('&nbsp;');
				if (ddcfDatesCompulsoryCheck&&(ddcfWidth>300)) {
					if (andSubmit)
						errors += 'Please enter a booking end date<br />';
					bChecksOut = false;
				}
			}
		}
	}


    /* Number of people */
    if (jQuery('#ddcf_num_adults').length !== 0) { // element found
        var numAdults = jQuery("#ddcf_num_adults").val();
        if (numAdults !== ddcfNumAdultsDefault && numAdults !== '') {
            ddcfNumAdults = jQuery.trim(numAdults);
            if (ddcfErrorCheckingMethod === 'realtime') {
                jQuery('#ddcf_num_adults_fb').html('&#10004;');
                jQuery('#ddcf_num_children_fb').html('&#10004;');
            }
        } else {
            if (makeChanges && numAdults === '')
                jQuery("#ddcf_num_adults").val(ddcfNumAdultsDefault);
            if (ddcfErrorCheckingMethod === 'realtime') {
                jQuery('#ddcf_num_adults_fb').html('&nbsp;');
                jQuery('#ddcf_num_children_fb').html('&nbsp;');
            }
            if (ddcfPartySizeCompulsoryCheck) {
                if (andSubmit)
                    errors += 'Please select at least one adult<br />';
                bChecksOut = false;
            }
        }
    } // ddcfNumChildren
    else if (jQuery('#ddcf_num_children').length !== 0) { // element found
        var numChildren = jQuery("#ddcf_num_children").val();
        if (numChildren !== ddcfNumChildrenDefault && numChildren !== '') {
            ddcfNumChildren = jQuery.trim(numChildren);
            if (ddcfErrorCheckingMethod === 'realtime' && jQuery('#ddcf_num_adults').length === 0) {
                jQuery('#ddcf_num_children_fb').html('&#10004;');
            }
        } else {
            if (makeChanges && numChildren === '')
                jQuery("#ddcf_num_adults").val(ddcfNumChildrenDefault);
            if (ddcfErrorCheckingMethod === 'realtime' && jQuery('#ddcf_num_adults').length === 0) {
                jQuery('#ddcf_num_children_fb').html('&nbsp;');
            }
            if (ddcfPartySizeCompulsoryCheck) {
                if (andSubmit)
                    errors += 'Please select at least one child<br />';
                bChecksOut = false;
            }
        }
    }

    /* Simple Addition Captcha */
    if (jQuery('#ddcf_contact_captcha_add').length !== 0) { // element found
        ddcfCaptcha = jQuery.trim(jQuery("#ddcf_contact_captcha_add").val());
        if (ddcfCaptcha !== ddcfCaptchaDefault && ddcfCaptcha !== '') {
            var ddcfNumOne = parseInt(jQuery.trim(jQuery("#ddcf_captcha_one").html(), 10));
            var ddcfNumTwo = parseInt(jQuery.trim(jQuery("#ddcf_captcha_two").html(), 10));
            if ((ddcfNumOne + ddcfNumTwo) === parseInt(ddcfCaptcha, 10)) {
                if (ddcfErrorCheckingMethod === 'realtime')
                    jQuery('#ddcf_contact_captcha_fb').html('&#10004;').css('color', 'green');
            }
            else {
                if (ddcfErrorCheckingMethod === 'realtime')
                    jQuery('#ddcf_contact_captcha_fb').html('&#10008;').css('color', 'red');
                      
                if (andSubmit)
                    errors += 'The Captcha answer is incorrect<br />';
                bChecksOut = false;
            }
        } else {
            if (makeChanges && ddcfCaptcha === '')
                jQuery("#ddcf_contact_captcha_add").val(ddcfCaptchaDefault);
            if (ddcfErrorCheckingMethod === 'realtime')
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
                jQuery("#recaptcha_response_field").val(ddcfreCaptchaDefault);
            if (andSubmit)
                errors += 'reCaptcha not set<br />';
            bChecksOut = false;
            if (ddcfErrorCheckingMethod === 'realtime')
                jQuery('#ddcf_contact_captcha_fb').html('&nbsp;');
        } else if (jQuery("#recaptcha_response_field").val() !== ddcfreCaptchaDefault) {
            ddcfreCaptcha = jQuery.trim(jQuery("#recaptcha_response_field").val());
            if (ddcfErrorCheckingMethod === 'realtime')
                jQuery('#ddcf_contact_captcha_fb').html('&#10004;').css('color', 'green');
        } else {
            //ddcfreCaptcha = jQuery("#recaptcha_response_field").val();
            if (andSubmit)
                errors += 'reCaptcha not set<br />';
            bChecksOut = false;
            if (ddcfErrorCheckingMethod === 'realtime')
                jQuery('#ddcf_contact_captcha_fb').html('&nbsp;');
        }
    }

    /* Enable send button if form checks out and in realtime checking mode */
    if (ddcfErrorCheckingMethod === 'realtime') {
        if(bChecksOut) {
                jQuery('#ddcf_contact_send').prop('disabled', false).css('opacity', 1.0);
                jQuery('#ddcf_contact_send_fb').html('&nbsp;');

        }
        else {
                jQuery('#ddcf_contact_send').prop('disabled', true).css('opacity', 0.4);
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
            jQuery('#ddcf_error_reporting').html('');
            jQuery("#ddcf_contact_form_contents").fadeOut("fast");
            jQuery("#ddcf_throbber").css("display", "block");
            submit();
        }
    }
    if (errors.length > 0)
        jQuery('#ddcf_error_reporting').html(errors);

    return bChecksOut;
}

function adjust_for_size() {
    
    //if(!jQuery("#ddcf_contact_form_contents").css('visibility'))
    jQuery("#ddcf_throbber").css("display", "none");
    jQuery("#ddcf_contact_form_contents").css('visibility','visible').fadeIn( "fast");
    jQuery('body').css('cursor', 'auto');
    
    ddcfWidth = jQuery("#ddcf_contact_form_wrapper").width();
    var formDensity = parseInt(jQuery('#ddcf_form_density').val());
    
    jQuery("#ddcf_error_reporting").html('Form width: '+ddcfWidth+'px'); /**/
    
    /* deal with the simplest case first */
    if(formDensity===0) {
        jQuery('#ddcf_contact_form_top_right').css('display','none');
        return;
    }    

    /* start of adjustments */
    
    /* tiny screens */
    if(ddcfWidth<=250){
//                jQuery('#ddcf_span_captcha_add').css('width', '100%');
                jQuery('#ddcf_contact_captcha_fb').css('top', 'auto').css('bottom', '0px').css('right', '50%');                
    } else {
//                jQuery('#ddcf_span_captcha_add').css('width', '17.5em');
                jQuery('#ddcf_contact_captcha_fb').css('top', '47%').css('bottom', 'auto').css('right', '-1.5em');
    }

    /* small screens */
    var smallScreenLimit = 500;
    /* can be as low as 375 (small fonts), to 500 (large fonts) - dependent on theme */
    /* reluctant to go below 474, or WP2014 theme is too narrow to ever show */
    /* limiting factor is with full density rhs - ticks and crosses overlap party size */    
    switch (formDensity) {
        case 0:
            smallScreenLimit = 300;
            break;
        case 1:
            smallScreenLimit = 300;
            break;
        case 2:
            smallScreenLimit = 300;
            break;
        case 3:
            smallScreenLimit = 300;
            break;
        case 4:
            smallScreenLimit = 300;
            break;
        case 5:
            smallScreenLimit = 300;
            break;
        case 6:
            smallScreenLimit = 435;
            break;
        case 7:
            smallScreenLimit = 470;         
    }
    if(ddcfWidth<smallScreenLimit){
                /* hide date/time pickers, widen buttons etc */
                jQuery('#ddcf_button_area').css('width', '100%');
                jQuery('.ddcf_button').css('width', '100%').css('margin', '0 0 1em 0');        
                jQuery('#ddcf_checkbox_area').css('width', '100%');
                jQuery('.ddcf_span_date').css('display','none');
                jQuery('.ddc_span_datetime').css('display','none');
                jQuery('#ddcf_dates_row_division').css('display','none');
                if(formDensity===2||formDensity===3)
                    jQuery('#ddcf_contact_form_top_right').css('display','none');
    }
    else {
                jQuery('#ddcf_button_area').css('width', 'auto');
                jQuery('.ddcf_button').css('width', 'auto').css('margin', '0 0 0 0.5em');        
                jQuery('#ddcf_checkbox_area').css('width', 'auto');
                jQuery('.ddcf_span_date').css('display','inline-block');
                jQuery('.ddc_span_datetime').css('display','inline-block');
                jQuery('#ddcf_dates_row_division').css('display','table-cell');
                if(formDensity===2||formDensity===3)
                    jQuery('#ddcf_contact_form_top_right').css('display','block');
    }

    /* flip between 1 and 2 columns @ flipWidth px*/
    var flipWidth = 700; 
    switch (formDensity) {
        case 0:
            flipWidth = 300;
            break;
        case 1:
            flipWidth = 300;
            break;
        case 2:
            flipWidth = 300;
            break;
        case 3:
            flipWidth = 300;
            break;
        case 4:
            flipWidth = 300;
            break;
        case 5:
            flipWidth = 300;
            break;
        case 6:
            flipWidth = 300;
            break;
        case 7:
            flipWidth = 700;         
    }    
    if(ddcfWidth<=flipWidth) {
		jQuery('#ddcf_contact_form_top_left').css('width', '100%');
		jQuery('#ddcf_contact_form_top_right').css('width', '100%').css('float', 'left');//.css('margin-top', '-1em');
                jQuery('.ddcf_span_date').css('float','left').css('margin-left','1.0em');
                jQuery('.ddc_span_datetime').css('float','left').css('margin-left','1.0em');
                jQuery('#ddcf_google_recaptcha').css('float', 'none');
    }
    else { // width > flipWidth                
                /* tweak top right and left section width ratio */                
                var widthLeft = '33%';var widthRight = '67%';
                if(formDensity===0) { widthLeft = '100%'; widthRight = '0%'; }          /* nothing = 0 */
                else if(formDensity===1) { widthLeft = '70%'; widthRight = '30%'; }     /* party size = 1 */
                else if(formDensity===2) { widthLeft = '60%'; widthRight = '40%'; }     /* booking dates = 2 */
                else if(formDensity===3) { widthLeft = '55%'; widthRight = '45%'; }     /* booking datetimes = 3 */
                else if(formDensity===4) { widthLeft = '50%'; widthRight = '50%'; }     /* simple additon captcha = 4 */
                else if(formDensity===5) { widthLeft = '45%'; widthRight = '55%'; }     /* google reCaptcha = 5 */
                else if(formDensity===6) { widthLeft = '45%'; widthRight = '55%'; }     /* booking dates & party size = 6 */
                else { widthLeft = '30%'; widthRight = '70%'; }                         /* booking datetimes & party size = 7 */
                                
		jQuery('#ddcf_contact_form_top_left').css('width', widthLeft);
		jQuery('#ddcf_contact_form_top_right').css('width', widthRight).css('float', 'right');//.css('margin-top', '0em');
                
                /* tidy margins for new layout */
                jQuery('.ddcf_span_date').css('float','none').css('margin-left','0em');
                jQuery('.ddc_span_datetime').css('float','none').css('margin-left','0em');
                
                /* make the reCaptcha look a bit tidier */
                if(ddcfWidth<750&&jQuery('#ddcf_google_recaptcha').length>0) jQuery('#ddcf_google_recaptcha').css('float', 'right');
                else jQuery('#ddcf_google_recaptcha').css('float', 'none');
    }   
    
    /* tidy up form elements */
    
    /* vertically align FB divs (ticks and crosses) */
    var fBOffset = jQuery('#ddcf_contact_name').height()/2;
    //jQuery("#ddcf_contact_name_fb").css('margin-top', -fBOffset );
    jQuery("#ddcf_contact_name_fb").css('margin-top', -fBOffset );
    jQuery("#ddcf_contact_email_fb").css('margin-top', -fBOffset );
    jQuery("#ddcf_contact_subject_fb").css('margin-top', -fBOffset );
    jQuery("#ddcf_question_one_fb").css('margin-top', -fBOffset );
    jQuery("#ddcf_question_two_fb").css('margin-top', -fBOffset );
    jQuery("#ddcf_contact_message_fb").css('margin-top', -fBOffset );
    
    /* do we have additional fields? */
    var usingDatePicker = jQuery('.ddcf_date_picker').length;
    var usingDateTimePicker = jQuery('.ddcf_datetime_picker').length;
    var usingDropDowns = jQuery('.ddcf_dropdown').length;
    var usingSimpleAddition = jQuery('#ddcf_span_captcha_add').length;
    
    /* gap between label and control, px */
    var controlMargin = 10; 
    
    if(usingDatePicker||usingDateTimePicker) {
        /* set the width of the containers */
        var datePickerWidth = jQuery('#ddcf_arrival_date').outerWidth();
        var datePickerLabelWidth = jQuery('label[for="ddcf_arrival_date"]').outerWidth();
        if(jQuery('#ddcf_departure_date').outerWidth()>datePickerWidth)
            datePickerWidth = jQuery('#ddcf_departure_date').outerWidth();
        if(jQuery('label[for="ddcf_departure_date"]').width()>datePickerLabelWidth)
            datePickerLabelWidth = jQuery('label[for="ddcf_departure_date"]').outerWidth();     
        if(usingDatePicker)     jQuery('.ddcf_span_date').width( datePickerWidth + datePickerLabelWidth  + controlMargin );
        if(usingDateTimePicker) jQuery('.ddc_span_datetime').width( datePickerWidth + datePickerLabelWidth  + controlMargin );
    }
    
    
    if(usingDropDowns){
        /* copy the text input's styling to the dropdown selects, as often the theme doesn't seem to style them explicitly */
        jQuery('.ddcf_dropdown').css('border-style' , jQuery('#ddcf_contact_name').css('border-top-style'));
        jQuery('.ddcf_dropdown').css('border-color' , jQuery('#ddcf_contact_name').css('border-top-color'));
        jQuery('.ddcf_dropdown').css('border-width' , jQuery('#ddcf_contact_message').css('border-top-width'));
        jQuery('.ddcf_dropdown').css('border-radius' , jQuery('#ddcf_contact_name').css('border-top-left-radius'));
        jQuery('.ddcf_dropdown').css('-webkit-transition' , jQuery('#ddcf_contact_name').css('-webkit-transition'));
        jQuery('.ddcf_dropdown').css('-moz-transition' , jQuery('#ddcf_contact_name').css('-moz-transition'));
        jQuery('.ddcf_dropdown').css('-o-transition' , jQuery('#ddcf_contact_name').css('-o-transition'));
        jQuery('.ddcf_dropdown').css('transition' , jQuery('#ddcf_contact_name').css('transition'));
        jQuery('.ddcf_dropdown').css('color' , jQuery('#ddcf_contact_name').css('color'));
        //jQuery('.ddcf_dropdown').css('height' , jQuery('#ddcf_contact_name').outerHeight());
        jQuery('.ddcf_dropdown').css('background-color' , jQuery('#ddcf_contact_name').css('background-color'));
        jQuery('.ddcf_dropdown').css('-webkit-appearance' , jQuery('#ddcf_contact_name').css('-webkit-appearance'));
        jQuery('.ddcf_dropdown').css('-moz-appearance' , jQuery('#ddcf_contact_name').css('-moz-appearance'));
        jQuery('.ddcf_dropdown').css('appearance' , jQuery('#ddcf_contact_name').css('appearance'));
        jQuery('.ddcf_dropdown').css('-o-appearance' , jQuery('#ddcf_contact_name').css('-o-appearance'));
        jQuery('.ddcf_dropdown').css('margin-top' , '0px');
        
        /* set the width of the container */
        var dropdownWidth = jQuery('#ddcf_num_adults').outerWidth();
        var dropdownLabelWidth = jQuery('label[for="ddcf_num_adults"]').outerWidth();
        if(jQuery('#ddcf_num_children').outerWidth()>dropdownWidth)
            dropdownWidth = jQuery('#ddcf_num_children').outerWidth();
        if(jQuery('label[for="ddcf_num_children"]').width()>dropdownLabelWidth)
            dropdownLabelWidth = jQuery('label[for="ddcf_num_children"]').outerWidth();
        jQuery('.ddcf_table_span_dropdown').css('width', dropdownLabelWidth + dropdownWidth + controlMargin );
        jQuery('#ddcf_dropdowns_align').css('width', dropdownLabelWidth + dropdownWidth + controlMargin )
                                       .css('margin', 'auto')
                                       .css('float','none');      
    }
    
    if(usingSimpleAddition) {
        /* set the width of the containers */
        var captchaTextWidth = jQuery('#ddcf_captcha_question').outerWidth(); 
        var captchaInputWidth = jQuery('#ddcf_contact_captcha_add').outerWidth(); 
        jQuery('#ddcf_span_captcha_add').width( captchaTextWidth + captchaInputWidth  + controlMargin );
    }    
    
    if((usingDatePicker||usingDateTimePicker)&&usingDropDowns) {
        /* need to distribute the available space between the two */
        var reqdDateSpace = datePickerWidth + datePickerLabelWidth + controlMargin;
        var reqdDropdownSpace = dropdownLabelWidth + dropdownWidth + controlMargin;
        var ratio = reqdDateSpace / (reqdDateSpace + reqdDropdownSpace);
        var availableSpace = jQuery('#ddcf_contact_form_top_right').outerWidth();
        var datesWidth = ratio * availableSpace;
        var dropdownsWidth = (1 - ratio) * availableSpace;
        if(ddcfWidth<smallScreenLimit){
            jQuery('#ddcf_dates_details_row_division').width('0em');
            jQuery('#ddcf_party_size_details_row_division').width('100%');
        } else {
            jQuery('#ddcf_dates_details_row_division').width(datesWidth-1);
            jQuery('#ddcf_party_size_details_row_division').width(dropdownsWidth-1);
        }
    }
    
    /* set top right section's vertical alignment  */
    var topLeftHeight = jQuery("#ddcf_contact_form_top_left").height();
    var topRightHeight = jQuery("#ddcf_contact_form_top_right").height();
    if(topRightHeight<topLeftHeight) topRightHeight = topLeftHeight;
    jQuery("#ddcf_contact_form_top_right").css('height', topRightHeight);
    var topRightOffset = topRightHeight/2.0;
    jQuery("#ddcf_details_rows_container").css('margin-top', -topRightOffset);  
    
    /* set the rhs form elements container vertical alignment */
    /* first a wee cludge to account for a miscounted bottom margin of 1em on the lhs  */
    var div = jQuery('<div style="width:1em;margin:0 !important;padding:0 !important"></div>').appendTo('body');
    var marginError = div.width(); 
    //var marginError = 0;
    div.remove();    
    var middleBitOffset = ((topRightHeight-jQuery("#ddcf_middle_bit").height())-marginError)/2;
    jQuery("#ddcf_middle_bit").css('margin-top', middleBitOffset );      
}


jQuery(document).ready(function ($) {
    
        jQuery('body').css('cursor', 'wait');

	// tweak on resize
	jQuery(window).resize(function() {adjust_for_size();});

	// Get the default values in case of form reset
	ddcfContactNameDefault = jQuery.trim(jQuery('#ddcf_contact_name').val());
	ddcfContactEmailDefault = jQuery.trim(jQuery('#ddcf_contact_email').val());
	ddcfContactSubjectDefault = jQuery.trim(jQuery('#ddcf_contact_subject').val());
	ddcfContactMessageDefault = jQuery.trim(jQuery('#ddcf_contact_message').val());
	ddcfQuestionOneDefault = jQuery.trim(jQuery('#ddcf_question_one').val());
	ddcfQuestionTwoDefault = jQuery.trim(jQuery('#ddcf_question_two').val());
	ddcfArrivalDateDefault = jQuery.trim(jQuery('#ddcf_arrival_date').val());
	ddcfDepartureDateDefault = jQuery.trim(jQuery('#ddcf_departure_date').val());
	ddcfNumAdultsDefault = jQuery.trim(jQuery('#ddcf_num_adults').val());
	ddcfNumChildrenDefault = jQuery.trim(jQuery('#ddcf_num_children').val());
	ddcfReceiveUpdatesDefault = jQuery.trim(jQuery('#ddcf_newsletter_signup').is(':checked'));
	ddcfCaptchaDefault = jQuery.trim(jQuery('#ddcf_contact_captcha_add').val());
	ddcfreCaptchaDefault = jQuery.trim(jQuery('#recaptcha_response_field').val());
	ddcfErrorCheckingMethod = jQuery.trim(jQuery('#ddcf_error_checking_method').val());
	ddcfQuestionsCompulsoryCheck = jQuery.trim(jQuery('#ddcf_questions_compulsory_check').val());
	ddcfPartySizeCompulsoryCheck = jQuery.trim(jQuery('#ddcf_party_size_compulsory_check').val());
	ddcfDatesCompulsoryCheck = jQuery.trim(jQuery('#ddcf_dates_compulsory_check').val());

	// focus and unfocus handling:

	// Contact name text field
	jQuery('#ddcf_contact_name').focusout(function(){checkForm(false,true);})
							   .focus(function() {
									if(jQuery("#ddcf_contact_name").val()===ddcfContactNameDefault) jQuery("#ddcf_contact_name").val("");});

	// email text field
	jQuery('#ddcf_contact_email').focusout(function(){checkForm(false,true);})
							    .focus(function() {
									if(jQuery("#ddcf_contact_email").val()===ddcfContactEmailDefault) jQuery("#ddcf_contact_email").val("");});

	// subject text field
	jQuery('#ddcf_contact_subject').focusout(function(){checkForm(false,true);})
							      .focus(function() {
										if(jQuery("#ddcf_contact_subject").val()===ddcfContactSubjectDefault) jQuery("#ddcf_contact_subject").val("");});

	// message text field
	jQuery('#ddcf_contact_message').focusout(function(){checkForm(false,true);})
							   .focus(function() {
										if(jQuery("#ddcf_contact_message").val()===ddcfContactMessageDefault) jQuery("#ddcf_contact_message").val(""); });


	// question one field
	jQuery('#ddcf_question_one').focusout(function(){checkForm(false,true);})
							   	.focus(function() {
										if(jQuery("#ddcf_question_one").val()===ddcfQuestionOneDefault) jQuery("#ddcf_question_one").val(""); });

	// question two field
	jQuery('#ddcf_question_two').focusout(function(){checkForm(false,true);})
							   .focus(function() {
									if(jQuery("#ddcf_question_two").val()===ddcfQuestionTwoDefault) jQuery("#ddcf_question_two").val(""); });


	// contact form booking functionality */
	jQuery(".ddcf_date_picker").datepicker({ timeFormat: "HH:mm", dateFormat : "yy-mm-dd", minDate: 1 });
	jQuery(".ddcf_datetime_picker").datetimepicker({ timeFormat: "HH:mm", dateFormat : "yy-mm-dd", minDate: 1 });
	jQuery("#ddcf_arrival_date").focusout(function(){checkForm(false,true);})
								.focus(function() {
									if(jQuery("#ddcf_arrival_date").val()===ddcfArrivalDateDefault) jQuery("#ddcf_arrival_date").val(""); });
	jQuery("#ddcf_departure_date").focusout(function(){checkForm(false,true);})
									.focus(function() {
									if(jQuery("#ddcf_departure_date").val()===ddcfDepartureDateDefault) jQuery("#ddcf_departure_date").val(""); });

	// bind unfocus event for num adults / children
	jQuery('#ddcf_num_adults').focusout(function(){checkForm(false,true);});
	jQuery('#ddcf_num_children').focusout(function(){checkForm(false,true);});


	// Reset Button
	jQuery('#ddcf_contact_reset')
	                        .click(function( event ) {
								event.preventDefault();
								resetForm();
							});
	// Submit Button
        if(ddcfErrorCheckingMethod==='onsubmit') {
            jQuery('#ddcf_contact_send').prop('disabled', false)
                                .click(function( event ) {
                                        event.preventDefault();
                                        checkForm(true,true);
                                });
        }
        else { // assume 'realtime'
            jQuery('#ddcf_contact_send').prop('disabled', true)
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

	// show realtime error checking (ticks and crosses)?
	if(jQuery('#ddcf_error_checking_method').val()==='realtime') {
		jQuery('.ddcf_contact_input_verify').css('display', 'inline');
	}
	else {
		jQuery('.ddcf_contact_input_verify').css('display', 'none');
	}

	// set up captcha etc (js / jQuery via WP / Ajax / php )
	initialise_session();

        // adjust screen elements after given time to allow for setting up of captcha (multiple of 500mS)
        ddcfInitialTimer = 2500;

	// start monitoring input
	setInterval(function(){checkForm(false,false);},500);
});