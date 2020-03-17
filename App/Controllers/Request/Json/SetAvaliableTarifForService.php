<?php
declare(strict_types=1);

namespace App\Controllers\Request\Json;
use App\Controllers\Database;
use App\Controllers\Request;

class SetAvaliableTarifForService{
	public static function set($userID, $serviceID, $tarifID): string
	{
		try{
			
			$service = Database\Service::ByUniqueID($serviceID);
			
			if($service->getUserID()!=$userID) throw new \Exception('У данного пользователя нет такого сервиса');			
			
			$actual = json_decode(Request\Router::Route(
										new Request\RequestMethod('GET'),
										new Request\RequestUrl("/users/{$userID}/services/{$serviceID}/tarifs")
				 					));
		#	Проверяем что нет ошибок
			if(json_last_error() !== JSON_ERROR_NONE) throw new \Exception('Не верные входные данные json');
			
			if($actual->result == "error") throw new \Exception('Сервис вернул {"result": "error"}');
			
			
			$avaliableTarifsArray = $actual->tarifs[0]->tarifs;
			
			foreach($avaliableTarifsArray as $avaliableTarif)
			{
				if($avaliableTarif->ID == $tarifID)
				{
					$serviceTarif = Database\Tarif::ByUniqueID($avaliableTarif->ID);
					$service->writeNewTarif($serviceTarif);
					return '{"result":"ok"}';
				}
			}
			
			throw new \Exception('Желаемый тариф отсутствует в списке доступных');
			
		}catch(\Throwable $throw){
			
			return '{"result":"error"}';
		}
	}

}