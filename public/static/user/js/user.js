$(function(){
	//上传图片
	$('#avatar').on('change', function(){
		$('#upload').ajaxSubmit(function(data){
			$('#avatar_url').val('/uploads/' + data.data.url);
			var image = $('#image');
            image.attr('src', '/uploads/' + data.data.url);
			image.cropper({
				spectRatio: 1,
				preview: $('.avatar-preview').selector,
				crop: function(e){
					var json = [
                        '{"x":' + e.x,
                        '"y":' + e.y,
                        '"height":' + e.height,
                        '"width":' + e.width,
                        '"rotate":' + e.rotate + '}'
                    ].join();
                    $("input[name=avatar_data]").val(json);
				}
			});
		});
	});

	//裁剪上传
	$('#uploadAvatar').click(function() {
		$('formAvatar').ajaxSubmit(function(data){
			
		});
	});
});