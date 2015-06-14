// 商城数量
$(function(){
    $(".increase").click(function(){
        var cur_qty = parseInt($(".amount_input").val());
        if(cur_qty > 1){
            $(".amount_input").val(cur_qty -1);
        }
        error_stock(cur_qty);
    });

    $(".decrease").click(function(){
        var cur_qty = parseInt($(".amount_input").val());
        if(cur_qty < 99){
            $(".amount_input").val(cur_qty + 1);
        }
        error_stock(cur_qty);
    });

    $(".amount_input").bind('keyup',function(){
        var cur_qty = parseInt($(".amount_input").val());
        if(!/^[1-9][0-9]*$/.test(cur_qty)){
            $(this).val(1);
        }
        error_stock(cur_qty);
    });

    $(".choose span.item").click(function(){
        $(".choose span.item").removeClass("selected");
        $(this).addClass("selected");
        var stock = $(this).attr("data-stock");
        $("#mall_stock").text(stock);
        $(".choose p").hide(); 
    }); 
});

function error_stock(){
    var cur_qty = parseInt($(".amount_input").val());
    var cur_stock = parseInt($("#mall_stock").text());
    if(cur_qty > cur_stock){
        $(".error_stock").show();
    }else{
       $(".error_stock").hide(); 
    }
}

// add to cart
function buyCart(){
    var storeroom_id = $("#storeroom_id").text();
    var material_id = $("#material_id").text();
    var quantity = $(".amount_input").val();
    var stock = parseInt($("#mall_stock").text());
    var cart_url = "/cart/add";
    var safeCode = $('meta[name=csrf-token]');
    var cart_data = {"material_id":material_id,"_csrf":safeCode.attr('content'),"storeroom_id":storeroom_id,"quantity":quantity};
    if(quantity > stock){
        $(".error_stock").show();
        return false;
    }else{
        $(".error_stock").hide();
    }
    $.ajax({
        url:cart_url,
        dataType:"json",
        type:"POST",
        data:cart_data,
        success:function(json){
            if(json.errno == 0){
                $(".cart_tip").fadeIn();
                window.setTimeout(hideTip,3000);
            }else{
                alert(json.err);
            }
            
        }
    });
}

function hideTip(){
    $(".cart_tip").fadeOut();
}
