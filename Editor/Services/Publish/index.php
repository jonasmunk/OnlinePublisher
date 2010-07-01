<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Publish
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Templates.php';
require_once '../../Classes/Object.php';

$close = requestGetText('close');

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" align="center" top="20">'.
'<titlebar title="Udgiv ændringer" icon="Basic/Internet">';
if ($close!='') {
	$gui.='<close link="'.$close.'" target="Desktop" help="Luk vinduet"/>';
}
$gui.=
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Udgiv" icon="Basic/Internet" overlay="Upload" link="javascript: document.forms[0].submit();" help="Udgiv ændringer af de valgt emner"/>'.
'</toolbar>'.
'<statusbar text="Vælg de emner de emner der skal udgives og klik på &#34;Udgiv&#34;"/>'.
'<content>'.
'<form xmlns="uri:Form" method="post" action="Publish.php" name="Form">'.
'<hidden name="close">'.encodeXML($close).'</hidden>'.
'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
'<content>'.
'<headergroup>'.
'<header align="center"/>'.
'<header title="Titel" width="60%"/>'.
'<header title="Type" width="40%"/>'.
'</headergroup>';

$templates = getTemplatesKeyed();

$sql="select page.id,page.title,template.unique from page,template where page.template_id=template.id and changed>published";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<row>'.
	'<cell><checkbox name="page[]" value="'.$row['id'].'" selected="true"/></cell>'.
	'<cell>'.
	'<icon size="1" icon="'.$templates[$row['unique']]['icon'].'"/>'.
	'<text>'.encodeXML($row['title']).'</text>'.
	'</cell>'.
	'<cell>Side</cell>'.
	'</row>';
}
Database::free($result);

$sql="select * from hierarchy where changed>published";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<row>'.
	'<cell><checkbox name="hierarchy[]" value="'.$row['id'].'" selected="true"/></cell>'.
	'<cell>'.
	'<icon size="1" icon="Element/Structure"/>'.
	'<text>'.encodeXML($row['name']).'</text>'.
	'</cell>'.
	'<cell>Hierarki</cell>'.
	'</row>';
}
Database::free($result);

$sql="select id from object where updated>published";
$result = Database::select($sql);
$ids = array();
while ($row = Database::next($result)) {
	$ids[] = $row['id'];
}
Database::free($result);

for ($i=0;$i<count($ids);$i++) {
    $object = Object::load($ids[$i]);
	if (!$object) {
		error_log('object with id: '.$ids[$i].' does not exists');
	} else {
		$gui.='<row>'.
		'<cell><checkbox name="object[]" value="'.$object->getId().'" selected="true"/></cell>'.
		'<cell>'.
		'<icon size="1" icon="'.$object->getIcon().'"/>'.
		'<text>'.encodeXML($object->getTitle()).'</text>'.
		'</cell>'.
		'<cell>'.encodeXML($object->getType()).'</cell>'.
		'</row>';
	}
}

$gui.=
'</content>'.
'</list>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","List","Form");
writeGui($xwg_skin,$elements,$gui);
?>