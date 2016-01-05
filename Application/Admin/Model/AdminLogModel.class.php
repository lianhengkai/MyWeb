<?php

namespace Admin\Model;

use Think\Model;

/**
 * 管理员操作日志表模型
 *
 * @author lhk(2016/01/04)
 */
class AdminLogModel extends Model {
	/**
	 * 定义数据表字段信息
	 * 
	 * @author lhk(2016/01/05)
	 */
	protected $fields = array (
			'admin_log_id',
			'admin_id',
			'admin_log_type',
			'admin_log_content',
			'admin_log_time',
			'admin_log_ip',
			'admin_log_status',
			'_autoinc' => true 
	);
	
	/**
	 * 定义数据表主键
	 * 
	 * @author lhk(2016/01/05)
	 */
	protected $pk = 'admin_log_id';
	
	/**
	 * 添加操作日志方法
	 *
	 * @author lhk(2016/01/04)
	 */
	public function addAdminLog($admin_id, $admin_log_type, $admin_log_content) {
		$data = array (
				'admin_id' => $admin_id,
				'admin_log_type' => $admin_log_type,
				'admin_log_content' => $admin_log_content,
				'admin_log_time' => time (),
				'admin_log_ip' => get_client_ip () 
		);
		$this->add ( $data );
	}
}