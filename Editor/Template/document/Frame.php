<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';

$gui='
<gui xmlns="uri:hui" title="Vis ændringer" padding="5">
	<iframe source="Editor.php" name="EditorFrame" id="Editor" border="true"/>
</gui>';

In2iGui::render($gui);
?>