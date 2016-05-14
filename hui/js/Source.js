/** A data source
 * @constructor
 */
hui.ui.Source = function(options) {
	this.options = hui.override({url:null,parameters:[],lazy:false},options);
	this.name = options.name;
	this.data = null;
	this.parameters = [];
    // Clone parameters so they can be reused
    for (var i = 0; i < this.options.parameters.length; i++) {
        var p = this.options.parameters[i]
        this.parameters.push({key:p.key,value:p.value,separate:p.separate});
    }
	hui.ui.extend(this);
	if (options.delegate) {
		this.listen(options.delegate);
	}
	this.initial = true;
	this.busy = false;
	hui.ui.onReady(this._init.bind(this));
};

hui.ui.Source.prototype = {
	_init : function() {
		var self = this;
		hui.each(this.parameters,function(parm) {
			var val = hui.ui.bind(parm.value,function(value) {
				self.changeParameter(parm.key,value);
			});
			parm.value = self._convertValue(val);
		});
		if (!this.options.lazy) {
			this.refresh();
		}
	},
	_convertValue : function(value) {
		if (value && value.getTime) {
			return value.getTime();
		}
		return value;
	},
	refreshFirst : function() {
		if (this.initial) {
			this.refresh();
		}
	},
	/** Will refresh, but wait a little to let others contribute */
	refreshLater : function() {
		window.clearTimeout(this.paramDelay);
		this.paramDelay = window.setTimeout(function() {
			this.refresh();
		}.bind(this),100)
	},

	/** Refreshes the data source */
	refresh : function() {
		if (this.options.delay<1) {
			this._refresh();
		} else {
			window.clearTimeout(this._refreshDelay);
			this._refreshDelay = window.setTimeout(this._refresh.bind(this),this.options.delay);
		}
	},
	_refresh : function() {
		if (this.delegates.length==0) {
			return;
		}
		var shouldRefresh = this.delegates.length==0;
		for (var i=0; i < this.delegates.length; i++) {
			var d = this.delegates[i];
			if (d['$sourceShouldRefresh']) {
				shouldRefresh = shouldRefresh || d['$sourceShouldRefresh']();
			} else {
				shouldRefresh = true;
			}
		};
		if (!shouldRefresh) {return}
		if (this.busy) {
			this.pendingRefresh = true;
			// It might be better to cue rather than abort
			//if (this.transport) {
			//	this.transport.abort();
			//}
			return;
		}
		this.initial = false;
		this.pendingRefresh = false;
		var self = this;
		if (this.options.url) {
			var prms = [];
			for (var j=0; j < this.parameters.length; j++) {
				var p = this.parameters[j];
                if (hui.isArray(p.value) && p.separate) {
                    for (var k = 0; k < p.value.length; k++) {
        				prms.push({
                            name : p.key,
                            value : p.value[k]
                        });
                    }
                } else {
    				prms.push({name : p.key, value : p.value});
                }
			};
			this.busy = true;
			hui.ui.callDelegates(this,'sourceIsBusy');
			this.transport = hui.request({
				method : 'post',
				url : this.options.url,
				parameters : prms,
				$success : this.parse.bind(this),
				$exception : function(e,t) {
					hui.log('Exception while loading source...')
					hui.log(e)
					self._end();
				},
				$forbidden : function() {
					hui.ui.handleForbidden(self);
					hui.ui.callDelegates(self,'sourceFailed');
					self._end();
				},
				$failure : function(t) {
					hui.log('sourceFailed');
					hui.ui.callDelegates(self,'sourceFailed');
					self._end();
				}
			});
		}
	},
	_end : function() {
		this.busy = false;
		hui.ui.callDelegates(this,'sourceIsNotBusy');
		if (this.pendingRefresh) {
			this.refresh();
		}
	},
	/** @private */
	parse : function(t) {
		if (hui.request.isXMLResponse(t)) {
			this.parseXML(t.responseXML);
		} else {
			var str = t.responseText.replace(/^\s+|\s+$/g, ''),
				json = null;
			if (str.length>0) {
				json = hui.string.fromJSON(t.responseText);
			}
			this.fire('objectsLoaded',json);
		}
		this._end();
	},
	/** @private */
	parseXML : function(doc) {
		if (doc.documentElement.tagName=='items') {
			this.data = hui.ui.parseItems(doc);
			this.fire('itemsLoaded',this.data);
		} else if (doc.documentElement.tagName=='list') {
			this.fire('listLoaded',doc);
		} else if (doc.documentElement.tagName=='articles') {
			this.fire('articlesLoaded',doc);
		}
	},
	addParameter : function(parm) {
		this.parameters.push(parm);
		var val = hui.ui.bind(parm.value,function(value) {
			this.changeParameter(parm.key,value);
		}.bind(this));
		parm.value = this._convertValue(val);
	},
	setParameter : function(key,value) {
		value = this._convertValue(value);
		for (var i=0; i < this.parameters.length; i++) {
			var p = this.parameters[i]
			if (p.key==key) {
				p.value=value;
				return;
			}
		}
		this.parameters.push({key:key,value:value});
	},
	changeParameter : function(key,value) {
		value = this._convertValue(value);
		for (var i=0; i < this.parameters.length; i++) {
			var p = this.parameters[i]
			if (p.key==key) {
				p.value=value;
			}
		};
		this.refreshLater();
	}
}
/* EOF */