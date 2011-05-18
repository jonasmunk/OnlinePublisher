if (!op) {var op={}};

if (!op.part) {op.part={}};

op.part.utils = {
	previewTimer : null,
	
	updatePreview : function(options) {
		options.node = hui.get(options.node);
		var f = function() {
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
				},
				onFailure:function(e) {
					hui.log(e);
				},
				onException:function(e) {
					hui.log(e);
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