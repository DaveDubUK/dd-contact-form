=== DD Contact Form ===
Contributors: Davedub
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DSNPQBLWGLFMJ
Tags: contact,form,contact form,davedub,contacts management
Requires at least: 3.0.1
Tested up to: 4.4.2
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The DD Contact Form plugin for WordPress is a fully responsive, feature rich and easy to use contact form designed with a focus on usability.

== Description ==

Apologies for missing the support requests recently, for some reason wordpress.org was set to NOT send me email notifications. I have now updated this.

**DD Contact Form** by **Dave Wooldridge**

* easy to use, works straight out of the box, just add shortcode
* easy to set up, no registration
* integrated captcha options
* integrated custom css editor
* built in contact manager system (working, but under development)

Simple to use and highly configurable, DD Contact Form gives your website visitors a clear, easy to fill in contact form designed to make the message sending process as painless as possible. The form is fully responsive, automagically optimising the layout for even the smallest of screens. The form's look and feel follows the underlying theme as close as possible, but customisations are easy using the built in css editor and appearance settings. The captcha options offer a choice of built in Simple Addition (works with no sign up) or Google reCaptcha (Google reCaptcha id required). Also integrated into the plugin is a basic but functional contacts management system. Other features include: multiple contact form recipient email addresses, additional question options, date fields and more.

**General Settings**

Set, edit or view email settings. View or change captcha settings in security settings section. Enable user details logging in privacy settings section.

Security

Select captcha type: simple addition (default) or google reCaptcha. To use the Google reCaptcha, you will need to sign up and get private and public keys. Signup is extremely quick and easy. You can sign up [here] (https://www.google.com/recaptcha/admin#whyrecaptcha "google recaptcha signup")

Privacy

Offer to save user permission to send newsletters or other updates in the future.
Save the IP address and geolocation information for each contact form user. Please ensure there are no legal issues using this in your area before enabling.
To use the geolocation services, you will need to register with IPinfoDB to get a key. Registration is relatively straightforward. You can get an account [here] (http://www.ipinfodb.com/register.php "IPinfoDB signup")

Email

Set recipient email addresses for receiving contact form enquiries.
Configure the sending of a confirmation email to the user once the system has received the form.
Specify an image to display at the top of emails sent by the contact form. The URL should be in absolute format.

**User Interface Settings**

User Feedback

There are two options in the user feedback section.

Form sent action

Once the user has successfully sent their message, you can either send them to a new page or display a thank you messsage on the current page. This section allows you to specify the custom thank you message and the url to jump to.

Error Checking

The form can be checked for errors in one of two ways. Either the form is checked on the fly, whereby users are shown a green tick or red cross beside wach field, or the form is checked when the user presses the 'Send' button. Note that the form data is effectively checked twice when the on the fly option is selected, as the form will always check the form data before sending in either case.

Appearance

This section contains various options for controlling the look and feel of the contact form.

Custom CSS Classes

Note that there are at least two ways to specify css for the form and it's elements; you can specify custom css classes supplied by your theme / child theme or you can use the Custom CSS editor (DD Contact From Settings, 4th tab). If you would prefer to specify custom classes you can do so here.

Button Styling

If your theme already applies appropriate styling to your contact form buttons then leave this option on the default (WordPress). However, if you'd like to use the jQuery-UI library to style your buttons, then select the jQueryUI option here.

jQuery UI Theme

Select a theme for jQueryUI widgets. The setting only affects the datepickers and buttons and the buttons are only affected if jQueryUI button styling is selected in 'Button Styling'.

**Extra Information Settings**

You can request extra information from the contact form user. Each of the extra information types can be either obligatory or optional. If you only want to ask for these details on posts that are in a certain category, you can specify the category here.

Dates & Times

You may want to request a date or arrival / departure dates. For example a restaurant or hotel room booking. You can ask for arrival and / or departure dates on the contact form using these settings. Optionally, you can also ask for arrival / departure times.

Party Size

If your contact form is being used to collect bookings, you may want to request the number of people. For example a restaurant or hotel room booking. You can ask for the number of adults and children on the contact form using these settings.

Additional Questions

If you want to add extra questions for the user to answer you can specify them here.

**CSS Editor**

The built is CSS editor allows you to add custom CSS to the form. Once edited, the CSS can be updated immediately using the 'Update CSS' button. You can also use the 'Save Changes' button to update the CSS, but you'll have to wait for WordPress to reload the entire page every time you make an edit.

Commonly styled classes

.ddcf_button

This class will style the buttons on the form

.ddcf_input_base

This class will style all the text inputs on the form: name, email, subject and message. If the relevant fields are enabled in the settings, it will also style the simple add captcha, date/time picker displays and extra question fields.

.ddcf_dropdown

This class will style the party size dropdowns (if enabled in the Extra Information section).

**Contact Manager**

The built in contact manager allows you to instantly recall details of people who have previously contacted you through the contact form. To do this, the contact form logs each contact form enquiry, along with metadata to the WordPress database. The details can then be retrieved using a page only accessible to users whom are both registered on the site (Administrator or Editor) and logged on).

Using the manager page

To use the manager page, create a new page and add the following shortcode:

[dd_manager_page]

Although the page is viewable only by registered users (Administrator or Editor), it is recommended that the page be published privately to provide two layers of protection for your client details.

**FAQ**

Q: Why doesn't the form work for yahoo.com email addresses? 
A: Pleae see here: https://wordpress.org/support/topic/yahoo-senders-not-going-through?replies=21

**About DD Contact Form**

Whilst working on WordPress websites for friends, I found I was unable to find a free contact form plugin that had all the features I needed. The ones that came with themes I'd bought were not so good either, so I started making my own. After 18 months evolution, the plugin was ready. I decided to release it open source, as I feel I've benefited greatly from the community over the years. I hope you find it useful. Please report any bugs [here](https://groups.google.com/forum/#!forum/dd-contact-form "Google group for DD Contact Form").

Dave Wooldridge

April 2014


== Installation ==

How to install the DD Contact Form plugin and get it working.

1. Upload the `dd-contact-form` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. (not essential) You can set the form's settings via the WordPress Settings Menu -> DD Contact Form
1. Place shortcode [dd_contact_form] on the pages / posts where you want the form to appear

== Frequently Asked Questions ==

= How do I get started? =

Install and activate the plugin, the place shortcode [dd_contact_form] on the pages / posts where you want the form to appear


== Screenshots ==

1. DD Contact Form on the 2014 theme with all extra fields enabled
2. DD Contact Form on the 2012 theme with only simple captcha enabled
3. DD Contact Form on the Expound theme with google recaptcha enabled
4. DD Contact Form on the X2 theme with only simple captcha enabled
5. DD Contact Form contact manager page
6. DD Contact Form settings pages

== Changelog ==

= 1.000 =
* Initial release

= 1.100 =

* Prevented (green) tick marks being replaced by (black) emoji characters on some browsers
* Updated default email address to be site admin address on fresh install
* Fixed google recaptcha formatting issues
* Added setting to disable email footer text
* Fixed form not clearing posted values on refresh after submission
* Fixed issues with single required question (https://wordpress.org/support/topic/two-bugs-with-optional-questions?replies=1)
* Fixed issues with single date settings (https://wordpress.org/support/topic/dates-times-not-working?replies=2)
* Widespread code layout / formatting improvements
* Fixed minor plugin conflict with Photocrati / nextgen-gallery-pro eCommerce solution
* Prevent form from being displayed on blog listings
 
== Upgrade Notice ==

Please check your settings after upgrade.

...

