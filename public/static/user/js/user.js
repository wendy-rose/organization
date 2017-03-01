$(function(){
	$('#avatar').on('change', function(){
		$('#upload').ajaxSubmit(function(data){
            console.log('/uploads/' + data.data.url);
            $('#test').attr('url', data.data.url);
			$("input[name='avatar_url']").val('/uploads/' + data.data.url);
			img = $('<img scr="' +"__ROOT__" + '/uploads/' + data.data.url + '">');
			$('.avatar-wrapper').empty().html(img);
			$('.avatar-preview').empty().html(img);
			img.cropper({
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