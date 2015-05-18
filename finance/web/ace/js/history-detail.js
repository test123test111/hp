;
(function(window,$){
	var orderIds = [],
		onoff = true,
		buyerNum = $("table").length,
		orderNum = $("tbody tr").length;

	bind();

	function bind(){

		$(document).on("click","#confirmAccount",function(){
			orderIds = [];
			$(".checkAll").each(function(index,elem){
				onoff = $(this).prop("checked");
				
				if(onoff){
					$orderId = ($(this).parents("table").find(".order-id"));
					$orderId.each(function(index,elem){
						orderIds.push($(this).html());
					})	
				}else{
					
					$(this).parents("table").find("input:checked").parents("tr").find(".order-id").html();
					$(this).parents("table").find("input:checked").each(function(index,elem){
						var orderId = $(this).parents("tr").find(".order-id").html();
						orderIds.push(orderId);
					});
				}

			});
			
		}).on("click","#menuContent li",function(){
			var val = $(this).children("a").html();
				contentId = $(this).data('content-id');
			$("#printContent em").html(val);
		
		}).on("click","#printBtn",function(){
			var parameter = $(this).data("id");
			window.open("/settlement/print/" + parameter , "_blank");
		}).on("click","#exportBtn",function(){
			var parameter = $(this).data("id");
			window.open("/settlement/export/" + parameter);
		});

		//数据初始化
		dataInitialize();
		function dataInitialize(){
			var sum = 0;

			$("#buyerNums").html($("table").length);  //买手个数
			$("#orderNums").html($("tbody tr").length); //订单个数
			//待结算
			$(".outstanding-amount").each(function(index,elem){ 
				var num =$(this).find("span").html();
				sum = parseFloat(removeComma(num)) + parseFloat(sum);			
				sum = toDecimal(sum);	

			});
			$("#outstandingAmount").html(addKannma(sum));
			//调整金额
			sum = 0;
			$(".adjust-amount").each(function(index,elem){
				var num =parseFloat($(this).find("span").html());
				sum = parseFloat(removeComma(num)) + parseFloat(sum);
				sum = toDecimal(sum);
			});
			if(sum > 0){
				
				sum = "+" + sum;
			}
			$("#adjustAmount").html(addKannma(sum));
			//应结金额
			sum = 0;
			$(".final-amount").each(function(index,elem){
				var num = $(this).find("span").html();
				
				sum = parseFloat(removeComma(num)) + parseFloat(sum);
				sum = toDecimal(sum);	
			});

			$("#finalAmount").html(addKannma(sum));
		}


		//四舍五入保留2位小数，同时：2，会在2后面补上00.即2.00  
        function toDecimal(x) {  
            var f = parseFloat(x);  
            if (isNaN(f)) {  
                return false;  
            }  
            var f = Math.round(x*100)/100;  
            var s = f.toString();  
            var rs = s.indexOf('.');  
            if (rs < 0) {  
                rs = s.length;  
                s += '.';  
            }  
            while (s.length <= rs + 2) {  
                s += '0';  
            }  
            return s;  
        }
        //添加千分位分割符
        function addKannma(number) {  
		    var num = number + "";  
		    num = num.replace(new RegExp(",","g"),"");   
		    // 正负号处理   
		    var symble = "";   
		    if(/^([-+]).*$/.test(num)) {   
		        symble = num.replace(/^([-+]).*$/,"$1");   
		        num = num.replace(/^([-+])(.*)$/,"$2");   
		    }   
		  
		    if(/^[0-9]+(\.[0-9]+)?$/.test(num)) {   
		        var num = num.replace(new RegExp("^[0]+","g"),"");   
		        if(/^\./.test(num)) {   
		        num = "0" + num;   
		        }   
		  
		        var decimal = num.replace(/^[0-9]+(\.[0-9]+)?$/,"$1");   
		        var integer= num.replace(/^([0-9]+)(\.[0-9]+)?$/,"$1");   
		  
		        var re=/(\d+)(\d{3})/;  
		  
		        while(re.test(integer)){   
		            integer = integer.replace(re,"$1,$2");  
		        }   
		        return symble + integer + decimal;   
		  
		    } else {   
		        return number;   
		    }   
		} 

		//删除千分位分割符
		function removeComma(number) {  
		    var num = number+"";  
		    num = num.replace(new RegExp(",","g"),"");   
		        if(/^[-+]?[0-9]+(\.[0-9]+)?$/.test(num)) {   
		        return num;   
		    } else {   
		        return number;   
		    }   
		}   

		//复选框部分
		$('input').iCheck('check'); 

		$("input").iCheck({

		    checkboxClass: 'icheckbox_square-blue',
		    increaseArea: '20%'// optional

		});

		//全选 反选
		$("table").each(function(index,elm){
			var This = $(this);

			$(this).find(".checkAll").on("ifClicked",function(){
				onoff = $(this).prop("checked");
				
				if(onoff){
					This.find(".check-box").iCheck("uncheck");
				}else{
					This.find(".check-box").iCheck("check");
				}

			});

			$(this).find(".check-box input").on("ifChecked",function(){
				var liLen = This.find(".order_list .info-detail").length,
					checkBoxLen = This.find(".order_list input:checked").length;
				
				if(checkBoxLen == liLen){
					This.find(".checkAll").iCheck('check');
				}
			});

			$(this).find(".check-box input").on("ifUnchecked",function(){
				This.find(".checkAll").iCheck("uncheck");
			});

			//结算数据变化

			$(this).find(".check-box input").on("ifUnchecked",function(){
				var checkedNum = This.find("input:checked").length,

					outstandingAllAmount = removeComma($("#outstandingAmount").html()),
					outstandingSingleAmount = removeComma($(this).parents("td").siblings(".outstanding-single-amount").find("span").html()),

					adjustAllAmount = removeComma($("#adjustAmount").html()),
					adjustSingleAmount = removeComma($(this).parents("td").siblings(".adjust-single-amount").find("span").html()),

					finalAllAmount = removeComma($("#finalAmount").html()),
					finalSingleAmount = removeComma($(this).parents("td").siblings(".final-single-amount").find("span").html());
				
				orderNum --;
				if(checkedNum == 0){
					buyerNum --;
				}
				
				outstandingNewNum = toDecimal(outstandingAllAmount - outstandingSingleAmount);
				adjustNewAmount = toDecimal(adjustAllAmount - adjustSingleAmount);
				finalNewAmount = toDecimal(finalAllAmount - finalSingleAmount);

				$("#orderNums").html(orderNum);
				$("#buyerNums").html(buyerNum);
				$("#outstandingAmount").html(addKannma(outstandingNewNum));
				$("#adjustAmount").html(addKannma(adjustNewAmount));
				$("#finalAmount").html(addKannma(finalNewAmount));
			});
			
			$(this).find(".check-box input").on("ifChecked",function(){
				var checkedNum = This.find("input:checked").length,

					outstandingAllAmount = removeComma($("#outstandingAmount").html()),
					outstandingSingleAmount = removeComma($(this).parents("td").siblings(".outstanding-single-amount").find("span").html());

					adjustAllAmount = removeComma($("#adjustAmount").html()),
					adjustSingleAmount = removeComma($(this).parents("td").siblings(".adjust-single-amount").find("span").html());

					finalAllAmount = removeComma($("#finalAmount").html()),
					finalSingleAmount = removeComma($(this).parents("td").siblings(".final-single-amount").find("span").html());
				
				orderNum ++;
				
				if(checkedNum <= 1){
					buyerNum += 1;
				}

				outstandingNewNum = parseFloat(outstandingAllAmount) + parseFloat(outstandingSingleAmount);
				outstandingNewNum = toDecimal(outstandingNewNum);
				adjustNewAmount = parseFloat(adjustAllAmount) + parseFloat(adjustSingleAmount);
				adjustNewAmount = toDecimal(adjustNewAmount);
				finalNewAmount = parseFloat(finalAllAmount) + parseFloat(finalSingleAmount);
				finalNewAmount = toDecimal(finalNewAmount);

				$("#orderNums").html(orderNum);
				$("#buyerNums").html(buyerNum);
				$("#outstandingAmount").html(addKannma(outstandingNewNum));
				$("#adjustAmount").html(addKannma(adjustNewAmount));
				$("#finalAmount").html(addKannma(finalNewAmount));

			});
		});

	}
    
})(window,jQuery);
