<?php
namespace Sigapp\Gis;

use \Sigapp\Layers\LayersModel;

class GisEntity
{
    public $id;

    public function __construct($gis_id = 1)
    {
        $this->id = $gis_id;
    }

	public function getFolders($id = 0)
	{
		$folders = GisFoldersModel::where([
			['gis_id', $this->id],
			['parent_id', $id],
			['role_id', '>=', ROLE],
		])->orderBy('sort')->get();
		if(!isset($folders)){
			return [];
		}
		foreach ($folders as $folder) {
			$folder->subfolders = $this->getFolders( $folder->id );
			$folder->layers = $this->getlayers( $folder->id );
		}
		return $folders;
	}

	public function getLayers($folder_id)
	{
		$result = LayersModel::where([
			['folder_id', $folder_id],
			['role_id', '>=', ROLE],
		])->orderBy('title')->get();
		if ( !isset($result) ) {
			return [];
		}
		return $result;
	}
    
    public function getPaths()
    {
		$result = ViewFolderLayerModel::distinct()->get(['path', 'folder_id']);
		foreach ($result as $item) {
			$paths[ $item->path ] = $item->folder_id;
		}
		return ( isset($paths) ) ? $paths : null;
    }


	public function getTempDir()
	{
		if ( ROLE > 2 ) {
            return null;
        }
		return (object) [
			'id' => 0,
			'title' => 'Temp',
			'sort' => 999,
			'parent_id' => 0,
			'role_id' => 2,
			'layers' => $this->getLayers(0),
			'subfolders' => []
		];
	}

	public function deleteFolder($folder_id)
    {
		$folder = GisFoldersModel::find($folder_id);
		$folders = ViewFolderLayerModel::select('folder_id')
			->distinct()
			->where('path', 'like', '%' . $folder->title . '%')
			->get();
		foreach ($folders as $folder) {
			LayersModel::where('folder_id', $folder_id)->update(['folder_id' => 0]);
			GisFoldersModel::destroy($folder_id);
		}
	}
	
}