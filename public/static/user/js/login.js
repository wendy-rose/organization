jQuery(function($) {
    $(document).on('click', '.toolbar a[data-target]', function(e) {
        e.preventDefault();
        var target = $(this).data('target');
        $('.widget-box.visible').removeClass('visible');//hide others
        $(target).addClass('visible');//show target
    });

    //个人登录事件
    $('#person').click(function (e) {
        if (!($('#login-box').is('.visible'))){
            $('.widget-box.visible').removeClass('visible');
            $('#login-box').addClass('visible');
        }
    });
    var login = $('#loginForm').easyform();
    login.is_submit = false;
    login.error = function (ef) {
        console.log('tttt');
    };
    login.success = function (ef) {
        console.log('fff');
    };
    login.complete = function (ef) {
        console.log('aaaa');
    };
});
