$(function(){
    // delete new address
    $(".send-approval").live("click",function(event){
        event.stopPropagation();
        var id = $(this).data('id');
        sendapproval(id);
    });
    $(".send-budget-approval").live("click",function(event){
        event.stopPropagation();
        var id = $(this).data('id');
        sendbudgetapproval(id);
    });
    $(".send-fee-approval").live("click",function(event){
        event.stopPropagation();
        var id = $(this).data('id');
        sendfeeapproval(id);
    });
    $(".cancel-order").live("click",function(event){
        event.stopPropagation();
        var id = $(this).data('id');
        cancelOrder(id);
    });
});
function sendapproval(id){
    safeCode = $('meta[name=csrf-token]');
    $.layer({
        shade: [1],
        // area: ['auto','auto'],
        dialog: {
            msg: '确认发送审批吗？',
            btns: 2,                    
            type: 4,
            btn: ['确认','取消'],
            shade: [0.5, '#000'],
            area: ['900', '380'],
            yes: function(){
                var delete_url = "/order/sendapproval";
                $.ajax({
                    url:delete_url,
                    dataType:"json",
                    type:"POST",
                    data:{"id":id,'_csrf':safeCode.attr('content')},
                    success:function(json){
                        if(json == 1){
                            $("#spprovalStatus").html("审批申请已发送,请等待审批人审批");
                        }else{
                            alert(json);
                        }
                    }
                });
                layer.closeAll();
            }
        }
    });
}
function sendbudgetapproval(id){
    safeCode = $('meta[name=csrf-token]');
    $.layer({
        shade: [1],
        // area: ['auto','auto'],
        dialog: {
            msg: '确认发送审批吗？',
            btns: 2,                    
            type: 4,
            btn: ['确认','取消'],
            shade: [0.5, '#000'],
            area: ['900', '380'],
            yes: function(){
                var delete_url = "/order/sendbudgetapproval";
                $.ajax({
                    url:delete_url,
                    dataType:"json",
                    type:"POST",
                    data:{"id":id,'_csrf':safeCode.attr('content')},
                    success:function(json){
                        if(json == 1){
                            $("#spprovalBudget").html("审批申请已发送,请等待审批人审批");
                        }else{
                            alert(json);
                        }
                    }
                });
                layer.closeAll();
            }
        }
    });
}
function sendfeeapproval(id){
    safeCode = $('meta[name=csrf-token]');
    $.layer({
        shade: [1],
        // area: ['auto','auto'],
        dialog: {
            msg: '确认发送审批吗？',
            btns: 2,                    
            type: 4,
            btn: ['确认','取消'],
            shade: [0.5, '#000'],
            area: ['900', '380'],
            yes: function(){
                var delete_url = "/order/sendapprovalfee";
                $.ajax({
                    url:delete_url,
                    dataType:"json",
                    type:"POST",
                    data:{"id":id,'_csrf':safeCode.attr('content')},
                    success:function(json){
                        if(json == 1){
                            $("#spprovalfeeStatus").html("费用审批申请已发送,请等待审批人审批");
                        }else{
                            alert(json);
                        }
                    }
                });
                layer.closeAll();
            }
        }
    });
}
function cancelOrder(id){
    safeCode = $('meta[name=csrf-token]');
    $.layer({
        shade: [1],
        // area: ['auto','auto'],
        dialog: {
            msg: '确认取消订单吗？',
            btns: 2,                    
            type: 4,
            btn: ['确认','取消'],
            shade: [0.5, '#000'],
            area: ['900', '380'],
            yes: function(){
                var delete_url = "/order/cancel";
                $.ajax({
                    url:delete_url,
                    dataType:"json",
                    type:"POST",
                    data:{"id":id,'_csrf':safeCode.attr('content')},
                    success:function(json){
                        if(json == 0){
                            window.location.reload();
                        }else if(json == 1){
                            alert("当前订单状态不允许取消");
                        }else if(json == 2){
                            alert("部分申请人已经通过审批,订单当前不可取消！");
                        }
                    }
                });
                layer.closeAll();
            }
        }
    });
}