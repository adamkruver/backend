<?php
declare(strict_types=1);

namespace App\Controllers\Database;

class Service extends DBUnit{
	
	protected $userID;
	protected $tarifID;
	protected $payday;
	
	public function __construct($id)
	{
		$this->setId($id);		
	}
	
	public static function ByUniqueID($id)
	{
		$service = new Service($id);
		$id = $service->getID();
		$db = MySQL::getInstance();
		
		$query = $db->prepare("SELECT * FROM `services` WHERE `id`=:service_id");
		$query->bindParam(':service_id', $id, \PDO::PARAM_INT);
		$query->execute();
		if($query->rowCount()!=1) throw new \Exception("Сервис c id: `{$id}` не найден");
			
		$serviceInfo = $query->fetch(\PDO::FETCH_OBJ);
		
		$service->setUserID($serviceInfo->user_id);
		$service->setTarifID($serviceInfo->tarif_id);
		$service->setPayDay($serviceInfo->payday);
		
		return $service;
		
	}	
	
	public function writeNewTarif(Tarif $tarif)
	{
		$db = MySQL::getInstance();
		$tarifID = $tarif->getID();
		$payday = (new \DateTime("today +{$tarif->getPayPeriod()} months"))->format('Y-m-d');
		$query = $db->prepare("UPDATE `services` SET `tarif_id`=:tarif_id, `payday`=:payday WHERE `user_id`=:user_id;");
		$query->bindParam(':user_id', $this->userID, \PDO::PARAM_INT);
		$query->bindParam(':tarif_id',$tarifID, \PDO::PARAM_INT);
		$query->bindParam(':payday', $payday, \PDO::PARAM_STR);
		$query->execute();
	}
	
#	Обработчик nameFirst;	
	private function setUserID($serviceUserID)
	{
		if(!is_numeric($serviceUserID)) throw new \Exception("Ошибка в данных БД. Services -> user_id is not number");
		$this->userID = intval($serviceUserID);
	}
	
#	Обработчик nameLast;	
	private function setTarifID($serviceTarifID)
	{
		if(!is_numeric($serviceTarifID)) throw new \Exception("Ошибка в данных БД. Services -> tarif_id is not number");
		$this->tarifID = intval($serviceTarifID);
	}
	
#	Обработчик login;	
	private function setPayDay($servicePayDay)
	{
		$this->payday = $servicePayDay;
	}
	
####	Реализуем ServiceInterface
	
	public function getID(): int
	{
		return $this->ID;
	}
	
	public function getUserID(): int
	{
		return $this->userID;
	}
	
	public function getTarifID(): int
	{
		return $this->tarifID;
	}
	
	public function getPayDay(): string
	{
		return $this->payday;
	}
	

}