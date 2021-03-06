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
<!--[if gte IE 9]>
<style type="text/css">.gradient { filter: none; }</style>
<![endif]-->
<?php screen_icon(); ?>
<h2>
    <div id="icon-plugins" class="icon32"></div>
    <?php _e('DD Contact Form Settings', 'ddcf_plugin') ?>
</h2>
<div style="margin-left:1.2em">
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="M3TPTL2LSP4UG">
        <input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
        <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
    </form>
</div>
<div class="ddcf-options-div">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">General Settings</a></li>
            <li><a href="#tabs-2">User Interface</a></li>
            <li><a href="#tabs-3">Extra Information</a></li>
            <li><a href="#tabs-4">Custom CSS</a></li>
        </ul>
        <form method="POST" action="options.php" id="ddcf_options_form" name="ddcf_options_form">
            <?php
                settings_fields('ddcf_settings_group'); // render the hidden input fields and handle the security aspects
                do_settings_fields('ddcf_settings_group', 'ddcf_settings_section_id');
                /* get the custom css from the database */
                global $wpdb;
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                $table_name = $wpdb->prefix . "custom_css";
                $query = "SELECT custom_css_text FROM ".$table_name." WHERE custom_css_index = 1";
                $custom_css = $wpdb->get_row($query);
            ?>
            <div id="tabs-1">
                <div id="accordion1">
                    <h3>Security</h3>
                    <div>
                        <h4><?php _e('Captcha type', 'ddcf_plugin') ?></h4>
                        <?php _e('Select the Captcha type:', 'ddcf_plugin') ?>
                        <select name="ddcf_captcha_type" id="ddcf_captcha_type">
                            <option value="reCaptcha" <?php if (get_option(ddcf_captcha_type) == 'reCaptcha') echo 'selected';?>>reCaptcha</option>
                            <option value="Simple Addition" <?php if (get_option(ddcf_captcha_type, 'Simple Addition') == 'Simple Addition') echo 'selected';?>>Simple Addition</option>
                            <option value="None" <?php if (get_option(ddcf_captcha_type) == 'None') echo 'selected';?>>None (not recommended)</option>
                        </select>
                        <br /><br /><br />
                        <h4><?php _e('Google reCaptcha keys', 'ddcf_plugin') ?></h4>
                        <?php _e('To use the Google reCaptcha, you will need to sign up and get private and public keys. Signup is extremely quick and easy - see here: <a href="https://www.google.com/recaptcha/admin/create" target="_blank">https://www.google.com/recaptcha/admin/create</a>', 'ddcf_plugin') ?>
                        <br /><br />
                        <?php _e('Paste public key here:', 'ddcf_plugin') ?>
                        <br />
                        <textarea class="ddcf_textarea_input" name="ddcf_recaptcha_public_key" id="ddcf_recaptcha_public_key" value="<?php echo get_option(ddcf_recaptcha_public_key); ?>" ><?php echo get_option('ddcf_recaptcha_public_key'); ?></textarea>
                        <br /><br />
                        <?php _e('Paste private key here:', 'ddcf_plugin') ?>
                        <br />
                        <textarea class="ddcf_textarea_input" name="ddcf_recaptcha_private_key" id="ddcf_recaptcha_private_key" value="<?php echo get_option(ddcf_recaptcha_private_key); ?>" ><?php echo get_option('ddcf_recaptcha_private_key'); ?></textarea>
                        <br /><br /><br />
                        <div id="ddcf_captcha_options">
                            <h4><?php _e('reCaptcha theme', 'ddcf_plugin') ?></h4>
                            <?php _e('Select the theme for the google reCaptcha:', 'ddcf_plugin') ?>
                            <select name="ddcf_recaptcha_theme" id="ddcf_recaptcha_theme">
                                <option value="red" <?php if (get_option(ddcf_recaptcha_theme) == 'red') echo 'selected';?>>Red (default)</option>
                                <option value="white" <?php if (get_option(ddcf_recaptcha_theme) == 'white') echo 'selected';?>>White</option>
                                <option value="blackglass" <?php if (get_option(ddcf_recaptcha_theme) == 'blackglass') echo 'selected';?>>Black Glass</option>
                                <option value="clean" <?php if (get_option(ddcf_recaptcha_theme) == 'clean') echo 'selected';?>>Clean</option-->
                            </select>
                        </div>
                    </div>
                    <h3>Privacy</h3>
                    <div>
                        <h4><?php _e('Keep a record of each enquiry', 'ddcf_plugin') ?></h4>
                        <?php _e('Save details of each message sent from the contact form to your Wordpress database.', 'ddcf_plugin') ?>
                        <br /><br />
                        <input type="checkbox" id="ddcf_save_user_details_check" name="ddcf_save_user_details_check" value="ddcf_save_user_details_check" <?php if (get_option(ddcf_save_user_details_check, 'true')) echo ' checked '; ?>>&nbsp;&nbsp;<?php _e('Save details') ?>
                        <br /><br /><br />
                        <h4><?php _e('Future Contact Permission', 'ddcf_plugin') ?></h4>
                        <?php _e('Offer to save user permission to send newsletters or other updates in the future.', 'ddcf_plugin') ?>
                        <br /><br />
                        <input type="checkbox" id="ddcf_offer_to_register_check" name="ddcf_offer_to_register_check" value="ddcf_offer_to_register_check" <?php if (get_option('ddcf_offer_to_register_check')) echo ' checked '; ?>>&nbsp;&nbsp;<?php _e('Offer to register contact form users') ?>
                        <br /><br />
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="ddcf_offer_to_register_custom_message_check" name="ddcf_offer_to_register_custom_message_check" value="ddcf_offer_to_register_custom_message_check" <?php if (get_option('ddcf_offer_to_register_custom_message_check')) echo ' checked '; ?>>&nbsp;&nbsp;<?php _e('Custom message:'); ?>
                        <br />
                        <textarea class="ddcf_textarea_input" name="ddcf_offer_to_register_custom_message" value="<?php echo get_option('ddcf_offer_to_register_custom_message'); ?>" id="ddcf_offer_to_register_custom_message" ><?php echo get_option('ddcf_offer_to_register_custom_message'); ?></textarea>
                        <br /><br /><br />
                        <h4><?php _e('Geolocation', 'ddcf_plugin') ?></h4>
                        <?php _e('Save the IP address and geolocation information for each contact form user.<br /><br />To use the geolocation services, you will need to register with IPinfoDB - see here: <a href="http://www.ipinfodb.com/register.php" target="_blank">http://www.ipinfodb.com/register.php</a>.', 'ddcf_plugin') ?>
                        <br /><br /><input type="checkbox" id="ddcf_geolocation_check" name="ddcf_geolocation_check" value="ddcf_geolocation_check" <?php if (get_option('ddcf_geolocation_check', false)) echo ' checked '; ?>>
                        <?php _e('Save contact form user&#39;s IP address and geolocation information', 'ddcf_plugin') ?>
                        <br /><br />
                        <?php _e('Paste key here:', 'ddcf_plugin') ?>
                        <br />
                        <textarea class="ddcf_textarea_input" name="ddcf_geolocation_key" id="ddcf_geolocation_key" value="<?php echo get_option(ddcf_geolocation_key); ?>" ><?php echo get_option('ddcf_geolocation_key'); ?></textarea>
                    </div>
                    <h3>Email</h3>
                    <div>
                        <div>
                            <h4><?php _e('Enquiry email recipients', 'ddcf_plugin') ?></h4>
                            <?php _e('Set recipient email addresses for receiving contact form enquiries.<br /><br />', 'ddcf_plugin') ?>
                        </div>
                        <div><?php
								_e('Email 1: ', 'ddcf_plugin')
                            ?><input class="ddcf_text_input" type="text" name="ddcf_enquiries_email_one" value="<?php echo get_option('ddcf_enquiries_email_one'); ?>" />
                            <br />
							<?php
                                _e('Email 2: ', 'ddcf_plugin')
                            ?><input class="ddcf_text_input" type="text" name="ddcf_enquiries_email_two" value="<?php echo get_option('ddcf_enquiries_email_two'); ?>" />
                            <br />
							<?php
                                _e('Email 3: ', 'ddcf_plugin')
                            ?><input class="ddcf_text_input" type="text" name="ddcf_enquiries_email_three" value="<?php echo get_option('ddcf_enquiries_email_three'); ?>" />
                            <br />
							<?php
                                _e('Email 4: ', 'ddcf_plugin')
                            ?><input class="ddcf_text_input" type="text" name="ddcf_enquiries_email_four" value="<?php echo get_option('ddcf_enquiries_email_four'); ?>" />
                            <br />
							<?php
                                _e('Email 5: ', 'ddcf_plugin')
                            ?><input class="ddcf_text_input" type="text" name="ddcf_enquiries_email_five" value="<?php echo get_option('ddcf_enquiries_email_five'); ?>" />
                        </div>
                        <br /><br />
                        <div>
                            <h4><?php _e('Confirmation Email', 'ddcf_plugin') ?></h4>
                            <?php
                                _e('You can Send the user a confirmation email once the system has received the form.', 'ddcf_plugin')
                            ?>
                            <br /><br />
                            <input type="checkbox" id="ddcf_email_confirmation_check" name="ddcf_email_confirmation_check" value="ddcf_email_confirmation_check" <?php if (get_option('ddcf_email_confirmation_check', 'ddcf_email_confirmation_check')) echo ' checked '; ?>>
                            <?php _e('Send confirmation email', 'ddcf_plugin') ?>
                            <br /><br />
                            <?php
                                _e('Confirmation email text:', 'ddcf_plugin');
                                $email_confirmation_text = get_option(ddcf_email_confirmation_text);
                            ?>
                            <br />
                            <textarea class="ddcf_textarea_input" name="ddcf_email_confirmation_text" value="<?php echo $email_confirmation_text; ?>" id="ddcf_email_confirmation_text" ><?php echo $email_confirmation_text; ?></textarea>
                        </div>
                        <br /><br />
                        <div>
                            <h4><?php _e('Email Header', 'ddcf_plugin') ?></h4>
                            <?php
                                _e('You can specify an image to display at the top of emails sent by the contact form. The URL should be in absolute format.', 'ddcf_plugin')
                                ?>
                            <br /><br />
                            <input type="checkbox" id="ddcf_email_header_check" name="ddcf_email_header_check" value="ddcf_email_header_check" <?php if (get_option('ddcf_email_header_check', false)) echo ' checked '; ?>>
                            <?php _e('Include header image', 'ddcf_plugin') ?>
                            <br /><br />
                            <?php _e('Image URL:', 'ddcf_plugin') ?>
                            <br />
                            <textarea class="ddcf_textarea_input" name="ddcf_email_header_image_url" value="<?php echo get_option(ddcf_email_header); ?>" id="ddcf_email_header_image_url" ><?php echo get_option('ddcf_email_header_image_url'); ?></textarea>
                        </div>
                        <br /><br />
                        <div>
                            <h4><?php _e('Email Footer', 'ddcf_plugin') ?></h4>
                            <?php
                                _e('You can add a legal disclaimer (or any other text) to the footer of emails generated by the DD Contact Form.', 'ddcf_plugin')
                            ?>
                            <br /><br />
                            <input type="checkbox" id="ddcf_email_footer_check" name="ddcf_email_footer_check" value="ddcf_email_footer_check" <?php if (get_option('ddcf_email_footer_check', false)) echo ' checked '; ?>>
                            <?php _e('Include footer text', 'ddcf_plugin') ?>
                            <br /><br />
                            <?php _e('Email footer text:', 'ddcf_plugin');
                                $email_footer_text = get_option(ddcf_email_footer_text);
                            ?>
                            <br />
                            <textarea class="ddcf_textarea_input" name="ddcf_email_footer_text" id="ddcf_email_footer_text" id="ddcf_email_footer_text" ><?php echo $email_footer_text; ?></textarea>
                        </div>
                        <br /><br />
                        <br /><br />
                    </div>
                </div>
                <!-- #accordion1 -->
            </div>
            <!-- tabs-1 -->
            <div id="tabs-2">
                <div id="accordion2">
                    <h3>User Feedback</h3>
                    <div>
                        <h4><?php _e('Form sent action', 'ddcf_plugin') ?></h4>
                        <?php
                            _e('Once the user has sucessfully sent their message, you can either send them to a new page or display a thank you messsage on the current page:', 'ddcf_plugin');
                            $thankyouType = get_option(ddcf_thankyou_type);
                            $thankyouMessage = get_option(ddcf_thankyou_message);
                            $thankyouURL = get_option(ddcf_thankyou_url);
                        ?>
                        <br /><br />
                        <input type="radio" name="ddcf_thankyou_type" id="ddcf_thankyou_type" value="ddcf_thankyou_message" <?php if ($thankyouType=='ddcf_thankyou_message') echo ' checked';?>>
                        <?php _e('Display thank you message:', 'ddcf_plugin') ?>
                        <br />
                        <textarea class="ddcf_textarea_input" name="ddcf_thankyou_message" id="ddcf_thankyou_message" value=""><?php echo $thankyouMessage; ?></textarea>
                        <br /><br />
                        <input type="radio" name="ddcf_thankyou_type" id="ddcf_thankyou_type" value="ddcf_thankyou_url" <?php if ($thankyouType=='ddcf_thankyou_url') echo ' checked';?>>
                        <?php _e('Display contact form thank you page. Enter URL here:', 'ddcf_plugin') ?>
                        <br />
                        <textarea class="ddcf_textarea_input" name="ddcf_thankyou_url" id="ddcf_thankyou_url" value="<?php echo $thankyouURL; ?>" ><?php echo $thankyouURL; ?></textarea>
                        <br /><br /><br />
                        <h4><?php _e('Error checking', 'ddcf_plugin') ?></h4>
                        <?php
                            _e('Choose whether to check for user input errors either on the fly (ticks and crosses) or on submit button press.', 'ddcf_plugin');
                            $error_checking_method = get_option(ddcf_error_checking_method);
                        ?>
                        <br /><br />
                        <input type="radio" name="ddcf_error_checking_method" id="ddcf_error_checking_method" value="realtime" <?php if ($error_checking_method=='realtime') echo ' checked';?>>
                        <?php _e('Check for errors on the fly', 'ddcf_plugin'); ?>
                        <br /><br />
                        <input type="radio" name="ddcf_error_checking_method" id="ddcf_error_checking_method" value="onsubmit" <?php if ($error_checking_method=='onsubmit') echo ' checked';?>>
                        <?php _e('Check for errors on form submit button press', 'ddcf_plugin'); ?>
                        <br /><br />
                        <br /><br />
                    </div>
                    <h3>Appearance</h3>
                    <div>
                        <h4><?php _e('Input CSS classes', 'ddcf_plugin') ?></h4>
                        <?php _e('CSS classes listed here will be applied to the text, date and dropdown form elements.', 'ddcf_plugin') ?>
                        <br />
                        <textarea class="ddcf_textarea_input" name="ddcf_input_css_classes" value="<?php echo get_option(ddcf_input_css_classes); ?>" id="ddcf_input_css_classes" ><?php echo get_option('ddcf_input_css_classes'); ?></textarea>
                        <br /><br />
                        <h4><?php _e('Button CSS classes', 'ddcf_plugin') ?></h4>
                        <?php _e('CSS classes listed here will be applied to the submit and reset buttons.', 'ddcf_plugin') ?>
                        <br />
                        <textarea class="ddcf_textarea_input" name="ddcf_button_css_classes" value="<?php echo get_option(ddcf_button_css_classes); ?>" id="ddcf_button_css_classes" ><?php echo get_option('ddcf_button_css_classes'); ?></textarea>
                        <br /><br />
                        <h4><?php _e('Button Styling', 'ddcf_plugin') ?></h4>
                        <?php _e('Choose between theme styled buttons or premade ones from the jQueryUI library.', 'ddcf_plugin') ?>
                        <br /><br />
                        <?php _e('Button styling:', 'ddcf_plugin') ?>
                        <select name="ddcf_button_styling" id="ddcf_button_styling">
                            <option value="ddcf_button_styling_themed" <?php if (get_option(ddcf_button_styling)!='ddcf_button_styling_jqueryui') echo 'selected';?>>WordPress</option>
                            <option value="ddcf_button_styling_jqueryui" <?php if (get_option(ddcf_button_styling)=='ddcf_button_styling_jqueryui') echo 'selected';?>>jQueryUI</option>
                        </select>
                        <br /><br />
                        <?php _e('<h4>jQuery UI Theme</h4>
                            Select a theme for jQueryUI widgets.
                            <br /><br />This only affects the datepickers and buttons. The buttons are only affected if jQueryUI button styling is selected above.<br /><br />Select a premade jQuery UI theme:', 'ddcf_plugin') ?>
                        <select name="ddcf_jqueryui_theme" id="ddcf_jqueryui_theme">
                            <option value="none" <?php if (get_option(ddcf_jqueryui_theme)=='none') echo 'selected';?>>None</option>
                            <option value="custom" <?php if (get_option(ddcf_jqueryui_theme)=='custom') echo 'selected';?>>Custom</option>
                            <option value="black-tie" <?php if (get_option(ddcf_jqueryui_theme)=='black-tie') echo 'selected';?>>Black Tie</option>
                            <option value="blitzer" <?php if (get_option(ddcf_jqueryui_theme)=='blitzer') echo 'selected';?>>Blitzer</option>
                            <option value="cupertino" <?php if (get_option(ddcf_jqueryui_theme)=='cupertino') echo 'selected';?>>Cupertino</option>
                            <option value="dark-hive" <?php if (get_option(ddcf_jqueryui_theme)=='dark-hive') echo 'selected';?>>Dark Hive</option>
                            <option value="dot-luv" <?php if (get_option(ddcf_jqueryui_theme)=='dot-luv') echo 'selected';?>>Dot Luv</option>
                            <option value="eggplant" <?php if (get_option(ddcf_jqueryui_theme)=='eggplant') echo 'selected';?>>Eggplant</option>
                            <option value="excite-bike" <?php if (get_option(ddcf_jqueryui_theme)=='excite-bike') echo 'selected';?>>Excite Bike</option>
                            <option value="flick" <?php if (get_option(ddcf_jqueryui_theme)=='flick') echo 'selected';?>>Flick</option>
                            <option value="hot-sneaks" <?php if (get_option(ddcf_jqueryui_theme)=='hot-sneaks') echo 'selected';?>>Hot Sneaks</option>
                            <option value="humanity" <?php if (get_option(ddcf_jqueryui_theme)=='humanity') echo 'selected';?>>Humanity</option>
                            <option value="le-frog" <?php if (get_option(ddcf_jqueryui_theme)=='le-frog') echo 'selected';?>>Le Frog</option>
                            <option value="mint-choc" <?php if (get_option(ddcf_jqueryui_theme)=='mint-choc') echo 'selected';?>>Mint Choc</option>
                            <option value="overcast" <?php if (get_option(ddcf_jqueryui_theme)=='overcast') echo 'selected';?>>Overcast</option>
                            <option value="pepper-grinder" <?php if (get_option(ddcf_jqueryui_theme)=='pepper-grinder') echo 'selected';?>>Pepper Grinder</option>
                            <option value="redmond" <?php if (get_option(ddcf_jqueryui_theme)=='redmond') echo 'selected';?>>Redmond</option>
                            <option value="smoothness" <?php if (get_option(ddcf_jqueryui_theme)=='smoothness'||get_option(ddcf_jqueryui_theme)=='') echo 'selected';?>>Smoothness</option>
                            <option value="south-street" <?php if (get_option(ddcf_jqueryui_theme)=='south-street') echo 'selected';?>>South Street</option>
                            <option value="start" <?php if (get_option(ddcf_jqueryui_theme)=='start') echo 'selected';?>>Start</option>
                            <option value="sunny" <?php if (get_option(ddcf_jqueryui_theme)=='sunny') echo 'selected';?>>Sunny</option>
                            <option value="swanky-purse" <?php if (get_option(ddcf_jqueryui_theme)=='swanky-purse') echo 'selected';?>>Swanky Purse</option>
                            <option value="trontastic" <?php if (get_option(ddcf_jqueryui_theme)=='trontastic') echo 'selected';?>>Trontastic</option>
                            <option value="ui-darkness" <?php if (get_option(ddcf_jqueryui_theme)=='ui-darkness') echo 'selected';?>>UI Darkness</option>
                            <option value="ui-lightness" <?php if (get_option(ddcf_jqueryui_theme)=='ui-lightness') echo 'selected';?>>UI Lightness</option>
                            <option value="vader" <?php if (get_option(ddcf_jqueryui_theme)=='vader') echo 'selected';?>>Vader</option>
                        </select>
                        <br /><br />
                        <br /><br />
                        <br /><br />
                    </div>
                </div>
                <!-- #accordion2 -->
            </div>
            <!-- tabs-2 -->
            <div id="tabs-3">
                <div id="accordion3">
                    <h3>Dates & Times</h3>
                    <div>
                        <div style="width:100%; height: 96px; margin-top:20px;">
                            <div style="width:50%; float:left;">
                                <?php _e('<h4>Start Date Date / Time</h4>', 'ddcf_plugin'); ?>
                                <input type="checkbox" id="ddcf_start_date_check" name="ddcf_start_date_check" value="ddcf_start_date_check" <?php if (get_option('ddcf_start_date_check')) echo ' checked '; ?>>&nbsp;<?php _e('Request start date', 'ddcf_plugin'); ?>
                                <br /><br />
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="ddcf_time_with_date_check" name="ddcf_time_with_date_check" value="ddcf_time_with_date_check" <?php if (get_option('ddcf_time_with_date_check')) echo ' checked '; ?>>&nbsp;<?php _e('Request times', 'ddcf_plugin'); ?>
                                <br /><br />
                            </div>
                            <div style="width:50%; float:left;">
                                <?php _e('<h4>End Date Date / Time</h4>', 'ddcf_plugin'); ?>
                                <input type="checkbox" id="ddcf_end_date_check" name="ddcf_end_date_check" value="ddcf_end_date_check" <?php if (get_option('ddcf_end_date_check')) echo ' checked '; ?>>&nbsp;<?php _e('Request booking end date', 'ddcf_plugin'); ?>
                            </div>
                        </div>
                        <br /><br /><br />
                        <div  style="width:100%; height:65px;;">
                            <?php _e('<h4>Required Field</h4>', 'ddcf_plugin'); ?>
                            <input type="checkbox" id="ddcf_dates_compulsory_check" name="ddcf_dates_compulsory_check" value="ddcf_dates_compulsory_check" <?php if (get_option('ddcf_dates_compulsory_check')) echo ' checked '; ?>>
                            <?php _e('Dates are a required field', 'ddcf_plugin'); ?>
                        </div>
                        <br /><br />
                        <div>
                            <?php _e('<h4>Apply to posts in a specific category</h4>', 'ddcf_plugin'); ?>
                            <?php _e('You may want to only ask for dates on posts with a certain category. You can specify which category here.<br /><br />', 'ddcf_plugin'); ?>
                            <input type="checkbox" id="ddcf_dates_category_filter_check" name="ddcf_dates_category_filter_check" value="ddcf_dates_category_filter_check" <?php if (get_option('ddcf_dates_category_filter_check')) echo ' checked '; ?>>&nbsp;
                            <?php
                                _e('Apply these settings to only one category: ', 'ddcf_plugin');
                                wp_dropdown_categories(array('hide_empty' => 0, 'name' => 'ddcf_dates_category_filter', 'id' => 'ddcf_dates_category_filter', 'class' => 'ddcf-category-filter-check', 'orderby' => 'name', 'selected' => get_option('ddcf_dates_category_filter'), 'hierarchical' => true, 'show_option_none' => __('None')));
                                ?>
                        </div>
                    </div>
                    <h3>Numbers dropdowns (e.g. Party Size)</h3>
                    <div>
                        <div style="width:100%; height:250px; margin-top:0.7em;">
                            <?php _e('<h4>Request Numbers (e.g.Party Size)</h4>', 'ddcf_plugin'); ?>
                            <?php _e('Collect numbers - e.g. If your contact form is being used to collect bookings, you may, for example, want to request the number of people for a restaurant or hotel room booking.', 'ddcf_plugin'); ?>
                            <br /><br />
                            <input type="checkbox" id="ddcf_extra_dropdown_one_check" name="ddcf_extra_dropdown_one_check" value="ddcf_extra_dropdown_one_check" <?php if (get_option('ddcf_extra_dropdown_one_check')) echo ' checked '; ?>>&nbsp;<?php _e('Include dropdown menu 1', 'ddcf_plugin');?>
                            <br />
                            <br />
                            <input class="ddcf_text_input" type="text" name="ddcf_extra_dropdown_one_label" id="ddcf_extra_dropdown_one_label" value="<?php echo get_option('ddcf_extra_dropdown_one_label'); ?>" />
                            <br />
                            <br />
                            <input type="checkbox" id="ddcf_extra_dropdown_two_check" name="ddcf_extra_dropdown_two_check" value="ddcf_extra_dropdown_two_check" <?php if (get_option('ddcf_extra_dropdown_two_check')) echo ' checked '; ?>>&nbsp;<?php _e('Include dropdown menu 2', 'ddcf_plugin'); ?>
                            <br />
                            <br />
                            <input class="ddcf_text_input" type="text" name="ddcf_extra_dropdown_two_label" id="ddcf_extra_dropdown_two_label" value="<?php echo get_option('ddcf_extra_dropdown_two_label'); ?>" />
                        </div>
                        <br /><br />
                        <div  style="width:100%; margin-top: 3em;;">
                            <?php _e('<h4>Required Field</h4>', 'ddcf_plugin'); ?>
                            <input type="checkbox" id="ddcf_numbers_compulsory_check" name="ddcf_numbers_compulsory_check" value="ddcf_numbers_compulsory_check" <?php if (get_option('ddcf_numbers_compulsory_check')) echo ' checked '; ?>>
                            <?php _e('Numbers is a required field', 'ddcf_plugin'); ?>
                        </div>
                        <br /><br />
                        <div>
                            <?php _e('<h4>Apply to posts in a specific category</h4>', 'ddcf_plugin'); ?>
                            <?php _e('You may want to only ask for party sizes on posts with a certain category. You can specify which category here.<br /><br />', 'ddcf_plugin'); ?>
                            <input type="checkbox" id="ddcf_dropdown_category_filter_check" name="ddcf_dropdown_category_filter_check" value="ddcf_dropdown_category_filter_check" <?php if (get_option('ddcf_dropdown_category_filter_check')) echo ' checked '; ?>>&nbsp;
                            <?php
                                _e('Apply these settings to only one category: ', 'ddcf_plugin');
                                wp_dropdown_categories(array('hide_empty' => 0, 'name' => 'ddcf_numbers_category_filter', 'id' => 'ddcf_numbers_category_filter', 'class' => 'ddcf-category-filter-check', 'orderby' => 'name', 'selected' => get_option('ddcf_numbers_category_filter'), 'hierarchical' => true, 'show_option_none' => __('None')));
                            ?>
                        </div>
                    </div>
                    <?php _e('<h3>Additional Questions</h3>', 'ddcf_plugin'); ?>
                    <div>
                        <?php _e('If you want to add extra questions to the contact form, you can specify them here.', 'ddcf_plugin'); ?>
                        <br /><br />
                        <h4>Optional Questions</h4>
                        <input type="checkbox" id="ddcf_extra_question_one_check" name="ddcf_extra_question_one_check" value="ddcf_extra_question_one_check" <?php if (get_option('ddcf_extra_question_one_check')) echo ' checked '; ?>>&nbsp; <?php _e('Include question 1'); ?>
                        <textarea class="ddcf_textarea_input" name="ddcf_extra_question_one" value="<?php echo get_option('ddcf_extra_question_one'); ?>" id="ddcf_extra_question_one" ><?php echo get_option('ddcf_extra_question_one'); ?></textarea>
                        <br />
                        <br />
                        <input type="checkbox" id="ddcf_extra_question_two_check" name="ddcf_extra_question_two_check" value="ddcf_extra_question_two_check" <?php if (get_option('ddcf_extra_question_two_check')) echo ' checked '; ?>>&nbsp;<?php _e('Include question 2'); ?>
                        <textarea class="ddcf_textarea_input" name="ddcf_extra_question_two" value="<?php echo get_option('ddcf_extra_question_two'); ?>" id="ddcf_extra_question_two" ><?php echo get_option('ddcf_extra_question_two'); ?></textarea>
                        <br />
                        <br /><br />
                        <div>
                            <?php _e('<h4>Required Field</h4>', 'ddcf_plugin'); ?>
                            <input type="checkbox" id="ddcf_questions_compulsory_check" name="ddcf_questions_compulsory_check" value="ddcf_questions_compulsory_check" <?php if (get_option('ddcf_questions_compulsory_check')) echo ' checked '; ?>>
                            <?php _e('Additional questions are a required field', 'ddcf_plugin'); ?>
                        </div>
                        <div>
                            <?php _e('<h4>Apply to posts in a specific category</h4><br />', 'ddcf_plugin'); ?>
                            <?php _e('You may want to only add extra question requests to posts within a certain category. You can specify which category here.<br /><br />', 'ddcf_plugin'); ?>
                            <input type="checkbox" id="ddcf_extra_questions_category_filter_check" name="ddcf_extra_questions_category_filter_check" value="ddcf_extra_questions_category_filter_check" <?php if (get_option('ddcf_extra_questions_category_filter_check')) echo ' checked '; ?>>&nbsp;
                            <?php
                                _e('Apply these settings to only one category:', 'ddcf_plugin');
                                wp_dropdown_categories(array('hide_empty' => 0, 'name' => 'ddcf_extra_questions_category_filter', 'id' => 'ddcf_extra_questions_category_filter', 'class' => 'ddcf-category-filter-check', 'orderby' => 'name', 'selected' => get_option('ddcf_extra_questions_category_filter'), 'hierarchical' => true, 'show_option_none' => __('None')));
                            ?>
                        </div>
                        <br /><br />
                    </div>
                </div>
                <!-- #accordion3 -->
            </div>
            <!-- tabs-3 -->
            <div id="tabs-4">
                <br />
                <?php _e('CSS entered here will only be applied to pages containing a contact form.'); ?>
                <br /><br />
                Use custom CSS: <input type="checkbox" id="ddcf_custom_css_check" name="ddcf_custom_css_check" value="ddcf_custom_css_check" <?php if (get_option('ddcf_custom_css_check')) echo ' checked '; ?>><br /><br />
                <div id="ddcf_custom_css_container">
                    <div id="ddcf_options_feedback"></div>
                    <div id="ddcf_options_feedback_text"></div>
                    <textarea class="ddcf_textarea_input" name="ddcf_custom_css" value="" id="ddcf_custom_css">
                        <?php echo $custom_css->custom_css_text; ?>                        
                    </textarea>
                </div>
                <br />
                <div id="ddcf_update_css_btn_container">
                    <input id="ddcf_update_css_btn" name="ddcf_update_css_btn" type="button" class="button button-primary" value="<?php _e('Update CSS','ddcf_plugin') ?>" action="the_ajax_hook">
                </div>
                <!-- this div holds the inputs needed for the ajax call to update the css -->
                <!-- however, the inputs will conflict with the normal operation of the settings form -->
                <!-- so we hide this div using jQuery until there is an 'Update CSS' button press -->
                <!-- after which hide the div again when the ajax call returns -->
                <div id="ddcf_sneaky_ajax" name="ddcf_sneaky_ajax">
                    <input type="hidden" name="ddcf_session" id="ddcf_session" value="ddcf_options_session" />
                    <input type="hidden" name="action" value="the_ajax_hook" />
                    <?php wp_nonce_field('ddcf_update_css_action'); ?>
                </div>
            </div>
            <!-- tabs-4 -->
    </div>
    <!-- tabs -->
    <?php submit_button(); ?>
    </form>
</div>
<!-- wrap -->