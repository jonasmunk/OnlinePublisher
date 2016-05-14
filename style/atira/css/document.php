<?php
require_once '../../../Editor/Include/Public.php';
header('Content-type: text/css');
Response::setExpiresInHours(24*7);

include '../../basic/css/parts.php';
include 'parts.css';
?>

div.document
{
	width: 100%;
}

td.document_column
{
	padding-left: 10px;
}
td.document_column_first
{
	padding-left: 0px;
}