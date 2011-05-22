hui.ui.getDragProxy = function() {
	if (!hui.ui.dragProxy) {
		hui.ui.dragProxy = hui.build('div',{'class':'in2igui_dragproxy',style:'display:none'});
		document.body.appendChild(hui.ui.dragProxy);
	}
	return hui.ui.dragProxy;
};

hui.ui.startDrag = function(e,element,options) {
	e = new hui.Event(e);
	var info = element.dragDropInfo;
	hui.ui.dropTypes = hui.ui.findDropTypes(info);
	if (!hui.ui.dropTypes) return;
	var proxy = hui.ui.getDragProxy();
	hui.listen(document.body,'mousemove',hui.ui.dragListener);
	hui.listen(document.body,'mouseup',hui.ui.dragEndListener);
	hui.ui.dragInfo = info;
	if (info.icon) {
		proxy.style.backgroundImage = 'url('+hui.ui.getIconUrl(info.icon,1)+')';
	}
	hui.ui.startDragPos = {top:e.top(),left:e.left()};
	proxy.innerHTML = info.title ? '<span>'+hui.escape(info.title)+'</span>' : '###';
	hui.ui.dragging = true;
	document.body.onselectstart = function () { return false; };
};

hui.ui.findDropTypes = function(drag) {
	var gui = hui.ui;
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

hui.ui.dragListener = function(e) {
	e = new hui.Event(e);
	hui.ui.dragProxy.style.left = (e.left()+10)+'px';
	hui.ui.dragProxy.style.top = e.top()+'px';
	hui.ui.dragProxy.style.display='block';
	var target = hui.ui.findDropTarget(e.getElement());
	if (target && hui.ui.dropTypes[target.dragDropInfo['kind']]) {
		if (hui.ui.latestDropTarget) {
			hui.removeClass(hui.ui.latestDropTarget,'in2igui_drop');
		}
		hui.addClass(target,'in2igui_drop');
		hui.ui.latestDropTarget = target;
	} else if (hui.ui.latestDropTarget) {
		hui.removeClass(hui.ui.latestDropTarget,'in2igui_drop');
		hui.ui.latestDropTarget = null;
	}
	return false;
};

hui.ui.findDropTarget = function(node) {
	while (node) {
		if (node.dragDropInfo) {
			return node;
		}
		node = node.parentNode;
	}
	return null;
};

hui.ui.dragEndListener = function(event) {
	hui.unListen(document.body,'mousemove',hui.ui.dragListener);
	hui.unListen(document.body,'mouseup',hui.ui.dragEndListener);
	hui.ui.dragging = false;
	if (hui.ui.latestDropTarget) {
		hui.removeClass(hui.ui.latestDropTarget,'in2igui_drop');
		hui.ui.callDelegatesDrop(hui.ui.dragInfo,hui.ui.latestDropTarget.dragDropInfo);
		hui.ui.dragProxy.style.display='none';
	} else {
		hui.animate(hui.ui.dragProxy,'left',(hui.ui.startDragPos.left+10)+'px',200,{ease:hui.ease.fastSlow});
		hui.animate(hui.ui.dragProxy,'top',(hui.ui.startDragPos.top-5)+'px',200,{ease:hui.ease.fastSlow,hideOnComplete:true});
	}
	hui.ui.latestDropTarget=null;
	document.body.onselectstart=null;
};

hui.ui.dropOverListener = function(event) {
	if (hui.ui.dragging) {
		//this.style.backgroundColor='#3875D7';
	}
};

hui.ui.dropOutListener = function(event) {
	if (hui.ui.dragging) {
		//this.style.backgroundColor='';
	}
};