hui.ui.listen({
	$ready : function() {
		hui.ui.require(['Input'],this._input.bind(this))
	},
	_input : function() {
		this.latest = hui.get('latest');
		this.number = new hui.ui.Input({element:'number',name:'number'});
		this.date = new hui.ui.Input({element:'date'});
		this.value = new hui.ui.Input({element:'value'});
		this.submit = new hui.ui.Input({element:'submit',name:'submit'});
	},
	_pendingNumber : null,
	_busy : false,
	$valueChanged$number : function(value) {
		if (this._busy) {
			this._pendingNumber = value;
			hui.log('pending: '+value);
			return;
		}
		this._busy = true;
		hui.ui.request({
			url : 'services/waterusage/latest.php',
			parameters : {number:value},
			onJSON : function(obj) {
				this._busy = false;
				if (this._pendingNumber) {
					hui.log('pending number exists: '+value);
					this._onNumberChange(this._pendingNumber);
					this._pendingNumber = null;
				} else {
					this._updateLatest(obj);
				}
			}.bind(this)
		})
	},
	_updateLatest : function(obj) {
		if (obj.found) {
			hui.dom.setText(this.latest,obj.value+' ('+new Date(obj.date*1000)+')');
		} else {
			hui.dom.setText(this.latest,'Ikke fundet');
		}
		hui.log(obj)
	},
	$click$submit : function(e) {
		hui.stop(e)
		var params = {
			number : this.number.getValue(),
			date : this.date.getValue(),
			value : this.value.getValue()
		}
	}
})