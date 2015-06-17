$(function(){
    // select address
    $("#address_area li").live("mouseover",function(){
        $(".addr_list li").removeClass("cur");
        if(!$(this).hasClass("more")){
            $(this).addClass("cur");
        }
    });

    // click address
    $("#address_area li").live("click",function(){
        $(".addr_list li").removeClass("selected");
        $(".addr_list li").find("input[type='radio']").removeAttr("checked");
        if(!$(this).hasClass("more")){
            $(this).addClass("selected");
            $(this).find("input").attr("checked",true);  
        }
    });

    // select time
    $("#delivery_time li").click(function(){
        $("#delivery_time li").removeClass("selected");
        $(this).addClass("selected");
    });

    // select safe
    $("#safe_label li").click(function(){
        var data_show = $(this).attr("data-show");
        $("#safe_label li").removeClass("selected");
        $(this).addClass("selected");
        if(data_show == 1){
            $("#safe_price").show();
        }else{
            $("#safe_price").hide();
        }
    });
    // select time
    $("#delivery_style li").click(function(){
        $("#delivery_style li").removeClass("selected");
        $(this).addClass("selected");
    });

    // buy submit
    $("#buy_btn").click(function(){
        if(!$(this).hasClass("btn_order_undefined")){
            if($("#orderType").val() == 0){
                if($("#address_area li").length == 0){
                    alert("请输入收件人地址");
                    return false;
                }
            }
            // else{
            //     $("#buyForm").submit();
            // }
            var storeroom = $("#storeType").val();
            var orderType = $("#orderType").val();
            if(storeroom != 1 && orderType == 1){
                alert("只能从中央库调配物资到平台库");
                return false;
            }
            var to_city = $("#addrArea").html();
            var a = document.getElementsByName("Order[transport_type]");
            var n;
            for(var i=0;i<a.length;i++) {
                if(a[i].checked) {n = a[i].value;break;}
            }
            checkShipComplete(storeroom,to_city,orderType,n);
            // if($("#shipResult").val() == 0){
            //     alert("目的地不支持您选择的运输方式");
            //     return false;
            // }
        }
        
    });

    // edit address
    $(".edit_link").live("click",function(event){
        event.stopPropagation();
        var id = $(this).attr("data-id");
        editAddress(id);
    });

    // add new address
    $(".addnew").live("click",function(){
        editAddress("");
    });

    // delete new address
    $(".del_link").live("click",function(event){
        event.stopPropagation();
        var id = $(this).attr("data-id");
        deleteAddress(id);
    });

    doProvince();

    $(".type_tab li").click(function(){
        $(".type_tab li").removeClass("cur");
        $(this).addClass("cur");
        $("#orderType").val($(this).data("type"));
        $(".add_content").show();
        $(".add_content").eq($(this).index()).hide();
    });

    
});
function checkShipComplete(storeroom,to_city,orderType,transportType){
    var check_url = '/order/checkshipmethod';
    $.ajax({
        url:check_url,
        dataType:"json",
        type:"POST",
        data:{"storeroom_id":storeroom,"to_city":to_city,"order_type":orderType,'transport_type':transportType},
        success:function(json){
            if(json == 1){
                $("#buyForm").submit();
            }else{
                 alert("目的地不支持您选择的运输方式");
                 return false;
            }
        }
    });
    
}
// edit address
function editAddress(id){
    var addr_url = "/order/addressdisplay";
    $.ajax({
        url:addr_url,
        dataType:"html",
        type:"POST",
        data:{"id":id},
        success:function(json){
            $.layer({
                type: 1,
                shade: [0.5, '#000'],
                area: ['900', '430'],
                title: '地址',
                btns: 2,
                btn: ['确定', '取消'],
                border: [5, 0.2, '#000'],
                yes: function(){
                    var allow = verifyAddress();
                    if(allow){
                        postAddressData();
                    }else{
                        return false;
                    }
                },
                page: {html:json}
            });
            doProvince();
        }
    });
}

function postAddressData(){
    var addr_id = $("#default_addr_id").val();
    var addr_province = $("#addr_province").find("option:selected").text();
    var addr_city = $("#addr_city").find("option:selected").text();
    var addr_district = $("#addr_district").find("option:selected").text();
    var addr_name = $("#addr_name").val();
    var addr_company = $("#addr_company").val();
    var addr_zip = $("#addr_zip").val();
    var addr_address = $("#addr_address").val();
    var addr_area = $("#addr_area").val();
    var addr_tel = $("#addr_tel").val();
    var addr_ext = $("#addr_ext").val();
    var addr_phone = $("#addr_phone").val();
    var addr_url = "/order/address";
    $.ajax({
        url:addr_url,
        dataType:"html",
        type:"POST",
        data:{"id":addr_id,"Address":{"name":addr_name,'company':addr_company,"phone":addr_phone,"province":addr_province,"city":addr_city,"area":addr_district,"address":addr_address,"zip":addr_zip,"tel":addr_tel,"tel_area_code":addr_area,"addr_ext":addr_ext}},
        success:function(json){
            if(json != 0){
                alert('设置成功');
                window.location.reload();
            }
        }
    });
}

// delete address
function deleteAddress(id){
    $.layer({
        shade: [1],
        area: ['auto','auto'],
        dialog: {
            msg: '确认删除此地址吗？',
            btns: 2,                    
            type: 4,
            btn: ['确认','取消'],
            yes: function(){
                var delete_url = "/order/deleteaddress";
                $.ajax({
                    url:delete_url,
                    dataType:"json",
                    type:"POST",
                    data:{"id":id},
                    success:function(json){
                        if(json != 0){
                            alert('成功删除');
                            window.location.reload();
                        }else{
                            alert("删除地址失败！");
                        }
                    }
                });
                layer.closeAll();
            }
        }
    });
}

// 显示省
function doProvince(){
    var province_options = "";
    var default_province = $("#default_province").val();
    $.each(province,function(i,p){
        if(p["name"] == default_province){
            province_options += "<option value='"+ p["ProID"] +"' selected='selected'>"+ p["name"] +"</option>";
        }else{
            province_options += "<option value='"+ p["ProID"] +"'>"+ p["name"] +"</option>";
        }
    });
    $("#addr_province").append(province_options);
    doCity();
}

// 显示市
function doCity(){
    var selValue = $("#addr_province").val();
    var default_city = $("#default_city").val();
    $("#addr_city option").remove();
    $.each(city,function(i,c){
        if (c.ProID == selValue) {
            if(c["name"] == default_city){
                var city_option = "<option value='"+ c["CityID"] +"' selected='selected'>"+ c["name"] +"</option>";
            }else{
                var city_option = "<option value='"+ c["CityID"] +"'>"+ c["name"] +"</option>";
            }
            $("#addr_city").append(city_option);
        }
    });
    doDistrict();
}

// 显示区县
function doDistrict(){
    var selValue = $("#addr_city").val();
    var default_district = $("#default_district").val();
    $("#addr_district option").remove();
    $.each(district,function(i,d){
        if (d.CityID == selValue) {
            if(d["DisName"] == default_district){
                var district_option = "<option value='"+ d["Id"] +"' selected='selected'>"+ d["DisName"] +"</option>";
            }else{
                var district_option = "<option value='"+ d["Id"] +"'>"+ d["DisName"] +"</option>";
            }
            $("#addr_district").append(district_option);
        }
    });
}
function verifyAddress(){
    var allow = true;
    if($("#address_province").val() == "" || $("#address_city").val() == "" || $("#address_district").val() == ""){
        $("#error_area").show();
        allow = false;
    }else{
        $("#error_area").hide();
    }
    // verify zip
    var reg_zip = /^[0-9]\d{5}$/;
    if(!(reg_zip.test($("#addr_zip").val()))){
        $("#error_zip").show();
        $("#addr_zip").addClass('error');
        allow = false;
    }else{
        $("#error_zip").hide();
        $("#addr_zip").removeClass('error');
    }
    // verify company
    if($.trim($("#addr_company").val()) == ""){
        $("#error_company").show();
        $("#addr_company").addClass('error');
        allow = false;
    }else{
        $("#error_address").hide();
        $("#error_company").removeClass('error');
    }
    // verify address
    if($.trim($("#addr_address").val()) == ""){
        $("#error_address").show();
        $("#addr_address").addClass('error');
        allow = false;
    }else{
        $("#error_address").hide();
        $("#addr_address").removeClass('error');
    }
    // verify name
    if($.trim($("#addr_name").val()) == ""){
        $("#error_name").show();
        $("#addr_name").addClass('error');
        allow = false;
    }else{
        $("#error_name").hide();
        $("#addr_name").removeClass('error');
    }
    // verity tel/phone
    var reg_phone = /^(13[0-9]{9}|15[012356789][0-9]{8}|18[02356789][0-9]{8}|14[57][0-9]{8})$/;
    var reg_area = /^([0-9]{3,6})$/;
    var reg_tel = /^([0-9]{5,10})$/;
    var reg_ext = /^([0-9]{0,6})$/;
    if($.trim($("#addr_area").val()) !="" || $.trim($("#addr_tel").val()) !="" || $.trim($("#addr_ext").val()) !=""){
        if(!(reg_area.test($("#addr_area").val()))){
            $("#error_tel").show().html("请输入3-6位数字的区号！");
            $("#addr_area").addClass('error');
            allow = false;
        }else if(!(reg_tel.test($("#addr_tel").val()))){
            $("#error_tel").show().html("请输入5-10位数字的电话号码！");
            $("#addr_tel").addClass('error');
            $("#addr_area").removeClass('error');
            allow = false;
        }else if(!(reg_ext.test($("#addr_ext").val()))){
            $("#error_tel").show().html("请输入少于6位数字的分机号！");
            $("#addr_ext").addClass('error');
            $("#addr_tel").removeClass('error');
            allow = false;
        }else{
            $("#error_tel").hide();
            $("#addr_area").removeClass('error');
            $("#addr_tel").removeClass('error');
            $("#addr_ext").removeClass('error');
        }
    }else{
        $("#error_tel").hide();
        $("#addr_area").removeClass('error');
        $("#addr_tel").removeClass('error');
        $("#addr_ext").removeClass('error');
        if($.trim($("#addr_phone").val()) == ""){
            $("#error_phone").show();
            $("#addr_phone").addClass('error');
            allow = false;
        }else if(!(reg_phone.test($("#addr_phone").val()))){
            $("#error_phone").show().html("错误的手机号码！");
            $("#addr_phone").addClass('error');
            allow = false;
        }else{
            $("#error_phone").hide();
            $("#addr_phone").removeClass('error');
        }
    }
    return allow;
}