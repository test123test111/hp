;
(function(window, $) {

	bind();

	function bind() {


		$(document).on("click","#addList",function(){
			var len = $("#blockArea .field-block").length,
				num = len + 1,

				$fieldBlock = $('<div class="field-block col-xs-12"></div>'),
				$serial = $('<div class="serial col-xs-2"><div class="list-num">'+ num +'</div><a id="deleteList" href="#">删除</a></div>'),
				$fieldContent =$('<ul class="field-content col-xs-5"><li><span>字段名称</span><input name="OpPositionDetail['+ len +'][name]" type="text" class="input-xlarge"  placeholder=""></li><li><span>参数名称</span><input name="OpPositionDetail['+ len +'][column_name]" type="text" class="input-xlarge" id="form-field-5" placeholder=""></li><li><span>是否需要图片</span><label><input name="OpPositionDetail['+ len +'][need_image]" class="need-img ace ace-switch ace-switch-sm ace-switch-7" value="0" type="checkbox" /><span class="lbl"></span></label></li><li><span>是否需要文字</span><label><input name="OpPositionDetail['+ len +'][need_text]" value="0" class="need-text ace ace-switch ace-switch-sm ace-switch-7" type="checkbox" /><span class="lbl"></span></label></li><li><span>备注</span><input name="OpPositionDetail['+ len +'][desc]" type="text" class="input-xlarge" id="form-field-5" placeholder=""></li></ul>'),
				$num= $('<div class="num col-xs-4"><span>x</span><div class="input-box"><input name="OpPositionDetail['+ len +'][num]" type="text" class="input-sm" id="form-field-5" value="1"><div>数量</div></div></div>')
				
			
			$fieldBlock.append($serial);
			$fieldBlock.append($fieldContent);
			$fieldBlock.append($num);
			$("#blockArea").append($fieldBlock);

		}).on("click","#deleteList",function(){
			var len = $("#blockArea .field-block").length;
			$(this).parents(".field-block").remove();
			$(".list-num").each(function(index,elem){
				var num = index + 1;
				$(this).html(num);
			});
		}).on("click",".need-img,.need-text",function(){
			
			if($(this).val() == 0){
				$(this).val(1);
			}else{
				$(this).val(0);
			}
		}).on("click","#uploadImg",function(){
			
			$("#imageNum").val(0);

		}).on("change","#upload_file",function(){
			
			if($(this).val() == ""){
				$("#linkImg").find("em").html("未选择任何文件");
			}else{
				$("#linkImg").find("em").html($(this).val());
			}
			$("#linkImg").css("color","#4c8fbd");
		});
			
	}

})(window, jQuery);