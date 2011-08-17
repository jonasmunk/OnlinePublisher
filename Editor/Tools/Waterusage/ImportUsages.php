<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterssage
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Network/FileUpload.php';
require_once '../../Classes/Objects/Waterusage.php';
require_once '../../Classes/Database.php';

$upload = new FileUpload();
$upload->process('file');
$path = $upload->getFilePath();
$contents = file_get_contents($upload->getFilePath());

Log::logTool('waterusage','import','Starting import (usage)');
$handle = @fopen($path, "r");
if ($handle) {
    while (!feof($handle)) {
        $line = fgets($handle, 4096);
		handleLine($line);
    }
	fclose($handle);
}
Log::logTool('waterusage','import','Import complete (usage)');

In2iGui::respondUploadSuccess();

function handleLine($line) {
	$words = preg_split('/;/',$line);
	if (count($words)!=3) {
		Log::logTool('waterusage','import','Line does not have 3 words: '.$line);
		return;
	}
	$number = $words[0];
	$value = $words[1];
	$date = DateUtils::parse($words[2]);
	if (!ValidateUtils::validateDigits($number)) {
		Log::logTool('waterusage','import','The number is not made of pure digits: '.$line);
		return;
	}
	if (!ValidateUtils::validateDigits($value)) {
		Log::logTool('waterusage','import','The value is not made of pure digits: '.$line);
		return;
	}
	if ($date==null) {
		Log::logTool('waterusage','import','Unable to parse date: '.$line);
		return;
	}
	$meter = Query::after('watermeter')->withProperty('number',$number)->first();
	if (!$meter) {
		Log::logTool('waterusage','import','Meter not found, creating it: number='.$number);
		$meter = new Watermeter();
		$meter->setNumber($number);
		$meter->save();
		$meter->publish();
	}
	$usage = Query::after('waterusage')->withProperty('watermeterId',$meter->getId())->withProperty('value',$value)->withProperty('date',$date)->first();
	if (!$usage) {
		Log::debug('Usage not found');
		$usage = new Waterusage();
		$usage->setValue($value);
		$usage->setWatermeterId($meter->getId());
		$usage->setDate($date);
		$usage->save();
		$usage->publish();
	} else {
		Log::logTool('waterusage','import','Usage already found: meter='.$meter->getNumber().', value='.$usage->getValue().', date='.DateUtils::formatLongDate($date));
	}
}
?>