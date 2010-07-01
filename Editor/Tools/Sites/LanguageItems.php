<?php
/**
 * @package OnlinePublisher
 * @subpackage Sites
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Templates.php';
require_once '../../Include/International.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Request.php';

$sql="select language,count(id) as count from page group by language order by language";
$result = Database::select($sql);
$items = array();
while ($row = Database::next($result)) {
	if ($row['language']==null || count($row['language'])==0) {
		$icon = 'monochrome/round_question';
		$language = 'Intet sprog';
	} else {
		$icon = $languageIcons[$row['language']];
		$language = $languages[$row['language']];
	}
	if (array_key_exists($row['language'],$items)) {
		$items[$row['language']]['count']+=$row['count'];
	} else {
		$items[$row['language']] = array('icon'=>$icon,'language'=>$language,'count'=>$row['count']);
	}
}
Database::free($result);


header('Content-Type: text/xml;');
echo '<?xml version="1.0"?>
<items>';
foreach ($items as $key => $value) {
	echo '<item icon="'.$value['icon'].'" value="'.In2iGui::escape($key).'" title="'.In2iGui::escape($value['language']).'" kind="language" badge="'.$value['count'].'"/>';
}
echo '</items>';
?>