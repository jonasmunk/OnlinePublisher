/**
	Used to choose an image
	@constructor
*/
hui.ui.ImagePicker = function(o) {
	this.name = o.name;
	this.options = o || {};
	this.element = hui.get(o.element);
	this.images = [];
	this.object = null;
	this.thumbnailsLoaded = false;
	hui.ui.extend(this);
	this.addBehavior();
}

hui.ui.ImagePicker.prototype = {
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
	getValue : function() {
		return this.object;
	},
	reset : function() {
		this.object = null;
		this.updateUI();
	},
	/** @private */
	updateUI : function() {
		if (this.object==null) {
			this.element.style.backgroundImage = '';
		} else {
			var url = hui.ui.resolveImageUrl(this,this.object,48,48);
			this.element.style.backgroundImage = 'url('+url+')';
		}
	},
	/** @private */
	showPicker : function() {
		if (!this.picker) {
			var self = this;
			this.picker = hui.ui.BoundPanel.create({modal:true});
			this.content = hui.build('div',{'class':'hui_imagepicker_thumbs'});
			var buttons = hui.ui.Buttons.create({align:'right'});
			var close = hui.ui.Button.create({text:'Luk',highlighted:true,small:true});
			close.listen({
				$click : function() {self.hidePicker()}
			});
			var remove = hui.ui.Button.create({text:'Fjern',small:true});
			remove.listen({
				$click : function() {self.setObject(null);self.hidePicker()}
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
		hui.request({
			onSuccess:function(t) {
				self.parse(t.responseXML);
			},
			url:this.options.source
		});
	},
	/** @private */
	parse : function(doc) {
		this.content.innerHTML='';
		var images = doc.getElementsByTagName('image');
		var self = this;
		for (var i=0; i < images.length; i++) {
			var id = images[i].getAttribute('id');
			var img = {id:images[i].getAttribute('id')};
			var url = hui.ui.resolveImageUrl(this,img,48,48);
			var thumb = hui.build('div',{'class':'hui_imagepicker_thumbnail',style:'background-image:url('+url+')'});
			thumb.huiObject = {'id':id};
			thumb.onclick = function() {
				self.setObject(this.huiObject);
				self.hidePicker();
			}
			this.content.appendChild(thumb);
		};
	}
}

/* EOF */