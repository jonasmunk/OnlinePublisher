<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once 'NewsController.php';

$value = NewsController::getViewType();
if ($value=='group') {
	$value=NewsController::getGroupId();
}

$sql = "select count(object_id) as `count` from news,object where object.id=news.object_id";
$row = Database::selectFirst($sql);
$totalCount = $row['count'];
$sql = "select count(object_id) as `count` from object,news left join newsgroup_news on newsgroup_news.news_id=news.object_id where object.id = news.object_id and newsgroup_news.newsgroup_id is null";
$row = Database::selectFirst($sql);
$noGroupCount = $row['count'];

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<selection xmlns="uri:Selection" object="Selection" value="'.$value.'">'.
'<item icon="Tool/News" title="Alle nyheder" value="all" badge="'.$totalCount.'"/>'.
'<item icon="Element/Folders" title="Alle grupper" value="groups"/>'.
'<title>Grupper</title>';
$sql="select object.id,object.title,object.note,count(news.object_id) as newscount from newsgroup, newsgroup_news, news,object  where newsgroup_news.newsgroup_id=newsgroup.object_id and newsgroup_news.news_id = news.object_id and object.id=newsgroup.object_id group by newsgroup.object_id union select object.id,object.title,object.note,'0' from object left join newsgroup_news on newsgroup_news.newsgroup_id=object.id where object.type='newsgroup' and newsgroup_news.news_id is null order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<item icon="Element/Folder" title="'.encodeXML(shortenString($row['title'],20)).'" value="'.$row['id'].'" badge="'.$row['newscount'].'"/>';
}
Database::free($result);

$gui.=
'</selection>'.
'<refresh xmlns="uri:Script" source="HierarchyUpdateCheck.php" interval="3000"/>'.
'<script xmlns="uri:Script">
var delegate = {
    valueDidChange : function(event,obj) {
        var link = "";
        if (obj.getValue()=="all") {
            link="Library.php?noupdate=true";
        } else if (obj.getValue()=="groups") {
            link="Groups.php?noupdate=true";
        } else {
            link="Group.php?id="+obj.getValue()+"&amp;noupdate=true";
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