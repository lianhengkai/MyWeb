var CONTROLLER = '/admin.php/System';

$(function() {
	$('.table-sort').dataTable({
		"bAutoWidth" : false, // 自动宽度
		"ajax" : CONTROLLER + '/logData',
		"columns" : [ {
			"title" : "<input type=\"checkbox\" id=\"check_all\" />",
			"class" : "text-c",
			"width" : "20px"
		}, {
			"title" : "ID",
			"class" : "text-c"
		}, {
			"title" : "用户名",
			"class" : "text-c"
		}, {
			"title" : "类型",
			"class" : "text-c",
			"width" : "100px"
		}, {
			"title" : "内容",
			"class" : "text-c"
		}, {
			"title" : "时间",
			"class" : "text-c"
		}, {
			"title" : "客户端IP",
			"class" : "text-c"
		}, {
			"title" : "操作",
			"class" : "text-c"
		} ],
		"aaSorting" : [ [ 1, "desc" ] ],// 默认第几个排序
		"bStateSave" : true,// 状态保存
		"aoColumnDefs" : [ // {"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
		{
			"orderable" : false,
			"aTargets" : [ 0, 7 ]
		} // 制定列不参与排序
		]
	});

	$("#check_all").click(
			function() {
				var $box = $('input[name=id]');
				$('input[type=checkbox][name=id]').prop('checked',
						$('#check_all').is(':checked'));
			});
});

function delete_all() {
	var $checkbox = $('input[type=checkbox][name=id]').filter(':checked');
	if ($checkbox.filter(':checked').length == 0) {
		layer_msg('请选择需要删除的数据', 'warning');
		return;
	}
}

function delete_one(id) {

}