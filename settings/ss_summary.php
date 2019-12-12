<?php
/* translate: full */

if ( ! defined( 'ABSPATH' ) ) die;
if ( ! current_user_can( 'manage_options' ) ) die( __('Access Denied',SFS_TXTDOMAIN) );

if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'protect' ) ) {
	echo 	'<div>'
		.__(
		'Jetpack Protect has been detected. Stop Spammers has disabled itself.<br />'
		.'Please turn off Jetpack Protect or uninstall Stop Spammers.'
		,SFS_TXTDOMAIN)
		.'</div>';
	return;
}

ss_fix_post_vars();
$stats = ss_get_stats();
extract( $stats );
$now = date( 'Y/m/d H:i:s', time() + ( get_option( 'gmt_offset' ) * 3600 ) );
// counter list - this should be copied from the get option utility
// counters should have the same name as the YN switch for the check
// I see lots of missing counters here
$counters = array(
	'cntpass'             => __('Total Pass',SFS_TXTDOMAIN), // passed

	'cntcap'              => __('Passed CAPTCHA',SFS_TXTDOMAIN), // captha success
	'cntncap'             => __('Failed CAPTCHA',SFS_TXTDOMAIN), // captha not success

	'cntchk404'           => __('404 Exploit Attempt',SFS_TXTDOMAIN),
	'cntchkaccept'        => __('Bad or Missing Accept Header',SFS_TXTDOMAIN),
	'cntchkadmin'         => __('Admin Login Attempt',SFS_TXTDOMAIN),
	'cntchkadminlog'      => __('Passed Login OK',SFS_TXTDOMAIN),
	'cntchkagent'         => __('Bad or Missing User Agent',SFS_TXTDOMAIN),
	'cntchkakismet'       => __('Reported by Akismet',SFS_TXTDOMAIN),
	'cntchkamazon'        => __('Amazon AWS',SFS_TXTDOMAIN),
	'cntchkaws'           => __('Amazon AWS Allow',SFS_TXTDOMAIN),
	'cntchkbbcode'        => __('BBCode in Request',SFS_TXTDOMAIN),
	'cntchkbcache'        => __('Bad Cache',SFS_TXTDOMAIN),
	'cntchkblem'          => __('Deny List Email',SFS_TXTDOMAIN),
	'cntchkblip'          => __('Deny List IP',SFS_TXTDOMAIN),
	'cntchkbotscout'      => __('BotScout',SFS_TXTDOMAIN),
	'cntchkcloudflare'    => __('Pass Cloudflare',SFS_TXTDOMAIN),
	'cntchkdisp'          => __('Disposable Email',SFS_TXTDOMAIN),
	'cntchkdnsbl'         => __('DNSBL Hit',SFS_TXTDOMAIN),
	'cntchkexploits'      => __('Exploit Attempt',SFS_TXTDOMAIN),
	'cntchkform'          => __('Check for Standard Form',SFS_TXTDOMAIN),
	'cntchkgcache'        => __('Pass Good Cache',SFS_TXTDOMAIN),
	'cntchkgenallowlist'  => __('Pass Generated Allow List',SFS_TXTDOMAIN),
	'cntchkgoogle'        => __('Pass Google',SFS_TXTDOMAIN),
	'cntchkgooglesafe'    => __('Google Safe Browsing',SFS_TXTDOMAIN),
	'cntchkhoney'         => __('Project Honeypot',SFS_TXTDOMAIN),
	'cntchkhosting'       => __('Known Spam Host',SFS_TXTDOMAIN),
	'cntchkinvalidip'     => __('Block Invalid IP',SFS_TXTDOMAIN),
	'cntchklong'          => __('Long Email',SFS_TXTDOMAIN),
	'cntchkmiscallowlist' => __('Pass Allow List',SFS_TXTDOMAIN),
	'cntchkmulti'         => __('Repeated Hits',SFS_TXTDOMAIN),
	'cntchkpaypal'        => __('Pass PayPal',SFS_TXTDOMAIN),
	'cntchkreferer'       => __('Bad HTTP_REFERER',SFS_TXTDOMAIN),
	'cntchkscripts'       => __('Pass Scripts',SFS_TXTDOMAIN),
	'cntchksession'       => __('Session Speed',SFS_TXTDOMAIN),
	'cntchksfs'           => __('Stop Forum Spam',SFS_TXTDOMAIN),
	'cntchkshort'         => __('Short Email',SFS_TXTDOMAIN),
	'cntchkspamwords'     => __('Spam Words',SFS_TXTDOMAIN),
	'cntchktld'           => __('Email TLD',SFS_TXTDOMAIN),
	'cntchkubiquity'      => __('Ubiquity Servers',SFS_TXTDOMAIN),
	'cntchkuserid'        => __('Allow User ID/Author',SFS_TXTDOMAIN),
	'cntchkuserid'        => __('Deny User ID/Author',SFS_TXTDOMAIN),
	'cntchkvalidip'       => __('Pass Uncheckable IP',SFS_TXTDOMAIN),
	'cntchkwlem'          => __('Allow List Email',SFS_TXTDOMAIN),
	'cntchkwlist'         => __('Pass Allow List IP',SFS_TXTDOMAIN),
	'cntchkyahoomerchant' => __('Pass Yahoo merchant',SFS_TXTDOMAIN),

	'cntchkAD'            => 'Andorra',
	'cntchkAE'            => 'United Arab Emirates',
	'cntchkAF'            => 'Afghanistan',
	'cntchkAL'            => 'Albania',
	'cntchkAM'            => 'Armenia',
	'cntchkAR'            => 'Argentina',
	'cntchkAT'            => 'Austria',
	'cntchkAU'            => 'Australia',
	'cntchkAX'            => 'Aland Islands',
	'cntchkAZ'            => 'Azerbaijan',
	'cntchkBA'            => 'Bosnia And Herzegovina',
	'cntchkBB'            => 'Barbados',
	'cntchkBD'            => 'Bangladesh',
	'cntchkBE'            => 'Belgium',
	'cntchkBG'            => 'Bulgaria',
	'cntchkBH'            => 'Bahrain',
	'cntchkBN'            => 'Brunei Darussalam',
	'cntchkBO'            => 'Bolivia',
	'cntchkBR'            => 'Brazil',
	'cntchkBS'            => 'Bahamas',
	'cntchkBY'            => 'Belarus',
	'cntchkBZ'            => 'Belize',
	'cntchkCA'            => 'Canada',
	'cntchkCD'            => 'Congo, Democratic Republic',
	'cntchkCH'            => 'Switzerland',
	'cntchkCL'            => 'Chile',
	'cntchkCN'            => 'China',
	'cntchkCO'            => 'Colombia',
	'cntchkCR'            => 'Costa Rica',
	'cntchkCU'            => 'Cuba',
	'cntchkCW'            => 'CuraÃ§ao',
	'cntchkCY'            => 'Cyprus',
	'cntchkCZ'            => 'Czech Republic',
	'cntchkDE'            => 'Germany',
	'cntchkDK'            => 'Denmark',
	'cntchkDO'            => 'Dominican Republic',
	'cntchkDZ'            => 'Algeria',
	'cntchkEC'            => 'Ecuador',
	'cntchkEE'            => 'Estonia',
	'cntchkES'            => 'Spain',
	'cntchkEU'            => 'European Union',
	'cntchkFI'            => 'Finland',
	'cntchkFJ'            => 'Fiji',
	'cntchkFR'            => 'France',
	'cntchkGB'            => 'Great Britain',
	'cntchkGE'            => 'Georgia',
	'cntchkGF'            => 'French Guiana',
	'cntchkGI'            => 'Gibraltar',
	'cntchkGP'            => 'Guadeloupe',
	'cntchkGR'            => 'Greece',
	'cntchkGT'            => 'Guatemala',
	'cntchkGU'            => 'Guam',
	'cntchkGY'            => 'Guyana',
	'cntchkHK'            => 'Hong Kong',
	'cntchkHN'            => 'Honduras',
	'cntchkHR'            => 'Croatia',
	'cntchkHT'            => 'Haiti',
	'cntchkHU'            => 'Hungary',
	'cntchkID'            => 'Indonesia',
	'cntchkIE'            => 'Ireland',
	'cntchkIL'            => 'Israel',
	'cntchkIN'            => 'India',
	'cntchkIQ'            => 'Iraq',
	'cntchkIR'            => 'Iran, Islamic Republic Of',
	'cntchkIS'            => 'Iceland',
	'cntchkIT'            => 'Italy',
	'cntchkJM'            => 'Jamaica',
	'cntchkJO'            => 'Jordan',
	'cntchkJP'            => 'Japan',
	'cntchkKE'            => 'Kenya',
	'cntchkKG'            => 'Kyrgyzstan',
	'cntchkKH'            => 'Cambodia',
	'cntchkKR'            => 'Korea',
	'cntchkKW'            => 'Kuwait',
	'cntchkKY'            => 'Cayman Islands',
	'cntchkKZ'            => 'Kazakhstan',
	'cntchkLA'            => "Lao People's Democratic Republic",
	'cntchkLB'            => 'Lebanon',
	'cntchkLK'            => 'Sri Lanka',
	'cntchkLT'            => 'Lithuania',
	'cntchkLU'            => 'Luxembourg',
	'cntchkLV'            => 'Latvia',
	'cntchkMD'            => 'Moldova',
	'cntchkME'            => 'Montenegro',
	'cntchkMK'            => 'Macedonia',
	'cntchkMM'            => 'Myanmar',
	'cntchkMN'            => 'Mongolia',
	'cntchkMO'            => 'Macao',
	'cntchkMP'            => 'Northern Mariana Islands',
	'cntchkMQ'            => 'Martinique',
	'cntchkMT'            => 'Malta',
	'cntchkMV'            => 'Maldives',
	'cntchkMX'            => 'Mexico',
	'cntchkMY'            => 'Malaysia',
	'cntchkNC'            => 'New Caledonia',
	'cntchkNI'            => 'Nicaragua',
	'cntchkNL'            => 'Netherlands',
	'cntchkNO'            => 'Norway',
	'cntchkNP'            => 'Nepal',
	'cntchkNZ'            => 'New Zealand',
	'cntchkOM'            => 'Oman',
	'cntchkPA'            => 'Panama',
	'cntchkPE'            => 'Peru',
	'cntchkPG'            => 'Papua New Guinea',
	'cntchkPH'            => 'Philippines',
	'cntchkPK'            => 'Pakistan',
	'cntchkPL'            => 'Poland',
	'cntchkPR'            => 'Puerto Rico',
	'cntchkPS'            => 'Palestinian Territory, Occupied',
	'cntchkPT'            => 'Portugal',
	'cntchkPW'            => 'Palau',
	'cntchkPY'            => 'Paraguay',
	'cntchkQA'            => 'Qatar',
	'cntchkRO'            => 'Romania',
	'cntchkRS'            => 'Serbia',
	'cntchkRU'            => 'Russian Federation',
	'cntchkSA'            => 'Saudi Arabia',
	'cntchkSC'            => 'Seychelles',
	'cntchkSE'            => 'Sweden',
	'cntchkSG'            => 'Singapore',
	'cntchkSI'            => 'Slovenia',
	'cntchkSK'            => 'Slovakia',
	'cntchkSV'            => 'El Salvador',
	'cntchkSX'            => 'Sint Maarten',
	'cntchkSY'            => 'Syrian Arab Republic',
	'cntchkTH'            => 'Thailand',
	'cntchkTJ'            => 'Tajikistan',
	'cntchkTM'            => 'Turkmenistan',
	'cntchkTR'            => 'Turkey',
	'cntchkTT'            => 'Trinidad And Tobago',
	'cntchkTW'            => 'Taiwan',
	'cntchkUA'            => 'Ukraine',
	'cntchkUK'            => 'United Kingdom',
	'cntchkUS'            => 'United States',
	'cntchkUY'            => 'Uruguay',
	'cntchkUZ'            => 'Uzbekistan',
	'cntchkVC'            => 'Saint Vincent And Grenadines',
	'cntchkVE'            => 'Venezuela',
	'cntchkVN'            => 'Viet Nam',
	'cntchkYE'            => 'Yemen',

);

$message  = "";
$nonce    = (array_key_exists( 'ss_stop_spammers_control', $_POST )?$_POST['ss_stop_spammers_control']:'');

if ( wp_verify_nonce( $nonce, 'ss_stopspam_update' ) ) {
	if ( array_key_exists( 'clear', $_POST ) ) {
		foreach ( $counters as $v1 => $v2 ) {
			$stats[ $v1 ] = 0;
		}
		$addonstats          = array();
		$stats['addonstats'] = $addonstats;
		$msg                 = '<div class="notice notice-success"><p>'.__('Summary Cleared',SFS_TXTDOMAIN).'</p></div>';
		ss_set_stats( $stats );
		extract( $stats ); // extract again to get the new options
	}
	if ( array_key_exists( 'update_total', $_POST ) ) {
		$stats['spmcount'] = $_POST['spmcount'];
		$stats['spmdate']  = $_POST['spmdate'];
		ss_set_stats( $stats );
		extract( $stats ); // extract again to get the new options
	}
}
$nonce = wp_create_nonce( 'ss_stopspam_update' );

?>
<div id="ss-plugin" class="wrap ss-set-alt">
    <h1><?php echo __('Stop Spammers — Summary',SFS_TXTDOMAIN); ?></h1>
    <p>Version <span class="green"><?php echo SS_VERSION; ?></span></p>
	<?php
	if ( ! empty( $msg ) ) {
		echo "$msg";
	}
	$current_user_name = wp_get_current_user()->user_login;
	if ( $current_user_name == 'admin' ) {
		echo '<p style="color:red;font-style:italic;">'
			.__('You are using the admin ID "admin". This is an invitation to hackers to try and guess your password. Please change this.'
			.'<br />Here is discussion on WordPress.org'
			,SFS_TXTDOMAIN)
				.' : <a href="https://wordpress.org/support/topic/how-to-change-admin-username?replies=4" target="_blank">'
				.__('How to Change Admin Username',SFS_TXTDOMAIN)
				.'</a>'
			.'</p>';
	}
	$showcf = false; // hide this for now
	if ( $showcf && array_key_exists( 'HTTP_CF_CONNECTING_IP', $_SERVER ) && ! function_exists( 'cloudflare_init' ) && ! defined( 'W3TC' ) ) {
		echo '<p style="color:red;font-style:italic;">'
		.sprintf(__('Cloudflare Remote IP address detected. Please install the %s.<br />This plugin works best with the Cloudflare plugin when yout website is using Cloudflare.',SFS_TXTDOMAIN)
			,'<a href="https://wordpress.org/plugins/cloudflare/" target="_blank">'.__('Cloudflare Plugin.',SFS_TXTDOMAIN).'</a>'
			)
		.'</p>';
	}

	if ( $spmcount > 0 ) {
		?>
        <script type="text/javascript">
            function showcheat() {
                var el = document.getElementById('cheater');
		if(el.style.display == 'block'){
			el.style.display = 'none';
		}else{
                	el.style.display = 'block';
		}
                return false;
            }
        </script>

<?php echo sprintf(	__('Stop Spammers in total has stopped %s spammers since %s.',SFS_TXTDOMAIN)
			,"<a href=\"\" onclick=\"showcheat();return false;\" class=\"green\">$spmcount</a>"
			,$spmdate
		);
?>
        <div id="cheater" style="display:none"><?php echo __('This is cheating! Enter a new Total Spam Count',SFS_TXTDOMAIN);?>:
	    <br />
            <form method="post" action="">
                <input type="hidden" name="ss_stop_spammers_control" value="<?php echo $nonce; ?>"/>
                <input type="hidden" name="update_total" value="Update Total"/>
                <?php echo __('Count',SFS_TXTDOMAIN);?>:<input type="text" name="spmcount" value="<?php echo $spmcount; ?>"/><br />
                <?php echo __('Date',SFS_TXTDOMAIN);?>: <input type="text" name="spmdate" value="<?php echo $spmdate; ?>"/><br />
                <p class="submit" style="clear:both"><input class="button-primary" value="<?php echo __('Update Total Spam',SFS_TXTDOMAIN);?>" type="submit"/></p>
            </form>
        </div>
		<?php
	}

	if ( $spcount > 0 ) {
		?>
        <p>
<?php echo sprintf(	__('Stop Spammers has stopped %s spammers since %s.',SFS_TXTDOMAIN)
			,"<span class=\"green\">$spcount</span>"
			,$spdate
		);
?>
	</p>
		<?php
	}

	$num_comm = wp_count_comments();
	$num      = number_format_i18n( $num_comm->spam );
	if ( $num_comm->spam > 0 && SS_MU != 'Y' ) {
		?>
        <p>There are <a href='edit-comments.php?comment_status=spam'><?php echo $num; ?></a> spam comments waiting for you to report them.</p>
		<?php
	}
	$num_comm = wp_count_comments();
	$num      = number_format_i18n( $num_comm->moderated );
	if ( $num_comm->moderated > 0 && SS_MU != 'Y' ) {
		?>
        <p>There are <a href='edit-comments.php?comment_status=moderated'><?php echo $num; ?></a> comments waiting to be moderated.</p>
		<?php
	}
	$summry = '<div>';

	foreach ( $counters as $v1 => $v2 ) {

		if($v1=='cntchkAD'){ // for delim for start country block
			$summry .= '<div class="stat-box" style="border:0;width:100%;">&nbsp;</div>';
		}

		if (  !empty($stats[ $v1 ]) ) {
			$summry .= "<div class='stat-box'><strong>$v2</strong> : " . $stats[ $v1 ] . "</div>";
		} else {
// echo "  $v1 - $v2 , ";
			$summry .= "<div class='stat-box' style='font-size:10px;'>$v2 : " . $stats[ $v1 ] . "</div>";
		}
	}
	$summry .='';

	$addonstats = $stats['addonstats'];
	foreach ( $addonstats as $key => $data ) {
// count is in data[0] and use the plugin name
		$summry .= "<div class='stat-box'><strong>$key</strong> : " . $data[0] . "</div>";
	}
	if ( ! empty( $summry ) ) {
		?>
		<?php
	}
	$ip = ss_get_ip();
	?>
    <p><?php echo __('Your current IP address is',SFS_TXTDOMAIN)." : <span class=\"green\"><strong>$ip</strong></span>"; echo ' ('.$_SERVER['REMOTE_ADDR'].')';?></p>
		<?php
		// check the IP to see if we are local
		$ansa = be_load( 'chkvalidip', ss_get_ip() );
		if ( $ansa !== false ) {
		?>
    <p><?php echo __('This address is invalid for testing for the following reason',SFS_TXTDOMAIN);?>:
        <span style="font-weight:bold;font-size:1.2em"><?php echo $ansa; ?></span>.
<?php echo __('<br />If you working on a local installation of WordPress, this might be OK.'
.'<br />However, if the plugin reports that your IP is invalid it may be because you are using Cloudflare or a proxy server to access this page.'
.'<br />This will make it impossible for the plugin to check IP addresses.'
.'<br />You may want to go to the Stop Spammers Testing page in order to test all possible reasons'
.'<br />that your IP is not appearing as the IP of the machine that your using to browse this site.'
.'<br />It is possible to use the plugin if this problem appears, but most checking functions will be turned off.'
.'<br />The plugin will still perform spam checks which do not require an IP.'
.'<br />If the error says that this is a Cloudflare IP address, you can fix this by installing the Cloudflare plugin.'
.'<br />If you use Cloudflare to protect and speed up your site then you MUST install the Cloudflare plugin.'
.'<br />This plugin will be crippled until you install it.',
	SFS_TXTDOMAIN);
?>
   </p>
<?php
}
// need the current guy
$sname = '';
if ( isset( $_SERVER['REQUEST_URI'] ) ) {
	$sname = $_SERVER["REQUEST_URI"];
}
if ( empty( $sname ) ) {
	$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
	$sname                  = $_SERVER["SCRIPT_NAME"];
}
if ( strpos( $sname, '?' ) !== false ) {
	$sname = substr( $sname, 0, strpos( $sname, '?' ) );
}
?>
    <fieldset>
        <legend><span style="font-weight:bold;font-size:1.2em"><?php echo __('Summary of Spam',SFS_TXTDOMAIN);?></span></legend>
		<?php
		echo $summry;
		?>
        <form method="post" action="">
            <input type="hidden" name="ss_stop_spammers_control" value="<?php echo $nonce; ?>"/>
            <input type="hidden" name="clear" value="clear summary"/>
            <p class="submit" style="clear:both"><input class="button-primary" value="<?php echo __('Clear Summary',SFS_TXTDOMAIN);?>" type="submit"/></p>
        </form>
    </fieldset>
    <h2><?php echo __('Get Support and Help Improve Stop Spammers',SFS_TXTDOMAIN);?></h2>
    <h3><?php echo __('Free Support',SFS_TXTDOMAIN);?></h3>
    <p>First, <a href="https://github.com/bhadaway/stop-spammers/wiki/faqs" target="_blank">read the FAQs page</a>.
Then, please post all issues, bugs, typos, questions, suggestions, requests, and complaints <a href="https://github.com/bhadaway/stop-spammers/issues" target="_blank">on GitHub</a>. Thank you.
   </p>
    <h3><?php echo __('Paid Support',SFS_TXTDOMAIN);?></h3>
    <p>
<?php
	echo __('If you need more advanced help securing your site or other web services,'
		.'<br />and are interested in hiring me, please <a href="mailto:bhadaway@gmail.com">email me</a>. — <em>Bryan</em>'
		,SFS_TXTDOMAIN);
?>
</p>
    <h2><?php echo __('Plugin Options',SFS_TXTDOMAIN);?></h2>
    <ul>
        <li><a href="?page=stop_spammers"><?php echo __('Summary',SFS_TXTDOMAIN); ?></a> :
<?php echo __('This checks to see if there may be problems from your current incoming IP address and displays a summary of events.',SFS_TXTDOMAIN); ?></li>
        <li><a href="?page=ss_options"><?php echo __('Protection Options',SFS_TXTDOMAIN); ?></a> :
<?php echo __('This has all the options for checking for spam and logins. You can also block whole countries.',SFS_TXTDOMAIN);?></li>
        <li><a href="?page=ss_allow_list"><?php echo __('Allow Lists',SFS_TXTDOMAIN); ?></a> :
<?php echo __('Here you can set up your Allow List to allow IP addresses to log in and leave comments on your site, without being checked for spam. It also sets up the options which you can use to allow certain kinds of users into your site, even though they may trigger spam detection.',SFS_TXTDOMAIN);?></li>
        <li><a href="?page=ss_deny_list"><?php echo __('Block Lists',SFS_TXTDOMAIN); ?></a> :
<?php echo __('This is where you set up your Deny List for IPs and emails. It also allows you to enter spam words and phrases that trigger spam.',SFS_TXTDOMAIN);?></li>
        <li><a href="?page=ss_challenge"><?php echo __('Challenge &amp; Deny',SFS_TXTDOMAIN); ?></a> :
<?php echo __('This sets up CAPTCHA and notification options. You can give users who trigger the plugin a second chance to use a CAPTCHA. Supports Google reCAPTCHA and Solve Media CAPTCHA.',SFS_TXTDOMAIN);?></li>
        <li><a href="?page=ss_allowrequests"><?php echo __('Allow Requests',SFS_TXTDOMAIN); ?></a> :
<?php echo __('Displays users who were denied and filled out the form requesting access to your site.',SFS_TXTDOMAIN);?></li>
        <li><a href="?page=ss_webservices_settings"><?php echo __('Web Services',SFS_TXTDOMAIN); ?></a> :
<?php echo __('This is where you enter the API keys for StopForumSpam.com and other web checking services. You don\'t need to have these set for the plugin to work, but if you do, you will have better protection and the ability to report spam.',SFS_TXTDOMAIN);?></li>
        <li><a href="?page=ss_cache"><?php echo __('Cache',SFS_TXTDOMAIN); ?></a> :
<?php echo __('Shows the cache of recently detected events.',SFS_TXTDOMAIN);?></li>
        <li><a href="?page=ss_reports"><?php echo __('Log Report',SFS_TXTDOMAIN); ?></a> :
<?php echo __('Shows details of the most recent events detected by Stop Spammers.',SFS_TXTDOMAIN);?></li>
        <li><a href="?page=ss_diagnostics"><?php echo __('Diagnostics',SFS_TXTDOMAIN); ?></a> :
<?php echo __('You can use this to test an IP, email, or comment against all of the options. This can tell you more about why an IP address might fail. It will also show you any options that might crash the plugin on your site due to system settings.',SFS_TXTDOMAIN);?></li>
    </ul>
    <h2><?php echo __('Beta Options',SFS_TXTDOMAIN); ?></h2>
    <span class="notice notice-warning" style="display:block">
        <p><?php echo __('This feature is to be considered experimental. Use with caution and at your own risk.',SFS_TXTDOMAIN); ?></p>
    </span>
    <ul>
        <li><a href="?page=ss_option_maint"><?php echo __('DB Cleanup',SFS_TXTDOMAIN); ?></a>: <?php echo __('Delete leftover options from deleted plugins or anything that appears suspicious.',SFS_TXTDOMAIN); ?></li>
        <li><a href="?page=ss_threat_scan"><?php echo __('Threat Scan',SFS_TXTDOMAIN); ?></a>: <?php echo __('A simple scan to find possibly malicious code.',SFS_TXTDOMAIN); ?></li>
    </ul>
</div>
<?php

