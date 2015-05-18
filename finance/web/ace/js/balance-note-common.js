;
(function(window,$){
	var safeCode = $('meta[name=csrf-token]'),
		orderId = "",
		noteContent = "";

	bind();

	function bind(){
		$(document).off("click",".note-single").on("click",".note-single",function(){
			orderId = $(this).attr("data-id");
			// console.log(orderId);
			note();
		}).off("click","#release").on("click","#release",function(){
			noteContent = $("#noteContent").val();
			if(noteContent.trim() == ""){
				alert("请输入要备注的内容!!");
				return false;
			}
			releaseNote();

		});
	}

	function note() {

	 	$("#loadingBar").css("display","block");
		$.ajax({
			type: 'POST',
			url: '/settlement/notelist',
			dataType: 'json',
			data: {
				"order_id" : orderId,
				"_csrf": safeCode.attr('content')
			},
			success: function(data) {
				$("#loadingBar").hide();
				$("#noteModal").html(data.rst);
				$("#noteModal").modal();
				
				
			},
			error : function(xhr,textStatus){
		      	$("#loadingBar").hide();
		      	$("#noteModal").modal("hide");
		      	$("#noticeModal").modal();
				$("#noticeContent").html("哦呦报错啦~，错误类型是:["+ xhr.status +"],请检查网络连接或与技术联系！");
				
		    }
		});
	}

	function releaseNote() {
	 	$("#loadingBar").css("display","block");
		$.ajax({
			type: 'POST',
			url: '/settlement/createnote',
			dataType: 'json',
			data: {
				"order_id" : orderId,
				"content" : noteContent,
				"_csrf": safeCode.attr('content')
			},
			success: function(data) {
				$("#loadingBar").hide();
				$("#noteModalBody").prepend(data.rst);
				
			},
			error : function(xhr,textStatus){
		      	$("#loadingBar").hide();
		      	$("#noteModal").modal("hide");
		      	$("#noticeModal").modal();
				$("#noticeContent").html("哦呦报错啦~，错误类型是:["+ xhr.status +"],请检查网络连接或与技术联系！");
				
		    }
		});
	}

})(window,jQuery);