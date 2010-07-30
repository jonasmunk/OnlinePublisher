<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Weblog
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';

$gui='
<frames xmlns="uri:In2iGui">
	<frame source="Toolbar.php" scrolling="false" name="Toolbar"/>
	<frame source="Editor.php" name="Frame"/>
</frames>';

In2iGui::render($gui);
?>