<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class ss_addto_bcache {
	public function process( $ip, &$stats = array(), &$options = array(), &$post = array() ) {

		while ( count( $stats['badips'] ) > $ss_sp_cache ) {
			array_shift( $stats['badips'] );
		}

		$nowtimeout    = date( 'Y/m/d H:i:s', time() - ( 4 * 3600 ) + ( get_option( 'gmt_offset' ) * 3600 ) );
		foreach ( $stats['badips'] as $key => $date ) {
			if ( $date < $nowtimeout ) {
				unset( $stats['badips'][$key] );
			}
		}

// if we add to Bad Cache we need to delete from Good Cache
		if ( array_key_exists( $ip, $stats['goodips'] ) ) {
			unset( $stats['goodips'][$ip] );
		}

		$stats['badips'][$ip] = date( 'Y/m/d H:i:s', time() + ( get_option( 'gmt_offset' ) * 3600 ) );

		ss_set_stats( $stats );
		return $stats['badips'];
	}
}

