/**
 * 定义全局变量
 */
var ajax_index;

/**
 * 设置AJAX的全局默认选项
 * 
 * @author lhk(2016/01/30)
 */
$.ajaxSetup({
	type : "POST", // 默认使用POST方式
	beforeSend : function(xhr) {
		// 加载层-风格3
		ajax_index = layer.load(2);
	},
	complete : function(xhr, status) {
		layer.close(ajax_index);
	}
});

/**
 * 提示层函数
 * 
 * @param msg 提示信息
 * @param type 类型 （success error warning）
 * @param callback 回调函数
 * @author lhk(2016/01/30)
 */
function my_msg(msg, type, callback) {
	var icon = type == 'success' ? 6 : (type == 'error' ? 5 : 0);
	var time = type == 'success' ? 1000 : (type == 'error' ? 2000 : 1500);
	layer.msg(msg, {
		icon : icon,
		time : time
	}, callback);
}

/**
 * 询问层函数
 * 
 * @param confirm 询问信息
 * @param callback1 回调函数1
 * @param callback2 回调函数2
 * @author lhk(2016/01/30)
 */
function my_confirm(confirm, callback1, callback2) {
	layer.confirm(confirm, {
		btn : [ "确定", "取消" ], // 按钮
		title : '提示'
	}, callback1, callback2);
}

/**
 * iframe层函数(最大化显示)
 * 
 * @param title 标题
 * @param url 请求网址
 * @author lhk(2016/02/16)
 */
function my_iframe(title, url) {
	var index = layer.open({
		type : 2,
		title : title,
		shadeClose : true,
		shade : 0.8,
		content : url
	});

	layer.full(index);
}

/**
 * IE下不支持maxlength的解决办法(jquery)
 * 用法：$("input[name=videoname]").textarealimit({length:30});
 * 
 * @author lhk(2016/02/16)
 */
(function($) {
	$.fn.textarealimit = function(settings) {
		settings = jQuery.extend({
			length : 100
		}, settings);
		maxLength = settings.length;
		$(this).attr("maxlength", maxLength).bind("keydown", doKeydown).bind("keypress", doKeypress).bind("beforepaste", doBeforePaste).bind("paste", doPaste);

		function doKeypress() {
			if (document.selection) {
				var oTR = document.selection.createRange();
				if (oTR.text.length >= 1) {
					event.returnValue = true;
				} else if (this.value.length > maxLength - 1) {
					event.returnValue = false;
				}
			}
		}

		function doKeydown() {
			if (document.selection) {
				var _obj = this;
				setTimeout(function() {
					if (_obj.value.length > maxLength - 1) {
						var oTR = window.document.selection.createRange();
						oTR.moveStart("character", -1 * (_obj.value.length - maxLength));
						oTR.text = "";
					}
				}, 1);
			}
		}

		function doBeforePaste() {
			event.returnValue = false;
		}

		function doPaste() {
			if (document.selection) {
				event.returnValue = false;
				var oTR = document.selection.createRange();
				var iInsertLength = maxLength - this.value.length
						+ oTR.text.length;
				var sData = window.clipboardData.getData("Text").substr(0,
						iInsertLength);
				oTR.text = sData;
			}
		}
	}
})(jQuery);