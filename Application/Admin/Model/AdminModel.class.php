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
			'admin_sex',
			'admin_tel',
			'admin_remark',
			'admin_hits',
			'admin_ip',
			'admin_logintime',
			'admin_addtime',
			'admin_open',
			'admin_status',
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
	public $validate_login = array (
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
	 * 添加管理员表添加的验证规则 使用自定义验证规则（动态验证）
	 *
	 * @author lhk(2016/02/16)
	 */
	public $validate_add = array (
			array (
					'admin_username',
					'require',
					'用户名不能为空' 
			),
			array (
					'admin_username',
					'4,20',
					'用户名请填写4到20位任意字符！',
					1,
					'length' 
			),
			array (
					'admin_username',
					'',
					'用户名已经存在！',
					1,
					'unique' 
			),
			array (
					'admin_realname',
					'require',
					'真实姓名不能为空' 
			),
			array (
					'admin_realname',
					'2,10',
					'真实姓名请填写2到10位任意字符！',
					1,
					'length' 
			),
			array (
					'admin_pwd',
					'require',
					'密码不能为空' 
			),
			array (
					'admin_pwd',
					'6,20',
					'密码请填写6到20位任意字符！',
					1,
					'length' 
			),
			array (
					'admin_pwd2',
					'require',
					'请再输入一次新密码！' 
			),
			array (
					'admin_pwd2',
					'admin_pwd',
					'您两次输入的新密码不一致！',
					1,
					'confirm' 
			),
			array (
					'admin_sex',
					'require',
					'请选择性别！' 
			),
			array (
					'admin_tel',
					'require',
					'手机不能为空' 
			),
			array (
					'admin_tel',
					'/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}|17[0-9]{9}$/',
					'请填写手机号码！',
					1,
					'regex' 
			),
			array (
					'admin_email',
					'email',
					'请输入邮箱！' 
			) 
	);
	
	/**
	 * 添加管理员表添加的验证规则 使用自定义验证规则（动态验证）
	 *
	 * @author lhk(2016/03/01)
	 */
	public $validate_edit = array (
			array (
					'admin_username',
					'require',
					'用户名不能为空' 
			),
			array (
					'admin_username',
					'4,20',
					'用户名请填写4到20位任意字符！',
					1,
					'length' 
			),
			array (
					'admin_username',
					'',
					'用户名已经存在！',
					1,
					'unique' 
			),
			array (
					'admin_realname',
					'require',
					'真实姓名不能为空' 
			),
			array (
					'admin_realname',
					'2,10',
					'真实姓名请填写2到10位任意字符！',
					1,
					'length' 
			),
			array (
					'admin_pwd',
					'6,20',
					'密码请填写6到20位任意字符！',
					2,
					'length' 
			),
			array (
					'admin_pwd2',
					'admin_pwd',
					'您两次输入的新密码不一致！',
					1,
					'confirm' 
			),
			array (
					'admin_sex',
					'require',
					'请选择性别！' 
			),
			array (
					'admin_tel',
					'require',
					'手机不能为空' 
			),
			array (
					'admin_tel',
					'/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}|17[0-9]{9}$/',
					'请填写手机号码！',
					1,
					'regex' 
			),
			array (
					'admin_email',
					'email',
					'请输入邮箱！' 
			) 
	);
	
	/**
	 * 检查验证码是否正确
	 *
	 * @author lhk(2016/01/05)
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
				'admin_pwd' => ':admin_pwd',
				'admin_status' => 1 
		);
		$key = array (
				':admin_username' => array (
						I ( 'post.admin_username' ),
						\PDO::PARAM_STR 
				),
				':admin_pwd' => array (
						my_password ( I ( 'post.admin_pwd' ) ),
						\PDO::PARAM_STR 
				) 
		);
		$admin_info = $this->field ( $field )->where ( $where )->bind ( $key )->find ();
		if ($admin_info) {
			if ($admin_info ['admin_open'] != 1) {
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
					'admin_ip' => get_client_ip ( 0, true ) 
			);
			$this->save ( $data );
			return $data ['admin_id'];
		}
		// 没有通过验证，返回false
		$this->error = '用户名或密码错误！';
		return false;
	}
	
	/**
	 * 管理员列表数据
	 *
	 * @author lhk(2016/01/31)
	 */
	public function adminData($conditions) {
		$field = '*';
		$where = array (
				'admin_status' => 1 
		);
		$key = array ();
		
		if ($conditions ['start_date'] != '' && $conditions ['end_date'] != '') {
			$where ['admin_addtime'] = array (
					array (
							'EGT',
							':start_date' 
					),
					array (
							'ELT',
							':end_date' 
					),
					'AND' 
			);
			$key [':start_date'] = array (
					strtotime ( $conditions ['start_date'] ),
					\PDO::PARAM_INT 
			);
			$key [':end_date'] = array (
					strtotime ( $conditions ['end_date'] ),
					\PDO::PARAM_INT 
			);
		} elseif ($conditions ['start_date'] != '' && $conditions ['end_date'] == '') {
			$where ['admin_addtime'] = array (
					'EGT',
					':start_date' 
			);
			$key [':start_date'] = array (
					strtotime ( $conditions ['start_date'] ),
					\PDO::PARAM_INT 
			);
		} elseif ($conditions ['start_date'] == '' && $conditions ['end_date'] != '') {
			$where ['admin_addtime'] = array (
					'ELT',
					':end_date' 
			);
			$key [':end_date'] = array (
					strtotime ( $conditions ['end_date'] ),
					\PDO::PARAM_INT 
			);
		}
		
		if ($conditions ['keywords'] != '') {
			$where ['admin_username|admin_realname'] = array (
					array (
							'LIKE',
							':keywords' 
					),
					array (
							'LIKE',
							':keywords' 
					),
					'_multi' => true 
			);
			$key [':keywords'] = array (
					'%' . $conditions ['keywords'] . '%',
					\PDO::PARAM_STR 
			);
		}
		
		$list = $this->field ( $field )->where ( $where )->bind ( $key )->select ();
		return $list;
	}
	
	/**
	 * 检查用户名唯一性
	 *
	 * @author lhk(2016/02/16)
	 */
	public function checkAdminUnique($admin_id, $admin_username) {
		$where = array (
				'admin_username' => ':admin_username' 
		);
		$key = array (
				':admin_username' => array (
						$admin_username,
						\PDO::PARAM_STR 
				) 
		);
		
		if ($admin_id != 0) {
			$where ['admin_id'] = array (
					'NEQ',
					':admin_id' 
			);
			
			$key [':admin_id'] = array (
					$admin_id,
					\PDO::PARAM_INT 
			);
		}
		
		return ! ( boolean ) $this->where ( $where )->bind ( $key )->select ();
	}
	
	/**
	 * 钩子函数 _before_insert()
	 *
	 * @author lhk(2016/02/16)
	 */
	protected function _before_insert(&$data, $options) {
		// 时间
		$data ['admin_addtime'] = time ();
		$data ['admin_pwd'] = my_password ( $data ['admin_pwd'] );
	}
	
	/**
	 * 钩子函数 _before_update()
	 *
	 * @author lhk(2016/03/01)
	 */
	protected function _before_update(&$data, $options) {
		if($data ['admin_pwd'] != '') {
			$data ['admin_pwd'] = my_password ( $data ['admin_pwd'] );
		}
	}
	
	/**
	 * 删除管理员(伪删除)
	 *
	 * @author lhk(2016/02/17)
	 */
	public function deleteAdmin($id) {
		$where = array (
				'admin_id' => ':admin_id' 
		);
		$key = array (
				':admin_id' => array (
						$id,
						\PDO::PARAM_INT 
				) 
		);
		$data = array (
				'admin_status' => 0 
		);
		
		return $this->where ( $where )->bind ( $key )->save ( $data );
	}
}