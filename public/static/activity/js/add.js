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
        action: '/activity/activity/upload',
        name: 'corpImg',
        autoSubmit: true,
        onComplete: function(file, response) {
            response = JSON.parse(response);
            $('input[name = actpic]').val(response.thumb);
            $('#imagename').text(response.imgname);
            toastr.success("上传成功");
        }
    };

    var oAjaxUpload = new AjaxUpload('#corpImg', uploadOption);

    $('input[name="starttime"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            timePicker: true, //显示时间
            timePicker24Hour: true, //24小时制
            locale: {
                format: 'YYYY/MM/DD HH:mm',
                applyLabel: '确定',
                cancelLabel: '取消'
            }
        },
        function(start, end, label) {
            $('input[name="starttime"]').val(start.format('YYYY/MM/DD HH:mm'));
        }
    );

    $('input[name="endtime"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            timePicker: true, //显示时间
            timePicker24Hour: true, //24小时制
            locale: {
                format: 'YYYY/MM/DD HH:mm',
                applyLabel: '确定',
                cancelLabel: '取消'
            }
        },
        function(start, end, label) {
            $('input[name="endtime"]').val(start.format('YYYY/MM/DD HH:mm'));
        }
    );

    $('input[name="begin"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            timePicker: true, //显示时间
            timePicker24Hour: true, //24小时制
            locale: {
                format: 'YYYY/MM/DD HH:mm',
                applyLabel: '确定',
                cancelLabel: '取消'
            }
        },
        function(start, end, label) {
            $('input[name="begin"]').val(start.format('YYYY/MM/DD HH:mm'));
        }
    );

    $('input[name="end"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            timePicker: true, //显示时间
            timePicker24Hour: true, //24小时制
            locale: {
                format: 'YYYY/MM/DD HH:mm',
                applyLabel: '确定',
                cancelLabel: '取消'
            }
        },
        function(start, end, label) {
            $('input[name="end"]').val(start.format('YYYY/MM/DD HH:mm'));
        }
    );

    $('#getAddress').click(function(event) {
        $('#address').val($('#mapAddress').val());
        $('#myMap').modal('hide')
    });

    $("input[type='checkbox']").on('ifChanged', function(event) {
        var checked = $(this).is(':checked');
        if (checked) {
            $('#begin,#end').attr('disabled', false);
            $('#number').attr('disabled', false);
            $('#begin,#end').attr('required', 'required');
        } else {
            $('#begin,#end').attr('disabled', true);
            $('#number').attr('disabled', true);
            $('#number').val("");
            $('#begin,#end').val("");
            $('#begin,#end').removeAttr('required');
        }
    });

    $('#addActivity').click(function(event) {
        $('#addForm').ajaxSubmit(function(data) {
            if (data.success) {
                toastr.success(data.msg);
                window.location.reload();
            }else {
                toastr.warning(data.msg);
            }
        });
        return false;
    });

    $.get('/corp/index/getCorp', function(response) {
        var data = response.data;
        $('.corpname').html(data.corpname);
        $('#userdept').html(data.username);
        $('.user-panel img').attr("src", data.corppic);
    });
});
