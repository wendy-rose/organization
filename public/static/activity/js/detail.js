$(function() {

    toastr.options = {
        "timeOut": "1000"
    };

    $("#editor").emoji({
        button: "#btn",
        showTab: false,
        animation: 'slide',
        icons: [{
            name: "QQ表情",
            path: "/static/lib/emoji/img/qq/",
            maxNum: 91,
            excludeNums: [41, 45, 54],
            file: ".gif",
        }, {
            name: "贴吧表情",
            path: "/static/lib/emoji/img/tieba/",
            maxNum: 50,
            excludeNums: [41, 45, 54],
            file: ".jpg",
            alias: {
                1: "hehe",
                2: "haha",
                3: "tushe",
                4: "a",
                5: "ku",
                6: "lu",
                7: "kaixin",
                8: "han",
                9: "lei",
                10: "heixian",
                11: "bishi",
                12: "bugaoxing",
                13: "zhenbang",
                14: "qian",
                15: "yiwen",
                16: "yinxian",
                17: "tu",
                18: "yi",
                19: "weiqu",
                20: "huaxin",
                21: "hu",
                22: "xiaonian",
                23: "neng",
                24: "taikaixin",
                25: "huaji",
                26: "mianqiang",
                27: "kuanghan",
                28: "guai",
                29: "shuijiao",
                30: "jinku",
                31: "shengqi",
                32: "jinya",
                33: "pen",
                34: "aixin",
                35: "xinsui",
                36: "meigui",
                37: "liwu",
                38: "caihong",
                39: "xxyl",
                40: "taiyang",
                41: "qianbi",
                42: "dnegpao",
                43: "chabei",
                44: "dangao",
                45: "yinyue",
                46: "haha2",
                47: "shenli",
                48: "damuzhi",
                49: "ruo",
                50: "OK"
            },
            title: {
                1: "呵呵",
                2: "哈哈",
                3: "吐舌",
                4: "啊",
                5: "酷",
                6: "怒",
                7: "开心",
                8: "汗",
                9: "泪",
                10: "黑线",
                11: "鄙视",
                12: "不高兴",
                13: "真棒",
                14: "钱",
                15: "疑问",
                16: "阴脸",
                17: "吐",
                18: "咦",
                19: "委屈",
                20: "花心",
                21: "呼~",
                22: "笑脸",
                23: "冷",
                24: "太开心",
                25: "滑稽",
                26: "勉强",
                27: "狂汗",
                28: "乖",
                29: "睡觉",
                30: "惊哭",
                31: "生气",
                32: "惊讶",
                33: "喷",
                34: "爱心",
                35: "心碎",
                36: "玫瑰",
                37: "礼物",
                38: "彩虹",
                39: "星星月亮",
                40: "太阳",
                41: "钱币",
                42: "灯泡",
                43: "茶杯",
                44: "蛋糕",
                45: "音乐",
                46: "haha",
                47: "胜利",
                48: "大拇指",
                49: "弱",
                50: "OK"
            }
        }]
    });

    function getUrlParam(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
        var r = window.location.search.substr(1).match(reg); //匹配目标参数
        if (r != null) return unescape(r[2]);
        return null; //返回参数值
    }

    ajaxPage(1);

    function ajaxPage(curr) {
        $.getJSON('/activity/commit/getList', {
                page: curr,
                aid: getUrlParam('aid')
            },
            function(res) {
                var corpData = res.data;
                $("#commitList").find("li").remove();
                $('#commitTemplate').tmpl(corpData).appendTo('#commitList');
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

    $('#commit').click(function() {
        var content = $('#editor').html();
        if (content == '') {
            toastr.warning('评论内容不能为空');
            return false;
        }
        var cid = $('input[name=cid]').val();
        var aid = $('input[name=aid]').val();
        $.post('/activity/commit/add', { content: content, cid: cid, aid: aid }, function(data, textStatus, xhr) {
            if (data.success) {
                ajaxPage(1);
                $('#editor').html("");
            } else {
                toastr.error('评论失败');
            }
        });
    });

    $('#addLike').click(function(event) {
        var params = $(this).data('param');
        $.post('/activity/activity/like', params, function(data) {
            if (data.success) {
                $('#addLike').addClass('hidden');
                $('#resetLike').removeClass('hidden');
            } else {
                toastr.error('点赞失败');
            }
        });
    });

    $('#resetLike').click(function(event) {
        var params = $(this).data('param');
        $.post('/activity/activity/resetLike', params, function(data) {
            if (data.success) {
                $('#addLike').removeClass('hidden');
                $('#resetLike').addClass('hidden');
            } else {
                toastr.error('取消点赞失败');
            }
        });
    });

    $('#resetApply').click(function(event){
        var params = $(this).data('param');
        $.post('/activity/activity/resetApply', params, function(data, textStatus, xhr) {
            if (data.success) {
                $(this).removeClass("hidden");
                $('#addApply').addClass("hidden");
            }else{
                toastr.error('取消报名失败');
            }
        });
    });
    
    $('#applyActivity').click(function(event) {
        $('#ajaxApply').ajaxSubmit(function(data){
            if (data.success) {
                toastr.success(data.msg);
                $('#myModal').modal('hide');
                $('#resetApply').addClass("hidden");
            }else{
                toastr.error('报名失败');
            }
        });
    });
});
