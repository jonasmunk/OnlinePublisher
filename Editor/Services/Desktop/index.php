<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Desktop
 */
require_once '../../Include/Private.php';

$gui='
<gui xmlns="uri:hui" title="Desktop" background="white">
	<css url="style.css"/>
	<controller name="controller" source="controller.js"/>
	
	<div id="intro">Bliss</div>
	
	<div id="username" class="auth">
		<label>User:</label>
		<input/>
	</div>
	
</gui>';

In2iGui::render($gui);
?>