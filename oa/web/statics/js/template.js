;
(function(window, $) {

	var $template = $('#template');

	bind();

	function bind() {
		$(document).on('click', '#template', function() {
			if( $(this).val() !== $('#password').val() ){
				am.tip('确认密码与密码不同');
			}
		});
	}

	function saveTemplate() {
		$.ajax({
			type: 'POST',
			url: 'u/template',
			dataType: 'json',
			data: loginParam,
			success: function(data) {
				if( !data.errno ){
					window.location.assign('index.html');
				}else{
					am.tip(data.err);
				}
			}
		});
	}

	function getTemplate() {
		$.getJSON('u/shippingAddr/showAddrs',
			function(data) {
				if ( !data.errno ) {
					var source = $("#myaddressTmpl").html();
					var template = Handlebars.compile(source);
					$("#myAddress").html(template(data.rst));
				} else {
					am.tip(data.err);
				}
			});
	}
		
	function getTemplate() {
		$.ajax({
			type: 'GET',
			url: 'u/template',
			dataType: 'json',
			data: loginParam,
			success: function(data) {
				if( !data.errno ){
					
				}else{
					am.tip(data.err);
				}
			}
		});
	}

})(window, Zepto);