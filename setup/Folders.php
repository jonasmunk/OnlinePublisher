<?php
/**
 * @package OnlinePublisher
 * @subpackage Setup
 */

require_once '../Config/Setup.php';
require_once '../Editor/Include/Functions.php';
require_once '../Editor/Include/XmlWebGui.php';
require_once 'Functions.php';
require_once 'Security.php';

require_once '../Editor/Libraries/domit/xml_domit_include.php';
$fix = requestGetBoolean('fix');

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
'<header title="Forventet skrivbar" width="10%" align="center"/>'.
'<header title="Rettigheder" width="10%" align="center"/>'.
'<header title="Problemer" width="35%"/>'.
'</headergroup>';
$result = checkFolders($fix);
foreach ($result as $path => $folder) {
	$gui.=
	'<row>'.
	'<cell><icon icon="Element/Folder"/><text>'.encodeXML($path).'</text></cell>'.
	'<cell>'.($folder['writable'] ? '<status type="Finished"/>' : '<status type="Stopped"/>').'</cell>'.
	'<cell>'.$folder['permissions'].' ('.$folder['owner'].'/'.$folder['group'].')</cell>'.
	'<cell>'.($folder['problem'] ? '<status type="Error"/><text>'.encodeXML($folder['problem']).'</text>' : '').'</cell>'.
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
	$parsed = parseFolders();
	asort($parsed);
	$count = count($parsed);
	foreach ($parsed as $path => $writable) {
		$checked = array('writable' => $writable, 'problem' => false, 'permissions' => 'ukendt', 'owner' => 'ukendt', 'group' => 'ukendt');
		$fullPath = $basePath.$path;
		if (file_exists($fullPath)) {
			if ($writable && !is_writable($fullPath)) {
				$checked['problem'] = 'Er ikke skrivbar';
				if ($fix) {
					@chmod($fullPath,0777);
				}
			} elseif (!$writable && is_writable($fullPath)) {
				$checked['problem'] = 'Er skrivbar';
			}
			$checked['permissions'] = parsePermissions(fileperms($fullPath));
			$checked['owner'] = resolveOwner($fullPath);
			$checked['group'] = resolveGroup($fullPath);
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

function parseFolders() {
	global $basePath;
	$parsed = array();
	$file = $basePath."Editor/Info/Folders.xml";
	if (file_exists($file)) {
		$doc =& new DOMIT_Document();
		if ($doc->loadXML($file)) {
			folderIterator($doc->documentElement,$parsed);
		}
		else {
			error_log('checkFolders: '.$doc->getErrorString());
		}
	}
	else {
		error_log('The folder info file does not exist!');
	}
	return $parsed;
}

function folderIterator(&$node,&$parsed,$path='') {
	if ($nodes =& $node->selectNodes('folder')) {
		$num = $nodes->getLength();
		for ($i=0;$i<$num;$i++) {
			$folder = $nodes->item($i);
			$name = $folder->getAttribute('name');
			$newPath = appendWordToString($path,$name,'/');
			if ($folder->getAttribute('write')==='true') {
				$parsed[$newPath] = true;
			} else {
				$parsed[$newPath] = false;
			}
			folderIterator($folder,$parsed,$newPath);
		}
	}
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

function resolveOwner($fullPath) {
	$name = '';
	if ($id = fileowner($fullPath)) {
		if ($info = posix_getpwuid($id)) {
			$name = $info['name'];
		}
	}
	return $name;
}

function resolveGroup($fullPath) {
	$name = '';
	if ($id = filegroup($fullPath)) {
		if ($info = posix_getgrgid($id)) {
			$name = $info['name'];
		}
	}
	return $name;
}
?>