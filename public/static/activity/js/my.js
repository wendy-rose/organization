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
            'status': $('select[name=status]').val(),
            'title': $('#title').val(),
            'time': $('input[type=radio]:checked').val(),
            'page': curr
        };
        $.getJSON('/activity/apply/my', params,
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

    $('select[name=status]').change(function(event) {
        obj.ajaxPage(1);
    });

    $('#search').click(function(event) {
        obj.ajaxPage(1);
    });

    $('input[type=radio]').on('ifChanged', function(event) {
        obj.ajaxPage(1);
    });

    $('#applyActivity').click(function(){
        $('#ajaxApply').ajaxSumbit(function(data){
            if (data.success) {
                $('#myApply').modal('hide');
                obj.success(data.msg);
            }else {
                obj.success("报名失败");
            }
        });
    });

    $('#callApply').click(function(){
        $('#ajaxCall').ajaxSumbit(function(data){
            if (data.success) {
                $('#callApply').modal('hide');
                obj.success(data.msg);
            }else {
                obj.success("催办失败");
            }
        });
    });
});

function apply(aid){
	$.post('/activity/apply/getMyApply', {aid: aid}, function(data, textStatus, xhr) {
        $('#ajaxApply input, #ajaxApply textarea').val();
		$('#applyTemplate').tmpl(data.data).appendTo('#ajaxApply');
        $('#myApply').modal('show');
	});
}

function call(aid, cid){
     $('#callApply').modal('show');
     $('#ajaxCall input[name=cid]').val(cid);
     $('#ajaxCall input[name=aid]').val(aid);
}

function searchReason(aid){
    $('#callApply').modal('show');
    $.post('/activity/apply/getMyApply', {aid: aid}, function(data, textStatus, xhr) {
        $('#reason').val(data.data.reason);
    });
}
