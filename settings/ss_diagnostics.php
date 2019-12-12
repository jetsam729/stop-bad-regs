<?php
/* translate: full */

if ( ! defined( 'ABSPATH' ) ) die;
if ( ! current_user_can( 'manage_options' ) ) 	die( __('Access Denied',SFS_TXTDOMAIN) );

$stats   = ss_get_stats();
$options = ss_get_options();

ss_fix_post_vars();
$now = date( 'Y/m/d H:i:s', time() + ( get_option( 'gmt_offset' ) * 3600 ) );

$ip	 = ( array_key_exists('ip',$_POST)?$_POST['ip']:ss_get_ip());
$hip	 = ( array_key_exists( 'SERVER_ADDR', $_SERVER )?$_SERVER["SERVER_ADDR"]:'unknown');
$email   = ( array_key_exists('email',$_POST)?$_POST['email']:'');
$author  = ( array_key_exists('author',$_POST)?$_POST['author']:'');
$subject = ( array_key_exists('subject',$_POST)?$_POST['subject']:'');
$body    = ( array_key_exists('body',$_POST)?$body = $_POST['body']:'');


$nonce = wp_create_nonce( 'ss_stopspam_update' );
?>
<div id="ss-plugin" class="wrap ss-set-alt">
    <h1><?php echo __('Stop Spammers — Diagnostics',SFS_TXTDOMAIN);?></h1>
    <p><?php echo __('This allows you to test the plugin against an IP address.',SFS_TXTDOMAIN);?></p>
    <form method="post" action="">
        <input type="hidden" name="action" value="update"/>
        <input type="hidden" name="ss_stop_spammers_control" value="<?php echo $nonce; ?>"/>
        <fieldset>
		<legend>
		<span style="font-weight:bold;font-size:1.2em;">
		<?php echo __('Option Testing',SFS_TXTDOMAIN);?>
		</span>
		</legend>
<?php echo __('IP Address',SFS_TXTDOMAIN);?>:<br /><input name="ip" type="text" value="<?php echo $ip; ?>">
(<?php echo __('Your server address is',SFS_TXTDOMAIN);?> <?php echo $hip; ?>)
<br /><br />
<?php echo __('Email',SFS_TXTDOMAIN);?>:<br /><input name="email" type="text" value="<?php echo $email; ?>"><br /><br />
<?php echo __('Author/User',SFS_TXTDOMAIN);?>:<br /><input name="author" type="text" value="<?php echo $author; ?>"><br /><br />
<?php echo __('Subject',SFS_TXTDOMAIN);?> :<br /><input name="subject" type="text" value="<?php echo $subject; ?>"><br /><br />
<?php echo __('Comment',SFS_TXTDOMAIN);?> :<br /><textarea name="body"><?php echo $body; ?></textarea><br />
            <div style="width:50%;float:left">
                <p class="submit"><input name="testopt" class="button-primary"
			value="<?php echo __('Test Options',SFS_TXTDOMAIN);?>" type="submit"/></p>
            </div>
            <div style="width:50%;float:right">
                <p class="submit"><input name="testcountry" class="button-primary"
			value="<?php echo __('Test Countries',SFS_TXTDOMAIN);?>" type="submit"/></p>
            </div>
            <br style="clear:both"/>
			<?php
			$nonce = '';
			if ( array_key_exists( 'ss_stop_spammers_control', $_POST ) ) {
				$nonce = $_POST['ss_stop_spammers_control'];
			}
			if ( ! empty( $nonce ) && wp_verify_nonce( $nonce, 'ss_stopspam_update' ) ) {
				$post = get_post_variables();
				if ( array_key_exists( 'testopt', $_POST ) ) {
// do the test
					$optionlist = array(
						'chkaws',
						'chkcloudflare',
						'chkgcache',
						'chkgenallowlist',
						'chkgoogle',
						'chkmiscallowlist',
						'chkpaypal',
						'chkscripts',
						'chkvalidip',
						'chkwlem',
						'chkwluserid',
						'chkwlist',
						'chkform',
						'chkyahoomerchant'
					);
					$m1         = memory_get_usage( true );
					$m2         = memory_get_peak_usage( true );

					echo '<br />'.sprintf(__('Memory used, peak: %s, %s',SFS_TXTDOMAIN)
								,$m1, $m2).'<br />';
					echo '<ul>'.__('Allow Checks.',SFS_TXTDOMAIN).'<br />';
					foreach ( $optionlist as $chk ) {
						$ansa = be_load( $chk, $ip, $stats, $options, $post );
						if ( empty( $ansa ) ) {
							$ansa = '<span class="green">OK</span>';
						}else	$ansa = "<strong>$ansa</strong>";
						echo "$chk : $ansa<br />";
					}
					echo "</ul>";
					$optionlist = array(
						'chk404',
						'chkaccept',
						'chkadmin',
						'chkadminlog',
						'chkagent',
						'chkamazon',
						'chkbbcode',
						'chkbcache',
						'chkblem',
						'chkbluserid',
						'chkblip',
						'chkbotscout',
						'chkdisp',
						'chkdnsbl',
						'chkexploits',
						'chkgooglesafe',
						'chkhoney',
						'chkhosting',
						'chkinvalidip',
						'chklong',
						'chkshort',
						'chkreferer',
						'chksession',
						'chksfs',
						'chkspamwords',
						'chktld',
						'chkubiquity',
						'chkmulti'
					);
					$m1         = memory_get_usage( true );
					$m2         = memory_get_peak_usage( true );
					echo '<br />'.sprintf(__('Memory used, peak: %s, %s',SFS_TXTDOMAIN),$m1,$m2).'<br />';
					echo '<ul>'.__('Deny Checks',SFS_TXTDOMAIN).'<br />';
					foreach ( $optionlist as $chk ) {
						$ansa = be_load( $chk, $ip, $stats, $options, $post );
						if ( empty( $ansa ) ) {
							$ansa = '<span class="green">OK</span>';
						}else	$ansa = "<strong>$ansa</strong>";
						echo "$chk : $ansa<br />";
					}
					echo "</ul>";
					$optionlist = array();
					$a1         = apply_filters( 'ss_addons_allow', $optionlist );
					$a3         = apply_filters( 'ss_addons_deny', $optionlist );
					$a5         = apply_filters( 'ss_addons_get', $optionlist );
					$optionlist = array_merge( $a1, $a3, $a5 );
					if ( ! empty( $optionlist ) ) {
						echo '<ul>'.__('Add-on Checks',SFS_TXTDOMAIN).'<br />';
						foreach ( $optionlist as $chk ) {
							$ansa = be_load( $chk, $ip, $stats, $options, $post );
							if ( empty( $ansa ) ) {
								$ansa = '<span class="green">OK</span>';
							}else	$ansa = "<strong>$ansa</strong>";
							$nm = $chk[1];
							echo "$nm : $ansa<br />";
						}
						echo "</ul>";
					}
					$m1 = memory_get_usage( true );
					$m2 = memory_get_peak_usage( true );
					echo '<br />'.sprintf(__('Memory used, peak: %s, %s',SFS_TXTDOMAIN),$m1,$m2).'<br />';

				}
				if ( array_key_exists( 'testcountry', $_POST ) ) {
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
// KE - Kenya
// chkMA missing
// SC - Seychelles
					$m1 = memory_get_usage( true );
					$m2 = memory_get_peak_usage( true );
					echo '<br />'.sprintf(__('Memory used, peak: %s, %s',SFS_TXTDOMAIN),$m1,$m2).'<br />';

					foreach ( $optionlist as $chk ) {
						$ansa = be_load( $chk, $ip, $stats, $options, $post );
						if ( empty( $ansa ) ) {
							$ansa = '<span class="green">OK</span>';
						}else $ansa = "<strong>$ansa</strong>";
						echo "$chk : $ansa<br />";
					}
					$m1 = memory_get_usage( true );
					$m2 = memory_get_peak_usage( true );
					echo '<br />'.sprintf(__('Memory used, peak: %s, %s',SFS_TXTDOMAIN),$m1,$m2).'<br />';
				}
			}
			?>
        </fieldset>
        <br />
        <div style="width:50%;float:left">
            <h2><?php echo __('Display All Options',SFS_TXTDOMAIN);?></h2>
            <p><?php echo __('You can dump all options here (useful for debugging)',SFS_TXTDOMAIN);?>: </p>
            <p class="submit"><input name="dumpoptions" class="button-primary" value="<?php echo __('Dump Options',SFS_TXTDOMAIN);?>" type="submit"/></p>
        </div>
        <div style="width:50%;float:right">
            <h2><?php echo __('Display All Stats',SFS_TXTDOMAIN);?></h2>
            <p><?php echo __('You can dump all stats here',SFS_TXTDOMAIN);?>: </p>
            <p class="submit"><input name="dumpstats" class="button-primary" value="<?php echo __('Dump Stats',SFS_TXTDOMAIN);?>" type="submit"/></p>
        </div>
        <br style="clear:both"/>
		<?php
		if ( array_key_exists( 'ss_stop_spammers_control', $_POST ) ) {
			$nonce = $_POST['ss_stop_spammers_control'];
		}
		if ( ! empty( $nonce ) && wp_verify_nonce( $nonce, 'ss_stopspam_update' ) ) {
			if ( array_key_exists( 'dumpoptions', $_POST ) ) {
				?>
                <pre>
<?php
echo "\r\n";
$options = ss_get_options();
foreach ( $options as $key => $val ) {
	if ( is_array( $val ) ) {
		$val = print_r( $val, true );
	}
	echo "<strong>&bull; $key</strong> = $val\r\n";
}
echo "\r\n";
?>
</pre>
				<?php
			}
		}
		?>
		<?php
		if ( array_key_exists( 'ss_stop_spammers_control', $_POST ) ) {
			$nonce = $_POST['ss_stop_spammers_control'];
		}
		if ( ! empty( $nonce ) && wp_verify_nonce( $nonce, 'ss_stopspam_update' ) ) {
			if ( array_key_exists( 'dumpstats', $_POST ) ) {
				?>
                <pre>
<?php
$stats = ss_get_stats();
echo "\r\n";
foreach ( $stats as $key => $val ) {
	if ( is_array( $val ) ) {
		$val = print_r( $val, true );
	}
	echo "<strong>&bull; $key</strong> = $val\r\n";
}
echo "\r\n";
?>
</pre>
				<?php
			}
		}
		?>
        <p>&nbsp;</p>
    </form>
	<?php
	// if there is a log file we can display it here
	$dfile = SS_PLUGIN_DATA . '.sfs_debug_output.txt';
	if ( file_exists( $dfile ) ) {
		if ( array_key_exists( 'ss_stop_spammers_control', $_POST ) ) {
			$nonce = $_POST['ss_stop_spammers_control'];
		}
		if ( ! empty( $nonce ) && wp_verify_nonce( $nonce, 'ss_stopspam_update' ) ) {
			if ( array_key_exists( 'killdebug', $_POST ) ) {
				$f = unlink( $dfile );
				echo '<p>'.__('Debug File deleted',SFS_TXTDOMAIN).'<p>';
			}
		}
	}
	if ( file_exists( $dfile ) ) {
// we have a file - we can view it or delete it
		$nonce = "";
		$to    = get_option( 'admin_email' );
		$f     = file_get_contents( $dfile );
		$ff    = wordwrap( $f, 70, "\r\n" );
		?>
		<?php
		if ( array_key_exists( 'ss_stop_spammers_control', $_POST ) ) {
			$nonce = $_POST['ss_stop_spammers_control'];
		}
		if ( ! empty( $nonce ) && wp_verify_nonce( $nonce, 'ss_stopspam_update' ) ) {
			if ( array_key_exists( 'showdebug', $_POST ) ) {
				echo '<p><strong>'.__('Debug Output',SFS_TXTDOMAIN).':</strong></p>'
					.'<pre>'
					.$f
					.'</pre><p><strong>'
					.__('end of file (if empty, there are no errors to display)',SFS_TXTDOMAIN)
					.'</p></strong>';
			}
		}
		$nonce = wp_create_nonce( 'ss_stopspam_update' );
		?>
        <div style="width:50%;float:left">
            <form method="post" action="">
                <input type="hidden" name="update_options" value="update"/>
                <input type="hidden" name="ss_stop_spammers_control" value="<?php echo $nonce; ?>"/>
                <p class="submit"><input class="button-primary" name="showdebug"
			value="<?php echo __('Show Debug File',SFS_TXTDOMAIN);?>" type="submit"/>
                </p>
            </form>
        </div>
        <div style="width:50%;float:right">
            <form method="post" action="">
                <input type="hidden" name="update_options" value="update"/>
                <input type="hidden" name="ss_stop_spammers_control" value="<?php echo $nonce; ?>"/>
                <p class="submit"><input class="button-primary" name="killdebug"
			value="<?php echo __('Delete Debug File',SFS_TXTDOMAIN);?>" type="submit"/></p>
            </form>
        </div>
        <br style="clear:both"/><br />
		<?php
	}
	$ini  = '';
	$pinf = true;
	$ini  = @ini_get( 'disable_functions' );
	if ( ! empty( $ini ) ) {
		$disabled = explode( ',', $ini );
		if ( is_array( $disabled ) && in_array( 'phpinfo', $disabled ) ) {
			$pinf = false;
		}
	}
	if ( $pinf ) {
		?>
        <a href="" onclick="document.getElementById('shpinf').style.display='block';return false;"
           class="button-primary"><?php echo __('Show PHP Info',SFS_TXTDOMAIN);?></a>
		<?php
		ob_start();
		phpinfo();
		preg_match( '%<style type="text/css">(.*?)</style>.*?(<body>.*</body>)%s', ob_get_clean(), $matches );
# $matches [1]; # Style information
# $matches [2]; # Body information
		echo "<div class='phpinfodisplay' id=\"shpinf\" style=\"display:none;\"><style type='text/css'>\n",
		join( "\n",
			array_map(
				function($i) {
					return ".phpinfodisplay " . preg_replace( "/,/", ",.phpinfodisplay ", $i );
				},
				preg_split( '/\n/', $matches[1] )
			)
		),
		"</style>\n",
		$matches[2],
		"\n</div>\n";
	}
	?>
</div>