$(function(){
	toastr.options = {
        "timeOut": "1000"
    };

    $('#loginCorp').click(function(){
        if ($('#inputEmail3').val() == '') {
            toastr.warning('邮箱不能为空');
            return false;
        }
        if ($('#inputPassword3').val() == '') {
            toastr.warning('密码不能为空');
            return false;
        }
    	$('#loginForm').ajaxSubmit(function(data){
    		if (data.success) {
    			window.location.href = '/corp/dashboard/index?cid=' + data.cid;
    		}else {
    			toastr.error(data.msg);
                return false;
    		}
            return false;
    	});
    });
});