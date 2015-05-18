;
(function(window,$){
	
	var safeCode = $('meta[name=csrf-token]');
	bind();

	function bind(){

		// $("#copyAccountBtn").zclip({
		// 	path: '/ace/js/ZeroClipboard.swf',
		// 	copy: $('#bankAccount').text(),
		// 	afterCopy: function(){
		// 		$("#bankAccount").css("background-color","red");
		// 		$("#copySuccess").show().fadeOut(2000);
		// 	}
		// });
		// $("#accountInfoModal").modal();
		$("#copyAccountBtn").zclip({
	        path: "/ace/js/ZeroClipboard.swf",
	        copy: function(){
	        	//alert(0);
		        return $("#bankAccount").text();
		    },
	        beforeCopy:function(){/* 按住鼠标时的操作 */
	        	//alert(1);
	            $(this).css("color","orange");
	        },
	        afterCopy:function(){/* 复制成功后的操作 */
	        	//alert(2);
	            $("#copySuccess").show().fadeOut(9000);
	        }
    	});
    	$(document).off("click",".buyer_do").on("click",".buyer_do",function(){
			orderId = $(this).attr("data-id");
			buyerId = $(this).attr("data-buyer-id");
			// console.log(orderId);
			buyerAccount();
		});
		function buyerAccount() {

		 	$("#loadingBar").css("display","block");
			$.ajax({
				type: 'POST',
				url: '/settlement/buyeraccount',
				dataType: 'json',
				data: {
					"id" : orderId,
					"buyer_id":buyerId,
					"_csrf": safeCode.attr('content')
				},
				success: function(data) {
					$("#loadingBar").hide();
					$("#accountInfoModal").html(data.rst);
					$("#accountInfoModal").modal();
					
					
				},
				error : function(xhr,textStatus){
			      	$("#loadingBar").hide();
			      	$("#accountInfoModal").modal("hide");
			      	$("#accountInfoModal").modal();
					$("#noticeContent").html("哦呦报错啦~，错误类型是:["+ xhr.status +"],请检查网络连接或与技术联系！");
					
			    }
			});
		}
    	$("#copyNameBtn").zclip({
	        path: "/ace/js/ZeroClipboard.swf",
	        copy: function(){
		        return $("#buyerName").text();
		    },
	        beforeCopy:function(){/* 按住鼠标时的操作 */
	            $(this).css("color","orange");
	        },
	        afterCopy:function(){/* 复制成功后的操作 */
	            $("#copySuccess").show().fadeOut(2000);
	        }
		});
	}
})(window,jQuery);