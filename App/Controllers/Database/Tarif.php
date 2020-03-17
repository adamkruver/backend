<?php
declare(strict_types=1);

namespace App\Controllers\Database;


class Tarif extends DBUnit {
	
	protected $title;
	protected $price;
	protected $link;
	protected $speed;
	protected $payPeriod;
	protected $tarifGroupID;
	
	public function __construct($id)
	{
		$this->setId($id);
	}
	
	public static function ByUniqueID($id): Tarif
	{
		$tarif = new Tarif($id);
		$id = $tarif->getID();
		$db = MySQL::getInstance();
		
		$query = $db->prepare("SELECT * FROM `tarifs` WHERE `id`=:tarif_id");
		$query->bindParam(':tarif_id', $id, \PDO::PARAM_INT);
		$query->execute();
		if($query->rowCount()!=1) throw new \Exception("Тариф c id: `{$id}` не найден");
			
		$tarifInfo = $query->fetch(\PDO::FETCH_OBJ);
		
		$tarif->setTitle($tarifInfo->title);
		$tarif->setPrice($tarifInfo->price);
		$tarif->setLink($tarifInfo->link);
		$tarif->setSpeed($tarifInfo->speed);
		$tarif->setPayPeriod($tarifInfo->pay_period);
		$tarif->setTarifGroupID($tarifInfo->tarif_group_id);
		
		return $tarif;
		
	}
	
	public static function ArrayByTarifID(int $tarifGroupID): Array
	{
		$db = MySQL::getInstance();
	
		$query = $db->prepare("SELECT `ID` FROM `tarifs` WHERE `tarif_group_id`=:tarif_group_id");
		$query->bindParam(':tarif_group_id', $tarifGroupID, \PDO::PARAM_INT);
		$query->execute();
		if($query->rowCount()==0) throw new \Exception("tarif group id: `{$tarifGroupID}` не найден");
			
		$groupOfTarifsInfo = $query->fetchAll(\PDO::FETCH_OBJ);
		
		
		$tarifs = Array();
		foreach($groupOfTarifsInfo as $tarifInfo){
			
			array_push($tarifs, Tarif::ByUniqueID($tarifInfo->ID));
		}
		
		return $tarifs;
	}
	
	private function setTitle($title)
	{
		if(strlen($title)<0) throw new \Exception("Tarif title can't be NULL");
		$this->title = $title;
	}
	
	private function setPrice($price)
	{
		if(!is_numeric($price)) throw new \Exception("Tarif price isn't Number");
		$this->price = floatval($price);
	}
	
	private function setLink($link)
	{
		$this->link = $link;
	}
	
	private function setSpeed($speed)
	{
		if(!is_numeric($speed)) throw new \Exception("Tarif speed isn't Number");
		$this->speed = intval($speed);
	}
	
	private function setPayPeriod($payPeriod)
	{
		if(!is_numeric($payPeriod)) throw new \Exception("Tarif pay period isn't Number");
		$this->payPeriod = intval($payPeriod);
	}
	
	private function setTarifGroupID($tarifGroupID)
	{
		if(!is_numeric($tarifGroupID)) throw new \Exception("Tarif group id isn't Number");
		$this->tarifGroupID = intval($tarifGroupID);
	}
	
	
####	Реализуем TarifInterface
	
	
	public function getID(): int
	{
		return $this->ID;
	}
	
	public function getTitle(): string
	{
		return $this->title;
	}
	
	public function getPrice(): float
	{
		return $this->price;
	}
		
	public function getLink(): string
	{
		return $this->link;
	}
	
	public function getSpeed(): int
	{
		return $this->speed;
	}
	
	public function getPayPeriod(): int
	{
		return $this->payPeriod;
	}
	
	public function getTarifGroupID(): int
	{
		return $this->tarifGroupID;
	}
	
}
		