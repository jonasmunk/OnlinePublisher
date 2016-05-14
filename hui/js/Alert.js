/**
 * An alert
 * <pre><strong>options:</strong> {
 *  element : «Element | ID»,
 *  name : «String»,
 *  modal : «true | <strong>false</strong>»
 * }
 * </pre>
 * @constructor
 * @param {Object} options The options
 */
hui.ui.Alert = function(options) {
	this.options = hui.override({modal:false},options);
	this.element = hui.get(options.element);
	this.name = options.name;
	this.body = hui.get.firstByClass(this.element,'hui_alert_body');
	this.content = hui.get.firstByClass(this.element,'hui_alert_content');
	this.emotion = this.options.emotion;
	this.title = hui.get.firstByTag(this.element,'h1');
	hui.ui.extend(this);
};

/**
 * Creates a new instance of an alert
 * <pre><strong>options:</strong> {
 *  title : «String»,
 *  text : «String»,
 *  emotion: «'smile' | 'gasp'»,
 *
 *  modal : «true | <strong>false</strong>»,
 *  name : «String»
 * }
 * </pre>
 * @static
 * @param {Object} options The options
 */
hui.ui.Alert.create = function(options) {
	options = hui.override({text:'',emotion:null,title:null},options);
	
	var element = options.element = hui.build('div',{'class':'hui_alert'});
	var body = hui.build('div',{'class':'hui_alert_body',parent:element});
	hui.build('div',{'class':'hui_alert_content',parent:body});
	document.body.appendChild(element);
	var obj = new hui.ui.Alert(options);
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
};

hui.ui.Alert.prototype = {
	/** Shows the alert */
	show : function() {
		var zIndex = hui.ui.nextAlertIndex();
		if (this.options.modal) {
			hui.ui.showCurtain({widget:this,zIndex:zIndex});
			zIndex++;
		}
		this.element.style.zIndex=zIndex;
		this.element.style.display='block';
		this.element.style.top=(hui.window.getScrollTop()+100)+'px';
		hui.animate(this.element,'opacity',1,200);
		hui.animate(this.element,'margin-top','40px',600,{ease:hui.ease.elastic});
	},
	/** Hides the alert */
	hide : function() {
		hui.animate(this.element,'opacity',0,200,{hideOnComplete:true});
		hui.animate(this.element,'margin-top','0px',200);
		hui.ui.hideCurtain(this);
	},
	/** Sets the alert title
	 * @param {String} text The new title
	 */
	setTitle : function(text) {
		if (!this.title) {
			this.title = hui.build('h1',{parent:this.content});
		}
		hui.dom.setText(this.title,hui.ui.getTranslated(text));
		
	},
	/** Sets the alert text
	 * @param {String} text The new text
	 */
	setText : function(text) {
		if (!this.text) {
			this.text = hui.build('p',{parent:this.content});
		}
		hui.dom.setText(this.text,hui.ui.getTranslated(text));
	},
	/** Sets the alert emotion
	 * @param {String} emotion Can be 'smile' or 'gasp'
	 */
	setEmotion : function(emotion) {
		if (this.emotion) {
			hui.cls.remove(this.body,this.emotion);
		}
		this.emotion = emotion;
		hui.cls.add(this.body,emotion);
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
	/** Adds a Button to the alert
	 * @param {hui.ui.Button} button The button to add
	 */
	addButton : function(button) {
		if (!this.buttons) {
			this.buttons = hui.ui.Buttons.create({align:'right'});
			this.body.appendChild(this.buttons.element);
		}
		this.buttons.add(button);
	}
};