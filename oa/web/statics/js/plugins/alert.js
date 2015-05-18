;(function(window, $) {

	var $markbg = $('#markbg');

	$(document).on('click', '.cancel-alert', function(event) {
		event.preventDefault();
		$(this).parent().hide();
		$markbg.hide();
	});

	$.extend(am, {
		confrim: function(argument) {
			var $confrim = $('#confrim');
			$markbg.show();
			$confrim.show().find('p').html(argument);
		},
		choice: function(content) {
			var $choice = $('#choice');
			$markbg.show();
			$choice.show().find('p').html(content);
		}
	});

})(window, Zepto);