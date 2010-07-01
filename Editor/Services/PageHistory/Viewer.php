<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.PageHistory
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Templates.php';
require_once '../../Classes/UserInterface.php';

$close = "index.php";
$id = requestGetNumber('id');
$sql = "select UNIX_TIMESTAMP(page_history.time) as time,page_id from page_history where id=".$id;
$row = Database::selectFirst($sql);
$sql = "select id from page_history where page_id=".$row['page_id']." and time<".sqlTimestamp($row['time'])." order by time desc limit 1";
$previous = Database::selectFirst($sql);
$sql = "select id from page_history where page_id=".$row['page_id']." and time>".sqlTimestamp($row['time'])." order by time asc limit 1";
$next = Database::selectFirst($sql);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%" align="center" margin="10">'.
'<titlebar title="'.UserInterface::presentDateTime($row['time']).'" icon="Basic/Time">'.
'<close link="'.$close.'" help="Luk vinduet"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar">'.
'<tool title="Luk" icon="Basic/Close" link="'.$close.'"/>'.
'<divider/>'.
($previous ? 
'<direction direction="Left" title="Forrige" link="Viewer.php?id='.$previous['id'].'"/>'
: '<direction direction="Left" title="Forrige" style="disabled"/>').
($next ? 
'<direction direction="Right" title="Næste" link="Viewer.php?id='.$next['id'].'"/>'
: '<direction direction="Right" title="Næste" style="disabled"/>').
'<divider/>'.
'<tool title="Gendan version" icon="Basic/Refresh" link="Reconstruct.php?id='.$id.'"/>'.
'</toolbar>'.
'<content>'.
'<iframe source="../Preview/viewer/?history='.$id.'" xmlns="uri:Frame"/>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Frame");
writeGui($xwg_skin,$elements,$gui);
?>