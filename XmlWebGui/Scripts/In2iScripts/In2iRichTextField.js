if (!N2i) {var N2i = {};}

N2i.RichTextField = function(field,options) {
	this.field = $id(field);
	this.iframe;
	this.document;
	this.container;
	this.toolbar;
	this.options = options || {debug:false};
	if (N2i.RichTextField.isCompatible()) {
		this.buildIframe();
	}
}

N2i.RichTextField.actions = [
	{key:'p',		cmd:'formatblock',		value:'p'},
	{key:'h1',		cmd:'formatblock',		value:'h1'},
	{key:'h2',		cmd:'formatblock',		value:'h2'},
	{key:'h3',		cmd:'formatblock',		value:'h3'},
	{key:'h4',		cmd:'formatblock',		value:'h4'},
	{key:'h5',		cmd:'formatblock',		value:'h5'},
	{key:'h6',		cmd:'formatblock',		value:'h6'},
	{key:'bold',	cmd:'bold',				value:null},
	{key:'italic',	cmd:'italic',			value:null},
	{key:'removeformat', cmd:'removeformat', 'value':null}
];

N2i.RichTextField.isCompatible = function() {
    var agt=navigator.userAgent.toLowerCase();
	return true;
	return (agt.indexOf('msie 6')>-1 || agt.indexOf('msie 7')>-1 || (agt.indexOf('gecko')>-1 && agt.indexOf('safari')<0));
}

N2i.RichTextField.prototype.buildIframe = function() {
	var width = N2i.Element.getStyle(this.field,'width');
	var height = N2i.Element.getStyle(this.field,'height');
	this.iframe = document.createElement('iframe');
	this.iframe.style.width=width;
	this.iframe.style.height=height;
	this.iframe.src="about:blank";
	this.iframe.frameBorder = 0;
	if (!this.options.debug) {
		this.field.style.display='none';
	}
	var self = this;
	if (N2i.isIE()) {
		window.setTimeout(function() {self.setupIframe();},1000);
	} else {
		this.iframe.onload=function() {self.setupIframe();}
	}
	N2i.Event.addListener(window,'unload',function() {self.documentChanged()});
	this.buildContainer();
	this.container.appendChild(this.iframe);
	this.field.parentNode.insertBefore(this.container,this.field);
}

N2i.RichTextField.prototype.setupIframe = function() {
	var self = this;
	this.window = this.iframe.contentWindow;
	this.window.onkeypress=function() {self.documentChanged()};
	if (N2i.isIE()) {
		this.document = this.window.document
	} else {
		this.document = this.iframe.contentDocument;
	}
	this.document.body.innerHML='';
	this.document.designMode='on';
	if (!N2i.isIE()) {
		this.document.body.innerHTML = this.field.value;
		this.document.body.style.minHeight='100%';
		this.document.documentElement.style.cursor='text';
		this.document.documentElement.style.minHeight='100%';
	}
	N2i.Event.addListener(this.window,'keyup',function() {self.documentChanged()});
}

N2i.RichTextField.prototype.buildContainer = function() {
	var width = N2i.Element.getStyle(this.field,'width');
	var height = N2i.Element.getStyle(this.field,'height');
	this.container = document.createElement('div');
	this.container.className='in2i_richtextfield';
	this.container.style.width=width;
	this.buildToolbar();
	this.container.appendChild(this.toolbar);
}

N2i.RichTextField.prototype.buildToolbar = function() {
	var self = this;
	this.toolbar = document.createElement('div');
	this.toolbar.className='toolbar';
	var actions = N2i.RichTextField.actions;
	for (var i=0; i < actions.length; i++) {
		var div = document.createElement('div');
		div.className='action';
		div.appendChild(document.createTextNode(actions[i].key));
		div.n2iRichTextFieldAction = actions[i];
		div.onmousedown = function(e) {return self.actionWasClicked(this.n2iRichTextFieldAction,e);}
		this.toolbar.appendChild(div);
	};
}

N2i.RichTextField.prototype.documentChanged = function() {
	this.field.value = this.document.body.innerHTML;
}

N2i.RichTextField.prototype.actionWasClicked = function(action,e) {
	switch(action.key) {
		case "save":
			
		default:
			this.execCommand(action);
	}
  var evt = e ? e : window.event; 
	N2i.Event.stop(e);
	if (evt.returnValue) {
    evt.returnValue = false;
  } else if (evt.preventDefault) {
    evt.preventDefault( );
  } else {
    return false;
  }
	return false;
}


N2i.RichTextField.prototype.execCommand = function(action) {
	this.document.execCommand(action.cmd,false,action.value);
	this.documentChanged();
}