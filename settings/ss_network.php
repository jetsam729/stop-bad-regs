<?php
/* translate: full */

if ( ! defined( 'ABSPATH' ) ) die;
if ( ! current_user_can( 'manage_options' ) ) 	die( __('Access Denied',SFS_TXTDOMAIN) );

ss_fix_post_vars();
?>
<div id="ss-plugin" class="wrap ss-set-alt">
    <h1><?php echo __('Stop Spammers — Multisite',SFS_TXTDOMAIN);?></h1>
	<?php

	$muswitch = get_option( 'ss_muswitch' );
	if ( empty( $muswitch ) ) {
		$muswitch = 'N';
	}

	$nonce    = (array_key_exists( 'ss_stop_spammers_control', $_POST)?$nonce = $_POST['ss_stop_spammers_control']:'');

	if ( wp_verify_nonce( $nonce, 'ss_stopspam_update' ) ) {
		if ( array_key_exists( 'action', $_POST ) ) {
			if ( array_key_exists( 'muswitch', $_POST ) ) {
				$muswitch = trim( stripslashes( $_POST['muswitch'] ) );
			}
			if ( empty( $muswitch ) || $muswitch != 'Y'){
				$muswitch = 'N';
			}

			update_option( 'ss_muswitch', $muswitch );
			echo  '<div class="notice notice-success"><p>'.__('Options Updated',SFS_TXTDOMAIN).'</p></div>';

		}
	} else {
// echo "no nonce<br />";
	}
	$nonce = wp_create_nonce( 'ss_stopspam_update' );
	?>
    <form method="post" action="">
        <input type="hidden" name="ss_stop_spammers_control" value="<?php echo $nonce; ?>"/>
        <input type="hidden" name="action" value="update mu settings"/>
        <fieldset>
		<legend>
		<span style="font-weight:bold;font-size:1.2em;">
			<?php echo __('Network Blog Option',SFS_TXTDOMAIN);?>
		</span>
		</legend>
            <p><?php echo __('Networked ON',SFS_TXTDOMAIN);?>: 
		<input name="muswitch" type="radio" value='Y' <?php echo ($muswitch=='Y'?'checked="true"':'');?> /><br />
                <?php echo __('Networked OFF',SFS_TXTDOMAIN);?>: 
		<input name="muswitch" type="radio" value='N' <?php echo ($muswitch!='Y'?'checked="true"':'');?> />
		<br />
		<br />
		<?php echo __('If you are running WPMU and want to control options and history through the main login admin panel, select ON. If you select OFF, each blog will have to configure the plugin separately, and each blog will have a separte history.'
			,SFS_TXTDOMAIN);?>
		</p>
		<p class="submit"><input class="button-primary" value="<?php echo __('Save Changes',SFS_TXTDOMAIN); ?>" type="submit"/></p>
        </fieldset>
    </form>
</div>