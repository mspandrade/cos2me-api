<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreList;
use App\Mapper\ListMapper;
use App\Service\ListService;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\UpdateImagesList;
use App\Http\Requests\UpdateList;
use App\Http\Requests\DeleteList;
use App\Http\Requests\PublishList;

class ListController {
    
    private $listService;
    
    public function __construct(ListService $listService) {
        $this->listService = $listService;
    }
    
    public function store(StoreList $request) {
        return DB::transaction( function() use($request) {

            return $this->listService->insert(
                ListMapper::mapToStore($request)
                );
        });
    }
    
    public function findById(int $id) {
        $list = $this->listService->findById($id);

        if ($list == null) {
            throw new NotFoundHttpException();
        }
        return $list;
    }
    
    public function updateImage(UpdateImagesList $request) {
        return DB::transaction( function() use ($request) {
            return $this->listService->updateImages(
                        ListMapper::mapToUpdateImage($request)
                   );
        });
    }
    
    public function update(UpdateList $request) {
        return DB::transaction( function() use ($request) {
            return $this->listService->update(ListMapper::mapToUpdate($request));
        });
    }
    
    public function publish(PublishList $request) {
        return DB::transaction(function() use($request) {
            $this->listService->publish(ListMapper::mapToPublish($request));
            return [ 'message' => 'List was published' ];
        });
    }
    
    public function delete(DeleteList $request) {
        $this->listService->delete($request->route('id'));
        return [ 'message' => 'List was deleted' ];
    }
    
}