<?php
if ( ! defined( 'ABSPATH' ) ) die;


class ss_get_gcache {

	public function process( $ip, &$stats = array(), &$options = array(), &$post = array() ) {

// gets the innerhtml for cache - same as get gcache except for names
		$goodips	= $stats['goodips'];
		$cachedel	= 'delete_gcache';
		$container	= 'goodips';
		$ajaxurl	= admin_url( 'admin-ajax.php' );

		$img_tpl	= '<div class="iconset-sfs ico-%s"></div>';	// tpl for all img in line
		$trash_img	= sprintf($img_tpl,'trash');
		$whois_img	= sprintf($img_tpl,'web-whois');
		$deny_img	= sprintf($img_tpl,'shield-minus');
		$allow_img	= sprintf($img_tpl,'shield-plus');
		$web_sfs_img	= sprintf($img_tpl,'web-stopforumspam');
		$web_hpot_img	= sprintf($img_tpl,'web-honeypot');
		$web_abuseip_img= sprintf($img_tpl,'web-abuseip');
		$web_blocklistde_img= sprintf($img_tpl,'web-blocklistde');
		$web_cleantalk_img  = sprintf($img_tpl,'web-cleantalk');

		$whois_url = '<a title="'.__('Look Up WHOIS',SFS_TXTDOMAIN).'" target="_stopspam"'
				.' href="https://lacnic.net/cgi-bin/lacnic/whois?lg=EN&query=%s">'
				.$whois_img
				.'</a>'."\r\n";

		$honeypot_url	= '<a title="Check project HoneyPot" target="_stopspam"'
					.' href="https://www.projecthoneypot.org/ip_%s">'.$web_hpot_img.'</a>';
		$abuseipdb_url	= '<a title="Check AbuseIp" target="_stopspam" '
					.'href="https://www.abuseipdb.com/check/%s">'.$web_abuseip_img.'</a>';
		$blocklistde_url= '<a title="Check Blocklistde" target="_stopspam" '
					.'href="https://www.blocklist.de/en/view.html?ip=%s">'.$web_blocklistde_img.'</a>';
		$cleantalk_url	= '<a title="Check CleanTalk" target="_stopspam" '
					.'href="https://cleantalk.org/blacklists/%s">'.$web_cleantalk_img.'</a>';

		$show	= '';
		foreach ( $goodips as $key => $value ) {

			$ip	= $key;		//!

			$show	.= '<div class="ss-cache-row">';
			// delete IP from cache 
			$alt_t	= sprintf(__('Delete %s from Cache',SFS_TXTDOMAIN),$key);
			$show	.= sprintf('<a href="" onclick="%s" title="%s" alt="%s">%s</a>'
			           	, "sfs_ajax_process('$key','$container','$cachedel','$ajaxurl');return false;"	//onClick
					, $alt_t
					, $alt_t
					, $trash_img
					);
                        $show	.= '&nbsp;';


			if(!empty($options['blist']) && !in_array($ip,$options['blist'])){ //[jetsam]: ONLY NOT EXISTS IN BLIST!
				// add IP to Deny List 
				$alt_t	= sprintf(__('Add %s to Deny List',SFS_TXTDOMAIN),$key);
				$show	.= sprintf('<a href="" onclick="%s" title="%s" alt="%s">%s</a>'
			           	, "sfs_ajax_process('$key','$container','add_black','$ajaxurl');return false;"	//onClick
					, $alt_t
					, $alt_t
					, $deny_img
					);
                	        $show	.= '&nbsp;';
			}

			if(!empty($options['wlist']) && !in_array($ip,$options['wlist'])){ //[jetsam]: ONLY NOT EXISTS IN WLIST!
				// add IP to Allow List
				$alt_t	= sprintf(__('Add %s to Allow List',SFS_TXTDOMAIN),$key);
				$show	.= sprintf('<a href="" onclick="%s" title="%s" alt="%s">%s</a>'
			           	, "sfs_	ajax_process('$key','$container','add_white','$ajaxurl');return false;"	//onClick
					, $alt_t
					, $alt_t
					, $allow_img
					);
                        	$show	.= '&nbsp;';
			}


			if(filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4|FILTER_FLAG_IPV6
								|FILTER_FLAG_NO_RES_RANGE | FILTER_FLAG_NO_PRIV_RANGE )){
                        	$show	.= sprintf($whois_url,$ip);
	                        $show	.= '&nbsp;';

				// get info about IP from sfs
				$alt_t	= sprintf(__('Search %s in StopForumSpam.com',SFS_TXTDOMAIN),$ip);
				$show	.= "<a href=\"https://www.stopforumspam.com/search?q=$ip\" title=\"$alt_t\""
						." target=\"_stopspam\">$web_sfs_img</a>";
                        	$show	.= '&nbsp;';

				$show	.= sprintf($honeypot_url,$ip);
        	                $show	.= '&nbsp;';
				$show	.= sprintf($abuseipdb_url,$ip);
                	        $show	.= '&nbsp;';
				$show	.= sprintf($blocklistde_url,$ip);
        	                $show	.= '&nbsp;';
				$show	.= sprintf($cleantalk_url,$ip);
                        	$show	.= '&nbsp;';

			}

			// date & ip
			$show	.= "<strong>$ip</strong>";
			$show	.= " <small>$value</small>&nbsp;";
			$show	.= '</div>';
			$show	.= '<div class="sfs-empty"></div>';

		}

		return $show;
	}
}

?>