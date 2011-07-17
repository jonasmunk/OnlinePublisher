if (!op) {var op={}};

if (!op.part) {op.part={}};

op.part.utils = {
	previewTimer : null,
	busy : false,
	pendingUpdateOptions : null,
	
	updatePreview : function(options) {
		options.node = hui.get(options.node);
		window.clearTimeout(this.previewTimer);
		if (options.delay) {
			this.previewTimer = window.setTimeout(function() {
				this._send(options)
			}.bind(this),options.delay);
		} else {
			this._send(options);
		}
	},
	_send : function(options) {
		if (this.busy) {
			this.pendingUpdateOptions = options;
			hui.log('Im busy, saving options for later')
			return;
		}
		this.busy = true;
		this.showSpinner(options.node);
		var url = controller.context+'Editor/Services/Parts/Preview.php?type='+options.type;
		var params = hui.form.getValues(options.form);
		hui.ui.request({
			url : url,
			parameters : params,
			onSuccess : function(t) {
				options.node.innerHTML=t.responseText;
				if (options.onComplete) {
					options.onComplete();
				}
				this.busy = false;
				this.hideSpinner(options.node);
				if (this.pendingUpdateOptions) {
					hui.log('Found pending options, sending again...')
					this._send(this.pendingUpdateOptions);
					this.pendingUpdateOptions = null;
				}
			}.bind(this),
			onFailure:function(e) {
				hui.log(e);
			},
			onException:function(e) {
				hui.log(e);
				throw e;
			}
		});
	},
	showSpinner : function(node) {
		if (!this.spinner) {
			this.spinner = hui.build('div',{
				parent : document.body,
				style : 'width: 24px; height: 24px; position: absolute; background: #fff url(\''+hui.ui.context+'hui/gfx/progress/spinner_white_24.gif\');'
			});
		}
		hui.setOpacity(node,.5);
		hui.place({source:{element:this.spinner},target:{element:node},left:1,top:1})
		this.spinner.style.display='';
	},
	hideSpinner : function(node) {
		hui.setOpacity(node,1);
		this.spinner.style.display='none';
	}
}