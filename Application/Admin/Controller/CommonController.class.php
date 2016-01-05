<?php

namespace Admin\Controller;

use Think\Controller;

/**
 * 后台管理基础控制器
 *
 * @author lhk(2015/12/31)
 */
class CommonController extends Controller {
	
	/**
	 * 初始化方法
	 *
	 * @author lhk(2015/12/31)
	 */
	protected function _initialize() {
		if (CONTROLLER_NAME != 'Login') {
			// 判断用户是否登录（cookie）
			if (! session ( '?admin' ) && isset ( $_COOKIE ['admin_id'] )) {
				// 说明曾经登录过：记住用户：帮助用户登录
				$adminModel = D ( 'Admin' );
				// 判断
				$field = "admin_id,admin_username,admin_realname,admin_hits,admin_open,admin_ip,admin_logintime";
				if ($admin_info = $adminModel->field ( $field )->find ( cookie ( 'admin_id' ) )) {
					if ($admin_info ['admin_open'] != 1) {
						// 不允许登录，返回false
						$this->error ( '该帐号被禁止登录，请联系管理员', U ( 'Login/index' ) );
						return false;
					}
					// 登录成功 设置session
					session ( 'admin', $admin_info );
					// 更新最后登录时间和IP
					$data = array (
							'admin_id' => $admin_info ['admin_id'],
							'admin_hits' => $admin_info ['admin_hits'] + 1,
							'admin_logintime' => time (),
							'admin_ip' => get_client_ip () 
					);
					$adminModel->save ( $data );
				} else {
					// 失败，显示错误信息
					$this->error ( '您还没登录！', U ( 'Login/index' ) );
				}
			}
			// 判断（session）
			if (! session ( '?admin' )) {
				// 失败，显示错误信息
				$this->error ( '您还没登录！', U ( 'Login/index' ) );
			}
		}
		// $this->redirect('Login/index');
	}
	
	/**
	 * 添加管理员操作日志方法
	 *
	 * @author lhk(2016/01/04)
	 */
	protected function addAdminLog($admin_log_type, $admin_log_content) {
		$adminLogModel = D ( 'AdminLog' );
		$adminLogModel->addAdminLog ( $this->admin_id, $admin_log_type, $admin_log_content );
	}
	
	/**
	 * 空方法
	 *
	 * @author lhk(2015/12/31)
	 */
	protected function _empty() {
		$this->display ( 'Common/404' );
	}
}