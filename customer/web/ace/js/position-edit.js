;
(function(window, $) {

	bind();

	function bind() {
		$(".classified-btn").each(function(index,elem){
			var dataId = $(this).data("id");

			$("#type-" + dataId).val($(this).data("type"));
		});

		$(document).on("click",".search-menu li",function(){
			var dropVal = $(this).find("a").html(),
				dataId = $(this).data("id"),
				dataType = $(this).data("type"),
				$em = $(this).parents(".search-menu").prev().find("em"),
				$disabledBtn = $(this).parents("ul").find("button:disabled");

			$em.html(dropVal);
			$("#type-" + dataId).val($(this).data("type"));
			$(".no-url-" + dataId).attr("value","");
			$disabledBtn.parents(".classify-info").siblings(".classify-content").find("input").attr("value","");
			
			$disabledBtn.find("em").html(dropVal);
			$disabledBtn.siblings("input").val(dataType);
			
		}).on("click",".upload-img",function(){
			
			$("#imageNum").val($(this).data("num"));

		}).on("change","#upload_file",function(){
			if($(this).val() == ""){
				$("#linkImg").find("em").html("未选择任何文件");
			}else{
				$("#linkImg").find("em").html($(this).val());
			}
			$("#linkImg").css("color","#4c8fbd");
		});
		
	}

})(window, jQuery);