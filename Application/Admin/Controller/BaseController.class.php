<?php

namespace Admin\Controller;

use Think\Controller;

/**
 * 后台管理基础控制器
 *
 * @author lhk(2015/12/31)
 */
class BaseController extends Controller {
	
	/**
	 * 初始化方法
	 * 
	 * @author lhk(2015/12/31)
	 */
	protected function _initialize() {
		$this->redirect('Login/index');
	}
	
	/**
	 * 空方法
	 *
	 * @author lhk(2015/12/31)
	 */
	public function _empty() {
		$this->display ( 'Common/404' );
	}
}