<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
require_once($basePath.'Editor/Classes/Modules/Water/WatermeterSummary.php');

class WaterusageService {
	
	function overwrite($dummy) {
		$sql="select object_id from waterusage where number=".Database::text($dummy->getNumber())." and year=".Database::int($dummy->getYear());
		if ($row = Database::selectFirst($sql)) {
			$usage = Waterusage::load($row['object_id']);
		}
		if (!$usage) {
			$usage = new Waterusage();
		}
		$usage->setNumber($dummy->getNumber());
		$usage->setYear($dummy->getYear());
		$usage->setDate($dummy->getDate());
		$usage->setValue($dummy->getValue());
		$usage->save();
		$usage->publish();
	}
	
	function getSummary($number) {
		$meter = Query::after('watermeter')->withProperty('number',$number)->first();
		if ($meter) {
			return WaterusageService::getSummaryByMeter($meter);
		}
		return null;
	}
	
	function getSummaryByMeter($meter) {
		$summary = new WatermeterSummary();
		$summary->setNumber($meter->getNumber());
		$usages = Query::after('waterusage')->withProperty('watermeterId',$meter->getId())->get();
		$summary->setUsages($usages);
		$address = Query::after('address')->withRelationFrom($meter)->first();
		if ($address) {
			$summary->setStreet($address->getStreet());
			$summary->setZipcode($address->getZipcode());
			$summary->setCity($address->getCity());
		}
		return $summary;
	}

	function getSummaryById($id) {
		$meter = Watermeter::load($id);
		if ($meter) {
			return WaterusageService::getSummaryByMeter($meter);
		} else {
			Log::debug('Unable to load summary with id='.$id);
		}
		return null;
	}
	
	function saveSummary($summary) {
		$meter = Watermeter::load($summary->getWatermeterId());
		if ($meter) {
			$address = Query::after('address')->withRelationFrom($meter)->first();
			if ($address) {
				Log::debug('Address was found!');
				$address->setStreet($summary->getStreet());
				$address->setCity($summary->getCity());
				$address->setZipcode($summary->getZipcode());
				$address->save();
			} else {
				Log::debug('Address not found, creating new');
				$address = new Address();
				$address->setStreet($summary->getStreet());
				$address->setCity($summary->getCity());
				$address->setZipcode($summary->getZipcode());
				$address->save();
				ObjectService::addRelation($meter,$address);
			}
		} else {
			Log::debug('Meter not found');
			// create new
		}
	}
}