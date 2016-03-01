var CONTROLLER = '/admin.php/Admin';

$(function() {
	$('input[type=password]').val('');

	$("textarea[name=admin_remark]").textarealimit({
		length : 100
	});

	$('.skin-minimal input').iCheck({
		checkboxClass : 'icheckbox-blue',
		radioClass : 'iradio-blue',
		increaseArea : '20%'
	});

	$("#form-admin-edit").Validform({
		ajaxPost : true,
		dragonfly: true,
		tiptype : 2,
		callback : function(data) {
			if (data.status == 1) {
				my_msg(data.info, data.type, function() {
					var index = parent.layer.getFrameIndex(window.name);
					parent.location.replace(parent.location.href);
					parent.layer.close(index);
				});
			} else if (data.info && data.type) {
				my_msg(data.info, data.type);
			} else {
				my_msg("服务器繁忙，请稍后再试～", "error");
			}
		}
	});
});