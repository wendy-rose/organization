$(function(){
	$('#avatar').on('change', function(){
		console.log('寻找');
		$('#upload').ajaxSubmit(function(data){
			console.log(data);
		});
	});
});