<?php
declare(strict_types=1);

namespace App\Controllers\Request;

class RequestMethod {
	
	private const Methods = array("GET", "PUT");
	
	private $method;
	private $json;
	
	public function __construct(string $method)
	{
		$method = strtoupper($method);
		
		if(!in_array($method,self::Methods)) throw new \Exception("Метод `{$method}` не является допустимым. ");
			
		if($method == "PUT"){
			
			$this->json = json_decode(file_get_contents('php://input'));
			
		#	Проверяем что нет ошибок
			if(json_last_error() !== JSON_ERROR_NONE) throw new \Exception("Входные данные не являются valid JSON");
		}		
			
		$this->method = $method;

	}
	
	public function get(): string
	{
		return $this->method;
	}
	
	public function getProperty(string $property) # или /stdObject
	{
		if(!isset($this->json->{$property})) return false;
		return $this->json->{$property};
	}
}