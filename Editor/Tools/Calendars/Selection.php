<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once 'CalendarsController.php';

$value = CalendarsController::getSelection();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<selection xmlns="uri:Selection" object="Selection" value="'.$value.'">'.
'<item icon="Tool/Calendar" title="Oversigt" value="overview"/>'.
'<title>Kalendere</title>';
$sql="select title,id from object where type='calendar' order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<item icon="Tool/Calendar" title="'.encodeXML(shortenString($row['title'],20)).'" value="calendar-'.$row['id'].'"/>';
}
Database::free($result);
$gui.=
'<title>Kilder</title>';
$sql="select title,id from object where type='calendarsource' order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<item icon="Basic/Internet" title="'.encodeXML(shortenString($row['title'],20)).'" value="source-'.$row['id'].'"/>';
}
Database::free($result);
$gui.=
'</selection>'.
'<refresh xmlns="uri:Script" source="SelectionUpdateCheck.php" interval="3000"/>'.
'<script xmlns="uri:Script">
var delegate = {
    valueDidChange : function(event,obj) {
        var link = "";
        if (obj.getValue()=="overview") {
            link="Overview.php";
        } else {
			var splitted = obj.getValue().split("-");
			if (splitted[0]=="source") { 
            	link="Source.php?id="+splitted[1]+"&amp;noupdate=true";
			} else if (splitted[0]=="calendar") {
            	link="Calendar.php?id="+splitted[1]+"&amp;noupdate=true";
			} 
		}
		parent.frames["Right"].location.href = link;
    }
};
Selection.setDelegate(delegate);
</script>'.
'</interface>'.
'</xmlwebgui>';


$elements = array("Selection","Script");
writeGui($xwg_skin,$elements,$gui);
?>