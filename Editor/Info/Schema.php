<?php
/**
 * @package OnlinePublisher
 * @subpackage Info
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
$HUMANISE_EDITOR_SCHEMA = array (
  'tables' => 
  array (
    0 => 
    array (
      'name' => 'address',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'street' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'zipcode' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'city' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'country' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    1 => 
    array (
      'name' => 'authentication',
      'columns' => 
      array (
        'page_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'title' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    2 => 
    array (
      'name' => 'cachedurl',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'url' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'synchronized' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'mimeType' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    3 => 
    array (
      'name' => 'calendar',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    4 => 
    array (
      'name' => 'calendar_event',
      'columns' => 
      array (
        'calendar_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'event_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    5 => 
    array (
      'name' => 'calendarsource',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'url' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'synchronized' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'sync_interval' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '3600',
          'key' => '',
          'extra' => '',
        ),
        'display_title' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'filter' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    6 => 
    array (
      'name' => 'calendarsource_event',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'calendarsource_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'summary' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'description' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'startdate' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'enddate' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'timestamp' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'uniqueid' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'location' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'recurring' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'frequency' => 
        array (
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'until' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'count' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'interval' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'bymonth' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'bymonthday' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'byday' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'byyearday' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'byweeknumber' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'weekstart' => 
        array (
          'type' => 'char(2)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'duration' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'url' => 
        array (
          'type' => 'varchar(1024)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    7 => 
    array (
      'name' => 'calendarviewer',
      'columns' => 
      array (
        'page_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'title' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'weekview_starthour' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'standard_view' => 
        array (
          'type' => 'varchar(128)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => 'week',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    8 => 
    array (
      'name' => 'calendarviewer_object',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'page_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    9 => 
    array (
      'name' => 'design',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'unique' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'name' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'parameters' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    10 => 
    array (
      'name' => 'design_parameter',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'design_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'key' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'type' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'value' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    11 => 
    array (
      'name' => 'document',
      'columns' => 
      array (
        'page_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    12 => 
    array (
      'name' => 'document_column',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'row_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'index' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'page_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'width' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'top' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'bottom' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'left' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'right' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    13 => 
    array (
      'name' => 'document_row',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'page_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'index' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'top' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'bottom' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'spacing' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    14 => 
    array (
      'name' => 'document_section',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'page_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'column_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'index' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'type' => 
        array (
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'top' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'bottom' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'left' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'right' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'part_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'float' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'width' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    15 => 
    array (
      'name' => 'email_validation_session',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'unique' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'user_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'email' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'timelimit' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    16 => 
    array (
      'name' => 'emailaddress',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'address' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'containing_object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    17 => 
    array (
      'name' => 'event',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'startdate' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ),
        'enddate' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ),
        'location' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    18 => 
    array (
      'name' => 'feedback',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'name' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'email' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'message' => 
        array (
          'type' => 'mediumtext',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    19 => 
    array (
      'name' => 'file',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'filename' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'size' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'type' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    20 => 
    array (
      'name' => 'filegroup',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    21 => 
    array (
      'name' => 'filegroup_file',
      'columns' => 
      array (
        'file_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'filegroup_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    22 => 
    array (
      'name' => 'frame',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'title' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'name' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'hierarchy_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'searchbuttontitle' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'searchenabled' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'searchpage_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'searchpages' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'searchimages' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'searchfiles' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'searchnews' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'searchpersons' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'searchproducts' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'data' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'changed' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'published' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'bottomtext' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'dynamic' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'userstatusenabled' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'userstatuspage_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    23 => 
    array (
      'name' => 'frame_link',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'frame_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'position' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'index' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'target' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'target_type' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'target_value' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'target_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'alternative' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'title' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    24 => 
    array (
      'name' => 'frame_newsblock',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'frame_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'index' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'title' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'sortby' => 
        array (
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'sortdir' => 
        array (
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'maxitems' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'timetype' => 
        array (
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'timecount' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'startdate' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'enddate' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    25 => 
    array (
      'name' => 'frame_newsblock_newsgroup',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'frame_newsblock_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'newsgroup_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    26 => 
    array (
      'name' => 'guestbook',
      'columns' => 
      array (
        'page_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'title' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'text' => 
        array (
          'type' => 'mediumtext',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    27 => 
    array (
      'name' => 'guestbook_item',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'page_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'time' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'text' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'name' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    28 => 
    array (
      'name' => 'hierarchy',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'name' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'data' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'changed' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'published' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'language' => 
        array (
          'type' => 'varchar(5)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    29 => 
    array (
      'name' => 'hierarchy_item',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'hierarchy_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'parent' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'index' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'type' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'title' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'alternative' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'target' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'target_type' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'target_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'target_value' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'hidden' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    30 => 
    array (
      'name' => 'html',
      'columns' => 
      array (
        'page_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'html' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'valid' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ),
        'title' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    31 => 
    array (
      'name' => 'image',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'filename' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'size' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'width' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'height' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'type' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    32 => 
    array (
      'name' => 'imagegallery',
      'columns' => 
      array (
        'page_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => 'PRI',
          'extra' => '',
        ),
        'title' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'text' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'imagesize' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '48',
          'key' => '',
          'extra' => '',
        ),
        'showtitle' => 
        array (
          'type' => 'tinyint(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ),
        'shownote' => 
        array (
          'type' => 'tinyint(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ),
        'rotate' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    33 => 
    array (
      'name' => 'imagegallery_custom_info',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'page_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'image_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'title' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'note' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    34 => 
    array (
      'name' => 'imagegallery_object',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'page_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'position' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    35 => 
    array (
      'name' => 'imagegroup',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    36 => 
    array (
      'name' => 'imagegroup_image',
      'columns' => 
      array (
        'image_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'imagegroup_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    37 => 
    array (
      'name' => 'issue',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'kind' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'issuestatus_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    38 => 
    array (
      'name' => 'issuestatus',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    39 => 
    array (
      'name' => 'link',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'page_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'part_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'source_type' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'source_text' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'target' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'target_type' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'target_value' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'target_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'alternative' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    40 => 
    array (
      'name' => 'log',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'time' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'category' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'event' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'entity' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'message' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'user_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'ip' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'session' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    41 => 
    array (
      'name' => 'mailinglist',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    42 => 
    array (
      'name' => 'milestone',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'deadline' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'containing_object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'completed' => 
        array (
          'type' => 'tinyint(1)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    43 => 
    array (
      'name' => 'news',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'startdate' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ),
        'enddate' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ),
        'image_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    44 => 
    array (
      'name' => 'newsgroup',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    45 => 
    array (
      'name' => 'newsgroup_news',
      'columns' => 
      array (
        'news_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'newsgroup_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    46 => 
    array (
      'name' => 'newssource',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'url' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'synchronized' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'sync_interval' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '3600',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    47 => 
    array (
      'name' => 'newssourceitem',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'newssource_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'text' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'date' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ),
        'url' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'guid' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    48 => 
    array (
      'name' => 'object',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'title' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'type' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'note' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'data' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'created' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ),
        'updated' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ),
        'published' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ),
        'searchable' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ),
        'index' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'owner_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    49 => 
    array (
      'name' => 'object_link',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'target' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'target_type' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'target_value' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'alternative' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'title' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'position' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    50 => 
    array (
      'name' => 'page',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'title' => 
        array (
          'type' => 'varchar(100)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'description' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'keywords' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'template_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'data' => 
        array (
          'type' => 'longtext',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'created' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ),
        'changed' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ),
        'published' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ),
        'design_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'frame_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'index' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'dynamic' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'language' => 
        array (
          'type' => 'varchar(5)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'searchable' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ),
        'secure' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'disabled' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'name' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'path' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'next_page' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'previous_page' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    51 => 
    array (
      'name' => 'page_cache',
      'columns' => 
      array (
        'page_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'stamp' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'version' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'html' => 
        array (
          'type' => 'mediumtext',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'path' => 
        array (
          'type' => 'varchar(1024)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    52 => 
    array (
      'name' => 'page_history',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'page_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'user_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'time' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'data' => 
        array (
          'type' => 'longtext',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'message' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    53 => 
    array (
      'name' => 'page_translation',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'page_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'translation_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    54 => 
    array (
      'name' => 'pageblueprint',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'design_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'frame_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'template_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    55 => 
    array (
      'name' => 'parameter',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'name' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'level' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'language' => 
        array (
          'type' => 'varchar(5)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'value' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    56 => 
    array (
      'name' => 'part',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'type' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'created' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ),
        'updated' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ),
        'dynamic' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    57 => 
    array (
      'name' => 'part_authentication',
      'columns' => 
      array (
        'part_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    58 => 
    array (
      'name' => 'part_file',
      'columns' => 
      array (
        'part_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'file_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'text' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    59 => 
    array (
      'name' => 'part_formula',
      'columns' => 
      array (
        'part_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'receivername' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'receiveremail' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'recipe' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    60 => 
    array (
      'name' => 'part_header',
      'columns' => 
      array (
        'part_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'level' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ),
        'text' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'textalign' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'fontfamily' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'fontsize' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'lineheight' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'fontweight' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'color' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'wordspacing' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'letterspacing' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'textdecoration' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'textindent' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'texttransform' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'fontstyle' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'fontvariant' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    61 => 
    array (
      'name' => 'part_horizontalrule',
      'columns' => 
      array (
        'part_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    62 => 
    array (
      'name' => 'part_html',
      'columns' => 
      array (
        'part_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'html' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    63 => 
    array (
      'name' => 'part_image',
      'columns' => 
      array (
        'part_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'image_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'align' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'greyscale' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'adaptive' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'scalemethod' => 
        array (
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'scalewidth' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'scaleheight' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'scalepercent' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'text' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'frame' => 
        array (
          'type' => 'varchar(30)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    64 => 
    array (
      'name' => 'part_imagegallery',
      'columns' => 
      array (
        'part_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'imagegroup_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'height' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '64',
          'key' => '',
          'extra' => '',
        ),
        'width' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '64',
          'key' => '',
          'extra' => '',
        ),
        'framed' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'show_title' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'variant' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'frame' => 
        array (
          'type' => 'varchar(30)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    65 => 
    array (
      'name' => 'part_link',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'part_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'source_type' => 
        array (
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'source_text' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'target' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'target_type' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'target_value' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'alternative' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'title' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'position' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    66 => 
    array (
      'name' => 'part_list',
      'columns' => 
      array (
        'part_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'title' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'align' => 
        array (
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'width' => 
        array (
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'maxitems' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '10',
          'key' => '',
          'extra' => '',
        ),
        'maxtextlength' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'variant' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => 'box',
          'key' => '',
          'extra' => '',
        ),
        'time_count' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '7',
          'key' => '',
          'extra' => '',
        ),
        'time_type' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => 'days',
          'key' => '',
          'extra' => '',
        ),
        'show_source' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'show_text' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ),
        'show_timezone' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'sort_direction' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => 'ascending',
          'key' => '',
          'extra' => '',
        ),
        'timezone' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => 'days',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    67 => 
    array (
      'name' => 'part_list_object',
      'columns' => 
      array (
        'part_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    68 => 
    array (
      'name' => 'part_listing',
      'columns' => 
      array (
        'part_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'text' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'textalign' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'fontfamily' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'fontsize' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'lineheight' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'fontweight' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'color' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'wordspacing' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'letterspacing' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'textdecoration' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'textindent' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'texttransform' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'fontstyle' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'fontvariant' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'type' => 
        array (
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    69 => 
    array (
      'name' => 'part_mailinglist',
      'columns' => 
      array (
        'part_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    70 => 
    array (
      'name' => 'part_mailinglist_mailinglist',
      'columns' => 
      array (
        'part_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'mailinglist_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    71 => 
    array (
      'name' => 'part_map',
      'columns' => 
      array (
        'part_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'provider' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'latitude' => 
        array (
          'type' => 'decimal(20,17)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'longitude' => 
        array (
          'type' => 'decimal(20,17)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'text' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'maptype' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'markers' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'zoom' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'width' => 
        array (
          'type' => 'varchar(11)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'height' => 
        array (
          'type' => 'varchar(11)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'frame' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    72 => 
    array (
      'name' => 'part_menu',
      'columns' => 
      array (
        'part_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'hierarchy_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'variant' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'header' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'depth' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    73 => 
    array (
      'name' => 'part_movie',
      'columns' => 
      array (
        'part_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'file_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'image_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'text' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'code' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'url' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'width' => 
        array (
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'height' => 
        array (
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    74 => 
    array (
      'name' => 'part_news',
      'columns' => 
      array (
        'part_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'align' => 
        array (
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'width' => 
        array (
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'news_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'mode' => 
        array (
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'title' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'sortby' => 
        array (
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'sortdir' => 
        array (
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'maxitems' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'timetype' => 
        array (
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'timecount' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'startdate' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'enddate' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'variant' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => 'box',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    75 => 
    array (
      'name' => 'part_news_newsgroup',
      'columns' => 
      array (
        'part_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'newsgroup_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    76 => 
    array (
      'name' => 'part_person',
      'columns' => 
      array (
        'part_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'align' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'person_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'show_firstname' => 
        array (
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ),
        'show_middlename' => 
        array (
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ),
        'show_surname' => 
        array (
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ),
        'show_initials' => 
        array (
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'show_nickname' => 
        array (
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'show_jobtitle' => 
        array (
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ),
        'show_sex' => 
        array (
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'show_email_job' => 
        array (
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ),
        'show_email_private' => 
        array (
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ),
        'show_phone_job' => 
        array (
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ),
        'show_phone_private' => 
        array (
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ),
        'show_streetname' => 
        array (
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ),
        'show_zipcode' => 
        array (
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ),
        'show_city' => 
        array (
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ),
        'show_country' => 
        array (
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ),
        'show_webaddress' => 
        array (
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ),
        'show_image' => 
        array (
          'type' => 'int(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '1',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    77 => 
    array (
      'name' => 'part_poster',
      'columns' => 
      array (
        'part_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'recipe' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    78 => 
    array (
      'name' => 'part_richtext',
      'columns' => 
      array (
        'part_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'html' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    79 => 
    array (
      'name' => 'part_table',
      'columns' => 
      array (
        'part_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'html' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    80 => 
    array (
      'name' => 'part_text',
      'columns' => 
      array (
        'part_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'text' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'textalign' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'fontfamily' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'fontsize' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'lineheight' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'fontweight' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'color' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'wordspacing' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'letterspacing' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'textdecoration' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'textindent' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'texttransform' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'fontstyle' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'fontvariant' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'image_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'imagefloat' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => 'left',
          'key' => '',
          'extra' => '',
        ),
        'imagewidth' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'imageheight' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    81 => 
    array (
      'name' => 'part_widget',
      'columns' => 
      array (
        'part_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'key' => 
        array (
          'type' => 'varchar(100)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'data' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    82 => 
    array (
      'name' => 'path',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'path' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'page_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    83 => 
    array (
      'name' => 'person',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'firstname' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'middlename' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'surname' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'initials' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'nickname' => 
        array (
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'jobtitle' => 
        array (
          'type' => 'varchar(30)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => '0000-00-00 00:00:00',
          'key' => '',
          'extra' => '',
        ),
        'sex' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'email_job' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'email_private' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'phone_job' => 
        array (
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'phone_private' => 
        array (
          'type' => 'varchar(20)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'streetname' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'zipcode' => 
        array (
          'type' => 'varchar(4)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'city' => 
        array (
          'type' => 'varchar(30)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'country' => 
        array (
          'type' => 'varchar(30)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'webaddress' => 
        array (
          'type' => 'varchar(30)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'image_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    84 => 
    array (
      'name' => 'person_mailinglist',
      'columns' => 
      array (
        'person_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'mailinglist_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    85 => 
    array (
      'name' => 'persongroup',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    86 => 
    array (
      'name' => 'persongroup_person',
      'columns' => 
      array (
        'person_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'persongroup_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    87 => 
    array (
      'name' => 'personrole',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'person_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    88 => 
    array (
      'name' => 'phonenumber',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'number' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'context' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'containing_object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    89 => 
    array (
      'name' => 'problem',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'deadline' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'containing_object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'completed' => 
        array (
          'type' => 'tinyint(1)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'milestone_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'priority' => 
        array (
          'type' => 'float',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    90 => 
    array (
      'name' => 'product',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'number' => 
        array (
          'type' => 'varchar(100)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'image_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'producttype_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'allow_offer' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    91 => 
    array (
      'name' => 'productattribute',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'product_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'name' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'value' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'index' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    92 => 
    array (
      'name' => 'productgroup',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    93 => 
    array (
      'name' => 'productgroup_product',
      'columns' => 
      array (
        'product_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'productgroup_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    94 => 
    array (
      'name' => 'productoffer',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'offer' => 
        array (
          'type' => 'double',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'product_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'person_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'expiry' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    95 => 
    array (
      'name' => 'productprice',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'product_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'amount' => 
        array (
          'type' => 'double',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'type' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'price' => 
        array (
          'type' => 'double',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'currency' => 
        array (
          'type' => 'varchar(5)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'index' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    96 => 
    array (
      'name' => 'producttype',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    97 => 
    array (
      'name' => 'project',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'parent_project_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    98 => 
    array (
      'name' => 'relation',
      'columns' => 
      array (
        'from_type' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => 'object',
          'key' => '',
          'extra' => '',
        ),
        'from_object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'to_type' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => 'object',
          'key' => '',
          'extra' => '',
        ),
        'to_object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'kind' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    99 => 
    array (
      'name' => 'remotepublisher',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'url' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    100 => 
    array (
      'name' => 'review',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'accepted' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'date' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    101 => 
    array (
      'name' => 'search',
      'columns' => 
      array (
        'page_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => 'PRI',
          'extra' => '',
        ),
        'title' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'text' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'pagesenabled' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'pageslabel' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'pagesdefault' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'pageshidden' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'imagesenabled' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'imageslabel' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'imagesdefault' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'imageshidden' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'filesenabled' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'fileslabel' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'filesdefault' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'fileshidden' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'newsenabled' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'newslabel' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'newsdefault' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'newshidden' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'personsenabled' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'personslabel' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'personsdefault' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'personshidden' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'productsenabled' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'productslabel' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'productsdefault' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'productshidden' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'buttontitle' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    102 => 
    array (
      'name' => 'securityzone',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'authentication_page_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    103 => 
    array (
      'name' => 'securityzone_page',
      'columns' => 
      array (
        'securityzone_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'page_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    104 => 
    array (
      'name' => 'securityzone_user',
      'columns' => 
      array (
        'securityzone_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'user_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    105 => 
    array (
      'name' => 'setting',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'domain' => 
        array (
          'type' => 'varchar(30)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'subdomain' => 
        array (
          'type' => 'varchar(30)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'key' => 
        array (
          'type' => 'varchar(30)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'value' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'user_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    106 => 
    array (
      'name' => 'specialpage',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'page_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'type' => 
        array (
          'type' => 'varchar(30)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'language' => 
        array (
          'type' => 'varchar(11)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    107 => 
    array (
      'name' => 'statistics',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'ip' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'country' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'agent' => 
        array (
          'type' => 'varchar(4096)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'method' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'uri' => 
        array (
          'type' => 'varchar(4096)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'language' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'type' => 
        array (
          'type' => 'varchar(10)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'value' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'session' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'time' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'referer' => 
        array (
          'type' => 'varchar(4096)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'host' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'robot' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'known' => 
        array (
          'type' => 'tinyint(4)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    108 => 
    array (
      'name' => 'task',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'deadline' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'containing_object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'completed' => 
        array (
          'type' => 'tinyint(1)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'milestone_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'priority' => 
        array (
          'type' => 'float',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    109 => 
    array (
      'name' => 'template',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'unique' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    110 => 
    array (
      'name' => 'testphrase',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    111 => 
    array (
      'name' => 'tool',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'unique' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    112 => 
    array (
      'name' => 'user',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'username' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'password' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'email' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'language' => 
        array (
          'type' => 'varchar(5)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'internal' => 
        array (
          'type' => 'tinyint(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'external' => 
        array (
          'type' => 'tinyint(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'administrator' => 
        array (
          'type' => 'tinyint(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'secure' => 
        array (
          'type' => 'tinyint(1)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    113 => 
    array (
      'name' => 'user_permission',
      'columns' => 
      array (
        'id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => NULL,
          'key' => 'PRI',
          'extra' => 'auto_increment',
        ),
        'user_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'entity_type' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'entity_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'permission' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    114 => 
    array (
      'name' => 'watermeter',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'number' => 
        array (
          'type' => 'varchar(50)',
          'collation' => 'utf8_danish_ci',
          'null' => 'NO',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    115 => 
    array (
      'name' => 'waterusage',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'watermeter_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'value' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'date' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'status' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'source' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    116 => 
    array (
      'name' => 'weblog',
      'columns' => 
      array (
        'page_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'pageblueprint_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'title' => 
        array (
          'type' => 'varchar(255)',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    117 => 
    array (
      'name' => 'weblog_webloggroup',
      'columns' => 
      array (
        'page_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'webloggroup_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    118 => 
    array (
      'name' => 'weblogentry',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'text' => 
        array (
          'type' => 'text',
          'collation' => 'utf8_danish_ci',
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'date' => 
        array (
          'type' => 'datetime',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
        'page_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    119 => 
    array (
      'name' => 'webloggroup',
      'columns' => 
      array (
        'object_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'YES',
          'default' => NULL,
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
    120 => 
    array (
      'name' => 'webloggroup_weblogentry',
      'columns' => 
      array (
        'weblogentry_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
        'webloggroup_id' => 
        array (
          'type' => 'int(11)',
          'collation' => NULL,
          'null' => 'NO',
          'default' => '0',
          'key' => '',
          'extra' => '',
        ),
      ),
    ),
  ),
)
?>