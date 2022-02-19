<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\RoleRequest;
use App\Interfaces\RoleInterface;
use App\Models\Role;

class RoleController extends Controller
{
    protected $roleInterface;

    public function __construct(RoleInterface $roleInterface)
    {
        $this->roleInterface = $roleInterface;
    }

    public function index(RoleRequest $request)
    {
        return $this->roleInterface->getAllRoles($request);
    }

    public function store(RoleRequest $request)
    {
        return $this->roleInterface->storeRole($request);
    }

    public function show(RoleRequest $request, Role $role)
    {
        return $this->roleInterface->getRoleById($request, $role);
    }

    public function update(RoleRequest $request, Role $role)
    {
        return $this->roleInterface->storeRole($request, $role);
    }

    public function destroy(RoleRequest $request, Role $role)
    {
        return $this->roleInterface->deleteRole($request, $role);
    }
}
