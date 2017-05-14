
var ue;

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
    var ueOption = {
        serverUrl: '/index/index/ue',
        toolbars: [
            ['source', 'undo', 'redo', '|', 'fontfamily', 'fontsize', 'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', 'justifyleft', 'justifyright', 'justifycenter', 'justifyjustify', 'imagecenter', 'lineheight', 'rowspacingtop', 'rowspacingbottom', 'paragraph', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'time', 'date', 'simpleupload', 'insertimage', 'cleardoc']
        ],
        autoHeightEnabled: true,
        autoFloatEnabled: true,
        allowDivTransToP: false,
        initialContent: '编辑社团信息，增加吸引力.......'
    };
    ue = UE.getEditor('description', ueOption);
    //不能直接使用ue的setContent的方法，因为如果初始化过的就不会出发ready，所以会报错，需要使用以下代码
    // ue.addListener("ready", function () {
    //     // editor准备好之后才可以使用
    //     ue.setContent('abc');

    // });
    // $.get('/corp/index/getCorp', function(response) {
    //     var data = response.data;
    //     $('.corpname').html(data.corpname);
    //     $('#userdept').html(data.username);
    //     $('.user-panel img').attr("src", data.corppic);
    //     ue.addListener("ready", function(){
    //         ue.setContent(data.description);
    //     });
    // });
});
