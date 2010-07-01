<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Request.php';
require_once '../../../Classes/In2iGui.php';
require_once '../../../Classes/Event.php';

$id = Request::getInt('id');
$file=Event::load($id);
$file->toUnicode();

$groups = $file->getCalendarIds();

In2iGui::sendObject(array('event' => $file, 'calendars' => $groups));
?>