<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Hierarchy.php';
require_once '../../Classes/Page.php';
require_once '../../Classes/EventManager.php';

$id=Request::getInt('id',-1);
$name=Request::getString('name');
$path=Request::getString('path');
$title=Request::getString('title');
$description=Request::getString('description');
$keywords=Request::getString('keywords');
$language=Request::getString('language');
$design=Request::getInt('design',-1);
$frame=Request::getInt('frame',-1);
$searchable=Request::getCheckbox('searchable');
$disabled=Request::getCheckbox('disabled');
$nextPage=Request::getInt('nextPage',0);
$previousPage=Request::getInt('previousPage',0);

$hierarchyItemId=Request::getInt('hierarchyItemId');
$hierarchyItemTitle=Request::getString('hierarchyItemTitle');


$sql="update page set".
" title=".Database::text($title).
",description=".Database::text($description).
",keywords=".Database::text($keywords).
",name=".Database::text($name).
",path=".Database::text($path).
",design_id=".$design.
",frame_id=".$frame.
",language=".Database::text($language).
",searchable=".Database::boolean($searchable).
",disabled=".Database::boolean($disabled).
",next_page=".Database::int($nextPage).
",previous_page=".Database::int($previousPage).
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

InternalSession::setToolSessionVar('pages','updateHier',true);
Response::redirect('EditPage.php?id='.$id);
?>