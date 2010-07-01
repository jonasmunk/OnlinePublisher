<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once($basePath.'Editor/Classes/FileSystemUtil.php');


$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<hierarchy xmlns="uri:Hierarchy" persistence="true" unique="Tools.System.Hierarchy">'.
'<element icon="Basic/Build" title="Unit-tests">'.
'<element icon="Basic/Start" title="Kør alle" link="UnitTestsRunAll.php" target="Right"/>';
$folders = FileSystemUtil::listDirs($basePath.'Editor/Tests/');
foreach ($folders as $folder) {
	if ($folder=='CVS') continue;
	$gui.=
	'<element icon="Element/Folder" title="'.encodeXML($folder).'">';
	$files = FileSystemUtil::listFiles($basePath.'Editor/Tests/'.$folder.'/');
	foreach ($files as $file) {
		$gui.='<element icon="Basic/Start"'.
		' title="'.encodeXML($file).'"'.
		' link="UnitTestsRun.php?path='.encodeXML($folder).'/'.encodeXML($file).'"'.
		' target="Right"/>';
	}
	$gui.='</element>';
}

$gui.=
'</element>'.
'<element icon="Tool/Developer" title="PHP-info" link="PhpInfo.php" target="Right"/>'.
'<element icon="Basic/Time" title="Session" link="Session.php" target="Right"/>'.
'<element icon="File/css" title="Designs" link="Designs.php" target="Right"/>'.
'<element icon="Element/Folder" title="Skrammel" link="Junk/" target="Right"/>'.
'<element icon="Element/Folder" title="In2iGui" link="In2iGui/" target="Right"/>'.
'<element icon="Element/Folder" title="RSS" link="RSS.php" target="Right"/>'.
'<element icon="Element/Folder" title="vCal" link="VCal.php" target="Right"/>'.
'<element icon="Basic/World" title="Pear" link="Pear.php" target="Right"/>'.
'<element icon="Basic/World" title="Text" link="TextAnalysis.php" target="Right"/>'.
'</hierarchy>'.
'</interface>'.
'</xmlwebgui>';
$elements = array("Hierarchy","Script");
writeGui($xwg_skin,$elements,$gui);
?>