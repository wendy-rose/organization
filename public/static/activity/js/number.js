var obj = {};

$(function() {

    $.get('/corp/index/getCorp', function(response) {
        var data = response.data;
        $('.corpname').html(data.corpname);
        $('#userdept').html(data.username);
        $('.user-panel img').attr("src", data.corppic);
    });
    
    toastr.options = {
        "timeOut": "1000"
    };

    $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
    });

    obj.getParam = function getUrlParam(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
        var r = window.location.search.substr(1).match(reg); //匹配目标参数
        if (r != null) return unescape(r[2]);
        return null; //返回参数值
    }

    obj.ajaxPage = function(curr) {
        var params = {
            'page': curr,
            'aid': obj.getParam('aid'),
            'status': $('#status').val()
        };
        $.getJSON('/activity/activity/number', params,
            function(res) {
                var numberData = res.data;
                $("#number").find("tr").remove();
                $('#numberTemplate').tmpl(numberData).appendTo('#number');
                laypage({
                    cont: 'page',
                    pages: res.count,
                    curr: res.page,
                    skin: '#5bc0de',
                    jump: function(objPage, first) {
                        if (!first) {
                            obj.ajaxPage(objPage.curr);
                        }
                    }
                });
            }
        );
    }

    obj.ajaxPage(1);

    $('#status').change(function(event) {
        obj.ajaxPage(1);
    });

    $('#checkAll').on('ifChecked', function(event) {
        $('input[name="id"]').attr("checked", true);
    });

    $('#checkAll').on('ifUnchecked', function(event) {
        $('input[name="id"]').attr("checked", false);
    });

    $('#pass').click(function(event) {
        var value = [];
        $('input[name="id"]:checked').each(function(index, el) {
            if ($(this).value != 0) {
                value.push($(this).val());
            }
        });
        if (value.length == 0) {
            toastr.warning('至少选择一项');
            return false;
        }
        $.post('/activity/apply/pass', { id: value.join(",") }, function(data, textStatus, xhr) {
            if (data.success) {
                toastr.success(data.msg);
                obj.ajaxPage(1);
            } else {
                toastr.error('操作失败');
            }
        });
        return false;
    });

    $('#back').click(function(event) {
        var value = [];
        $('input[name="id"]:checked').each(function(index, el) {
            if ($(this).value != 0) {
                value.push($(this).val());
            }
        });
        if (value.length == 0) {
            toastr.warning('至少选择一项');
            return false;
        }
        $('#myModal').modal('toggle');

        return false;
    });

    $('#backApply').click(function(event) {
        var value = [];
        $('input[name="id"]:checked').each(function(index, el) {
            if ($(this).value != 0) {
                value.push($(this).val());
            }
        });
        var reason = $('#reason').val();
        if (reason == "") {
            toastr.warning('退回理由不能为空');
            return false;
        }
        $.post('/activity/apply/back', { id: value.join(","), reason: reason }, function(data, textStatus, xhr) {
            if (data.success) {
                toastr.success(data.msg);
                $('#myModal').modal('hide');
                obj.ajaxPage(1);
            } else {
                toastr.error('操作失败');
            }
        });
    });
});
