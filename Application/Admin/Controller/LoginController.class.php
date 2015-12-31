<?php

namespace Admin\Controller;

use Think\Controller;

/**
 * 登录控制器
 *
 * @author lhk(2015/12/22)
 */
class LoginController extends Controller {
	
	/**
	 * 默认显示登录页面
	 *
	 * @author lhk(2015/12/30)
	 */
	public function index() {
		$this->display ();
	}
	
	/**
	 * 定义生成验证码方法
	 * 
	 * @author lhk(2015/12/31)
	 */ 
	public function verify() {
		// 导入命名空间，生成验证码对象
		$verity = new \Think\Verify ();
		// 自定义验证码属性
		$verity->length = 4;
		$verity->useCurve = false;
		$verity->useNoise = false;
		// 兼容处理
		ob_clean ();
		// 生成验证码
		$verity->entry ();
	}
	/**
	 * 定义退出系统方法
	 */ 
	public function logout() {
		// 清除session信息
		session ( 'admin', null );
		// 删除可能存在的cookie
		cookie ( 'admin', null );
		// 跳转：登录界面
		$this->success ( '退出成功！', U ( 'Login/index' ) );
	}
}