<?php
declare(strict_types=1);

namespace App\Controllers\Database;

trait DBUnitID {	
	
	public function getID():int {
		return $this->ID;
	}
	
	protected function setID($id)
	{	
		if(!is_numeric($id)) throw new \Exception(__CLASS__." id isn't numeric");
		
		$this->ID = intval($id);
	}	
}

abstract class DBUnit {
	
	protected $ID;	
	
	use DBUnitID;
	
	public function __construct()
	{
		
	}
	
}