<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');
require_once 'ImageChooserController.php';

$value = ImageChooserController::getViewType();
if ($value=='group') {
	$value=ImageChooserController::getGroupId();
}

$sql = "select count(object_id) as `count` from image";
$row = Database::selectFirst($sql);
$totalCount = $row['count'];
$sql = "select count(object_id) as `count` from object,image left join imagegroup_image on imagegroup_image.image_id=image.object_id where object.id = image.object_id and imagegroup_image.imagegroup_id is null";
$row = Database::selectFirst($sql);
$noGroupCount = $row['count'];
$notUsedCount = ImageChooserController::getUnusedImagesCount();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<selection xmlns="uri:Selection" object="Selection" value="'.$value.'">'.
'<item icon="Tool/Images" title="Alle billeder" value="all" badge="'.$totalCount.'"/>'.
'<item icon="Basic/Time" title="Seneste billeder" value="lastadded"/>'.
'<title>Grupper</title>';
$sql="select object.id,object.title,object.note,count(image.object_id) as imagecount,sum(image.size) as totalsize from imagegroup, imagegroup_image, image,object  where imagegroup_image.imagegroup_id=imagegroup.object_id and imagegroup_image.image_id = image.object_id and object.id=imagegroup.object_id group by imagegroup.object_id union select object.id,object.title,object.note,'0','0' from object left join imagegroup_image on imagegroup_image.imagegroup_id=object.id where object.type='imagegroup' and imagegroup_image.image_id is null order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<item icon="Element/Album" title="'.encodeXML(StringUtils::shortenString($row['title'],16)).'" value="'.$row['id'].'" badge="'.$row['imagecount'].'"/>';
}
Database::free($result);

$gui.=
'<title>Oprydning</title>'.
'<item icon="Basic/Warning" title="Billeder ikke i gruppe" value="nogroup" badge="'.$noGroupCount.'"/>'.
'<item icon="Basic/Stop" title="Billeder ikke i brug" value="notused" badge="'.$notUsedCount.'"/>'.
'</selection>'.
'<script xmlns="uri:Script">
var delegate = {
    valueDidChange : function(event,obj) {
        var link = "";
        if (obj.getValue()=="all") {
            link="Icons.php?type=all";
        } else if (obj.getValue()=="nogroup") {
            link="Icons.php?type=nogroup";
        } else if (obj.getValue()=="notused") {
            link="Icons.php?type=notused";
        } else if (obj.getValue()=="lastadded") {
            link="Icons.php?type=lastadded";
        } else {
            link="Icons.php?group="+obj.getValue();
		}
		parent.frames["Images"].location.href = link;
    }
};
Selection.setDelegate(delegate);
</script>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Selection","Script");
writeGui($xwg_skin,$elements,$gui);
?>