jQuery(function($) {

    //切换找回密码，登录，注册页面
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
    /**
     * 登录表单验证ajax提交
     */
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
                UI.tip(result.msg, 'error');
            }
        }, 'json');
    });

    //发送邮箱验证码
    $('#send').click(function (e) {
        emailAddress = $('#checkEmail').val();
        if (emailAddress == null){
            UI.tip('邮箱不能为空', 'error');
        }else if (!/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(emailAddress)) {
            UI.tip('邮箱格式错误', 'error');
        }else {
            $.post("{:Url('index/code/send')}", {email:emailAddress}, function (result) {
                if (result.success){
                    UI.tip(result.msg, 'success');
                    $('#sendMsg').html('已发送');
                    $('#send').attr('disabled',"true");
                }else {
                    UI.tip(result.msg, 'error');
                }
            }, 'json');
        }
    });

    //找回密码
    $('#findPassword').bootstrapValidator({
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
            code : {
                message : 'The email is not valid',
                validators: {
                    notEmpty: {
                        message : '验证码不能为空'
                    },
                    threshold : 6,
                    remote : {
                        url : "{:Url('index/code/check')}",
                        message : '验证码错误',
                        delay : 2000,
                        type : 'POST'
                    }
                }
            },
            pwd : {
                message: 'The email is not valid',
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
            },
            repwd : {
                message: 'The email is not valid',
                validators: {
                    notEmpty: {
                        message : '确认密码不能为空'
                    },
                    stringLength: {
                        min: 6,
                        max: 16,
                        message: '密码长度必须在6-16位'
                    },
                    identical: {//相同
                        field: 'pwd',
                        message: '两次密码不一致'
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
                if (!($('#login-box').is('.visible'))){
                    $('.widget-box.visible').removeClass('visible');
                    $('#login-box').addClass('visible');
                    $('#email').val($('#checkEmail').val());
                    $('#password').val($('#repwd').val());
                }
            }else{
                UI.tip(result.msg, 'error');
            }
        }, 'json');
    });

    //注册
    $('#registerFrom').bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields : {
            email: {
                message: 'The email is not valid',
                validators: {
                    notEmpty: {
                        message : '邮箱不能为空'
                    },
                    emailAddress: {
                        message : '邮箱格式错误'
                    },
                    remote : {
                        url : "{:Url('user/info/exsitemail')}",
                        message : '邮箱已经存在',
                        delay : 2000,
                        type : 'POST'
                    }
                }
            },
            username : {
                message: 'The username is not valid',
                validators: {
                    notEmpty: {
                        message : '用户名不能为空'
                    },
                    remote : {
                        url : "{:Url('user/info/exsitename')}",
                        message : '用户名已经存在',
                        delay : 2000,
                        type : 'POST'
                    }
                }
            },
            password : {
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
            },
            repassword : {
                message: 'The repassword is not valid',
                validators: {
                    notEmpty: {
                        message : '确认密码不能为空'
                    },
                    stringLength: {
                        min: 6,
                        max: 16,
                        message: '密码长度必须在6-16位'
                    },
                    identical: {//相同
                        field: 'password',
                        message: '两次密码不一致'
                    }
                }
            },
            captcha : {
                message : 'The captcha is not valid',
                validators : {
                    notEmpty: {
                        message : '验证码不能为空'
                    },
                    stringLength: {
                        min: 4,
                        max: 4,
                        message: '验证码位数必须为4位'
                    },
                    threshold : 4,
                    remote : {
                        url : "{:Url('index/code/captcha')}",
                        message : '验证码错误',
                        delay : 2000,
                        type : 'POST'
                    }
                }
            }
        }
    }).on('success.form.bv', function (e) {
        e.preventDefault();
        e.preventDefault();
        var $form = $(e.target);
        var bv = $form.data('bootstrapValidator');
        $.post($form.attr('action'), $form.serialize(), function(result) {
            if(result.success){
                if (!($('#login-box').is('.visible'))){
                    $('.widget-box.visible').removeClass('visible');
                    $('#login-box').addClass('visible');
                }
                UI.tip('注册成功，请登录');
            }else{
                UI.tip(result.msg, 'error');
            }
        }, 'json');
    });
});
