(function($) {

	var sapceH = 30
		windowW = $(window).width(),
	   	windowH = $(window).height(),
		navbarH = $("#navbar").outerHeight(),
		mainbarH = windowH - navbarH;

	//mainbar的高度
	if(windowW > 760){
		$("#mainbar").css("height", mainbarH);
	}else{
		$("#mainbar").css("height", mainbarH - sapceH);
	}
	
	

	$(window).on("resize",function(){
		windowW = $(window).width(),
		windowH = $(window).height(),
		navbarH = $("#navbar").outerHeight(),
		mainbarH = windowH - navbarH;
		
		if(windowW > 760){
			$("#mainbar").css("height", mainbarH);
		}else{
			$("#mainbar").css("height", mainbarH - sapceH);
		}

	
	});
	
	//userName bug fix

	$("#userName").on("click",function(e){
	  	
	    $(this).find("li").addClass("open");
	    
	   	$(".dropdown-menu li").on("click",function(){
	   		window.location.assign($(this).find("a").attr("href"));
	   	});
	    
	    return false;
	  
	});

	//错误提示框键盘事件
	$("#noticeModal").on('shown.bs.modal', function () {
	  	$("#noticeConfirm").focus();
	});
	
	
})(jQuery);
