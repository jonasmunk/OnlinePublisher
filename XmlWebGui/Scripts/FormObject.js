if (!In2iGui.Form) In2iGui.Form = {};

In2iGui.Form.Object = function(id) {
	this.id = id;
	this.translation = {none:'',choose:''};
	this.source = {list:''};
	this.base = $id(this.id+'-base');
	this.icon = $id(this.id+'-icon');
	this.image = $id(this.id+'-image');
	this.title = $class('title',this.base)[0];
	this.description = $class('description',this.base)[0];
	this.value = $tag('input',this.base)[0];
	this.changeButton = $id(this.id+'-change');
	this.changeButton.in2iGuiFormObject = this;
	this.changeButton.onclick = function() {
		this.in2iGuiFormObject.changeButtonWasClicked();
		return false;
	}
	this.removeButton = $id(this.id+'-remove');
	if (this.removeButton) {
		this.removeButton.in2iGuiFormObject = this;
		this.removeButton.onclick = function() {
			this.in2iGuiFormObject.removeButtonWasClicked();
			return false;
		}
	}
}

In2iGui.Form.Object.prototype.changeButtonWasClicked = function() {
	if (!this.window) {
		this.windowDelegate = {title:this.translation.choose,size:{width: 300,height:250},position:{top:50,left:50}};
		this.window = new N2i.Window(this.windowDelegate);
	}
	this.window.show();
	var self = this;
	var delegate = {
		onSuccess : function(t) {
			self.updateWindow(t.responseXML);
		}
	}
	var request = new N2i.Request(delegate);
	request.request(this.source.list+'?'+new Date());
}

In2iGui.Form.Object.prototype.itemWasClicked = function(id) {
	this.window.hide();
	this.changeObject(this.data[id]);
}

In2iGui.Form.Object.prototype.removeButtonWasClicked = function() {
	this.removeObject();
}

In2iGui.Form.Object.prototype.updateWindow = function(doc) {
	var list = this.buildListData(doc);
	this.window.changeContent(N2i.Window.createList(list,this));
}

In2iGui.Form.Object.prototype.buildListData = function(doc) {
	this.data = [];
	var list = [];
	var objects = doc.getElementsByTagName('entity');
	for (var i=0; i < objects.length; i++) {
		var title = objects[i].getAttribute('title');
		var value = objects[i].getAttribute('value');
		var description = objects[i].getAttribute('description');
		var icon = objects[i].getAttribute('icon');
		var image = objects[i].getAttribute('image');
		var item = {
			title : title,
			id : value
		}
		if (image) {
			item.image = image;
		} else if (icon) {
			item.image = In2iGui.paths.iconset+icon+'Standard2.gif';
		}
		list[list.length] = item;
		this.data[value] = {title:title,value:value,description:description,icon:icon,image:image};
	}
	return list;
}

In2iGui.Form.Object.prototype.displayObjectList = function(doc) {
	var list = $id(this.id+'-list');
	var html = '';
	var objects = doc.getElementsByTagName('entity');
	for (var i=0; i < objects.length; i++) {
		var title = objects[i].getAttribute('title');
		var value = objects[i].getAttribute('value');
		var description = objects[i].getAttribute('description');
		var icon = objects[i].getAttribute('icon');
		var image = objects[i].getAttribute('image');
		var click = this.id+'_object.objectWasSelected(event,{value:\''+value+'\',description:\''+this.makeSafe(description)+'\',icon:\''+(icon || '')+'\',image:\''+(image || '')+'\',title:\''+this.makeSafe(title)+'\'});';
		html+='<div onclick="'+click+'" onmouseover="N2i.Element.addClassName(this,\'hover\');" onmouseout="N2i.Element.removeClassName(this,\'hover\');"';
		if (icon || image) {
			html+=' class="icon" style="background-image: url(\'';
			if (icon) {
				html+=In2iGui.paths.iconset+icon+'Standard2.gif';
			} else if (image) {
				html+=image;
			}
			html+='\');"';
		}
		html+='><strong>'+title+'</strong><br/>';
		html+='<span>'+(description || '')+'</span>';
		html+='</div>';
	};
	//alert(html);
	list.innerHTML = html;
	var self = this;
	N2i.Event.addListener(document,'click',function() {self.hideObjectList()});
	list.style.display='block';
}

In2iGui.Form.Object.prototype.makeSafe = function(str) {
	if (str) {
		return str.replace(/'/g,'\\\'').replace(/"/g,'&quot;');
	} else {
		return '';
	}
}

In2iGui.Form.Object.prototype.objectWasSelected = function(e,object) {
	N2i.Event.stop(e);
	this.hideObjectList();
	this.changeObject(object);
}

In2iGui.Form.Object.prototype.changeObject = function(object) {
	this.title.innerHTML = object.title;
	this.description.innerHTML = object.description || '';
	this.value.value = object.value;
	if (object.icon) {
		this.icon.src = In2iGui.paths.iconset+object.icon+'Standard2.gif';
		this.icon.style.display = 'inline';
	} else {
		this.icon.style.display = 'none';
	}
	if (object.image) {
		this.image.style.backgroundImage = "url('"+object.image+"')";
		this.image.style.display = 'block';
	} else {
		this.image.style.display = 'none';
	}
	if (this.removeButton) {
		this.removeButton.style.display='';
	}
}

In2iGui.Form.Object.prototype.removeObject = function() {
	this.title.innerHTML = this.translation.none;
	this.description.innerHTML = '';
	this.value.value = '';
	this.icon.style.display = 'none';
	this.image.style.display = 'none';
	this.removeButton.style.display='none';
}

In2iGui.Form.Object.prototype.hideObjectList = function() {
	var list = $id(this.id+'-list');
	list.style.display='';
}

In2iGui.Form.Object.prototype.setHint = function(str) {
	var obj = document.getElementById(this.id+'-HINT');
	obj.innerHTML=str;
	if (str.length>0) {
		obj.style.display='block';
	}
	else {
		obj.style.display='none';
	}
};

In2iGui.Form.Object.prototype.setError = function(str) {
	var obj = document.getElementById(this.id+'-ERROR');
	obj.innerHTML=str;
	if (str.length>0) {
		obj.style.display='block';
	}
	else {
		obj.style.display='none';
	}
};

In2iGui.Form.Object.prototype.blinkError = function (millis) {
	var b = setInterval('blinkSomething(\''+this.id+'-ERROR\')',200);
	setTimeout('window.clearInterval('+b+');',millis);
	setTimeout('document.getElementById(\''+this.id+'-ERROR\').style.visibility=\'visible\';',millis+100);
};


In2iGui.Form.Object.prototype.blinkHint = function (millis) {
	var b = setInterval('blinkSomething(\''+this.id+'-HINT\')',200);
	setTimeout('window.clearInterval('+b+');',millis);
	setTimeout('document.getElementById(\''+this.id+'-HINT\').style.visibility=\'visible\';',millis+100);
};

function blinkSomething(id) {
	var element=document.getElementById(id);
	if (element.style.visibility=='hidden') {
		element.style.visibility='visible';
	}
	else {
		element.style.visibility='hidden';
	}
};