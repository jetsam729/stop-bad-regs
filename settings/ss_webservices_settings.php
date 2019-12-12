<?php
/* translate: full */

if ( ! defined( 'ABSPATH' ) ) die;
if ( ! current_user_can( 'manage_options' ) ) die( __('Access Denied',SFS_TXTDOMAIN) );

ss_fix_post_vars();
$now     = date( 'Y/m/d H:i:s', time() + ( get_option( 'gmt_offset' ) * 3600 ) );
$options = ss_get_options();
extract( $options );

/* jetsam: ??? wordpress_api_key not used in file - delete late
$wordpress_api_key = get_option( 'wordpress_api_key' );
if ( empty( $wordpress_api_key ) ) {$wordpress_api_key = '';}
*/

$nonce = (array_key_exists( 'ss_stop_spammers_control', $_POST )?$_POST['ss_stop_spammers_control']:'');

if ( ! empty( $nonce ) && wp_verify_nonce( $nonce, 'ss_stopspam_update' ) ) {
	if ( array_key_exists( 'action', $_POST ) ) {

		if ( array_key_exists( 'apikey', $_POST ) ) {
			$apikey            = stripslashes( $_POST['apikey'] );
			$options['apikey'] = $apikey;
		}
		if ( array_key_exists( 'googleapi', $_POST ) ) {
			$googleapi            = stripslashes( $_POST['googleapi'] );
			$options['googleapi'] = $googleapi;
		}
		if ( array_key_exists( 'honeyapi', $_POST ) ) {
			$honeyapi            = stripslashes( $_POST['honeyapi'] );
			$options['honeyapi'] = $honeyapi;
		}
		if ( array_key_exists( 'botscoutapi', $_POST ) ) {
			$botscoutapi            = stripslashes( $_POST['botscoutapi'] );
			$options['botscoutapi'] = $botscoutapi;
		}
		if ( array_key_exists( 'sfsfreq', $_POST ) ) {
			$sfsfreq            = stripslashes( $_POST['sfsfreq'] );
			$options['sfsfreq'] = $sfsfreq;
		}
		if ( array_key_exists( 'sfsage', $_POST ) ) {
			$sfsage            = stripslashes( $_POST['sfsage'] );
			$options['sfsage'] = $sfsage;
		}
		if ( array_key_exists( 'hnyage', $_POST ) ) {
			$hnyage            = stripslashes( $_POST['hnyage'] );
			$options['hnyage'] = $hnyage;
		}
		if ( array_key_exists( 'hnylevel', $_POST ) ) {
			$hnylevel            = stripslashes( $_POST['hnylevel'] );
			$options['hnylevel'] = $hnylevel;
		}
		if ( array_key_exists( 'botfreq', $_POST ) ) {
			$botfreq            = stripslashes( $_POST['botfreq'] );
			$options['botfreq'] = $botfreq;
		}
		$optionlist = array( 'chksfs', 'chkdnsbl' );
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
	}
	$msg = '<div class="notice notice-success"><p>'.__('Options Updated',SFS_TXTDOMAIN).'</p></div>';
}
$nonce = wp_create_nonce( 'ss_stopspam_update' );
?>
<div id="ss-plugin" class="wrap ss-set-alt">
    <h1><?php echo __('Stop Spammers — Web Services',SFS_TXTDOMAIN);?></h1>
	<?php if (!empty($msg)) echo "$msg"; ?>
    <p>
	<?php echo __('There are many services that can be used to check for spam or protect your website against spammers.'
		.'<br />Most require a key so that only registered users can use their services.'
		.'<br />All of the services here can be used by Stop Spammers and all are free.'
	,SFS_TXTDOMAIN);?>
    </p>
    <form method="post" action="" class="ss-set-webs">
        <input type="hidden" name="action" value="update"/>
        <input type="hidden" name="ss_stop_spammers_control" value="<?php echo $nonce; ?>"/>
        <fieldset>
		<legend>
		<span style="font-weight:bold;font-size:1.2em;">
		<?php echo __('StopForumSpam.com API Key',SFS_TXTDOMAIN);?>
		</span>
		</legend>
		<input size="32" name="apikey" type="text" value="<?php echo $apikey; ?>"/>
		<br />
            <p><?php echo sprintf(__('Enable Stop Forum Spam Lookups: %s Check to enable SFS lookups',SFS_TXTDOMAIN)
			,'<input name="chksfs" type="checkbox" value="Y" '.($chksfs =='Y'?'checked="checked"/>':'/>')
				);?>
		<br />
<?php echo sprintf(__('You do not need an API key to check the Stop Forum Spam database,'
			.' but if you want to report any spam that you find, you need to provide it here.'
			.'<br />You can register and get an API key at %s.',SFS_TXTDOMAIN)
			,'<a href="https://www.stopforumspam.com/keys" target="_blank">https://www.stopforumspam.com/keys</a>'
			);?>
		<br />
		<?php echo __('You can set the minimum settings to allow possible spammers to use your site.'
		.'<br />You may wish to forgive spammers with few incidents or no recent activity.'
		.'<br />I would recommend that to be on the safe side, you should block users who appear on the spam database unless they specifically ask to be Allow Listed.'
		,SFS_TXTDOMAIN);?>
		<br />
		<?php echo __('Allowed values are 0 to 9999. Only numbers are accepted.',SFS_TXTDOMAIN);?>
            </p>
            <div style="background-color:white;font-size:0.9em;">
		<?php echo sprintf(
			__('Deny spammers found on Stop Forum Spam with more than %s incidents, and occurring less than %s days ago.',SFS_TXTDOMAIN)
			,'<input size="3" name="sfsfreq" type="text" style="width:75px;" value="'.$sfsfreq.'"/>'
			,'<input size="4" name="sfsage" type="text" style="width:75px;" value="'.$sfsage.'"/>'
			);?>
            </div>

        </fieldset>
        <br />
        <fieldset>
		<legend>
		<span style="font-weight:bold;font-size:1.2em;">
		<?php echo __('Project Honeypot API Key',SFS_TXTDOMAIN);?>
		</span>
		</legend>
            <input size="32" name="honeyapi" type="text" value="<?php echo $honeyapi; ?>"/><br />
		<p><?php echo sprintf(__('This API key is used for querying the Project Honeypot Deny List.'
				.'<br />It is required if you want to check IP addresses against the Project Honeypot database.'
				.'<br />You can register and get an API key at %s.',SFS_TXTDOMAIN)
				,'<a href="http://www.projecthoneypot.org/account_login.php" target="_blank">http://www.projecthoneypot.org/account_login.php</a>'
				);?>
		<br />
		<?php echo __('Allowed values are 0 to 9999. Only numbers are accepted.',SFS_TXTDOMAIN);?>
            <div style="background-color:white;font-size:0.9em;">
		<?php echo sprintf(__('Deny spammers found on Project HoneyPot with incidents less than %s days ago, and with more than %s threat level.'
					.'<br />(25 threat level is average, threat level 5 is fairly low.)',SFS_TXTDOMAIN)
	               			,'<input size="3" name="hnyage" type="text" style="width:75px;" value="'.$hnyage.'"/>'
                        		,'<input size="4" name="hnylevel" type="text" style="width:75px;" value="'.$hnylevel.'"/>'
					);?>
            </div>
        </fieldset>
        <br />
        <fieldset>
		<legend>
		<span style="font-weight:bold;font-size:1.2em;">
		<?php echo __('BotScout API Key',SFS_TXTDOMAIN);?>
		</span>
		</legend>
            <input size="32" name="botscoutapi" type="text" value="<?php echo $botscoutapi; ?>"/><br />
            <p>
		<?php echo sprintf(__('This API key is used for querying the BotScout database.'
				.'<br />It is required if you want to check IP addresses against the botscout.com database.'
				.'<br />You can register and get an API key at %s.',SFS_TXTDOMAIN)
				,'<a href="https://botscout.com/getkey.htm" target="_blank">https://botscout.com/getkey.htm</a>'
				);?>
		<br />
		<?php echo __('Allowed values are 0 to 9999. Only numbers are accepted.',SFS_TXTDOMAIN);?>
		<br />
                <em><?php echo __('Please note that BotScout is disabled in this release because of policy changes at botscout.com.',SFS_TXTDOMAIN);?></em></p>
            <div cellspacing="1" style="background-color:white;font-size:0.9em;">
		<?php echo sprintf(__('Deny spammers found on BotScout with more than %s incidents.',SFS_TXTDOMAIN)
					,'<input size="3" name="botfreq" type="text" style="width:75px;" value="'.$botfreq.'"/>'
				);?>
            </div>
        </fieldset>
        <br />
        <fieldset>
		<legend>
		<span style="font-weight:bold;font-size:1.2em;">
			<?php echo __('Check Against DNSBL Lists Such as Spamhaus.org',SFS_TXTDOMAIN);?>
		</span>
		</legend>
            <input name="chkdnsbl" type="checkbox" value="Y" <?php echo ($chkdnsbl=='Y'?'checked="checked"':'')?> />
			<?php echo __('Checks the IP on Spamhaus.org. This is primarily used for email spam,'
			.' but the same bots sending out email spam are probably running comment spam and other exploits.'
			,SFS_TXTDOMAIN);?>
        </fieldset>
        <br />
        <fieldset>
		<legend>
		<span style="font-weight:bold;font-size:1.2em;">
			<?php echo __('Google Safe Browsing API Key',SFS_TXTDOMAIN);?>
		</span>
		</legend>
		<input size="32" name="googleapi" type="text" value="<?php echo $googleapi; ?>"/>
		<br />
<a href="https://developers.google.com/safe-browsing/key_signup" target="_blank">
<?php echo __('Sign up for a Google Safe Browsing API Key',SFS_TXTDOMAIN);?></a>
<?php echo __('If this API key is present, URLs found in comments will be checked for phishing or malware sites and if found, will be rejected.',SFS_TXTDOMAIN);?></a>
        </fieldset>
        <br />
        <br />
        <p class="submit"><input class="button-primary" value="<?php echo __('Save Changes',SFS_TXTDOMAIN);?>" type="submit"/></p>
    </form>
</div>
<?php
