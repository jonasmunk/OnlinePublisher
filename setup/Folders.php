<?php
/**
 * @package OnlinePublisher
 * @subpackage Setup
 */

require_once '../Config/Setup.php';
require_once '../Editor/Include/Public.php';
require_once '../Editor/Include/Functions.php';
require_once '../Editor/Classes/Request.php';
require_once '../Editor/Include/XmlWebGui.php';
require_once '../Editor/Classes/Utilities/StringUtils.php';

require_once 'Functions.php';
require_once 'Security.php';

require_once '../Editor/Libraries/domit/xml_domit_include.php';
$fix = Request::getBoolean('fix');

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../"/>'.
'<interface background="Window">'.
'<text align="center" top="15" bottom="15" xmlns="uri:Text">'.
'<strong>Kontrol af mappestruktur</strong>'.
'<break/>'.
'<small>Her kontrolleres om alle specielle mapper findes og om systemet har de korrekte rettigheder til dem.'.
'</small>'.
'</text>'.
'<group size="Small" xmlns="uri:Button" align="center" bottom="5">'.
'<button title="Opdater" link="Folders.php"/>'.
'<button title="Forsøg at løse problemer" link="Folders.php?fix=true"/>'.
'</group>'.
'<list width="100%" xmlns="uri:List">'.
'<content>'.
'<headergroup>'.
'<header title="Mappe" width="45%"/>'.
'<header title="Rettigheder" width="10%" align="center"/>'.
'<header title="Problemer" width="35%"/>'.
'</headergroup>';
$result = checkFolders($fix);
foreach ($result as $path => $folder) {
	$gui.=
	'<row>'.
	'<cell><icon icon="Element/Folder"/><text>'.StringUtils::escapeXML($path).'</text></cell>'.
	'<cell>'.$folder['permissions'].'</cell>'.
	'<cell>'.($folder['problem'] ? '<status type="Error"/><text>'.StringUtils::escapeXML($folder['problem']).'</text>' : '').'</cell>'.
	'</row>';
}
$gui.=
'</content>'.
'</list>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Form","List","Text","Button");
writeGui($xwg_skin,$elements,$gui);


function checkFolders($fix=false) {
	global $basePath;
	error_reporting(E_ALL ^ E_WARNING);
	$output = array();
	$parsed = array("files","images","local/cache/images","local/cache/urls","local/cache/temp");
	asort($parsed);
	$count = count($parsed);
	foreach ($parsed as $path) {
		$checked = array('problem' => false, 'permissions' => 'ukendt', 'owner' => 'ukendt', 'group' => 'ukendt');
		$fullPath = $basePath.$path;
		if (file_exists($fullPath)) {
			if (!is_writable($fullPath)) {
				$checked['problem'] = 'Er ikke skrivbar';
				if ($fix) {
					@chmod($fullPath,0777);
				}
			}
			$checked['permissions'] = parsePermissions(fileperms($fullPath));
		} else {
			if ($fix) {
				if (!mkdir($fullPath)) {
					$checked['problem'] = 'Kunne ikke oprettes';
				}
			} else {
				$checked['problem'] = 'Findes ikke';
			}
		}
		$output[$path] = $checked;
	}
	ksort($output);
	return $output;
}

function parsePermissions($perms) {
	$info = '';
	$info .= (($perms & 0x0100) ? 'r' : '-');
	$info .= (($perms & 0x0080) ? 'w' : '-');
	$info .= (($perms & 0x0040) ?
            (($perms & 0x0800) ? 's' : 'x' ) :
            (($perms & 0x0800) ? 'S' : '-'));

	// Group
	$info .= (($perms & 0x0020) ? 'r' : '-');
	$info .= (($perms & 0x0010) ? 'w' : '-');
	$info .= (($perms & 0x0008) ?
            (($perms & 0x0400) ? 's' : 'x' ) :
            (($perms & 0x0400) ? 'S' : '-'));

	// World
	$info .= (($perms & 0x0004) ? 'r' : '-');
	$info .= (($perms & 0x0002) ? 'w' : '-');
	$info .= (($perms & 0x0001) ?
            (($perms & 0x0200) ? 't' : 'x' ) :
            (($perms & 0x0200) ? 'T' : '-'));
	return $info;
}
?>