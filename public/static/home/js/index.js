var showDialog = {};

$(function() {

    toastr.options = {
        "timeOut": "1000"
    };
    
    showDialog.success = function(msg){
        toastr.success(msg)
    }
    
    function changeState(el) {
        if (el.readOnly) el.checked = el.readOnly = false;
        else if (!el.checked) el.readOnly = el.indeterminate = true;
    }

    ajaxPage(1);

    $('#search-btn,input[name=type],input[name=time]').click(function(event) {
        ajaxPage(1);
    });
    
    function ajaxPage(curr) {
        var type = $('input[name=type]:checked').val();
        var time = $('input[name=time]:checked').val();
        var title = $('input[name=title]').val();
        $.getJSON('/activity/activity/index', {
                page: curr,
                type: type,
                time: time,
                title: title
            },
            function(res) {
                var corpData = res.data;
                $("#activityList").find("li").remove();
                $('#actTemplate').tmpl(corpData).appendTo('#activityList');
                laypage({
                    cont: 'page',
                    pages: res.allpage,
                    curr: res.nowpage,
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

    $('#applyActivity').click(function(event) {
        $('#ajaxApply').ajaxSubmit(function(data){
            if (data.success) {
                toastr.success(data.msg);
                $('#myModal').modal('hide');
            }
        });
    });

});

function addLike(aid) {
    $.post('/activity/activity/like', { aid: aid }, function(data) {
        if (data.success) {
            $('#like').attr('src', '/static/lib/img/iconst4.png');
            $('#like').attr('onclick', 'resetLike(+'+aid+')');
            $('#likeCount').text(data.data.like);
        }
    });
}

function resetLike(aid) {
    $.post('/activity/activity/resetLike', { aid: aid }, function(data) {
        if (data.success) {
            $('#like').attr('src', '/static/lib/img/iconst3.png');
            $('#like').attr('onclick', 'addLike(+'+aid+')');
            $('#likeCount').text(data.data.like);
        }
    });
}

function apply(aid, cid){
    $.post('/activity/activity/isApply', {adi: aid, cid: cid}, function(data, textStatus, xhr) {
        if (data.success) {
            toastr.warning(data.msg);
        }else {
            $('input[name=aid]').val(aid);
            $('input[name=cid]').val(cid);
            $('#myModal').modal('show');
        }
    });
}