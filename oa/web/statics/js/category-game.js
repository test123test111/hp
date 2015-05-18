;
(function(window, $) {
	var speed = 0,
		time = null;

	bind();
	
	function bind() {
		$(document).on("click", "#primaryList li", function() {
			var liW = $(this).width(),
				This =  $(this);

			bgFun(This,liW,"category-second.html");

		}).on("click","#secondList li",function(){
			var liW = $(this).width(),
				This =  $(this);

			bgFun(This,liW,"category-final.html");
			
		});

		function bgFun(This,liW,url){
	
			var emWidth = This.find(".touch-style").width();
			time = setInterval(function(){

				speed += 80;
				if(speed >= liW){
					speed = liW;
				}
				
				This.find(".touch-style").css("width",speed);
				emWidth = speed;
				if( emWidth >= liW){
					clearInterval(time);
					window.location.assign(url);
				}

			},10);
		}
	}
})(window, Zepto);