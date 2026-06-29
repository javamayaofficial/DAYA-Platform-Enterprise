<?php

declare(strict_types=1);

namespace App\Modules\Collection\Controllers;

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Modules\Collection\Dto\CollectionListCriteria;
use App\Modules\Collection\Requests\CollectionRequest;
use App\Modules\Collection\Responses\CollectionResponse;
use RuntimeException;

final class CollectionController extends AbstractCollectionController
{
    public function dashboard(Request $request): Response
    {
        $auth = $this->auth($request);
        $creator = $this->factory->service()->creatorProfileForUser((int) ($auth['user_id'] ?? 0));
        $criteria = new CollectionListCriteria(
            '',
            '',
            1,
            5,
            false,
            false,
            is_array($creator) ? (int) ($creator['id'] ?? 0) : null
        );
        $result = $this->factory->service()->paginate($criteria);

        return $this->render('dashboard/index', 'Collection Dashboard', [
            'auth' => $auth,
            'creator' => $creator,
            'result' => $result,
            'flash' => $request->session()->pullFlash('collection.status'),
        ]);
    }

    public function showCreate(Request $request): Response
    {
        $collectionRequest = CollectionRequest::from($request);
        $creator = $this->factory->service()->creatorProfileForUser($collectionRequest->userId());
        if (!$this->factory->policy()->canCreate($this->auth($request), is_array($creator))) {
            return $this->forbiddenResponse('Anda harus memiliki profil Creator sebelum membuat collection.');
        }

        return $this->render('collection/create', 'Create Collection', [
            'errors' => [],
            'old' => [
                'visibility' => (string) $this->module()->config('default_visibility', 'public'),
            ],
            'visibilityOptions' => (array) $this->module()->config('visibility_options', []),
            'flash' => $request->session()->pullFlash('collection.status'),
        ]);
    }

    public function store(Request $request): Response
    {
        $collectionRequest = CollectionRequest::from($request);
        $creator = $this->factory->service()->creatorProfileForUser($collectionRequest->userId());
        if (!$this->factory->policy()->canCreate($this->auth($request), is_array($creator))) {
            return $this->forbiddenResponse('Anda harus memiliki profil Creator sebelum membuat collection.');
        }

        $collection = $collectionRequest->collectionData();
        $validation = $this->factory->validator()->validateCollection($collection);
        if ($validation['errors'] !== []) {
            return $this->render('collection/create', 'Create Collection', [
                'errors' => $validation['errors'],
                'old' => $collection,
                'visibilityOptions' => (array) $this->module()->config('visibility_options', []),
                'flash' => null,
            ], 422);
        }

        try {
            $created = $this->factory->service()->create($collectionRequest->userId(), $collection);
        } catch (RuntimeException $exception) {
            return $this->render('collection/create', 'Create Collection', [
                'errors' => ['title' => $exception->getMessage()],
                'old' => $collection,
                'visibilityOptions' => (array) $this->module()->config('visibility_options', []),
                'flash' => null,
            ], 422);
        }

        $request->session()->flash('collection.status', ['type' => 'success', 'message' => 'Collection created successfully.']);

        return CollectionResponse::redirect('/collection/' . $created->id);
    }

    public function showOwn(Request $request): Response
    {
        $collectionRequest = CollectionRequest::from($request);
        $collection = $this->factory->service()->detailById($collectionRequest->collectionId(), true);
        $creator = $this->factory->service()->creatorProfileForUser($collectionRequest->userId());
        if (!$this->factory->policy()->canManageOwn($this->auth($request), $collection, is_array($creator) ? (int) ($creator['id'] ?? 0) : null)) {
            return $this->forbiddenResponse();
        }

        return $this->render('collection/show', 'Collection Detail', [
            'collection' => $this->factory->service()->detailResource($collection),
            'statistics' => $this->factory->service()->statistics($collection),
            'availableContents' => is_array($creator) ? $this->factory->service()->creatorContents((int) ($creator['id'] ?? 0)) : [],
            'flash' => $request->session()->pullFlash('collection.status'),
        ]);
    }

    public function showEdit(Request $request): Response
    {
        $collectionRequest = CollectionRequest::from($request);
        $collection = $this->factory->service()->detailById($collectionRequest->collectionId(), true);
        $creator = $this->factory->service()->creatorProfileForUser($collectionRequest->userId());
        if (!$this->factory->policy()->canManageOwn($this->auth($request), $collection, is_array($creator) ? (int) ($creator['id'] ?? 0) : null)) {
            return $this->forbiddenResponse();
        }

        return $this->render('collection/edit', 'Edit Collection', [
            'collection' => $this->factory->service()->detailResource($collection),
            'errors' => [],
            'visibilityOptions' => (array) $this->module()->config('visibility_options', []),
            'flash' => $request->session()->pullFlash('collection.status'),
        ]);
    }

    public function update(Request $request): Response
    {
        $collectionRequest = CollectionRequest::from($request);
        $existing = $this->factory->service()->detailById($collectionRequest->collectionId(), true);
        $creator = $this->factory->service()->creatorProfileForUser($collectionRequest->userId());
        if (!$this->factory->policy()->canManageOwn($this->auth($request), $existing, is_array($creator) ? (int) ($creator['id'] ?? 0) : null)) {
            return $this->forbiddenResponse();
        }

        $collection = $collectionRequest->collectionData();
        $validation = $this->factory->validator()->validateCollection($collection);
        if ($validation['errors'] !== []) {
            return $this->render('collection/edit', 'Edit Collection', [
                'collection' => array_merge($this->factory->service()->detailResource($existing), $collection),
                'errors' => $validation['errors'],
                'visibilityOptions' => (array) $this->module()->config('visibility_options', []),
                'flash' => null,
            ], 422);
        }

        try {
            $this->factory->service()->update($existing->id, $collection);
        } catch (RuntimeException $exception) {
            return $this->render('collection/edit', 'Edit Collection', [
                'collection' => array_merge($this->factory->service()->detailResource($existing), $collection),
                'errors' => ['slug' => $exception->getMessage()],
                'visibilityOptions' => (array) $this->module()->config('visibility_options', []),
                'flash' => null,
            ], 422);
        }

        $request->session()->flash('collection.status', ['type' => 'success', 'message' => 'Collection updated successfully.']);

        return CollectionResponse::redirect('/collection/' . $existing->id);
    }

    public function addItem(Request $request): Response
    {
        $collectionRequest = CollectionRequest::from($request);
        $collection = $this->factory->service()->detailById($collectionRequest->collectionId(), true);
        $creator = $this->factory->service()->creatorProfileForUser($collectionRequest->userId());
        if (!$this->factory->policy()->canManageOwn($this->auth($request), $collection, is_array($creator) ? (int) ($creator['id'] ?? 0) : null)) {
            return $this->forbiddenResponse();
        }

        try {
            $this->factory->service()->addItem(
                $collection->id,
                (int) ($creator['id'] ?? 0),
                $collectionRequest->itemContentId()
            );
        } catch (RuntimeException $exception) {
            $request->session()->flash('collection.status', ['type' => 'danger', 'message' => $exception->getMessage()]);

            return CollectionResponse::redirect('/collection/' . $collection->id);
        }

        $request->session()->flash('collection.status', ['type' => 'success', 'message' => 'Content added to collection successfully.']);

        return CollectionResponse::redirect('/collection/' . $collection->id);
    }

    public function deleteItem(Request $request): Response
    {
        $collectionRequest = CollectionRequest::from($request);
        $collection = $this->factory->service()->detailById($collectionRequest->collectionId(), true);
        $creator = $this->factory->service()->creatorProfileForUser($collectionRequest->userId());
        if (!$this->factory->policy()->canManageOwn($this->auth($request), $collection, is_array($creator) ? (int) ($creator['id'] ?? 0) : null)) {
            return $this->forbiddenResponse();
        }

        try {
            $this->factory->service()->removeItem($collection->id, $collectionRequest->itemId());
        } catch (RuntimeException $exception) {
            $request->session()->flash('collection.status', ['type' => 'danger', 'message' => $exception->getMessage()]);

            return CollectionResponse::redirect('/collection/' . $collection->id);
        }

        $request->session()->flash('collection.status', ['type' => 'success', 'message' => 'Collection item removed successfully.']);

        return CollectionResponse::redirect('/collection/' . $collection->id);
    }

    public function reorderItems(Request $request): Response
    {
        $collectionRequest = CollectionRequest::from($request);
        $collection = $this->factory->service()->detailById($collectionRequest->collectionId(), true);
        $creator = $this->factory->service()->creatorProfileForUser($collectionRequest->userId());
        if (!$this->factory->policy()->canManageOwn($this->auth($request), $collection, is_array($creator) ? (int) ($creator['id'] ?? 0) : null)) {
            return $this->forbiddenResponse();
        }

        try {
            $this->factory->service()->reorderItems($collection->id, $collectionRequest->itemOrders());
        } catch (RuntimeException $exception) {
            $request->session()->flash('collection.status', ['type' => 'danger', 'message' => $exception->getMessage()]);

            return CollectionResponse::redirect('/collection/' . $collection->id);
        }

        $request->session()->flash('collection.status', ['type' => 'success', 'message' => 'Collection item order updated successfully.']);

        return CollectionResponse::redirect('/collection/' . $collection->id);
    }

    public function publish(Request $request): Response
    {
        return $this->changeStatus($request, 'published', 'Collection published successfully.');
    }

    public function draft(Request $request): Response
    {
        return $this->changeStatus($request, 'draft', 'Collection moved back to draft.');
    }

    public function destroy(Request $request): Response
    {
        $collectionRequest = CollectionRequest::from($request);
        $collection = $this->factory->service()->detailById($collectionRequest->collectionId(), true);
        $creator = $this->factory->service()->creatorProfileForUser($collectionRequest->userId());
        if (!$this->factory->policy()->canManageOwn($this->auth($request), $collection, is_array($creator) ? (int) ($creator['id'] ?? 0) : null)) {
            return $this->forbiddenResponse();
        }

        try {
            $this->factory->service()->delete($collection->id);
        } catch (RuntimeException $exception) {
            $request->session()->flash('collection.status', ['type' => 'danger', 'message' => $exception->getMessage()]);

            return CollectionResponse::redirect('/collection/' . $collection->id);
        }

        $request->session()->flash('collection.status', ['type' => 'success', 'message' => 'Collection removed successfully.']);

        return CollectionResponse::redirect('/collection');
    }

    public function publicList(Request $request): Response
    {
        $criteria = CollectionListCriteria::fromRequest(CollectionRequest::from($request), true);
        $result = $this->factory->service()->paginate($criteria);

        return $this->render('public/index', 'Collection Directory', [
            'result' => $result,
            'status' => $criteria->status,
            'visibility' => $criteria->visibility,
            'flash' => null,
        ]);
    }

    public function publicShow(Request $request): Response
    {
        try {
            $collection = $this->factory->service()->detailBySlug((string) $request->route('slug', ''));
        } catch (RuntimeException) {
            return $this->notFoundResponse('Collection page not found.');
        }

        return $this->render('public/show', (string) $collection->title . ' | Collection', [
            'collection' => $this->factory->service()->detailResource($collection),
            'statistics' => $this->factory->service()->statistics($collection),
            'flash' => null,
        ]);
    }

    private function changeStatus(Request $request, string $status, string $message): Response
    {
        $collectionRequest = CollectionRequest::from($request);
        $collection = $this->factory->service()->detailById($collectionRequest->collectionId(), true);
        $creator = $this->factory->service()->creatorProfileForUser($collectionRequest->userId());
        if (!$this->factory->policy()->canManageOwn($this->auth($request), $collection, is_array($creator) ? (int) ($creator['id'] ?? 0) : null)) {
            return $this->forbiddenResponse();
        }

        try {
            $this->factory->service()->changeStatus($collection->id, $status);
        } catch (RuntimeException $exception) {
            $request->session()->flash('collection.status', ['type' => 'danger', 'message' => $exception->getMessage()]);

            return CollectionResponse::redirect('/collection/' . $collection->id);
        }

        $request->session()->flash('collection.status', ['type' => 'success', 'message' => $message]);

        return CollectionResponse::redirect('/collection/' . $collection->id);
    }

    private function forbiddenResponse(string $message = 'Access denied.'): Response
    {
        return Response::html(render_layout('Forbidden', '<div class="alert alert-danger">' . e($message) . '</div>'), 403);
    }

    private function notFoundResponse(string $message): Response
    {
        return Response::html(render_layout('Not Found', '<div class="alert alert-warning">' . e($message) . '</div>'), 404);
    }
}
