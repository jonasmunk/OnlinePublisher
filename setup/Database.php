<?php
/**
 * @package OnlinePublisher
 * @subpackage Setup
 */

require_once '../Config/Setup.php';
require_once '../Editor/Include/Public.php';
require_once '../Editor/Info/Database.php';
require_once '../Editor/Classes/DatabaseUtil.php';
require_once '../Editor/Include/XmlWebGui.php';

require_once 'Functions.php';
require_once 'Security.php';


$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../"/>'.
'<interface background="Window">'.
'<area width="100%" height="100%" xmlns="uri:Area">'.
'<tabgroup size="Large">'.
'<tab title="Opdatering" style="Hilited"/>'.
'<tab title="Detaljer" link="DatabaseDetails.php"/>'.
'</tabgroup>'.
'<content padding="20" align="center">';
$begin = time();
$correct = DatabaseUtil::isCorrect() && DatabaseUtil::isUpToDate();
$end = time();
if ($correct) {
	$gui.='<message icon="Message" width="60%" xmlns="uri:Message">'.
	'<title>Databasen er opsat korrekt</title>'.
	'<description>Databasens struktur svarer til hvad systemet forventer og den behøver ikke at blive opdateret.</description>'.
	'<description>Kontrollen tog '.($end-$begin).' sekund(er).</description>'.
	'</message>';
} else {
	$gui.='<message icon="Error" width="70%" xmlns="uri:Message">'.
	'<title>Databasen er ikke opsat korrekt</title>'.
	'<description>Databasens struktur svarer ikke til hvad systemet forventer og skal derfor opdateres.</description>'.
	'<description>Klik på knappen "Opdater..." for at lade system opdatere databasen.</description>'.
	'<description>Hvis ikke databasen opdateres vil systemet være ustabilt!</description>'.
	'<buttongroup size="Large">'.
	'<button title="Opdater..." link="DatabaseUpdater.php" style="Hilited"/>'.
	'</buttongroup>'.
	'</message>';
}
$gui.=
'</content>'.
'</area>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Area","Button","Message");
writeGui($xwg_skin,$elements,$gui);
?>