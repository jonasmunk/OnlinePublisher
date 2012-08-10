<?php
if (file_exists('../../Config/Setup.php')) {
	session_write_close();
	header('Location: ../../');
	exit;
}
$baseUrl = getBaseUrl();

require_once('../../Editor/Include/Public.php');

if (!function_exists('xslt_create') && !class_exists('xsltProcessor')) {
	Response::internalServerError('No XSLT processor');
	exit;
}

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
			<fields>
				<field label="Web address:">
					<text-input name="baseUrl" value="'.StringUtils::escapeXML($baseUrl).'"/>
				</field>
				<field label="Database host:">
					<text-input name="databaseHost" value="localhost"/>
				</field>
				<field label="Database name:">
					<text-input name="databaseName" value="onlinepublisher"/>
				</field>
				<field label="Database user:">
					<text-input name="databaseUser"/>
				</field>
				<field label="Database password:">
					<text-input name="databasePassword" secret="true"/>
				</field>
				<field label="Super user:">
					<text-input name="superUser"/>
				</field>
				<field label="Super password:">
					<text-input name="superPassword" secret="true"/>
				</field>
				<buttons>
					<button title="Test database" name="test"/>
					<button title="OK" name="save" highlighted="true"/>
				</buttons>
			</fields>
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