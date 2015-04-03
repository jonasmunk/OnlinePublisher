<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../../Include/Private.php';

$id = Request::getString('id');

if ($parameter = Parameter::load($id)) {
    $parameter->remove();
} else {
    Response::notFound();
}
?>