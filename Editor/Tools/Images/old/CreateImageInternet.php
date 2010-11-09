<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';
require_once '../../Include/Images.php';
require_once '../../Classes/Image.php';
require_once '../../Classes/RemoteFile.php';
require_once 'ImagesController.php';

error_reporting(E_ERROR);

$group = ImagesController::getGroupId();
$close = ImagesController::getBaseWindow();
$url = requestPostText('url');

$report = array('imported' => array(), 'problems' => array());
$error = false;

if (strlen($url)=="") {
	$error = array(
		"title" => "Adressen er ikke angivet",
		"description" => "Du skal udfylde hvilken adresse billedet skal hentes fra."
	);
}
else if ($file = fopen ($url, "rb")) {
    $tempFilename = $basePath.'local/cache/temp/'.basename($url);
    $temp = fopen($tempFilename, "wb");
	while (!feof($file)) {
		fwrite($temp,fread($file, 8192));
	}
    fclose($temp);
	$result = createImageFromFile($tempFilename,basename($url),null,filesize($tempFilename),null,$group);
	if ($result['success']) {
		$report['imported'][] = shortenString($url,50);
		ImagesController::setUpdateHierarchy(true);
	} else {
		$report['problems'][] = shortenString($url,50).' kunne ikke importeres: '.$result['errorMessage'].
								(strlen($result['errorDetails'])>0 ? '. '.$result['errorDetails'] : '');
	}
	unlink($tempFilename);
	fclose($file);
} else {
	$error = array(
		"title" => "Kunne ikke hente billede",
		"description" => "Den angivne adresse kunne ikke kontaktes."
	);
}

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">';
if ($error) {
	$gui.='<window xmlns="uri:Window" width="300" align="center" top="30">'.
	'<titlebar title="Advarsel">'.
	'<close link="'.$close.'"/>'.
	'</titlebar>'.
	'<content background="true">'.
	'<message xmlns="uri:Message" icon="Caution">'.
	'<title>'.encodeXML($error['title']).'</title>'.
	'<description>'.encodeXML($error['description']).'</description>'.
	'<buttongroup size="Large">'.
	'<button title="Annuller" link="'.$close.'"/>'.
	'<button title="Prøv igen" link="NewImageInternet.php" style="Hilited"/>'.
	'</buttongroup>'.
	'</message>'.
	'</content>';
} else {
	$gui.='<window xmlns="uri:Window" width="450" align="center" top="30">'.
	'<titlebar title="Resultat af import" icon="Tool/Message">'.
	'<close link="'.$close.'"/>'.
	'</titlebar>'.
	'<content padding="8" background="true">'.
	'<text top="10" bottom="10" align="center" xmlns="uri:Text">'.
	'<strong>Billederne er nu importeret</strong><break/>'.
	'<small>Her kan du se hvilke billeder der er blevet importeret fra internettet.'.
	'<break/>Desuden vises hvilke billeder der ikke kunne importeres.</small>'.
	'</text>'.
	'<list width="100%" xmlns="uri:List">'.
	'<content>'.
	'<headergroup>'.
	'<header title="Status" width="30%"/>'.
	'<header title="Filnavn" width="70%"/>'.
	'</headergroup>';
	foreach ($report['imported'] as $imported) {
		$gui.='<row>'.
		'<cell><status type="Finished"/><text>Importeret</text></cell>'.
		'<cell>'.encodeXML($imported).'</cell>'.
		'</row>';
	}
	foreach ($report['problems'] as $problem) {
		$gui.='<row>'.
		'<cell><status type="Error"/><text>Fejl</text></cell>'.
		'<cell>'.encodeXML($problem).'</cell>'.
		'</row>';
	}
	$gui.='</content></list>'.
	'<group size="Large" xmlns="uri:Button" align="right" top="8">'.
	'<button title="Tilbage" link="NewImageInternet.php"/>'.
	'<button title="OK" link="'.$close.'" style="Hilited"/>'.
	'</group>'.
	'</content>';
}
$gui.=
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Message","List","Button","Text");
writeGui($xwg_skin,$elements,$gui);
?>