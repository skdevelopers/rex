@extends('layouts.vertical', ['title' => 'Role', 'sub_title' => 'Roles'])

@section('content')
<div class="max-w-7xl mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6">Roles</h1>
    <a href="{{ route('roles.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-6 inline-block">Create Role</a>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white shadow-md rounded-lg">
            <thead>
                <tr>
                    <th class="w-1/3 px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider">Name</th>
                    <th class="w-1/3 px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider">Permissions</th>
                    <th class="w-1/3 px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($roles as $role)
                <tr>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $role->name }}</td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                        @if($role->permissions->isNotEmpty())
                            <ul class="list-disc pl-5">
                                @foreach($role->permissions as $permission)
                                    <li>{{ $permission->name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-gray-500">No permissions assigned</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                        <a href="{{ route('roles.edit', $role->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</a>
                        <a href="{{ route('roles.permissions.edit', $role->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">Assign Permission</a>
                        
                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this role?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
