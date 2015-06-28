/**
 * Help stuff
 * @namespace
 */
hui.ui.help = {
	bubble : function(options) {
		var bubble = hui.build('div',{
			style : 'display: none; border: 1px solid #aaa; box-shadow: 0px 2px 8px rgba(0, 0, 0, .2),inset 0px 0px 50px rgba(255, 255, 255, 1),inset 0px 0px 50px rgba(255, 255, 255, 1),inset 0px 0px 20px rgba(255, 255, 255, 1); position: absolute;',
			parent : document.body
		});
		var target = hui.get(options.target);
		var size = Math.max(target.clientWidth,target.clientHeight)+20;
		hui.style.set(bubble,{
			width : size+'px',
			height : size+'px',
			display : 'block',
			visibility : 'hidden',
			borderRadius: size+'px'
		});
		hui.position.place({
			source : {element:bubble,vertical:.5,horizontal:.5},
			target : {element:target,vertical:.5,horizontal:.5}
		});
		hui.effect.bounceIn({element:bubble})
		hui.listen(bubble,'click',function() {
			hui.dom.remove(bubble);
		})
	}
}