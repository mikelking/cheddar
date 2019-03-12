/**
 * Adds tab functionality for admin page.
 *
 * @package rkv-admin
 */

(function($) {

	'use strict';

	var RkvAdminTabs = {

		el: {},

		init: function() {
			RkvAdminTabs.el.tabs = $( '.rkv-nav-tab' );

			RkvAdminTabs.bindActions();
		},

		bindActions: function() {
			RkvAdminTabs.el.tabs.on( 'click', RkvAdminTabs.maybeChangeTab );
		},

		maybeChangeTab: function( e ) {
			e.preventDefault();

			RkvAdminTabs.el.tab = $( this );

			if ( RkvAdminTabs.el.tab.hasClass( 'nav-tab-active' ) ) {
				return;
			}

			RkvAdminTabs.el.currentTab    = $( '.rkv-nav-tab.nav-tab-active' );
			RkvAdminTabs.el.currentTarget = $( RkvAdminTabs.el.currentTab.attr( 'href' ) );
			RkvAdminTabs.el.target        = $( RkvAdminTabs.el.tab.attr( 'href' ) );

			RkvAdminTabs.hideCurrent();
			RkvAdminTabs.changeTab();
			RkvAdminTabs.showTarget();
		},

		hideCurrent: function() {
			RkvAdminTabs.el.currentTarget.hide();
		},

		changeTab: function() {
			RkvAdminTabs.el.currentTab.removeClass( 'nav-tab-active' );
			RkvAdminTabs.el.tab.addClass( 'nav-tab-active' );
		},

		showTarget: function() {
			RkvAdminTabs.el.target.show();
		}

	}

	$( document ).ready(
		function() {
			RkvAdminTabs.init()
		}
	);

})( jQuery );