<?php 

return array(
	'kernel' => array(
		'class' => 'Moulino\\Framework\\Core\\Kernel',
		'arguments' => array('@container', '%app.mode%', '%app.charset%')
	),

	'request' => array(
		'class' => 'Moulino\\Framework\\Http\\Request',
		'arguments' => array('%app.locale%')
	),

	'database' => array(
		'class' => 'Moulino\\Framework\\Database\\Database',
		'arguments' => array('%database%')
	),

	'logger' => array(
		'class' => 'Moulino\\Framework\\Log\\Logger',
		'arguments' => array('@mailer', '%logger%', '%app.mode%', '%app.date_format%')
	),

	'mail_logger' => array(
		'class' => 'Moulino\\Framework\\Log\\MailLogger',
		'arguments' => array('%app.date_format%')
	),

	'validator' => array(
		'class' => 'Moulino\\Framework\\Validation\\Validator',
		'arguments' => array('@translator', '%entities%')
	),

	'session' => array(
		'class' => 'Moulino\\Framework\\Session\\Session'
	),

	'translator' => array(
		'class' => 'Moulino\\Framework\\Translation\\Translator',
		'arguments' => array('@translation_container', '@request')
	),

	'translation_container' => array(
		'class' => 'Moulino\\Framework\\Translation\\Container',
		'arguments' => array('@logger')
	),

	'translation_loader' => array(
		'class' => 'Moulino\\Framework\\Translation\\Loader',
		'arguments' => array('@translation_container')
	),

	'router' => array(
		'class' => 'Moulino\\Framework\\Routing\\Router'
	),

	'routes_loader' => array(
		'class' => 'Moulino\\Framework\\Routing\\Loader',
		'arguments' => array('@router', '@route_validator', '%routes%')
	),

	'route_validator' => array(
		'class' => 'Moulino\\Framework\\Routing\\RouteValidator',
		'arguments' => array('@translator')
	),

	'view' => array(
		'class' => 'Moulino\\Framework\\View\\Engine',
		'arguments' => array('@container', '%view%', '%app.mode%')
	),

	'firewall' => array(
		'class' => 'Moulino\\Framework\\Firewall\\AccessControl',
		'arguments' => array('@authenticator', '@translator')
	),

	'firewall_loader' => array(
		'class' => 'Moulino\\Framework\\Firewall\\Loader',
		'arguments' => array('@firewall', '%security.firewall_rules%')
	),

	'password_encoder' => array(
		'class' => 'Moulino\\Framework\\Auth\\PasswordEncoder',
		'arguments' => array('%security.salt%')
	),

	'authenticator' => array(
		'class' => array('Moulino\\Framework\\Auth\\AuthenticatorLoader','getClass'),
		'arguments' => array('@container', '@%security.entity%_model')
	),

	'exception_handler' => array(
		'class' => 'Moulino\\Framework\\Core\\ExceptionHandler',
		'arguments' => array('@logger', '@view', '%app.mode%')
	),

	'error_handler' => array(
		'class' => 'Moulino\\Framework\\Core\\ErrorHandler',
		'arguments' => array('@logger')
	),

	'mailer' => array(
		'class' => 'Moulino\\Framework\\Mail\\Mailer',
		'arguments' => array('@mail_logger')
	)
);

?>