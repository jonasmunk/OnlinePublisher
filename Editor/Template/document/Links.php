<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../Include/Private.php';

$gui='
<gui xmlns="uri:hui" title="Links" padding="30">
	<controller source="js/Links.js"/>
	<source name="linksSource" url="data/LinksList.php"/>
	<box width="700" title="Links">
		<toolbar>
			<icon icon="common/close" text="{Close;da:Luk}" name="close"/>
		</toolbar>
		<list name="list" source="linksSource"/>
	</box>
</gui>';

In2iGui::render($gui);
?>