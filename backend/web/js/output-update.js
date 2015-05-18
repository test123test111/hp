(function($) {
	
	var expressState = 0,
		orderState =  0,
		len = 0,
		expressNo = 0,
		expressType = 0,
		numberValue = "",
		orderIds = [],
		rmOrderId = 0,
		boxId = $("#boxId").html(),
		safeCode = $('meta[name=csrf-token]');

		//js改变元素的高度
		spaceH = 100,
	   	windowH = $(window).height(),
		navbarH = $("#navbar").outerHeight(),
		pageHeadH = $("#pageHead").outerHeight(),
		infoTitleH = $("#infoTitle").outerHeight(),
		btnH = $(".btn-height").outerHeight(),
		matterH = windowH - navbarH  - pageHeadH - spaceH,
		orderMainH = matterH - infoTitleH - btnH;

	//matter的高度
	$("#matter").css("height", matterH);

	//orderMain的高度
	$("#orderMain").css("height",orderMainH);
	//expressNum的高度
	$("#expressNum").css({
		"height": orderMainH ,
		"line-height": orderMainH + "px"
	});


	$(window).on("resize",function(){

		windowH = $(window).height();
		navbarH = $("#navbar").outerHeight();
		pageHeadH = $("#pageHead").outerHeight();
		infoTitleH = $("#infoTitle").outerHeight();
		btnH = $(".btn-height").outerHeight();
		matterH = windowH - navbarH  - pageHeadH - spaceH,
		orderMainH = matterH - infoTitleH - btnH;
		
		//matter的高度
		$("#matter").css("height", matterH);
		//orderMain的高度
		$("#orderMain").css("height",orderMainH);
		//expressNum的高度
		$("#expressNum").css({
			"height": orderMainH,
			"line-height": orderMainH+ "px"
		});
	});
	
	
	
	//进入页面输入框获取焦点 	
	$("#number").focus();

	//错误提示框点击确定后获取焦点
	$('#noticeModal,#outputModal,#scanExpressModal,#moveOrderModal').on('hidden.bs.modal', function () {
  		$("#number").val("").focus();
	});

	//扫描快递单和订单 以及 确认出库键盘事件
	$("#outputUpdateForm").keypress(function(event){
		expressState = $('.express-info').length,
		orderState =  $('.order-info li').length,
		numberValue = $("#number").val();
		len = numberValue.length;
		if( event.which == 13){
			event.preventDefault();
			
			if(orderState == 0){//订单详情不存在
				if(expressState == 0){//快递单号信息不存在
					if(len < 12  ){ //扫描的是订单号

						$("#noticeModal").modal();
						$("#noticeContent").html("请扫描快递单!");
						
					}else{//扫描的是快递单号
						getExpressNumber();
					}
				}else{//快递单号信息存在
					if(len < 12){//扫描的是订单号
						expressNo = $("#expressNo").html();
						getOrderNumber();
					}else{//又扫描了一次快递单号
						getExpressNumber();
					}
				}
			}else{//订单详情存在
				if(len == 0){
					$("#outputModal").modal();
					$("#outputModal").on("shown.bs.modal",function(){
						$("#confirmOutput").focus();
					});
				}else if(len < 12){//扫描订单号
					//scan order
					expressNo = $("#expressNo").html();
					getOrderNumber();
				}else{//又扫描了快递单号
					$("#scanExpressModal").modal();
					$("#scanExpressModal").on("shown.bs.modal",function(){
						$("#confirmSecondOutput").focus();
					});
				}
			}
		}
		
	});

	//扫描快递单号点击事件
	$("#outputUpdateForm").on("submit",function(){
		expressState = $('.express-info').length,
		orderState =  $('.order-info li').length,
		numberValue = $("#number").val();
		len = numberValue.length;
		event.preventDefault();
		
		if(orderState == 0){//订单详情不存在
			if(expressState == 0){//快递单号信息不存在
				if(len < 12  ){ //扫描的是订单号

					$("#noticeModal").modal();
					$("#noticeContent").html("请扫描快递单!");
					
				}else{//扫描的是快递单号
					getExpressNumber();
				}
			}else{//快递单号信息存在
				if(len < 12){//扫描的是订单号
					expressNo = $("#expressNo").html();
					getOrderNumber();
				}else{//又扫描了一次快递单号
					getExpressNumber();
				}
			}
		}else{//订单详情存在
			if(len < 12){//扫描订单号
				//scan order
				expressNo = $("#expressNo").html();
				getOrderNumber();
			}else{//又扫描了快递单号
				$("#scanExpressModal").modal();
			}
		}
		
	});
	
	//确认出库
	$("#output").on("click",function(){
		$("#outputModal").on("shown.bs.modal",function(){
			$("#confirmOutput").focus();
		});
	});

	$("#confirmOutput,#confirmSecondOutput").click(output);

	function output(){
		expressType = $(".express-type").attr("data-type");		
		orderIds = [];
		$("#orderList li").each(function(elm,index){
		 	
		 	orderIds.push($(this).data("id"));
		 	
		});

		confirmOutput();
	}




	//取消出库
	$("#cancelOutput").on("click",function(){
		window.location.assign("/output/update/" + boxId);
	});

	//切换箱子
	$("#switchBox").on("click",function(event){
		event.preventDefault();
		window.location.assign("/output/index");
	});
	
	//确定移动订单
	$("#moveOrderModal").on("shown.bs.modal",function(){
		$("#confirmmove").focus();
	});

	$("#confirmmove").on("click",function(){
		getMoveOrderNumber();
	});


	$("#rmOrderModal").on("shown.bs.modal",function(){
		$("#confirmRm").focus();
	});
	//确定删除订单的键盘事件
	$("#orderList ").delegate(".fa-trash-o","click",function(){
		rmOrderId = $(this).parent().data("id");
		$("#rmOrderModal").on("shown.bs.modal",function(){
			$("#confirmRm").focus();
		});
	});
	//将订单移出 出库列表，只是从列表上移除而已，不移出箱子
	$("#confirmRm").on("click",function(){
		removeOrderFromList();
	});

	function removeOrderFromList(){
		$("#loadingBar").css("display","block");
		console.log(rmOrderId);

		$.ajax({
			type: 'POST',
			url: '/output/rmorder',
			dataType: 'json',
			data: {
				"box_id" : boxId,
				"order_id" : rmOrderId,
				"_csrf": safeCode.attr('content')
			},
			success: function(data) {
				$("#loadingBar").hide();
				if( !data.errno ){


					$("#"+rmOrderId).remove();

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

	//首次扫描快递单号
	function getExpressNumber() {
		$("#loadingBar").css("display","block");
		$.ajax({
			type: 'POST',
			url: '/output/scanexpress',
			dataType: 'json',
			data: {
				"express_no" : numberValue,
				"_csrf"  : safeCode.attr('content')
			},
			success: function(data) {
				$("#loadingBar").hide();
				if( !data.errno ){	

					$(".express-num").hide();
					$(".order-num").removeClass("hide");
					$("#expressContent").html(data.rst);
					
					
				}else{
					
					$("#noticeModal").modal();
					$("#noticeContent").html(data.err);
					
				}

				$("#number").val("").focus();
			},
			error : function(xhr,textStatus){
		      	$("#loadingBar").hide();
		      	$("#noticeModal").modal();
				$("#noticeContent").html("哦呦报错啦~，错误类型是:["+ xhr.status +"],请检查网络连接或与技术联系！");
		    }
		});
		
	 }

	//扫描订单号
	function getOrderNumber() {
		$("#loadingBar").css("display","block");
		$.ajax({
			type: 'POST',
			url: '/output/scanorder',
			dataType: 'json',
			data: {
				"box_id" : boxId,
				"order_id" :numberValue,
				"express_no" : expressNo,
				"_csrf"  : safeCode.attr('content')
			},
			success: function(data) {
				$("#loadingBar").hide();
				if( !data.errno ){	

					$("#expressNum").hide();
					$("#orderNum").hide();
					$("#orderList").prepend(data.rst.order);
					
					if(data.rst.same_order){
						var detail_info = "";
						$.each(data.rst.same_order,function(i,v){
							detail_info += "<div class='merge-note'>"
							detail_info += "<span>同箱可合并订单</span>"
				            detail_info += '<span>'+ data.rst.same_order[i].order_id +'</span>';
				            detail_info += '<span>'+ data.rst.same_order[i].goods_name +'</span>';
				            detail_info += "</div>"
				         });
						$(".merge").html(detail_info);
					}
          			
					
				}else{
					if(data.errno == 12003){

						$("#moveOrderModal").modal();
					}else{
						$("#noticeModal").modal();
						$("#noticeContent").html(data.err);
						
					}
				}

				$("#number").val("").focus();
			},
			error : function(xhr,textStatus){
		      	$("#loadingBar").hide();
		      	$("#noticeModal").modal();
				$("#noticeContent").html("哦呦报错啦~，错误类型是:["+ xhr.status +"],请检查网络连接或与技术联系！");
		    }
		});
		
	}

	//移单
	function getMoveOrderNumber() {
		$("#loadingBar").css("display","block");
		$.ajax({
			type: 'POST',
			url: '/output/scanorder',
			dataType: 'json',
			data: {
				"box_id" : boxId,
				"order_id" :numberValue,
				"express_no" : expressNo,
				"direct" : 1,
				"_csrf"  : safeCode.attr('content')
			},
			success: function(data) {
				$("#loadingBar").hide();
				if( !data.errno ){	

					$("#expressNum").hide();
					$("#orderNum").hide();
					$("#orderList").append(data.rst.order);
					
					if(data.rst.same_order){
						var detail_info = "";
						$.each(data.rst.same_order,function(i,v){
							detail_info += "<div class='merge-note'>"
							detail_info += "<span>同箱可合并订单</span>"
				            detail_info += '<span>'+ data.rst.same_order[i].order_id +'</span>';
				            detail_info += '<span>'+ data.rst.same_order[i].goods_name +'</span>';
				            detail_info += "</div>"
				         });
						$(".merge").html(detail_info);
					}
					
				}else{

					$("#noticeModal").modal();
					$("#noticeContent").html(data.err);
				}
				$("#number").val("").focus();
			},
			error : function(xhr,textStatus){
		      	$("#loadingBar").hide();
		      	$("#noticeModal").modal();
		      	$("#noticeContent").html("哦呦报错啦~，错误类型是:["+ xhr.status +"],请检查网络连接或与技术联系！");
		    }
		});
		
	}

	//确认出库
	function confirmOutput() {
		$("#loadingBar").css("display","block");
		$.ajax({
			type: 'POST',
			url: '/output/do',
			dataType: 'json',
			data: {
				"box_id" : boxId,
				"order_id" : orderIds,
				"express_no" : expressNo,
				"logistic_provider" : expressType,
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
				$("#noticeContent").html("哦呦报错啦~，错误类型是:["+ xhr.status +"],请检查网络连接或与技术联系！");
		    }
		});
		
	}

})(jQuery);
