<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Formats
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class DBUCalendarParser {

  static function parseURL($url) {
    $string = @file_get_contents($url);
    if (!$string) {
      return false;
    }
    $cal = new DBUCalendar();
    $string = Strings::toUnicode($string);
    $table = HtmlTableParser::parseUsingHeader($string);
    if (isset($table[0])) {
      $first = $table[0];
      if (is_array($first)) {
        foreach ($first as $row) {
          $date = @$row['Dato'];
          $time = @$row['Kl.'];
          $home = @$row['Hjemmehold'];
          $away = @$row['Udehold'];
          $location = @$row['Spillested'];
          $score = @$row['Res'];

          if (Strings::isBlank($date) || Strings::isBlank($time)) {
            continue;
          }
          $parts = preg_split('/:/',$time);
          if (count($parts) != 2) {
            continue;
          }
          $parsed = Dates::parse($date);
          $parsed = Dates::addHours($parsed,intval($parts[0]));
          $startDate = Dates::addMinutes($parsed,intval($parts[1]));
          $endDate = Dates::addMinutes($parsed,60*1.75);

          $event = new DBUCalendarEvent();
          $event->setStartDate($startDate);
          $event->setEndDate($endDate);
          $event->setHomeTeam($home);
          $event->setGuestTeam($away);
          $event->setLocation($location);
          $event->setScore($score);
          $cal->addEvent($event);

        }
      }
    }
    return $cal;
  }
}
?>