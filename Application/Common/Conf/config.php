<?php
return array (
		// '配置项'=>'配置值'
		// 模板替换
		'TMPL_PARSE_STRING' => array (
				// 增加新的路径替换规则
				'__HOME__' => '/Public/Home',
				'__ADMIN__' => '/Public/Admin' 
		),
	
		/* 数据库设置 */
		'DB_TYPE' => 'mysql', // 数据库类型
		'DB_HOST' => 'localhost', // 服务器地址
		'DB_NAME' => 'MyWeb', // 数据库名
		'DB_USER' => 'root', // 用户名
		'DB_PWD' => '', // 密码
		'DB_PORT' => '1117', // 端口
		'DB_PREFIX' => 'my_', // 数据库表前缀
		
		'PASSWORD_TOKEN' => 'MyWebToken20160105', // 不要修改这一项
		                                         
); 

