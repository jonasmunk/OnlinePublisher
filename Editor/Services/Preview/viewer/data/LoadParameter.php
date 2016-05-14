<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../../Include/Private.php';

$pageId = Request::getInt('pageId');
$name = Request::getString('name');

if (Strings::isBlank($name)) {
    Response::badRequest();
    exit;
}

$parameter = [
    'name' => $name,
    'levels' => [
        'global' => [
            'id' => null,
            'value' => null
        ],
        'page' => [
            'id' => null,
            'value' => null
        ]
    ]
];

$sql = "select id from parameter where name=@text(name)";
if ($row = Database::selectFirst($sql,['name'=>$name])) {
    $parameter = Parameter::load(intval($row['id']));
} else {
    $parameter = new Parameter();
    $parameter->setName($name);
}

Response::SendObject($parameter);
?>