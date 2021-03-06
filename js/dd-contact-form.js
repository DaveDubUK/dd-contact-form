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
var ddcfContactName = '';
var ddcfContactEmail = '';
var ddcfContactSubject = '';
var ddcfContactMessage = '';
var ddcfStartDate = '';
var ddcfEndDate = '';
var ddcfDropdownOne = '';
var ddcfDropdownTwo = '';
var ddcfQuestionOne = '';
var ddcfQuestionTwo = '';
var ddcfReceiveUpdates = '';
var ddcfCaptcha = '';
var ddcfreCaptcha = '';
// defaults
var ddcfContactNameDefault;
var ddcfContactEmailDefault;
var ddcfContactSubjectDefault;
var ddcfContactMessageDefault;
var ddcfStartDateDefault;
var ddcfEndDateDefault;
var ddcfQuestionOneDefault;
var ddcfQuestionTwoDefault;
var ddcfCaptchaDefault;
var ddcfreCaptchaDefault;
// resizing vars
var ddcfWidth;
var ddcfInitialTimer;
var ddcfErrorCheckingMethod;
var ddcfQuestionsCompulsoryCheck;
var ddcfDropdownsCompulsoryCheck;
var ddcfDatesCompulsoryCheck;

function ddcfResetForm() {
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
	ddcfStartDate  = '';
	ddcfEndDate  = '';
	jQuery("#ddcf_start_date").val(ddcfStartDateDefault);
	jQuery("#ddcf_end_date").val(ddcfEndDateDefault);
	jQuery('#ddcf_start_date_fb').html('&nbsp;');
	jQuery('#ddcf_end_date_fb').html('&nbsp;');

	// reset captcha answers (but not questions)
	ddcfCaptcha = '';
	jQuery("#ddcf_contact_captcha_add").val(ddcfCaptchaDefault);
	jQuery('#ddcf_contact_captcha_fb').html('&nbsp;');
	ddcfreCaptcha = '';
	jQuery("#recaptcha_response_field").val(ddcfreCaptchaDefault);

    // reset Rec. Updates option
	jQuery("#ddcf_newsletter_signup").prop('checked', true);

	// reset the person count dropdowns
	ddcfDropdownOne = '';
	jQuery("#ddcf_dropdown_one").val('');
	jQuery('#ddcf_dropdown_one_fb').html('&nbsp;');
	ddcfDropdownTwo = '';
	jQuery("#ddcf_dropdown_two").val('');
	jQuery('#ddcf_dropdown_two_fb').html('&nbsp;');

	// clear the user feedback div contents
	jQuery('#ddcf_error_reporting').html('');

        // reset important hidden fields
        //jQuery("#ddcf_init_nonce").val('');
        //jQuery("#ddcf_submit_nonce").val('');
        //jQuery("#ddcf_session_initialised").val('uninitialised');
        //jQuery("#ddcf_form_density").val(0);
}

function ddcfValidateEmail(email)
{
	// from http://www.paulund.co.uk/regular-expression-to-validate-email-address
	var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.) {2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return emailReg.test(email);
}


function ddcfCheckForm(andSubmit, makeChanges)
{
    var bChecksOut = true;
    var errors = '';

    /* just give a little settling down time before initial resizing */
    if (ddcfInitialTimer > 0) {
        ddcfInitialTimer = ddcfInitialTimer - 500;

        if (ddcfInitialTimer <= 0) ddcfMakePretty();
    }
    
    /* Check out each form element in turn. Report any problems */

    /* Name */
    var szName = jQuery("#ddcf_contact_name").val(); 
    if (szName !== ddcfContactNameDefault && szName !== '') {
        ddcfContactName = jQuery.trim(szName);
        if (ddcfErrorCheckingMethod === 'realtime'){
            
        jQuery('#ddcf_contact_name_fb').html('&#10004;&#xFE0E');}
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

        if (!ddcfValidateEmail(szEmailEntry)) {

            if (ddcfErrorCheckingMethod === 'realtime')
                jQuery('#ddcf_contact_email_fb').html('<p style="color:red;">&#10008;&#xFE0E</p>');

            if (andSubmit)
                errors += 'Email format not recognised<br />';
            bChecksOut = false;
        } else {
            ddcfContactEmail = szEmailEntry;

            if (makeChanges)
                jQuery("#ddcf_contact_email").val(ddcfContactEmail);

            if (ddcfErrorCheckingMethod === 'realtime')
                jQuery('#ddcf_contact_email_fb').html('<p style="color:green;">&#10004;&#xFE0E</p>');
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
            jQuery('#ddcf_contact_subject_fb').html('&#10004;&#xFE0E');
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
            jQuery('#ddcf_contact_message_fb').html('&#10004;&#xFE0E');
    } else {
        
        if (makeChanges && szMessage === '')
            jQuery("#ddcf_contact_message").val(ddcfContactMessageDefault);
        
        if (ddcfErrorCheckingMethod === 'realtime')
            jQuery('#ddcf_contact_message_fb').html('&nbsp;');
        
        if (andSubmit) errors += 'Please write a message<br />';
        bChecksOut = false;
    }

    /* Datepickers (only verify if displayed - i.e. width > 400px)*/
    ddcfWidth = jQuery("#ddcf_contact_form_contents").width();
    
    if (ddcfWidth > 400) {
		
        if (jQuery('#ddcf_start_date').length !== 0) { // element found
			ddcfStartDate = jQuery("#ddcf_start_date").val();
			
            if (ddcfStartDate !== ddcfStartDateDefault && ddcfStartDate !== '') {
				
                if (ddcfErrorCheckingMethod === 'realtime')
					jQuery('#ddcf_start_date_fb').html('&#10004;&#xFE0E');
			} else {
				
                if (makeChanges && ddcfStartDate === '')
					jQuery("#ddcf_start_date").val(ddcfStartDateDefault);
				
                if (ddcfErrorCheckingMethod === 'realtime')
					jQuery('#ddcf_start_date_fb').html('&nbsp;');
				
                if (ddcfDatesCompulsoryCheck === 'ddcf_dates_compulsory_check') {
					
                    if (andSubmit) errors += 'Please enter a start date<br />';
					bChecksOut = false;
				}
			}
		}
        
		if (jQuery('#ddcf_end_date').length !== 0) { // element found
			ddcfEndDate = jQuery("#ddcf_end_date").val();
			
            if (ddcfEndDate !== ddcfEndDateDefault && ddcfEndDate !== '') {
				
                if (ddcfErrorCheckingMethod === 'realtime')
					jQuery('#ddcf_end_date_fb').html('&#10004;&#xFE0E');
			} else {
				
                if (makeChanges && ddcfEndDate === '')
					jQuery("#ddcf_end_date").val(ddcfEndDateDefault);
				
                if (ddcfErrorCheckingMethod === 'realtime')
					jQuery('#ddcf_end_date_fb').html('&nbsp;');
				
                if (ddcfDatesCompulsoryCheck === 'ddcf_dates_compulsory_check') {
					
                    if (andSubmit) errors += 'Please enter an end date<br />';
					bChecksOut = false;
				}
			}
		}
	}

    /* Numbers dropdowns */
    if (jQuery('#ddcf_dropdown_one').length !== 0) { // element found
        var dropdownOne = jQuery("#ddcf_dropdown_one").val();
        
        if (dropdownOne != null) {
            ddcfDropdownOne = jQuery.trim(dropdownOne);
            
            if (ddcfErrorCheckingMethod === 'realtime') jQuery('#ddcf_dropdown_one_fb').html('&#10004;&#xFE0E');
        } else {
            
            //if (makeChanges && dropdownOne === '') jQuery("#ddcf_dropdown_one").val(ddcfDropdownOneDefault);
            
            if (ddcfErrorCheckingMethod === 'realtime') jQuery('#ddcf_dropdown_one_fb').html('&nbsp;');
            
            if (ddcfDropdownsCompulsoryCheck === 'ddcf_numbers_compulsory_check') {
                
                if (andSubmit) errors += 'Please make a selection from the '+jQuery('#ddcf_extra_dropdown_one_label').val()+' dropdown<br />';
                bChecksOut = false;
            }
        }
    }
    
    if (jQuery('#ddcf_dropdown_two').length !== 0) { // element found
        var dropdownTwo = jQuery("#ddcf_dropdown_two").val();
        
        if (dropdownTwo != null) {
            ddcfDropdownTwo = jQuery.trim(dropdownTwo);
            
            if (ddcfErrorCheckingMethod === 'realtime') jQuery('#ddcf_dropdown_two_fb').html('&#10004;&#xFE0E');
        } else {
            
            //if (makeChanges && dropdownTwo === '') jQuery("#ddcf_dropdown_one").val(ddcfDropdownTwoDefault);
            
            if (ddcfErrorCheckingMethod === 'realtime') jQuery('#ddcf_dropdown_two_fb').html('&nbsp;');

            if (ddcfDropdownsCompulsoryCheck) {
                
                if (andSubmit) errors += 'Please make a selection from the '+jQuery('#ddcf_extra_dropdown_one_label').val()+' dropdown<br />';
                bChecksOut = false;
            }
        }
    }

    /* Question One */
    if (jQuery('#ddcf_question_one').length !== 0) { // element found
        var szQuestionOne = jQuery("#ddcf_question_one").val();
        
        if (szQuestionOne !== ddcfQuestionOneDefault && szQuestionOne !== '') {
            ddcfQuestionOne = jQuery.trim(szQuestionOne);
            
            if (ddcfErrorCheckingMethod === 'realtime')
                jQuery('#ddcf_question_one_fb').html('&#10004;&#xFE0E');
        } else {
            
            if (makeChanges && szQuestionOne === '')
                jQuery("#ddcf_question_one").val(ddcfQuestionOneDefault);
            
            if (ddcfErrorCheckingMethod === 'realtime')
                jQuery('#ddcf_question_one_fb').html('&nbsp;');
            
            if (ddcfQuestionsCompulsoryCheck) {
                
                if (andSubmit) errors += 'Please answer the question: ' + ddcfQuestionOneDefault + '<br />';
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
                jQuery('#ddcf_question_two_fb').html('&#10004;&#xFE0E');
        } else {
            
            if (makeChanges && szQuestionTwo === '')
                jQuery("#ddcf_question_two").val(ddcfQuestionTwoDefault);
            
            if (ddcfErrorCheckingMethod === 'realtime')
                jQuery('#ddcf_question_two_fb').html('&nbsp;');
            
            if (ddcfQuestionsCompulsoryCheck) {
                
                if (andSubmit) errors += 'Please answer the question: ' + ddcfQuestionTwoDefault + '<br />';
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
                    jQuery('#ddcf_contact_captcha_fb').html('&#10004;&#xFE0E').css('color', 'green');
            } else {
                
                if (ddcfErrorCheckingMethod === 'realtime')
                    jQuery('#ddcf_contact_captcha_fb').html('&#10008;&#xFE0E').css('color', 'red');

                if (andSubmit) errors += 'The Captcha answer is incorrect<br />';
                bChecksOut = false;
            }
        } else {
            
            if (makeChanges && ddcfCaptcha === '')
                jQuery("#ddcf_contact_captcha_add").val(ddcfCaptchaDefault);
            
            if (ddcfErrorCheckingMethod === 'realtime')
                jQuery('#ddcf_contact_captcha_fb').html('&nbsp;');
            
            if (andSubmit) errors += 'Please answer the captcha question<br />';
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
            
            if (andSubmit)
                errors += 'reCaptcha not set<br />';
            bChecksOut = false;
            
            if (ddcfErrorCheckingMethod === 'realtime')
                jQuery('#ddcf_contact_captcha_fb').html('&nbsp;');
        }
    }

    /* Enable send button if form checks out and in realtime checking mode */
    if (ddcfErrorCheckingMethod === 'realtime') {
        
        if (bChecksOut) {
                jQuery('#ddcf_contact_send').prop('disabled', false).css('opacity', 1.0);
                jQuery('#ddcf_contact_send_fb').html('&nbsp;');

        } else {
                jQuery('#ddcf_contact_send').prop('disabled', true).css('opacity', 0.4);
                jQuery('#ddcf_contact_send_fb').html('&nbsp;');

        }
    }

    if (andSubmit) {

        if ((jQuery('#ddcf_start_date').length !== 0) && (jQuery('#ddcf_end_date').length !== 0)) { // element found
            // check dates out
            var datepickerBegin = jQuery("#ddcf_start_date").datepicker('getDate');
            var datepickerEnd = jQuery("#ddcf_end_date").datepicker('getDate');

            var difference = (datepickerEnd - datepickerBegin) / (86400000 * 1);
            
            if (difference < 0) {
                errors += 'The end date must come after the start date<br />';
                bChecksOut = false;
            } else if (difference === 0) {
                //errors+= 'The booking dates are the same - please set the booking date<br />';
                //bChecksOut = false;
            }
        }
        
        if (bChecksOut) {
            jQuery('#ddcf_error_reporting').html('');
            jQuery("#ddcf_contact_form_contents").hide();
            jQuery("#ddcf_throbber").css("display", "block");
            ddcfSubmit();
        }
    }
    
    if (errors.length > 0) jQuery('#ddcf_error_reporting').html(errors+'<br />');
    return bChecksOut;
}

function ddcfMakePretty() {

    /* if not by now, should be... */
    jQuery("#ddcf_throbber").css("display", "none");
    jQuery("#ddcf_contact_form_contents").css('visibility','visible').fadeIn("slow");
    jQuery('body').css('cursor', 'auto');

    /* get the width of the actual available space */
    ddcfWidth = jQuery("#ddcf_contact_form_wrapper").width();
    var formDensity = parseInt(jQuery('#ddcf_form_density').val());
    if (formDensity === 0) jQuery('#ddcf_contact_form_top_right').css('display','none');

    /* tiny screens */
    if (ddcfWidth <= 250)
        /* just nudge the Simple Add captcha feedback div out the way */
        jQuery('#ddcf_contact_captcha_fb').css('top', 'auto').css('bottom', '-3px').css('right', '50%');
    else
        jQuery('#ddcf_contact_captcha_fb').css('top', '47%').css('bottom', 'auto').css('right', '-2em');

    /* small screens */
    var smallScreenLimit = 470;
    switch (formDensity) {
        case 0:smallScreenLimit = 320; break;
        case 1:smallScreenLimit = 320; break;
        case 2:smallScreenLimit = 355; break;
        case 3:smallScreenLimit = 320; break;
        case 4:smallScreenLimit = 360; break;
        case 5:smallScreenLimit = 320; break;
        case 6:smallScreenLimit = 435; break;
        case 7:smallScreenLimit = 470; break;
    }

    if (ddcfWidth < smallScreenLimit) {
        /* hide date/time pickers, widen buttons etc */
        jQuery('#ddcf_button_area').css('width', '100%').css('height', '100px').css('float', 'none');
        jQuery('.ddcf_button').css('width', '100%').css('margin', '0 0 1em 0');
        jQuery('#ddcf_checkbox_area').css('width', '100%');
        jQuery('.ddcf_span_date').css('display','none');
        jQuery('.ddc_span_datetime').css('display','none');
        jQuery('#ddcf_dates_row_division').css('display','none');
        
        if (formDensity===2||formDensity===4)
            jQuery('#ddcf_contact_form_top_right').css('display','none');
    } else {
        jQuery('#ddcf_button_area').css('width', 'auto').css('height', 'auto').css('float', 'right');
        jQuery('.ddcf_button').css('width', 'auto').css('margin', '0 0 0 0.5em');
        jQuery('#ddcf_checkbox_area').css('width', 'auto');
        jQuery('.ddcf_span_date').css('display','inline-block');
        jQuery('.ddc_span_datetime').css('display','inline-block');
        jQuery('#ddcf_dates_row_division').css('display','table-cell');
        
        if (formDensity===2||formDensity===4)
            jQuery('#ddcf_contact_form_top_right').css('display','block');
    }

    /* flip between 1 and 2 columns @ flipWidth px*/
    var flipWidth = 700;
    switch (formDensity) {
        case 0: flipWidth = 0; break;
        case 1: flipWidth = 450; break;
        case 2: flipWidth = 550; break;
        case 3: flipWidth = 600; break;
        case 4: flipWidth = 490; break;
        case 5: flipWidth = 605; break;
        case 6: flipWidth = 700; break;
        case 7: flipWidth = 700; break;
    }

    if (ddcfWidth <= flipWidth) {
		jQuery('#ddcf_contact_form_top_left').css('width', '100%');
		jQuery('#ddcf_contact_form_top_right').css('width', '100%').css('float', 'left');//.css('margin-top', '-1em');
		jQuery('#ddcf_dates_container').css('float','left');
		jQuery('.ddcf_span_date').css('float','left').css('margin-left','0.5em');
		jQuery('.ddc_span_datetime').css('float','left').css('margin-left','1.0em');
		//jQuery('#ddcf_google_recaptcha').css('float', 'none').css('margin', 'auto auto 1.0em auto');
		jQuery('#ddcf_captcha_add').css('margin-bottom', '1.0em');
    } else { // width > flipWidth
		/* tweak top right and left section width ratio */
		var widthLeft = '33%';var widthRight = '67%';
		var usingReCaptcha = jQuery('#ddcf_google_recaptcha').length;

		if (formDensity === 0) { widthLeft = '100%'; widthRight = '0%'; }          /* nothing = 0 */
		else if (formDensity === 1) { widthLeft = '60%'; widthRight = '40%'; }     /* party size = 1 */
		else if (formDensity === 2) { widthLeft = '50%'; widthRight = '50%'; }     /* booking dates = 2 */
		else if (formDensity === 3) { widthLeft = '50%'; widthRight = '50%'; }     /* simple addition captcha = 3 */
		else if (formDensity === 4) { widthLeft = '42%'; widthRight = '58%'; }     /* booking datetimes = 4 */
		else if (formDensity === 5) { widthLeft = '45%'; widthRight = '55%'; }     /* google reCaptcha = 5 */
		else if (formDensity === 6) { widthLeft = '45%'; widthRight = '55%'; }     /* booking dates & party size = 6 */
		else { widthLeft = '30%'; widthRight = '70%'; }                         /* booking datetimes & party size = 7 */

		jQuery('#ddcf_contact_form_top_left').css('width', widthLeft);
		jQuery('#ddcf_contact_form_top_right').css('width', widthRight).css('float', 'right');//.css('margin-top', '0em');

		/* tidy margins for centered layout */
		jQuery('#ddcf_dates_container').css('float','none');
		jQuery('.ddcf_span_date').css('float','none').css('margin-left','0em');
		jQuery('.ddc_span_datetime').css('float','none').css('margin-left','0em');

		/* don't double up the margin */
		jQuery('#ddcf_captcha_add').css('margin-bottom', '0px');

		/* make the reCaptcha look a bit tidier
		if (ddcfWidth < 750 && jQuery('#ddcf_google_recaptcha').length > 0 && formDensity === 5) jQuery('#ddcf_google_recaptcha').css('float', 'right').css('margin', '0');
		else jQuery('#ddcf_google_recaptcha').css('float', 'none').css('margin', 'auto');*/
    }

    /* tidy up form elements */

    /* do we have additional fields we need to consider now? */
    var usingDatePicker = jQuery('.ddcf_date_picker').length;
    var usingDateTimePicker = jQuery('.ddcf_datetime_picker').length;
    var usingDropDowns = jQuery('.ddcf_dropdown').length;
    var usingSimpleAddition = jQuery('#ddcf_captcha_add').length;

    /* gap between label and control (px) */
    var controlMargin = 16;

    /* get our target values */
    var borderWidthTop = jQuery('#ddcf_contact_message').css('border-top-width');
    if (!borderWidthTop) borderWidthTop = '0px';
    var borderWidthBottom = jQuery('#ddcf_contact_message').css('border-bottom-width');
    if (!borderWidthBottom) borderWidthBottom = '0px';
    var paddingTop = jQuery('#ddcf_contact_name').css('padding-top');
    if (!paddingTop) paddingTop = '0px';
    var paddingBottom = jQuery('#ddcf_contact_name').css('padding-bottom');
    if (!paddingBottom) paddingBottom = '0px';
    var inputHeight = jQuery('#ddcf_contact_name').height();
    if (!inputHeight) inputHeight = '0px';

    /* calculate our standard line height */
    var token = 'px';
    var lineHeight = inputHeight +
                     parseInt(borderWidthTop.replace(token,'')) +
                     parseInt(borderWidthBottom.replace(token,'')) +
                     parseInt(paddingTop.replace(token,'')) +
                     parseInt(paddingBottom.replace(token,''));

    /* vertically align FB divs (ticks and crosses) */
    var fBOffset = jQuery('#ddcf_contact_name_fb').innerHeight()/2;
    jQuery("#ddcf_contact_name_fb").css('margin-top', -fBOffset);
    jQuery("#ddcf_contact_email_fb").css('margin-top', -fBOffset);
    jQuery("#ddcf_contact_subject_fb").css('margin-top', -fBOffset);
    jQuery("#ddcf_question_one_fb").css('margin-top', -fBOffset);
    jQuery("#ddcf_question_two_fb").css('margin-top', -fBOffset);
    jQuery("#ddcf_contact_message_fb").css('margin-top', (fBOffset)/2);

    /* copy the text input's font and padding to the message box, as often the theme doesn't seem to style textareas explicitly */
    jQuery('#ddcf_contact_message').css('font-family' , jQuery('#ddcf_contact_name').css('font-family'));
    jQuery('#ddcf_contact_message').css('font-size' , jQuery('#ddcf_contact_name').css('font-size'));
    jQuery('#ddcf_contact_message').css('font-color' , jQuery('#ddcf_contact_name').css('font-color'));
    jQuery('#ddcf_contact_message').css('padding-left' , jQuery('#ddcf_contact_name').css('padding-left'));
    jQuery('#ddcf_contact_message').css('padding-top' , jQuery('#ddcf_contact_name').css('padding-top'));

    jQuery('.ddcf_label').css('line-height', lineHeight + 'px');

    if (usingDatePicker || usingDateTimePicker) {
        /* set the width of the containers */
        var datePickerWidth = jQuery('#ddcf_start_date').outerWidth();
        var datePickerLabelWidth = jQuery('label[for="ddcf_start_date"]').outerWidth();
        
        if (jQuery('#ddcf_end_date').outerWidth()>datePickerWidth)
            datePickerWidth = jQuery('#ddcf_end_date').outerWidth();
        
        if (jQuery('label[for="ddcf_end_date"]').width()>datePickerLabelWidth)
            datePickerLabelWidth = jQuery('label[for="ddcf_end_date"]').outerWidth();
        jQuery('#ddcf_dates_container').width(datePickerWidth + datePickerLabelWidth  + controlMargin);
        
        if (usingDatePicker)
            jQuery('.ddcf_span_date').width(datePickerWidth + datePickerLabelWidth  + controlMargin).css('height', lineHeight);

        if (usingDateTimePicker) 
            jQuery('.ddc_span_datetime').width(datePickerWidth + datePickerLabelWidth  + controlMargin).css('height' , lineHeight);

        jQuery('#ddcf_start_date').css('height' , lineHeight).css('margin-bottom', jQuery('#ddcf_contact_name').css('margin-bottom'))
        jQuery('#ddcf_end_date').css('height' , lineHeight);
    }

    if (usingDropDowns) {
        /* copy the text input's styling to the dropdown selects, as often the theme doesn't seem to style them explicitly */
        jQuery('.ddcf_dropdown').css('border-width' , borderWidthTop);
        jQuery('.ddcf_dropdown').css('border-style' , jQuery('#ddcf_contact_name').css('border-top-style'));
        jQuery('.ddcf_dropdown').css('border-color' , jQuery('#ddcf_contact_name').css('border-top-color'));
        jQuery('.ddcf_dropdown').css('border-radius' , jQuery('#ddcf_contact_name').css('border-top-left-radius'));
        jQuery('.ddcf_dropdown').css('-webkit-transition' , jQuery('#ddcf_contact_name').css('-webkit-transition'));
        jQuery('.ddcf_dropdown').css('-moz-transition' , jQuery('#ddcf_contact_name').css('-moz-transition'));
        jQuery('.ddcf_dropdown').css('-o-transition' , jQuery('#ddcf_contact_name').css('-o-transition'));
        jQuery('.ddcf_dropdown').css('transition' , jQuery('#ddcf_contact_name').css('transition'));
        jQuery('.ddcf_dropdown').css('color' , jQuery('#ddcf_contact_name').css('color'));
        jQuery('.ddcf_dropdown').css('background-color' , jQuery('#ddcf_contact_name').css('background-color'));
        jQuery('.ddcf_dropdown').css('box-shadow' , jQuery('#ddcf_contact_name').css('box-shadow'));
        jQuery('.ddcf_dropdown').css('margin-top' , '0px').css('height' , lineHeight);

        /* set the width of the container */
        controlMargin = 18;
        var dropdownWidth = jQuery('#ddcf_dropdown_one').outerWidth();
        var dropdownLabelWidth = jQuery('label[for="ddcf_dropdown_one"]').outerWidth();

        if (jQuery('#ddcf_dropdown_two').outerWidth()>dropdownWidth)
            dropdownWidth = jQuery('#ddcf_dropdown_two').outerWidth();

        if (jQuery('label[for="ddcf_dropdown_two"]').width()>dropdownLabelWidth)
            dropdownLabelWidth = jQuery('label[for="ddcf_dropdown_two"]').outerWidth();
        jQuery('.ddcf_span_dropdown').outerWidth(dropdownLabelWidth + dropdownWidth + controlMargin);
        jQuery('#ddcf_dropdowns_align').outerWidth(dropdownLabelWidth + dropdownWidth + controlMargin)
                                       .css('margin', 'auto')
                                       .css('float','none');
    }

    if (usingSimpleAddition) {
        /* set the width & height */
        var captchaTextWidth = jQuery('#ddcf_captcha_question').outerWidth();
        var captchaInputWidth = jQuery('#ddcf_contact_captcha_add').outerWidth();
        jQuery('#ddcf_captcha_add').width(captchaTextWidth + captchaInputWidth  + controlMargin)
                                        .css('height' , lineHeight);
        jQuery('#ddcf_contact_captcha_add').css('height' , lineHeight);
    }

    if ((usingDatePicker||usingDateTimePicker)&&usingDropDowns) {
        /* need to distribute the available space between the two */
        var reqdDateSpace = datePickerWidth + datePickerLabelWidth + controlMargin;
        var reqdDropdownSpace = dropdownLabelWidth + dropdownWidth + controlMargin;
        var ratio = reqdDateSpace / (reqdDateSpace + reqdDropdownSpace);
        var availableSpace = jQuery('#ddcf_contact_form_top_right').outerWidth();
        var datesWidth = ratio * availableSpace;
        var dropdownsWidth = (1 - ratio) * availableSpace;

        if (ddcfWidth<smallScreenLimit) {
            jQuery('#ddcf_dates_details_row_division').width('0em');
            jQuery('#ddcf_party_size_details_row_division').width('100%');
        } else {
            jQuery('#ddcf_dates_details_row_division').width(datesWidth-1);
            jQuery('#ddcf_party_size_details_row_division').width(dropdownsWidth-1);
        }
    }

    if (ddcfWidth > flipWidth) {
        /* vertically align the top rhs section */

        /* set top right section's vertical alignment  */
        var topLeftHeight = jQuery("#ddcf_contact_form_top_left").height();
        var topRightHeight = jQuery("#ddcf_contact_form_top_right").height();

        if (topRightHeight<topLeftHeight) topRightHeight = topLeftHeight;
        jQuery("#ddcf_contact_form_top_right").css('height', topRightHeight);
        var topRightOffset = topRightHeight/2.0;
        jQuery("#ddcf_details_rows_container").css('margin-top', -topRightOffset);

        /* set the rhs form elements container vertical alignment */
        /* first a wee cludge to account for a miscounted bottom margin of 1em on the lhs  */
        var div = jQuery('<div style="width:1em;margin:0 !important;padding:0 !important;border:0 !important;"></div>').appendTo('body');
        var marginError = div.width(); div.remove();
        var middleBitOffset = ((topRightHeight-jQuery("#ddcf_middle_bit").height())-marginError)/2;

        if ((middleBitOffset<5) || (ddcfWidth<750 && ddcfWidth>flipWidth&&jQuery('#ddcf_google_recaptcha').length>0&&formDensity===5)) jQuery("#ddcf_middle_bit").css('margin-top', '0');
        else jQuery("#ddcf_middle_bit").css('margin-top', middleBitOffset);
    } else {
        jQuery("#ddcf_middle_bit").css('margin-top', '0');
        jQuery("#ddcf_contact_form_top_right").css('height', 'auto');
    }
}


jQuery(document).ready(function ($) {

    jQuery('body').css('cursor', 'wait');

	/* window functions */
	jQuery(window).resize(function() {ddcfMakePretty();}).bind('beforeunload',function() {ddcfResetForm();});

	/* Get the default form field values from the hidden divs, then set them */
    /* (avoids displaying already posted data on refresh after submission) */
	ddcfContactNameDefault = jQuery.trim(jQuery('#ddcf_contact_name_default').text());
    jQuery("#ddcf_contact_name").val(ddcfContactNameDefault);
	ddcfContactEmailDefault = jQuery.trim(jQuery('#ddcf_contact_email_default').text());
    jQuery("#ddcf_contact_email").val(ddcfContactEmailDefault);
	ddcfContactSubjectDefault = jQuery.trim(jQuery('#ddcf_contact_subject_default').text());
    jQuery("#ddcf_contact_subject").val(ddcfContactSubjectDefault);
	ddcfContactMessageDefault = jQuery.trim(jQuery('#ddcf_contact_message_default').text());
    jQuery("#ddcf_contact_message").val(ddcfContactMessageDefault);
	ddcfQuestionOneDefault = jQuery.trim(jQuery('#ddcf_question_one_default').text());
    jQuery("#ddcf_question_one").val(ddcfQuestionOneDefault);
	ddcfQuestionTwoDefault = jQuery.trim(jQuery('#ddcf_question_two_default').text());
    jQuery("#ddcf_question_two").val(ddcfQuestionTwoDefault);
    ddcfStartDateDefault = jQuery.trim(jQuery('#ddcf_start_date_default').text());
    jQuery("#ddcf_start_date").val(ddcfStartDateDefault);
	ddcfEndDateDefault = jQuery.trim(jQuery('#ddcf_end_date_default').text());
    jQuery("#ddcf_end_date").val(ddcfEndDateDefault);
    jQuery("#ddcf_dropdown_one").val('');
    jQuery("#ddcf_dropdown_two").val('');
    jQuery("#ddcf_newsletter_signup").prop('checked', true);
	ddcfErrorCheckingMethod = jQuery.trim(jQuery('#ddcf_error_checking_method').val());
	ddcfDatesCompulsoryCheck = jQuery.trim(jQuery('#ddcf_dates_compulsory_check').val());
	ddcfDropdownsCompulsoryCheck = jQuery.trim(jQuery('#ddcf_numbers_compulsory_check').val());
	ddcfQuestionsCompulsoryCheck = jQuery.trim(jQuery('#ddcf_questions_compulsory_check').val());
    
/*
	ddcfCaptchaDefault = jQuery.trim(jQuery('#ddcf_contact_captcha_add').text());
	ddcfreCaptchaDefault = jQuery.trim(jQuery('#recaptcha_response_field').text());;
*/
	// focus and unfocus handling:

	// Contact name text field
	jQuery('#ddcf_contact_name').focusout(function() { ddcfCheckForm(false, true); })
                                .focus(function() { if (jQuery("#ddcf_contact_name").val() === ddcfContactNameDefault) jQuery("#ddcf_contact_name").val(""); });

	// email text field
	jQuery('#ddcf_contact_email').focusout(function() { ddcfCheckForm(false, true); })
                                 .focus(function() { if (jQuery("#ddcf_contact_email").val() === ddcfContactEmailDefault) jQuery("#ddcf_contact_email").val(""); });

	// subject text field
	jQuery('#ddcf_contact_subject').focusout(function() {ddcfCheckForm(false, true);})
                                   .focus(function() { if (jQuery("#ddcf_contact_subject").val() === ddcfContactSubjectDefault) jQuery("#ddcf_contact_subject").val(""); });

	// message text field
	jQuery('#ddcf_contact_message').focusout(function() {ddcfCheckForm(false, true);})
                                   .focus(function() { if (jQuery("#ddcf_contact_message").val() === ddcfContactMessageDefault) jQuery("#ddcf_contact_message").val(""); });

	// question one field
	jQuery('#ddcf_question_one').focusout(function() { ddcfCheckForm(false, true);} )
                                .focus(function() { if (jQuery("#ddcf_question_one").val() === ddcfQuestionOneDefault) jQuery("#ddcf_question_one").val(""); });

	// question two field
	jQuery('#ddcf_question_two').focusout(function() {ddcfCheckForm(false, true);})
                                .focus(function() { if (jQuery("#ddcf_question_two").val() === ddcfQuestionTwoDefault) jQuery("#ddcf_question_two").val(""); });

	// contact form booking functionality */
	jQuery(".ddcf_date_picker").datepicker({ timeFormat: "HH:mm", dateFormat : "yy-mm-dd", minDate: 1 });
	jQuery(".ddcf_datetime_picker").datetimepicker({ timeFormat: "HH:mm", dateFormat : "yy-mm-dd", minDate: 1 });
	jQuery("#ddcf_start_date").focusout(function() {ddcfCheckForm(false, true);})
                              .focus(function() { if (jQuery("#ddcf_start_date").val() === ddcfStartDateDefault) jQuery("#ddcf_start_date").val(""); });
	jQuery("#ddcf_end_date").focusout(function() {ddcfCheckForm(false, true);})
                            .focus(function() { if (jQuery("#ddcf_end_date").val() === ddcfEndDateDefault) jQuery("#ddcf_end_date").val(""); });

	// bind unfocus event for num adults / children
	jQuery('#ddcf_dropdown_one').focusout(function() {ddcfCheckForm(false, true);});
	jQuery('#ddcf_dropdown_two').focusout(function() {ddcfCheckForm(false, true);});


	// button type (WordPress themed or jQuery-UI themed)
	jQuery('#ddcf_button_area').height('+=3px'); /* allow for button press movement */

	if (jQuery('#ddcf_button_styling').val() === 'ddcf_button_styling_jqueryui') {
		jQuery('.ddcf_button').button();
	}

	// Reset Button
	jQuery('#ddcf_contact_reset').click(function(event) {
            event.preventDefault();
            ddcfResetForm();
        }
    );

    // Submit Button
    if (ddcfErrorCheckingMethod==='onsubmit') {
        jQuery('#ddcf_contact_send').prop('disabled', false).click(function(event) {
                event.preventDefault();
                ddcfCheckForm(true, true);
            }
        );
    } else { // assume 'realtime'
        jQuery('#ddcf_contact_send').prop('disabled', true).click(function(event) {
                event.preventDefault();
                ddcfCheckForm(true, true);
            }
        );
    }

	/* take control of enter keypresses */
	jQuery('input').keydown(function(e) {
            var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
            if (key === 13) {
                var inputs = $(this).closest('form').find(':input:visible');
                var nextInput = inputs.index(this)+ 1;
                /* always tab forward unless on last two elements (reset and submit) */
                if (inputs.index(this)<inputs.length-2) {
                    e.preventDefault();
                    inputs.eq(nextInput).focus();
                    /* pressing return when autocomplete is active in FF is borked */
                    /* The code below showed signs of a fix, but ran out of time */
//                    if (self.menu.active) e.preventDefault();
//                    if (jQuery(this).val()!=ui.item.value) jQuery(this).val(ui.item.value);
//                    else inputs.eq(nextInput).focus();
                }
            }
        });

    // show realtime error checking (ticks and crosses)?
    if (jQuery('#ddcf_error_checking_method').val() === 'realtime')
        jQuery('.ddcf_contact_input_verify').css('display', 'inline');
    else
        jQuery('.ddcf_contact_input_verify').css('display', 'none');

    // set up captcha etc (js / jQuery via WP / Ajax / php)
    ddcfInitialiseSession();

    // adjust screen elements after given time to allow for setting up of captcha (multiple of 500mS)
    ddcfInitialTimer = 2500;

    // start monitoring input
    setInterval(function() {ddcfCheckForm(false, false);}, 500);
});