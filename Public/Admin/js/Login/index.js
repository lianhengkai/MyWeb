// inline scripts related to this page
jQuery(function($) {
	 $(document).on('click', '.toolbar a[data-target]', function(e) {
		e.preventDefault();
		var target = $(this).data('target');
		$('.widget-box.visible').removeClass('visible');//hide others
		$(target).addClass('visible');//show target
	 });
	});

// 登入
$(function() {
	$('#runlogin').ajaxForm({
		beforeSubmit: logcheckForm,
		// 此方法主要是提交前执行的方法，根据需要设置
		success: logcomplete,
		// 这是提交后的方法
		dataType: 'json'
	});

	function logcheckForm() {
		if ('' == $.trim($('#admin_username').val())) {
			layer.alert('登录用户名不能为空', {
				icon: 6
			}, function(index) {
				layer.close(index);
				$('#member_list_email').focus();
			});
			return false;
		}
		if ('' == $.trim($('#admin_pwd').val())) {
			layer.alert('登录密码不能为空', {
				icon: 6
			}, function(index) {
				layer.close(index);
				$('#member_list_email').focus();
			});
			return false;
		}
	}

	function logcomplete(data) {
		if (data.status == 1) {
			window.location.href = "{:U('Index/index')}";
			return false;
		} else {
			layer.alert(data.info, {
				icon: 6
			});
			return false;
		}
	}
});

// 找回密码，发送邮件
$(function() {
	$('#runemail').ajaxForm({
		beforeSubmit: emailcheckForm,
		// 此方法主要是提交前执行的方法，根据需要设置
		success: emailcomplete,
		// 这是提交后的方法
		dataType: 'json'
	});

	function emailcheckForm() {
		if ('' == $.trim($('#email').val())) {
			layer.alert('邮件不能为空', {
				icon: 6
			});
			$('#email').focus();
			return false;
		}
	}

	function emailcomplete(data) {
		if (data.status == 1) {
			layer.alert(data.info, {
				icon: 6
			});
			return false;
		} else {
			layer.alert(data.info, {
				icon: 5
			});
			return false;
		}
	}
});

// 注册AJAX方法
$(function() {
	$('#reg').ajaxForm({
		beforeSubmit: checkFormreg,
		success: complete,
		dataType: 'json'
	});

	function checkFormreg() {
		if ('' == $.trim($('#admin_email').val())) {
			layer.alert('注册邮箱不能为空', {
				icon: 6
			}, function(index) {
				layer.close(index);
				$('#admin_email').focus();
			});
			return false;
		}
		if ('' == $.trim($('#admin_usernamereg').val())) {
			layer.alert('用户名不能为空', {
				icon: 6
			}, function(index) {
				layer.close(index);
				$('#admin_usernamereg').focus();
			});
			return false;
		}
		if ('' == $.trim($('#admin_pwdreg').val())) {
			layer.alert('密码不能为空', {
				icon: 6
			}, function(index) {
				layer.close(index);
				$('#admin_pwdreg').focus();
			});
			return false;
		}
		if ('' == $.trim($('#admin_newpwdreg').val())) {
			layer.alert('确认密码不能为空', {
				icon: 6
			}, function(index) {
				layer.close(index);
				$('#admin_newpwdreg').focus();
			});
			return false;
		}
		if ($.trim($('#admin_pwdreg').val()) != $.trim($('#admin_newpwdreg').val())) {
			layer.alert('2次输入密码不同，请重新输入', {
				icon: 6
			}, function(index) {
				layer.close(index);
				$('#admin_pwdreg').focus();
			});
			return false;
		}
	}

	function complete(data) {
		if (data.status == 1) {
			layer.alert(data.info, {
				icon: 6
			}, function(index) {
				layer.close(index);
				window.location.href = data.url;
			});
		} else {
			layer.alert(data.info, {
				icon: 5
			});
			return false;
		}
	}
});