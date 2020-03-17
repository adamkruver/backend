<?php
declare(strict_types=1);

namespace App\Controllers\Request;

class RequestUrl {
	private $url;
	public function __construct(string $url)
	{
		
		$this->url = urldecode(parse_url($url,PHP_URL_PATH));
		$this->properties = $this->toArray($this->url);
	}
	
	public function get(): string
	{
		return $this->url;
	}
	
#	Очень похоже на trait
	private function toArray(string $url): \stdClass 
	{
		
		$indexPathArray = explode('/',$_SERVER['SCRIPT_NAME']);
		array_pop($indexPathArray);
		
		
		$urlAsArray = explode('/',$url);
		
	#	Сдвигаем пустой элемент
		foreach($indexPathArray as $indexPathElement){
			if($urlAsArray[0] == $indexPathElement)
			{
				array_shift($urlAsArray);
			}
		}
		
		$keyValueArray = array_chunk($urlAsArray,2);
		
		$properties = new \stdClass();
		
		foreach($keyValueArray as $keyValue)
		{
			if(isset($keyValue[0]))
				$properties->{$keyValue[0]}=true;
			
			if(isset($keyValue[1]))
				$properties->{$keyValue[0]}=$keyValue[1];
			
 		}
		return $properties;
	}	
	
	public function getProperty(string $property)
	{
		if(!isset($this->properties->{$property})) return false;
		return $this->properties->{$property};
	}
	
}