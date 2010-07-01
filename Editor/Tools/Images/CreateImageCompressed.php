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
require_once $basePath.'Editor/Libraries/pclzip/pclzip.lib.php';
require_once '../../Classes/Image.php';
require_once '../../Classes/GuiUtils.php';
require_once '../../Classes/FileSystemUtil.php';
require_once 'ImagesController.php';

error_reporting(E_ALL);

$group = ImagesController::getGroupId();
$close = ImagesController::getBaseWindow();
$error = false;
$report = array('imported' => array(), 'ignored' => array(), 'problems' => array());

if (requestGetBoolean('upload') && (count($_FILES)==0 || $_FILES['file']['error']==1 || $_FILES['file']['error']==2)) {
    $error = array(
        'title' => 'Filen er for stor',
        'description' => 'Den valgte fil er for stor til at systemet kan håndtere den. Den maksimale størrelse filen må have er '.GuiUtils::bytesToLongString(FileSystemUtil::getMaxUploadSize()).'.'
        );
}
elseif (requestGetBoolean('upload') && ($_FILES['file']['error'] == UPLOAD_ERR_NO_FILE || $_FILES['file']['tmp_name']=='')) {
    $error = array(
        'title' => 'Der blev ikke sendt en fil',
        'description' => 'Du skal vælge en fil på din lokale maskine.'
        );
}
elseif (requestGetBoolean('upload') && $_FILES['file']['error']>0) {
    $error = array(
        'title' => 'Der er sket en ukendt fejl',
        'description' => $_FILES['file']['error']
        );
}
else {
	$zip = new PclZip($_FILES["file"]["tmp_name"]);
	$content = $zip->listContent();
	if (!is_array($content)) {
		$error = array(
            'title' => 'Filen er ikke et understøttet komprimeret arkiv',
            'description' => 'Denne funktion kan kun importere filer i ZIP-formatet.',
            'error' => 'Denne funktion kan kun importere filer i ZIP-formatet.'
            );
	} else {
		// Loop through all items in the ZIP file
		foreach ($content as $file) {
			$fileName = $file['filename'];
			$base = basename($fileName);
			// Filter all .files and folders
			if ($base{0}!='.' && !$file['folder']) {
				// Only if filename is supported
				if (isSupportedImageFile($fileName)) {
					// Extract the file
					$extracted = $zip->extractByIndex($file['index'],$basePath.'local/cache/temp');
					// If could extract
					if ($extracted[0]['status']=='ok') {
						$fileName = basename($extracted[0]['stored_filename']);
						$filePath = $extracted[0]['filename'];
						$fileSize = $extracted[0]['size'];
						$result = createImageFromFile($filePath,$fileName,null,$fileSize,null,$group);
						if ($result['success']) {
							$report['imported'][] = $extracted[0]['stored_filename'];
						} else {
							$report['problems'][] = $extracted[0]['stored_filename'].' kunne ikke importeres: '.$result['errorMessage'].
								(strlen($result['errorDetails'])>0 ? '. '.$result['errorDetails'] : '');
						}
					} else {
						$report['problems'][] = $fileName.' kunne ikke pakkes ud';
					}
				} else {
					$base = basename($fileName);
					if (!$file['folder']) {
						$report['ignored'][] = $fileName;
					}
				}
			}
		}
	}
	ImagesController::setUpdateHierarchy(true);
}

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">';
if (is_array($error)) {
	$msg = 'Filens mime-type: '.(isset($_FILES['file']) ? $_FILES['file']["type"] : '');
	$gui.='<window xmlns="uri:Window" width="350" align="center" top="30">'.
	'<titlebar title="Advarsel">'.
	'<close link="'.$close.'"/>'.
	'</titlebar>'.
	'<content background="true" padding="5">'.
	'<message xmlns="uri:Message" icon="Caution">'.
	'<title>'.encodeXML($error['title']).'</title>'.
	'<description>'.encodeXML($error['description']).'</description>'.
	(isset($error['error']) ? '<error badge="Vis fejl">'.encodeXML($msg).'</error>' : '').
	'<buttongroup size="Large">'.
	'<button title="Annuller" link="'.$close.'"/>'.
	'<button title="Prøv igen" link="NewImageCompressed.php" style="Hilited"/>'.
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
	'<strong>Filen er nu importeret</strong><break/>'.
	'<small>Her kan du se hvilke billeder der blevet importeret fra det komprimerede arkiv.'.
	'<break/>Desuden vises hvilke arkiver der er ignoreret eller ikke kunne importeres.</small>'.
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
	foreach ($report['ignored'] as $ignored) {
		$gui.='<row>'.
		'<cell><status type="Unknown"/><text>Ignoreret</text></cell>'.
		'<cell>'.encodeXML($ignored).'</cell>'.
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
	'<button title="Tilbage" link="NewImageCompressed.php"/>'.
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