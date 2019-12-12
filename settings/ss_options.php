<?php
/* translated: full */

if ( ! defined( 'ABSPATH' ) ) die;
if ( ! current_user_can( 'manage_options' ) ) die( __('Access Denied',SFS_TXTDOMAIN) );

ss_fix_post_vars();
$now     = date( 'Y/m/d H:i:s', time() + ( get_option( 'gmt_offset' ) * 3600 ) );
$options = ss_get_options();
extract( $options );

$nonce =  (array_key_exists( 'ss_stop_spammers_control', $_POST )?$nonce = $_POST['ss_stop_spammers_control']:'' );

if ( ! empty( $nonce ) && wp_verify_nonce( $nonce, 'ss_stopspam_update' ) ) {
// update
// check box items - keep track of these
	$optionlist = array(
		'chkamazon',
		'addtoallowlist',
		'chkadmin',
		'chkaccept',
		'chkbbcode',
		'chkreferer',
		'chkdisp',
		'chklong',
		'chkshort',
		'chkmulti',
		'chksession',
		'chk404',
		'chkexploits',
		'chkadminlog',
		'chkhosting',
		'chkakismet',
		'filterregistrations',
		'chkubiquity',
		'chkform'
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
// countries
	$optionlist = array(
		'chkAD',
		'chkAE',
		'chkAF',
		'chkAL',
		'chkAM',
		'chkAR',
		'chkAT',
		'chkAU',
		'chkAX',
		'chkAZ',
		'chkBA',
		'chkBB',
		'chkBD',
		'chkBE',
		'chkBG',
		'chkBH',
		'chkBN',
		'chkBO',
		'chkBR',
		'chkBS',
		'chkBY',
		'chkBZ',
		'chkCA',
		'chkCD',
		'chkCH',
		'chkCL',
		'chkCN',
		'chkCO',
		'chkCR',
		'chkCU',
		'chkCW',
		'chkCY',
		'chkCZ',
		'chkDE',
		'chkDK',
		'chkDO',
		'chkDZ',
		'chkEC',
		'chkEE',
		'chkES',
		'chkEU',
		'chkFI',
		'chkFJ',
		'chkFR',
		'chkGB',
		'chkGE',
		'chkGF',
		'chkGI',
		'chkGP',
		'chkGR',
		'chkGT',
		'chkGU',
		'chkGY',
		'chkHK',
		'chkHN',
		'chkHR',
		'chkHT',
		'chkHU',
		'chkID',
		'chkIE',
		'chkIL',
		'chkIN',
		'chkIQ',
		'chkIR',
		'chkIS',
		'chkIT',
		'chkJM',
		'chkJO',
		'chkJP',
		'chkKE',
		'chkKG',
		'chkKH',
		'chkKR',
		'chkKW',
		'chkKY',
		'chkKZ',
		'chkLA',
		'chkLB',
		'chkLK',
		'chkLT',
		'chkLU',
		'chkLV',
		'chkMD',
		'chkME',
		'chkMK',
		'chkMM',
		'chkMN',
		'chkMO',
		'chkMP',
		'chkMQ',
		'chkMT',
		'chkMV',
		'chkMX',
		'chkMY',
		'chkNC',
		'chkNI',
		'chkNL',
		'chkNO',
		'chkNP',
		'chkNZ',
		'chkOM',
		'chkPA',
		'chkPE',
		'chkPG',
		'chkPH',
		'chkPK',
		'chkPL',
		'chkPR',
		'chkPS',
		'chkPT',
		'chkPW',
		'chkPY',
		'chkQA',
		'chkRO',
		'chkRS',
		'chkRU',
		'chkSA',
		'chkSC',
		'chkSE',
		'chkSG',
		'chkSI',
		'chkSK',
		'chkSV',
		'chkSX',
		'chkSY',
		'chkTH',
		'chkTJ',
		'chkTM',
		'chkTR',
		'chkTT',
		'chkTW',
		'chkUA',
		'chkUK',
		'chkUS',
		'chkUY',
		'chkUZ',
		'chkVC',
		'chkVE',
		'chkVN',
		'chkYE'
	);
	foreach ( $optionlist as $check ) {
		$v = 'N';
		if ( array_key_exists( $check, $_POST ) ) {
			$v = ($_POST[ $check ]=='Y'?'Y':'N');
		}
		$options[ $check ] = $v;
	}
// text options
	if ( array_key_exists( 'sesstime', $_POST ) ) {
		$sesstime            = stripslashes( $_POST['sesstime'] );
		$options['sesstime'] = $sesstime;
	}
	if ( array_key_exists( 'multitime', $_POST ) ) {
		$multitime            = stripslashes( $_POST['multitime'] );
		$options['multitime'] = $multitime;
	}
	if ( array_key_exists( 'multicnt', $_POST ) ) {
		$multicnt            = stripslashes( $_POST['multicnt'] );
		$options['multicnt'] = $multicnt;
	}
	ss_set_options( $options );
	extract( $options ); // extract again to get the new options
	$msg = '<div class="notice notice-success"><p>'.__('Options Updated',SFS_TXTDOMAIN).'</p></div>';

}
$nonce = wp_create_nonce( 'ss_stopspam_update' );
?>
<div id="ss-plugin" class="wrap ss-set-alt">
    <h1><?php echo __('Stop Spammers — Protection Options',SFS_TXTDOMAIN); ?></h1>
	<?php if ( ! empty( $msg ) ) echo "$msg"; ?>
    <form method="post" action="" name="ss">
        <input type="hidden" name="action" value="update"/>
        <input type="hidden" name="ss_stop_spammers_control" value="<?php echo $nonce; ?>"/>

        <fieldset>
            <legend>	<span style="font-weight:bold;font-size:1.2em;">
			<?php echo __('Only Use the Plugin for Standard WordPress Forms',SFS_TXTDOMAIN); ?>
			</span>
            </legend>
            <p><input name="chkform" type="checkbox" value="Y" <?php echo ( $chkform == 'Y'?'checked="checked" />':'/>');?>
		<?php echo __('Stop Spammers normally kicks off whenever someone fills out a form and presses submit.'
		.'<br />This means it checks all the forms on a website, not just comments and logins.'
		.'<br />This option will limit the plugin to wp-comments-post.php and wp-login.php.'
		.'<br />WooCommerce and other plugins will not be checked.'
		.'<br />Use this option if you are running an ecommerce site or a specialized site that has forms that are blocked by Stop Spammers.'
		.'<br />For the most protection, this option is off by default (recommended).'
			,SFS_TXTDOMAIN); ?>
	</p>
        </fieldset>

        <fieldset>
		<legend>
		<span style="font-weight:bold;font-size:1.5em;"><?php echo __('Prevent Lockouts',SFS_TXTDOMAIN); ?></span>
		</legend>
		<p>
		<?php echo __('This plugin aggressively checks for spammers and is unforgiving to the point'
			.'<br />where even you may get locked out of your own website when you log off and try to log back in.'
			.'<br />There are two options which help prevent this, but these options can make it easier for a spammer to hack your site.'
			.'<br />When you are confident that the plugin is working you can uncheck these boxes.'
			,SFS_TXTDOMAIN);
		?>
		</p>

            <fieldset>
                <legend>
		<span style="font-weight:bold;font-size:1.2em;">
			<?php echo __('Automatically Add Admins to Allow List',SFS_TXTDOMAIN); ?>
		</span>
                </legend>
                <p><input name="addtoallowlist" type="checkbox" value="Y" <?php echo ($addtoallowlist=='Y'?'checked="checked" />':'/>');?>
                <?php	echo __('Whenever an administrative user logs in, the IP address is added to the Allow List.'
				.'<br />This means that you can\'t be locked out unless your IP address changes or you log in from a different location.'
				.'<br />As soon as a login is successful then the IP is white-listed to prevent future problems.'
				.'<br />Disable this if you think that you will never be locked out.'
				,SFS_TXTDOMAIN); 
		?>
		</p>
            </fieldset>

            <br />

            <fieldset>
                <legend><span style="font-weight:bold;font-size:1.2em;">
			<?php echo __('Check Credentials on All Login Attempts',SFS_TXTDOMAIN);?>
		</span>
                </legend>
                <p><input name="chkadminlog" type="checkbox" value="Y" <?php echo ($chkadminlog=='Y'?'checked="checked"':'');?> />
			<?php echo __('Normally the plugin checks for spammers before WordPress can try to log in a user.'
			.'<br />If you check this box, every attempt to log in will be tested for a valid user.'
			.'<br />This may allow a hacker to guess your user ID and password by making thousands of attempts to login.'
			.'<br />This is turned on initially to prevent you from being locked out of your own blog,'
			.'<br />but should be unchecked after you verify that the plugin does not think you are a spammer.'
		,SFS_TXTDOMAIN);?>
		</p>
            </fieldset>

        </fieldset>

        <br />

        <fieldset>
            <legend><span style="font-weight:bold;font-size:1.5em;">
			<?php echo __('Validate Requests',SFS_TXTDOMAIN);?>
		    </span>
            </legend>
            <p>
		<?php echo __('Spam bots do not always follow rules. They don\'t provide the proper request headers or are too quick.'
               		.'<br />These items can be quickly checked. These rules are the most economical way of detecting spammers.'
			,SFS_TXTDOMAIN);?>
	    </p>
            <br />
            <fieldset>
                <legend><span style="font-weight:bold;font-size:1.2em;">
			<?php echo __('Block Spam Missing the HTTP_ACCEPT Header',SFS_TXTDOMAIN);?>
			</span>
                </legend>
                <p><input name="chkaccept" type="checkbox" value="Y" <?php echo ($chkaccept=='Y'?'checked="checked"':'');?> />
			<?php echo __('Blocks users who have a missing or incomplete HTTP_ACCEPT header. All browsers provide this header.'
			.'<br />If a hit on your site is missing the HTTP_ACCEPT header it is because a poorly written bot is trying access your site.'
			,SFS_TXTDOMAIN);?>
		</p>
            </fieldset>

            <br />

            <fieldset>
                <legend><span style="font-weight:bold;font-size:1.2em;">
			<?php echo __('Deny Disposable Email Addresses',SFS_TXTDOMAIN);?>
			</span>
		</legend>
                <p><input name="chkdisp" type="checkbox" value="Y" <?php echo ($chkdisp=='Y'?'checked="checked"':'');?> />
			<?php echo __('Spammers who want to hide their true identities use disposable email addresses.'
			.'<br />You can get these from a number of sites. The spammer doesn\'t have to register. He just picks up any mail anonymously.'
			.'<br />Legitimate users use their real email address. It is very likely that anyone using a disposable email address is a spammer.'
			,SFS_TXTDOMAIN);?>
		</p>
            </fieldset>

            <br />

            <fieldset>
                <legend><span style="font-weight:bold;font-size:1.2em;">
			<?php echo __('Check for Long Emails, Author Name, or Password',SFS_TXTDOMAIN);?>
			</span>
                </legend>
                <p><input name="chklong" type="checkbox" value="Y" <?php echo ($chklong=='Y'?'checked="checked"':'');?> />
                    <?php echo __('Spammers can\'t resist using very long names and emails. This rejects these if they are over 64 characters in length.',SFS_TXTDOMAIN);?>
		</p>
            </fieldset>

            <fieldset>
                <legend><span style="font-weight:bold;font-size:1.2em;">
			<?php echo __('Check for Short Emails or Author Name',SFS_TXTDOMAIN);?>
			</span>
                </legend>
                <p><input name="chkshort" type="checkbox" value="Y" <?php echo ($chkshort=='Y'?'checked="checked"':'');?> />
                    <?php echo __('Spammers sometimes use blank usernames or author names.'
				.'<br />If you are having trouble with a plugin or theme not using the correct fields with rejects for short usernames, then uncheck this box.'
				,SFS_TXTDOMAIN);?>
		</p>
            </fieldset>

            <br />

            <fieldset>
                <legend><span style="font-weight:bold;font-size:1.2em;">
				<?php echo __('Check for BBCodes',SFS_TXTDOMAIN);?>
			</span>
		</legend>
                <p><input name="chkbbcode" type="checkbox" value="Y" <?php echo ($chkbbcode=='Y'?'checked="checked"':'');?> />
                    <?php echo __('BBCodes are codes like [url] that spammers like to place in comments.'
			.'<br />WordPress does not support BBCodes without a plugin.'
                    	.'<br />If you have a BBCode plugin then disable this. This will mark any comment that has BBCodes as spam.'
			,SFS_TXTDOMAIN);?>
                </p>
            </fieldset>

            <br />

            <fieldset>
                <legend><span style="font-weight:bold;font-size:1.2em;">
			<?php echo __('Check for Quick Responses',SFS_TXTDOMAIN);?>
			</span>
		</legend>
                <p><input name="chksession" type="checkbox" value="Y" <?php echo ($chksession=='Y'?'checked="checked"':'');?> />
		<?php echo __('<em>(disabled if caching is active)</em> The plugin will drop a cookie with the current time in it.'
			.'<br />When the user enters a comment or tries to log into the system, the time is checked.'
			.'<br />If the user responds too fast, he is a spammer. If cookies are not supported, this is disabled.'
			.'<br />Use the timeout value below to control the speed (stops the most spammers of all the methods listed here).'
			,SFS_TXTDOMAIN);?>
			<br /><?php echo __('Response Timeout Value',SFS_TXTDOMAIN);?> : 
			<input name="sesstime" type="text" value="<?php echo $sesstime;?>" size="2" style="width:70px;" />
                    	<?php echo __('<br />This is the time used to determine if a spammer has filled out a form too quickly.'
				.'<br />Humans take more than 10 seconds, at least, to fill out forms. The default is 4 seconds.'
				.'<br />If a user takes 4 seconds or less to fill out a form they are not human and are denied.'
				.'<br />Users who use automatic passwords may show up as false positives, so keep this low.'
				,SFS_TXTDOMAIN);?>
			</p>
            </fieldset>

            <br />

            <fieldset>
                <legend><span style="font-weight:bold;font-size:1.2em;">
			<?php echo __('Deny 404 Exploit Probing',SFS_TXTDOMAIN);?>
			</span>
		</legend>
                <p><input name="chk404" type="checkbox" value="Y" <?php echo ($chk404=='Y'?'checked="checked"':'');?> />
			<?php echo __('Bots often search your site for exploitable files.'
			.'<br />If there is a match to a known exploit URL,this will automatically add the IP address to the Deny List.'
			,SFS_TXTDOMAIN);?>
		</p>
            </fieldset>

            <br />

            <fieldset>
                <legend><span style="font-weight:bold;font-size:1.2em;">
			<?php echo __('Deny IPs Detected by Akismet',SFS_TXTDOMAIN);?>
			</span>
		</legend>
                <p><input name="chkakismet" type="checkbox" value="Y" <?php echo ($chkakismet=='Y'?'checked="checked"':'');?> />
			<?php echo __('Akismet does a good job detecting spam. If Akismet catches a spammer, then the IP address should be added to the bad IP cache.'
			.'<br />Akismet will continue to block comment spam, but if there is a login or registration attempt from the same IP, it will be blocked.'
			,SFS_TXTDOMAIN);?>
		</p>
            </fieldset>

            <br />
            <fieldset>
                <legend><span style="font-weight:bold;font-size:1.2em;">
			<?php echo __('Check for Exploits',SFS_TXTDOMAIN);?>
			</span>
		</legend>
                <p><input name="chkexploits" type="checkbox" value="Y" <?php echo ($chkexploits=='Y'?'checked="checked"':'');?> />
			<?php echo __('This checks for the PHP eval() function and typical SQL injection strings in comments and login attempts.'
			.'<br />It also checks for JavaScript that may potentially be used for cross domain exploits.'
			,SFS_TXTDOMAIN);?>
			</p>
            </fieldset>

            <br />

            <fieldset>
                <legend><span style="font-weight:bold;font-size:1.2em;">
			<?php echo __('Deny Login Attempts Using \'admin\' in Username',SFS_TXTDOMAIN);?>
			</span>
                </legend>
                <p><input name="chkadmin" type="checkbox" value="Y" <?php echo ($chkadmin=='Y'?'checked="checked"':'');?> />
			<?php echo __('When a spammer starts hitting the login page with \'admin\' anywhere in the login ID'
			.'<br />and there is no matching user, then it is a spammer trying to figure out your password.'
			.'<br />Deny List immediately. This only works if you do not have any users with \'admin\' in their username.'
			.'<br />It is dangerous to have a username \'admin\'. Sites get thousands of hits from bots trying to guess the admin password.'
			.'<br />This has the side effect of preventing users from registering a username with the word admin in it.'
			.'<br />Users cannot register with \'admin2\' or \'superadmin\' or \'Administrator.\''
			,SFS_TXTDOMAIN);?>
			</p>
            </fieldset>

            <br />

            <fieldset>
		<legend>
			<span style="font-weight:bold;font-size:1.2em;">
			<?php echo __('Check Against List of Ubiquity-Nobis and Other Spam Server IPs',SFS_TXTDOMAIN);?>
			</span>
		</legend>
                <p><input name="chkubiquity" type="checkbox" value="Y" <?php echo ($chkubiquity=='Y'?'checked="checked" />':'/>');?>
			<?php echo __('A list of hosting companies who tolerate spammers.'
			.'<br />They are the source of many comment spam and login attempts. This blocks many of them.'
			,SFS_TXTDOMAIN);?>
		</p>
            </fieldset>

            <br />

            <fieldset>
                <legend><span style="font-weight:bold;font-size:1.2em;">
			<?php echo __('Check for Major Hosting Companies and Cloud Services',SFS_TXTDOMAIN);?>
			</span>
                </legend>
                <p><input name="chkhosting" type="checkbox" value="Y" <?php echo ($chkhosting=='Y'?'checked="checked" />':'/>');?>
			<?php echo __('Your users should come from ISPs only. If a request comes from a web host such as Softlayer, Rackspace, or Amazon AWS,'
			.'<br />it is likely that the the user is a spammer who is running some spam software to attack your site.'
			,SFS_TXTDOMAIN);?>
		</p>
            </fieldset>
            <br />
            <fieldset>
                <legend><span style="font-weight:bold;font-size:1.2em;">
			<?php echo __('Check for Many Hits in a Short Time',SFS_TXTDOMAIN);?>
			</span>
                </legend>
                <p><input name="chkmulti" type="checkbox" value="Y" <?php echo ($chkmulti=='Y'?'checked="checked"':'');?> />
                    <?php echo __('Deny access when there are',SFS_TXTDOMAIN);?>
                    <select name="multicnt">
                        <option val="4" <?php echo ($multicnt <= 4 ?'selected="selected">':'>');?>4</option>
                        <option val="5" <?php echo ($multicnt == 5 ?'selected="selected">':'>');?>5</option>
                        <option val="6" <?php echo ($multicnt == 6 ?'selected="selected">':'>');?>6</option>
                        <option val="7" <?php echo ($multicnt == 7 ?'selected="selected">':'>');?>7</option>
                        <option val="8" <?php echo ($multicnt == 8 ?'selected="selected">':'>');?>8</option>
                        <option val="9" <?php echo ($multicnt == 9 ?'selected="selected">':'>');?>9</option>
                        <option val="10" <?php echo ($multicnt == 10 ?'selected="selected">':'>');?>10</option>
                    </select>
                    <?php echo __('comments or logins in less than',SFS_TXTDOMAIN);?>
                    <select name="multitime">
                        <option val="1" <?php echo ($multitime <= 1 ?'selected="selected">':'>');?>1</option>
                        <option val="2" <?php echo ($multitime == 2 ?'selected="selected">':'>');?>2</option>
                        <option val="3" <?php echo ($multitime == 3 ?'selected="selected">':'>');?>3</option>
                        <option val="4" <?php echo ($multitime == 4 ?'selected="selected">':'>');?>4</option>
                        <option val="5" <?php echo ($multitime == 5 ?'selected="selected">':'>');?>5</option>
                        <option val="6" <?php echo ($multitime == 6 ?'selected="selected">':'>');?>6</option>
                        <option val="7" <?php echo ($multitime == 7 ?'selected="selected">':'>');?>7</option>
                        <option val="8" <?php echo ($multitime == 8 ?'selected="selected">':'>');?>8</option>
                        <option val="9" <?php echo ($multitime == 9 ?'selected="selected">':'>');?>9</option>
                        <option val="10" <?php echo ($multitime >= 10 ?'selected="selected">':'>');?>10</option>
                        </option>
                    </select>
                    <?php echo __('minutes',SFS_TXTDOMAIN);?>.
			<br />
			<?php echo sprintf(__('Spammers hit your site over and over again.'
			.'<br />If you get more than %s hit(s) in %s minute(s), the spammer will be stopped,'
			.'<br />added to the bad cache, and shown the challenge page.',SFS_TXTDOMAIN)
				,$multicnt,$multitime);
			?>
                </p>
            </fieldset>


            <br />
            <fieldset>
                <legend><span style="font-weight:bold;font-size:1.2em;">
                    <?php echo __('Check for Amazon Cloud',SFS_TXTDOMAIN);?>.
			</span>
		</legend>
                <p><input name="chkamazon" type="checkbox" value="Y" <?php echo ($chkamazon=='Y'?'checked="checked"/>':'/>');?>
                    <?php echo __('You can block comments and logins from Amazon Cloud Servers using this setting.'
			.'<br >It may be that good services use Amazon Cloud servers so you may not want to use this.'
			.'<br >Be careful about blocking Amazon. Sometimes you get spam from  one of their servers,'
			.'<br >but they shut it down right away.'
			,SFS_TXTDOMAIN);?>.
		</p>
            </fieldset>

            <br />

            <fieldset>
                <legend><span style="font-weight:bold;font-size:1.2em;">
			<?php echo __('Filter Login Requests',SFS_TXTDOMAIN);?>
			</span>
		</legend>
                <p><input name="filterregistrations" type="checkbox" value="Y" <?php echo ($filterregistrations=='Y'?'checked="checked"':'');?> />
			<?php echo __('Some plugins and themes bypass the standard registration forms.'
				.'<br />If you check this, Stop Spammers will try to intercept the login in the WordPress user login module.'
				.'<br />This will cause some overhead, but gives Stop Spammers another shot at detecting spam.'
				.'<br />This is turned off by default because it could potentially be called at every page load.'
				,SFS_TXTDOMAIN);?>
		</p>
            </fieldset>

            <br />

            <fieldset>
                <legend><span style="font-weight:bold;font-size:1.2em;">
			<?php echo __('Block Countries',SFS_TXTDOMAIN);?>
			</span>
		</legend>
                <p><strong>This does not block the whole country. It only blocks spam sources in a country.</strong></p>
                <p>Blocking countries only blocks the known spam blocks from those countries.
		Blocking residential ISPs in countries where spammers are quickly shut down is avoided.
		<em><strong>Blocking the US will not block Cox, Verizon, AT&amp;T, etc. It will block
                            hosting companies that send out spam that are located in the US.</strong></em></p>
                Blocking RU will, however, block most of Russia, because Russian ISPs do not shut down zombie
                    computers in residential blocks.</p>
                If you block countries, make sure that you have set the Challenge to use a CAPTCHA screen so that
                    legitimate users
                    can get into your site even if blocked.</p>
                The biggest countries can put a strain on memory. US, Russia, India, Ukraine, Brazil, China, and
                    Indonesia (in that order)
                    are the sources of most spam, but they also take up to a half a meg of memory to load. This may slow
                    things a little
                    and in some cases might shut down your site. If you are using a free or low-budget host to run your site,
                    there could be a problem.</p>
                <p>Check all:&nbsp;<input type="checkbox" name="ss_set" value="1" onclick='var t=ss.ss_set.checked;
var els=(document.getElementById("CountryBlockList")).getElementsByTagName("INPUT");
for (index = 0; index < els.length; ++index){
	if (els[index].type=="checkbox"){
		els[index].checked=t;
	}
};' />
		Inverse all:&nbsp;<input type="checkbox" name="ss_inverse" value="1" onclick='
var els=(document.getElementById("CountryBlockList")).getElementsByTagName("INPUT");
for (index = 0; index < els.length; ++index){
	if (els[index].type=="checkbox"){
		els[index].checked=!els[index].checked;
	}
};' />
                </p>
		<div id="CountryBlockList">
<?
@require_once(SS_PLUGIN_FILE.'geoip2lite/geo_code2_to_name.php');

foreach($country_code2_to_name AS $code2=>$country_name){

		$var = "chk$code2";
		echo sprintf('<div class="stat-box" style="font-size:11px;"><input name="chk%s" type="checkbox" value="Y" %s/>%s%s</div>'
			,$code2
			,(!empty(${$var}) && ${$var}!='N'?'checked="checked"':'')
			,sprintf('<div class="flagset2 flag-%s"></div>',strtolower($code2))
			,$country_name
			)."\r\n";
}

?>
            </fieldset>
        </fieldset>
        <br />

        <p class="submit"><input class="button-primary" value="<?php echo __('Save Changes',SFS_TXTDOMAIN);?>" type="submit"/></p>

    </form>
</div>
<?php
//

