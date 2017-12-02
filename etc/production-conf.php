<?php

/*
	This file contains the configuration specific to the production site
	@version 1.3
*/
require( 'server-conf-base.php' );

class ServerConfig extends ServerConfigBase {
	const SITENAME         = 'olivent.net';
	const SITE_SALT        = 'olivent_rule$';
	const PROTOCOL         = 'http';
	const PROTOCOL_DELIM   = '://';
	const DEFAULT_TIMEZONE = 'America/New_York';

	// DB Access
	const DB_PASSWORD = 'P0pc0rn78%32';
	const DB_USER     = 'oliventdbadm';
	const DB_NAME     = 'olivent_network';
	const DB_HOST     = 'localhost';

	// Caching
	const WP_CACHE = true;

	// Debugging
	const DEBUG        = false;
	const LOG_ERRORS   = false;
	const SHOW_ERRORS  = false;
	const SCRIPT_DEBUG = false;
	const SAVE_QUERIES = false;

	// Setup Logging
	const REPORTING_LEVEL  = 0;
	const ERROR_LEVEL      = 'E_ALL';

	// CMS Settings
	const AUTO_SAVE_DELAY       = 86400; // seconds
	const WP_TURN_OFF_ADMIN_BAR = false;
	const WP_POST_REVISIONS     = 10;

	// File System
	const BLOCK_FILE_EDITS = false;
	const BLOCK_FILE_MODS  = false;
	const FS_CHMOD_DIR     = 0775;
	const FS_CHMOD_FILE    = 0664;
	const DEFAULT_UMASK    = 0002;

	// System Updates
	const AUTOMATIC_UPDATER_DISABLED = true;
	const WP_AUTO_UPDATE_CORE        = false;

	// Security SSL not necessary if you entire site is HTTPS
	const COOKIE_DOMAIN = '';
	const FORCE_SSL_LOGIN = false;
	const FORCE_SSL_ADMIN = false;

	// Performance
	const WP_MEMORY_LIMIT     = '512M';
	const CONCATENATE_SCRIPTS = false;

	// MulitSite
	const MULTISITE           = true;
	const WP_ALLOW_MULTISITE  = true;
	const SUBDOMAIN_INSTALL   = true;
	const DOMAIN_CURRENT_SITE = 'olivent.net';
	const PATH_CURRENT_SITE   = '/';
	const SITE_ID_CURRENT_SITE = 1;
	const BLOG_ID_CURRENT_SITE = 1;

	// Misc
	const SUNRISE         = false;
	const DISABLE_WP_CRON = false;

}
