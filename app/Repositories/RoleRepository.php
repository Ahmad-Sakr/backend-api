<?php


namespace App\Repositories;


use App\Helpers\ResponseMessages;
use App\Http\Requests\v1\RoleRequest;
use App\Http\Resources\v1\RoleResource;
use App\Interfaces\RoleInterface;
use App\Models\Role;
use App\Traits\ApiExceptionHandler;
use App\Traits\ApiResponder;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class RoleRepository implements RoleInterface
{
    use ApiResponder, ApiExceptionHandler;

    public function getAllRoles(RoleRequest $request)
    {
        return $this->success(RoleResource::collection(Role::all()),"List of Roles", Response::HTTP_OK);
    }

    public function getRoleById(RoleRequest $request, Role $role)
    {
        return $this->success(new RoleResource($role));
    }

    public function storeRole(RoleRequest $request, Role $role = null)
    {
        DB::beginTransaction();
        try {
            $create = ($role === null);
            $data = $request->validated();

            if($create) {
                $role = Role::query()->create($data);
            }
            else {
                $role->update($data);
            }

            DB::commit();
            return $this->success(new RoleResource($role),
                $create ? ResponseMessages::CREATED : ResponseMessages::UPDATED,
                $create ? Response::HTTP_CREATED : Response::HTTP_OK);
        } catch(Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    public function deleteRole(RoleRequest $request, Role $role)
    {
        DB::beginTransaction();
        try {
            $role->delete();

            DB::commit();
            return $this->success([],ResponseMessages::DELETED, Response::HTTP_OK);
        } catch(Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }
}
