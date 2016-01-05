<?php

namespace Admin\Controller;

/**
 * 后台首页控制器
 * 
 * @author lhk(2015/12/31)
 */
class IndexController extends CommonController {
	/**
	 * 默认显示首页
	 * 
	 * @author lhk(2015/12/31)
	 */
	public function index() {
		
		$this->display();
	}
	
	/**
	 * 我的桌面
	 * 
	 * @author lhk(2016/01/05)
	 */
	public function welcome() {
		$this->admin_hits = session('admin')['admin_hits'] + 1;
		$this->display();
	}
}