<?
if (file_exists('../../Config/Setup.php')) {
	session_write_close();
	header('Location: ../');
	exit;
}
$baseUrl = getBaseUrl();

require_once('../../Editor/Include/Public.php');
require_once('../../Editor/Classes/In2iGui.php');
require_once('../../Editor/Classes/Utilities/StringUtils.php');

if (!is_dir($basePath."Config/") || !is_writable($basePath."Config/")) {
$gui='
<gui xmlns="uri:hui" padding="10">
	<controller source="controller.js"/>
	<box width="400" top="30" variant="rounded">
		<space left="30" right="30" top="10" bottom="10">
		<text>
			<h>The configuration file does not exist and I cannot help create it</h>
			<p>The file should be located at Config/Setup.php, but I cannot write the file so you have to do it yourself.</p>
			<p>Good luck :-)</p>
		</text>
		</space>
	</box>
</gui>
';
In2iGui::render($gui);
exit;
}
$gui='
<gui xmlns="uri:hui" padding="10">
	<controller source="controller.js"/>
	<box width="500" top="30" padding="10" title="Initial setup">
		<space left="10" right="10" top="5" bottom="10">
		<text>
			<p>The configuration file "Config/Setup.php" was not found, this will help create it...</p>
		</text>
		</space>
		<formula name="formula">
			<group>
				<text name="baseUrl" label="Web address:" value="'.StringUtils::escapeXML($baseUrl).'"/>
				<text name="databaseHost" label="Database host:" value="localhost"/>
				<text name="databaseName" label="Database name:" value="onlinepublisher"/>
				<text name="databaseUser" label="Database user:"/>
				<text name="databasePassword" label="Database password:" secret="true"/>
				<text name="superUser" label="Super user:"/>
				<text name="superPassword" label="Super password:" secret="true"/>
				<buttons>
					<button title="Test database" name="test"/>
					<button title="OK" name="save" highlighted="true"/>
				</buttons>
			</group>
		</formula>
	</box>
</gui>
';
In2iGui::render($gui);
exit;

function getBaseUrl() {
	$uri = $_SERVER['REQUEST_URI'];
	$find = 'setup/initial/';
	$pos = strpos($uri,$find);
	return 'http://'.$_SERVER['SERVER_NAME'].'/'.substr($uri,0,$pos);
}
?>