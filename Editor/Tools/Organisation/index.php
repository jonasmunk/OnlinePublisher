<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';

$action = Request::getString('action');

if ($action=='newperson') {
	$right = 'NewPerson.php';
}
else {
	$right = 'Library.php';
}

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<layout xmlns="uri:Layout" width="100%" height="100%" spacing="10">'.
'<row><cell width="250">'.
'<iframe xmlns="uri:Frame" source="Left.php" name="Left"/>'.
'</cell><cell>'.
'<iframe xmlns="uri:Frame" source="'.$right.'" name="Right"/>'.
'</cell></row>'.
'</layout>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Layout","Frame");
writeGui($xwg_skin,$elements,$gui);
?>