<?php
require_once '../../../Editor/Include/Public.php';
header('Content-type: text/css');
Response::setExpiresInHours(24*7);


include '../../basic/css/parts.php';
include 'overwrite.css';
include 'main.css';
include 'poster.css';
include 'menu.css';
include 'adaption.css';
?>