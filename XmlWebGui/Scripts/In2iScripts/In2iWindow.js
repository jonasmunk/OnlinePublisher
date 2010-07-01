if (!N2i) var N2i = {};


N2i.Window = function(delegate) {
	this.delegate = delegate || {};
	this.window = null;
	this.content = null;
	this.titlebar = null;
	this.build();
}

N2i.Window.latestZindex = 1000;

N2i.Window.prototype.build = function() {
	var self = this;
	this.window=document.createElement('div');
	this.window.className='in2iwindow';
	this.window.style.display='none';
	var html = '<div class="titlebar"><div><div><div class="close"></div><strong>'+this.delegate.title+'</strong></div></div></div><div class="body"><div class="body"><div class="body"><div class="content"></div></div></div></div><div class="bottom"><div><div></div></div></div>';
	this.window.innerHTML=html;
	this.content=$class('content',this.window)[0];
	if (this.delegate.size) {
		this.content.style.width=this.delegate.size.width+'px';
		this.content.style.height=this.delegate.size.height+'px';
	}
	if (this.delegate.getContent) {
		this.content.appendChild(this.delegate.getContent());
	}
	this.titlebar = $class('titlebar',this.window)[0];
	this.titlebar.onmousedown = function(e) {self.startDrag(e);return false;};
	var close = $class('close',this.titlebar)[0];
	close.onclick = function() {self.hide()};
	if (this.delegate.position) {
		this.window.style.top=this.delegate.position.top+'px';
		this.window.style.left=this.delegate.position.left+'px';
	}
	N2i.Window.latestZindex++;
	this.window.style.zIndex=N2i.Window.latestZindex;
	document.body.appendChild(this.window);
	/*
	this.window.onmouseover = function() {
		$ani(this,'opacity',1,200);
	}
	this.window.onmouseout = function() {
		$ani(this,'opacity',.5,1000);
	}
	*/
}

N2i.Window.prototype.changeContent = function(node) {
	this.content.innerHTML='';
	this.content.appendChild(node);
}

N2i.Window.prototype.show = function() {
	this.window.style.display='';
}

N2i.Window.prototype.hide = function() {
	this.window.style.display='none';
	if (this.delegate.windowDidClose) {
		this.delegate.windowDidClose();
	}
}

N2i.Window.prototype.startDrag = function(e) {
	N2i.Window.latestZindex++;
	this.window.style.zIndex=N2i.Window.latestZindex;
	var event = new N2i.Event(e);
	this.dragState = {left:event.mouseLeft()-N2i.Element.getLeft(this.window),top:event.mouseTop()-N2i.Element.getTop(this.window)};
	var self = this;
	this.moveListener = function(e) {self.drag(e)};
	this.upListener = function(e) {self.endDrag(e)};
	N2i.Event.addListener(document,'mousemove',this.moveListener);
	N2i.Event.addListener(document,'mouseup',this.upListener);
	N2i.Event.stop(e);
	return false;
}

N2i.Window.prototype.drag = function(e) {
	var event = new N2i.Event(e);
	this.window.style.right = 'auto';
	this.window.style.top = (event.mouseTop()-this.dragState.top)+'px';
	this.window.style.left = (event.mouseLeft()-this.dragState.left)+'px';
	return false;
}

N2i.Window.prototype.endDrag = function() {
	N2i.Event.removeListener(document,'mousemove',this.moveListener);
	N2i.Event.removeListener(document,'mouseup',this.upListener);
}

/** Utility methods **/

N2i.Window.createButton = function(title) {
	var a = document.createElement('a');
	var span1 = document.createElement('span');
	var span2 = document.createElement('span');
	a.setAttribute('class','button');
	a.appendChild(span1);
	span1.appendChild(span2);
	span2.appendChild(document.createTextNode(title));
	return a;
}

N2i.Window.buildButton = function(title,delegate) {
	var a = document.createElement('a');
	var span1 = document.createElement('span');
	var span2 = document.createElement('span');
	a.setAttribute('class','button');
	a.appendChild(span1);
	span1.appendChild(span2);
	span2.appendChild(document.createTextNode(title));
	if (delegate.buttonWasClicked) {
		a.onclick = function() {
			delegate.buttonWasClicked(a);
		}
	}
	return a;
}


N2i.Window.createList = function(data,delegate) {
	delegate = delegate || {};
	var table = document.createElement('table');
	table.setAttribute('cellspacing','0');
	table.setAttribute('cellpadding','0');
	table.setAttribute('class','list');
	for (var i=0;i<data.length;i++) {
		var item = data[i];
		var row = document.createElement('tr');
		row.n2iWindowId = item.id;
		row.onclick = function() {
			if (delegate.itemWasClicked) {
				delegate.itemWasClicked(this.n2iWindowId);
			}
		}
		if (item.image) {
			var imageCell = document.createElement('td');
			imageCell.className='image';
			var image = document.createElement('img');
			image.src=item.image;
			imageCell.appendChild(image);
			row.appendChild(imageCell);
		}
		var cell = document.createElement('td');
		cell.appendChild(document.createTextNode(item.title));
		row.appendChild(cell);
		table.appendChild(row);
	}
	return table;
}