<?php
/**
 * This file displays the root of the interface
 *
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once 'ImagesController.php';

$action = requestGetText('action');

if ($action=='newimage') {
	$right = 'NewImage.php';
}
else {
	$type = ImagesController::getViewType();
	if ($type=='group') {
		$right = 'Group.php';
	} elseif ($type=='groups') {
		$right = 'Groups.php';
	} elseif ($type=='notused') {
		$right = 'NotUsed.php';
	} elseif ($type=='nogroup') {
		$right = 'NoGroup.php';
	} elseif ($type=='lastadded') {
		$right = 'LastAdded.php';
	} else {
		$right = 'Library.php';		
	}
}

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<layout xmlns="uri:Layout" width="100%" height="100%" spacing="10">'.
'<row><cell width="250">'.
'<area xmlns="uri:Area" width="100%" height="100%">'.
'<titlebar title="Oversigt"/>'.
'<content>'.
'<iframe xmlns="uri:Frame" source="Selection.php"/>'.
'</content>'.
'<bottom>'.
'<group xmlns="uri:Icon" size="1" spacing="5" titles="right">'.
'<row>'.
'<icon title="Tilføj billede" icon="Element/Image" overlay="New" link="NewImage.php" target="Right"/>'.
'<icon title="Ny gruppe" icon="Element/Album" overlay="New" link="NewGroup.php" target="Right"/>'.
'</row>'.
'</group>'.
'</bottom>'.
'</area>'.
'</cell><cell>'.
'<iframe xmlns="uri:Frame" source="'.$right.'" name="Right"/>'.
'</cell></row>'.
'</layout>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Layout","Area","Frame","Icon");
writeGui($xwg_skin,$elements,$gui);
?>