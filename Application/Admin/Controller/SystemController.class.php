<?php

namespace Admin\Controller;

/**
 * 系统管理控制器
 *
 * @author lhk(2016/01/28)
 */
class SystemController extends CommonController {
	/**
	 * 系统日志显示页面
	 *
	 * @author lhk(2016/01/28)
	 */
	public function log() {
		$this->display ();
	}
	
	/**
	 * 系统日志数据
	 *
	 * @author lhk(2016/01/28)
	 */
	public function logData() {
		$conditions = array (
				'start_date' => I ( 'get.start_date' ),
				'end_date' => I ( 'get.end_date' ),
				'keywords' => I ( 'get.keywords' ) 
		);
		$adminLogModel = D ( 'AdminLog' );
		$list = $adminLogModel->logData ( $conditions );
		
		$data = array ();
		foreach ( $list as $k => $v ) {
			$data [$k] = array (
					'checkbox' => "<input value=\"{$v['admin_log_id']}\" name=\"id\" type=\"checkbox\" />",
					'admin_log_id' => $v ['admin_log_id'],
					'admin_realname' => $v ['admin_realname'],
					'admin_log_type' => $v ['admin_log_type'],
					'admin_log_content' => $v ['admin_log_content'],
					'admin_log_time' => date ( 'Y-m-d H:i:s', $v ['admin_log_time'] ),
					'admin_log_ip' => $v ['admin_log_ip'],
					'handle' => "<a title=\"删除\" href=\"javascript:;\" onclick=\"delete_one('{$v['admin_log_id']}')\" class=\"ml-5\" style=\"text-decoration:none\"><i class=\"Hui-iconfont\">&#xe6e2;</i></a>" 
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
	public function deleteLog() {
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
				$data = array (
						'status' => 1,
						'type' => 'success',
						'info' => '删除系统日志成功' 
				);
			} else {
				$data = array (
						'status' => 0,
						'type' => 'error',
						'info' => '删除系统日志失败，ID为：' . rtrim ( $delete_id, '，' ) 
				);
			}
			
			$this->ajaxReturn ( $data );
		}
	}
}