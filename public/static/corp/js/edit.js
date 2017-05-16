
$(function() {
    
    //不能直接使用ue的setContent的方法，因为如果初始化过的就不会出发ready，所以会报错，需要使用以下代码
    // ue.addListener("ready", function () {
    //     // editor准备好之后才可以使用
    //     ue.setContent('abc');

    // });
    // $.get('/corp/index/getCorp', function(response) {
    //     var data = response.data;
    //     $('.corpname').html(data.corpname);
    //     $('#userdept').html(data.username);
    //     $('.user-panel img').attr("src", data.corppic);
    //     ue.addListener("ready", function(){
    //         ue.setContent(data.description);
    //     });
    // });
});
