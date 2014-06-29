<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../Include/Private.php';


$pageId = Request::getInt('pageId');
$title = Request::getString('title');
$placement = Request::getString('placement');

$page = createPage($pageId,$title,$placement);
if ($page==false) {
    Response::badRequest();
} else {
    Response::sendObject(['id' => $page->getId()]);
}


function createPage($pageId,$title,$placement) {
    $context_page = Page::load($pageId);
    if (!$context_page) {
        Log::debug('No page');
        return false;
    }
    $context_item = HierarchyItem::loadByPageId($context_page->getId());
    $template = TemplateService::getTemplateByUnique('document');
    if ($context_item && $template) {
        $page = new Page();
        $page->setTitle($title);
        $page->setTemplateId($template->getId());
        $page->setDesignId($context_page->getDesignId());
        $page->setFrameId($context_page->getFrameId());
        $page->setLanguage($context_page->getLanguage());
        if ($page->create()) {
            $hierarchy = Hierarchy::load($context_item->getHierarchyId());
            if (!$hierarchy) {
                Log::debug('No hierarchy');
                return false;
            }
            // TODO: Support different placements
        	$success = $hierarchy->createItem(array(
        		'title' => $title,
        		'targetType' => 'page',
                'hidden' => false,
        		'targetValue' => $page->getId(),
        		'parent' => $context_item->getId()
        	));
            
            return $page;            
        }
    }
    return false;    
}
?>