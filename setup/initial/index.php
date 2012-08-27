<?php
if (file_exists('../../Config/Setup.php')) {
	session_write_close();
	header('Location: ../../');
	exit;
}

require_once("inc.php");


if (!function_exists('xslt_create') && !class_exists('xsltProcessor')) {
	Response::internalServerError('No XSLT processor');
	exit;
}

$canWrite = is_dir($basePath."Config/") && is_writable($basePath."Config/");

$gui='
<gui xmlns="uri:hui" padding="10">
	<controller source="controller.js"/>
	<box width="500" top="30" padding="10" title="Initial setup">
		<space left="10" right="10" top="5" bottom="10">
		<text>
			<p>The configuration file "Config/Setup.php" was not found, this will help you create it...</p>
			'.($canWrite
				? '<p>It looks like we can create the file for you.</p>'
				: '<p>It looks like we cannot wite the file so you have to create is yourself</p>'
			).'
		</text>
		</space>
		<formula name="formula">
			<fields>
				<field label="Base address:">
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
			</fields>
		</formula>
		<field label="Configuration file:" hint="Put the text above into the file: Config/Setup.php">
			<code-input name="preview"/>
		</field>
		<buttons align="right" top="20">
			<button title="Test database" name="test"/>
			'.($canWrite 
			? '<button title="Create configuration file" name="save" highlighted="true"/>'
			: '').'
		</buttons>
	</box>
</gui>
';
In2iGui::render($gui);
exit;
?>