<?php
/* translate: full */

if ( ! defined( 'ABSPATH' ) ) die;
if ( ! current_user_can( 'manage_options' ) ) die( __('Access Denied',SFS_TXTDOMAIN) );

ss_fix_post_vars();
$trash    = SS_PLUGIN_URL . 'images/trash.png';
$tdown    = SS_PLUGIN_URL . 'images/tdown.png';
$tup      = SS_PLUGIN_URL . 'images/tup.png';
$whois    = SS_PLUGIN_URL . 'images/whois.png';
$stophand = SS_PLUGIN_URL . 'images/stop.png';
$search   = SS_PLUGIN_URL . 'images/search.png';

$now      = date( 'Y/m/d H:i:s', time() + ( get_option( 'gmt_offset' ) * 3600 ) );
?>
<div id="ss-plugin" class="wrap ss-set-alt-reports">
    <h1><?php echo __('Stop Spammers — Log Report',SFS_TXTDOMAIN);?></h1>
	<?php

	$stats = ss_get_stats();
	extract( $stats );
	$options = ss_get_options();
	extract( $options );

	$nonce = (array_key_exists( 'ss_stop_spammers_control',$_POST)?$_POST['ss_stop_spammers_control']:'');
	$msg   = '';

	if ( wp_verify_nonce( $nonce, 'ss_stopspam_update' ) ) {
		if ( array_key_exists( 'ss_stop_clear_hist', $_POST ) ) {
			// clean out the history
			$hist             = array();
			$stats['hist']    = $hist;
			$spcount          = 0;
			$stats['spcount'] = $spcount;
			$spdate           = $now;
			$stats['spdate']  = $spdate;
			ss_set_stats( $stats );
			extract( $stats ); // extract again to get the new options
			$msg = '<div class="notice notice-success"><p>'.__('Activity Log Cleared',SFS_TXTDOMAIN).'</p></div>';
		}
		if ( array_key_exists( 'ss_stop_update_log_size', $_POST ) ) {

			if ( array_key_exists( 'ss_sp_hist', $_POST ) ) {
				$ss_sp_hist            = stripslashes( $_POST['ss_sp_hist'] );
				$options['ss_sp_hist'] = $ss_sp_hist;
				$msg	= '<div class="notice notice-success"><p>'.__('Options Updated',SFS_TXTDOMAIN).'</p></div>';

				ss_set_options( $options );
			}
		}
	}
	if (!empty( $msg )) echo $msg;

	$num_comm = wp_count_comments();
	$num      = number_format_i18n( $num_comm->spam );

	if ( $num_comm->spam > 0 && SS_MU != 'Y' ) {
		?>
	<p>
	<?php echo sprinf(__('There are %s spam comments waiting for you to report them.',SFS_TXTDOMAIN)
				,'<a href="edit-comments.php?comment_status=spam">'.$num.'</a>');?>
	</p>
		<?php
	}
	$num_comm = wp_count_comments();
	$num      = number_format_i18n( $num_comm->moderated );
	if ( $num_comm->moderated > 0 && SS_MU != 'Y' ) {
		?>
        <p>
	<?php echo sprinf(__('There are %s comments waiting to be moderated.',SFS_TXTDOMAIN)
				,'<a href="edit-comments.php?comment_status=moderated">'.$num.'</a>');?>
	</p>
		<?php
	}
	$nonce = wp_create_nonce( 'ss_stopspam_update' );
	?>
    <script>
        // setTimeout(function(){
        // window.location.reload(1);
        // }, 10000);
    </script>
    <form method="post" action="">
        <input type="hidden" name="ss_stop_spammers_control" value="<?php echo $nonce; ?>"/>
        <input type="hidden" name="ss_stop_update_log_size" value="true"/>
        <fieldset>
		<legend>
		<span style="font-weight:bold;font-size:1.2em;">
		<?php echo __('History Size',SFS_TXTDOMAIN);?>
		</span>
		</legend>
		<p class="submit"><input class="button-primary"
				value="<?php echo __('Update Log Size',SFS_TXTDOMAIN);?>" type="submit"/>
		</p>
		<?php echo __('Select the number of items to save in the History. Keep this small.',SFS_TXTDOMAIN);?>
		<br />
            <select name="ss_sp_hist">
                <option value="10" <?php echo ( $ss_sp_hist == '10'?'selected="true"':'')?> >10</option>
                <option value="25" <?php echo ( $ss_sp_hist == '25'?'selected="true"':'')?> >25</option>
                <option value="50" <?php echo ( $ss_sp_hist == '50'?'selected="true"':'')?> >50</option>
                <option value="75" <?php echo ( $ss_sp_hist == '75'?'selected="true"':'')?> >75</option>
                <option value="100" <?php echo ( $ss_sp_hist == '100'?'selected="true"':'')?> >100</option>
            </select>
	</fieldset>
    </form>
    <form method="post" action="" >
	<fieldset>
		<legend>
		<span style="font-weight:bold;font-size:1.2em;">
			<?php echo __('Clear Activity',SFS_TXTDOMAIN);?>
		</span>
		</legend>
			<input type="hidden" name="ss_stop_spammers_control" value="<?php echo $nonce; ?>"/>
			    <input type="hidden" name="ss_stop_clear_hist" value="true"/>
			    <p class="submit"><input class="button-primary"
					value="<?php echo __('Clear Recent Activity',SFS_TXTDOMAIN);?>" type="submit"/></p>
	</fieldset>
    </form>

	<?php
	if ( empty( $hist ) ) {
		echo '<p>'.__('Nothing in logs.',SFS_TXTDOMAIN).'</p>';
	} else {
		?>
	<br />
        <table style="width:100%;background-color:#eee" cellspacing="2">
            <tr style="background-color:ivory;text-align:center">
                <td><?php echo __('Date/Time',SFS_TXTDOMAIN);?></td>
                <td><?php echo __('Email',SFS_TXTDOMAIN);?></td>
                <td><?php echo __('IP',SFS_TXTDOMAIN);?></td>
                <td><?php echo __('Author, User/Pwd',SFS_TXTDOMAIN);?></td>
                <td><?php echo __('Script',SFS_TXTDOMAIN);?></td>
                <td><?php echo __('Reason',SFS_TXTDOMAIN);?></td>
		<?php	if ( function_exists( 'is_multisite' ) && is_multisite() ) {
				echo '<td>'.__('Blog',SFS_TXTDOMAIN).'</td>';
			}
		?>
            </tr>
			<?php
			// sort list by date descending
			krsort( $hist );
			foreach ( $hist as $key => $data ) {
// $hist[$now]=array($ip,$email,$author,$sname,'begin');
				$em = strip_tags( trim( $data[1] ) );
				$dt = strip_tags( $key );
				$ip = $data[0];
				$au = strip_tags( $data[2] );
				$id = strip_tags( $data[3] );
				if ( empty( $au ) ) $au = ' -- ';
				if ( empty( $em ) ) $em = ' -- ';

				$reason = $data[4];
				$blog   = 1;
				if ( count( $data ) > 5 ) {
					$blog = $data[5];
				}
				if ( empty( $blog ) ) {
					$blog = 1;
				}
				if ( empty( $reason ) ) {
					$reason = "passed";
				}
				$stopper     = "<a title=\"Check Stop Forum Spam (SFS)\" target=\"_stopspam\" href=\"https://www.stopforumspam.com/search.php?q=$ip\"><img src=\"$stophand\" height=\"16px\" /></a>";
				$honeysearch = "<a title=\"Check project HoneyPot\" target=\"_stopspam\" href=\"https://www.projecthoneypot.org/ip_$ip\"><img src=\"$search\" height=\"16px\" /></a>";
				$botsearch   = "<a title=\"Check BotScout\" target=\"_stopspam\" href=\"https://botscout.com/search.htm?stype=q&sterm=$ip\"><img src=\"$search\" height=\"16px\" /></a>";
				$who         = "<br /><a title=\"Look Up WHOIS\" target=\"_stopspam\" href=\"https://lacnic.net/cgi-bin/lacnic/whois?lg=EN&query=$ip\"><img src=\"$whois\" height=\"16px\" /></a>";
				echo "<tr style=\"background-color:white\">
<td>$dt</td>
<td>$em</td>
<td>$ip $who $stopper $honeysearch $botsearch";
				if ( stripos( $reason, 'passed' ) !== false && ( $id == '/' || strpos( $id, 'login' ) ) !== false || strpos( $id, 'register' ) !== false && ! in_array( $ip, $blist ) && ! in_array( $ip, $wlist ) ) {
					$ajaxurl = admin_url( 'admin-ajax.php' );
					echo "<a href=\"\" onclick=\"sfs_ajax_process( '$ip','log','add_black','$ajaxurl' );return false;\" title=\"Add to Deny List\" alt=\"Add to Deny List\" ><img src=\"$tdown\" height=\"16px\" /></a>";
					$options = get_option( 'ss_stop_sp_reg_options' );
					$apikey  = $options['apikey'];
					if ( ! empty( $apikey ) ) {
						$href    = "href=\"#\"";
						$onclick = "onclick=\"sfs_ajax_report_spam(this, 'registration', '$blog', '$ajaxurl', '$em', '$ip', '$au');return false;\"";
					}
					if ( ! empty( $em ) ) {
						echo "|";
						echo "<a title=\"Report to Stop Forum Spam (SFS)\" $href $onclick class='delete:the-comment-list:comment-$id::delete=1 delete vim-d vim-destructive'>Report to SFS</a>";
					}
				}
				echo "
</td>
<td>$au</td>
<td>$id</td>
<td>$reason</td>";
				if ( function_exists( 'is_multisite' ) && is_multisite() ) {
					$blogname  = get_blog_option( $blog, 'blogname' );
					$blogadmin = esc_url( get_admin_url( $blog ) );
					$blogadmin = trim( $blogadmin, '/' );
					echo "<td style=\"font-size:.9em;padding:2px\" align=\"center\">";
					echo "<a href=\"$blogadmin/edit-comments.php\">$blogname</a>";
					echo "</td>";
				}
				echo "</tr>";
			}
			?>
        </table>
		<?php
	}
	?>
</div>
<?php

function GetRowIp(&$rec){
	//
	$td_blog = '';
	if ( function_exists( 'is_multisite' ) && is_multisite() ) {
		$blogname	= get_blog_option( $blog, 'blogname' );
		$blogadmin	= esc_url( get_admin_url( $blog ) );
		$blogadmin	= trim( $blogadmin, '/' );
		$td_blog	=  "\r\n".'<td style="font-size:.9em;padding:2px;text-align:center">'
					. '<a href="'.$blogadmin.'/edit-comments.php">'.$blogname.'</a></td>'."\r\n";
	}

$ROW	= <<<ENDROW
<tr>
<td>$td_date</td>
<td>$td_em</td>
<td>$td_ip</td>
<td>$td_user</td>
<td>$td_URI</td>
<td>$td_reason</td>$td_blog
</tr>
ENDROW;
	
}