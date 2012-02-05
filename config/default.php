<?php

return array(
	'destination'  => 'api',
	'update-check' => 'no',
	'exclude'      => array(
		// The stub is not "real" PHP and breaks the ApiGen parser
		'*/laravel/cli/tasks/migrate/stub.php',

		// Migration class names do not have to be unique, but this causes
		// problems with the ApiGen parser
		'*/migrations/*',
	),
);
