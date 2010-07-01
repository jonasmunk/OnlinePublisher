<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';
require_once '../../Classes/GuiUtils.php';

require_once 'Functions.php';

$group = getToolSessionVar('files','group');

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<group xmlns="uri:Icon" width="100%" spacing="6" size="2" cellwidth="20%" >'.
'<row>';


$counter=0;

if ($group>0) {
	$sql="select object.title,object.id,file.* from object,file,filegroup_file where file.object_id=object.id and filegroup_file.file_id=object.id and filegroup_file.filegroup_id=$group order by title";
}
else {
	$sql="SELECT object.title,object.id,file.* FROM file,object WHERE object.id=file.object_id order by title";
}
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$counter++;
	if ($counter==6) {
		$gui.='</row><row>';
		$counter=1;
	}
	$gui.=
	'<icon title="'.encodeXML(shortenString($row['title'],16)).'" icon="'.GuiUtils::getFileIcon($row['filename']).'" link="File.php?id='.$row['id'].'" target="_parent"/>';
}
Database::free($result);


$gui.=
'</row>'.
'</group>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Icon");
writeGui($xwg_skin,$elements,$gui);
?>