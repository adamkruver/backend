<?php
declare(strict_types = 1);

namespace App\Controllers\Database;
use \PDO;

class DBConfigException extends \Exception {};

abstract class Database {
	
	protected $databaseType; # MySQL, etc
	protected $db;
	
#	Из конфигов
	protected $host="";
	protected $name="";
	protected $user="";
	protected $password="";
	
#	Конструктор	
	protected function __construct()
	{
	#	Проверяем Установлен ли тип базы данных и проверяем конфиги
		if(!isset($this->databaseType)) throw new DBConfigException("Не выбран тип DB");
		$this->checkDatabaseConfig();
	}
	
#	Позаботимся о закрытии соединения с Database
	public function __destruct()
	{
		$this->db=null;
	}
	
#	Устанавливаем тип Database
	protected function setDatabaseType(string $type)
	{
		$this->databaseType = $type;
	}
	
	public function connect(): PDO
	{
		try{
			$this->db = new PDO("{$this->databaseType}:host={$this->host}; dbname={$this->name};",
									$this->user,
									$this->password);

			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $this->db;
		}catch(Throwable $throw){
			$m = $throw->getMessage();
			throw DBConfigException("Ошибка при подключении PDO: {$m}");
			return null;
		}
	}	
	
	
	public function assocQuery(string $query)
	{
		$this->db = $this->db->PDO->prepare($query);
	}
	
#	Проверяем Конфиг
	protected function checkDatabaseConfig()
	{		
		$this->setDatabaseProp('DB_HOST');
		$this->setDatabaseProp('DB_NAME');
		$this->setDatabaseProp('DB_USER');
		$this->setDatabaseProp('DB_PASSWORD');
	}
	
	private function setDatabaseProp(?string $prop = null)
	{
	#	Проверяем что есть GLOBAL[user][$prop];
		if(!defined($prop)) throw DBConfigException("Значение `{$prop}` не установлено");
		
	#	Проверяем идентификатор 'DB_'
		if(strpos($prop,'DB_') === false) throw DBConfigException("Значение `{$prop}` должно начинаться с `DB_`");
		
	#	Переводим в нижний регистр после удаления приставки 'DB_'
		$thisProp = strtolower(substr($prop,3));
		
	#	Проверяем на наличие значения в this
		if(!isset($this->{$thisProp})) throw DBConfigException("Значение `{$thisProp}` не определено в классе");
		
		$this->{$thisProp} = get_defined_constants(true)['user'][$prop];		
	}
}
