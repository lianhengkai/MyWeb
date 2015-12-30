<?php

namespace Admin\Controller;

use Think\Controller;

/**
 * 登录控制器
 *
 * @author lhk(2015/12/22)
 */
class LoginController extends Controller {
	
	/**
	 * 默认显示登录页面
	 * 
	 * @author lhk(2015/12/30)
	 */
	public function index() {
		$this->display ();
	}
}