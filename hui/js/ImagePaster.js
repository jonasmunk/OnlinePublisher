/**
 * Image pasting madness
 * @constructor
 */
hui.ui.ImagePaster = function(options) {
	hui.log('New paster')
	this.options = options || {};
	this.element = hui.get(options.element);
	this.name = options.name;
	this.data = null;
	hui.ui.extend(this);
}

hui.ui.ImagePaster.create = function(options) {
	options = options || {};
	var e = options.element = hui.build('div',{className:'hui_imagepaster'});
	return new hui.ui.ImagePaster(options);
}

hui.ui.ImagePaster.prototype = {
	_initialize : function() {
		/*
		this.element.innerHTML = '<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" width= "290" height= "290" style="border-width:0;"  id="rup" name="rup" codebase="http://java.sun.com/products/plugin/autodl/jinstall-1_4_1-windows-i586.cab#version=1,4,1"><param name="archive" value="../../../hui/lib/supa/Supa.jar"><param name="code" value="de.christophlinder.supa.SupaApplet"><param name="mayscript" value="yes"><param name="scriptable" value="true"><param name="name" value="jsapplet"><param name="encoding" value="base64"><param name="previewscaler" value="original size"></object>';
		return;
		this.element.innerHTML = '<applet id="SupaApplet" archive="../../../hui/lib/supa/Supa.jar" code="de.christophlinder.supa.SupaApplet" width="0" height="0"><param name="imagecodec" value="png"><param name="encoding" value="base64"><param name="previewscaler" value="original size"></applet>';
		return;*/
		hui.log('Initializing...');
		this.applet = hui.build('applet',{
			archive : hui.ui.context+"/hui/lib/supa/Supa.jar",
			code : 'de.christophlinder.supa.SupaApplet',
			width : 0,
			height : 0,
			html : '<param name="imagecodec" value="png"><param name="encoding" value="base64"><param name="previewscaler" value="original size">',
			parent : this.element
		});
		if (this.options.invisible) {
			hui.log('Adding to body...');
			document.body.appendChild(this.element);
		}
		this._checkReady();
		this.initialized = true;
	},
	_checkReady : function() {
		if (this._isReady()) {
			hui.log('Ready...');
			if (this.pendingPaste) {
				hui.log('Running pedning paste...');
				this.paste();
			}
			this.fire('readyToPaste',this);
		} else {
			window.setTimeout(this._checkReady.bind(this),100);
		}
	},
	_isReady : function() {
		try {
			if (this.applet.ping) {
				return this.applet.ping()
			}
		} catch (e) {
			hui.log(e)
		}
		return false;
	},
	paste : function() {
		if (!this.initialized) {
			hui.log('Pasting, not intitialized, so pending...');
			this.pendingPaste = true;
			this._initialize();
			return;
		}
		hui.log('Pasting...');
		var error = this.applet.pasteFromClipboard(); 
		if (error!==0) {
			this._error(error);
			return;
		}
		this.data = this.applet.getEncodedString();
		this._updatePreview();
		hui.log('Sending: imageWasPasted');
		this.fire('imageWasPasted',this.data);
	},
	_error : function(code) {
		var key = 'unknown';
		if (code==2) {
			key = 'empty';
		} else if (code==3) {
			key = 'invalid';
		} else if (code==4) {
			key = 'busy';
		}
		this.fire('imagePasteFailed',key);
	},
	_updatePreview : function() {
		if (this.options.invisible) {return}
		if (this.preview) {
			this.preview.src = 'data:image/png;base64,'+this.data;
		} else {
			var container = hui.build('div',{className:'hui_imagepaster_preview',parent:this.element});
			this.preview = hui.build('img',{src:'data:image/png;base64,'+this.data,parent:container});
		}
	}
}