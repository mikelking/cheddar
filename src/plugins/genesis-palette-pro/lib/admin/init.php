<?php
/**
 * Genesis Design Palette Pro - Require the admin files.
 *
 * @package Design Palette Pro
 */

/*
	Copyright 2014-2018 Reaktiv Studios

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; version 2 of the License (GPL v2) only.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

namespace DPP\Admin;

// The admin settings..
Add_On::get_instance();
Design::get_instance()->init();
License::get_instance()->init();
Settings::get_instance();
Customizer::get_instance()->init();
Support::get_instance();
Utilities::get_instance();

/**
 * This loads before the class-setup.php file.
 *
 * Use this hook to add any file that will extend DPP\Admin\Base or otherwise modify filters for DPP\Admin\Setup.
 *
 * @since 1.4.0
 */
do_action( 'dpp_before_admin_setup' );

// The Setup Class that ties it all together.
Setup::get_instance()->init();
