<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class WaterusageService {

	static $STATUS_ICONS = array(0 => 'monochrome/round_question',1 => 'common/success',-1 => 'common/stop');
	static $STATUS_TEXT = array(0 => 'Ukendt',1 => 'Valideret',-1 => 'Afvist');
	static $SOURCE_TEXT = array(0 => 'Administrator',1 => 'Import',2 => 'Kunde');
	
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
	
	//select * from watermeter where not LENGTH(number)=8
	
	function getSummaryByMeter($meter) {
		$summary = new WatermeterSummary();
		$summary->setNumber($meter->getNumber());
		$address = Query::after('address')->withRelationFrom($meter)->first();
		if ($address) {
			$summary->setStreet($address->getStreet());
			$summary->setZipcode($address->getZipcode());
			$summary->setCity($address->getCity());
		}
		$email = Query::after('emailaddress')->withRelationFrom($meter)->first();
		if ($email) {
			$summary->setEmail($email->getAddress());
		}
		$phone = Query::after('phonenumber')->withRelationFrom($meter)->first();
		if ($phone) {
			$summary->setPhone($phone->getNumber());
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
	
	function getYearCounts() {
		$sql = "select DATE_FORMAT(waterusage.date, '%Y') as year,count(object_id) as count from waterusage group by DATE_FORMAT(waterusage.date, '%Y')";
		return Database::selectAll($sql);
	}
	
	function getStatusCounts() {
		$sql = "select status as status,count(object_id) as count from waterusage group by status order by status";
		return Database::selectAll($sql);
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
			WaterusageService::updateEmailOfMeter($meter,$summary->getEmail());
			WaterusageService::updatePhoneOfMeter($meter,$summary->getPhone());
		} else {
			Log::debug('Meter not found: '.$summary->getWatermeterId());
		}
	}
	
	function updateEmailOfMeter($meter,$address) {
		$email = Query::after('emailaddress')->withRelationFrom($meter)->first();
		if (StringUtils::isBlank($address) && $email) {
			$email->remove();
		} else {
			if ($email) {
				$email->setAddress($address);
				$email->save();
				$email->publish();
			} else {
				$email = new EmailAddress();
				$email->setAddress($address);
				$email->save();
				$email->publish();
				ObjectService::addRelation($meter,$email);
			}
		}
	}
	
	function updatePhoneOfMeter($meter,$number) {
		$phone = Query::after('phonenumber')->withRelationFrom($meter)->first();
		if (StringUtils::isBlank($number) && $phone) {
			$phone->remove();
		} else {
			if ($phone) {
				$phone->setNumber($number);
				$phone->save();
				$phone->publish();
			} else {
				$phone = new Phonenumber();
				$phone->setNumber($number);
				$phone->save();
				$phone->publish();
				ObjectService::addRelation($meter,$phone);
			}
		}
	}
	
	function parseAddress($str) {
		if (preg_match("/([^,]+),([\\w ]+,)[ ]?([0-9]+) ([\\w]+)/", $str,$matches)) {
			return array('street'=>$matches[1],'zipcode'=>$matches[3],'city'=>$matches[4]);
		}
		if (preg_match("/([^,]+),[ ]?([0-9]+) ([\\w]+)/", $str,$matches)) {
			return array('street'=>$matches[1],'zipcode'=>$matches[2],'city'=>$matches[3]);
		}
		return null;
	}
		
	function getStatusIcon($status) {
		return WaterusageService::$STATUS_ICONS[$status];
	}
	
	function getStatusText($status) {
		return WaterusageService::$STATUS_TEXT[$status];
	}
	
	function getSourceText($source) {
		return WaterusageService::$SOURCE_TEXT[$source];
	}
}