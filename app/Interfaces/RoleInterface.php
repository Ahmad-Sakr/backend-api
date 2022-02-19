<?php


namespace App\Interfaces;


use App\Http\Requests\v1\RoleRequest;
use App\Models\Role;

interface RoleInterface
{
    public function getAllRoles(RoleRequest $request);

    public function getRoleById(RoleRequest $request, Role $role);

    public function storeRole(RoleRequest $request, Role $role = null);

    public function deleteRole(RoleRequest $request, Role $role);
}
