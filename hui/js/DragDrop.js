/** Send a global drag and drop message */
hui.ui.callDelegatesDrop = function(dragged,dropped) {
	for (var i=0; i < hui.ui.delegates.length; i++) {
		if (hui.ui.delegates[i]['$drop$'+dragged.kind+'$'+dropped.kind]) {
			hui.ui.delegates[i]['$drop$'+dragged.kind+'$'+dropped.kind](dragged,dropped);
		}
	}
};

/** @private */
hui.ui.getDragProxy = function() {
	if (!hui.ui.dragProxy) {
		hui.ui.dragProxy = hui.build('div',{'class':'hui_dragproxy',style:'display:none'});
		document.body.appendChild(hui.ui.dragProxy);
	}
	return hui.ui.dragProxy;
};

/** @private */
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
		proxy.style.backgroundImage = 'url('+hui.ui.getIconUrl(info.icon,16)+')';
	}
	hui.ui.startDragPos = {top:e.getTop(),left:e.getLeft()};
	proxy.innerHTML = info.title ? '<span>'+hui.string.escape(info.title)+'</span>' : '###';
	hui.ui.dragging = true;
	hui.selection.enable(false);
};

/** @private */
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

/** @private */
hui.ui.dragListener = function(e) {
	e = new hui.Event(e);
	hui.ui.dragProxy.style.left = (e.getLeft()+10)+'px';
	hui.ui.dragProxy.style.top = e.getTop()+'px';
	hui.ui.dragProxy.style.display='block';
	var target = hui.ui.findDropTarget(e.getElement());
	if (target && hui.ui.dropTypes[target.dragDropInfo['kind']]) {
		if (hui.ui.latestDropTarget) {
			hui.cls.remove(hui.ui.latestDropTarget,'hui_drop');
		}
		hui.cls.add(target,'hui_drop');
		hui.ui.latestDropTarget = target;
	} else if (hui.ui.latestDropTarget) {
		hui.cls.remove(hui.ui.latestDropTarget,'hui_drop');
		hui.ui.latestDropTarget = null;
	}
	return false;
};

/** @private */
hui.ui.findDropTarget = function(node) {
	while (node) {
		if (node.dragDropInfo) {
			return node;
		}
		node = node.parentNode;
	}
	return null;
};

/** @private */
hui.ui.dragEndListener = function(event) {
	hui.unListen(document.body,'mousemove',hui.ui.dragListener);
	hui.unListen(document.body,'mouseup',hui.ui.dragEndListener);
	hui.ui.dragging = false;
	if (hui.ui.latestDropTarget) {
		hui.cls.remove(hui.ui.latestDropTarget,'hui_drop');
		hui.ui.callDelegatesDrop(hui.ui.dragInfo,hui.ui.latestDropTarget.dragDropInfo);
		hui.ui.dragProxy.style.display='none';
	} else {
		hui.animate(hui.ui.dragProxy,'left',(hui.ui.startDragPos.left+10)+'px',200,{ease:hui.ease.fastSlow});
		hui.animate(hui.ui.dragProxy,'top',(hui.ui.startDragPos.top-5)+'px',200,{ease:hui.ease.fastSlow,hideOnComplete:true});
	}
	hui.ui.latestDropTarget=null;
	hui.selection.enable(false);
};

/** @private */
hui.ui.dropOverListener = function(event) {
	if (hui.ui.dragging) {
		//this.style.backgroundColor='#3875D7';
	}
};

/** @private */
hui.ui.dropOutListener = function(event) {
	if (hui.ui.dragging) {
		//this.style.backgroundColor='';
	}
};