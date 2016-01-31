/**
 * 定义全局变量
 */
var ajax_index;

/**
 * 设置AJAX的全局默认选项
 * @author lhk(2016/01/30)
 */
$.ajaxSetup({
	type: "POST" , // 默认使用POST方式
	beforeSend: function (xhr) {
		// 加载层-风格3
		ajax_index = layer.load(2);
	},
	complete: function (xhr, status) { 
		layer.close(ajax_index);
	}
});

/**
 * 提示层函数
 * @param msg 提示信息
 * @param type 类型 （success error warning）
 * @param callback 回调函数
 * @author lhk(2016/01/30)
 */
function my_msg(msg, type, callback) {
	var icon = type == 'success' ? 6 : (type == 'error' ? 5 : 0);
	var time = type == 'success' ? 1000 : (type == 'error' ? 2000 : 1500);
	layer.msg(msg, {
		icon: icon,
		time: time
	}, callback);
}

/**
 * 询问层函数
 * @param confirm 询问信息
 * @param callback1 回调函数1
 * @param callback2 回调函数2
 * @author lhk(2016/01/30)
 */
function my_confirm(confirm, callback1, callback2) {
	layer.confirm(confirm, {
		btn : ["确定","取消"], //按钮 
		title:'提示'
	}, callback1, callback2);
}

/**
 * iframe层函数
 * @param confirm 询问信息
 * @param title 回调函数1
 * @param url 回调函数2
 * @author lhk(2016/01/30)
 */
function my_iframe(title, url, callback2) {
	layer.open({
	    type: 2,
	    title: title,
	    shadeClose: true,
	    shade: 0.8,
	    area: ['380px', '90%'],
	    content: url
	}); 
}