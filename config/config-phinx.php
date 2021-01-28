<?php
require __DIR__ . '/bootstrap.php';
return [
  'paths' => [
		'migrations' => dirname(__DIR__) . '/db/migrations',
		'seeds' => dirname(__DIR__) . '/db/seeds',
  ],
  'migration_base_class' => '\Database\Migrations\Migration',
  'environments' => [
		'default_migration_table' => 'phinxlog',
		'default_database' => 'pgsql',
		'pgsql' => [
			'adapter' 	=> 'pgsql',
			'host' 		=> getenv('DB_HOST'),
			'name'		=> getenv('DB_NAME'),
			'user'		=> getenv('DB_USER'),
			'pass'		=> getenv('DB_PASSWORD'),
			'port'	 	=> getenv('DB_PORT'),
			'charset' 	=> 'utf8',
			'collation'	=> 'utf8_unicode_ci'
		]
  ]
];