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

    // $('#email').completer({
    //     separator: "@",
    //     source: ["qq.com", "163.com", "162.com", "sina.com", "gmail.com", "aliyun.com", "mail.com"]
    // });

    $('#loginForm').bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            email: {
                message: 'The email is not valid',
                validators: {
                    notEmpty: {
                        message : '邮箱不能为空'
                    },
                    emailAddress: {
                        message : '邮箱格式错误'
                    }
                }
            },
            password: {
                message: 'The password is not valid',
                validators: {
                    notEmpty: {
                        message : '密码不能为空'
                    },
                    stringLength: {
                        min: 6,
                        max: 16,
                        message: '密码长度必须在6-16位'
                    }
                }
            }
        }
    }).on('success.form.bv', function (e) {
        e.preventDefault();
        var $form = $(e.target);
        var bv = $form.data('bootstrapValidator');
        $.post($form.attr('action'), $form.serialize(), function(result) {
            if(result.success){
                window.location = "{:Url('home/index/index')}";
            }else{
                ShowTipAlwaysInTheMiddle(result.msg, 'danger');
            }
        }, 'json');
    });
});
