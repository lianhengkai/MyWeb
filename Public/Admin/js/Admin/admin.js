var CONTROLLER = '/admin.php/Admin';
var start_date, end_date, keywords, search_str = '';

$(function() {
	start_date = $('input[name=start_date]').val();
	end_date = $('input[name=end_date]').val();
	keywords = $('input[name=keywords]').val();

	search_str += '?start_date=' + start_date + '&end_date=' + end_date
			+ '&keywords=' + keywords;

	$('.table-sort').dataTable({
		"bAutoWidth" : false, // 自动宽度
		"ajax" : CONTROLLER + '/adminData' + search_str,
		"columns" : [ {
			"data" : "checkbox",
			"title" : "<input type=\"checkbox\" id=\"check_all\" />",
			"class" : "text-c",
			"width" : "20px"
		}, {
			"data" : "admin_id",
			"title" : "ID",
			"class" : "text-c"
		}, {
			"data" : "admin_username",
			"title" : "用户名",
			"class" : "text-c"
		}, {
			"data" : "admin_realname",
			"title" : "真实姓名",
			"class" : "text-c"
		}, {
			"data" : "admin_sex",
			"title" : "性别",
			"class" : "text-c"
		}, {
			"data" : "admin_email",
			"title" : "邮箱",
			"class" : "text-c"
		}, {
			"data" : "admin_tel",
			"title" : "手机",
			"class" : "text-c"
		}, {
			"data" : "admin_addtime",
			"title" : "加入时间",
			"class" : "text-c"
		}, {
			"data" : "admin_open",
			"title" : "是否已启用",
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
			"aTargets" : [ 0, 7 ]
		} // 制定列不参与排序
		]
	});

	$("#check_all").bind(
			'click',
			function() {
				var $box = $('input[name=id]');
				$('input[type=checkbox][name=id]').prop('checked',
						$('#check_all').is(':checked'));
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
					my_msg(data.info, data.type);
				} else {
					my_msg("服务器繁忙，请稍后再试～", "error");
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
					my_msg(data.info, data.type);
				} else {
					my_msg("服务器繁忙，请稍后再试～", "error");
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