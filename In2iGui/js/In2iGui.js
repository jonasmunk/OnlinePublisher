var in2igui = {
	locales : {
		'da/DK':{decimal:',',thousands:'.'},
		'en/US':{decimal:'.',thousands:','}
	},
	locale : {decimal:',',thousands:'.'},
	setLocale : function(code) {
		if (this.locales[code]) {
			this.locale = this.locales[code];
		}
	}
};

/**
  The base class of the In2iGui framework
  @constructor
 */
function In2iGui() {
	/** {boolean} Is true when the DOM is loaded */
	this.domLoaded = false;
	/** @private */
	this.overflows = null;
	/** @private */
	this.delegates = [];
	/** @private */
	this.objects = {};
	/** @private */
	this.layoutWidgets = [];
	/** @private */
	this.state = 'default';
	this.addBehavior();
}

window.ui = In2iGui;

/** @private */
In2iGui.latestObjectIndex = 0;
/** @private */
In2iGui.latestIndex = 500;
/** @private */
In2iGui.latestPanelIndex = 1000;
/** @private */
In2iGui.latestAlertIndex = 1500;
/** @private */
In2iGui.latestTopIndex = 2000;
/** @private */
In2iGui.toolTips = {};

/** Gets the one instance of In2iGui */
In2iGui.get = function(nameOrWidget) {
	if (!In2iGui.instance) {
		In2iGui.instance = new In2iGui();
	}
	if (nameOrWidget) {
		if (nameOrWidget.element) {
			return nameOrWidget;
		}
		return In2iGui.instance.objects[nameOrWidget];
	} else {
		return In2iGui.instance;
	}
};

//document.observe('dom:loaded', function () {
//	In2iGui.get().ignite();
//});

n2i.onReady(function() {
	In2iGui.get().ignite();
});

In2iGui.prototype = {
	/** @private */
	ignite : function() {
		if (window.dwr) {
			if (dwr && dwr.engine && dwr.engine.setErrorHandler) {
				dwr.engine.setErrorHandler(function(msg,e) {
					n2i.log(msg);
					n2i.log(e);
					In2iGui.get().alert({title:'An unexpected error occurred!',text:msg,emotion:'gasp'});
				});
			}
		}
		this.domLoaded = true;
		In2iGui.domReady = true;
		this.resize();
		//In2iGui.callSuperDelegates(this,'interfaceIsReady');
		In2iGui.callSuperDelegates(this,'ready');

		this.reLayout();
		n2i.listen(window,'resize',this.reLayout.bind(this));
	},
	/** @private */
	addBehavior : function() {
		n2i.listen(window,'resize',this.resize.bind(this));
	},
	/** Adds a global delegate
	 * @deprecated
	*/
	listen : function(delegate) {
		this.delegates.push(delegate);
	},
	getTopPad : function(element) {
		var all,top;
		all = parseInt(n2i.getStyle(element,'padding'),10);
		top = parseInt(n2i.getStyle(element,'padding-top'),10);
		if (all) {return all;}
		if (top) {return top;}
		return 0;
	},
	getBottomPad : function(element) {
		var all,bottom;
		all = parseInt(n2i.getStyle(element,'padding'),10);
		bottom = parseInt(n2i.getStyle(element,'padding-bottom'),10);
		if (all) {return all;}
		if (bottom) {return bottom;}
		return 0;
	},
	/** @private */
	resize : function() {
		if (!this.overflows) {return;}
		var height = n2i.getInnerHeight();
		for (var i=0; i < this.overflows.length; i++) {
			var overflow = this.overflows[i];
			if (n2i.browser.webkit || n2i.browser.gecko) {
				overflow.element.style.display='none';
				overflow.element.style.width = overflow.element.parentNode.clientWidth+'px';
				overflow.element.style.display='';
			}
			overflow.element.style.height = height+overflow.diff+'px';
		};
	},
	registerOverflow : function(id,diff) {
		if (!this.overflows) {this.overflows=[];}
		var overflow = n2i.get(id);
		this.overflows.push({element:overflow,diff:diff});
	},
	/** @private */
	alert : function(options) {
		if (!this.alertBox) {
			this.alertBox = In2iGui.Alert.create(options);
			this.alertBoxButton = In2iGui.Button.create({name:'in2iGuiAlertBoxButton',text : 'OK'});
			this.alertBoxButton.listen(this);
			this.alertBox.addButton(this.alertBoxButton);
		} else {
			this.alertBox.update(options);
		}
		this.alertBoxCallBack = options.onOK;
		this.alertBoxButton.setText(options.button ? options.button : 'OK');
		this.alertBox.show();
	},
	/** @private */
	$click$in2iGuiAlertBoxButton : function() {
		In2iGui.get().alertBox.hide();
		if (this.alertBoxCallBack) {
			this.alertBoxCallBack();
			this.alertBoxCallBack = null;
		}
	},
	confirm : function(options) {
		if (!options.name) {
			options.name = 'in2iguiConfirm';
		}
		var alert = In2iGui.get(options.name);
		var ok;
		if (!alert) {
			alert = In2iGui.Alert.create(options);
			var cancel = In2iGui.Button.create({name:name+'_cancel',text : options.cancel || 'Cancel',highlighted:options.highlighted==='cancel'});
			cancel.listen({$click:function(){
				alert.hide();
				if (options.onCancel) {
					options.onCancel();
				}
				In2iGui.callDelegates(alert,'cancel');
			}});
			alert.addButton(cancel);
		
			ok = In2iGui.Button.create({name:name+'_ok',text : options.ok || 'OK',highlighted:options.highlighted==='ok'});
			alert.addButton(ok);
		} else {
			alert.update(options);
			ok = In2iGui.get(name+'_ok');
			ok.setText(options.ok || 'OK');
			ok.setHighlighted(options.highlighted=='ok');
			ok.clearDelegates();
			In2iGui.get(name+'_cancel').setText(options.ok || 'Cancel');
			In2iGui.get(name+'_cancel').setHighlighted(options.highlighted=='cancel');
			if (options.cancel) {In2iGui.get(name+'_cancel').setText(options.cancel);}
		}
		ok.listen({$click:function(){
			alert.hide();
			if (options.onOK) {
				options.onOK();
			}
			In2iGui.callDelegates(alert,'ok');
		}});
		alert.show();
	},
	reLayout : function() {
		for (var i = this.layoutWidgets.length - 1; i >= 0; i--){
			this.layoutWidgets[i]['$$layout']();
		};
	},
	getDescendants : function(widgetOrElement) {
		var desc = [],e = widgetOrElement.getElement ? widgetOrElement.getElement() : widgetOrElement;
		if (e) {
			var d = e.getElementsByTagName('*');
			var o = [];
			for (key in this.objects) {
				o.push(this.objects[key]);
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
	},
	/** Gets all ancestors of a widget
		@param {Widget} A widget
		@returns {Array} An array of all ancestors
	*/
	getAncestors : function(widget) {
		var desc = [];
		var e = widget.element;
		if (e) {
			var a = n2i.getAncestors(e);
			var o = [];
			for (key in this.objects) {
				o.push(this.objects[key]);
			}
			for (var i=0; i < a.length; i++) {
				for (var j=0; j < o.length; j++) {
					if (o[j].element==a[i]) {
						desc.push(o[j]);
					}
					
				};
			};
		}
		return desc;
	},
	getAncestor : function(widget,cls) {
		var a = this.getAncestors(widget);
		for (var i=0; i < a.length; i++) {
			if (n2i.hasClass(a[i].getElement(),cls)) {
				return a[i];
			}
		};
		return null;
	}
};

In2iGui.confirmOverlays = {};

In2iGui.confirmOverlay = function(options) {
	var node = options.element || options.widget.getElement();
	if (In2iGui.confirmOverlays[node]) {
		var overlay = In2iGui.confirmOverlays[node];
		overlay.clear();
	} else {
		var overlay = ui.Overlay.create({modal:true});
		In2iGui.confirmOverlays[node] = overlay;
	}
	if (options.text) {
		overlay.addText(options.text);
	}
	var ok = ui.Button.create({text:options.okText || 'OK',highlighted:'true'});
	ok.click(function() {
		if (options.onOk) {
			options.onOk();
		}
		overlay.hide();
	});
	overlay.add(ok);
	var cancel = ui.Button.create({text:options.cancelText || 'Cancel'});
	cancel.onClick(function() {
		overlay.hide();
	});
	overlay.add(cancel);
	overlay.show({element:node});
}

In2iGui.destroyDescendants = function(element) {
	var desc = In2iGui.get().getDescendants(element);
	var objects = In2iGui.get().objects;
	for (var i=0; i < desc.length; i++) {
		var obj  = delete(objects[desc[i].name]);
		if (!obj) {
			n2i.log('not found: '+desc[i].name);
		}
	};
}

In2iGui.changeState = function(state) {
	if (In2iGui.get().state===state) {return;}
	var all = In2iGui.get().objects;
	for (key in all) {
		var obj = all[key];
		if (obj.options && obj.options.state) {
			if (obj.options.state==state) {
				obj.show();
			} else {
				obj.hide();
			}
		}
	}
	In2iGui.get().state==state;
}

///////////////////////////////// Indexes /////////////////////////////

In2iGui.nextIndex = function() {
	In2iGui.latestIndex++;
	return 	In2iGui.latestIndex;
};

In2iGui.nextPanelIndex = function() {
	In2iGui.latestPanelIndex++;
	return 	In2iGui.latestPanelIndex;
};

In2iGui.nextAlertIndex = function() {
	In2iGui.latestAlertIndex++;
	return 	In2iGui.latestAlertIndex;
};

In2iGui.nextTopIndex = function() {
	In2iGui.latestTopIndex++;
	return 	In2iGui.latestTopIndex;
};

///////////////////////////////// Curtain /////////////////////////////

In2iGui.showCurtain = function(options,zIndex) {
	var widget = options.widget;
	if (!widget.curtain) {
		widget.curtain = n2i.build('div',{'class':'in2igui_curtain',style:'z-index:none'});
		widget.curtain.onclick = function() {
			if (widget['$curtainWasClicked']) {
				widget['$curtainWasClicked']();
			}
		};
		var body = n2i.firstByClass(document.body,'in2igui_body');
		if (!body) {
			body=document.body;
		}
		body.appendChild(widget.curtain);
	}
	if (options.color) {
		widget.curtain.style.backgroundColor=options.color;
	}
	if (n2i.browser.msie) {
		widget.curtain.style.height=n2i.getDocumentHeight()+'px';
	} else {
		widget.curtain.style.position='fixed';
		widget.curtain.style.top='0';
		widget.curtain.style.left='0';
		widget.curtain.style.bottom='0';
		widget.curtain.style.right='0';
	}
	widget.curtain.style.zIndex=options.zIndex;
	n2i.setOpacity(widget.curtain,0);
	widget.curtain.style.display='block';
	n2i.ani(widget.curtain,'opacity',0.7,1000,{ease:n2i.ease.slowFastSlow});
}

In2iGui.hideCurtain = function(widget) {
	if (widget.curtain) {
		n2i.ani(widget.curtain,'opacity',0,200,{hideOnComplete:true});
	}
};

//////////////////////////////// Message //////////////////////////////

In2iGui.alert = function(o) {
	In2iGui.get().alert(o);
};

In2iGui.showMessage = function(options) {
	if (typeof(options)=='string') {
		// TODO: Backwards compatibility
		options={text:options};
	}
	if (!In2iGui.message) {
		In2iGui.message = n2i.build('div',{'class':'in2igui_message',html:'<div><div></div></div>'});
		if (!n2i.browser.msie) {
			n2i.setOpacity(In2iGui.message,0);
		}
		document.body.appendChild(In2iGui.message);
	}
	In2iGui.message.getElementsByTagName('div')[1].innerHTML=options.text;
	In2iGui.message.style.display='block';
	In2iGui.message.style.zIndex=In2iGui.nextTopIndex();
	In2iGui.message.style.marginLeft=(In2iGui.message.clientWidth/-2)+'px';
	In2iGui.message.style.marginTop=n2i.getScrollTop()+'px';
	if (!n2i.browser.msie) {
		n2i.ani(In2iGui.message,'opacity',1,300);
	}
	window.clearTimeout(In2iGui.messageTimer);
	if (options.duration) {
		In2iGui.messageTimer = window.setTimeout(In2iGui.hideMessage,options.duration);
	}
};

In2iGui.hideMessage = function() {
	if (In2iGui.message) {
		if (!n2i.browser.msie) {
			n2i.ani(In2iGui.message,'opacity',0,300,{hideOnComplete:true});
		} else {
			In2iGui.message.setStyle({display:'none'});
		}
	}
};

In2iGui.showToolTip = function(options) {
	var key = options.key || 'common';
	var t = In2iGui.toolTips[key];
	if (!t) {
		t = n2i.build('div',{'class':'in2igui_tooltip',style:'display:none;',html:'<div><div></div></div>',parent:document.body});
		In2iGui.toolTips[key] = t;
	}
	t.onclick = function() {In2iGui.hideToolTip(options);};
	var n = $(options.element);
	var pos = n.cumulativeOffset();
	t.select('div')[1].update(options.text);
	if (t.style.display=='none' && !n2i.browser.msie) {t.setStyle({opacity:0});}
	t.setStyle({'display':'block',zIndex:In2iGui.nextTopIndex()});
	t.setStyle({left:(pos.left-t.getWidth()+4)+'px',top:(pos.top+2-(t.getHeight()/2)+(n.getHeight()/2))+'px'});
	if (!n2i.browser.msie) {
		n2i.ani(t,'opacity',1,300);
	}
};

In2iGui.hideToolTip = function(options) {
	var key = options ? options.key || 'common' : 'common';
	var t = In2iGui.toolTips[key];
	if (t) {
		if (!n2i.browser.msie) {
			n2i.ani(t,'opacity',0,300,{hideOnComplete:true});
		} else {
			t.setStyle({display:'none'});
		}
	}
};

/////////////////////////////// Utilities /////////////////////////////

In2iGui.getElement = function(widgetOrElement) {
	if (n2i.dom.isElement(widgetOrElement)) {
		return widgetOrElement;
	} else if (widgetOrElement.getElement) {
		return widgetOrElement.getElement();
	}
	return null;
}

In2iGui.isWithin = function(e,element) {
	e = new n2i.Event(e);
	var offset = {left:n2i.getLeft(element),top:n2i.getTop(element)};
	var dims = {width:element.clientWidth,height:element.clientHeight};
	return e.left()>offset.left && e.left()<offset.left+dims.width && e.top()>offset.top && e.top()<offset.top+dims.height;
};

In2iGui.getIconUrl = function(icon,size) {
	return In2iGui.context+'/In2iGui/icons/'+icon+size+'.png';
};

In2iGui.createIcon = function(icon,size) {
	return n2i.build('span',{'class':'in2igui_icon in2igui_icon_'+size,style:'background-image: url('+In2iGui.getIconUrl(icon,size)+')'});
};

In2iGui.onDomReady = In2iGui.onReady = function(func) {
	if (In2iGui.domReady) {return func();}
	if (n2i.browser.gecko && document.baseURI.endsWith('xml')) {
		window.setTimeout(func,1000);
		return;
	}
	n2i.onReady(func);
	//document.observe('dom:loaded', func);
};

In2iGui.wrapInField = function(e) {
	var w = n2i.build('div',{'class':'in2igui_field',html:
		'<span class="in2igui_field_top"><span><span></span></span></span>'+
		'<span class="in2igui_field_middle"><span class="in2igui_field_middle"><span class="in2igui_field_content"></span></span></span>'+
		'<span class="in2igui_field_bottom"><span><span></span></span></span>'
	});
	n2i.firstByClass(w,'in2igui_field_content').appendChild(e);
	return w;
};

In2iGui.addFocusClass = function(o) {
	var ce = o.classElement || o.element, c = o['class'];
	n2i.listen(o.element,'focus',function() {
		n2i.addClass(ce,c);
	});
	n2i.listen(o.element,'blur',function() {
		n2i.removeClass(ce,c);
	});
};


/////////////////////////////// Validation /////////////////////////////

In2iGui.NumberValidator = function(options) {
	n2i.override({allowNull:false,min:0,max:10},options)
	this.min = options.min;
	this.max = options.max;
	this.allowNull = options.allowNull;
	this.middle = Math.max(Math.min(this.max,0),this.min);
}

In2iGui.NumberValidator.prototype = {
	validate : function(value) {
		if (n2i.isBlank(value) && this.allowNull) {
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

In2iGui.fadeIn = function(node,time) {
	if (n2i.getStyle(node,'display')=='none') {
		n2i.setStyle(node,{opacity:0,display:''});
	}
	n2i.animate(node,'opacity',1,time);
};

In2iGui.fadeOut = function(node,time) {
	hui.animate(node,'opacity',0,time,{hideOnComplete:true});
};

In2iGui.bounceIn = function(node,time) {
	if (n2i.browser.msie) {
		n2i.setStyle(node,{'display':'block',visibility:'visible'});
	} else {
		n2i.setStyle(node,{'display':'block','opacity':0,visibility:'visible'});
		n2i.animate(node,'transform','scale(0.1)',0);// rotate(10deg)
		window.setTimeout(function() {
			n2i.animate(node,'opacity',1,300);
			n2i.animate(node,'transform','scale(1)',400,{ease:n2i.ease.backOut}); // rotate(0deg)
		});
	}
};

//////////////////////////// Positioning /////////////////////////////

In2iGui.positionAtElement = function(element,target,options) {
	options = options || {};
	element = n2i.get(element);
	target = n2i.get(target);
	var origDisplay = n2i.getStyle(element,'display');
	if (origDisplay=='none') {
		n2i.setStyle(element,{'visibility':'hidden','display':'block'});
	}
	var pos = left = n2i.getLeft(target),top = n2i.getTop(target);
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
	n2i.setStyle(element,{'left':left+'px','top':top+'px'});
	if (origDisplay=='none') {
		n2i.setStyle(element,{'visibility':'visible','display':'none'});
	}
};

In2iGui.getTextAreaHeight = function(input) {
	var t = this.textAreaDummy;
	if (!t) {
		var t = this.textAreaDummy = document.createElement('div');
		t.className='in2igui_textarea_dummy';
		document.body.appendChild(t);
	}
	var html = input.value;
	if (html[html.length-1]==='\n') {
		html+='x';
	}
	html = n2i.escape(html).replace(/\n/g,'<br/>');
	t.innerHTML = html;
	t.style.width=(input.clientWidth)+'px';
	return t.clientHeight;
}

//////////////////// Delegating ////////////////////

In2iGui.extend = function(obj,options) {
	if (!obj.name) {
		In2iGui.latestObjectIndex++;
		obj.name = 'unnamed'+In2iGui.latestObjectIndex;
	}
	if (options!==undefined) {
		if (obj.options) {
			obj.options = n2i.override(obj.options,options);
		}
		obj.element = n2i.get(options.element);
		obj.name = options.name;
	}
	var ctrl = In2iGui.get();
	ctrl.objects[obj.name] = obj;
	obj.delegates = [];
	obj.listen = function(delegate) {
		n2i.addToArray(this.delegates,delegate);
	}
	obj.removeDelegate = function(delegate) {
		n2i.removeFromArray(this.delegates,delegate);
	}
	obj.clearDelegates = function() {
		this.delegates = [];
	}
	obj.fire = function(method,value,event) {
		In2iGui.callDelegates(this,method,value,event);
	}
	obj.fireProperty = function(key,value) {
		In2iGui.firePropertyChange(this,key,value);
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
		ctrl.layoutWidgets.push(obj);
	}
};

In2iGui.callDelegatesDrop = function(dragged,dropped) {
	var gui = In2iGui.get();
	var result = null;
	for (var i=0; i < gui.delegates.length; i++) {
		if (gui.delegates[i]['$drop$'+dragged.kind+'$'+dropped.kind]) {
			gui.delegates[i]['$drop$'+dragged.kind+'$'+dropped.kind](dragged,dropped);
		}
	}
};

In2iGui.callAncestors = function(obj,method,value,event) {
	if (typeof(value)=='undefined') value=obj;
	var d = In2iGui.get().getAncestors(obj);
	for (var i=0; i < d.length; i++) {
		if (d[i][method]) {
			d[i][method](value,event);
		}
	};
};

In2iGui.callDescendants = function(obj,method,value,event) {
	if (typeof(value)=='undefined') {
		value=obj;
	}
	if (!method[0]=='$') {
		method = '$'+method;
	}
	var d = In2iGui.get().getDescendants(obj);
	for (var i=0; i < d.length; i++) {
		if (d[i][method]) {
			thisResult = d[i][method](value,event);
		}
	};
};

In2iGui.callVisible = function(widget) {
	In2iGui.callDescendants(widget,'$visibilityChanged');
}

In2iGui.listen = function(d) {
	In2iGui.get().listen(d);
}

In2iGui.callDelegates = function(obj,method,value,event) {
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
	var superResult = In2iGui.callSuperDelegates(obj,method,value,event);
	if (result==null && superResult!=null) {
		result = superResult;
	}
	return result;
};

In2iGui.callSuperDelegates = function(obj,method,value,event) {
	if (typeof(value)=='undefined') value=obj;
	var gui = In2iGui.get();
	var result = null;
	for (var i=0; i < gui.delegates.length; i++) {
		var delegate = gui.delegates[i];
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

In2iGui.resolveImageUrl = function(widget,img,width,height) {
	for (var i=0; i < widget.delegates.length; i++) {
		if (widget.delegates[i].$resolveImageUrl) {
			return widget.delegates[i].$resolveImageUrl(img,width,height);
		}
	};
	var gui = In2iGui.get();
	for (var i=0; i < gui.delegates.length; i++) {
		var delegate = gui.delegates[i];
		if (delegate.$resolveImageUrl) {
			return delegate.$resolveImageUrl(img,width,height);
		}
	}
	return null;
};

////////////////////////////// Bindings ///////////////////////////

In2iGui.firePropertyChange = function(obj,name,value) {
	In2iGui.callDelegates(obj,'propertyChanged',{property:name,value:value});
};

In2iGui.bind = function(expression,delegate) {
	if (expression.charAt(0)=='@') {
		var pair = expression.substring(1).split('.');
		var obj = In2iGui.get(pair[0]);
		if (!obj) {
			n2i.log('Unable to bind to '+expression);
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

In2iGui.dwrUpdate = function() {
	var func = arguments[0];
	var delegate = {
  		callback:function(data) { In2iGui.handleDwrUpdate(data) }
	}
	var num = arguments.length;
	if (num==1) {
		func(delegate);
	} else if (num==2) {
		func(arguments[1],delegate);
	} else {
		alert('Too many parameters');
	}
};

In2iGui.handleDwrUpdate = function(data) {
	var gui = In2iGui.get();
	for (var i=0; i < data.length; i++) {
		if (gui.objects.get(data[i].name)) {
			gui.objects.get(data[i].name).updateFromObject(data[i]);
		}
	};
};

In2iGui.update = function(url,delegate) {
	var dlgt = {
		onSuccess:function(t) {In2iGui.handleUpdate(t,delegate)}
	}
	$get(url,dlgt);
};

In2iGui.handleUpdate = function(t,delegate) {
	var gui = In2iGui.get();
	var doc = t.responseXML.firstChild;
	var children = doc.childNodes;
	for (var i=0; i < children.length; i++) {
		if (children[i].nodeType==1) {
			var name = children[i].getAttribute('name');
			if (name && name!='' && gui.objects.get(name)) {
				gui.objects.get(name).updateFromNode(children[i]);
			}
		}
	};
	delegate.onSuccess();
};

/** @private */
In2iGui.jsonResponse = function(t,key) {
	if (!t.responseXML || !t.responseXML.documentElement) {
		var str = t.responseText.replace(/^\s+|\s+$/g, '');
		if (str.length>0) {
			var json = n2i.fromJSON(t.responseText);
		} else {
			json = '';
		}
		In2iGui.callDelegates(json,'success$'+key)
	} else {
		In2iGui.callDelegates(t,'success$'+key)
	}
};

/** @deprecated */
In2iGui.json = function(data,url,delegateOrKey) {
	var options = {url:url,method:'post',parameters:{},onException:function(e) {throw e}};
	if (typeof(delegateOrKey)=='string') {
		options.onSuccess=function(t) {In2iGui.jsonResponse(t,delegateOrKey)};
	} else {
		delegate = delegateOrKey;
	}
	for (key in data) {
		options.parameters[key]=n2i.toJSON(data[key])
	}
	n2i.request(options);
};

In2iGui.jsonRequest = function(o) {
	var options = {method:'post',parameters:{},onException:function(e) {throw e}};
	if (typeof(o.event)=='string') {
		options.onSuccess=function(t) {In2iGui.jsonResponse(t,o.event)};
	} else {
		delegate = delegateOrKey;
	}
	for (key in o.parameters) {
		options.parameters[key]=n2i.toJSON(o.parameters[key])
	}
	options.url = o.url;
	n2i.request(options);
};

In2iGui.request = function(options) {
	options = n2i.override({method:'post',parameters:{}},options);
	if (options.json) {
		for (key in options.json) {
			options.parameters[key]=n2i.toJSON(options.json[key]);
		}
	}
	var onSuccess = options.onSuccess;
	options.onSuccess=function(t) {
		if (typeof(onSuccess)=='string') {
			In2iGui.jsonResponse(t,onSuccess);
		} else if (t.responseXML && t.responseXML.documentElement && t.responseXML.documentElement.nodeName!='parsererror' && options.onXML) {
			options.onXML(t.responseXML);
		} else if (options.onJSON) {
			var str = t.responseText.replace(/^\s+|\s+$/g, '');
			if (str.length>0) {
				var json = n2i.fromJSON(t.responseText);
			} else {
				var json = null;
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
			In2iGui.callDelegates(t,'failure$'+onFailure)
		} else if (typeof(onFailure)=='function') {
			onFailure(t);
		}
	}
	options.onException = function(t,e) {n2i.log(e)};
	n2i.request(options);
};

In2iGui.parseItems = function(doc) {
	var root = doc.documentElement;
	var out = [];
	In2iGui.parseSubItems(root,out);
	return out;
};

In2iGui.parseSubItems = function(parent,array) {
	var children = parent.childNodes;
	for (var i=0; i < children.length; i++) {
		var node = children[i];
		if (node.nodeType==1 && node.nodeName=='title') {
			array.push({title:node.getAttribute('title'),type:'title'})
		} else if (node.nodeType==1 && node.nodeName=='item') {
			var sub = [];
			In2iGui.parseSubItems(node,sub);
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




//////////////////////////// Prototype extensions ////////////////////////////////
/*
if (Element.addMethods) {
	Element.addMethods({
		setClassName : function(element,name,set) {
			if (set) {
				element.addClassName(name);
			} else {
				element.removeClassName(name);
			}
			return element;
		}
	});
}
*/
/* EOF */
