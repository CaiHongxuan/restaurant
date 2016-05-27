<?php
return array(
	/* 项目设定 */
	'APP_GROUP_LIST'    => 'Admin,Index', // 项目分组设定,多个组之间用逗号分隔,例如'Home,Admin'
	'DEFAULT_GROUP'     => 'Index',  // 默认分组
	'APP_GROUP_MODE'    =>  1,  // 分组模式 0 普通分组 1 独立分组
	'APP_GROUP_PATH'    =>  'Modules', // 分组目录 独立分组模式下面有效

	/* 数据库设置 */
	'DB_TYPE'           => 'mysql',     // 数据库类型
	'DB_HOST'           => 'localhost', // 服务器地址
	'DB_NAME'           => 'restaurant',// 数据库名
	'DB_USER'           => 'root',      // 用户名
	'DB_PWD'            => '',      // 密码
	'DB_PREFIX'         => 'tp_',       // 数据库表前缀

	/* URL设置 */
    'URL_MODEL'             => 2,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式，提供最好的用户体验和SEO支持

	/* SESSION设置 */
	'SESSION_TYPE'      => 'Db', // (将session存储到数据库)session hander类型 默认无需设置 除非扩展了session hander驱动

	// 显示页面Trace信息
	// 'SHOW_PAGE_TRACE'   => true,

	/* 邮件配置 */
	'MAIL_ADDRESS'      => 'xxxxxx@sina.com', // 邮箱地址
	'MAIL_LOGINNAME'    => 'xxxxxx@sina.com', // 邮箱登录帐号
	'MAIL_SMTP'         => 'smtp.sina.com', // 邮箱SMTP服务器
	'MAIL_PASSWORD'     => 'password', // 邮箱密码

);
?>
