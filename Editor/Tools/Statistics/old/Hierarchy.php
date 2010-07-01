<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Statistics
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once 'Functions.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<hierarchy xmlns="uri:Hierarchy" persistence="true" unique="tools-statistics-month-hierarchy">'.

'<element icon="Tool/Statistics" title="Besøgende" link="Visitors.php" target="Right">'.
'<element icon="Tool/Statistics" title="År" link="Visitors.php?mode=years" target="Right"/>'.
'<element icon="Tool/Statistics" title="Måneder" link="Visitors.php?mode=months" target="Right"/>'.
'<element icon="Tool/Statistics" title="Uger" link="Visitors.php?mode=weeks" target="Right"/>'.
'<element icon="Tool/Statistics" title="Dage" link="Visitors.php?mode=days" target="Right"/>'.
'<element icon="Tool/Statistics" title="Ugedage" link="Visitors.php?mode=daysOfWeek" target="Right"/>'.
'<element icon="Tool/Statistics" title="Dage om måneden" link="Visitors.php?mode=daysOfMonth" target="Right"/>'.
'<element icon="Tool/Statistics" title="Timer i døgnet" link="Visitors.php?mode=hours" target="Right"/>'.
'</element>'.

'<element icon="Tool/Statistics" title="Lande" link="Countries.php" target="Right"/>'.
'<element icon="Tool/Statistics" title="Browsere" link="Browsers.php" target="Right">'.
'<element icon="Tool/Statistics" title="Teknologier" link="Browsers.php?mode=techs" target="Right"/>'.
'<element icon="Tool/Statistics" title="Applikationer" link="Browsers.php?mode=apps" target="Right"/>'.
'<element icon="Tool/Statistics" title="Versioner" link="Browsers.php?mode=versions" target="Right"/>'.
'<element icon="Tool/Statistics" title="Detaljeret" link="Browsers.php?mode=details" target="Right"/>'.
'</element>'.
'<element icon="Tool/Statistics" title="Sessioner" link="Sessions.php" target="Right"/>'.
'<element icon="Tool/Statistics" title="Sider" link="Pages.php" target="Right"/>'.
'<element icon="Tool/Statistics" title="Filer" link="Files.php" target="Right"/>'.
//'<element icon="Element/Folder" title="Gammelt">'.
//'<element icon="Tool/Statistics" title="Besøgs statistik" link="UserStats.php?type=month" target="Right"/>'.
//'<element icon="Tool/Statistics" title="Bruger statistik" link="UserAnalysisStats.php?type=0" target="Right"/>'.
//'<element icon="Tool/Statistics" title="Lande statistik" link="CountryStats.php?type=month" target="Right"/>'.
//'<element icon="Tool/Statistics" title="Side statistik" link="PageStats.php?type=month" target="Right"/>'.
//'<element icon="Tool/Statistics" title="Browser statistik" link="BrowserStats.php?type=month" target="Right"/>'.
//'<element icon="Tool/Statistics" title="Reference statistik" link="ReferStats.php?type=month" target="Right"/>'.
//'<element icon="Tool/Statistics" title="Time statistik" link="HourStats.php?type=month" target="Right"/>'.
//'<element icon="Tool/Statistics" title="Server statistik" link="HostStats.php?type=month" target="Right"/>'.
//'</element>'.

'</hierarchy>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Hierarchy","Script");
writeGui($xwg_skin,$elements,$gui);
?>