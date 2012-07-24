/**
 * @constructor
 */
hui.ui.ObjectList = function(options) {
	this.options = hui.override({key:null},options);
	this.name = options.name;
	this.element = hui.get(options.element);
	this.body = hui.get.firstByTag(this.element,'tbody');
	this.template = [];
	this.objects = [];
	hui.ui.extend(this);
}

hui.ui.ObjectList.create = function(o) {
	o=o || {};
	var e = o.element = hui.build('table',{'class':'hui_objectlist',cellpadding:'0',cellspacing:'0'});
	if (o.template) {
		var head = '<thead><tr>';
		for (var i=0; i < o.template.length; i++) {
			head+='<th>'+(o.template[i].label || '')+'</th>';
		};
		head+='</tr></thead>';
		e.innerHTML=head;
	}
	hui.build('tbody',{parent:e});
	var list = new hui.ui.ObjectList(o);
	if (o.template) {
		hui.each(o.template,function(item) {
			list.registerTemplateItem(new hui.ui.ObjectList.Text(item.key));
		});
	}
	return list;
}

hui.ui.ObjectList.prototype = {
	ignite : function() {
		this.addObject({});
	},
	addObject : function(data,addToEnd) {
		var obj;
		if (this.objects.length==0 || addToEnd) {
			obj = new hui.ui.ObjectList.Object(this.objects.length,data,this);
			this.objects.push(obj);
			this.body.appendChild(obj.getElement());
		} else {
			var last = this.objects[this.objects.length-1];
			hui.array.remove(this.objects,last);
			obj = new hui.ui.ObjectList.Object(last.index,data,this);
			last.index++;
			this.objects.push(obj);
			this.objects.push(last);
			this.body.insertBefore(obj.getElement(),last.getElement())
		}
	},
	reset : function() {
		for (var i=0; i < this.objects.length; i++) {
			var element = this.objects[i].getElement();
			if (!element.parentNode) {
				hui.log('no parent for...');
				hui.log(element);
			} else {
				element.parentNode.removeChild(element);
			}
		};
		this.objects = [];
		this.addObject({});
	},
	addObjects : function(data) {
		for (var i=0; i < data.length; i++) {
			this.addObject(data[i]);
		};
	},
	setObjects : function(data) {
		this.reset();
		this.addObjects(data);
	},
	getObjects : function(data) {
		var list = [];
		for (var i=0; i < this.objects.length-1; i++) {
			list.push(this.objects[i].getData());
		};
		return list;
	},
	getValue : function() {
		return this.getObjects();
	},
	setValue : function(data) {
		this.setObjects(data);
	},
	registerTemplateItem : function(item) {
		this.template.push(item);
	},
	objectDidChange : function(obj) {
		if (obj.index>=this.objects.length-1) {
			this.addObject({},true);
		}
	},
	getLabel : function() {
		return this.options.label;
	}
}

/********************** Object ********************/

/** @constructor */
hui.ui.ObjectList.Object = function(index,data,list) {
	this.data = data;
	this.index = index;
	this.list = list;
	this.fields = [];
}

hui.ui.ObjectList.Object.prototype = {
	getElement : function() {
		if (!this.element) {
			this.element = document.createElement('tr');
			for (var i=0; i < this.list.template.length; i++) {
				var template = this.list.template[i];
				var field = template.clone();
				field.object = this;
				this.fields.push(field);
				var cell = document.createElement('td');
				if (i==0) cell.className='first';
				cell.appendChild(field.getElement());
				field.setValue(this.data[template.key]);
				this.element.appendChild(cell);
			};
		}
		return this.element;
	},
	valueDidChange : function() {
		this.list.objectDidChange(this);
	},
	getData : function() {
		var data = this.data;
		for (var i=0; i < this.fields.length; i++) {
			data[this.fields[i].key] = this.fields[i].getValue();
		};
		return data;
	}
}

/*************************** Text **************************/

hui.ui.ObjectList.Text = function(key) {
	this.key = key;
	this.value = null;
}

hui.ui.ObjectList.Text.prototype = {
	clone : function() {
		return new hui.ui.ObjectList.Text(this.key);
	},
	getElement : function() {
		var input = hui.build('input',{'class':'hui_formula_text'});
		var field = hui.ui.wrapInField(input);
		this.wrapper = new hui.ui.Input({element:input});
		this.wrapper.listen(this);
		hui.ui.addFocusClass({element:input,classElement:field,'class':'hui_field_focused'});
		return field;
	},
	$valueChanged : function(value) {
		this.value = value;
		this.object.valueDidChange();
	},
	getValue : function() {
		return this.value;
	},
	setValue : function(value) {
		this.value = value;
		this.wrapper.setValue(value);
	}
}

/*************************** Select **************************/

hui.ui.ObjectList.Select = function(key) {
	this.key = key;
	this.value = null;
	this.options = [];
}

hui.ui.ObjectList.Select.prototype = {
	clone : function() {
		var copy = new hui.ui.ObjectList.Select(this.key);
		copy.options = this.options;
		return copy;
	},
	getElement : function() {
		this.select = hui.build('select');
		for (var i=0; i < this.options.length; i++) {
			this.select.options[this.select.options.length] = new Option(this.options[i].label,this.options[i].value);
		};
		var self = this;
		this.select.onchange = function() {
			self.object.valueDidChange();
		}
		return this.select;
	},
	getValue : function() {
		return this.select.value;
	},
	setValue : function(value) {
		this.select.value = value;
	},
	addOption : function(value,label) {
		this.options.push({value:value,label:label});
	}
}

/* EOF */