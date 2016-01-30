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
	public function logData($conditions) {
		$field = "l.admin_log_id,a.admin_realname,l.admin_log_type,l.admin_log_content,l.admin_log_time,l.admin_log_ip";
		$where = array (
				'admin_log_status' => ':admin_log_status' 
		);
		$key = array (
				':admin_log_status' => array (
						1,
						\PDO::PARAM_INT 
				) 
		);
		
		if ($conditions ['start_date'] != '' && $conditions ['end_date'] != '') {
			$where ['admin_log_time'] = array (
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
			$where ['admin_log_time'] = array (
					'EGT',
					':start_date' 
			);
			$key [':start_date'] = array (
					strtotime ( $conditions ['start_date'] ),
					\PDO::PARAM_INT 
			);
		} elseif ($conditions ['start_date'] == '' && $conditions ['end_date'] != '') {
			$where ['admin_log_time'] = array (
					'ELT',
					':end_date' 
			);
			$key [':end_date'] = array (
					strtotime ( $conditions ['end_date'] ),
					\PDO::PARAM_INT 
			);
		}
		
		if ($conditions ['keywords'] != '') {
			$where ['admin_realname|admin_log_type|admin_log_content'] = array (
					array (
							'LIKE',
							':keywords' 
					),
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
		
		$list = $this->field ( $field )->join ( "l LEFT JOIN my_admin a ON l.admin_id = a.admin_id" )->where ( $where )->bind ( $key )->select ();
		
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
		
		return array (
				'data' => $data 
		);
	}
	
	/**
	 * 删除系统日志(伪删除)
	 *
	 * @author lhk(2016/01/30)
	 */
	public function deleteLog($id) {
		$where = array (
				'admin_log_id' => ':admin_log_id' 
		);
		$key = array (
				':admin_log_id' => array (
						$id,
						\PDO::PARAM_INT 
				) 
		);
		$data = array (
				'admin_log_status' => 0 
		);
		
		return $this->where ( $where )->bind ( $key )->save ( $data );
	}
}