/**
	Used to choose an image
	@constructor
*/
In2iGui.ImagePicker = function(o) {
	this.name = o.name;
	this.options = o || {};
	this.element = $(o.element);
	this.images = [];
	this.object = null;
	this.thumbnailsLoaded = false;
	In2iGui.extend(this);
	this.addBehavior();
}

In2iGui.ImagePicker.prototype = {
	/** @private */
	addBehavior : function() {
		this.element.onclick = this.showPicker.bind(this);
	},
	setObject : function(obj) {
		this.object = obj;
		this.updateUI();
	},
	getObject : function() {
		return this.object;
	},
	reset : function() {
		this.object = null;
		this.updateUI();
	},
	/** @private */
	updateUI : function() {
		if (this.object==null) {
			this.element.style.backgroundImage='';
		} else {
			var url = In2iGui.resolveImageUrl(this,this.object,48,48);
			this.element.style.backgroundImage='url('+url+')';
		}
	},
	/** @private */
	showPicker : function() {
		if (!this.picker) {
			var self = this;
			this.picker = In2iGui.BoundPanel.create();
			this.content = new Element('div',{'class':'in2igui_imagepicker_thumbs'});
			var buttons = In2iGui.Buttons.create({align:'right'});
			var close = In2iGui.Button.create({text:'Luk',highlighted:true});
			close.listen({
				$click:function() {self.hidePicker()}
			});
			var remove = In2iGui.Button.create({text:'Fjern'});
			remove.listen({
				$click:function() {self.setObject(null);self.hidePicker()}
			});
			buttons.add(remove).add(close);
			this.picker.add(this.content);
			this.picker.add(buttons);
		}
		this.picker.position(this.element);
		this.picker.show();
		if (!this.thumbnailsLoaded) {
			this.updateImages();
			this.thumbnailsLoaded = true;
		}
	},
	/** @private */
	hidePicker : function() {
		this.picker.hide();
	},
	/** @private */
	updateImages : function() {
		var self = this;
		var delegate = {
			onSuccess:function(t) {
				self.parse(t.responseXML);
			}
		};
		new Ajax.Request(this.options.source,delegate);
	},
	/** @private */
	parse : function(doc) {
		this.content.update();
		var images = doc.getElementsByTagName('image');
		var self = this;
		for (var i=0; i < images.length; i++) {
			var id = images[i].getAttribute('id');
			var img = {id:images[i].getAttribute('id')};
			var url = In2iGui.resolveImageUrl(this,img,48,48);
			var thumb = new Element('div',{'class':'in2igui_imagepicker_thumbnail'}).setStyle({'backgroundImage':'url('+url+')'});
			thumb.in2iguiObject = {'id':id};
			thumb.onclick = function() {
				self.setObject(this.in2iguiObject);
				self.hidePicker();
			}
			this.content.appendChild(thumb);
		};
	}
}

/* EOF */