/**
 * A component for attaching objects
 * @constructor
 */
hui.ui.ObjectInput = function(options) {
	this.options = hui.override({value:null},options);
	this.name = options.name;
	this.element = hui.get(options.element);
    this.text = hui.get.firstByClass(this.element,'hui_objectinput_text');
    this.choose = null;
    this.remove = null;
	hui.ui.extend(this);
	this._attach();
}

hui.ui.ObjectInput.create = function(options) {
	options = options || {};
	options.element = hui.build('span',{'class':'hui_object',html:'TODO'});
	return new hui.ui.ObjectInput(options);
}

hui.ui.ObjectInput.prototype = {
	_attach : function() {
        this.choose = new hui.ui.Button({
            element : hui.get.firstByClass(this.element,'hui_objectinput_choose')
        });
        this.choose.listen({
            $click : this._choose.bind(this)
        });
        this.remove = new hui.ui.Button({
            element : hui.get.firstByClass(this.element,'hui_objectinput_remove')
        });
        this.remove.listen({
            $click : this._remove.bind(this)
        });
	},
    _choose : function() {
		if (!this.finder) {
			this.finder = hui.ui.Finder.create(
				this.options.finder
			);
			this.finder.listen({
				$select : this._found.bind(this)
			})
		}
		this.finder.show();
    },
    _remove : function() {
        this.setValue(null);
    },
    _found : function(object) {
		this.finder.hide();
		this.setValue(object);
		this.fireValueChange();
    },
    _render : function() {
        var txt = this.value ? this.value.title : 'No value';
        hui.dom.setText(this.text,txt);
    },
    setValue : function(value) {
        this.value = value;
        this._render();
    }
}