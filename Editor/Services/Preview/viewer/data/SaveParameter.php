<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../../Include/Private.php';

$id = Request::getString('id');
$name = Request::getString('name');
$value = Request::getString('value');

if ($id) {
    $parameter = Parameter::load($id);
} else {
    $parameter = new Parameter();    
    $parameter->setName($name);
}
$parameter->setValue($value);
$parameter->save();

Response::SendObject($parameter);
?>