<?php

declare(strict_types=1);

namespace App\Modules\Collection\Controllers;

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Modules\Collection\Dto\CollectionListCriteria;
use App\Modules\Collection\Requests\CollectionRequest;
use RuntimeException;

final class AdminCollectionController extends AbstractCollectionController
{
    public function index(Request $request): Response
    {
        $criteria = CollectionListCriteria::fromRequest(CollectionRequest::from($request));
        $result = $this->factory->service()->paginate($criteria);

        return $this->render('admin/index', 'Collection Directory', [
            'auth' => $this->auth($request),
            'result' => $result,
            'status' => $criteria->status,
            'visibility' => $criteria->visibility,
            'includeDeleted' => $criteria->includeDeleted,
            'statuses' => (array) $this->module()->config('statuses', []),
            'visibilityOptions' => (array) $this->module()->config('visibility_options', []),
            'flash' => $request->session()->pullFlash('collection.status'),
        ]);
    }

    public function show(Request $request): Response
    {
        try {
            $collection = $this->factory->service()->detailById(CollectionRequest::from($request)->collectionId(), true);
        } catch (RuntimeException) {
            return Response::html(render_layout('Not Found', '<div class="alert alert-warning">Collection not found.</div>'), 404);
        }

        return $this->render('admin/show', 'Collection Detail', [
            'auth' => $this->auth($request),
            'collection' => $this->factory->service()->detailResource($collection),
            'statistics' => $this->factory->service()->statistics($collection),
            'flash' => $request->session()->pullFlash('collection.status'),
        ]);
    }
}
