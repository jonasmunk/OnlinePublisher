<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Utilities/StringUtils.php';

require_once 'Functions.php';

$group = getPersonGroup();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<group xmlns="uri:Icon" width="100%" spacing="12" size="2" cellwidth="17%" >'.
'<row>';

$counter=0;


	$sql="select id, title from personrole, object where personrole.object_id = object.id";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$counter++;
	if ($counter==7) {
		$gui.='</row><row>';
		$counter=1;
	}
	$gui.=
	'<icon title="'.StringUtils::escapeXML($row['title']).'" icon="Role/Administrator" link="RoleProperties.php?id='.$row['id'].'" target="_parent"/>';
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