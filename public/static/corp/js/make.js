$(function () {
    toastr.options = {
        "timeOut": "1000"
    };

	var uploadOption = {
		action: '/corp/index/UploadCorp',
		name: 'corpImg',
		autoSubmit: true,
		onComplete: function(file, response){
			response = JSON.parse(response);
			if (response.success) {
				var image = $('<img src="' + response.thumb + '">');
	            $('#corpPicture').empty().html(image);
	            $('input[name=corppic]').val(response.thumb);
			}else {
				toastr.warning(response.msg);
			}
		}
	};
	var oAjaxUpload = new AjaxUpload('#corpPicture', uploadOption);

	$('#uploadAttach').uploadify({
		'buttonText':'添加附件',
		'fileObjName':'uploadAttach',
		'width':80,
		'queueID':'queue',
		'fileSizeLimit':'2048KB',
		'method':'post',
		'swf':'/static/lib/css/uploadify.swf',
		'uploader':'/corp/index/UploadAttach',
		'auto':true,
		'onUploadSuccess':function(file, data, reponse){
			
		}
	});
});
