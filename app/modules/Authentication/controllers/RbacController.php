<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Controllers;

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Modules\Authentication\Responses\AuthResponse;

final class RbacController extends AbstractAuthenticationController
{
    public function users(Request $request): Response
    {
        return $this->render('admin/users', 'Manage Users', [
            'users' => $this->factory->userRepository()->listWithRoles(),
            'flash' => $request->session()->pullFlash('auth.status'),
        ]);
    }

    public function editRoles(Request $request): Response
    {
        $userId = (int) $request->route('id', 0);
        $user = $this->factory->userRepository()->findById($userId);

        return $this->render('admin/user-roles', 'Assign Roles', [
            'user' => $user,
            'roles' => $this->factory->roleRepository()->getAllRoles(),
            'assignedRoles' => $this->factory->roleRepository()->getRoleSlugsForUser($userId),
            'errors' => [],
            'flash' => $request->session()->pullFlash('auth.status'),
        ]);
    }

    public function updateRoles(Request $request): Response
    {
        $userId = (int) $request->route('id', 0);
        $validation = $this->factory->validator()->validateRoleAssignment($request->all());
        $this->factory->roleRepository()->syncUserRoles($userId, (array) $validation['data']['roles']);
        $request->session()->flash('auth.status', ['type' => 'success', 'message' => 'Role user berhasil diperbarui.']);

        return AuthResponse::redirect('/auth/admin/users/' . $userId . '/roles');
    }

    public function roleMatrix(Request $request): Response
    {
        return $this->render('admin/role-matrix', 'Role Matrix', [
            'matrix' => $this->factory->roleRepository()->getRolePermissionMatrix(),
            'flash' => $request->session()->pullFlash('auth.status'),
        ]);
    }
}
