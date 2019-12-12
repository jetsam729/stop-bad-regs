<?php
/* translate: full */

if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! current_user_can( 'manage_options' ) ) 	die( __('Access Denied',SFS_TXTDOMAIN) );

ss_fix_post_vars();
$now     = date( 'Y/m/d H:i:s', time() + ( get_option( 'gmt_offset' ) * 3600 ) );
$options = ss_get_options();
extract( $options );
$nonce = ( array_key_exists( 'ss_stop_spammers_control', $_POST )?$_POST['ss_stop_spammers_control']:'');


if(!isset($options['blist']))	{$options['blist'] = []; $blist=[];}	

if ( ! empty( $nonce ) && wp_verify_nonce( $nonce, 'ss_stopspam_update' ) ) {

	if ( array_key_exists( 'blist', $_POST ) ) {
		$blist = $_POST['blist'];
		if ( empty( $blist ) ) {
			$blist = array();
		} else {
			$blist = explode( "\n", $blist );
		}
		$tblist = array();
		foreach ( $blist as $bl ) {
			$bl = trim( $bl );
			if ( ! empty( $bl ) ) {
				$tblist[] = $bl;
			}
		}
		$options['blist'] = $tblist;
		$blist            = $tblist;
	}

	if ( array_key_exists( 'spamwords', $_POST ) ) {
		$spamwords = $_POST['spamwords'];
		if ( empty( $spamwords ) ) {
			$spamwords = array();
		} else {
			$spamwords = explode( "\n", $spamwords );
		}
		$tblist = array();
		foreach ( $spamwords as $bl ) {
			$bl = trim( $bl );
			if ( ! empty( $bl ) ) {
				$tblist[] = $bl;
			}
		}
		$options['spamwords'] = $tblist;
		$spamwords            = $tblist;
	}

	if ( array_key_exists( 'badTLDs', $_POST ) ) {
		$badTLDs = $_POST['badTLDs'];
		if ( empty( $badTLDs ) ) {
			$badTLDs = array();
		} else {
			$badTLDs = explode( "\n", $badTLDs );
		}
		$tblist = [];
		foreach ( $badTLDs as $bl ) {
			$bl = trim( $bl );
			if ( ! empty( $bl ) ) {
				$tblist[] = $bl;
			}
		}
		$options['badTLDs'] = $tblist;
		$badTLDs            = $tblist;
	}

	if ( array_key_exists( 'badagents', $_POST ) ) {
		$badagents = $_POST['badagents'];
		if ( empty( $badagents ) ) {
			$badagents = array();
		} else {
			$badagents = explode( "\n", $badagents );
		}
		$tblist = array();
		foreach ( $badagents as $bl ) {
			$bl = trim( $bl );
			if ( ! empty( $bl ) ) {
				$tblist[] = $bl;
			}
		}
		$options['badagents'] = $tblist;
		$badagents            = $tblist;
	}
// check box setting
	$optionlist = array(
		'chkspamwords',
		'chkbluserid',
		'chkagent'
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
	extract( $options );
	$msg = '<div class="notice notice-success"><p>'.__('Options Updated',SFS_TXTDOMAIN).'</p></div>';
}else{
	//
	$options['blist'] = array_values($options['blist']);	// for recovery
}
$nonce = wp_create_nonce( 'ss_stopspam_update' );
?>
<div id="ss-plugin" class="wrap ss-set-alt">
    <h1><?php echo __('Stop Spammers — Block Lists',SFS_TXTDOMAIN); ?></h1>
	<?php if ( ! empty( $msg ) ) {
		echo "$msg";
	} ?>
    <form method="post" action="">
        <input type="hidden" name="action" value="update"/>
        <input type="hidden" name="ss_stop_spammers_control" value="<?php echo $nonce; ?>"/>
        <fieldset>
            <legend><span style="font-weight:bold;font-size:1.2em"><?php echo __('Block List',SFS_TXTDOMAIN); ?></span></legend>
            <p><?php echo __('Put IP addresses or emails here that you want blocked. One email or IP to a line.<br />You can mix email addresses and IP numbers. You can use IPv4 or IPv6 numbers.<br />You can use CIDR format to  block a range (e.g. 1.2.3.4/16)<br />or you can use wild cards (e.g. spammer@spam.* or 1.2.3.*).<br />You can also use this to deny user IDs.<br />This is usually not useful as spammers can change the user ID that they use.<br />To block usernames in this list, check this box.',SFS_TXTDOMAIN); ?>
                <input name="chkbluserid" type="checkbox" value="Y" <?php if ( $chkbluserid == 'Y' ) {
					echo "checked=\"checked\"";
				} ?> /></p>
            <p><?php echo __('These are checked after the Allow List so the Allow List overrides any blocking.',SFS_TXTDOMAIN); ?></p>
<textarea name="blist" cols="40" rows="8">
<?php foreach ( $blist as $p ) echo  "$p\r\n";?>
</textarea>
        </fieldset>
        <br />
        <fieldset>
            <legend><span style="font-weight:bold;font-size:1.2em"><?php echo __('Spam Words List',SFS_TXTDOMAIN); ?></span></legend>
            <p><?php echo __('Use the spam words list to check comment body, email, and author fields.<br />If a word here shows up in an email address or author field then block the comment (wild cards do not work here)',SFS_TXTDOMAIN); ?>
		<br /><strong><?php echo __('Check Spam Words',SFS_TXTDOMAIN); ?></strong>: <input name="chkspamwords" type="checkbox"
                                         value="Y" <?php if ( $chkspamwords == 'Y' ) {
					echo "checked=\"checked\"";
				} ?> /><br /><?php echo __('Add or delete spam words (one word per line)',SFS_TXTDOMAIN); ?>:</p>
<textarea name="spamwords" cols="40" rows="8">
<?php foreach ( $spamwords as $p ) echo "$p\r\n";?>
</textarea>
        </fieldset>
        <br />
        <fieldset>
            <legend><span style="font-weight:bold;font-size:1.2em"><?php echo __('Bad User Agents List',SFS_TXTDOMAIN); ?></span></legend>
            <p><?php echo __('Browsers always include a user agent string when they access a site.<br />A missing user agent is usually a spammer using poorly written software or a leech who is stealing the pages from your site.<br />This option checks for a variety of user agents such as WGET and PHP, Java, or Ruby language standard agents.<br />It also checks for known abusive robots that sometimes submit forms.',SFS_TXTDOMAIN); ?>
		<br /><strong><?php echo __('Check Agents',SFS_TXTDOMAIN); ?></strong>: <input name="chkagent" type="checkbox" value="Y" <?php if ( $chkagent == 'Y' ) {
					echo "checked=\"checked\"";
				} ?> /><br /><?php echo __('Add or delete agent strings (one word per line)',SFS_TXTDOMAIN); ?>:</p>
<textarea name="badagents" cols="40" rows="8">
<?php foreach($badagents as $p) echo  "$p\r\n";?>
</textarea>
            <br />
            <p><?php echo __('This is a string search so that all you have to enter is enough of the agent to match. Telesoft matches Telesoft Spider or Telesoft 3.2.',SFS_TXTDOMAIN); ?></p>
        </fieldset>
        <br />
        <fieldset>
            <legend><span style="font-weight:bold;font-size:1.2em"><?php echo __('Blocked TLDs',SFS_TXTDOMAIN); ?></span></legend>
		<p><?php echo __("Enter the TLD name including the '.' e.g. .XXX<br /><strong>This only works for email addresses entered by the user.</strong><br />This will block all comments and registrations that use this TLD in domains for emails.<br />If you have a problem with a more complex sub-domains you can also use this to check anything after the first period.<br />This is not for stopping domains, though. Entering '.xxx.ru' will stop 'user@mail.xxx.ru',but it will not stop 'user@xxx.ru'.<br />Blocked TLDs (One TLD per line not case sensitive)",SFS_TXTDOMAIN); ?>:
		</p>
<textarea name="badTLDs" cols="40" rows="8">
<?php foreach($badTLDs as $p) echo "$p\r\n";?>
</textarea>
		<br />
		<p><?php echo __('A TLD is the last part of a domain like .COM or .NET.<br />You can block emails from various countries this way by adding a TLD such as .CN or .RU (these will block Russia and China).<br />It will not block the whole country.<br />A list of TLDs can be found at <a href="https://wikipedia.org/wiki/List_of_Internet_top-level_domains" target="_blank">Wikipedia list of internet top-level domains</a>.',SFS_TXTDOMAIN); ?></p>
        </fieldset>
        <br />
        <p class="submit"><input class="button-primary" value="<?php echo __('Save Changes',SFS_TXTDOMAIN); ?>" type="submit"/></p>
    </form>
</div>
<?php
