<?php
/**
 * 生成密码函数
 * 
 * @author lhk(2016/01/05)
 */
function my_password($password) {
	return md5 ( substr ( sha1 ( C ( 'PASSWORD_TOKEN' ) . $password ), 0, 20 ) );
}