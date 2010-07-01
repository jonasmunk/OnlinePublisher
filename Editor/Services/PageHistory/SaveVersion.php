<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.PageHistory
 */
require_once '../../../Config/Setup.php';

require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Publishing.php';
require_once '../../Classes/Page.php';

$id = requestGetNumber('id');

$page = Page::load($id);
$template = $page->getTemplateUnique();


$data = getPagePreview($id,$template);
createPageHistory($id,$data);


redirect('index.php');
?>