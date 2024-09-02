<?php 
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class CheckPermissions
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        $route = $request->route();
        $actionName = $route->getActionName();
        
        list($controller, $method) = explode('@', $actionName);
        $controller = class_basename($controller); // Extract the controller name
        
        // Define a map of CRUD operations to their respective permissions
        $permissionMap = [
            'index' => 'view',
            'show' => 'view',
            'create' => 'create',
            'store' => 'create',
            'edit' => 'edit',
            'update' => 'edit',
            'destroy' => 'delete',
        ];

        // Convert the controller name to a resource name
        // Assuming the controller name is in the format 'CategoryController' or 'ProductController'
        $resource = strtolower(str_replace('Controller', '', $controller));
        
        // Check if the method exists in the permission map
        if (isset($permissionMap[$method])) {
            $permission = $permissionMap[$method] . ' ' . $resource;
            
            if (!$user->can($permission)) {
                //throw UnauthorizedException::forPermissions([$permission]);
                return response()->view('errors.403', ['message' => 'You do not have permission to perform this action.'], 403);
            }
        }

        return $next($request);
    }
}
