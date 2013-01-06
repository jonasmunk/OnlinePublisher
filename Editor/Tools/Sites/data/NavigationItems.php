<?php
/**
 * @package OnlinePublisher
 * @subpackage Sites
 */
require_once '../../../Include/Private.php';

$writer = new ItemsWriter();

$writer->startItems();

$writer->item(array(
	'title' => array('All pages','da'=>'Alle sider'),
	'icon' => 'common/page',
	'value' => 'all',
	'badge' => PageService::getTotalPageCount()
));
$writer->item(array(
	'title' => array('Latest','da'=>'Seneste'),
	'icon' => 'common/time',
	'value' => 'latest',
	'badge' => PageService::getLatestPageCount()
));

$writer->title(array('Hierarchies','da'=>'Hierarkier'));

$hierarchies = Hierarchy::search();

foreach ($hierarchies as $hierarchy) {
	$title = $hierarchy->getName();
	if ($hierarchy->getChanged()>$hierarchy->getPublished()) {
		$title.=' !';
	}
	$writer->startItem(array('icon'=>'common/hierarchy','kind'=>'hierarchy','value'=>$hierarchy->getId(),'title'=>$title));
	encodeLevel(0,$hierarchy->getId(),$writer);
	$writer->endItem();
}

$writer->item(array(
	'title' => array('No menu item','da'=>'Uden menupunkt'),
	'icon' => 'monochrome/nomenu',
	'value' => 'nomenu',
	'kind' => 'subset',
	'badge' => PageService::getNoItemPageCount()
));

function encodeLevel($parent,$hierarchyId,&$writer) {
   	$sql="select hierarchy_item.*,page.disabled,page.path,page.id as pageid from hierarchy_item".
    	" left join page on page.id = hierarchy_item.target_id and (hierarchy_item.target_type='page' or hierarchy_item.target_type='pageref')".
    	" where parent=".Database::int($parent).
    	" and hierarchy_id=".Database::int($hierarchyId).
    	" order by `index`";
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
		$icon = Hierarchy::getItemIcon($row['target_type']);
		if ($row['target_type']=='page' && !$row['pageid']) {
			$icon = "common/warning";
		}
		$writer->startItem(array('icon' => $icon, 'kind' => 'hierarchyItem', 'value' => $row['id'], 'title' => $row['title']));
		encodeLevel($row['id'],$hierarchyId,$writer);
		$writer->endItem();
	}
	Database::free($result);
}






$writer->title(array('Languages','da'=>'Sprog'));

$counts = PageService::getLanguageCounts();

foreach ($counts as $row) {
	$options = array('kind'=>'language');
	if ($row['language']==null || count($row['language'])==0) {
		$options['icon'] = 'monochrome/round_question';
		$options['title'] = array('No language', 'da'=>'Intet sprog');
	} else {
		$options['icon'] = GuiUtils::getLanguageIcon($row['language']);
		$options['title'] = GuiUtils::getLanguageName($row['language']);
	}
	$options['badge']=$row['count'];
	$options['value']=$row['language'];
	$writer->item($options);
}

$writer->title(array('Overviews','da'=>'Oversigter'));

$writer->item(array(
	'title' => array('News','da'=>'Nyheder'),
	'icon' => 'monochrome/news',
	'value' => 'news',
	'kind' => 'subset',
	'badge' => PageService::getNewsPageCount()
));

$writer->item(array(
	'title' => array('Warnings','da'=>'Advarsler'),
	'icon' => 'monochrome/warning',
	'value' => 'warnings',
	'kind' => 'subset',
	'badge' => PageService::getWarningsPageCount()
));

$writer->item(array(
	'title' => array('Modified','da'=>'Ændret'),
	'icon' => 'monochrome/edit',
	'value' => 'changed',
	'kind' => 'subset',
	'badge' => PageService::getChangedPageCount()
));

$writer->item(array(
	'title' => array('Review','da'=>'Revidering'),
	'icon' => 'monochrome/stamp',
	'value' => 'review',
	'kind' => 'subset',
	'badge' => PageService::getReviewPageCount()
));

$writer->endItems();
?>