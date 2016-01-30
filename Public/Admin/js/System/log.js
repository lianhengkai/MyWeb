var CONTROLLER = '/admin.php/System';
var start_date,end_date,keywords,search_str = '';

$(function() {
	start_date = $('input[name=start_date]').val();
	end_date = $('input[name=end_date]').val();
	keywords = $('input[name=keywords]').val();
	
	search_str += '?start_date=' + start_date + '&end_date=' + end_date + '&keywords=' + keywords;
	
	$('.table-sort').dataTable({
		"bAutoWidth" : false, // 自动宽度
		"ajax" : CONTROLLER + '/logData' + search_str,
		"columns" : [ {
			"data" : "checkbox",
			"title" : "<input type=\"checkbox\" id=\"check_all\" />",
			"class" : "text-c",
			"width" : "20px"
		}, {
			"data" : "admin_log_id",
			"title" : "ID",
			"class" : "text-c"
		}, {
			"data" : "admin_realname",
			"title" : "用户名",
			"class" : "text-c"
		}, {
			"data" : "admin_log_type",
			"title" : "类型",
			"class" : "text-c",
			"width" : "100px"
		}, {
			"data" : "admin_log_content",
			"title" : "内容",
			"class" : "text-l"
		}, {
			"data" : "admin_log_time",
			"title" : "时间",
			"class" : "text-c"
		}, {
			"data" : "admin_log_ip",
			"title" : "客户端IP",
			"class" : "text-c"
		}, {
			"data" : "handle",
			"title" : "操作",
			"class" : "text-c"
		} ],
		"aaSorting" : [ [ 1, "desc" ] ],// 默认第几个排序
		"bStateSave" : true,// 状态保存
		"aoColumnDefs" : [
		    {
		    	"orderable" : false,
		    	"aTargets" : [ 0, 7 ]
		    } // 制定列不参与排序
		]
	});

	$("#check_all").bind('click', function() {
		var $box = $('input[name=id]');
		$('input[type=checkbox][name=id]').prop('checked', $('#check_all').is(':checked'));
	});
	
	$('#search_button').bind('click', function() {
		
	});
});

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
			url : CONTROLLER + '/deleteLog',
			data : {ids : ids},
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

function delete_one(ids) {
	my_confirm('确定要删除选中的数据吗？', function() {
		$.ajax({
			url : CONTROLLER + '/deleteLog',
			data : {ids : ids},
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