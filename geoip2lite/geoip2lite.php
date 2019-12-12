<?php
/*
 * Andrew Elisov (aka [729]jetsam)
 * обертка над (wrap of) MaxMind\Db\Reader.php 
 * 2019.12.01
 * v1
*/

if(!class_exists('\\MaxMind\\Db\\Reader')){	//
	//
	@require_once(__DIR__.'/MaxMind/Db/Reader.php');
	@require_once(__DIR__.'/MaxMind/Db/Reader/Decoder.php');
	@require_once(__DIR__.'/MaxMind/Db/Reader/Util.php');
	@require_once(__DIR__.'/MaxMind/Db/Reader/Metadata.php');
	@require_once(__DIR__.'/MaxMind/Db/Reader/InvalidDatabaseException.php');
}

use MaxMind\Db\Reader;

class GeoipLite2{
	const	GEOIP2DB_FNAME_ASN	= 'GeoLite2-ASN.mmdb';
	const	GEOIP2DB_FNAME_CITY	= 'GeoLite2-City.mmdb';
	const	GEOIP2DB_FNAME_COUNTRY	= 'GeoLite2-Country.mmdb';

	const	_G2_TYPE_COUNTRY_REG	= 'registered_country';
	const	_G2_TYPE_COUNTRY	= 'country';
	const	_G2_TYPE_CITY		= 'city';
	const	_G2_TYPE_ASN		= 'asn';
	const	_G2_TYPE_ISO_CODE	= 'iso_code';

	public	$GEO2_DEFA_LANG		= 'en';
	public	$GEO2_MAXCACHE		= 20;

	public	$db_dir			= '';

	private	$db			= [
					self::_G2_TYPE_COUNTRY	=>GeoipLite2::GEOIP2DB_FNAME_ASN,
					self::_G2_TYPE_CITY	=>GeoipLite2::GEOIP2DB_FNAME_CITY,
					self::_G2_TYPE_ASN	=>GeoipLite2::GEOIP2DB_FNAME_COUNTRY,
					];

	private	$readers		= [
					self::_G2_TYPE_COUNTRY	=>null,
					self::_G2_TYPE_CITY	=>null,
					self::_G2_TYPE_ASN	=>null,
					];

	private	$cache			= [
					self::_G2_TYPE_COUNTRY	=>[],
					self::_G2_TYPE_CITY	=>[],
					self::_G2_TYPE_ASN	=>[],
					];

	/**
	* Retrieves the record for the IP address.
	*
	* @param string $path2base		path where *.mmdb files
	*                          
	**/

	public function __construct($path2base = ''){

		if(!empty($path2base) && is_dir($path2base)){
			if($path2base[strlen($path2base)-1]!='/'||$path2base[strlen($path2base)-1]!='\\'){
				$path2base .= '/';
			}
			$this->db_dir = $path2base;
		}

		register_shutdown_function( [$this, 'CloseReaders']);
	}


	/**
	* Retrieves the record for the IP address.
	*
	* @param string $ipAddress	the IP address to look up
	*
	* @return array			the record for the IP address
	*                          
	**/
	public function geoip2_get_country($ipAddress){
		return $this->geoip2_get_data($ipAddress, self::_G2_TYPE_COUNTRY);
	}


	/**
	* @param string $ipAddress	the IP address to look up
	*
	* @param string	$lang		lang (cityname[$lang]): default 'en'
	*
	*
	* @return string		name of sity if found or empty string
	*                          
	**/
	function geoip2_get_city_name($ipAddress, $lang = 'en'){
		$city	= $this->get_city($ipAddress);
		return (isset($city['city']['names'][$lang])?$city['city']['names'][$lang]:'');
	}


	public function geoip2_get_asn($ipAddress){
		$asn = $this->get_asn($ipAddress);
		if(!empty($asn) && isset($asn['autonomous_system_number'])
				&& isset($asn['autonomous_system_organization'])
			){
			return sprintf('%s, %s'
					,$asn['autonomous_system_number']
					,$asn['autonomous_system_organization']);
		}else	return '';
	}

	public function geoip2_get_code2($ipAddress){
		return $this->get_code2($ipAddress, self::_G2_TYPE_COUNTRY);
	}

	public function geoip2_get_code2_registered($ipAddress){
		return $this->get_code2($ipAddress, self::_G2_TYPE_COUNTRY_REG);
	}


	public function geoip2_get_country_name($ipAddress, $lang = ''){
		return $this->get_country_name($ipAddress, self::_G2_TYPE_COUNTRY , $lang);
	}

	public function geoip2_get_country_name_registered($ipAddress, $lang = ''){
		return $this->get_country_name($ipAddress, self::_G2_TYPE_COUNTRY_REG , $lang);
	}


	public function geoip2_is_country_eq($ipAddress){
		$data	= $this->geoip2_get_country($ipAddress);

		if(empty($data)) return true;

		$c1 = (isset($data[self::_G2_TYPE_COUNTRY_REG][self::_G2_TYPE_ISO_CODE])
			?$data[self::_G2_TYPE_COUNTRY_REG][self::_G2_TYPE_ISO_CODE]:'');
		$c2 = (isset($data[self::_G2_TYPE_COUNTRY][self::_G2_TYPE_ISO_CODE])
			?$data[self::_G2_TYPE_COUNTRY][self::_G2_TYPE_ISO_CODE]:'');

		return ($c1==$c2);
	}

	public function geoip2_set_dbpath($dbpath){
		if(!empty($dbpath) && is_dir($dbpath)){
			$last = strlen($dbpath)-1;
			if($dbpath[$last]!='/'||$dbpath[$last]!='\\'){
				$dbpath .= '/';
			}
			$this->db_dir = $dbpath;
			return true;
		}

		return false; //oopsssss.
	}

	public function geoip2_get_dbpath(){
		return $this->db_dir = $dbpath;
	}

//
// --------------------------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------

	public function get_db_languages($type){
		$info = $this->get_metadata($type);
		return (property_exists($info,'languages')?$info->languages:[]);
	}

	public function get_city($ipAddress){
		return $this->geoip2_get_data($ipAddress, self::_G2_TYPE_CITY);
	}

	public function get_metadata($type){
		return (isset($this->readers[$type]) ? $this->readers[$type]->metadata():null);
	}

	public function get_asn($ipAddress){
		return $this->geoip2_get_data($ipAddress, self::_G2_TYPE_ASN);
	}

	public function get_code2($ipAddress, $coutry_type){
		$data = $this->geoip2_get_data($ipAddress, self::_G2_TYPE_COUNTRY);
		return (isset($data[$coutry_type]['iso_code']) ? $data[$coutry_type]['iso_code'] : '');
	}

	private function get_country_name($ipAddress, $coutry_type, $lang = 'en'){

		$data = $this->geoip2_get_data($ipAddress, self::_G2_TYPE_COUNTRY);

		if(isset($data[$coutry_type]['names'])){
			$names = $data[$coutry_type]['names'];
			if(empty($lang) || !isset($names[$lang])){
				$lang = $this->GEO2_DEFA_LANG;
			}

			if(isset($names[$lang])){
				return $names[$lang];
			}
		}
		return '';
	}

	private function geoip2_get_data($ipAddress, $type){

		$data = $this->geoip2_get_from_cache($ipAddress, $type);
		if(!empty($data)) return $data;

		if(empty($this->readers[$type])){
			if(!empty($this->db_dir) && file_exists($this->db_dir.$this->db[$type]) ){
				$this->readers[$type] = new \MaxMind\Db\Reader($this->db_dir.$this->db[$type]);
			}
		}
		
		if(empty($this->readers[$type])) return [];	// no db


		$data = $this->readers[$type]->get($ipAddress);
		$this->geoip2_add_to_cache($ipAddress, $data, $type);	

		return $data;
	}



	private function geoip2_get_from_cache($ipAddress, $type){
		return (isset($this->cache[$type][$ipAddress])?$this->cache[$type][$ipAddress]:[]);
	}

	private function geoip2_add_to_cache($ipAddress, &$data, $type){
		if(isset($this->cache[$type][$ipAddress])) return;	//already in cache

		while(count($this->cache[$type]) > ($this->GEO2_MAXCACHE-1)){
			array_shift($this->cache[$type]);
		}
		$this->cache[$type][$ipAddress] = $data;	// if empty - cache too! 
	}



	public function close(){
		$this->CloseReaders();
	}
	public function CloseReaders(){
		foreach($this->readers AS $key=>$obj){
			if(!empty($this->readers[$key])){
				$obj->close();
			}
		}
	}

	// открыть все базы (полезно, если надо получить metadata)
	public function geoip2_AutoOpenAllDB(){
		$ar	= [self::_G2_TYPE_ASN,self::_G2_TYPE_CITY,self::_G2_TYPE_COUNTRY,];
		foreach($ar AS $fake=>$type){
			if(empty($this->readers[$type]) && !empty($this->db_dir)
					&& file_exists($this->db_dir.$this->db[$type]) 
				){
				$this->readers[$type] = new \MaxMind\Db\Reader($this->db_dir.$this->db[$type]);
			}
		}
	}


	public function get_metadata_short($type){
		$ret	= [];
		$info = (isset($this->readers[$type]) ? $this->readers[$type]->metadata():null);
		if($info){

		$ret['buildEpoch']	= (property_exists($info,'buildEpoch')?Date('Y.m.d H:i:s',(int) $info->buildEpoch):'');
		$ret['nodeCount']	= (property_exists($info,'nodeCount')?$info->nodeCount:'');
		$ret['databaseType']	= (property_exists($info,'databaseType')?$info->databaseType:'');
		$ret['description']	= (property_exists($info,'description')?$info->description:'');
		$ret['languages']	= (property_exists($info,'languages')?$info->languages:'');

		}
		return $ret;
	}

}

