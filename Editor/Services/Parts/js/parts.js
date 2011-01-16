if (!op) {var op={}};

if (!op.part) {op.part={}};

op.part.utils = {
	previewTimer : null,
	
	updatePreview : function(options) {
		options.node = n2i.get(options.node);
		var f = function() {
			var url = controller.context+'Editor/Services/Parts/Preview.php?type='+options.type;
			var params = n2i.form.getValues(options.form);
			ui.request({
				url : url,
				parameters : params,
				onSuccess : function(t) {
					options.node.innerHTML=t.responseText;
					if (options.onComplete) {
						options.onComplete();
					}
				},
				onFailure:function(e) {
					n2i.log(e);
				},
				onException:function(e) {
					n2i.log(e);
					throw e;
				}
			});
		}
		window.clearTimeout(this.previewTimer);
		if (options.delay) {
			this.previewTimer = window.setTimeout(f,options.delay);
		} else {
			f();
		}
	}
}