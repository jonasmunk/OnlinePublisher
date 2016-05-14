<?php
require_once '../../../Editor/Include/Public.php';
header('Content-type: text/css');
Response::setExpiresInHours(24*7);


include '../../basic/css/parts.php';
include 'overwrite.css';
include 'main.css';
include 'front.css';
include 'async.css';
?>