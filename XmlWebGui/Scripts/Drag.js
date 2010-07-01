var dropIds = new Array();
var dragIds = new Array();
var otherFrames = new Array();
var delegateActive = false;
var dragMode = null;
document.onmousemove = dragger;
var startX = 0;
var startY = 0;
var dragObject = null;
var dragActive;


function registerDragObject(id)
{
	try {
		dragIds[dragIds.length]=id;
		o = document.getElementById(id);
		o.onmousedown	= startDragging;
	} catch(e) {
		debug(e);
	}
}

function registerDropArea(id) {
	dropIds[dropIds.length]=id;
}

function stopDragging(e) {
	dragMode=null;
	var drops = getOverlay(lastMouseX,lastMouseY);
	if (drops.length>0) {
		document.forms.DragForm.dragId.value=dragObject.id;
		document.forms.DragForm.dropId.value=drops;
		document.forms.DragForm.submit();
	}
	else {
		var libObj = new LibraryObject('DragProxy');
		libObj.slideTo(startX,startY,20,10,'hideDragProxy()');
		clearOverlay();
	}
	for (i=0;i<otherFrames.length;i++) {
		try {
			eval(otherFrames[i]+'.exitDragging();');
		} catch(ignore) {}
	}
}

function setDragActive(bool) {
	if (bool!=dragActive) {
		var dragProxy = document.getElementById('DragProxy');
		if (bool) {
			dragProxy.style.display='inline';
		}
		else {
			dragProxy.style.display='none';
			clearOverlay();
		}
		dragActive=bool;
	}
}

function stopDelegate(e) {
	exitDragging();
	for (i=0;i<otherFrames.length;i++) {
		try {
			eval(otherFrames[i]+'.exitDragging();');
		} catch(ignore) {}
	}
	var drops = getOverlay(lastMouseX,lastMouseY);
	if (drops.length>0) {
		document.forms.DragForm.dragId.value=dragObject.id;
		document.forms.DragForm.dropId.value=drops;
		document.forms.DragForm.submit();
	}
}

function exitDragging() {
	dragMode=null;
	hideDragProxy();
	clearOverlay();
}

function registerFrame(obj) {
	otherFrames[otherFrames.length] = obj;
}

function hideDragProxy() {
	document.getElementById('DragProxy').style.display='none';
}

function dragger(e) {
	if (dragMode=='drag') {
		var dragProxy = document.getElementById('DragProxy');
		
		var scroll = getScrollXY();
		
		var y	= e.clientY;
		var x	= e.clientX;
		dragProxy.style["left"] = x-16+scroll[0] + "px";
		dragProxy.style["top"] = y-16+scroll[1] + "px";
		lastMouseX = x;
		lastMouseY = y;
		displayOverlay(x,y,dragObject.id);
		for (i=0;i<otherFrames.length;i++) {
			try {
				eval(otherFrames[i]+'.setDragActive(false);');
			} catch(ignore) {}
		}
		setDragActive(true);
		return false;
	}
}

function startDelegate(obj) {
	dragObject = obj;
	dragMode='drag';
	document.onclick=stopDelegate;
}

function startDragging(e) {
	if (e.altKey) {
		dragObject = this;
		dragMode='drag';
		document.onclick=stopDragging;
		var scroll = getScrollXY();
		for (i=0;i<otherFrames.length;i++) {
			try {
				eval(otherFrames[i]+'.startDelegate(this);');
			} catch(ignore) {}
		}
		y = e.clientY-16+scroll[1];
		x = e.clientX-16+scroll[0];

		startY = y;
		startX = x;

		var dragProxy = document.getElementById('DragProxy');
		dragProxy.style["left"] = x + "px";
		dragProxy.style["top"] = y + "px";
		dragProxy.style.display='inline';
		return false;
	}
	else {
		return true;
	}	
}

function fixEvent(e) {
	if (typeof e == 'undefined') e = window.event;
	if (typeof e.layerX == 'undefined') e.layerX = e.offsetX;
	if (typeof e.layerY == 'undefined') e.layerY = e.offsetY;
	return e;
}

function debug(val) {
	//document.getElementById('debug').value=val;
}

function displayOverlay(ex,ey,id) {
	var scroll = getScrollXY();
	ey+=scroll[0];
	ey+=scroll[1];
	var output=false;
	for (var i=0; i < dropIds.length; i++) {
		elementId=dropIds[i];
      if (id!=elementId) {
			if (ex>getElementLeft(elementId) && ex<(getElementLeft(elementId)+getElementWidth(elementId)) && ey>getElementTop(elementId) && ey<(getElementTop(elementId)+getElementHeight(elementId))) {
				document.getElementById(elementId).style.backgroundColor='lightgrey';
				output = output && true;
			}
			else {
				document.getElementById(elementId).style.backgroundColor='transparent';
			}
		}
	}
	return output;
}

function getOverlay(ex,ey) {
	var scroll = getScrollXY();
	ey+=scroll[0];
	ey+=scroll[1];
    var output=new Array();
    for (var i=0; i < dropIds.length; i++) {
        elementId=dropIds[i];
        if (dragObject.id!=elementId) {
			if (ex>getElementLeft(elementId) && ex<(getElementLeft(elementId)+getElementWidth(elementId)) && ey>getElementTop(elementId) && ey<(getElementTop(elementId)+getElementHeight(elementId))) {
				output[output.length]=elementId;
			}
		}
	}
	return output;
}

function clearOverlay(ex,ey) {
    for (var i=0; i < dropIds.length; i++) {
        elementId=dropIds[i];
		document.getElementById(elementId).style.backgroundColor='transparent';
	}
}