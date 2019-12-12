<?php
if ( ! defined( 'ABSPATH' ) ) die;

class ss_remove_bcache {
	public function process( $ip, &$stats = array(), &$options = array(), &$post = array() ) {
		
		$UpdateNeeded	= false;

		if(isset($stats['badips'][$ip])){			// first, if ip in cache
			unset( $stats['badips'][$ip] );
			$UpdateNeeded = true;
		}

		while ( count($stats['badips']) > $ss_sp_cache ) {	// if cache > max_cache || $ss_sp_cache rename to $ss_max_bcache
			array_shift( $stats['badips'] );
			$UpdateNeeded = true;
		}

		if(isset($options['bcache_ttl'])){			// TimeToLive in Hours!
			$cache_ttl = $options['bcache_ttl']*3600;
		}else	$cache_ttl = 12 * 3600;				// defa goodcache:2h, defa badcache:12h! 


		if($cache_ttl){	// 0 - NO CLEAR OLD CACHE REC

			// clear old records if need
			$nowtimeout = date('Y/m/d H:i:s', time() - $cache_ttl  + (get_option('gmt_offset')*3600) );

			foreach ( $stats['badips'] as $key => $data ) { 
				if ($data<$nowtimeout){
					unset($stats['badips'][$key]);
					$UpdateNeeded = true;
				}
			}
		}

		if($UpdateNeeded) ss_set_stats($stats);

		return $stats['badips']; // return the array so AJAX can show it
	}
}
