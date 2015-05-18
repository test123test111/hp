;
(function(window, $) {
	var filesInput = $('#filesInput').get(0),
		$avatar = $("#avatar"),
		$mask = $("#mask"),
		canvas = document.getElementById('canvas'),
		ctx = canvas.getContext('2d'),
		imgData = '';

	if (filesInput) {
		filesInput.addEventListener("change",uploadFile, false);
	}

	function uploadFile(e) {	
	    var reader = new FileReader();

		reader.onload = function(event) {
			var img = new Image();
	        img.onload = function(){
	        	img.width = 200;
	        	img.height = 200;
	            ctx.drawImage(img,0,0,200,200);
	            imgData = canvas.toDataURL("image/jpeg", 1);      
	            //console.info(imgData);
	            
	            $mask.show();
	            $.ajax({
					type: 'POST',
					url: 's/user/uploadHead',
					dataType: 'json',
					data: {
						"avatar": imgData
					},
					success: function(data) {
						if( data.errno == 0 ){
							var avatarPic = data.rst.pic;
							$avatar.attr('src', avatarPic);
							sessionStorage.setItem('avatar',avatarPic);
						}else{
							am.tip(data.err);
						}
					},
					complete: function() {
						$mask.height(0);
					},
					error: function() {
						$avatar.parent().addClass('fail');
					},
				});
	        }
	        img.src = event.target.result;
		};

		// Read in the image file as a data URL.
		reader.readAsDataURL(e.target.files[0]);	

	}

})(window, Zepto);