<?php
if ( ! defined( 'ABSPATH' ) ) die;


class ss_addtoallowlist {
	public function process( $ip, &$stats = array(), &$options = array(), &$post = array() ) {

		$StatsNeedUpdate = false;
		$OptNeedUpdate	 = false;

		if(!isset($options['wlist']))	$options['wlist'] = [];	// if corrupt
		if(!isset($options['blist']))	$options['blist'] = [];	// if corrupt

		if ( !in_array( $ip, $options['wlist']) ) {
			$options['wlist'][]	= $ip;
			$OptNeedUpdate		= true;
		}

		if ( in_array( $ip, $options['blist']) ) {
			unset($options['blist'][array_search($ip,$options['blist'])] );
			$OptNeedUpdate		= true;
		}

		if ( array_key_exists( $ip, $stats['badips'] ) ) {
			unset($stats['badips'][$ip]);
			$StatsNeedUpdate = true;
		}
/*
		
		if ( !array_key_exists( $ip, $stats['goodips'] ) ) {
			$stats['goodips'][$ip] = date( 'Y/m/d H:i:s', time() + ( get_option( 'gmt_offset' ) * 3600 ) );
			$StatsNeedUpdate = true;
		}
*/

		// update if need | обновить, если было изменение
		if($OptNeedUpdate)	ss_set_options($options);
		if($StatsNeedUpdate)	ss_set_stats($stats);

		return false;



	}
}

?>