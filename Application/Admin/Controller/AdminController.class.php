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
			$handle = $v ['admin_open'] == 1 ? "<a style=\"text-decoration:none\" onclick=\"admin_open('{$v['admin_id']}', 0)\" href=\"javascript:;\" title=\"停用\"><i class=\"Hui-iconfont\">&#xe631;</i></a>" : "<a style=\"text-decoration:none\" onclick=\"admin_open('{$v['admin_id']}', 1)\" href=\"javascript:;\" title=\"启用\"><i class=\"Hui-iconfont\">&#xe615;</i></a>";
			$data [$k] = array (
					'checkbox' => "<input value=\"{$v['admin_id']}\" name=\"id\" type=\"checkbox\" />",
					'admin_id' => $v ['admin_id'],
					'admin_username' => $v ['admin_username'],
					'admin_realname' => $v ['admin_realname'],
					'admin_email' => $v ['admin_email'],
					'admin_tel' => $v ['admin_tel'],
					'admin_addtime' => date ( 'Y-m-d H:i:s', $v ['admin_addtime'] ),
					'admin_open' => $v ['admin_open'] == 1 ? '<span class="label label-success radius">已启用</span>' : '<span class="label radius">已停用</span>',
					'handle' => $handle . "<a title=\"编辑\" href=\"javascript:;\" onclick=\"admin_edit('管理员编辑','admin-add.html','1','800','500')\" class=\"ml-5\" style=\"text-decoration:none\"><i class=\"Hui-iconfont\">&#xe6df;</i></a><a title=\"删除\" href=\"javascript:;\" onclick=\"delete_one('{$v['admin_id']}')\" class=\"ml-5\" style=\"text-decoration:none\"><i class=\"Hui-iconfont\">&#xe6e2;</i></a>" 
			);
		}
		
		$this->ajaxReturn ( array (
				'data' => $data 
		) );
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