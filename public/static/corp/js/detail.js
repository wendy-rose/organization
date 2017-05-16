$(function() {
     function getUrlParam(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
        var r = window.location.search.substr(1).match(reg); //匹配目标参数
        if (r != null) return unescape(r[2]);
        return null; //返回参数值
    }

    $.ajax({
        url: "/corp/index/getOrg?cid=" + getUrlParam('cid'),
        type: 'POST',
        dataType: 'JSON',
        success: function(result) {

            var showlist = $("<ul id='org' style='display:none;'></ul>");
            showall(result, showlist);
            $("#jOrgChart").append(showlist);
            $("#org").jOrgChart({
                chartElement: '#jOrgChart', //指定在某个dom生成jorgchart
                dragAndDrop: false //设置是否可拖动
            });

        }
    });
    
     function isNull(value) {
        return (value == "" || value == undefined || value == null) ? true : false;
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
});

function showall(menu_list, parent) {
    $.each(menu_list, function(index, val) {
        if (val.childrens.length > 0) {

            var li = $("<li></li>");

            li.append("<a href='javascript:void(0)' onclick=getOrgId(" + val.id + ");>" + val.name + "</a>").append("<ul></ul>").appendTo(parent);
            //递归显示
            showall(val.childrens, $(li).children().eq(1));
        } else {
            $("<li></li>").append("<a href='javascript:void(0)' onclick=getOrgId(" + val.id + ");>" + val.name + "</a>").appendTo(parent);
        }
    });
}
