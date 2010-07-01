<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/Object.php');

Object::$schema['waterusage'] = array(
	'number' => array('type'=>'string'),
	'year'   => array('type'=>'int'),
	'value'  => array('type'=>'int'),
	'date'  => array('type'=>'datetime')
);

class WaterUsage extends Object {
	var $number;
	var $year;
	var $value;
	var $date;

	function WaterUsage() {
		parent::Object('waterusage');
	}
	
	function getIn2iGuiIcon() {
		return "file/generic";
	}
	
	function setNumber($number) {
	    $this->title = $number;
	    $this->number = $number;
	}

	function getNumber() {
	    return $this->number;
	}
	
	function setYear($year) {
	    $this->year = $year;
	}

	function getYear() {
	    return $this->year;
	}
	
	function setValue($value) {
	    $this->value = $value;
	}

	function getValue() {
	    return $this->value;
	}
	
	function setDate($date) {
	    $this->date = $date;
	}

	function getDate() {
	    return $this->date;
	}
	
	
	// Static
	
	function load($id) {
		return Object::get($id,'waterusage');
	}
	
	function search($query=array()) {
		$query['type']='waterusage';
		$query['limits']=array();
		if (isset($query['year'])) {
			$query['limits'][] = 'waterusage.year='.Database::int($query['year']);
		}
		return Object::retrieve($query);
	}
	
	function override($dummy) {
		$sql="select object_id from waterusage where number=".Database::text($dummy->getNumber())." and year=".Database::int($dummy->getYear());
		if ($row = Database::selectFirst($sql)) {
			$usage = WaterUsage::load($row['object_id']);
			error_log('found:'.$row['object_id']);
		}
		if (!$usage) {
			$usage = new WaterUsage();
		}
		$usage->setNumber($dummy->getNumber());
		$usage->setYear($dummy->getYear());
		$usage->setDate($dummy->getDate());
		$usage->setValue($dummy->getValue());
		$usage->save();
		$usage->publish();
		error_log('saved:'.$usage->getId());
	}
}
?>