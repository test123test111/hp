(function($) {

	var orderId = 0,
		reasonId = 0,
		expressType = 0,
		orderIds = [],
		orderCount = $("#orderCount").html(),
		boxId = $("#boxId").html(),
		safeCode = $('meta[name=csrf-token]'),
		
		//js改变元素的高度
		spaceH = 100,
	    windowH = $(window).height(),
		navbarH = $("#navbar").outerHeight(),
		infoTitleH = $("#infoTitle").outerHeight(),
		btnH = $(".btn-height").outerHeight(),
		matterH = windowH - navbarH - spaceH ,
		orderMainH = matterH - infoTitleH - btnH;

	//matter的高度
	$("#matter").css("height", matterH);

	//orderMain的高度
	$("#orderMain").css("height",orderMainH);

	$(window).on("resize",function(){
		windowH = $(window).height(),
		navbarH = $("#navbar").outerHeight(),
		infoTitleH = $("#infoTitle").outerHeight(),
		btnH = $(".btn-height").outerHeight(),
		matterH = windowH - navbarH - spaceH ,
		orderMainH = matterH - infoTitleH - btnH;
		
		//matter的高度
		$("#matter").css("height", matterH);
		//orderMain的高度
		$("#orderMain").css("height",orderMainH);
	});
	
	//切换箱子
	$("#switchBox").on("click",function(event){
		event.preventDefault();
		window.location.assign("/stock/index");
	});

	//打印单个订单
	$(".order-list  .fa-print").on("click",function(){
		$("#batchPrint").focus();

		orderId = $(this).parent().data("id");
		expressType = $(this).parents("div").find($(".express-type")).val();
		window.open("/stock/print?orderid="+ orderId+ "&eid=" +expressType,"_blank");
	});

	//点击删除订单
	$(".order-list  .fa-trash-o").on("click",function(event){

		orderId = $(this).parent().data("id");

		$("#delOrderModal").on("shown.bs.modal" , function(){
			$("#confirmDelOrder").focus();
		});
		
	});

	//选择删除订单理由
	$(".reasons").on("click",function(){
		reason = $(this).find("a").html();
		$("#dropdownMenu em").html(reason);
		reasonId = $(this).data("reason-id");

		$("#confirmDelOrder").focus();
	});
	//确定删除订单
	$("#confirmDelOrder").on("click",function(event){
		event.preventDefault();
		if($("#dropdownMenu em").html() == "请选择理由"){

			$("#noticeModal").modal();
			$("#noticeContent").html("请选择删除订单的理由");
			
			return false;

		}else{
	
			delOrder();
		}
		
		
	});

	//打开页面，批量打印按钮获取焦点
	$("#batchPrint").focus();

	//错误提示框点击确定后  批量打印按钮获取焦点
	$('#noticeModal ,#delOrderModal, #batchPrintModal').on('hidden.bs.modal', function () {
  		$("#batchPrint").focus();
	});

	//批量打印
	$("#batchPrint").keypress(function(event){
		event.preventDefault();
		if(event.which == 13){
			$("#batchPrintModal").modal();
		}
	});

	$("#batchPrint").on("click",function(event){
		event.preventDefault();
		$("#batchPrintModal").modal();
	});

	//顺风批量打印
	$("#shunfengForm").on("submit",function(){
		orderIds = [];
		 $(".express-type[data-type=0]").each(function(elm,index){
		 	$(this).parents(".block").find("ul li").each(function(elm,index){
		 		orderIds.push($(this).data("id"));
		 	});
		 	$("#sfOrderids").val(orderIds);
		 	$("#sfBoxId").val(boxId);

		 });
	});
	//圆通批量打印
	$("#yuantongForm").on("submit",function(){
		orderIds = [];
		 $(".express-type[data-type=1]").each(function(elm,index){
		 	$(this).parents(".block").find("ul li").each(function(elm,index){
		 		orderIds.push($(this).data("id"));
		 	});
		 	$("#ytOrderids").val(orderIds);
		 	$("#ytBoxId").val(boxId);
		 });
	});
	//快递类型发生变化
	$(".express-type").on("change",function(){
		var expressVal = $(this).val();
		$(this).attr("data-type",expressVal) ;

	});

	//确定删除订单
	function delOrder() {
		$("#loadingBar").css("display","block");

		$.ajax({
			type: 'POST',
			url: '/stock/delete',
			dataType: 'json',
			data: {
				"box_id" : boxId,
				"order_id" : orderId,
				"reason_id" : reasonId,
				"_csrf"  : safeCode.attr('content')
			},
			success: function(data) {
				$("#loadingBar").hide();
				if( !data.errno ){	

					var len = $('#'+orderId).parent().find("li").length;
					if( len == 1){
						
						$('#'+orderId).parents(".block").find(".express-info").remove();
	
					}
					$('#'+orderId).remove();

					orderCount --;
					$("#orderCount").html(orderCount);
					
				}else{

					$("#noticeModal").modal();
					$("#noticeContent").html(data.err);
					
				}
			},
			error : function(xhr,textStatus){
		      	$("#loadingBar").hide();
		      	$("#noticeModal").modal();
				$("#noticeContent").html("哦呦报错啦~，错误类型是:["+ xhr.status +"],请检查网络连接或与技术联系！");		    }
		});
		
	 }


})(jQuery);
