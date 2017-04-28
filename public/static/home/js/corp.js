$(function(){
	$('#nav ul li').click(function(){
		$(this).addClass('navLi').siblings().removeClass('navLi');
	});
});