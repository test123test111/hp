$(function(){
    // edit address
    $(".edit_link").live("click",function(event){
        event.stopPropagation();
        var id = $(this).attr("data-id");
        editBudget(id);
    });
});
// edit address
function editBudget(id){
    var addr_url = "/owner/budget";
    safeCode = $('meta[name=csrf-token]');
    $.ajax({
        url:addr_url,
        dataType:"html",
        type:"POST",
        data:{"id":id,'_csrf':safeCode.attr('content')},
        success:function(json){
            $.layer({
                type: 1,
                shade: [0.5, '#000'],
                area: ['500', '150'],
                title: '调整预算',
                btns: 2,
                btn: ['确定', '取消'],
                border: [5, 0.2, '#000'],
                yes: function(){
                    postAddressData();
                },
                page: {html:json}
            });
            doProvince();
        }
    });
}

function postAddressData(){
    var owner_id = $("#budgetId").val();
    var price = $("#budgetPrice").val();
    var safeCode = $('meta[name=csrf-token]');
    var addr_url = '/owner/updatebudget'
    $.ajax({
        url:addr_url,
        dataType:"json",
        type:"POST",
        data:{"id":owner_id,'price':price,'_csrf':safeCode.attr('content')},
        success:function(json){
            if(json == 0){
                alert("设置成功!");
                layer.closeAll();
                window.location.reload();
            }
        }
    });
}