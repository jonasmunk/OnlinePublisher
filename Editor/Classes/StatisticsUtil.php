<?
require_once($basePath.'Editor/Classes/UserAgentAnalyzer.php');

class StatisticsUtil {
	
	//////////////////////// Static /////////////////////
	
	function preProcess() {
		$sql = "update statistics left join log on statistics.ip=log.ip set statistics.known=1 where not(log.id is null) and known is null";
		Database::update($sql);
		$sql = "update statistics left join log on statistics.ip=log.ip set statistics.known=0 where log.id is null and known is null";
		Database::update($sql);
		$sql = "select id,agent from statistics where robot is null";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$analyzer = new UserAgentAnalyzer();
			$analyzer->setUserAgent($row['agent']);
			$sql = "update statistics set robot=".($analyzer->isRobot() ? "1" : "0")." where id=".$row['id'];
			Database::update($sql);
		}
		Database::free($result);
	}
}
?>