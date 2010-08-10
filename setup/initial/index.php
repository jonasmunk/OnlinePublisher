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
<gui xmlns="uri:In2iGui" padding="10">
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
<gui xmlns="uri:In2iGui" padding="10">
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

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../"/>'.
'<meta><title>OnlinePublisher</title></meta>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="600" align="center" top="30">'.
'<titlebar title="Grundlæggende opsætning"/>'.
'<content background="true" padding="10">'.
'<form xmlns="uri:Form" action="Build.php" method="post" submit="true" focus="basePath" name="Formula">'.
'<group size="Large" badgewidth="20%">'.
'<box title="Stier">'.
'<textfield badge="Filsystem:" name="basePath" object="BasePath">'.$basePath.'</textfield>'.
'<textfield badge="Internet:" name="baseUrl" object="BaseUrl">'.$baseUrl.'</textfield>'.
'</box>'.
'<space/>'.
'<box title="Database">'.
'<textfield badge="Adresse:" name="databaseHost" object="DatabaseHost">localhost</textfield>'.
'<textfield badge="Brugernavn:" name="databaseUser" object="DatabaseUser"></textfield>'.
'<textfield badge="Kodeord:" name="databasePassword" object="DatabasePassword"></textfield>'.
'<textfield badge="Database:" name="database" object="Database"></textfield>'.
'</box>'.
'<space/>'.
'<box title="Superbruger">'.
'<textfield badge="Brugernavn:" name="superUsername" object="SuperUsername"/>'.
'<textfield badge="Kodeord:" name="superPassword" object="SuperPassword"/>'.
'</box>'.
'<buttongroup size="Large">'.
'<button title="Gem" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';
$elements = array("Window","Form","Script");
writeGui("In2ition",$elements,$gui);

function getBaseUrl() {
	return 'http://'.$_SERVER['SERVER_NAME'].strstr($_SERVER['REQUEST_URI'],'setup',true);
}
?>