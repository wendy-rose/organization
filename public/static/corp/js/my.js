$(function() {
    $('#rangtime').daterangepicker({
    	autoUpdateInput:false,
        locale: {
            format: 'YYYY/MM/DD',
            applyLabel: '确定',
            cancelLabel: '取消'
        }
    }, function(start, end, label) {
    	$('#rangtime').val(start.format('YYYY/MM/DD') + '-' + end.format('YYYY/MM/DD'));
    });
});
