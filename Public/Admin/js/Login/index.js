// 登入
$(function() {
	$('form[name=loginForm]').Validform({
		ajaxPost : true,
		tiptype : 4,
		beforeCheck : function(curform) {
			// 在表单提交执行验证之前执行的函数，curform参数是当前表单对象。
			// 这里明确return false的话将不会继续执行验证操作;
			if ($.trim($('#admin_username').val()) == '') {
				my_msg('登录用户名不能为空', 'warning', function() {
					$('#admin_username').focus();
				});
				return false;
			}
			if ($.trim($('#admin_pwd').val()) == '') {
				my_msg('登录密码不能为空', 'warning', function() {
					$('#admin_pwd').focus();
				});
				return false;
			}
			if ($.trim($('#verify').val()) == '') {
				my_msg('验证码不能为空', 'warning', function() {
					$('#verify').focus();
				});
				return false;
			}
		},
		callback : function(data) {
			if (data.status == 1) {
				my_msg(data.info, data.type, function() {
					window.location.href = "/admin.php/Index/index.html";
				});
			} else if (data.info && data.type) {
				my_msg(data.info, data.type);
			} else {
				my_msg("服务器繁忙，请稍后再试～", "error");
			}
		}
	});
});