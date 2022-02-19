<?php


namespace App\Traits;


use Illuminate\Support\Str;

trait ApiRequestAuthorization
{
    public function authorizeRequest($modelClass, $routeVar = null)
    {
        if(!$routeVar)
        {
            $routeVar = Str::lower(class_basename($modelClass));
        }

        $modelInstance = request()->route($routeVar);
        switch ($this->method())
        {
            case 'GET':
                if($modelInstance)
                {
                    return request()->user()->can('view', $modelInstance);
                } else {
                    return request()->user()->can('viewAny', $modelClass);
                }
                break;
            case 'POST':
                return request()->user()->can('create', $modelClass);
                break;
            case 'PATCH':
                if($modelInstance)
                {
                    return request()->user()->can('update', $modelInstance);
                } else {
                    return false;
                }
                break;
            case 'DELETE':
                if($modelInstance)
                {
                    return request()->user()->can('delete', $modelInstance);
                } else {
                    return false;
                }
                break;
            default:
                return false;
        }
    }

    public function authorizeRequestWithNestedResource($modelClass, $parentModel, $routeVar = null)
    {
        if(!$routeVar)
        {
            $routeVar = Str::lower(class_basename($modelClass));
        }

        $modelInstance = request()->route($routeVar);
        switch ($this->method())
        {
            case 'GET':
                if($modelInstance)
                {
                    return request()->user()->can('view', [$modelInstance, $parentModel]);
                } else {
                    return request()->user()->can('viewAny', [$modelClass, $parentModel]);
                }
                break;
            case 'POST':
                return request()->user()->can('create', [$modelClass, $parentModel]);
                break;
            case 'PATCH':
                if($modelInstance)
                {
                    return request()->user()->can('update', [$modelInstance, $parentModel]);
                } else {
                    return false;
                }
                break;
            case 'DELETE':
                if($modelInstance)
                {
                    return request()->user()->can('delete', [$modelInstance, $parentModel]);
                } else {
                    return false;
                }
                break;
            default:
                return false;
        }
    }
}
