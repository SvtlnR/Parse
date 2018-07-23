<?php
namespace parser\utils;
class LocalStorage{
	public static $storage=[];
	public static function get($key=null,$default=null){
		if (isset(self::$storage[$key])) {
			return self::$storage[$key];
		}
		return $default;
	}



	public static function set($key=null,$value=null){
		self::$storage[$key]=$value;
	}

	public static function getAll(){
		return self::$storage;
	}


public static function dispose(){
		 self::$storage=[];
	}

}