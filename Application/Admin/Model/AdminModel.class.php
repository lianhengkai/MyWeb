<?php

namespace Admin\Model;

use Think\Model;

/**
 * 管理员表模型
 *
 * @author lhk(2016/01/04)
 */
class AdminModel extends Model {
	/**
	 * 定义数据表字段信息
	 *
	 * @author lhk(2016/01/05)
	 */
	protected $fields = array (
			'admin_id',
			'admin_username',
			'admin_pwd',
			'admin_email',
			'admin_realname',
			'admin_tel',
			'admin_hits',
			'admin_ip',
			'admin_logintime',
			'admin_addtime',
			'admin_open',
			'_autoinc' => true 
	);
	
	/**
	 * 定义数据表主键
	 *
	 * @author lhk(2016/01/05)
	 */
	protected $pk = 'admin_id';
	
	/**
	 * 添加管理员表登录的验证规则 使用自定义验证规则（动态验证）
	 *
	 * @author lhk(2016/01/05)
	 */
	public $_validate_login = array (
			array (
					'admin_username',
					'require',
					'登录用户名不能为空' 
			),
			array (
					'admin_pwd',
					'require',
					'登录密码不能为空' 
			),
			array (
					'verify',
					'require',
					'验证码不能为空' 
			),
			array (
					'verify',
					'checkVerify',
					'验证码错误',
					1,
					'callback' 
			) 
	);
	
	/**
	 * 检查验证码是否正确
	 */
	protected function checkVerify($verify) {
		// 检查验证码是否正确
		$verifyObject = new \Think\Verify ();
		return $verifyObject->check ( $verify );
	}
	
	/**
	 * 登录验证方法
	 *
	 * @author lhk(2016/01/05)
	 */
	public function checkLogin() {
		// 通过I方法接收用户名与密码，系统会自动过滤数据
		$admin_username = I ( 'post.admin_username' );
		$admin_pwd = I ( 'post.admin_pwd' );
		// 根据管理员查找用户数据，取出密码，和输入的密码进行匹配
		$field = "admin_id,admin_username,admin_realname,admin_hits,admin_open,admin_ip,admin_logintime";
		$where = array (
				'admin_username' => ':admin_username',
				'admin_pwd' => ':admin_pwd' 
		);
		$bind = array (
				':admin_username' => array (
						I ( 'post.admin_username' ),
						\PDO::PARAM_STR 
				),
				':admin_pwd' => array (
						my_password ( I ( 'post.admin_pwd' ) ),
						\PDO::PARAM_STR 
				) 
		);
		$admin_info = $this->field ( $field )->where ( $where )->bind ( $bind )->find ();
		if ($admin_info) {
			if($admin_info['admin_open'] != 1) {
				// 不允许登录，返回false
				$this->error = '该帐号被禁止登录，请联系管理员';
				return false;
			}
			session ( 'admin', $admin_info );
			if (( int ) I ( 'post.online' ) == 1) {
				// 用户选择记住信息
				cookie ( 'admin_id', $admin_info ['admin_id'], 3600 * 24 * 7 );
			}
			// 更新最后登录时间和IP
			$data = array (
					'admin_id' => $admin_info ['admin_id'],
					'admin_hits' => $admin_info ['admin_hits'] + 1,
					'admin_logintime' => time (),
					'admin_ip' => get_client_ip () 
			);
			$this->save ( $data );
			return true;
		}
		// 没有通过验证，返回false
		$this->error = '用户名或密码错误！';
		return false;
	}
}