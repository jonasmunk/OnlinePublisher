/**
 * An input for objects
 * @constructor
 */
hui.ui.ObjectInput = function(options) {
	var e = this.element = hui.get(options.element);
	this.options = hui.override({types:[]},options);
	this.types = this.options.types;	
	this.name = options.name;
	this.value = options.value;
	this.input = hui.get.firstByTag(e,'input')
	this.object = hui.get.firstByClass(e,'hui_objectinput_object')
	this.dropdown = new hui.ui.DropDown({
		element : hui.get.firstByClass(e,'hui_dropdown'),
		value : 'none',
		items : this._buildDropDownOptions(),
		listener : {
			$valueChanged : this._onDropDownChange.bind(this)
		}
	})
	hui.ui.extend(this);
	this._addBehavior();
}

hui.ui.ObjectInput.prototype = {
	_addBehavior : function() {
		//hui.listen(this.textarea,'keydown',this._onKeyDown.bind(this));
		//hui.listen(this.textarea,'keyup',this._onKeyUp.bind(this));
	},
	_buildDropDownOptions : function() {
		var options = [{value:'none',text:'Intet link'}];
		hui.log(this.options.types)
		for (var i=0; i < this.options.types.length; i++) {
			var type = this.options.types[i];
			options.push({value:type.key,text:type.label})
		};
		return options;
	},
	_getType : function(key) {
		for (var i=0; i < this.types.length; i++) {
			if (this.types[i].key==key) {
				return this.types[i];
			}
		};
	},
	_onDropDownChange : function(value) {
		this._closeAllFinders();
		var type = this._getType(value);
		if (!type) {
			this.input.style.display = this.object.style.display = 'none';
			return;
		}
		this.input.style.display = !type.finderOptions ? '' : 'none';
		this.object.style.display = type.finderOptions ? '' : 'none';
		if (type.finderOptions) {
			if (!type._finder) {
				type._finder = hui.ui.Finder.create(
					type.finderOptions
				);
				type._finder.listen({
					$select : function(object) {
						this._selectObject(type,object);
					}.bind(this)
				})
			}
			type._finder.show();
		} else  {
			this.input.focus();
		}
	},
	_closeAllFinders : function() {
		for (var i=0; i < this.types.length; i++) {
			if (this.types[i]._finder) {
				this.types[i]._finder.hide();
			}
		}
	},
	_showPageFinder : function() {
		if (!this._pageFinder) {
			this._pageFinder = hui.ui.Finder.create({
				title : 'Vælg side',
				selection : {}
			});
		}
		this._pageFinder.show();
	},
	_selectObject : function(type,object) {
		this.value = {type : type.key, value : object};
		var title = hui.get.firstByClass(this.element,'hui_objectinput_title'),
			icon = hui.get.firstByClass(this.element,'hui_objectinput_icon');
		hui.dom.setText(title,hui.string.shorten(object.title,40));
		icon.style.backgroundImage = 'url(\''+hui.ui.getIconUrl(object.icon,16)+'\')';
		this._closeAllFinders();
		this.fireValueChange();
	},
	_showFileFinder : function() {
		if (!this._fileFinder) {
			this._fileFinder = hui.ui.Finder.create({
				title : 'Vælg fil',
				selection : {}
			});
		}
		this._fileFinder.show();
	},
	
	getValue : function() {
		return this.value;
	},
	setValue : function(value) {
		this.value = value;
	},
	reset : function() {
		this.setValue(null);
	}
}