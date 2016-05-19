<?php 

return array(
	'kernel' => array(
		'class' => 'Moulino\\Framework\\Core\\Kernel',
		'arguments' => array('@router', '@firewall', '@translator', '@error_handler', '%app.charset%')
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
		'arguments' => array('@translation_container')
	),

	'translation_container' => array(
		'class' => 'Moulino\\Framework\\Translation\\Container',
		'arguments' => array('@logger')
	),

	'translation_loader' => array(
		'class' => 'Moulino\\Framework\\Translation\\Loader',
		'arguments' => array('@translation_container', '%app.language%')
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
		'arguments' => array('@authenticator', '%app.mode%')
	),

	'firewall' => array(
		'class' => 'Moulino\\Framework\\Firewall\\AccessControl',
		'arguments' => array('@authenticator', '@translator')
	),

	'firewall_loader' => array(
		'class' => 'Moulino\\Framework\\Firewall\\Loader',
		'arguments' => array('@firewall', '%security.firewall_rules%')
	),

	'authenticator' => array(
		'class' => 'Moulino\\Framework\\Auth\\Authenticator',
		'arguments' => array('@session', '@%security.entity%_model', '@translator', '%security.salt%')
	),

	'error_handler' => array(
		'class' => 'Moulino\\Framework\\Core\\ErrorHandler',
		'arguments' => array('@logger', '@view', '%app.mode%')
	),

	'mailer' => array(
		'class' => 'Moulino\\Framework\\Mail\\Mailer',
		'arguments' => array('@mail_logger')
	),
);

?>