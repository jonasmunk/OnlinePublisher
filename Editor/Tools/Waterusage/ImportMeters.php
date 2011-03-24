<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterssage
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/FileUpload.php';
require_once '../../Classes/Objects/Waterusage.php';
require_once '../../Classes/Database.php';

$upload = new FileUpload();
$upload->process('file');
$path = $upload->getFilePath();
$contents = file_get_contents($upload->getFilePath());

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
	$line = StringUtils::fromUnice($line);
	$words = preg_split('/;/',$line);
	$number = $words[0];
	$name = $words[1];
	$street = $words[2];
	$zipcode = $words[3];
	$city = $words[4];
	if (count($words)<5) {
		Log::logTool('waterusage','import','Line does not have at least 5 words: '.$line);
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
		$meter->publish();
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
	if ($new) {
		ObjectService::addRelation($meter,$address);
	}
}
?>