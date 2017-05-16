var allObj = {};

$(function(){

	toastr.options = {
        "timeOut": "1000"
    };

	$('#nav ul li').click(function(){
		$(this).addClass('navLi').siblings().removeClass('navLi');
	});

	function isNull(value) {
        return (value == "" || value == undefined || value == null) ? true : false;
    }
    
     allObj.ajaxPage = function (curr) {
     	var type;
     	$('#nav ul li').each(function(index, el) {
     		if ($(this).hasClass('navLi')) {
     			type = $(this).val();
     		}
     	});
        var belong = $('#belong').val();
        var num = $('#num').val();
        var corpname = $('#corpname').val();
        $.getJSON('/corp/index/index', {
                page: curr,
                type: type,
                num: num,
                belong: belong,
                corpname: corpname
            },
            function(res) {
                var corpData = res.data;
                $("#corp").find("li").remove();
                $('#corpTemplate').tmpl(corpData).appendTo('#corp');
                laypage({
                    cont: 'page',
                    pages: res.allpage,
                    curr: res.nowpage,
                    skin: '#5bc0de',
                    jump: function(obj, first) {
                        if (!first) {
                            allObj.ajaxPage(obj.curr);
                        }
                    }
                });
            }
        );
    }

    $.get("/user/index/start.html", function(data) {
        var result = data.data.data;
        if (data.success) {
            var user = result.user;
            if (!isNull(user.avatar)) {
                $('#avatar').attr('src', user.avatar);
            }
        } else {
            toastr.warning(data.msg);
            window.location = '/user/login/index.html';
        }
    });

    allObj.ajaxPage(1);

    $('li').click(function(event) {
    	allObj.ajaxPage(1);
    });

    $('#belong,#num').change(function(event) {
    	allObj.ajaxPage(1);
    });

   
});

//这里为了让span标签在div中能够有触发点击事件
var n =0;
function a(){
	n = 1;
}
function b(){
	if (n == 1) {
		allObj.ajaxPage(1);
	}
	n = 0;
}