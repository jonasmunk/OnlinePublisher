<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Statistics
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Session.php';

require_once 'Functions.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui">'.
'<configuration path="../../../"/>'.
'<interface>'.
'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
'<content>';

$gui.=
'<headergroup>'.
'<header title="Side" type="number" width="50%"/>'.
'<header title="Graf" type="number" align="right" width="10%"/>'.
'<header title="Hits" type="number" align="right" width="10%"/>'.
'<header title="%" type="number" align="right" width="10%"/>'.
'<header title="Sessioner" type="number" align="right" width="10%"/>'.
'<header title="Adresser" type="number" align="right" width="10%"/>'.
'</headergroup>';

$total=getTotalCount('page');

$sql=buildPagesSql();
$max = findMaxHit($sql);
$result = Database::select($sql);	
while($row = Database::next($result)) {
	$gui.=
	'<row>'.
	'<cell>'.encodeXML($row['title']).'</cell>'.
	'<cell index="'.$row['hits'].'"><progress value="'.round($row['hits']/$max*100).'"/></cell>'.
	'<cell>'.$row['hits'].'</cell>'.
	'<cell index="'.$row['hits'].'">'.round($row['hits']/$total*100,1).'</cell>'.
	'<cell>'.$row['sessions'].'</cell>'.
	'<cell>'.$row['ips'].'</cell>'.
	'</row>';
}
Database::free($result);


$gui.=
'</content>'.
'</list>'.
'</interface>'.
'</xmlwebgui>';


$elements = array("List");

writeGui($xwg_skin,$elements,$gui);

?>