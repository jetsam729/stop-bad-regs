<?php
/* translated: full */

if ( ! defined( 'ABSPATH' ) ) 	die;
if ( ! current_user_can( 'manage_options' ) ) die( __('Access Denied',SFS_TXTDOMAIN) );

ss_fix_post_vars();
$now     = date( 'Y/m/d H:i:s', time() + ( get_option( 'gmt_offset' ) * 3600 ) );
$options = ss_get_options();
extract( $options );
$chkcloudflare = 'Y'; // force back to on - always fix Cloudflare if the plugin is not present and Cloudflare detected

$nonce = ( array_key_exists( 'ss_stop_spammers_control', $_POST )?$_POST['ss_stop_spammers_control']:'');
if(!isset($options['wlist'])){	$options['wlist'] = []; $wlist=[];};

if ( ! empty( $nonce ) && wp_verify_nonce( $nonce, 'ss_stopspam_update' ) ) {
	if ( array_key_exists( 'wlist', $_POST ) ) {
		$wlist  = $_POST['wlist'];
		$wlist  = explode( "\n", $wlist );
		$tblist = [];
		foreach ( $wlist as $bl ) {
			$bl = trim( $bl );
			if ( ! empty( $bl ) ) {
				$tblist[] = $bl;
			}
		}
		$options['wlist'] = $tblist;
		$wlist            = $tblist;
	}
	$optionlist = array(
		'chkgoogle',
		'chkaws',
		'chkwluserid',
		'chkpaypal',
		'chkgenallowlist',
		'chkmiscallowlist',
		'chkyahoomerchant'
	);
	foreach ( $optionlist as $check ) {
		$v = 'N';
		if ( array_key_exists( $check, $_POST ) ) {
			$v = $_POST[ $check ];
			if ( $v != 'Y' ) {
				$v = 'N';
			}
		}
		$options[ $check ] = $v;
	}
	ss_set_options( $options );
	extract( $options ); // extract again to get the new options
	$msg = '<div class="notice notice-success"><p>'.__('Options Updated',SFS_TXTDOMAIN).'</p></div>';

}else{
	$options['wlist'] = array_values($options['wlist']);	// for recovery
}

$nonce = wp_create_nonce( 'ss_stopspam_update' );
?>
<div id="ss-plugin" class="wrap ss-set-alt">
    <h1><?php echo __('Stop Spammers — Allow Lists',SFS_TXTDOMAIN); ?></h1>
	<?php if ( ! empty( $msg ) ) {
		echo "$msg";
	} ?>
    <form method="post" action="">
        <input type="hidden" name="action" value="update"/>
        <input type="hidden" name="ss_stop_spammers_control" value="<?php echo $nonce; ?>"/>
        <fieldset>
            <legend><span style="font-weight:bold;font-size:1.2em;"><?php echo __('Allow Lists',SFS_TXTDOMAIN); ?></span></legend>
            <p><?php echo __('Put IP addresses or emails here that you don\'t want blocked. One email or IP to a line. You can use wild cards here for emails.',SFS_TXTDOMAIN); ?></p>
            <p><?php echo __('You may put user IDs here, but this is dangerous because spammers can easily find a user\'s ID from previous comments, and add comments using it.<br />I don\'t recommend using this. Normally user ID checking is turned off so you must check this box to use it.',SFS_TXTDOMAIN); ?>
                <input name="chkwluserid" type="checkbox" value="Y" <?php if ( $chkwluserid == 'Y' ) {
					echo "checked=\"checked\"";
				} ?> /></p>
            <p><?php echo __('These are checked first so they override any blocking.',SFS_TXTDOMAIN); ?></p>
            <textarea name="wlist" cols="40" rows="8"><?php
				for ( $k = 0; $k < count( $wlist ); $k ++ ) {
					echo $wlist[ $k ] . "\r\n";
				}
            ?></textarea>
        </fieldset>
        <br />
        <h2><?php echo __('Allow Options',SFS_TXTDOMAIN); ?></h2>
        <p><?php echo __('These options will be checked first and will allow some users to continue without being checked further.',SFS_TXTDOMAIN); ?><br />
            <?php echo __('You can prevent Google, PayPal, and other services from ever being blocked.',SFS_TXTDOMAIN); ?></p>

        <fieldset>
            <legend><span style="font-weight:bold;font-size:1.2em;">Google</span></legend>
		<p><input name="chkgoogle" type="checkbox" value="Y" <?php echo ($chkgoogle=='Y'?'checked="checked"/>':'/>');?>
		<?php echo __('<strong>DON\'T TOUCH.</strong> Google is very important to most websites.'
				.' This prevents Google from being blocked.',SFS_TXTDOMAIN); ?>
		</p>
        </fieldset>
        <br />
        <fieldset>
		<legend><span style="font-weight:bold;font-size:1.2em;">
			<?php echo __('Generated Allow List',SFS_TXTDOMAIN); ?>
			</span>
		</legend>
		<p><input name="chkgenallowlist" type="checkbox" value="Y" <?php echo ($chkgenallowlist=='Y'?'checked="checked"/>':'/>');?>
		<?php echo __('An Allow List of well-behaved and responsible IP blocks in North America, Western Europe, and Australia.'
			.'<br />These are a major source of spam, but also a major source of paying customers.'
			.'<br />Checking this will let in some spam, but will not block residential ISP customers from industrialized countries.'
			,SFS_TXTDOMAIN); ?>
		</p>
        </fieldset>
        <br />
        <fieldset>
		<legend>	<span style="font-weight:bold;font-size:1.2em;">
			<?php echo __('Other Allow Lists',SFS_TXTDOMAIN); ?>
			</span>
		</legend>
		<p><input name="chkmiscallowlist" type="checkbox" value="Y" <?php echo ($chkmiscallowlist=='Y'?'checked="checked"/>':'/>');?>
		<?php echo sprintf(
				__('A list of small web service providers that can be accidentally blocked as bad actors.'
				.'<br />Currently on the list: VaultPress.'
				.'<br />Request other services be added to this whitelist %s',SFS_TXTDOMAIN)
				,'<a href="https://github.com/bhadaway/stop-spammers/issues" target="_blank">on GitHub</a>.'
				);
		?>
		</p>
        </fieldset>
        <br />
        <fieldset>
		<legend>
		<span style="font-weight:bold;font-size:1.2em;">
		<?php echo __('Allow PayPal',SFS_TXTDOMAIN); ?>
		</span>
		</legend>
		<p><input name="chkpaypal" type="checkbox" value="Y" <?php echo ($chkpaypal=='Y'?'checked="checked"/>':'/>');?>
                <?php echo __('If you accept payment through PayPal, keep this box checked.',SFS_TXTDOMAIN); ?>
		</p>
        </fieldset>
        <br />
        <fieldset>
            	<legend>
		<span style="font-weight:bold;font-size:1.2em;">
		<?php echo __('Allow Yahoo Merchant Services',SFS_TXTDOMAIN); ?>
		</span>
		</legend>
		<p><input name="chkyahoomerchant" type="checkbox" value="Y" <?php echo ($chkyahoomerchant=='Y'?'checked="checked"/>':'/>');?>
                <?php echo __('If you use Yahoo Merchant Services, keep this box checked.',SFS_TXTDOMAIN); ?>
		</p>
        </fieldset>
        <br />
        <fieldset>
		<legend>
		<span style="font-weight:bold;font-size:1.2em;">
                <?php echo __('Allow Amazon Cloud',SFS_TXTDOMAIN); ?>
		</span>
		</legend>
            <p><input name="chkaws" type="checkbox" value="Y" <?php echo ($chkaws=='Y'?'checked="checked"/>':'/>');?>
                <?php echo __('The Amazon Cloud is the source of occasional spam, but they shut it down right away.'
		.'<br />Lots of startup web services use Amazon Cloud Servers to host their services.'
		.'<br />If you use a service to check your site, share on Facebook, or cross post from Twitter,'
		.'<br />it may be using Amazon\'s cloud services. Check this if you want to always allow Amazon AWS.'
		,SFS_TXTDOMAIN); ?>
		</p>
        </fieldset>
        <br />

        <p class="submit"><input class="button-primary" value="<?php echo __('Save Changes',SFS_TXTDOMAIN); ?>" type="submit"/></p>
    </form>
</div>
<?php
