/**
 * A tokens component
 * @constructor
 */
hui.ui.TokenField = function(o) {
	this.options = hui.override({label:null,key:null},o);
	this.element = hui.get(o.element);
	this.name = o.name;
	this.value = [''];
	hui.ui.extend(this);
	this.updateUI();
}

hui.ui.TokenField.create = function(o) {
	o = o || {};
	o.element = hui.build('div',{'class':'hui_tokenfield'});
	return new hui.ui.TokenField(o);
}

hui.ui.TokenField.prototype = {
	setValue : function(objects) {
		this.value = objects;
		this.value.push('');
		this.updateUI();
	},
	reset : function() {
		this.value = [''];
		this.updateUI();
	},
	getValue : function() {
		var out = [];
		hui.each(this.value,function(value) {
			value = hui.trim(value);
			if (value.length>0) {
				out.push(value);
			}
		})
		return out;
	},
	getLabel : function() {
		return this.options.label;
	},
	updateUI : function() {
		this.element.innerHTML='';
		hui.each(this.value,function(value,i) {
			var input = hui.build('input',{'class':'hui_tokenfield_token',parent:this.element});
			if (this.options.width) {
				input.style.width=this.options.width+'px';
			}
			input.value = value;
			hui.listen(input,'keyup',function() {
				this.inputChanged(input,i)
			}.bind(this));
		}.bind(this));
	},
	/** @private */
	inputChanged : function(input,index) {
		if (index==this.value.length-1 && input.value!=this.value[index]) {
			this.addField();
		}
		this.value[index] = input.value;
		hui.animate({node:input,css:{width:Math.min(Math.max(input.value.length*7+3,50),150)+'px'},duration:200});
	},
	/** @private */
	addField : function() {
		var input = hui.build('input',{'class':'hui_tokenfield_token'});
		if (this.options.width) {
			input.style.width = this.options.width+'px';
		}
		var i = this.value.length;
		this.value.push('');
		this.element.appendChild(input);
		var self = this;
		hui.listen(input,'keyup',function() {self.inputChanged(input,i)});
	}
}

/* EOF */