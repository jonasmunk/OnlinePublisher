<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/In2iGui.php';


$gui='
<gui xmlns="uri:In2iGui" title="Vis ændringer" padding="5">
	<iframe source="Editor.php" name="EditorFrame" id="Editor"/>
</gui>';

In2iGui::render($gui);
exit;

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<area xmlns="uri:Area" width="100%" height="100%" margin="5">'.
'<content>'.
'<iframe xmlns="uri:Frame" source="Editor.php" name="Editor" object="EditorFrame"/>'.
'</content>'.
'</area>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Area","Frame");
writeGui($xwg_skin,$elements,$gui);
?>