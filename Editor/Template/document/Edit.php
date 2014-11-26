<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../Include/Private.php';

$gui='
<frames xmlns="uri:hui">
	<frame source="Toolbar.php" scrolling="false" name="Toolbar"/>
	<frame source="Editor.php" name="Frame"/>
	<script>
		hui.ui.tellContainers("changeSelection","service:edit");
	</script>
</frames>';

UI::render($gui);
?>
