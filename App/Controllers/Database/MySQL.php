<?php
declare(strict_types = 1);

namespace App\Controllers\Database;

class MySQL extends Database{
	private static $mySQL;
	
	public static function getInstance(): \PDO
	{
		if(empty(self::$mySQL))
			self::$mySQL = (new MySQL())->connect();
		
		return self::$mySQL;
	}
	
	private function __construct(){		
		
		parent::setDatabaseType("mysql");
		parent::__construct();
		
	# 	Тут Конфиги для MySQL
	}
}
