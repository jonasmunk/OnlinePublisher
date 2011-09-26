<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Utilities/StringUtils.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<selection xmlns="uri:Selection" object="Selection" value="overview">'.
'<item title="Oversigt" icon="Web/Page" value="overview"/>'.
'<item title="Milepæle" icon="Basic/Time" value="milestones"/>'.
'<title>Projekter</title>'.
'</selection>'.
'<hierarchy xmlns="uri:Hierarchy" persistence="true" selection="" unique="tools-projects-hierarchy">'.
projectSpider(0).
'</hierarchy>'.
'<refresh xmlns="uri:Script" source="HierarchyUpdateCheck.php" interval="3000"/>'.
'<script xmlns="uri:Script" source="js/Overview.js"/>'.
'</interface>'.
'</xmlwebgui>';


$elements = array("Hierarchy","Script","Selection");
writeGui($xwg_skin,$elements,$gui);

function projectSpider($parent) {
    $gui='';
    $sql="SELECT object.id,object.title FROM project,object WHERE object.id=project.object_id and project.parent_project_id=".$parent." order by title";
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
    	$gui.='<element icon="Tool/Knowledgebase" title="'.StringUtils::escapeXML(StringUtils::shortenString($row['title'],20)).'" link="Project.php?id='.$row['id'].'" target="Right">'.
    	projectSpider($row['id']).
    	'</element>';
    }
    Database::free($result);
    return $gui;
}
?>