$(function() {

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
