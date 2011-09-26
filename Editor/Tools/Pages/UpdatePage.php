<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Core/InternalSession.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Model/Hierarchy.php';
require_once '../../Classes/Model/Page.php';
require_once '../../Classes/Services/EventService.php';
require_once '../../Classes/Services/CacheService.php';

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


CacheService::clearPageCache($id);

// TODO : templateUnique instead of null
EventService::fireEvent('update','page',null,$id);

InternalSession::setToolSessionVar('pages','updateHier',true);
Response::redirect('EditPage.php?id='.$id);
?>