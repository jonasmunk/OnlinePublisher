<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Design.php';
require_once '../../Classes/Object.php';
require_once '../../Classes/GuiUtils.php';

$designId = requestGetNumber('designId');
$design = Design::load($designId);
$key = requestGetText('key');
$info = $design->getParameterInfo($key);
$parameter = $design->getParameter($key);
$return = 'EditDesignParameters.php?id='.$designId;

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center" top="30">'.
'<titlebar title="Redigering af parameter">'.
'<close link="'.$return.'"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="UpdateDesignParameter.php" method="post" name="Formula">'.
'<hidden name="designId">'.$designId.'</hidden>'.
'<hidden name="type">'.$info['type'].'</hidden>'.
'<hidden name="key">'.$key.'</hidden>'.
'<group size="Large">'.
Design::buildParameterOptions($info,$parameter).
'<buttongroup size="Large">'.
'<button title="Annuller" link="'.$return.'"/>'.
'<button title="Opdater" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Form");
writeGui($xwg_skin,$elements,$gui);
?>