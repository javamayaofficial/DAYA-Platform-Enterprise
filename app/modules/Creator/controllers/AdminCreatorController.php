<?php

declare(strict_types=1);

namespace App\Modules\Creator\Controllers;

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Modules\Creator\Dto\CreatorSearchCriteria;
use App\Modules\Creator\Models\Creator;
use App\Modules\Creator\Requests\CreatorRequest;

final class AdminCreatorController extends AbstractCreatorController
{
    public function index(Request $request): Response
    {
        $criteria = CreatorSearchCriteria::fromRequest(CreatorRequest::from($request));
        $result = $this->factory->service()->paginate($criteria);

        return $this->render('admin/index', 'Creator Directory', [
            'auth' => $this->auth($request),
            'result' => $result,
            'search' => $criteria->search,
            'status' => $criteria->status,
            'category' => $criteria->category,
            'includeDeleted' => $criteria->includeDeleted,
            'statuses' => (array) $this->module()->config('statuses', []),
            'categories' => (array) $this->module()->config('categories', []),
            'flash' => $request->session()->pullFlash('creator.status'),
        ]);
    }

    public function show(Request $request): Response
    {
        $creator = $this->factory->service()->detailById((int) $request->route('id', 0), true);

        return $this->render('admin/show', 'Creator Detail', [
            'auth' => $this->auth($request),
            'creator' => $this->factory->service()->detailResource($creator),
            'statistics' => $this->factory->service()->statistics($creator),
            'statuses' => (array) $this->module()->config('statuses', []),
            'creatorLevels' => (array) $this->module()->config('creator_levels', []),
            'verificationStatuses' => (array) $this->module()->config('verification_statuses', []),
            'badgeCatalog' => (array) $this->module()->config('badge_catalog', []),
            'flash' => $request->session()->pullFlash('creator.status'),
        ]);
    }

    public function review(Request $request): Response
    {
        $creatorRequest = CreatorRequest::from($request);
        $creator = $this->factory->service()->detailById($creatorRequest->creatorId(), true);

        if (!$creator instanceof Creator || !$this->factory->policy()->canReview($this->auth($request))) {
            return Response::html(render_layout('Forbidden', '<div class="alert alert-danger">Access denied.</div>'), 403);
        }

        $reviewData = $creatorRequest->reviewData();
        $badgeCatalog = (array) $this->module()->config('badge_catalog', []);
        $selectedBadges = array_values(array_intersect((array) $reviewData['badges'], array_keys($badgeCatalog)));
        $reviewData['badges'] = array_intersect_key($badgeCatalog, array_flip($selectedBadges));
        $validation = $this->factory->validator()->validateReview($reviewData);
        if ($validation['errors'] !== []) {
            $request->session()->flash('creator.status', ['type' => 'danger', 'message' => 'Creator review data is invalid.']);

            return Response::redirect('/creator/admin/' . $creator->id);
        }

        $this->factory->service()->review($creator->id, $reviewData);
        $request->session()->flash('creator.status', ['type' => 'success', 'message' => 'Creator review status updated successfully.']);

        return Response::redirect('/creator/admin/' . $creator->id);
    }
}
