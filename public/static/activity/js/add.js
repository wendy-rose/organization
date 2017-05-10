$(function() {
    var ueOption = {
        serverUrl: '/index/index/ue',
        toolbars: [
            ['source', 'undo', 'redo', '|', 'fontfamily', 'fontsize', 'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', 'justifyleft', 'justifyright', 'justifycenter', 'justifyjustify', 'imagecenter', 'lineheight', 'rowspacingtop', 'rowspacingbottom', 'paragraph', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'time', 'date', 'simpleupload', 'insertimage', 'cleardoc']
        ],
        autoHeightEnabled: true,
        autoFloatEnabled: true,
        allowDivTransToP: false,
        initialContent: '活动详细内容'
    };
    var ue = UE.getEditor('content', ueOption);

    $("#openapply").click(function() {
        var checked = $(this).is(':checked');
        if (checked) {
            $('#begin').attr("disabled", false);
            $('#end').attr("disabled", false);
        } else {
            $('#begin').attr("disabled", true);
            $('#end').attr("disabled", true);
        }
    });
});
