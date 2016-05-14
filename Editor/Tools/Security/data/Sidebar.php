<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Security
 */
require_once '../../../Include/Private.php';

$writer = new ItemsWriter();

$writer->startItems();

$writer->item([
	'title' => ['Users','da'=>'Brugere'],
	'value' => 'users',
    'icon' => 'view/list'
]);

$list = Query::after(Securityzone::$TYPE)->get();
foreach ($list as $item) {
    $writer->item([
    	'title' => $item->getTitle(),
    	'value' => $item->getId(),
    	'kind' => 'securityzone',
    	'icon' => 'common/folder'
    ]);
}
$writer->endItems();
?>