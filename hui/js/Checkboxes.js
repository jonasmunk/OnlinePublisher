/////////////////////////// Checkboxes ////////////////////////////////

/**
 * Multiple checkboxes
 * @constructor
 */
hui.ui.Checkboxes = function(options) {
  this.options = options;
  this.element = hui.get(options.element);
  this.name = options.name;
  this.items = options.items || [];
  this.subItems = [];
  this.values = options.values || options.value || []; // values is deprecated
  hui.ui.extend(this);
  this._addBehavior();
  this._updateUI();
  if (options.url) {
    new hui.ui.Source({url:options.url,delegate:this});
  }
};

hui.ui.Checkboxes.create = function(o) {
  o.element = hui.build('div',{'class':o.vertical ? 'hui_checkboxes hui_checkboxes_vertical' : 'hui_checkboxes'});
  if (o.items) {
    hui.each(o.items,function(item) {
      var node = hui.build('a',{'class':'hui_checkbox',href:'javascript:void(0);',html:'<span><span></span></span>'+item.title});
      hui.ui.addFocusClass({element:node,'class':'hui_checkbox_focused'});
      o.element.appendChild(node);
    });
  }
  return new hui.ui.Checkboxes(o);
};

hui.ui.Checkboxes.prototype = {
  _addBehavior : function() {
    var checks = hui.get.byClass(this.element,'hui_checkbox');
    hui.each(checks,function(check,i) {
      hui.ui.addFocusClass({element:check,'class':'hui_checkbox_focused'});
      hui.listen(check,'click',function(e) {
        hui.stop(e);
        this.flipValue(this.items[i].value);
      }.bind(this));
    }.bind(this));
  },
  getValue : function() {
    return this.values;
  },
  _checkValues : function() {
    var newValues = [];
    for (var i=0; i < this.values.length; i++) {
      var value = this.values[i],
        found = false,
        j;
      for (j=0; j < this.items.length; j++) {
        found = found || this.items[j].value===value;
      }
      for (j=0; j < this.subItems.length; j++) {
        found = found || this.subItems[j]._hasValue(value);
      }
      if (found) {
        newValues.push(value);
      }
    }
    this.values=newValues;
  },
  setValue : function(values) {
    this.values = values;
    this._checkValues();
    this._updateUI();
  },
  flipValue : function(value) {
    hui.array.flip(this.values,value);
    this._checkValues();
    this._updateUI();
    this.fire('valueChanged',this.values);
    hui.ui.callAncestors(this,'childValueChanged',this.values);
  },
  _updateUI : function() {
    var i,item,found;
    for (i=0; i < this.subItems.length; i++) {
      this.subItems[i]._updateUI();
    }
    var nodes = hui.get.byClass(this.element,'hui_checkbox');
    for (i=0; i < this.items.length; i++) {
      item = this.items[i];
      found = hui.array.contains(this.values,item.value);
      hui.cls.set(nodes[i],'hui_checkbox_selected',found);
    }
  },
  refresh : function() {
    for (var i=0; i < this.subItems.length; i++) {
      this.subItems[i].refresh();
    }
  },
  reset : function() {
    this.setValues([]);
  },
  getLabel : function() {
    return this.options.label;
  },
  /** @private @deprecated */
  setValues : function(values) {
    this.setValue(values);
  },
  /** @private @deprecated */
  getValues : function() {
    return this.values;
  },
  /** @private */
  registerItem : function(item) {
    // If it is a number, treat it as such
    if (parseInt(item.value)==item.value) {
      item.value = parseInt(item.value);
    }
    this.items.push(item);
  },
  /** @private */
  registerItems : function(items) {
    items.parent = this;
    this.subItems.push(items);
  },
  /** @private */
  $itemsLoaded : function(items) {
    hui.each(items,function(item) {
      var node = hui.build('a',{'class':'hui_checkbox',href:'javascript:void(0);',html:'<span><span></span></span>'+hui.string.escape(item.title)});
      hui.listen(node,'click',function(e) {
        hui.stop(e);
        this.flipValue(item.value);
      }.bind(this))
      hui.ui.addFocusClass({element:node,'class':'hui_checkbox_focused'});
      this.element.appendChild(node);
      this.items.push(item);
    }.bind(this));
    this._checkValues();
    this._updateUI();
  }
}

/////////////////////// Checkbox items ///////////////////

/**
 * Check box items
 * @constructor
 */
hui.ui.Checkboxes.Items = function(options) {
  this.element = hui.get(options.element);
  this.name = options.name;
  this.parent = null;
  this.options = options;
  this.checkboxes = [];
  hui.ui.extend(this);
  if (this.options.source) {
    this.options.source.listen(this);
  }
};

hui.ui.Checkboxes.Items.prototype = {
  refresh : function() {
    if (this.options.source) {
      this.options.source.refresh();
    }
  },
  /** @private */
  $itemsLoaded : function(items) {
    this.checkboxes = [];
    this.element.innerHTML='';
    var self = this;
    hui.each(items,function(item) {
      var node = hui.build('a',{'class':'hui_checkbox',href:'javascript://',html:'<span><span></span></span>'+item.title});
      hui.listen(node,'click',function(e) {
        hui.stop(e);
        node.focus();
        self._onItemClick(item)
      });
      hui.ui.addFocusClass({element:node,'class':'hui_checkbox_focused'});
      self.element.appendChild(node);
      self.checkboxes.push({title:item.title,element:node,value:item.value});
    });
    this.parent._checkValues();
    this._updateUI();
  },
  _onItemClick : function(item) {
    this.parent.flipValue(item.value);
  },
  _updateUI : function() {
    try {
    for (var i=0; i < this.checkboxes.length; i++) {
      var item = this.checkboxes[i];
      var index = hui.array.indexOf(this.parent.values,item.value);
      hui.cls.set(item.element,'hui_checkbox_selected',index!=-1);
    }
    } catch (e) {
      alert(typeof(this.parent.values));
      alert(e);
    }
  },
  _hasValue : function(value) {
    for (var i=0; i < this.checkboxes.length; i++) {
      if (this.checkboxes[i].value==value) {
        return true;
      }
    }
    return false;
  }
};