<?php
if ( ! defined( 'ABSPATH' ) ) DIE;


class ss_remove_gcache {
	public function process( $ip, &$stats = array(), &$options = array(), &$post = array() ) {

		$UpdateNeeded	= false;

		if(isset($stats['goodips'][$ip])){			// first, if ip in cache
			unset( $stats['goodips'][$ip] );
			$UpdateNeeded = true;
		}

		while ( count($stats['goodips']) > $ss_sp_good ) {	// if cache > max_cache|| $ss_sp_good rename to $ss_max_gcache
			array_shift( $stats['goodips'] );
			$UpdateNeeded = true;
		}

		if(isset($options['gcache_ttl'])){			// TimeToLive in Hours!
			$cache_ttl = $options['gcache_ttl']*3600;
		}else	$cache_ttl = 2 * 3600;				// defa goodcache:2h, defa badcache:12h! 


		if($cache_ttl){	// 0 - NO CLEAR OLD CACHE REC

			// clear old records if need
			$nowtimeout = date('Y/m/d H:i:s', time() - $cache_ttl  + (get_option('gmt_offset')*3600) );

			foreach ( $stats['goodips'] as $key => $data ) { 
				if ($data<$nowtimeout){
					unset($stats['goodips'][$key]);
					$UpdateNeeded = true;
				}
			}
		}

		if($UpdateNeeded) ss_set_stats($stats);

		return $stats['goodips']; // return the array so AJAX can show it

	}
}

