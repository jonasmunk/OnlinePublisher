<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Utilities/StringUtils.php';

$freeText = InternalSession::getToolSessionVar('pages','freeTextSearch');

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<form xmlns="uri:Form" action="PagesFrame.php" method="post" target="Right" focus="freetext">'.
'<group size="Large" badgeplacement="above">'.
'<textfield badge="Fritekst" name="freetext">'.StringUtils::escapeXML($freeText).'</textfield>'.
'<buttongroup size="Large">'.
'<button title="Søg" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Form");
writeGui($xwg_skin,$elements,$gui);
?>