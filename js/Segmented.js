/**
 * @constructor
 * @param {Object} options The options
 */
hui.ui.Segmented = function(options) {
	this.options = hui.override({value:null,allowNull:false},options);
	this.element = hui.get(options.element);
	this.name = options.name;
	this.value = this.options.value;
	hui.ui.extend(this);
	hui.listen(this.element,'mousedown',this._click.bind(this));
}

hui.ui.Segmented.create = function(options) {
    var e = options.element = hui.build('span',{'class':'hui_segmented hui_segmented_standard'});
    if (options.items) {
        for (var i = 0; i < options.items.length; i++) {
            var item = options.items[i];
            var a = hui.build('a',{parent:e,href:'javascript://','rel':item.value});
            if (item.icon) {
                a.appendChild(hui.ui.createIcon(item.icon,16));
            }
            if (item.text) {
                hui.build('span',{'class':'hui_segmented_text',text:item.text,parent:a});
            }
            if (options.value!==undefined && options.value == item.value) {
                hui.cls.add(a,'hui_segmented_selected');
            }
        }
    }
    return new hui.ui.Segmented(options);
}

hui.ui.Segmented.prototype = {
	_click : function(e) {
		e = new hui.Event(e);
		var a = e.findByTag('a');
		if (a) {
			var changed = false;
			var value = a.getAttribute('rel');
			var x = hui.get.byClass(this.element,'hui_segmented_selected');
			for (var i=0; i < x.length; i++) {
				hui.cls.remove(x[i],'hui_segmented_selected');
			};
			if (value===this.value && this.options.allowNull) {
				changed=true;
				this.value = null;
			} else {
				hui.cls.add(a,'hui_segmented_selected');
				changed=this.value!== value;
				this.value = value;
			}
			if (changed) {
				this.fireValueChange();
			}
		}
	},
	setValue : function(value) {
		if (value===undefined) {
			value=null;
		}
		var as = this.element.getElementsByTagName('a');
		this.value = null;
		for (var i=0; i < as.length; i++) {
			if (as[i].getAttribute('rel')===value) {
				hui.cls.add(as[i],'hui_segmented_selected');
				this.value=value;
			} else {
				hui.cls.remove(as[i],'hui_segmented_selected');
			}
		};
	},
	getValue : function() {
		return this.value;
	}
}

/* EOF */