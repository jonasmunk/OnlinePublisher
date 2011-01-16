/** A data source
 * @constructor
 */
In2iGui.Source = function(options) {
	this.options = n2i.override({url:null,dwr:null,parameters:[],lazy:false},options);
	this.name = options.name;
	this.data = null;
	this.parameters = this.options.parameters;
	In2iGui.extend(this);
	if (options.delegate) {
		this.listen(options.delegate);
	}
	this.busy=false;
	In2iGui.onDomReady(this.init.bind(this));
};

In2iGui.Source.prototype = {
	/** @private */
	init : function() {
		var self = this;
		n2i.each(this.parameters,function(parm) {
			var val = In2iGui.bind(parm.value,function(value) {
				self.changeParameter(parm.key,value);
			});
			parm.value = self.convertValue(val);
		});
		if (!this.options.lazy) {
			this.refresh();
		}
	},
	/** @private */
	convertValue : function(value) {		
		if (value && value.getTime) {
			return value.getTime();
		}
		return value;
	},
	/** Refreshes the data source */
	refresh : function() {
		if (this.delegates.length==0) {
			return;
		}
		for (var i=0; i < this.delegates.length; i++) {
			var d = this.delegates[i];
			if (d['$sourceShouldRefresh'] && d['$sourceShouldRefresh']()==false) {
				return;
			}
		};
		if (this.busy) {
			this.pendingRefresh = true;
			return;
		}
		this.pendingRefresh = false;
		var self = this;
		if (this.options.url) {
			var prms = {};
			for (var i=0; i < this.parameters.length; i++) {
				var p = this.parameters[i];
				prms[p.key] = p.value;
			};
			this.busy=true;
			In2iGui.callDelegates(this,'sourceIsBusy');
			n2i.request({
				method:'post',
				url:this.options.url,
				parameters:prms,
				onSuccess : function(t) {self.parse(t)},
				onException : function(t,e) {n2i.log(e)},
				onFailure : function(t,e) {
					In2iGui.callDelegates(self,'sourceFailed');
				}
			});
		} else if (this.options.dwr) {
			var pair = this.options.dwr.split('.');
			var facade = eval(pair[0]);
			var method = pair[1];
			var args = facade[method].argumentNames();
			for (var i=0; i < args.length; i++) {
				if (this.parameters[i]) {
					args[i]=this.parameters[i].value===undefined ? null : this.parameters[i].value;
				}
			};
			args[args.length-1]=function(r) {self.parseDWR(r)};
			this.busy=true;
			In2iGui.callDelegates(this,'sourceIsBusy');
			facade[method].apply(facade,args);
		}
	},
	/** @private */
	end : function() {
		In2iGui.callDelegates(this,'sourceIsNotBusy');
		this.busy=false;
		if (this.pendingRefresh) {
			this.refresh();
		}
	},
	/** @private */
	parse : function(t) {
		if (t.responseXML && t.responseXML.documentElement && t.responseXML.documentElement.nodeName!='parsererror') {
			this.parseXML(t.responseXML);
		} else {
			var str = t.responseText.replace(/^\s+|\s+$/g, '');
			if (str.length>0) {
				var json = n2i.fromJSON(t.responseText);
			} else {
				var json = null;
			}
			this.fire('objectsLoaded',json);
		}
		this.end();
	},
	/** @private */
	parseXML : function(doc) {
		if (doc.documentElement.tagName=='items') {
			this.data = In2iGui.parseItems(doc);
			this.fire('itemsLoaded',this.data);
		} else if (doc.documentElement.tagName=='list') {
			this.fire('listLoaded',doc);
		} else if (doc.documentElement.tagName=='articles') {
			this.fire('articlesLoaded',doc);
		}
	},
	/** @private */
	parseDWR : function(data) {
		this.data = data;
		this.fire('objectsLoaded',data);
		this.end();
	},
	addParameter : function(parm) {
		//n2i.log(parm.value);
		this.parameters.push(parm);
	},
	changeParameter : function(key,value) {
		value = this.convertValue(value);
		for (var i=0; i < this.parameters.length; i++) {
			var p = this.parameters[i]
			if (p.key==key) {
				p.value=value;
			}
		};
		window.clearTimeout(this.paramDelay);
		this.paramDelay = window.setTimeout(function() {
			this.refresh();
		}.bind(this),100)
	}
}

/* EOF */