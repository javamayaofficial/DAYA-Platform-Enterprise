<?php

declare(strict_types=1);

namespace App\Modules\Creator\Controllers;

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Modules\Creator\Dto\CreatorSearchCriteria;
use App\Modules\Creator\Models\Creator;
use App\Modules\Creator\Requests\CreatorRequest;
use App\Modules\Creator\Responses\CreatorResponse;
use RuntimeException;

final class CreatorController extends AbstractCreatorController
{
    public function dashboard(Request $request): Response
    {
        $creatorRequest = CreatorRequest::from($request);
        $creator = $this->factory->service()->detailByUserId($creatorRequest->userId(), true);

        return $this->render('dashboard/index', 'Creator Dashboard', [
            'auth' => $this->auth($request),
            'creator' => $creator instanceof Creator ? $this->factory->service()->detailResource($creator) : null,
            'statistics' => $creator instanceof Creator ? $this->factory->service()->statistics($creator) : null,
            'flash' => $request->session()->pullFlash('creator.status'),
        ]);
    }

    public function showCreate(Request $request): Response
    {
        $creatorRequest = CreatorRequest::from($request);
        $existingCreator = $this->factory->service()->detailByUserId($creatorRequest->userId(), true);

        if (!$this->factory->policy()->canCreate($this->auth($request), $existingCreator)) {
            $request->session()->flash('creator.status', ['type' => 'warning', 'message' => 'Creator profile already exists for this account.']);

            return CreatorResponse::redirect('/creator');
        }

        return $this->render('creator/create', 'Create Creator', [
            'errors' => [],
            'old' => [
                'category' => (string) $this->module()->config('default_category', 'General'),
                'creator_type' => (string) $this->module()->config('default_creator_type', 'individual'),
            ],
            'categories' => (array) $this->module()->config('categories', []),
            'creatorTypes' => (array) $this->module()->config('creator_types', []),
            'flash' => $request->session()->pullFlash('creator.status'),
        ]);
    }

    public function store(Request $request): Response
    {
        $creatorRequest = CreatorRequest::from($request);
        $existingCreator = $this->factory->service()->detailByUserId($creatorRequest->userId(), true);

        if (!$this->factory->policy()->canCreate($this->auth($request), $existingCreator)) {
            return Response::html(render_layout('Conflict', '<div class="alert alert-warning">Creator profile already exists.</div>'), 409);
        }

        $profile = $creatorRequest->profileData();
        $profile['categories'] = $creatorRequest->categories();
        $profile['skills'] = $creatorRequest->skills();
        $application = $creatorRequest->applicationData();
        $settings = $creatorRequest->settingsData();
        $validation = $this->factory->validator()->validateRegistration($profile, $application);
        $collectionValidation = $this->factory->validator()->validateCollections($profile['categories'], $profile['skills']);
        if ($collectionValidation['errors'] !== []) {
            $validation['errors'] = array_merge($validation['errors'], $collectionValidation['errors']);
        }
        if ($validation['errors'] !== []) {
            return $this->render('creator/create', 'Create Creator', [
                'errors' => $validation['errors'],
                'old' => array_merge($profile, $application, $settings),
                'categories' => (array) $this->module()->config('categories', []),
                'creatorTypes' => (array) $this->module()->config('creator_types', []),
                'flash' => null,
            ], 422);
        }

        try {
            $this->factory->service()->register($creatorRequest->userId(), $profile, $application, $settings);
        } catch (RuntimeException $exception) {
            return $this->render('creator/create', 'Create Creator', [
                'errors' => ['handle' => $exception->getMessage()],
                'old' => array_merge($profile, $application, $settings),
                'categories' => (array) $this->module()->config('categories', []),
                'creatorTypes' => (array) $this->module()->config('creator_types', []),
                'flash' => null,
            ], 422);
        }

        $request->session()->flash('creator.status', ['type' => 'success', 'message' => 'Creator application submitted successfully.']);

        return CreatorResponse::redirect('/creator');
    }

    public function showProfile(Request $request): Response
    {
        $creatorRequest = CreatorRequest::from($request);
        $creator = $this->factory->service()->detailByUserId($creatorRequest->userId(), true);
        if (!$creator instanceof Creator) {
            $request->session()->flash('creator.status', ['type' => 'warning', 'message' => 'Create your creator profile first.']);

            return CreatorResponse::redirect('/creator/register');
        }

        return $this->render('creator/profile', 'Creator Profile', [
            'creator' => $this->factory->service()->detailResource($creator),
            'statistics' => $this->factory->service()->statistics($creator),
            'socialPlatforms' => (array) $this->module()->config('social_platforms', []),
            'portfolioTypes' => (array) $this->module()->config('portfolio_types', []),
            'flash' => $request->session()->pullFlash('creator.status'),
        ]);
    }

    public function showEdit(Request $request): Response
    {
        $creatorRequest = CreatorRequest::from($request);
        $creator = $this->factory->service()->detailByUserId($creatorRequest->userId(), true);
        if (!$creator instanceof Creator) {
            return CreatorResponse::redirect('/creator/register');
        }

        return $this->render('creator/edit', 'Edit Creator', [
            'creator' => $this->factory->service()->detailResource($creator),
            'categories' => (array) $this->module()->config('categories', []),
            'creatorTypes' => (array) $this->module()->config('creator_types', []),
            'errors' => [],
            'flash' => $request->session()->pullFlash('creator.status'),
        ]);
    }

    public function update(Request $request): Response
    {
        $creatorRequest = CreatorRequest::from($request);
        $creator = $this->factory->service()->detailByUserId($creatorRequest->userId(), true);
        if (!$creator instanceof Creator || !$this->factory->policy()->canManageOwn($this->auth($request), $creator)) {
            return Response::html(render_layout('Forbidden', '<div class="alert alert-danger">Access denied.</div>'), 403);
        }

        $profile = $creatorRequest->profileData();
        $profile['categories'] = $creatorRequest->categories();
        $profile['skills'] = $creatorRequest->skills();
        $validationErrors = $this->factory->validator()->validateProfile($profile);
        $collectionValidation = $this->factory->validator()->validateCollections($profile['categories'], $profile['skills']);
        if ($collectionValidation['errors'] !== []) {
            $validationErrors = array_merge($validationErrors, $collectionValidation['errors']);
        }
        if ($validationErrors !== []) {
            return $this->render('creator/edit', 'Edit Creator', [
                'creator' => array_merge($this->factory->service()->detailResource($creator), $profile),
                'categories' => (array) $this->module()->config('categories', []),
                'creatorTypes' => (array) $this->module()->config('creator_types', []),
                'errors' => $validationErrors,
                'flash' => null,
            ], 422);
        }

        try {
            $this->factory->service()->updateProfile($creator->id, $profile);
        } catch (RuntimeException $exception) {
            return $this->render('creator/edit', 'Edit Creator', [
                'creator' => array_merge($this->factory->service()->detailResource($creator), $profile),
                'categories' => (array) $this->module()->config('categories', []),
                'creatorTypes' => (array) $this->module()->config('creator_types', []),
                'errors' => ['handle' => $exception->getMessage()],
                'flash' => null,
            ], 422);
        }

        $request->session()->flash('creator.status', ['type' => 'success', 'message' => 'Creator profile updated successfully.']);

        return CreatorResponse::redirect('/creator/profile');
    }

    public function updateSettings(Request $request): Response
    {
        $creatorRequest = CreatorRequest::from($request);
        $creator = $this->factory->service()->detailByUserId($creatorRequest->userId(), true);
        if (!$creator instanceof Creator || !$this->factory->policy()->canManageOwn($this->auth($request), $creator)) {
            return Response::html(render_layout('Forbidden', '<div class="alert alert-danger">Access denied.</div>'), 403);
        }

        $settings = $creatorRequest->settingsData();
        $validation = $this->factory->validator()->validateSettings($settings);
        if ($validation['errors'] !== []) {
            $request->session()->flash('creator.status', ['type' => 'danger', 'message' => 'Creator settings validation failed.']);

            return CreatorResponse::redirect('/creator/profile');
        }

        $this->factory->service()->updateSettings($creator->id, $settings);
        $request->session()->flash('creator.status', ['type' => 'success', 'message' => 'Creator settings updated successfully.']);

        return CreatorResponse::redirect('/creator/profile');
    }

    public function addSocialLink(Request $request): Response
    {
        $creatorRequest = CreatorRequest::from($request);
        $creator = $this->factory->service()->detailByUserId($creatorRequest->userId(), true);
        if (!$creator instanceof Creator || !$this->factory->policy()->canManageOwn($this->auth($request), $creator)) {
            return Response::html(render_layout('Forbidden', '<div class="alert alert-danger">Access denied.</div>'), 403);
        }

        $validation = $this->factory->validator()->validateSocialLink($creatorRequest->socialLinkData());
        if ($validation['errors'] !== []) {
            $request->session()->flash('creator.status', ['type' => 'danger', 'message' => 'Social link data is invalid.']);

            return CreatorResponse::redirect('/creator/profile');
        }

        $this->factory->service()->addSocialLink($creator->id, $creatorRequest->socialLinkData());
        $request->session()->flash('creator.status', ['type' => 'success', 'message' => 'Social link added successfully.']);

        return CreatorResponse::redirect('/creator/profile');
    }

    public function deleteSocialLink(Request $request): Response
    {
        $creatorRequest = CreatorRequest::from($request);
        $creator = $this->factory->service()->detailByUserId($creatorRequest->userId(), true);
        if (!$creator instanceof Creator || !$this->factory->policy()->canManageOwn($this->auth($request), $creator)) {
            return Response::html(render_layout('Forbidden', '<div class="alert alert-danger">Access denied.</div>'), 403);
        }

        $this->factory->service()->deleteSocialLink($creator->id, $creatorRequest->itemId());
        $request->session()->flash('creator.status', ['type' => 'success', 'message' => 'Social link deleted successfully.']);

        return CreatorResponse::redirect('/creator/profile');
    }

    public function addPortfolioItem(Request $request): Response
    {
        $creatorRequest = CreatorRequest::from($request);
        $creator = $this->factory->service()->detailByUserId($creatorRequest->userId(), true);
        if (!$creator instanceof Creator || !$this->factory->policy()->canManageOwn($this->auth($request), $creator)) {
            return Response::html(render_layout('Forbidden', '<div class="alert alert-danger">Access denied.</div>'), 403);
        }

        $validation = $this->factory->validator()->validatePortfolio($creatorRequest->portfolioData());
        if ($validation['errors'] !== []) {
            $request->session()->flash('creator.status', ['type' => 'danger', 'message' => 'Portfolio data is invalid.']);

            return CreatorResponse::redirect('/creator/profile');
        }

        $this->factory->service()->addPortfolioItem($creator->id, $creatorRequest->portfolioData());
        $request->session()->flash('creator.status', ['type' => 'success', 'message' => 'Portfolio item added successfully.']);

        return CreatorResponse::redirect('/creator/profile');
    }

    public function deletePortfolioItem(Request $request): Response
    {
        $creatorRequest = CreatorRequest::from($request);
        $creator = $this->factory->service()->detailByUserId($creatorRequest->userId(), true);
        if (!$creator instanceof Creator || !$this->factory->policy()->canManageOwn($this->auth($request), $creator)) {
            return Response::html(render_layout('Forbidden', '<div class="alert alert-danger">Access denied.</div>'), 403);
        }

        $this->factory->service()->deletePortfolioItem($creator->id, $creatorRequest->itemId());
        $request->session()->flash('creator.status', ['type' => 'success', 'message' => 'Portfolio item deleted successfully.']);

        return CreatorResponse::redirect('/creator/profile');
    }

    public function addAchievement(Request $request): Response
    {
        $creatorRequest = CreatorRequest::from($request);
        $creator = $this->factory->service()->detailByUserId($creatorRequest->userId(), true);
        if (!$creator instanceof Creator || !$this->factory->policy()->canManageOwn($this->auth($request), $creator)) {
            return Response::html(render_layout('Forbidden', '<div class="alert alert-danger">Access denied.</div>'), 403);
        }

        $validation = $this->factory->validator()->validateAchievement($creatorRequest->achievementData());
        if ($validation['errors'] !== []) {
            $request->session()->flash('creator.status', ['type' => 'danger', 'message' => 'Achievement data is invalid.']);

            return CreatorResponse::redirect('/creator/profile');
        }

        $this->factory->service()->addAchievement($creator->id, $creatorRequest->achievementData());
        $request->session()->flash('creator.status', ['type' => 'success', 'message' => 'Achievement added successfully.']);

        return CreatorResponse::redirect('/creator/profile');
    }

    public function deleteAchievement(Request $request): Response
    {
        $creatorRequest = CreatorRequest::from($request);
        $creator = $this->factory->service()->detailByUserId($creatorRequest->userId(), true);
        if (!$creator instanceof Creator || !$this->factory->policy()->canManageOwn($this->auth($request), $creator)) {
            return Response::html(render_layout('Forbidden', '<div class="alert alert-danger">Access denied.</div>'), 403);
        }

        $this->factory->service()->deleteAchievement($creator->id, $creatorRequest->itemId());
        $request->session()->flash('creator.status', ['type' => 'success', 'message' => 'Achievement deleted successfully.']);

        return CreatorResponse::redirect('/creator/profile');
    }

    public function destroy(Request $request): Response
    {
        $creatorRequest = CreatorRequest::from($request);
        $creator = $this->factory->service()->detailByUserId($creatorRequest->userId(), true);
        if (!$creator instanceof Creator || !$this->factory->policy()->canDelete($this->auth($request), $creator)) {
            return Response::html(render_layout('Forbidden', '<div class="alert alert-danger">Access denied.</div>'), 403);
        }

        $this->factory->service()->delete($creator->id);
        $request->session()->flash('creator.status', ['type' => 'success', 'message' => 'Creator profile deleted successfully.']);

        return CreatorResponse::redirect('/creator');
    }

    public function publicList(Request $request): Response
    {
        $criteria = CreatorSearchCriteria::fromRequest(CreatorRequest::from($request), true);
        $result = $this->factory->service()->paginate($criteria);

        return $this->render('public/index', 'Creators', [
            'result' => $result,
            'search' => $criteria->search,
            'category' => $criteria->category,
            'categories' => (array) $this->module()->config('categories', []),
            'flash' => $request->session()->pullFlash('creator.status'),
        ]);
    }

    public function publicShow(Request $request): Response
    {
        try {
            $creator = $this->factory->service()->detailByHandle((string) $request->route('handle', ''));
        } catch (RuntimeException) {
            return Response::html(render_layout('Not Found', '<div class="alert alert-warning">Creator page not found.</div>'), 404);
        }

        $creatorData = $this->factory->service()->detailResource($creator);

        return $this->render('public/show', ($creatorData['seo_title'] ?? '') !== '' ? (string) $creatorData['seo_title'] : ((string) $creatorData['display_name'] . ' | Creator'), [
            'creator' => $creatorData,
            'statistics' => $this->factory->service()->statistics($creator),
            'flash' => null,
        ]);
    }
}
