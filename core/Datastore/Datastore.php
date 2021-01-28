<?php
namespace Core\Datastore;

class Datastore
{
    public static function dump(\Core\Qgis\Maplayer $maplayer): DatastoreModel
    {
        $dump = new Dump($maplayer);
        $dump->fileExists()->process()->updateDatasource();
        return self::log([
            'project_name'      => $dump->project_name,
            'layer_name'        => $dump->layer_name,
            'tablename'         => $dump->tablename,
            'tablename_origin'  => $dump->tablename_origin,
        ]);
    }

    public static function drop(string $tablename): int
    {
        $drop = new Drop($tablename);
        $drop->deleteTableIfExists();
        return self::unlog($tablename);
    }

    private static function log(array $data): DatastoreModel
    {
        return DatastoreModel::create($data);
    }

    private static function unlog(string $tablename): int
    {
        return DatastoreModel::where('tablename', $tablename)->delete();
    }

    public static function getParams(): array
    {
        return [
            'driver'    => 'PostgreSQL',
            'host'      => getenv('DB_HOST'),
            'port'      => getenv('DB_PORT'),
            'dbname'    => getenv('DB_NAME'),
            'user'      => getenv('DB_USER'),
            'password'  => getenv('DB_PASSWORD'),
            'provider'  => 'postgres',
            'database'  => 'dbname=\''. getenv('DB_NAME') .'\' host=\''. getenv('DB_HOST') .'\' port=\''. getenv('DB_PORT') .'\' user=\''. getenv('DB_USER') .'\' password=\''. getenv('DB_PASSWORD') .'\''
        ];
    }

    public static function connect(): \PDO
    {
        return \Database\Database::connect();
    }
}