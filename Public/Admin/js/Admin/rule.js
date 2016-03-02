var CONTROLLER = '/admin.php/Admin';

$(function() {
	$('.table-sort').dataTable({
		"bAutoWidth" : false, // 自动宽度
		"ajax" : CONTROLLER + '/ruleData',
		"columns" : [ {
			"data" : "checkbox",
			"title" : "<input type=\"checkbox\" id=\"check_all\" />",
			"class" : "text-c",
			"width" : "20px"
		}, {
			"data" : "id",
			"title" : "ID",
			"class" : "text-c"
		}, {
			"data" : "name",
			"title" : "规则名",
			"class" : "text-l"
		}, {
			"data" : "title",
			"title" : "中文名称",
			"class" : "text-l"
		}, {
			"data" : "status",
			"title" : "状态",
			"class" : "text-c"
		}, {
			"data" : "icon",
			"title" : "图标",
			"class" : "text-c"
		}, {
			"data" : "sort",
			"title" : "排序",
			"class" : "text-c"
		}, {
			"data" : "pid",
			"title" : "父ID",
			"class" : "text-c"
		}, {
			"data" : "addtime",
			"title" : "创建时间",
			"class" : "text-c"
		}, {
			"data" : "handle",
			"title" : "操作",
			"class" : "text-c"
		} ],
		"bStateSave" : false,// 状态保存
		"aoColumnDefs" : [ {
			"orderable" : false,
			"aTargets" : [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
			} // 制定列不参与排序
		]
	});

	$("#check_all").bind('click', function() {
		var $box = $('input[name=id]');
		$('input[type=checkbox][name=id]').prop('checked', $('#check_all').is(':checked'));
	});
});

/**
 * 删除选中数据
 */
function delete_select() {
	var $checkbox = $('input[type=checkbox][name=id]').filter(':checked');
	if ($checkbox.filter(':checked').length == 0) {
		my_msg('请选择需要删除的数据', 'warning');
		return;
	}

	my_confirm('确定要删除选中的数据吗？', function() {
		var ids = '';
		$checkbox.each(function(i, item) {
			ids += $(this).val() + ',';
		});

		$.ajax({
			url : CONTROLLER + '/deleteRule',
			data : {
				ids : ids
			},
			success : function(data) {
				if (data.status == 1) {
					my_msg(data.info, data.type, function() {
						window.location.reload();
					});
				} else if (data.info && data.type) {
					my_msg(data.info, data.type, function() {
						window.location.reload();
					});
				} else {
					my_msg("服务器繁忙，请稍后再试～", "error", function() {
						window.location.reload();
					});
				}
			}
		});
	});
}

/**
 * 删除单条数据
 */
function delete_one(ids) {
	my_confirm('确定要删除该数据吗？', function() {
		$.ajax({
			url : CONTROLLER + '/deleteRule',
			data : {
				ids : ids
			},
			success : function(data) {
				if (data.status == 1) {
					my_msg(data.info, data.type, function() {
						window.location.reload();
					});
				} else if (data.info && data.type) {
					my_msg(data.info, data.type, function() {
						window.location.reload();
					});
				} else {
					my_msg("服务器繁忙，请稍后再试～", "error", function() {
						window.location.reload();
					});
				}
			}
		});
	});
}

/**
 * 编辑规则
 */
function edit_rule(id) {
	my_iframe('编辑规则', CONTROLLER + '/editRule/id/' + id);
}