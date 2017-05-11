$(function() {
     toastr.options = {
        "timeOut": "1000"
    };

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

    var uploadOption = {
        action: '/corp/index/UploadCorp',
        name: 'corpImg',
        autoSubmit: true,
        onComplete: function(file, response) {
            response = JSON.parse(response);
            $('input[name = actpic]').val(response.thumb);
            toastr.success("上传成功");
        }
    };

    var oAjaxUpload = new AjaxUpload('#corpImg', uploadOption);

    $('input[name="starttime"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            locale: {
                format: 'YYYY/MM/DD',
                applyLabel: '确定',
                cancelLabel: '取消'
            }
        },
        function(start, end, label) {
            $('input[name="starttime"]').val(start.format('YYYY/MM/DD'));
        });

    $('input[name="endtime"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            locale: {
                format: 'YYYY/MM/DD',
                applyLabel: '确定',
                cancelLabel: '取消'
            }
        },
        function(start, end, label) {
            $('input[name="endtime"]').val(start.format('YYYY/MM/DD'));
        });

    $('input[name="begin"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            locale: {
                format: 'YYYY/MM/DD',
                applyLabel: '确定',
                cancelLabel: '取消'
            }
        },
        function(start, end, label) {
            $('input[name="begin"]').val(start.format('YYYY/MM/DD'));
        });

    $('input[name="end"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            locale: {
                format: 'YYYY/MM/DD',
                applyLabel: '确定',
                cancelLabel: '取消'
            }
        },
        function(start, end, label) {
            $('input[name="end"]').val(start.format('YYYY/MM/DD'));
        });

    $('#addActivity').click(function(event) {
        $('#addForm').ajaxSubmit(function(data){
            console.log(data);
        });
        return false;
    });
});
