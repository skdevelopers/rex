@extends('layouts.vertical', ['title' => 'Assign Permission to Role', 'sub_title' => 'Assign Permission'])

@section('content')
<div class="max-w-7xl mx-auto py-8">
    <h2 class="text-3xl font-bold mb-6">Assign Permissions to {{ $role->name }}</h2>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    <form action="{{ route('roles.permissions.update', $role->id) }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf

        <div class="mb-4">
            <label for="permissions" class="block text-gray-700 text-sm font-bold mb-2">Permissions</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($permissions as $permission)
                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="form-checkbox h-5 w-5 text-blue-600" {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700">{{ $permission->name }}</span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Update Permissions
            </button>
            <a href="{{ route('roles.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                Back to Roles
            </a>
        </div>
    </form>
</div>
@endsection
