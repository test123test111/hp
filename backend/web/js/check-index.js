(function($) {
	var checkBoxLen = 0,
		liLen = 0,
		orderId = 0,
		orderIds = [],
		packageNum = 0,
		safeCode = $('meta[name=csrf-token]'),
		onoff = $("#checkAll").prop("checked");

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

	

	//获取包裹信息
	$("#checkBtn").on("click",function(event){
		event.preventDefault();
		packageNum = $("#number").val();
		getPackageInfo();
	});

	//错误提示框点击确定后获取焦点
	$('#noticeModal').on('hidden.bs.modal', function () {
  		$("#number").val("").focus();
	});


	//打印单个订单条码

	$(document).on("click","#orderContent .fa-print",function(){
		orderId = $(this).parents("li").data("id");
		$("#singlePrintCodeModal").modal();
		$("#singlePrintCodeModal").on("shown.bs.modal",function(){
			$("#confirmSinglePrint").focus();

		});
		
	});
	//确定打印单个条码
	$("#confirmSinglePrint").on("click",function(){
		printSingleOrder();

	});



	//批量打印条码提示
	$(document).on("click", "#batchPrintCode",function(event){
		event.preventDefault();
		var checkLen = $("input:checked").length;

		if(checkLen === 0){
			$("#noticeModal").modal();
			$("#noticeContent").html("亲，请先选择要批量打印的订单。");
		}else{
			$("#batchPrintCodeModal").modal();
			$("#batchPrintCodeModal").on("shown.bs.modal",function(){
				$("#confirmBatchPrint").focus();
			});
		}
		
	});

	//确定批量打印
	$("#confirmBatchPrint").on("click",function(event){
		event.preventDefault();

		onoff = $("#checkAll").prop("checked");
		orderIds = [];
		if(onoff){
			
			$("#orderContent li").each(function(elm,index){
				orderIds.push($(this).data("id"));
			});
			
		}else{

			$("input:checked").each(function(){
				orderId = $(this).parents("li").data("id");
				orderIds.push(orderId);
			});
		}
		
		batchPrint();

		
	});

	
	//批量入箱
	$(document).on("click","#batchInput",function(){
		var checkLen = $("input:checked").length;
			boxId = 0;
		if(checkLen === 0){
			$("#noticeModal").modal();
			$("#noticeContent").html("亲，请先选择要批量入库的订单。");
		}else{
			$("#batchInBoxModal").modal();
			$(".box,#confirmCreatBox").unbind("click").bind("click",function(){
				boxId = $(this).find("span").html();
				onoff = $("#checkAll").prop("checked");
				orderIds = [];
				if(onoff){
					
					$("#orderContent li").each(function(elm,index){
						orderIds.push($(this).data("id"));
					});
				}else{

					$("input:checked").each(function(){
						orderId = $(this).parents("li").data("id");
						orderIds.push(orderId);
					});
				}
				batchInBox();
			
			});
		}
		
	});

	//创建新箱子
	$("#addBox").on("click",function(){
		$("#creatBoxModal").on("shown.bs.modal",function(){
			$("#confirmCreatBox").focus();
		});
	});

	//获取包裹信息
	function getPackageInfo() {
		$("#loadingBar").css("display","block");
		$.ajax({
			type: 'POST',
			url: '/check/scanpack',
			dataType: 'json',
			data: {
				"pack_id" : packageNum,
				"_csrf": safeCode.attr('content')
			},
			success: function(data) {
				$("#loadingBar").hide();
				if( !data.errno ){
					
					$("#matter").html(data.rst);
					$("#number").val("").focus();


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

	 //打印单个订单
	 function printSingleOrder() {

	 	$("#loadingBar").css("display","block");
		$.ajax({
			type: 'POST',
			url: '/check/print',
			dataType: 'json',
			data: {
				"order_id" : orderId,
				"_csrf": safeCode.attr('content')
			},
			success: function(data) {
				$("#loadingBar").hide();
				if( !data.errno ){	
			
				}else{
						
					$("#noticeModal").modal();
					
				}
				
			},
			error : function(xhr,textStatus){
		      	$("#loadingBar").hide();
		      	$("#noticeModal").modal();
				$("#noticeContent").html("哦呦报错啦~，错误类型是:["+ xhr.status +"],请检查网络连接或与技术联系！");
				
		    }
		});
	 }

	 //批量打印
	 function batchPrint() {
		$("#loadingBar").css("display","block");
		$.ajax({
			type: 'POST',
			url: '/check/batchprint',
			dataType: 'json',
			data: {
				"orderids" : orderIds,
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

	 //批量入箱
	 function batchInBox() {
		$("#loadingBar").css("display","block");
		$.ajax({
			type: 'POST',
			url: '/check/batchinput',
			dataType: 'json',
			data: {
				"box_id" : boxId,
				"orderids" : orderIds,
				"_csrf": safeCode.attr('content')
			},
			success: function(data) {
				$("#loadingBar").hide();
				if( !data.errno ){
					$("#creatBoxModal").modal("hide");
					$("#batchInBoxModal").modal("hide");
					$("#noticeModal").modal();
					$("#noticeContent").html("入箱成功！");

				}else{
					$("#noticeModal").modal();
					$("#noticeContent").html(data.err);
					$("#batchInBoxModal").modal("hide");
					$("#creatBoxModal").modal("hide");
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
