<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';
require_once '../../Classes/Hierarchy.php';
require_once '../../Classes/Page.php';
require_once '../../Classes/EventManager.php';

$id=requestPostNumber('id',-1);
$name=requestPostText('name');
$path=requestPostText('path');
$title=requestPostText('title');
$description=requestPostText('description');
$keywords=requestPostText('keywords');
$language=requestPostText('language');
$design=requestPostNumber('design',-1);
$frame=requestPostNumber('frame',-1);
$searchable=requestPostCheckbox('searchable');
$disabled=requestPostCheckbox('disabled');
$nextPage=requestPostNumber('nextPage',0);
$previousPage=requestPostNumber('previousPage',0);

$hierarchyItemId=requestPostNumber('hierarchyItemId');
$hierarchyItemTitle=requestPostText('hierarchyItemTitle');


$sql="update page set".
" title=".Database::text($title).
",description=".Database::text($description).
",keywords=".Database::text($keywords).
",name=".Database::text($name).
",path=".Database::text($path).
",design_id=".$design.
",frame_id=".$frame.
",language=".Database::text($language).
",searchable=".sqlBoolean($searchable).
",disabled=".sqlBoolean($disabled).
",next_page=".sqlInt($nextPage).
",previous_page=".sqlInt($previousPage).
" where id=".$id;
Database::update($sql);

if ($hierarchyItemId>0) {
    $sql = "update hierarchy_item set title=".Database::text($hierarchyItemTitle)." where id=".$hierarchyItemId;
    Database::update($sql);
    Hierarchy::markHierarchyOfItemChanged($hierarchyItemId);
}


$page = Page::load($id);
$page->clearPreviews();

// TODO : templateUnique instead of null
EventManager::fireEvent('update','page',null,$id);

setToolSessionVar('pages','updateHier',true);
redirect('EditPage.php?id='.$id);
?>