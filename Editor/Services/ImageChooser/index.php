<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.ImageChooser
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/InternalSession.php';

$group = InternalSession::getRequestServiceSessionVar('imagechooser','group','group',0);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<meta><title>Vælg billede</title></meta>'.
'<interface background="Desktop">'.
'<area xmlns="uri:Area" width="100%" height="100%">'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Luk" icon="Basic/Close" link="javascript: window.close()"/>'.
'<flexible/>'.
'<tool title="Upload billede" icon="Element/Image" overlay="Upload" link="NewImage.php" target="Images"/>'.
'<flexible/>'.
'</toolbar>'.
'<content>'.
'<layout xmlns="uri:Layout" width="100%" height="100%" spacing="0" padding="0">'.
'<row><cell width="240" border-right="1">'.
'<iframe xmlns="uri:Frame" name="Selection" source="Selection.php"/>'.
'</cell><cell>'.
'<iframe xmlns="uri:Frame" name="Images" source="Icons.php"/>'.
'</cell></row></layout>'.
'</content>'.
'</area>'.
'<script xmlns="uri:Script">
function selectImage(id) {
	opener.Chooser.selectImage(id);
	//window.close();
}
</script>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Area","Frame","Toolbar","Script","Layout");
writeGui($xwg_skin,$elements,$gui);
?>