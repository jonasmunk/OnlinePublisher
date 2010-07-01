<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';

$gui='
<gui xmlns="uri:In2iGui" title="Vis ændringer">
	<controller source="controller.js"/>
	<tabs small="true" below="true">
		<tab title="Vis ændringer" background="light">
			<toolbar>
				<icon icon="common/close" title="Luk" name="close"/>
				<divider/>
				<icon icon="common/edit" title="Rediger" name="edit"/>
				<icon icon="common/view" title="Vis udgivet" name="view"/>
				<icon icon="common/info" title="Egenskaber" name="properties"/>
				<icon icon="common/info" title="Egenskaber (beta)" name="beta"/>
				<divider/>
				<icon icon="common/internet" overlay="upload" title="Udgiv ændringer" name="publish"/>
			</toolbar>
		</tab>
	</tabs>
</gui>';

In2iGui::render($gui);
?>