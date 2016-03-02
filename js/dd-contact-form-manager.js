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

var gLastSearch = '';
var gLastQuery;
var gLastAction;
var gResultsPerPage = 10;
var gGreyOut = '#cccccc';

function ddcfSearch(str) {
	gLastQuery = str;
	gLastAction = jQuery('#ddcf_action').val();
	ddcfShowUser(str);
}
function ddcfShowMore() {
	if (gLastQuery) {
		jQuery('#ddcf_action').val(gLastAction);
		ddcfShowUser(gLastQuery);
	}
}

function ddcfResetButtons() {
	jQuery('.ddcf_button').prop('disabled', true)
                          .click(function(event) {
                                event.preventDefault();
                            });
}

jQuery(document).ready(function ($) {

	/* initialise */
    ddcfResetButtons();

	/* previous and next results page links  */
	jQuery('#ddcf_previous_page').css('color', gGreyOut)
                                 .click(function() {
		var cursorType = jQuery(this).css('cursor');
		if (cursorType === 'pointer') {
			ddcfResetButtons();
			var currentValue = parseInt(jQuery('#ddcf_results_offset').val(), 10);
			currentValue -= parseInt(gResultsPerPage, 10);
			if (currentValue<0) currentValue = 0;
			jQuery('#ddcf_results_offset').val(currentValue);
            jQuery('#ddcf_action').val('ddcf_search_action');
			ddcfShowMore();
		}
	});
	jQuery('#ddcf_next_page').css('color', gGreyOut)
                             .click(function() {
		var cursorType = jQuery(this).css('cursor');
		if (cursorType === 'pointer') {
			ddcfResetButtons();
			var currentValue = parseInt(jQuery('#ddcf_results_offset').val(), 10);
			currentValue += parseInt(gResultsPerPage, 10);
            if (currentValue > jQuery('#ddcf_total_num_results').val()) currentValue -= gResultsPerPage;
			jQuery('#ddcf_results_offset').val(currentValue);
			jQuery('#ddcf_action').val('ddcf_search_action');
			ddcfShowMore();
		}
	});

	jQuery("#ddcf_action").val('');
	jQuery("#ddcf_action_arg").val('');
	jQuery("#ddcf_num_results").val(gResultsPerPage);
    jQuery("#ddcf_results_offset").val('0');

	ddcfInitialiseManagerSession();

	// monitor the search box for changes when in focus
	gLastSearch = jQuery('#ddcf_search_form').val();
	var intervalID = setInterval(function() {
		var currentSearch=jQuery('#ddcf_search_form').val();
		if (currentSearch !== gLastSearch) {
			ddcfResetButtons();
			jQuery('#ddcf_action').val('ddcf_search_action');
			jQuery('#ddcf_results_offset').val(0);
			var searchString = jQuery('#ddcf_search_form').val();
			ddcfSearch(searchString);
		}
		gLastSearch = jQuery('#ddcf_search_form').val();
    }, 600); // 600 ms check

    jQuery("#ddcf_create_contact_dialog").dialog({
			autoOpen: false,
			height: 462,
			width: 300,
			modal: true,
			buttons: {
				Cancel: function () {
                    jQuery(this).dialog("close");
				},
				"Create New Contact": function () {
                    jQuery(this).dialog("close");
				}
			},
			open: function() {
				// get currently selected contact-id

				//alert("Contact ID is "+gCurrentContactID.toString());
			},
			close: function () {
				////allFields.val("").removeClass("ui-state-error");
			}
	});

    jQuery("#ddcf_edit_contact_dialog").dialog({
        autoOpen: false,
        height: 485,
        width: 300,
        modal: true,
        buttons: {
            Cancel: function () {
                jQuery(this).dialog("close");
            },
            "Update Contact": function () {
                jQuery(this).dialog("close");
            }
        },
        open: function() {
            // get currently selected contact-id

            //alert("Contact ID is "+gCurrentContactID.toString());
        },
        close: function () {
            ////allFields.val("").removeClass("ui-state-error");
        }
	});

    jQuery("#ddcf_add_contact_info_dialog").dialog({
        autoOpen: false,
        height: 485,
        width: 300,
        modal: true,
        buttons: {
            Cancel: function () {
                jQuery(this).dialog("close");
            },
            "Update Contact": function () {
                jQuery(this).dialog("close");
            }
        },
        open: function() {
            // get currently selected contact-id

            //alert("Contact ID is "+gCurrentContactID.toString());
        },
        close: function () {
            ////allFields.val("").removeClass("ui-state-error");
        }
	});

    jQuery("#ddcf_add_contact_rel_dialog").dialog({
        autoOpen: false,
        height: 485,
        width: 300,
        modal: true,
        buttons: {
            Cancel: function () {
                jQuery(this).dialog("close");
            },
            "Update Contact": function () {
                jQuery(this).dialog("close");
            }
        },
        open: function() {
            // get currently selected contact-id

            //alert("Contact ID is "+gCurrentContactID.toString());
        },
        close: function () {
            ////allFields.val("").removeClass("ui-state-error");
        }
	});

    jQuery("#ddcf_add_contact_note_dialog").dialog({
        autoOpen: false,
        height: 485,
        width: 300,
        modal: true,
        buttons: {
            Cancel: function () {
                jQuery(this).dialog("close");
            },
            "Update Contact": function () {
                jQuery(this).dialog("close");
            }
        },
        open: function() {
            // get currently selected contact-id

            //alert("Contact ID is "+gCurrentContactID.toString());
        },
        close: function () {
            ////allFields.val("").removeClass("ui-state-error");
        }
	});
});