$(function(){
        // 商品数量减少
        $(".cart_list .increase").click(function(){
                var cart_id = $(this).attr("data-cart-id");
        var cur_qty = parseInt($("#cart_"+cart_id).find(".amount_input").val());
        if(cur_qty > 1){
            $("#cart_"+cart_id).find(".amount_input").val(cur_qty -1); 
        }
        error_stock(cart_id); // is show error
    });

        // 商品数量添加
    $(".cart_list .decrease").click(function(){
        var cart_id = $(this).attr("data-cart-id");
        var cur_qty = parseInt($("#cart_"+cart_id).find(".amount_input").val());
        if(cur_qty < 99){
            $("#cart_"+cart_id).find(".amount_input").val(cur_qty +1); 
        }
        error_stock(cart_id); // is show error
    });

    $(".amount_input").bind('keyup',function(){
        var cart_id = $(this).attr("data-cart-id");
        var cur_qty = parseInt($(this).val());
        if(!/^[1-9][0-9]*$/.test(cur_qty)){
            $(this).val(1);
        }
        error_stock(cart_id); // is show error
    });

    $(".cart_list li .check").click(function(){
        $(this).toggleClass("selected");
        if($(".cart_list").find("li").length == $(".cart_list li").find("span.selected").length){
            $(".cart_list .cart_tt .check").addClass("selected");
        }else{
            $(".cart_list .cart_tt .check").removeClass("selected");
        }
    });

    $(".cart_list .cart_tt .check").click(function(){
        if($(this).hasClass("selected")){
            $(".cart_list li .check").removeClass("selected");
        }else{
            $(".cart_list li .check").addClass("selected");
        }
        $(this).toggleClass("selected");
    });

});

// totalNumber
function totalNumber(){
    var total_num = $("li .cart_item").length;
    $("#selectedCount").text(total_num);
}

// show stock error
function error_stock(id){
    var cur_qty = parseInt($("#cart_"+id).find(".amount_input").val());
    var cur_stock = parseInt($("#cart_"+id).attr("data-stock"));
    if(cur_qty > cur_stock){
        $("#cart_"+id).find(".error_stock").show();
        $("#cart_"+id).find(".amount_input").val(cur_stock);
    }else{
        $("#cart_"+id).find(".error_stock").hide(); 
    }
}


// delete cart 
function deleteCart(cart_id){
    $.layer({
        shade: [1],
        area: ['auto','auto'],
        dialog: {
            msg: '确认从购物车删除此商品吗？',
            btns: 2,                    
            type: 4,
            btn: ['确认','取消'],
            yes: function(){
                var delete_url = "/cart/delete";
                $.ajax({
                    url:delete_url,
                    dataType:"json",
                    type:"POST",
                    data:{"id":cart_id},
                    success:function(json){
                        if(json == 1){
                            $("#cart_" + cart_id).parent().slideUp(300,function(){
                                $(this).remove();
                                totalPrice();
                                totalNumber();
                            });
                        }
                    }
                });
                layer.closeAll();
            }
        }
    });  
}

// cart submit
$(function(){
    $("#buy_btn").click(function(){
        if($(".cart_list li span.selected").length > 0){
            var first_cart = $(".cart_list li span.selected").eq(0).attr("data-storeroom-id");
            var issame = true;
            $.each($(".cart_list li span.selected"),function(){
                if($(this).attr("data-storeroom-id") != first_cart){
                    issame = false;
                    return false;
                }
            });
            if(!issame){
                alert("库房必须一致！");
            }else{
                cartSubmit();
            }
        }else{
            alert("请选择下订单的物料!");return false;
        }
    });
});

function cartSubmit(){
    var goods_data = {"items":[]};
    safeCode = $('meta[name=csrf-token]');
    $.each($(".cart_list li"),function(i){
        if($(this).find(".check").hasClass("selected")){
            var cart_id = $(".cart_list li").eq(i).attr("data-cart-id");
            var storeroom_id = $(".cart_list li").eq(i).attr("data-storeroom");
            var quantity = $(".amount_input").eq(i).val();
            var one_info = {"cart_id":cart_id,"quantity":quantity};
            goods_data["items"].push(one_info);
        }
    });

    var html = "";
    $.each(goods_data['items'],function(i){
        html += '<input type="hidden" name="items['+i+'][cart_id]" value="'+ goods_data["items"][i]["cart_id"]+'" />';
        html += '<input type="hidden" name="items['+i+'][quantity]" value="'+ goods_data["items"][i]["quantity"]+'" />';
        html += '<input type="hidden" name="_csrf" value="'+ safeCode.attr('content') +'" />';
    });
    $("#cartForm").html(html);
    $("#cartForm").submit();
}