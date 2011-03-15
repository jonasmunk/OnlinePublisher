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
			$summary = new WatermeterSummary();
			$summary->setNumber($meter->getNumber());
			$usages = Query::after('waterusage')->withProperty('watermeterId',$meter->getId())->get();
			$summary->setUsages($usages);
			return $summary;
		}
		return null;
	}
}