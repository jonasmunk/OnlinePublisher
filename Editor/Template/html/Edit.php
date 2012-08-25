<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.HTML
 */
require_once '../../Include/Private.php';

$gui='
<frames xmlns="uri:hui">
	<frame source="../Toolbar.php?id='.Request::getId().'&amp;title=HTML" scrolling="false" name="Toolbar"/>
	<frame source="Editor.php?id='.Request::getId().'" name="Frame"/>
	<script>
		hui.ui.tellContainers("changeSelection","service:edit");
	</script>
</frames>';

In2iGui::render($gui);
?>