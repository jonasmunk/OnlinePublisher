<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';
require_once '../../Classes/Page.php';
require_once 'PagesController.php';

$info = PagesController::getNewPageInfo();

$title = requestPostText('title');
$description = requestPostText('description');
$keywords = requestPostText('keywords');
$language = requestPostText('language');
$template = $info['template'];
$design = $info['design'];
$frame = $info['frame'];
if ($info['hierarchy']>0) {
	$hierarchyParent = $info['hierarchyParent'];
	$hierarchy = $info['hierarchy'];
}
else if ($info['fixedHierarchy']>0) {
	$hierarchyParent = $info['fixedHierarchyParent'];
	$hierarchy = $info['fixedHierarchy'];
}
else {
	$hierarchyParent = 0;
	$hierarchy = 0;
}

$page = new Page();
$page->setTitle($title);
$page->setDescription($description);
$page->setLanguage($language);
$page->setKeywords($keywords);
$page->setTemplateId($template);
$page->setDesignId($design);
$page->setFrameId($frame);
$page->create();

$pageId=$page->getId();

if ($hierarchy>0) {
	
	// find index
	$sql="select max(`index`) as `index` from hierarchy_item where parent=".$hierarchyParent." and hierarchy_id=".$hierarchy;
	$result = Database::select($sql);
	if ($row2 = Database::next($result)) {
		$index=$row2['index']+1;
	}
	else {
		$index=1;
	}
	Database::free($result);
	
	$sql="insert into hierarchy_item (title,alternative,type,hierarchy_id,parent,`index`,target_type,target_id,target_value,target) values (".
	Database::text($title).
	",".Database::text('').
	",'item'".
	",".$hierarchy.
	",".$hierarchyParent.
	",".$index.
	",".Database::text('page').
	",".$pageId.
	",".Database::text('').
	",".Database::text('').
	")";
	Database::insert($sql);
	
	// Mark hierarchy as changed
	$sql="update hierarchy set changed=now() where id=".$hierarchy;
	Database::update($sql);

	setToolSessionVar('pages','updateHier',true);
}


redirect('EditPage.php?id='.$pageId);
?>