<?php
if (!defined('ABSPATH')) exit;

/*
	adds IP to Deny List and to badCache
		and remove (if exists) from goodCache
		and remove (if exists) from whiteList
*/

class ss_addtodenylist {

	public function process( $ip, &$stats = array(), &$options = array(), &$post = array() ) {


		$UpdateStatsNeed = false;
		$UpdateOptNeed	 = false;

		if(!isset($options['wlist']))	$options['wlist'] = [];
		if(!isset($options['blist']))	$options['blist'] = [];


		if ( !in_array( $ip, $options['blist']) ) {
			$options['blist'][]	= $ip;
			$UpdateOptNeed		= true;
		}

		if ( in_array( $ip, $options['wlist']) ) {
			unset($options['wlist'][array_search($ip,$options['wlist'])] );
			$UpdateOptNeed		= true;
		}

/*
		if ( !array_key_exists( $ip, $stats['badips'] ) ) {
			$stats['badips'][$ip] = date( 'Y/m/d H:i:s', time() + ( get_option( 'gmt_offset' ) * 3600 ) );
			$UpdateStatsNeed = true;
		}
*/

		
		if ( array_key_exists( $ip, $stats['goodips'] ) ) {
			unset( $stats['goodips'][$ip] );
			$UpdateStatsNeed = true;
		}

		// update if need | обновить, если было изменение
		if($UpdateOptNeed)	ss_set_options($options);
		if($UpdateStatsNeed)	ss_set_stats($stats);

		return false;
	}
}

