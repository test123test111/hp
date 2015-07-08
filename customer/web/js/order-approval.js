$(function(){
    // delete new address
    $(".agree-approval").live("click",function(event){
        event.stopPropagation();
        var id = $(this).data('id');
        var detail_id = $(this).data('detail-id');
        agreeApproval(id,detail_id);
    });
    $(".agree-fee").live("click",function(event){
        event.stopPropagation();
        var id = $(this).data('id');
        agreeFee(id);
    });
    $(".disagree-fee").live("click",function(event){
        event.stopPropagation();
        var id = $(this).data('id');
        disagreeFee(id);
    });
    $(".disagree-approval").live("click",function(event){
        event.stopPropagation();
        var id = $(this).data('id');
        var detail_id = $(this).data('detail-id');
        disagreeApproval(id,detail_id);
    });
    $(".agree-budget-approval").live("click",function(event){
        event.stopPropagation();
        var id = $(this).data('id');
        agreeBudgetApproval(id);
    });
    $(".disagree-budget-approval").live("click",function(event){
        event.stopPropagation();
        var id = $(this).data('id');
        disagreeBudgetApproval(id);
    });
});
function agreeApproval(id,detail_id){
    safeCode = $('meta[name=csrf-token]');
    $.layer({
        shade: [1],
        // area: ['auto','auto'],
        dialog: {
            msg: '确认通过审批吗？',
            btns: 2,                    
            type: 4,
            btn: ['确认','取消'],
            shade: [0.5, '#000'],
            area: ['900', '380'],
            yes: function(){
                var delete_url = "/order/approvalmaterial";
                $.ajax({
                    url:delete_url,
                    dataType:"json",
                    type:"POST",
                    data:{"id":id,'detail_id':detail_id,'_csrf':safeCode.attr('content')},
                    success:function(json){
                        if(json == 0){
                            $("#spprovalStatus"+detail_id).html("已同意审批人审批");
                            $("#oSt").html("审核通过");
                        }else if(json == 1){
                            $("#spprovalStatus"+detail_id).html("已同意审批人审批");
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
function agreeBudgetApproval(id){
    safeCode = $('meta[name=csrf-token]');
    $.layer({
        shade: [1],
        // area: ['auto','auto'],
        dialog: {
            msg: '确认通过审批吗？',
            btns: 2,                    
            type: 4,
            btn: ['确认','取消'],
            shade: [0.5, '#000'],
            area: ['900', '380'],
            yes: function(){
                var delete_url = "/order/agreeapprovalbudget";
                $.ajax({
                    url:delete_url,
                    dataType:"json",
                    type:"POST",
                    data:{"id":id,'_csrf':safeCode.attr('content')},
                    success:function(json){
                        if(json == 0){
                            $("#spprovalBudget").html("已同意审批人审批");
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
function disagreeBudgetApproval(id){
    safeCode = $('meta[name=csrf-token]');
    $.layer({
        shade: [1],
        // area: ['auto','auto'],
        dialog: {
            msg: '确认驳回审批吗？',
            btns: 2,                    
            type: 4,
            btn: ['确认','取消'],
            shade: [0.5, '#000'],
            area: ['900', '380'],
            yes: function(){
                var delete_url = "/order/disagreeapprovalbudget";
                $.ajax({
                    url:delete_url,
                    dataType:"json",
                    type:"POST",
                    data:{"id":id,'_csrf':safeCode.attr('content')},
                    success:function(json){
                        if(json == 0){
                            $("#spprovalBudget").html("已驳回审批人审批");
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
function agreeFee(id){
    safeCode = $('meta[name=csrf-token]');
    $.layer({
        shade: [1],
        // area: ['auto','auto'],
        dialog: {
            msg: '确认通过预算审批吗？',
            btns: 2,                    
            type: 4,
            btn: ['确认','取消'],
            shade: [0.5, '#000'],
            area: ['900', '380'],
            yes: function(){
                var delete_url = "/order/approvalfee";
                $.ajax({
                    url:delete_url,
                    dataType:"json",
                    type:"POST",
                    data:{"id":id,'_csrf':safeCode.attr('content')},
                    success:function(json){
                        if(json == 0){
                            $("#spprovalfeeStatus").html("预算审批已通过");
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
function disagreeFee(id){
    safeCode = $('meta[name=csrf-token]');
    $.layer({
        shade: [1],
        // area: ['auto','auto'],
        dialog: {
            msg: '确认驳回该订单预算审批吗？',
            btns: 2,                    
            type: 4,
            btn: ['确认','取消'],
            shade: [0.5, '#000'],
            area: ['900', '380'],
            yes: function(){
                var delete_url = "/order/disagreefee";
                $.ajax({
                    url:delete_url,
                    dataType:"json",
                    type:"POST",
                    data:{"id":id,'_csrf':safeCode.attr('content')},
                    success:function(json){
                        if(json == 0){
                            $("#spprovalfeeStatus").html("已驳回预算审批");
                        }
                    }
                });
                layer.closeAll();
            }
        }
    });
}
function disagreeApproval(id,detail_id){
    safeCode = $('meta[name=csrf-token]');
    $.layer({
        shade: [1],
        // area: ['auto','auto'],
        dialog: {
            msg: '确认驳回该订单物料审批吗？',
            btns: 2,                    
            type: 4,
            btn: ['确认','取消'],
            shade: [0.5, '#000'],
            area: ['900', '380'],
            yes: function(){
                var delete_url = "/order/disagreeapproval";
                $.ajax({
                    url:delete_url,
                    dataType:"json",
                    type:"POST",
                    data:{"id":id,"detail_id":detail_id,'_csrf':safeCode.attr('content')},
                    success:function(json){
                        if(json == 0){
                            $("#spprovalStatus"+detail_id).html("已驳回物料审批");
                        }
                    }
                });
                layer.closeAll();
            }
        }
    });
}