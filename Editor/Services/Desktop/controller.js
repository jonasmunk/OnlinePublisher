hui.ui.listen({
	$ready : function() {
		hui.animate({node:'intro',css:{color: '#aaa','font-size':'60px'},duration:2000,ease:hui.ease.fastSlow,onComplete:function() {
			hui.animate({node:'intro',css:{'color':'#fff'},duration:2000,ease:hui.ease.fastSlow})
		}})
		hui.animate({node:'intro',css:{'letter-spacing':'160px'},duration:16000,ease:hui.ease.fastSlow})
	}
})