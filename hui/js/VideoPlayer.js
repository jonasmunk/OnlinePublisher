/** A video player
 * @constructor
 */
hui.ui.VideoPlayer = function(options) {
	this.options = options;
	this.element = hui.get(options.element);
	this.placeholder = hui.get.firstByTag(this.element,'div');
	this.name = options.name;
	this.state = {duration:0,time:0,loaded:0};
	this.handlers = [hui.ui.VideoPlayer.HTML5,hui.ui.VideoPlayer.QuickTime,hui.ui.VideoPlayer.Embedded];
	this.handler = null;
	hui.ui.extend(this);
	if (this.options.video) {
		if (this.placeholder) {
			hui.listen(this.placeholder,'click',function() {
				this.setVideo(this.options.video);
			}.bind(this))
		} else {
			hui.ui.onReady(function() {
				this.setVideo(this.options.video);
			}.bind(this));			
		}
	}
}

hui.ui.VideoPlayer.prototype = {
	setVideo : function(video) {
		if (this.placeholder) {
			this.placeholder.style.display='none';
		}
		this.handler = this.getHandler(video);
		this.element.appendChild(this.handler.element);
		if (this.handler.showController()) {
			this.buildController();
		}
	},
	getHandler : function(video) {
		for (var i=0; i < this.handlers.length; i++) {
			var handler = this.handlers[i];
			if (handler.isSupported(video)) {
				return new handler(video,this);
			}
		};
	},
	buildController : function() {
		var e = hui.build('div',{'class':'hui_videoplayer_controller',parent:this.element});
		this.playButton = hui.build('a',{href:'javascript:void(0);','class':'hui_videoplayer_playpause',text:'wait!',parent:e});
		hui.listen(this.playButton,'click',this.playPause.bind(this));
		this.status = hui.build('span',{'class':'hui_videoplayer_status',parent:e});
	},
	onCanPlay : function() {
		this.playButton.update('Play');
	},
	onLoad : function() {
		this.state.loaded = this.state.duration;
		this.updateStatus();
	},
	onDurationChange : function(duration) {
		this.state.duration = duration;
		this.updateStatus();
	},
	onTimeChange : function(time) {
		this.state.time = time;
		this.updateStatus();
	},
	onLoadProgressChange : function(progress) {
		this.state.loaded = progress;
		this.updateStatus();
	},
	playPause : function() {
		if (this.handler.isPlaying()) {
			this.pause();
		} else {
			this.play();
		}
	},
	play : function() {
		this.handler.play();
	},
	pause : function() {
		this.handler.pause();
	},
	updateStatus : function() {
		this.status.innerHTML = this.state.time+' / '+this.state.duration+' / '+this.state.loaded;
	}
}

///////// HTML5 //////////

hui.ui.VideoPlayer.HTML5 = function(video,player) {
	var e = this.element = hui.build('video',{width:video.width,height:video.height,src:video.src});
	hui.listen(e,'load',player.onLoad.bind(player));
	hui.listen(e,'canplay',player.onCanPlay.bind(player));
	hui.listen(e,'durationchange',function(x) {
		player.onDurationChange(e.duration);
	});
	hui.listen(e,'timeupdate',function() {
		player.onTimeChange(this.element.currentTime);
	}.bind(this));
}

hui.ui.VideoPlayer.HTML5.isSupported = function(video) {
	if (hui.browser.webkitVersion>528 && (video.type==='video/quicktime' || video.type==='video/mp4')) {
		return true;
	}
	return false;
}

hui.ui.VideoPlayer.HTML5.prototype = {
	showController : function() {
		return true;
	},
	pause : function() {
		this.element.pause();
	},
	play : function() {
		this.element.play();
	},
	getTime : function() {
		return this.element.currentTime;
	},
	isPlaying : function() {
		return !this.element.paused;
	}
}

///////// QuickTime //////////

hui.ui.VideoPlayer.QuickTime = function(video,player) {
	this.player = player;
	var e = this.element = hui.build('object',{width:video.width,height:video.height,data:video.src,type:'video/quicktime'});
	e.innerHTML = '<param value="false" name="controller"/>'
		+'<param value="true" name="enablejavascript"/>'
		+'<param value="undefined" name="posterframe"/>'
		+'<param value="false" name="showlogo"/>'
		+'<param value="false" name="autostart"/>'
		+'<param value="true" name="cache"/>'
		+'<param value="white" name="bgcolor"/>'
		+'<param value="false" name="aggressivecleanup"/>'
		+'<param value="true" name="saveembedtags"/>'
		+'<param value="true" name="postdomevents"/>';
		
	hui.listen(e,'qt_canplay',player.onCanPlay.bind(player));
	hui.listen(e,'qt_load',player.onLoad.bind(player));
	hui.listen(e,'qt_progress',function() {
		player.onLoadProgressChange(e.GetMaxTimeLoaded()/3000);
	});
	hui.listen(e,'qt_durationchange',function(x) {
		player.onDurationChange(e.GetDuration()/3000);
	});
	hui.listen(e,'qt_timechanged',function() {
		player.onTimeChange(e.GetTime());
	})
}

hui.ui.VideoPlayer.QuickTime.isSupported = function(video) {
	return video.html==undefined;
}

hui.ui.VideoPlayer.QuickTime.prototype = {
	showController : function() {
		return true;
	},
	pause : function() {
		window.clearInterval(this.observer);
		this.element.Stop();
	},
	play : function() {
		this.element.Play();
		this.observer = window.setInterval(this.observeVideo.bind(this),100);
	},
	observeVideo : function() {
		this.player.onTimeChange(this.element.GetTime()/3000);
	},
	getTime : function() {
		return this.element.GetTime();
	},
	isPlaying : function() {
		return this.element.GetRate()!==0;
	}
}

///////// Embedded //////////

hui.ui.VideoPlayer.Embedded = function(video,player) {
	this.element = hui.build('div',{width:video.width,height:video.height,html:video.html});
}

hui.ui.VideoPlayer.Embedded.isSupported = function(video) {
	return video.html!==undefined;
}

hui.ui.VideoPlayer.Embedded.prototype = {
	showController : function() {
		return false;
	},
	pause : function() {
		
	},
	play : function() {
		
	},
	getTime : function() {
		
	},
	isPlaying : function() {
		
	}
}

/* EOF */