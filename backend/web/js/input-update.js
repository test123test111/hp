(function($) {
	var orderId = 0,
		orderNum = 0,
		boxId = $("#boxId").html(),
		orderCount = $("#orderCount").html(),
		safeCode = $('meta[name=csrf-token]');

		//js改变元素的高度
		spaceH = 100,
	   	windowH = $(window).height(),
		navbarH = $("#navbar").outerHeight(),
		pageHeadH = $("#pageHead").outerHeight(),
		infoTitleH = $("#infoTitle").outerHeight(),
		btnH = $(".btn-height").outerHeight(),
		matterH = windowH - navbarH  - pageHeadH - spaceH,
		orderContentH = matterH - infoTitleH - btnH;

	//matter的高度
	$("#matter").css("height", matterH);

	//orderMain的高度
	$("#orderContent").css("height",orderContentH);
	

	$(window).on("resize",function(){

		windowH = $(window).height();
		navbarH = $("#navbar").outerHeight();
		pageHeadH = $("#pageHead").outerHeight();
		infoTitleH = $("#infoTitle").outerHeight();
		btnH = $(".btn-height").outerHeight();
		orderListH = $("#orderList").outerHeight();
		matterH = windowH - navbarH  - pageHeadH - spaceH,
		orderContentH = matterH - infoTitleH - btnH;
		
		//matter的高度
		$("#matter").css("height", matterH);
		//orderMain的高度
		$("#orderContent").css("height",orderContentH);
		
	});
	
	
	//打开页面获取焦点
	$("#number").focus();

	//错误提示框点击确定后获取焦点
	$('#noticeModal ,#printLabelModal, #sealBoxModal ,#delOrderModal').on('hidden.bs.modal', function () {
  		$("#number").val("").focus();
	});

	//获取订单
	$("#inputBtn").on("click",function(event){
		event.preventDefault();
		orderNum = $("#number").val();

		getOrderInfo();
	});
	

	//确定删除订单的键盘事件
	$("#orderContent ").delegate(".fa-trash-o","click",function(){
		orderId = $(this).parent().data("id");
		$("#delOrderModal").on('shown.bs.modal',function(){
			$("#confirmDel").focus();
		});
	});

	//确定删除订单 点击事件
	$("#confirmDel").on("click",function(event){
		event.preventDefault();	
		delOrder();
	});
	


	//确定打印物流箱标签键盘事件
	$("#printLabel").on("click",function(){
		$("#printLabelModal").on('shown.bs.modal', function () {
		  	$('#confirmPrint').focus();  //确定按钮获取焦点
		});
	
	});

	//确定打印物流箱标签
	$("#confirmPrint").on("click",function(event){
		event.preventDefault();
		printLabel();
	});

	//封箱入库
	$("#storage").on("click",function(){
		var orderLen = $("#orderContent li").length;
		if(orderLen == 0){
			
			$("#noticeModal").modal();
			$("#noticeContent").html("请添加订单!");

		}else{
			$("#sealBoxModal").modal();
			$("#sealBoxModal").on('shown.bs.modal', function () {
		  		$('#confirmSealBox').focus();  
			});
			
		}
		
	});

	$("#confirmSealBox").click(function(){
		$("#sealBox").trigger("submit");
	})

	
	//扫描订单和封箱入库的键盘事件	
	$("#orderForm").keypress(function(event){
		if( event.which == 13){
			event.preventDefault();
			orderNum = $("#number").val();  
			if(orderNum){ //订单号不为空，页面添加订单号
				getOrderInfo(); //获取订单
			}else{ //订单号为空，分为2种情况
				var orderLen = $("#orderContent li").length;
				if(orderLen == 0){  //没与扫描过订单
					$("#noticeModal").modal();
					$("#noticeContent").html("请添加订单!");

				}else{ //扫描过订单，弹出提示框，确定以后封箱入库
					$("#sealBoxModal").modal();
					$('#sealBoxModal').on('shown.bs.modal', function () {
					  	$('#confirmSealBox').focus();
					});
					
					$("#confirmSealBox").keypress(function(event){
						if(event.which == 13){
							$("#sealBox").trigger("submit");  //封箱入库
						}
					});
				}
			}
		}
	});




	//获取订单信息
	function getOrderInfo() {
		$("#loadingBar").css("display","block");
		$.ajax({
			type: 'POST',
			url: '/input/addorder',
			dataType: 'json',
			data: {
				"box_id" : boxId,
				"order_id" : orderNum,
				"_csrf": safeCode.attr('content')
			},
			success: function(data) {
				$("#loadingBar").hide();
				if( !data.errno ){
					
					orderCount ++;
					$("#orderCount").html(orderCount);
					$("#orderContent").prepend(data.rst);
					$("#number").val("").focus();

				}else{
					if(data.errno == 10004){
						$("#number").val("").focus();
					}else{
						
						$("#noticeModal").modal();
						$("#noticeContent").html(data.err);
						
					}
				}
				
			},
			error : function(xhr,textStatus){
		      	$("#loadingBar").hide();
		      	$("#noticeModal").modal();
				$("#noticeContent").html("哦呦报错啦~，错误类型是:["+ xhr.status +"],请检查网络连接或与技术联系！");
		    }
		});
	 }

	 //删除订单
	 function delOrder() {

	 	$("#loadingBar").css("display","block");
		$.ajax({
			type: 'POST',
			url: '/input/deleteorder',
			dataType: 'json',
			data: {
				"box_id" : boxId,
				"order_id" : orderId,
				"_csrf": safeCode.attr('content')
			},
			success: function(data) {
				$("#loadingBar").hide();
				if( !data.errno ){	
					
					orderCount --;
					$("#orderCount").html(orderCount);

					$('#'+orderId).remove();

					$("#number").val("").focus();

				}else{
					if(data.errno == 10004){
						
					}else{
						$("#noticeModal").modal();
						$("#noticeContent").html(data.err);
					}
				}
				
			},
			error : function(xhr,textStatus){
		      	$("#loadingBar").hide();
		      	$("#noticeModal").modal();
				$("#noticeContent").html("哦呦报错啦~，错误类型是:["+ xhr.status +"],请检查网络连接或与技术联系！");
				
		    }
		});
	 }

	 //打印标签
	 function printLabel() {
		$("#loadingBar").css("display","block");
		$.ajax({
			type: 'POST',
			url: '/input/print',
			dataType: 'json',
			data: {
				"box_id" : boxId,
				"_csrf": safeCode.attr('content')
			},
			success: function(data) {
				$("#loadingBar").hide();
				if( !data.errno ){

				}else if(data.errno == 10004){

				}else{
					$("#noticeModal").modal();
					$("#noticeContent").html(data.err);
				}
			},
			error : function(xhr,textStatus){
		      	$("#loadingBar").hide();
		      	$("#noticeModal").modal();
				$("#noticeContent").html("哦呦报错啦~，错误类型是:["+ xhr.status +"],请检查网络连接或与技术联系！");
				
		    }
		});
	 }

	

})(jQuery);
