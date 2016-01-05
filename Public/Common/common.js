/**
 * 提示层函数
 * @param msg 提示信息
 * @param type 类型 （success error warning）
 * @param callback 回调函数
 */
function layer_msg(msg, type, callback) {
	var icon = type == 'success' ? 6 : (type == 'error' ? 5 : 0);
	var time = type == 'success' ? 1000 : (type == 'error' ? 2000 : 1500);
	layer.msg(msg, {
		icon: icon,
		time: time
	}, callback);
}