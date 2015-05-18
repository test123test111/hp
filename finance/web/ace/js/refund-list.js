;
(function(window, $) {
	var $amount = $(".amount"),
		$resultId =$(".result-id"),
		resultIds = [],
		safeCode = $('meta[name=csrf-token]');


	bind();

	function bind() {
		$(document).off("click",".agreement,.reject").on("click",".agreement,.reject",function(){
			var money =$(this).parents(".operate").siblings(".pay-num").children(".amount-num").html();
				resultId = $(this).data("result");

			$amount.html(money);
			$resultId.val(resultId);

		}).off("click","#confirmRefund").on("click","#confirmRefund",function(){

			confirmRefund();
			
		});	

		//复选框部分
		$('input').iCheck('check'); 
		
		$("input").iCheck({
		    checkboxClass: 'icheckbox_square-blue',
		    increaseArea: '20%' // optional
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
			liLen = $("tbody .order-num").length;
			checkBoxLen = $("tbody input:checked").length;

			if(checkBoxLen == liLen){
				$('#checkAll').iCheck('check');
			}
		});
		$(document).on("ifUnchecked",".check-box input",function(){
			$("#checkAll").iCheck("uncheck");
		});


		$("#fullRefundBtn").on("click",function(event){
			event.preventDefault();

			onoff = $("#checkAll").prop("checked");
			resultIds = [];
			if(onoff){
				
				$(".rasult-id").each(function(index,elem){
					resultIds.push($(this).data("result"));
				});
				
			}else{

				$("input:checked").each(function(){
					resultNum = $(this).parents("td").siblings(".rasult-id").data("result");
					resultIds.push(resultNum);
				});
			}
			console.log(resultIds);

			
		
		});	

		$("#confirmBatchRefund").on("click",function(){
			batchRefund();
		});
		
	}

	//删除订单
	 function confirmRefund() {

	 	$("#loadingBar").css("display","block");
		$.ajax({
			type: 'POST',
			url: '/refund/do',
			dataType: 'json',
			data: {
				"id" : resultId,
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

	 function batchRefund() {

	 	$("#loadingBar").css("display","block");
		$.ajax({
			type: 'POST',
			url: '/refund/batch',
			dataType: 'json',
			data: {
				"ids" : resultIds,
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