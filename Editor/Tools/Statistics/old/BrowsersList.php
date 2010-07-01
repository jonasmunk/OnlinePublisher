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

$mode = getRequestToolSessionVar('statistics','browsers.mode','mode','apps');


$gui='<xmlwebgui xmlns="uri:XmlWebGui">'.
'<configuration path="../../../"/>'.
'<interface>'.
'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
'<tabgroup align="left">'.
'<tab title="Teknologier"'.($mode=='techs' ? ' style="Hilited"' : ' link="BrowsersList.php?mode=techs"').'/>'.
'<tab title="Teknologiversioner"'.($mode=='techversions' ? ' style="Hilited"' : ' link="BrowsersList.php?mode=techversions"').'/>'.
'<tab title="Applikationer"'.($mode=='apps' ? ' style="Hilited"' : ' link="BrowsersList.php?mode=apps"').'/>'.
'<tab title="Versioner"'.($mode=='versions' ? ' style="Hilited"' : ' link="BrowsersList.php?mode=versions"').'/>'.
'<tab title="Detaljeret"'.($mode=='details' ? ' style="Hilited"' : ' link="BrowsersList.php?mode=details"').'/>'.
'</tabgroup>'.
'<content>'.
'<headergroup>'.	
'<header title="Browser" width="50%"/>'.
'<header title="Graf" type="number" width="10%" align="right"/>'.
'<header title="Hits" type="number" width="10%" align="right"/>'.
'<header title="%" type="number" width="10%" align="right"/>'.
'<header title="Sessioner" type="number" width="10%" align="right"/>'.
'<header title="Adresser" type="number" width="10%" align="right"/>'.
'</headergroup>';

$build = buildBrowserData($mode);
$data = $build['data'];
$total = $build['total'];
$max = 0;
foreach ($data as $browser => $info) {
	if ($info['hits']>$max) $max = $info['hits'];
}

foreach ($data as $browser => $info) {
	$gui.=
	'<row>'.
	'<cell>'.encodeXML($browser).'</cell>'.
	'<cell index="'.$info['hits'].'"><progress value="'.round($info['hits']/$max*100).'"/></cell>'.
	'<cell>'.$info['hits'].'</cell>'.
	'<cell index="'.$info['hits'].'">'.round($info['hits']/$total*100,1).'</cell>'.
	'<cell>'.$info['sessions'].'</cell>'.
	'<cell>'.$info['ips'].'</cell>'.
	'</row>';
}
$gui.=
'</content>'.
'</list>'.

'</interface>'.

'</xmlwebgui>';


$elements = array("List");

writeGui($xwg_skin,$elements,$gui);
?>