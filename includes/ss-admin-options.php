<?php
if ( ! defined( 'ABSPATH' ) ) die;


$options = ss_get_options();
if ( $options['addtoallowlist'] == 'Y' ) {
	ss_sfs_check_admin(); // adds user to Allow List
}
// admin vs. mu admin
if ( SS_MU == 'Y' ) {
	add_action( 'mu_rightnow_end', 'ss_sp_rightnow' );
	add_filter( 'network_admin_plugin_action_links_' . plugin_basename( __FILE__ ), 'ss_sp_plugin_action_links' );
	add_filter( 'plugin_row_meta', 'ss_sp_plugin_action_links', 10, 2 );
	add_filter( 'wpmu_users_columns', 'ss_sfs_ip_column_head' );
} else {
	add_action( 'admin_menu', 'ss_admin_menu' );
	add_action( 'rightnow_end', 'ss_sp_rightnow' );
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ss_sp_plugin_action_links' );
	add_filter( 'manage_users_columns', 'ss_sfs_ip_column_head' );
}


add_action( 'network_admin_menu', 'ss_admin_menu' );
add_filter( 'comment_row_actions', 'ss_row', 1, 2 );

// add_action('wp_ajax_nopriv_sfs_sub', 'sfs_handle_ajax_sub');	
add_action( 'wp_ajax_sfs_sub', 'sfs_handle_ajax_sub' );
// new replacement for multiple AJAX hooks
// add_action('wp_ajax_nopriv_sfs_process', 'sfs_handle_ajax_sfs_process');	
add_action( 'wp_ajax_sfs_process', 'sfs_handle_ajax_sfs_process' );
add_action( 'manage_users_custom_column', 'ss_sfs_ip_column', 10, 3 );

// the uninstall hook only gets set if user is logged in and can manage options (plugins)
if ( function_exists( 'register_uninstall_hook' ) ) {
// uncomment this or when we go to beta
// register_uninstall_hook(__FILE__, 'ss_sfs_reg_uninstall');
}

/* removed from translate script 
add_action( 'admin_enqueue_scripts', 'sfs_handle_ajax' );
function sfs_handle_ajax() {wp_enqueue_script( 'stop-spammers', SS_PLUGIN_URL . 'js/sfs_handle_ajax.js', false );}
*/

/* 
jetsam: added/replace amin.js as <script> for translate on-the-fly! 
*/
add_action( 'admin_print_footer_scripts', 'ss_insert_admin_js' );


function ss_sp_plugin_action_links( $links, $file ) {
// get the links
	if ( strpos( $file, 'stop-spammer' ) === false ) {
		return $links;
	}
	if ( SS_MU == 'Y' ) {
		$link = '<a href="' . admin_url( 'network/admin.php?page=stop_spammers' ) . '">'
			.__('Settings',SFS_TXTDOMAIN).'</a>';
	} else {
		$link = '<a href="' . admin_url( 'admin.php?page=stop_spammers' ) . '">'
			.__('Settings',SFS_TXTDOMAIN).'</a>';
	}
// check to see if we are in network
// to-do
	$links[] = $link;

	return $links;
}

function ss_sp_rightnow() {
	$stats = ss_get_stats();
	extract( $stats );
	$options = ss_get_options();
	if ( $spmcount > 0 ) {
// steal the Akismet stats CSS format 
// get the path to the plugin
		echo	'<p>'
			.sprintf(__('Stop Spammers has prevented <strong>%s</strong> spammers from registering or leaving comments.',SFS_TXTDOMAIN)
				,$spmcount)
			.'</p>';
	}

	if ( count( $wlrequests ) >0 ) {
		echo '<p><strong>' . count( $wlrequests ) . '</strong> '
		.__('user(s) has been denied access and <a href="admin.php?page=ss_allowrequests">requested</a> that you add them to the Allow List.',SFS_TXTDOMAIN)
		.'</p>';
	}
}

function ss_row( $actions, $comment ) {
	$options = get_option( 'ss_stop_sp_reg_options' ); // for some reason the main call is not available?
	$apikey  = $options['apikey'];
	$email   = urlencode( $comment->comment_author_email );
	$ip      = $comment->comment_author_IP;
	$action  = "";
// $action.="|";
// $action.="<a title=\"Check Project HoneyPot\" target=\"_stopspam\" href=\"https://www.projecthoneypot.org/search_ip.php?ip=$ip\">Check HoneyPot</a>";
// add the network check
	$whois    = SS_PLUGIN_URL . 'images/whois.png';
	$who      = "<a title=\"Look Up WHOIS\" target=\"_stopspam\" href=\"https://lacnic.net/cgi-bin/lacnic/whois?lg=EN&query=$ip\"><img src=\"$whois\" height=\"16px\"/></a>";
	$stophand = SS_PLUGIN_URL . 'images/stop.png';
	$stop     = "<a title=\"Check Stop Forum Spam (SFS)\" target=\"_stopspam\" href=\"https://www.stopforumspam.com/search.php?q=$ip\"><img src=\"$stophand\" height=\"16px\"/> </a>";
	$action   .= " $who $stop";
// now add the report function
	$email = urlencode( $comment->comment_author_email );
	if ( empty( $email ) ) {
		$actions['check_spam'] = $action;

		return $actions;
	}
	$ID       = $comment->comment_ID;
	$exst     = '';
	$uname    = urlencode( $comment->comment_author );
	$content  = $comment->comment_content;
	$evidence = $comment->comment_author_url;
	if ( empty( $evidence ) ) {
		$evidence = '';
	}
	preg_match_all( '@((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)@', $content, $post, PREG_PATTERN_ORDER );
	if ( is_array( $post ) && is_array( $post[1] ) ) {
		$urls1 = array_unique( $post[1] );
	} else {
		$urls1 = array();
	}
// BBCode
	preg_match_all( '/\[url=(.+)\]/iU', $content, $post, PREG_PATTERN_ORDER );
	if ( is_array( $post ) && is_array( $post[0] ) ) {
		$urls2 = array_unique( $post[0] );
	} else {
		$urls2 = array();
	}
	$urls3 = array_merge( $urls1, $urls2 );
	if ( is_array( $urls3 ) ) {
		$evidence .= "\r\n" . implode( "\r\n", $urls3 );
	}
	$evidence = urlencode( trim( $evidence, "\r\n" ) );
	if ( strlen( $evidence ) > 128 ) {
		$evidence = substr( $evidence, 0, 125 ) . '...';
	}
	$target  = " target=\"_blank\" ";
	$href    = "href=\"https://www.stopforumspam.com/add.php?username=$uname&email=$email&ip_addr=$ip&evidence=$evidence&api_key=$apikey\" ";
	$onclick = '';
	$blog    = 1;
	global $blog_id;
	if ( ! isset( $blog_id ) || $blog_id != 1 ) {
		$blog = $blog_id;
	}
	$ajaxurl = admin_url( 'admin-ajax.php' );
	if ( ! empty( $apikey ) ) {
// $target="target=\"ss_sfs_reg_if1\"";
// make this the xlsrpc call
		$href    = "href=\"#\"";
		$onclick = "onclick=\"sfs_ajax_report_spam(this,'$ID','$blog','$ajaxurl');return false;\"";
	}
	if ( ! empty( $email ) ) {
		$action .= '|';
		$action .= "<a $exst title=\"Report to Stop Forum Spam (SFS)\" $target $href $onclick class='delete:the-comment-list:comment-$ID::delete=1 delete vim-d vim-destructive'> "
			.__('Report to SFS',SFS_TXTDOMAIN).'</a>';
	}
	$actions['check_spam'] = $action;

	return $actions;
}

function ipChkk() {
	$actionvalid = array( 'chkvalidip', 'chkcloudflare' );
	foreach ( $actionvalid as $chk ) {
		$reason = be_load( $chk, $ip );
		if ( $reason !== false ) {
			return false;
		}
	}

	return true;
}

function sfs_handle_ajax_sub( $data ) {
// check to see if it user can manage options
	if ( ! is_user_logged_in() ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
// suddenly loading before 'init' has loaded things?
// get the stuff from the $_GET and call stop forum spam
// this tages the stuff from the get and uses it to do the get from SFS
// get the configuration items
	$options = get_option( 'ss_stop_sp_reg_options' ); // for some reason the main call is not available?
	if ( empty( $options ) ) { // can't happen?
		echo ' '.__('No Options Set',SFS_TXTDOMAIN);
		die;
	}
// print_r($options);
	extract( $options );
// get the comment_id parameter	
	$comment_id = urlencode( $_GET['comment_id'] );
	if ( empty( $comment_id ) ) {
		echo ' '.__('No Comment ID Found',SFS_TXTDOMAIN);
		die;
	}
// need to pass the blog ID also
	$blog = '';
	$blog = $_GET['blog_id'];
	if ( $blog != '' ) {
		if ( function_exists( 'switch_to_blog' ) ) {
			switch_to_blog( $blog );
		}
	}
// get the comment
	$comment = get_comment( $comment_id, ARRAY_A );
	if ( $comment_id == 'registration' ) {
		$comment = array(
			'comment_author_email' => $_GET['email'],
			'comment_author'       => $_GET['user'],
			'comment_author_IP'    => $_GET['ip'],
			'comment_content'      => 'registration',
			'comment_author_url'   => ''
		);
	} else {
		if ( empty( $comment ) ) {
			echo ' '.sprintf(__('No Comment Found for %s',SFS_TXTDOMAIN),$comment_id);
			die;
		}
	}
// print_r($comment);
	$email   = urlencode( $comment['comment_author_email'] );
	$uname   = urlencode( $comment['comment_author'] );
	$ip_addr = $comment['comment_author_IP'];
// code added as per Paul at Stop Forum Spam
	$content  = $comment['comment_content'];
	$evidence = $comment['comment_author_url'];
	if ( $blog != '' ) {
		if ( function_exists( 'restore_current_blog' ) ) {
			restore_current_blog();
		}
	}
	if ( empty( $evidence ) ) {
		$evidence = '';
	}
	preg_match_all( '@((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)@', $content, $post, PREG_PATTERN_ORDER );
	$urls1 = array();
	$urls2 = array();
	if ( is_array( $post ) && is_array( $post[1] ) ) {
		$urls1 = array_unique( $post[1] );
	} else {
		$urls1 = array();
	}
// BBCode
	preg_match_all( '/\[url=(.+)\]/iU', $content, $post, PREG_PATTERN_ORDER );
	if ( is_array( $post ) && is_array( $post[0] ) ) {
		$urls2 = array_unique( $post[0] );
	} else {
		$urls2 = array();
	}
	$urls3 = array_merge( $urls1, $urls2 );
	if ( is_array( $urls3 ) ) {
		$evidence .= "\r\n" . implode( "\r\n", $urls3 );
	}
	$evidence = urlencode( trim( $evidence, "\r\n" ) );
	if ( strlen( $evidence ) > 128 ) {
		$evidence = substr( $evidence, 0, 125 ) . '...';
	}
	if ( empty( $apikey ) ) {
		echo __('Cannot Report Spam without API Key',SFS_TXTDOMAIN);
		die;
	}
	$hget = "https://www.stopforumspam.com/add.php?ip_addr=$ip_addr&api_key=$apikey&email=$email&username=$uname&evidence=$evidence";
// echo $hget;
	$ret = ss_read_file( $hget );
	if ( stripos( $ret, 'data submitted successfully' ) !== false ) {
		echo $ret;
	} else if ( stripos( $ret, 'recent duplicate entry' ) !== false ) {
		echo ' '.__('Recent Duplicate Entry',SFS_TXTDOMAIN).' ';
	} else {
		' '.__('Returning from AJAX',SFS_TXTDOMAIN).' : ' . $hget . ' - ' . $ret;
	}
	die;
}

function sfs_get_urls( $content ) {
	preg_match_all( '@((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)@', $content, $post, PREG_PATTERN_ORDER );
	$urls1 = array();
	$urls2 = array();
	$urls3 = array();
	if ( is_array( $post ) && is_array( $post[1] ) ) {
		$urls1 = array_unique( $post[1] );
	} else {
		$urls1 = array();
	}
// BBCode
	preg_match_all( '/\[url=(.+)\]/iU', $content, $post, PREG_PATTERN_ORDER );
	if ( is_array( $post ) && is_array( $post[0] ) ) {
		$urls2 = array_unique( $post[0] );
	} else {
		$urls2 = array();
	}
	$urls3 = array_merge( $urls1, $urls2 );
	if ( ! is_array( $urls3 ) ) {
		return array();
	}
	for ( $j = 0; $j < count( $urls3 ); $j ++ ) {
		$urls3[ $j ] = urlencode( $urls3[ $j ] );
	}

	return $urls3;
}

function sfs_handle_ajax_check( $data ) {
	if ( ! ipChkk() ) {
		echo ' '.__('Not Enabled',SFS_TXTDOMAIN);
		die;
	}
// this does a call to the SFS site to check a known spammer
// returns success or not
	$query = "https://www.stopforumspam.com/api?ip=91.186.18.61";
	$check = '';
	$check = ss_sfs_reg_getafile( $query );
	if ( ! empty( $check ) ) {
		$check = trim( $check );
		$check = trim( $check, '0' );
		if ( substr( $check, 0, 4 ) == "ERR:" ) {
			echo ' '.__('Access to the Stop Forum Spam Database Shows Errors',SFS_TXTDOMAIN)."\r\n";
			echo ' '.__('Response Was',SFS_TXTDOMAIN)." :\r\n$check\r\n";
		}
// access to the Stop Forum Spam database is working
		$n = strpos( $check, '<response success="true">' );
		if ( $n === false ) {
			echo ' '.__('Access to the Stop Forum Spam Database is Not Working',SFS_TXTDOMAIN)."\r\n";
			echo ' '.__('Response Was',SFS_TXTDOMAIN)." :\r\n$check\r\n";
		} else {
			echo ' '.__('Access to the Stop Forum Spam Database is Working',SFS_TXTDOMAIN)."\r\n";
		}
	} else {
		echo ' '.__('No Response from the Stop Forum Spam API Call',SFS_TXTDOMAIN)."\r\n";
	}

	return;
}

function sfs_handle_ajax_sfs_process( $data ) {
	if ( ! is_user_logged_in() ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	sfs_errorsonoff();
	sfs_handle_ajax_sfs_process_watch( $data );
	sfs_errorsonoff( 'off' );
}

function sfs_handle_ajax_sfs_process_watch( $data ) {
// anything in data? never
// get the things out of the get
// check for valid get
	if ( ! array_key_exists( 'func', $_GET ) ) {
		echo ' '.__('Function Not Found',SFS_TXTDOMAIN)."\r\n";
		die;
	}
	$trash     = SS_PLUGIN_URL . 'images/trash.png';
	$tdown     = SS_PLUGIN_URL . 'images/tdown.png';
	$tup       = SS_PLUGIN_URL . 'images/tup.png'; // fix this
	$whois     = SS_PLUGIN_URL . 'images/whois.png'; // fix this
	$ip        = $_GET['ip'];
	$container = $_GET['cont'];
	$func      = $_GET['func'];
// echo "error $ip, $func, $container,".print_r($_GET,true);exit();
// container is blank, goodips, badips or log
// func is add_black, add_white, delete_gcache or delete_bcache
	$options = ss_get_options();
	$stats   = ss_get_stats();
// $stats,$options);
	$ansa = array();
	switch ( $func ) {

		case 'delete_gcache':
// deletes a Good Cache item
			$ansa = be_load( 'ss_remove_gcache', $ip, $stats, $options );
			$show = be_load( 'ss_get_gcache', 'x', $stats, $options );
			echo $show;
			die;


		case 'delete_bcache':
// deletes a Bad Cache item
			$ansa = be_load( 'ss_remove_bcache', $ip, $stats, $options );
			$show = be_load( 'ss_get_bcache', 'x', $stats, $options );
			echo $show;
			die;

		case 'add_black':
			if ( $container == 'badips' ) {
				be_load( 'ss_remove_bcache', $ip, $stats, $options );
			} else if ( $container == 'goodips' ) {
				be_load( 'ss_remove_gcache', $ip, $stats, $options );
			} else { // wlreq
				be_load( 'ss_remove_bcache', $ip, $stats, $options );
				be_load( 'ss_remove_gcache', $ip, $stats, $options );
			}
			be_load( 'ss_addtodenylist', $ip, $stats, $options );
			break;

		case 'add_white':
			if ( $container == 'badips' ) {
				be_load( 'ss_remove_bcache', $ip, $stats, $options );
			} else if ( $container == 'goodips' ) {
				be_load( 'ss_remove_gcache', $ip, $stats, $options );
			} else {
				be_load( 'ss_remove_bcache', $ip, $stats, $options );
				be_load( 'ss_remove_gcache', $ip, $stats, $options );
			}
			be_load( 'ss_addtoallowlist', $ip, $stats, $options );
// if it is not good or bad IP we don't need the container as it is the log
			break;
		case 'delete_wl_row': // this is from the Allow Requests list
			$ansa = be_load( 'ss_get_alreq', $ip, $stats, $options );
			echo $ansa;
			die;

		case 'delete_wlip': // this is from the Allow Requests list
			$ansa = be_load( 'ss_get_alreq', $ip, $stats, $options );
			echo $ansa;
			die;

		case 'delete_wlem': // this is from the Allow Requests list
			$ansa = be_load( 'ss_get_alreq', $ip, $stats, $options );
			echo $ansa;
			die;

		default:
			echo "\r\n\r\n".sprintf(__('Unrecognized function "%s"',SFS_TXTDOMAIN),$func)."\r\n";
			die;
	}
	$ajaxurl  = admin_url( 'admin-ajax.php' );
	$cachedel = 'delete_gcache';
	switch ( $container ) {
		case 'badips':
			$show = be_load( 'ss_get_bcache', 'x', $stats, $options );
			echo $show;
			die;

		case 'goodips':
			$show = be_load( 'ss_get_gcache', 'x', $stats, $options );
			echo $show;
			die;

		case 'wlreq':
			$ansa = be_load( 'ss_get_alreq', $ip, $stats, $options );
			echo $ansa;
			die;
		default:
// coming from logs report we need to display an appropriate message, I think
			echo ' '.sprintf(__('Something is missing "%s"',SFS_TXTDOMAIN),$container)."\r\n";
			die;
	}
}

function ss_sfs_ip_column( $value, $column_name, $user_id ) {
// get the IP for this column
	$trash    = SS_PLUGIN_URL . 'images/trash.png';
	$tdown    = SS_PLUGIN_URL . 'images/tdown.png';
	$tup      = SS_PLUGIN_URL . 'images/tup.png';
	$whois    = SS_PLUGIN_URL . 'images/whois.png';
	$stophand = SS_PLUGIN_URL . 'images/stop.png';
	$search   = SS_PLUGIN_URL . 'images/search.png';
	if ( $column_name == 'signup_ip' ) {
		$signup_ip  = get_user_meta( $user_id, 'signup_ip', true );
		$signup_ip2 = $signup_ip;
		$ipline     = "";
		if ( ! empty( $signup_ip ) ) {
			$ipline = apply_filters( 'ip2link', $signup_ip2 ); // if the ip2link plugin is installed
// now add the check 
			$user_info   = get_userdata( $user_id );
			$useremail   = urlencode( $user_info->user_email ); // for reporting
			$userurl     = urlencode( $user_info->user_url );
			$username    = $user_info->display_name;
			$stopper     = "<a title=\"Check Stop Forum Spam (SFS)\" target=\"_stopspam\" href=\"https://www.stopforumspam.com/search.php?q=$signup_ip\"><img src=\"$stophand\" height=\"16px\"/></a>";
			$honeysearch = "<a title=\"Check Project HoneyPot\" target=\"_stopspam\" href=\"https://www.projecthoneypot.org/ip_$signup_ip\"><img src=\"$search\" height=\"16px\"/></a>";
			$botsearch   = "<a title=\"Check BotScout\" target=\"_stopspam\" href=\"https://botscout.com/search.htm?stype=q&sterm=$signup_ip\"><img src=\"$search\" height=\"16px\"/></a>";
			$who         = "<br /><a title=\"Look Up WHOIS\" target=\"_stopspam\" href=\"https://lacnic.net/cgi-bin/lacnic/whois?lg=EN&query=$signup_ip\"><img src=\"$whois\" height=\"16px\"/></a>";
			$action      = " $who $stopper $honeysearch $botsearch";
			$options     = ss_get_options();
			$apikey      = $options['apikey'];
			if ( ! empty( $apikey ) ) {
				$report = "<a title=\"Report to SFS\" target=\"_stopspam\" href=\"https://www.stopforumspam.com/add.php?username=$username&email=$useremail&ip_addr=$signup_ip&evidence=$userurl&api_key=$apikey\"><img src=\"$stophand\" height=\"16px\"/></a>";
				$action .= $report;
			}

			return $ipline . $action;
		}

		return "";
	}

	return $value;
}

function ss_insert_admin_js() {

$TXT='

<script type="text/javascript">

var sfs_ajax_who = "";

function sfs_ajax_process(sip, contx, sfunc, url) {
    sfs_ajax_who = contx;
    var data = {
	action:	"sfs_process",
	ip:	sip,
	cont:	contx,
	func:	sfunc,
	ajax_url: url
    };
    jQuery.get(ajaxurl, data, sfs_ajax_return_process);
}

function sfs_ajax_return_process(response) {
    var el = "";
    if (response == "OK") {
        return false;
    }

    if (response.substring(0, 3) == "err") {
        alert(response);
        return false;
    }

    if (response.substring(0, 4) == "\r\n\r\n") {
        alert(response);
        return false;
    }

    if (sfs_ajax_who != "") {
        var el = document.getElementById(sfs_ajax_who);
        el.innerHTML = response;
    }

    return false;
}

function sfs_ajax_report_spam(t, id, blog, url, email, ip, user) {
    sfs_ajax_who = t;
    var data = {
	action:		"sfs_sub",
	blog_id: 	blog,
	comment_id: 	id,
	ajax_url:	url,
	email:		email,
	ip:		ip,
	user:		user
    };
    jQuery.get(ajaxurl, data, sfs_ajax_return_spam);
}

function sfs_ajax_return_spam(response) {
    sfs_ajax_who.innerHTML	= " '.__('Spam Reported',SFS_TXTDOMAIN).'";
    sfs_ajax_who.style.color	= "green";
    sfs_ajax_who.style.fontWeight = "bolder";

    if (response.indexOf("data submitted successfully") > 0) {
        return false;
    }

    if (response.indexOf("recent duplicate entry") > 0) {
        sfs_ajax_who.innerHTML	= " '.__('Spam Already Reported',SFS_TXTDOMAIN).'";
        sfs_ajax_who.style.color = "yellow";
        sfs_ajax_who.style.fontWeight = "bolder";
        return false;
    }

    sfs_ajax_who.innerHTML	= " '.__('Status',SFS_TXTDOMAIN).': " + response;
    sfs_ajax_who.style.color	= "black";
    sfs_ajax_who.style.fontWeight = "bolder";
    alert(response);
    return false;
}

function sfs_test_translate(t,resp) {
	alert("'.__('TEST STRING TRANSLATE',SFS_TXTDOMAIN).'"+resp);
	t.style.display	= "none";
	return false;
}

</script>

';	//
	echo $TXT;
}

