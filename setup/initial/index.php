<?
require_once('../../Editor/Include/Functions.php');
require_once('../../Editor/Include/XmlWebGui.php');

if (file_exists('../../Config/Setup.php')) {
	redirect('../');
	exit;
}
$basePath = getBasePath();
$baseUrl = getBaseUrl();

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



function getBasePath() {
	$trans = $_SERVER['PATH_TRANSLATED'];
	$split = split("/",$trans);
	$sliced = array_slice($split,0,-3);
	$basePath = implode("/",$sliced);
	return $basePath."/";
}

function getBaseUrl() {
	$trans = $_SERVER['SCRIPT_URI'];
	$split = split("/",$trans);
	$sliced = array_slice($split,0,-3);
	$basePath = implode("/",$sliced);
	return $basePath."/";
}
?>