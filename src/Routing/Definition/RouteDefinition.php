<?php 

return array(
	'path' => array(
		'require' => true,
		'match' => '[\w/]+'
	),
	'method' => array(
		'require' => true,
		'match' => 'GET|POST|PUT|DELETE'
	),
	'callable' => array(
		'require' => true,
		'match' => '^\w+:\w+$'
	),
	'requirements' => array(
		'require' => false,
	)
);

?>