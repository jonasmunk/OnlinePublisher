<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';

$gui='
<gui xmlns="uri:In2iGui" padding="10" title="Customers">
	<controller source="controller.js"/>
	<source name="testsSource" url="ListTests.php"/>
	<layout>
		<top>
		<toolbar>
			<!--divider/-->
		</toolbar>
		</top>
		<middle>
			<left>
				<overflow>
					<selection value="phpInfo" name="selector">
						<item icon="common/info" title="PHP info" value="phpInfo"/>
						<item icon="common/info" title="Session" value="session"/>
						<title>Tests</title>
						<items name="testSelection" source="testsSource"/>
					</selection>
				</overflow>
			</left>
			<center>
				<iframe source="PhpInfo.php" name="xyz"/>
			</center>
		</middle>
		<bottom/>
	</layout>
</gui>';
In2iGui::render($gui);
?>