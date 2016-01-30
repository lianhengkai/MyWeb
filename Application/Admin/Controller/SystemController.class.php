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
		$data = $adminLogModel->logData ( $conditions );
		
		$this->ajaxReturn ( $data );
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