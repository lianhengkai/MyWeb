<?php

namespace Admin\Model;

use Think\Model;

/**
 * 规则表模型
 *
 * @author lhk(2016/02/23)
 */
class AuthRuleModel extends Model {
	/**
	 * 定义数据表字段信息
	 *
	 * @author lhk(2016/02/23)
	 */
	protected $fields = array (
			'id',
			'name',
			'title',
			'type',
			'status',
			'condition',
			'pid',
			'sort',
			'module',
			'icon',
			'addtime',
			'_autoinc' => true 
	);
	
	/**
	 * 定义数据表主键
	 *
	 * @author lhk(2016/01/05)
	 */
	protected $pk = 'id';
	
	/**
	 * 添加管理员表登录的验证规则 使用自定义验证规则（动态验证）
	 *
	 * @author lhk(2016/01/05)
	 */
	public $validate_login = array (
			array (
					'admin_username',
					'require',
					'登录用户名不能为空' 
			),
			array (
					'admin_pwd',
					'require',
					'登录密码不能为空' 
			),
			array (
					'verify',
					'require',
					'验证码不能为空' 
			),
			array (
					'verify',
					'checkVerify',
					'验证码错误',
					1,
					'callback' 
			) 
	);
	
	/**
	 * 添加管理员表添加的验证规则 使用自定义验证规则（动态验证）
	 *
	 * @author lhk(2016/02/16)
	 */
	public $validate_add = array (
			array (
					'admin_username',
					'require',
					'用户名不能为空' 
			),
			array (
					'admin_username',
					'2,16',
					'用户名请填写2到16位任意字符！',
					1,
					'length' 
			),
			array (
					'admin_username',
					'',
					'用户名已经存在！',
					1,
					'unique' 
			),
			array (
					'admin_realname',
					'require',
					'真实姓名不能为空' 
			),
			array (
					'admin_realname',
					'2,16',
					'真实姓名请填写2到16位任意字符！',
					1,
					'length' 
			),
			array (
					'admin_pwd',
					'require',
					'密码不能为空' 
			),
			array (
					'admin_pwd',
					'6,20',
					'密码请填写6到20位任意字符！',
					1,
					'length' 
			),
			array (
					'admin_pwd2',
					'require',
					'请再输入一次新密码！' 
			),
			array (
					'admin_pwd2',
					'admin_pwd',
					'您两次输入的新密码不一致！',
					1,
					'confirm' 
			),
			array (
					'admin_sex',
					'require',
					'请选择性别！' 
			),
			array (
					'admin_tel',
					'require',
					'手机不能为空' 
			),
			array (
					'admin_tel',
					'/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}|17[0-9]{9}$/',
					'请填写手机号码！',
					1,
					'regex' 
			),
			array (
					'admin_email',
					'email',
					'请输入邮箱！' 
			) 
	);
	
	/**
	 * 权限管理数据
	 *
	 * @author lhk(2016/02/23)
	 */
	public function ruleData($conditions) {
		$field = '*';
		$where = array (
				'module' => 'Admin' 
		);
		$key = array ();
		
		if ($conditions ['keywords'] != '') {
			$where ['name|title'] = array (
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
		
		$list = $this->field ( $field )->where ( $where )->bind ( $key )->order ( 'sort' )->select ();
		return $this->_ruleData ( $list );
	}
	
	/**
	 * 递归权限管理数据(无限级分类)
	 *
	 * @param array $list需要进行无限极分类的二维数组        	
	 * @param int $stop_id=0,不需要获取子分类的id        	
	 * @param int $parent_id=0,需要查询父分类ID,默认为0表示找顶级分类        	
	 * @param int $level=0,默认方法被调用的层级,默认是第一层为0        	
	 * @return array已经进行无限极分类的二维数组
	 * @author lhk(2016/02/23)
	 */
	private function _ruleData($list, $stop_id = 0, $pid = 0) {
		// 定义数组保存最终结果
		static $lists = array ();
		
		// 查询所有顶级分类
		foreach ( $list as $v ) {
			// 排除当前自己分类及其分类
			if ($v ['id'] != $stop_id) {
				// 顶级分类: pid == 0
				if ($v ['pid'] == $pid) {
					$lists [] = $v; // 子分类有可能有自己的子分类
					                
					// 递归点: 调用父问题的解决方案解决子问题
					$this->_ruleData ( $list, $stop_id, $v ['id'] );
				}
			}
		}
		// 递归出口: 数组遍历结束也没有一个满足条件的分类
		
		// 返回结果
		return $lists;
	}
	
	/**
	 * 检查指定ID下是否有子分类
	 *
	 * @author (2016/03/01)
	 */
	public function hasSon($id) {
		$res = $this->where ( array (
				'pid' => $id 
		) )->find ();
		
		return ( boolean ) $res;
	}
	
	/**
	 * 删除规则(真删除)
	 *
	 * @author lhk(2016/03/01)
	 */
	public function deleteRule($id) {
		$where = array (
				'id' => ':id' 
		);
		$key = array (
				':id' => array (
						$id,
						\PDO::PARAM_INT 
				) 
		);
		
		return $this->where ( $where )->bind ( $key )->delete ();
	}
}