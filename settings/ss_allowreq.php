<?php
/* translated full */

if ( ! defined( 'ABSPATH' ) ) die;
if ( ! current_user_can( 'manage_options' ) ) die( __('Access Denied',SFS_TXTDOMAIN) );

ss_fix_post_vars();
$stats = ss_get_stats();
extract( $stats );
$now     = date( 'Y/m/d H:i:s', time() + ( get_option( 'gmt_offset' ) * 3600 ) );
$options = ss_get_options();
extract( $options );
$stats = ss_get_stats();
extract( $stats );
$trash   = SS_PLUGIN_URL . 'images/trash.png';
$tdown   = SS_PLUGIN_URL . 'images/tdown.png';
$tup     = SS_PLUGIN_URL . 'images/tup.png'; // fix this
$whois   = SS_PLUGIN_URL . 'images/whois.png'; // fix this

$nonce   = (array_key_exists( 'ss_stop_spammers_control', $_POST )?$_POST['ss_stop_spammers_control']:'');
$ajaxurl = admin_url( 'admin-ajax.php' );

if ( ! empty( $nonce ) && wp_verify_nonce( $nonce, 'ss_stopspam_update' ) ) {
	if ( array_key_exists( 'ss_stop_clear_wlreq', $_POST ) ) {
		$wlrequests          = array();
		$stats['wlrequests'] = $wlrequests;
		ss_set_stats( $stats );
	}

	$msg = '<div class="notice notice-success"><p>'.__('Requests Cleared',SFS_TXTDOMAIN).'</p></div>';
}

$nonce = wp_create_nonce( 'ss_stopspam_update' );
?>
<div id="ss-plugin" class="wrap ss-set-alt">
    <h1><?php echo __('Stop Spammers — Allow Requests',SFS_TXTDOMAIN);?></h1>
	<?php
	if ( ! empty( $msg ) ) {
		echo "$msg";
	} ?>
    <p>
	<?php echo __('When users are blocked they can fill out a form asking to be added to the allow list.'
			.'<br />Any users that have filled out the form will appear below.'
			.'<br />Some spam robots fill in any form that they find so their may be some garbage here.'
			,SFS_TXTDOMAIN);?>
     </p>
	<?php
	if ( count( $wlrequests ) == 0 ) {
		echo '<p>'.__( 'No requests.', SFS_TXTDOMAIN).'</p>';
	} else {
		?>
        <h2><?php echo __('Allow List Requests',SFS_TXTDOMAIN);?></h2>
        <form method="post" action="">
            <input type="hidden" name="ss_stop_spammers_control" value="<?php echo $nonce; ?>"/>
            <input type="hidden" name="ss_stop_clear_wlreq" value="true"/>
            <p class="submit"><input class="button-primary" value="<?php echo __('Clear the Requests',SFS_TXTDOMAIN);?>" type="submit"/></p>
        </form>
		<?php
		?>
        <table width="100%" style="background-color:#eee" cellspacing="2">
            <thead>
            <tr style="background-color:ivory;text-align:center">
                <th><?php echo __('Time', SFS_TXTDOMAIN);?></th>
                <th><?php echo __('IP', SFS_TXTDOMAIN);?></th>
                <th><?php echo __('Email', SFS_TXTDOMAIN);?></th>
                <th><?php echo __('Reason', SFS_TXTDOMAIN);?></th>
                <th><?php echo __('URL', SFS_TXTDOMAIN);?></th>
            </tr>
            </thead>
            <tbody id="wlreq">
			<?php
			$show = '';
			$cont = 'wlreqs';

			// wlrequs has an array of arrays
			// time,ip,email,author,reason,info,sname
			// time,ip,email,author,reason,info,sname
			// use the be_load to get badips
			$options = ss_get_options();
			$stats   = ss_get_stats();
			$show    = be_load( 'ss_get_alreq', 'x', $stats, $options );
			echo $show;
			?>
            </tbody>
        </table>
		<?php
	}

?>
