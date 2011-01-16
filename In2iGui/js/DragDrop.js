In2iGui.getDragProxy = function() {
	if (!In2iGui.dragProxy) {
		In2iGui.dragProxy = n2i.build('div',{'class':'in2igui_dragproxy',style:'display:none'});
		document.body.appendChild(In2iGui.dragProxy);
	}
	return In2iGui.dragProxy;
};

In2iGui.startDrag = function(e,element,options) {
	e = new n2i.Event(e);
	var info = element.dragDropInfo;
	In2iGui.dropTypes = In2iGui.findDropTypes(info);
	if (!In2iGui.dropTypes) return;
	var proxy = In2iGui.getDragProxy();
	n2i.listen(document.body,'mousemove',In2iGui.dragListener);
	n2i.listen(document.body,'mouseup',In2iGui.dragEndListener);
	In2iGui.dragInfo = info;
	if (info.icon) {
		proxy.style.backgroundImage = 'url('+In2iGui.getIconUrl(info.icon,1)+')';
	}
	In2iGui.startDragPos = {top:e.top(),left:e.left()};
	proxy.innerHTML = info.title ? '<span>'+n2i.escape(info.title)+'</span>' : '###';
	In2iGui.dragging = true;
	document.body.onselectstart = function () { return false; };
};

In2iGui.findDropTypes = function(drag) {
	var gui = In2iGui.get();
	var drops = null;
	for (var i=0; i < gui.delegates.length; i++) {
		if (gui.delegates[i].dragDrop) {
			for (var j=0; j < gui.delegates[i].dragDrop.length; j++) {
				var rule = gui.delegates[i].dragDrop[j];
				if (rule.drag==drag.kind) {
					if (drops==null) drops={};
					drops[rule.drop] = {};
				}
			};
		}
	}
	return drops;
};

In2iGui.dragListener = function(e) {
	e = new n2i.Event(e);
	In2iGui.dragProxy.style.left = (e.left()+10)+'px';
	In2iGui.dragProxy.style.top = e.top()+'px';
	In2iGui.dragProxy.style.display='block';
	var target = In2iGui.findDropTarget(e.getElement());
	if (target && In2iGui.dropTypes[target.dragDropInfo['kind']]) {
		if (In2iGui.latestDropTarget) {
			n2i.removeClass(In2iGui.latestDropTarget,'in2igui_drop');
		}
		n2i.addClass(target,'in2igui_drop');
		In2iGui.latestDropTarget = target;
	} else if (In2iGui.latestDropTarget) {
		n2i.removeClass(In2iGui.latestDropTarget,'in2igui_drop');
		In2iGui.latestDropTarget = null;
	}
	return false;
};

In2iGui.findDropTarget = function(node) {
	while (node) {
		if (node.dragDropInfo) {
			return node;
		}
		node = node.parentNode;
	}
	return null;
};

In2iGui.dragEndListener = function(event) {
	n2i.unListen(document.body,'mousemove',In2iGui.dragListener);
	n2i.unListen(document.body,'mouseup',In2iGui.dragEndListener);
	In2iGui.dragging = false;
	if (In2iGui.latestDropTarget) {
		n2i.removeClass(In2iGui.latestDropTarget,'in2igui_drop');
		In2iGui.callDelegatesDrop(In2iGui.dragInfo,In2iGui.latestDropTarget.dragDropInfo);
		In2iGui.dragProxy.style.display='none';
	} else {
		n2i.ani(In2iGui.dragProxy,'left',(In2iGui.startDragPos.left+10)+'px',200,{ease:n2i.ease.fastSlow});
		n2i.ani(In2iGui.dragProxy,'top',(In2iGui.startDragPos.top-5)+'px',200,{ease:n2i.ease.fastSlow,hideOnComplete:true});
	}
	In2iGui.latestDropTarget=null;
	document.body.onselectstart=null;
};

In2iGui.dropOverListener = function(event) {
	if (In2iGui.dragging) {
		//this.style.backgroundColor='#3875D7';
	}
};

In2iGui.dropOutListener = function(event) {
	if (In2iGui.dragging) {
		//this.style.backgroundColor='';
	}
};