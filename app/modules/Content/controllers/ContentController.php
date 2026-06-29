<?php

declare(strict_types=1);

namespace App\Modules\Content\Controllers;

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Modules\Content\Dto\ContentSearchCriteria;
use App\Modules\Content\Models\Content;
use App\Modules\Content\Requests\ContentRequest;
use App\Modules\Content\Responses\ContentResponse;
use RuntimeException;

final class ContentController extends AbstractContentController
{
    public function dashboard(Request $request): Response
    {
        $auth = $this->auth($request);
        $creator = $this->factory->service()->creatorProfileForUser((int) ($auth['user_id'] ?? 0));
        $criteria = new ContentSearchCriteria('', '', '', 1, 5, false, false, is_array($creator) ? (int) ($creator['id'] ?? 0) : null);
        $result = $this->factory->service()->paginate($criteria);

        return $this->render('dashboard/index', 'Content Dashboard', [
            'auth' => $auth,
            'creator' => $creator,
            'result' => $result,
            'flash' => $request->session()->pullFlash('content.status'),
        ]);
    }

    public function showCreate(Request $request): Response
    {
        return $this->render('content/create', 'Create Content', [
            'errors' => [],
            'old' => [
                'content_type' => (string) $this->module()->config('default_content_type', 'story'),
                'access_policy' => (string) $this->module()->config('default_access_policy', 'free'),
                'visibility' => (string) $this->module()->config('default_visibility', 'public'),
                'currency_code' => (string) $this->module()->config('currency_code', 'IDR'),
            ],
            'contentTypes' => (array) $this->module()->config('content_types', []),
            'accessPolicies' => (array) $this->module()->config('access_policies', []),
            'visibilityOptions' => (array) $this->module()->config('visibility_options', []),
            'flash' => $request->session()->pullFlash('content.status'),
        ]);
    }

    public function store(Request $request): Response
    {
        $contentRequest = ContentRequest::from($request);
        $content = $contentRequest->contentData();
        $validation = $this->factory->validator()->validateContent($content);

        if ($validation['errors'] !== []) {
            return $this->render('content/create', 'Create Content', [
                'errors' => $validation['errors'],
                'old' => $content,
                'contentTypes' => (array) $this->module()->config('content_types', []),
                'accessPolicies' => (array) $this->module()->config('access_policies', []),
                'visibilityOptions' => (array) $this->module()->config('visibility_options', []),
                'flash' => null,
            ], 422);
        }

        try {
            $created = $this->factory->service()->create($contentRequest->userId(), $content);
        } catch (RuntimeException $exception) {
            return $this->render('content/create', 'Create Content', [
                'errors' => ['title' => $exception->getMessage()],
                'old' => $content,
                'contentTypes' => (array) $this->module()->config('content_types', []),
                'accessPolicies' => (array) $this->module()->config('access_policies', []),
                'visibilityOptions' => (array) $this->module()->config('visibility_options', []),
                'flash' => null,
            ], 422);
        }

        $request->session()->flash('content.status', ['type' => 'success', 'message' => 'Content created successfully.']);

        return ContentResponse::redirect('/content/' . $created->id);
    }

    public function showOwn(Request $request): Response
    {
        $contentRequest = ContentRequest::from($request);
        $content = $this->factory->service()->detailById($contentRequest->contentId(), true);
        $creator = $this->factory->service()->creatorProfileForUser($contentRequest->userId());
        if (!$this->factory->policy()->canManageOwn($this->auth($request), $content, is_array($creator) ? (int) ($creator['id'] ?? 0) : null)) {
            return Response::html(render_layout('Forbidden', '<div class="alert alert-danger">Access denied.</div>'), 403);
        }

        return $this->render('content/show', 'Content Detail', [
            'content' => $this->factory->service()->detailResource($content),
            'statistics' => $this->factory->service()->statistics($content),
            'contentTypes' => (array) $this->module()->config('content_types', []),
            'flash' => $request->session()->pullFlash('content.status'),
        ]);
    }

    public function showEdit(Request $request): Response
    {
        $contentRequest = ContentRequest::from($request);
        $content = $this->factory->service()->detailById($contentRequest->contentId(), true);
        $creator = $this->factory->service()->creatorProfileForUser($contentRequest->userId());
        if (!$this->factory->policy()->canManageOwn($this->auth($request), $content, is_array($creator) ? (int) ($creator['id'] ?? 0) : null)) {
            return Response::html(render_layout('Forbidden', '<div class="alert alert-danger">Access denied.</div>'), 403);
        }

        return $this->render('content/edit', 'Edit Content', [
            'content' => $this->factory->service()->detailResource($content),
            'contentTypes' => (array) $this->module()->config('content_types', []),
            'accessPolicies' => (array) $this->module()->config('access_policies', []),
            'visibilityOptions' => (array) $this->module()->config('visibility_options', []),
            'errors' => [],
            'flash' => $request->session()->pullFlash('content.status'),
        ]);
    }

    public function update(Request $request): Response
    {
        $contentRequest = ContentRequest::from($request);
        $existing = $this->factory->service()->detailById($contentRequest->contentId(), true);
        $creator = $this->factory->service()->creatorProfileForUser($contentRequest->userId());
        if (!$this->factory->policy()->canManageOwn($this->auth($request), $existing, is_array($creator) ? (int) ($creator['id'] ?? 0) : null)) {
            return Response::html(render_layout('Forbidden', '<div class="alert alert-danger">Access denied.</div>'), 403);
        }

        $content = $contentRequest->contentData();
        $validation = $this->factory->validator()->validateContent($content);
        if ($validation['errors'] !== []) {
            return $this->render('content/edit', 'Edit Content', [
                'content' => array_merge($this->factory->service()->detailResource($existing), $content),
                'contentTypes' => (array) $this->module()->config('content_types', []),
                'accessPolicies' => (array) $this->module()->config('access_policies', []),
                'visibilityOptions' => (array) $this->module()->config('visibility_options', []),
                'errors' => $validation['errors'],
                'flash' => null,
            ], 422);
        }

        try {
            $this->factory->service()->update($existing->id, $content);
        } catch (RuntimeException $exception) {
            return $this->render('content/edit', 'Edit Content', [
                'content' => array_merge($this->factory->service()->detailResource($existing), $content),
                'contentTypes' => (array) $this->module()->config('content_types', []),
                'accessPolicies' => (array) $this->module()->config('access_policies', []),
                'visibilityOptions' => (array) $this->module()->config('visibility_options', []),
                'errors' => ['slug' => $exception->getMessage()],
                'flash' => null,
            ], 422);
        }

        $request->session()->flash('content.status', ['type' => 'success', 'message' => 'Content updated successfully.']);

        return ContentResponse::redirect('/content/' . $existing->id);
    }

    public function addPart(Request $request): Response
    {
        $contentRequest = ContentRequest::from($request);
        $content = $this->factory->service()->detailById($contentRequest->contentId(), true);
        $creator = $this->factory->service()->creatorProfileForUser($contentRequest->userId());
        if (!$this->factory->policy()->canManageOwn($this->auth($request), $content, is_array($creator) ? (int) ($creator['id'] ?? 0) : null)) {
            return Response::html(render_layout('Forbidden', '<div class="alert alert-danger">Access denied.</div>'), 403);
        }

        $validation = $this->factory->validator()->validatePart($contentRequest->partData());
        if ($validation['errors'] !== []) {
            $request->session()->flash('content.status', ['type' => 'danger', 'message' => 'Content part data is invalid.']);

            return ContentResponse::redirect('/content/' . $content->id);
        }

        $this->factory->service()->addPart($content->id, $contentRequest->partData());
        $request->session()->flash('content.status', ['type' => 'success', 'message' => 'Content part added successfully.']);

        return ContentResponse::redirect('/content/' . $content->id);
    }

    public function deletePart(Request $request): Response
    {
        $contentRequest = ContentRequest::from($request);
        $content = $this->factory->service()->detailById($contentRequest->contentId(), true);
        $creator = $this->factory->service()->creatorProfileForUser($contentRequest->userId());
        if (!$this->factory->policy()->canManageOwn($this->auth($request), $content, is_array($creator) ? (int) ($creator['id'] ?? 0) : null)) {
            return Response::html(render_layout('Forbidden', '<div class="alert alert-danger">Access denied.</div>'), 403);
        }

        $this->factory->service()->deletePart($content->id, $contentRequest->itemId());
        $request->session()->flash('content.status', ['type' => 'success', 'message' => 'Content part deleted successfully.']);

        return ContentResponse::redirect('/content/' . $content->id);
    }

    public function submit(Request $request): Response
    {
        $contentRequest = ContentRequest::from($request);
        $content = $this->factory->service()->detailById($contentRequest->contentId(), true);
        $creator = $this->factory->service()->creatorProfileForUser($contentRequest->userId());
        if (!$this->factory->policy()->canManageOwn($this->auth($request), $content, is_array($creator) ? (int) ($creator['id'] ?? 0) : null)) {
            return Response::html(render_layout('Forbidden', '<div class="alert alert-danger">Access denied.</div>'), 403);
        }

        $this->factory->service()->submitForReview($content->id);
        $request->session()->flash('content.status', ['type' => 'success', 'message' => 'Content submitted for review.']);

        return ContentResponse::redirect('/content/' . $content->id);
    }

    public function destroy(Request $request): Response
    {
        $contentRequest = ContentRequest::from($request);
        $content = $this->factory->service()->detailById($contentRequest->contentId(), true);
        $creator = $this->factory->service()->creatorProfileForUser($contentRequest->userId());
        if (!$this->factory->policy()->canManageOwn($this->auth($request), $content, is_array($creator) ? (int) ($creator['id'] ?? 0) : null)) {
            return Response::html(render_layout('Forbidden', '<div class="alert alert-danger">Access denied.</div>'), 403);
        }

        $this->factory->service()->delete($content->id);
        $request->session()->flash('content.status', ['type' => 'success', 'message' => 'Content removed successfully.']);

        return ContentResponse::redirect('/content');
    }

    public function publicList(Request $request): Response
    {
        $contentRequest = ContentRequest::from($request);
        $criteria = ContentSearchCriteria::fromRequest($contentRequest, true);
        $result = $this->factory->service()->paginate($criteria);

        return $this->render('public/index', 'Content Directory', [
            'result' => $result,
            'search' => $criteria->search,
            'contentType' => $criteria->contentType,
            'contentTypes' => (array) $this->module()->config('content_types', []),
            'flash' => null,
        ]);
    }

    public function publicShow(Request $request): Response
    {
        try {
            $content = $this->factory->service()->detailBySlug((string) $request->route('slug', ''));
        } catch (RuntimeException) {
            return Response::html(render_layout('Not Found', '<div class="alert alert-warning">Content page not found.</div>'), 404);
        }

        $data = $this->factory->service()->detailResource($content);

        return $this->render(
            'public/show',
            ($data['seo_title'] ?? '') !== '' ? (string) $data['seo_title'] : ((string) $data['title'] . ' | Content'),
            [
                'content' => $data,
                'statistics' => $this->factory->service()->statistics($content),
                'flash' => null,
            ]
        );
    }
}
