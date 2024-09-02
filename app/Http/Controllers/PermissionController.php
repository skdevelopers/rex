<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return view('permissions.index', compact('permissions'));
    }



    public function create()
    {
        // Get all model files from the app/Models directory
        $modelPath = app_path('Models');
        $models = [];

        foreach (File::allFiles($modelPath) as $file) {
            $models[] = Str::replaceLast('.php', '', $file->getFilename());
        }

        $operations = ['create', 'edit', 'delete', 'view']; // CRUD operations

        return view('permissions.create', compact('models', 'operations'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'model' => 'required',
            'operations' => 'required|array|min:1',
        ]);

        $table = $request->input('model');
        $operations = $request->input('operations');

        foreach ($operations as $operation) {
            $name = "{$table}_{$operation}";
            if (!Permission::where('name', $name)->exists()) {
                Permission::create(['name' => $name]);
            }
        }

        return redirect()->route('permissions.index')->with('success', 'Permissions created successfully.');
    }


    public function edit($id)
    {
        $permission = Permission::findOrFail($id);

        // Get all model files from the app/Models directory
        $modelPath = app_path('Models');
        $models = [];

        foreach (File::allFiles($modelPath) as $file) {
            $models[] = Str::replaceLast('.php', '', $file->getFilename());
        }

        $operations = ['create', 'edit', 'delete', 'view']; // CRUD operations

        return view('permissions.edit', compact('permission', 'models', 'operations'));
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'model' => 'required',
            'operations' => 'required|array|min:1',
        ]);

        $permission = Permission::findOrFail($id);

        // Generate the new permission name
        $table = $request->input('model');
        $operations = $request->input('operations');
        $newName = "{$table}_" . implode('_', $operations);

        // Check if another permission with the new name already exists
        $existingPermission = Permission::where('name', $newName)->first();
        if ($existingPermission && $existingPermission->id !== $id) {
            return redirect()->back()->withErrors(['name' => 'Permission with this name already exists.']);
        }

        // Update the permission name
        $permission->name = $newName;
        $permission->save();

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }


    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully.');
    }
}
