<?php
require_once '../../Editor/Include/Public.php';

StatisticsService::registerPage(Request::getInt('page'),Request::getString('referer'));
?>