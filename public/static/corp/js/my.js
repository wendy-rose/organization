$(function() {
    toastr.options = {
        "timeOut": "1000"
    };

    $('#rangtime').daterangepicker({
        autoUpdateInput: false,
        locale: {
            format: 'YYYY/MM/DD',
            applyLabel: '确定',
            cancelLabel: '取消'
        }
    }, function(start, end, label) {
        $('#rangtime').val(start.format('YYYY/MM/DD') + '-' + end.format('YYYY/MM/DD'));
    });

    ajaxPage(1);

    function ajaxPage(curr) {
        $.getJSON('/corp/index/my', { page: curr },
            function(res) {
                var corpData = res.data;
                $("#corpList").find("li").remove();
                $('#myCorpList').tmpl(corpData).appendTo('#corpList');
                laypage({
                    cont: 'myCorpPage',
                    pages: res.count,
                    curr: res.page,
                    skin: '#5bc0de',
                    jump: function(obj, first) {
                        if (!first) {
                            ajaxPage(obj.curr);
                        }
                    }
                });
            }
        );
    }
});

function enterCorp(cid) {
    var url = '/corp/index/login?cid=' + cid;
    window.open(url, '_blank');
}

function exitCorp(cid) {
    $.$.post('/corp/index/exitCorp', { cid: cid }, function(data, textStatus, xhr) {
        toastr.success(data.msg);
        ajaxPage(1);
    });
}
