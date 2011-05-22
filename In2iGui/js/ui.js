/**
  The namespace of the HUI framework
  @namespace
 */
hui.ui = {
	domReady : false,
	context : '',
	language : 'en',

	layoutWidgets : [],
	objects : [],
	delegates : [],

	state : 'default',

	latestObjectIndex : 0,
	latestIndex : 500,
	latestPanelIndex : 1000,
	latestAlertIndex : 1500,
	latestTopIndex : 2000,
	toolTips : {},
	confirmOverlays : {}
}

//window.ui = hui.ui; // TODO: old namespace

//window.In2iGui = hui.ui; // TODO: old namespace


/** Gets the one instance of In2iGui */
hui.ui.get = function(nameOrWidget) {
	if (nameOrWidget) {
		if (nameOrWidget.element) {
			return nameOrWidget;
		}
		return hui.ui.objects[nameOrWidget];
	} else {
		return hui.ui;
	}
};

hui.onReady(function() {
	if (window.dwr && window.dwr.engine && window.dwr.engine.setErrorHandler) {
		window.dwr.engine.setErrorHandler(function(msg,e) {
			hui.log(msg);
			hui.log(e);
		});
	}
	hui.ui.callSuperDelegates(this,'ready');
	hui.listen(window,'resize',hui.ui._resize);
	hui.ui._resize();
	hui.ui.domReady = true;
	if (window.parent && window.parent.hui && window.parent.hui.ui) {
		window.parent.hui.ui._frameLoaded(window);
	}
});

hui.ui._frameLoaded = function(win) {
	hui.ui.callSuperDelegates(this,'frameLoaded',win);
}

/** @private */
hui.ui._resize = function() {
	for (var i = hui.ui.layoutWidgets.length - 1; i >= 0; i--){
		hui.ui.layoutWidgets[i]['$$layout']();
	};
}

hui.ui.confirmOverlay = function(options) {
	var node = options.element || options.widget.getElement(),
		overlay;
	if (hui.ui.confirmOverlays[node]) {
		overlay = hui.ui.confirmOverlays[node];
		overlay.clear();
	} else {
		overlay = hui.ui.Overlay.create({modal:true});
		hui.ui.confirmOverlays[node] = overlay;
	}
	if (options.text) {
		overlay.addText(options.text);
	}
	var ok = hui.ui.Button.create({text:options.okText || 'OK',highlighted:'true'});
	ok.click(function() {
		if (options.onOk) {
			options.onOk();
		}
		overlay.hide();
	});
	overlay.add(ok);
	var cancel = hui.ui.Button.create({text:options.cancelText || 'Cancel'});
	cancel.onClick(function() {
		overlay.hide();
	});
	overlay.add(cancel);
	overlay.show({element:node});
}

hui.ui.destroy = function(widget) {
	var objects = hui.ui.objects;
	delete(objects[widget.name]);
}

hui.ui.destroyDescendants = function(element) {
	var desc = hui.ui.getDescendants(element);
	var objects = hui.ui.objects;
	for (var i=0; i < desc.length; i++) {
		var obj  = delete(objects[desc[i].name]);
		if (!obj) {
			hui.log('not found: '+desc[i].name);
		}
	};
}

/** Gets all ancestors of a widget
	@param {Widget} A widget
	@returns {Array} An array of all ancestors
*/
hui.ui.getAncestors = function(widget) {
	var desc = [];
	var e = widget.element;
	if (e) {
		var a = hui.getAncestors(e);
		var o = [];
		for (var key in hui.ui.objects) {
			o.push(hui.ui.objects[key]);
		}
		for (var i=0; i < a.length; i++) {
			for (var j=0; j < o.length; j++) {
				if (o[j].element==a[i]) {
					desc.push(o[j]);
				}
			}
		}
	}
	return desc;
}

hui.ui.getDescendants = function(widgetOrElement) {
	var desc = [],e = widgetOrElement.getElement ? widgetOrElement.getElement() : widgetOrElement;
	if (e) {
		var d = e.getElementsByTagName('*');
		var o = [];
		for (var key in hui.ui.objects) {
			o.push(hui.ui.objects[key]);
		}
		for (var i=0; i < d.length; i++) {
			for (var j=0; j < o.length; j++) {
				if (d[i]==o[j].element) {
					desc.push(o[j]);
				}
			};
			
		};
	}
	return desc;
}

hui.ui.getAncestor = function(widget,cls) {
	var a = hui.ui.getAncestors(widget);
	for (var i=0; i < a.length; i++) {
		if (hui.hasClass(a[i].getElement(),cls)) {
			return a[i];
		}
	};
	return null;
}



hui.ui.changeState = function(state) {
	if (hui.ui.state===state) {return;}
	var all = hui.ui.objects,
		key,obj;
	for (key in all) {
		obj = all[key];
		if (obj.options && obj.options.state) {
			if (obj.options.state==state) {
				obj.show();
			} else {
				obj.hide();
			}
		}
	}
	hui.ui.state=state;
}

///////////////////////////////// Indexes /////////////////////////////

hui.ui.nextIndex = function() {
	hui.ui.latestIndex++;
	return 	hui.ui.latestIndex;
};

hui.ui.nextPanelIndex = function() {
	hui.ui.latestPanelIndex++;
	return 	hui.ui.latestPanelIndex;
};

hui.ui.nextAlertIndex = function() {
	hui.ui.latestAlertIndex++;
	return 	hui.ui.latestAlertIndex;
};

hui.ui.nextTopIndex = function() {
	hui.ui.latestTopIndex++;
	return 	hui.ui.latestTopIndex;
};

///////////////////////////////// Curtain /////////////////////////////

hui.ui.showCurtain = function(options,zIndex) {
	var widget = options.widget;
	if (!widget.curtain) {
		widget.curtain = hui.build('div',{'class':'in2igui_curtain',style:'z-index:none'});
		widget.curtain.onclick = function() {
			if (widget['$curtainWasClicked']) {
				widget['$curtainWasClicked']();
			}
		};
		var body = hui.firstByClass(document.body,'in2igui_body');
		if (!body) {
			body=document.body;
		}
		body.appendChild(widget.curtain);
	}
	if (options.color) {
		widget.curtain.style.backgroundColor=options.color;
	}
	if (hui.browser.msie) {
		widget.curtain.style.height=hui.getDocumentHeight()+'px';
	} else {
		widget.curtain.style.position='fixed';
		widget.curtain.style.top='0';
		widget.curtain.style.left='0';
		widget.curtain.style.bottom='0';
		widget.curtain.style.right='0';
	}
	widget.curtain.style.zIndex=options.zIndex;
	hui.setOpacity(widget.curtain,0);
	widget.curtain.style.display='block';
	hui.ani(widget.curtain,'opacity',0.7,1000,{ease:hui.ease.slowFastSlow});
}

hui.ui.hideCurtain = function(widget) {
	if (widget.curtain) {
		hui.ani(widget.curtain,'opacity',0,200,{hideOnComplete:true});
	}
};

//////////////////////////////// Message //////////////////////////////

hui.ui.confirm = function(options) {
	if (!options.name) {
		options.name = 'in2iguiConfirm';
	}
	var alert = hui.ui.get(options.name);
	var ok;
	if (!alert) {
		alert = hui.ui.Alert.create(options);
		var cancel = hui.ui.Button.create({name:name+'_cancel',text : options.cancel || 'Cancel',highlighted:options.highlighted==='cancel'});
		cancel.listen({$click:function(){
			alert.hide();
			if (options.onCancel) {
				options.onCancel();
			}
			hui.ui.callDelegates(alert,'cancel');
		}});
		alert.addButton(cancel);
	
		ok = hui.ui.Button.create({name:name+'_ok',text : options.ok || 'OK',highlighted:options.highlighted==='ok'});
		alert.addButton(ok);
	} else {
		alert.update(options);
		ok = hui.ui.get(name+'_ok');
		ok.setText(options.ok || 'OK');
		ok.setHighlighted(options.highlighted=='ok');
		ok.clearDelegates();
		hui.ui.get(name+'_cancel').setText(options.ok || 'Cancel');
		hui.ui.get(name+'_cancel').setHighlighted(options.highlighted=='cancel');
		if (options.cancel) {hui.ui.get(name+'_cancel').setText(options.cancel);}
	}
	ok.listen({$click:function(){
		alert.hide();
		if (options.onOK) {
			options.onOK();
		}
		hui.ui.callDelegates(alert,'ok');
	}});
	alert.show();
}

hui.ui.alert = function(options) {
	if (!this.alertBox) {
		this.alertBox = hui.ui.Alert.create(options);
		this.alertBoxButton = hui.ui.Button.create({name:'huiAlertBoxButton',text : 'OK'});
		this.alertBoxButton.listen({
			$click$huiAlertBoxButton : function() {
				hui.ui.alertBox.hide();
				if (hui.ui.alertBoxCallBack) {
					hui.ui.alertBoxCallBack();
					hui.ui.alertBoxCallBack = null;
				}
			}
		});
		this.alertBox.addButton(this.alertBoxButton);
	} else {
		this.alertBox.update(options);
	}
	this.alertBoxCallBack = options.onOK;
	this.alertBoxButton.setText(options.button ? options.button : 'OK');
	this.alertBox.show();
};

hui.ui.showMessage = function(options) {
	if (typeof(options)=='string') {
		// TODO: Backwards compatibility
		options={text:options};
	}
	if (options.delay) {
		hui.ui.messageDelayTimer = window.setTimeout(function() {
			options.delay=null;
			hui.ui.showMessage(options);
		},options.delay);
		return;
	}
	window.clearTimeout(hui.ui.messageDelayTimer);
	if (!hui.ui.message) {
		hui.ui.message = hui.build('div',{'class':'in2igui_message',html:'<div><div></div></div>'});
		if (!hui.browser.msie) {
			hui.setOpacity(hui.ui.message,0);
		}
		document.body.appendChild(hui.ui.message);
	}
	var inner = hui.ui.message.getElementsByTagName('div')[1];
	if (options.icon) {
		hui.dom.clear(inner);
		inner.appendChild(hui.ui.createIcon(options.icon,24));
		hui.dom.addText(inner,options.text);
	}
	else if (options.busy) {
		inner.innerHTML='<span class="in2igui_message_busy"></span>';
		hui.dom.addText(inner,options.text);
	} else {
		hui.dom.setText(inner,options.text);
	}
	hui.ui.message.style.display = 'block';
	hui.ui.message.style.zIndex = hui.ui.nextTopIndex();
	hui.ui.message.style.marginLeft = (hui.ui.message.clientWidth/-2)+'px';
	hui.ui.message.style.marginTop = hui.getScrollTop()+'px';
	if (hui.browser.opacity) {
		hui.ani(hui.ui.message,'opacity',1,300);
	}
	window.clearTimeout(hui.ui.messageTimer);
	if (options.duration) {
		hui.ui.messageTimer = window.setTimeout(hui.ui.hideMessage,options.duration);
	}
};

hui.ui.hideMessage = function() {
	window.clearTimeout(hui.ui.messageDelayTimer);
	if (hui.ui.message) {
		if (hui.browser.opacity) {
			hui.ani(hui.ui.message,'opacity',0,300,{hideOnComplete:true});
		} else {
			hui.ui.message.style.display='none';
		}
	}
};

hui.ui.showToolTip = function(options) {
	var key = options.key || 'common';
	var t = hui.ui.toolTips[key];
	if (!t) {
		t = hui.build('div',{'class':'in2igui_tooltip',style:'display:none;',html:'<div><div></div></div>',parent:document.body});
		hui.ui.toolTips[key] = t;
	}
	t.onclick = function() {hui.ui.hideToolTip(options);};
	var n = hui.get(options.element);
	var pos = hui.getPosition(n);
	hui.dom.setText(t.getElementsByTagName('div')[1],options.text);
	if (t.style.display=='none' && hui.browser.opacity) {
		hui.setOpacity(t,0);
	}
	hui.setStyle(t,{'display':'block',zIndex:hui.ui.nextTopIndex()});
	hui.setStyle(t,{left:(pos.left-t.clientWidth+4)+'px',top:(pos.top+2-(t.clientHeight/2)+(n.clientHeight/2))+'px'});
	if (hui.browser.opacity) {
		hui.ani(t,'opacity',1,300);
	}
};

hui.ui.hideToolTip = function(options) {
	var key = options ? options.key || 'common' : 'common';
	var t = hui.ui.toolTips[key];
	if (t) {
		if (!hui.browser.msie) {
			hui.ani(t,'opacity',0,300,{hideOnComplete:true});
		} else {
			hui.style.display = 'none';
		}
	}
};

/////////////////////////////// Utilities /////////////////////////////

hui.ui.getElement = function(widgetOrElement) {
	if (hui.dom.isElement(widgetOrElement)) {
		return widgetOrElement;
	} else if (widgetOrElement.getElement) {
		return widgetOrElement.getElement();
	}
	return null;
}

hui.ui.isWithin = function(e,element) {
	e = new hui.Event(e);
	var offset = {left:hui.getLeft(element),top:hui.getTop(element)};
	var dims = {width:element.clientWidth,height:element.clientHeight};
	return e.left()>offset.left && e.left()<offset.left+dims.width && e.top()>offset.top && e.top()<offset.top+dims.height;
};

hui.ui.getIconUrl = function(icon,size) {
	return hui.ui.context+'/In2iGui/icons/'+icon+size+'.png';
};

hui.ui.createIcon = function(icon,size) {
	return hui.build('span',{'class':'in2igui_icon in2igui_icon_'+size,style:'background-image: url('+hui.ui.getIconUrl(icon,size)+')'});
};

/**
 * Called when the DOM is ready and hui.ui is ready
 */
hui.ui.onReady = function(func) {
	if (hui.ui.domReady) {return func();}
	if (hui.browser.gecko && hui.string.endsWith(document.baseURI,'xml')) {
		window.setTimeout(func,1000);
		return;
	}
	hui.onReady(func);
};

hui.ui.wrapInField = function(e) {
	var w = hui.build('div',{'class':'in2igui_field',html:
		'<span class="in2igui_field_top"><span><span></span></span></span>'+
		'<span class="in2igui_field_middle"><span class="in2igui_field_middle"><span class="in2igui_field_content"></span></span></span>'+
		'<span class="in2igui_field_bottom"><span><span></span></span></span>'
	});
	hui.firstByClass(w,'in2igui_field_content').appendChild(e);
	return w;
};

hui.ui.addFocusClass = function(o) {
	var ce = o.classElement || o.element, c = o['class'];
	hui.listen(o.element,'focus',function() {
		hui.addClass(ce,c);
	});
	hui.listen(o.element,'blur',function() {
		hui.removeClass(ce,c);
	});
};


/////////////////////////////// Validation /////////////////////////////

hui.ui.NumberValidator = function(options) {
	hui.override({allowNull:false,min:0,max:10},options)
	this.min = options.min;
	this.max = options.max;
	this.allowNull = options.allowNull;
	this.middle = Math.max(Math.min(this.max,0),this.min);
}

hui.ui.NumberValidator.prototype = {
	validate : function(value) {
		if (hui.isBlank(value) && this.allowNull) {
			return {valid:true,value:null};
		}
		var number = parseFloat(value);
		if (isNaN(number)) {
			return {valid:false,value:this.middle};
		} else if (number<this.min) {
			return {valid:false,value:this.min};
		} else if (number>this.max) {
			return {valid:false,value:this.max};
		}
		return {valid:true,value:number};
	}
}

/////////////////////////////// Animation /////////////////////////////

hui.ui.fadeIn = function(node,time) {
	if (hui.getStyle(node,'display')=='none') {
		hui.setStyle(node,{opacity:0,display:''});
	}
	hui.animate(node,'opacity',1,time);
};

hui.ui.fadeOut = function(node,time) {
	hui.animate(node,'opacity',0,time,{hideOnComplete:true});
};

hui.ui.bounceIn = function(node,time) {
	if (hui.browser.msie) {
		hui.setStyle(node,{'display':'block',visibility:'visible'});
	} else {
		hui.setStyle(node,{'display':'block','opacity':0,visibility:'visible'});
		hui.animate(node,'transform','scale(0.1)',0);// rotate(10deg)
		window.setTimeout(function() {
			hui.animate(node,'opacity',1,300);
			hui.animate(node,'transform','scale(1)',400,{ease:hui.ease.backOut}); // rotate(0deg)
		});
	}
};

//////////////////////////// Positioning /////////////////////////////

hui.ui.positionAtElement = function(element,target,options) {
	options = options || {};
	element = hui.get(element);
	target = hui.get(target);
	var origDisplay = hui.getStyle(element,'display');
	if (origDisplay=='none') {
		hui.setStyle(element,{'visibility':'hidden','display':'block'});
	}
	var left = hui.getLeft(target),
		top = hui.getTop(target);
	var vert=options.vertical || null;
	if (options.horizontal && options.horizontal=='right') {
		left = left+target.clientWidth-element.clientWidth;
	}
	if (vert=='topOutside') {
		top = top-element.clientHeight;
	} else if (vert=='bottomOutside') {
		top = top+target.clientHeight;
	}
	left+=(options.left || 0);
	top+=(options.top || 0);
	hui.setStyle(element,{'left':left+'px','top':top+'px'});
	if (origDisplay=='none') {
		hui.setStyle(element,{'visibility':'visible','display':'none'});
	}
};

hui.ui.getTextAreaHeight = function(input) {
	var t = this.textAreaDummy;
	if (!t) {
		t = this.textAreaDummy = document.createElement('div');
		t.className='in2igui_textarea_dummy';
		document.body.appendChild(t);
	}
	var html = input.value;
	if (html[html.length-1]==='\n') {
		html+='x';
	}
	html = hui.escape(html).replace(/\n/g,'<br/>');
	t.innerHTML = html;
	t.style.width=(input.clientWidth)+'px';
	return t.clientHeight;
}

//////////////////// Delegating ////////////////////

hui.ui.extend = function(obj,options) {
	if (!obj.name) {
		hui.ui.latestObjectIndex++;
		obj.name = 'unnamed'+hui.ui.latestObjectIndex;
	}
	if (options!==undefined) {
		if (obj.options) {
			obj.options = hui.override(obj.options,options);
		}
		obj.element = hui.get(options.element);
		obj.name = options.name;
	}
	hui.ui.objects[obj.name] = obj;
	obj.delegates = [];
	obj.listen = function(delegate) {
		hui.addToArray(this.delegates,delegate);
		return this;
	}
	obj.removeDelegate = function(delegate) {
		hui.removeFromArray(this.delegates,delegate);
	}
	obj.clearDelegates = function() {
		this.delegates = [];
	}
	obj.fire = function(method,value,event) {
		hui.ui.callDelegates(this,method,value,event);
	}
	obj.fireProperty = function(key,value) {
		hui.ui.firePropertyChange(this,key,value);
	}
	if (!obj.getElement) {
		obj.getElement = function() {
			return this.element;
		}
	}
	if (!obj.valueForProperty) {
		obj.valueForProperty = function(p) {return this[p]};
	}
	if (obj['$$layout']) {
		hui.ui.layoutWidgets.push(obj);
	}
};

hui.ui.callDelegatesDrop = function(dragged,dropped) {
	for (var i=0; i < hui.ui.delegates.length; i++) {
		if (hui.ui.delegates[i]['$drop$'+dragged.kind+'$'+dropped.kind]) {
			hui.ui.delegates[i]['$drop$'+dragged.kind+'$'+dropped.kind](dragged,dropped);
		}
	}
};

hui.ui.callAncestors = function(obj,method,value,event) {
	if (typeof(value)=='undefined') value=obj;
	var d = hui.ui.getAncestors(obj);
	for (var i=0; i < d.length; i++) {
		if (d[i][method]) {
			d[i][method](value,event);
		}
	};
};

hui.ui.callDescendants = function(obj,method,value,event) {
	if (typeof(value)=='undefined') {
		value=obj;
	}
	if (!method[0]=='$') {
		method = '$'+method;
	}
	var d = hui.ui.getDescendants(obj);
	for (var i=0; i < d.length; i++) {
		if (d[i][method]) {
			thisResult = d[i][method](value,event);
		}
	};
};

hui.ui.callVisible = function(widget) {
	hui.ui.callDescendants(widget,'$visibilityChanged');
}

hui.ui.listen = function(delegate) {
	hui.ui.delegates.push(delegate);
}

hui.ui.callDelegates = function(obj,method,value,event) {
	if (typeof(value)=='undefined') {
		value=obj;
	}
	var result = null;
	if (obj.delegates) {
		for (var i=0; i < obj.delegates.length; i++) {
			var delegate = obj.delegates[i];
			var thisResult = null;
			if (obj.name && delegate['$'+method+'$'+obj.name]) {
				thisResult = delegate['$'+method+'$'+obj.name](value,event);
			} else if (delegate['$'+method]) {
				thisResult = delegate['$'+method](value,event);
			}
			if (result==null && thisResult!=null && typeof(thisResult)!='undefined') {
				result = thisResult;
			}
		};
	}
	var superResult = hui.ui.callSuperDelegates(obj,method,value,event);
	if (result==null && superResult!=null) {
		result = superResult;
	}
	return result;
};

hui.ui.callSuperDelegates = function(obj,method,value,event) {
	if (typeof(value)=='undefined') value=obj;
	var result = null;
	for (var i=0; i < hui.ui.delegates.length; i++) {
		var delegate = hui.ui.delegates[i];
		var thisResult = null;
		if (obj.name && delegate['$'+method+'$'+obj.name]) {
			thisResult = delegate['$'+method+'$'+obj.name](value,event);
		} else if (delegate['$'+method]) {
			thisResult = delegate['$'+method](value,event);
		}
		if (result==null && thisResult!=null && typeof(thisResult)!='undefined') {
			result = thisResult;
		}
	};
	return result;
};

hui.ui.resolveImageUrl = function(widget,img,width,height) {
	for (var i=0; i < widget.delegates.length; i++) {
		if (widget.delegates[i].$resolveImageUrl) {
			return widget.delegates[i].$resolveImageUrl(img,width,height);
		}
	};
	for (var j=0; j < hui.ui.delegates.length; j++) {
		var delegate = hui.ui.delegates[j];
		if (delegate.$resolveImageUrl) {
			return delegate.$resolveImageUrl(img,width,height);
		}
	}
	return null;
};

////////////////////////////// Bindings ///////////////////////////

hui.ui.firePropertyChange = function(obj,name,value) {
	hui.ui.callDelegates(obj,'propertyChanged',{property:name,value:value});
};

hui.ui.bind = function(expression,delegate) {
	if (expression.charAt(0)=='@') {
		var pair = expression.substring(1).split('.');
		var obj = hui.ui.get(pair[0]);
		if (!obj) {
			hui.log('Unable to bind to '+expression);
			return;
		}
		var p = pair.slice(1).join('.');
		obj.listen({
			$propertyChanged : function(prop) {
				if (prop.property==p) {
					delegate(prop.value);
				}
			}
		});
		return obj.valueForProperty(p);
	}
	return expression;
};

//////////////////////////////// Data /////////////////////////////

hui.ui.request = function(options) {
	options = hui.override({method:'post',parameters:{}},options);
	if (options.json) {
		for (var key in options.json) {
			options.parameters[key]=hui.toJSON(options.json[key]);
		}
	}
	var onSuccess = options.onSuccess;
	var message = options.message;
	options.onSuccess=function(t) {
		if (message) {
			if (message.success) {
				hui.ui.showMessage({text:message.success,icon:'common/success',duration:message.duration || 2000});
			} else if (message.start) {
				hui.ui.hideMessage();
			}
		}
		var str,json;
		if (typeof(onSuccess)=='string') {
			if (!hui.request.isXMLResponse(t)) {
				str = t.responseText.replace(/^\s+|\s+$/g, '');
				if (str.length>0) {
					json = hui.fromJSON(t.responseText);
				} else {
					json = '';
				}
				hui.ui.callDelegates(json,'success$'+onSuccess);
			} else {
				hui.ui.callDelegates(t,'success$'+onSuccess);
			}
		} else if (hui.request.isXMLResponse(t) && options.onXML) {
			options.onXML(t.responseXML);
		} else if (options.onJSON) {
			str = t.responseText.replace(/^\s+|\s+$/g, '');
			if (str.length>0) {
				json = hui.fromJSON(t.responseText);
			} else {
				json = null;
			}
			options.onJSON(json);
		} else if (typeof(onSuccess)=='function') {
			onSuccess(t);
		} else if (options.onText) {
			options.onText(t.responseText);
		}
	};
	var onFailure = options.onFailure;
	options.onFailure = function(t) {
		if (typeof(onFailure)=='string') {
			hui.ui.callDelegates(t,'failure$'+onFailure)
		} else if (typeof(onFailure)=='function') {
			onFailure(t);
		}
	}
	options.onException = function(t,e) {
		hui.log(t);
		hui.log(e);
	};
	if (options.message && options.message.start) {
		hui.ui.showMessage({text:options.message.start,busy:true,delay:options.message.delay});
	}
	hui.request(options);
};

hui.ui.parseItems = function(doc) {
	var root = doc.documentElement;
	var out = [];
	hui.ui.parseSubItems(root,out);
	return out;
};

hui.ui.parseSubItems = function(parent,array) {
	var children = parent.childNodes;
	for (var i=0; i < children.length; i++) {
		var node = children[i];
		if (node.nodeType==1 && node.nodeName=='title') {
			array.push({title:node.getAttribute('title'),type:'title'})
		} else if (node.nodeType==1 && node.nodeName=='item') {
			var sub = [];
			hui.ui.parseSubItems(node,sub);
			array.push({
				title:node.getAttribute('title'),
				value:node.getAttribute('value'),
				icon:node.getAttribute('icon'),
				kind:node.getAttribute('kind'),
				badge:node.getAttribute('badge'),
				children:sub
			});
		}
	};
}

hui.ui.Bundle = function(strings) {
	this.strings = strings;
}

hui.ui.Bundle.prototype = {
	get : function(key) {
		var values = this.strings[key];
		if (values) {
			return values[hui.ui.language];
		}
		hui.log(key+' not found for language:'+hui.ui.language);
		return key;
	}
}

hui.ui.require = function(names,func) {
	for (var i = names.length - 1; i >= 0; i--){
		names[i] = hui.ui.context+'In2iGui/js/'+names[i]+'.js';
	};
	hui.require(names,func);
}
/* EOF */
