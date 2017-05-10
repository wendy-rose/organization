$(function() {
    function changeState(el) {
        if (el.readOnly) el.checked = el.readOnly = false;
        else if (!el.checked) el.readOnly = el.indeterminate = true;
    }

    ajaxPage(1);

    $('#search-btn,input[name=status],input[name=time]').click(function(event) {
        ajaxPage(1);
    });

    function ajaxPage(curr) {
        var status = $('input[name=status]:checked').val();
        var time = $('input[name=time]:checked').val();
        var title = $('input[name=title]').val();
        $.getJSON('/activity/activity/index', {
                page: curr,
                status: status,
                time: time,
                title: title
            },
            function(res) {
            	 var corpData = res.data;
                $("#corpList").find("li").remove(); 
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
            });
    }
});
