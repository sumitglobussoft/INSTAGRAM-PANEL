/**
 * jquery.materialRipple.js v1.0.0
 * http://www.cerocreativo.cl
 *
 * Click effect inspired by Google's Material Design
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright (c) 2015 Paulo Mendez,
 * http://www.cerocreativo.cl
 * http://www.rockandthemes.com
 *
 */

;(function ($) {
	$.fn.materialRipple = function(options) {
		var defaults = $.extend({
			// These are the defaults.
			duration: 		900,			// Duration of the ripple in miliseconds
			type: 			"btn",
			style: 			"default",		// default = dynamic for flat styled buttons | light | dark
		},options);

		var settings = $.extend( {}, defaults, options );

		return this.each(function() {
			var obj 				= $(this),
				rippleContainer 	= "<div class='materialRipple-md-ripple-container'></div>";

			if (settings.style == "light") {
				obj.addClass("materialRipple-light");
			}
			if (settings.style == "dark") {
				obj.addClass("materialRipple-dark");
			}

			if (!$(".materialRipple-md-ripple-container",obj).length) {
				obj.addClass("materialRipple-btn").append(rippleContainer);
				var duplicated = true;
			}
			
			var rippleContainer 	= $(".materialRipple-md-ripple-container",obj);


			// Mouse Events
			// --------------------------------------------------
			var newRIppleId;
			obj.on("mousedown",function(e){
				// id = Date.now();
				var id 			= Math.round(new Date().getTime() + (Math.random() * 1000)),
					offset		= obj.offset(),
					cx 			= e.pageX - offset.left,
					cy 			= e.pageY - offset.top,
					pw 			= obj.outerWidth(),
					ph 			= obj.outerHeight(),
					gD 			= function(c1,c2) {
									var h = c1 * c1 + c2 * c2,
										h = Math.sqrt(h);
									return h;
								},
					byNum 		= function(a,b){
									return b - a;
								},
					dw1			= cx,
					dw2 		= (pw - dw1),
					dh1			= cy,
					dh2			= (ph - dh1),
					dp1			= gD(dw1,dh1),
					dp2			= gD(dw2,dh1),
					dp3			= gD(dw1,dh2),
					dp4			= gD(dw2,dh2),
					dt 			= [dp1,dp2,dp3,dp4],
					dt 			= dt.sort(byNum),
					radius 		= dt[0],
					zoom		= radius * 2;


				if (settings.type == "icon"){
					var w2 		= Math.round(Math.abs(w -100))+180;
						zoom 	= pw * w2 / 100;
				}

				newRippleId	= "materialRipple-"+id;

				// Creates a New Ripple
				rippleContainer.prepend("<div id='"+newRippleId+"' class='md-ripple md-ripple-placed'></div>");

				var newRipple = $("#"+newRippleId,rippleContainer),
					styles 	= {
						"top": +cy+"px",
						"left":+cx+"px",
						"width":+zoom+"px",
						"height":+zoom+"px",
					};

				newRipple.css(styles);

				var timer = setTimeout(function(){
						newRipple.addClass("md-ripple-scaled");
					}, 0);

			});


			obj.on("mouseup",function(e){
				var style = {
					"opacity":0,
				};
				$("#"+newRippleId).css(style).one("webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend",function(){
					$(this).remove();
				});
			});


			obj.on("mouseout",function(e){
				var style = {
					"opacity":0,
				};
				var newRippleId = newRippleId || false;

				if ($(".md-ripple",rippleContainer).length){
					$(".md-ripple",rippleContainer).css(style).one("webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend",function(){
						rippleContainer.empty();
					});	
				}
			});


		});
	};
}(jQuery));