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
                
            }
        }
    };

    var t = $('#tree');
    t = $.fn.zTree.init(t, setting, zNodes);

    // $('#addDeptUser').click(function(event) {
    //     $('#deptUser').removeClass('hidden');
    //     $('#deptTreeUser').addClass('hidden');
    // });

    // $('#addOrEditUser').click(function(event) {
    //     $('#deptUser').addClass('hidden');
    //     $('#deptTreeUser').removeClass('hidden');
    // });

});

// function editUser(userid) {
//     $('#deptUser').removeClass('hidden');
//     $('#deptTreeUser').addClass('hidden');
//     //ajax显示社员信息
// }
