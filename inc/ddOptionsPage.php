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

	<!-- stylin -->
	<?php
		wp_enqueue_style('ddcf_normalise_style', plugins_url().'/dd-contact-form/css/normalise.css');
		wp_enqueue_style('ddcf_options_page_style', plugins_url().'/dd-contact-form/css/style-options.css');
		wp_enqueue_style('ddcf_jqueryui_theme_style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/smoothness/jquery-ui.css');
	?>
	<!--[if gte IE 9]><style type="text/css">.gradient { filter: none; }</style><![endif]-->
	<!-- jQuery -->
	<?php wp_enqueue_script( 'ddcf_options_page_script',
							 plugins_url().'/dd-contact-form/js/dd-contact-form-options.js',
							 array( 'jquery', 'jquery-ui-core',  'jquery-ui-accordion',  'jquery-ui-tabs') ); ?>

		<?php screen_icon(); ?>

		<h2><?php _e('DD Contact Form Settings', 'ddcf_plugin') ?></h2>

                <div style="margin-left:2.0em">
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                        <input type="hidden" name="cmd" value="_s-xclick">
                        <input type="hidden" name="hosted_button_id" value="M3TPTL2LSP4UG">
                        <input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
                        <label><br />Like the contact form?<br />Please donate towards future development!</label>
                        <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
                    </form>
                </div>
                
                
		<div class="ddcf-options-div">
			<div id="tabs">

				<ul>
					<li><a href="#tabs-1">General Settings</a></li>
					<li><a href="#tabs-2">User Interface</a></li>
					<li><a href="#tabs-3">Extra Information</a></li>
				</ul>

				<form method="POST" action="options.php">
				<?php 
                                    settings_fields( 'ddcf_settings_group' ); // render the hidden input fields and handles the security aspects
                                    do_settings_fields( 'ddcf_settings_group', 'settings-section-id' ); // settings-section-id not used - investigate?
                                ?>

				<div id="tabs-1">
					<p>
						<?php _e('Set, edit or view email settings. View or change captcha settings in security settings section. Enable user details logging in privacy settings section.', 'ddcf_plugin') ?><br />
					</p>
					<div id="accordion1">
						<h3>Security</h3>
						<div>
							<p>
								<strong><?php _e('Captcha type', 'ddcf_plugin') ?></strong>
                                                                <br /><br />
								<?php _e('Select the Captcha type:', 'ddcf_plugin') ?>
								<select name="ddcf_captcha_type" id="ddcf_captcha_type">
										<option value="reCaptcha" <?php if(get_option(ddcf_captcha_type)=='reCaptcha') echo 'selected';?>>reCaptcha</option>
										<option value="Simple Addition" <?php if(get_option(ddcf_captcha_type, 'Simple Addition')=='Simple Addition') echo 'selected';?>>Simple Addition</option>
										<option value="None" <?php if(get_option(ddcf_captcha_type)=='None') echo 'selected';?>>None (not recommended)</option>
								</select>
							</p>
                                                        <br />
							<p>
								<strong><?php _e('Google reCaptcha keys', 'ddcf_plugin') ?></strong>
								<br /><br />
                                                                <?php _e('To use the Google reCaptcha, you will need to sign up and get private and public keys. Signup is extremely quick and easy - see here: <a href="https://www.google.com/recaptcha/admin/create" target="_blank">https://www.google.com/recaptcha/admin/create</a>', 'ddcf_plugin') ?>
                                                                <br /><br />
                                                                <?php _e('Paste public key here:', 'ddcf_plugin') ?>
                                                                <br />
                                                                <textarea class="ddcf_textarea_input" name="ddcf_recaptcha_public_key" id="ddcf_recaptcha_public_key" value="<?php echo get_option(ddcf_recaptcha_public_key); ?>" ><?php echo get_option('ddcf_recaptcha_public_key'); ?></textarea>
                                                                <br />
                                                                <?php _e('Paste private key here:', 'ddcf_plugin') ?>
                                                                <br />
                                                                <textarea class="ddcf_textarea_input" name="ddcf_recaptcha_private_key" id="ddcf_recaptcha_private_key" value="<?php echo get_option(ddcf_recaptcha_private_key); ?>" ><?php echo get_option('ddcf_recaptcha_private_key'); ?></textarea>
                                                        </p>
                                                </div>
						<h3>Privacy</h3>
                                                    <div>
                                                        <p>
                                                                <strong><?php _e('Keep a record of each enquiry', 'ddcf_plugin') ?></strong>
                                                                <br /><br />
                                                                <?php _e('Save details of each message sent from the contact form to your Wordpress database.', 'ddcf_plugin') ?>
                                                                <br /><br />
                                                                <input type="checkbox" id="ddcf_keep_records_check" name="ddcf_keep_records_check" value="ddcf_keep_records_check" <?php if(get_option('ddcf_keep_records_check')) echo ' checked '; ?>>&nbsp;&nbsp;<?php _e('Save details') ?>
                                                        </p>
                                                        <br />
							<p>
                                                                <strong><?php _e('Future Contact Permission', 'ddcf_plugin') ?></strong>
                                                                <br /><br />
                                                                <?php _e('Offer to save user permission to send newsletters or other updates in the future.', 'ddcf_plugin') ?>                                                                       <br /><br />
								<input type="checkbox" id="ddcf_rec_updates_option_check" name="ddcf_rec_updates_option_check" value="ddcf_rec_updates_option_check" <?php if(get_option('ddcf_rec_updates_option_check')) echo ' checked '; ?>>&nbsp;&nbsp;<?php _e('Offer to register contact form users') ?>
								<br /><br />
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="ddcf_rec_updates_message_check" name="ddcf_rec_updates_message_check" value="ddcf_rec_updates_message_check" <?php if(get_option('ddcf_rec_updates_message_check')) echo ' checked '; ?>>&nbsp;&nbsp;<?php _e('Custom message:'); ?>
								<br />
<textarea class="ddcf_textarea_input" name="ddcf_rec_updates_message" value="<?php echo get_option('ddcf_rec_updates_message'); ?>" id="ddcf_rec_updates_message" ><?php echo get_option('ddcf_rec_updates_message'); ?></textarea>
							</p>
                                                        <br />
                                                        <p>
                                                                <strong><?php _e('Geolocation', 'ddcf_plugin') ?></strong>
                                                                <br /><br />
                                                                <?php _e('Save the IP address and geolocation information for each contact form user. Please ensure there are no legal issues using this in your area before enabling.<br /><br />To use the geolocation services, you will need to register with IPinfoDB - see here: <a href="http://www.ipinfodb.com/register.php" target="_blank">http://www.ipinfodb.com/register.php</a>.', 'ddcf_plugin') ?>
                                                                <br /><br /><input type="checkbox" id="ddcf_geo_ip_option_check" name="ddcf_geo_ip_option_check" value="ddcf_geo_ip_option_check" <?php if(get_option('ddcf_geo_ip_option_check', false)) echo ' checked '; ?>>
                                                                <?php _e('Save contact form user&#39;s IP address and geolocation information', 'ddcf_plugin') ?>
                                                                <br /><br />
                                                                <?php _e('Paste key here:', 'ddcf_plugin') ?>
                                                                <br />
                                                                <textarea class="ddcf_textarea_input" name="ddcf_geoloc_key" id="ddcf_geoloc_key" value="<?php echo get_option(ddcf_geoloc_key); ?>" ><?php echo get_option('ddcf_geoloc_key'); ?></textarea>
                                                        </p>
                                                </div>
                                                <h3>Email</h3>
						<div>
							<p>
                                                            <strong><?php _e('Enquiry email recipients', 'ddcf_plugin') ?></strong>
                                                            <br /><br />
                                                            <?php _e('Set recipient email addresses for receiving contact form enquiries.', 'ddcf_plugin') ?>
                                                        </p>
							<?php _e('Email 1: ', 'ddcf_plugin') ?>
								<input class="ddcf_text_input" type="text" name="ddcf_enquiries_email_one" value="<?php echo get_option('ddcf_enquiries_email_one'); ?>" />
							<br />
							<?php _e('Email 2: ', 'ddcf_plugin') ?>
								<input class="ddcf_text_input" type="text" name="ddcf_enquiries_email_two" value="<?php echo get_option('ddcf_enquiries_email_two'); ?>" />
							<br />
							<?php _e('Email 3: ', 'ddcf_plugin') ?>
								<input class="ddcf_text_input" type="text" name="ddcf_enquiries_email_three" value="<?php echo get_option('ddcf_enquiries_email_three'); ?>" />
							<br />
							<?php _e('Email 4: ', 'ddcf_plugin') ?>
								<input class="ddcf_text_input" type="text" name="ddcf_enquiries_email_four" value="<?php echo get_option('ddcf_enquiries_email_four'); ?>" />
							<br />
							<?php _e('Email 5: ', 'ddcf_plugin') ?>
								<input class="ddcf_text_input" type="text" name="ddcf_enquiries_email_five" value="<?php echo get_option('ddcf_enquiries_email_five'); ?>" />

							<br /><br /><br />
							<p>
								<strong><?php _e('Confirmation Email', 'ddcf_plugin') ?></strong>
								<br /><br />
                                                                <?php _e('You can Send the user a confirmation email once the system has received the form.', 'ddcf_plugin') ?>
                                                                <br /><br /><input type="checkbox" id="ddcf_email_confirmation" name="ddcf_email_confirmation" value="ddcf_email_confirmation" <?php if(get_option('ddcf_email_confirmation', false)) echo ' checked '; ?>>
																<?php _e('Send confirmation email', 'ddcf_plugin') ?>
                                                                <br /><br />
                                                                <?php
                                                                	_e('Confirmation email text:', 'ddcf_plugin');
                                                                	$confirmation_text = get_option(ddcf_email_confirmation_text);
                                                                	if($confirmation_text=='') $confirmation_text  = _('This is an automatically generated message to let you know your message has been received. Please do not reply to this email. We will be in touch shortly.');
                                                                ?>
                                                                <br />
                                                                <textarea class="ddcf_textarea_input" name="ddcf_email_confirmation_text" value="<?php echo $confirmation_text; ?>" id="ddcf_email_confirmation_text" ><?php echo $confirmation_text; ?></textarea>
							</p>

							<br /><br />
							<p>
								<strong><?php _e('Email Header', 'ddcf_plugin') ?></strong>
								<br /><br />
                                                                <?php _e('You can specify an image to display at the top of emails sent by the contact form. The URL should be in absolute format.', 'ddcf_plugin') ?>
                                                                <br /><br />
                                                                <?php _e('Image URL:', 'ddcf_plugin') ?>
                                                                <br /><textarea class="ddcf_textarea_input" name="ddcf_email_header" value="<?php echo get_option(ddcf_email_header); ?>" id="ddcf_email_header" ><?php echo get_option('ddcf_email_header'); ?></textarea>
                                                                <br /><br />
							</p>
                                                </div>
					</div> <!-- #accordion1 -->
				</div><!-- tabs-1 -->

				<div id="tabs-2">
					<p>
						<?php _e('View or change user feedback options or make changes to the UI appearance.', 'ddcf_plugin') ?><br />
					</p>
					<div id="accordion2">
						<h3>User Feedback</h3>
						<div>
                                                    <p>
                                                            <strong><?php _e('Form sent action', 'ddcf_plugin') ?></strong>
                                                            <br /><br />
                                                            <?php
                                                                _e('Once the user has sucessfully sent their message, you can either send them to a new page or display a thank you messsage on the current page:', 'ddcf_plugin');
                                                                $thankyouType = (get_option(ddcf_thankyou_type)=='') ? 'ddcf_thankyou_message' : get_option(ddcf_thankyou_type);
                                                                $thankyouMessage = (get_option(ddcf_thankyou_message)=='') ? _('Thank you for your enquiry. A representative will be in touch shortly.') : get_option(ddcf_thankyou_message);
                                                                $thankyouURL = (get_option(ddcf_thankyou_url)=='') ? '/' : get_option(ddcf_thankyou_url);
                                                            ?>
                                                            <br /><br />


                                                            <input type="radio" name="ddcf_thankyou_type" id="ddcf_thankyou_type" value="ddcf_thankyou_message" <?php if($thankyouType=='ddcf_thankyou_message') echo ' checked';?>>
                                                          <?php _e('Display thank you message:', 'ddcf_plugin') ?>
                                                            <br />
                                                            <textarea class="ddcf_textarea_input" name="ddcf_thankyou_message" id="ddcf_thankyou_message" value="<?php echo $thankyouMessage; ?>" ><?php echo $thankyouMessage; ?></textarea>
                                                            <br /><br />


                                                            <input type="radio" name="ddcf_thankyou_type" id="ddcf_thankyou_type" value="ddcf_thankyou_url" <?php if($thankyouType=='ddcf_thankyou_url') echo ' checked';?>>
                                                            <?php _e('Display contact form thank you page. Enter URL here:', 'ddcf_plugin') ?>
                                                            <br />
                                                            <textarea class="ddcf_textarea_input" name="ddcf_thankyou_url" id="ddcf_thankyou_url" value="<?php echo $thankyouURL; ?>" ><?php echo $thankyouURL; ?></textarea>

                                                    </p>
                                                    <br />
                                                    <p>
                                                        <strong><?php _e('Error checking', 'ddcf_plugin') ?></strong>
                                                        <br /><br />
                                                        <?php
                                                            _e('Choose whether to check for user input errors either on the fly (ticks and crosses) or on submit button press.', 'ddcf_plugin');
                                                            $error_checking_method = (get_option(ddcf_error_checking_method)=='') ? 'realtime' : get_option(ddcf_error_checking_method);
                                                        ?>
                                                        <br /><br />
                                                        <input type="radio" name="ddcf_error_checking_method" id="ddcf_error_checking_method" value="realtime" <?php if($error_checking_method=='realtime') echo ' checked';?>>
                                                        <?php _e('Check for errors on the fly', 'ddcf_plugin'); ?>
                                                        <br /><br />
                                                        <input type="radio" name="ddcf_error_checking_method" id="ddcf_error_checking_method" value="onsubmit" <?php if($error_checking_method=='onsubmit') echo ' checked';?>>
                                                        <?php _e('Check for errors on form submit button press', 'ddcf_plugin'); ?>
                                                    </p>
                                                </div>
						<h3>Appearance</h3>
						<div>
                                                    <p>
                                                        <strong><?php _e('CSS Theme', 'ddcf_plugin') ?></strong>
                                                        <br /><br />
                                                        <?php _e('Select a css theme for the Contact form. The custom css template (style-theme-custom.css) can be found in the plugin css folder.', 'ddcf_plugin') ?>
                                                        <br /><br />
                                                        <?php _e('Select a css theme:', 'ddcf_plugin') ?>
                                                        <select name="ddcf_form_theme" id="ddcf_form_theme">ddcf_jqueryui_theme ddcf_form_theme
                                                                        <option value="clean" <?php if(get_option(ddcf_form_theme)=='clean') echo 'selected';?>>Clean</option>
                                                                        <option value="cream" <?php if(get_option(ddcf_form_theme)=='cream') echo 'selected';?>>Cream</option>
                                                                        <option value="black" <?php if(get_option(ddcf_form_theme)=='black') echo 'selected';?>>Black</option>
                                                                        <option value="steel" <?php if(get_option(ddcf_form_theme)=='steel') echo 'selected';?>>Steel</option>
                                                                        <option value="custom" <?php if(get_option(ddcf_form_theme)=='custom') echo 'selected';?>>Custom</option>
                                                        </select>
                                                    </p>
                                                    <br /><br />
                                                    <div id="ddcf_captcha_options">
                                                            <p>
                                                                    <strong><?php _e('reCaptcha theme', 'ddcf_plugin') ?></strong>
                                                                    <br /><br />
                                                                    <?php _e('Select the theme for the google reCaptcha:', 'ddcf_plugin') ?>
                                                                    <select name="ddcf_recaptcha_theme" id="ddcf_recaptcha_theme">
                                                                                    <option value="red" <?php if(get_option(ddcf_recaptcha_theme)=='red') echo 'selected';?>>Red (default)</option>
                                                                                    <option value="white" <?php if(get_option(ddcf_recaptcha_theme)=='white') echo 'selected';?>>White</option>
                                                                                    <option value="blackglass" <?php if(get_option(ddcf_recaptcha_theme)=='blackglass') echo 'selected';?>>Black Glass</option>
                                                                                    <!-- weird sizing problems - option value="clean" <?php if(get_option(ddcf_recaptcha_theme)=='clean') echo 'selected';?>>Clean</option-->
                                                                    </select>
                                                            </p>
                                                    </div>
                                                    <br /><br />
                                                    <p>
                                                            <strong><?php _e('UI Widget Theme', 'ddcf_plugin') ?></strong>
                                                            <br /><br />
                                                            <?php _e('Select a theme for the contact form jQuery-UI widgets (buttons and datepickers).<br /><br />To use a custom made jQuery UI theme, name your rolled theme jquery-ui-custom.min.css and put it in the css folder in the Contacts and Bookings plugin folder.<br />To use the online theme roller to roll your own theme, see here:<br /><a href="http://jqueryui.com/themeroller/" target="_blank">http://jqueryui.com/themeroller/</a>.', 'ddcf_plugin') ?>
                                                            <br /><br />
                                                            <?php _e('Select a premade jQuery UI theme:', 'ddcf_plugin') ?>
                                                            <select name="ddcf_jqueryui_theme" id="ddcf_jqueryui_theme">
                                                                            <option value="none" <?php if(get_option(ddcf_jqueryui_theme)=='none') echo 'selected';?>>None</option>
                                                                            <option value="custom" <?php if(get_option(ddcf_jqueryui_theme)=='custom') echo 'selected';?>>Custom</option>
                                                                            <option value="black-tie" <?php if(get_option(ddcf_jqueryui_theme)=='black-tie') echo 'selected';?>>Black Tie</option>
                                                                            <option value="blitzer" <?php if(get_option(ddcf_jqueryui_theme)=='blitzer') echo 'selected';?>>Blitzer</option>
                                                                            <option value="cupertino" <?php if(get_option(ddcf_jqueryui_theme)=='cupertino') echo 'selected';?>>Cupertino</option>
                                                                            <option value="dark-hive" <?php if(get_option(ddcf_jqueryui_theme)=='dark-hive') echo 'selected';?>>Dark Hive</option>
                                                                            <option value="dot-luv" <?php if(get_option(ddcf_jqueryui_theme)=='dot-luv') echo 'selected';?>>Dot Luv</option>
                                                                            <option value="eggplant" <?php if(get_option(ddcf_jqueryui_theme)=='eggplant') echo 'selected';?>>Eggplant</option>
                                                                            <option value="excite-bike" <?php if(get_option(ddcf_jqueryui_theme)=='excite-bike') echo 'selected';?>>Excite Bike</option>
                                                                            <option value="flick" <?php if(get_option(ddcf_jqueryui_theme)=='flick') echo 'selected';?>>Flick</option>
                                                                            <option value="hot-sneaks" <?php if(get_option(ddcf_jqueryui_theme)=='hot-sneaks') echo 'selected';?>>Hot Sneaks</option>
                                                                            <option value="humanity" <?php if(get_option(ddcf_jqueryui_theme)=='humanity') echo 'selected';?>>Humanity</option>
                                                                            <option value="le-frog" <?php if(get_option(ddcf_jqueryui_theme)=='le-frog') echo 'selected';?>>Le Frog</option>
                                                                            <option value="mint-choc" <?php if(get_option(ddcf_jqueryui_theme)=='mint-choc') echo 'selected';?>>Mint Choc</option>
                                                                            <option value="overcast" <?php if(get_option(ddcf_jqueryui_theme)=='overcast') echo 'selected';?>>Overcast</option>
                                                                            <option value="pepper-grinder" <?php if(get_option(ddcf_jqueryui_theme)=='pepper-grinder') echo 'selected';?>>Pepper Grinder</option>
                                                                            <option value="redmond" <?php if(get_option(ddcf_jqueryui_theme)=='redmond') echo 'selected';?>>Redmond</option>
                                                                            <option value="smoothness" <?php if(get_option(ddcf_jqueryui_theme)=='smoothness') echo 'selected';?>>Smoothness</option>
                                                                            <option value="south-street" <?php if(get_option(ddcf_jqueryui_theme)=='south-street') echo 'selected';?>>South Street</option>
                                                                            <option value="start" <?php if(get_option(ddcf_jqueryui_theme)=='start') echo 'selected';?>>Start</option>
                                                                            <option value="sunny" <?php if(get_option(ddcf_jqueryui_theme)=='sunny') echo 'selected';?>>Sunny</option>
                                                                            <option value="swanky-purse" <?php if(get_option(ddcf_jqueryui_theme)=='swanky-purse') echo 'selected';?>>Swanky Purse</option>
                                                                            <option value="trontastic" <?php if(get_option(ddcf_jqueryui_theme)=='trontastic') echo 'selected';?>>Trontastic</option>
                                                                            <option value="ui-darkness" <?php if(get_option(ddcf_jqueryui_theme)=='ui-darkness') echo 'selected';?>>UI Darkness</option>
                                                                            <option value="ui-lightness" <?php if(get_option(ddcf_jqueryui_theme)=='ui-lightness') echo 'selected';?>>UI Lightness</option>
                                                                            <option value="vader" <?php if(get_option(ddcf_jqueryui_theme)=='vader') echo 'selected';?>>Vader</option>
                                                            </select>
                                                    </p>
                                                    <br /><br />
                                                </div>
					</div> <!-- #accordion2 -->
				</div><!-- tabs-2 -->


				<div id="tabs-3">
					<p>
						<?php _e('Request extra information from the contact form user.', 'ddcf_plugin') ?><br />
					</p>
					<div id="accordion3">
						<h3>Dates & Times</h3>
						<div>
							<div style="width:100%; height: 96px; margin-top:20px;">
								<div style="width:50%; float:left;">
									<?php _e('<strong>Booking Start Date / Time</strong><br /><br />', 'ddcf_plugin'); ?>
									<input type="checkbox" id="ddcf_start_date_check" name="ddcf_start_date_check" value="ddcf_start_date_check" <?php if(get_option('ddcf_start_date_check')) echo ' checked '; ?>>&nbsp;<?php _e('Request booking start date', 'ddcf_plugin'); ?>
									<br /><br />
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="ddcf_start_date_time_check" name="ddcf_start_date_time_check" value="ddcf_start_date_time_check" <?php if(get_option('ddcf_start_date_time_check')) echo ' checked '; ?>>&nbsp;<?php _e('Request booking start time', 'ddcf_plugin'); ?>
									<br /><br />
								</div>
								<div style="width:50%; float:left;">
									<?php _e('<strong>Booking End Date / Time</strong><br /><br />', 'ddcf_plugin'); ?>
									<input type="checkbox" id="ddcf_end_date_check" name="ddcf_end_date_check" value="ddcf_end_date_check" <?php if(get_option('ddcf_end_date_check')) echo ' checked '; ?>>&nbsp;<?php _e('Request booking end date', 'ddcf_plugin'); ?>
									<br /><br />
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="ddcf_end_date_time_check" name="ddcf_end_date_time_check" value="ddcf_end_date_time_check" <?php if(get_option('ddcf_end_date_time_check')) echo ' checked '; ?>>&nbsp;<?php _e('Request booking end time', 'ddcf_plugin'); ?>
									<br /><br />
								</div>
							</div>
                                                        <br /><br />
                                                        <div  style="width:100%; height:65px;;">
                                                            <?php _e('<strong>Required Field</strong>', 'ddcf_plugin'); ?>
                                                            <p>
                                                                <input type="checkbox" id="ddcf_dates_compulsory_check" name="ddcf_dates_compulsory_check" value="ddcf_dates_compulsory_check" <?php if(get_option('ddcf_dates_compulsory_check')) echo ' checked '; ?>>
                                                                <?php _e('Dates are a required field', 'ddcf_plugin'); ?>
                                                            </p>
                                                        </div>
                                                        <br /><br />
							<div>
								<?php _e('<strong>Apply to posts in a specific category</strong><br />', 'ddcf_plugin'); ?>
								<p>
									<?php _e('You may want to only ask for dates on posts with a certain category. You can specify which category here.<br /><br />', 'ddcf_plugin'); ?>
								<input type="checkbox" id="ddcf_dates_category_filter_check" name="ddcf_dates_category_filter_check" value="ddcf_dates_category_filter_check" <?php if(get_option('ddcf_dates_category_filter_check')) echo ' checked '; ?>>&nbsp;
								<?php
                                                                    _e('Apply these settings to only one category: ', 'ddcf_plugin');
                                                                    wp_dropdown_categories(array('hide_empty' => 0, 'name' => 'ddcf_dates_category_filter', 'id' => 'ddcf_dates_category_filter', 'class' => 'ddcf-category-filter-check', 'orderby' => 'name', 'selected' => get_option('ddcf_dates_category_filter'), 'hierarchical' => true, 'show_option_none' => __('None')));
								?>
								</p>
							</div>
                                                </div>
						<h3>Party Size</h3>
                                                <div>
                                                    <div style="width:100%; height:160px; margin-top:0.7em;">
                                                        <?php _e('<strong>Request Party Size</strong><br />', 'ddcf_plugin'); ?>
                                                        <p>
                                                                <?php _e('If your contact form is being used to collect bookings, you may want to request the number of people - for example, a restaurant or hotel room booking. You can ask for the number of adults and children on the contact form using these settings.', 'ddcf_plugin'); ?>
                                                        </p><br />
                                                        <input type="checkbox" id="ddcf_extra_dropdown_one_check" name="ddcf_extra_dropdown_one_check" value="ddcf_extra_dropdown_one_check" <?php if(get_option('ddcf_extra_dropdown_one_check')) echo ' checked '; ?>>&nbsp;<?php _e('Include dropdown menu for number of adults', 'ddcf_plugin'); ?>
                                                        <br />
                                                        <br />
                                                        <input type="checkbox" id="ddcf_extra_dropdown_two_check" name="ddcf_extra_dropdown_two_check" value="ddcf_extra_dropdown_two_check" <?php if(get_option('ddcf_extra_dropdown_two_check')) echo ' checked '; ?>>&nbsp;<?php _e('Include dropdown menu for number of children', 'ddcf_plugin'); ?>
                                                    </div>
                                                        <br /><br />
                                                        <div  style="width:100%; margin-top: 3em;;">
                                                            <?php _e('<strong>Required Field</strong>', 'ddcf_plugin'); ?>
                                                            <p>
                                                                <input type="checkbox" id="ddcf_party_size_compulsory_check" name="ddcf_party_size_compulsory_check" value="ddcf_party_size_compulsory_check" <?php if(get_option('ddcf_party_size_compulsory_check')) echo ' checked '; ?>>
                                                                <?php _e('Party size is a required field', 'ddcf_plugin'); ?>
                                                            </p>
                                                        </div>
                                                        <br /><br />
							<div>
								<?php _e('<strong>Apply to posts in a specific category</strong><br />', 'ddcf_plugin'); ?>
								<p>
									<?php _e('You may want to only ask for party sizes on posts with a certain category. You can specify which category here.<br /><br />', 'ddcf_plugin'); ?>
								<input type="checkbox" id="ddcf_party_size_category_filter_check" name="ddcf_party_size_category_filter_check" value="ddcf_party_size_category_filter_check" <?php if(get_option('ddcf_party_size_category_filter_check')) echo ' checked '; ?>>&nbsp;
								<?php
                                                                    _e('Apply these settings to only one category: ', 'ddcf_plugin');
                                                                    wp_dropdown_categories(array('hide_empty' => 0, 'name' => 'ddcf_party_size_category_filter', 'id' => 'ddcf_party_size_category_filter', 'class' => 'ddcf-category-filter-check', 'orderby' => 'name', 'selected' => get_option('ddcf_party_size_category_filter'), 'hierarchical' => true, 'show_option_none' => __('None')));
								?>
								</p>
							</div>
                                                </div>
						<?php _e('<h3>Additional Questions</h3>', 'ddcf_plugin'); ?>
						<div>
							<p>
								<?php _e('If you want to add extra questions to the contact form, you can specify them here.', 'ddcf_plugin'); ?>
								<br /><br />
							</p>

							<strong>Optional Questions</strong>
							<br /><br />
							<input type="checkbox" id="ddcf_extra_question_one_check" name="ddcf_extra_question_one_check" value="ddcf_extra_question_one_check" <?php if(get_option('ddcf_extra_question_one_check')) echo ' checked '; ?>>&nbsp; <?php _e('Include question 1'); ?>
							<textarea class="ddcf_textarea_input" name="ddcf_extra_question_one" value="<?php echo get_option('ddcf_extra_question_one'); ?>" id="ddcf_extra_question_one" ><?php echo get_option('ddcf_extra_question_one'); ?></textarea><br />
							<br />
							<input type="checkbox" id="ddcf_extra_question_two_check" name="ddcf_extra_question_two_check" value="ddcf_extra_question_two_check" <?php if(get_option('ddcf_extra_question_two_check')) echo ' checked '; ?>>&nbsp;<?php _e('Include question 2'); ?>
							<textarea class="ddcf_textarea_input" name="ddcf_extra_question_two" value="<?php echo get_option('ddcf_extra_question_two'); ?>" id="ddcf_extra_question_two" ><?php echo get_option('ddcf_extra_question_two'); ?></textarea><br />

                                                        <br /><br />


                                                        <div  style="width:100%; height:85px;">
                                                            <?php _e('<strong>Required Field</strong>', 'ddcf_plugin'); ?>
                                                            <p>
                                                                <input type="checkbox" id="ddcf_questions_compulsory_check" name="ddcf_questions_compulsory_check" value="ddcf_questions_compulsory_check" <?php if(get_option('ddcf_questions_compulsory_check')) echo ' checked '; ?>>
                                                                <?php _e('Additional questions are a required field', 'ddcf_plugin'); ?>
                                                            </p>
                                                        </div>
							<div>
								<?php _e('<strong>Apply to posts in a specific category</strong><br />', 'ddcf_plugin'); ?>
								<p>
									<?php _e('You may want to only add extra question requests to posts within a certain category. You can specify which category here.<br /><br />', 'ddcf_plugin'); ?>
								<input type="checkbox" id="ddcf_extra_question_category_filter_check" name="ddcf_extra_question_category_filter_check" value="ddcf_extra_question_category_filter_check" <?php if(get_option('ddcf_extra_question_category_filter_check')) echo ' checked '; ?>>&nbsp;
								<?php
                                                                    _e('Apply these settings to only one category:', 'ddcf_plugin');
                                                                    wp_dropdown_categories(array('hide_empty' => 0, 'name' => 'ddcf_extra_question_category_filter', 'id' => 'ddcf_extra_question_category_filter', 'class' => 'ddcf-category-filter-check', 'orderby' => 'name', 'selected' => get_option('ddcf_extra_question_category_filter'), 'hierarchical' => true, 'show_option_none' => __('None')));
								?>
								</p>
							</div>
                                                        <br /><br />
						</div>
					</div> <!-- #accordion3 -->
				</div><!-- tabs-3 -->


			</div> <!-- tabs -->
			<?php submit_button(); ?>
		</form>
	</div> <!-- wrap -->
