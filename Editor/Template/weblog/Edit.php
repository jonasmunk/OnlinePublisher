<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Weblog
 */
require_once '../../Include/Private.php';

$gui='
<frames xmlns="uri:hui">
	<frame source="Toolbar.php" scrolling="false" name="Toolbar"/>
	<frame source="Editor.php" name="Frame"/>
	<script>
		if (window.parent!=window) {
			window.parent.baseController.changeSelection("service:edit");
		}
	</script>
</frames>';

In2iGui::render($gui);
?>