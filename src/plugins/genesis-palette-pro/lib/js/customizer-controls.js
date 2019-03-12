/**
 * Handles custom controls for DPP.
 *
 * @package smart-passive-income-pro
 */

/* globals dppControlIDs */

( function( $, api ) {

	'use strict';

	var DPP_Range = {

		/**
		 * DOM elements used.
		 */
		el: {},

		/**
		 * The current val.
		 */
		val: '',

		/**
		 * Initialize all the things.
		 */
		init: function() {
			DPP_Range.bind_actions();
		},

		/**
		 * Binds the actions.
		 */
		bind_actions: function() {
			jQuery('body').on( 'input', '.customize-control-dpp-range input', DPP_Range.change );
		},

		/**
		 * Changes the range value.
		 */
		change: function() {
			DPP_Range.el.input = $( this );
			DPP_Range.val      = DPP_Range.el.input.val();

			DPP_Range.el.input.next( '.dpp-range-output' ).find( 'span' ).text( DPP_Range.val );
			DPP_Range.el.input.val( val );
		}
	},
	DPP_Responsive = {

		previewDevice : '',
		control:        '',
		devices:        [],

		/**
		 * Initialize all the things.
		 */
		init: function() {
			DPP_Responsive.bind_actions();
		},

		/**
		 * Binds the actions.
		 */
		bind_actions: function() {
			api.state( 'expandedSection' ).bind( DPP_Responsive.expandedSection );
			api.previewedDevice.bind( DPP_Responsive.setControlActiveStates );
		},

		/**
		 * Action on the expandedSection state ensures the dpp responsive classes are setup.
		 */
		expandedSection: function() {
			if ( $( '.dpp-control-desktop' ).length === 0 ) {
				_.each(dppControlIDs, function (controls, deviceSlug) {
					DPP_Responsive.devices.push(deviceSlug);
					_.each(controls, function (controlID) {
						$('#customize-control-' + controlID).addClass('dpp-control-' + deviceSlug);
					});
				});
			}

			DPP_Responsive.setControlActiveStates( api.previewedDevice.get() );
		},

		/**
		 * Shows/Hides responsive controls when the preview state changes.
		 *
		 * @param previewedDevice
		 */
		setControlActiveStates: function( previewedDevice ) {
			if ( 'undefined' === typeof previewedDevice ) {
				previewedDevice = 'desktop';
			}

			_.each( DPP_Responsive.devices, function( deviceSlug ) {

				if ( deviceSlug !== previewedDevice ) {
					$( '.dpp-control-' + deviceSlug ).hide();
				}

			} );

			$( '.dpp-control-' + previewedDevice ).show();
		}
	};

	$( document ).ready( function() {
		DPP_Range.init();
		DPP_Responsive.init();
	} );

} )( jQuery, wp.customize );
