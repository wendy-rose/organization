$(function(){
	toastr.options = {
        "timeOut": "1000"
    };

	//判断变量是否为空
	function isNull(value) {
        return (value == "" || value == undefined || value == null) ? true : false;
    }

    //个人信息
    $.get("/user/index/start.html", function(data) {
    	var result = data.data.data;
    	if (data.success){
    		var user = result.user;
    		if (!isNull(user.avatar)){
                $('.user-header img').attr('src', user.avatar);
                $('.image img').attr('src', user.avatar);
			}
			$('.username').text(user.username);
    		$('.creditType').text(user.creditType);
    		$('#ratio').text(user.ratio);
    		$('#sorce').attr('width', user.sorce);
    		$('#experience').text(user.experience);
    		$('#money').text(user.money);
    		$('#donation').text(user.donation);
    		$('#email').val(user.email);
			$('#username').val(user.username);
		}else {
    		toastr.warning(data.msg);
    		window.location = '/user/login/index.html';
		}
    });

	//上传图片
	$('#avatar').on('change', function(){
		$('#upload').ajaxSubmit(function(data){
			$("input[name=avatar_url]").val('/uploads/' + data.data.url);
			var image = $('<img src="' + '/uploads/' + data.data.url + '">');
			$('.avatar-wrapper').empty().html(image);
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
		$('#formAvatar').ajaxSubmit(function(data){
			if (data.success) {
				toastr.success(data.msg);
				$('.user-header img').attr('src', data.data.imgUrl);
				$('.image img').attr('src', data.data.imgUrl);
				$('.avatar-wrapper').empty();
				$('.avatar-preview').empty();
			}else {
				toastr.error(data.msg);
			}
		});
	});

	$('#editSubmit').click(function () {
		$('#editUser').ajaxSubmit(function (data) {
			if (data.success) {
				var user = data.data;
                $('.username').text(user.username);
			}else {
				toastr.error(data.msg);
			}
        });
    });
});