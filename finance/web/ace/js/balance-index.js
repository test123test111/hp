;
(function(window, $){
	var ignoreDataType = $("#menuContent").data("type"),
		buyerId = "",
		buyerIds = [],
		beginTime = "",
		endTime = "",
		status = "";

		safeCode = $('meta[name=csrf-token]');
	bind();
	$(document).on("click","#searchMenu li a",function(){
		var type = $(this).data("name");
		$("#searchType").attr("name",type);
		$("#searchType").val("");
	});
	function bind(){
		$(document).off("click","#menuContent li").on("click","#menuContent li",function(){
			var val = $(this).children("a").html();
				contentId = $(this).data('content-id');
			$("#ignoreContent em").html(val);
		
		}).on("click","#tabLevelOne li",function(){
			var type = $(this).data("type");
			$("#settlementStatus").val(type);
		}).on("click","#subSettlement",function(event){
			event.preventDefault();
			var parameter = window.location.search;
			window.location.assign("/settlement/buyers" + parameter);
		}).on("click","#menuContent li",function(){

			ignoreDataType = $(this).data("id");
			$("#menuContent").attr("data-type",ignoreDataType);

		}).on("click",".ignore",function(){
			buyerId = $(this).data("id");
		}).on("click","#confirmIgnoreBtn",function(){
			var content = $("#ignoreContent").find("em").html();
				
			if( content == "请选择忽略内容"){
				$("#noticeModal").modal();
 				$("#noticeContent").html("亲，请先请选择忽略内容。");
			}else{
				if(ignoreDataType == 0){
					confirmIgnoreOrder();
				}else{
					confirmIgnoreBuyer();
				}
			}
		});
	}

	function confirmIgnoreOrder() {

	 	$("#loadingBar").css("display","block");
		$.ajax({
			type: 'get',
			url: '/settlement/ignore-batch-orders',
			dataType: 'json',
			data: {
				"buyer_id": buyerId,
				"begin_time" : beginTime,
				"end_time": endTime,
				"status" : status
			},
			success: function(data) {
				$("#loadingBar").hide();
				window.location.reload();
				
			},
			error : function(xhr,textStatus){
		      	$("#loadingBar").hide();
		      	$("#noticeModal").modal();
				$("#noticeContent").html("哦呦报错啦~，错误类型是:["+ xhr.status +"],请检查网络连接或与技术联系！");
				
		    }
		});
	}
	function confirmIgnoreBuyer() {
	 	$("#loadingBar").css("display","block");
		$.ajax({
			type: 'POST',
			url: '/settlement/ignorebuyer',
			dataType: 'json',
			data: {
				"buyer_id" : buyerId,
				"_csrf": safeCode.attr('content')
			},
			success: function(data) {
				$("#loadingBar").hide();
				window.location.reload();
				
			},
			error : function(xhr,textStatus){
		      	$("#loadingBar").hide();
		      	$("#noticeModal").modal();
				$("#noticeContent").html("哦呦报错啦~，错误类型是:["+ xhr.status +"],请检查网络连接或与技术联系！");
				
		    }
		});
	}

})(window, jQuery);
