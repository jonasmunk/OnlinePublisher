<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Calendar
 */
require_once '../../Include/Private.php';

$gui='
<frames xmlns="uri:hui">
	<frame source="Toolbar.php?id='.Request::getId().'" scrolling="false" name="Toolbar"/>
	<frame source="Editor.php?id='.Request::getId().'" name="Frame"/>
	<script>
		if (window.parent!=window) {
			window.parent.baseController.changeSelection("service:edit");
		}
	</script>
</frames>';

In2iGui::render($gui);
?>