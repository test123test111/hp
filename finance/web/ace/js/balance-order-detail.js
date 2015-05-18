;
(function(window,$){

	var orderIds = [],  //多个订单号
		orderId = 0, //订单号
		changeAmount = 0,
		operateId = 0, //批量操作id
		onoff = true,
		safeCode = $('meta[name=csrf-token]');
		

	bind();

	function bind(){
		$(document).on("click","#bachtMenuContent li",function(){
			var contentVal = $(this).find("a").html();

			$("#batchOperate").find("em").html(contentVal);
		}).on("click",".ignore-restore",function(){
			var operateVal = $(this).find("a").html();
				dataId = $(this).data("id");

			$("#ignoreRestoreModal").modal();
			$("#ignoreRestoreModalLabel,#ignoreRestoreBody em").html(operateVal);
			$("#confirmSingleBtn").attr("data-order-id",dataId);

		}).on("click",".change-amount",function(){
			var dataId = $(this).data("id");
			$("#confirmChangeAmount").attr("data-order-id",dataId);
		}).on("click","#apply",function(event){
			event.preventDefault();
			var batchVal = $("#batchOperate em").html();
				checkedLen = $("input:checked").length;

			
			onoff = $("#checkAll").prop("checked");
			orderIds = [];

			if(onoff){
				
				$("#orderList .info-detail").each(function(elm,index){
					orderIds.push($(this).data("id"));
				});
				
			}else{

				$("input:checked").each(function(){
					orderId = $(this).parents("tr").data("id");
					orderIds.push(orderId);
				});
			}

			if(checkedLen == 0){
				$("#noticeModal").modal();
				$("#noticeContent").html("批量操作不能为空，请选择复选框!");
			}else if(batchVal == "选择批量操作"){
				$("#noticeModal").modal();
				$("#noticeContent").html("请选择批量操作的应用!");
			}else if(batchVal == "忽略"){
				$("#ignoreBatchModal").modal();
				$("#batchIgnoreInput").val(orderIds);				
			}else if(batchVal == "调整"){
				$("#adjustBatchModal").modal();
				$("#batchChangeInput").val(orderIds);
			}else{
				$("#settlementModal").modal();
				$("#batchSettleMent").val(orderIds);
				$("#serchBeginTime").val($("#beginTime").val());
				$("#serchEndTime").val($("#endTime").val());
			}

			
		
		console.log(orderIds);

		}).on("click","#confirmSingleBtn",function(){ //单个忽略
			orderId = $(this).data("order-id");
			confirmSingleIgnore();
		}).on("click","#confirmChangeAmount",function(){
			orderId = $(this).data("order-id");
			changeAmount = $("#changNum").val();
			confirmChangeAmount();
		});	

		//刷新页面获取一级tab的类型
		var source = getQuery("status");
		$("#status").val(source);
		//获取url固定参数
		function getQuery(name) {       
		     var pattern = new RegExp("[?&]" + name + "\=([^&]+)", "g");       
		     var matcher = pattern.exec(window.location.search);       
		     var items = null;       
		     if (matcher) {             
		          items = decodeURIComponent(matcher[1]);       
		     };
		     return items;
		}

		//复选框部分
		$("input").iCheck({
		    checkboxClass: 'icheckbox_square-blue',
		    increaseArea: '20%'// optional

		});

		//全选 反选
		$('#checkAll').on('ifClicked', function(event){

			
			onoff = $(this).prop("checked");
			
			if(onoff){
				$(".check-box").iCheck("uncheck");
			}else{
				$(".check-box").iCheck("check");
			}
			
		});
		$(document).on("ifChecked",".check-box div",function(){
			liLen = $("#orderList .info-detail").length;
			checkBoxLen = $("#orderList input:checked").length;
			
			if(checkBoxLen == liLen){
				$('#checkAll').iCheck('check');
			}
		});
		$(document).on("ifUnchecked",".check-box input",function(){
			$("#checkAll").iCheck("uncheck");
		});
		
	}

	function confirmSingleIgnore() {

	 	$("#loadingBar").css("display","block");
		$.ajax({
			type: 'POST',
			url: '/settlement/ignore',
			dataType: 'json',
			data: {
				"order_id" : orderId,
				"_csrf"  : safeCode.attr('content')
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
	
	function confirmChangeAmount() {

	 	$("#loadingBar").css("display","block");
		$.ajax({
			type: 'POST',
			url: '/settlement/adjust',
			dataType: 'json',
			data: {
				"order_id" : orderId,
				"price" : changeAmount,
				"_csrf"  : safeCode.attr('content')
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
})(window,jQuery);