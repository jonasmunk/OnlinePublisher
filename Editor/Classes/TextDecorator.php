<?
class TextDecorator {

	var $tags = array();
	var $replacements = array();
	var $httpOpenTag = '<a href="{subject}">';
	var $httpCloseTag = '</a>';
	var $emailOpenTag = '<a href="mailto:{subject}">';
	var $emailCloseTag = '</a>';
	
	function TextDecorator() {
		
	}
	
	function setEmailTags($open,$close) {
		$this->emailOpenTag = $open;
		$this->emailCloseTag = $close;
	}
	
	function setHttpTags($open,$close) {
		$this->httpOpenTag = $open;
		$this->httpCloseTag = $close;
	}
	
	function addTag($from,$to) {
		$this->tags[] = array('from' => $from, 'to' => $to);
	}
	
	function addReplacement($subject,$open,$close) {
		$this->replacements[] = array('subject' => $subject, 'open' => $open, 'close' => $close);
	}
	
    function decorate($text) {
		$rules = array();
		foreach ($this->replacements as $replacement) {
			$this->replacement($text,$rules,$replacement['subject'],$replacement['open'],$replacement['close']);
		}
		foreach ($this->tags as $tag) {
			$this->tags($text,$rules,$tag['from'],$tag['to']);
		}
		$this->email($text,$rules);
		$this->url($text,$rules);
		ksort($rules);
		return $this->render($text,$rules);
    }

	function replacement($text,&$rules,$subject,$openTag,$closeTag) {
		$pos = -1;
		while ($pos !== false) {
			$pos = strpos( $text, $subject , $pos+1);
			if ($pos!==false) {
				$rules[$pos] = array('start' => $pos, 'stop' => $pos+strlen($subject),'openTag' => $openTag,'closeTag' => $closeTag);
			}
		}
	}
	
	function tags($text,&$rules,$orig,$replacement) {
		$pattern = "/\[".$orig."\]([^\[\]\n]+)\[".$orig."\]/i";
		preg_match_all($pattern, $text, $matches,PREG_OFFSET_CAPTURE);
		for ($i=0;$i<count($matches[0]);$i++) {
			$pos = $matches[0][$i][1];
			$old = $matches[0][$i][0];
			$subject = $matches[1][$i][0];
			$open = '';
			$close = '';
			if ($replacement) {
				$open = '<'.$replacement.'>';
				$close = '</'.$replacement.'>';
			}
			$rules[$pos] = array('start' => $pos, 'stop' => $pos+strlen($old),'openTag' => $open,'closeTag' => $close,'subject' => $subject);
		}
		foreach ($matches[0] as $match) {
		}
	}
	
	function email($text,&$rules) {
		$pattern = "/[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z]{2,4}\b/i";
		preg_match_all($pattern, $text, $matches,PREG_OFFSET_CAPTURE);
		foreach ($matches[0] as $match) {
			$pos = $match[1];
			$subject = $match[0];
			$openTag = str_replace('{subject}', $subject, $this->emailOpenTag);
			$rules[$pos] = array('start' => $pos, 'stop' => $pos+strlen($subject),'openTag' => $openTag,'closeTag' => $this->emailCloseTag);
		}
	}
	
	function url($text,&$rules) {
		$pattern = "/((http[s]?:\/\/[a-z0-9\-\.]+\.[a-z0-9]{2,3})((\/[a-z0-9.\?&\/\#=_\-\%)\(;]*)| |\r\n))/mi";
		preg_match_all($pattern, $text, $matches,PREG_OFFSET_CAPTURE);
		foreach ($matches[0] as $match) {
			$pos = $match[1];
			$subject = trim($match[0]);
			$openTag = str_replace('{subject}', $subject, $this->httpOpenTag);
			$rules[$pos] = array('start' => $pos, 'stop' => $pos+strlen($subject),'openTag' => $openTag,'closeTag' => $this->httpCloseTag);
		}
	}

	function render($text,$rules) {
		$output = '';
		$index = -1;
		foreach ($rules as $rule) {
			if ($rule['start']>$index) {
				if ($index<0) $index=0;
				$output .= substr($text,$index,$rule['start']-$index);
				$output .= $rule['openTag'];
				if (isset($rule['subject'])) {
					$output.=$rule['subject'];
				} else {
					$output .= substr($text,$rule['start'],$rule['stop']-$rule['start']);
				}
				$output .= $rule['closeTag'];
				$index = $rule['stop'];
			}
		}
		if ($index<0) $index=0;
		$output .= substr($text,$index);
		return $output;
	}
}
?>