<?php
/* translate: full */

if ( ! defined( 'ABSPATH' ) ) die;
if ( ! current_user_can( 'manage_options' ) ) die( __('Access Denied',SFS_TXTDOMAIN) );

ss_fix_post_vars();
$now     = date( 'Y/m/d H:i:s', time() + ( get_option( 'gmt_offset' ) * 3600 ) );
$options = ss_get_options();
extract( $options );

$nonce = (array_key_exists( 'ss_stop_spammers_control', $_POST )?$_POST['ss_stop_spammers_control']:'');
$msg   = '';

if ( wp_verify_nonce( $nonce, 'ss_stopspam_update' ) ) {
	if ( array_key_exists( 'action', $_POST ) ) {
		$optionlist = array( 'redir', 'notify', 'wlreq' );
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
// other options
		if ( array_key_exists( 'redirurl', $_POST ) ) {
			$redirurl            = trim( stripslashes( $_POST['redirurl'] ) );
			$options['redirurl'] = $redirurl;
		}
		if ( array_key_exists( 'wlreqmail', $_POST ) ) {
			$wlreqmail            = trim( stripslashes( $_POST['wlreqmail'] ) );
			$options['wlreqmail'] = $wlreqmail;
		}
		if ( array_key_exists( 'rejectmessage', $_POST ) ) {
			$rejectmessage            = trim( stripslashes( $_POST['rejectmessage'] ) );
			$options['rejectmessage'] = $rejectmessage;
		}
		if ( array_key_exists( 'chkcaptcha', $_POST ) ) {
			$chkcaptcha            = trim( stripslashes( $_POST['chkcaptcha'] ) );
			$options['chkcaptcha'] = $chkcaptcha;
		}
// added the API key stiff for Captchas
		if ( array_key_exists( 'recaptchaapisecret', $_POST ) ) {
			$recaptchaapisecret            = stripslashes( $_POST['recaptchaapisecret'] );
			$options['recaptchaapisecret'] = $recaptchaapisecret;
		}
		if ( array_key_exists( 'recaptchaapisite', $_POST ) ) {
			$recaptchaapisite            = stripslashes( $_POST['recaptchaapisite'] );
			$options['recaptchaapisite'] = $recaptchaapisite;
		}
		if ( array_key_exists( 'solvmediaapivchallenge', $_POST ) ) {
			$solvmediaapivchallenge            = stripslashes( $_POST['solvmediaapivchallenge'] );
			$options['solvmediaapivchallenge'] = $solvmediaapivchallenge;
		}
		if ( array_key_exists( 'solvmediaapiverify', $_POST ) ) {
			$solvmediaapiverify            = stripslashes( $_POST['solvmediaapiverify'] );
			$options['solvmediaapiverify'] = $solvmediaapiverify;
		}
// validate the chkcaptcha variable
		if ( $chkcaptcha == 'G' && ( $recaptchaapisecret == '' || $recaptchaapisite == '' ) ) {
			$chkcaptcha            = 'Y';
			$options['chkcaptcha'] = $chkcaptcha;
			$msg                   = "You cannot use Google reCAPTCHA unless you have entered an API key";
		}
		if ( $chkcaptcha == 'S' && ( $solvmediaapivchallenge == '' || $solvmediaapiverify == '' ) ) {
			$chkcaptcha            = 'Y';
			$options['chkcaptcha'] = $chkcaptcha;
			$msg                   = "You cannot use Solve Media CAPTCHA unless you have entered an API key";
		}
		ss_set_options( $options );
		extract( $options ); // extract again to get the new options
	}
	$update = '<div class="notice notice-success"><p>'.__('Options Updated',SFS_TXTDOMAIN).'</p></div>';
}
$nonce = wp_create_nonce( 'ss_stopspam_update' );
?>
<div id="ss-plugin" class="wrap ss-set-alt">
    <h1><?php echo __('Stop Spammers — Challenge and Deny',SFS_TXTDOMAIN);?></h1>
	<?php
		if ( ! empty( $update ) ) echo "$update";
		if ( ! empty( $msg ) ) 	echo "<span style=\"color:red;size=2em;\">$msg</span>";
	?>
    <form method="post" action="">
        <input type="hidden" name="ss_stop_spammers_control" value="<?php echo $nonce; ?>"/>
        <input type="hidden" name="action" value="update challenge"/>
        <fieldset>
		<legend>
		<span style="font-weight:bold;font-size:1.2em;">
		<?php echo __('Spammer Message',SFS_TXTDOMAIN);?>
		</span>
		</legend>
		<p><?php echo __('This message is only visible to spammers.'
			.'<br />It only shows if spammers are rejected at the time the login or comment form is displayed.'
			.'<br />You can use the shortcode <em>[reason]</em> to include the deny reason code with the message.'
			.'<br />You can also use <em>[ip]</em> in your message which would be the user\'s IP address.'
			.'<br />(You may not want to give spammers hints on how they were denied.)'
			,SFS_TXTDOMAIN);?>
		</p>
            <textarea id="rejectmessage" name="rejectmessage" cols="40" rows="5"><?php echo $rejectmessage; ?></textarea>
        </fieldset>
        <br />
        <fieldset>
		<legend><span style="font-weight:bold;font-size:1.2em;">
			<?php echo __('Send Spammer to Another Web Page',SFS_TXTDOMAIN);?>
			</span>
		</legend>
            <?php echo __('Enable redirect',SFS_TXTDOMAIN);?>:
            <input type="checkbox" name="redir" value="Y" <?php echo ($redir=='Y'?'checked="checked"':'');?> />
            <br />
		<p>
		<?php echo __('If you want you can send the spammer to a web page.'
			.'<br />This can be a custom page explaining terms of service for example.',SFS_TXTDOMAIN);?>
		</p>
            Redirect URL:
            <input size="77" name="redirurl" type="text" value="<?php echo $redirurl; ?>"/>
        </fieldset>
        <br />
        <fieldset>
            <legend>
		<span style="font-weight:bold;font-size:1.2em;">
			<?php echo __('Allow Users to Add to the Allow Request List',SFS_TXTDOMAIN);?>
		</span>
            </legend>
		<p>
			<?php echo __('Users can see the form to add themselves to the request list, but lots of spammers fill it out randomly.'
				.'<br />This hides the request form.',SFS_TXTDOMAIN);?>
		</p>
            <?php echo __('Blocked users see the Allow Request form',SFS_TXTDOMAIN);?>:
            <input type="checkbox" name="wlreq" value="Y" <?php echo ($wlreq=='Y'?'checked="checked"':'')?>/>
        </fieldset>
        <br />
        <fieldset>
		<legend>
		<span style="font-weight:bold;font-size:1.2em;">
			<?php echo __('Notify Webmaster When a User Requests to be Added to the Allow List',SFS_TXTDOMAIN);?>
		</span>
		</legend>
		<p>
			<?php echo __('Blocked users can add their email addresses to the the Allow List request.'
			.'<br />This will also send you an email notification.',SFS_TXTDOMAIN);?>
		</p>
			<?php echo __('Enable email request',SFS_TXTDOMAIN);?>:
			<input type="checkbox" name="notify" value="Y" <?php echo ($notify=='Y'?'checked="checked"':'');?>/>
			<br />
			<?php echo __('(Optional) email where requests are sent',SFS_TXTDOMAIN);?>:
			<br />
            <input size="48" name="wlreqmail" type="text" value="<?php echo $wlreqmail; ?>"/>
        </fieldset>
        <br />
        <fieldset>
		<legend>
		<span style="font-weight:bold;font-size:1.2em;">
			<?php echo __('Second Chance CAPTCHA Challenge',SFS_TXTDOMAIN);?>
		</span>
		</legend>
			<?php
			if ( ! empty( $msg ) ) {
				echo "<span style=\"color:red;size=1.2em;\">$msg</span>";
			}
			?>
		<p>
			<?php echo __('The plugin is extremely aggressive and will probably block some small number of legitimate users.'
			.'<br />You can give users a second chance by displaying a CAPTCHA image and asking them to type in the letters that they see.'
			.'<br />This prevents lockouts.'
			.'<br />This option will override the email notification option above.'
			.'<br />By default, the plugin will support the arithmetic question, which is okay.'
			.'<br />For better results, use Google\'s reCAPTCHA, or you can try SolveMedia\'s CAPTCHA'
			,SFS_TXTDOMAIN);?>
			<br />
                <input type="radio" value="N" name="chkcaptcha" <?php echo ( $chkcaptcha == 'N'?'checked="checked"':'');?>/>
			<?php echo __('No CAPTCHA (default)',SFS_TXTDOMAIN);?><br />
                <input type="radio" value="G" name="chkcaptcha" <?php echo ( $chkcaptcha == 'G'?'checked="checked"':'');?>/>
			<?php echo __('Google reCAPTCHA',SFS_TXTDOMAIN);?><br />
                <input type="radio" value="S" name="chkcaptcha" <?php echo ( $chkcaptcha == 'S'?'checked="checked"':'');?>/>
			<?php echo __('Solve Media CAPTCHA',SFS_TXTDOMAIN);?><br />
                <input type="radio" value="A" name="chkcaptcha" <?php echo ( $chkcaptcha == 'A'?'checked="checked"':'');?>/>
			<?php echo __('Arithmetic Question',SFS_TXTDOMAIN);?>
		</p>
		<p>
		<?php echo __('In order to use Solve Media or Google reCAPTCHA you will need to get an API key.'
			.'<br />Open CAPTCHA is no longer supported so the arithmetic question will be used for those that had it set.'
			,SFS_TXTDOMAIN);?>
		</p>
        </fieldset>
        <br />
        <fieldset>
		<legend>
		<span style="font-weight:bold;font-size:1.2em;">
		<?php echo __('Google reCAPTCHA API Key',SFS_TXTDOMAIN);?>
		</span>
		</legend>
		<?php echo __('Site Key',SFS_TXTDOMAIN);?>:
            <input size="64" name="recaptchaapisite" type="text" value="<?php echo $recaptchaapisite; ?>"/>
            <br />
		<?php echo __('Secret Key',SFS_TXTDOMAIN);?>:
            <input size="64" name="recaptchaapisecret" type="text" value="<?php echo $recaptchaapisecret; ?>"/>
            <br />
		<p>
		<?php echo sprintf(__('These API keys are used for displaying a Google reCAPTCHA on your site.'
		.'<br />You can display the reCAPTCHA in case a real user is blocked, so they can still leave a comment.'
		.'<br />You can register and get an API key at %s.'
		.'<br />If the keys are correct you should see the reCAPTCHA here'
		,SFS_TXTDOMAIN)
		,'<a href="https://www.google.com/recaptcha/admin#list" target="_blank">https://www.google.com/recaptcha/admin#list</a>'
		);?>:
		</p>
			<?php
			if ( ! empty( $recaptchaapisite ) ) {
				?>
                <script type="text/javascript" src="https://www.google.com/recaptcha/api.js" async defer></script>
                <div class="g-recaptcha" data-sitekey="<?php echo $recaptchaapisite; ?>"></div>
		<?php echo __('If the reCAPTCHA form looks good, you need to enable the reCAPTCHA on the Challenge'
				.' &amp; Deny options page. (see left)'
				,SFS_TXTDOMAIN);?>
				<?php
			}
			?>
        </fieldset>
        <br />
        <fieldset>
		<legend>
		<span style="font-weight:bold;font-size:1.2em;">
		<?php echo __('Solve Media CAPTCHA API Key',SFS_TXTDOMAIN);?>
		</span>
		</legend>
		<?php echo __('Solve Media Challenge Key',SFS_TXTDOMAIN);?>:
            <input size="64" name="solvmediaapivchallenge" type="text" value="<?php echo $solvmediaapivchallenge; ?>"/>
            <br />
		<?php echo __('Solve Media Verification Key',SFS_TXTDOMAIN);?>:
            <input size="64" name="solvmediaapiverify" type="text" value="<?php echo $solvmediaapiverify; ?>"/>
            <br />
            <p>
		<?php echo sprintf(__('This API key is used for displaying a Solve Media CAPTCHA on your site.'
				.'<br />You can display the CAPTCHA in case a real user is blocked, so they can still leave a comment.'
				.'<br />You can register and get an API key at %s .'
				.'<br />If the keys are correct you should see the CAPTCHA here'
				,SFS_TXTDOMAIN)
				,'<a href="https://portal.solvemedia.com/portal/public/signup" target="_blank">https://portal.solvemedia.com/portal/public/signup</a>'
				);?>:
		</p>
			<?php
			if ( ! empty( $solvmediaapivchallenge ) ) {
				?>
                <script type="text/javascript"
                        src="https://api-secure.solvemedia.com/papi/challenge.script?k=<?php echo $solvmediaapivchallenge; ?>">
                </script>
                <p><?php echo __('If the reCAPTCHA form looks good, you need to enable the reCAPTCHA on the Challenge'
				.' &amp; Deny options page. (see left)',SFS_TXTDOMAIN);?>
		</p>
				<?php
			}
			?>
        </fieldset>
        <br />
        <br />
        <p class="submit"><input class="button-primary" value="<?php echo __('Save Changes',SFS_TXTDOMAIN);?>" type="submit"/></p>
    </form>
</div>