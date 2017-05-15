var obj = {};

$(function() {
	 toastr.options = {
        "timeOut": "1000"
    };

    $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
    });
    
    
    obj.ajaxPage = function(curr) {
        var params = {
            'type': $('select[name=type]').val(),
            'likes': $('select[name=likes]').val(),
            'title': $('#title').val(),
            'status': $('input[type=radio]:checked').val(),
            'page': curr
        };
        $.getJSON('/activity/activity/search', params,
            function(res) {
                var searchData = res.data;
                $("#searchContent").find("li").remove();
                $('#searchTemplate').tmpl(searchData).appendTo('#searchContent');
                laypage({
                    cont: 'searchPage',
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

    obj.success = function(msg){
    	toastr.success(msg);
    }

    obj.error = function(msg){
    	toastr.error(msg);
    }

    obj.ajaxPage(1);

    $('select[name=type],select[name=likes]').change(function(event) {
        obj.ajaxPage(1);
    });

    $('#search').click(function(event) {
        obj.ajaxPage(1);
    });

    $('input[type=radio]').on('ifChanged', function(event) {
        obj.ajaxPage(1);
    });

     $.get('/corp/index/getCorp', function(response) {
        var data = response.data;
        $('.corpname').html(data.corpname);
        $('#userdept').html(data.username);
        $('.user-panel img').attr("src", data.corppic);
    });

});

function delActivity(aid){
	$.post('/activity/activity/del', {aid: aid}, function(data, textStatus, xhr) {
		if (data.success) {
			obj.success(data.msg);
			obj.ajaxPage(1);
		}else {
			obj.error('删除失败');
		}
	});
}
