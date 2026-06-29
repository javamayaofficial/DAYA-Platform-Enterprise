<?php

declare(strict_types=1);

namespace App\Modules\Story\Controllers;

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Modules\Story\Dto\StoryListCriteria;
use App\Modules\Story\Requests\StoryRequest;
use App\Modules\Story\Responses\StoryResponse;
use RuntimeException;

final class StoryController extends AbstractStoryController
{
    public function dashboard(Request $request): Response
    {
        $auth = $this->auth($request);
        $creator = $this->factory->service()->creatorProfileForUser((int) ($auth['user_id'] ?? 0));
        $criteria = new StoryListCriteria('', '', '', 1, 5, false, false, is_array($creator) ? (int) ($creator['id'] ?? 0) : null);
        $result = $this->factory->service()->paginate($criteria);

        return $this->render('dashboard/index', 'Story Dashboard', [
            'auth' => $auth,
            'creator' => $creator,
            'result' => $result,
            'flash' => $request->session()->pullFlash('story.status'),
        ]);
    }

    public function showCreate(Request $request): Response
    {
        $storyRequest = StoryRequest::from($request);
        $creator = $this->factory->service()->creatorProfileForUser($storyRequest->userId());
        if (!$this->factory->policy()->canCreate($this->auth($request), is_array($creator))) {
            return $this->forbiddenResponse('Anda harus memiliki profil Creator sebelum membuat story.');
        }

        return $this->render('story/create', 'Create Story', [
            'errors' => [],
            'old' => [
                'language' => (string) $this->module()->config('default_language', 'id'),
                'visibility' => (string) $this->module()->config('default_visibility', 'private'),
            ],
            'languageOptions' => (array) $this->module()->config('language_options', []),
            'visibilityOptions' => (array) $this->module()->config('visibility_options', []),
            'collections' => is_array($creator) ? $this->factory->service()->creatorCollections((int) ($creator['id'] ?? 0)) : [],
            'flash' => $request->session()->pullFlash('story.status'),
        ]);
    }

    public function store(Request $request): Response
    {
        $storyRequest = StoryRequest::from($request);
        $creator = $this->factory->service()->creatorProfileForUser($storyRequest->userId());
        if (!$this->factory->policy()->canCreate($this->auth($request), is_array($creator))) {
            return $this->forbiddenResponse('Anda harus memiliki profil Creator sebelum membuat story.');
        }

        $story = $storyRequest->storyData();
        $validation = $this->factory->validator()->validateStory($story);
        if ($validation['errors'] !== []) {
            return $this->render('story/create', 'Create Story', [
                'errors' => $validation['errors'],
                'old' => $story,
                'languageOptions' => (array) $this->module()->config('language_options', []),
                'visibilityOptions' => (array) $this->module()->config('visibility_options', []),
                'collections' => is_array($creator) ? $this->factory->service()->creatorCollections((int) ($creator['id'] ?? 0)) : [],
                'flash' => null,
            ], 422);
        }

        try {
            $created = $this->factory->service()->create($storyRequest->userId(), $story);
        } catch (RuntimeException $exception) {
            return $this->render('story/create', 'Create Story', [
                'errors' => ['title' => $exception->getMessage()],
                'old' => $story,
                'languageOptions' => (array) $this->module()->config('language_options', []),
                'visibilityOptions' => (array) $this->module()->config('visibility_options', []),
                'collections' => is_array($creator) ? $this->factory->service()->creatorCollections((int) ($creator['id'] ?? 0)) : [],
                'flash' => null,
            ], 422);
        }

        $request->session()->flash('story.status', ['type' => 'success', 'message' => 'Story created successfully.']);

        return StoryResponse::redirect('/story/' . $created->id);
    }

    public function showOwn(Request $request): Response
    {
        $storyRequest = StoryRequest::from($request);
        $story = $this->factory->service()->detailById($storyRequest->storyId(), true);
        $creator = $this->factory->service()->creatorProfileForUser($storyRequest->userId());
        if (!$this->factory->policy()->canManageOwn($this->auth($request), $story, is_array($creator) ? (int) ($creator['id'] ?? 0) : null)) {
            return $this->forbiddenResponse();
        }

        return $this->render('story/show', 'Story Detail', [
            'story' => $this->factory->service()->detailResource($story),
            'statistics' => $this->factory->service()->statistics($story),
            'flash' => $request->session()->pullFlash('story.status'),
        ]);
    }

    public function showEdit(Request $request): Response
    {
        $storyRequest = StoryRequest::from($request);
        $story = $this->factory->service()->detailById($storyRequest->storyId(), true);
        $creator = $this->factory->service()->creatorProfileForUser($storyRequest->userId());
        if (!$this->factory->policy()->canManageOwn($this->auth($request), $story, is_array($creator) ? (int) ($creator['id'] ?? 0) : null)) {
            return $this->forbiddenResponse();
        }

        return $this->render('story/edit', 'Edit Story', [
            'story' => $this->factory->service()->detailResource($story),
            'errors' => [],
            'languageOptions' => (array) $this->module()->config('language_options', []),
            'visibilityOptions' => (array) $this->module()->config('visibility_options', []),
            'collections' => is_array($creator) ? $this->factory->service()->creatorCollections((int) ($creator['id'] ?? 0)) : [],
            'flash' => $request->session()->pullFlash('story.status'),
        ]);
    }

    public function update(Request $request): Response
    {
        $storyRequest = StoryRequest::from($request);
        $existing = $this->factory->service()->detailById($storyRequest->storyId(), true);
        $creator = $this->factory->service()->creatorProfileForUser($storyRequest->userId());
        if (!$this->factory->policy()->canManageOwn($this->auth($request), $existing, is_array($creator) ? (int) ($creator['id'] ?? 0) : null)) {
            return $this->forbiddenResponse();
        }

        $story = $storyRequest->storyData();
        $validation = $this->factory->validator()->validateStory($story);
        if ($validation['errors'] !== []) {
            return $this->render('story/edit', 'Edit Story', [
                'story' => array_merge($this->factory->service()->detailResource($existing), $story),
                'errors' => $validation['errors'],
                'languageOptions' => (array) $this->module()->config('language_options', []),
                'visibilityOptions' => (array) $this->module()->config('visibility_options', []),
                'collections' => is_array($creator) ? $this->factory->service()->creatorCollections((int) ($creator['id'] ?? 0)) : [],
                'flash' => null,
            ], 422);
        }

        try {
            $this->factory->service()->update($existing->id, $story);
        } catch (RuntimeException $exception) {
            return $this->render('story/edit', 'Edit Story', [
                'story' => array_merge($this->factory->service()->detailResource($existing), $story),
                'errors' => ['slug' => $exception->getMessage()],
                'languageOptions' => (array) $this->module()->config('language_options', []),
                'visibilityOptions' => (array) $this->module()->config('visibility_options', []),
                'collections' => is_array($creator) ? $this->factory->service()->creatorCollections((int) ($creator['id'] ?? 0)) : [],
                'flash' => null,
            ], 422);
        }

        $request->session()->flash('story.status', ['type' => 'success', 'message' => 'Story updated successfully.']);

        return StoryResponse::redirect('/story/' . $existing->id);
    }

    public function preview(Request $request): Response
    {
        $storyRequest = StoryRequest::from($request);
        $story = $this->factory->service()->detailById($storyRequest->storyId(), true);
        $creator = $this->factory->service()->creatorProfileForUser($storyRequest->userId());
        if (!$this->factory->policy()->canManageOwn($this->auth($request), $story, is_array($creator) ? (int) ($creator['id'] ?? 0) : null)) {
            return $this->forbiddenResponse();
        }

        $data = $this->factory->service()->detailResource($story);

        return $this->render('story/preview', (($data['seo_title'] ?? '') !== '' ? (string) $data['seo_title'] : (string) $data['title']) . ' | Preview', [
            'story' => $data,
            'statistics' => $this->factory->service()->statistics($story),
            'flash' => $request->session()->pullFlash('story.status'),
        ]);
    }

    public function review(Request $request): Response
    {
        return $this->changeStatusAction($request, 'review', 'Story moved to review.');
    }

    public function publish(Request $request): Response
    {
        $storyRequest = StoryRequest::from($request);
        $story = $this->factory->service()->detailById($storyRequest->storyId(), true);
        $creator = $this->factory->service()->creatorProfileForUser($storyRequest->userId());
        if (!$this->factory->policy()->canManageOwn($this->auth($request), $story, is_array($creator) ? (int) ($creator['id'] ?? 0) : null)) {
            return $this->forbiddenResponse();
        }

        try {
            $this->factory->service()->publish($story->id);
        } catch (RuntimeException $exception) {
            $request->session()->flash('story.status', ['type' => 'danger', 'message' => $exception->getMessage()]);

            return StoryResponse::redirect('/story/' . $story->id);
        }

        $request->session()->flash('story.status', ['type' => 'success', 'message' => 'Story published successfully.']);

        return StoryResponse::redirect('/story/' . $story->id);
    }

    public function schedule(Request $request): Response
    {
        $storyRequest = StoryRequest::from($request);
        $story = $this->factory->service()->detailById($storyRequest->storyId(), true);
        $creator = $this->factory->service()->creatorProfileForUser($storyRequest->userId());
        if (!$this->factory->policy()->canManageOwn($this->auth($request), $story, is_array($creator) ? (int) ($creator['id'] ?? 0) : null)) {
            return $this->forbiddenResponse();
        }

        try {
            $this->factory->service()->schedule($story->id, $storyRequest->scheduleAt());
        } catch (RuntimeException $exception) {
            $request->session()->flash('story.status', ['type' => 'danger', 'message' => $exception->getMessage()]);

            return StoryResponse::redirect('/story/' . $story->id);
        }

        $request->session()->flash('story.status', ['type' => 'success', 'message' => 'Story scheduled successfully.']);

        return StoryResponse::redirect('/story/' . $story->id);
    }

    public function archive(Request $request): Response
    {
        return $this->changeStatusAction($request, 'archived', 'Story archived successfully.');
    }

    public function duplicate(Request $request): Response
    {
        $storyRequest = StoryRequest::from($request);
        $story = $this->factory->service()->detailById($storyRequest->storyId(), true);
        $creator = $this->factory->service()->creatorProfileForUser($storyRequest->userId());
        if (!$this->factory->policy()->canManageOwn($this->auth($request), $story, is_array($creator) ? (int) ($creator['id'] ?? 0) : null)) {
            return $this->forbiddenResponse();
        }

        try {
            $duplicated = $this->factory->service()->duplicate($story->id);
        } catch (RuntimeException $exception) {
            $request->session()->flash('story.status', ['type' => 'danger', 'message' => $exception->getMessage()]);

            return StoryResponse::redirect('/story/' . $story->id);
        }

        $request->session()->flash('story.status', ['type' => 'success', 'message' => 'Story duplicated successfully.']);

        return StoryResponse::redirect('/story/' . $duplicated->id);
    }

    public function destroy(Request $request): Response
    {
        $storyRequest = StoryRequest::from($request);
        $story = $this->factory->service()->detailById($storyRequest->storyId(), true);
        $creator = $this->factory->service()->creatorProfileForUser($storyRequest->userId());
        if (!$this->factory->policy()->canManageOwn($this->auth($request), $story, is_array($creator) ? (int) ($creator['id'] ?? 0) : null)) {
            return $this->forbiddenResponse();
        }

        try {
            $this->factory->service()->delete($story->id);
        } catch (RuntimeException $exception) {
            $request->session()->flash('story.status', ['type' => 'danger', 'message' => $exception->getMessage()]);

            return StoryResponse::redirect('/story/' . $story->id);
        }

        $request->session()->flash('story.status', ['type' => 'success', 'message' => 'Story removed successfully.']);

        return StoryResponse::redirect('/story');
    }

    public function publicList(Request $request): Response
    {
        $criteria = StoryListCriteria::fromRequest(StoryRequest::from($request), true);
        $result = $this->factory->service()->paginate($criteria);

        return $this->render('public/index', 'Story Directory', [
            'result' => $result,
            'search' => $criteria->search,
            'flash' => null,
        ]);
    }

    public function publicShow(Request $request): Response
    {
        try {
            $story = $this->factory->service()->detailBySlug((string) $request->route('slug', ''));
        } catch (RuntimeException) {
            return $this->notFoundResponse('Story page not found.');
        }

        $data = $this->factory->service()->detailResource($story);

        return $this->render((string) 'public/show', (($data['seo_title'] ?? '') !== '' ? (string) $data['seo_title'] : (string) $data['title']) . ' | Story', [
            'story' => $data,
            'statistics' => $this->factory->service()->statistics($story),
            'flash' => null,
        ]);
    }

    private function changeStatusAction(Request $request, string $status, string $message): Response
    {
        $storyRequest = StoryRequest::from($request);
        $story = $this->factory->service()->detailById($storyRequest->storyId(), true);
        $creator = $this->factory->service()->creatorProfileForUser($storyRequest->userId());
        if (!$this->factory->policy()->canManageOwn($this->auth($request), $story, is_array($creator) ? (int) ($creator['id'] ?? 0) : null)) {
            return $this->forbiddenResponse();
        }

        try {
            if ($status === 'review') {
                $this->factory->service()->markReview($story->id);
            } elseif ($status === 'archived') {
                $this->factory->service()->archive($story->id);
            } else {
                throw new RuntimeException('Unsupported story status action.');
            }
        } catch (RuntimeException $exception) {
            $request->session()->flash('story.status', ['type' => 'danger', 'message' => $exception->getMessage()]);

            return StoryResponse::redirect('/story/' . $story->id);
        }

        $request->session()->flash('story.status', ['type' => 'success', 'message' => $message]);

        return StoryResponse::redirect('/story/' . $story->id);
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
