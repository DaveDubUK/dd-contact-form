<?php
/*
Plugin Name: DD Contact Form
Plugin URI: http://davedub.co.uk
Description: Both simple to use and highly configurable, DD Contact Form gives your customers a clear, easy to fill in contact form designed to make the message sending process as painless as possible. Also integrated into the plugin is a basic but functional contacts management system. The form look and feel is highly customisable, with the choice of built in css styles or your own. The captcha options offer a choice of built in Simple Addition or Google reCaptcha (Google reCaptcha id required). Other features include: multiple contact form recipient emails, additional questions, date fields and more. This is my first WP plugin, and first open source project, so any constructive criticism gratefully received.
Version: 0.9b
Author: David Wooldridge
Author URI: http://davedub.co.uk
License: GNU GPL
*/

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

// include all the extra functions
include 'inc/functions.php';

// add the shortcode support for [dd_contact_form]
add_shortcode('dd_contact_form', 'ddcf_contact_form');

// add the shortcode support for [dd_management_page]   
add_shortcode('dd_management_page', 'ddcf_management_page');

// load up scripts and styles for front end
add_action('wp_enqueue_scripts', 'ddcf_enqueue_front_end_pages');

// load up scripts and styles for back end
add_action('admin_enqueue_scripts', 'ddcf_enqueue_back_end_pages');

// hook into the 'admin_menu' action to add settings page to WP Settings menu
add_action( 'admin_menu', 'add_ddcf_options_to_menu' );

// call register settings and enqueue scripts / styles function
add_action( 'admin_init', 'ddcf_admin_init' );

// plugin activation, deactivation and uninstall
register_activation_hook(__FILE__,'dd_contact_form_activation');
//register_deactivation_hook(__FILE__,'dd_contact_form_deactivation'); // not currently required
register_uninstall_hook(__FILE__,'dd_contact_form_uninstall');
?>