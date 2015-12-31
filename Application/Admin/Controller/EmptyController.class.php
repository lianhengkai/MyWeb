<?php

namespace Admin\Controller;

use Think\Controller;

/**
 * 空控制器
 *
 * @author lhk(2015/12/31)
 */
class EmptyController extends Controller {
	/**
	 * 空方法
	 *
	 * @author lhk(2015/12/31)
	 */
	public function _empty() {
		$this->display ( 'Common/404' );
	}
}