<?php
namespace Database;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{
    public static function init()
    {
        $capsule = new Capsule;
        $capsule->addConnection( self::connection() );
        $capsule->bootEloquent();
        $capsule->setAsGlobal();
        return [
            'schema' => $capsule->schema(),
            'db'     => $capsule->getConnection(),
        ];
    }

	public static function connection()  {
        return [
            'driver'    => 'pgsql',
            'host'      => getenv('DB_HOST'),
            'port'      => getenv('DB_PORT'),
            'database'  => getenv('DB_NAME'),
            'username'  => getenv('DB_USER'),
            'password'  => getenv('DB_PASSWORD'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => ''
        ];
    }

    public static function connect()
    {
        return new \PDO('pgsql:host='. getenv('DB_HOST') .';port='. getenv('DB_PORT') .';dbname='. getenv('DB_NAME') .';user='. getenv('DB_USER') .';password='. getenv('DB_PASSWORD') );
    }
}