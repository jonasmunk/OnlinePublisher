<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class ReportService {
	
	function setEmail($value) {
		SettingService::setSetting('system','reports','email',$value);
	}

	function getEmail() {
		return SettingService::getSetting('system','reports','email');
	}
	
	function heartBeat() {
		$latest = intval(SettingService::getSetting('system','reports','latest'));
		Log::debug('Latest: '.$latest);
		$seconds = time() - intval($latest);
		$oneDay = 60 * 60 * 24;
		if ($seconds > $oneDay) {
			Log::debug('Latest run was more than one day ago');
			SettingService::setSetting('system','reports','latest',time());
			ReportService::sendReport();
		} else {
			Log::debug('Will not send report yet: '.DateUtils::formatDuration($seconds));
		}
	}
	
	function sendReport() {		
		$emails = ReportService::getEmail();
		if (StringUtils::isBlank($emails)) {
			return false;
		}
		$success = true;

		$html = ReportService::generateFullReport();
		$parts = preg_split("/[\s,;]+/", $emails);
		foreach ($parts as $email) {
			if (ValidateUtils::validateEmail($email)) {
				ReportService::_sendReportToEmail($email,$html);
			} else {
				$success = false;
				Log::debug('The email is not valid: '.$email);
			}
		}
		return $success;
	}

	function _sendReportToEmail($email,$html) {
		Log::debug('Sending report to: '.$email);
		$name = '';
		$subject = 'Report from '.ConfigurationService::getCompleteBaseUrl();
		$body = 'This is an HTML-only mail';
		return MailService::send($email,$name,$subject,$body,$html);
	}
	
	function generateFullReport() {
		global $basePath;
		$html = '<!DOCTYPE html>
		<html>
		<head>
		<meta xmlns="http://www.w3.org/1999/xhtml" http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<style>';
		
		$filename = $basePath."Editor/Resources/report.css";
		$handle = fopen($filename, "rb");
		$contents = fread($handle, filesize($filename));
		fclose($handle);
		$html.=$contents;
		$html.='</style>
		</head>
		<body>';
		$html.= ReportService::generateReport();
		$html.='</body></html>';
		return $html;
	}
	
	function generateReport() {
		$url = ConfigurationService::getCompleteBaseUrl();
		
		$query = new StatisticsQuery();
		$query->setStartTime(DateUtils::addDays(time(),-14));
		$query->setEndTime(DateUtils::getDayStart(time()));
		$stats = StatisticsService::searchVisits($query);

		$report = '<div class="report">';
		$report.= '<h1>Report</h1><p class="info">'.DateUtils::formatLongDate(time()).' for <a href="'.$url.'">'.$url.'</a></p>';
		$report.= '<div class="block statistics">';
		$report.= '<h2>Statistics for the last two weeks</h2>';
		$report.= '<table>'.
			'<thead><th class="date">Date</th><th>Sessions</th><th>IPs</th><th>Hits</th></thead><tbody>';
		$max = array('sessions'=>0,'ips'=>0,'hits'=>0);
		foreach ($stats as $stat) {
			$max['sessions'] = max($max['sessions'],$stat['sessions']);
			$max['ips'] = max($max['ips'],$stat['ips']);
			$max['hits'] = max($max['hits'],$stat['hits']);
		}
		
		foreach ($stats as $stat) {
			$report.='<tr>'.
				'<th class="date">'.$stat['label'].'</th>'.
				'<td class="value"><div><strong style="width: '.($stat['sessions']/$max['sessions']*100).'%;"></strong><span>'.$stat['sessions'].'</span></div></td>'.
				'<td class="value"><div><strong style="width: '.($stat['ips']/$max['ips']*100).'%;"></strong><span>'.$stat['ips'].'</span></div></td>'.
				'<td class="value"><div><strong style="width: '.($stat['hits']/$max['hits']*100).'%;"></strong><span>'.$stat['hits'].'</span></div></td>'.
				'</tr>';
		}
		$report.= '</tbody></table>';
		$report.= '</div>';
		
		
		$issues = Query::after('issue')->withCreatedMin(DateUtils::addDays(time(),-2))->withoutProperty('kind',Issue::$error)->orderByCreated()->descending()->get();
		if ($issues) {
			$report.= '<div class="block issues">';
			$report.= '<h2>Issues the last two days</h2>';
			$report.= '<ul>';
			foreach ($issues as $issue) {
				$report.='<li class="'.$issue->getKind().'">';
				$report.='<p><strong>'.StringUtils::escapeXML($issue->getTitle()).'</strong></p>';
				$report.='<p>'.StringUtils::escapeXML($issue->getNote()).'</p>';
				$report.='</li>';
			}
			$report.= '</ul>';
			$report.= '</div>';
		}
		return $report;
	}
}