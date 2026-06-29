<?php

declare(strict_types=1);

namespace App\Modules\Content\Controllers;

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Modules\Content\Dto\ContentSearchCriteria;
use App\Modules\Content\Requests\ContentRequest;

final class AdminContentController extends AbstractContentController
{
    public function index(Request $request): Response
    {
        $criteria = ContentSearchCriteria::fromRequest(ContentRequest::from($request));
        $result = $this->factory->service()->paginate($criteria);

        return $this->render('admin/index', 'Content Directory', [
            'auth' => $this->auth($request),
            'result' => $result,
            'search' => $criteria->search,
            'status' => $criteria->status,
            'contentType' => $criteria->contentType,
            'includeDeleted' => $criteria->includeDeleted,
            'statuses' => (array) $this->module()->config('statuses', []),
            'contentTypes' => (array) $this->module()->config('content_types', []),
            'flash' => $request->session()->pullFlash('content.status'),
        ]);
    }

    public function show(Request $request): Response
    {
        $content = $this->factory->service()->detailById(ContentRequest::from($request)->contentId(), true);

        return $this->render('admin/show', 'Content Detail', [
            'auth' => $this->auth($request),
            'content' => $this->factory->service()->detailResource($content),
            'statistics' => $this->factory->service()->statistics($content),
            'reviewStatuses' => (array) $this->module()->config('review_statuses', []),
            'flash' => $request->session()->pullFlash('content.status'),
        ]);
    }

    public function review(Request $request): Response
    {
        $contentRequest = ContentRequest::from($request);
        $validation = $this->factory->validator()->validateReview($contentRequest->reviewData());
        if ($validation['errors'] !== []) {
            $request->session()->flash('content.status', ['type' => 'danger', 'message' => 'Content review data is invalid.']);

            return Response::redirect('/content/admin/' . $contentRequest->contentId());
        }

        $this->factory->service()->review($contentRequest->contentId(), $contentRequest->reviewData());
        $request->session()->flash('content.status', ['type' => 'success', 'message' => 'Content review updated successfully.']);

        return Response::redirect('/content/admin/' . $contentRequest->contentId());
    }
}
