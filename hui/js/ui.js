/**
  The namespace of the HUI framework
  @namespace
 */
hui.ui = {
	domReady : false,
	context : '',
	language : 'en',

	objects : {},
	delegates : [],

	state : 'default',

	latestObjectIndex : 0,
	latestIndex : 500,
	latestPanelIndex : 1000,
	latestAlertIndex : 1500,
	latestTopIndex : 2000,
	toolTips : {},
	confirmOverlays : {},
	
	delayedUntilReady : [],
	
	texts : {
		request_error : {en:'An error occurred on the server',da:'Der skete en fejl på serveren'},
		'continue' : {en:'Continue',da:'Fortsæt'},
		reload_page : {en:'Reload page',da:'Indæs siden igen'},
		access_denied : {en:'Access denied, maybe you are nolonger logged in',da:'Adgang nægtet, du er måske ikke længere logget ind'}
	}
};

/**
 * Get a component by name
 * @param nameOrComponent {hui.ui.Component | String} Get a component by name, if the parameter is already a component it is returned
 * @return {hui.ui.Component} The component with the name or undefined
 */
hui.ui.get = function(nameOrComponent) {
	if (nameOrComponent) {
		if (nameOrComponent.element) {
			return nameOrComponent;
		}
		return hui.ui.objects[nameOrComponent];
	}
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
	hui.ui.delayedUntilReady.push(func);
};

hui.ui._frameLoaded = function(win) {
	hui.ui.callSuperDelegates(this,'frameLoaded',win);
};

/** @private */
hui.ui._resize = function() {
	hui.ui.reLayout();
	window.clearTimeout(this._delayedResize);
	if (!hui.ui._resizeFirst) {
		this._delayedResize = window.setTimeout(hui.ui._afterResize,500);
	}
};

hui.ui._afterResize = function() {
  hui.onDraw(function() {
  	hui.ui.callSuperDelegates(hui.ui,'$afterResize');    
  })
};

/**
 * Show a confirming overlay
 * <pre><strong>options:</strong> {
 *  element : «Element», // the element to show at
 *  widget : «Widget», // the widget to show at
 *  text : «String», // the text message
 *  okText : «String», // text of OK button
 *  cancelText «String», // text of cancel button
 *  $ok: «Function», // called when user clicks the OK button
 *  $cancel: «Function» // called when user clicks the Cancel button
 * }
 * </pre>
 * @param options {Object} The options
 */
hui.ui.confirmOverlay = function(options) {
	var node = options.element,
		overlay;
	if (!node) {
		node = document.body;
	}
	if (options.widget) {
		node = options.widget.getElement();
	}
	if (hui.ui.confirmOverlays[node]) {
		overlay = hui.ui.confirmOverlays[node];
		overlay.clear();
	} else {
		overlay = hui.ui.Overlay.create({modal:true});
		hui.ui.confirmOverlays[node] = overlay;
	}
	if (options.text) {
		overlay.addText(hui.ui.getTranslated(options.text));
	}
	var ok = hui.ui.Button.create({text:hui.ui.getTranslated(options.okText) || 'OK',highlighted:'true'});
	ok.click(function() {
		if (options.onOk) {
			options.onOk();
		}
		else if (options.$ok) {
			options.$ok();
		}
		overlay.hide();
	});
	overlay.add(ok);
	var cancel = hui.ui.Button.create({text:hui.ui.getTranslated(options.cancelText) || 'Cancel'});
	cancel.onClick(function() {
		if (options.onCancel) {
			options.onCancel();
		}
		else if (options.$cancel) {
			options.$cancel();
		}
		overlay.hide();
	});
	overlay.add(cancel);
	overlay.show({element:node});
};

/**
 * Unregisters a widget
 * @param widget {Widget} The widget to destroy 
 */
hui.ui.destroy = function(widget) {
  if (typeof(widget.destroy)=='function') {
    widget.destroy();
  }
	delete(hui.ui.objects[widget.name]);
};

hui.ui.destroyDescendants = function(widgetOrElement) {
	var desc = hui.ui.getDescendants(widgetOrElement);
	var objects = hui.ui.objects;
	for (var i=0; i < desc.length; i++) {
    hui.ui.destroy(desc[i]);
	}
};

/** Gets all ancestors of a widget
	@param {Widget} widget A widget
	@returns {Array} An array of all ancestors
*/
hui.ui.getAncestors = function(widget) {
	var desc = [];
	var e = widget.element;
	if (e) {
		var a = hui.get.ancestors(e);
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
};

hui.ui.getDescendants = function(widgetOrElement) {
	var desc = [];
	if (widgetOrElement) {
		var e = widgetOrElement.getElement ? widgetOrElement.getElement() : widgetOrElement;
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
				}
			}
		}
	}
	return desc;
};

hui.ui.getAncestor = function(widget,cls) {
	var a = hui.ui.getAncestors(widget);
	for (var i=0; i < a.length; i++) {
		if (hui.cls.has(a[i].getElement(),cls)) {
			return a[i];
		}
	}
	return null;
};



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
	
	this.reLayout();
};

hui.ui.reLayout = function() {
	var widgets = hui.ui.getDescendants(document.body);
	for (var i=0; i < widgets.length; i++) {
		var obj = widgets[i];
		if (obj.$$layout) {
			obj.$$layout();
		}
	}
};



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

/**
 * Shows a "curtain" behind an element
 * @param options { widget:«widget», color:«cssColor | 'auto'», zIndex:«cssZindex» }
 */
hui.ui.showCurtain = function(options) {
	var widget = options.widget;
	if (!widget.curtain) {
		widget.curtain = hui.build('div',{'class':'hui_curtain',style:'z-index:none'});
		
		var body = hui.get.firstByClass(document.body,'hui_body');
		if (!body) {
			body=document.body;
		}
		body.appendChild(widget.curtain);
		hui.listen(widget.curtain,'click',function() {
			if (widget.$curtainWasClicked) {
				widget.$curtainWasClicked();
			}
		});
	}
	var curtain = widget.curtain;
	if (options.transparent) {
		curtain.style.background='none';
	}
	else if (options.color) {
		if (options.color=='auto') {
			var color = hui.style.get(document.body,'background-color');
			if (color=='transparent' || color=='rgba(0, 0, 0, 0)') {
				color='#fff';
			}
			curtain.style.backgroundColor=color;
		} else {
			curtain.style.backgroundColor=options.color;
		}
	}
	if (hui.browser.msie) {
		curtain.style.height=hui.document.getHeight()+'px';
	} else {
		curtain.style.position='fixed';
		curtain.style.top='0';
		curtain.style.left='0';
		curtain.style.bottom='0';
		curtain.style.right='0';
	}
	curtain.style.zIndex=options.zIndex;
	if (options.transparent) {
		curtain.style.display='block';		
	} else {
		hui.style.setOpacity(curtain,0);
		curtain.style.display='block';
		hui.animate(curtain,'opacity',0.7,1000,{ease:hui.ease.slowFastSlow});
	}
};

hui.ui.hideCurtain = function(widget) {
	if (widget.curtain) {
		hui.animate(widget.curtain,'opacity',0,200,{hideOnComplete:true});
	}
};



///////////////////////////// Localization ////////////////////////////

/**
 * Get a localized text, defaults to english or the key
 * @param {String} key The key of the text
 * @returns {String} The localized string
 */
hui.ui.getText = function(key) {
	var parts = this.texts[key];
	if (!parts) {return key;}
	if (parts[this.language]) {
		return parts[this.language];
	} else {
		return parts.en;
	}
};

hui.ui.getTranslated = function(value) {
	if (!hui.isDefined(value) || hui.isString(value) || typeof(value) == 'number') {
		return value;
	}
	if (value[hui.ui.language]) {
		return value[hui.ui.language];
	}
	if (value[null]) {
		return value[null];
	}
	for (var key in value) {
		return value[key];
	}
};



//////////////////////////////// Message //////////////////////////////

hui.ui.confirm = function(options) {
	if (!options.name) {
		options.name = 'huiConfirm';
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
		ok.clearListeners();
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
};

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
		hui.ui.message = hui.build('div',{'class':'hui_message',html:'<div><div></div></div>'});
		if (!hui.browser.msie) {
			hui.style.setOpacity(hui.ui.message,0);
		}
		document.body.appendChild(hui.ui.message);
	}
	var text = hui.ui.getTranslated(options.text) || '';
	var inner = hui.ui.message.getElementsByTagName('div')[1];
	if (options.icon) {
		hui.dom.clear(inner);
		inner.appendChild(hui.ui.createIcon(options.icon,24));
		hui.dom.addText(inner,text);
	}
	else if (options.busy) {
		inner.innerHTML='<span class="hui_message_busy"></span>';
		hui.dom.addText(inner,text);
	} else {
		hui.dom.setText(inner,text);
	}
	hui.ui.message.style.display = 'block';
	hui.ui.message.style.zIndex = hui.ui.nextTopIndex();
	hui.ui.message.style.marginLeft = (hui.ui.message.clientWidth/-2)+'px';
	hui.ui.message.style.marginTop = hui.window.getScrollTop()+'px';
	if (hui.browser.opacity) {
		hui.animate(hui.ui.message,'opacity',1,300);
	}
	window.clearTimeout(hui.ui.messageTimer);
	if (options.duration) {
		hui.ui.messageTimer = window.setTimeout(hui.ui.hideMessage,options.duration);
	}
};

hui.ui.msg = hui.ui.showMessage;

hui.ui.msg.success = function(options) {
	options = hui.override({icon:'common/success',duration:2000},options);
	hui.ui.msg(options);
};

hui.ui.msg.fail = function(options) {
	options = hui.override({icon:'common/warning',duration:3000},options);
	hui.ui.msg(options);
};

hui.ui.hideMessage = function() {
	window.clearTimeout(hui.ui.messageDelayTimer);
	if (hui.ui.message) {
		if (hui.browser.opacity) {
			hui.animate(hui.ui.message,'opacity',0,300,{hideOnComplete:true});
		} else {
			hui.ui.message.style.display='none';
		}
	}
};

hui.ui.showToolTip = function(options) {
	var key = options.key || 'common';
	var t = hui.ui.toolTips[key];
	if (!t) {
		t = hui.build('div',{'class':'hui_tooltip',style:'display:none;',html:'<div><div></div></div>',parent:document.body});
		hui.ui.toolTips[key] = t;
	}
	t.onclick = function() {hui.ui.hideToolTip(options);};
	var n = hui.get(options.element);
	var pos = hui.position.get(n);
	hui.dom.setText(t.getElementsByTagName('div')[1],options.text);
	if (t.style.display=='none' && hui.browser.opacity) {
		hui.style.setOpacity(t,0);
	}
	hui.style.set(t,{'display':'block',zIndex:hui.ui.nextTopIndex()});
	hui.style.set(t,{left:(pos.left-t.clientWidth+4)+'px',top:(pos.top+2-(t.clientHeight/2)+(n.clientHeight/2))+'px'});
	if (hui.browser.opacity) {
		hui.animate(t,'opacity',1,300);
	}
};

hui.ui.hideToolTip = function(options) {
	var key = options ? options.key || 'common' : 'common';
	var t = hui.ui.toolTips[key];
	if (t) {
		if (!hui.browser.msie) {
			hui.animate(t,'opacity',0,300,{hideOnComplete:true});
		} else {
			t.style.display = 'none';
		}
	}
};



/////////////////////////////// Utilities /////////////////////////////

/**
 * Get the element of a widget if not already an element
 * @param widgetOrElement {Widget | Element} The widget to get the element for
 * @returns {Element} The element or null
 */
hui.ui.getElement = function(widgetOrElement) {
	if (hui.dom.isElement(widgetOrElement)) {
		return widgetOrElement;
	} else if (widgetOrElement.getElement) {
		return widgetOrElement.getElement();
	}
	return null;
};

hui.ui.isWithin = function(e,element) {
	e = hui.event(e);
	var offset = hui.position.get(element),
		dims = { width : element.offsetWidth, height : element.offsetHeight },
		left = e.getLeft(),
		top = e.getTop();
	return left > offset.left && left < offset.left+dims.width && top > offset.top && top < offset.top+dims.height;
};

hui.ui.getIconUrl = function(icon,size) {
	return hui.ui.context+'/hui/icons/'+icon+size+'.png';
};

hui.ui.createIcon = function(icon,size,tag) {
	return hui.build(tag || 'span',{'class':'hui_icon hui_icon_'+size,style:'background-image: url('+hui.ui.getIconUrl(icon,size)+')'});
};

hui.ui.wrapInField = function(element) {
	var w = hui.build('div',{'class':'hui_field',html:
		'<span class="hui_field_top"><span><span></span></span></span>'+
		'<span class="hui_field_middle"><span class="hui_field_middle"><span class="hui_field_content"></span></span></span>'+
		'<span class="hui_field_bottom"><span><span></span></span></span>'
	});
	hui.get.firstByClass(w,'hui_field_content').appendChild(element);
	return w;
};

/**
 * Add focus class to an element
 * @param options {Object} {element : «Element», class : «String»}
 */
hui.ui.addFocusClass = function(options) {
	var ce = options.classElement || options.element, c = options['class'];
	hui.listen(options.element,'focus',function() {
		hui.cls.add(ce,c);
		if (options.widget) {
			hui.ui.setKeyboardTarget(options.widget);
		}
	});
	hui.listen(options.element,'blur',function() {
		hui.cls.remove(ce,c);
		if (options.widget) {
			hui.ui.setKeyboardTarget(null);
		}
	});
};

hui.ui.keyboardTarget = null; // The widget currently accepting keyboard input

hui.ui.setKeyboardTarget = function(widget) {
	hui.ui.keyboardTarget = widget;
};


/**
 * Make a widget draw attention to itself
 * @param widget {Widget} The widget to stress
 */
hui.ui.stress = function(widget) {
	var e = hui.ui.getElement(widget);
	hui.effect.wiggle({element:e,duration:1000});
};


//////////////////////////// Positioning /////////////////////////////

hui.ui.positionAtElement = function(element,target,options) {
	options = options || {};
	element = hui.get(element);
	target = hui.get(target);
	var origDisplay = hui.style.get(element,'display');
	if (origDisplay=='none') {
		hui.style.set(element,{'visibility':'hidden','display':'block'});
	}
	var left = hui.position.getLeft(target),
		top = hui.position.getTop(target);
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
	hui.style.set(element,{'left':left+'px','top':top+'px'});
	if (origDisplay=='none') {
		hui.style.set(element,{'visibility':'visible','display':'none'});
	}
};

//////////////////// Delegating ////////////////////

hui.ui.extend = function(obj,options) {
	if (options!==undefined) {
		if (obj.options) {
			obj.options = hui.override(obj.options,options);
		}
		obj.element = hui.get(options.element);
		obj.name = options.name;
	}
	if (!obj.name) {
		hui.ui.latestObjectIndex++;
		obj.name = 'unnamed'+hui.ui.latestObjectIndex;
	}
  hui.ui.registerComponent(obj);
	obj.delegates = [];
	obj.listen = function(delegate) {
		hui.array.add(this.delegates,delegate);
		return this;
	};
	obj.unListen = function(delegate) {
		hui.array.remove(this.delegates,delegate);
	};
	obj.clearListeners = function() {
		this.delegates = [];
	};
	obj.fire = function(method,value,event) {
		return hui.ui.callDelegates(this,method,value,event);
	};
	obj.fireValueChange = function() {
		obj.fire('valueChanged',obj.value);
		hui.ui.firePropertyChange(obj,'value',obj.value);
		hui.ui.callAncestors(obj,'childValueChanged',obj.value);
	};
	obj.fireProperty = function(key,value) {
		hui.ui.firePropertyChange(this,key,value);
	};
	obj.fireSizeChange = function() {
		hui.ui.callAncestors(obj,'$$childSizeChanged');
	};
	if (!obj.getElement) {
		obj.getElement = function() {
			return this.element;
		};
	}
	if (!obj.destroy) {
		obj.destroy = function() {
            if (this.element) {
                hui.dom.remove(this.element);
            }
		};
	}
	if (!obj.valueForProperty) {
		obj.valueForProperty = function(p) {return this[p];};
	}
	if (obj.nodes && obj.element) {
		obj.nodes = hui.collect(obj.nodes,obj.element);
	}
};

hui.ui.registerComponent = function(component) {
	if (hui.ui.objects[component.name]) {
		hui.log('Widget replaced: '+component.name,hui.ui.objects[component.name]);
	}
	hui.ui.objects[component.name] = component;  
};

/** Send a message to all ancestors of a widget */
hui.ui.callAncestors = function(obj,method,value,event) {
	if (typeof(value)=='undefined') value=obj;
	var d = hui.ui.getAncestors(obj);
	for (var i=0; i < d.length; i++) {
		if (d[i][method]) {
			d[i][method](value,event);
		}
	}
};

/** Send a message to all descendants of a widget */
hui.ui.callDescendants = function(obj,method,value,event) {
	if (typeof(value)=='undefined') {
		value=obj;
	}
	if (method[0] !== '$') {
		method = '$'+method;
	}
	var d = hui.ui.getDescendants(obj);
	for (var i=0; i < d.length; i++) {
		if (d[i][method]) {
			d[i][method](value,event);
		}
	}
};

/** Signal that a widget has changed visibility */
hui.ui.callVisible = function(widget) {
	hui.ui.callDescendants(widget,'$visibilityChanged');
};

/** Listen for global events */
hui.ui.listen = function(delegate) {
	if (hui.ui.domReady && delegate.$ready) {
		delegate.$ready();
	}
	hui.ui.delegates.push(delegate);
};

hui.ui.unListen = function(listener) {
	hui.array.remove(hui.ui.delegates,listener);
};

hui.ui.callDelegates = function(obj,method,value,event) {
	if (typeof(value)=='undefined') {
		value=obj;
	}
	var result;
	if (obj.delegates) {
		for (var i=0; i < obj.delegates.length; i++) {
			var delegate = obj.delegates[i],
				thisResult,
				x = '$'+method+'$'+obj.name;
			if (obj.name && delegate[x]) {
				thisResult = delegate[x](value,event);
			} else if (delegate['$'+method]) {
				thisResult = delegate['$'+method](value,event);
			}
			if (result===undefined && thisResult!==undefined && typeof(thisResult)!='undefined') {
				result = thisResult;
			}
		}
	}
	var superResult = hui.ui.callSuperDelegates(obj,method,value,event);
	if (result===undefined && superResult!==undefined) {
		result = superResult;
	}
	return result;
};

/**
 * Sends a message to parent frames
 */
hui.ui.tellContainers = function(event,value) {
	if (window.parent!=window) {
		try {
			window.parent.hui.ui._tellContainers(event,value);
		} catch (e) {
			//hui.log('Unable to callContainers')
		}
	}
};

hui.ui._tellContainers = function(event,value) {
	hui.ui.callSuperDelegates({},event,value);
	if (window.parent!=window) {
		try {
			window.parent.hui.ui._tellContainers(event,value);
		} catch (e) {
			//hui.log('Unable to callContainers')
		}
	}
};

hui.ui.callSuperDelegates = function(obj,method,value,event) {
	if (typeof(value)=='undefined') value=obj;
	var result;
	for (var i=0; i < hui.ui.delegates.length; i++) {
		var delegate = hui.ui.delegates[i],
            thisResult;
		if (obj.name && delegate['$'+method+'$'+obj.name]) {
			thisResult = delegate['$'+method+'$'+obj.name](value,event);
		} else if (delegate['$'+method]) {
			thisResult = delegate['$'+method](value,event);
		}
		if (result===undefined && thisResult!==undefined && typeof(thisResult)!='undefined') {
			result = thisResult;
		}
	}
	return result;
};

hui.ui.resolveImageUrl = function(widget,img,width,height) {
	for (var i=0; i < widget.delegates.length; i++) {
		if (widget.delegates[i].$resolveImageUrl) {
			return widget.delegates[i].$resolveImageUrl(img,width,height);
		}
	}
	for (var j=0; j < hui.ui.delegates.length; j++) {
		var delegate = hui.ui.delegates[j];
		if (delegate.$resolveImageUrl) {
			return delegate.$resolveImageUrl(img,width,height);
		}
	}
	return null;
};

/** Load som UI from an URL */
hui.ui.include = function(options) {
	hui.ui.request({
		url : options.url,
		$text : function(html) {
			var container = hui.build('div',{html:html,parent:document.body});
			hui.dom.runScripts(container);
			options.$success();
		}
	});
};



////////////////////////////// Bindings ///////////////////////////

hui.ui.firePropertyChange = function(obj,name,value) {
	hui.ui.callDelegates(obj,'propertyChanged',{property:name,value:value});
};

hui.ui.bind = function(expression,delegate) {
	if (hui.isString(expression) && expression.charAt(0)=='@') {
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

hui.ui.handleRequestError = function(widget) {
	hui.log('General request error received');
	var result = hui.ui.callSuperDelegates(widget || this,'requestError');
	if (!result) {
		hui.ui.confirmOverlay({
			element : document.body,
			text : hui.ui.getText('request_error'),
			okText : hui.ui.getText('reload_page'),
			cancelText : hui.ui.getText('continue'),
			onOk : function() {
				document.location.reload();
			}
		});
	}
};

hui.ui.handleForbidden = function(widget) {
	hui.log('General access denied received');
	var result = hui.ui.callSuperDelegates(widget || this,'accessDenied');
	if (!result) {
		hui.ui.confirmOverlay({
			element : document.body,
			text : hui.ui.getText('access_denied'),
			okText : hui.ui.getText('reload_page'),
			cancelText : hui.ui.getText('continue'),
			onOk : function() {
				document.location.reload();
			}
		});
	}
};

hui.ui.request = function(options) {
	options = hui.override({method:'post',parameters:{}},options);
	if (options.json) {
		for (var key in options.json) {
			options.parameters[key]=hui.string.toJSON(options.json[key]);
		}
	}
	var success = options.$success,
		obj = options.$object,
		text = options.$text,
		xml = options.$xml,
		failure = options.$failure,
		forbidden = options.$forbidden,
		message = options.message;
	options.$success = function(t) {
		if (message) {
			if (message.success) {
				hui.ui.showMessage({text:message.success,icon:'common/success',duration:message.duration || 2000});
			} else if (message.start) {
				hui.ui.hideMessage();
			}
		}
		var str,json;
		if (typeof(success)=='string') {
			if (!hui.request.isXMLResponse(t)) {
				str = t.responseText.replace(/^\s+|\s+$/g, '');
				if (str.length>0) {
					json = hui.string.fromJSON(t.responseText);
				} else {
					json = '';
				}
				hui.ui.callDelegates(json,'success$'+success);
			} else {
				hui.ui.callDelegates(t,'success$'+success);
			}
		} else if (xml && hui.request.isXMLResponse(t)) {
			xml(t.responseXML);
		} else if (obj) {
			str = t.responseText.replace(/^\s+|\s+$/g, '');
			if (str.length>0) {
				json = hui.string.fromJSON(t.responseText);
			} else {
				json = null;
			}
			obj(json);
		} else if (typeof(success)=='function') {
			success(t);
		} else if (text) {
			text(t.responseText);
		}
	};
	options.$failure = function(t) {
		if (typeof(failure)=='string') {
			hui.ui.callDelegates(t,'failure$'+failure);
		} else if (typeof(failure)=='function') {
			failure(t);
		} else {
			if (options.message && options.message.start) {
				hui.ui.hideMessage();
			}
			hui.ui.handleRequestError();
		}
	};
	options.$exception = options.$exception || function(e,t) {
		hui.log(e);
		hui.log(t);
		throw e;
	};
	options.$forbidden = function(t) {
		if (options.message && options.message.start) {
			hui.ui.hideMessage();
		}
		if (forbidden) {
			forbidden(t);
		} else {
			options.$failure(t);
			hui.ui.handleForbidden();
		}
	};
	if (options.message && options.message.start) {
		hui.ui.msg({text:options.message.start,busy:true,delay:options.message.delay});
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
			array.push({title:node.getAttribute('title'),type:'title'});
		} else if (node.nodeType==1 && node.nodeName=='item') {
			var sub = [];
			hui.ui.parseSubItems(node,sub);
			array.push({
				text : node.getAttribute('text'),
				title : node.getAttribute('title'),
				value : node.getAttribute('value'),
				icon : node.getAttribute('icon'),
				kind : node.getAttribute('kind'),
				badge : node.getAttribute('badge'),
				children : sub
			});
		}
	}
};

/**
 * Import some widgets by name
 * @param {Array} names Array of widgets to import
 * @param {Function} func The function to call when finished
 */
hui.ui.require = function(names,func) {
	for (var i = names.length - 1; i >= 0; i--){
		names[i] = hui.ui.context+'hui/js/'+names[i]+'.js';
	}
	hui.require(names,func);
};

if (window.define) {
	define('hui.ui',hui.ui);
}

hui.onReady(function() {
	hui.listen(window,'resize',hui.ui._resize);
	hui.ui.reLayout();
	hui.ui.domReady = true;
	if (window.parent && window.parent.hui && window.parent.hui.ui) {
		window.parent.hui.ui._frameLoaded(window);
	}
	for (var i=0; i < hui.ui.delayedUntilReady.length; i++) {
		hui.ui.delayedUntilReady[i]();
	}
	// Call super delegates after delayedUntilReady...
	hui.ui.callSuperDelegates(this,'ready');
});

/* EOF */