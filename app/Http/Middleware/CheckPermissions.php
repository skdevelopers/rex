<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Support\Str;

class CheckPermissions
{
    public function handle($request, Closure $next)
    {
        // Get the current route action
        $action = $request->route()->getAction();

        // Get the controller and method name
        $controllerAction = class_basename($action['controller']);
        list($controller, $method) = explode('@', $controllerAction);

        // Derive the model name from the controller name (assuming convention-based names)
        $model = Str::replaceLast('Controller', '', $controller);

        // Determine the operation based on the method name
        $operation = $this->getOperationFromMethod($method);

        // Formulate the permission name
        $permissionName = "{$model}_{$operation}";
       // dd(Auth::user()->roles);
        // Check if the user has the permission
        if (!Auth::user()->hasRole('admin')) {
            if (!Auth::user()->can($permissionName)) {
                return response()->view('errors.403', ['message' => 'You do not have permission to perform this action.'], 403);
            }
        }

        return $next($request);
    }

    protected function getOperationFromMethod($method)
    {
        // Map method names to operations
        $map = [
            'index' => 'view',
            //'view' => 'view',
            'create' => 'create',
            'store' => 'create',
            'edit' => 'edit',
            'update' => 'edit',
            'destroy' => 'delete',
            'show' => 'view',
        ];

        return $map[$method] ?? $method;
    }
}
