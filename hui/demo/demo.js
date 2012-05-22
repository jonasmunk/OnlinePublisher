var demo = {
	_go : function(method,time) {
		window.setTimeout(this[method].bind(this),time)
	},
	start : function() {
		hui.ui.showMessage({text:'Ready...',busy:true});
		this._go('start2',1000)
	},
	start2 : function() {
		hui.ui.showMessage({text:'...set...',busy:true});
		this._go('start3',2000)
	},
	start3 : function() {
		hui.ui.showMessage({text:'GO!',icon:'common/success'});
		this._go('showWindow',2000)
	},
	showWindow : function() {
		var win = hui.ui.Window.create({title:'This is a window',width:500});
		win.add(hui.build('div',{html:'<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>'}))
		win.show();
		hui.ui.hideMessage();
		hui.ui.confirmOverlay({widget:win,text:'Are you ready for the next?',onOk : function() {
			win.hide();
			demo.animation();
		}})
	},
	animation : function() {
		hui.ui.showMessage({text:'I can animate...'});
		var node = hui.build('div',{text:'Sem Cras Amet Purus Euismod',style:'top:0px;left:0px;position:absolute; font-family: Arial; border: 1px solid #fff; font-size: 0px; color: #333; background:#fff;padding: 2px 10px;',parent:document.body})
		hui.animate({node:node,css:{left:'40px',top:'50px',fontSize:'30px'},duration:2000,ease:hui.ease.fastSlow,onComplete:function() {
			hui.animate({node:node,css:{left:'20px',top:'50px',fontSize:'50px',color:'#468',backgroundColor:'#eee'},duration:500,ease:hui.ease.slowFastSlow,onComplete:function() {
			
				hui.animate({node:node,css:{left:'20px',top:'30px',borderWidth:'4px',borderColor:'#aaa',fontSize:'40px',color:'#864',backgroundColor:'#eee'},duration:1500,ease:hui.ease.elastic,onComplete:function() {
					hui.ui.showMessage({text:'I can animate anything'});
				}})
			}})
		}})
	}
}
hui.ui.onReady(demo.animation.bind(demo));
