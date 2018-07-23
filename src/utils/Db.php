<?php 
namespace parser\utils;
use PDO;
class Db{
	static private $instance = null;
	private function __construct(){}
	public static function getInstance(){
		if(!self::$instance){
			try {
				$host = 'localhost';
				$dbname = 'parseSites';
				$dbuser = 'root';
				$dbpassword =''; 
				$dsn='mysql:host='.$host.';dbname='.$dbname;
				self::$instance=new PDO($dsn, $dbuser,$dbpassword);	
				} catch (PDOException $e) {
					$instance=null;	
				}
			}	
			return self::$instance;
		}
	}