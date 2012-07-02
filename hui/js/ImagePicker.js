/**
	Used to choose an image
	@constructor
*/
hui.ui.ImagePicker = function(options) {
	this.name = options.name;
	this.options = hui.override({width:48,height:48},options);
	this.element = hui.get(options.element);
	this.images = [];
	this.value = null;
	this.thumbnailsLoaded = false;
	hui.ui.extend(this);
	this._addBehavior();
}

hui.ui.ImagePicker.prototype = {
	_addBehavior : function() {
		hui.listen(this.element,'click',this._showPicker.bind(this));
	},
	setObject : function(obj) {
		this.value = obj;
		this._updateUI();
	},
	getObject : function() {
		return this.value;
	},
	getValue : function() {
		return this.value;
	},
	setValue : function(obj) {
		this.setObject(obj);
	},
	reset : function() {
		this.value = null;
		this._updateUI();
	},
	_updateUI : function() {
		if (this.value==null) {
			this.element.style.backgroundImage = '';
		} else {
			var url = hui.ui.resolveImageUrl(this,this.value,this.options.width,this.options.height);
			this.element.style.backgroundImage = 'url('+url+')';
		}
	},
	_showPicker : function() {
		if (!this.picker) {
			var self = this;
			this.picker = hui.ui.BoundPanel.create({modal:true});
			this.content = hui.build('div',{'class':'hui_imagepicker_thumbs'});
			var buttons = hui.ui.Buttons.create({align:'right'});
			var close = hui.ui.Button.create({text:'Luk',highlighted:true,small:true});
			close.listen({
				$click : function() {self._hidePicker()}
			});
			var remove = hui.ui.Button.create({text:'Fjern',small:true});
			remove.listen({
				$click : function() {
					self.setObject(null);
					self._hidePicker()
					self._fireChange();
				}
			});
			buttons.add(remove).add(close);
			this.picker.add(this.content);
			this.picker.add(buttons);
		}
		this.picker.position(this.element);
		this.picker.show();
		if (!this.thumbnailsLoaded) {
			this._updateImages();
			this.thumbnailsLoaded = true;
		}
	},
	_hidePicker : function() {
		this.picker.hide();
	},
	_fireChange : function() {
		this.fireValueChange();
	},
	_updateImages : function() {
		var self = this;
		hui.request({
			onSuccess:function(t) {
				self._parse(t.responseXML);
			},
			url : this.options.source
		});
	},
	_parse : function(doc) {
		this.content.innerHTML='';
		var images = doc.getElementsByTagName('image');
		var self = this;
		for (var i=0; i < images.length; i++) {
			var id = parseInt(images[i].getAttribute('id'));
			var img = {id:id};
			var url = hui.ui.resolveImageUrl(this,img,48,48);
			var thumb = hui.build('div',{'class':'hui_imagepicker_thumbnail',style:'background-image:url('+url+')'});
			thumb.huiObject = {'id':id};
			thumb.onclick = function() {
				self.setObject(this.huiObject);
				self._hidePicker();
				self._fireChange();
			}
			this.content.appendChild(thumb);
		};
	}
}

/* EOF */