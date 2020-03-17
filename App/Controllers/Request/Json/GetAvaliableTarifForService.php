<?php
declare(strict_types=1);

namespace App\Controllers\Request\Json;
use App\Controllers\Database;

class GetAvaliableTarifForService{
	public static function get($userID, $serviceID): string
	{
		try{
			$service = Database\Service::ByUniqueID($serviceID);
			if($service->getUserID()!=$userID) throw new \Exception('У данного пользователя нет такого сервиса');
			
			$user = Database\User::ByUniqueID($userID);
			
			$serviceTarif = Database\Tarif::ByUniqueID($service->getTarifID());
			
			$tarifsWithSameGroupID = Database\Tarif::ArrayByTarifID($serviceTarif->getTarifGroupID());
			
			
			$serverAnswer = new \stdClass();
			$serverAnswer->{'result'} = "ok";
			$serverAnswer->{'tarifs'} = self::makeJSON($serviceTarif,$tarifsWithSameGroupID);
			
			return json_encode($serverAnswer);
			
		}catch(\Throwable $throw){
			
			return '{"result":"error"}';
		}
	}
	private static function makeJSON(Database\Tarif $tarif,array $sameGruopID)
	{
		$result = Array();
		$serviceTarif = new \stdClass();
		$serviceTarif->{'title'} = $tarif->getTitle();
		$serviceTarif->{'link'} = $tarif->getLink();
		$serviceTarif->{'speed'} = $tarif->getSpeed();
		
		$serviceTarif->{'tarifs'} = Array();
		
		foreach($sameGruopID as $tarifsWithSameGroupID){
			if($tarifsWithSameGroupID instanceof Database\Tarif){
				
				
				$sameTarif = new \stdClass();
				$sameTarif->{'ID'} = $tarifsWithSameGroupID->getID();
				$sameTarif->{'title'} = $tarifsWithSameGroupID->getTitle();
				$sameTarif->{'price'} = $tarifsWithSameGroupID->getPrice();
				$sameTarif->{'pay_period'} = $tarifsWithSameGroupID->getPayPeriod();
				$sameTarif->{'new_payday'} = (new \DateTime("today +{$sameTarif->{'pay_period'}} months"))->format('UO');
				$sameTarif->{'speed'} = $tarifsWithSameGroupID->getSpeed();
		
				array_push($serviceTarif->tarifs,$sameTarif);
			}
		}
		
		array_push($result,$serviceTarif);
		
		return $result;
		
	}
}