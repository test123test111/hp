;
(function(window,$){
    var orderIds = [],
        onoff = true,
        buyerNum = $("table").length,
        orderNum = $("tbody tr").length;

    bind();

    function bind(){

        $(document).off("click","#confirmAccount").on("click","#confirmAccount",function(){
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
                $("#batchSettleMent").val(orderIds);
            });
            
        });

        //鏁版嵁鍒濆鍖�
        dataInitialize();
        function dataInitialize(){
            var sum = 0;

            $("#buyerNums").html($("table").length);  //涔版墜涓暟
            $("#orderNums").html($("tbody tr").length); //璁㈠崟涓暟
            //寰呯粨绠�
            $(".outstanding-amount").each(function(index,elem){ 
                var num =$(this).find("span").html();
                sum = parseFloat(removeComma(num)) + parseFloat(sum);           
                sum = toDecimal(sum);   

            });
            $("#outstandingAmount").html(addKannma(sum));
            //璋冩暣閲戦
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
            //搴旂粨閲戦
            sum = 0;
            $(".final-amount").each(function(index,elem){
                var num = $(this).find("span").html();
                
                sum = parseFloat(removeComma(num)) + parseFloat(sum);
                sum = toDecimal(sum);   
            });

            $("#finalAmount").html(addKannma(sum));
        }


        //鍥涜垗浜斿叆淇濈暀2浣嶅皬鏁帮紝鍚屾椂锛�2锛屼細鍦�2鍚庨潰琛ヤ笂00.鍗�2.00  
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
        //娣诲姞鍗冨垎浣嶅垎鍓茬
        function addKannma(number) {  
            var num = number + "";  
            num = num.replace(new RegExp(",","g"),"");   
            // 姝ｈ礋鍙峰鐞�   
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

        //鍒犻櫎鍗冨垎浣嶅垎鍓茬
        function removeComma(number) {  
            var num = number+"";  
            num = num.replace(new RegExp(",","g"),"");   
                if(/^[-+]?[0-9]+(\.[0-9]+)?$/.test(num)) {   
                return num;   
            } else {   
                return number;   
            }   
        }   

        //澶嶉€夋閮ㄥ垎
        $('input').iCheck('check'); 

        $("input").iCheck({

            checkboxClass: 'icheckbox_square-blue',
            increaseArea: '20%'// optional

        });

        //鍏ㄩ€� 鍙嶉€�
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

            //缁撶畻鏁版嵁鍙樺寲

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