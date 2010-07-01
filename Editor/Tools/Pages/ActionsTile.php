<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Templates.php';
require_once 'Functions.php';


$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<layout xmlns="uri:Layout" width="100%" height="100%"><row><cell padding="3" align="center" valign="middle">'.
'<group xmlns="uri:Icon" size="2" spacing="3" titles="right">'.
'<row>'.
'<icon title="Ny side" icon="Template/Generic" overlay="New" link="NewPageTemplate.php?reset=true" target="Right" help="Opret en ny side"/>'.
'</row>'.
//'<row>'.
//'<icon title="Nyt hierarki" icon="Element/Structure" overlay="New" link="NewPageTemplate.php" target="Right"/>'.
//'</row>'.
'<row>'.
'<icon title="Udgiv ændringer" icon="Basic/Internet" overlay="Upload" link="../../Services/Publish/?close=../../Tools/Pages/" target="Right" help="Udgiv ændringer foretaget på hjemmesiden"/>'.
'</row>'.
'</group>'.
'</cell></row></layout>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Icon","Text","Layout");
writeGui($xwg_skin,$elements,$gui);
?>