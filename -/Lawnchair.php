<?php

abstract class Lawnchair_Adapter{
	abstract protected function read($file);
	abstract protected function write($file,$data);

	public function printOut() {
				print $this->getValue() . "\n";
		}
}

class Lawnchair_file extends Lawnchair_Adapter{
	function read($file){
		$file = realpath(dirname(__FILE__). '/.')."/data/".$file.".json";
		if( file_exists($file) ){
			return file_get_contents($file);
		}else{
			return "";
		}
	}
	function write($file,$data){
		$file = realpath(dirname(__FILE__). '/.')."/data/".$file.".json";
		file_put_contents($file,$data);
	}
}

function Lawnchair($args) {
	$Lawnchair = new Lawnchair($args);
	return $Lawnchair;
}

// Lawnchair.php
class Lawnchair{
	private $data;
	private $name;
	private $store;
	public function __construct( $args ){
		extract($args);
		$this->name = $name;
		$this->data[$name] = array();
		$this->data[$name]['name'] = $name;
		switch($store){
			case "sql":
			case "s3":
			case "file":
			default:
				$this->store = new Lawnchair_file();
				break;
		}
		$cache = $this->store->read( md5($name) );
		if( !empty($cache) ){
			$this->data = json_decode($cache,true);
		}
	}
	public function __destruct(){
		$save = json_encode($this->data);
#		echo $save;
#		echo "<pre>".print_r( json_decode($save,true),true )."</pre>";
		$this->store->write( md5($this->name),$save );
	}
	public function keys($callback = null){
		$keys = array();
		foreach( $this->data[$this->name]['values'] as $k=>$v ){
			$keys[] = $k;
		}
		return $keys;
	}
	public function save($args,$callback = null){
		extract($args);
		if( !empty($key) ){
			$this->data[$this->name]['values'][$key] = $value;	
		}else{
			$this->data[$this->name]['values'][] = $value;	
		}
	}	
	public function batch($args,$callback = null){
	
	}
	public function count(){
		return count($this->data[$this->name]['values']);
	}
	public function max($callback = null){
		if( $callback ){
			$collection = max($this->data[$this->name]['values']);
			return $callback($collection);
		}else{
			return max($this->data[$this->name]['values']);
		}
	}	
	public function lastid(){
		return count($this->data[$this->name]['values'])-1;
	}	
	public function get($key,$callback = null){
		return $this->data[$this->name]['values'][$key];
	}
	public function exists($key,$callback = null){
	
	}
	/*
		field = field name to search
		a = operator (eq, ne, le, ge)
		q = value to search for
	*/
	public function find($args,$callback = null){
		extract($args);
		$matches = array();
		if( count( $this->data[$this->name]['values']) ){
			foreach( $this->data[$this->name]['values'] as $k=>$v ){
				foreach($v as $a=>$b){
					if( $field == $a ){
						switch($eq){
							case "ne":
								if( stripos($b,$q) !== false )	$matches[$k] = $v;						
								break;
							case "ge":
								if( $b >= $q )	$matches[$k] = $v;						
								break;
							case "le":
								if( $b <= $q )	$matches[$k] = $v;						
								break;
							case "eq":
							default:
								if( stripos($b,$q) !== false )	$matches[$k] = $v;
						}
					}
				}
			}
		}
		return $matches;
	}

	private function preg_array_key( $sPattern, $aInput ){ 
		return key( preg_grep( $sPattern, $aInput ) ); 
	}
	private function preg_array( $sPattern, $aInput ){ 
		return preg_grep( $sPattern, $aInput ); 
	}

	public function each($callback = null){
		if( $callback ){
			$collection = $this->data[$this->name]['values'];
			
		}else{
			return $this->data[$this->name]['values'];	
		}
	}
	public function all($sort="DESC",$callback = null){
		if( $sort == "ASC"){
			return array_reverse($this->data[$this->name]['values']);
		}else{
			return $this->data[$this->name]['values'];
		}
	}
	public function remove($key,$callback = null){
		unset( $this->data[$this->name]['values'][$key] );
	}
	public function nuke($callback = null){
		unset( $this->data[$this->name] );
		$this->data[$this->name] = array();
		$this->data[$name]['name'] = $name;
	}
	private function objectToArray($object){
		$array=array();
		foreach($object as $member=>$data){
			$array[$member]=$data;
		}
		return $array;
	}
}
