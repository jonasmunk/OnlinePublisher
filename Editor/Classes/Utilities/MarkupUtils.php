<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Utilities
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class MarkupUtils {
	
	static function findScriptSegments($str) {
		$start = '<script';
		$stop = '</script>';
		$segments = array();
		$pos = 0;
		while ($pos!==false) {
			$from = strpos($str,$start,$pos);
			if ($from===false) {
				$pos = false;
				continue;
			}
			$to = strpos($str,$stop,$from+strlen($start));
			if ($to!==false) {
				$to+=strlen($stop);
				$segments[] = array('from'=>$from,'to'=>$to);
				$pos = $to;
			} else {
				$pos = false;
			}
		}
		return $segments;
	}
  
	static function moveScriptsToBottom($html) {
		if (strpos($html,'</body>')===FALSE) {
			return $html;
		}
		preg_match_all("/<script[^>]+\\/>|<script[^>]*>[\s\S]*<\\/script>/uU", $html, $matches);
		$found = $matches[0];
		$html = str_replace($found,'',$html);
		$pos = strpos($html,'</body>');    
		$html = substr($html,0,$pos) . join($found,'') . substr($html,$pos);
		return $html;

	}
	
	static function _moveScriptsToBottom($html) {
		$result = MarkupUtils::findScriptSegments($html);
		$found = array();
		foreach ($result as $row) {
			$found[] = substr($html,$row['from'],$row['to']-$row['from']);
		}
		$html = str_replace($found,'<!-- moved script -->',$html);
		$pos = strpos($html,'</body>');

		$html = substr($html,0,$pos) . join($found,'') . substr($html,$pos);
		return $html;
	}
}