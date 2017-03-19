var zTress;
	var demoIframe;

	var setting = {
		view:{
			dblClickExpand: false,
			showLine: false,
			selectedMulti: false,
			showIcon: false,
			nameIsHTML: true
		},
		data: {
			simpleData: {
				enable:true,
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
					return false;
				}
			}
		}
	};

	var zNodes = [
	    {id:0, name:'计科青协', open:true},
	    {id:1, pid:0, name:"会长团"},
	    {id:101, pid:1, name:"正会长"},
	    {id:102, pid:1, name:"副会长"},
	    {id:103, pid:1, name:"副会长"},

	    {id:2, pid:0, name:"数学辅导队"},
	    {id:201, pid:2, name:"海珠"},
	    {id:202, pid:2, name:"白云"},
	    {id:211, pid:201, name:"正部长"},
	    {id:212, pid:201, name:"副部长"},
	    {id:221, pid:202, name:"正部长"},

	    {id:3, pid:0, name:"项目统筹部"},
	    {id:301, pid:3, name:"海珠"},
	    {id:302, pid:3, name:"白云"},
	    {id:311, pid:301, name:"正部长"},
	    {id:312, pid:301, name:"副部长"},
	    {id:321, pid:302, name:"正部长"},

	    {id:4, pid:0, name:"宣传部"},
	    {id:401, pid:4, name:"海珠"},
	    {id:402, pid:4, name:"白云"},
	    {id:411, pid:401, name:"正部长"},
	    {id:412, pid:401, name:"副部长"},
	    {id:421, pid:402, name:"正部长"},
	];
$(function(){
	var t = $('#tree');
	t = $.fn.zTree.init(t, setting, zNodes);
});