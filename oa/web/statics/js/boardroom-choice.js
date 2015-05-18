

(function (window, $) {
    function getUrlParam(sParam)
    {
        var sPageURL = window.location.search.substring(1);
        var sURLVariables = sPageURL.split('&');
        for (var i = 0; i < sURLVariables.length; i++)
        {
            var sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == sParam)
            {
                return sParameterName[1];
            }
        }
    }

    bind();

    function bind() {
        $(document).on("click", "#bookingBtn", function () {
            var room_id = getUrlParam('room_id');
            var user_id = getUrlParam('user_id');          
            var day = $('#day_range').val();
            var start_time = $('#start_time_range').val();
            var use_second = $('#use_second').val();
            $.post('/wboardroom/booking', 
                {room_id: room_id, 
                user_id: user_id, day: day,start_time:start_time, use_second:use_second} 
                ,function(response) {
                    if (response.errno > 0) {
                        alert(response.msg);
                    } else {
                        window.location.href = '/statics/boardroom-sucess.html?user_id='+user_id;
                    }
            }, 'json');
        });
    }
    var user_id = getUrlParam('user_id'); 
    var room_id = getUrlParam('room_id');
    $.get('/wboardroom/choice', {user_id: user_id, room_id: room_id}, function (response) {
        var day_range_html = '';
        $.each(response.day_range, function(key, item) {
             day_range_html += '<option>'+item+'</option>';
        });
        $('#day_range').append(day_range_html);
        
        var time_range_html = '';
        $.each(response.time_range, function(key, item) {
            time_range_html += '<option>'+item+'</option>';
        });
        $('#start_time_range').append(time_range_html);

        var records_html = '';
        $.each(response.records, function(key, item) {
           records_html += '<li class=\"clearfix\"><p>'+item.time_range+"</p>"+	
                            "<span class=\"gray-font\">"+item.user_id+"</span></li>";
        });
        $('#records').append(records_html);
       
    }, 'json');

})(window, Zepto);
