<?php
require_once '../../../Editor/Include/Public.php';
header('Content-type: text/css');
Response::setExpiresInHours(24*7);



include 'fonts.css';
include '../../basic/css/parts.php';
include 'main.css';
include 'front.css';
include 'overwrite.css';
include 'adaption.css';
include 'humanise.css';
?>