$(function() {
    toastr.options = {
        "timeOut": "1000"
    };

    var uploadOption = {
        action: '/corp/index/UploadCorp',
        name: 'corpImg',
        autoSubmit: true,
        onComplete: function(file, response) {
            response = JSON.parse(response);
            if (response.success) {
                var image = $('<img src="' + response.thumb + '">');
                $('#corpPicture').empty().html(image);
                $('input[name=corppic]').val(response.thumb);
            } else {
                toastr.warning(response.msg);
            }
        }
    };
    var oAjaxUpload = new AjaxUpload('#corpPicture', uploadOption);
    var attachids = new Array();
    var attach = $('input[name=attach]');
    $('#uploadAttach').uploadify({
        buttonText: '添加附件',
        fileObjName: 'uploadAttach',
        successTimeout: 0,
        removeTimeout: 0,
        width: 80,
        queueID: 'queue',
        fileSizeLimit: '2048KB',
        method: 'post',
        swf: '/static/lib/css/uploadify.swf',
        uploader: '/corp/index/UploadAttach',
        auto: true,
        onUploadSuccess: function(file, data, reponse) {
            if (reponse) {
                data = JSON.parse(data);
                attachFile = data.data;
                attachids.push(attachFile.attachid);
                attach.val(attachids.join(','));
                $('#uploadContent').append(uploadContent(attachFile));
            } else {
                console.log(reponse);
            }
        },
    });

    function uploadContent(data) {
        content = '<div id="attach' + data.attachid + '" class="attachContent"><div>' +
            '<img src="' + data.icon + '"><label class="text-center"><a href="' + data.path + '">' + data.name + '</a></label></div>' +
            '<span class="glyphicon glyphicon-trash pull-right" aria-hidden="true" onclick="deleteAttach(' + data.attachid + ')"></span></div>';
        return content;
    }

    $('#addCorp').click(function() {
        $('#makeCorp').ajaxSubmit(function(response) {
            if (response.success) {
                toastr.success(response.msg);
                window.location.href = '/corp/index/my';
            } else {
                toastr.error(reponse.msg);
            }
        });
    });

});

Array.prototype.removeByValue = function(val) {
    for (var i = 0; i < this.length; i++) {
        if (this[i] == val) {
            this.splice(i, 1);
            break;
        }
    }
};

function deleteAttach(attachid) {
    $.post('/corp/index/deleteAttach', { attachid: attachid }, function(data) {
        if (data.success) {
            attachids = $('input[name=corppic]').val();
            attachIdsArr = attachids.split(",");
            attachIdsArr.removeByValue(attachid);
            attachidsStr = (attachIdsArr.length == 0) ? '' : attachIdsArr.join(',');
            $('input[name=corppic]').val(attachidsStr);
            $('#attach' + attachid).remove();
        }
    });
}
