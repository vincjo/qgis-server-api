<?php
use Database\Database;
use Phinx\Seed\AbstractSeed;

class ViewFolderLayerSeeder extends AbstractSeed
{
    public function run()
    {
       Database::init()['db']->statement("CREATE VIEW view_folder_layer AS 
            WITH RECURSIVE subfolders (
                folder_id, layer_id, level, path, name, title, abstract, source, parent_id, role_id, 
                map, symbol_url, symbol_type, preview_url, protocol, search_field, created_at, updated_at
            ) AS (
                SELECT folders.id AS folder_id, layers.id AS layer_id,
                    0 as level,
                folders.title::TEXT AS path, 
                layers.name, layers.title, layers.abstract, layers.source, parent_id, layers.role_id, 
                    layers.map, layers.symbol_url, layers.symbol_type, layers.preview_url, layers.protocol, 
                UPPER(
                    folders.title::TEXT || 
                    CASE WHEN layers.title IS NULL THEN '' ELSE layers.title::TEXT END || 
                    CASE WHEN layers.abstract IS NULL THEN '' ELSE layers.abstract::TEXT END || 
                    CASE WHEN layers.source IS NULL THEN '' ELSE layers.source::TEXT END
                ) AS search_field,
                    layers.created_at, layers.updated_at
                FROM gis_folders folders
                LEFT JOIN layers ON layers.folder_id = folders.id
                WHERE parent_id = 0

                UNION ALL SELECT 
                    folders.id AS folder_id, layers.id AS layer_id,
                    subfolders.level + 1,
                subfolders.path::TEXT || ' / ' || folders.title::TEXT, 
                layers.name, layers.title, layers.abstract, layers.source, folders.parent_id, layers.role_id, 
                    layers.map, layers.symbol_url, layers.symbol_type, layers.preview_url, layers.protocol,
                UPPER(
                    subfolders.path::TEXT || ' / ' ||
                    folders.title::TEXT || 
                    CASE WHEN layers.title IS NULL THEN '' ELSE layers.title::TEXT END || 
                    CASE WHEN layers.abstract IS NULL THEN '' ELSE layers.abstract::TEXT END || 
                    CASE WHEN layers.source IS NULL THEN '' ELSE layers.source::TEXT END
                ) AS search_field,
                    layers.created_at, layers.updated_at
                FROM subfolders
                JOIN gis_folders folders ON subfolders.folder_id = folders.parent_id
                LEFT JOIN layers ON layers.folder_id = folders.id
            )
            SELECT * FROM subfolders ORDER BY level
        ");
    }
}