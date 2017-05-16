$(function(){
	$.get('/corp/index/getCorp', function(response) {
        var data = response.data;
        $('.corpname').html(data.corpname);
        $('#userdept').html(data.username);
        $('.user-panel img').attr("src", data.corppic);
    });
});