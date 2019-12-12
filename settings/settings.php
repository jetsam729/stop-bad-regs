<?php
/* translated: full */

// this is the new settings pages for Stop Spammers
// this is loaded only when users who can change settings are logged in
if ( ! defined( 'ABSPATH' ) ) die;


function ss_admin_menu_l() {
	$icon2   = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAAAAACo4kLRAAAA5UlEQVQY02P4DwS/251dwMC5/TeIzwASa4rcDAWRTb8hgkhiUFEGVDGIKAOaGFiUoR1NDCjazuC8uTusc2l6evrkNclJq9elZzRtdmZwWSPkxtNvxmlU76SqabWSw4Sz14XBZbb8qoIFm2WXreZfs15wttRmv2yg4CYVzpDNQMHpWps36zcLZEjXAwU3r8oRbgMKTlHZvFm7lcMoeBNQsNlks2sZUHAV97wlPAukgNYDBdeIKnAvBApuDucTCFgJEXTevKh89ubNEzZs3tzWvHlDP1DQGbvjsXoTa4BgDzrsgYwZHQBqzOv51ZaiYwAAAABJRU5ErkJggg==';
	$iconpng = SS_PLUGIN_URL . 'images/sticon.png';
	add_menu_page(
		"Stop Spammers",	// $page_title,
		"Stop Spammers",	// $menu_title,
		'manage_options',	// $capability,
		'stop_spammers',	// $menu_slug,
		'ss_summary',		// $function
		$iconpng,		// $icon_url,
		78.92			// $position
	);

	if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'protect' ) ) {
		return;
	}

	add_submenu_page(
		'stop_spammers',
		__('Stop Spammers — Summary',SFS_TXTDOMAIN),
		__('Summary',SFS_TXTDOMAIN),
		'manage_options',
		'stop_spammers',
		'ss_summary'
	);
	add_submenu_page(
		'stop_spammers',
		__('Stop Spammers — Protection Options',SFS_TXTDOMAIN),
		__('Protection Options',SFS_TXTDOMAIN),
		'manage_options',
		'ss_options',
		'ss_options'
	);
	add_submenu_page(
		'stop_spammers',
		__('Stop Spammers — Allow Lists',SFS_TXTDOMAIN),
		__('Allow Lists',SFS_TXTDOMAIN),
		'manage_options',
		'ss_allow_list',
		'ss_allowlist_settings'
	);
	add_submenu_page(
		'stop_spammers',
		__('Stop Spammers — Block Lists',SFS_TXTDOMAIN),
		__('Block Lists',SFS_TXTDOMAIN),
		'manage_options',
		'ss_deny_list',
		'ss_denylist_settings'
	);
	add_submenu_page(
		'stop_spammers',
		__('Stop Spammers — Challenge and Deny',SFS_TXTDOMAIN),
		__('Challenge &amp; Deny',SFS_TXTDOMAIN),
		'manage_options',
		'ss_challenge',
		'ss_challenges'
	);
	add_submenu_page(
		'stop_spammers',
		__('Stop Spammers — Allow Requests',SFS_TXTDOMAIN),
		__('Allow Requests',SFS_TXTDOMAIN),
		'manage_options',
		'ss_allowrequests',
		'ss_allowreq'
	);
	add_submenu_page(
		'stop_spammers',
		__('Stop Spammers — Web Services',SFS_TXTDOMAIN),
		__('Web Services',SFS_TXTDOMAIN),
		'manage_options',
		'ss_webservices_settings',
		'ss_webservices_settings'
	);
	add_submenu_page(
		'stop_spammers',
		__('Stop Spammers — Cache',SFS_TXTDOMAIN),
		__('Cache',SFS_TXTDOMAIN),
		'manage_options',
		'ss_cache',
		'ss_cache'
	);
	add_submenu_page(
		'stop_spammers',
		__('Stop Spammers — Log Report',SFS_TXTDOMAIN),
		__('Log Report',SFS_TXTDOMAIN),
		'manage_options',
		'ss_reports',
		'ss_reports'
	);
	add_submenu_page(
		'stop_spammers',
		__('Stop Spammers — Diagnostics',SFS_TXTDOMAIN),
		__('Diagnostics',SFS_TXTDOMAIN),
		'manage_options',
		'ss_diagnostics',
		'ss_diagnostics'
	);
	add_submenu_page(
		'stop_spammers',
		'Beta : '.__('Stop Spammers — DB Cleanup',SFS_TXTDOMAIN),
		'Beta : '.__('DB Cleanup',SFS_TXTDOMAIN),
		'manage_options',
		'ss_option_maint',
		'ss_option_maint'
	);

	add_submenu_page(
		'stop_spammers',
		'Beta : '.__('Stop Spammers — Threat Scan',SFS_TXTDOMAIN),
		'Beta : '.__('Threat Scan',SFS_TXTDOMAIN),
		'manage_options',
		'ss_threat_scan',
		'ss_threat_scan'
	);

	if ( function_exists( 'is_multisite' ) && is_multisite() ) {
		add_submenu_page(
			'stop_spammers',
			__('Stop Spammers — Multisite',SFS_TXTDOMAIN),
			__('Network of Blogs',SFS_TXTDOMAIN),
			'manage_options',
			'ss_network',
			'ss_network'
		);
	}
}


function ss_access() 			{include_setting( "ss_access.php" );}
function ss_allowlist_settings()	{include_setting( "ss_allowlist_settings.php" );}
function ss_allowreq()			{include_setting( "ss_allowreq.php" );}
function ss_cache()			{include_setting( "ss_cache.php" );}
function ss_challenges()		{include_setting( "ss_challenge.php" );}
function ss_change_admin()		{include_setting( "ss_change_admin.php" );}
function ss_contribute()		{include_setting( "ss_contribute.php" );}
function ss_denylist_settings()		{include_setting( "ss_denylist_settings.php" );}
function ss_diagnostics() 		{include_setting( "ss_diagnostics.php" );}
function ss_network() 			{include_setting( "ss_network.php" );}
function ss_option_maint()		{include_setting( "ss_option_maint.php" );}
function ss_options() 			{include_setting( "ss_options.php" );}
function ss_reports() 			{include_setting( "ss_reports.php" );}
function ss_summary()			{include_setting( "ss_summary.php" );}
function ss_threat_scan()		{include_setting( "ss_threat_scan.php" );}
function ss_webservices_settings()	{include_setting( "ss_webservices_settings.php" );}


function include_setting( $file ) {
	sfs_errorsonoff();
	$ppath = plugin_dir_path( __FILE__ );
	if ( file_exists( $ppath . $file ) ) {
		require_once( $ppath . $file );
	} else {
		echo '<br />'.__('Missing file',SFS_TXTDOMAIN).":$ppath $file <br />";
	}
	sfs_errorsonoff( 'off' );
}

function ss_fix_post_vars() {
// sanitize post
	$p    = $_POST;
	$keys = array_keys( $_POST );
	foreach ( $keys as $var ) {
		try {
			$val = $_POST[ $var ];
			if ( is_string( $val ) ) {
				if ( strpos( $val, "\n" ) !== false ) {
					$val2 = esc_textarea( $val );
				} else {
					$val2 = sanitize_text_field( $val );
				}
				$_POST[ $var ] = $val2;
			}
		} catch ( Exception $e ) {
		}
	}
}

