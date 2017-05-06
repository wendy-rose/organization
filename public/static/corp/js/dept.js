//部门数据

$(function() {

    toastr.options = {
        "timeOut": "1000"
    };

    var zTress;
    var demoIframe;

    var zNodes = [
        { id: 0, name: '计科青协', open: true },
        { id: 1, pid: 0, name: "会长团", open: true },

        { id: 2, pid: 0, name: "数学辅导队" },
        { id: 201, pid: 2, name: "海珠", open: true },
        { id: 202, pid: 2, name: "白云", open: true },

        { id: 3, pid: 0, name: "项目统筹部", open: true },
        { id: 301, pid: 3, name: "海珠", open: true },
        { id: 302, pid: 3, name: "白云", open: true },

        { id: 4, pid: 0, name: "宣传部", open: true },
        { id: 401, pid: 4, name: "海珠", open: true },
        { id: 402, pid: 4, name: "白云", open: true },
    ];

    var setting = {
        view: {
            dblClickExpand: false,
            showLine: false,
            selectedMulti: false,
            showIcon: false,
            nameIsHTML: true
        },
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
                pIdKey: "pid",
                rootPId: ""
            }
        },
        callback: {
            beforeClick: function(treeId, treeNode) {
                var zTree = $.fn.zTree.getZTreeObj("tree");
                if (treeNode.isParent) {
                    zTree.expandNode(treeNode);
                }
            },
            onClick: function(event, treeId, treeNode) {
                table.ajax.url('/corp/index/getUserList?deptid=' + treeNode.id).load();
            }
        }
    };

    var t = $('#tree');
    t = $.fn.zTree.init(t, setting, zNodes);

    //用户数据
    var table = $('#DataTable').DataTable({
        "processing": true, //显示加载信息
        "serverSide": true, //开启服务器模式
        "ordering": false, //关闭排序
        "bLengthChange": false, //关闭可选长度
        "info": false, //关闭分页信息
        "autoWidth": true, //让Datatables自动计算宽度
        "searching": false, //开启全局搜索功能
        "pagingType": "full_numbers", //分页按钮种类显示选项
        "language": {
            "processing": "玩命加载中...",
            "search": "搜索:",
            "url": "",
            "emptyTable": "表中数据为空",
            "loadingRecords": "正在加载数据...",
            "paginate": {
                "first": "首页",
                "previous": "上一页",
                "next": "下一页",
                "last": "尾页"
            },
        },
        "ajax": {
            "url": '/corp/index/getUserList',
            "type": 'POST',
        },
        "columns": [{
            "data": null,
            "orderable": false,
            "title": "<input type='checkbox' name='checklist' id='checkAll' />",
        },
            { "data": "username" },
            { "data": "dept" },
            { "data": "position" },
            { "data": "phone" },
            { "data": "email" },
            { "data": null }
        ],
        "columnDefs": [{
            "targets": 0, //改写哪一列
            "render": function(data, type, row, meta) {
                return "<input type='checkbox' name='checkList' id='row_" + row.id + "' />";
            }
        }, {
            "targets": 6, //改写哪一列
            "serchable": false,
            "render": function(data, type, row, meta) {
                return "<button class='btn btn-primary' onclick=editUser(" + row.id + ")>修改</button>";
            }
        }]
    });


    var uploadOption = {
        action: '/corp/index/UploadCorp',
        name: 'corpImg',
        autoSubmit: true,
        onComplete: function(file, response) {
            response = JSON.parse(response);
            if (response.success) {
                var image = $('<img src="' + response.thumb + '">');
                $('#userpic').empty().html(image);
                $('input[name=userpic]').val(response.thumb);
            } else {
                toastr.warning(response.msg);
            }
        }
    };
    var oAjaxUpload = new AjaxUpload('#userpic', uploadOption);

    $('#addDeptUser').click(function(event) {
        $('#deptUser').removeClass('hidden');
        $('#deptTreeUser').addClass('hidden');
    });

    $('#addOrEditUser').click(function(event) {
        $('#deptUser').addClass('hidden');
        $('#deptTreeUser').removeClass('hidden');
    });
});

function editUser(userid) {
    $('#deptUser').removeClass('hidden');
    $('#deptTreeUser').addClass('hidden');
    //ajax显示社员信息
}
