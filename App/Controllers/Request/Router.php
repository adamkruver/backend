<?php
declare(strict_types=1);

namespace App\Controllers\Request;

use \App\Controllers\View;
use \App\Services\Json\Get\Tarifs as getTarifs;


class Router {
	private static $router;	
	
	public static function Route(RequestMethod $method, RequestUrl $url)
	{
		switch($method->get())
		{
			case "GET":
				if($url->getProperty('tarifs'))
				{
					return $json = Json\GetAvaliableTarifForService::get(
						$url->getProperty('users'),
						$url->getProperty('services')
					);
				}
				
				
			break;
				
			case "PUT":
				if($method->getProperty('tarif_id')){
					return $json = Json\SetAvaliableTarifForService::set(
						$url->getProperty('users'),
						$url->getProperty('services'),
						$method->getProperty('tarif_id')
					);
				}				
			break;
				
			default: 
				#	redirect;
		}
		
		http_response_code(404);
		
	}
	
	public static function getCurrentUrl(): RequestUrl
	{
		return new RequestUrl($_SERVER["REQUEST_URI"]);
	}
	
	public static function getCurrentMethod(): RequestMethod
	{
		return new RequestMethod($_SERVER["REQUEST_METHOD"]);
	}
	
	public static function init()
	{
		if(empty(self::$router)){
			$url = 
			$method = $_SERVER["REQUEST_METHOD"];
			self::$router = new Router(new Request($url,$method));
		}		
	}

/*	public function __construct()
	{
		switch($request->getMethod()){
			case 'GET':
				
				if(!$request->getUrlProperty('tarifs')
				|| !$request->getUrlProperty('users')
				|| !$request->getUrlProperty('services'))
				{
					http_response_code(404);
					exit;
				}

			#	Если сервис будет доступен всем 
			#	header('Access-Control-Allow-Origin: *');
				$view = View::getInstance();
				$view->setHeader("Content-type: application/json; charset=utf-8");
				
				new getTarifs($request);
			break;
				
			default:
			break;
		}
	}*/
}