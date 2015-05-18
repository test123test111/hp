;
(function(window, $) {

	bind();

	


	function bind() {
		$(".classified-btn").each(function(index,elem){
			var dataId = $(this).data("id");

			if($(this).data("type") == 4){
				// $(this).parents("classify-info").next().find(".url").show();
				// $(this).parents("classify-info").next().find(".recommend-three").hide();
				$(".url-"+dataId).show();
				$(".url-"+dataId).next().hide();
				$(".url-input-"+dataId).attr("name","OpPositionDetail[" + dataId + "][value]");
				$(this).parents(".classify-info").next().find(".recommend-three").attr("name","");
			}else{
				$(".url-"+dataId).hide();
				$(".url-"+dataId).next().show();
				$(".url-input-"+dataId).attr("name","");
				$(this).parents(".classify-info").next().find(".recommend-three").attr("name","OpPositionDetail[" + dataId + "][value]");
				// $(this).parents("classify-info").next().find(".url").hide();
				// $(this).parents("classify-info").next().find(".recommend-three").show();
			}

			$("#type-" + dataId).val($(this).data("type"));
		});

		$(document).on("click",".search-menu li",function(){
			var dropVal = $(this).find("a").html(),
				$em = $(this).parents("ul").prev().find("em"),
				dataId = $(this).data("id");

			$em.html(dropVal);
			$("#type-" + dataId).val($(this).data("type"));
			$(this).parents("ul").prev().attr("data-type",$(this).data("type"));
			$(this).parents("ul").prev().attr("data-type",$(this).data("id"));

			if($(this).data("type") == 4){
				$(".url-"+dataId).show();
				$(this).parents(".classify-info").next().find(".recommend-three").hide();
				$(".url-input-"+dataId).attr("name","OpPositionDetail[" + dataId + "][value]");
				$(this).parents(".classify-info").next().find(".recommend-three").attr("name","");
			}else{
				$(".url-"+dataId).hide();
				$(this).parents(".classify-info").next().find(".recommend-three").show();
				$(".url-input-"+dataId).attr("name","");
				$(this).parents(".classify-info").next().find(".recommend-three").attr("name","OpPositionDetail[" + dataId + "][value]");
			}
			
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