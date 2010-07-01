N2i.InlineImage = function(elementId,path,id,width,height) {
	this.element = $id(elementId);
	this.path = path;
	this.id = id;
	this.width = width;
	this.height = height;
	// Register controller on the element
	this.element.n2iInlineImage = this;
	// Set the click
	this.element.onclick = function(event) {
		return this.n2iInlineImage.imageWasClicked(event);
	}
}

N2i.InlineImage.prototype.buildViewer = function() {
	if (this.viewer) return;
	this.viewer = document.createElement('div');
	this.viewer.style.height='300px';
	this.viewer.style.width='300px';
	this.viewer.style.background="#fff url('"+this.path+"style/basic/graphics/SpinningArrowSlow.gif') center center no-repeat";
	this.viewer.style.border='1px solid #ddd';
	this.viewer.style.position='absolute';
	this.viewer.style.top='0px';
	this.viewer.style.marginLeft='50%';
	this.viewer.style.left='-150px';
	this.viewer.style.display='none';
	this.viewerImage = document.createElement('div');
	this.viewerImage.style.marginLeft='10px';
	this.viewerImage.style.marginTop='10px';
	this.viewerImage.style.cursor='pointer';
	this.viewerImage.n2iInlineImage = this;
	this.viewerImage.onclick = function(event) {
		return this.n2iInlineImage.viewerImageWasClicked(event);
	}
	this.viewer.appendChild(this.viewerImage);
	document.body.appendChild(this.viewer);
}

N2i.InlineImage.prototype.resizeViewer = function(width,height) {
	this.viewer.style.top=(Math.round((N2i.Window.getInnerHeight()-height)/2)+N2i.Window.getScrollTop())-10+'px';
	this.viewer.style.height=(height+20)+'px';
	this.viewer.style.width=(width+20)+'px';
	this.viewer.style.left='-'+Math.round((width+20)/2)+'px';
	this.viewerImage.style.height=height+'px';
	this.viewerImage.style.width=width+'px';
}

/////////////////////////////////////// Event handlers //////////////////////////////////////



N2i.InlineImage.prototype.imageWasClicked = function(event) {
	this.buildViewer();
	var winHeight = N2i.Window.getInnerHeight();
	var winWidth = N2i.Window.getInnerWidth();
	var maxWidth = winWidth-50;
	var maxHeight = winHeight-50;
	var parm = '';
	if (maxHeight>this.height && maxWidth>this.width) {
		var renderWidth = this.width;
		var renderHeight = this.height;
	}
	else if (winHeight/winWidth>this.height/this.width) {
		if (this.width>maxWidth) {
			var renderWidth = Math.floor(maxWidth/400)*400;
		} else {
			var renderWidth = Math.floor(this.width/400)*400;
		}
		var renderHeight = Math.round(this.height/this.width*renderWidth);
		parm = "&maxwidth="+renderWidth;
	} else {
		if (this.height>maxHeight) {
			var renderHeight = Math.floor(maxHeight/200)*200;
		} else {
			var renderHeight = Math.floor(this.height/200)*200;
		}
		var renderWidth = Math.round(this.width/this.height*renderHeight);
		parm = "&maxheight="+renderHeight;
	}
	window.status = renderWidth+'/'+renderHeight;
	this.resizeViewer(renderWidth,renderHeight);
	this.viewerImage.style.backgroundImage="url('"+this.path+"util/images/?id="+this.id+parm+"&format=jpg&quality=90')";
	this.viewer.style.display='';
	return false;
}

N2i.InlineImage.prototype.viewerImageWasClicked = function(event) {
	this.viewerImage.src=null;
	this.viewer.style.display='none';
}