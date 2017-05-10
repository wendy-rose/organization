$(function(){
	toastr.options = {
        "timeOut": "1000"
    };

    $('#loginCorp').click(function(){
    	$('#loginForm').ajaxSubmit(function(data)){
    		if (data.success) {
    			window.location.href = data.url;
    		}else {
    			toastr.error(data.msg);
    		}
    	}
    });
});