<?php

namespace Admin\Controller;

/**
 * 管理员管理控制器
 *
 * @author lhk(2016/01/31)
 */
class AdminController extends CommonController {
	/**
	 * 管理员列表显示页面
	 *
	 * @author lhk(2016/01/28)
	 */
	public function admin() {
		$this->display ();
	}
	
	/**
	 * 管理员列表数据
	 *
	 * @author lhk(2016/01/28)
	 */
	public function adminData() {
		$conditions = array (
				'start_date' => I ( 'get.start_date' ),
				'end_date' => I ( 'get.end_date' ),
				'keywords' => I ( 'get.keywords' ) 
		);
		$adminModel = D ( 'Admin' );
		$list = $adminModel->adminData ( $conditions );
		
		$data = array ();
		foreach ( $list as $k => $v ) {
			$handle = $v ['admin_open'] == 1 ? "<a style=\"text-decoration:none\" onclick=\"change_admin_open('{$v['admin_id']}', 0)\" href=\"javascript:;\" title=\"停用\"><i class=\"Hui-iconfont\">&#xe631;</i></a>" : "<a style=\"text-decoration:none\" onclick=\"change_admin_open('{$v['admin_id']}', 1)\" href=\"javascript:;\" title=\"启用\"><i class=\"Hui-iconfont\">&#xe615;</i></a>";
			$data [$k] = array (
					'checkbox' => "<input value=\"{$v['admin_id']}\" name=\"id\" type=\"checkbox\" />",
					'admin_id' => $v ['admin_id'],
					'admin_username' => $v ['admin_username'],
					'admin_realname' => $v ['admin_realname'],
					'admin_sex' => $v ['admin_sex'],
					'admin_email' => $v ['admin_email'],
					'admin_tel' => $v ['admin_tel'],
					'admin_addtime' => date ( 'Y-m-d H:i:s', $v ['admin_addtime'] ),
					'admin_open' => $v ['admin_open'] == 1 ? '<span class="label label-success radius">已启用</span>' : '<span class="label radius">已停用</span>',
					'handle' => $handle . "<a title=\"编辑\" href=\"javascript:;\" onclick=\"edit_admin('{$v['admin_id']}')\" class=\"ml-5\" style=\"text-decoration:none\"><i class=\"Hui-iconfont\">&#xe6df;</i></a><a title=\"删除\" href=\"javascript:;\" onclick=\"delete_one('{$v['admin_id']}')\" class=\"ml-5\" style=\"text-decoration:none\"><i class=\"Hui-iconfont\">&#xe6e2;</i></a>" 
			);
		}
		
		$this->ajaxReturn ( array (
				'data' => $data 
		) );
	}
	
	/**
	 * 添加管理员
	 *
	 * @author lhk(2016/02/16)
	 */
	public function addAdmin() {
		if (IS_AJAX) {
			$adminModel = D ( 'Admin' );
			
			if ($adminModel->validate ( $adminModel->validate_add )->create ()) {
				
				if ($adminModel->add () !== false) {
					$data = array (
							'status' => 1,
							'type' => 'success',
							'info' => '添加管理员成功' 
					);
					$this->addAdminLog ( "管理员管理", "添加管理员成功，插入ID为：" . $adminModel->getLastInsID () );
				} else {
					$data = array (
							'status' => 0,
							'type' => 'error',
							'info' => '添加管理员失败' 
					);
					$this->addAdminLog ( "管理员管理", "添加管理员失败，原因：插入数据库失败，SQL语句：" . $adminModel->getLastSql () );
				}
			} else {
				// 没有通过验证，输出错误提示
				$data = array (
						'status' => 0,
						'type' => 'error',
						'info' => $adminModel->getError () 
				);
			}
			
			$this->ajaxReturn ( $data );
		} else {
			$this->display ();
		}
	}
	
	/**
	 * 编辑管理员
	 *
	 * @author lhk(2016/02/16)
	 */
	public function editAdmin() {
		if (IS_AJAX) {
		} else {
			$this->display ();
		}
	}
	
	/**
	 * 改变管理员审核状态
	 *
	 * @author lhk(2016/02/16)
	 */
	public function changeAdminOpen() {
		if (IS_AJAX) {
			$data = array (
					'admin_id' => ( int ) I ( 'post.admin_id' ),
					'admin_open' => ( int ) I ( 'post.admin_open' ) 
			);
			
			$adminModel = D ( 'Admin' );
			
			if ($adminModel->save ( $data ) !== false) {
				$data = array (
						'status' => 1,
						'type' => 'success',
						'info' => '更改审核状态成功' 
				);
				$this->addAdminLog ( "管理员管理", "更改审核状态成功，更改ID为：" . $data ['admin_id'] );
			} else {
				$data = array (
						'status' => 0,
						'type' => 'error',
						'info' => '更改审核状态失败' 
				);
				$this->addAdminLog ( "管理员管理", "更改审核状态失败，更改ID为：" . $data ['admin_id'] );
			}
			
			$this->ajaxReturn ( $data );
		}
	}
	
	/**
	 * 检查用户名唯一性
	 *
	 * @author lhk(2016/02/16)
	 */
	public function checkAdminUnique() {
		if (IS_AJAX) {
			$admin_id = ( int ) I ( 'get.admin_id', 0 );
			$admin_username = I ( 'param' );
			
			$adminModel = D ( 'Admin' );
			
			if ($adminModel->checkAdminUnique ( $admin_id, $admin_username )) {
				$data = array (
						'info' => '',
						'status' => 'y' 
				);
			} else {
				$data = array (
						'info' => '该用户名已经存在！' 
				);
			}
			
			$this->ajaxReturn ( $data );
		}
	}
	
	/**
	 * 删除系统日志(伪删除)
	 *
	 * @author lhk(2016/01/30)
	 */
	public function deleteAdmin() {
		if (IS_AJAX) {
			$id_array = explode ( ',', rtrim ( I ( 'post.ids' ), ',' ) );
			$adminLogModel = D ( 'AdminLog' );
			
			$log_type = "系统管理";
			$delete_flag = true;
			$delete_id = '';
			
			foreach ( $id_array as $k => $v ) {
				if ($adminLogModel->deleteLog ( $v ) !== false) {
					$this->addAdminLog ( $log_type, "删除系统日志成功，ID为：{$v}" );
				} else {
					$delete_flag = false;
					$delete_id .= $v . '，';
					$this->addAdminLog ( $log_type, "删除系统日志失败，ID为：{$v}" );
				}
			}
			
			if ($delete_flag === true) {
				$result = array (
						'status' => 1,
						'type' => 'success',
						'info' => '删除系统日志成功' 
				);
			} else {
				$result = array (
						'status' => 0,
						'type' => 'error',
						'info' => '删除系统日志失败，ID为：' . rtrim ( $delete_id, '，' ) 
				);
			}
			
			$this->ajaxReturn ( $result );
		}
	}
}