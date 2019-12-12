<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class ss_addto_gcache {
	public function process( $ip, &$stats = array(), &$options = array(), &$post = array() ) {

		while ( count( $stats['goodips'] ) > $ss_sp_good ) {
			array_shift( $stats['goodips'] );
		}

		$nowtimeout     = date( 'Y/m/d H:i:s', time() - ( 4 * 3600 ) + ( get_option( 'gmt_offset' ) * 3600 ) );
		foreach ( $stats['goodips'] as $key => $data ) {
			if ( $data < $nowtimeout ) {
				unset( $stats['goodips'][$key] );
			}
		}

// if we add to Good Cache we need to delete from Bad Cache
		if ( array_key_exists( $ip, $stats['badips'] ) ) {
			unset( $stats['badips'][$ip] );
		}

		$stats['goodips'][$ip] = date( 'Y/m/d H:i:s', time() + ( get_option( 'gmt_offset' ) * 3600 ) );

		ss_set_stats( $stats );
		return $stats['goodips'];
	}
}

