OP.QuickTimePlayerTemplate = function() {
	this.movie = $id('Movie');
	var dims = document.Movie.GetRectangle().split(',');
	var width = dims[2]-dims[0];
	var height = dims[3]-dims[1];
	this.playButton = $class('play')[0];
	this.stopButton = $class('stop')[0];
	this.restartButton = $class('restart')[0];
	this.startObserver();
	this.addBehavior();
}

OP.QuickTimePlayerTemplate.prototype.addBehavior = function() {
	var self = this;
	this.playButton.onmousedown = function() {
		self.movie.Play();
		return false;
	}
	this.playButton.onclick = function() {return false;}
	this.stopButton.onmousedown = function() {
		self.movie.Stop();
		return false;
	}
	this.stopButton.onclick = function() {return false;}
	this.restartButton.onmousedown = function() {
		self.restart();
		return false;
	}
	this.restartButton.onclick = function() {return false;}
}

OP.QuickTimePlayerTemplate.prototype.startObserver = function() {
	var self = this;
	window.setTimeout(function() {self.observe()}, 50)
}

OP.QuickTimePlayerTemplate.prototype.observe = function() {
	try {
		var rate = document.Movie.GetRate();
		if (rate == 0) {
			this.playButton.style.color='';
		} else {
			this.playButton.style.color='red';
		}
		//window.status=document.Movie.GetMaxBytesLoaded();
	} catch (ignore) {}
	this.startObserver();
 }

OP.QuickTimePlayerTemplate.prototype.restart = function() {
    document.Movie.SetTime(0);
    window.setTimeout('document.Movie.Play()',1000);
}

OP.QuickTimePlayerTemplate.getInstance = function() {
	if (!OP.QuickTimePlayerTemplate.instance) {
		OP.QuickTimePlayerTemplate.instance = new OP.QuickTimePlayerTemplate();
	}
	return OP.QuickTimePlayerTemplate.instance;
}

N2i.Event.addLoadListener(
	function() {
		new OP.QuickTimePlayerTemplate.getInstance();
	}
);