<?php
/**
 * @package OnlinePublisher
 * @subpackage Public.Services.Statistics
 */

require_once '../../../Config/Setup.php';
require_once '../../../Editor/Include/Public.php';
require_once '../../../Editor/Classes/Core/Database.php';
require_once '../../../Editor/Classes/Network/UserAgentAnalyzer.php';

statistics();

function statistics() {
echo "BEGIN:VCALENDAR\n";
echo "VERSION:2.0\n";
echo "X-WR-CALNAME:Statistics\n";
echo "PRODID:OnlinePublisher
X-WR-TIMEZONE:Europe/Copenhagen
CALSCALE:GREGORIAN
METHOD:PUBLISH
BEGIN:VTIMEZONE
TZID:Europe/Copenhagen
LAST-MODIFIED:20041018T153349Z
BEGIN:DAYLIGHT
DTSTART:20030330T010000
TZOFFSETTO:+0200
TZOFFSETFROM:+0000
TZNAME:CEST
END:DAYLIGHT
BEGIN:STANDARD
DTSTART:20031026T030000
TZOFFSETTO:+0100
TZOFFSETFROM:+0200
TZNAME:CET
END:STANDARD
BEGIN:DAYLIGHT
DTSTART:20040328T030000
TZOFFSETTO:+0200
TZOFFSETFROM:+0100
TZNAME:CEST
END:DAYLIGHT
BEGIN:STANDARD
DTSTART:20041031T030000
TZOFFSETTO:+0100
TZOFFSETFROM:+0200
TZNAME:CET
END:STANDARD
END:VTIMEZONE\n";
$analyzer = new UserAgentAnalyzer();

$sql="select ip,agent,count(session) as num,DATE_FORMAT(min(time),'%Y%m%dT%H%i%s') as begin,DATE_FORMAT(max(time),'%Y%m%dT%H%i%s') as end from statistics group by session";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$analyzer->setUserAgent($row['agent']);
	echo "BEGIN:VEVENT\n";
	echo "DTSTART;TZID=Europe/Copenhagen:".$row['begin']."\n";
	echo "DTEND;TZID=Europe/Copenhagen:".$row['end']."\n";
	echo "SUMMARY:Antal: ".$row['num']."\\nIP: ".$row['ip']."\n";
	echo "DESCRIPTION:Browser: ".$analyzer->getApplicationName()." ".$analyzer->getApplicationVersion()."\n";
	echo "END:VEVENT\n";
}
Database::free($result);

echo "END:VCALENDAR";
}
?>