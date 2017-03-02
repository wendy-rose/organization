$(function(){
	$('#avatar').on('change', function(){
		$('#upload').ajaxSubmit(function(data){
            $('#test').attr('src', '/uploads/' + data.data.url);
			$('#test').cropper({
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
});