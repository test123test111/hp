(function($) {

	var safeCode = $('meta[name=csrf-token]'),
		stockIndexUrl = "/stock/valid",
		inputIndexUrl = "/input/valid",
		outputIndexUrl = "/output/valid";

	//进入页面输入框获取焦点
	$("#number").focus();

	//入库首页
	$("#inputIndexForm").on("submit",function(event){
		event.preventDefault();
		getBoxInfo(inputIndexUrl);
	});

	//入库首页 点击创建箱子键盘事件
	$("#addBox").on("click",function(){
		$("#creatBoxModal").on("shown.bs.modal",function(){
			$("#confirmCreat").focus();
		});
		
	});

	//在库首页
	$("#stockIndexForm").on("submit",function(event){
		event.preventDefault();
		getBoxInfo(stockIndexUrl);
	});
	//出库首页
	$("#outputIndexForm").on("submit",function(event){
		event.preventDefault();
		getBoxInfo(outputIndexUrl);
	});

	//错误提示框点击确定后 输入框获取焦点
	$('#noticeModal').on('hidden.bs.modal', function () {
  		$("#number").val("").focus();
	})

	//箱子请求数据
	function getBoxInfo(url) {
		var boxId = $("#number").val(); 
		$("#loadingBar").css("display","block");

		$.ajax({
			type: 'POST',
			url: url,
			dataType: 'json',
			data: {
				"box_id" : boxId,
				"_csrf"  : safeCode.attr('content')

			},
			success: function(data) {
				$("#loadingBar").hide();
				if( !data.errno ){
					window.location.assign(data.rst);

				}else{
					
					$("#noticeModal").modal();
					$("#noticeContent").html(data.err);
					$("#number").val("").focus();
				}
			},
			error : function(xhr,textStatus){
				
		      	$("#loadingBar").hide();
		      	$("#noticeModal").modal();
				$("#noticeContent").html("哦呦报错啦~，错误类型是:["+ xhr.status +"],请检查网络连接或与技术联系！");		    }
		});
	}



})(jQuery);
