<?php

declare(strict_types=1);

namespace App\Modules\Story\Controllers;

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Modules\Story\Dto\StoryListCriteria;
use App\Modules\Story\Requests\StoryRequest;
use RuntimeException;

final class AdminStoryController extends AbstractStoryController
{
    public function index(Request $request): Response
    {
        $criteria = StoryListCriteria::fromRequest(StoryRequest::from($request));
        $result = $this->factory->service()->paginate($criteria);

        return $this->render('admin/index', 'Story Directory', [
            'auth' => $this->auth($request),
            'result' => $result,
            'search' => $criteria->search,
            'status' => $criteria->status,
            'visibility' => $criteria->visibility,
            'includeDeleted' => $criteria->includeDeleted,
            'statuses' => (array) $this->module()->config('statuses', []),
            'visibilityOptions' => (array) $this->module()->config('visibility_options', []),
            'flash' => $request->session()->pullFlash('story.status'),
        ]);
    }

    public function show(Request $request): Response
    {
        try {
            $story = $this->factory->service()->detailById(StoryRequest::from($request)->storyId(), true);
        } catch (RuntimeException) {
            return Response::html(render_layout('Not Found', '<div class="alert alert-warning">Story not found.</div>'), 404);
        }

        return $this->render('admin/show', 'Story Detail', [
            'auth' => $this->auth($request),
            'story' => $this->factory->service()->detailResource($story),
            'statistics' => $this->factory->service()->statistics($story),
            'flash' => $request->session()->pullFlash('story.status'),
        ]);
    }
}
