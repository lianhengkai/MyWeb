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
	 * 添加管理员表添加的验证规则 使用自定义验证规则（动态验证）
	 *
	 * @author lhk(2016/02/16)
	 */
	public $validate_add = array (
			array (
					'name',
					'require',
					'规则名不能为空' 
			),
			array (
					'name',
					'1,80',
					'规则名请填写1到80位任意字符！',
					1,
					'length' 
			),
			array (
					'name',
					'',
					'规则名已经存在！',
					1,
					'unique' 
			),
			array (
					'admin_realname',
					'require',
					'真实姓名不能为空' 
			),
			array (
					'title',
					'1,20',
					'中文名称请填写1到20位任意字符！',
					1,
					'length' 
			),
			array (
					'pid',
					'require',
					'请选择父规则！' 
			),
			array (
					'status',
					'require',
					'请选择状态！' 
			),
			array (
					'sort',
					'number',
					'请填写数字！' 
			) 
	);
	
	/**
	 * 钩子函数 _before_insert()
	 *
	 * @author lhk(2016/02/16)
	 */
	protected function _before_insert(&$data, $options) {
		// 时间
		$data ['addtime'] = time ();
	}
	
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
	 * @param int $pid=0,需要查询父分类ID,默认为0表示找顶级分类        	
	 * @param int $level=0,默认方法被调用的层级,默认是第一层为0        	
	 * @return array已经进行无限极分类的二维数组
	 * @author lhk(2016/02/23)
	 */
	private function _ruleData($list, $stop_id = 0, $pid = 0, $level = 0) {
		// 定义数组保存最终结果
		static $lists = array ();
		
		// 查询所有顶级分类
		foreach ( $list as $v ) {
			// 排除当前自己分类及其分类
			if ($v ['id'] != $stop_id) {
				// 顶级分类: pid == 0
				if ($v ['pid'] == $pid) {
					//将当前层级加到商品分类中
					$v['level'] = $level;
					
					$lists [] = $v; // 子分类有可能有自己的子分类
					                
					// 递归点: 调用父问题的解决方案解决子问题
					$this->_ruleData ( $list, $stop_id, $v ['id'], $level + 1 );
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
	
	/**
	 * 检查规则名唯一性
	 *
	 * @author lhk(2016/03/01)
	 */
	public function checkRuleUnique($id, $name) {
		$where = array (
				'name' => ':name',
				'module' => 'Admin'
		);
		$key = array (
				':name' => array (
						$name,
						\PDO::PARAM_STR
				)
		);
	
		if ($admin_id != 0) {
			$where ['id'] = array (
					'NEQ',
					':id'
			);
				
			$key [':id'] = array (
					$admin_id,
					\PDO::PARAM_INT
			);
		}
	
		return ! ( boolean ) $this->where ( $where )->bind ( $key )->select ();
	}
}