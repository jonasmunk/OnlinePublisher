In2iGui.WindowSheet = function (id,visible,width,windowId) {
	this.id=id;
	this.sheet = document.getElementById(this.id);
	this.windowId = windowId;
	this.visible=visible;
	this.width=width;
	var obj = this;
	this.findDimensions();
	this.timer = window.setInterval(function() {obj.findDimensions()},10);
}

In2iGui.WindowSheet.prototype.findDimensions = function() {
	this.width = getElementDisplayWidth(this.sheet);
	if (this.width>0) {
		this.height = getElementDisplayHeight(this.sheet);
		var win = document.getElementById(this.windowId);
		this.top = getAbsoluteElementTop(win)+getElementDisplayHeight(win);
		this.position();
		window.clearInterval(this.timer);
	}
}

In2iGui.WindowSheet.prototype.position = function() {
	try {
	if (this.windowId) {
		var win = document.getElementById(this.windowId);
		if (win) {
			var winWidth = getElementDisplayWidth(win);
			this.sheet.style.top = this.top+'px';
			this.sheet.style.marginLeft = Math.round((winWidth-this.width)/2)+'px';
		}
	}
	if (this.visible) {
		this.sheet.style.visibility='visible';
	}
	} catch(ignore) {}
}


In2iGui.WindowSheet.prototype.show = function() {
	if (!this.visible) {
		this.visible=true;
		this.sheet.style.top=this.top+'px';
		var element = this.sheet;
		var top = this.top;
		var width = this.width;
		var height = this.height;
		setTimeout(
			function() {
				In2iGui.WindowSheet.showIterator(element,top,width,height,0);
			}
		,0);
	}
	this.position();
}

In2iGui.WindowSheet.prototype.hide = function() {
	if (this.visible) {
		this.visible=false;
		setTimeout('In2iGui.WindowSheet.hideIterator(\''+this.id+'\','+this.top+','+this.width+','+this.height+',0,'+this.height+');',0)
	}
}

In2iGui.WindowSheet.prototype.setWindowId = function(id) {
	this.windowId = id;
}

In2iGui.WindowSheet.hideIterator = function(id,top,width,height,currTop,currHeight) {
	var element = document.getElementById(id);
	if (currHeight>0) {
		currHeight-=10;
		currTop-=10;
		element.style.top=(top+currTop)+'px';
		element.style.clip='rect('+(height-currHeight)+'px '+width+'px '+height+'px 0px)';
		setTimeout('In2iGui.WindowSheet.hideIterator(\''+id+'\','+top+','+width+','+height+','+currTop+','+currHeight+');',10)
	}
}

In2iGui.WindowSheet.showIterator = function(element,top,width,height,currHeight) {
	if (height>currHeight) {
		currHeight+=10;
		if (currHeight>height) currHeight=height;
		element.style.top = (top-height+currHeight)+'px';
		element.style.clip='rect('+(height-currHeight)+'px '+width+'px '+height+'px 0px)';
		setTimeout(
			function() {
				In2iGui.WindowSheet.showIterator(element,top,width,height,currHeight);
			}
			,20);
	}
	else {
		if (navigator.userAgent.indexOf('Gecko')>-1) {
			element.style.clip='auto';
		}
		else {
			element.style.clip='rect(auto)';
		}
	}
}