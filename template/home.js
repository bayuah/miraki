		function fc(){
			f=document.getElementById("[%home_js_focus]");
			f.focus();
		};
		if(window.attachEvent) {
			window.attachEvent('onload', fc);
		} else {
			if(window.onload) {
				var curronload = window.onload;
				var newonload = function() {
					curronload();
					fc();
				};
				window.onload = newonload;
			} else {
				window.onload = fc;
			}
		}