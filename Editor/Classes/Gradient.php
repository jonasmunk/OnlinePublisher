<?
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class Gradient {
	var $red = array();
	var $green = array();
	var $blue = array();
	var $position = array();
	var $rotation = 0;
	var $vertical = false;
  
	function addcolor($red,$green,$blue,$position) {
		if(@in_array((int)$position,$this->position)){
			return false;
		}
		$this->red[sizeof($this->red)] = (int)$red;
		$this->green[sizeof($this->green)] = (int)$green;
		$this->blue[sizeof($this->blue)] = (int)$blue;
		$this->position[sizeof($this->position)] = (int)$position;
		return true;
	}

	function buildgradient($width,$height) {
		if($im = @imagecreate((int)$width,(int)$height))
		{
			asort($this->position);
			$percentage = (int)$width / 100;
			$last = null;
			foreach($this->position as $id => $value)
			{
				if(is_numeric($last)){
					$c['red'] = $this->red[$last];
					$c['green'] = $this->green[$last];
					$c['blue'] = $this->blue[$last];
					$diff = $this->position[$last] - $value;
					$p['red'] = ($this->red[$last] - $this->red[$id]) / $diff;
					$p['green'] = ($this->green[$last] - $this->green[$id]) / $diff;
					$p['blue'] = ($this->blue[$last] - $this->blue[$id]) / $diff;
					for($i=$this->position[$last];$i<$value;$i++)
					{
						$w = floor($i * $percentage);
						$color = @imagecolorallocate($im,floor($c['red']),floor($c['green']),floor($c['blue']));
						@imagefilledrectangle($im,$w,0,floor($w + $percentage),(int)$height,$color);

						$c['red'] += $p['red'];
						$c['green'] += $p['green'];
						$c['blue'] += $p['blue'];
					}
				}
				$last = $id;
			}
			if ($this->vertical) {
				$im = imagerotate($im, 90, 0);
			}
			return $im;
		}else return false;
	}
	function clearcache()
	{
		$this->red = array();
		$this->green = array();
		$this->blue = array();
		$this->position = array();
		$this->rotation = 0;
		return true;
	}
}
?>