<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Include/Private.php';

$query = Request::getString('query');

$xml = '<?xml version="1.0"?>' . $query;
$doc = DOMUtils::parse($xml);

if (!$doc) {
  echo 'INVLAID';
  exit;
}


$types = $doc->getElementsByTagName('type');
for ($i=0; $i < $types->length; $i++) { 
  $type = $types->item($i);
  echo 'select * from `' . $type->getAttribute('name') . '`;';
}
?>