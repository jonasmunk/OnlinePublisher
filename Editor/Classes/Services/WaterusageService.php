<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */

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
}