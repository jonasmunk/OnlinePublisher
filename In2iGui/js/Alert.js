/**
 * An alert
 * @constructor
 */
In2iGui.Alert = function(options) {
	this.options = n2i.override({modal:false},options);
	this.element = n2i.get(options.element);
	this.name = options.name;
	this.body = this.element.select('.in2igui_alert_body')[0];
	this.content = this.element.select('.in2igui_alert_content')[0];
	this.emotion = this.options.emotion;
	this.title = this.element.select('h1').first();
	In2iGui.extend(this);
}

/**
 * Creates a new instance of an alert
 * <br/><strong>options:</strong> { name: «String», title: «String», text: «String», emotion: «'smile' | 'gasp'», modal: «Boolean»}
 * @static
 */
In2iGui.Alert.create = function(options) {
	options = n2i.override({title:'',text:'',emotion:null,title:null},options);
	
	var element = options.element = new Element('div',{'class':'in2igui_alert'});
	var body = new Element('div',{'class':'in2igui_alert_body'});
	element.insert(body);
	var content = new Element('div',{'class':'in2igui_alert_content'});
	body.insert(content);
	document.body.appendChild(element);
	var obj = new In2iGui.Alert(options);
	if (options.emotion) {
		obj.setEmotion(options.emotion);
	}
	if (options.title) {
		obj.setTitle(options.title);
	}
	if (options.text) {
		obj.setText(options.text);
	}
	
	return obj;
}

In2iGui.Alert.prototype = {
	/** Shows the alert */
	show : function() {
		var zIndex = In2iGui.nextAlertIndex();
		if (this.options.modal) {
			In2iGui.showCurtain({widget:this,zIndex:zIndex});
			zIndex++;
		}
		this.element.style.zIndex=zIndex;
		this.element.style.display='block';
		this.element.style.top=(n2i.getScrollTop()+100)+'px';
		n2i.animate(this.element,'opacity',1,200);
		n2i.animate(this.element,'margin-top','40px',600,{ease:n2i.ease.elastic});
	},
	/** Hides the alert */
	hide : function() {
		n2i.animate(this.element,'opacity',0,200,{hideOnComplete:true});
		n2i.animate(this.element,'margin-top','0px',200);
		In2iGui.hideCurtain(this);
	},
	/** Sets the alert title */
	setTitle : function(/**String*/ text) {
		if (!this.title) {
			this.title = new Element('h1');
			this.content.appendChild(this.title);
		}
		this.title.innerHTML = text;
		
	},
	/** Sets the alert text */
	setText : function(/**String*/ text) {
		if (!this.text) {
			this.text = new Element('p');
			this.content.appendChild(this.text);
		}
		this.text.innerHTML = text || '';
	},
	/** Sets the alert emotion */
	setEmotion : function(/**String*/ emotion) {
		if (this.emotion) {
			this.body.removeClassName(this.emotion);
		}
		this.emotion = emotion;
		this.body.addClassName(emotion);
	},
	/** Updates multiple properties
	 * @param {Object} options {title: «String», text: «String», emotion: «'smile' | 'gasp'»}
	 */
	update : function(options) {
		options = options || {};
		this.setTitle(options.title || null);
		this.setText(options.text || null);
		this.setEmotion(options.emotion || null);
	},
	/** Adds an In2iGui.Button to the alert */
	addButton : function(button) {
		if (!this.buttons) {
			this.buttons = In2iGui.Buttons.create({align:'right'});
			this.body.appendChild(this.buttons.element);
		}
		this.buttons.add(button);
	}
}

/* EOF */