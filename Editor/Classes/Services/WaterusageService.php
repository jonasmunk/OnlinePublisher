<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
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
	
	function getLatestUsage($number) {
		$meter = Query::after('watermeter')->withProperty('number',$number)->first();
		if ($meter) {
			$meter = Query::after('waterusage')->withProperty('watermeterId',$meter->getId())->orderBy('date')->descending()->withWindowSize(1)->first();
			return $meter;
		}
		return null;
	}
	
	function removeMeter($id) {
		$meter = Watermeter::load($id);
		if ($meter) {
			$address = Query::after('address')->withRelationFrom($meter)->first();
			if ($address) {
				$address->remove();
			}
			$usages = Query::after('waterusage')->withProperty('watermeterId',$meter->getId())->get();
			foreach ($usages as $usage) {
				$usage->remove();
			}
			$meter->remove();
		}
	}
	
	function getSummaryByMeter($meter) {
		$summary = new WatermeterSummary();
		$summary->setNumber($meter->getNumber());
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
	
	function getYears() {
		$years = array();
		$sql = "select distinct DATE_FORMAT(waterusage.date, '%Y') as year from waterusage where date is not null order by year";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$years[] = intval($row['year']);
		}
		Database::free($result);
		return $years;
	}
	
	function saveSummary($summary) {
		$meter = Watermeter::load($summary->getWatermeterId());
		if ($meter) {
			$meter->setNumber($summary->getNumber());
			$meter->save();
			$meter->publish();
			$address = Query::after('address')->withRelationFrom($meter)->first();
			if ($address) {
				$address->setStreet($summary->getStreet());
				$address->setCity($summary->getCity());
				$address->setZipcode($summary->getZipcode());
				$address->publish();
				$address->save();
			} else {
				$address = new Address();
				$address->setStreet($summary->getStreet());
				$address->setCity($summary->getCity());
				$address->setZipcode($summary->getZipcode());
				$address->save();
				$address->publish();
				ObjectService::addRelation($meter,$address);
			}
		} else {
			Log::debug('Meter not found');
			// create new
		}
	}
}