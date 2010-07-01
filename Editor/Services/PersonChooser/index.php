<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.PersonChooser
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<area xmlns="uri:Area" width="100%" height="100%">'.
'<toolbar xmlns="uri:Toolbar" align="left">'.
'<tool title="Luk" icon="Basic/Close" link="javascript: window.close()"/>'.
'</toolbar>'.
'<content>'.
'<iframe xmlns="uri:Frame" name="Persons" source="List.php"/>'.
'</content>'.
'</area>'.
'<script xmlns="uri:Script">
function selectPerson(id) {
	opener.Chooser.selectPerson(id);
	window.close();
}
</script>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Area","Frame","Toolbar","Script");
writeGui($xwg_skin,$elements,$gui);
?>