<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../Include/Private.php';

$gui='
<gui xmlns="uri:hui" title="Vis ændringer" padding="5">
	<iframe source="Editor.php" name="EditorFrame" id="Editor" border="true"/>
</gui>';

In2iGui::render($gui);
?>