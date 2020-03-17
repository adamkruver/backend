<?php
declare(strict_types=1);

namespace App\Controllers\Database;

class User extends DBUnit{
	
	protected $nameFirst;
	protected $nameLast;
	protected $login;
	
	private $services = array();
	
	public function __construct($id)
	{
	#	Проверяем что id is numeric
		$this->setId($id);
	}
	
	public static function ByUniqueID($id)
	{
		$user = new User($id);
		$id = $user->getID();
		$db = MySQL::getInstance();
		
		$query = $db->prepare("SELECT * FROM `users` WHERE `id`=:user_id");
		$query->bindParam(':user_id', $id, \PDO::PARAM_INT);
		$query->execute();
		if($query->rowCount()!=1) throw new \Exception("Пользователь c id: `{$id}` не найден");
			
		$userInfo = $query->fetch(\PDO::FETCH_OBJ);
		
		$user->setLogin($userInfo->login);
		$user->setNameFirst($userInfo->name_first);
		$user->setNameLast($userInfo->name_last);
		return $user;
	}	

#	Обработчик login;	
	private function setLogin($userLogin)
	{
		$this->login = $userLogin;
	}
	
#	Обработчик nameFirst;	
	private function setNameFirst($userNameFirst)
	{
		$this->nameFirst = $userNameFirst;
	}
	
#	Обработчик nameLast;	
	private function setNameLast($userNameLast)
	{
		$this->nameLast = $userNameLast;
	}
	
####	Реализуем UserInterface
	
	public function getID(): int
	{
		return $this->ID;
	}
	
	public function getLogin(): string
	{
		return $this->login;
	}
	
	public function getNameFirst(): string
	{
		return $this->nameFirst;
	}
	
	public function getNameLast(): string
	{
		return $this->nameLast;
	}	
}