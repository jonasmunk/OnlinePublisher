<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';

$cats = array(
		array("Redigering","edit"),
		array("Analyse","analyse"),
		array("Opsætning","setup")
	);
$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>';
foreach ($cats as $cat) {
	$tools = InternalSession::getToolsByCategory($cat[1]);
	if (count($tools)>0) {
		$gui.=
		'<text xmlns="uri:Text" top="10" left="10">'.
		'<strong>'.$cat[0].':</strong>'.
		'</text>'.
		'<group size="2" spacing="10" width="100%" cellwidth="20%" padding="0" titles="right" xmlns="uri:Icon">';

		$count=0;
		foreach ($tools as $value) {
			$gui.='<row>'.
			'<icon icon="'.$value['icon'].'" title="'.$value['name'].'" link="../../Tools/'.$value['unique'].'/" target="Desktop" description="'.$value['description'].'"/>'.
			'</row>';
		}

		$gui.='</group>';
	}
}
$gui.=
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Icon","Text");
writeGui($xwg_skin,$elements,$gui);

?>