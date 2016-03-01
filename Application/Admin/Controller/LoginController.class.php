<?php

namespace Admin\Controller;

/**
 * 登录控制器
 *
 * @author lhk(2015/12/22)
 */
class LoginController extends CommonController {
	
	/**
	 * 默认显示登录页面
	 *
	 * @author lhk(2015/12/30)
	 */
	public function index() {
		$this->display ();
	}
	
	/**
	 * 登录验证方法
	 *
	 * @author lhk(2016/01/04)
	 */
	public function login() {
		if (IS_AJAX) {
			// 改用tp框架的自定义规则验证数据
			$adminModel = D ( 'Admin' );
			
			if ($adminModel->validate ( $adminModel->validate_login )->create ()) {
				// 通过验证 开始执行登录验证
				if ($adminid = $adminModel->checkLogin ()) {
					// 登录成功
					$result = array (
							'status' => 1,
							'type' => 'success',
							'info' => '登录成功' 
					);
					// 记录admin_id
					$this->admin_id = $adminid;
					$this->addAdminLog ( "系统管理", "登录系统" );
					$this->ajaxReturn ( $result );
					exit ();
				}
			}
			// 没有通过验证，输出错误提示
			$result = array (
					'status' => 0,
					'type' => 'error',
					'info' => $adminModel->getError () 
			);
			$this->ajaxReturn ( $result );
		}
	}
	
	/**
	 * 定义生成验证码方法
	 *
	 * @author lhk(2015/12/31)
	 */
	public function verify() {
		// 生成验证码对象
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
		cookie ( 'admin_id', null );
		$this->addAdminLog ( "系统管理", "退出系统" );
		// 跳转：登录界面
		$this->success ( '退出成功！', U ( 'Login/index' ) );
	}
}