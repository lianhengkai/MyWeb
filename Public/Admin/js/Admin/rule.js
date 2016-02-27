var CONTROLLER = '/admin.php/Admin';
var keywords, search_str = '';

$(function() {
	keywords = $('input[name=keywords]').val();

	search_str += '?keywords=' + keywords;

	$('.table-sort').dataTable({
		"bAutoWidth" : false, // 自动宽度
		"ajax" : CONTROLLER + '/ruleData' + search_str,
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
			"class" : "text-c"
		}, {
			"data" : "title",
			"title" : "中文名称",
			"class" : "text-c"
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
		"aaSorting" : [ [ 1, "desc" ] ],// 默认第几个排序
		"bStateSave" : true,// 状态保存
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
			url : CONTROLLER + '/deleteAdmin',
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
			url : CONTROLLER + '/deleteAdmin',
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
 * 改变管理员启用状态
 */
function change_admin_open(admin_id, admin_open) {
	$.ajax({
		type : 'post',
		url : CONTROLLER + '/changeAdminOpen',
		data : {
			admin_id : admin_id,
			admin_open : admin_open
		},
		dataType : 'json',
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
}

/**
 * 编辑管理员
 */
function edit_admin(admin_id) {
	my_iframe('编辑管理员', CONTROLLER + '/editAdmin/admin_id/' + admin_id);
}