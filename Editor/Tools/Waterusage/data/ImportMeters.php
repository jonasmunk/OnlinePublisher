<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterssage
 */
require_once '../../../Include/Private.php';

$upload = new FileUpload();
$upload->process('file');
$path = $upload->getFilePath();
/*
Log::logTool('waterusage','import','Starting import (meters)');
$contents = file_get_contents($upload->getFilePath());
$lines = preg_split("/\n/",$contents);
foreach ($lines as $line) {
	handleLine($line);
}
Log::logTool('waterusage','import','Import complete (meters)');
In2iGui::respondUploadSuccess();
exit;*/

Log::logTool('waterusage','import','Starting import (meters)');
$handle = @fopen($path, "r");
if ($handle) {
    while (!feof($handle)) {
        $line = fgets($handle, 4096);
		handleLine($line);
    }
	fclose($handle);
}
Log::logTool('waterusage','import','Import complete (meters)');

In2iGui::respondUploadSuccess();

function handleLine($line) {
	$line = StringUtils::fromUnicode($line);
	$words = preg_split('/;/',$line);
	$street = '';
	$zipcode = '';
	$city = '';
	if (count($words)==2) {
		$number = $words[0];
		$address = $words[1];
		$parsed = WaterusageService::parseAddress($address);
		if ($parsed) {
			$street = $parsed['street'];
			$zipcode = $parsed['zipcode'];
			$city = $parsed['city'];
		} else {
			Log::debug('Unable to parse address: '.$address);
			Log::logTool('waterusage','import','Unable to parse address: '.$address);
		}
	} else if (count($words)==4) {
		$number = $words[0];
		$street = $words[1];
		$zipcode = $words[2];
		$city = $words[3];
	} else {
		Log::logTool('waterusage','import','Unable to parse line: '.$line);
		return;
	}
	if (!ValidateUtils::validateDigits($number)) {
		Log::logTool('waterusage','import','The number is not made of pure digits: '.$line);
		return;
	}
	$meter = Query::after('watermeter')->withProperty('number',$number)->first();
	if (!$meter) {
		$meter = new Watermeter();
		$meter->setNumber($number);
		$meter->save();
	}
	$address = Query::after('address')->withRelationFrom($meter)->first();
	$new = false;
	if (!$address) {
		$address = new Address();
		$new = true;
	}
	$address->setStreet($street);
	$address->setCity($city);
	$address->setZipcode($zipcode);
	$address->save();
	$address->publish();
	if ($new) {
		ObjectService::addRelation($meter,$address);
	}
	// Be sure to publish at end
	$meter->publish();
}
?>