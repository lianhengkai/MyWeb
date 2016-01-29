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
	 * @author lhk(2016/01/28)
	 */
	public function addAdminLog($admin_id, $admin_log_type, $admin_log_content) {
		$data = array (
				'admin_id' => $admin_id,
				'admin_log_type' => $admin_log_type,
				'admin_log_content' => $admin_log_content,
				'admin_log_time' => time (),
				'admin_log_ip' => get_client_ip ( 0, true ) 
		);
		$this->add ( $data );
	}
	
	/**
	 * 系统日志数据
	 *
	 * @author lhk(2016/01/28)
	 */
	public function logData() {
		$field = "l.admin_log_id,a.admin_realname,l.admin_log_type,l.admin_log_content,l.admin_log_time,l.admin_log_ip";
		$where = array (
				'admin_log_status' => ':admin_log_status' 
		);
		$bind = array (
				':admin_log_status' => array (
						1,
						\PDO::PARAM_INT 
				) 
		);
		
		$list = $this->field($field)->join(" l LEFT JOIN my_admin a ON l.admin_id = a.admin_id")->where($where)->bind($bind)->select();
		
		return $list;
	}
}