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
		$adminLogModel = D ( 'AdminLog' );
		$list = $adminLogModel->logData ();
		
		$data = array ();
		foreach ( $list as $k => $v ) {
			$data ['data'] [$k] = array (
					"<input value=\"{$v['admin_log_id']}\" name=\"id\" type=\"checkbox\" />",
					$v ['admin_log_id'],
					$v ['admin_realname'],
					$v ['admin_log_type'],
					$v ['admin_log_content'],
					date ( 'Y-m-d H:i:s', $v ['admin_log_time'] ),
					$v ['admin_log_ip'],
					"<a title=\"删除\" href=\"javascript:;\" onclick=\"log_del(this,'{$v['admin_log_id']}')\" class=\"ml-5\" style=\"text-decoration:none\"><i class=\"Hui-iconfont\">&#xe6e2;</i></a>" 
			);
		}
		
		$this->ajaxReturn ( $data );
	}
	
	/**
	 * 我的桌面
	 *
	 * @author lhk(2016/01/05)
	 */
	public function welcome() {
		$this->admin_hits = session ( 'admin' ) ['admin_hits'] + 1;
		$this->display ();
	}
}